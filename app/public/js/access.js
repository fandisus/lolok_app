import TreeView from './fomantic/TreeView/TreeView.js';
import yesNoModal from './fomantic/yesNoModal.js';
let uri = WEBHOME + 'user/access';
export default {
  name: 'app',
  components: {TreeView, yesNoModal},
  data:function() { return {
    accesses: [], availableMenus:[],
    form: { name:'', menu_tree:[], pk:'' },
  } },
  mounted: async function() { await this.init(); },
  methods: {
    init:async function() {
      let rep = await tr.post(uri, {a:'init'});
      if (!rep) return;
      this.availableMenus = rep.availableMenus;
      this.accesses = rep.accesses;
    },
    getData:async function() {
      let rep = await tr.post(uri, {a:"getData"});
      if (rep) this.accesses = rep.accesses;
    },

    //### Modal Methods
    add:function() {
      this.form = { name:'', role:'user', menu_tree:[], pk:'' };
      this.$refs.tree.unselectAll();
      $('#modAccess').modal('show');
    },
    edit:function(a) {
      this.form.name = a.name;
      this.form.role = a.role;
      this.form.pk = a.name;
      this.$refs.tree.setMenuTree(a._pages);
      $('#modAccess').modal('show');
    },

    //### Data Actions
    del: function(p) {
      let yesno = this.$refs.yesno;
      yesno.show('Confirmation', 'Are you sure?', ()=>{
        let rep = tr.post(uri, {a:'delete', name: p.name});
        if (!rep) return;

        let idx = this.accesses.indexOf(p);
        this.accesses.splice(idx, 1);
        tr.notifySuccess('Access Profile Deleted');
      });
    },
    async saveOrUpdate() {
      this.form.menu_tree = this.$refs.tree.getPageRights();
      let isNew = this.form.pk === '';
      let rep = await tr.post(uri, { a:(isNew)?'save':'update', obj: JSON.stringify(this.form) });
      if (!rep) return;

      tr.notifySuccess('Access Profile Saved');
      let newAccess = rep.newAccess;
      if (isNew) this.accesses.push(newAccess);
      else {
        let obj = this.accesses.find(a=>a.name === this.form.pk);
        _.assign(obj, newAccess);
      }
      $('#modAccess').modal('hide');
    },
  }
}