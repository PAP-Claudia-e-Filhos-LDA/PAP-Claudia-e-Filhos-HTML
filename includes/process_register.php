  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $password = $_POST['password'];

    require_once 'conn.php';
    require_once 'functions_inc.php';

    if (emptyInputSignup($username, $phoneNumber, $password) !== false) {
      header("location: ../php/register.php?error=emptyinput");
      exit();
    }
    if (invalidUsername($username) !== false) {
      header("location: ../php/register.php?error=ivalidusername");
      exit();
    }
    if (invalidPhone($phoneNumber) !== false) {
      header("location: ../php/register.php?error=ivalidphone");
      exit();
    }

    if (userExists($db, $username, $phoneNumber, $email) !== false) {
      header("location: ../php/register.php?error=userexists");
      exit();
    }

    createUser($db, $username, $nome, $email, $phoneNumber, $password);
  } else {
    header("location: ../php/register.php");
    exit();
  }
