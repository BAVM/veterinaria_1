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
                // Función para listar tipos de animales
                listarTiposAnimales();
                break;
            case "crear":
                // Función para crear un tipo de animal
                crearTipoAnimal();
                break;
            case "ver":
                // Función para ver un tipo de animal
                verTipoAnimal($_POST["id_tipo_animal"]);
                break;
            case "editar":
                // Función para actualizar un tipo de animal
                actualizarTipoAnimal();
                break;
            case "eliminar":
                // Función para eliminar un tipo de animal
                eliminarTipoAnimal($_POST["id_tipo_animal"]);
                break;
            default:
                echo "Acción no válida";
        }
    } else {
        echo "No se proporcionó ninguna acción";
    }
}

function existeTipo($id_tipo_animal)
{
    global $mysql;

    // Consultar la existencia de la raza
    $query = "SELECT id FROM tipo_animal WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_tipo_animal);
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

// Función para listar tipos de animales
function listarTiposAnimales()
{
    global $mysql;
    $estado = isset($_POST['estado']) ? $_POST['estado'] : "";

    // Realizar la consulta a la base de datos
    if ($estado != "") {
        $query = "SELECT * FROM tipo_animal WHERE estado = '$estado'";
    } else {
        $query = "SELECT * FROM tipo_animal";
    }

    $result = $mysql->query($query);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Objeto JSON para almacenar los datos de los usuarios
        $response = new stdClass();
        $response->status = "OK";
        $response->tiposAnimales = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = $result->fetch_assoc()) {
            $response->tiposAnimales[] = $fila;
        }

        // Convertir el array en formato JSON
        echo json_encode($response);
    } else {
        // Si no hay resultados, devolver un JSON con un mensaje
        echo json_encode(array("mensaje" => "No se encontraron tipos de animales"));
    }

    // Cerrar la conexión a la base de datos
    $mysql->close();
}

// Función para crear un tipo de animal
function crearTipoAnimal()
{
    global $mysql;

    // Obtener datos del formulario
    $nombre_animal = $_POST['nombre_animal'];

    // Insertar datos en la tabla de tipos de animales
    $query = "INSERT INTO tipo_animal (nombre_animal) VALUES (?)";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("s", $nombre_animal);

    try {
        $stmt->execute();

        echo "Tipo de animal creado exitosamente";
    } catch (mysqli_sql_exception $e) {
        echo "Error al crear tipo de animal: " . $e->getMessage();
    }
    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para ver un tipo de animal
function verTipoAnimal($id_tipo_animal)
{
    global $mysql;

    // Consultar la información del tipo de animal
    $query = "SELECT * FROM tipo_animal WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_tipo_animal);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el tipo de animal
    if ($result->num_rows == 1) {
        // El tipo de animal existe, obtener sus datos
        $tipoAnimal = $result->fetch_assoc();

        // Devolver los datos del tipo de animal en formato JSON
        echo json_encode($tipoAnimal);
    } else {
        // El tipo de animal no existe
        echo json_encode(array("mensaje" => "El tipo de animal no existe"));
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para actualizar un tipo de animal
function actualizarTipoAnimal()
{
    global $mysql;

    // Obtener datos del formulario
    $id_tipo_animal = $_POST['id_tipo_animal'];
    $nombre_animal = $_POST['nombre_animal'];
    $estado = $_POST['estado'];
    
    if (existeTipo($id_tipo_animal)) {

        // Construir la consulta SQL para actualizar el tipo de animal
        $query = "UPDATE tipo_animal SET nombre_animal = ?, estado = ?  WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("ssi",$nombre_animal, $estado, $id_tipo_animal);

        try {

            $stmt->execute();
            echo "Tipo de animal actualizado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al actualizar el Tipo de animal: " . $e->getMessage();
        }
    } else {
        // El usuario no existe
        echo "El Tipo de animal  no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

function eliminarTipoAnimal($id_tipo_animal)
{
    global $mysql;

    if (existeTipo($id_tipo_animal)) {

        // Construir la consulta SQL para eliminar el tipo de animal
        $query = "UPDATE tipo_animal SET estado = 'inactivo' WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $id_tipo_animal);

        try {
            $stmt->execute();
            echo "Tipo de animal eliminado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al eliminar tipo de animal: Ya esta en Uso ";
        }
    } else {
        echo "El tipo_animal no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}
