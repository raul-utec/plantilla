<?php

if(!isset($_SERVER['HTTP_REFERER'])){
    header('Location: http://localhost/freshcery/index.php');
    exit;
}


try {
    define("HOST", "localhost");
    define("DBNAME", "freshcery");
    define("USER", "root");
    define("PASS", "");

    // Crear una nueva conexión PDO
    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME . ";charset=utf8", USER, PASS);
    
    // Configurar PDO para que lance excepciones en caso de error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // echo "Connected successfully";
} catch (PDOException $e) {
    // Mostrar mensaje de error
    echo "Connection failed: " . $e->getMessage();
}
?>