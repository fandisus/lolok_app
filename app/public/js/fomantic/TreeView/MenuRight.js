let componentStyle = document.createElement('style');
componentStyle.innerHTML = `
.tvi.item > .content > .description > input[type=checkbox] { margin-right:3px; }
`;
document.head.appendChild(componentStyle);
export default {
  name: 'MenuRight',
  components: {},
  props:['right'],
  data:function() { return {
    selected:true
  } },
  template:`
    <input type="checkbox" v-model="selected" v-if="interactive"/>
    <span class="ui text" :class="{green: selected}" @click="toggle($event)">{{right}}&nbsp;</span>
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