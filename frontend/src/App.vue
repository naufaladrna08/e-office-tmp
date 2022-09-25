<template>
  <div>
    <div v-if="user == false && isNewsPage()">
      <nav class="navbar navbar-light py-4 bg-light">
        <div class="container">
          <a class="navbar-brand" href="/"> E-Office </a>
        </div>
      </nav>
    </div>

    <div v-if="user">
      <div class="sidebar d-flex flex-column flex-shrink-0 bg-light" style="width: 4.5rem;">
        <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
          <li class="nav-item">
            <router-link to="/dashboard" class="nav-link py-3 border-bottom" :class="currentRoute === 'Dashboard' ? 'active' : ''" title="Dashboard">
              <i class="fa fa-home" style="width: 24px; height: 24px"></i>
            </router-link>
          </li>
          <!-- <li>
            <router-link to="/user" class="nav-link py-3 border-bottom" :class="currentRoute === 'User' ? 'active' : ''" title="User">
              <i class="fa fa-user" style="width: 24px; height: 24px"></i>
            </router-link>
          </li> -->
          <li>
            <!-- <router-link to="/mail/new" class="nav-link py-3 border-bottom" :class="currentRoute === 'Create Mail' ? 'active' : ''" title="Create Mail">
              <i class="fa fa-inbox" style="width: 24px; height: 24px"></i>
            </router-link> -->
            <div class="dropdown border-top">
              <a href="#" class="d-flex align-items-center justify-content-center p-3 text-decoration-none" id="maildropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-inbox" style="width: 24px; height: 24px"></i>
              </a>
              <ul class="dropdown-menu text-small shadow" aria-labelledby="maildropdown">
                <li><a class="dropdown-item" href="/mail/new"> New Message </a></li>
                <li><a class="dropdown-item" href="/mail/inbox"> Inbox </a></li>
              </ul>
            </div>
          </li>
          <li>
            <router-link to="/archive" class="nav-link py-3 border-bottom" :class="currentRoute === 'Archive' ? 'active' : ''" title="Archive">
              <i class="fa fa-archive" style="width: 24px; height: 24px"></i>
            </router-link>
          </li>
          <li v-if="userdata.role === 'admin'">
            <router-link to="/admin" class="nav-link py-3 border-bottom" title="Admin">
              <i class="fa fa-dashboard" style="width: 24px; height: 24px"></i>
            </router-link>
          </li>
          <li>
            <a href="javascript:void();" @click="logout" class="nav-link py-3 border-bottom" title="Logout">
              <i class="fa fa-sign-out" style="width: 24px; height: 24px"></i>
            </a>
          </li>
        </ul>
        <div class="dropdown border-top">
          <a href="#" class="d-flex align-items-center justify-content-center p-3 link-dark text-decoration-none dropdown-toggle" id="dropdownUser3" data-bs-toggle="dropdown" aria-expanded="false">
            <img :src="userdata.profile_path" width="24" height="24" class="rounded-circle">
          </a>
          <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser3">
            <li><a class="dropdown-item" href="javascript:void(0);" @click="logout"> Sign out </a></li>
          </ul>
        </div>
      </div>

      <nav class="navbar navbar-light bg-light">
        <div class="container px-2 my-2">
          <b> E-Office </b>
        </div>
      </nav>

      <div class="container">
        <BreadcrumbGlobal :crumbs="crumbs" @selected="selected" />
      </div>
    </div>

    <router-view />

    <div v-if="user">
      <div class="container" style="margin-top: 8em">
        <footer class="my-4 text-center text-secondary small"> {{ pageFooter }} </footer>  
      </div>

    <div class="modal fade show" id="changePassword" ref="passwordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changePasswordLabel"> Update Password </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="lead"> Please update your password for your security. </p>
            <div class="form">
              <div class="form-group">
                <label for="password"> New Password </label>
                <input type="password" class="form-control my-2" id="password" name="password" v-model="newpassword">
              </div>
              <div class="form-group">
                <label for="password"> Confirm Password </label>
                <input type="password" class="form-control my-2" id="cnewpassword" name="cnewpassword" v-model="cnewpassword">
              </div>
              <p class="lead text-danger"> {{ errorpassword }} </p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="assign-divisi-button" @click="changePassword()"> Apply and Continue </button>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

<script>
import store from './store'
import axios from 'axios'
import { mapActions } from 'vuex'
import BreadcrumbGlobal from './components/BreadcrumbGlobal.vue'
import { Modal } from 'bootstrap'
import 'bootstrap/dist/js/bootstrap.js'

export default {
  name: 'App',
  components: {
    BreadcrumbGlobal
  },
  data() {
    return {
      newpassword: null,
      errorpassword: null,
      cnewpassword: null,
      user: store.state.auth.authenticated,
      userdata: {
        "uid": null,
        "id": null,
        "name": null,
        "email": null,
        "username": null,
        "code_divisi": null,
        "code_jabatan": null,
        "profile_photo_id": null,
        "cover_photo_id": null,
        "remember_token": null,
        "created_at": null,
        "updated_at": null,
        "nama_divisi": null,
        "nama_jabatan": null,
        "profile_path": null,
        "cover_path": null,
        "roles": null,
        "password_changed": null
      },
      currentRoute: 'dashboard',
      crumbs: [],
      pageFooter: null,
      modal: null
    }
  },
  methods: {
    ...mapActions({
      signOut: "auth/signOut"
    }),
    async logout() {
      this.signOut()
      this.$router.push('/login')
    },
    changePassword() {
      if (this.newpassword != this.cnewpassword) {
        this.errorpassword = 'Password mismatch'
        return
      }

      /* Clear the error message */
      this.errorpassword = ''

      axios.post('/profile/update-password', {
        'password': this.newpassword
      }).then((resp) => {
        this.modal.hide()

        if (resp.data.code == 200) {
          this.$swal.fire(
            'Updated!',
            'Your password has been updated.',
            'success'
          )
        } else {
          this.$swal.fire(
            'Failed!',
            'Your password not updated.',
            'failed'
          )
        }
        
      })
    },
    isNewsPage() {
      return this.$route.name === 'Read News'
    }
  },
  watch:{
    $route() {
      this.user = store.state.auth.authenticated
      this.currentRoute = this.$route.name
      this.crumbs[0] = this.$route.name
      
      if (this.user) {
        axios.get('/profile/userdata').then((resp) => {
          this.userdata = resp.data.data
        })
      }
    }
  },
  created() {
    if (this.user) {
      axios.get('/profile/userdata').then((resp) => {
        this.userdata = resp.data.data
      
        if (this.userdata.password_changed == false) {
          this.modal = new Modal(this.$refs.passwordModal)
          this.modal.show()
        }
      })

      axios.get('/parameter/fetch?type=page_footer').then((resp) => {
        this.pageFooter = resp.data.data.description
      })
    }
  },
}
</script>

<style>
#app {
  font-family: 'Open Sans', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #222222;
}

.sidebar {
  background-color: rgba(115, 24, 152, 1);
  position: fixed;
  height: 100vh !important;
  z-index: 9999 !important;
}

</style>
