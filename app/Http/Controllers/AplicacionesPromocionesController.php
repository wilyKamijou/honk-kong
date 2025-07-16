<?php

namespace App\Http\Controllers;

use App\Models\aplicaciones_promociones;
use App\Models\productos;
use App\Models\promociones;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Carbon\Carbon;
Use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str;

class AplicacionesPromocionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
 * Asigna promociones automáticamente a productos (máximo a la mitad de los productos)
 */

    public function asignarPromocionesAutomaticas()
    {
        try {
            // Obtener el total de productos y calcular la mitad
            $totalProductos = productos::count();
            $cantidadPromociones = ceil($totalProductos / 2);
            
            // Obtener productos sin promociones activas
            $productosSinPromocion = productos::whereDoesntHave('aplicaciones_promociones', function($query) {
                $query->whereHas('promociones', function($q) {
                    $q->where('fecha_fin', '>=', Carbon::now());
                });
            })->inRandomOrder()->take($cantidadPromociones)->get();
            
            if ($productosSinPromocion->isEmpty()) {
                return redirect()->back()
                    ->with('warning', 'No hay productos disponibles sin promoción activa.');
            }
            
            $promocionesAsignadas = 0;
            
            foreach ($productosSinPromocion as $producto) {
                // Crear nueva promoción
                $promocion = promociones::create([
                    'nombre' => 'Promo ' . Str::random(5),
                    'valor' => rand(10, 40), // 10% a 40% de descuento
                    'fecha_inicio' => Carbon::now(),
                    'fecha_fin' => Carbon::now()->addDays(rand(14, 30)), // 2 a 4 semanas
                ]);
                
                // Asignar promoción al producto
                aplicaciones_promociones::create([
                    'id_producto' => $producto->id_producto,
                    'id_promocion' => $promocion->id_promocion
                ]);
                
                $promocionesAsignadas++;
            }
            
            return redirect()->back()
                ->with('success', "Se asignaron {$promocionesAsignadas} promociones a productos aleatorios.");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al asignar promociones: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $appromociones=aplicaciones_promociones::all();
        $productos=productos::all();
        $promociones=promociones::all();
        return view('appromocion.index',compact('appromociones','productos','promociones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            $productos=productos::all();
            $promociones=promociones::all();
            return view('appromocion.create',compact('productos','promociones'));
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $aplicaciones_promocione=aplicaciones_promociones::create($request->all());
            return redirect('/appromociones');
        }
        catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error de duplicado
                return redirect()->back()->with('error', 'Esta aplicacion de promocion ya existe.');

            }
    
            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Intenta de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(aplicaciones_promociones $aplicaciones_promociones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id1 , $id2)
    {
        $appromocion=aplicaciones_promociones::where('id_producto',$id1)->where('id_promocion',$id2)->first();
        $productos=productos::all();
        $promociones=promociones::all();
        return view('appromocion.edit',compact('appromocion','productos','promociones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id1 , $id2)
    {
        try{
            $appromocion=aplicaciones_promociones::create($request->all());
            return redirect('/appromociones');
        }
        catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error de duplicado
                return redirect()->back()->with('error', 'Esta aplicacion de promocion ya existe.');

            }
    
            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Intenta de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id1 , $id2)
    {
        $appromocion=aplicaciones_promociones::where('id_producto',$id1)->where('id_promocion',$id2)->delete();
        return redirect('/appromociones');
    }
}
