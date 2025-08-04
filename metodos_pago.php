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
                // Función para listar métodos de pago
                listarMetodosPago();
                break;
            case "crear":
                // Función para crear un método de pago
                crearMetodoPago();
                break;
            case "ver":
                // Función para ver un método de pago
                verMetodoPago($_POST["id_metodo_pago"]);
                break;
            case "editar":
                // Función para actualizar un método de pago
                actualizarMetodoPago();
                break;
            case "eliminar":
                // Función para eliminar un método de pago
                eliminarMetodoPago($_POST["id_metodo_pago"]);
                break;
            default:
                echo "Acción no válida";
        }
    } else {
        echo "No se proporcionó ninguna acción";
    }
}

function existeMetodoPago($id_metodo_pago)
{
    global $mysql;

    // Consultar la existencia del método de pago
    $query = "SELECT id FROM metodos_pago WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_metodo_pago);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el método de pago
    if ($result->num_rows == 1) {
        return true;
    } else {
        return false;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
}

// Función para listar métodos de pago
function listarMetodosPago()
{
    global $mysql;

    $estado = isset($_POST['estado']) ? $_POST['estado'] : "";

    // Realizar la consulta a la base de datos
    if ($estado != "") {
        $query = "SELECT * FROM metodos_pago WHERE estado = '$estado'";
    } else {
        $query = "SELECT * FROM metodos_pago";
    }

    $result = $mysql->query($query);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Objeto JSON para almacenar los datos de los métodos de pago
        $response = new stdClass();
        $response->status = "OK";
        $response->metodosPago = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = $result->fetch_assoc()) {
            $response->metodosPago[] = $fila;
        }

        // Convertir el array en formato JSON
        echo json_encode($response);
    } else {
        // Si no hay resultados, devolver un JSON con un mensaje
        echo json_encode(array("mensaje" => "No se encontraron métodos de pago"));
    }

    // Cerrar la conexión a la base de datos
    $mysql->close();
}

// Función para crear un método de pago
function crearMetodoPago()
{
    global $mysql;

    // Obtener datos del formulario
    $nombre_metodo = $_POST['nombre_metodo'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : "";

    // Insertar datos en la tabla de métodos de pago
    $query = "INSERT INTO metodos_pago (nombre_metodo, descripcion) VALUES (?, ?)";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("ss", $nombre_metodo, $descripcion);

    try {
        $stmt->execute();
        echo "Método de pago creado exitosamente";
    } catch (mysqli_sql_exception $e) {
        echo "Error al crear método de pago: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para ver un método de pago
function verMetodoPago($id_metodo_pago)
{
    global $mysql;

    // Consultar la información del método de pago
    $query = "SELECT id, nombre_metodo FROM metodos_pago WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_metodo_pago);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el método de pago
    if ($result->num_rows == 1) {
        // El método de pago existe, obtener sus datos
        $metodoPago = $result->fetch_assoc();

        // Devolver los datos del método de pago en formato JSON
        echo json_encode($metodoPago);
    } else {
        // El método de pago no existe
        echo json_encode(array("mensaje" => "El método de pago no existe"));
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para actualizar un método de pago
function actualizarMetodoPago()
{
    global $mysql;

    // Obtener datos del formulario
    $id_metodo_pago = $_POST['id_metodo_pago'];
    $nombre_metodo = $_POST['nombre_metodo'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : "";
    $estado = $_POST['estado'];

    if (existeMetodoPago($id_metodo_pago)) {
        // Construir la consulta SQL para actualizar el método de pago
        $query = "UPDATE metodos_pago SET nombre_metodo = ?, descripcion = ?, estado = ? WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("sssi", $nombre_metodo, $descripcion, $estado, $id_metodo_pago);

        try {
            $stmt->execute();
            echo "Método de pago actualizado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al actualizar método de pago: " . $e->getMessage();
        }
    } else {
        // El método de pago no existe
        echo "El método de pago no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para eliminar un método de pago
function eliminarMetodoPago($id_metodo_pago)
{
    global $mysql;

    if (existeMetodoPago($id_metodo_pago)) {
        // Construir la consulta SQL para eliminar el método de pago
        $query = "UPDATE metodos_pago SET estado = 'inactivo' WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $id_metodo_pago);

        try {
            $stmt->execute();
            echo "Método de pago eliminado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al eliminar método de pago: " . $e->getMessage();
        }
    } else {
        // El método de pago no existe
        echo "El método de pago no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}
?>
