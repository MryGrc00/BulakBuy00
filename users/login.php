


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" /> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity= "sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <title>Login Account</title>
    <style>
         body{
            background-color:#f5f5f5;
        }
        .container{
            width:500px;
            margin:auto;
            margin-top: 180px;
            font-family: 'Poppins';
            background-color: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding:20px;
            padding-bottom:70px;
        }

        .row img{
            margin:auto;
            width:230px;
            height:90px;
        }
        .login{
            color:#666;
            font-size: 17px;
            text-align: center;
            letter-spacing: 0.1rem;
            margin-top: 15px;
            font-weight: 500;
        }
        .form-control {
            /* Add general styling for form controls here */
            padding: 20px;
            border:none;
            width: 440px;
            background-color: #F5F5F5;
            border-radius:10px;
            letter-spacing: 0.1rem;
            color:#888;
            margin-top: 10px;
            outline: none !important;
        }
        .form-control::placeholder {
            font-size: 15px;
            color:#A0A0A0;
        }
        .form-control:focus {
            border:1px solid #fefefe;
            outline:none;
        }
        .btn{
            background-color: #65A5A5;
            color:white;
            width:440px;
            padding:7px;
            border-radius:10px;
            margin-top: 10px;
            margin-top: 10px;
            letter-spacing: 0.1rem;
            font-size: 16px;
        }
        .btn:hover{
            color:#fefefe;
        }
        .password-input-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 45%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Eye icon styles */
        .toggle-password i {
            font-size: 18px;
            color: #999;
        }

        /* Style the eye icon when password is revealed */
        .password-revealed i {
            color: #33b5e5;
        }
        .button{
            margin-top: -20px;
        }
        .container1{
            margin-top: -10px;
        }
        .container1 .row a{
            color:#888;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }
        .error-text{
            color: #721c24;
            padding: 8px 10px;
            text-align: center;
            border-radius: 5px;
            background: #f8d7da;
            font-size: 15px;
            border: 1px solid #f5c6cb;
            margin-bottom: 20px;
            display: none;
            font-weight: 300;
        }
        @media (max-width: 768px) {
            body{
            background-color:transparent;
        }
        .container{
            margin:auto;
            margin-top:70px;
            font-family: 'Poppins';
            padding:20px;
            padding-bottom:30px;
            box-shadow: none;
            border-radius: none;
        }

        .row img{
            margin:auto;
            width:200px;
            height:80px;
        }
        .login{
            color:#666;
            font-size: 15px;
            text-align: center;
            letter-spacing: 0.1rem;
            margin-top: 10px;
            font-weight: 500;
        }
        .form-control {
            /* Add general styling for form controls here */
            padding: 20px;
            border:none;
            width: 350px;
            background-color: #F5F5F5;
            border-radius:10px;
            letter-spacing: 0.1rem;
            color:#888;
            margin-top: 20px;
            outline: none !important;
            font-size: 13px;
        }
        .form-control::placeholder {
            font-size: 13px;
            color:#A0A0A0;
        }
        .form-control:focus {
            border:1px solid #fefefe;
            outline:none;
        }
        .btn{
            background-color: #65A5A5;
            color:white;
            width:350px;
            padding:7px;
            border-radius:10px;
            margin-top: 30px;
            letter-spacing: 0.1rem;
            font-size: 15px;
        }
        .btn:hover{
            color:#fefefe;
        }
        .password-input-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 45%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Eye icon styles */
        .toggle-password i {
            font-size: 18px;
            color: #999;
        }

        /* Style the eye icon when password is revealed */
        .password-revealed i {
            color: #33b5e5;
        }
        .button{
            margin-top: -20px;
        }
        .container1{
            margin-top: -10px;
        }
        .container1 .row a{
            color:#888;
            font-size: 13px;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
        }
        .error-text{
            color: #721c24;
            padding: 8px 10px;
            text-align: center;
            border-radius: 5px;
            background: #f8d7da;
            font-size: 15px;
            border: 1px solid #f5c6cb;
            margin-bottom: 25px;
            display: none;
            font-weight: 300;
        }
        }
    </style>
</head>
<body>
  
<div class="container">
    <div class="container-fluid mt-3">
        <header>
            <div class="row">
                <img src="php/images/logo.png" alt="logo" class="img-fluid rounded float-start">
            </div>
        </header>
    </div>
    <main>
        <div class="container-fluid ">
            <div class="row">
                <p class="login">Login to your Account</p>
                <section class="form login">
                <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text"></div>
                <div class="form-group">
                    <input type="text" class="form-control" name="input" id="input" placeholder="Username or Email" required>
                </div>
                    <div class="form-group">
                        <div class="password-input-container mt-4">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                            <span class="toggle-password" id="togglePassword">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                        <br><br>
                        <div class="button">
                            <input type="submit" name="submit" class="btn"  value="Login"></button>
                        </div>
                        <br>
                    </div>
                </form>                
                <div class="container1">
                    <div class="row">
                        <br>
                        <a href="signup.php" class="link-dark pt-2 text-center">Don't have an account?</a>
                      <a href="forgot_pass.php" class="link-dark pt-2 text-center">Forgot your password?</a>
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
