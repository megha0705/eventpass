<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Send notification | EventHub Admin</title>
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
      <a href="manage-events.php" >Events</a>
      <a href="send-notification.php" class="active">Send notification</a>
      <a href="../index.php">View site</a>
      <a href="login.php">Log out</a>
    </nav>
  </div>
</header>
<main class="container">
  <div class="page-head"><h1>Send notification</h1><p>Write an update. It is emailed to everyone registered for the event you choose and saved in their notifications.</p></div>
  <!-- PHP: show success / error message here -->
  <!-- <div class="alert alert-success">Notification sent to 58 people.</div> -->
  <div class="form-box">
    <form action="send-notification.php" method="post">
      <div class="field"><label for="event_id">Send to attendees of</label>
        <select id="event_id" name="event_id" required>
          <!-- PHP: loop over events -->
          <option value="1">Web Development Workshop (58 registered)</option>
          <option value="2">Tech Talk: Careers in AI (80 registered)</option>
        </select></div>
      <div class="field"><label for="subject">Subject</label><input type="text" id="subject" name="subject" required></div>
      <div class="field"><label for="message">Message</label><textarea id="message" name="message" required></textarea></div>
      <button class="btn" type="submit">Send notification</button>
    </form>
  </div>

  <h2 style="margin-top:2.5rem">Sent notifications</h2>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Subject</th><th>Event</th><th>Sent on</th><th>Recipients</th></tr></thead>
      <tbody>
        <!-- PHP: loop over notifications table -->
        <tr><td>Venue changed</td><td>Web Development Workshop</td><td>10 Nov 2026</td><td>58</td></tr>
        <tr><td>Reminder: 2 days to go</td><td>Tech Talk: Careers in AI</td><td>8 Nov 2026</td><td>80</td></tr>
      </tbody>
    </table>
  </div>
</main>
<footer class="site-footer"><div class="container">EventHub admin panel</div></footer>
</body>
</html>
