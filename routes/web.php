<?php

use App\Empresa;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/nueva-empresa', function () {
    return view('crear_empresa');
});

Route::post('/guardar-empresa', function (Request $request) {
    Empresa::create($request->all());
    return "¡Empresa guardada exitosamente en db_app_ventas!";
})->name('empresa.guardar');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/registro-inicial', function () { return view('registro_maestro'); });

Route::post('/empresa/store', 'EmpresaController@store')->name('empresa.store');

// Ruta para MOSTRAR el formulario (Método GET)
Route::get('/productos/nuevo', 'ProductoController@create')->name('productos.create');

// Ruta para PROCESAR el envío del formulario e imagen (Método POST)
Route::post('/productos/guardar', 'ProductoController@store')->name('productos.store');

Route::get('/productos/gestion', 'ProductoController@index')->name('productos.index');

Route::get('/productos/{id}/edit', 'ProductoController@edit')->name('productos.edit');

Route::put('/productos/{id}', 'ProductoController@update')->name('productos.update');

//VENTAS
Route::get('/ventas/pos', 'VentaController@index')->name('ventas.pos');

Route::post('/ventas/guardar', 'VentaController@store')->name('ventas.store');

Route::get('/cocina', 'CocinaController@index')->name('cocina.index');
Route::post('/cocina/listo/{id}', 'CocinaController@marcarListo')->name('cocina.listo');

Route::get('/cocina', 'PedidoController@vistaCocina')->name('cocina.index');

// Rutas de Pagos
Route::get('/pagos', 'PagoController@index')->name('pagos.index');
Route::post('/pagos/finalizar/{id}', 'PagoController@finalizar')->name('pagos.finalizar');

// Vista principal de gestión de pedidos
Route::get('/pedidos/gestion', 'PedidoController@indexGestion')->name('pedidos.gestion');

// Ruta para cancelar (cambiar estado a 'cancelado')
Route::post('/pedidos/{id}/cancelar', 'PedidoController@cancelar')->name('pedidos.cancelar');

// Ruta para ir a la edición (similar al POS)
Route::get('/pedidos/{id}/editar', 'PedidoController@editar')->name('pedidos.editar');

Route::put('/pedidos/{id}/update', 'PedidoController@updatePedido')->name('pedidos.update_pedido');

// Route::post('/pedidos/{id}/despachar', 'PedidoController@despacharPedido')->name('pedidos.despachar');

Route::post('/pedidos/{id}/despachar', 'PedidoController@despachar')->name('pedidos.despachar');
// Debe ser POST, no GET


Route::get('/pedidos/pagos', 'PedidoController@vistaPagos')->name('pedidos.pagos');

Route::get('/informes/ventas-diarias', 'InformeController@ventasDiarias')->name('informes.ventas_diarias');