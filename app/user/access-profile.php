<?php
if (!$login->canAccess(APP_PATH, 'read')) header('location:'.WEBHOME.'user/403');

include DIR.'/engine/templates/sidebar_layout.php';

function htmlHead() {?>
<script src="<?=WEBHOME?>libs/fomantic-2.8/tablesort.js"></script>
<style>
  #app { padding: 15px; }
  .ui.table { white-space: nowrap; }
  .ui.table td { padding: 0.10em; }
  #modUser .ui.form .inline.field label:first-child { width: 120px; }
  #modCPass .ui.form .inline.field label:first-child { width: 120px; }
  .islink { cursor: pointer; }
  .bold { font-weight: bolder;}
  .tree {
    height: 50vh; border:1px solid lightgrey; box-shadow: 1px 1px 2px 1px lightgrey;
    overflow: auto; padding: 10px;
  }
</style>
<?php }

function bodyEnd() {?>
<script type="module">
  import app from "<?= WEBHOME ?>js/access-profile.js";
  $(document).ready(function() {
    Vue.createApp(app).mount('#app');
    $('#tabs .button').tab();
    $('#data-table').tablesort();
  });
</script>
<?php }

function mainContent() {
  ?>
<div id="app" class="ui container">
  <h1>User Access Profile</h1>
  <button class="ui green mini button" @click="add"><i class="plus icon"></i> New Profile</button>
  <table class="ui striped unstackable very compact sortable collapsing table" id="data-table">
    <thead>
      <tr>
        <th>Name</th>
        <th class="no-sort">Menus</th>
        <th class="no-sort"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="p in profiles">
        <td>{{p.name}}</td>
        <td><a class="islink" @click="edit(p)">
          <div v-for="m in p.menu_tree">
            <span class="ui text">{{m.text}}&nbsp;</span>
            <span v-for="sm in m.subMenus" class="ui small text">&bullet;{{sm.text}}&nbsp;</span>
          </div>
        </a></td>
        <td>
          <i class="blue small pencil icon islink" title="Edit" @click="edit(p)"></i>
          <i class="red small trash icon islink" title="Delete" @click="del(p)"></i>
        </td>
      </tr>
    </tbody>
  </table>

  <div class="ui tiny modal" id="modProfile">
    <div class="header">Access Profile Input</div>
    <div class="content">
      <div class="ui form">
        <div class="inline field">
          <input type="text" placeholder="Profile Name" v-model="form.name" />
        </div>
      </div>
      <div class="tree">
        <treeview :tree="availableMenus" ref="tree"></treeview>
      </div>
    </div>
    <div class="actions">
      <div class="ui cancel tertiary button">Batal</div>
      <div class="ui tertiary button" @click="$refs.tree.selectAll()">Select All</div>
      <div class="ui tertiary button" @click="$refs.tree.unselectAll()">Unselect All</div>
      <div class="ui green button" @click="saveOrUpdate">Simpan</div>
    </div>
  </div>
  <yes-no-modal ref="yesno"></yes-no-modal>
</div>
<?php }