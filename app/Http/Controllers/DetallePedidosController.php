<?php

namespace App\Http\Controllers;

use App\Models\detalle_pedidos;
use App\Models\pedidos;
use App\Models\productos;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class DetallePedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function generarDetallesAutomaticos()
{
    // Obtener todos los productos disponibles
    $productos = productos::all();
    
    if ($productos->isEmpty()) {
        return redirect('/dtpedidos')->with('error', 'No hay productos disponibles para generar detalles');
    }
    
    // Obtener pedidos sin detalles o que podrían tener más (usando el nombre correcto de la relación)
    $pedidos = pedidos::whereDoesntHave('detalle_pedido') // Nombre exacto de tu relación
                ->orWhereHas('detalle_pedido', function($query) {
                    $query->groupBy('id_pedido')
                          ->havingRaw('COUNT(*) < 3'); // Pedidos con menos de 3 productos
                })->get();
    
    if ($pedidos->isEmpty()) {
        return redirect('/dtpedidos')->with('info', 'Todos los pedidos ya tienen suficientes detalles');
    }
    
    $detallesGenerados = 0;
    
    foreach ($pedidos as $pedido) {
        // Determinar cuántos productos añadir (1-3)
        $cantidadProductos = rand(1, 3);
        
        // Obtener productos aleatorios que no estén ya en este pedido
        $productosExistentes = $pedido->detalle_pedido->pluck('id_producto')->toArray(); // Usar el nombre correcto
        $productosDisponibles = $productos->whereNotIn('id_producto', $productosExistentes);
        
        if ($productosDisponibles->isNotEmpty()) {
            // Tomar hasta $cantidadProductos productos disponibles
            $productosParaAnadir = $productosDisponibles->random(min($cantidadProductos, $productosDisponibles->count()));
            
foreach ($productosParaAnadir as $producto) {
    // Generar cantidad aleatoria una sola vez
    $cantidad = rand(1, 5);
    
    // Crear detalle con cantidad aleatoria (1-5) y el precio actual del producto
    detalle_pedidos::create([
        'id_pedido' => $pedido->id_pedido,
        'id_producto' => $producto->id_producto,
        'cantidad' => $cantidad,
        'precio' => $producto->precio
    ]);
    
    $detallesGenerados++;
    
    // Actualizar total del pedido usando la cantidad generada
    $pedido->total += $producto->precio * $cantidad;
    $pedido->save();
}
        }
    }
    
    return redirect('/dtpedidos')->with('success', "Se generaron $detallesGenerados nuevos detalles de pedido");
}


    public function index()
    {
        $clientes=User::all();
        $detalles = DB::table('detalle_pedidos')
            ->join('productos', 'detalle_pedidos.id_producto', '=', 'productos.id_producto')
            ->join('pedidos', 'detalle_pedidos.id_pedido', '=', 'pedidos.id_pedido')
            ->join('users', 'pedidos.user_id', '=', 'users.id')
            ->select(
            'users.name as nombre_cliente',
            'detalle_pedidos.id_producto',
            'detalle_pedidos.id_pedido',
            'productos.nombre as nombre_producto',
            'detalle_pedidos.cantidad',
            'detalle_pedidos.precio',
            DB::raw('detalle_pedidos.cantidad * detalle_pedidos.precio as subtotal')
        )->get();

      
        return view('dtpedido.index',compact('detalles'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos=productos::all();
        $pedidos=pedidos::all();
        return view('dtpedido.create',compact('productos','pedidos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        
        try {
            $ex = detalle_pedidos::where('id_pedido', $request->id_pedido)
            ->where('id_producto', $request->id_producto)->first();
            $pedido=pedidos::findorfail($request->id_pedido);
            if ($ex) {
                // Solo actualizamos el mismo registro
                detalle_pedidos::where('id_pedido', $request->id_pedido)
                        ->where('id_producto', $request->id_producto)
                        ->update([
                            'cantidad' => $ex->cantidad + $request->cantidad
                        ]);
              
                $pedido->total += $request->precio * $request->cantidad;
                $pedido->save();
            }else
            {
                detalle_pedidos::create($request->all());
                $pedido->total += $request->precio * $request->cantidad;
                $pedido->save();
            }
                /*
                $detalle=detalle_pedidos::create($request->all());
                $pedido=pedidos::findorfail($request->id_pedido);
                $pedido->total = $pedido->total + $request->precio*$request->cantidad;
                $pedido->save(); // ¡No olvides guardar!
                
                */  
                return redirect('/dtpedidos'); 
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error de duplicado
                return redirect()->back()->with('error', 'Este producto ya fue registrado en pedidos.');

            }
    
            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Intenta de nuevo.');
        }
        
       
    }

    /**
     * Display the specified resource.
     */
    public function show(detalle_pedidos $detalle_pedidos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id1,$id2)
    {   
        
        $detalle=detalle_pedidos::where('id_pedido',$id1)->where('id_producto',$id2)->first();
        $productos=productos::all();
        $pedidos=pedidos::all();
        return view('dtpedido.edit',compact('detalle','productos','pedidos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id1, $id2 )
    {
           try {
                // Buscar el pedido y el detalle original
                $pedido = pedidos::findOrFail($id1);
                $detalleOriginal = detalle_pedidos::where('id_pedido', $id1)
                                                  ->where('id_producto', $id2)
                                                  ->first();
        
                // Restar el subtotal anterior del total del pedido
                $pedido->total -= $detalleOriginal->precio * $detalleOriginal->cantidad;
                $pedido->save();
                // Verificamos si el usuario mantuvo la misma combinación de IDs
                if ($request->id_pedido == $id1 && $request->id_producto == $id2) {
                    // Solo actualizamos el mismo registro
                    $detalleOriginal->cantidad = $request->cantidad;
                    $detalleOriginal->precio = $request->precio;
                    $detalleOriginal->save();
                    $pedido->total += $request->precio * $request->cantidad;
                    $pedido->save();
                } else {
                    // Ver si el nuevo detalle ya existe
                    $detalleExistente = detalle_pedidos::where('id_pedido', $request->id_pedido)
                                                       ->where('id_producto', $request->id_producto)
                                                       ->first();
                                             
                    if ($detalleExistente) {
                        // Sumar cantidad al existente
                        detalle_pedidos::where('id_pedido', $request->id_pedido)
                        ->where('id_producto', $request->id_producto)
                        ->update([
                            'cantidad' => $detalleExistente->cantidad + $request->cantidad
                        ]);
    
             
                    } else {
                        // Crear nuevo detalle
                        $nuevo=detalle_pedidos::create($request->all());
                        $nuevo->save();
                    }
                    //cuadra total de pedido
                    $pedido = pedidos::findOrFail($request->id_pedido);
                    $pedido->total += $request->precio * $request->cantidad;
                    $pedido->save();

                      // Eliminar el detalle original
                      detalle_pedidos::where('id_pedido', $id1)
                      ->where('id_producto', $id2)
                      ->delete();
                
                }        
                return redirect('/dtpedidos')->with('success', 'Detalle actualizado correctamente.');
               
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
            }
           
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id1,$id2)
    {
        $pedido=pedidos::findorfail($id1);
        $detalle=detalle_pedidos::where('id_pedido',$id1)->where('id_producto',$id2)->first();
        $pedido->total=$pedido->total-$detalle->precio*$detalle->cantidad;
        $pedido->save();
        $detalle=detalle_pedidos::where('id_pedido',$id1)->where('id_producto',$id2)->delete();
        return redirect('/dtpedidos');
    }
}
