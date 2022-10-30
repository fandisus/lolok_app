<?php

use Fandisus\Lolok\JSONResponse;

$id = (isset($_GET['id'])) ? $_GET['id'] : $_POST['id'];
try { $session->user->login($id); }
catch (\Throwable $th) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') JSONResponse::Error($th->getMessage());
  else echo $th->getMessage();
}
header('location:'.$_SERVER['HTTP_REFERER']);