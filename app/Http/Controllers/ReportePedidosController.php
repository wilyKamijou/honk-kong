<?php

namespace App\Http\Controllers;

use App\Models\pedidos;
use App\Models\User;
use App\Models\productos;
use App\Models\detalle_pedidos;
use App\Models\envios;
use App\Models\metodos_pagos;
use App\Models\categorias;
use App\Models\resenas;
use App\Models\aplicaciones_promociones;
use App\Models\promociones;
use App\Models\aplicaciones_descuentos;
use App\Models\descuentos;
use App\Models\aplicaciones_envios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportePedidosController extends Controller
{

public function adminPage()
{
    // Obtener pedidos recientes con sus detalles y productos
$pedidosRecientes = pedidos::with(['detalle_pedido.productos']) // Usar el nombre exacto de la relación
    ->latest()
    ->take(5)
    ->get()
    ->map(function($pedido) {
        $primerDetalle = $pedido->detalle_pedido->first(); // Cambiado a detalle_pedido
        return [
            'pedido' => $pedido,
            'producto' => $primerDetalle ? $primerDetalle->productos : null,
            'detalle' => $primerDetalle
        ];
    });

    return view('adminPage', [
        'totalPedidosHoy' => pedidos::whereDate('created_at', today())->count(),
        'ventasHoy' => pedidos::whereDate('created_at', today())->sum('total'),
        'clientesNuevos' => User::whereDate('created_at', today())->count(),
        'totalProductos' => productos::count(),
        'pedidosRecientes' => $pedidosRecientes,
        'ventasMensuales' => $this->getVentasMensuales(),
        'ventasMensualesAnterior' => $this->getVentasMensualesAnterior()
    ]);
}
protected function getVentasMensuales()
{
    $ventas = [];
    for ($i = 1; $i <= 12; $i++) {
        $ventas[] = pedidos::whereYear('created_at', now()->year)
            ->whereMonth('created_at', $i)
            ->sum('total');
    }
    return $ventas;
}

protected function getVentasMensualesAnterior()
{
    $ventas = [];
    for ($i = 1; $i <= 12; $i++) {
        $ventas[] = pedidos::whereYear('created_at', now()->subYear()->year)
            ->whereMonth('created_at', $i)
            ->sum('total');
    }
    return $ventas;
}
public function mostrarReportes(Request $request)
{
    $fechaInicio = $request->input('fecha_inicio', Carbon::now()->subMonth()->format('Y-m-d'));
    $fechaFin = $request->input('fecha_fin', Carbon::now()->format('Y-m-d'));
    
    // Verificar si es la carga inicial sin filtros
    $esCargaInicial = !$request->has('fecha_inicio') && !$request->has('fecha_fin');
    
    // Obtener datos reales
    $pedidos = Pedidos::with(['detalle_pedido.productos'])
        ->whereBetween('created_at', [
            Carbon::parse($fechaInicio)->startOfDay(),
            Carbon::parse($fechaFin)->endOfDay()
        ])
        ->get();
    
    // Procesar datos para los gráficos
    $pedidosPorFecha = $this->generarReportePedidosPorFecha($pedidos);
    $productosMasVendidos = $this->obtenerProductosMasVendidos($fechaInicio, $fechaFin);
    
    // Si es carga inicial y no hay datos, mostrar datos de muestra
    if ($esCargaInicial && ($pedidosPorFecha->isEmpty() || $productosMasVendidos->isEmpty())) {
        return $this->mostrarDatosDeMuestra($fechaInicio, $fechaFin);
    }
    
    $datosReporte = [
        'fechaInicio' => $fechaInicio,
        'fechaFin' => $fechaFin,
        'totalGeneral' => $pedidos->sum('total') ?? 0,
        'pedidosPorFecha' => $pedidosPorFecha,
        'productosMasVendidos' => $productosMasVendidos,
        'tableData' => $this->prepararDatosTabla($pedidosPorFecha),
        'esDemo' => false // Indicador de datos reales
    ];
    
    return view('reportes.pedidos', compact('datosReporte'));
}

protected function obtenerProductosMasVendidos($fechaInicio, $fechaFin)
{
    return detalle_pedidos::with('productos')
        ->select(
            'id_producto',
            DB::raw('SUM(cantidad) as total_vendido')
        )
        ->whereHas('pedidos', function($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('created_at', [
                Carbon::parse($fechaInicio)->startOfDay(),
                Carbon::parse($fechaFin)->endOfDay()
            ]);
        })
        ->groupBy('id_producto')
        ->orderByDesc('total_vendido')
        ->limit(10)
        ->get()
        ->map(function($item) {
            return [
                'nombre' => optional($item->productos)->nombre ?? 'Producto Desconocido',
                'total_vendido' => $item->total_vendido ?? 0
            ];
        });
}

protected function generarReportePedidosPorFecha($pedidos)
{
    return $pedidos->groupBy(function($pedido) {
        return Carbon::parse($pedido->fecha)->format('Y-m-d');
    })->map(function($grupo) {
        return [
            'fecha' => $grupo->first()->fecha,
            'total_pedidos' => $grupo->count(),
            'total_ventas' => $grupo->sum('total')
        ];
    })->values();
}

protected function mostrarDatosDeMuestra($fechaInicio, $fechaFin)
{
    // Generar 30 días de datos de muestra para el gráfico de ventas
    $pedidosPorFecha = collect();
    $dias = Carbon::parse($fechaInicio)->diffInDays($fechaFin);
    
    for ($i = 0; $i <= $dias; $i++) {
        $fecha = Carbon::parse($fechaInicio)->addDays($i)->format('Y-m-d');
        $pedidosPorFecha->push([
            'fecha' => $fecha,
            'total_pedidos' => rand(1, 10),
            'total_ventas' => rand(500, 5000)
        ]);
    }
    
    // Datos de muestra para productos más vendidos
    $productosMasVendidos = collect([
        ['nombre' => 'Producto A', 'total_vendido' => 45],
        ['nombre' => 'Producto B', 'total_vendido' => 32],
        ['nombre' => 'Producto C', 'total_vendido' => 28],
        ['nombre' => 'Producto D', 'total_vendido' => 15],
        ['nombre' => 'Producto E', 'total_vendido' => 10]
    ]);
    
    $datosReporte = [
        'fechaInicio' => $fechaInicio,
        'fechaFin' => $fechaFin,
        'totalGeneral' => $pedidosPorFecha->sum('total_ventas'),
        'pedidosPorFecha' => $pedidosPorFecha,
        'productosMasVendidos' => $productosMasVendidos,
        'tableData' => $this->prepararDatosTabla($pedidosPorFecha),
        'esDemo' => true // Indicador de datos de muestra
    ];
    
    return view('admin.reportes', compact('datosReporte'));
}


protected function prepararDatosTabla($pedidosPorFecha)
{
    return $pedidosPorFecha->map(function($item) {
        return [
            'fecha' => $item['fecha'],
            'pedidos' => $item['total_pedidos'],
            'ventas' => '$' . number_format($item['total_ventas'], 2)
        ];
    });
}
    
    protected function generarReporteVentas($pedidos)
    {
        return [
            'por_dia_semana' => $pedidos->groupBy(function($pedido) {
                return Carbon::parse($pedido->fecha)->dayName;
            })->map->sum('total'),
            
            'por_mes' => $pedidos->groupBy(function($pedido) {
                return Carbon::parse($pedido->fecha)->format('F Y');
            })->map->sum('total'),
            
            'por_metodo_pago' => $pedidos->groupBy('id_pago')->map->sum('total')
        ];
    }


// nuevos
public function ventasPorCategoria(Request $request)
{
    $fechaInicio = $request->input('fecha_inicio', now()->subMonth());
    $fechaFin = $request->input('fecha_fin', now());

    // Datos para la tabla (categorías)
    $ventas = categorias::select(
            'categorias.id_categoria',
            'categorias.nombre',
            DB::raw('SUM(detalle_pedidos.cantidad) as total_unidades'),
            DB::raw('SUM(detalle_pedidos.cantidad * detalle_pedidos.precio) as total_ventas')
        )
        ->join('productos', 'categorias.id_categoria', '=', 'productos.id_categoria')
        ->join('detalle_pedidos', 'productos.id_producto', '=', 'detalle_pedidos.id_producto')
        ->join('pedidos', 'detalle_pedidos.id_pedido', '=', 'pedidos.id_pedido')
        ->whereBetween('pedidos.fecha', [$fechaInicio, $fechaFin])
        ->groupBy('categorias.id_categoria', 'categorias.nombre')
        ->orderByDesc('total_ventas')
        ->get();

    // Datos para el gráfico (top productos por categoría)
    $productosMasVendidos = productos::select(
            'productos.id_producto',
            'productos.nombre as producto_nombre',
            'categorias.nombre as categoria_nombre',
            DB::raw('SUM(detalle_pedidos.cantidad) as total_vendido')
        )
        ->join('categorias', 'productos.id_categoria', '=', 'categorias.id_categoria')
        ->join('detalle_pedidos', 'productos.id_producto', '=', 'detalle_pedidos.id_producto')
        ->join('pedidos', 'detalle_pedidos.id_pedido', '=', 'pedidos.id_pedido')
        ->whereBetween('pedidos.fecha', [$fechaInicio, $fechaFin])
        ->groupBy('productos.id_producto', 'productos.nombre', 'categorias.nombre')
        ->orderByDesc('total_vendido')
        ->limit(10) // Top 10 productos
        ->get();

    return view('reportes.ventas_categoria', [
        'ventas' => $ventas,
        'productosMasVendidos' => $productosMasVendidos,
        'fechaInicio' => $fechaInicio,
        'fechaFin' => $fechaFin
    ]);
}
public function ventasPorFecha(Request $request)
{
    // Filtros con valores por defecto (últimos 30 días)
    $fechaInicio = $request->input('fecha_inicio', now()->subDays(30)->format('Y-m-d'));
    $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));
    $agrupacion = $request->input('agrupacion', 'dia'); // día, semana o mes

    // Consulta base
    $query = pedidos::select(
            DB::raw($this->getDateExpression($agrupacion, 'fecha') . ' as fecha_agrupada'),
            DB::raw('COUNT(*) as total_pedidos'),
            DB::raw('SUM(total) as total_ventas'),
            DB::raw('SUM(
                (SELECT SUM(cantidad) 
                 FROM detalle_pedidos 
                 WHERE detalle_pedidos.id_pedido = pedidos.id_pedido)
            ) as total_productos')
        )
        ->whereBetween('fecha', [$fechaInicio, $fechaFin])
        ->groupBy('fecha_agrupada')
        ->orderBy('fecha_agrupada');

    $ventas = $query->get();

    return view('reportes.ventas_fecha', [
        'ventas' => $ventas,
        'fechaInicio' => $fechaInicio,
        'fechaFin' => $fechaFin,
        'agrupacion' => $agrupacion
    ]);
}

// Helper para expresiones SQL de fecha según agrupación
private function getDateExpression($agrupacion, $column)
{
    switch ($agrupacion) {
        case 'dia':
            return "DATE_FORMAT($column, '%Y-%m-%d')";
        case 'semana':
            return "DATE_FORMAT($column, '%x-%v')"; // Año-numero semana
        case 'mes':
            return "DATE_FORMAT($column, '%Y-%m')";
        default:
            return "DATE($column)";
    }
}

public function analisisTemporadas(Request $request)
{
    $year = $request->input('year', date('Y'));
    $categoriaId = $request->input('categoria_id');

    $datos = DB::table('pedidos')
        ->selectRaw('
            categorias.nombre as categoria,
            MONTH(pedidos.fecha) as mes,
            SUM(detalle_pedidos.cantidad) as unidades,
            SUM(detalle_pedidos.cantidad * detalle_pedidos.precio) as venta_total
        ')
        ->join('detalle_pedidos', 'pedidos.id_pedido', '=', 'detalle_pedidos.id_pedido')
        ->join('productos', 'detalle_pedidos.id_producto', '=', 'productos.id_producto')
        ->join('categorias', 'productos.id_categoria', '=', 'categorias.id_categoria')
        ->whereYear('pedidos.fecha', $year)
        ->when($categoriaId, function($query, $categoriaId) {
            return $query->where('categorias.id_categoria', $categoriaId);
        })
        ->groupBy('categorias.nombre', 'mes')
        ->orderBy('mes')
        ->get();

    $categorias = categorias::all();
    $heatmapData = $datos->groupBy('categoria');

    return view('reportes.temporadas', [
        'heatmapData' => $heatmapData,
        'categorias' => $categorias,
        'year' => $year,
        'categoriaId' => $categoriaId
    ]);
}





public function customerLifetimeValue(Request $request)
{
    $cohortes = User::selectRaw('
        DATE_FORMAT(users.created_at, "%Y-%m") as cohorte,
        COUNT(users.id) as total_clientes,
        SUM(pedidos.total) as ventas_totales,
        COUNT(pedidos.id_pedido) as total_pedidos,
        AVG(pedidos.total) as ticket_promedio
    ')
    ->leftJoin('pedidos', function($join) {
        $join->on('users.id', '=', 'pedidos.user_id')
             ->where('pedidos.estado', 'entregado');
    })
    ->groupBy('cohorte')
    ->orderBy('cohorte')
    ->get();

    // Calcular retención
    $retention = [];
    foreach ($cohortes as $cohort) {
        $monthStart = Carbon::createFromFormat('Y-m', $cohort->cohorte);
        $data = [
            'cohorte' => $monthStart->format('M Y'),
            'clientes' => $cohort->total_clientes
        ];

        for ($i = 1; $i <= 6; $i++) {
            $month = $monthStart->copy()->addMonths($i);
            $pedidos = Pedidos::where('user_id', 'users.id')
                ->whereBetween('fecha', [$monthStart, $month])
                ->count();

            $data['m_' . $i] = $pedidos > 0 ? '✔' : '✘';
        }

        $retention[] = $data;
    }

    return view('reportes.clv', [
        'cohortes' => $cohortes,
        'retention' => $retention
    ]);
}

}