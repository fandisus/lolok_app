<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= (isset($title)) ? $title : APPNAME ?></title>
  <?php include DIR.'/app/templates/standard_js_css.php'; ?>
  <?php if (function_exists('htmlHead')) htmlHead() ?>
</head>
<body>

  <?php include 'nav.php'; ?>

  <script>
    $(document).ready(function () {
      $('.ui.dropdown').dropdown({transition:'drop', on:'hover'});
      $('.ui.accordion').accordion();
    });
  </script>
  <?php if (function_exists('bodyEnd')) bodyEnd(); ?>
</body>
</html>