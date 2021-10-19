<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= (isset($title)) ? $title : APPNAME ?></title>
  <?php include 'standard_js_css.php'; ?>
  <?php if (function_exists('htmlHead')) htmlHead() ?>
</head>
<body>
  <?php include 'sidebar_nav.php'; ?>
  <style>
    main { margin-left:15em; }
    @media (max-width:767px) { main {margin-left: 0}}
  </style>
  <main>
    <!-- Top menubar (for logout and others) -->
    <div class="ui top attached menu">
      <div class="right menu"><a class="item"><i class="mail icon"></i></a>
        <div class="ui dropdown item"><i class="user icon"></i>
          <div class="menu">
            <a class="item" href="<?= WEBHOME ?>user/change-password"><i class="privacy icon"></i>Change password</a>
            <a class="item" href="<?= WEBHOME ?>user/logout"><i class="power icon"></i>Logout</a>
          </div>
        </div>
      </div>
    </div>

    <?php if (function_exists('mainContent')) mainContent(); ?>
    <script>
      $(document).ready(function () {
        $('#m-sidebar-toggle').click(() => {
          $('#mobilesidebar').sidebar('toggle');
        });
        $('.ui.dropdown').dropdown({transition:'drop', on:'hover'});
        $('.ui.accordion').accordion();
      });
    </script>
    <?php if (function_exists('bodyEnd')) bodyEnd(); ?>
  </main>
</body>
</html>