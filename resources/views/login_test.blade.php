<!-- resources/views/login_test.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Test</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2>Login Test</h2>
<form id="loginForm">
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>
    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>

<p id="responseMessage" style="color: red;"></p>

<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(event) {
            event.preventDefault(); // Prevent page refresh

            // Get email and password values from the form
            var email = $('#email').val();
            var password = $('#password').val();

            // Perform AJAX request to the login API endpoint
            $.ajax({
                url: '{{ url('/api/login') }}', // Use Laravel's URL helper
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    email: email,
                    password: password
                }),
                success: function(response) {
                    // Display token if login is successful
                    $('#responseMessage').text('Login successful! Token: ' + response.access_token);
                    $('#responseMessage').css('color', 'green');
                },
                error: function(xhr) {
                    // Display error message if login fails
                    if (xhr.status === 401) {
                        $('#responseMessage').text('Login failed: Incorrect email or password.');
                    } else {
                        $('#responseMessage').text('An error occurred. Status: ' + xhr.status);
                    }
                }
            });
        });
    });
</script>

</body>
</html>
