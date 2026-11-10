<?php 
include '../db-config.php'; 

if(session_status() == PHP_SESSION_NONE){
  session_start();
}
$registrations = [];
$event_id = (int)($_GET['event_id'] ?? 0);
if ($event_id) {
  $registrations = mysqli_query($conn, "SELECT r.registered_at, u.name, u.email, u.phone FROM registrations r LEFT JOIN users u ON r.user_id=u.id WHERE r.event_id=$event_id ORDER BY r.registered_at DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Attendees | EventHub Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-bar"><div class="container">Admin panel</div></div>
<header class="site-header">
  <div class="container">
    <a class="brand" href="dashboard.php">Event<span>Hub</span> Admin</a>
    <nav class="nav">
      <a href="dashboard.php" >Dashboard</a>
      <a href="manage-events.php" class="active">Events</a>
      <a href="send-notification.php" >Send notification</a>
      <a href="../index.php">View site</a>
      <a href="login.php">Log out</a>
    </nav>
  </div>
</header>
<main class="container">
  <div class="page-head"><h1>Attendees: Web Development Workshop</h1><p>15 Dec 2026, Seminar Hall A. 58 of 100 seats taken.</p></div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Registered on</th></tr></thead>
      <tbody>
        <?= mysqli_num_rows($registrations) > 0 ? '' : '<tr><td colspan="4">No registrations yet.</td></tr>' ?>   
        <!-- PHP: loop over registrations for this event id -->
        <?php while($registration = mysqli_fetch_assoc($registrations)): ?>
          <tr>
            <td><?= $registration['name'] ?></td>
            <td><?= $registration['email'] ?></td>
            <td><?= $registration['phone'] ?></td>
            <td><?= $registration['registered_at'] ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
  <p style="margin-top:1.2rem"><a href="manage-events.php">&larr; Back to events</a></p>
</main>
<footer class="site-footer"><div class="container">EventHub admin panel</div></footer>
</body>
</html>
