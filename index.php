<?php 
  session_start(); 
    // Your UserManagement class with getCurrentUserId() method
    require_once 'server.php';

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CompileCrew - Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>

    <!-- header section starts  -->

    <header class="header">
        <a href="index.php" class="logo">
            <i class="fas fa-shopping-basket"></i> <i class="fa-solid fa-right-from-bracket"></i> Online Store
        </a>
        <div class="d-flex gap-2 align-items-center">
            <div class="icons">
                <div class="" id="cart-btn" name="cart_button">
                    <a href="cart.php" class="fas fa-shopping-cart text-decoration-none"></a>
                </div>

            </div>
            <div class="icons">
                <div class="w-auto" id="cart-btn"><a href="index.php?logout='1'" class="text-decoration-none">LogOut</a>
                </div>
            </div>
        </div>
    </header>

    <!-- header section ends -->

    <div class="container" style="margin-top: 120px">
        <div class="row">
        <!-- 
            PHP code Logic implementation goes here
        -->

            <div class="col-12 col-md-6 col-lg-4 mt-3">
                <form action="" method="post" name="add_cart1">
                    <div class="card productCard">
                        <img src="" class="card-img-top w-100" alt="Product Image"
                            style="height: 350px">
                        <div class="card-body">
                            <h5 class="card-title">title</h5>
                            <p class="card-text">description</p>
                            <p class="card-text">Price</p>
                            <button class="btn btn-primary" type="submit"
                                name="add_cart1">Buy Now</button>
                            <input type='hidden' name='product_id' value='1'>
                        </div>
                    </div>
                </form>
            </div>        
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>