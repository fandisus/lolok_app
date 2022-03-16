import treeviewitem from './treeviewitem.js';
export default {
  name: 'treeview',
  components: {treeviewitem},
  props:{tree: Array, interactive: { type:Boolean, default:true }},
  data:function() { return {
    subTreeEls:[]
  } },
  template:`
  <div class="ui list">
    <treeviewitem v-for="m in tree" :menuitem="m" :ref="setSubtrees" @select-changed="handleSelectEmit"></treeviewitem>
  </div>
  `,
  mounted: function() {},
  methods: {
    setSubtrees:function(el) { 
      let filter = this.subTreeEls.filter(ref=> ref.menuitem.name === el.menuitem.name);
      if (filter.length === 0) this.subTreeEls.push(el);
    },
    handleSelectEmit:function(val) {
      // if (!this.interactive) return;
      // for (let sub of this.subTreeEls) { sub.selectDown(val); }
      // for (let sub of this.subTreeEls) { sub.unselect(); }
    },
    selectAll: function() { for (let sub of this.subTreeEls) { sub.selectAll(); } },
    unselectAll:function() { for (let sub of this.subTreeEls) { sub.unselectAll(); } },
    setMenuTree:function(tree) {
      let rights = this.flattenTree(tree);
      for (let sub of this.subTreeEls) sub.resetSelect(rights);
    },
    flattenTree:function(tree) {
      let result = _.cloneDeep(tree);
      let hasChild = true;
      while (hasChild) {
        hasChild = false;
        for (var t of result) {
          while (t.subMenus.length > 0) {
            result.push(t.subMenus.shift());
            hasChild = true;
          }
        }
      }
      return result;
    },
    getPojo:function() {
      let result = []
      for (let s of this.subTreeEls) {
        if (s.selected) {
          let subPojo = s.getPojo();
          if (subPojo.rights.length > 0 || subPojo.subMenus.length > 0) result.push(subPojo);
        } 
      }
      return result;
    }
  }
}