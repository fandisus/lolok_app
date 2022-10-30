<?php
include DIR.'/app/templates/sidebar/layout.php';

function htmlHead() { ?>
  <style>
    #modUser .ui.form .inline.field label:first-child { width: 120px; }
    #modCPass .ui.form .inline.field label:first-child { width: 120px; }
  </style>
<?php }
function bodyEnd() { ?>
  <script type="module">
    import app from "../js/users.js";
    $(document).ready(() => {
      window.vueapp = Vue.createApp(app).mount('#app');
    });
  </script>
<?php }

function mainContent() { ?>
  <div id="app" class="ui container">
    <h1>User Management</h1>
    <button class="ui mini green button" @click="showModUser">
      <i class="plus icon"></i> Add User
    </button>

    <table class="ui very compact striped collapsing table" id="table">
      <thead>
        <tr>
          <th></th>
          <th>Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Accesses</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="u in users">
          <td>
            <i class="blue pencil icon islink" @click="showModUser(u)"></i>
            <i class="lock icon islink" @click="showCPass(u)"></i>
            <i class="red delete icon islink" @click="delUser(u)"></i>
          </td>
          <td>{{u.fullname}}</td>
          <td>{{u.username}}</td>
          <td>{{u.email}}</td>
          <td>{{u.phone}}</td>
          <td>
            <div class="ui mini blue label" v-for="a in u.accesses">{{a}}</div>
          </td>
        </tr>
      </tbody>
    </table>


    <div class="ui small modal" id="modUser">
      <div class="header">User</div>
      <div class="content">
        <div class="ui small form">
          <div class="inline field"><label>Full Name</label><input type="text" v-model="modUser.fullname" /></div>
          <div class="inline field"><label>Username</label><input type="text" v-model="modUser.username" :disabled="modUser.id != 0" /></div>
          <template v-if="modUser.id===0">
            <div class="inline field"><label>Password</label><input type="password" v-model="modUser.pass" /></div>
            <div class="inline field"><label>Confirm Password</label><input type="password" v-model="modUser.cpass" /></div>
          </template>
          <div class="inline field"><label>Email</label><input type="email" v-model="modUser.email" /></div>
          <div class="inline field"><label>Phone</label><input type="text" v-model="modUser.phone" /></div>
          <div class="inline field">
            <label>Accesses</label>
            <select v-model="modUser._selAccess" @change="addAccess">
              <option :value="a" v-for="a in accesses">{{a}}</option>
            </select>
            <button class="ui mini circular icon green button" @click="addAccess"><i class="plus icon"></i></button>
          </div>
          <div class="ui blue label" v-for="a in modUser.accesses" @click="remAccess(a)">{{a}}<i class="delete icon"></i></div>
        </div>
      </div>
      <div class="actions">
        <div class="ui green button" @click="saveUser">Save</div>
        <div class="ui cancel button">Cancel</div>
      </div>
    </div>


    <div class="ui small modal" id="modCPass">
      <div class="header">Change Password</div>
      <div class="content">
        <div class="ui form">
          <div class="inline field">
            <label>Username</label>
            <input type="text" v-model="modPassword.target.username" disabled="disabled" />
          </div>
          <div class="inline field">
            <label>New Password</label>
            <input type="password" v-model="modPassword.pass" />
          </div>
        </div>
      </div>
      <div class="actions">
        <div class="ui green button" @click="changePass"><i class="disk icon"></i>Set Password</div>
        <div class="ui cancel button"><i class="escape icon"></i>Cancel</div>
      </div>
    </div>

  </div>
<?php }
