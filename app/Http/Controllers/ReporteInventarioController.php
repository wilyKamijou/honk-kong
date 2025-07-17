<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categorias;
use App\Models\productos;
use App\Models\pedidos;
use App\Models\detalle_pedidos;
use App\Models\envios;
use App\Models\user;
use App\Models\metodos_pagos;
use App\Models\promociones;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ReporteInventarioController extends Controller
{

        public function panel()
    {
        // Datos estadísticos básicos
        $totalClientes = user::where('role', 'cliente')->count();
        $totalProductos = Productos::count();
        $totalPedidos = Pedidos::count();
        $totalCategorias = Categorias::count();
        $totalGanado = Pedidos::sum('total');

        // Datos para el gráfico de ventas
        $years = Pedidos::selectRaw('YEAR(fecha) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        $currentYear = Carbon::now()->year;
        $meses = [];
        $datasets = [];

        // Prepara datos para cada año encontrado
        foreach ($years as $year) {
            $ventasPorMes = Pedidos::selectRaw('MONTH(fecha) as month, SUM(total) as total')
                ->whereYear('fecha', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $data = array_fill(0, 12, 0); // Inicializa todos los meses con 0

            foreach ($ventasPorMes as $venta) {
                $data[$venta->month - 1] = $venta->total;
            }

            $datasets[] = [
                'label' => (string)$year,
                'data' => $data,
                'backgroundColor' => $this->getRandomColor(),
                'borderColor' => 'rgba(75, 192, 192, 1)',
                'borderWidth' => 1
            ];
        }

        // Nombres de los meses en español
        $meses = [
            'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
            'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'
        ];

        return view('panel.dashboard', compact(
            'totalClientes',
            'totalProductos',
            'totalPedidos',
            'totalCategorias',
            'totalGanado',
            'years',
            'meses',
            'datasets'
        ));
    }

    /**
     * Genera un color aleatorio para el gráfico
     */
    private function getRandomColor()
    {
        return 'rgba(' . rand(0, 255) . ', ' . rand(0, 255) . ', ' . rand(0, 255) . ', 0.2)';
    }
    public function index(Request $request)
    {
        $categorias = categorias::all();

        $tabla = $request->input('tabla');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $categoria_id = $request->input('categoria_id');

        return view('panel.dashboard', compact('categorias', 'tabla', 'fechaInicio', 'fechaFin', 'categoria_id'));
    }

public function generarPDF(Request $request)
{
    $request->validate([
        'tabla' => 'required|in:pedidos,detalle_pedido,producto,user,promociones',
        'fecha_inicio' => 'nullable|date',
        'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        'categoria_id' => 'nullable|exists:categorias,id_categoria'
    ]);

    $tabla = $request->input('tabla');
    $categoria_id = $request->input('categoria_id');
    $fecha_inicio = $request->input('fecha_inicio');
    $fecha_fin = $request->input('fecha_fin');

    // Obtener los datos filtrados
    $datos = $this->obtenerDatosFiltrados($tabla, $categoria_id, $fecha_inicio, $fecha_fin);

    // Obtener nombre de categoría si existe
    $nombreCategoria = 'Todas';
    if ($categoria_id) {
        $categoria = Categorias::find($categoria_id);
        $nombreCategoria = $categoria ? $categoria->nombre : 'Todas';
    }

    // Cargar la vista adecuada según el tipo de reporte
    $viewName = 'panel.pdf.'.$tabla;
    
    $pdf = PDF::loadView($viewName, [
        'datos' => $datos,
        'tabla' => $tabla,
        'nombreCategoria' => $nombreCategoria,
        'fecha_inicio' => $fecha_inicio ? Carbon::parse($fecha_inicio)->format('d/m/Y') : 'No especificada',
        'fecha_fin' => $fecha_fin ? Carbon::parse($fecha_fin)->format('d/m/Y') : 'No especificada'
    ]);

    return $pdf->download('reporte_'.$tabla.'_'.now()->format('Y-m-d').'.pdf');
}

        public function newindex(Request $request)
    {
        $categorias = Categorias::all();
        
        return view('panel.reportes', [
            'categorias' => $categorias,
            'tabla' => $request->input('tabla'),
            'categoria_id' => $request->input('categoria_id'),
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fecha_fin' => $request->input('fecha_fin')
        ]);
    }

    /**
     * Genera el PDF con los datos filtrados
     */

    /**
     * Obtiene los datos filtrados según los parámetros
     */
    private function obtenerDatosFiltrados($tabla, $categoria_id, $fecha_inicio, $fecha_fin)
    {
        switch ($tabla) {
            case 'pedidos':
                return $this->getPedidos($categoria_id, $fecha_inicio, $fecha_fin);
                
            case 'detalle_pedido':
                return $this->getDetallePedidos($categoria_id, $fecha_inicio, $fecha_fin);
                
            case 'producto':
                return $this->getProductos($categoria_id, $fecha_inicio, $fecha_fin);
                
            case 'user':
                return $this->getUsuarios($fecha_inicio, $fecha_fin);
                
            case 'promociones':
                return $this->getPromociones($fecha_inicio, $fecha_fin);
                
            default:
                return collect();
        }
    }


    private function getPedidos($categoria_id, $fecha_inicio, $fecha_fin)
    {
        return pedidos::with(['users', 'detalle_pedido.productos'])
            ->when($fecha_inicio, fn($q) => $q->whereDate('fecha', '>=', $fecha_inicio))
            ->when($fecha_fin, fn($q) => $q->whereDate('fecha', '<=', $fecha_fin))
            ->when($categoria_id, fn($q) => $q->whereHas('detalle_pedido.productos', 
                fn($q2) => $q2->where('id_categoria', $categoria_id)
            ))
            ->get();
    }

    private function getDetallePedidos($categoria_id, $fecha_inicio, $fecha_fin)
    {
        return detalle_pedidos::with(['productos', 'pedidos'])
            ->when($fecha_inicio, fn($q) => $q->whereHas('pedidos', 
                fn($q2) => $q2->whereDate('fecha', '>=', $fecha_inicio)
            ))
            ->when($fecha_fin, fn($q) => $q->whereHas('pedidos', 
                fn($q2) => $q2->whereDate('fecha', '<=', $fecha_fin)
            ))
            ->when($categoria_id, fn($q) => $q->whereHas('productos', 
                fn($q2) => $q2->where('id_categoria', $categoria_id)
            ))
            ->get();
    }
private function getProductos($categoria_id, $fecha_inicio, $fecha_fin)
{
    return Productos::with(['categorias'])
        ->when($categoria_id, fn($q) => $q->where('id_categoria', $categoria_id))
        ->when($fecha_inicio, fn($q) => $q->whereDate('created_at', '>=', $fecha_inicio))
        ->when($fecha_fin, fn($q) => $q->whereDate('created_at', '<=', $fecha_fin))
        ->get();
}

private function getUsuarios($fecha_inicio, $fecha_fin)
{
    return user::withCount(['pedidos'])
        ->where('role', 'cliente')
        ->when($fecha_inicio, fn($q) => $q->whereDate('created_at', '>=', $fecha_inicio))
        ->when($fecha_fin, fn($q) => $q->whereDate('created_at', '<=', $fecha_fin))
        ->get();
}

private function getPromociones($fecha_inicio, $fecha_fin)
{
    return promociones::withCount(['productos'])
        ->when($fecha_inicio, fn($q) => $q->whereDate('fecha_inicio', '>=', $fecha_inicio))
        ->when($fecha_fin, fn($q) => $q->whereDate('fecha_fin', '<=', $fecha_fin))
        ->get();
}

}
