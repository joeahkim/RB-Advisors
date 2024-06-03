<?php
// Redirect logged-in users to dashboard
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RB Financial Advisors</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<h2>RB ADVISORS</h2>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="signup.php" method="post">
			<h1>Create Account</h1>
			<!-- <div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div>
			<span>or use your email for registration</span>-->
			<input type="text" id="name" name="name" placeholder="Name" required/>
			<input type="email" id="email" name="email" placeholder="Email" required/>
			<input type="number" id="idNumber" name="idNumber" placeholder="ID Number" required/>
			<input type="address" id="address" name="address" placeholder="Address" required/>
			<input type="password" id="password" name="password" placeholder="Password" required/>
			<button>Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="login.php" method="POST">
			<h1>Sign in</h1>
			<!--<div class="social-container">
				<a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div>
			<span>or use your account</span>-->
			<input type="email" id="email" name="email" placeholder="Email" required/>
			<input type="password" id="password" name="password" placeholder="Password" required/>
			<a href="#">Forgot your password?</a>
			<button type="submit">Sign In</button>
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
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>Already have an account? Sign in</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Join us</h1>
				<p>Welcome to this world of financial freedom</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>

<script src="script.js"></script>
</body>
</html>