<template>
  <div id="root-page">
    <img v-bind:src="images.top_right" id="top-right">
    <img v-bind:src="images.middle_left" id="middle-left">
    <img v-bind:src="images.computer" id="computer">

    <section class="container my-4">
      <nav class="navbar navbar-light">
        <a class="navbar-brand" href="#">
          <img v-bind:src="images.logo">
        </a>
      </nav>

      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <div class="my-4">
                <h3> Selamat Datang </h3>
                <p class="lead">
                  E-OFFICE DPC SPPI II PTP
                </p>
              </div>
              <form action="javascript:void(0)" class="row" method="post">
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa fa-user" aria-hidden="true"></i></span>
                  <input type="text" v-model="auth.username" name="username" id="username" class="form-control" aria-label="Username" aria-describedby="basic-addon1" placeholder="Username">
                </div>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa fa-solid fa-lock"></i></span>
                  <input type="password" v-model="auth.password" name="password" id="password" class="form-control" aria-label="password" aria-describedby="basic-addon1" placeholder="***">
                </div>
                <div class="col-12 mt-4">
                  <button type="submit" :disabled="processing" @click="login" class="btn btn-primary btn-block">
                    {{ processing ? "Please wait" : "Login" }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
  #root-page {
    background-image: url('~@/assets/background.svg');
    height: 100vh;
    margin-top: -2em;
  }

  #top-right {
    position: absolute;
    right: 0;
    width: 40%;
    overflow: hidden;
    line-height: 0;
  }

  #middle-left {
    position: absolute;
    top: 24%;
    left: 0;
    width: 10%;
    overflow: hidden;
    line-height: 0;
  }

  #computer {
    position: absolute;
    top: 15%;
    right: 3%;
    width: 40%;
    overflow: hidden;
    line-height: 0;
  }

  .navbar {
    padding: 4em 0;
  }

  .card {
    border-radius: 2rem;
    background: #F4F0F0;
  }

  .btn-primary {
    background-color: #02BFFF;
    border: 1px solid #02BFFF;
  }
</style>

<script>
import axios from '@/axios'
import { mapActions } from 'vuex'

export default {
  name: 'LoginView',
  data() {
    return {
      auth: {
        username: '',
        password: ''
      },
      processing: false,
      images: {
        "top_right": require("../assets/particles/top-right-login.svg"),
        "middle_left": require("../assets/particles/middle-left-login.svg"),
        "computer": require("../assets/particles/computer-login.svg"),
        "logo": require("../assets/logo.svg")
      }
    }
  },
  methods: {
    ...mapActions({
      signIn: 'auth/login'
    }),
    async login() {
      this.processing = true

      axios.get('/sanctum/csrf-cookie').then(res => {
        console.log(res)
        axios.post('/api/login',this.auth).then(({data})=>{
          this.signIn()
          console.log(data)
        }).catch(({response: {data}})=>{
          alert(data.message)
        }).finally(()=>{
          this.processing = false
        })
      })
    }
  }
}
</script>