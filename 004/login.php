<?php
session_start();

include './db.php';
$http_method = $_SERVER['REQUEST_METHOD'] ?? '';
$api = $_POST['api'] ?? '';
if ($http_method === 'POST' && $api === 'login'){
    $user = $_POST['user'] ?? '';
    $pw   = $_POST['pw'] ?? '';
    $user_id = $db->login($user, $pw);
    if ($user_id >= 0){
        $_SESSION['user_id'] = $user_id;
        header("Location: home.php");
        exit;
    } else {
        $message = "Login failed, retry";
    }
}
?>

<html>
    <head>
        <title>Login</title>
        <link rel="icon" href="https://buonarroti.tn.it/images/logoBh.webp">
    </head>
    <body>
        <div id="container">
            <form method="POST">
                <input type="hidden" name="api" value="login">
                <input type="text" name="user" placeholder="Username">
                <br><br>
                <input type="password" name="pw" placeholder="Password">
                <br><br>
                <button type="submit">Login</button>
            </form>
            <?php if ($message !== ""): ?>`
                <p style="color:red;"><?php echo $message; ?></p>
            <?php endif; ?>
        </div>
    </body>
    <style>
        #container{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            align-content: center;
            gap: 10px;
            width: 100%;
            height: 100%;
        }
    </style>
</html>
