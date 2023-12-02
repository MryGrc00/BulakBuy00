
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
    <title>Register Account</title>

    <style>
         body{
            background-color:#f5f5f5;
        }
        .container{
          
            width:500px;
            margin:auto;
            margin-top: 70px;
            font-family: 'Poppins';
            background-color: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding:20px;
            padding-bottom:40px;
            padding-top: 20px;
        }

        .row img{
            margin:auto;
            width:230px;
            height:90px;
        }
        .signup{
            color:#666;
            font-size: 17px;
            text-align: center;
            letter-spacing: 0.1rem;
            margin-top: 15px;
            font-weight: 500;
        }
        .form-control{
            /* Add general styling for form controls here */
            padding: 20px;
            border:none;
            width: 440px;
            background-color: #F5F5F5;
            border-radius:10px;
            letter-spacing: 0.1rem;
            color:#888;
            margin-top: 10px;
            font-size: 13px;
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
        .form-control1{
             font-size: 13px;
            padding: 8px;
            margin-bottom: 10px;
            border:none;
            width: 440px;
            background-color: #F5F5F5;
            border-radius:10px;
            letter-spacing: 0.1rem;
            color:#888;
       
            outline: none !important;
        }
        .form-control1::placeholder {
            font-size: 15px;
            color:#A0A0A0;
            padding-left: 8px;
        }
        .form-control1:focus {
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
        }
        .btn:hover{
            color:#fefefe;
        }
        .password-input-container {
            position: relative;
            margin-bottom: -10px;
        }

        .toggle-password {
            position: absolute;
            top: 35%;
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
        .form-select {
            border:none;
            width: 210px;
            padding: 8px;
            background-color: #F5F5F5;
            border-radius:10px;
            letter-spacing: 0.1rem;
            color:#A0A0A0;
            margin-top: 10px;
            outline: none !important;
            padding-left:15px;
            font-size:15px;
        }

        .form-select:hover {
            background-color: #fefefe; 
            
        }
        .two {
            margin-bottom: 10px; /* Adjust as needed */
        }
        .form-control {
            width: 210px;
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

        .signup{
            color:#666;
            font-size: 20px;
            text-align: center;
            letter-spacing: 0.1rem;
            margin-top: -35px;
            font-weight: 500;
        }
        .form-control{
            /* Add general styling for form controls here */
            padding: 20px;
            border:none;
            width: 350px;
            background-color: #F5F5F5;
            border-radius:10px;
            letter-spacing: 0.1rem;
            color:#888;
            margin-top: 10px;
            margin-bottom: 15px;
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
        .form-control1{
          
          padding: 8px;
          margin-bottom: 10px;
          border:none;
          width: 350px;
          background-color: #F5F5F5;
          border-radius:10px;
          letter-spacing: 0.1rem;
          color:#888;

          outline: none !important;
      }
      .form-control1::placeholder {
          font-size: 15px;
          color:#A0A0A0;
          padding-left: 8px;
      }
      .form-control1:focus {
          border:1px solid #fefefe;
          outline:none;
      }
        .btn{
            background-color: #65A5A5;
            color:white;
            width:250px;
            padding:7px;
            border-radius:10px;
            margin-top: 10px;
            letter-spacing: 0.1rem;
        }
        .btn:hover{
            color:#fefefe;
        }
        .password-input-container {
            position: relative;
            margin-bottom: -10px;
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
        .form-select {
            border:none;
            width: 350px;
            padding: 8px;
            background-color: #F5F5F5;
            border-radius:10px;
            letter-spacing: 0.1rem;
    
            margin-top: -5px;
            margin-bottom: 15px;
            outline: none !important;
            padding-left:15px;
            font-size:15px;
            color:#A0A0A0;
        }

        .form-select:hover {
            background-color: #fefefe; 
        
        }
        #zipcode{
            margin-top: -5px;
        }
        #address{
            margin-bottom: 0;
        }
        .two {
                display: flex; /* Use flexbox for horizontal alignment */
                flex-wrap: wrap; /* Allow flex items to wrap to the next line if needed */
               
            }

            .form-group {
                flex: 1; /* Each form-group takes equal space */
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
        <div class="container-fluid">
            <div class="row fw-semibold">
             <p class="signup">Create your Account</p>
                <br><br>
             <section class="form signup">
                <form action="#" method="POST"class="row" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text"></div>
                  <div class="two">
                    <div class="form-group">
                        <div class="row gx-3 flex-row">
                            <div class="col">
                                <input type="text" class="form-control" name="fname" id="fname" placeholder="First Name" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="lname" id="lname" placeholder="Last Name" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row gx-3 flex-row">
                            <div class="col">
                                <select class="form-select" name="role" id="role" required>
                                    <option selected disabled value="">Select Type</option>
                                    <option value="seller">Seller</option>
                                    <option value="customer">Customer</option>
                                    <option value="arranger">Arranger</option>
                                </select>
                            </div>
                            <div class="col">
                                <input type="tel" class="form-control" name="phone" id="phone" placeholder="09...." required maxlength="11" minlength="11" pattern="^09\d{9}$">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row gx-3 flex-row">
                            <div class="col">
                                <input type="number" class="form-control" name="zipcode" id="zipcode" placeholder="Zip Code" required>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="address" id="address" placeholder="Address" required>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <input type="email" class="form-control1" name="email" id="email" placeholder="Email" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <input type="text" class="form-control1" name="username" id="username" placeholder="Username" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <div class="password-input-container">
                            <input type="password" class="form-control1" name="password" id="password" placeholder="Password" required>
                            <span class="toggle-password" id="togglePassword">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                        <br>
                        <div class="password-input-container">
                            <input type="password" class="form-control1" name="cpassword" id="cpassword" placeholder="Confirm Password" required>
                            <span class="toggle-password" id="toggleCPassword">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                        <br><br>
                          <div class="button">
                             <input type="submit" class="btn" name="submit" value="Register">
                         </div>
                        <br>
                    </div>
                </form>                
                <div class="container1">
                    <div class="row">
                        <br><br>
                      <a href="login.php" class="link-dark pt-2 text-center">Already have an account?</a>
                    </div>
                </div>
              </section>
            </div>
        </div>
        </div> 
    </main>

    <script src="js/signup.js"></script>
    <script src="js/show-hide-pass.js"></script>




</body>

</html>


