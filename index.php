<?php include_once('dbutils.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include('navigation.php'); ?>
    <?php if(!$_SESSION['loggedIn']){?>
        <a href="login.php">login</a>
    <?php } else {?>
        <form>
        <button name="logout" value="logout">logout</button>
        </form>
    <?php }?>
</body>
</html>