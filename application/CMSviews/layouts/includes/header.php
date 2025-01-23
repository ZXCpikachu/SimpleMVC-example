<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your website description">
    <meta name="author" content="Your Name">
    <title><?php echo $title?></title>
    <link rel="stylesheet" type="text/css" href="/CSS/style.css" />

    <script src="JS/jquery-3.2.1.js"></script>
    <script src="JS/loaderIdentity.js"></script>
    <script src="JS/showContent.js"></script>
  </head>
  <body>
    <div id="container">
      <a href="."><img id="logo" src="images/logo.jpg" alt="Logo of Widget News" /></a>
    <p>You are logged in as <b><?php echo $User->userName ?></b>.
        <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Login/logout') ?>">Log out </a>