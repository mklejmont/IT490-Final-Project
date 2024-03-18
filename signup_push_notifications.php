<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup for Push Notifications</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <?php include 'navigation.php'; ?>
    <h1>Signup for Push Notifications</h1>
    <form action="process_signup.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required>
        <br>
        <input type="submit" value="Sign Up">
    </form>
</body>
</html>
