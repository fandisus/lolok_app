var uri=WEBHOME+'user/users';
export default {
  name:'app',
  data: function() { return {
    modUser:{
      id:0, fullname:'', username:'', pass:'', cpass:'',
      accesses:[], email:'', phone:'', _selAccess:''
    },
    modPassword:{pass:'',cpass:'',target:{username:''}},
    users:[],
    accesses:[]
  }},
  mounted:function() { this.init(); },
  methods:{
    init:async function() {
      let rep = await tr.post(uri,{a:'init'});
      if (!rep) return;

      this.users = rep.users;
      this.accesses = rep.accesses;
    },
    showModUser:function(u) {
      if (u.username === undefined) {
        this.modUser = {
          id:0, fullname:'', username:'', pass:'', cpass:'',
          email:'', phone:'', accesses: []
        };
      } else {
        this.modUser = {
          id:u.id,
          username:u.username,
          fullname:u.fullname,
          email: u.email,
          phone: u.phone,
          accesses: u.accesses,
          pass:'', cpass:''
        };
      }
      $('#modUser').modal('show');
    },
    //Modal functions
    addAccess:function() {
      if (this.modUser._selAccess === '') return;
      if (this.modUser.accesses.includes(this.modUser._selAccess)) return;
      this.modUser.accesses.push(this.modUser._selAccess);
    },
    remAccess:function(a) { this.modUser.accesses.splice(this.modUser.accesses.indexOf(a), 1); },
    showCPass:function(u) {
      this.modPassword = { target: u, pass:'', cpass:'' };
      $('#modCPass').modal('show');
    },
    //END Modal functions

    //Data functions
    saveUser:async function() {
      let rep = await tr.post(uri, {a:'saveUser', u:JSON.stringify(this.modUser)});
      if (!rep) return;

      if (this.modUser.id === 0) { //New User
        this.users.push (rep.u);
        tr.notifySuccess('User Added');
      } else { //Update old user
        let u = this.users.find(r => r.id === rep.u.id);
        _.assign(u, rep.u);
        tr.notifySuccess('User updated');
      }
      $('#modUser').modal('hide');
    },
    changePass:async function() {
      let rep = await tr.post(uri,{a:'changePass', uid:this.modPassword.target.id, pass:this.modPassword.pass});
      $('body').toast({title:'Success', message:'Password changed', class:'success'});
      $('#modCPass').modal('hide');
    },
    delUser:function(u) {
      let that = this;
      $.modal('confirm', 'Are you sure?', `Confirm to delete User: ${u.username}?`, async function(yesno) {
        if (!yesno) return;
        let rep = await tr.post(uri, {a:'delUser', target:u.id});
        if (!rep) return;
        that.users.splice(that.users.indexOf(u), 1);
        $('body').toast({title:'Deleted!', message:`User ${u.username} has been deleted.`, class:"success"});
      });
    },
    //END Data functions
  }
}