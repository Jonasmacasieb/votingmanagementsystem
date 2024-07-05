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
        text-shadow: 2px 2px 4px yellow;

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

    .navbar {
        height: 85px;

    }

    /* Media queries for responsiveness */
    @media (max-width:768px) {
        .logo img {
            width: 50px;
            position: absolute;
            left: -10px;
            top: -10px;

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
            left: 55px;
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

        #manila-time {

            position: absolute;
            top: -30px;
            right: 30px;
        }


    }

    @media (min-width: 992px) {
        .logo img {
            width: 70px;
            height: 60px;
        }

        .perps {
            font-size: 24px;
            margin-top: 15px;
        }

        .adlogout {
            font-size: 24px;
            margin-top: 15px;
        }

        .float-right img {
            width: 60px;
            height: 60px;
        }

        #manila-time {

            position: absolute;
            top: 35px;
            right: 45px;
        }
    }
</style>

<nav class="navbar navbar-dark fixed-top" style="padding: 0; background: #00008B; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
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

                <div id="manila-time" class="float-right text-white mt-3"></div>
                <div class=" float-right text-white mt-3">
                    <a href="ajax.php?action=logout" class="adlogout"><?php echo isset($_SESSION['login_name']) ? $_SESSION['login_name'] : ''; ?> <i class="fa fa-power-off"></i></a>
                </div>
            </div>
        </div>
    </div>
</nav>



<!-- Add an empty element with an id to display the time -->


<!-- Add the following JavaScript code at the end of your HTML body -->
<script>
    function getManilaTime() {

        var now = new Date();

        // Set the time zone to Manila (Philippines Standard Time)
        var manilaTime = new Date(now.toLocaleString("en-US", {
            timeZone: "Asia/Manila"
        }));

        // Extract hours, minutes, and seconds
        var hours = manilaTime.getHours();
        var minutes = manilaTime.getMinutes();
        var seconds = manilaTime.getSeconds();

        // Determine AM or PM
        var ampm = hours >= 12 ? 'PM' : 'AM';

        // Convert hours to 12-hour format
        hours = hours % 12;
        hours = hours ? hours : 12; // Handle midnight

        // Add leading zeros to minutes and seconds if necessary
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        // Construct the time string with AM/PM indicator
        var timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;

        // Update the content of the element with the id "manila-time"
        document.getElementById("manila-time").textContent = timeString;
    }

    // Call the function initially to display the time immediately
    getManilaTime();

    // Call the function every second to update the time in real-time
    setInterval(getManilaTime, 1000);
</script>