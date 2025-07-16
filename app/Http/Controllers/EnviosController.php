<?php

namespace App\Http\Controllers;

use App\Models\envios;
use App\Models\pedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use carbon\carbon;

class EnviosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function igualarEnvios()
{
    // Datos ficticios
    $estados = ['preparacion', 'en_transito', 'reparto', 'entregado', 'fallido'];
    $transportistas = ['DHL', 'FedEx', 'UPS', 'Correos', 'Seur'];
    
    // Usa el nombre correcto de la relación (envio, no envios)
$pedidosSinEnvio = pedidos::doesntHave('envios')->get(); 
    
    if ($pedidosSinEnvio->isEmpty()) {
        return redirect('/envios')->with('status', 'Todos los pedidos ya tienen envíos asociados');
    }
    
    foreach ($pedidosSinEnvio as $pedido) {
        $fechaEnvio = Carbon::now()->addDays(rand(1, 3));
        
        envios::create([
            'id_pedido' => $pedido->id_pedido,
            'direccion_envio' => $pedido->direccion ?? 'Dirección no especificada',
            'fecha_envio' => $fechaEnvio,
            'fecha_estimada_llegada' => $fechaEnvio->addDays(rand(2, 7)),
            'metodo_envio' => $transportistas[array_rand($transportistas)],
            'estado_envio' => $estados[array_rand($estados)],
        ]);
    }
    
    return redirect('/envios')->with('success', 'Se generaron '.$pedidosSinEnvio->count().' envíos nuevos');
}

    public function index()
    {
        $envios=envios::all();
        $pedidos=pedidos::all();
        return view('envio.index',compact('envios','pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pedidos=pedidos::all();
        return view('envio.create',compact('pedidos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $envio=envios::create($request->all());
        return redirect('/envios');
    }

    /**
     * Display the specified resource.
     */
    public function show(envios $envios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $envio=envios::findorfail($id);
        $pedidos=pedidos::all();
        return view('envio.edit',compact('envio','pedidos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $envio=envios::findorfail($id);
        $envio->update($request->all());
        return redirect('/envios');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $envio=envios::findorfail($id);
        $envio->delete();
        return redirect('/envios');
    }
}
