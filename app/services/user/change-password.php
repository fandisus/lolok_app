<?php
include DIR.'/app/templates/sidebar_layout.php';

function htmlHead() {?>
<style>
  .ui.form .inline.field label { width: 110px;}
</style>
<?php }
function bodyEnd() {}
function mainContent() { ?>
<div id="app">
  <h1>Change password</h1>
  <div class="ui form">
    <div class="inline field"><label>Old Password</label>
      <div class="ui mini input"><input type="password" v-model="oldpass" /></div>
    </div>
    <div class="inline field"><label>New Password</label>
      <div class="ui mini input"><input type="password" v-model="pass" /></div>
    </div>
    <div class="inline field"><label>Confirm Password</label>
      <div class="ui mini input"><input type="password" v-model="cpass" @keydown.enter="changePassword" /></div>
    </div><button class="ui blue button" @click="changePassword">Change Password</button>
  </div>
</div>
<script type="module">
  import app from "../js/change-password.js";
  $(document).ready(function() {
    Vue.createApp(app).mount('#app');
  });
</script>
<?php }