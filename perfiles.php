<?php
require_once("./conexion.php");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar la acción a realizar
    if (isset($_POST["accion"])) {
        $accion = $_POST["accion"];

        // Ejecutar la acción correspondiente
        switch ($accion) {
            case "listar":
                // Función para listar perfiles
                listarPerfiles();
                break;
            case "crear":
                // Función para crear un perfil
                crearPerfil();
                break;
            case "ver":
                // Función para ver un perfil
                verPerfil($_POST["id_perfil"]);
                break;
            case "editar":
                // Función para actualizar un perfil
                actualizarPerfil();
                break;
            case "eliminar":
                // Función para eliminar un perfil
                eliminarPerfil($_POST["id_perfil"]);
                break;
            default:
                echo "Acción no válida";
        }
    } else {
        echo "No se proporcionó ninguna acción";
    }
}

function existePerfil($id_perfil)
{
    global $mysql;

    // Consultar la existencia del perfil
    $query = "SELECT id FROM perfiles WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_perfil);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el perfil
    if ($result->num_rows == 1) {
        return true;
    } else {
        return false;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
}

// Función para listar perfiles
function listarPerfiles()
{
    global $mysql;
    $estado = isset($_POST['estado']) ? $_POST['estado'] : "";

    // Realizar la consulta a la base de datos
    if ($estado != "") {
        $query = "SELECT * FROM perfiles WHERE estado = '$estado'";
    } else {
        $query = "SELECT * FROM perfiles";
    }
    
    $result = $mysql->query($query);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Objeto JSON para almacenar los datos de los perfiles
        $response = new stdClass();
        $response->status = "OK";
        $response->perfiles = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = $result->fetch_assoc()) {
            $response->perfiles[] = $fila;
        }

        // Convertir el array en formato JSON
        echo json_encode($response);
    } else {
        // Si no hay resultados, devolver un JSON con un mensaje
        echo json_encode(array("mensaje" => "No se encontraron perfiles"));
    }

    // Cerrar la conexión a la base de datos
    $mysql->close();
}

// Función para crear un perfil
function crearPerfil()
{
    global $mysql;

    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : "";
    $estado = $_POST['estado'];

    // Insertar datos en la tabla de perfiles
    $query = "INSERT INTO perfiles (nombre, descripcion, estado) VALUES (?, ?, ?)";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("sss", $nombre, $descripcion, $estado);

    try {
        $stmt->execute();

        echo "Perfil creado exitosamente";
    } catch (mysqli_sql_exception $e) {
        echo "Error al crear perfil: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para ver un perfil
function verPerfil($id_perfil)
{
    global $mysql;

    // Consultar la información del perfil
    $query = "SELECT * FROM perfiles WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_perfil);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el perfil
    if ($result->num_rows == 1) {
        // El perfil existe, obtener sus datos
        $perfil = $result->fetch_assoc();

        // Devolver los datos del perfil en formato JSON
        echo json_encode($perfil);
    } else {
        // El perfil no existe
        echo json_encode(array("mensaje" => "El perfil no existe"));
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para actualizar un perfil
function actualizarPerfil()
{
    global $mysql;

    // Obtener datos del formulario
    $id_perfil = $_POST['id_perfil'];
    $nombre = $_POST['nombre'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : "";
    $estado = $_POST['estado'];

    if (existePerfil($id_perfil)) {

        // Construir la consulta SQL para actualizar el perfil
        $query = "UPDATE perfiles SET nombre = ?, descripcion = ?, estado = ? WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("sssi", $nombre, $descripcion, $estado, $id_perfil);

        try {
            $stmt->execute();
            echo "Perfil actualizado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al actualizar perfil: " . $e->getMessage();
        }
    } else {
        // El perfil no existe
        echo "El perfil no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para eliminar un perfil
function eliminarPerfil($id_perfil)
{
    global $mysql;

    if (existePerfil($id_perfil)) {

        // Construir la consulta SQL para eliminar el perfil
        $query = "DELETE FROM perfiles WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $id_perfil);

        try {
            $stmt->execute();
            echo "Perfil eliminado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al eliminar perfil: " . $e->getMessage();
        }
    } else {
        echo "El perfil no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}
