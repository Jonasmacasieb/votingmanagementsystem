<head>
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/all.min.css">
</head>
<style>
    .logo img {
        width: 80px;
        height: 70px;
        object-fit: cover;
        border-radius: 50%;
        /* Ensure the image inside is also a circle */
    }

    .perps {
        font-family: Arial, sans-serif;
        color: #fff;
        font-size: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        margin-top: 10px;
        /* Adjust margin for better alignment */
    }

    .adlogout {
        font-family: Arial, sans-serif;
        color: #fff;
        font-size: 20px;
        text-decoration: none;
        transition: background-color 0.3s, color 0.3s;
        margin-top: 10px;
        /* Adjust margin for better alignment */
    }

    .adlogout:hover {
        color: #000;
    }

    .float-right img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
    }

    /* Media queries for responsiveness */
    @media (max-width:768px) {
        .logo img {
            width: 50px;
            position: absolute;
            left: -15px;
            top: -15px;

        }

        .navbar {
            height: 80px;

        }



        .adlogout {
            font-size: 14px;

            position: relative;
            right: 30px;
            top: 10px;

        }

        .perps {
            font-size: 12px;

            position: absolute;
            left: 50px;
            top: -10px;


        }

        .float-right img {
            width: 30px !important;
            /* Adjust the width of the user image */
            height: 30px !important;
            /* Adjust the height of the user image */
            position: absolute;
            right: 20px;
            /* Adjust the right offset */
            top: 22px;
            /* Adjust the top offset */
            border-radius: 50%;
            /* Ensure it remains circular */
        }



    }
</style>

<nav class="navbar navbar-dark fixed-top" style="padding: 0; background: linear-gradient(to left, #3498db, #87ceeb); box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <div class="container-fluid mt-2 mb-2">
        <div class="col-lg-12">
            <div class="col-md-1 float-left" style="display: flex;">
                <div class="logo mr-4">
                    <img src="perps logo.png" alt="Logo Image">
                </div>
            </div>

            <label class="perps mt-3">PERPETUAL HELP COLLEGE OF PANGASINAN</label>

            <div class="mr-4 float-right">

                <img src="<?php echo isset($_SESSION['login_picture_path']) ? $_SESSION['login_picture_path'] : 'default_image.jpg'; ?>" alt="User Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">

                <div class=" float-right text-white mt-3">
                    <a href="ajax.php?action=logout" class="adlogout"><?php echo isset($_SESSION['login_name']) ? $_SESSION['login_name'] : ''; ?> <i class="fa fa-power-off"></i></a>
                </div>
            </div>
        </div>
    </div>
</nav>