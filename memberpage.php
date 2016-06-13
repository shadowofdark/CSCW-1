<?php
require('includes/config.php');

//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: login.php');
}

//define page title
$title = 'CSCW - Simulacion 2016';

//include header template
require('layout/header.php');
?>

<div class="container">

    <div class="row">

        <div class="">
            <nav class="navbar navbar-default" role="navigation">
                <!-- El logotipo y el icono que despliega el menú se agrupan
                     para mostrarlos mejor en los dispositivos móviles -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="memberpage.php">CSCW</a>
                </div>

                <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                     otro elemento que se pueda ocultar al minimizar la barra -->
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#myModal"  data-toggle="modal" data-target="#myModal">Compartir Archivo</a></li>
                        <li class="active"><a href="#myModal2"  data-toggle="modal" data-target="#myModal2">Agregar Novedad</a></li>
                        <li class="active"><a href='logout.php'>Logout</a></li>
                    </ul>

                </div>
            </nav>

            <div class="modal fade" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="form" enctype="multipart/form-data" role="form" method="post" action="post.php">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Subir Archivo...</h4>
                            </div>
                            <div class="modal-body">
                                <div id="messages"></div>
                                <input type="file" name="file" id="file">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Subir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="myModal2">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="form" action="agregarComentario.php" method="post" role="form">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title">Agregar Novedad...</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="comment">Comentario:</label>
                                    <textarea name="comment" class="form-control" rows="5" id="comment"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Añadir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <h2>Bienvenido <?php echo $_SESSION['username']; ?>!</h2>
            <hr>

            <table class="table table-bordered " width="100%" height="100%">

                <tr height="200px">
                    <td>
                        <table class="table table-hover" title="Contenido" border="0" >
                            <tr>
                            <td class="success"><span class="glyphicon glyphicon-paperclip"></span> Mis Archivos</td></tr>
                <tr>
                    <td><strong>Archivo</strong></td>
                    <td><strong>Fecha Upload</strong></td>
                    <td><strong>Tamaño</strong></td>

                </tr >
                <?php
                $stmt = $db->prepare('SELECT * FROM Files WHERE prop = :username');
                $stmt->execute(array(':username' => $_SESSION['username']));
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $long = count($row);

                for ($i = 0; $i <= $long - 1; $i++) {
                    $id = $row[$i]['id_file'];
                    $usern = $_SESSION['username'];
                    echo "<tr>";
                    echo "<td>" . $row[$i]['nombre'] . "</td>";
                    echo "<td>" . $row[$i]['hora'] . "</td>";
                    echo "<td>" . $row[$i]['peso'] . " Kb</td>";
                    echo '<td><a href="eliminar.php?nombre=' . $row[$i]['nombre'] . '&id=' . $id . '"a>Eliminar<td>';
                    echo '<td><a href="descargar.php?nombre=' . $row[$i]['nombre'] . '&usu=' . $usern . '"a>Descargar<td>';
                    echo "</tr>";
                }
                ?>


                <?php
                $stmt = $db->prepare('SELECT * FROM mybb_users WHERE username != :username');
                $stmt->execute(array(':username' => $_SESSION['username']));


                $otros = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $long = count($otros);

                for ($i = 0; $i <= $long - 1; $i++) {
                    $usern = $otros[$i]['username'];
                    ?>
                   <tr><td class="success">
                   <span class="glyphicon glyphicon-paperclip"></span> Archivos de  <?php echo $usern?></td></tr>
                    <?php
                    echo' <tr>
                                <td><strong>Archivo</strong></td>
                                <td><strong>Fecha Upload</strong></td>
                                <td><strong>Tamaño</strong></td>
                                </tr >';
                    $stmt = $db->prepare('SELECT * FROM Files WHERE prop = :username');
                    $stmt->execute(array(':username' => $usern));
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $long = count($row);

                    for ($i = 0; $i <= $long - 1; $i++) {
                        $id = $row[$i]['id_file'];
                        echo "<tr>";
                        echo "<td>" . $row[$i]['nombre'] . "</td>";
                        echo "<td>" . $row[$i]['hora'] . "</td>";
                        echo "<td>" . $row[$i]['peso'] . " Kb</td>";
                        echo '<td></td>';
                        echo '<td><a href="descargar.php?nombre=' . $row[$i]['nombre'] . '&usu=' . $usern . '"a>Descargar<td>';
                        echo "</tr>";
                    }
                }
                ?>

                </tr>
            </table>
            </td>
            <td>
                <table class="table table-bordered"  title="Menu" width="30%">
                    <?php
                    $stmt = $db->prepare('SELECT username, email FROM mybb_users WHERE username != :username');
                    $stmt->execute(array(':username' => $_SESSION['username']));
                    $otros = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo '<center><caption><strong> <span class="glyphicon glyphicon-user"></span> Usuarios Disponibles<strong></caption></center>';

                    $long4 = count($otros);

                    echo' <tr>
                                <td><strong>Usuario</strong></td>
                                <td><strong>E-Mail</strong></td>
                                                               </tr >';
                    for ($i = 0; $i <= $long4 - 1; $i++) {
                        $usern = $otros[$i]['username'];
                        $emailn = $otros[$i]['email'];
                        echo "<tr>";
                        echo "<td>" . $usern . "</td>";
                        echo "<td>" . $emailn . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </td>
            </tr>
            <tr height="50px">
                <td colspan="2px">
                    <table class="table table-condensed">
                        <tr>
                            <td class="success"> <span class="glyphicon glyphicon-pushpin"></span> Ultimas Novedades</td>

                            <?php
                            $stmt = $db->prepare('SELECT * FROM novedades ORDER BY id_novedad DESC LIMIT 10');
                            $stmt->execute();
                            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            $long = count($row);

                            for ($i = 0; $i <= $long - 1; $i++) {

                                echo "<tr>";
                                echo "<td>" . "[" . $row[$i]['fecha'] . "] " . $row[$i]['user'] . ":" . $row[$i]['novedad'] . "</td>";
                                echo "</tr>";
                            }
                            ?>

                        </tr>
                    </table>
                </td>
            </tr>
            </table>


        </div>
    </div>


    <script>
        $('#form').submit(function (e) {

            var form = $(this);
            var formdata = false;
            if (window.FormData) {
                formdata = new FormData(form[0]);
            }

            var formAction = form.attr('action');

            $.ajax({
                type: 'POST',
                url: 'post.php',
                cache: false,
                data: formdata ? formdata : form.serialize(),
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response != 'error') {
                        //$('#messages').addClass('alert alert-success').text(response);
                        // OP requested to close the modal
                        $('#myModal').modal('hide');
                    } else {
                        $('#messages').addClass('alert alert-danger').text(response);
                    }
                }
            });
            e.preventDefault();
        });
    </script>

</div>

<?php
//include header template
require('layout/footer.php');
?>
