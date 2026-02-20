<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class backendController extends Controller
{
    /**
     * GET - Obtener datos
     * Método: GET
     * URL: /api/elementos
     */
    public function getDatos(){
        return response()->json(["message" => "Hola, esto es un GET"], 200);
    }

    /**
     * GET - Obtener un elemento específico por ID
     * Método: GET
     * URL: /api/elementos/{id}
     */
    public function getElemento($id){
        return response()->json(["message" => "Hola, esto es un GET con parámetro. El ID recibido es: " . $id], 200);
    }

    /**
     * POST - Crear un nuevo elemento
     * Método: POST
     * URL: /api/elementos
     */
    public function crearElemento(Request $request){

    //return response()->json($request->all());
        $val1 = $request->input('valor1');
        $val2 = $request->input('valor2');
        $resultado = $val1+$val2;
        return response()->json(["message" => "El resultado de la suma es: ".$resultado], 201);
    }





    public function multiplicarValores(Request $request){

    //return response()->json($request->all());
        $val1 = $request->input('valor1');
        $val2 = $request->input('valor2');
        $resultado = $val1*$val2;
        return response()->json(["message" => "El resultado de la operacion multiplicar es: ".$resultado], 201);
    }

    public function sumarValores(Request $request){

    //return response()->json($request->all());
        $val1 = $request->input('valor1');
        $val2 = $request->input('valor2');
        $resultado = $val1+$val2;
        return response()->json(["message" => "El resultado de la operacion suma es: ".$resultado], 201);
    }

    /**
     * PUT - Actualizar un elemento completo
     * Método: PUT
     * URL: /api/elementos/{id}
     */
    public function actualizarElemento(Request $request, $id){
        return response()->json(["message" => "Hola, esto es un PUT. El ID recibido es: " . $id], 200);
    }

    /**
     * PATCH - Actualizar un elemento parcialmente
     * Método: PATCH
     * URL: /api/elementos/{id}
     */
    public function actualizarElementoParcial(Request $request, $id){
        return response()->json(["message" => "Hola, esto es un PATCH. El ID recibido es: " . $id], 200);
    }

    /**
     * DELETE - Eliminar un elemento
     * Método: DELETE
     * URL: /api/elementos/{id}
     */
    public function eliminarElemento($id){
        return response()->json(["message" => "Hola, esto es un DELETE. El ID recibido es: " . $id], 200);
    }
}
