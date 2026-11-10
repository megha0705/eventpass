<?php
include '../db-config.php';
if(session_status() == PHP_SESSION_NONE){
session_start();
}
$message = '';
$message_type = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {

$eventtitle = $_POST['title'] ?? '';
$eventdescription = $_POST['description'] ?? '';
$eventdate = $_POST['event_date'] ?? '';
$eventcategory = $_POST['category'] ?? '';
$eventstarttime = $_POST['start_time'] ?? '';
$eventendtime = $_POST['end_time'] ?? '';
$eventvenue = $_POST['venue'] ?? '';
$eventcapacity = $_POST['capacity'] ?? '';

$result = mysqli_query($conn, "INSERT INTO events (title, description, event_date, category, start_time, end_time, venue, capacity) VALUES ('$eventtitle', '$eventdescription', '$eventdate', '$eventcategory', '$eventstarttime', '$eventendtime', '$eventvenue', $eventcapacity)");
if($result){
 $message = 'Event created successfully.';
$message_type = 'success';
}else{
  $message = 'Error creating event';
  $message_type = 'error';
}
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add event | EventHub Admin</title>
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
  <div class="page-head"><h1>Add new event</h1><p>The same form works for editing. In PHP, fill the fields with the saved values when an id is in the link.</p></div>
  <?php if ($message): ?>
  <div class="alert alert-<?= $message_type ?>"><?= $message ?></div>
<?php endif; ?>

<div class="form-box">
  <form action="" method="post">
  <div class="form-box">
    <form action="" method="post">
      <!-- PHP: for edit, add <input type="hidden" name="id" value="..."> -->
      <div class="field"><label for="title">Event title</label><input type="text" id="title" name="title" required></div>
      <div class="field"><label for="description">Description</label><textarea id="description" name="description" required></textarea></div>
      <div class="form-row">
        <div class="field"><label for="event_date">Date</label><input type="date" id="event_date" name="event_date" required></div>
        <div class="field"><label for="category">Category</label>
          <select id="category" name="category"><option>Workshop</option><option>Seminar</option><option>Meetup</option><option>Cultural</option></select></div>
      </div>
      <div class="form-row">
        <div class="field"><label for="start_time">Start time</label><input type="time" id="start_time" name="start_time" required></div>
        <div class="field"><label for="end_time">End time</label><input type="time" id="end_time" name="end_time" required></div>
      </div>
      <div class="form-row">
        <div class="field"><label for="venue">Venue</label><input type="text" id="venue" name="venue" required></div>
        <div class="field"><label for="capacity">Total seats</label><input type="number" id="capacity" name="capacity" min="1" required></div>
      </div>
     
      <button class="btn" type="submit">Save event</button>
      <a class="btn btn-outline" href="manage-events.php">Cancel</a>
    </form>
  </div>
</main>
<footer class="site-footer"><div class="container">EventHub admin panel</div></footer>
</body>
</html>
