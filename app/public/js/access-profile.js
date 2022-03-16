import treeview from './access-profile/treeview.js';
import yesNoModal from './fomantic/yesNoModal.js';
let uri = WEBHOME + 'user/access-profile';
export default {
  name: 'app',
  components: {treeview, yesNoModal},
  data:function() { return {
    profiles: [], availableMenus:[],
    form: { name:'', menu_tree:[], pk:'' },
  } },
  mounted: function() { this.init(); this.getData(); },
  methods: {
    init:function() { tr.post(uri, {a:'init'}, rep=>{ this.availableMenus = rep.availableMenus; }); },
    getData:function() { tr.post(uri, {a:"getData"}, rep=> { this.profiles = rep.profiles; }); },
    add:function() {
      this.form = { name:'', menu_tree:[], pk:'' };
      this.$refs.tree.setMenuTree(this.availableMenus);
      $('#modProfile').modal('show');
    },
    edit:function(p) {
      this.form.name = p.name;
      this.form.pk = p.name;
      this.$refs.tree.setMenuTree(p.menu_tree);
      $('#modProfile').modal('show');
    },
    del: function(p) {
      let yesno = this.$refs.yesno;
      yesno.show('Confirmation', 'Are you sure?', ()=>{
        tr.post(uri, {a:'remove', name: p.name}, rep=>{
          let idx = this.profiles.indexOf(p);
          this.profiles.splice(idx, 1);
          $('body').toast({title:'Success', message:'Access Profile removed', class:"success"});
        });
      });
    },
    saveOrUpdate() {
      this.form.menu_tree = this.$refs.tree.getPojo();
      tr.post(uri, { a:'save', obj: JSON.stringify(this.form) }, rep=>{
        $('body').toast({title:'Success', message:'Data berhasil disimpan.', class:'success'});
        let newProfile = _.cloneDeep(this.form);
        if (this.form.pk != '') { //When updating
          let obj = this.profiles.find(p=>p.name === this.form.pk);
          _.assign(obj, newProfile);
        } else this.profiles.push(newProfile); //When inserting
        $('#modProfile').modal('hide');
      });
    },
    menu_names:function(menuTree) {
      let texts = [];
      for (let m of menuTree) texts.push(m.text);
      return texts.join(', ');
    }
  }
}