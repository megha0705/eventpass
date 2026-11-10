<?php
function load_env($path) {
  if (!file_exists($path)) {
    return;
  }
  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
      continue;   // skip comments and bad lines
    }
    list($key, $value) = explode('=', $line, 2);
    $key   = trim($key);
    $value = trim($value, " \t\n\r\0\x0B\"'");
    $_ENV[$key] = $value;
    putenv("$key=$value");
  }
}