<template>
  <div id="root" class="container my-4">
    <div class="card">
      <div class="card-body">
        <h1 class="text-center">Register</h1>

        <form action="javascript:void(0)" @submit="register" class="row" method="post">
          <div class="form-group col-12">
            <label for="name"> Name: </label>
            <input type="text" name="name" v-model="user.name" id="name" placeholder="johndoe" class="form-control my-2">
          </div>
          <div class="form-group col-12">
            <label for="username"> Username: </label>
            <input type="text" name="username" v-model="user.username" id="username" placeholder="John Doe" class="form-control my-2">
          </div>
          <div class="form-group col-12">
            <label for="nipp"> NIPP: </label>
            <input type="text" name="nipp" v-model="user.nipp" id="nipp" placeholder="1213141516" class="form-control my-2">
          </div>
          <div class="form-group col-12">
            <label for="email"> Email: </label>
            <input type="email" name="email" v-model="user.email" id="email" placeholder="johndoe@mail.com" class="form-control my-2">
          </div>
          <div class="form-group col-12">
            <label for="password"> Password: </label>
            <input type="password" name="password" v-model="user.password" id="password" placeholder="****" class="form-control my-2">
          </div>
          <div class="form-group col-12">
            <button class="btn btn-primary btn-block w-100 mt-2" type="submit"> Register </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>

<script>
import { mapActions } from 'vuex'
import axios from 'axios'

export default {
  name: "RegisterView",
  data() {
    return {
      user: {
        name: '',
        username: '',
        password: '',
        nipp: '',
        email: ''
      },
      processing: false
    }
  },
  methods:{
    ...mapActions({
      signIn: "auth/signIn"
    }),
    async register(){
      await axios.post('/register', this.user).then(() => {
        this.signIn(this.user)
      }).catch(({response: {data}}) => {
        alert(data.message)
      })
    }
  }
}
</script>