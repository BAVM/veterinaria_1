<?php
require_once("./conexion.php");
header('Content-Type: application/json; charset=utf-8');

// Incluir la biblioteca PHPMailer
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';
require_once ('./TCPDF-main/tcpdf.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // error_log("data: " . print_r($_POST, true) . " \n", 3, "error.log");
    // Verificar la acción a realizar
    if (isset($_POST["accion"])) {
        $accion = $_POST["accion"];

        // Ejecutar la acción correspondiente
        switch ($accion) {
            case "listar":
                // Función para listar pedidos
                listarPedidos();
                break;
            case "crear":
                // Función para crear un pedido
                crearPedido();
                break;
            case "ver":
                // Función para ver un pedido
                verPedido($_POST["id_pedido"]);
                break;
            case "editar":
                // Función para actualizar un pedido
                actualizarPedido();
                break;
            case "eliminar":
                // Función para eliminar un pedido
                eliminarPedido($_POST["id_pedido"]);
                break;
            default:
                echo "Acción no válida";
        }
    } else {
        echo "No se proporcionó ninguna acción";
    }
}

function existePedido($id_pedido)
{
    global $mysql;

    // Consultar la existencia del pedido
    $query = "SELECT id FROM pedidos WHERE id = ?";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el pedido
    if ($result->num_rows == 1) {
        return true;
    } else {
        return false;
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
}

function listarPedidos()
{
    global $mysql;

    // Realizar la consulta a la base de datos
    $query = "SELECT p.id, p.fecha_pedido, 
                CONCAT(u.nombre, ' ', u.apellido) AS cliente, 
                p.direccion_envio,
                p.valor_total_pedido, 
                mp.nombre_metodo AS metodo_pago, 
                p.fecha_envio, p.fecha_entrega, p.observaciones,
                p.estado,
                pr.id AS producto_id, pr.nombre_producto AS producto_nombre, pr.precio AS producto_precio
                FROM pedidos p 
                LEFT JOIN clientes c ON p.id_cliente = c.id
                LEFT JOIN usuarios u ON c.id_usuario = u.id
                LEFT JOIN metodos_pago mp ON p.id_metodo_pago = mp.id
                LEFT JOIN pedido_producto pp ON p.id = pp.id_pedido
                LEFT JOIN productos pr ON pp.id_producto = pr.id";
                
    $result = $mysql->query($query);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Array asociativo para almacenar los pedidos con sus productos
        $pedidos_con_productos = array();

        // Recorrer los resultados y almacenarlos en el array asociativo
        while ($fila = $result->fetch_assoc()) {
            $pedido_id = $fila['id'];

            // Verificar si el pedido ya existe en el array
            if (!isset($pedidos_con_productos[$pedido_id])) {
                // Si no existe, crear un nuevo objeto de pedido
                $pedido = new stdClass();
                $pedido->id = $pedido_id;
                $pedido->fecha_pedido = $fila['fecha_pedido'];
                $pedido->estado = $fila['estado'];
                $pedido->valor_total_pedido = $fila['valor_total_pedido'];
                $pedido->cliente = $fila['cliente'];
                $pedido->metodo_pago = $fila['metodo_pago'];
                $pedido->direccion_envio = $fila['direccion_envio'];
                $pedido->fecha_envio = $fila['fecha_envio'];
                $pedido->fecha_entrega = $fila['fecha_entrega'];
                $pedido->observaciones = $fila['observaciones'];
                $pedido->productos = array(); // Inicializar array de productos
            }

            // Verificar si hay información de productos en el resultado
            if (!is_null($fila['producto_id'])) {
                // Crear un objeto de producto y añadirlo al array de productos del pedido
                $producto = new stdClass();
                $producto->id = $fila['producto_id'];
                $producto->nombre = $fila['producto_nombre'];
                $producto->precio = $fila['producto_precio'];
                $pedido->productos[] = $producto;
            }

            // Agregar o actualizar el pedido en el array asociativo
            $pedidos_con_productos[$pedido_id] = $pedido;
        }

        // Convertir el array en formato JSON y devolverlo como respuesta
        $response = new stdClass();
        $response->status = "OK";
        $response->pedidos = array_values($pedidos_con_productos); // Obtener solo los valores del array asociativo
        echo json_encode($response);
    } else {
        // Si no hay resultados, devolver un JSON con un mensaje
        echo json_encode(array("mensaje" => "No se encontraron pedidos"));
    }

    // Cerrar la conexión a la base de datos
    $mysql->close();
}



// Función para listar pedidos
function listarPedidos1()
{
    global $mysql;

    // Realizar la consulta a la base de datos
    $query = "SELECT p.id, p.fecha_pedido,
                CONCAT(u.nombre, ' ', u.apellido) AS cliente, 
                p.direccion_envio, 
                -- pr.nombre_producto, 
                p.valor_total_pedido,
                mp.nombre_metodo AS metodo_pago, p.fecha_envio, p.fecha_entrega, p.observaciones, p.estado
                FROM pedidos p 
                LEFT JOIN clientes c
                ON p.id_cliente = c.id
                LEFT JOIN usuarios u
                ON c.id_usuario = u.id
                LEFT JOIN metodos_pago mp
                ON p.id_metodo_pago = mp.id";
    // LEFT JOIN productos pr
    // ON pr.id = p.id_producto";
    $result = $mysql->query($query);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        // Objeto JSON para almacenar los datos de los pedidos
        $response = new stdClass();
        $response->status = "OK";
        $response->pedidos = array();

        // Recorrer los resultados y almacenarlos en el array
        while ($fila = $result->fetch_assoc()) {
            $response->pedidos[] = $fila;
        }

        // Convertir el array en formato JSON
        echo json_encode($response);
    } else {
        // Si no hay resultados, devolver un JSON con un mensaje
        echo json_encode(array("mensaje" => "No se encontraron pedidos"));
    }

    // Cerrar la conexión a la base de datos
    $mysql->close();
}

// Función para crear un pedido
function crearPedido()
{
    global $mysql;

    // Obtener datos del formulario
    $id_cliente = $_POST['id_cliente'];
    $fecha_pedido = $_POST['fecha_pedido'];
    $estado = $_POST['estado'];
    $valor_total_pedido = $_POST['valor_total_pedido'];
    $id_metodo_pago = $_POST['id_metodo_pago'];
    $direccion_envio = $_POST['direccion_envio'];
    $fecha_envio = isset($_POST['fecha_envio']) ? $_POST['fecha_envio'] : null;
    $fecha_entrega = isset($_POST['fecha_entrega']) ? $_POST['fecha_entrega'] : null;
    $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : null;
    $productos = $_POST['productos'];
    // $cantidad = $_POST['cantidad'];

    // Insertar datos en la tabla de pedidos
    $query = "INSERT INTO pedidos (id_cliente, fecha_pedido, estado, valor_total_pedido, id_metodo_pago, direccion_envio, fecha_envio, fecha_entrega, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysql->prepare($query);
    $stmt->bind_param("isssissss", $id_cliente, $fecha_pedido, $estado, $valor_total_pedido, $id_metodo_pago, $direccion_envio, $fecha_envio, $fecha_entrega, $observaciones);
    try {

        $stmt->execute();
        $pedido_id = $stmt->insert_id;
        
        // Insertar productos en la tabla de detalles del pedido
        foreach ($productos as $producto) {
            $producto_id = $producto['id'];
            $query = "INSERT INTO pedido_producto (id_pedido, id_producto) VALUES (?, ?)";
            $stmt = $mysql->prepare($query);
            $stmt->bind_param("ii", $pedido_id, $producto_id);
            $stmt->execute();
        }
        echo "Pedido creado exitosamente";
        
        $query = "SELECT u.*
                FROM pedidos p 
                LEFT JOIN clientes c
                ON p.id_cliente = c.id
                LEFT JOIN usuarios u
                ON c.id_usuario = u.id
                where p.id= LAST_INSERT_ID()";
        $result = $mysql->query($query);

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            $cliente = $result->fetch_assoc();


        // Crear una nueva instancia de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                                    
        // Establecer las propiedades del documento
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Autor del PDF');
        $pdf->SetTitle('Título del PDF');
        $pdf->SetSubject('Sujeto del PDF');
        $pdf->SetKeywords('Palabras clave del PDF');
        
        // Agregar una página al documento
        $pdf->AddPage();
        
        // Agregar contenido al PDF
        $pdf->SetFont('times', '', 12);
        $pdf->Write(0, 'MascotasApp', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Información del Pedido:', '', 0, 'L', true, 0, false, false, 0);
        
        // Ejemplo de cómo agregar información del pedido
        $pdf->Ln();
        $pdf->Write(0, 'Hola ' . $cliente['nombre'] .'', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, '','', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Id de Pedido # ' . $pedido_id .'', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, '','', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Fecha de pedido: ' . $fecha_pedido .'', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Cliente: ' . $cliente['nombre'] . ' ' . $cliente['apellido'] . '', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Dirección de envío: ' . $direccion_envio . '', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, '','', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Productos en el pedido:','', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, '','', 0, 'L', true, 0, false, false, 0);

        foreach ($productos as $producto) {
            $id_producto = $producto['id'];
            //$cantidad = $producto['cantidad'];

            // Obtener información del producto
            $queryProducto = "SELECT * FROM productos WHERE id = $id_producto";
            $productoInfo = $mysql->query($queryProducto);

            // Verificar si se encontró el producto
            if ($productoInfo->num_rows > 0) {
                $productoData = $productoInfo->fetch_assoc();
                // Agregar detalles del producto al cuerpo del correo
                $pdf->Write(0, 'Producto: ' . $productoData['nombre_producto'] . '', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'Precio: ' . $productoData['precio'] . '', '', 0, 'L', true, 0, false, false, 0);
            } else {
                // Si no hay resultados, devolver un JSON con un mensaje
                echo json_encode(array("mensaje" => "No se enconto producto"));
            }
        }


        
        $pdf->Write(0, 'Subtotal: ' . ($valor_total_pedido - ($valor_total_pedido * 0.19)) . '', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'IVA: ' . $valor_total_pedido * 0.19 .  '', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Total a Pagar: ' . $valor_total_pedido .  '', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Estado del pedido: ' . $estado . '', '', 0, 'L', true, 0, false, false, 0);
        
        $pdf->Write(0, 'Fecha Probable de Entrega: ' . $fecha_entrega . '', '', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, '','', 0, 'L', true, 0, false, false, 0);
        $pdf->Write(0, 'Esperamos verte pronto!','', 0, 'L', true, 0, false, false, 0);

        // Cerrar el PDF y generar el archivo
        $pdf->Output($_SERVER['DOCUMENT_ROOT'] . 'veterinaria/output.pdf', 'F');




            // Enviar correo electrónico con la informacion del pedido
            $mail = new PHPMailer(true);
            try {
                // Configurar el servidor SMTP
                //To load the French version
                $mail->CharSet = 'UTF-8';
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'eddvallejo08@gmail.com';
                $mail->Password = 'socndbwsulcjmzzc';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Configurar el remitente y el destinatario
                $mail->setFrom('eddvallejo@hotmail.com', 'Veterinaria Online');
                $mail->addAddress($cliente['correo']);

                $mail->addAttachment('./output.pdf');

                // Configurar el contenido del correo electrónico
                $mail->isHTML(true);
                $mail->Subject = 'Haz realizado una Nueva Compra';
                // Construir el cuerpo del correo electrónico
                $body = '<h1>Hola ' . $cliente['nombre'] . ', estos son los detalles de tu pedido:</h1>';
                $body .= '<p>Pedido #' . $pedido_id . '</p>';
                $body .= '<p>Fecha de pedido: ' . $fecha_pedido . '</p>';
                $body .= '<p>Cliente: ' . $cliente['nombre'] . ' ' . $cliente['apellido'] . '</p>';
                $body .= '<p>Dirección de envío: ' . $direccion_envio . '</p>';

                    // Configurar el remitente y el destinatario
                    $mail->setFrom('eddvallejo@hotmail.com', 'Veterinaria Online');
                    $mail->addAddress($cliente['correo']);
                    // Envio del PDF
                    //$mail->addAttachment($pdfPath, 'pedido.pdf', 'base64', 'application/pdf');

                // Obtener información de cada producto en el pedido
                $body .= '<h2>Productos en el pedido:</h2>';
                foreach ($productos as $producto) {
                    $id_producto = $producto['id'];
                    //$cantidad = $producto['cantidad'];

                    // Obtener información del producto
                    $queryProducto = "SELECT * FROM productos WHERE id = $id_producto";
                    $productoInfo = $mysql->query($queryProducto);

                    // Verificar si se encontró el producto
                    if ($productoInfo->num_rows > 0) {
                        $productoData = $productoInfo->fetch_assoc();
                        // Agregar detalles del producto al cuerpo del correo
                        $body .= '<p>Producto: ' . $productoData['nombre_producto'] . '</p>';
                        $body .= '<p>Precio: ' . $productoData['precio'] . '</p>';
                    } else {
                        // Si no hay resultados, devolver un JSON con un mensaje
                        echo json_encode(array("mensaje" => "No se enconto producto"));
                    }
                }

                $body .= '<p>Subtotal: ' . ($valor_total_pedido - ($valor_total_pedido * 0.19)) . '</p>';
                $body .= '<p>IVA: ' . $valor_total_pedido * 0.19 . '</p>';
                $body .= '<p>Total a Pagar: ' . $valor_total_pedido . '</p>';
                $body .= '<p>Estado del pedido: ' . $estado . '</p>';
                $body .= '<p>Fecha Probable de Entrega: ' . $fecha_entrega . '</p>';
                $body .= '<p>¡Esperamos verte pronto!</p>';
                $body .= '<img src="cid:veterinaria_logo" alt="Logo de nuestra veterinaria" style="max-width: 100%;">';
                // Incorporar la imagen en el correo electrónico como recurso embebido (CID)
                $ruta_imagen = './logo.png'; // Ruta de la imagen
                $mail->AddEmbeddedImage($ruta_imagen, 'veterinaria_logo');

                $mail->Body = $body;
                // Enviar el correo electrónico
                $mail->send();
                echo ' Info del Pedido enviado al correo';
            } catch (Exception $e) {
                echo "Error al enviar el correo electrónico: {$mail->ErrorInfo}";
            }
        } else {
            // Si no hay resultados, devolver un JSON con un mensaje
            echo json_encode(array("mensaje" => "No se encontraron pedidos"));
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error al crear pedido: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para ver un pedido
function verPedido($id_pedido)
{
    global $mysql;

    // Consultar la información del pedido
    $query = "SELECT p.id, p.fecha_pedido,
                CONCAT(u.nombre, ' ', u.apellido) AS cliente, 
                p.direccion_envio, pr.nombre_producto, p.valor_total_pedido,
                mp.nombre_metodo AS metodo_pago, p.fecha_envio, p.fecha_entrega, p.estado
                FROM pedidos p 
                LEFT JOIN clientes c
                ON p.id_cliente = c.id
                LEFT JOIN usuarios u
                ON c.id_usuario = u.id
                LEFT JOIN metodos_pago mp
                ON p.id_metodo_pago = mp.id 
                LEFT JOIN productos pr
                ON pr.id = p.id_producto
                WHERE p.id = ?";

    $stmt = $mysql->prepare($query);
    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró el pedido
    if ($result->num_rows == 1) {
        // El pedido existe, obtener sus datos
        $pedido = $result->fetch_assoc();

        // Devolver los datos del pedido en formato JSON
        echo json_encode($pedido);
    } else {
        // El pedido no existe
        echo json_encode(array("mensaje" => "El pedido no existe"));
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para actualizar un pedido
function actualizarPedido()
{
    global $mysql;

    // Obtener datos del formulario
    $id_pedido = $_POST['id_pedido'];
    $id_cliente = $_POST['id_cliente'];
    $fecha_pedido = $_POST['fecha_pedido'];
    $estado = $_POST['estado'];
    $valor_total_pedido = $_POST['valor_total_pedido'];
    $id_metodo_pago = $_POST['id_metodo_pago'];
    $direccion_envio = isset($_POST['direccion_envio']) ? $_POST['direccion_envio'] : null;
    $fecha_envio = isset($_POST['fecha_envio']) ? $_POST['fecha_envio'] : null;
    $fecha_entrega = isset($_POST['fecha_entrega']) ? $_POST['fecha_entrega'] : null;
    $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : null;
    $productos = $_POST['productos'];

    if (existePedido($id_pedido)) {

        // Construir la consulta SQL para actualizar el pedido
        $query = "UPDATE pedidos 
                SET id_cliente = ?, 
                    fecha_pedido = ?, 
                    estado = ?, 
                    valor_total_pedido = ?, 
                    id_metodo_pago = ?, 
                    direccion_envio = ?, 
                    fecha_envio = ?, 
                    fecha_entrega = ?, 
                    observaciones = ?
                WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("isssissssi", $id_cliente, $fecha_pedido, $estado, $valor_total_pedido, $id_metodo_pago, $direccion_envio, $fecha_envio, $fecha_entrega, $observaciones, $id_pedido);
        try {

            $stmt->execute();

            // Eliminar los productos asociados al pedido para luego insertar los nuevos
            $queryDelete = "DELETE FROM pedido_producto WHERE id_pedido = ?";
            $stmtDelete = $mysql->prepare($queryDelete);
            $stmtDelete->bind_param("i", $id_pedido);
            $stmtDelete->execute();
            $stmtDelete->close();

            // Insertar los nuevos productos en la tabla de detalles del pedido
            foreach ($productos as $producto) {
                $producto_id = $producto['id'];
                $queryInsert = "INSERT INTO pedido_producto (id_pedido, id_producto) VALUES (?, ?)";
                $stmtInsert = $mysql->prepare($queryInsert);
                $stmtInsert->bind_param("ii", $id_pedido, $producto_id);
                $stmtInsert->execute();
                $stmtInsert->close();
            }

            echo "Pedido actualizado exitosamente";

            $query = "SELECT u.*
                FROM pedidos p 
                LEFT JOIN clientes c
                ON p.id_cliente = c.id
                LEFT JOIN usuarios u
                ON c.id_usuario = u.id
                where p.id= $id_pedido";
            $result = $mysql->query($query);

            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                $cliente = $result->fetch_assoc();

                


                // Crear una nueva instancia de TCPDF
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                            
                // Establecer las propiedades del documento
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Autor del PDF');
                $pdf->SetTitle('Título del PDF');
                $pdf->SetSubject('Sujeto del PDF');
                $pdf->SetKeywords('Palabras clave del PDF');
                
                // Agregar una página al documento
                $pdf->AddPage();
                
                // Agregar contenido al PDF
                $pdf->SetFont('times', '', 12);
                $pdf->Write(0, 'MascotasApp', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'Información del Pedido:', '', 0, 'L', true, 0, false, false, 0);
                
                // Ejemplo de cómo agregar información del pedido
                $pdf->Ln();
                $pdf->Write(0, 'Hola ' . $cliente['nombre'] .'', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, '','', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'Id de Pedido # ' . $id_pedido .'', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, '','', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'Fecha de pedido: ' . $fecha_pedido .'', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'Cliente: ' . $cliente['nombre'] . ' ' . $cliente['apellido'] . '', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'Dirección de envío: ' . $direccion_envio . '', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, '','', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'Productos en el pedido:','', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, '','', 0, 'L', true, 0, false, false, 0);

                foreach ($productos as $producto) {
                    $id_producto = $producto['id'];
                    //$cantidad = $producto['cantidad'];

                    // Obtener información del producto
                    $queryProducto = "SELECT * FROM productos WHERE id = $id_producto";
                    $productoInfo = $mysql->query($queryProducto);

                    // Verificar si se encontró el producto
                    if ($productoInfo->num_rows > 0) {
                        $productoData = $productoInfo->fetch_assoc();
                        // Agregar detalles del producto al cuerpo del correo
                        $pdf->Write(0, 'Producto: ' . $productoData['nombre_producto'] . '', '', 0, 'L', true, 0, false, false, 0);
                        $pdf->Write(0, 'Precio: ' . $productoData['precio'] . '', '', 0, 'L', true, 0, false, false, 0);
                    } else {
                        // Si no hay resultados, devolver un JSON con un mensaje
                        echo json_encode(array("mensaje" => "No se enconto producto"));
                    }
                }


                
                $pdf->Write(0, 'Subtotal: ' . ($valor_total_pedido - ($valor_total_pedido * 0.19)) . '', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'IVA: ' . $valor_total_pedido * 0.19 .  '', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'Total a Pagar: ' . $valor_total_pedido .  '', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'Estado del pedido: ' . $estado . '', '', 0, 'L', true, 0, false, false, 0);
                
                $pdf->Write(0, 'Fecha Probable de Entrega: ' . $fecha_entrega . '', '', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, '','', 0, 'L', true, 0, false, false, 0);
                $pdf->Write(0, 'Esperamos verte pronto!','', 0, 'L', true, 0, false, false, 0);

                // Cerrar el PDF y generar el archivo
                $pdf->Output($_SERVER['DOCUMENT_ROOT'] . 'veterinaria/output.pdf', 'F');



                // Enviar correo electrónico con la informacion del pedido
                $mail = new PHPMailer(true);
                try {
                    // Configurar el servidor SMTP
                    //To load the French version
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'eddvallejo08@gmail.com';
                    $mail->Password = 'socndbwsulcjmzzc';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Configurar el remitente y el destinatario
                    $mail->setFrom('eddvallejo@hotmail.com', 'Veterinaria Online');
                    $mail->addAddress($cliente['correo']);

                    $mail->addAttachment('./output.pdf');

                    // Configurar el contenido del correo electrónico
                    $mail->isHTML(true);
                    $mail->Subject = 'Actualizacion de Pedido #' . $id_pedido;
                    // Construir el cuerpo del correo electrónico
                    $body = '<h1>Hola ' . $cliente['nombre'] . ', estos son los detalles de tu pedido:</h1>';
                    $body .= '<p>Pedido #' . $id_pedido . '</p>';
                    $body .= '<p>Fecha de pedido: ' . $fecha_pedido . '</p>';
                    $body .= '<p>Cliente: ' . $cliente['nombre'] . ' ' . $cliente['apellido'] . '</p>';
                    $body .= '<p>Dirección de envío: ' . $direccion_envio . '</p>';


                    // Obtener información de cada producto en el pedido
                    $body .= '<h2>Productos en el pedido:</h2>';
                    foreach ($productos as $producto) {
                        $id_producto = $producto['id'];
                        //$cantidad = $producto['cantidad'];

                        // Obtener información del producto
                        $queryProducto = "SELECT * FROM productos WHERE id = $id_producto";
                        $productoInfo = $mysql->query($queryProducto);

                        // Verificar si se encontró el producto
                        if ($productoInfo->num_rows > 0) {
                            $productoData = $productoInfo->fetch_assoc();
                            // Agregar detalles del producto al cuerpo del correo
                            $body .= '<p>Producto: ' . $productoData['nombre_producto'] . '</p>';
                            $body .= '<p>Precio: ' . $productoData['precio'] . '</p>';
                        } else {
                            // Si no hay resultados, devolver un JSON con un mensaje
                            echo json_encode(array("mensaje" => "No se enconto producto"));
                        }
                    }

                    $body .= '<p>Subtotal: ' . ($valor_total_pedido - ($valor_total_pedido * 0.19)) . '</p>';
                    $body .= '<p>IVA: ' . $valor_total_pedido * 0.19 . '</p>';
                    $body .= '<p>Total a Pagar: ' . $valor_total_pedido . '</p>';
                    $body .= '<p>Estado del pedido: ' . $estado . '</p>';
                    $body .= '<p>Fecha Probable de Entrega: ' . $fecha_entrega . '</p>';
                    $body .= '<p>¡Esperamos verte pronto!</p>';
                    $body .= '<img src="cid:veterinaria_logo" alt="Logo de nuestra veterinaria" style="max-width: 100%;">';
                    // Incorporar la imagen en el correo electrónico como recurso embebido (CID)
                    $ruta_imagen = './logo.png'; // Ruta de la imagen
                    $mail->AddEmbeddedImage($ruta_imagen, 'veterinaria_logo');

                    $mail->Body = $body;
                    // Enviar el correo electrónico
                    $mail->send();
                    echo ' Actualizacion del Pedido enviado al correo';
                } catch (Exception $e) {
                    echo "Error al enviar el correo electrónico: {$mail->ErrorInfo}";
                }
            } else {
                // Si no hay resultados, devolver un JSON con un mensaje
                echo json_encode(array("mensaje" => "No se encontro pedidos"));
            }
        } catch (mysqli_sql_exception $e) {
            echo "Error al actualizar el pedido: " . $e->getMessage();
        }
    } else {
        // El pedido no existe
        echo "El pedido no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}

// Función para eliminar un pedido
function eliminarPedido($id_pedido)
{
    global $mysql;

    if (existePedido($id_pedido)) {

        // Construir la consulta SQL para eliminar el pedido
        $query = "DELETE FROM pedidos WHERE id = ?";
        $query = "UPDATE pedidos SET estado = 'anulado' WHERE id = ?";
        $stmt = $mysql->prepare($query);
        $stmt->bind_param("i", $id_pedido);

        try {
            $stmt->execute();
            echo "Pedido eliminado exitosamente";
        } catch (mysqli_sql_exception $e) {
            echo "Error al eliminar el pedido: " . $e->getMessage();
        }
    } else {
        echo "El pedido no existe";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $mysql->close();
}
