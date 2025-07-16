<?php

namespace App\Http\Controllers;

use App\Models\promociones;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PromocionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promociones=promociones::all();
        return view('promocion.index',compact('promociones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('promocion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $promocion=promociones::create($request->all());
        return redirect('/promociones');
    }

    /**
     * Display the specified resource.
     */
    public function show(promociones $promociones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $promocion=promociones::findorfail($id);
        return view('promocion.edit',compact('promocion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            
            $promocion=promociones::findorfail($id);
            $promocion->update($request->all());
            return redirect('/promociones');

        } catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error de duplicado
                return redirect()->back()->with('error', 'Este producto ya fue registrado en pedidos.');

            }
    
            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Intenta de nuevo.');
        }
   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $promocion=promociones::findorfail($id);
        $promocion->delete();
        return redirect('/promociones');
    }
}
