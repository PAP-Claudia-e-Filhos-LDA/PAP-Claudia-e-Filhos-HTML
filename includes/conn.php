<?php
class MyDB extends SQLite3
{
  function __construct()
  {
    $this->open(__DIR__ . '/../BD/Rissois.db');
  }
}

$db = new MyDB();
if (!$db) {
  echo $db->lastErrorMsg();
}
