<?php
include 'db-config.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$search_query    = $_GET['q'] ?? '';
$category_filter = $_GET['category'] ?? '';
$date_filter     = $_GET['date'] ?? '';
$is_search       = ($search_query || $category_filter || $date_filter);

// base query: always upcoming events
$query = "SELECT * FROM events WHERE event_date >= CURDATE()";

// add a condition only for the boxes that were filled in
if ($search_query) {
  $query .= " AND title LIKE '%$search_query%'";
}
if ($category_filter) {
  $query .= " AND category = '$category_filter'";
}
if ($date_filter) {
  $query .= " AND event_date = '$date_filter'";
}

$query .= " ORDER BY event_date, start_time";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Events | EventHub</title>
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
<section class="hero">
  <div class="container">
    <h1>Find an event and save your seat</h1>
    <p>Browse upcoming workshops, talks and meetups. Register in a minute and get email reminders.</p>
    <form class="search-bar" action="" method="get">
      <input type="text" name="q" placeholder="Search by event name" aria-label="Search events">
      <select name="category" aria-label="Category">
        <option value="">All categories</option>
        <option>Workshop</option>
        <option>Seminar</option>
        <option>Meetup</option>
        <option>Cultural</option>
      </select>
      <input type="date" name="date" aria-label="Date">
      <button class="btn" type="submit">Search</button>
    </form>
  </div>
</section>

<main class="container">
  <h2>Upcoming events</h2>

  <?php if (mysqli_num_rows($result) == 0): ?>
    <p>No events found.</p>
  <?php endif; ?>

  <ul class="event-list">
    <?php while ($ev = mysqli_fetch_assoc($result)): ?>
    <li class="event-row">
      <div class="date-block">
        <span class="day"><?= date('d', strtotime($ev['event_date'])) ?></span>
        <span class="month"><?= date('M', strtotime($ev['event_date'])) ?></span>
      </div>
      <div class="event-info">
        <h3><a href="event-details.php?id=<?= (int)$ev['id'] ?>"><?= $ev['title'] ?></a></h3>
        <p class="meta">
          <?= date('g:i A', strtotime($ev['start_time'])) ?> - <?= date('g:i A', strtotime($ev['end_time'])) ?>
          |  <?= $ev['venue'] ?>
        </p>
      </div>
      <a class="btn btn-outline" href="event-details.php?id=<?= (int)$ev['id'] ?>">View details</a>
    </li>
    <?php endwhile; ?>
  </ul>
</main>
<footer class="site-footer"><div class="container">&copy; 2026 EventHub. All rights reserved.</div></footer>
</body>
</html>