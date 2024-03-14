<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registration</title>
</head>
<body>
	<h1>Registration Page</h1>
	<form action="{{ route('registration') }}" method="post">
		<label for="name">Name:</label>
		<input type="text" name="name" id="name">
		<br>
		<label for="email">Email:</label>
		<input type="text" name="email" id="email">
		<br>
		<label for="password">Password:</label>
		<input type="password" name="password" id="password">
		<br>
		<button type="submit">Register</button>
		<br>
		<a href="{{ route('login') }}">Login</a>

	</form>
</body>
</html>