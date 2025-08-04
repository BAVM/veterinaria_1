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
                // Función para listar tipos de productos
                listarTiposProductos();
                break;
            case "crear":
                // Función para crear un tipo de producto
                crearTipoProducto();
                break;
            case "ver":
                // Función para ver un tipo de producto
                verTipoProducto($_POST["id_tipo_producto"]);
                break;
            case "editar":
                // Función para actualizar un tipo de producto
                actualizarTipoProducto();
                break;
            case "eliminar":
                // Función para eliminar un tipo de producto
                eliminarTipoProducto($_POST["id_tipo_producto"]);
                break;
            default:
                echo "Acción no válida";
        }
    } else {
        echo "No se proporcionó ninguna acción";
    }
}

function existeTipoProducto($id_tipo_producto)
{
    global $mysql;

    // Consultar la existencia del tipo de producto
    $query = "SELECT id FROM tipos_productos WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_tipo_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el tipo de producto
    if ($result->num_rows == 1) {
        return true;
    } else {
        return false;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
}

// Función para listar tipos de productos
function listarTiposProductos()
{
    global $mysql;

    $estado = isset($_POST['estado']) ? $_POST['estado'] : "";

    // Realizar la consulta a la base de datos
    if ($estado != "") {
        $query = "SELECT * FROM tipos_productos WHERE estado = '$estado'";
    } else {
        $query = "SELECT * FROM tipos_productos";
    }
    
    $result = $mysql->query($query);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Objeto JSON para almacenar los datos de los tipos de productos
        $response = new stdClass();
        $response->status = "OK";
        $response->tiposProductos = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = $result->fetch_assoc()) {
            $response->tiposProductos[] = $fila;
        }

        // Convertir el array en formato JSON
        echo json_encode($response);
    } else {
        // Si no hay resultados, devolver un JSON con un mensaje
        echo json_encode(array("mensaje" => "No se encontraron tipos de productos"));
    }

    // Cerrar la conexión a la base de datos
    $mysql->close();
}

// Función para crear un tipo de producto
function crearTipoProducto()
{
    global $mysql;

    // Obtener datos del formulario
    $nombre_tipo = $_POST['nombre_tipo'];

    // Insertar datos en la tabla de tipos de productos
    $query = "INSERT INTO tipos_productos (nombre_tipo) VALUES (?)";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("s", $nombre_tipo);

    try {
        $stmt->execute();

        echo "Tipo de producto creado exitosamente";
    } catch (mysqli_sql_exception $e) {
        echo "Error al crear tipo de producto: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para ver un tipo de producto
function verTipoProducto($id_tipo_producto)
{
    global $mysql;

    // Consultar la información del tipo de producto
    $query = "SELECT * FROM tipos_productos WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_tipo_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el tipo de producto
    if ($result->num_rows == 1) {
        // El tipo de producto existe, obtener sus datos
        $tipoProducto = $result->fetch_assoc();

        // Devolver los datos del tipo de producto en formato JSON
        echo json_encode($tipoProducto);
    } else {
        // El tipo de producto no existe
        echo json_encode(array("mensaje" => "El tipo de producto no existe"));
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para actualizar un tipo de producto
function actualizarTipoProducto()
{
    global $mysql;
    // Obtener datos del formulario
    $id_tipo_producto = $_POST['id_tipo_producto'];
    $nombre_tipo = $_POST['nombre_tipo'];
    $estado = $_POST['estado'];

    if (existeTipoProducto($id_tipo_producto)) {

        // Construir la consulta SQL para actualizar el tipo de producto
        $query = "UPDATE tipos_productos SET nombre_tipo = ?, estado = ?  WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("ssi",$nombre_tipo, $estado, $id_tipo_producto);

        try {
            $stmt->execute();
            echo "Tipo de producto actualizado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al actualizar el tipo de producto: " . $e->getMessage();
        }
    } else {
        // El tipo de producto no existe
        echo "El tipo de producto no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para eliminar un tipo de producto
function eliminarTipoProducto($id_tipo_producto)
{
    global $mysql;

    if (existeTipoProducto($id_tipo_producto)) {

        // Construir la consulta SQL para eliminar el tipo de producto
        $query = "UPDATE tipos_productos SET estado = 'inactivo' WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $id_tipo_producto);

        try {
            $stmt->execute();
            echo "Tipo de producto eliminado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al eliminar tipo de producto: " . $e->getMessage();
        }
    } else {
        echo "El tipo de producto no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}
