<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

include '../users/php/dbhelper.php'; 
$pdo = dbconnect();

$users = get_record("users","user_id",$user_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        @media (min-width: 576px) {
            .container {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {
            .container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .container {
                max-width: 960px;
            }
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 1140px;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12 order-md-1">
                <h4 class="mb-3">Billing address</h4>
                <?php if (!empty($users)): ?>
                <form class="needs-validation" method="POST" action="post.php">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">First name</label>
                            <input type="text" class="form-control" name="first_name" value="<?php echo $users['first_name']; ?>" >
                            <div class="invalid-feedback">Valid first name is required.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Last name</label>
                            <input type="text" class="form-control" name="last_name" value="<?php echo $users['last_name']; ?>">
                            <div class="invalid-feedback">Valid last name is required.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="mobile">Mobile</label>
                        <input type="number" class="form-control" name="phone" value="<?php echo $users['phone']; ?>">
                        <div class="invalid-feedback">Please enter a valid mobile number for shipping updates.</div>
                    </div>
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $users['email']; ?>">
                        <div class="invalid-feedback">Please enter a valid email address for shipping updates.</div>
                    </div>
                    <div class="mb-3">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" name="address" value="<?php echo $users['address']; ?>">
                        <div class="invalid-feedback">Please enter your shipping address.</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="zip">Zip</label>
                            <input type="text" class="form-control" name="zipcode" value="<?php echo $users['zipcode']; ?>">
                            <div class="invalid-feedback">Zip code required.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" name="amount" value="249" readonly>
                            <div class="invalid-feedback">Please enter the amount to be paid.</div>
                        </div>
                    </div>
                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
                </form>
                <?php else: ?>
                    <p>User not found.</p>
                <?php endif; ?>
            </div>
        </div>
        <footer class="my-3 pt-3 text-muted text-center text-small">
            <p class="mb-1">© <?php echo date("Y"); ?> BulakBuy.ph</p>
        </footer>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>

</html>
