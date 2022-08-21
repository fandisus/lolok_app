import TreeViewItem from './TreeViewItem.js';
export default {
  name: 'TreeView',
  components: {TreeViewItem},
  props:{
    tree: Array,
    interactive: { type:Boolean, default:true },
    showRights: { type:Boolean, default:true}
  },
  data:function() { return {

  } },
  template:`
  <div class="ui list">
    <tree-view-item v-for="m in tree" :menuitem="m" ref="menuitems"></tree-view-item>
  </div>
  `,
  mounted: function() {},
  methods: {
    selectAll: function() { for (let sub of this.$refs.menuitems) { sub.selectAll(); } },
    unselectAll:function() { for (let sub of this.$refs.menuitems) { sub.unselectAll(); } },
    setMenuTree:function(pageRights) {
      for (let sub of this.$refs.menuitems) sub.setMenuTree(pageRights);
    },
    getPageRights:function() {
      let result = [];
      for (let sub of this.$refs.menuitems) result = result.concat(sub.getPageRights());
      return result;
    }
  }
}