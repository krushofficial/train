<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="views/style.css">
	<title>Snake Game</title>
</head>
<body>
	<div class="box">
		<h1 id="game-over" class="warning"></h1>
		<h1 id="wrong-auth" class="warning">WRONG USERNAME OR PASSWORD</h1>
	</div>
	<div id="game-board-outer" class="box">
		<div id="game-board">
			<div id="auth-container">
				<div class="auth-input-container">
					<label for="username" class="auth-label">Username:</label>
					<input type="text" id="username" name="username" class="auth-input">
				</div>
				<div class="auth-input-container">
					<label for="password" class="auth-label">Password:</label>
					<input type="password" id="password" name="password" class="auth-input">
				</div>
			</div>
		</div>
	</div>
	<div class="box">
		<div id="btn-list">
			<div id="reset-box" class="btn-box">
				<img src="views/images/arrow.png" class="arrow" style="margin-right: 16px;">
				<input type="button" id="reset" class="btn" value="RESTART" onclick="resetGame(); startGame();"></input>
				<img src="views/images/arrow.png" class="arrow" style="transform: scaleX(-1); margin-left: 16px;">
			</div>
			<div id="login-box" class="btn-box">
				<img src="views/images/arrow.png" class="arrow" style="margin-right: 16px;">
				<input type="button" id="login" class="btn" value="LOGIN" onclick="login();"></input>
				<img src="views/images/arrow.png" class="arrow" style="transform: scaleX(-1); margin-left: 16px;">
			</div>
			<div id="register-box" class="btn-box">
				<input type="button" id="register" class="btn" value="REGISTER" onclick="register();"></input>
			</div>
		</div>
	</div>

	<script src="views/script.js"></script>
</body>
</html>
