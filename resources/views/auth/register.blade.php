<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registration</title>
</head>
<body>
	<h1>Register Page</h1>
	<form action="{{ route('register') }}" autocomplete="off" method="post" novalidate>
	@csrf
		<label for="name">Name:</label>
		<input type="text" name="name" id="name">
		<br>
		<label for="email">Email:</label>
		<input type="text" name="email" id="email">
		<br>
		<label for="password">Password:</label>
		<input type="password" name="password" id="password">
		<br>
		<label for="password_confirmation">Confirm Password:</label>
		<input type="password" name="password_confirmation" id="password_confirmation">
		<br>
		<button class="w-full" type="submit">Register</button>
		<br>
		<a href="{{ route('login') }}">Login</a>

	</form>
</body>
</html>