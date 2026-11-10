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
      <a href="index.php" >Events</a>
      <a href="calendar.php" class="active">Calendar</a>
      <a href="my-registrations.php" >My registrations</a>
      <a href="login.php" >Log in</a>
      <a class="btn btn-amber btn-sm" href="signup.php">Sign up</a>
    </nav>
  </div>
</header>
<main class="container">
  <div class="page-head"><h1>Event calendar</h1><p>See what is happening each day. Select an event to view details and register.</p></div>

  <div class="cal-head">
    <a class="btn btn-outline btn-sm" href="calendar.php?month=11&year=2026">&larr; Previous</a>
    <h2 style="margin:0">December 2026</h2>
    <a class="btn btn-outline btn-sm" href="calendar.php?month=1&year=2027">Next &rarr;</a>
  </div>

  <!-- PHP: generate the <tr> rows with a loop (days in month, first weekday). Static sample below. -->
  <div class="cal-wrap">
    <table class="calendar">
      <thead><tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr></thead>
      <tbody>
        <tr>
          <td class="other"><span class="num">29</span></td><td class="other"><span class="num">30</span></td>
          <td><span class="num">1</span></td><td><span class="num">2</span></td><td><span class="num">3</span></td><td><span class="num">4</span></td><td><span class="num">5</span></td>
        </tr>
        <tr>
          <td><span class="num">6</span></td><td><span class="num">7</span></td><td><span class="num">8</span></td><td><span class="num">9</span></td><td><span class="num">10</span></td><td><span class="num">11</span></td><td><span class="num">12</span></td>
        </tr>
        <tr>
          <td><span class="num">13</span></td><td><span class="num">14</span></td>
          <td class="today"><span class="num">15</span><a class="cal-event" href="event-details.php">Web Dev Workshop</a></td>
          <td><span class="num">16</span></td><td><span class="num">17</span></td><td><span class="num">18</span></td><td><span class="num">19</span></td>
        </tr>
        <tr>
          <td><span class="num">20</span><a class="cal-event" href="event-details.php">Tech Talk: AI</a></td><td><span class="num">21</span></td><td><span class="num">22</span></td><td><span class="num">23</span></td><td><span class="num">24</span></td><td><span class="num">25</span></td><td><span class="num">26</span></td>
        </tr>
        <tr>
          <td><span class="num">27</span></td><td><span class="num">28</span></td><td><span class="num">29</span></td><td><span class="num">30</span></td><td><span class="num">31</span></td>
          <td class="other"><span class="num">1</span></td><td class="other"><span class="num">2</span></td>
        </tr>
      </tbody>
    </table>
  </div>
</main>
<footer class="site-footer"><div class="container">&copy; 2026 EventHub. All rights reserved.</div></footer>
</body>
</html>
