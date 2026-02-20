<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\backendController;
use App\Http\Controllers\Api\EstudianteController;
use App\Http\Controllers\Api\CursoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// Rutas para el CRUD de elementos

// GET - Obtener todos los elementos
Route::get('/elementos', [backendController::class, 'getDatos']);

// GET - Obtener un elemento específico por ID
Route::get('/elementos/{id}', [backendController::class, 'getElemento']);

// POST - Crear un nuevo elemento
Route::post('/calcular', [backendController::class, 'crearElemento']);

Route::post('/sumar', [backendController::class, 'sumarValores']);
Route::post('/multiplicar', [backendController::class, 'multiplicarValores']);


// PUT - Actualizar un elemento completo
Route::put('/elementos/{id}', [backendController::class, 'actualizarElemento']);

// PATCH - Actualizar un elemento parcialmente
Route::patch('/elementos/{id}', [backendController::class, 'actualizarElementoParcial']);

// DELETE - Eliminar un elemento
Route::delete('/elementos/{id}', [backendController::class, 'eliminarElemento']);



// ============================================
// Rutas para el CRUD de Estudiantes
// ============================================

// GET - Consultar todos los estudiantes
Route::get('/estudiantes', [EstudianteController::class, 'consultarEstudiantes']);

// GET - Consultar un estudiante específico por ID
Route::get('/estudiantes/{id}', [EstudianteController::class, 'consultarEstudiante']);

// POST - Insertar un nuevo estudiante
Route::post('/estudiantes', [EstudianteController::class, 'insertarEstudiante']);

// PUT - Actualizar un estudiante completo
Route::put('/estudiantes/{id}', [EstudianteController::class, 'actualizarEstudiante']);

// PATCH - Actualizar un estudiante parcialmente
Route::patch('/estudiantes/{id}', [EstudianteController::class, 'actualizarEstudianteParcial']);

// DELETE - Eliminar un estudiante
Route::delete('/estudiantes/{id}', [EstudianteController::class, 'eliminarEstudiante']);


//Route::apiResource('cursos', CursoController::class);

Route::get('/cursos', [CursoController::class, 'listarCursos']);
Route::get('/cursos/{id}', [CursoController::class, 'consultarCurso']);
Route::post('/cursos', [CursoController::class, 'crearCurso']);
Route::put('/cursos/{id}', [CursoController::class, 'actualizarCurso']);
Route::delete('/cursos/{id}', [CursoController::class, 'eliminarCurso']);