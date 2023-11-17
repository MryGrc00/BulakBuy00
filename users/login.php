


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" /> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity= "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>

    
    <title>Login Account</title>
</head>
<body>
  
<div class="container">
    <div class="container-fluid mt-5">
        <header>
            <div class="row">
                <h1 style="font-size: 37px; font-family: 'Poppins';">Welcome to <br></h1>
                <img src="php/images/logo.png" alt="logo" class="img-fluid rounded float-start" style="height: 75px; width: 180px;">
            </div>
        </header>
    </div>
    <main>
        <div class="container-fluid mt-5">
            <div class="row fw-semibold">
                <h2>Login</h2>
                <p>This will help to keep your account safe </p>
                <section class="form login">
                <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text"></div>
                <div class="form-group">
                    <input type="text" class="form-control bg-light rounded" name="input" id="input" placeholder="Username or Email" required>
                </div>
                    <div class="form-group">
                        <div class="password-input-container">
                            <input type="password" class="form-control bg-light rounded" name="password" id="password" placeholder="Password" required>
                            <span class="toggle-password" id="togglePassword">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                        <br><br>
                        <div class="button">
                            <input type="submit" name="submit" class="btn btn-primary w-100"  value="Login"></button>
                        </div>
                        <br>
                    </div>
                </form>                
                <div class="container">
                    <div class="row">
                        <br>
                      <a href="signup.php" class="link-dark pt-2 text-center">Don't have an account?</a>
                      <a href="forgot_password.php" class="link-dark pt-2 text-center">Forgot your password?</a>
                    </div>
                </div>
                </section>

            </div>
        </div>
    </div>
    </main>


  
  <script src="js/login.js"></script>
  <script src="js/show-hide-pass.js"></script>


</body>
</html>
