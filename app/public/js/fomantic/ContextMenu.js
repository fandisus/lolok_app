$('body').click(function() { $('.vue-el.contextMenu.menu').hide(); });

export default {
  name: 'ContextMenu',
  components: {},
  props:[],
  data:function() { return {
  } },
  template:`
  <div class="ui vertical menu vue-el contextMenu" style="position:absolute;display:none;">
    <slot></slot>
  </div>
  `,
  mounted: function() {},
  methods: {
    show:function(e) {
      console.log(e);
      let bodyBounds = this.$el.parentElement.getBoundingClientRect();

      let pointX = (bodyBounds.width > e.layerX + this.$el.clientWidth) ? e.layerX : e.layerX - this.$el.clientWidth;
      let pointY = (bodyBounds.height > e.layerY + this.$el.clientHeight) ? e.layerY : e.layerY - this.$el.clientHeight;

      this.$el.style.display = 'none';
      this.$el.style.left = `${pointX}px`;
      this.$el.style.top = `${pointY}px`;
      $(this.$el).fadeIn();
    }
  }
}