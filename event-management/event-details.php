<?php
include 'db-config.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// 1. get the event using the id in the link (index.php sends event-details.php?id=1)
$event_id = (int)($_GET['id'] ?? 0);
$result = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
$event = mysqli_fetch_assoc($result);

// no such event, go back to the list
if (!$event) {
  header("Location: index.php");
  exit();
}

$user_id = (int)($_SESSION['user_id'] ?? 0);

// 2. seats left = capacity minus confirmed registrations
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS taken FROM registrations WHERE event_id = $event_id AND status = 'confirmed'"));
$seats_left = $event['capacity'] - $row['taken'];

// 3. has this user already registered? (a cancelled row can be reused)
$existing = null;
if ($user_id) {
  $existing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, status FROM registrations WHERE user_id = $user_id AND event_id = $event_id"));
}
$is_registered = ($existing && $existing['status'] == 'confirmed');

$event_over = ($event['event_date'] < date('Y-m-d'));
$just_registered = false;

// 4. the Register button was clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && $user_id && !$event_over && !$is_registered && $seats_left > 0) {
  if ($existing) {
    // user cancelled earlier, so turn the same row back on
    $ok = mysqli_query($conn, "UPDATE registrations SET status = 'confirmed' WHERE id = " . (int)$existing['id']);
  } else {
    $ok = mysqli_query($conn, "INSERT INTO registrations (user_id, event_id) VALUES ($user_id, $event_id)");
  }

  if ($ok) {
    $is_registered = true;
    $just_registered = true;
    $seats_left--;
    // email confirmation will be added here later
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $event['title'] ?> | EventHub</title>
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
      <?php if ($user_id): ?>
        <a href="#"><?= $_SESSION['user_name'] ?></a>
        <a class="btn btn-amber btn-sm" href="logout.php">Log out</a>
      <?php else: ?>
        <a href="login.php">Log in</a>
        <a class="btn btn-amber btn-sm" href="signup.php">Sign up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
  <p><a href="index.php">&larr; Back to events</a></p>
  <div class="page-head" style="margin-top:1rem">
    <h1><?= $event['title'] ?></h1>
  </div>

  <div class="detail-grid">
    <div>
      <section class="panel">
        <h2>About this event</h2>
        <p><?= nl2br($event['description']) ?></p>
      </section>
    </div>

    <aside>
      <section class="panel">
        <ul class="detail-list">
          <li><strong>Date</strong><?= date('d F Y', strtotime($event['event_date'])) ?></li>
          <li><strong>Time</strong><?= date('g:i A', strtotime($event['start_time'])) ?> - <?= date('g:i A', strtotime($event['end_time'])) ?></li>
          <li><strong>Venue</strong><?= $event['venue'] ?></li>
          <li><strong>Seats left</strong><?= $seats_left ?> of <?= $event['capacity'] ?></li>
        </ul>
      </section>
      <section class="panel">
        <?php if ($event_over): ?>
          <div class="alert alert-error">This event has ended.</div>

        <?php elseif (!$user_id): ?>
          <p style="margin-bottom:1rem">Log in to register for this event.</p>
          <a class="btn" href="login.php" style="width:100%;text-align:center">Log in to register</a>

        <?php elseif ($is_registered): ?>
          <div class="alert alert-success">
            <?= $just_registered ? 'You are registered for this event.' : 'You have already registered for this event.' ?>
          </div>

        <?php elseif ($seats_left <= 0): ?>
          <div class="alert alert-error">Sorry, this event is full.</div>

        <?php else: ?>
          <form action="" method="post">
            <button class="btn" type="submit" style="width:100%">Register for this event</button>
          </form>
        <?php endif; ?>

        <a class="btn btn-outline" href="calendar.php" style="width:100%;text-align:center;margin-top:.7rem">Add to calendar</a>
      </section>
    </aside>
  </div>
</main>
<footer class="site-footer"><div class="container">&copy; 2026 EventHub. All rights reserved.</div></footer>
</body>
</html>