var uri=WEBHOME+'login';
export default {
  name: 'app',
  data: function() { return {
    username:'',password:''
  }},
  methods: {
    tryLogin: async function() {
      let rep = await tr.post(uri, {username:this.username, password:this.password});
      if (!rep) return;

      $('body').toast({
        title:'Login success', message:'Login successful. You will be directed to member\'s page', class:'success',
        showProgress:'bottom', onHide:()=>{ window.location=WEBHOME+'user/'; }
      });
    }
  }
}