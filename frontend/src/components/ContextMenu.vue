<template>
  <div
    class="context-menu"
    ref="popper"
    v-show="isVisible"
    tabindex="-1"
    @contextmenu.capture.prevent
    v-click-outside="onClickOutside"
  >
    <ul>
      <slot :contextData="contextData" />
    </ul>
  </div>
</template>

<script>
import { createPopper } from '@popperjs/core'
import vClickOutside from 'v-click-outside'

export default {
  props: {
    boundariesElement: {
      type: String,
      default: 'body',
    },
  },
  data() {
    return {
      opened: false,
      contextData: {},
      popper: null
    };
  },
  directives: {
    clickOutside: vClickOutside.directive,
  },
  computed: {
    isVisible() {
      return this.opened;
    },
  },
  methods: {
    open(evt, contextData) {
      this.opened = true;
      this.contextData = contextData;
      
      if (this.popper) {
        this.popper.destroy();
      }

      this.popper = new createPopper(this.referenceObject(evt), this.$refs.popper, {
        placement: 'right',

      });
    },
    onClickOutside(event) {
      console.log('Clicked outside. Event: ', event)
      this.opened = false;
      this.contextData = null;
    },
    referenceObject(evt) {
      const left = evt.clientX;
      const top = evt.clientY;
      const right = left + 1;
      const bottom = top + 1;
      const clientWidth = 1;
      const clientHeight = 1;

      function getBoundingClientRect() {
        return {
          left,
          top,
          right,
          bottom,
        };
      }

      const obj = {
        getBoundingClientRect,
        clientWidth,
        clientHeight,
      };
      return obj;
    },
  },
  beforeUnmount() {
    if (this.popper !== undefined) {
      this.popper.destroy();
    }
  },
};

</script>

<style lang="scss" scoped>

.context-menu {
  position: fixed;
  z-index: 999;
  overflow: hidden;
  background: #FFF;
  border-radius: 4px;
  box-shadow: 0 1px 4px 0 #eee;
  
  &:focus {
      outline: none;
  }

  ul {
    padding:0px;
    margin:0px;
  }
}


</style>
