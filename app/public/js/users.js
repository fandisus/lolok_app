import yesNoModal from './fomantic/yesNoModal.js';
var uri=WEBHOME+'user/users';
export default {
  name:'app',
  components: { yesNoModal },
  data: function() { return {
    modUser:{
      id:0, fullname:'', username:'', pass:'', cpass:'',
      accessProfile:'', email:'', phone:''
    },
    modPassword:{pass:'',cpass:'',target:{username:''}},
    users:[],
    accessProfiles:[]
  }},
  mounted:function() { this.init(); },
  methods:{
    init:function() {
      tr.post(uri,{a:'init'}, rep=>{
        this.users = rep.users;
        this.accessProfiles = rep.accessProfiles;
      });
    },
    showModUser:function(u) {
      if (u.username === undefined) {
        this.modUser = {
          id:0, fullname:'', username:'', pass:'', cpass:'',
          accessProfile:'', email:'', phone:''
        };
      } else {
        this.modUser = {
          id:u.id,
          username:u.username,
          fullname:u.fullname,
          email: u.email,
          phone: u.phone,
          accessProfile:u.accessProfile,
          pass:'', cpass:''
        };
      }
      $('#modUser').modal('show');
    },
    saveUser:function() {
      tr.post(uri, {a:'saveUser', u:JSON.stringify(this.modUser)}, rep=> {
        if (this.modUser.id === 0) { //New User
          this.users.push (rep.u);
          $('body').toast({title:'Success', message:'User added', class:'success'});
        } else { //Update old user
          let u = this.users.find(r => r.id === rep.u.id);
          _.assign(u, rep.u);
          $('body').toast({title:'Success', message:'User updated', class:'success'});
        }
        $('#modUser').modal('hide');
      });
    },
    showCPass:function(u) {
      this.modPassword = { target: u, pass:'', cpass:'' };
      $('#modCPass').modal('show');
    },
    changePass:function() {
      tr.post(uri,{a:'changePass', uid:this.modPassword.target.id, pass:this.modPassword.pass}, rep=>{
        $('body').toast({title:'Success', message:'Password changed', class:'success'});
        $('#modCPass').modal('hide');
      });
    },
    delUser:function(u) {
      let yesno = this.$refs.yesno;
      yesno.show('Are you sure?', `Confirm to delete User: ${u.username}?`, ()=>{
        tr.post(uri, {a:'delUser', target:u.id}, rep=>{
          this.users.splice(this.users.indexOf(u), 1);
          $('body').toast({title:'Deleted!', message:`User ${u.username} has been deleted.`, class:"success"});
        });
      });

    },
  }
}