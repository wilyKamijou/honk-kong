<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use App\Models\detalle_pedidos;
use App\Models\envios;
use App\Models\metodos_pagos;
use App\Models\pedidos;
use Illuminate\Http\Request;
use App\Models\productos;

class CarritoController extends Controller
{
    public function agregar(request $request ,$id)
    {
        $productoDB = Productos::findorfail($id);
        if ($request->all()==[]) {
            if (!$productoDB) {
                return redirect()->back()->with('error', 'Producto no encontrado');
            }
        
            $producto = [
                'id' => $productoDB->id_producto,
                'nombre' => $productoDB->nombre,
                'precio' => $productoDB->precio,
                'cantidad' => 1,
            ];
        }else
        {
            $producto = [
                'id' => $request->id_producto,
                'nombre' => $request->nombre,
                'precio' => $request->precio,
                'cantidad' => 1,
            ];
        }
    
        $carrito = session()->get('carrito', []);
    
        if (isset($carrito[$producto['id']])) {
            $carrito[$producto['id']]['cantidad'] += 1;
        } else {
            $carrito[$producto['id']] = $producto;
        }
    
        session(['carrito' => $carrito]);
    
        return redirect('/');
    }

    public function ver()
    {
        $categorias=categorias::all();
        $carrito = session('carrito', []);
        $total = 0;
    
        foreach ($carrito as $producto) {
            $total += $producto['precio'] * $producto['cantidad'];
        }
     $carritoCantidad = count($carrito);
        return view('carrito.vercarrito', compact('carrito', 'total','categorias', 'carritoCantidad'));
    }

    public function eliminar()
    {
        session()->forget('carrito');
        return redirect()->back()->with('success', 'Carrito vaciado correctamente');
    }

    public function pago()
    {
        $categorias=categorias::all();
        return view('carrito.pago',compact('categorias'));
    }
public function procesarPago(Request $request)
{
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Debes iniciar sesión para completar el pago.');
    }
    
    $request->validate([
        'metodo_pago' => 'required|in:tarjeta,qr,efectivo',
        'direccion_envio' => 'required|string',
        'nombre_titular' => 'required_if:metodo_pago,tarjeta',
        'numero_tarjeta' => 'required_if:metodo_pago,tarjeta',
        'fecha_expiracion' => 'required_if:metodo_pago,tarjeta',
        'cvc' => 'required_if:metodo_pago,tarjeta'
    ]);
    
    $user = auth()->user();
    $carrito = session('carrito', []);
    
    if (empty($carrito)) {
        return redirect()->back()->with('error', 'El carrito está vacío.');
    }
    
    // Crear el método de pago si es necesario
    if ($request->metodo_pago === 'tarjeta') {
        $metodoPago = metodos_pagos::create([
            'tipo' => 'tarjeta',
            'nombre_titular' => $request->nombre_titular,
            'ultimos_digitos' => substr(preg_replace('/\D/', '', $request->numero_tarjeta), -4),
            'marca' => $request->marca ?? $this->detectarMarcaTarjeta($request->numero_tarjeta),
            'fecha_expiracion' => $request->fecha_expiracion,
            'user_id' => $user->id,
            'es_predeterminado' => true
        ]);
    } else {
        // Usar método existente o crear uno genérico
        $metodoPago = metodos_pagos::firstOrCreate([
            'tipo' => $request->metodo_pago,
            'user_id' => $user->id
        ], [
            'alias' => ucfirst($request->metodo_pago),
            'es_predeterminado' => false
        ]);
    }
    
    $total = collect($carrito)->sum(fn($item) => $item['precio'] * $item['cantidad']);

    // Crear el pedido
    $pedido = pedidos::create([
        'user_id' => $user->id,
        'fecha' => now(),
        'total' => $total,
        'estado' => $request->metodo_pago === 'efectivo' ? 'Pendiente' : 'Procesado',
        'direccion_envio' => $request->direccion_envio,
        'id_pago' => $metodoPago->id_pago,
        'indicaciones' => $request->indicaciones ?? null
    ]);
    
    // Crear detalles del pedido
    foreach ($carrito as $item) {
        detalle_pedidos::create([
            'id_pedido' => $pedido->id_pedido,
            'id_producto' => $item['id'],
            'cantidad' => $item['cantidad'],
            'precio' => $item['precio']
        ]);
    }
    
    // Crear envío
    envios::create([
        'direccion_envio' => $request->direccion_envio,
        'fecha_envio' => $request->metodo_pago === 'efectivo' ? null : now(),
        'fecha_estimada_llegada' => $request->metodo_pago === 'efectivo' ? null : now()->addDays(3),
        'metodo_envio' => 'Estándar',
        'estado_envio' => $request->metodo_pago === 'efectivo' ? 'Pendiente' : 'Procesado',
        'id_pedido' => $pedido->id_pedido
    ]);
    
    // Vaciar carrito
    session()->forget('carrito');
    
//    return redirect()->route('pedidos.show', $pedido->id_pedido)
//        ->with('success', '¡Pedido realizado con éxito!');
      return redirect()->route('pedido.detalles', $pedido->id_pedido)
    ->with('success', '¡Pedido realizado con éxito!');

}

public function mostrarDetallesPedido($id_pedido)
{
    // Obtener el pedido con sus relaciones
    $pedido = pedidos::with([
            'detalle_pedido.productos',  // Relación de detalles del pedido y sus productos
            'metodos_pagos',             // Método de pago
            'users'                      // Usuario que hizo el pedido
        ])
        ->where('id_pedido', $id_pedido)
        ->firstOrFail();

    // Verificar que el pedido pertenece al usuario autenticado
    if ($pedido->user_id != auth()->id()) {
        abort(403, 'No tienes permiso para ver este pedido');
    }
    // Obtener las categorías (igual que en el método ver())
    $categorias = categorias::all(); // Asegúrate de importar el modelo: use App\Models\categorias;

    return view('carrito.detalles', [
        'pedido' => $pedido,
        'detalles' => $pedido->detalle_pedido,
        'metodo_pago' => $pedido->metodos_pagos,
        'usuario' => $pedido->users,
        'categorias' => $categorias
    ]);
}

public function mostrarConfirmacion($id)
{

    $pedidos = pedidos::with(['detalles.producto', 'metodoPago'])
                ->where('user_id', auth()->id())
                ->findOrFail($id);
      
    return view('carrito.confirmacion', [
      'pedidos' => $pedidos,
        'detalles_pedidos' => $pedidos->detalle_pedidos, // Nombre correcto de la relación
        'metodos_pagos' => $pedidos->metodos_pagos, // Nombre correcto de la relación
        'direccion' => $pedidos->direccion_envio,
        'total' => $pedidos->total,
         'categorias' => $categorias    
    ]);
}

private function detectarMarcaTarjeta($numero)
{
    $numero = preg_replace('/\D/', '', $numero);
    
    if (preg_match('/^4/', $numero)) return 'Visa';
    if (preg_match('/^5[1-5]/', $numero)) return 'Mastercard';
    if (preg_match('/^3[47]/', $numero)) return 'American Express';
    
    return 'Otra';
}
public function actualizar(Request $request, $id)
{
    $carrito = session('carrito', []);

    if (isset($carrito[$id])) {
        if ($request->accion === 'sumar') {
            $carrito[$id]['cantidad']++;
        } elseif ($request->accion === 'restar' && $carrito[$id]['cantidad'] > 1) {
            $carrito[$id]['cantidad']--;
        }
        session(['carrito' => $carrito]);
    }

    return redirect()->back();
}
public function eliminarItem($id)
{
    $carrito = session('carrito', []);
    unset($carrito[$id]);
    session(['carrito' => $carrito]);

    return redirect()->back()->with('success', 'Producto eliminado del carrito');
}
}