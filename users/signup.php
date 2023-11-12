
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
</head>
<body>

  <div class="container">
    <div class="container-fluid mt-5">
        <header>
            <div class="row">
                <h1 style="font-size: 37px; font-family: 'Poppins';">Welcome to <br></h1>
                <img src="../images/logo.png" alt="logo" class="img-fluid rounded float-start" style="height: 75px; width: 180px;">
            </div>
        </header>
    </div>
    <main>
        <div class="container-fluid mt-5">
            <div class="row fw-semibold">
                <h2>Sign Up</h2>
                <p>Create an account </p>
                <br><br>
             <section class="form signup">
                <form action="#" method="POST"class="row" enctype="multipart/form-data" autocomplete="off">
                <div class="error-text"></div>
                    <div class="form-group">
                      <div class="row gx-3">
                        <div class="col">
                          <input type="text" class="form-control bg-light rounded" name="fname" id="fname" placeholder="First Name" required>
                          <div class="valid-feedback">
                            Looks good!
                          </div>
                        </div>
                        <div class="col">
                          <input type="text" class="form-control bg-light rounded" name="lname" id="lname" placeholder="Last Name" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row gx-3">
                        <div class="col">
                        <select class="form-select bg-light rounded" name="role" id="role" required>
                          <option selected disabled value="">Select Type</option>
                          <option value="seller">Seller</option>
                          <option value="customer">Customer</option>
                          <option value="arranger">Arranger</option>
                        </select>
                        </div>
                        <div class="col">
                          <input type="tel" class="form-control bg-light rounded" name="phone" id="phone" placeholder="09...." required>
                        </div>
                      </div> 
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <input type="email" class="form-control bg-light rounded" name="email" id="email" placeholder="Email" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <input type="text" class="form-control bg-light rounded" name="address" id="address" placeholder="Address" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <input type="text" class="form-control bg-light rounded" name="zipcode" id="zipcode" placeholder="Zip Code" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <input type="text" class="form-control bg-light rounded" name="username" id="username" placeholder="Username" required>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <div class="password-input-container">
                            <input type="password" class="form-control bg-light rounded" name="password" id="password" placeholder="Password" required>
                            <span class="toggle-password" id="togglePassword">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                        <br>
                        <div class="password-input-container">
                            <input type="password" class="form-control bg-light rounded" name="cpassword" id="cpassword" placeholder="Confirm Password" required>
                            <span class="toggle-password" id="toggleCPassword">
                                <i class="bi bi-eye-slash"></i>
                            </span>
                        </div>
                        <br><br>
                          <div class="button">
                             <input type="submit" class="btn btn-primary w-100" name="submit" value="Register">
                         </div>
                        <br>
                    </div>
                </form>                
                <div class="container">
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


