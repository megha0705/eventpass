<?php
include '../db-config.php';
include '../mail-helper.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$message = '';
$message_type = '';

// 1. success message after the redirect
if (isset($_GET['sent'])) {
  $count = (int)$_GET['sent'];
  $message = "Notification sent to $count " . ($count == 1 ? 'person.' : 'people.');
  $message_type = 'success';
}

// 2. form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $event_id = (int)($_POST['event_id'] ?? 0);
  $subject  = trim($_POST['subject'] ?? '');
  $body     = trim($_POST['message'] ?? '');

  if ($event_id <= 0 || $subject === '' || $body === '') {
    $message = 'Please choose an event and fill in the subject and message.';
    $message_type = 'error';
  } else {
    // everyone with a confirmed registration for this event
    $res = mysqli_query($conn, "SELECT u.name, u.email
      FROM registrations r
      JOIN users u ON r.user_id = u.id
      WHERE r.event_id = $event_id AND r.status = 'confirmed'");

    $recipients = [];
    while ($person = mysqli_fetch_assoc($res)) {
      $recipients[] = $person;
    }
    $total = count($recipients);

    if ($total == 0) {
      $message = 'Nobody is registered for this event, so nothing was sent.';
      $message_type = 'error';
    } else {
      // 1. send the emails first
      set_time_limit(0);   // sending to many people can take a while
      $mail_result = send_bulk_email($recipients, $subject, $body);

      // 2. save the notification
      $safe_subject = mysqli_real_escape_string($conn, $subject);
      $safe_body    = mysqli_real_escape_string($conn, $body);
      $admin_id = (int)($_SESSION['user_id'] ?? 0);
      $sent_by  = $admin_id > 0 ? $admin_id : 'NULL';

      $ok = mysqli_query($conn, "INSERT INTO notifications (event_id, subject, message, sent_by, recipient_count)
        VALUES ($event_id, '$safe_subject', '$safe_body', $sent_by, $total)");

      if ($ok && $mail_result['failed'] == 0) {
        // redirect so a refresh doesn't send it again
        header("Location: send-notification.php?sent=$total");
        exit();
      } elseif ($ok) {
        // saved, but some or all emails failed
        $message = "Saved, but only {$mail_result['sent']} of $total emails were sent. Error: " . $mail_result['error'];
        $message_type = 'error';
      } else {
        $message = 'Error saving the notification.';
        $message_type = 'error';
      }
    }
  }
}

// 3. events for the dropdown, with confirmed registration counts
$events = mysqli_query($conn, "SELECT e.id, e.title, COUNT(r.id) AS registered
  FROM events e
  LEFT JOIN registrations r ON r.event_id = e.id AND r.status = 'confirmed'
  GROUP BY e.id, e.title
  ORDER BY e.event_date DESC");

// 4. sent notifications
$sent = mysqli_query($conn, "SELECT n.subject, n.sent_at, n.recipient_count, e.title
  FROM notifications n
  JOIN events e ON n.event_id = e.id
  ORDER BY n.sent_at DESC");
?>
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
      <a href="dashboard.php">Dashboard</a>
      <a href="manage-events.php">Events</a>
      <a href="send-notification.php" class="active">Send notification</a>
      <a href="../index.php">View site</a>
      <a href="login.php">Log out</a>
    </nav>
  </div>
</header>
<main class="container">
  <div class="page-head"><h1>Send notification</h1><p>Write an update. It is emailed to everyone registered for the event you choose and saved in their notifications.</p></div>

  <?php if ($message): ?>
    <div class="alert alert-<?= $message_type ?>"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="form-box">
    <form action="send-notification.php" method="post">
      <div class="field"><label for="event_id">Send to attendees of</label>
        <select id="event_id" name="event_id" required>
          <?php while ($ev = mysqli_fetch_assoc($events)): ?>
            <option value="<?= $ev['id'] ?>" <?= (($_POST['event_id'] ?? '') == $ev['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($ev['title']) ?> (<?= $ev['registered'] ?> registered)
            </option>
          <?php endwhile; ?>
        </select></div>
      <div class="field"><label for="subject">Subject</label>
        <input type="text" id="subject" name="subject" maxlength="200" value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required></div>
      <div class="field"><label for="message">Message</label>
        <textarea id="message" name="message" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea></div>
      <button class="btn" type="submit">Send notification</button>
    </form>
  </div>

  <h2 style="margin-top:2.5rem">Sent notifications</h2>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Subject</th><th>Event</th><th>Sent on</th><th>Recipients</th></tr></thead>
      <tbody>
        <?php if (mysqli_num_rows($sent) == 0): ?>
          <tr><td colspan="4">No notifications sent yet.</td></tr>
        <?php endif; ?>
        <?php while ($n = mysqli_fetch_assoc($sent)): ?>
          <tr>
            <td><?= htmlspecialchars($n['subject']) ?></td>
            <td><?= htmlspecialchars($n['title']) ?></td>
            <td><?= date('d M Y', strtotime($n['sent_at'])) ?></td>
            <td><?= $n['recipient_count'] ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</main>
<footer class="site-footer"><div class="container">EventHub admin panel</div></footer>
</body>
</html>