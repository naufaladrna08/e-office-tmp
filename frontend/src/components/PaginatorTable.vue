<template>
  <div style="margin-top: 2.5em">
    <nav aria-label="Page navigation">
      <div class="text-lead my-3">
        Showing {{ total }} of {{ pagination.total }}
      </div>
      <ul class="pagination">
        <li class="page-item" 
          :class="{'disabled' : pagination.current_page === 1}"
          @click.prevent="changePage(1)"
        >
          <a class="page-link" href="#"> First </a>
        </li>

        <li 
          class="page-item" 
          :class="{'disabled' : pagination.current_page === 1}"
          @click.prevent="changePage(pagination.current_page - 1)"  
        >
          <a class="page-link" href="#">Previous</a>
        </li>

        <li 
          class="page-item" 
          v-for="page in pageNumbers" 
          :key="page" 
          :class="{'active' : page == pagination.current_page}"
          @click.prevent="changePage(page)"
        >
          <a class="page-link" href="#"> {{ page }} </a>  
        </li>

        <li 
          class="page-item" 
          :class="{'disabled' : pagination.current_page === pagination.last_page}"
        >
          <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1)">Next</a>
        </li>

        <li 
          class="page-item"
          @click.prevent="changePage(pagination.last_page)"
          :class="{'disabled' : pagination.current_page === pagination.last_page}"
        >
          <a class="page-link" href="#"> Last </a>
        </li>
      </ul>
    </nav>
  </div>  
</template>

<script>
export default {
  name: 'PaginatorTable',
  props: {
    pagination: { type: Object, required: true },
    total: { type: String, required: true }
  },
  data() {
    return {
      offset: 4
    }
  },
  computed: {
    pageNumbers() {
      let pages = []
      if (!this.pagination.to) return []
      let from = this.pagination.current_page - this.offset
      if (from < 1) from = 1
      let to = from + (this.offset * 2)
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page
      }

      for (let page = from; page <= to; page++) {
        pages.push(page)
      }

      return pages
    }
  },
  methods: {
    changePage(pageNumber) {
      this.$emit("pageChanged", pageNumber)
    }
  }
}
</script>

<style scoped>

</style>