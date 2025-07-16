<?php

namespace App\Http\Controllers;

use App\Models\metodos_pagos;
use App\Models\User;
use App\Models\pedidos;
use Illuminate\Http\Request;

class MetodosPagosController extends Controller
{
    /**
     * Display a listing of the resource.
     */public function index()
{
    $user = auth()->user(); // Más eficiente que User::find(auth()->id())
    $metodos = metodos_pagos::where('user_id', auth()->id())->get();
    $pedidos = pedidos::where('user_id', auth()->id())->get();

    return view('pago.index', compact('user', 'metodos', 'pedidos'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = ['tarjeta' => 'Tarjeta', 'qr' => 'QR', 'efectivo' => 'Efectivo', 'transferencia' => 'Transferencia'];
        return view('pago.create', compact('tipos'));
    }
    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:tarjeta,qr,efectivo,transferencia',
            'alias' => 'nullable|string|max:50',
            'nombre_titular' => 'required_if:tipo,tarjeta',
            'numero_tarjeta' => 'required_if:tipo,tarjeta',
            'fecha_expiracion' => 'required_if:tipo,tarjeta',
            'codigo_qr' => 'required_if:tipo,qr'
        ]);

        $data = [
            'tipo' => $request->tipo,
            'alias' => $request->alias,
            'user_id' => auth()->id(),
            'es_predeterminado' => $request->has('es_predeterminado')
        ];

        if ($request->tipo === 'tarjeta') {
            $data['nombre_titular'] = $request->nombre_titular;
            $data['ultimos_digitos'] = substr(preg_replace('/\D/', '', $request->numero_tarjeta), -4);
            $data['marca'] = $this->detectarMarcaTarjeta($request->numero_tarjeta);
            $data['fecha_expiracion'] = $request->fecha_expiracion;
        } elseif ($request->tipo === 'qr') {
            $data['codigo_qr'] = $request->codigo_qr;
        }

        // Desmarcar otros métodos como predeterminados si este lo es
        if ($data['es_predeterminado']) {
            MetodosPagos::where('user_id', auth()->id())->update(['es_predeterminado' => false]);
        }

        metodos_pagos::create($data);

        return redirect()->route('metodos-pago.index')->with('success', 'Método de pago añadido');
    }

    private function detectarMarcaTarjeta($numero)
    {
        $numero = preg_replace('/\D/', '', $numero);
        
        if (preg_match('/^4/', $numero)) return 'Visa';
        if (preg_match('/^5[1-5]/', $numero)) return 'Mastercard';
        if (preg_match('/^3[47]/', $numero)) return 'American Express';
        
        return 'Otra';
    }

    public function setDefault($id)
    {
        $metodo = MetodosPagos::where('user_id', auth()->id())->findOrFail($id);
        
        MetodosPagos::where('user_id', auth()->id())->update(['es_predeterminado' => false]);
        $metodo->update(['es_predeterminado' => true]);
        
        return back()->with('success', 'Método de pago predeterminado actualizado');
    }

    public function destroy($id)
    {
        $metodo = MetodosPagos::where('user_id', auth()->id())->findOrFail($id);
        $metodo->delete();
        
        return back()->with('success', 'Método de pago eliminado');
    }

    /**
     * Display the specified resource.
     */
    public function show(metodos_pagos $metodos_pagos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pago=metodos_pagos::findorfail($id);
        $clientes=User::all();
        return view('pago.edit',compact('pago','clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pago=metodos_pagos::findorfail($id);
        $pago->update($request->all());
        return redirect('/pagos');
    }

    /**
     * Remove the specified resource from storage.
     */
}
