<!DOCTYPE html>
<html>
<head>
    <title>Message Display</title>
    <!-- Include Bootstrap CSS (if not already included) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<?php
// Include the messages file


// Retrieve the message key from the URL query string
$messageKey = isset($_GET['message']) ? $_GET['message'] : null;

// Check if the message key exists in the messages array
if ($messageKey && isset($messages[$messageKey])) {
    // Display the message
    $message = $messages[$messageKey];
    echo "<div class='alert alert-success'>$message</div>";
}
?>

<!-- Include jQuery library (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Function to hide the messages after 3 seconds
    function hideMessages() {
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    }

    // Call the function when the page finishes loading
    $(document).ready(function() {
        hideMessages();
    });
</script>

</body>
</html>
