<?php
include 'db-config.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$user_id = (int)($_SESSION['user_id'] ?? 0);

// 1. which month to show (calendar.php?month=12&year=2026), default = this month
$month = (int)($_GET['month'] ?? date('n'));
$year  = (int)($_GET['year'] ?? date('Y'));
if ($month < 1 || $month > 12) { $month = (int)date('n'); }
if ($year < 2000 || $year > 2100) { $year = (int)date('Y'); }

// 2. previous and next month links (handles December -> January)
$prev_month = $month - 1; $prev_year = $year;
if ($prev_month < 1) { $prev_month = 12; $prev_year--; }
$next_month = $month + 1; $next_year = $year;
if ($next_month > 12) { $next_month = 1; $next_year++; }

// 3. facts about this month
$first_weekday = (int)date('w', mktime(0, 0, 0, $month, 1, $year)); // 0 = Sunday
$days_in_month = (int)date('t', mktime(0, 0, 0, $month, 1, $year));
$days_in_prev  = (int)date('t', mktime(0, 0, 0, $month - 1, 1, $year));
$month_title   = date('F Y', mktime(0, 0, 0, $month, 1, $year));

// 4. get all events in this month and group them by day number
$first_date = sprintf('%04d-%02d-01', $year, $month);
$last_date  = sprintf('%04d-%02d-%02d', $year, $month, $days_in_month);
$result = mysqli_query($conn, "SELECT id, title, event_date FROM events WHERE event_date BETWEEN '$first_date' AND '$last_date' ORDER BY start_time");

$events_by_day = [];
while ($e = mysqli_fetch_assoc($result)) {
  $d = (int)date('j', strtotime($e['event_date']));
  $events_by_day[$d][] = $e;
}

// 5. how many cells the grid needs (always full weeks)
$total_cells = (int)(ceil(($first_weekday + $days_in_month) / 7) * 7);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Event calendar | EventHub</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container">
    <a class="brand" href="index.php">Event<span>Hub</span></a>
    <nav class="nav">
      <a href="index.php">Events</a>
      <a href="calendar.php" class="active">Calendar</a>
      <a href="my-registrations.php">My registrations</a>
      <?php if ($user_id): ?>
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
  <div class="page-head"><h1>Event calendar</h1><p>See what is happening each day. Select an event to view details and register.</p></div>

  <div class="cal-head">
    <a class="btn btn-outline btn-sm" href="calendar.php?month=<?= $prev_month ?>&year=<?= $prev_year ?>"><- Previous</a>
    <h2 style="margin:0"><?= $month_title ?></h2>
    <a class="btn btn-outline btn-sm" href="calendar.php?month=<?= $next_month ?>&year=<?= $next_year ?>">Next -></a>
  </div>

  <div class="cal-wrap">
    <table class="calendar">
      <thead><tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr></thead>
      <tbody>
        <?php for ($i = 0; $i < $total_cells; $i++): ?>
          <?php
            $day = $i - $first_weekday + 1;   // below 1 = previous month, above days_in_month = next month
            if ($i % 7 == 0) { echo "<tr>"; }

            if ($day < 1): ?>
              <td class="other"><span class="num"><?= $days_in_prev + $day ?></span></td>
          <?php elseif ($day > $days_in_month): ?>
              <td class="other"><span class="num"><?= $day - $days_in_month ?></span></td>
          <?php else:
              $is_today = ($day == date('j') && $month == date('n') && $year == date('Y'));
          ?>
              <td<?= $is_today ? ' class="today"' : '' ?>>
                <span class="num"><?= $day ?></span>
                <?php if (isset($events_by_day[$day])): ?>
                  <?php foreach ($events_by_day[$day] as $ev): ?>
                    <a class="cal-event" href="event-details.php?id=<?= $ev['id'] ?>"><?= htmlspecialchars($ev['title']) ?></a>
                  <?php endforeach; ?>
                <?php endif; ?>
              </td>
          <?php endif;

            if ($i % 7 == 6) { echo "</tr>"; }
          ?>
        <?php endfor; ?>
      </tbody>
    </table>
  </div>
</main>
<footer class="site-footer"><div class="container">&copy; 2026 EventHub. All rights reserved.</div></footer>
</body>
</html>