<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $phoneNumber = $_POST['phone_number'];

  require_once 'conn.php';
  require_once 'functions_inc.php';

  if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] == 0) {
    $targetDir = "../img/profiles/";
    $targetFile = $targetDir . basename($_FILES["profile_image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedExtensions = array("jpg", "jpeg", "png", "gif");
    if (in_array($imageFileType, $allowedExtensions)) {
      if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
        $imagem_perfil_nome = basename($_FILES["profile_image"]["name"]);
        $imagem_perfil_caminho = "../img/profiles/" . $imagem_perfil_nome;

        header("location: ../php/editProfile.php?error=none");
        updateImage($db, $_SESSION["userid"], $imagem_perfil_caminho);

        exit();
      }
    }
  }




  if (emptyInputProfile($db, $username, $nome, $phoneNumber, $email) !== false) {

    header("location: ../php/editProfile.php?error=emptyinput");
    exit();
  }
  if (invalidUsername($username) !== false) {
    header("location: ../php/editProfile.php?error=ivalidusername");
    exit();
  }
  if (invalidPhone($phoneNumber) !== false) {
    header("location: ../php/editProfile.php?error=ivalidphone");
    exit();
  }
  if (userExistsProfile($db, $_SESSION["userid"], $username, $phoneNumber, $email) !== false) {
    header("location: ../php/editProfile.php?error=userexists");
    exit();
  }


  updateUser($db, $_SESSION["userid"], $username, $nome, $email, $phoneNumber,  $imagem_perfil_caminho);
} else {
  header("location: ../php/register.php");
  exit();
}
