<?php

namespace App\Http\Controllers;

use App\Models\categorias;
use App\Models\productos;
use App\Models\promociones;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Productos::all();
        return view('producto.index')->with('productos',$productos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias=categorias::all();
        $promociones=promociones::all();
        return view('producto.create',compact('categorias','promociones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $producto = Productos::create($request->all());
        return redirect('/producto');
    }

    /**
     * Display the specified resource.
     */
    public function show(productos $productos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto=productos::findOrFail($id);
        $categorias=categorias::all();
        return view('producto.edit', compact('producto','categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $producto = Productos::findOrFail($id);
        $producto->update($request->all());
        return redirect('/producto');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Productos::findOrFail($id);
        $producto->delete();
        return redirect('/producto');
    }
}
