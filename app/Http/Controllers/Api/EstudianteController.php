<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EstudianteController extends Controller
{
    // Ruta del archivo JSON para almacenar los estudiantes
    private $archivoEstudiantes = 'estudiantes.json';

    /**
     * Datos iniciales de estudiantes
     */
    private function getDatosIniciales(){
        return [
            [
                'id' => 1,
                'nombre' => 'Juan Perez',
                'edad' => 20,
                'carrera' => 'Ingeniería en Sistemas'
            ],
            [
                'id' => 2,
                'nombre' => 'Maria Garcia',
                'edad' => 22,
                'carrera' => 'Administración'
            ],
            [
                'id' => 3,
                'nombre' => 'Carlos Lopez',
                'edad' => 21,
                'carrera' => 'Contaduría'
            ]
        ];
    }

    /**
     * Método auxiliar para obtener el array de estudiantes desde el archivo
     */
    private function getEstudiantes(){
        // Si el archivo no existe, crear con datos iniciales
        if (!Storage::exists($this->archivoEstudiantes)) {
            $this->guardarEstudiantes($this->getDatosIniciales());
            return $this->getDatosIniciales();
        }

        // Leer el archivo JSON
        $contenido = Storage::get($this->archivoEstudiantes);
        $estudiantes = json_decode($contenido, true);

        // Si hay error al decodificar, retornar datos iniciales
        if ($estudiantes === null) {
            return $this->getDatosIniciales();
        }

        return $estudiantes;
    }

    /**
     * Método auxiliar para guardar el array de estudiantes en el archivo
     */
    private function guardarEstudiantes($estudiantes){
        Storage::put($this->archivoEstudiantes, json_encode($estudiantes, JSON_PRETTY_PRINT));
    }

    /**
     * GET - Consultar todos los estudiantes
     * Método: GET
     * URL: /api/estudiantes
     */
    public function consultarEstudiantes(){
        return response()->json([
            "message" => "Lista de estudiantes obtenida exitosamente",
            "data" => $this->getEstudiantes()
        ], 200);
    }

    /**
     * GET - Consultar un estudiante específico por ID
     * Método: GET
     * URL: /api/estudiantes/{id}
     */
    public function consultarEstudiante($id){
        $estudiantes = $this->getEstudiantes();
        $estudiante = collect($estudiantes)->firstWhere('id', $id);

        if (!$estudiante) {
            return response()->json([
                "message" => "Estudiante no encontrado"
            ], 404);
        }

        return response()->json([
            "message" => "Estudiante encontrado exitosamente",
            "data" => $estudiante
        ], 200);
    }

    /**
     * POST - Insertar un nuevo estudiante
     * Método: POST
     * URL: /api/estudiantes
     * Body (JSON): { "nombre": "Nombre", "edad": 20, "carrera": "Carrera" }
     */
    public function insertarEstudiante(Request $request){
        // Validar los datos recibidos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer|min:1',
            'carrera' => 'required|string|max:255'
        ]);

        // Obtener estudiantes actuales
        $estudiantes = $this->getEstudiantes();

        // Crear el nuevo estudiante
        $nuevoEstudiante = [
            'id' => count($estudiantes) > 0 ? max(array_column($estudiantes, 'id')) + 1 : 1,
            'nombre' => $request->nombre,
            'edad' => $request->edad,
            'carrera' => $request->carrera
        ];

        // Agregar al array
        $estudiantes[] = $nuevoEstudiante;

        // Guardar en el archivo
        $this->guardarEstudiantes($estudiantes);

        return response()->json([
            "message" => "Estudiante insertado exitosamente",
            "data" => $nuevoEstudiante
        ], 201);
    }

    /**
     * PUT - Actualizar un estudiante completo
     * Método: PUT
     * URL: /api/estudiantes/{id}
     * Body (JSON): { "nombre": "Nombre", "edad": 20, "carrera": "Carrera" }
     */
    public function actualizarEstudiante(Request $request, $id){
        // Validar los datos recibidos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer|min:1',
            'carrera' => 'required|string|max:255'
        ]);

        // Obtener estudiantes actuales
        $estudiantes = $this->getEstudiantes();

        // Buscar el estudiante
        $indice = collect($estudiantes)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Estudiante no encontrado"
            ], 404);
        }

        // Actualizar el estudiante
        $estudiantes[$indice] = [
            'id' => (int)$id,
            'nombre' => $request->nombre,
            'edad' => $request->edad,
            'carrera' => $request->carrera
        ];

        // Guardar en el archivo
        $this->guardarEstudiantes($estudiantes);

        return response()->json([
            "message" => "Estudiante actualizado exitosamente",
            "data" => $estudiantes[$indice]
        ], 200);
    }

    /**
     * PATCH - Actualizar un estudiante parcialmente (solo los campos enviados)
     * Método: PATCH
     * URL: /api/estudiantes/{id}
     * Body (JSON): { "nombre": "Solo actualizar nombre" } o { "edad": 25 } o cualquier combinación
     */
    public function actualizarEstudianteParcial(Request $request, $id){
        // Validar los datos recibidos (todos son opcionales en PATCH)
        $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'edad' => 'sometimes|integer|min:1',
            'carrera' => 'sometimes|string|max:255'
        ]);

        // Obtener estudiantes actuales
        $estudiantes = $this->getEstudiantes();

        // Buscar el estudiante
        $indice = collect($estudiantes)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Estudiante no encontrado"
            ], 404);
        }

        // Actualizar solo los campos que se enviaron
        if ($request->has('nombre')) {
            $estudiantes[$indice]['nombre'] = $request->nombre;
        }

        if ($request->has('edad')) {
            $estudiantes[$indice]['edad'] = $request->edad;
        }

        if ($request->has('carrera')) {
            $estudiantes[$indice]['carrera'] = $request->carrera;
        }

        // Guardar en el archivo
        $this->guardarEstudiantes($estudiantes);

        return response()->json([
            "message" => "Estudiante actualizado parcialmente exitosamente",
            "data" => $estudiantes[$indice]
        ], 200);
    }

    /**
     * DELETE - Eliminar un estudiante
     * Método: DELETE
     * URL: /api/estudiantes/{id}
     */
    public function eliminarEstudiante($id){
        // Obtener estudiantes actuales
        $estudiantes = $this->getEstudiantes();

        // Buscar el estudiante
        $indice = collect($estudiantes)->search(function($item) use ($id) {
            return $item['id'] == $id;
        });

        if ($indice === false) {
            return response()->json([
                "message" => "Estudiante no encontrado"
            ], 404);
        }

        // Guardar el estudiante antes de eliminarlo
        $estudianteEliminado = $estudiantes[$indice];

        // Eliminar del array
        unset($estudiantes[$indice]);
        $estudiantes = array_values($estudiantes); // Reindexar el array

        // Guardar en el archivo
        $this->guardarEstudiantes($estudiantes);

        return response()->json([
            "message" => "Estudiante eliminado exitosamente",
            "data" => $estudianteEliminado
        ], 200);
    }
}

