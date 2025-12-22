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

Route::get('/ventas/pos', 'VentaController@index')->name('ventas.pos');

Route::post('/ventas/guardar', 'VentaController@store')->name('ventas.store');