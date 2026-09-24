<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MesaController as AdminMesaController;
use App\Http\Controllers\Admin\PedidoController as AdminPedidoController;
use App\Http\Controllers\Admin\ProductoController as AdminProductoController;
use App\Http\Controllers\Admin\UsuarioController as AdminUsuarioController;
use App\Http\Controllers\Admin\VentaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordRecoveryController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\Cliente\DashboardController as ClienteDashboardController;
use App\Http\Controllers\Cocina\ComandaController;
use App\Http\Controllers\Publico\CarritoController;
use App\Http\Controllers\Publico\CarritoPageController;
use App\Http\Controllers\Publico\ConfirmacionController;
use App\Http\Controllers\Publico\HomeController;
use App\Http\Controllers\Staff\PedidoEstadoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Menu publico
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Autenticacion (un unico formulario sirve a staff y clientes)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/registro', [RegistroController::class, 'create'])->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

Route::get('/recuperar-password', [PasswordRecoveryController::class, 'show'])->name('password.form');
Route::post('/recuperar-password', [PasswordRecoveryController::class, 'enviarEnlace'])->name('password.enviar');
Route::post('/recuperar-password/restablecer', [PasswordRecoveryController::class, 'restablecer'])->name('password.restablecer');

/*
|--------------------------------------------------------------------------
| Carrito y checkout (publico; el carrito vive en localStorage)
|--------------------------------------------------------------------------
*/
Route::get('/carrito', [CarritoPageController::class, 'index'])->name('carrito');
Route::post('/carrito/procesar', [CarritoController::class, 'procesar'])->name('carrito.procesar');
Route::get('/confirmacion', [ConfirmacionController::class, 'show'])->name('confirmacion');

/*
|--------------------------------------------------------------------------
| Panel Cliente ("mi cuenta")
|--------------------------------------------------------------------------
*/
Route::middleware('auth:cliente')->prefix('mi-cuenta')->name('cliente.')->group(function () {
    Route::get('/', [ClienteDashboardController::class, 'inicio'])->name('inicio');
    Route::get('/pedidos', [ClienteDashboardController::class, 'pedidos'])->name('pedidos');
    Route::get('/pedidos/{pedido}', [ClienteDashboardController::class, 'detalle'])->name('detalle');
});

/*
|--------------------------------------------------------------------------
| Panel Administrador
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'role:administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::post('/usuarios', [AdminUsuarioController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios/{usuario}', [AdminUsuarioController::class, 'update'])->name('usuarios.update');
    Route::patch('/usuarios/{usuario}/estado', [AdminUsuarioController::class, 'toggleEstado'])->name('usuarios.toggleEstado');

    Route::get('/productos', [AdminProductoController::class, 'index'])->name('productos.index');
    Route::post('/productos', [AdminProductoController::class, 'store'])->name('productos.store');
    Route::put('/productos/{producto}', [AdminProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [AdminProductoController::class, 'destroy'])->name('productos.destroy');
    Route::patch('/productos/{producto}/disponibilidad', [AdminProductoController::class, 'toggleDisponible'])->name('productos.toggleDisponible');

    Route::get('/pedidos', [AdminPedidoController::class, 'index'])->name('pedidos.index');
    Route::post('/pedidos/{pedido}/cancelar', [AdminPedidoController::class, 'cancelar'])->name('pedidos.cancelar');
    Route::post('/pedidos/{pedido}/estado', [PedidoEstadoController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado');

    Route::get('/mesas', [AdminMesaController::class, 'index'])->name('mesas.index');
    Route::post('/mesas/liberar-todas', [AdminMesaController::class, 'liberarTodas'])->name('mesas.liberarTodas');
    Route::post('/mesas/{mesa}/liberar', [AdminMesaController::class, 'liberar'])->name('mesas.liberar');

    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
});

/*
|--------------------------------------------------------------------------
| Panel Cocina
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'role:cocina'])->prefix('cocina')->name('cocina.')->group(function () {
    Route::get('/', [ComandaController::class, 'comandas'])->name('comandas');
    Route::get('/productos', [ComandaController::class, 'productos'])->name('productos');
    Route::post('/productos/{producto}/agotar', [ComandaController::class, 'agotar'])->name('productos.agotar');
    Route::post('/productos/{producto}/activar', [ComandaController::class, 'activar'])->name('productos.activar');
    Route::post('/pedidos/{pedido}/estado', [PedidoEstadoController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado');
});
