

<?php 

require "includes/header.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluye el archivo de configuración
require "config/config.php";

// Asegúrate de que APPURL esté definido
define("APPURL", "http://localhost/freshcery/");



if(isset($_POST['submit'])){
    // Verifica si hay campos vacíos
    if(empty($_POST['fullname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['username'])){
        echo "<script>alert('One or more inputs are empty');</script>";
    } else {
        // Verifica si las contraseñas coinciden
        if($_POST['password'] == $_POST['confirm_password']){
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $username = $_POST['username'];

            // Hashea la contraseña para mayor seguridad
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                // Inserta los datos en la base de datos
                $insert = $conn->prepare("INSERT INTO users(fullname, email, username, mypassword) 
                                          VALUES(:fullname, :email, :username, :mypassword)"); 
                $insert->execute([
                    ":fullname" => $fullname,
                    ":email" => $email,
                    ":username" => $username,
                    ":mypassword" => $hashed_password,
                    
                ]); 

                echo "<script>alert('Registration successful!');</script>";
                // Redirige al usuario al login después de registrarse (descomentar la siguiente línea si es necesario)
                header("Location: " . APPURL . "/login.php");
                exit();
            } catch (PDOException $e) {
                echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
            }
        } else {
            echo "<script>alert('Passwords do not match');</script>";
        }       
    }
}
?>



    <div id="page-content" class="page-content">
        <div class="banner">
            <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('assets/img/bg-header.jpg');">
                <div class="container">
                    <h1 class="pt-5">
                        Register Page
                    </h1>
                    <p class="lead">
                        Lo bueno vuelve
                    </p>

                    <div class="card card-login mb-5">
                        <div class="card-body">
                            <form class="form-horizontal" method="POST" action="register.php">
                                <div class="form-group row mt-3">
                                    <div class="col-md-12">
                                        <input class="form-control" name="fullname" type="text" required="" placeholder="Full Name">
                                    </div>
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-md-12">
                                        <input class="form-control" name="email" type="email" required="" placeholder="Email">
                                    </div>
                                </div>
                                
                                <div class="form-group row mt-3">
                                    <div class="col-md-12">
                                        <input class="form-control" name="username" type="text" required="" placeholder="Username">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input class="form-control" name="password" type="password" required="" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input class="form-control" name="confirm_password" type="password" required="" placeholder="Confirm Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                <!--    <div class="col-md-12">
                                        <div class="checkbox">
                                            <input id="checkbox0" type="checkbox" name="terms">
                                            <label for="checkbox0" class="mb-0">I Agree with <a href="terms.html" class="text-light">Terms & Conditions</a> </label>
                                        </div>
                                    </div>
-->    
                                </div>
                                <div class="form-group row text-center mt-4">
                                    <div class="col-md-12">
                                        <button type="submit" name="submit" class="btn btn-primary btn-block text-uppercase">Register</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require "includes/footer.php"; ?>