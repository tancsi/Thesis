<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="styles/styles.css">
    <title>Driving supporter</title>
</head>
<body>
    <div class="container">
        <div class="text">You are a</div>
        <div class="btn-group">
            <a href="teacher/login.php" class="btn btn-primary btn-lg"><i class="fas fa-chalkboard-teacher"></i> Teacher</a>
            <a href="student/login.php" class="btn btn-secondary btn-lg"><i class="fas fa-user-graduate"></i> Student</a>
        </div>
    </div>
    <script>
        window.onload = function () {
            document.querySelector('.text').classList.add('animated', 'fadeInUp');
            document.querySelector('.btn-group').classList.add('animated', 'fadeIn');
        };
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>
</body>
</html>
