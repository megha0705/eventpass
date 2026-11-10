<?php
include 'db-config.php';
if(session_status()===PHP_SESSION_NONE){
  session_start();
}
$user_id = (int)($_SESSION['user_id'] ?? 0);
if(!$user_id){
  header("Location: login.php");
  exit();
}
$query = "SELECT r.id AS registration_id, r.status, e.title, e.event_date, e.venue FROM registrations r LEFT JOIN events e ON r.event_id=e.id WHERE r.user_id=$user_id and r.status='Confirmed' ORDER BY e.event_date, e.start_time";
$result = mysqli_query($conn, $query);

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registration_id'])) {
  $registration_id = (int)$_POST['registration_id'];
  // Update the registration status to 'Cancelled'
  $update_query = "UPDATE registrations SET status='Cancelled' WHERE id=$registration_id AND user_id=$user_id";
  mysqli_query($conn, $update_query);
  header("Location: my-registrations.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My registrations | EventHub</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container">
    <a class="brand" href="index.php">Event<span>Hub</span></a>
   <nav class="nav">
      <a href="index.php" class="active">Events</a>
      <a href="calendar.php">Calendar</a>
      <a href="my-registrations.php">My registrations</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="#"><?= htmlspecialchars($_SESSION['user_name']) ?></a>
        <a class="btn btn-amber btn-sm" href="logout.php">Log out</a>
      <?php else: ?>
        <a href="login.php">Log in</a>
        <a class="btn btn-amber btn-sm" href="signup.php">Sign up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
  <div class="page-head"><h1>My registrations</h1><p>Events you have signed up for, and the latest updates from the organisers.</p></div>

  <h2>Registered events</h2>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Event</th><th>Date</th><th>Venue</th><th>Status</th><th></th></tr></thead>
      <tbody>
        <!-- PHP: loop over this user's registrations -->
         <?php if(mysqli_num_rows($result) == 0): ?>
          <tr><td colspan="5">You have no confirmed registrations.</td></tr>
        <?php endif; ?> 
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $row['title'] ?></td>
            <td><?= $row['event_date'] ?></td>
            <td><?= $row['venue'] ?></td>
            <td><span class="badge badge-open"><?= $row['status'] ?></span></td>
            <td>
              <form action="" method="post">
                <input type="hidden" name="registration_id" value="<?= $row['registration_id'] ?>">
                <button class="btn btn-danger btn-sm" type="submit">Cancel</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  
</main>
<footer class="site-footer"><div class="container">&copy; 2026 EventHub. All rights reserved.</div></footer>
</body>
</html>
