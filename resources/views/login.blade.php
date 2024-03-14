<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
</head>
<body>
	<h1>Login page</h1>
	<form action="{{ route('login') }}" method="post">
		<label for="email">Email:</label>
		<input type="text" name="email" id="email">
		<br>
		<label for="password">Password:</label>
		<input type="password" name="password" id="password">
		<br>
		<button type="submit">Login</button>
	</form>
</body>
</html>