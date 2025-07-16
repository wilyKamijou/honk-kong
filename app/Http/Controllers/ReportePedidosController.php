<?php

namespace App\Http\Controllers;

use App\Models\pedidos;
use App\Models\User;
use App\Models\productos;
use App\Models\detalle_pedidos;
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
}