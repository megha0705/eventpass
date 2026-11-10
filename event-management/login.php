<?php
  include 'db-config.php';
  $login_error = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    if(password_verify($password, $row['password'])) {
      // Password is correct, start a session and redirect to dashboard
      session_start();
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_name'] = $row['name'];
      $_SESSION['role'] = $row['role'];

      if($row['role'] == 'admin') {
      header("Location: admin/dashboard.php");
      }else{
      header("Location: index.php");
      }
      exit();
    } else {    
      // Password is incorrect
      $login_error = "Email or password is incorrect. Try again.";
    }
  } else {
    // Email not found
    $login_error = "Email or password is incorrect. Try again.";
  }           
  

  // Close the statement
  mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Log in | EventHub</title>
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
    <div class="page-head"><h1>Log in</h1><p>Use your email and password to manage your registrations.</p></div>
    <div class="form-box">
      <!-- PHP: show login error here -->
      <!-- <div class="alert alert-error">Email or password is incorrect. Try again.</div> -->
      <form action="" method="post">
        <div class="field"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
        <div class="field"><label for="password">Password</label><input type="password" id="password" name="password" required></div>
        <button class="btn" type="submit">Log in</button>
      </form>
        <?php if ($login_error): ?>
        <div class="alert alert-error" style="margin-top:1.2rem"><?= htmlspecialchars($login_error) ?></div>
      <?php endif; ?>

      <p class="form-foot">New here? <a href="signup.php">Create an account</a></p>
    </div>
  </div>
</main>
<footer class="site-footer"><div class="container">&copy; 2026 EventHub. All rights reserved.</div></footer>
</body>
</html>
