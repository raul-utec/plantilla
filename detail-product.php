<?php 
require "includes/header.php";
require "config/config.php";

if(isset($_POST['submit'])){
    // Verifica si el formulario ha sido enviado. Esto se determina si la variable 'submit' está presente en el arreglo $_POST.

    // Asigna a las variables los valores enviados desde el formulario.
    $pro_id = $_POST['pro_id'];        // ID del producto
    $pro_title = $_POST['pro_title'];  // Título del producto
    $pro_image = $_POST['pro_image'];  // Imagen del producto
    $pro_price = $_POST['pro_price'];  // Precio del producto
    $pro_qty = $_POST['pro_qty'];      // Cantidad del producto
    $user_id = $_POST['user_id'];      // ID del usuario que está agregando el producto al carrito

    // Prepara una consulta SQL de inserción para agregar los datos a la tabla 'cart'.
    $insert = $conn->prepare("
        INSERT INTO cart(pro_id, pro_title, pro_image, pro_price, pro_qty, user_id)
        VALUES(:pro_id, :pro_title, :pro_image, :pro_price, :pro_qty, :user_id)
    ");

    // Ejecuta la consulta preparada, asignando los valores de las variables a los marcadores de posición (placeholders) en la consulta.
    $insert->execute(array(
        ":pro_id" => $pro_id,
        ":pro_title" => $pro_title,
        ":pro_image" => $pro_image,
        ":pro_price" => $pro_price,
        ":pro_qty" => $pro_qty,
        ":user_id" => $user_id
    ));

    // Si la ejecución es exitosa, el producto se agregará a la tabla 'cart' en la base de datos.
}


if(isset($_GET['id'])){
    $id = $_GET['id'];

    // Preparar la consulta para evitar inyecciones SQL
    $select = $conn->prepare("SELECT * FROM products WHERE status = 1 AND id = :id");
    $select->bindParam(':id', $id, PDO::PARAM_INT);
    $select->execute();

    // Obtener el producto
    $product = $select->fetch(PDO::FETCH_OBJ);
    
    // Verifica si se encontró el producto
    if ($product) {
        // Preparar la consulta para productos relacionados
        $relatedProducts = $conn->prepare("SELECT * FROM products WHERE status = 1 AND category_id = :category_id AND id != :id");
        $relatedProducts->bindParam(':category_id', $product->category_id, PDO::PARAM_INT);
        $relatedProducts->bindParam(':id', $id, PDO::PARAM_INT);
        $relatedProducts->execute();
        
        $allRelatedProducts = $relatedProducts->fetchAll(PDO::FETCH_OBJ);
        
//validating cart products
        
if(isset($_SESSION['user_id'])){
            $validate=$conn->query("SELECT * FROM cart WHERE pro_id='$id' AND user_id='$_SESSION[user_id]'");

            $validate->execute();

        }

    } else {
        echo "<script>alert('Product not found');</script>";
        // Redirigir a otra página si el producto no se encuentra
        // header("Location: products.php");
        // exit();
    }
} else {
    echo "<script>alert('No product ID provided');</script>";
    // Redirigir a otra página si no se proporciona un ID
    // header("Location: products.php");
    // exit();
}
?>


    <div id="page-content" class="page-content">
        <div class="banner">
            <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('assets/img/bg-header.jpg');">
                <div class="container">
                    <h1 class="pt-5">
                        The Meat Product Title
                    </h1>
                    <p class="lead">
                        Save time and leave the groceries to us.
                    </p>
                </div>
            </div>
        </div>
        <div class="product-detail">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="slider-zoom">
                            <a href="assets/img/<?php echo $product->image; ?>" class="cloud-zoom" rel="transparentImage: 'data:image/gif;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==', useWrapper: false, showTitle: false, zoomWidth:'500', zoomHeight:'500', adjustY:0, adjustX:10" id="cloudZoom">
                                <img alt="Detail Zoom thumbs image" src="assets/img/<?php echo $product->image; ?>" style="width: 100%;">
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <p>
                            <strong>Overview</strong><br>
                            <?php echo $product->description; ?>

                        </p>
                        <div class="row">
                            <div class="col-sm-6">
                                <p>
                                    <strong>Precio</strong> <br>
                                    <span class="price">S/. <?php echo $product->price; ?></span>
                                    <span class="old-price">S/.<?php echo ($product->price)*1.5; ?></span>
                                </p>
                            </div>
                           
                        </div>
                        <p class="mb-1">
                            <strong>Quantity</strong>
                        </p>
                        <form method="POST" id="form-data">
                        <div class="row">
                            <div class="col-sm-5">
                                <input class="form-control" type="text" name="pro_title" value="<?php echo $product->title;?>"  min="1" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="1">
                            </div>
                            <div class="col-sm-5">
                                <input class="form-control" type="text" name="pro_image" value="<?php echo $product->image;?>" min="1" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="1" >
                            </div>
                            <div class="col-sm-5">
                                <input class="pro_price form-control" type="text" name="pro_price" value="<?php echo $product->price;?>" min="1" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="1" >
                            </div>
                            <div class="col-sm-5">
                                <input class="form-control" type="text" name="user_id" value="<?php echo $_SESSION['user_id'];?>" min="1" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="1" >
                            </div>
                            <div class="col-sm-5">
                                <input class="form-control" type="text" name="pro_id" value="<?php echo $product->id;?>" min="1" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="1" >
                            </div>
                            <div class="col-sm-5">
                                <input class="form-control" type="text" name="pro_qty" value="<?php echo $product->quantity;?>" min="1" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="1" >
                            </div>
                        </div>
                    <?php if(isset($_SESSION['username'])): ?>   
                        <?php if($validate->rowCount()>0):?>
                            <button name="submit" type="submit" class="btn-insert mt-3 btn btn-primary btn-lg" disabled>
                                <i class="fa fa-shopping-basket" ></i> Added to Cart
                            </button>
                        <?php else: ?>
                        <button name="submit" type="submit" class="btn-insert mt-3 btn btn-primary btn-lg">
                            <i class="fa fa-shopping-basket" ></i> Add to Cart
                        </button>
                        <?php endif;?>
                    <?php else:?>
                            <div class="mt-5 alert alert-success bg-success text-white text-center">
                            <a href="login.php" style="color: white; text-decoration: none;">
                                Login to buy this product or add to cart
                                </a>
                            </div>
                    <?php endif;?>

                    </div>
                </div>
            </div>
        </div>

        <section id="related-product">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title">Related Products</h2>
                        <div class="product-carousel owl-carousel">
                            <?php foreach($allRelatedProducts as $products) :?>
                            <div class="item">
                                <div class="card card-product">
                                    <div class="card-ribbon">
                                        <div class="card-ribbon-container right">
                                            <span class="ribbon ribbon-primary">SPECIAL</span>
                                        </div>
                                    </div>
                                    <div class="card-badge">
                                        <div class="card-badge-container left">
                                            <span class="badge badge-default">
                                                Until <?php echo $products->exp_date;?> 
                                            </span>
                                            <span class="badge badge-primary">
                                                20% OFF
                                            </span>
                                        </div>
                                        <img src="assets/img/meats.jpg" alt="Card image 2" class="card-img-top">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            <a href="detail-product.html">Product Title</a>
                                        </h4>
                                        <div class="card-price">
                                            <span class="discount">Rp. 300.000</span>
                                            <span class="reguler">Rp. 200.000</span>
                                        </div>
                                        <a href="detail-product.html" class="btn btn-block btn-primary">
                                            Add to Cart
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
     
    </div>
    <?php require "includes/footer.php"; ?>


    <script>





$(document).ready(function() {
    $(".form-control").keyup(function() {
        var value = $(this).val();
        value = value.replace(/^(0*)/, ""); // Eliminar ceros iniciales
        if (value === "") {
            $(this).val(1); // Si el campo está vacío, asigna 1
        } else {
            $(this).val(value); // Asigna el valor corregido
        }
    });
});
        
$(document).ready(function() {
    // 1. Selecciona el botón con la clase .btn-insert y le asigna un evento click
    $(".btn-insert").on("click", function(e) {

        // 2. Evita que la acción por defecto del botón (enviar el formulario) ocurra
        e.preventDefault();

        // 3. Serializa los datos del formulario con el id #form-data
        var form_data = $("#form-data").serialize() + '&submit=submit';

        // 4. Envía los datos serializados a través de una solicitud AJAX
        $.ajax({
            url: "detail-product.php?id=<?php echo $id; ?>", // URL a la que se enviarán los datos
            method: "POST", // Método de envío
            data: form_data, // Datos del formulario
            success: function(response) { // Función que se ejecuta si la solicitud es exitosa
                alert('Product added to cart'); // Muestra una alerta indicando que el producto se ha añadido al carrito
                $(".btn-insert").html("<i class='fa fa-fa-shopping-basket'></i> Added to card").prop("disabled",true);
            }
        });
    });
});
        
    </script>
