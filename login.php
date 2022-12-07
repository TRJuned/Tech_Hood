<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Tech Hood</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Favicon -->
    <link href="img/th-short.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/signin.css" rel="stylesheet">
    
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-light py-1 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center h-100">
                    <a href="index.html">
                        <img class="img-fluid" src="img/th.png" alt="Tech Hood" width="200px">
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a href="about.html"class="btn btn-sm btn-light">About</a>
                    <a href="contact.html"class="btn btn-sm btn-light">Contact</a>
                    <a href="faq.html"class="btn btn-sm btn-light">FAQs</a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">My Account</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="login.php"><button class="dropdown-item" type="button">Sign in</button></a>
                            <a href="register.php"><button class="dropdown-item" type="button">Sign up</button></a>
                        </div>
                    </div>
                    <div class="btn-group mx-2">
                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">BDT</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item" type="button">USD</button>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown">English</button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button class="dropdown-item" type="button">বাংলা</button>
                        </div>
                    </div>
                    <div class="d-inline-flex align-items-center d-block d-lg-none">
                        <a href="" class="btn px-0 ml-2">
                            <i class="fas fa-heart text-dark"></i>
                            <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">0</span>
                        </a>
                        <a href="" class="btn px-0 ml-2">
                            <i class="fas fa-shopping-cart text-dark"></i>
                            <span class="badge text-dark border border-dark rounded-circle" style="padding-bottom: 2px;">0</span>
                        </a>
                    </div>
                    <div class="text-center d-none d-lg-flex">
                        <p class="m-0">Customer-Service<br><b>+8801772362414</b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5">
            <!-- Categories Start -->
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
                    <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Categories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
                    <div class="navbar-nav w-100">
                        <div class="nav-item dropdown dropright">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Laptop & Tablet <i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                <a href="" class="dropdown-item">Laptop</a>
                                <a href="" class="dropdown-item">Gaming Laptops</a>
                                <a href="" class="dropdown-item">Tablet PC</a>
                                <a href="" class="dropdown-item">Laptop Components</a>
                                <a href="" class="dropdown-item">Laptop Accessories</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown dropright">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Desktop<i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                <a href="" class="dropdown-item">Brand PC</a>
                                <a href="" class="dropdown-item">Gaming PC</a>
                                <a href="" class="dropdown-item">Budget PC</a>
                                <a href="" class="dropdown-item">Apple iMAC</a>
                                <a href="" class="dropdown-item">PC Combo</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown dropright">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Components<i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                <a href="" class="dropdown-item">Processor</a>
                                <a href="" class="dropdown-item">Motherboard</a>
                                <a href="" class="dropdown-item">CPU Cooler</a>
                                <a href="" class="dropdown-item">Graphics Card</a>
                                <a href="" class="dropdown-item">RAM</a>
                                <a href="" class="dropdown-item">Storage Device</a>
                                <a href="" class="dropdown-item">Power Supply</a>
                                <a href="" class="dropdown-item">CPU Casing</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown dropright">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">PC Accessories<i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                <a href="" class="dropdown-item">Mouse</a>
                                <a href="" class="dropdown-item">Keyboard</a>
                                <a href="" class="dropdown-item">Webcam</a>
                                <a href="" class="dropdown-item">Mousepad</a>
                                <a href="" class="dropdown-item">Casing Fan</a>
                                <a href="" class="dropdown-item">Gamepad</a>
                                <a href="" class="dropdown-item">Pendrive</a>
                                <a href="" class="dropdown-item">Memory Card</a>
                                <a href="" class="dropdown-item">Powerbank</a>
                                <a href="" class="dropdown-item">USB Hub</a>
                                <a href="" class="dropdown-item">Cable & Converter</a>
                                <a href="" class="dropdown-item">Thermalpaste</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown dropright">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Audio System<i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                <a href="" class="dropdown-item">Speakers</a>
                                <a href="" class="dropdown-item">Headsets</a>
                                <a href="" class="dropdown-item">Earphone</a>
                                <a href="" class="dropdown-item">Earbud</a>
                                <a href="" class="dropdown-item">Microphone</a>
                                <a href="" class="dropdown-item">Headphone Stands</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown dropright">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Monitor<i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                <a href="" class="dropdown-item">Asus</a>
                                <a href="" class="dropdown-item">HP</a>
                                <a href="" class="dropdown-item">Gigabyte</a>
                                <a href="" class="dropdown-item">Xiaomi</a>
                                <a href="" class="dropdown-item">Lenovo</a>
                                <a href="" class="dropdown-item">Samsung</a>
                                <a href="" class="dropdown-item">LG</a>
                                <a href="" class="dropdown-item">Dell</a>
                                <a href="" class="dropdown-item">MSI</a>
                                <a href="" class="dropdown-item">BenQ</a>
                                <a href="" class="dropdown-item">Razer</a>
                                <a href="" class="dropdown-item">Viewsonic</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown dropright">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Routers<i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                <a href="" class="dropdown-item">Asus</a>
                                <a href="" class="dropdown-item">TP-Link</a>
                                <a href="" class="dropdown-item">Tenda</a>
                                <a href="" class="dropdown-item">Xiaomi</a>
                                <a href="" class="dropdown-item">Walton</a>
                                <a href="" class="dropdown-item">D-Link</a>
                                <a href="" class="dropdown-item">LinkSYS</a>
                                <a href="" class="dropdown-item">Netis</a>
                                <a href="" class="dropdown-item">Netgear</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown dropright">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Gadgets<i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                <a href="" class="dropdown-item">Smartwatch</a>
                                <a href="" class="dropdown-item">Gimbal</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown dropright">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Cameras<i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                <a href="" class="dropdown-item">Action Camera</a>
                                <a href="" class="dropdown-item">Digital Camera</a>
                                <a href="" class="dropdown-item">Mirror Camera</a>
                                <a href="" class="dropdown-item">DSLR</a>
                                <a href="" class="dropdown-item">Security Camera</a>
                                <a href="" class="dropdown-item">Dashcam</a>
                                <a href="" class="dropdown-item">IP Camera</a>
                                <a href="" class="dropdown-item">Lenses</a>
                                <a href="" class="dropdown-item">Accessories</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown dropright">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Softwares<i class="fa fa-angle-right float-right mt-1"></i></a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0">
                                <a href="" class="dropdown-item">MS Office</a>
                                <a href="" class="dropdown-item">Windows</a>
                                <a href="" class="dropdown-item">Adobe</a>
                                <a href="" class="dropdown-item">Antivirus</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
 
            <!-- Categories End -->


            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <a href="index.html" class="bg-light d-block d-lg-none">
                        <span class=""><img src="img/th.png" alt="Tech Hood" width="200px"></span>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="index.html" class="nav-item nav-link">Home</a>
                            <a href="shop.html" class="nav-item nav-link">New Products</a>
                        </div>
                        <div class="col-lg-4 col-6 text-left">
                            <form action="">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search for products">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-transparent text-primary">
                                            <i class="fa fa-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="navbar-nav ml-auto py-0 d-none d-lg-block">
                            <a href="offers.html" class="btn px-0 ml-3 blink"><img src="img/hot-offers.png" alt="hot offers" height="24px"></a>
                            <a href="" class="btn px-0 ml-3">
                                <i class="fas fa-heart text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;">0</span>
                            </a>
                            <a href="cart.html" class="btn px-0 ml-3">
                                <i class="fas fa-shopping-cart text-primary"></i>
                                <span class="badge text-secondary border border-secondary rounded-circle" style="padding-bottom: 2px;">0</span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="index.html">Home</a>
                    <span class="breadcrumb-item active">My Account</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Breadcrump End -->

    <div class="wrapper form-container">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>




<!-- Footer Start -->
<div class="container-fluid bg-dark text-secondary mt-5 pt-5">
  <div class="row px-xl-5 pt-5">
      <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
          <h5 class="text-secondary text-uppercase mb-4">Address</h5>
          <!--<p class="mb-4"></p>-->
          <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>10 J/1,Tolarbagh gate-2, Mirpur-1, Dhaka-1216.</p>
          <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@paper-fly.com</p>
          <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+880 17723 63414</p>
      </div>
      <div class="col-lg-8 col-md-12">
          <div class="row">
              <div class="col-md-4 mb-5">
                  <h5 class="text-secondary text-uppercase mb-4">Our Collaborators</h5>
                  <div class="d-flex flex-column justify-content-start">
                      <a class="text-secondary mb-2" href="https://www.startech.com.bd/"><i class="fa fa-angle-right mr-2"></i>Startech</a>
                      <a class="text-secondary mb-2" href="https://www.techlandbd.com/"><i class="fa fa-angle-right mr-2"></i>Techland</a>
                      <a class="text-secondary mb-2" href="https://www.ryanscomputers.com/"><i class="fa fa-angle-right mr-2"></i>Ryans</a>
                      <a class="text-secondary mb-2" href="https://shop.daffodil-bd.com/"><i class="fa fa-angle-right mr-2"></i>Daffodil Computers</a>
                      <a class="text-secondary mb-2" href="https://www.globalbrand.com.bd/"><i class="fa fa-angle-right mr-2"></i>Global Brand BD</a>
                      <a class="text-secondary" href="https://casegallerybd.com/"><i class="fa fa-angle-right mr-2"></i>CaseGalleryBD</a>
                  </div>
              </div>
              <div class="col-md-4 mb-5">
                  <h5 class="text-secondary text-uppercase mb-4">Let Us Help</h5>
                  <div class="d-flex flex-column justify-content-start">
                      <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Manage Account</a>
                      <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Your Orders</a>
                      <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Your Cart</a>
                      <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Your Favourites</a>
                      <a class="text-secondary mb-2" href="#"><i class="fa fa-angle-right mr-2"></i>Payment Procedure</a>
                      <a class="text-secondary" href="contact.html"><i class="fa fa-angle-right mr-2"></i>Feedback</a>
                  </div>
              </div>
              <div class="col-md-4 mb-5">
                  <h5 class="text-secondary text-uppercase mb-4">Newsletter</h5>
                  <p>Join us! Get notified about all the latest technology, events and customer reviews from around the web</p>
                  <form action="">
                      <div class="input-group">
                          <input type="text" class="form-control" placeholder="Your Email Address">
                          <div class="input-group-append">
                              <button class="btn btn-primary">Sign Up</button>
                          </div>
                      </div>
                  </form>
                  <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>
                  <div class="d-flex">
                      <a class="btn btn-primary btn-square mr-2" href="https://twitter.com/juned_tr"><i class="fab fa-twitter"></i></a>
                      <a class="btn btn-primary btn-square mr-2" href="https://www.facebook.com/tr.juned"><i class="fab fa-facebook-f"></i></a>
                      <a class="btn btn-primary btn-square mr-2" href="https://www.linkedin.com/in/tr-juned-038906229/"><i class="fab fa-linkedin-in"></i></a>
                      <a class="btn btn-primary btn-square" href=""><i class="fab fa-instagram"></i></a>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
      <div class="col-md-6 px-xl-0">
          <p class="mb-md-0 text-center text-md-left text-secondary">
              &copy; <a class="text-primary" href="#">Domain</a>. All Rights Reserved. Designed
              by
              <a class="text-primary" href="about.html">Team Paper Fly</a>
          </p>
      </div>
      <div class="col-md-6 px-xl-0 text-center text-md-right">
          <img class="img-fluid" src="img/payments.png" alt="">
      </div>
  </div>
</div>
<!-- Footer End -->



  <!-- Back to Top -->
  <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>

  <!-- Contact Javascript File -->
  <script src="mail/jqBootstrapValidation.min.js"></script>
  <script src="mail/contact.js"></script>

  <!-- Template Javascript -->
  <script src="js/main.js"></script>
  <script src="js/signin.js"></script>

</body>
</html>