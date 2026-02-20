<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotaController extends Controller
{
    private $archivoNotas = 'notas.json';

    private function getDatosIniciales(){
    return [
        [
            'id' => 1,
            'estudiante' => 'Juan Pérez',
            'materia' => 'Ingenieria',
            'calificacion' => 4.5
        ],
        [
            'id' => 2,
            'estudiante' => 'María Garcia',
            'materia' => 'Programación',
            'calificacion' => 4.8
        ],
        [
            'id' => 3,
            'estudiante' => 'Carlos López',
            'materia' => 'Base de Datos',
            'calificacion' => 3.9
        ]
    ];
    }

 private function getNotas(){
    if (!Storage::exists($this->archivoNotas)) {
        $this->guardarNotas($this->getDatosIniciales());
        return $this->getDatosIniciales();
    }

    $contenido = Storage::get($this->archivoNotas);
    $notas = json_decode($contenido, true);

    if ($notas === null) {
        return $this->getDatosIniciales();
    }

    return $notas;
}

    // Guardar notas
    private function guardarNotas($notas){
        Storage::put($this->archivoNotas, json_encode($notas, JSON_PRETTY_PRINT));
    }

    
    public function listarNotas(){
        return response()->json([
            "message" => "Lista de notas obtenida",
            "data" => $this->getNotas()
        ], 200);
    }

    // 📌 CONSULTAR POR ID
    public function consultarNota($id){
        $notas = $this->getNotas();
        $nota = collect($notas)->firstWhere('id', $id);

        if (!$nota) {
            return response()->json([
                "message" => "Nota no encontrada"
            ], 404);
        }

        return response()->json([
            "message" => "Nota encontrada",
            "data" => $nota
        ], 200);
    }

    // 📌 CREAR NOTA
    public function crearNota(Request $request){

        $request->validate([
            'estudiante_id' => 'required|integer',
            'curso_id' => 'required|integer',
            'nota' => 'required|numeric|min:0|max:5'
        ]);

        $notas = $this->getNotas();

        // Validar estudiante
        if (!Storage::exists('estudiantes.json')) {
            return response()->json(["message" => "No existen estudiantes"], 404);
        }

        $estudiantes = json_decode(Storage::get('estudiantes.json'), true);
        $estudiante = collect($estudiantes)->firstWhere('id', $request->estudiante_id);

        if (!$estudiante) {
            return response()->json([
                "message" => "El estudiante no existe"
            ], 404);
        }

        // Validar curso
        if (!Storage::exists('cursos.json')) {
            return response()->json(["message" => "No existen cursos"], 404);
        }

        $cursos = json_decode(Storage::get('cursos.json'), true);
        $curso = collect($cursos)->firstWhere('id', $request->curso_id);

        if (!$curso) {
            return response()->json([
                "message" => "El curso no existe"
            ], 404);
        }

        
        $existe = collect($notas)->first(function ($item) use ($request) {
            return $item['estudiante_id'] == $request->estudiante_id &&
                   $item['curso_id'] == $request->curso_id;
        });

        if ($existe) {
            return response()->json([
                "message" => "Ya existe una nota para este estudiante en este curso"
            ], 400);
        }

        $nuevaNota = [
            'id' => count($notas) > 0 ? max(array_column($notas, 'id')) + 1 : 1,
            'estudiante_id' => $request->estudiante_id,
            'curso_id' => $request->curso_id,
            'nota' => $request->nota
        ];

        $notas[] = $nuevaNota;
        $this->guardarNotas($notas);

        return response()->json([
            "message" => "Nota creada exitosamente",
            "data" => $nuevaNota
        ], 201);
    }

    
    public function actualizarNota(Request $request, $id){

        $request->validate([
            'nota' => 'required|numeric|min:0|max:5'
        ]);

        $notas = $this->getNotas();

        $indice = collect($notas)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Nota no encontrada"
            ], 404);
        }

        $notas[$indice]['nota'] = $request->nota;

        $this->guardarNotas($notas);

        return response()->json([
            "message" => "Nota actualizada correctamente",
            "data" => $notas[$indice]
        ], 200);
    }

    
    public function eliminarNota($id){

        $notas = $this->getNotas();

        $indice = collect($notas)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Nota no encontrada"
            ], 404);
        }

        $notaEliminada = $notas[$indice];

        unset($notas[$indice]);
        $notas = array_values($notas);

        $this->guardarNotas($notas);

        return response()->json([
            "message" => "Nota eliminada correctamente",
            "data" => $notaEliminada
        ], 200);
    }
}