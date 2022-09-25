<template>
  <div>
    <div class="container">
      <h4> News </h4>

      <div class="row my-4">
        <div class="col-md-4">
          <router-link to="/admin/news/create" class="btn btn-primary"> Create News </router-link>
        </div>
      </div>

      <ul v-for="(data, id) in newsList" :key="id">
        <li>
          <div class="d-flex">
            <div class="flex-grow-1 ms-3">
              <h5> {{ data.title }} <small class="text-muted"><i> Posted on {{ data.created_at }} </i></small></h5>
              <p> {{ data.description }} </p>
            
              <router-link :to="{ path: '/news/read/' + data.id }" class="btn btn-primary btn-sm"> <i class="fa fa-eye"></i> Continue Reading </router-link> •
              <router-link :to="{ path: '/admin/news/edit/' + data.id }" class="btn btn-primary btn-sm"> <i class="fa fa-edit"></i> Edit </router-link> • 
              <a href="javascript:void();" @click="onDeleteNews(data.id)" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> Delete </a>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ListNewsView',
  data() {
    return {
      newsList: null
    }
  },
  mounted() {
    axios.get('/news/list').then((resp) => {
      this.newsList = resp.data.data
    })
  },
  methods: {
    onDeleteNews(id) {
      this.$swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.post('/news/delete', {
            id: id
          }).then((resp) => {
            if (resp.data.status == "Success") {
              this.$swal.fire({
                icon: 'success',
                title: 'All good.',
                text: 'Data has been deleted!'
              })

              /* Refresh */
              axios.get('/news/list').then((resp) => {
                this.newsList = resp.data.data
              })
            } else {
              this.$swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong! Please try again later.'
              })
            }
          })
        }
      })
    }
  }
}
</script>