import menuRight from './menuRight.js';
export default {
  name: 'treeviewitem',
  components: {menuRight},
  props:['menuitem'],
  data:function() { return {
    colapsed:false, selected:true,
    subTreeEls:[], rightEls:[],
  } },
  template:`
  <div class="item">
    <i :class="[icon]" class="icon" @click="toggle"></i>
    <div class="content">
      <div class="header" @click="select($event)" style="user-select:none;" :title="menuitem.href">
        <span :class="[selectClass]" class="ui text">{{menuitem.text}}</span>
      </div>
      <div class="description" style="user-select:none;">
        <menu-right v-for="r in menuitem.rights" :right="r" :ref="setRightEls"></menu-right>
      </div>
      <div class="list" v-if="menuitem.subMenus.length > 0 && !colapsed">
        <treeviewitem v-for="m in menuitem.subMenus" :menuitem="m" @select-changed="handleSelectEmit" :ref="setSubtrees"></treeviewitem>
      </div>
    </div>
  </div>
  `,
  mounted: function() {},
  methods: {
    setSubtrees:function(el) {
      let filter = this.subTreeEls.filter(ref=> ref.menuitem.name === el.menuitem.name);
      if (filter.length === 0) this.subTreeEls.push(el);
    },
    setRightEls:function(el) {
      let filter = this.rightEls.filter(ref=>ref.right === el.right);
      if (filter.length === 0) this.rightEls.push(el);
    },
    toggle:function() { this.colapsed = !this.colapsed; },
    select:function(e) {
      if (this.interactive) this.selected = !this.selected;
      // if (e.ctrlKey) { this.selected =  !this.selected; return; }
      // if (this.selected) this.selected = false;
      // else {
      //   this.$emit('selectChanged', this.menuitem.name);
      //   this.selected=true;
      // }
    },
    handleSelectEmit:function(val) { this.$emit('selectChanged', val); },
    unselect:function() { //Temporarily not used.
      this.selected = false;
      for (let sub of this.subTreeEls) sub.unselect();
    },
    resetSelect:function(access) {
      let f = access.filter(a=>a.name === this.menuitem.name);
      if (f.length > 0) {
        this.selected = true;
        let menuRight = f.pop();
        for (let r of this.rightEls) r.selected = (menuRight.rights.indexOf(r.right) > -1);
      } else {
        this.selected = false;
        for (let r of this.rightEls) r.selected = false;
      }
      for (let sub of this.subTreeEls) sub.resetSelect(access);
    },
    getPojo:function() {
      let mi = _.cloneDeep(this.menuitem);
      let result = {name:mi.name, text:mi.text, icon:mi.icon, href:mi.href, rights:[], subMenus:[]}
      for (let r of this.rightEls) if (r.selected) result.rights.push(r.right);
      for (let s of this.subTreeEls) {
        if (s.selected) {
          let subPojo = s.getPojo();
          if (subPojo.rights.length > 0 || subPojo.subMenus.length > 0) result.subMenus.push(subPojo);
        } 
      }
      return result;
    },
    selectAll:function() {
      this.selected = true;
      for (let sub of this.subTreeEls) sub.selectAll();
      for (let r of this.rightEls) r.selected = true;
    },
    unselectAll:function() {
      this.selected = false;
      for (let sub of this.subTreeEls) sub.unselectAll();
      for (let r of this.rightEls) r.selected = false;
    }

  },
  computed: {
    icon: function() {
      if (this.menuitem.subMenus.length === 0) return 'file alternate outline';
      if (this.colapsed) return 'yellow folder';
      return 'yellow folder open';
    },
    selectClass: function() {
      if (this.selected) return 'blue bold';
      else return '';
    },
    interactive: function() {
      return this.$parent.interactive;
    }
  }
}