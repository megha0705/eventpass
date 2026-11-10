<?php
include '../db-config.php';
if(session_status() == PHP_SESSION_NONE){
session_start();
} 
$event_details= mysqli_query($conn, "SELECT e.title, e.event_date, e.venue ,e.id, e.capacity, COUNT(r.id) as registered FROM events e LEFT JOIN registrations r ON e.id = r.event_id GROUP BY e.id ORDER BY event_date, start_time");
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
  $event_id = (int)$_POST['id'];
  // Delete the event
  $delete_query = "DELETE FROM events WHERE id=$event_id";
  mysqli_query($conn, $delete_query);
  header("Location: manage-events.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage events | EventHub Admin</title>
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
  <div class="toolbar">
    <div class="page-head" style="margin:0"><h1>Events</h1></div>
    <a class="btn" href="event-form.php">Add new event</a>
  </div>
  <!-- PHP: show success message after add / edit / delete -->
  <!-- <div class="alert alert-success">Event deleted.</div> -->
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Event</th><th>Date</th><th>Venue</th><th>Registered</th><th>Actions</th></tr></thead>
      <tbody>
        <!-- PHP: loop over events -->
        <?php while($event = mysqli_fetch_assoc($event_details)): ?>
          <tr>
            <td><?= $event['title'] ?></td>
            <td><?= $event['event_date'] ?></td>
            <td><?= $event['venue'] ?></td>
            <td><?= $event['registered'] ?> / <?= $event['capacity'] ?></td>
            <td class="actions">
              <a class="btn btn-outline btn-sm" href="registrations.php?event_id=<?= $event['id'] ?>">Attendees</a>
              <a class="btn btn-outline btn-sm" href="update-form.php?id=<?= $event['id'] ?>">Edit</a>
              <form action="" method="post" onsubmit="return confirm('Delete this event?');">
                <input type="hidden" name="id" value="<?= $event['id'] ?>">
                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>
<footer class="site-footer"><div class="container">EventHub admin panel</div></footer>
</body>
</html>
