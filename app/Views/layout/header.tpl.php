<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IndexPilot - Révision de tarifs</title>
  <link rel="icon" type="image/png" sizes="32x32" href="<?= $_SERVER['BASE_URI']?>/assets/images/indexpilot.png">
    <?php
            if (isset($_SESSION['userId'])){
    ?>
  <link rel="stylesheet" href="<?= $_SERVER['BASE_URI']?>/assets/css/styles-app.css">
    <?php
    } else {
    ?>
    <link rel="stylesheet" href="<?= $_SERVER['BASE_URI']?>/assets/css/styles-landing.css">
    <?php
    }
    ?>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@32,200,0,0" />
</head>
<body>

<!-- SIDEBAR -->
<?php
            if (isset($_SESSION['userId'])){
        ?>
<?php
// On inclut des sous-vues => "partials"
include __DIR__ . '/../partials/sidebar.tpl.php';
?>

<main class="main">
    <div class="topbar">
        <button id="menuToggle" class="burger-btn">

            <span class="material-symbols-rounded">
                menu
            </span>
        
        </button>
<?php
}
?>