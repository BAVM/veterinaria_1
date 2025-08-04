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
                // Función para crear un usuario
                listarUsuarios();
                break;
            case "crear":
                // Función para crear un usuario
                crearUsuario();
                break;
            case "ver":
                // Función para crear un usuario
                verUsuario($_POST["id_usuario"]);
                break;
            case "editar":
                // Función para actualizar un usuario
                actualizarUsuario();
                break;
            case "eliminar":
                // Función para eliminar un usuario
                eliminarUsuario($_POST["id_usuario"]);
                break;
            default:
                echo "Acción no válida";
        }
    } else {
        echo "No se proporcionó ninguna acción";
    }
}

// Función para crear un usuario
function crearUsuario()
{
    global $mysql;
    //error_log("isrequest: " . $_POST . " \n",3, "error.log"); IMPRIMIR DATOS
    //error_log("data: " . print_r($_POST, true) . " \n",3, "error.log"); IMPRIMIR ARRAY
    // Obtener datos del formulario
    $contraseña = $_POST['contraseña'];
    $correo = $_POST['correo'];
    $id_perfil = isset($_POST['id_perfil']) ? $_POST['id_perfil'] : 1;
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : "";
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : "";
    $identificacion = isset($_POST['identificacion']) ? $_POST['identificacion'] : "";
    $estado_activo = 'activo';

    if (!isset($_POST['nombre_usuario'])) {

        $nombre_array = explode(' ', $nombre);
        $apellido_array = explode(' ', $apellido);

        $nombre_usuario = strtolower(substr($nombre_array[0], 0, 1) . $apellido_array[0]);
    } else {
        $nombre_usuario  = $_POST['nombre_usuario'];
    }
    //error_log("isrequest: " . $nombre_usuario . " \n",3, "error.log");
    // Insertar datos en la tabla de usuarios
    $query = "INSERT INTO usuarios (nombre, apellido, nombre_usuario, contraseña, correo, id_perfil, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysql->prepare($query);
    
    $stmt->bind_param("sssssis", $nombre, $apellido, $nombre_usuario, $contraseña, $correo, $id_perfil, $estado_activo);
    // print_r($query);
    
    try {
        $stmt->execute();
        // error_log("isrequest: " . $stmt . " \n",3, "error.log");
        // Si el usuario es un cliente, también lo registramos en la tabla de clientes
        if ($id_perfil == 2) { // asumimos que el perfil de cliente es el 2
           
            // Insertar datos en la tabla de clientes
            $query = "INSERT INTO clientes ( direccion, telefono, identificacion, id_usuario) VALUES ( ?, ?, ?, LAST_INSERT_ID())";
            $stmtCliente = $mysql->prepare($query);
            $stmtCliente->bind_param("ssi", $direccion, $telefono, $identificacion);
            $stmtCliente->execute();
            echo "Cliente registrado exitosamente";
        } else {
            echo "Usuario dado de alta";
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error al registrar usuario: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

function listarUsuarios()  {
    global $mysql;
    // Realizar la consulta a la base de datos
    $query = "SELECT * FROM usuarios WHERE estado = 'activo'";
    $stmt = $mysql->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        
        // Objeto JSON para almacenar los datos de los usuarios
        $response = new stdClass();
        $response->status = "OK";
        $response->usuarios = array();

        // Iterar sobre los resultados y almacenarlos en el objeto JSON
        while ($fila = $result->fetch_assoc()) {
            $usuario = new stdClass();
            $usuario->id = $fila["id"];
            $usuario->nombre = $fila["nombre"];
            $usuario->apellido = $fila["apellido"];
            $usuario->nombre_usuario = $fila["nombre_usuario"];
            $usuario->contraseña = $fila["contraseña"];
            $usuario->correo = $fila["correo"];
            $usuario->estado = $fila["estado"];
            $usuario->id_perfil = ($fila["id_perfil"] == 1) ? "Administrador" : "Usuario";

            $query = "SELECT * FROM clientes WHERE id_usuario = $usuario->id";
            $stmt = $mysql->prepare($query);
            $stmt->execute();
            $result_cliente = $stmt->get_result();

            // Verificar si hay resultados
            if ($result_cliente->num_rows == 1) {
                // Si el usuario es un cliente, agregar los datos de cliente a la información del usuario
                $cliente = $result_cliente->fetch_assoc();
                $usuario->direccion = isset($cliente['direccion']) ? $cliente['direccion'] : "";;
                $usuario->telefono = isset($cliente['telefono']) ? $cliente['telefono'] : "";
                $usuario->identificacion = isset($cliente['identificacion']) ? $cliente['identificacion'] : "";
            }else {
                $usuario->direccion = "";
                $usuario->telefono = "";
                $usuario->identificacion = "";
            }

            $response->usuarios[] = $usuario;

        }
        // error_log("data: " . print_r($response, true) . " \n",3, "error.log");
        // Devolver el objeto JSON
        echo json_encode($response);
        echo "exito";

    } else {
        // Si no hay resultados, devolver un JSON con un mensaje
        echo json_encode(array("mensaje" => "No se encontraron usuarios"));
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

function verUsuario($id_usuario)
{
    global $mysql;

    // Consultar la información del usuario
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el usuario
    if ($result->num_rows == 1) {
        // El usuario existe, obtener sus datos
        $usuario = $result->fetch_assoc();

        // Consultar si el usuario es un cliente
        $query_cliente = "SELECT * FROM clientes WHERE id_usuario = ?";
        $stmt_cliente = $mysql->prepare($query_cliente);
        $stmt_cliente->bind_param("i", $id_usuario);
        $stmt_cliente->execute();
        $result_cliente = $stmt_cliente->get_result();

        if ($result_cliente->num_rows == 1) {
            // Si el usuario es un cliente, agregar los datos de cliente a la información del usuario
            $cliente = $result_cliente->fetch_assoc();
            $usuario['direccion'] = $cliente['direccion'];
            $usuario['telefono'] = $cliente['telefono'];
            $usuario['identificacion'] = $cliente['identificacion'];
        }

        // Devolver los datos del usuario en formato JSON
        echo json_encode($usuario);
        
    } else {
        // El usuario no existe
        echo json_encode(array("mensaje" => "El usuario no existe"));
    }
}

function actualizarUsuario()
{
    global $mysql;

    // Obtener datos del formulario
    $id_usuario = $_POST['id_usuario'];
    $contraseña = $_POST['contraseña'];
    $correo = $_POST['correo'];
    $id_perfil = $_POST['id_perfil'];
    $estado = $_POST['estado'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : "";
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : "";
    $identificacion = isset($_POST['identificacion']) ? $_POST['identificacion'] : "";

    // Comprobar si el usuario existe
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        // El usuario existe, proceder con la actualización
        // Construir la consulta SQL para actualizar el usuario
        $query = "UPDATE usuarios SET nombre = ?, apellido = ?, contraseña = ?, correo = ?, id_perfil = ?, estado = ? WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("ssssisi", $nombre, $apellido, $contraseña, $correo, $id_perfil, $estado, $id_usuario);
        $stmt->execute();

        // Consultar si el usuario es un cliente
        $query_cliente = "SELECT * FROM clientes WHERE id_usuario = ?";
        $stmt_cliente = $mysql->prepare($query_cliente);
        $stmt_cliente->bind_param("i", $id_usuario);
        $stmt_cliente->execute();
        $result_cliente = $stmt_cliente->get_result();

        if ($result_cliente->num_rows == 1) {
            // El usuario es un cliente, actualizar los datos en la tabla de clientes
            $query_update_cliente = "UPDATE clientes SET direccion = ?, telefono = ?, identificacion = ? WHERE id_usuario = ?";
            $stmt_update_cliente = $mysql->prepare($query_update_cliente);
            $stmt_update_cliente->bind_param("sssi", $direccion, $telefono, $identificacion, $id_usuario);
            $stmt_update_cliente->execute();

        } else if ($id_perfil == 2) {

            // El usuario no es un cliente, insertar los datos en la tabla de clientes
            $query_insert_cliente = "INSERT INTO clientes (direccion, telefono, identificacion, id_usuario) VALUES (?, ?, ?, ?)";
            $stmt_insert_cliente = $mysql->prepare($query_insert_cliente);
            $stmt_insert_cliente->bind_param("sssi", $direccion, $telefono, $identificacion, $id_usuario);
            $stmt_insert_cliente->execute();
        }

        echo "Usuario actualizado exitosamente";
        
    } else {
        // El usuario no existe
        echo "El usuario no existe";
    }
}

function eliminarUsuario($id_usuario)
{
    global $mysql;
    $estado_activo= 'activo';

    // Consultar si el usuario existe y está activo
    $query = "SELECT * FROM usuarios WHERE id = ? AND estado = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("is", $id_usuario, $estado_activo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el usuario y está activo
    if ($result->num_rows > 0) {
        // Cambiar el estado del usuario a "inactivo"
        $query_update = "UPDATE usuarios SET estado = 'inactivo' WHERE id = ?";
        $stmt_update = $mysql->prepare($query_update);
        $stmt_update->bind_param("i", $id_usuario);
        $stmt_update->execute();

        // Verificar si se realizó la actualización correctamente
        if ($stmt_update->affected_rows > 0) {
            echo "Usuario eliminado exitosamente cambiando su estado a 'inactivo'";
        } else {
            echo "Error al eliminar usuario";
        }
    } else {
        echo "El usuario no existe o ya está inactivo";
    }
}





