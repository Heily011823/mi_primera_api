<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramaAcademicoController extends Controller
{
     // Ruta del archivo JSON para almacenar los programas
  private $archivoProgramas = 'programas.json';

  //datos iniciales

  private function getDatosIniciales(){
        return [
            [
                'id' => 1,
                'nombre' => 'Ingeniería de Sistemas',
                'facultad' => 'Ingeniería',
                'duracion' => 10
            ],
            [
                'id' => 2,
                'nombre' => 'Administración de Empresas',
                'facultad' => 'Ciencias Económicas',
                'duracion' => 8
            ]
        ];
  }
  //obtener

  private function getProgramas(){
        if (!Storage::exists($this->archivoProgramas)) {
            $this->guardarProgramas($this->getDatosIniciales());
            return $this->getDatosIniciales();
        }

        $contenido = Storage::get($this->archivoProgramas);
        $programas = json_decode($contenido, true);

        if ($programas === null) {
            return $this->getDatosIniciales();
        }

        return $programas;
    }

    //  Guardar
    private function guardarProgramas($programas){
        Storage::put($this->archivoProgramas, json_encode($programas, JSON_PRETTY_PRINT));
    }


    //Listar

    public function listarProgramas(){
        return response()->json([
            "message" => "Lista de programas obtenida",
            "data" => $this->getProgramas()
        ], 200);
    }


    //Consultar id
    public function consultarPrograma($id){
        $programas = $this->getProgramas();
        $programa = collect($programas)->firstWhere('id', $id);

        if (!$programa) {
            return response()->json([
                "message" => "Programa no encontrado"
            ], 404);
        }

        return response()->json([
            "message" => "Programa encontrado",
            "data" => $programa
        ], 200);
    }

    //Crear
    public function crearPrograma(Request $request){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'facultad' => 'required|string|max:255',
            'duracion' => 'required|integer|min:1'
        ]);

        $programas = $this->getProgramas();

        $nuevoPrograma = [
            'id' => count($programas) > 0 ? max(array_column($programas, 'id')) + 1 : 1,
            'nombre' => $request->nombre,
            'facultad' => $request->facultad,
            'duracion' => $request->duracion
        ];

        $programas[] = $nuevoPrograma;
        $this->guardarProgramas($programas);

        return response()->json([
            "message" => "Programa creado exitosamente",
            "data" => $nuevoPrograma
        ], 201);
    }

    //Actualizar
    public function actualizarPrograma(Request $request, $id){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'facultad' => 'required|string|max:255',
            'duracion' => 'required|integer|min:1'
        ]);

        $programas = $this->getProgramas();

        $indice = collect($programas)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Programa no encontrado"
            ], 404);
        }

        $programas[$indice] = [
            'id' => (int)$id,
            'nombre' => $request->nombre,
            'facultad' => $request->facultad,
            'duracion' => $request->duracion
        ];

        $this->guardarProgramas($programas);

        return response()->json([
            "message" => "Programa actualizado",
            "data" => $programas[$indice]
        ], 200);
    }
    //Eliminar

    public function eliminarPrograma($id){
        $programas = $this->getProgramas();

        $indice = collect($programas)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Programa no encontrado"
            ], 404);
        }

        $programaEliminado = $programas[$indice];

        unset($programas[$indice]);
        $programas = array_values($programas);

        $this->guardarProgramas($programas);

        return response()->json([
            "message" => "Programa eliminado",
            "data" => $programaEliminado
        ], 200);
    }
}