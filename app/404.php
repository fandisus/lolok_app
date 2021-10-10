<?php
header("HTTP/1.0 404 Not Found");
?><!DOCTYPE html>
<html>
  <head>
    <title>404 <?= APPNAME ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      #center-container {
        margin: 0 auto;
        display:block;
        text-align: center;
        margin-top: 20vh;
      }
      #kode {
        margin: 0; padding: 0; display:inline-block; height: 140px; width: 300px; 
        border: 5px solid white; border-radius: 10px;
        font-size:7em;
      }
    </style>
  </head>
  <body style="background: #DF0;">
    <div id="center-container">
      <h1 id="kode">404</h1>
      <h2 id="keterangan">We apologize, the address you visited is not available yet.</h2>
      Click this link to go <a href="/">back to home</a>
    </div>
  </body>
</html>
