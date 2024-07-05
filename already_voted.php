<?php include('db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Already Voted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f3f4f6;
        }

        .message-box {
            border: 2px solid #ccc;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 15px;
        }

        p {
            color: #555;
            margin-bottom: 25px;
        }

        .adlogout {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .adlogout:hover {
            background-color: #2980b9;
        }

        .link {
            margin-top: 20px;
            display: inline-block;
        }

        .link a {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            text-decoration: none;
        }

        .view-vote {
            color: #fff;
            background-color: #27ae60;
            border: 1px solid #27ae60;
        }

        .view-vote:hover {
            background-color: #219a52;
        }

        .ongoing-votes {
            color: #fff;
            background-color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        .ongoing-votes:hover {
            background-color: #c0392b;
        }

        @media (max-width: 480px) {
            .message-box {
                width: 90%;
                padding: 20px;
            }

            h1 {
                font-size: 20px;
            }

            p {
                font-size: 14px;
                margin-bottom: 20px;
            }

            .link a {
                padding: 6px 12px;
            }
        }
    </style>
</head>

<body>
    <div class="message-box">
        <h1>Already Voted</h1>
        <p>You have already cast your vote. Thank you!</p>
        <a href="ajax.php?action=logout" class="adlogout"><?php echo isset($_SESSION['login_name']) ? $_SESSION['login_name'] . ' Ok' : 'Ok'; ?></a>
        <br>

        <div class="link">
            <a href="view_vote.php" class="view-vote">View Vote</a>

        </div>
        <br>
        <div class="link">
            <a href="ongoingvotesuser.php" class="ongoing-votes">View On Going Votes</a>
        </div>
    </div>
</body>

</html>