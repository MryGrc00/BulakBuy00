
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verification Code</title>
  <link rel="stylesheet" href="../css/modal.css">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pzjw8f+uaex3+ihrbIk8mz07tb2F4F5ssx6kl5v5PmUGp1ELjF8j5+zM1a7z5t2N" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

  <style>
        /* Import Google font - Poppins */
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
      }
      body{
          background-color:#f5f5f5;
      }

      .container1{
          width:500px;
          margin:auto;
          margin-top: 300px;
          font-family: 'Poppins';
          background-color: white;
          box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
          border-radius: 10px;
          padding:20px;
          padding-bottom:70px;
      }
      .enter {
        color:#666;
        font-size: 17px;
        text-align: center;
        letter-spacing: 0.1rem;
        margin-top: 30px;
        font-weight: 500;
      }
      form .input-field {
        flex-direction: row;
        column-gap: 25px; 
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 40px;


      }
      .input-field input {
        height: 49px;
        width: 49px;
        border-radius: 6px;
        outline: none;
        font-size: 15px;
        text-align: center;
        border: 1px solid #ddd;
        color:#666;
      
      }
      .input-field input:focus {
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
      }
      .input-field input::-webkit-inner-spin-button,
      .input-field input::-webkit-outer-spin-button {
        display: none;
      }
      .center-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 40px;
      }
      form button {
   
        background-color: #65A5A5;
        color:white;
        width: 420px;
        padding:10px;
        border-radius:10px;
        margin-top: 10px;
        margin-top: 10px;
        letter-spacing: 0.1rem;
        font-size: 15px;
        border:none;
      }

      form button.active {
        border:none;
        outline:none;
        pointer-events: auto;
      }


      .error-message {
        color: #721c24;
        padding: 8px 10px;
        text-align: center;
        border-radius: 5px;
        background: #f8d7da;
        font-size: 15px;
        border: 1px solid #f5c6cb;
        margin-bottom: 20px;
        margin-top: 30px;
        display: none;
        font-weight: 300;
      }

      .error-message.visible {
        display: block;
      }
      .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
      }
      .modal-content {
          background-color: #fff;
          margin: 17.3% auto;
          padding: 20px;
          border: 1px solid #888;
          border-radius: 10px;
          width: 100%;
          max-width: 330px;
          text-align: center;
      }
      
      .bi-check-circle{
          font-size: 50px;
          color:#65A5A5;
          margin:auto;
          margin-top:5%;
      }
      .confirmed{
          font-size: 16px;
          margin-top:10px;
          font-weight: 500;
          color:#555;
          letter-spacing: 0.1rem;
      }

      .c-shopping{
        background-color: #65A5A5;
        color:white;
        width:270px;
        padding:7px;
        border-radius:10px;
        margin-top: 30px;
        letter-spacing: 0.1rem;
        font-size: 15px;
      }
      .c-shopping:focus{
          outline:none;
          border:none;
      }
      @media (max-width: 768px) {
        body{
          background-color:transparent;
        }
        .row img{
            margin:auto;
            width:200px;
            height:80px;
        }
        .container1{
           margin:auto;
            margin-top:90px;
            font-family: 'Poppins';
            padding:20px;
            padding-bottom:30px;
            box-shadow: none;
            border-radius: none;
            width: 375px;
        }
        .enter {
          color:#666;
            font-size: 15px;
            text-align: center;
            letter-spacing: 0.1rem;
            margin-top: 10px;
            font-weight: 500;
      }
      form .input-field {
        flex-direction: row;
        column-gap: 15px; 
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 50px;


      }
      .input-field input {
        height: 40px;
        width: 40px;
        border-radius: 6px;
        outline: none;
        font-size: 13px;
        text-align: center;
        border: 1px solid #ddd;
        color:#666;
      
      }
      .input-field input:focus {
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
      }
      .input-field input::-webkit-inner-spin-button,
      .input-field input::-webkit-outer-spin-button {
        display: none;
      }
      .center-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 40px;

      }
      form button {
   
        background-color: #65A5A5;
        color:white;
        width: 350px;
        padding:7px;
        border-radius:10px;
        margin-top: 10px;
        letter-spacing: 0.1rem;
        font-size: 13px;
      }
      form button.active {
        border:none;
    
        pointer-events: auto;
      }


      .error-message {
        color: #721c24;
        padding: 8px 10px;
        text-align: center;
        border-radius: 5px;
        background: #f8d7da;
        font-size: 13px;
        border: 1px solid #f5c6cb;
        margin-bottom: 25px;
        display: none;
        font-weight: 300;
      }

      .error-message.visible {
        display: block;
      }
      .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
      }
      .modal-content {
          background-color: #fff;
          margin: 50% auto;
          padding: 20px;
          border: 1px solid #888;
          border-radius: 10px;
          width: 75%;
          max-width: 300px;
          text-align: center;
      }
      
      .bi-check-circle{
          font-size: 40px;
          color:#65A5A5;
          margin:auto;
          margin-top:5%;
      }
      .confirmed{
          font-size: 13px;
          margin-top:10px;
          font-weight: 500;
          color:#555;
          letter-spacing: 0.1rem;
      }

      .c-shopping{
        background-color: #65A5A5;
        color:white;
        width:200px;
        padding:7px;
        border-radius:10px;
        margin-top: 30px;
        letter-spacing: 0.1rem;
        font-size: 13px;
      }
      .c-shopping:focus{
          outline:none;
          border:none;
      }
      }
  </style>
</head>
<body>

<div class="container1">

    <h4 class="enter">Enter OTP Code</h4>
    <form id="otp-form" action="php/verify_otp.php" method="post">
        <div class="error-message"></div>
        <div class="input-field">
            <input type="number" maxlength="1"/>
            <input type="number" maxlength="1" disabled />
            <input type="number" maxlength="1" disabled />
            <input type="number" maxlength="1" disabled />
            <input type="number" maxlength="1" disabled />
            <input type="number" maxlength="1" disabled />
        </div>
        <div class="center-container">
          <button type="submit" id="verify-button">Verify OTP</button>
        </div>
    </form>
    <div id="emailverified" class="modal">
         <div class="modal-content">
            <i class="bi bi-check-circle"></i>
            <h2 class="confirmed">Email Confirmed !</h2>
            <a href="login.php"><button class="c-shopping">Continue Login</button></a>
          </div>
      </div>
      
</div>

<script src="js/verify_otp.js"></script>

</body>
</html>