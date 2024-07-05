<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Voting Management System</title>


  <?php include('./header.php'); ?>
  <?php
  session_start();
  if (isset($_SESSION['login_id']))
    header("location:index.php?page=home");
  ?>


  <style>
    * {
      padding: 0px;
      margin: 0px;
      font-family: arial;
    }

    #login {
      width: 100%;
      height: 100vh;
      background-image: url("newbglogo.jpeg");
      background-size: cover;
      background-repeat: no-repeat;
      position: absolute;

    }

    .center {
      width: 380px;
      height: auto;
      margin: 0 auto;
      margin-top: 150px;
      background-color: #fff;
      box-shadow: 2px 2px 16px 0px #757575;
      padding: 40px;
      border-radius: 24px;
      position: relative;
    }

    .center h2 {
      font-size: 25px;
      text-align: center;
      color: #000;
      padding-bottom: 25px;
      padding-top: 30px;
      font-family: 'Times New Roman', serif;
    }

    .its :hover {
      opacity: 0.8;
    }


    .fl {
      width: 100%;
    }

    .itpw {
      width: 100%;
      border: none;
      border-radius: 24px;
      font-size: 1rem;
      font-family: 'Raleway', sans-seriff;
      background-color: gainsboro;
      padding: 13px 10px;
      margin: 5px 0px;

    }

    .its {

      background-color: #E0E0E0;
      background-image: linear-gradient(19deg, #21D4FD 0%, #B721FF 100%);
      width: 100%;
      color: white;
      border: none;
      margin-top: 35px;
      cursor: pointer;
      padding: 10px;
      font-family: 'Raleway', sans-seriff;
      font-size: 1.3rem;
      font-weight: bold;
      border-radius: 24px;
      transition: 0.25s;

    }

    .itpw:focus {
      border-bottom: 3px #21D4FD solid;
      color: #004d40
    }

    .its:hover,
    .its:focus {
      opacity: 0.7;
      cursor: pointer;
    }

    .center p {
      margin: 20px 0;
      text-align: center;
      font-size: 14px;
    }

    .center p a {
      color: #757575;
    }

    /* .logo{
position:absolute;
top:-90px;
left:110px;

} */

    .logo {
      position: absolute;
      top: -80px;
      left: 50%;
      transform: translateX(-50%);
      /* Center the logo horizontally */
    }


    @media screen and (min-width:1500px) {

      .center {
        width: 350px;
      }


    }

    @media screen and (max-width:900px) {
      #login {
        background-size: 100% 100%;
      }

      .its {
        width: 100%;
      }

      .itpw {
        font-size: 14px;
        width: 90%;
        padding: 13px 3%;
      }

      .center {
        width: 230px;
      }


      .center p {
        font-size: 12px;
      }


    }

    @media screen and (max-width:350px) {
      .center {
        padding: 20px;
        width: 75%;
      }
    }

    @media screen and (min-width: 601px) and (max-width: 1024px) {
      .center {
        margin-top: 15%;
      }
    }

    @media screen and (max-width: 600px) {
      .center {
        margin-top: 10%;
      }

      .logo {
        top: -50px;
        /* Adjust the top position for better placement on small screens */
      }
    }
  </style>



</head>

<body>

  <div id="login">

    <div class="center">
      <div class="logo">
        <img src="perps logo.png" style="width:150px;
    height:150px;" alt="">
      </div>
      <h2>Voting Management system with Facial Recognition </h2>
      <!-- <h2>for Perpetual Help College of Pangasinan</h2> -->
      <form id="login-form" class="fl" action="" method="post">
        <input class="itpw" type="text" name="username" placeholder="School ID" maxlength="11" oninput="formatSchoolID(this)">
        <br>
        <input class="itpw" type="password" name="password" placeholder="Password"><br>
        <input class="its" type="submit" name="login" value="Proceed">
      </form>


    </div>
  </div>

</body>
<script>
  function formatSchoolID(input) {
    var schoolID = input.value.replace(/\D/g, '');
    if (schoolID.length > 2) {
      schoolID = schoolID.substring(0, 2) + '-' + schoolID.substring(2);
    }
    if (schoolID.length > 7) {
      schoolID = schoolID.substring(0, 7) + '-' + schoolID.substring(7);
    }
    input.value = schoolID;
  }
</script>
<script>
  $('#login-form').submit(function(e) {
    e.preventDefault()
    $('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');
    if ($(this).find('.alert-danger').length > 0)
      $(this).find('.alert-danger').remove();
    $.ajax({
      url: 'ajax.php?action=loginadmin',
      method: 'POST',
      data: $(this).serialize(),
      error: err => {
        console.log(err)
        $('#login-form button[type="button"]').removeAttr('disabled').html('Login');

      },
      success: function(resp) {
        if (resp == 1) {
          location.href = 'index.php?page=home';
        } else {
          $('#login-form').prepend('<div class="alert alert-danger">School ID is Incorrect</div>')
          $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
        }
      }
    })
  })
</script>




</html>