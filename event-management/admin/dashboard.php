<?php
include '../db-config.php';
if(session_status()===PHP_SESSION_NONE){
  session_start();
}
$total_events = mysqli_query($conn, "SELECT COUNT(*) as event_count FROM events");
$total_upcoming = mysqli_query($conn, "SELECT COUNT(*) as upcoming_count FROM events WHERE event_date >= CURDATE()");
$total_registrations = mysqli_query($conn, "SELECT COUNT(*) as registration_count FROM registrations");
$total_notifications = mysqli_query($conn, "SELECT COUNT(*) as notification_count FROM notifications");

$latest_registrations = mysqli_query($conn, "SELECT r.registered_at as registered_at, u.name AS user_name, u.email AS user_email, e.title AS event_title FROM registrations r JOIN users u ON r.user_id = u.id JOIN events e ON r.event_id = e.id  ORDER BY r.registered_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard | EventHub Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="admin-bar"><div class="container">Admin panel</div></div>
<header class="site-header">
  <div class="container">
    <a class="brand" href="dashboard.php">Event<span>Hub</span> Admin</a>
    <nav class="nav">
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="manage-events.php" >Events</a>
      <a href="send-notification.php" >Send notification</a>
      <a href="../index.php">View site</a>
      <a href="login.php">Log out</a>
    </nav>
  </div>
</header>
<main class="container">
  <div class="page-head"><h1>Dashboard</h1><p>A quick look at your events and registrations.</p></div>

  <!-- PHP: fill these numbers with COUNT queries -->
  <div class="stats">
    <div class="stat"><b><?=mysqli_fetch_assoc($total_events)['event_count']?></b><span>Total events</span></div>
    <div class="stat"><b><?=mysqli_fetch_assoc($total_upcoming)['upcoming_count']?></b><span>Upcoming events</span></div>
    <div class="stat"><b><?=mysqli_fetch_assoc($total_registrations)['registration_count']?></b><span>Registrations</span></div>
    <div class="stat"><b><?=mysqli_fetch_assoc($total_notifications)['notification_count']?></b><span>Notifications sent</span></div>
  </div>

  <div class="toolbar">
    <h2 style="margin:0">Latest registrations</h2>
    <a class="btn" href="event-form.php">Add new event</a>
  </div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Name</th><th>Email</th><th>Event</th><th>Registered on</th></tr></thead>
      <tbody>
        <?= mysqli_num_rows($latest_registrations) > 0 ? '' : '<tr><td colspan="4">No registrations yet.</td></tr>' ?>
        <!-- PHP: loop over latest registrations -->
        <?php while ($reg = mysqli_fetch_assoc($latest_registrations)): ?>
          <tr>
            <td><?= $reg['user_name'] ?></td>
            <td><?= $reg['user_email'] ?></td>
            <td><?= $reg['event_title'] ?></td>
            <td><?= date('j M Y', strtotime($reg['registered_at'])) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>
<footer class="site-footer"><div class="container">EventHub admin panel</div></footer>
</body>
</html>
