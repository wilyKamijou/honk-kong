<?php

namespace App\Http\Controllers;

use App\Models\descuentos;
use Illuminate\Http\Request;

class DescuentosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $descuentos=descuentos::all();
        return view('descuento.index',compact('descuentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('descuento.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $descuento=descuentos::create($request->all());
        return redirect('/descuentos');
    }

    /**
     * Display the specified resource.
     */
    public function show(descuentos $descuentos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $descuento=descuentos::findorfail($id);
        return view('descuento.edit',compact('descuento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $descuento=descuentos::findorfail($id);
        $descuento->update($request->all());
        return redirect('/descuentos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $descuento=descuentos::findorfail($id);
        $descuento->delete();
        return redirect('/descuentos');
    }
}
