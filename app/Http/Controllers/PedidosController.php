<?php

namespace App\Http\Controllers;

use App\Models\pedidos;
use App\Models\metodos_pagos;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use carbon\carbon;
class PedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      
    public function index()
    {
        $pagos=metodos_pagos::all();
        $pedidos=pedidos::all();
        $clientes=user::all();
        return view('pedido.index',compact('pagos','pedidos','clientes'));
    }

      public function generateFakePedidos()
    {
        // Direcciones disponibles
        $direcciones = [
            'Avenida Cañoto',
            'Avenida Noel Kempff',
            'Calle Jorge Wilstermann Camacho',
            'Calle Alejo Calatayud Espindola',
            'Avenida René Barrientos',
            'Avenida Florida',
            'Avenida Marcelo Quiroga Santa Cruz'
        ];

        // Estados posibles
        $estados = ['pendiente', 'en_proceso', 'enviado', 'entregado', 'cancelado'];

        // Obtener todos los métodos de pago con sus usuarios
        $metodosPago = metodos_pagos::with('users')->get();

        if ($metodosPago->isEmpty()) {
            return redirect('/pedidos')->with('error', 'No hay métodos de pago registrados. Crea algunos primero.');
        }

        // Generar 50 pedidos aleatorios
        for ($i = 0; $i < 50; $i++) {
            // Seleccionar un método de pago aleatorio
            $metodoPago = $metodosPago->random();
            
            // Crear el pedido
            Pedidos::create([
                'user_id' => $metodoPago->user_id,
                'fecha' => Carbon::now()->subDays(rand(0, 30)),
                'total' => rand(50, 500) + (rand(0, 99) / 100), // Total entre 50.00 y 500.99
                'estado' => $estados[array_rand($estados)],
                'direccion_envio' => $direcciones[array_rand($direcciones)],
                'id_pago' => $metodoPago->id_pago
            ]);
        }

        return redirect('/pedidos')->with('success', '50 pedidos aleatorios generados exitosamente.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $pagos=metodos_pagos::all();
        $clientes=user::all();
        return view('pedido.create',compact('pagos','clientes'));   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pedido=pedidos::create($request->all());
        return redirect('/pedidos');
    }

    /**
     * Display the specified resource.
     */
    public function show(pedidos $pedidos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pedido=pedidos::findorfail($id);
        $clientes=user::all();
        $pagos=metodos_pagos::all();
        return view('pedido.edit',compact('pedido','clientes','pagos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pedido=pedidos::findorfail($id);
        $pedido->update($request->all());
        return redirect('/pedidos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pedido=pedidos::findorfail($id);
        $pedido->delete();
        return redirect('/pedidos');
    }
}
