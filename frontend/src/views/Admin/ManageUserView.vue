<template>
  <div class="container">
    <h1> Manage User </h1>

    <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab" aria-controls="divisi" aria-selected="true"> User List </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="group-tab" data-bs-toggle="tab" data-bs-target="#group" type="button" role="tab" aria-controls="group" aria-selected="true"> Group List </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="role-tab" data-bs-toggle="tab" data-bs-target="#role" type="button" role="tab" aria-controls="role" aria-selected="false"> Assign Role </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="jabatan-tab" data-bs-toggle="tab" data-bs-target="#jabatan" type="button" role="tab" aria-controls="jabatan" aria-selected="false"> Assign Jabatan </button>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <input id="fileUpload" @change="handleFileUpload($event)" type="file" hidden>

      <div class="tab-pane fade show active" id="user" role="tabpanel" aria-labelledby="user-tab">
        <div class="my-4">
          <button 
            class="btn btn-primary"
            @click.prevent="showUserModal()"
          > 
            Create 
          </button> 
          <button class="btn btn-primary mx-2" @click="chooseFiles()"> Upload </button> 
        </div>
      
        <DataTables
          url="profile/list"
          tclass="table table-bordered w-100 p-2" 
          :columns="listUsers"
          useNumber="1"
          useAction="1"
          ref="users"
          @actionClicked="editUser"
          @deleteClicked="deleteUser"
        />
      </div>
      <div class="tab-pane fade" id="group" role="tabpanel" aria-labelledby="group-tab">
        <div class="my-4">
          <button 
            class="btn btn-primary" 
            @click="showCreateGroupModal()" 
          > 
            Create 
          </button>
        </div>
      
        <DataTables
          url="group/list"
          tclass="table table-bordered w-100 p-2" 
          :columns="listGroups"
          useNumber="1"
          useAssigner="1"
          @actionClicked="showGroupAssigner"
          ref="groups"
        />
      </div>
      <div class="tab-pane fade" id="role" role="tabpanel" aria-labelledby="role-tab">
        <br>
        
        <DataTables
          url="role/get-data"
          tclass="table table-bordered w-100 p-2" 
          :columns="roleColumn"
          useNumber="1"
          useAssigner="1"
          ref="role"
          @actionClicked="editRole"
        />
      </div>
      <div class="tab-pane fade" id="divisi" role="tabpanel" aria-labelledby="divisi-tab">
        <br>
        
        <DataTables
          url="profile/user_divisi"
          tclass="table table-bordered w-100 p-2" 
          :columns="divisiColumn"
          useNumber="1"
          useAssigner="1"
          ref="divisi"
          @actionClicked="editDivisi"
        />
      </div>
      <div class="tab-pane fade" id="jabatan" role="tabpanel" aria-labelledby="jabatan-tab">
        <br>
        
        <DataTables
          url="profile/user_jabatan"
          tclass="table table-bordered w-100 p-2" 
          :columns="jabatanColumn"
          useNumber="1"
          useAssigner="1"
          ref="jabatan"
          @actionClicked="editJabatan"
        />
      </div>
    </div>

    <!-- MODALS -->
    <div class="modal fade" id="createUser" ref="userModal" tabindex="-1" aria-labelledby="createUserLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createUserLabel"> Create User </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="form-group">
                <label for="code"> NIPP </label>
                <input type="text" class="form-control my-2" id="nipp" name="nipp" v-model="userdata.nipp">
              </div>
              <div class="form-group">
                <label for="name"> Username </label>
                <input type="text" class="form-control my-2" id="username" name="username" v-model="userdata.username">
              </div>
              <div class="form-group">
                <label for="name"> Name </label>
                <input type="text" class="form-control my-2" id="name" name="name" v-model="userdata.name">
              </div>
              <div class="form-group">
                <label for="name"> E-Mail </label>
                <input type="email" class="form-control my-2" id="email" name="email" v-model="userdata.email">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="create-user-button" @click="action == 'create' ? ioUser('create') : ioUser('update')"> Create </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="createGroup" ref="groupModal" tabindex="-1" aria-labelledby="createGroupLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createGroupLabel"> Create Group </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="form-group">
                <label for="name"> Name </label>
                <input type="text" class="form-control my-2" id="name" name="name" v-model="groupdata.name">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="create-group-button" @click="ioGroup()"> Create </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="changeDivisi" ref="divisiModal" tabindex="-1" aria-labelledby="changeDivisiLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changeDivisiLabel"> Update divisi </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="form-group">
                <label for="code"> NIPP </label>
                <input type="text" class="form-control my-2" id="nipp" name="nipp" v-model="userdataDivisi.nipp" disabled>
              </div>
              <div class="form-group">
                <label for="code"> Username </label>
                <input type="text" class="form-control my-2" id="username" name="username" v-model="userdataDivisi.username" disabled>
              </div>
              <div class="form-group">
                <label for="code"> Divisi </label>

                <select class="form-control my-2" id="divisi" name="divisi" v-model="userdataDivisi.nama_divisi">
                  <option disabled="true" selected> {{ userdataDivisi.nama_divisi }} </option>
                  <option v-for="data in dropdownDivisi" :value="data.code_divisi" :key="data.code_divisi">{{data.nama_divisi}}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="assign-divisi-button" @click="ioDivisi()"> Assign </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="changeRole" ref="roleModal" tabindex="-1" aria-labelledby="changeRoleLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changeRoleLabel"> Update Role </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="form-group">
                <label for="code"> NIPP </label>
                <input type="text" class="form-control my-2" id="nipp" name="nipp" v-model="userdataRole.nipp" disabled>
              </div>
              <div class="form-group">
                <label for="code"> Username </label>
                <input type="text" class="form-control my-2" id="username" name="username" v-model="userdataRole.username" disabled>
              </div>
              <div class="form-group">
                <label for="code"> Role </label>

                <select class="form-control my-2" id="divisi" name="divisi" v-model="userdataRole.role">
                  <option disabled="true" selected> {{ userdataRole.role }} </option>
                  <option v-for="data in dropdownRole" :value="data.role" :key="data.role">{{data.role}}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="assign-divisi-button" @click="ioRole()"> Assign </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="changeJabatan" ref="jabatanModal" tabindex="-1" aria-labelledby="changeJabatanLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changeJabatanLabel"> Update jabatan </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="form-group">
                <label for="code"> NIPP </label>
                <input type="text" class="form-control my-2" id="nipp" name="nipp" v-model="userdataJabatan.nipp" disabled>
              </div>
              <div class="form-group">
                <label for="code"> Username </label>
                <input type="text" class="form-control my-2" id="username" name="username" v-model="userdataJabatan.username" disabled>
              </div>
              <div class="form-group">
                <label for="code"> Jabatan </label>

                <select class="form-control my-2" id="jabatan" name="jabatan" v-model="userdataJabatan.nama_jabatan">
                  <option disabled="true" selected> {{ userdataJabatan.nama_jabatan }} </option>
                  <option v-for="data in dropdownJabatan" :value="data.code_jabatan" :key="data.code_jabatan"> {{ data.nama_jabatan }} </option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="assign-jabatan-button" @click="ioJabatan()"> Assign </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="groupAssignerModal" ref="groupAssignerModal" tabindex="-1" aria-labelledby="groupAssignerModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="groupAssignerModalLabel"> Assign Group </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <ul class="ws-container">
                  <li class="ws-item" v-for="(data, index) in groups.all_users" @click="assignGroup(data.id)" :key="index"> 
                    {{ data.name }} 
                  </li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul class="ws-container">
                  <li class="ws-item" v-for="(data, index) in groups.inside" @click="exitGroup(data.id)" :key="index"> 
                    {{ data.name }} 
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
          </div>
        </div>
      </div>
    </div>
  </div>  
</template>

<script>
import DataTables from '../../components/DataTables.vue'
import { Modal } from 'bootstrap'
import 'bootstrap/dist/js/bootstrap.js'
import axios from 'axios'
import { toJSON } from 'lodash-es'

export default {
  name: 'ManageUserView',
  components: {
    DataTables
  },
  data() {
    return {
      file: null,
      listUsers: {
        id: 'NIPP',
        username: 'Username',
        name: 'Name',
        email: 'E-Mail',
        created_at: 'Created'
      },
      listGroups: {
        id: 'ID',
        name: 'Name',
        count: 'Jumlah'
      },
      divisiColumn: {
        nipp: 'NIPP',
        username: 'Username',
        nama_divisi: 'Nama Divisi'
      },
      roleColumn: {
        nipp: 'NIPP',
        username: 'Username',
        role: 'Role'
      },
      jabatanColumn: {
        nipp: 'NIPP',
        username: 'Username',
        nama_divisi: 'Nama Jabatan'
      },
      userdata: {
        nipp: '',
        username: '',
        name: '',
        email: '',
      },
      groupdata: {
        name: ''
      },
      userdataDivisi: {
        nipp: '',
        username: '',
        nama_divisi: '',
        code_divisi: ''
      },
      userdataJabatan: {
        nipp: '',
        username: '',
        nama_jabatan: '',
        code_jabatan: ''
      },
      userdataRole: {
        nipp: '',
        username: '',
        role: ''
      },
      groups: {
        all_users: [],
        inside: null
      },
      ioUserModal: null,
      ioRoleModal: null,
      ioDivisiModal: null,
      ioJabatanModal: null,
      ioGroupModal: null,
      action: 'create',
      tmpCode: null,
      dropdownDivisi: [],
      dropdownJabatan: [],
      dropdownRole: [],
      selectedGroupID: null
    }
  },
  mounted() {
    this.ioUserModal = new Modal(this.$refs.userModal)
    this.ioDivisiModal = new Modal(this.$refs.divisiModal)
    this.ioJabatanModal = new Modal(this.$refs.jabatanModal)
    this.ioRoleModal = new Modal(this.$refs.roleModal)
    this.ioGroupModal = new Modal(this.$refs.groupModal)
    this.groupAssignerModal = new Modal(this.$refs.groupAssignerModal)
  },
  methods: {
    async ioUser(type) {
      let url = '/profile/' + type

      if (type == 'update') {
        this.userdata.old_nipp = this.tmpCode
        delete this.userdata.password
      } else if (type == 'create') {
        this.userdata.password = '12345678'
        delete this.userdata.old_nipp
      } else {
        console.error('Type not found')
      }

      await axios.post(url, this.userdata).then(() => {
        this.$refs.users.fetchData()

        this.$swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Data has been saved!'
        })
      }).catch(({response: {data}}) => {
        alert(data.message)
      })

      this.ioUserModal.hide()
    },
    async ioGroup() {
      await axios.post('/group/create', this.groupdata).then(() => {
        this.$refs.groups.fetchData()

        this.$swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Data has been saved!'
        })
      }).catch(({response: {data}}) => {
        alert(data.message)
      })

      this.$refs.groups.fetchData()
      this.ioGroupModal.hide()
    },
    async ioDivisi() {
      await axios.post('/divisi/user', this.userdataDivisi).then(() => {
        this.$refs.divisi.fetchData()

        this.$swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Data has been saved!'
        })
      }).catch(({response: {data}}) => {
        alert(data.message)
      })

      this.$refs.divisi.fetchData()
      this.ioDivisiModal.hide()
    },
    async ioRole() {
      await axios.post('/role/update', this.userdataRole).then(() => {
        this.$refs.role.fetchData()

        this.$swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Data has been saved!'
        })
      }).catch(({response: {data}}) => {
        alert(data.message)
      })

      this.$refs.role.fetchData()
      this.ioRoleModal.hide()
    },
    async ioJabatan() {
      await axios.post('/jabatan/user', this.userdataJabatan).then(() => {
        this.$refs.jabatan.fetchData()

        this.$swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Data has been saved!'
        })
      }).catch(({response: {data}}) => {
        alert(data.message)
      })

      this.ioJabatanModal.hide()
    },
    changeLabels(type, to) {
      if (type == 'create') {
        this.action = 'create'

        if (to == 'users') {
          document.getElementById('createUserLabel').innerHTML = 'Create User'
          document.getElementById('create-user-button').innerHTML = 'Create'

          for (let member in this.userdata) delete this.userdata[member]
        } else {
          console.error('Target is not found')
        }
      } else if (type == 'update') {
        this.action = 'update'
        
        if (to == 'users') {
          document.getElementById('createUserLabel').innerHTML = 'Update User'
          document.getElementById('create-user-button').innerHTML = 'Update'
        } else {
          console.error('Target is not found')
        }
      } else {
        console.error('Type is not found')
      }
    },
    editUser(data) {
      this.ioUserModal.show()
      this.changeLabels('update', 'users')
      
      let id = data.id
      let username = data.username
      let name = data.name
      let email = data.email

      this.tmpCode = data.id
      this.userdata.id = id
      this.userdata.nipp = id
      this.userdata.username = username
      this.userdata.name = name
      this.userdata.email = email
    },
    deleteUser(data) {
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
          axios.post('/profile/delete', { old_nipp: data.id }).then((resp) => {
            if (resp.data.data == 'SELF_DELETE') {
              this.$swal.fire(
                'Oops!',
                'You can\'t delete yourself',
                'error'
              )
            } else {
              this.$swal.fire(
                'Deleted!',
                'Data has been deleted.',
                'success'
              )
            }

            this.$refs.users.fetchData()  
          }).catch(({response: {data}}) => {
            alert(data.message)
          })
        }
      })
    },
    editDivisi(data) {
      this.ioDivisiModal.show()

      this.userdataDivisi.nipp = data.nipp
      this.userdataDivisi.username = data.username
      this.userdataDivisi.nama_divisi = data.nama_divisi

      axios.get('divisi/dropdown?nama_divisi=' + data.nama_divisi).then((resp) => {
        this.dropdownDivisi = resp.data.data
      })
    },
    editRole(data) {
      this.ioRoleModal.show()

      this.userdataRole.nipp = data.nipp
      this.userdataRole.username = data.username
      this.userdataRole.role = data.role

      axios.get('role/dropdown?existing=' + data.role).then((resp) => {
        this.dropdownRole = resp.data.data
      })
    },
    editJabatan(data) {
      this.ioJabatanModal.show()

      this.userdataJabatan.nipp = data.nipp
      this.userdataJabatan.username = data.username
      this.userdataJabatan.nama_jabatan = data.nama_jabatan

      axios.get('jabatan/dropdown?nama_jabatan=' + data.nama_jabatan).then((resp) => {
        this.dropdownJabatan = resp.data.data
      })
    },
    chooseFiles() {
      document.getElementById("fileUpload").click()
    },
    handleFileUpload(event) {
      this.file = event.target.files[0];

      this.$swal.fire({
        title: 'Are you sure?',
        text: "Make sure your data is correct!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Upload'
      }).then((result) => {
        if (result.isConfirmed) {
          let formData = new FormData()
          formData.append('file', this.file)

          axios.post('/user/upload', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }).then((resp) => {
            if (resp.data.code == 200) {
              this.$swal.fire(
                'Deleted!',
                'Data has been saved.',
                'success'
              )

              this.$refs.users.fetchData()
            }
          }).catch(() => {
            console.log('Error')
          })
        }
      })
    },
    showCreateGroupModal() {
      this.ioGroupModal.show()
    },
    showGroupAssigner(id) {
      axios.get('group/list-user-outside?gid=' + id.id).then((resp) => {
        const data = resp.data
        this.groups.all_users = data
      })

      axios.get('group/list-user-inside?gid=' + id.id).then((resp) => {
        const data = resp.data
        this.groups.inside = data
      })

      this.selectedGroupID = id
      this.groupAssignerModal.show()
    },
    assignGroup(id) {
      axios.post('group/assign', {
        gid: this.selectedGroupID.id,
        uid: id
      }).then((resp) => {
        const data = resp.data.original

        if (data.code == 200) {
          axios.get('group/list-user-outside?gid=' + this.selectedGroupID.id).then((resp) => {
            const data = resp.data
            this.groups.all_users = data
          })

          axios.get('group/list-user-inside?gid=' + this.selectedGroupID.id).then((resp) => {
            const data = resp.data
            this.groups.inside = data
          })
        }
      })

      this.$refs.groups.fetchData()
    },
    exitGroup(id) {
      axios.post('group/exit', {
        gid: this.selectedGroupID.id,
        uid: id
      }).then((resp) => {
        const data = resp.data.original

        if (data.code == 200) {
          axios.get('group/list-user-outside?gid=' + this.selectedGroupID.id).then((resp) => {
            const data = resp.data
            this.groups.all_users = data
          })

          axios.get('group/list-user-inside?gid=' + this.selectedGroupID.id).then((resp) => {
            const data = resp.data
            this.groups.inside = data
          })
        }
      })
      
      this.$refs.groups.fetchData()
    },
    showUserModal() {
      this.ioUserModal.show()

      this.changeLabels('create', 'users')
    }
  }
}
</script>

<style scoped>
  .ws-container {
    height: 256px;
    max-height: 256px;
    overflow-y: scroll;
    border: 1px solid rgb(118, 118, 118);
    border-radius: 2px;
    margin-left: 0;
  }

  .ws-container ul {
    margin-left: 0;
    padding-left: 0;
  }

  .ws-container li {
    width: 100%;
    list-style-type: none;
    margin-left: 0;
    padding: 8px;
  }

  li {
    margin-left:0;
  }

  .ws-container li:hover {
    background-color: #00000022;
  }

  ul { list-style-type: none; margin: 0; padding: 0; }
</style>