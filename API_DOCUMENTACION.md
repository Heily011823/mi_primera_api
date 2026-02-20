# Documentación de la API - Endpoints CRUD

Esta documentación explica cómo usar los diferentes endpoints HTTP implementados en esta API.

## Base URL
```
http://localhost:8000/api
```

---

## Endpoints Disponibles

### 1. GET - Obtener todos los elementos

**Método:** `GET`  
**URL:** `/api/elementos`  
**Descripción:** Obtiene una lista de todos los elementos disponibles.

**Ejemplo de uso:**
```bash
curl http://localhost:8000/api/elementos
```

**Respuesta exitosa (200):**
```json
{
  "message": "Lista de elementos obtenida exitosamente",
  "data": [
    {
      "id": 1,
      "nombre": "Elemento 1",
      "descripcion": "Descripción del elemento 1"
    },
    {
      "id": 2,
      "nombre": "Elemento 2",
      "descripcion": "Descripción del elemento 2"
    }
  ]
}
```

---

### 2. GET - Obtener un elemento específico

**Método:** `GET`  
**URL:** `/api/elementos/{id}`  
**Descripción:** Obtiene un elemento específico por su ID.

**Ejemplo de uso:**
```bash
curl http://localhost:8000/api/elementos/1
```

**Respuesta exitosa (200):**
```json
{
  "message": "Elemento obtenido exitosamente",
  "data": {
    "id": 1,
    "nombre": "Elemento 1",
    "descripcion": "Descripción del elemento 1"
  }
}
```

**Respuesta de error (404):**
```json
{
  "message": "Elemento no encontrado"
}
```

---

### 3. POST - Crear un nuevo elemento

**Método:** `POST`  
**URL:** `/api/elementos`  
**Descripción:** Crea un nuevo elemento en el sistema.

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
  "nombre": "Nuevo Elemento",
  "descripcion": "Descripción del nuevo elemento"
}
```

**Ejemplo de uso con cURL:**
```bash
curl -X POST http://localhost:8000/api/elementos \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nombre": "Nuevo Elemento",
    "descripcion": "Descripción del nuevo elemento"
  }'
```

**Ejemplo de uso con Postman:**
1. Método: POST
2. URL: `http://localhost:8000/api/elementos`
3. Headers: `Content-Type: application/json`
4. Body (raw JSON):
```json
{
  "nombre": "Nuevo Elemento",
  "descripcion": "Descripción del nuevo elemento"
}
```

**Respuesta exitosa (201):**
```json
{
  "message": "Elemento creado exitosamente",
  "data": {
    "id": 4,
    "nombre": "Nuevo Elemento",
    "descripcion": "Descripción del nuevo elemento"
  }
}
```

**Validaciones:**
- `nombre`: Requerido, debe ser un string, máximo 255 caracteres
- `descripcion`: Opcional, puede ser un string

---

### 4. PUT - Actualizar un elemento completo

**Método:** `PUT`  
**URL:** `/api/elementos/{id}`  
**Descripción:** Actualiza TODOS los campos de un elemento. Reemplaza completamente el elemento.

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**
```json
{
  "nombre": "Elemento Actualizado",
  "descripcion": "Nueva descripción completa"
}
```

**Ejemplo de uso con cURL:**
```bash
curl -X PUT http://localhost:8000/api/elementos/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nombre": "Elemento Actualizado",
    "descripcion": "Nueva descripción completa"
  }'
```

**Respuesta exitosa (200):**
```json
{
  "message": "Elemento actualizado exitosamente (PUT)",
  "data": {
    "id": 1,
    "nombre": "Elemento Actualizado",
    "descripcion": "Nueva descripción completa"
  }
}
```

**Nota importante:** PUT reemplaza TODOS los campos. Si no envías un campo, se perderá.

---

### 5. PATCH - Actualizar un elemento parcialmente

**Método:** `PATCH`  
**URL:** `/api/elementos/{id}`  
**Descripción:** Actualiza SOLO los campos que envíes. Los demás campos se mantienen igual.

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Body (JSON) - Ejemplo 1 (solo actualizar nombre):**
```json
{
  "nombre": "Solo actualizar el nombre"
}
```

**Body (JSON) - Ejemplo 2 (solo actualizar descripción):**
```json
{
  "descripcion": "Solo actualizar la descripción"
}
```

**Body (JSON) - Ejemplo 3 (actualizar ambos):**
```json
{
  "nombre": "Actualizar ambos",
  "descripcion": "Nueva descripción"
}
```

**Ejemplo de uso con cURL:**
```bash
curl -X PATCH http://localhost:8000/api/elementos/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nombre": "Solo actualizar el nombre"
  }'
```

**Respuesta exitosa (200):**
```json
{
  "message": "Elemento actualizado parcialmente exitosamente (PATCH)",
  "data": {
    "id": 1,
    "nombre": "Solo actualizar el nombre",
    "descripcion": "Descripción original se mantiene"
  }
}
```

**Diferencia entre PUT y PATCH:**
- **PUT**: Debes enviar TODOS los campos, reemplaza el elemento completo
- **PATCH**: Solo envías los campos que quieres actualizar, los demás se mantienen

---

### 6. DELETE - Eliminar un elemento

**Método:** `DELETE`  
**URL:** `/api/elementos/{id}`  
**Descripción:** Elimina un elemento del sistema.

**Ejemplo de uso con cURL:**
```bash
curl -X DELETE http://localhost:8000/api/elementos/1
```

**Respuesta exitosa (200):**
```json
{
  "message": "Elemento eliminado exitosamente",
  "data": {
    "id": 1,
    "nombre": "Elemento 1",
    "descripcion": "Descripción del elemento 1"
  }
}
```

**Respuesta de error (404):**
```json
{
  "message": "Elemento no encontrado"
}
```

---

## Resumen de Métodos HTTP

| Método | URL | Descripción | Código de Éxito |
|--------|-----|-------------|-----------------|
| GET | `/api/elementos` | Obtener todos | 200 |
| GET | `/api/elementos/{id}` | Obtener uno | 200 |
| POST | `/api/elementos` | Crear nuevo | 201 |
| PUT | `/api/elementos/{id}` | Actualizar completo | 200 |
| PATCH | `/api/elementos/{id}` | Actualizar parcial | 200 |
| DELETE | `/api/elementos/{id}` | Eliminar | 200 |

---

## Códigos de Estado HTTP

- **200 OK**: Operación exitosa
- **201 Created**: Recurso creado exitosamente
- **404 Not Found**: Recurso no encontrado
- **422 Unprocessable Entity**: Error de validación (datos inválidos)

---

## Notas para Estudiantes

1. **GET**: Solo lee datos, no modifica nada
2. **POST**: Crea nuevos recursos
3. **PUT**: Actualiza un recurso completo (debes enviar todos los campos)
4. **PATCH**: Actualiza un recurso parcialmente (solo envías los campos a cambiar)
5. **DELETE**: Elimina un recurso

### Importante:
- Esta implementación usa un array en memoria, por lo que los datos se reinician cada vez que se reinicia el servidor
- En un proyecto real, estos datos se guardarían en una base de datos
- Los endpoints están listos para conectarse a una base de datos cuando lo necesites

---

## Pruebas con Postman

1. **Instala Postman** si no lo tienes
2. Crea una nueva colección llamada "API Elementos"
3. Agrega cada endpoint con su método correspondiente
4. Para POST, PUT y PATCH, asegúrate de:
   - Seleccionar "Body" → "raw" → "JSON"
   - Agregar el header `Content-Type: application/json`

---

## Pruebas con cURL (Terminal)

Si prefieres usar la terminal, aquí tienes ejemplos completos:

```bash
# GET todos
curl http://localhost:8000/api/elementos

# GET uno
curl http://localhost:8000/api/elementos/1

# POST crear
curl -X POST http://localhost:8000/api/elementos \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Test","descripcion":"Descripción test"}'

# PUT actualizar
curl -X PUT http://localhost:8000/api/elementos/1 \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Actualizado","descripcion":"Nueva desc"}'

# PATCH actualizar parcial
curl -X PATCH http://localhost:8000/api/elementos/1 \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Solo nombre"}'

# DELETE eliminar
curl -X DELETE http://localhost:8000/api/elementos/1
```

