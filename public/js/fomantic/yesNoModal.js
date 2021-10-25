export default {
  name: 'yesNoModal',
  components: {},
  props:[],
  data:function() { return {
    header:'', message:'', onYes:null, onNo:null
  } },
  template:`
  <div class="ui mini inverted modal">
    <div class="header">{{header}}</div>
    <div class="content">{{message}}</div>
    <div class="actions">
      <div class="ui inverted negative button" @click="noClicked"> No </div>
      <div class="ui inverted positive right labeled icon button" @click="yesClicked">Yes<i class="checkmark icon"></i></div>
    </div>
  </div>
  `,
  mounted: function() {},
  methods: {
    noClicked:function() { if (this.onNo) this.onNo(); this.onNo = null; },
    yesClicked:function() { if (this.onYes) this.onYes(); this.onYes = null; },
    show:function(header,message, yesCb=null, noCb=null) {
      this.header = header;
      this.message = message;
      this.onYes = yesCb;
      this.onNo = noCb;
      $(this.$el).modal('show');
    }
  }
}