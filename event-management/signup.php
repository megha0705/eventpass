<?php
include 'db-config.php';
$message = '';
$message_type = '';
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $password = $_POST['password'];
  $confirm = $_POST['confirm'];

  // Validate input
  if($password !== $confirm) {
    $message = "Passwords do not match.";
    $message_type = "error";
  } else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $query = "INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$hashed_password')";

    // Execute the statement
    if(mysqli_query($conn, $query)) {
      $message = "Account created successfully. You can now <a href='login.php'>log in</a>.";
      $message_type = "success";
    } else {
      $message = "Error: " . mysqli_error($conn);
      $message_type = "error";
    }

    // Close the statement
    mysqli_close($conn);
  }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sign up | EventHub</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container">
    <a class="brand" href="index.php">Event<span>Hub</span></a>
    <nav class="nav">
      <a href="index.php" >Events</a>
      <a href="calendar.php" >Calendar</a>
      <a href="my-registrations.php" >My registrations</a>
      <a href="login.php" class="active">Log in</a>
      <a class="btn btn-amber btn-sm" href="signup.php">Sign up</a>
    </nav>
  </div>
</header>
<main class="container">
  <div class="auth-wrap">
    <div class="page-head"><h1>Create your account</h1><p>You need an account to register for events and receive email updates.</p></div>
    <div class="form-box">
      <!-- PHP: show validation errors here -->
      <form action="" method="post">
        <div class="field"><label for="name">Full name</label><input type="text" id="name" name="name" required></div>
        <div class="field"><label for="email">Email</label><input type="email" id="email" name="email" required><small>Notifications are sent to this address.</small></div>
        <div class="field"><label for="phone">Phone number</label><input type="tel" id="phone" name="phone"></div>
        <div class="form-row">
          <div class="field"><label for="password">Password</label><input type="password" id="password" name="password" minlength="6" required></div>
          <div class="field"><label for="confirm">Confirm password</label><input type="password" id="confirm" name="confirm" minlength="6" required></div>
        </div>
        <button class="btn" type="submit" >Create account</button>
      </form>
      <?php if ($message): ?>
        <div class="alert alert-<?= $message_type ?>" style="margin-top:1.2rem">
          <?= $message ?>
        </div>
      <?php endif; ?>
      <p class="form-foot">Already have an account? <a href="login.php">Log in</a></p>
    </div>
  </div>
</main>
<footer class="site-footer"><div class="container">&copy; 2026 EventHub. All rights reserved.</div></footer>
</body>
</html>
