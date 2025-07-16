<?php

namespace App\Http\Controllers;

use App\Models\Resena; // Nombre correcto del modelo
use App\Models\User;   // User en PascalCase
use App\Models\productos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ResenasController extends Controller
{
public function createByUser($productoId)
{
    // Primero obtenemos el producto
    $producto = productos::findOrFail($productoId);

    // Verifica si el usuario ya ha dejado una reseña para este producto
    $existingReview = Resena::where('user_id', auth()->id())
                          ->where('producto_id', $producto->id_producto)
                          ->first();

    if ($existingReview) {
        return redirect()->back()->with('error', 'Ya has dejado una reseña para este producto.');
    }

    return view('reseña.createByUser', compact('producto'));
}
   public function storeByUser(Request $request)
{
    $request->validate([
        'comentario' => 'required|string|max:500',
        'calificacion' => 'required|integer|between:1,5',
        'producto_id' => 'required|exists:productos,id_producto',
    ]);

    // Verifica si el usuario ya ha dejado una reseña para este producto
    $existingReview = Resena::where('user_id', auth()->id())
                          ->where('producto_id', $request->producto_id)
                          ->first();

    if ($existingReview) {
        return redirect()->back()->with('error', 'Ya has dejado una reseña para este producto.');
    }

    Resena::create([
        'comentario' => $request->comentario,
        'calificacion' => $request->calificacion,
        'fecha' => now(),
        'user_id' => auth()->id(),
        'producto_id' => $request->producto_id,
    ]);

    return redirect()->route('/inicio')->with('success', '¡Gracias por tu reseña!');
}

    public function index()
    {
        $reseñas=Resena::all();
        return view('reseña.index',compact('reseñas'));
    }

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $users = User::all();
    $productos = productos::all(); // Asegúrate de importar el modelo Producto
    return view('reseña.create', compact('users', 'productos'));
}
    

    /**
     * Store a newly created resource in storage.
*/
    public function store(Request $request)
    {
        $reseña= Resena::create($request->all());
        return redirect('/reseñas');
    }

    /**
     * Display the specified resource.
     */
    public function show(resenas $resenas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $users=user::all();
        $reseña=Resena::findorfail($id);
        return view('reseña.edit',compact('users','reseña'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $reseña=Resena::findorfail($id);
        $reseña->update($request->all());
        return redirect('/reseñas');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $reseña=Resena::findorfail($id);
        $reseña->delete();
        return redirect('/reseñas');
    }
}
