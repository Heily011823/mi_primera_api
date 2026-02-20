<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CursoController extends Controller
{
 // Ruta del archivo JSON para almacenar los cursos
  private $archivoCursos = 'cursos.json';

  /**
     * Datos iniciales de cursos
     */

  private function getDatosIniciales(){
        return [
            [
                'id' => 1,
                'nombre' => 'Programación I',
                'codigo' => 'PRG101',
                'creditos' => 3,
                'docente' => 'Carlos Ramírez'
            ],
            [
                'id' => 2,
                'nombre' => 'Bases de Datos',
                'codigo' => 'BD202',
                'creditos' => 4,
                'docente' => 'Ana Torres'
            ]
        ];
    }

    //obtener

    private function getCursos(){
        if (!Storage::exists($this->archivoCursos)) {
            $this->guardarCursos($this->getDatosIniciales());
            return $this->getDatosIniciales();
        }

        $contenido = Storage::get($this->archivoCursos);
        $cursos = json_decode($contenido, true);

        if ($cursos === null) {
            return $this->getDatosIniciales();
        }

        return $cursos;
    }
   //Metodo para guardar
    private function guardarCursos($cursos){
        Storage::put($this->archivoCursos, json_encode($cursos, JSON_PRETTY_PRINT));
    }

    //listar

    public function listarCursos(){
        return response()->json([
            "message" => "Lista de cursos obtenida",
            "data" => $this->getCursos()
        ], 200);
    }

    public function consultarCurso($id){
        $cursos = $this->getCursos();
        $curso = collect($cursos)->firstWhere('id', $id);

        if (!$curso) {
            return response()->json([
                "message" => "Curso no encontrado"
            ], 404);
        }

        return response()->json([
            "message" => "Curso encontrado",
            "data" => $curso
        ], 200);
    }

    public function crearCurso(Request $request){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'creditos' => 'required|integer|min:1',
            'docente' => 'required|string|max:255'
        ]);

        $cursos = $this->getCursos();

        $nuevoCurso = [
            'id' => count($cursos) > 0 ? max(array_column($cursos, 'id')) + 1 : 1,
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'creditos' => $request->creditos,
            'docente' => $request->docente
        ];

        $cursos[] = $nuevoCurso;
        $this->guardarCursos($cursos);

        return response()->json([
            "message" => "Curso creado exitosamente",
            "data" => $nuevoCurso
        ], 201);
    }


    public function actualizarCurso(Request $request, $id){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50',
            'creditos' => 'required|integer|min:1',
            'docente' => 'required|string|max:255'
        ]);

        $cursos = $this->getCursos();

        $indice = collect($cursos)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Curso no encontrado"
            ], 404);
        }

        $cursos[$indice] = [
            'id' => (int)$id,
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'creditos' => $request->creditos,
            'docente' => $request->docente
        ];

        $this->guardarCursos($cursos);

        return response()->json([
            "message" => "Curso actualizado",
            "data" => $cursos[$indice]
        ], 200);
    }

    public function eliminarCurso($id){
        $cursos = $this->getCursos();

        $indice = collect($cursos)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Curso no encontrado"
            ], 404);
        }

        $cursoEliminado = $cursos[$indice];

        unset($cursos[$indice]);
        $cursos = array_values($cursos);

        $this->guardarCursos($cursos);

        return response()->json([
            "message" => "Curso eliminado",
            "data" => $cursoEliminado
        ], 200);
    }
}