<?php

require_once 'connect.php';  


$successMessage = '';
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    // Get email and reCAPTCHA response from the form
    $email = trim($_POST['email']);
    $recaptchaResponse = $_POST['g-recaptcha-response'];  // Get the reCAPTCHA response token

    // Validate reCAPTCHA response using the secret key
    $verifyURL = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $config['RECAPTCHA_SECRET_KEY'],  // Use the secret key from config.php
        'response' => $recaptchaResponse
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($verifyURL, false, $context);
    $resultData = json_decode($result, true);  // Decode the JSON response

    // Check if reCAPTCHA verification was successful
    if (!$resultData["success"]) {
        $errorMessage = "❌ reCAPTCHA verification failed! Please try again.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "❌ Invalid email format!";
    } else {
        // If reCAPTCHA is valid, proceed to insert the email into the database
        require 'connect.php';  // Include your database connection

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
    <title>Laffy Waitlist</title>
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
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
          Remember <span style="color: #00B489">Vine</span> and <span style="color:#D32F2F ">America's Funniest Home Videos</span>? Welcome to <span style="font-family: 'Pacifico', cursive;
          font-weight: 400;
          font-style: normal;
          color: rgb(241, 63, 63); text-decoration: underline;" >Laffy</span>, the app where you can watch and upload 10-second funny video clips.
        </p>
        <!-- <section>
        <h5>Join the waitlist!</h5>
        <p class="waitlistText">I will only contact you with launch news—no spam, no extra emails.</p>
      
        <form action="index.php" method="POST">
          <div style="display: flex; flex-direction: column; width: 100%; align-items: center;">
            <div class="inputs">
            <input type="email" name="email" placeholder="Email" class="input_el" required>
            <input type="submit" value="Join!" class="submit_btn">
            </div>
            <div class="g-recaptcha" data-sitekey="6Leap_MqAAAAAH-IrhtQeXmuLt0c5-2dGBB1Uvjf" data-action="LOGIN"></div>
          </div>
            
        </form>
        </section> -->





        <section>
          <div class="top-text">
          <h5 class="joinText">Join the waitlist!</h5>
          <p class="waitlistText">I will only contact you with launch news—no spam, no extra emails.</p>
          </div>
        <form action="index.php" method="POST">
          <div>
            <div>
            <input type="email" name="email" placeholder="Email" class="input_el" required>
            <input type="submit" value="Join!" class="submit_btn">
            </div>
            <div style="width: 100%; display: flex; justify-content: center; margin-top: 10px;">
            <div class="g-recaptcha" data-sitekey="6Leap_MqAAAAAH-IrhtQeXmuLt0c5-2dGBB1Uvjf" data-action="LOGIN"></div>
            </div>
            
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

  </body>
</html>