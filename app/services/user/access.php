<?php
include DIR.'/app/templates/sidebar/layout.php';

function htmlHead() {?>
<script src="<?=WEBHOME?>libs/fomantic-2.8/tablesort.js"></script>
<style>
  .tree {
    height: 50vh; border:1px solid lightgrey; box-shadow: 1px 1px 2px 1px lightgrey;
    overflow: auto; padding: 10px;
  }
</style>
<?php }

function bodyEnd() {?>
<script type="module">
  import app from "<?= WEBHOME ?>js/access.js";
  $(document).ready(function() {
    window.vueapp = Vue.createApp(app).mount('#app');
    $('#tabs .button').tab();
  });
</script>
<?php }

function mainContent() {
  ?>
<div id="app" class="ui container">
  <!-- Breadcrumb -->
  <div class="ui breadcrumb">
    <div class="section">User Management</div>
    <i class="right angle icon divider"></i>
    <div class="active section">Access</div>
  </div>
  <hr />
  <!-- Table and Add Button -->
  <button class="ui green mini button" @click="add"><i class="plus icon"></i> New Access</button>
  <div class="ui cards">
    <div class="card" v-for="a in accesses">
      <div class="content">
        <i class="right floated red trash link icon" title="Delete" @click="del(a)"></i>
        <i class="right floated blue pencil link icon" title="Edit" @click="edit(a)"></i>
        <div class="header">{{a.name}} [{{a.role}}]</div>
        <div class="description">
          <tree-view :tree="a._menuTree" :show-rights="true" :interactive="false"></tree-view>
        </div>
      </div>
    </div>
  </div>

  <div class="ui tiny modal" id="modAccess">
    <div class="header">Access Input</div>
    <div class="scrolling content">
      <div class="ui form">
          <div class="two fields">
            <div class="field">
              <label>Access Name</label>
              <input type="text" v-model="form.name" />
            </div>
            <div class="field">
              <label>Role</label>
              <select v-model="form.role">
                <option value="">-- Role --</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </select>
            </div>
          </div>

          <div v-show="form.role === 'user'">
            <div class="ui tertiary button" @click="$refs.tree.selectAll()">Select All</div>
            <div class="ui tertiary button" @click="$refs.tree.unselectAll()">Unselect All</div>

            <tree-view :tree="availableMenus" :show-rights="true" ref="tree" ></tree-view>
          </div>
      </div>
    </div>
    <div class="actions">
      <div class="ui cancel tertiary button">Batal</div>
      <div class="ui green button" @click="saveOrUpdate">Simpan</div>
    </div>
  </div>
  <yes-no-modal ref="yesno"></yes-no-modal>
</div>
<?php }