export default {
  name: 'MenuRight',
  components: {},
  props:['right'],
  data:function() { return {
    selected:true
  } },
  template:`
    <span class="ui small text" :class="{green: selected}" @click="toggle($event)">{{right}}&nbsp;</span>
  `,
  mounted: function() {},
  methods: {
    toggle:function(e) {
      if (!this.interactive) return;
      this.selected = !this.selected;
    }
  }, computed: {
    interactive: function() {
      return this.$parent.interactive;
    }
  }
}