<template>
  <div>
    <nav aria-label="breadcrumb fixed-top">
      <ol class="breadcrumb mt-4">
        <!-- Route title -->
        <li class="breadcrumb-item">
          <div class="d-flex" style="vertical-align:middle">
            <p class="lead" style="margin-right: .6em; "> {{ $route.name }} | </p> 
            <a href="/dashboard/" class="mt-1" :class="{ disabled: isLast(ci) }" @click="selected(crumb)">
              <i class="fa fa-home"></i> 
            </a>
          </div>
        </li>

        <!-- Breadcrumb -->
        <li
          v-for="(crumb, ci) in crumbs"
          :key="ci"
          class="breadcrumb-item my-1"
        >
          
          <div class="d-flex">
            <a :href="'/' + crumb.toLowerCase()" :class="{ disabled: isLast(ci) }" @click="selected(crumb)">
              {{ crumb }}
            </a>
          </div>
        </li>
      </ol>
    </nav>
  </div>
</template>

<script>
export default {
  name: 'BreadcrumbGlobal',
  props: {
    crumbs: {
      type: Array, 
      required: true
    }
  },
  mounted: function() {
    console.log(this.crumbs)
  },
  methods: {
    isLast(id) {
      return id === this.crumbs.length - 1
    },
    selected(crumb) {
      this.$emit('selected', crumb)
    }
  }
}
</script>

<style scoped>
a {
  color: #0d6efd !important;
}
</style>