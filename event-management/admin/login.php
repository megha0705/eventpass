<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin log in | EventHub</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="site-header"><div class="container"><a class="brand" href="../index.php">Event<span>Hub</span> Admin</a></div></header>
<main class="container">
  <div class="auth-wrap">
    <div class="page-head"><h1>Admin log in</h1><p>Only organisers can manage events.</p></div>
    <div class="form-box">
      <!-- PHP: show login error here -->
      <form action="login.php" method="post">
        <div class="field"><label for="email">Email</label><input type="email" id="email" name="email" required></div>
        <div class="field"><label for="password">Password</label><input type="password" id="password" name="password" required></div>
        <button class="btn" type="submit">Log in</button>
      </form>
    </div>
  </div>
</main>
<footer class="site-footer"><div class="container">EventHub admin panel</div></footer>
</body>
</html>
