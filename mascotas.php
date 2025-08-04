<?php
require_once("./conexion.php");
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // error_log("data: " . print_r($_POST, true) . " \n", 3, "error.log");
    // Verificar la acción a realizar
    if (isset($_POST["accion"])) {
        $accion = $_POST["accion"];

        // Ejecutar la acción correspondiente
        switch ($accion) {
            case "listar":
                // Función para listar mascotas
                listarMascotas();
                break;
            case "crear":
                // Función para crear una mascotas
                crearMascota();
                break;
            case "ver":
                // Función para ver una mascota
                verMascota($_POST["id_mascota"]);
                break;
            case "editar":
                // Función para actualizar una mascota
                actualizarMascota();
                break;
            case "eliminar":
                // Función para eliminar una mascota
                eliminarMascota($_POST["id_mascota"]);
                break;
            default:
                echo "Acción no válida";
        }
    } else {
        echo "No se proporcionó ninguna acción";
    }
}

function existeMascota($id_mascota)
{
    global $mysql;

    // Consultar la existencia de la mascota
    $query = "SELECT id FROM mascotas WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_mascota);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró la mascota
    if ($result->num_rows == 1) {
        return true;
    } else {
        return false;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
}

// Función para listar mascotas
function listarMascotas()
{
    global $mysql;

    $estado = isset($_POST['estado']) ? $_POST['estado'] : "";

    // Realizar la consulta a la base de datos
    if ($estado != "") {
        $query = "SELECT * FROM tipos_productos WHERE estado = '$estado'";
        $query = "SELECT m.id, m.nombre_mascota, 
                m.fecha_nacimiento, 
                -- FLOOR(DATEDIFF(CURDATE(), m.fecha_nacimiento) / 365.25) AS edad, 
                CONCAT(u.nombre, ' ', u.apellido) as dueño, a.nombre_raza, b.nombre_animal, m.estado
                FROM mascotas m
                LEFT JOIN razas a
                ON m.id_raza = a.id
                LEFT JOIN tipo_animal b 
                ON a.id_tipo_animal = b.id
                LEFT JOIN clientes c
                ON m.id_cliente = c.id
                LEFT JOIN usuarios u
                ON c.id_usuario = u.id
                WHERE m.estado = '$estado'";
    } else {
        $query = "SELECT m.id, m.nombre_mascota, 
                m.fecha_nacimiento, 
                -- FLOOR(DATEDIFF(CURDATE(), m.fecha_nacimiento) / 365.25) AS edad, 
                CONCAT(u.nombre, ' ', u.apellido) as dueño, a.nombre_raza, b.nombre_animal, m.estado
                FROM mascotas m
                LEFT JOIN razas a
                ON m.id_raza = a.id
                LEFT JOIN tipo_animal b 
                ON a.id_tipo_animal = b.id
                LEFT JOIN clientes c
                ON m.id_cliente = c.id
                LEFT JOIN usuarios u
                ON c.id_usuario = u.id";
    }

    $result = $mysql->query($query);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Objeto JSON para almacenar los datos de las mascotas
        $response = new stdClass();
        $response->status = "OK";
        $response->mascotas = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = $result->fetch_assoc()) {
            $response->mascotas[] = $fila;
        }

        // Convertir el array en formato JSON
        echo json_encode($response);
    } else {
        // Si no hay resultados, devolver un JSON con un mensaje
        echo json_encode(array("mensaje" => "No se encontraron mascotas"));
    }

    // Cerrar la conexión a la base de datos
    $mysql->close();
}

// Función para crear una mascota
function crearMascota()
{
    global $mysql;

    // Obtener datos del formulario
    $nombre_mascota = $_POST['nombre_mascota'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $id_raza = $_POST['id_raza'];
    $id_cliente = $_POST['id_cliente'];

    // Insertar datos en la tabla de mascotas
    $query = "INSERT INTO mascotas (nombre_mascota, fecha_nacimiento, id_raza, id_cliente) VALUES (?, ?, ?, ?)";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("ssii", $nombre_mascota, $fecha_nacimiento, $id_raza, $id_cliente);

    try {
        $stmt->execute();

        echo "Mascota creada exitosamente";
    } catch (mysqli_sql_exception $e) {
        echo "Error al crear mascota: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para ver una mascota
function verMascota($id_mascota)
{
    global $mysql;

    // Consultar la información de la mascota
    $query = "SELECT m.id, m.nombre_mascota, 
                FLOOR(DATEDIFF(CURDATE(), m.fecha_nacimiento) / 365.25) AS edad, 
                CONCAT(u.nombre, ' ', u.apellido) as dueño, a.nombre_raza, b.nombre_animal 
                FROM mascotas m
                LEFT JOIN razas a
                ON m.id_raza = a.id
                LEFT JOIN tipo_animal b 
                ON a.id_tipo_animal = b.id
                LEFT JOIN clientes c
                ON m.id_cliente = c.id
                LEFT JOIN usuarios u
                ON c.id_usuario = u.id
                WHERE m.id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_mascota);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró la mascota
    if ($result->num_rows == 1) {
        // La mascota existe, obtener sus datos
        $mascota = $result->fetch_assoc();

        // Devolver los datos de la mascota en formato JSON
        echo json_encode($mascota);
    } else {
        // La mascota no existe
        echo json_encode(array("mensaje" => "La mascota no existe"));
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para actualizar una mascota
function actualizarMascota()
{
    global $mysql;

    // Obtener datos del formulario
    $id_mascota = $_POST['id_mascota'];
    $nombre_mascota = $_POST['nombre_mascota'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $id_raza = $_POST['id_raza'];
    $id_cliente = $_POST['id_cliente'];
    $estado = $_POST['estado'];

    if (existeMascota($id_mascota)) {
        // Construir la consulta SQL para actualizar la mascota
        $query = "UPDATE mascotas SET nombre_mascota = ?, fecha_nacimiento = ?, id_raza = ?, id_cliente = ?, estado = ? WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("ssiisi", $nombre_mascota, $fecha_nacimiento, $id_raza, $id_cliente, $estado, $id_mascota);

        try {
            $stmt->execute();
            echo "Mascota actualizada exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al actualizar mascota: " . $e->getMessage();
        }
    } else {
        // La mascota no existe
        echo "La mascota no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para eliminar una mascota
function eliminarMascota($id_mascota)
{
    global $mysql;

    if (existeMascota($id_mascota)) {
        // Construir la consulta SQL para eliminar la mascota
        $query = "UPDATE mascotas SET estado = 'inactivo' WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $id_mascota);

        try {
            $stmt->execute();
            echo "Mascota eliminada exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al eliminar mascota: " . $e->getMessage();
        }
    } else {
        // La mascota no existe
        echo "La mascota no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}
