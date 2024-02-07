<?php
ini_set('session.gc_maxlifetime', 7200);
ini_set('session.cookie_lifetime', 7200);
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: teacher_desktop.php");
    exit();
}

include "../kapcsolat.php";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = trim($_POST['username']);
    $input_password = trim($_POST['password']); 

    if (empty($input_username) || empty($input_password)) {
        $error_message = "Please fill out both fields."; 
    } else {
        $input_username = $db->real_escape_string($input_username);
        $input_password = $db->real_escape_string($input_password);
        $query = "SELECT * FROM teacher WHERE username='$input_username' AND password='$input_password'";
        $result = $db->query($query);

        if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$_SESSION['username'] = $input_username;
			$_SESSION['teacher_id'] = $row['teacher_id'];
            $_SESSION['loggedin'] = true;
            header("Location: teacher_desktop.php");
            exit();
        } else {
            $error_message = "Username or password is incorrect.";
        }
    }
}
$db->close();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
	<link rel="stylesheet" type="text/css" href="../styles/lstyle.css">
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Link to Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
	<div class="login-wrapper">
	<a href="../index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back</a>
        <div class="container">
			
            <div class="text">Login</div>
            <div class="error-message <?php if (!empty($error_message)) echo 'show'; ?>"><?php echo $error_message; ?></div>
            <form id="loginForm"  method="post" action="">
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" id="username" name="username"  placeholder="Username" value="<?php echo isset($input_username) ? htmlspecialchars($input_username) : ''; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    </div>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
	</div>

    <!-- Include Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

