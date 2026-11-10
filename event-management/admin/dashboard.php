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
    <div class="stat"><b>12</b><span>Total events</span></div>
    <div class="stat"><b>4</b><span>Upcoming events</span></div>
    <div class="stat"><b>386</b><span>Registrations</span></div>
    <div class="stat"><b>27</b><span>Notifications sent</span></div>
  </div>

  <div class="toolbar">
    <h2 style="margin:0">Latest registrations</h2>
    <a class="btn" href="event-form.php">Add new event</a>
  </div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Name</th><th>Email</th><th>Event</th><th>Registered on</th></tr></thead>
      <tbody>
        <!-- PHP: loop over latest registrations -->
        <tr><td>Anita Roy</td><td>anita@example.com</td><td>Web Development Workshop</td><td>9 Nov 2026</td></tr>
        <tr><td>Rahul Sen</td><td>rahul@example.com</td><td>Tech Talk: Careers in AI</td><td>9 Nov 2026</td></tr>
        <tr><td>Priya Das</td><td>priya@example.com</td><td>Web Development Workshop</td><td>8 Nov 2026</td></tr>
      </tbody>
    </table>
  </div>
</main>
<footer class="site-footer"><div class="container">EventHub admin panel</div></footer>
</body>
</html>
