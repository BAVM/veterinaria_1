<?php
include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bootstrap CRUD Data Table for Database with Modal Form</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Varela Round', sans-serif;
            font-size: 13px;
        }

        .table-responsive {
            margin: 30px 0;
        }

        .table-wrapper {
            background: #fff;
            padding: 20px 25px;
            border-radius: 3px;
            min-width: 1000px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .table-title {
            padding-bottom: 15px;
            background: #435d7d;
            color: #fff;
            padding: 16px 30px;
            min-width: 100%;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        .table-title .btn-group {
            float: right;
        }

        .table-title .btn {
            color: #fff;
            float: right;
            font-size: 13px;
            border: none;
            min-width: 50px;
            border-radius: 2px;
            border: none;
            outline: none !important;
            margin-left: 10px;
        }

        .table-title .btn i {
            float: left;
            font-size: 21px;
            margin-right: 5px;
        }

        .table-title .btn span {
            float: left;
            margin-top: 2px;
        }

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
            padding: 10px 5px;
            vertical-align: middle;
        }

        table.table tr th:first-child {
            width: 30px;
        }

        table.table tr th:last-child {
            width: 100px;
        }

        table.table-striped tbody tr:nth-of-type(odd) {
            background-color: #fcfcfc;
        }

        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table td:last-child i {
            opacity: 0.9;
            font-size: 22px;
            margin: 0 5px;
        }

        table.table td a {
            font-weight: bold;
            color: #566787;
            display: inline-block;
            text-decoration: none;
            outline: none !important;
        }

        table.table td a:hover {
            color: #2196F3;
        }

        table.table td a.edit {
            color: #FFC107;
        }

        table.table td a.delete {
            color: #F44336;
        }

        table.table td i {
            font-size: 19px;
        }

        table.table .avatar {
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 10px;
        }

        .pagination {
            float: right;
            margin: 0 0 5px;
        }

        .pagination li a {
            border: none;
            font-size: 13px;
            min-width: 30px;
            min-height: 30px;
            color: #999;
            margin: 0 2px;
            line-height: 30px;
            border-radius: 2px !important;
            text-align: center;
            padding: 0 6px;
        }

        .pagination li a:hover {
            color: #666;
        }

        .pagination li.active a,
        .pagination li.active a.page-link {
            background: #03A9F4;
        }

        .pagination li.active a:hover {
            background: #0397d6;
        }

        .pagination li.disabled i {
            color: #ccc;
        }

        .pagination li i {
            font-size: 16px;
            padding-top: 6px
        }

        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }

        /* Custom checkbox */
        .custom-checkbox {
            position: relative;
        }

        .custom-checkbox input[type="checkbox"] {
            opacity: 0;
            position: absolute;
            margin: 5px 0 0 3px;
            z-index: 9;
        }

        .custom-checkbox label:before {
            width: 18px;
            height: 18px;
        }

        .custom-checkbox label:before {
            content: '';
            margin-right: 10px;
            display: inline-block;
            vertical-align: text-top;
            background: white;
            border: 1px solid #bbb;
            border-radius: 2px;
            box-sizing: border-box;
            z-index: 2;
        }

        .custom-checkbox input[type="checkbox"]:checked+label:after {
            content: '';
            position: absolute;
            left: 6px;
            top: 3px;
            width: 6px;
            height: 11px;
            border: solid #000;
            border-width: 0 3px 3px 0;
            transform: inherit;
            z-index: 3;
            transform: rotateZ(45deg);
        }

        .custom-checkbox input[type="checkbox"]:checked+label:before {
            border-color: #03A9F4;
            background: #03A9F4;
        }

        .custom-checkbox input[type="checkbox"]:checked+label:after {
            border-color: #fff;
        }

        .custom-checkbox input[type="checkbox"]:disabled+label:before {
            color: #b8b8b8;
            cursor: auto;
            box-shadow: none;
            background: #ddd;
        }

        /* Modal styles */
        .modal .modal-dialog {
            max-width: 400px;
        }

        .modal .modal-header,
        .modal .modal-body,
        .modal .modal-footer {
            padding: 20px 30px;
        }

        .modal .modal-content {
            border-radius: 3px;
            font-size: 14px;
        }

        .modal .modal-footer {
            background: #ecf0f1;
            border-radius: 0 0 3px 3px;
        }

        .modal .modal-title {
            display: inline-block;
        }

        .modal .form-control {
            border-radius: 2px;
            box-shadow: none;
            border-color: #dddddd;
        }

        .modal textarea.form-control {
            resize: vertical;
        }

        .modal .btn {
            border-radius: 2px;
            min-width: 100px;
        }

        .modal form label {
            font-weight: normal;
        }
    </style>


</head>

<body>
    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Gestión de <b>Pedidos</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Agregar Pedido</span></a>
                        <!-- <a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Borrar</span></a> -->
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>
                            <!-- <span class="custom-checkbox">
                                <input type="checkbox" id="selectAll">
                                <label for="selectAll"></label>
                            </span> -->
                        </th>
                        <th># de Pedido</th>
                        <th>Cliente</th>
                        <th>Fecha de Pedido</th>
                        <th>Estado</th>
                        <th>Valor Total</th>
                        <th>Método de Pago</th>
                        <th>Dirección Envio</th>
                        <th>Fecha de Envio</th>
                        <th>Fecha de Entrega</th>
                        <th>Observaciones</th>
                        <th>Productos</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
            <div class="clearfix">
                <div class="hint-text">Mostrando <b>5</b> de <b>25</b> entradas</div>
                <ul class="pagination">
                    <li class="page-item disabled"><a href="#">Previo</a></li>
                    <li class="page-item active"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item "><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">4</a></li>
                    <li class="page-item"><a href="#" class="page-link">5</a></li>
                    <li class="page-item"><a href="#" class="page-link">Siguiente</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Add Modal HTML -->
    <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Crear Pedido</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Cliente</label>
                            <select id="cliente" type="text" class="form-control" required>
                                <!-- Opciones de usuario se poblarán dinámicamente -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Fecha del pedido</label>
                            <input id="fechaPedido" type="date" class="form-control" required>
                        </div>
                        <div id="contenedorProductos" class="form-group">
                            <button type="button" id="agregarProducto" class="btn btn-primary">Agregar Producto</button>
                            <div class="form-group">
                                <label>Producto</label>
                                <select id="nombreProducto" type="text" class="form-control" required>
                                    <!-- Opciones de raza se poblarán dinámicamente -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Valor Total</label>
                            <input id="valorPedido" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Método de Pago</label>
                            <select id="metodoPago" type="text" class="form-control" required>
                                <!-- Opciones de usuario se poblarán dinámicamente -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Dirección de Envio</label>
                            <input id="direccionEnvio" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Fecha de Envio</label>
                            <input id="fechaEnvio" type="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Fecha de Entrega</label>
                            <input id="fechaEntrega" type="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Observaciones</label>
                            <input id="observaciones" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <input id="estadoPedido" type="text" class="form-control" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-success" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal HTML -->
    <div id="editEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Pedido</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label># de Pedido</label>
                            <input id="id_pedido" type="text" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Cliente</label>
                            <select id="cliente_edit" type="text" class="form-control" required>
                                <!-- Opciones de usuario se poblarán dinámicamente -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Fecha del pedido</label>
                            <input id="fechaPedido_edit" type="date" class="form-control" required>
                        </div>
                        <button type="button" id="agregarProducto_edit" class="btn btn-primary">Agregar Producto</button>
                        <div id="contenedorProductos_edit" class="form-group">
                            <button type="button" id="agregarProducto_edit" class="btn btn-primary">Agregar Producto</button>
                            <!-- <div class="form-group">
                                <label>Producto</label>
                                <select id="nombreProducto_edit" type="text" class="form-control" required>
                                    
                                </select>
                            </div> -->
                        </div>
                        <div class="form-group">
                            <label>Valor Total</label>
                            <input id="valorPedido_edit" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Metodo de Pago</label>
                            <select id="metodoPago_edit" type="text" class="form-control" required>
                                <!-- Opciones de usuario se poblarán dinámicamente -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Dirección de Envio</label>
                            <input id="direccionEnvio_edit" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Fecha de Envio</label>
                            <input id="fechaEnvio_edit" type="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Fecha de Entrega</label>
                            <input id="fechaEntrega_edit" type="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Observaciones</label>
                            <input id="observaciones_edit" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <input id="estadoPedido_edit" type="text" class="form-control" required>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-info" value="Guardar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Modal HTML -->
    <div id="deleteEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Borrar Pedido</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>¿Está seguro de que desea eliminar estos registros?</p>
                        <p class="text-warning"><small>Esta acción no se puede deshacer.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancelar">
                        <input type="submit" class="btn btn-danger" value="Borrar">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="successMessage"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            // Realizar la petición HTTP para obtener los datos de los empleados
            $.ajax({
                url: '<?php echo $server_url; ?>/pedidos.php',
                type: 'POST', // O el método HTTP adecuado para tu servidor
                data: {
                    accion: 'listar'
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                },
                success: function(response) {
                    // console.log(response.usuarios);
                    $('table tbody').empty();

                    // Iterar sobre la respuesta del servidor
                    response.pedidos.forEach(function(usuario) {
                        var row = '<tr>' +
                            '<td>' +
                            // '<span class="custom-checkbox">' +
                            // '<input type="checkbox" id="checkbox1" name="options[]" value="1">' +
                            // '<label for="checkbox1"></label>' +
                            // '</span>' +
                            '</td>' +
                            // '<td>' + usuario.nombre + ' ' + usuario.apellido + '</td>' +
                            '<td>' + usuario.id + '</td>' +
                            '<td>' + usuario.cliente + '</td>' +
                            '<td>' + usuario.fecha_pedido + '</td>' +
                            '<td>' + usuario.estado + '</td>' +
                            '<td>' + usuario.valor_total_pedido + '</td>' +
                            '<td>' + usuario.metodo_pago + '</td>' +
                            '<td>' + usuario.direccion_envio + '</td>' +
                            '<td>' + usuario.fecha_envio + '</td>' +
                            '<td>' + usuario.fecha_entrega + '</td>' +
                            '<td>' + usuario.observaciones + '</td>';
                        // '<td>' + usuario.nombre_producto + '</td>';

                        // Construir la lista de productos del pedido
                        var productosHtml = '';
                        usuario.productos.forEach(function(producto) {
                            productosHtml += producto.nombre + ', ';
                        });
                        productosHtml = productosHtml.slice(0, -2); // Eliminar la última coma y espacio
                        row += '<td>' + productosHtml + '</td>';

                        row += '<td>' +
                            '<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>' +
                            '<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>' +
                            '</td>' +
                            '</tr>';

                        // Agregar la fila a la tabla
                        $('table tbody').append(row);
                    });

                    // Actualizar el tooltip después de agregar las nuevas filas
                    $('[data-toggle="tooltip"]').tooltip();
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener los datos de los tipos de Animales:', error);
                }
            });

            $('table').on('click', '.edit', function() {
                // Obtener la fila que se está editando
                var currentRow = $(this).closest('tr');
                var cols = currentRow.find('td');

                // Obtener los datos del usuario de la fila
                var id_pedido = currentRow.find('td:eq(1)').text();
                var cliente = currentRow.find('td:eq(2)').text();
                var fecha_pedido = currentRow.find('td:eq(3)').text();
                var estado = currentRow.find('td:eq(4)').text();
                var valor_total_pedido = currentRow.find('td:eq(5)').text();
                var metodo_pago = currentRow.find('td:eq(6)').text();
                var direccion_envio = currentRow.find('td:eq(7)').text();
                var fecha_envio = currentRow.find('td:eq(8)').text();
                var fecha_entrega = currentRow.find('td:eq(9)').text();
                var observaciones = currentRow.find('td:eq(10)').text();
                var products = currentRow.find('td:eq(11)').text().trim().split(', '); // Separar los nombres de los productos

                // Llenar los campos de la modal de edición con los datos del usuario
                $('#editEmployeeModal input[id="id_pedido"]').val(id_pedido);
                $('#editEmployeeModal input[id="cliente_edit"]').val(cliente);
                $('#editEmployeeModal input[id="fechaPedido_edit"]').val(fecha_pedido);
                $('#editEmployeeModal input[id="estadoPedido_edit"]').val(estado);
                $('#editEmployeeModal input[id="valorPedido_edit"]').val(valor_total_pedido);
                $('#editEmployeeModal input[id="metodoPago_edit"]').val(metodo_pago);
                $('#editEmployeeModal input[id="direccionEnvio_edit"]').val(direccion_envio);
                $('#editEmployeeModal input[id="fechaEnvio_edit"]').val(fecha_envio);
                $('#editEmployeeModal input[id="fechaEntrega_edit"]').val(fecha_entrega);
                $('#editEmployeeModal input[id="observaciones_edit"]').val(observaciones);

                // Limpiar el contenedor de productos en la modal de edición
                $('#contenedorProductos_edit').empty();

                // Obtener las opciones de productos y agregarlas a los select correspondientes
                $.ajax({
                    url: '<?php echo $server_url; ?>/productos.php',
                    type: 'POST',
                    data: {
                        accion: 'listar',
                        estado: 'activo'
                    },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                    },
                    success: function(response) {
                        // Iterar sobre cada producto
                        products.forEach(function(product, index) {
                            // Crear el HTML del nuevo producto
                            var nuevoProducto = '<div class="form-group">' +
                                '<label>Producto</label>' +
                                '<select id="nombreProducto_edit_' + index + '" type="text" class="form-control" required>' +
                                '</select>' +
                                '<button type="button" class="eliminarProducto btn btn-danger">Eliminar</button>' + // Botón para eliminar
                                '</div>';

                            // Agregar el nuevo producto al contenedor
                            $('#contenedorProductos_edit').append(nuevoProducto);

                            // Obtener el select correspondiente al nuevo producto
                            var select = $('#nombreProducto_edit_' + index);

                            response.productos.forEach(function(producto) {
                                select.append($('<option>', {
                                    value: producto.id,
                                    text: producto.nombre_producto,
                                    "data-valor": producto.precio
                                }));
                            });

                            $('#nombreProducto_edit_' + index + ' option').each(function() {
                                // console.log("product", $('#nombreProducto_edit_' + index + ' option'));
                                // console.log("texto", $(this).text());
                                // Comparar el valor de la opción con el valor del dueño obtenido de la fila de la tabla
                                if ($(this).text() == product) {
                                    // Si coincide, establecer el atributo selected como true
                                    $(this).prop('selected', true);
                                }
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al obtener datos de productos:', error);
                    }
                });

                var contador = 1;

                $('#agregarProducto_edit').click(function() {
                    var nuevoProducto = '<div class="form-group">' +
                        '<label>Producto</label>' +
                        '<select id="nombreProducto_' + contador + '" type="text" class="form-control" required>' +
                        '</select>' +
                        '<button type="button" class="eliminarProducto btn btn-danger">Eliminar</button>' + // Botón para eliminar
                        '</div>';

                    $('#contenedorProductos_edit').append(nuevoProducto);

                    // Incrementar el contador para asegurar IDs únicos
                    contador++;

                    // Obtener opciones de productos y agregarlas al nuevo campo de selección
                    $.ajax({
                        url: '<?php echo $server_url; ?>/productos.php',
                        type: 'POST',
                        data: {
                            accion: 'listar',
                            estado: 'activo'
                        },
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                        },
                        success: function(response) {
                            $('#nombreProducto_' + (contador - 1)).append($('<option>', {
                                value: "Seleccione un producto",
                                text: "Seleccione un producto"
                            }));
                            response.productos.forEach(function(producto) {
                                $('#nombreProducto_' + (contador - 1)).append($('<option>', {
                                    value: producto.id,
                                    text: producto.nombre_producto,
                                    "data-valor": producto.precio
                                }));
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al obtener datos de productos:', error);
                        }
                    });

                });

                // calcularValorTotalEdit();
                // Manejar el cambio en la selección de productos
                $(document).on('change', '#contenedorProductos_edit select', calcularValorTotalEdit);
                $(document).on('click', '.eliminarProducto', function() {
                    $(this).closest('.form-group').remove(); // Elimina el div del producto
                    calcularValorTotalEdit(); // Recalcular el valor total
                });



                $('#cliente_edit option').each(function() {

                    // Comparar el valor de la opción con el valor del dueño obtenido de la fila de la tabla
                    if ($(this).text() == cliente) {
                        // Si coincide, establecer el atributo selected como true
                        $(this).prop('selected', true);
                    }
                });

                $('#metodoPago_edit option').each(function() {

                    // Comparar el valor de la opción con el valor del dueño obtenido de la fila de la tabla
                    if ($(this).text() == metodo_pago) {
                        // Si coincide, establecer el atributo selected como true
                        $(this).prop('selected', true);
                    }
                });

                // Mostrar la modal de edición
                $('#editEmployeeModal').modal('show');

                //Actualizar el tooltip después de agregar las nuevas filas
                $('[data-toggle="tooltip"]').tooltip();
            });



            document.getElementById("editEmployeeModal").addEventListener("submit", function(event) {
                event.preventDefault(); // Evita que el formulario se envíe automáticamente

                // Obtener los valores de los campos de entrada
                var id_pedido = document.getElementById("id_pedido").value.trim();
                var firstName = document.getElementById("cliente_edit").value.trim();
                var firstFechaPedido = document.getElementById("fechaPedido_edit").value.trim();
                var firstEstadoPedido = document.getElementById("estadoPedido_edit").value.trim();
                var firstValorPedido = document.getElementById("valorPedido_edit").value.trim();
                var firstPagoPedido = document.getElementById("metodoPago_edit").value.trim();
                var firstDireccionPedido = document.getElementById("direccionEnvio_edit").value.trim();
                var firstFechaEPedido = document.getElementById("fechaEnvio_edit").value.trim();
                var firstFechaEnPedido = document.getElementById("fechaEntrega_edit").value.trim();
                var firstObservaciones = document.getElementById("observaciones_edit").value.trim();
                // var productos = document.getElementById("nombreProducto_edit").value.trim();

                // Validar que los campos no estén vacíos
                if (firstName === "" || firstFechaPedido === "" || firstEstadoPedido === "" || firstValorPedido === "" ||
                    firstPagoPedido === "" || firstDireccionPedido === "" || firstFechaEPedido === "" ||
                    firstFechaEnPedido === "" || firstObservaciones === "" || productos === "") {
                    alert("Por favor, complete todos los campos.");
                    return;
                }

                // Obtener los valores de los campos de selección de productos
                var selectedProducts = document.querySelectorAll("#contenedorProductos_edit select");
                var productos = [];
                selectedProducts.forEach(function(select) {
                    var productoId = select.value.trim();
                    if (productoId !== "") {
                        productos.push(
                            productoId);
                    }
                });

                // Validar que se haya seleccionado al menos un producto
                if (productos.length === 0) {
                    alert("Por favor, seleccione al menos un producto.");
                    return;
                }


                var formData = new URLSearchParams();
                formData.append('id_pedido', id_pedido);
                formData.append('id_cliente', firstName);
                formData.append('fecha_pedido', firstFechaPedido);
                formData.append('estado', firstEstadoPedido);
                formData.append('valor_total_pedido', firstValorPedido);
                formData.append('id_metodo_pago', firstPagoPedido);
                formData.append('direccion_envio', firstDireccionPedido);
                formData.append('fecha_envio', firstFechaEPedido);
                formData.append('fecha_entrega', firstFechaEnPedido);
                formData.append('observaciones', firstObservaciones);
                // formData.append('id_producto', firstIdProducto);

                // Agregar los productos al formData
                productos.forEach(function(productoId, index) {
                    formData.append('productos[' + index + '][id]', productoId);

                });

                formData.append('accion', 'editar');

                // Hacer la petición HTTP al servidor
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "<?php echo $server_url; ?>/pedidos.php");
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Si la respuesta es exitosa, consultar el contenido de la respuesta
                        // var response = JSON.parse(xhr.responseText);
                        if (xhr.responseText == "Pedido actualizado exitosamente Actualizacion del Pedido enviado al correo") {
                            // Si la respuesta indica éxito, mostrar el modal de éxito con el mensaje proporcionado por el servidor
                            var successMessage = xhr.responseText;
                            document.getElementById("successModalLabel").innerText = "Se realizo con Éxito";
                            document.getElementById("successMessage").innerText = successMessage;
                            $('#successModal').modal('show');

                            // Redireccionar a index.php cuando se cierre la modal de éxito
                            $('#successModal').on('hidden.bs.modal', function(e) {
                                window.location.href = "home.view.php";
                            });


                        } else {

                            // Si la respuesta indica error, mostrar un mensaje de error
                            // Si hay algún error, muestra un mensaje de error
                            var successMessage = xhr.responseText;
                            document.getElementById("successModalLabel").innerText = "Un Error a Ocurrido";
                            document.getElementById("successMessage").innerText = successMessage;
                            $('#successModal').modal('show');

                            $('#addEmployeeModal').on('hidden.bs.modal', function() {
                                // Restablecer el formulario a su estado original
                                $('#addEmployeeModal form')[0].reset();
                            });
                        }
                    } else {
                        // Si hay algún error, muestra un mensaje de error
                        alert("Error al enviar la solicitud. Por favor, intente nuevamente.");
                    }
                };
                xhr.send(formData);
            });

            $('#addEmployeeModal').on('hidden.bs.modal', function() {
                // Restablecer el formulario a su estado original
                $('#addEmployeeModal form')[0].reset();

            });

            $('#editEmployeeModal').on('hidden.bs.modal', function() {
                // Restablecer el formulario a su estado original
                $('#addEmployeeModal form')[0].reset();

            });

            document.getElementById("addEmployeeModal").addEventListener("submit", function(event) {
                event.preventDefault(); // Evita que el formulario se envíe automáticamente

                // Obtener los valores de los campos de entrada
                var firstName = document.getElementById("cliente").value.trim();
                var firstFechaPedido = document.getElementById("fechaPedido").value.trim();
                var firstEstadoPedido = document.getElementById("estadoPedido").value.trim();
                var firstValorPedido = document.getElementById("valorPedido").value.trim();
                var firstPagoPedido = document.getElementById("metodoPago").value.trim();
                var firstDireccionPedido = document.getElementById("direccionEnvio").value.trim();
                var firstFechaEPedido = document.getElementById("fechaEnvio").value.trim();
                var firstFechaEnPedido = document.getElementById("fechaEntrega").value.trim();
                var firstObservaciones = document.getElementById("observaciones").value.trim();

                // Validar que los campos no estén vacíos
                if (firstName === "" || firstFechaPedido === "" || firstEstadoPedido === "" || firstValorPedido === "" ||
                    firstPagoPedido === "" || firstDireccionPedido === "" || firstFechaEPedido === "" ||
                    firstFechaEnPedido === "" || firstObservaciones === "") {
                    alert("Por favor, complete todos los campos.");
                    return;
                }

                // Obtener los valores de los campos de selección de productos
                var selectedProducts = document.querySelectorAll("#contenedorProductos select");
                var productos = [];
                selectedProducts.forEach(function(select) {
                    var productoId = select.value.trim();
                    if (productoId !== "") {
                        productos.push(
                            productoId);
                    }
                });

                // Validar que se haya seleccionado al menos un producto
                if (productos.length === 0) {
                    alert("Por favor, seleccione al menos un producto.");
                    return;
                }

                var formData = new URLSearchParams();
                formData.append('id_cliente', firstName);
                formData.append('fecha_pedido', firstFechaPedido);
                formData.append('estado', firstEstadoPedido);
                formData.append('valor_total_pedido', firstValorPedido);
                formData.append('id_metodo_pago', firstPagoPedido);
                formData.append('direccion_envio', firstDireccionPedido);
                formData.append('fecha_envio', firstFechaEPedido);
                formData.append('fecha_entrega', firstFechaEnPedido);
                formData.append('observaciones', firstObservaciones);

                // Agregar los productos al formData
                productos.forEach(function(productoId, index) {
                    formData.append('productos[' + index + '][id]', productoId);

                });

                formData.append('accion', 'crear');

                // Hacer la petición HTTP al servidor
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "<?php echo $server_url; ?>/pedidos.php");
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var successMessage = xhr.responseText;
                        if (successMessage.includes("Pedido creado exitosamente")) {
                            document.getElementById("successModalLabel").innerText = "Se realizó con éxito";
                        } else {
                            document.getElementById("successModalLabel").innerText = "Ha ocurrido un error";
                        }
                        document.getElementById("successMessage").innerText = successMessage;
                        $('#successModal').modal('show');
                        // Redireccionar a index.php cuando se cierre la modal de éxito
                        $('#successModal').on('hidden.bs.modal', function(e) {
                            window.location.href = "home.view.php";
                        });
                    } else {
                        alert("Error al enviar la solicitud. Por favor, intente nuevamente.");
                    }
                };
                xhr.send(formData);
            });


            $('table').on('click', '.delete', function() {
                // Obtener la fila que se está editando
                var currentRow = $(this).closest('tr');
                var cols = currentRow.find('td');

                // Obtener los datos del usuario de la fila
                var id_pedido = currentRow.find('td:eq(1)').text();

                document.getElementById("deleteEmployeeModal").addEventListener("submit", function(event) {
                    event.preventDefault(); // Evita que el formulario se envíe automáticamente
                    // $('#successModal').modal(close);

                    var formData = new URLSearchParams();
                    formData.append('id_pedido', id_pedido);
                    formData.append('accion', 'eliminar');

                    // Hacer la petición HTTP al servidor
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "<?php echo $server_url; ?>/pedidos.php");
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                    xhr.onload = function() {
                        console.log(xhr.responseText);
                        if (xhr.status === 200) {
                            // Si la respuesta es exitosa, consultar el contenido de la respuesta
                            // var response = JSON.parse(xhr.responseText);
                            if (xhr.responseText == "Pedido eliminado exitosamente") {
                                // Si la respuesta indica éxito, mostrar el modal de éxito con el mensaje proporcionado por el servidor
                                var successMessage = xhr.responseText;
                                document.getElementById("successModalLabel").innerText = "Se realizo con Éxito";
                                document.getElementById("successMessage").innerText = successMessage;
                                $('#successModal').modal('show');

                                // Redireccionar a index.php cuando se cierre la modal de éxito
                                $('#successModal').on('hidden.bs.modal', function(e) {
                                    window.location.href = "home.view.php";
                                });
                            } else {

                                // Si la respuesta indica error, mostrar un mensaje de error
                                // Si hay algún error, muestra un mensaje de error
                                var successMessage = xhr.responseText;
                                document.getElementById("successModalLabel").innerText = "Un Error a Ocurrido";
                                document.getElementById("successMessage").innerText = successMessage;
                                $('#successModal').modal('show');
                            }
                        } else {
                            // Si hay algún error, muestra un mensaje de error
                            alert("Error al enviar la solicitud. Por favor, intente nuevamente.");
                        }
                    };
                    xhr.send(formData);
                });
            });

            $.ajax({
                url: '<?php echo $server_url; ?>/clientes.php', // Endpoint para obtener datos de usuarios y razas
                type: 'POST',
                data: {
                    accion: 'listar',
                    estado: 'activo'
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                },
                success: function(response) {

                    // Poblar campos de formulario de edición con datos de usuarios y razas
                    response.usuarios.forEach(function(usuario) {
                        // Por ejemplo, puedes agregar opciones a un campo de selección de usuarios
                        $('#cliente_edit').append($('<option>', {
                            value: usuario.id,
                            text: usuario.nombre + " " + usuario.apellido
                        }));
                        $('#cliente').append($('<option>', {
                            value: usuario.id,
                            text: usuario.nombre + " " + usuario.apellido
                        }));

                    });

                    // // // Actualizar el tooltip después de agregar las nuevas filas
                    // $('[data-toggle="tooltip"]').tooltip();
                },

                error: function(xhr, status, error) {
                    console.error('Error al obtener datos de usuarios', error);
                }
            });

            $.ajax({
                url: '<?php echo $server_url; ?>/metodos_pago.php', // Endpoint para obtener datos de usuarios y razas
                type: 'POST',
                data: {
                    accion: 'listar',
                    estado: 'activo'
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                },
                success: function(response) {
                    // Poblar campos de formulario de edición con datos de usuarios y razas
                    response.metodosPago.forEach(function(usuario) {
                        // Por ejemplo, puedes agregar opciones a un campo de selección de usuarios
                        $('#metodoPago_edit').append($('<option>', {
                            value: usuario.id,
                            text: usuario.nombre_metodo
                        }));
                        $('#metodoPago').append($('<option>', {
                            value: usuario.id,
                            text: usuario.nombre_metodo
                        }));
                    });

                    // // Actualizar el tooltip después de agregar las nuevas filas
                    // $('[data-toggle="tooltip"]').tooltip();
                },

                error: function(xhr, status, error) {
                    console.error('Error al obtener datos de razas:', error);
                }
            });

            $("#fechaPedido_edit").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0", // Rango de años permitidos, desde 100 años antes hasta el año actual
                dateFormat: "yy-mm-dd" // Formato de fecha
            });
            $("#fechaEnvio_edit").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0", // Rango de años permitidos, desde 100 años antes hasta el año actual
                dateFormat: "yy-mm-dd" // Formato de fecha
            });
            $("#fechaEntrega_edit").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0", // Rango de años permitidos, desde 100 años antes hasta el año actual
                dateFormat: "yy-mm-dd" // Formato de fecha
            });
        });

        // Script para agregar dinámicamente un campo de selección de productos
        $(document).ready(function() {

            $('#nombreProducto').append($('<option>', {
                value: "Seleccione un producto",
                text: "Seleccione un producto"
            }));

            $.ajax({
                url: '<?php echo $server_url; ?>/productos.php',
                type: 'POST',
                data: {
                    accion: 'listar',
                    estado: 'activo'
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                },
                success: function(response) {
                    response.productos.forEach(function(producto) {
                        $('#nombreProducto').append($('<option>', {
                            value: producto.id,
                            text: producto.nombre_producto,
                            "data-valor": producto.precio
                        }));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener datos de productos:', error);
                }
            });

            var contador = 1;

            $('#agregarProducto').click(function() {
                var nuevoProducto = '<div class="form-group">' +
                    '<label>Producto</label>' +
                    '<select id="nombreProducto_' + contador + '" type="text" class="form-control" required>' +
                    '</select>' +
                    '<button type="button" class="eliminarProducto btn btn-danger">Eliminar</button>' + // Botón para eliminar
                    '</div>';

                $('#contenedorProductos').append(nuevoProducto);

                // Incrementar el contador para asegurar IDs únicos
                contador++;

                // Obtener opciones de productos y agregarlas al nuevo campo de selección
                $.ajax({
                    url: '<?php echo $server_url; ?>/productos.php',
                    type: 'POST',
                    data: {
                        accion: 'listar',
                        estado: 'activo'
                    },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=UTF-8");
                    },
                    success: function(response) {
                        $('#nombreProducto_' + (contador - 1)).append($('<option>', {
                            value: "Seleccione un producto",
                            text: "Seleccione un producto"
                        }));
                        response.productos.forEach(function(producto) {
                            $('#nombreProducto_' + (contador - 1)).append($('<option>', {
                                value: producto.id,
                                text: producto.nombre_producto,
                                "data-valor": producto.precio
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al obtener datos de productos:', error);
                    }
                });

            });

            // Manejar el cambio en la selección de productos
            $(document).on('change', '#contenedorProductos select', calcularValorTotal);
            $(document).on('click', '.eliminarProducto', function() {
                $(this).closest('.form-group').remove(); // Elimina el div del producto
                calcularValorTotal(); // Recalcular el valor total
            });

        });
        // Función para calcular el valor total
        function calcularValorTotal() {
            console.log("Valor Total");
            var selectsProductos = document.querySelectorAll("#contenedorProductos select");
            var valorTotal = 0;

            // Recorrer todos los selects de productos
            selectsProductos.forEach(function(select) {
                if (select.selectedIndex >= 0) {
                    console.log("Valor Total 1", valorTotal);
                    var selectedOption = select.options[select.selectedIndex];
                    // Verificar si la opción seleccionada tiene un dataset antes de acceder a él
                    if (selectedOption.dataset.valor) {

                        // Aquí puedes obtener el valor del producto seleccionado y sumarlo al valor total
                        // Ejemplo:
                        var valorProducto = parseFloat(selectedOption.dataset.valor);
                        valorTotal += valorProducto;
                        console.log("Valor Total 2", valorTotal);
                    }
                }
            });


            // Mostrar el valor total en el campo correspondiente
            $('#valorPedido').val(valorTotal.toFixed(2));
        }

        function calcularValorTotalEdit() {
            console.log("Valor Total");
            var selectsProductos = document.querySelectorAll("#contenedorProductos_edit select");
            var valorTotal = 0;

            // Recorrer todos los selects de productos
            selectsProductos.forEach(function(select) {
                if (select.selectedIndex >= 0) {
                    console.log("Valor Total 1", valorTotal);
                    var selectedOption = select.options[select.selectedIndex];
                    // Verificar si la opción seleccionada tiene un dataset antes de acceder a él
                    if (selectedOption.dataset.valor) {

                        // Aquí puedes obtener el valor del producto seleccionado y sumarlo al valor total
                        // Ejemplo:
                        var valorProducto = parseFloat(selectedOption.dataset.valor);
                        valorTotal += valorProducto;
                        console.log("Valor Total 2", valorTotal);
                    }
                }
            });


            // Mostrar el valor total en el campo correspondiente
            $('#valorPedido_edit').val(valorTotal.toFixed(2));
        }
    </script>


</body>



</html>