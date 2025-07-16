<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\categorias; // Se mantiene si Categorias es el nombre de la clase
use App\Models\productos; // Cambiado a singular y PascalCase
use App\Models\AplicacionesPromociones; // Se mantiene si es el nombre de la clase y el archivo coincide
use App\Models\Resena; // ¡IMPORTANTE! Cambiado a singular y PascalCase
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class TiendaController extends Controller
{
    public function index()
    {
        $categorias = Categorias::all();
        $productos = productos::all(); // Usando el modelo Producto (singular)
        $clientes = User::all();
        $reseñas = Resena::with('user')->latest()->take(10)->get(); // Usando el modelo Resena (singular)
        
        $aplicaciones = DB::table('aplicaciones_promociones as ap')
        ->join('productos as p', 'ap.id_producto', '=', 'p.id_producto')
        ->join('promociones as pr', 'ap.id_promocion', '=', 'pr.id_promocion')
        ->select('p.id_producto', 'p.nombre', 'p.descripcion', 'p.precio', 'p.imagen_url', 'pr.nombre as promocion_nombre', 'pr.valor','pr.fecha_inicio','pr.fecha_fin')
        ->get();
        //dd($aplicaciones->pluck('aplicaciones'));
        return view('tienda.inicio', compact('categorias','productos','reseñas','clientes','aplicaciones'));
    }

    public function mostrar()
    {
        $categorias = Categorias::all();
        return view('tienda.quienes',compact('categorias'));
    }

    public function contacto()
    {
        $categorias = Categorias::all();
        return view('tienda.contactanos',compact('categorias'));
    }

    public function buscar($id)
    {
        $categorias = Categorias::all();
        $productos=Productos::where('id_categoria',$id)->get(); // Usando el modelo Producto (singular)
        return view('tienda.buscar',compact('productos','categorias'));
    }
    
    public function reseña(){
        $categorias = Categorias::all();
        $reseñas = Resena::all(); // Usando el modelo Resena (singular)
        return view('tienda.reseña', compact('categorias','reseñas'));
    }

    public function mostrarPerfil()
    {
        $categorias = Categorias::all();
        if (!auth()->check()) {
            return redirect()->route('login')->with('error','Debes iniciar sesion');
        } else {
            $usuario = auth()->user(); 
            return view('tienda.perfil', compact('usuario','categorias'));
        }
    }

    public function editarperfil($id)
    {
        $categorias = Categorias::all();
        $usuario=User::findorfail($id);
        return view('tienda.editperfil',compact('categorias','usuario'));
    }

    public function actualizarPerfil( Request $request, $id) // Corregido $REQUEST a $request por convención
    {
        $usuario=User::findorfail($id);
        $usuario->update($request->all()); // Corregido $REQUEST a $request
        return Redirect('/perfil');
    }
}