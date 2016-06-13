<?php
require('includes/config.php');

//if logged in redirect to members page
if ($user->is_logged_in()) {
    header('Location: memberpage.php');
}

//if form has been submitted process it
if (isset($_POST['submit'])) {

    //email validation
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Ingrese una direccion E-Mail valida...';
    } else {
        $stmt = $db->prepare('SELECT email FROM mybb_users WHERE email = :email');
        $stmt->execute(array(':email' => $_POST['email']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($row['email'])) {
            $error[] = 'El E-Mail no fue encontrado...';
        }
    }
    if (strlen($_POST['password']) < 3) {
        $error[] = 'La contraseña es demasiado corta....';
    }

    if (strlen($_POST['passwordConfirm']) < 3) {
        $error[] = 'Las contraseñas no coinciden... ';
    }

    if ($_POST['password'] != $_POST['passwordConfirm']) {
        $error[] = 'Las contraseñas no coinciden...';
    }
    //if no errors have been created carry on
    if (!isset($error)) {


        try {

            $stmt = $db->prepare("UPDATE mybb_users SET password = :password WHERE email = :email");
            $stmt->execute(array(
                ':email' => $row['email'],
                ':password' => $_POST['password']
            ));



            //redirect to index page
            header('Location: login.php?action=reset');
            exit;

            //else catch the exception and show the error.
        } catch (PDOException $e) {
            $error[] = $e->getMessage();
        }
    }
}

//define page title
$title = 'Reset Account';

//include header template
require('layout/header.php');
?>

<div class="container">

    <div class="row">

        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
            <form role="form" method="post" action="" autocomplete="off">
                <h2>Cambiar Contraseña</h2>
                <p><a href='login.php'>Volver a la pagina de inicio</a></p>
                <hr>

                <?php
                //check for any errors
                if (isset($error)) {
                    foreach ($error as $error) {
                        echo '<p class="bg-danger">' . $error . '</p>';
                    }
                }

                if (isset($_GET['action'])) {

                    //check the action
                    switch ($_GET['action']) {
                        case 'active':
                            echo "<h2 class='bg-success'>Contraseña Actualizada...</h2>";
                            break;
                        case 'reset':
                            echo "<h2 class='bg-success'></h2>";
                            break;
                    }
                }
                
                
                ?>
                

                <div class="form-group">
                    <input type="email" name="email" id="email" class="form-control input-lg" placeholder="Email" value="" tabindex="1">
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Contraseña" tabindex="1">
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <input type="password" name="passwordConfirm" id="passwordConfirm" class="form-control input-lg" placeholder="Confirmar contraseña" tabindex="1">
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Guardar" class="btn btn-primary btn-block btn-lg" tabindex="2"></div>
                </div>
            </form>
        </div>
    </div>


</div>

<?php
//include header template
require('layout/footer.php');
?>
