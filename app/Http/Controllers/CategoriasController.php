<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $categorias=categorias::all();
        return view('/categoria.index',compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('/categoria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $categoria=categorias::create($request->all());
        return redirect('/categorias');
    }

    /**
     * Display the specified resource.
     */
    public function show(categorias $categorias)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categoria=categorias::findorfail($id);
        return view('/categoria.edit',compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categoria=categorias::findorfail($id);
        $categoria->update($request->all());
        return redirect('/categorias');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoria=categorias::findorfail($id);
        $categoria->delete();
        return redirect('/categorias');
    }
}
