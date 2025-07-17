<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\PromocionesController;
use App\Http\Controllers\DescuentosController;
use App\Http\Controllers\ResenasController;
use App\Http\Controllers\MetodosPagosController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\EnviosController;
use App\Http\Controllers\AplicacionesPromocionesController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\AplicacionesDescuentosController;
use App\Http\Controllers\DetallePedidosController;
use App\Models\aplicaciones_descuentos;
use App\Http\Controllers\ReportePedidosController;



//rutas publicas
Route::get('/', [TiendaController::class, 'index'])->name('inicio');
Route::get('/quienes', [TiendaController::class, 'mostrar'])->name('quienes');
Route::get('/contactanos', [TiendaController::class, 'contacto'])->name('contactanos');
Route::get('/buscar/{id}', [TiendaController::class, 'buscar'])->name('buscar');
Route::get('/reseña', [TiendaController::class, 'reseña'])->name('reseña');
Route::get('/perfil', [TiendaController::class, 'mostrarPerfil'])->name('mostrarPerfil');
Route::get('/perfil/{id}/editar', [TiendaController::class, 'editarperfil'])->name('editperfil');
route::Put('/perfil/{id}/actualizar', [TiendaController::class, 'actualizarPerfil'])->name('actualizarPerfil');




Route::middleware(['auth'])->group(function () {

    //funciones del carrito publicas
    Route::get('/carrito/agregar/{id}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::get('/carrito/ver', [CarritoController::class, 'ver'])->name('carrito.vercarrito');
    Route::post('/carrito/eliminar', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::get('/carrito/pago', [CarritoController::class, 'pago'])->name('carrito.pago');
    Route::post('/carrito/procesar', [CarritoController::class, 'procesarPago'])->name('carrito.procesar');

// Nueva ruta para mostrar la confirmación
Route::get('/pedido/confirmacion/{id}', [CarritoController::class, 'mostrarConfirmacion'])->name('pedido.confirmacion');

Route::get('/pedido/detalles/{id_pedido}', [CarritoController::class, 'mostrarDetallesPedido'])
    ->name('pedido.detalles');

    Route::post('/carrito/actualizar/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminarItem'])->name('carrito.eliminar.item');

    //nuevas rutas de pago
    Route::prefix('metodos-pago')->group(function() {
    Route::get('/', [MetodosPagosController::class, 'index'])->name('metodos-pago.index');
    Route::get('/crear', [MetodosPagosController::class, 'create'])->name('metodos-pago.create');
    Route::post('/', [MetodosPagosController::class, 'store'])->name('metodos-pago.store');
    Route::post('/{id}/predeterminado', [MetodosPagosController::class, 'setDefault'])->name('metodos-pago.set-default');
    Route::delete('/{id}', [MetodosPagosController::class, 'destroy'])->name('metodos-pago.destroy');
    Route::post('/procesar-pago', [CarritoController::class, 'procesarPago'])->middleware('auth')->name('procesar-pago');

    // reseñas creadas por el usuario
    Route::post('/reseñas', [ResenasController::class, 'storeByUser'])->name('reseñas.storeByUser')->middleware('auth');

});

}); 


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('dashboard');

    Route::middleware(['auth', 'can:admin-only'])->group(function () {

        Route::get('/producto', [ProductosController::class, 'index'])->name('producto.index');
        route::get('/producto/crear', [ProductosController::class, 'create'])->name('producto.create');
        route::post('/producto/guardar',[ProductosController::class, 'store'])->name('producto.store');
        route::get('/producto/{id}/editar',[ProductosController::class, 'edit'])->name('producto.edit');
        route::Put('/producto/{id}/actualizar', [ProductosController::class, 'update'])->name('producto.update');
        route::delete('/producto/{id}/eliminar', [ProductosController::class, 'destroy'])->name('producto.destroy');


        //users
        route::get('/user', [UsersController::class, 'index'])->name('user.index'); 
        route::get('/user/crear', [UsersController::class, 'create'])->name('user.create');
        route::post('/user/guardar',[UsersController::class, 'store'])->name('user.store');
        route::get('/user/{id}/editar',[UsersController::class, 'edit'])->name('user.edit');
        route::Put('/user/{id}/actualizar', [UsersController::class, 'update'])->name('user.update');
        route::delete('/user/{id}/eliminar', [UsersController::class, 'destroy'])->name('user.destroy');

        // web.php
        Route::post('/user/{id}/cambiar-rol', [UsersController::class, 'cambiarRol'])->name('user.cambiarRol');

        //categorias
        route::get('/categorias', [categoriasController::class, 'index'])->name('categorias.index'); 
        route::get('/categorias/crear', [categoriasController::class, 'create'])->name('categorias.create');
        route::post('/categorias/guardar',[categoriasController::class, 'store'])->name('categorias.store');
        route::get('/categorias/{id}/editar',[categoriasController::class, 'edit'])->name('categorias.edit');
        route::Put('/categorias/{id}/actualizar', [categoriasController::class, 'update'])->name('categorias.update');
        route::delete('/categorias/{id}/eliminar', [categoriasController::class, 'destroy'])->name('categorias.destroy');
        //promociones
        route::get('promociones', [promocionesController::class, 'index'])->name('promociones.index'); 
        route::get('/promociones/crear', [promocionesController::class, 'create'])->name('promociones.create');
        route::post('promociones/guardar',[promocionesController::class, 'store'])->name('promociones.store');
        route::get('promociones/{id}/editar',[promocionesController::class, 'edit'])->name('promociones.edit');
        route::Put('promociones/{id}/actualizar', [promocionesController::class, 'update'])->name('promociones.update');
        route::delete('promociones/{id}/eliminar', [promocionesController::class, 'destroy'])->name('promociones.destroy');
        //descuentos
        route::get('/descuentos', [descuentosController::class, 'index'])->name('resenas.index'); 
        route::get('/descuentos/crear', [descuentosController::class, 'create'])->name('resenas.create');
        route::post('/descuentos/guardar',[descuentosController::class, 'store'])->name('resenas.store');
        route::get('/descuentos/{id}/editar',[descuentosController::class, 'edit'])->name('resenas.edit');
        route::Put('/descuentos/{id}/actualizar', [descuentosController::class, 'update'])->name('resenas.update');
        route::delete('/descuentos/{id}/eliminar', [descuentosController::class, 'destroy'])->name('resenas.destroy');
        //reseñas
        route::get('/reseñas', [resenasController::class, 'index'])->name('resenas.index'); 
        route::get('/reseñas/crear', [resenasController::class, 'create'])->name('resenas.create');
        route::post('/reseñas/guardar',[resenasController::class, 'store'])->name('resenas.store');
        route::get('/reseñas/{id}/editar',[resenasController::class, 'edit'])->name('resenas.edit');
        route::Put('/reseñas/{id}/actualizar', [resenasController::class, 'update'])->name('resenas.update');
        route::delete('/reseñas/{id}/eliminar', [resenasController::class, 'destroy'])->name('resenas.destroy');
        //metodos de pago
        route::get('/pagos', [metodosPagosController::class, 'index'])->name('pagos.index'); 
        route::get('/pagos/crear', [metodosPagosController::class, 'create'])->name('pagos.create');
        route::post('/pagos/guardar',[metodosPagosController::class, 'store'])->name('pagos.store');
        route::get('/pagos/{id}/editar',[metodosPagosController::class, 'edit'])->name('pagos.edit');
        route::Put('/pagos/{id}/actualizar', [metodosPagosController::class, 'update'])->name('pagos.update');
        route::delete('/pagos/{id}/eliminar', [metodosPagosController::class, 'destroy'])->name('pagos.destroy');
        //pedidos
        route::get('/pedidos', [pedidosController::class, 'index'])->name('pedidos.index'); 
        route::get('/pedidos/crear', [pedidosController::class, 'create'])->name('pedidos.create');
        route::post('/pedidos/guardar',[pedidosController::class, 'store'])->name('pedidos.store');
        route::get('/pedidos/{id}/editar',[pedidosController::class, 'edit'])->name('pedidos.edit');
        route::Put('/pedidos/{id}/actualizar', [pedidosController::class, 'update'])->name('pedidos.update');
        route::delete('/pedidos/{id}/eliminar', [pedidosController::class, 'destroy'])->name('pedidos.destroy');
        //envios
        route::get('/envios', [enviosController::class, 'index'])->name('envios.index'); 
        route::get('/envios/crear', [enviosController::class, 'create'])->name('envios.create');
        route::post('/envios/guardar',[enviosController::class, 'store'])->name('envios.store');
        route::get('/envios/{id}/editar',[enviosController::class, 'edit'])->name('envios.edit');
        route::Put('/envios/{id}/actualizar', [enviosController::class, 'update'])->name('envios.update');
        route::delete('/envios/{id}/eliminar', [enviosController::class, 'destroy'])->name('envios.destroy');
        //aplicaiones de promociones
        route::get('/appromociones', [aplicacionespromocionesController::class, 'index'])->name('appromociones.index'); 
        route::get('/appromociones/crear', [aplicacionespromocionesController::class, 'create'])->name('appromociones.create');
        route::post('/appromociones/guardar',[aplicacionespromocionesController::class, 'store'])->name('appromociones.store');
        route::get('/appromociones/{id1}/{id2}/editar',[aplicacionespromocionesController::class, 'edit'])->name('appromociones.edit');
        route::Put('/appromociones/{id1}/{id2}/actualizar', [aplicacionespromocionesController::class, 'update'])->name('appromociones.update');
        route::delete('/appromociones/{id1}/{id2}/eliminar', [aplicacionespromocionesController::class, 'destroy'])->name('appromociones.destroy');
        //detalle de pedidos
        route::get('/dtpedidos', [DetallePedidosController::class, 'index'])->name('detallepedidos.index'); 
        route::get('/dtpedidos/crear', [DetallePedidosController::class, 'create'])->name('detallepedidos.create');
        route::post('/dtpedidos/guardar',[DetallePedidosController::class, 'store'])->name('detallepedidos.store');
        route::get('/dtpedidos/{id1}/{id2}/editar',[DetallePedidosController::class, 'edit'])->name('detallepedidos.edit');
        route::Put('/dtpedidos/{id1}/{id2}/actualizar', [DetallePedidosController::class, 'update'])->name('detallepedidos.update');
        route::delete('/dtpedidos/{id1}/{id2}/eliminar', [DetallePedidosController::class, 'destroy'])->name('detallepedidos.destroy');
        
        //applicacion de descuentos
        route::get('/apdescuentos', [AplicacionesDescuentosController::class, 'index'])->name('appdescuentos.index'); 
        route::get('/apdescuentos/crear', [AplicacionesDescuentosController::class, 'create'])->name('appdescuentos.create');
        route::post('/apdescuentos/guardar',[AplicacionesDescuentosController::class, 'store'])->name('appdescuentos.store');
        route::get('/apdescuentos/{id1}/{id2}/editar',[AplicacionesDescuentosController::class, 'edit'])->name('appdescuentos.edit');
        route::Put('/apdescuentos/{id1}/{id2}/actualizar', [AplicacionesDescuentosController::class, 'update'])->name('appdescuentos.update');
        route::delete('/apdescuentos/{id1}/{id2}/eliminar', [AplicacionesDescuentosController::class, 'destroy'])->name('appdescuentos.destroy');
        
        //generar datos falsos 
        Route::get('/pedidos/generate-fake', [PedidosController::class, 'generateFakePedidos'])
            ->name('pedidos.generate-fake');

        Route::post('/envios/igualar', [EnviosController::class, 'igualarEnvios'])
            ->name('envios.igualar');

        Route::post('/dtpedidos/generar-detalles', [DetallePedidosController::class, 'generarDetallesAutomaticos'])
            ->name('dtpedidos.generar');

Route::get('/appromociones/asignar-automaticas', [AplicacionesPromocionesController::class, 'asignarPromocionesAutomaticas'])
    ->name('appromociones.asignar-automaticas');
        Route::get('/adminPage', [ReportePedidosController::class, 'adminPage'])
            ->name('adminPage');

        Route::get('/apdescuentos/asignar-automaticos', [AplicacionesDescuentosController::class, 'asignarDescuentosAutomaticos'])
            ->name('apdescuentos.asignar-automaticos');
        Route::post('/users/generate-fake', [UsersController::class, 'generateFakeUsers'])
        ->name('users.fake');
        
        Route::get('/reseñas/create/{producto}', [ResenasController::class, 'createByUser'])
            ->name('reseñas.createByUser');
    Route::group(['prefix' => 'admin'], function() {
    Route::get('/dashboard', [ReportePedidosController::class, 'adminPage'])->name('admin.dashboard');
 
    Route::get('/reportes/ventas-categoria', [ReportePedidosController::class, 'ventasPorCategoria'])->name('reportes.ventas-categoria');
    Route::get('/reportes/ventas-fecha', [ReportePedidosController::class, 'ventasPorFecha'])->name('reportes.ventas-fecha');

    Route::get('/reportes/temporadas', [ReportePedidosController::class, 'analisisTemporadas'])->name('reportes.temporadas');
    Route::get('/reportes/clv', [ReportePedidosController::class, 'customerLifetimeValue'])->name('reportes.clv');

});
        });

});
