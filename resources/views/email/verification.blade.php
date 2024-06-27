<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
	<p>Welcome!</p>
	<p>To complete your registration, you need to confirm your email address.</p>
	<p>Please use the following code for confirmation:</p>
	<h1>{{ $code }}</h1>
	<p>If you did not register on our website, please ignore this message.</p>
	<p>Best regards, {{ config('app.name') }}</p>
</body>
</html>