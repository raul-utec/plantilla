<?php 
require "includes/header.php"; 
require "config/config.php";

if(isset($_POST['submit'])){
    // Verifica si hay campos vacíos
    if(empty($_POST['email']) || empty($_POST['password'])) {
        echo "<script>alert('One or more inputs are empty');</script>";
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            // Prepara la consulta SQL para evitar inyecciones SQL
            $login = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $login->bindParam(':email', $email);
            $login->execute();
            $fetch = $login->fetch(PDO::FETCH_ASSOC);

            // Valida el email y la contraseña
            if($login->rowCount() > 0) {
                if(password_verify($password, $fetch['mypassword'])) {
                    echo "LOGGED IN";
                    $_SESSION['username']= $fetch['username'];
                    $_SESSION['email']= $fetch['email'];
                    $_SESSION['user_id']= $fetch['id'];
                    $_SESSION['username']= $fetch['username'];
                    echo "<script>window.location.href='".APPURL."';</script>";

                    // Aquí puedes manejar la redirección a otra página o iniciar sesión en la sesión del usuario.
                    // session_start();
                    // $_SESSION['user_id'] = $fetch['id'];
                    // header("Location: dashboard.php");
                    // exit();
                } else {
                    echo "<script>alert('Email or password is incorrect');</script>";
                }
            } else {
                echo "<script>alert('Email or password is incorrect');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
}
?>
    <div id="page-content" class="page-content">
        <div class="banner">
            <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('assets/img/bg-header.jpg');">
                <div class="container">
                    <h1 class="pt-5">
                        Login Page
                    </h1>
                    <p class="lead">
                        Lo bueno vuelve
                    </p>

                    <div class="card card-login mb-5">
                        <div class="card-body">
                            <form class="form-horizontal" method="POST" action="login.php">
                                <div class="form-group row mt-3">
                                    <div class="col-md-12">
                                        <input class="form-control" name="email" type="text" required="" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input class="form-control" name="password" type="password" required="" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12 d-flex justify-content-between align-items-center">
                                        
                                    <!-- <div class="checkbox">
                                            <input id="checkbox0" type="checkbox" name="remember">
                                            <label for="checkbox0" class="mb-0"> Remember Me? </label>
                                        </div> -->
                                        <!-- <a href="login.html" class="text-light"><i class="fa fa-bell"></i> Forgot password?</a> -->
                                    </div>
                                </div>
                                <div class="form-group row text-center mt-4">
                                    <div class="col-md-12">
                                        <button type="submit" name="submit" class="btn btn-primary btn-block text-uppercase">Log In</button>
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