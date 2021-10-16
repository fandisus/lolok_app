<?php
if (isset($GLOBALS['login'])) header('location:'.WEBHOME.'user/');

include DIR . '/engine/templates/topmenu_layout.php';

function htmlHead() { ?>
  <style type="text/css">
    body>.grid { height: 70vh; }
    body>.center.aligned.grid .column { max-width: 450px; }
  </style>
<?php }

function bodyEnd() {}
function mainContent() { ?>
  <div class="ui middle aligned center aligned grid" id="app">
    <div class="column">
      <h2 class="ui teal image header"><img class="image" src="<?= WEBHOME ?>images/dot.png" />
        <div class="content">Log-in to your account</div>
      </h2>
      <div class="ui large form" id="frmLogin">
        <div class="ui stacked segment">
          <div class="field">
            <div class="ui left icon input"><i class="user icon"></i><input type="text" v-model="username" placeholder="Username" /></div>
          </div>
          <div class="field">
            <div class="ui left icon input"><i class="lock icon"></i><input type="password" v-model="password" placeholder="Password" @keypress.enter="tryLogin" /></div>
          </div>
          <div class="ui fluid large blue submit button" @click="tryLogin">Login</div>
        </div>
        <div class="ui error message"></div>
      </div>
      <div class="ui message">New to us? <a href="#">Sign Up</a></div>
    </div>
  </div>
  <script type="module">
    import app from "<?= WEBHOME ?>js/login.js";
    Vue.createApp(app).mount('#app');
  </script>
<?php }
