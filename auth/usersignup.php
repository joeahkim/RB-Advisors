<?php
// Redirect logged-in users to dashboard
if(isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RB Financial Advisors</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="../style.css">
</head>
<body>
	<h2>RB ADVISORS</h2>
<div class="container" id="container">
	
	<div class="form-container sign-in-container">
		<form action="./signup.php" method="POST">
			<h1>Sign in</h1>
			<!--<div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div>
			<span>or use your account</span>-->
			<input type="text" id="name" name="name" placeholder="Name" required/>
			<input type="email" id="email" name="email" placeholder="Email" required/>
			<input type="number" id="idNumber" name="idNumber" placeholder="ID Number" required/>
			<input type="address" id="address" name="address" placeholder="Address" required/>
			<input type="password" id="password" name="password" placeholder="Password" required/>
			<input type="checkbox" class="checkbox" required>
			<a href="#">Terms and Conditions</a>
			<button type="submit">Sign Up</button>
				<!-- Display error message -->
				<?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo '<p style="color: #222020; font-size: 15px; font-weight:400">' . htmlspecialchars($_SESSION['error']) . '</p>';
                    unset($_SESSION['error']); 
                }
                ?>
		</form>
	</div>
	<div class="overlay-container d-none d-sm-none d-md-block">
		<div class="overlay">
			<div class="overlay-panel overlay-right">
			<h1>Welcome Back!</h1>
				<p>Already have an account? Sign in</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="../../script.js"></script>
</body>
</html>