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
        <tr><td>Web Development Workshop</td><td>15 Dec 2026</td><td>Seminar Hall A</td><td>58 / 100</td>
          <td class="actions">
            <a class="btn btn-outline btn-sm" href="registrations.php">Attendees</a>
            <a class="btn btn-outline btn-sm" href="event-form.php?id=1">Edit</a>
            <form action="delete-event.php" method="post" onsubmit="return confirm('Delete this event?');"><input type="hidden" name="id" value="1"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form>
          </td></tr>
        <tr><td>Tech Talk: Careers in AI</td><td>20 Dec 2026</td><td>Auditorium</td><td>80 / 200</td>
          <td class="actions">
            <a class="btn btn-outline btn-sm" href="registrations.php">Attendees</a>
            <a class="btn btn-outline btn-sm" href="event-form.php?id=2">Edit</a>
            <form action="delete-event.php" method="post" onsubmit="return confirm('Delete this event?');"><input type="hidden" name="id" value="2"><button class="btn btn-danger btn-sm" type="submit">Delete</button></form>
          </td></tr>
      </tbody>
    </table>
  </div>
</main>
<footer class="site-footer"><div class="container">EventHub admin panel</div></footer>
</body>
</html>
