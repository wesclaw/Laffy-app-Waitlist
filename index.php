<?php
// Initialize success and error messages
$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    // Get email from form
    $email = trim($_POST['email']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "❌ Invalid email format!";
    } else {
        // Include database connection
        require 'connect.php'; 

        // Prepare SQL statement to prevent SQL injection
        $stmt = mysqli_prepare($conn, "INSERT INTO email_list (email) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $email);

        // Execute the query and check for success
        if (mysqli_stmt_execute($stmt)) {
            $successMessage = "✅ Thank you for joining the waitlist! We'll notify you soon.";
        } else {
            $errorMessage = "❌ Error: " . mysqli_error($conn);
        }

        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>

<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laffy App Waitlist</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Fascinate+Inline&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="src/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
  <div class="container">
      <main>
        <h1>Laffy</h1>
        <h2>Make every second count</h2>
        <p>
          Remember <span style="color: #00B489">Vine</span> and <span style="color:#D32F2F ">America's Funniest Home Videos</span>? Welcome to Laffy, the app where you can upload and watch 10-second comedy clips.
        </p>
        <section>
        <h5>Join the waitlist!</h5>
        <form action="index.php" method="POST">
            <input type="email" placeholder="Email" class="input_el" name="email">
            <div>
            <input type="submit" value="Join!" class="submit_btn">
            </div> 
        </form>
        </section>

        <?php if ($successMessage): ?>
        <p style="color: green; margin-top: 30px; font-family: sans-serif;"><?php echo $successMessage; ?></p>
        <?php elseif ($errorMessage): ?>
        <p style="color: red; margin-top: 30px; font-family: sans-serif;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
      </main>
    </div>
    
    <script src="" async defer></script>
  </body>
</html>