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
                // Función para listar razas
                listarRazas();
                break;
            case "crear":
                // Función para crear una raza
                crearRaza();
                break;
            case "ver":
                // Función para ver una raza
                verRaza($_POST["id_raza"]);
                break;
            case "editar":
                // Función para actualizar una raza
                actualizarRaza();
                break;
            case "eliminar":
                // Función para eliminar una raza
                eliminarRaza($_POST["id_raza"]);
                break;
            default:
                echo "Acción no válida";
        }
    } else {
        echo "No se proporcionó ninguna acción";
    }
}

function existeRaza($id_raza)
{
    global $mysql;

    // Consultar la existencia de la raza
    $query = "SELECT id FROM razas WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_raza);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró la raza
    if ($result->num_rows == 1) {
        return true;
    } else {
        return false;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
}

// Función para listar razas
function listarRazas()
{
    global $mysql;

    $estado = isset($_POST['estado']) ? $_POST['estado'] : "";

    // Realizar la consulta a la base de datos
    if ($estado != "") {
        $query = "SELECT a.id, a.nombre_raza, b.nombre_animal, a.estado
              FROM razas a
              LEFT JOIN tipo_animal b 
              ON a.id_tipo_animal = b.id
              WHERE a.estado = '$estado'";
    } else {
        $query = "SELECT a.id, a.nombre_raza, b.nombre_animal, a.estado
              FROM razas a
              LEFT JOIN tipo_animal b 
              ON a.id_tipo_animal = b.id";
    }

    $result = $mysql->query($query);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Array para almacenar las razas
        $response = new stdClass();
        $response->status = "OK";
        $response->razas = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = $result->fetch_assoc()) {
            $response->razas[] = $fila;
        }

        // Convertir el array en formato JSON
        echo json_encode($response);
    } else {
        // Si no hay resultados, devolver un JSON con un mensaje
        echo json_encode(array("mensaje" => "No se encontraron razas"));
    }

    // Cerrar la conexión a la base de datos
    $mysql->close();
}

// Función para crear una raza
function crearRaza()
{
    global $mysql;

    // Obtener datos del formulario
    $nombre_raza = $_POST['nombre_raza'];
    $id_tipo_animal = $_POST['id_tipo_animal'];

    // Insertar datos en la tabla de razas
    $query = "INSERT INTO razas (nombre_raza, id_tipo_animal) VALUES (?, ?)";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("si", $nombre_raza, $id_tipo_animal);

    try {
        $stmt->execute();
        echo "Raza creada exitosamente";
    } catch (mysqli_sql_exception $e) {
        echo "Error al crear Raza: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para ver una raza
function verRaza($id_raza)
{
    global $mysql;

    // Consultar la información de la raza
    $query = "SELECT a.id, a.nombre_raza, b.nombre_animal 
              FROM razas a
              LEFT JOIN tipo_animal b 
              ON a.id_tipo_animal = b.id 
              WHERE a.id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_raza);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró la raza
    if ($result->num_rows == 1) {
        // La raza existe, obtener sus datos
        $raza = $result->fetch_assoc();

        // Devolver los datos de la raza en formato JSON
        echo json_encode($raza);
    } else {
        // La raza no existe
        echo json_encode(array("mensaje" => "La raza no existe"));
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para actualizar una raza
function actualizarRaza()
{
    global $mysql;

    // Obtener datos del formulario
    $id_raza = $_POST['id_raza'];
    $nombre_raza = $_POST['nombre_raza'];
    $id_tipo_animal = $_POST['id_tipo_animal'];
    $estado = $_POST['estado'];

    // Verificar si la raza existe
    if (existeRaza($id_raza)) {

        // Construir la consulta SQL para actualizar la raza
        $query = "UPDATE razas SET nombre_raza = ?, id_tipo_animal = ?, estado = ? WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("sisi", $nombre_raza, $id_tipo_animal, $estado, $id_raza);
        try {
            $stmt->execute();
            echo "Raza actualizada exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al actualizar raza: " . $e->getMessage();
        }
    } else {
        echo "La raza no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para eliminar una raza
function eliminarRaza($id_raza)
{
    global $mysql;

    // Verificar si la raza existe
    if (existeRaza($id_raza)) {

        // Construir la consulta SQL para eliminar la raza
        $query = "UPDATE razas SET estado = 'inactivo' WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $id_raza);

        try {
            $stmt->execute();
            echo "Raza eliminada exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al eliminar tipo de animal: Ya esta en Uso";
        }
    } else {
        echo "La raza no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}
