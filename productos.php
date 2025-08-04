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
                // Función para listar productos
                listarProductos();
                break;
            case "crear":
                // Función para crear un producto
                crearProducto();
                break;
            case "ver":
                // Función para ver un producto
                verProducto($_POST["id_producto"]);
                break;
            case "editar":
                // Función para actualizar un producto
                actualizarProducto();
                break;
            case "eliminar":
                // Función para eliminar un producto
                eliminarProducto($_POST["id_producto"]);
                break;
            default:
                echo "Acción no válida";
        }
    } else {
        echo "No se proporcionó ninguna acción";
    }
}

function existeProducto($id_producto)
{
    global $mysql;

    // Consultar la existencia del producto
    $query = "SELECT id FROM productos WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el producto
    if ($result->num_rows == 1) {
        return true;
    } else {
        return false;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
}

// Función para listar productos
function listarProductos()
{
    global $mysql;

    $estado = isset($_POST['estado']) ? $_POST['estado'] : "";
    // Realizar la consulta a la base de datos
    if ($estado != "") {
        $query = "SELECT p.id, p.nombre_producto, p.descripcion, p.precio, p.cantidad_disponible, tp.nombre_tipo AS tipo_producto_nombre, p.estado 
                FROM productos p
                INNER JOIN tipos_productos tp 
                ON p.id_tipo_producto = tp.id
                WHERE p.estado = '$estado'";
    } else {
        $query = "SELECT p.id, p.nombre_producto, p.descripcion, p.precio, p.cantidad_disponible, tp.nombre_tipo AS tipo_producto_nombre, p.estado 
                FROM productos p
                INNER JOIN tipos_productos tp 
                ON p.id_tipo_producto = tp.id";
    }
    
    $result = $mysql->query($query);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Objeto JSON para almacenar los datos de los productos
        $response = new stdClass();
        $response->status = "OK";
        $response->productos = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = $result->fetch_assoc()) {
            $response->productos[] = $fila;
        }

        // Convertir el array en formato JSON
        echo json_encode($response);
    } else {
        // Si no hay resultados, devolver un JSON con un mensaje
        echo json_encode(array("mensaje" => "No se encontraron productos"));
    }

    // Cerrar la conexión a la base de datos
    $mysql->close();
}

// Función para crear un producto
function crearProducto()
{
    global $mysql;

    // Obtener datos del formulario
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : "";
    $precio = $_POST['precio'];
    $cantidad_disponible = $_POST['cantidad_disponible'];
    $id_tipo_producto = $_POST['id_tipo_producto'];
    $estado = $_POST['estado'];

    // Insertar datos en la tabla de productos
    $query = "INSERT INTO productos (nombre_producto, descripcion, precio, cantidad_disponible, id_tipo_producto, estado) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("ssdiss", $nombre_producto, $descripcion, $precio, $cantidad_disponible, $id_tipo_producto, $estado);

    try {
        $stmt->execute();
        echo "Producto creado exitosamente";
    } catch (mysqli_sql_exception $e) {
        echo "Error al crear producto: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para ver un producto
function verProducto($id_producto)
{
    global $mysql;

    // Consultar la información del producto
    $query = "SELECT p.id, p.nombre_producto, p.descripcion, p.precio, p.cantidad_disponible, tp.nombre_tipo AS tipo_producto_nombre, p.estado 
                FROM productos p
                INNER JOIN tipos_productos tp 
                ON p.id_tipo_producto = tp.id
              WHERE p.id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el producto
    if ($result->num_rows == 1) {
        // El producto existe, obtener sus datos
        $producto = $result->fetch_assoc();

        // Devolver los datos del producto en formato JSON
        echo json_encode($producto);
    } else {
        // El producto no existe
        echo json_encode(array("mensaje" => "El producto no existe"));
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para actualizar un producto
function actualizarProducto()
{
    global $mysql;

    // Obtener datos del formulario
    $id_producto = $_POST['id_producto'];
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : "";
    $precio = $_POST['precio'];
    $cantidad_disponible = $_POST['cantidad_disponible'];
    $id_tipo_producto = $_POST['id_tipo_producto'];
    $estado = $_POST['estado'];

    if (existeProducto($id_producto)) {

        // Construir la consulta SQL para actualizar el producto
        $query = "UPDATE productos SET nombre_producto = ?, descripcion = ?, precio = ?, cantidad_disponible = ?, id_tipo_producto = ?, estado = ? WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("ssdissi", $nombre_producto, $descripcion, $precio, $cantidad_disponible, $id_tipo_producto, $estado, $id_producto);

        try {
            $stmt->execute();
            echo "Producto actualizado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al actualizar producto: " . $e->getMessage();
        }
    } else {
        // El producto no existe
        echo "El producto no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para


/// Función para eliminar un producto
function eliminarProducto($id_producto)
{
    global $mysql;

    if (existeProducto($id_producto)) {
        // Construir la consulta SQL para eliminar el producto
        $query = "UPDATE productos SET estado = 'inactivo' WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $id_producto);

        try {
            $stmt->execute();
            echo "Producto eliminado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al eliminar producto: " . $e->getMessage();
        }
    } else {
        // El producto no existe
        echo "El producto no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}