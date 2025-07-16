<?php

namespace App\Http\Controllers;

use App\Models\aplicaciones_descuentos;
use App\Models\descuentos;
use App\Models\pedidos;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class AplicacionesDescuentosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
 * Asigna descuentos automáticamente a pedidos que no los tengan
 */
public function asignarDescuentosAutomaticos()
{
        $maxDescuentosPorPedido = 1; // Cambia este número según necesites
    
    $descuentos = descuentos::all();
    
    if ($descuentos->isEmpty()) {
        return redirect('/apdescuentos')->with('error', 'No hay descuentos disponibles para asignar');
    }
    
    // Obtener pedidos con menos del máximo de descuentos permitidos
    $pedidos = pedidos::whereHas('aplicaciones_descuentos', function($query) use ($maxDescuentosPorPedido) {
        $query->groupBy('id_pedido')
              ->havingRaw('COUNT(*) < ?', [$maxDescuentosPorPedido]);
    }, '<', 1)->orWhereDoesntHave('aplicaciones_descuentos')->get();
    // Obtener todos los descuentos disponibles
    $descuentos = descuentos::all();
    
    if ($descuentos->isEmpty()) {
        return redirect('/apdescuentos')->with('error', 'No hay descuentos disponibles para asignar');
    }
    
    // Obtener pedidos sin descuentos aplicados
    $pedidos = pedidos::whereDoesntHave('aplicaciones_descuentos')->get();
    
    if ($pedidos->isEmpty()) {
        return redirect('/apdescuentos')->with('info', 'Todos los pedidos ya tienen descuentos aplicados');
    }
    
    $descuentosAsignados = 0;
    
    foreach ($pedidos as $pedido) {
        // Seleccionar un descuento aleatorio
        $descuento = $descuentos->random();
        
        try {
            // Aplicar el descuento al pedido
            aplicaciones_descuentos::create([
                'id_pedido' => $pedido->id_pedido,
                'id_descuento' => $descuento->id_descuento
            ]);
            
            $descuentosAsignados++;
            
        } catch (QueryException $e) {
            // Si hay un error (como duplicado), continuar con el siguiente pedido
            continue;
        }
    }
    
    return redirect('/apdescuentos')->with('success', "Se asignaron $descuentosAsignados descuentos a pedidos");
}
    public function index()
    {
        $apdes=DB::table('aplicaciones_descuentos as apdes')
            ->join('pedidos as p', 'apdes.id_pedido', '=', 'p.id_pedido')
            ->join('descuentos as d', 'apdes.id_descuento', '=', 'd.id_descuento')
            ->join('Users as u', 'u.id', '=', 'p.user_id')
            ->select(
                'apdes.id_descuento',
                'apdes.id_pedido',
                'd.descripcion',
                'd.porcentaje',
                'u.name'
            )->get();
    
        return view('apdescuento.index',compact('apdes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pedidos=DB::table('pedidos as p')
            ->join('Users as u', 'p.user_id', '=', 'u.id')
            ->select(
                'p.id_pedido',
                'u.name'
            )->get();
        $descuentos=descuentos::all();
        return view('apdescuento.create',compact('pedidos','descuentos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $existe=aplicaciones_descuentos::where('id_descuento', $request->id_descuento)->where('id_pedido', $request->id_pedido)->first();
        try{
            $apdes=aplicaciones_descuentos::create($request->all());
            return redirect('/apdescuentos');
        }
        catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error de duplicado
                return redirect()->back()->with('error', 'Esta aplicacion de descuento ya existe.');

            }
    
            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Intenta de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(aplicaciones_descuentos $aplicaciones_descuentos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id1, $id2)
    {
        $apde=aplicaciones_descuentos::where('id_descuento', $id1)->where('id_pedido', $id2)->first();
        $pedidos=DB::table('pedidos as p')
        ->join('Users as u', 'p.user_id', '=', 'u.id')
        ->select(
            'p.id_pedido',
            'u.name'
        )->get();
        $descuentos=descuentos::all();
        return view('apdescuento.edit',compact('apde','pedidos','descuentos'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id1, $id2)
    {
        try{
            $apdes=aplicaciones_descuentos::where('id_descuento', $id1)->where('id_pedido',$id2)->first();
            $apdes->update($request->all());
            return redirect('/apdescuentos');
        }
        catch (QueryException $e) {
            if ($e->getCode() == 23000) { // Código de error de duplicado
                return redirect()->back()->with('error', 'Esta aplicacion de descuento ya existe.');

            }
    
            return redirect()->back()->with('error', 'Ocurrió un error inesperado. Intenta de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id1, $id2)
    {
        $apdes=aplicaciones_descuentos::where('id_descuento', $id1)->where('id_pedido', $id2)->delete();
        return redirect('/apdescuentos');
    }
}
