let componentStyle = document.createElement('style');
componentStyle.innerHTML = `
.tvi.item > .content > .header,
.tvi.item > .content > .description { user-select:none; }
.tvi.item > .content > .description { padding-top:4px; }
`;
document.head.appendChild(componentStyle);

import menuRight from './MenuRight.js';
export default {
  name: 'TreeViewItem',
  components: {menuRight},
  props:['menuitem'],
  data:function() { return {
    colapsed:false, selected:true,
  } },
  template:`
  <div class="tvi item">
    <i :class="[icon]" class="icon" @click="toggle"></i>
    <div class="content">
      <div class="header" :title="menuitem.url">
        <span :class="[selectClass]" class="ui text" @click="select">
          <span v-if="!menuitem.subMenus" class="ui red text">[mobile]</span> {{menuitem.text}}
        </span>
      </div>
      <div class="description" v-if="showRights">
        <menu-right v-for="r in menuitem.rights" :right="r" ref="rights"></menu-right>
      </div>
      <template v-if="menuitem.subMenus">
        <div class="list" v-if="menuitem.subMenus.length > 0 && !colapsed">
          <tree-view-item v-for="m in menuitem.subMenus" :menuitem="m" @select-changed="handleSelectEmit" ref="menuitems"></tree-view-item>
        </div>
      </template>
    </div>
  </div>
  `,
  mounted: function() {},
  methods: {
    toggle:function() {
      if (!this.interactive) return;
      this.colapsed = !this.colapsed;
    },
    select:function(e) {
      if (!this.interactive) return;
      this.selected = !this.selected;
      this.selectEffects();
    },
    selectEffects:function() {
      let menuitems = this.$refs.menuitems ?? [];
      if (this.selected){
        for (let sub of menuitems) sub.selectAll();
      } else {
        for (let sub of menuitems) sub.unselectAll();
      }
      this.$emit('selectChanged', this.selected);
      let rights = this.$refs.rights;
      if (!rights) return;
      for (let r of rights) r.selected = this.selected;
    },
    handleSelectEmit:function(val) {
      this.$emit('selectChanged', val);
      let menuitems = this.$refs.menuitems ?? [];
      //Cek apakah semua anak terpilih atau tidak terpilih?
      let firstSubSelection = menuitems[0].selected;
      for (let m of menuitems) if (m.selected !== firstSubSelection) return;
      //Jika sama semua, samakan parent.
      this.selected = firstSubSelection;
    },
    unselect:function() { //Temporarily not used.
      this.selected = false;
      let menuitems = this.$refs.menuitems ?? [];
      for (let sub of menuitems) sub.unselect();
    },
    setMenuTree:function(pageRights) {
      let menuitems = this.$refs.menuitems ?? [];
      //Unselect all first.
      this.selected = false;
      let vmrights = this.$refs.rights ?? [];
      for (let r of vmrights) r.selected = false;

      for (let sub of menuitems) {
        sub.setMenuTree(pageRights);
        if (sub.selected) this.selected=true;
      }
      let pages = pageRights.map((pr)=>pr.url);
      if (pages.includes(this.menuitem.url)) {
        this.selected = true;
        let rightsToSet = pageRights.find(pr=>pr.url === this.menuitem.url);
        for (let r of vmrights) r.selected = rightsToSet.includes(r.right);
      }
    },
    getPageRights:function() {
      let result = [];
      if (this.selected && this.menuitem.url !== '') {
        let selectedRights = [];
        let vmrights = this.$refs.rights ?? [];
        for (let r of vmrights) if (r.selected) selectedRights.push(r.right);
        result.push({url:this.menuitem.url, rights:selectedRights});
      }
      let vmMenuItems = this.$refs.menuitems ?? [];
      for (let sub of vmMenuItems) result = result.concat(sub.getPageRights());
      return result;
    },
    selectAll:function() {
      this.selected = true;
      let menuitems = this.$refs.menuitems ?? [];
      for (let sub of menuitems) sub.selectAll();
      let rights = this.$refs.rights ?? [];
      for (let r of rights) r.selected = true;
    },
    unselectAll:function() {
      this.selected = false;
      let menuitems = this.$refs.menuitems ?? [];
      for (let sub of menuitems) sub.unselectAll();
      let rights = this.$refs.rights ?? [];
      for (let r of rights) r.selected = false;
    }

  },
  computed: {
    icon: function() {
      if (this.menuitem?.subMenus.length === 0) return 'file alternate outline';
      if (this.colapsed) return 'yellow folder';
      return 'yellow folder open';
    },
    selectClass: function() {
      if (this.selected) return 'blue bold';
      else return '';
    },
    interactive: function() { return this.$parent.interactive; },
    showRights: function() { return this.$parent.showRights; }
  }
}