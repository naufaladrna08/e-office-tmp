<template>
  <div>
    <div class="container">
      <h1> Edit Application Parameter </h1>

      <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="divisi-tab" data-bs-toggle="tab" data-bs-target="#divisi" type="button" role="tab" aria-controls="divisi" aria-selected="true"> Manage Divisi </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="jabatan-tab" data-bs-toggle="tab" data-bs-target="#jabatan" type="button" role="tab" aria-controls="jabatan" aria-selected="false"> Manage Jabatan </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="parameter-tab" data-bs-toggle="tab" data-bs-target="#parameter" type="button" role="tab" aria-controls="parameter" aria-selected="false"> Edit Parameter </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="klasifikasi-masalah-tab" data-bs-toggle="tab" data-bs-target="#klasifikasi-masalah" type="button" role="tab" aria-controls="klasifikasi-masalah" aria-selected="false"> Klasifikasi Masalah </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="jenis-surat-tab" data-bs-toggle="tab" data-bs-target="#jenis-surat" type="button" role="tab" aria-controls="jenis-surat" aria-selected="false"> Jenis Surat </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="false"> All </button>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="divisi" role="tabpanel" aria-labelledby="divisi-tab">
          <div 
            class="btn btn-primary mr-2 my-4" 
            data-bs-toggle="modal" 
            data-bs-target="#createDivisi" 
            @click="this.changeLabels('create', 'divisi')"
          > 
            Create 
          </div>
          <div class="btn btn-primary mr-2 my-4"> Upload </div>
          <div class="btn btn-primary mr-2 my-4"> Download Template </div>

          <!-- Datatables -->
          <DataTables 
            tclass="table table-hover table-bordered w-100 p-2" 
            url="divisi/read"
            :columns="divisiFields"
            useNumber="1"
            useAction="1"
            ref="divisi"
            @actionClicked="editDivisi"
            @deleteClicked="deleteDivisi"
          />
        </div>
        <div class="tab-pane fade" id="jabatan" role="tabpanel" aria-labelledby="jabatan-tab">
          <div class="btn btn-primary mr-2 my-4" data-bs-toggle="modal" data-bs-target="#createJabatan" @click="this.changeLabels('create', 'jabatan')"> Create </div>
          <div class="btn btn-primary mr-2 my-4"> Upload </div>
          <div class="btn btn-primary mr-2 my-4"> Download Template </div>

          <!-- Datatables -->
          <DataTables 
            tclass="table table-hover table-bordered w-100 p-2" 
            url="jabatan/read"
            :columns="jabatanFields"
            useNumber="1"
            useAction="1"
            ref="jabatan"
            @actionClicked="editJabatan"
            @deleteClicked="deleteJabatan"
          />
        </div>
        <div class="tab-pane fade" id="parameter" rol="tabpanel" aria-labelledby="parameter-tab">
          <div class="row my-4">
            <div class="col-6">
              <div class="form-group mb-2">
                <label for="footer"> Page Footer </label>
              
                <div class="d-flex">
                  <input
                    v-model="pageFooter"
                    class="form-control mt-2"
                  />

                  <button type="submit" class="btn btn-primary mt-2" @click.prevent="saveParameter('page_footer', pageFooter)"> Save </button>
                </div>
              </div>
              <div class="form-group mb-2">
                <label for="footer"> Visi </label>
              
                <textarea
                  v-model="visi"
                  class="form-control mt-2"
                />

                <button type="submit" class="btn btn-primary mt-2" @click.prevent="saveParameter('visi', visi)"> Save </button>
                <button type="submit" class="btn btn-success mt-2 mx-2" @click.prevent="preview()"> Preview </button>
              </div>
              <div class="form-group mb-2">
                <label for="footer"> Misi </label>
              
                <textarea
                  v-model="misi"
                  class="form-control mt-2"
                />

                <button type="submit" class="btn btn-primary mt-2" @click.prevent="saveParameter('misi', misi)"> Save </button>
                <button type="submit" class="btn btn-success mt-2 mx-2" @click.prevent="preview()"> Preview </button>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="klasifikasi-masalah" role="tabpanel" aria-labelledby="klasifikasi-masalah-tab">
          <div class="btn btn-primary mr-2 my-4" data-bs-toggle="modal" data-bs-target="#createKlasifikasiMasalah" @click="this.changeLabels('create', 'klasifikasi-masalah')"> Create </div>
          <div class="btn btn-primary mr-2 my-4"> Upload </div>
          <div class="btn btn-primary mr-2 my-4"> Download Template </div>

          <!-- Datatables -->
          <DataTables 
            tclass="table table-hover table-bordered w-100 p-2" 
            url="klasifikasi-masalah/read"
            :columns="klasifikasiMasalahFields"
            useNumber="1"
            useAction="1"
            ref="klasifikasiMasalah"
            @actionClicked="editKlasifikasiMasalah"
            @deleteClicked="deleteKlasifikasiMasalah"
          />
        </div>
        <div class="tab-pane fade" id="jenis-surat" role="tabpanel" aria-labelledby="jenis-surat-tab">
          <div class="btn btn-primary mr-2 my-4" data-bs-toggle="modal" data-bs-target="#createJenisSurat" @click="this.changeLabels('create', 'jenis-surat')"> Create </div>
          <div class="btn btn-primary mr-2 my-4"> Upload </div>
          <div class="btn btn-primary mr-2 my-4"> Download Template </div>

          <!-- Datatables -->
          <DataTables 
            tclass="table table-hover table-bordered w-100 p-2" 
            url="jenis-surat/read"
            :columns="jenisSuratFields"
            useNumber="1"
            useAction="1"
            ref="jenisSurat"
            @actionClicked="editJenisSurat"
            @deleteClicked="deleteJenisSurat"
          />
        </div>
        <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">
          <div class="btn btn-primary mr-2 my-4" data-bs-toggle="modal" data-bs-target="#createJenisSurat" @click="this.changeLabels('create', 'parameter')"> Create </div>
          <div class="btn btn-primary mr-2 my-4"> Upload </div>
          <div class="btn btn-primary mr-2 my-4"> Download Template </div>

          <!-- Datatables -->
          <DataTables 
            tclass="table table-hover table-bordered w-100 p-2" 
            url="parameter/read"
            :columns="allParameterFields"
            useNumber="1"
            useAction="1"
            ref="allParameter"
            @actionClicked="editParameter"
            @deleteClicked="deleteParameter"
          />
        </div>
      </div>
    </div>

    <!-- MODALS -->
    <div class="modal fade" ref="divisiModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createDivisiLabel"> Create Divisi </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="form-group">
                <label for="code"> Kode Divisi </label>
                <input type="text" class="form-control my-2" maxlength="8" id="code" name="code" v-bind="divisiData.code_divisi" v-model="divisiData.code_divisi">
              </div>
              <div class="form-group">
                <label for="name"> Nama Divisi </label>
                <input type="text" class="form-control my-2" id="name" name="name" v-model="divisiData.nama_divisi">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="create-divisi-button" @click="action == 'create' ? ioDivisi('create') : ioDivisi('update')"> Create Divisi </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" ref="jabatanModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createJabatanLabel"> Create Jabatan </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="form-group">
                <label for="code"> Kode Jabatan </label>
                <input type="text" class="form-control my-2" maxlength="8" id="code_jabatan" name="code" v-model="jabatanData.code_jabatan">
              </div>
              <div class="form-group">
                <label for="name"> Nama Jabatan </label>
                <input type="text" class="form-control my-2" id="nama_jabatan" name="name" v-model="jabatanData.nama_jabatan">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="create-jabatan-button" @click="action == 'create' ? ioJabatan('create') : ioJabatan('update')"> Create Jabatan </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" ref="klasifikasiMasalahModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createKlasifikasiMasalahLabel"> Create Klasifikasi Masalah </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="form-group">
                <label for="code"> Kode </label>
                <input type="text" class="form-control my-2" placeholder="Generated by application." disabled>
              </div>
              <div class="form-group">
                <label for="name"> Klasifikasi Masalah </label>
                <input type="text" class="form-control my-2" id="nama-klasifikasi-masalah" name="nama-klasifikasi-masalah" v-model="klasifikasiMasalahData.name">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="create-klasifikasi-masalah-button" @click="action == 'create' ? ioKlasifikasiMasalah('create') : ioKlasifikasiMasalah('update')"> Create </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" ref="jenisSuratModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createJenisSuratLabel"> Create Jenis Surat </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="form-group">
                <label for="code"> Kode </label>
                <input type="text" class="form-control my-2" placeholder="Generated by application." disabled>
              </div>
              <div class="form-group">
                <label for="name"> Jenis Surat </label>
                <input type="text" class="form-control my-2" id="nama-jenis-surat" name="nama-jenis-surat" v-model="jenisSuratData.name">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="create-jenis-surat-button" @click="action == 'create' ? ioJenisSurat('create') : ioJenisSurat('update')"> Create </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" ref="allParameterModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createParameterLabel"> Create Parameter </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form">
              <div class="form-group">
                <label for="code"> Type </label>
                <input type="text" class="form-control my-2" id="type-parameter" name="type-parameter" v-model="parameterData.type">
              </div>
              <div class="form-group">
                <label for="code"> Kode </label>
                <input type="text" class="form-control my-2" id="kode-parameter" name="kode-parameter" v-model="parameterData.code">
              </div>
              <div class="form-group">
                <label for="name"> Nama </label>
                <input type="text" class="form-control my-2" id="nama-parameter" name="nama-parameter" v-model="parameterData.name">
              </div>
              <div class="form-group">
                <label for="name"> Deskripsi </label>
                <textarea type="text" class="form-control my-2" id="deskripsi-parameter" name="deskripsi-parameter" v-model="parameterData.description" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="create-parameter-button" @click="action == 'create' ? ioParameter('create') : ioParameter('update')"> Create </button>
          </div>
        </div>
      </div>
    </div>
    <!-- END OF MODALS -->
  </div>
</template>

<script>
import DataTables from '../../components/DataTables.vue'
import axios from 'axios'
import { Modal } from 'bootstrap'
import 'bootstrap/dist/js/bootstrap.js'

export default {
  name: 'ParameterView',
  components: {
    DataTables
  },
  data() {
    return {
      divisiFields: ['code_divisi', 'nama_divisi'],
      jabatanFields: ['code_jabatan', 'nama_jabatan'],
      allParameterFields: ['type', 'code', 'name', 'description'],
      klasifikasiMasalahFields: ['code', 'description'],
      jenisSuratFields: ['code', 'description'],
      divisiData: {
        code_divisi: null,
        nama_divisi: null,
      },
      jabatanData: {
        code_jabatan: null,
        nama_jabatan: null,
      },
      klasifikasiMasalahData: {
        old_code: null,
        name: null,
      },
      jenisSuratData: {
        old_code: null,
        name: null,
      },
      parameterData: {
        old_code: null,
        type: null,
        code: null,
        name: null,
        description: null,
      },
      divisiModal: null,
      jabatanModal: null,
      klasifikasiMasalahModal: null,
      jenisSuratModal: null,
      allParameterModal: null,
      action: null,
      pageFooter: null,
      visi: null,
      misi: null
    }
  },
  mounted() {
    this.divisiModal = new Modal(this.$refs.divisiModal)
    this.jabatanModal = new Modal(this.$refs.jabatanModal)
    this.klasifikasiMasalahModal = new Modal(this.$refs.klasifikasiMasalahModal)
    this.jenisSuratModal = new Modal(this.$refs.jenisSuratModal)
    this.allParameterModal = new Modal(this.$refs.allParameterModal)
  },
  methods: {
    async ioDivisi(type) {
      let url = '/divisi/' + type

      if (type == 'update') {
        this.divisiData.old_code_divisi = this.tmpCode
      } else if (type == 'create') {
        delete this.divisiData.old_code_divisi
      } else {
        console.error('Type not found')
      }

      await axios.post(url, this.divisiData).then(() => {
        this.$refs.divisi.fetchData()
      }).catch(({response: {data}}) => {
        alert(data.message)
      })

      this.divisiModal.hide()
    },
    async ioJabatan(type) {
      let url = '/jabatan/' + type

      if (type == 'update') {
        this.jabatanData.old_code_jabatan = this.tmpCode
      } else if (type == 'create') {
        delete this.jabatanData.old_code_jabatan
      } else {
        console.error('Type not found')
      }

      await axios.post(url, this.jabatanData).then(() => {
        this.$refs.jabatan.fetchData()
      }).catch(({response: {data}}) => {
        alert(data.message)
      })

      this.jabatanModal.hide()
    },
    async ioKlasifikasiMasalah(type) {
      let url = '/klasifikasi-masalah/' + type

      if (type == 'update') {
        this.klasifikasiMasalahData.old_code = this.tmpCode
      } else if (type == 'create') {
        delete this.klasifikasiMasalahData.old_code
      } else {
        console.error('Type not found')
      }

      await axios.post(url, this.klasifikasiMasalahData).then(() => {
        this.$refs.klasifikasiMasalah.fetchData()
      }).catch(({response: {data}}) => {
        alert(data.message)
      })

      this.klasifikasiMasalahModal.hide()
    },
    async ioJenisSurat(type) {
      let url = '/jenis-surat/' + type

      if (type == 'update') {
        this.jenisSuratData.old_code = this.tmpCode
      } else if (type == 'create') {
        delete this.jenisSuratData.old_code
      } else {
        console.error('Type not found')
      }

      await axios.post(url, this.jenisSuratData).then(() => {
        this.$refs.jenisSurat.fetchData()
      }).catch(({response: {data}}) => {
        alert(data.message)
      })

      this.jenisSuratModal.hide()
    },
    async ioParameter() {
      let url = '/parameter/store'

      await axios.post(url, this.parameterData).then(() => {
        this.$refs.allParameter.fetchData()
      }).catch(({response: {data}}) => {
        alert(data.message)
      })

      this.allParameterModal.hide()
    },
    changeLabels(type, to) {
      if (type == 'create') {
        this.action = 'create'

        if (to == 'divisi') {
          document.getElementById('createDivisiLabel').innerHTML = 'Create Divisi'
          document.getElementById('create-divisi-button').innerHTML = 'Create'

          for (let member in this.divisiData) delete this.divisiData[member]
          document.getElementById('code').value = ''
          document.getElementById('name').value = ''
          
          this.divisiModal.show()
        } else if (to == 'jabatan') {
          document.getElementById('createJabatanLabel').innerHTML = 'Create Jabatan'
          document.getElementById('create-jabatan-button').innerHTML = 'Create'

          for (let member in this.jabatanData) delete this.jabatanData[member]
          document.getElementById('code_jabatan').value = ''
          document.getElementById('nama_jabatan').value = ''

          this.jabatanModal.show()
        } else if (to == 'klasifikasi-masalah') {
          document.getElementById('createKlasifikasiMasalahLabel').innerHTML = 'Create Klasifikasi Masalah'
          document.getElementById('create-klasifikasi-masalah-button').innerHTML = 'Create'

          for (let member in this.klasifikasiMasalahData) delete this.klasifikasiMasalahData[member]
          document.getElementById('nama-klasifikasi-masalah').value = ''
          
          this.klasifikasiMasalahModal.show()
        } else if (to == 'jenis-surat') {
          document.getElementById('createJenisSuratLabel').innerHTML = 'Create Jenis Surat'
          document.getElementById('create-jenis-surat-button').innerHTML = 'Create'

          for (let member in this.jenisSuratData) delete this.jenisSuratData[member]
          document.getElementById('nama-klasifikasi-masalah').value = ''
          
          /* Show modal */
          this.jenisSuratModal.show()
        } else if (to == 'parameter') {
          document.getElementById('createParameterLabel').innerHTML = 'Create Parameter'
          document.getElementById('create-parameter-button').innerHTML = 'Create'

          for (let member in this.parameterData) delete this.parameterData[member]
          document.getElementById('type-parameter').value = ''
          document.getElementById('kode-parameter').value = ''
          document.getElementById('nama-parameter').value = ''
          document.getElementById('deskripsi-parameter').value = ''
          
          /* Show modal */
          this.allParameterModal.show()
        } else {
          console.error('Target is not found')
        }
      } else if (type == 'update') {
        this.action = 'update'
        
        if (to == 'divisi') {
          document.getElementById('createDivisiLabel').innerHTML = 'Update Divisi'
          document.getElementById('create-divisi-button').innerHTML = 'Update'
        } else if (to == 'jabatan') {
          document.getElementById('createJabatanLabel').innerHTML = 'Update Jabatan'
          document.getElementById('create-jabatan-button').innerHTML = 'Update'
        } else if (to == 'klasifikasi-masalah') {
          document.getElementById('createKlasifikasiMasalahLabel').innerHTML = 'Update Klasifikasi'
          document.getElementById('create-klasifikasi-masalah-button').innerHTML = 'Update'
        } else if (to == 'jenis-surat') {
          document.getElementById('createJenisSuratLabel').innerHTML = 'Update Jenis Surat'
          document.getElementById('create-jenis-surat-button').innerHTML = 'Update'
        } else if (to == 'parameter]') {
          document.getElementById('createParameterLabel').innerHTML = 'Update Parameter'
          document.getElementById('create-parameter-button').innerHTML = 'Update'
        } else {
          console.error('Target is not found')
        }
      } else {
        console.error('Type is not found')
      }
    },
    editDivisi(data) {
      this.divisiModal.show()
      this.changeLabels('update', 'divisi')
      
      let code = data.code_divisi
      let nama = data.nama_divisi

      this.tmpCode = data.code_divisi
      this.divisiData.code_divisi = code
      this.divisiData.nama_divisi = nama

      document.getElementById('code').value = code
      document.getElementById('name').value = nama
    },
    deleteDivisi(data) {
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
          const postData = {
            code_divisi: data.code_divisi
          }
          
          axios.post('/divisi/delete', postData).then(() => {
            this.$swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )

            this.$refs.divisi.fetchData()
          }).catch(({response: {data}}) => {
            alert(data.message)
          })
        }
      })
    },
    editJabatan(data) {
      this.jabatanModal.show()
      this.changeLabels('update', 'jabatan')
      
      let code = data.code_jabatan
      let nama = data.nama_jabatan

      this.tmpCode = data.code_jabatan
      this.jabatanData.code_jabatan = code
      this.jabatanData.nama_jabatan = nama

      document.getElementById('code_jabatan').value = code
      document.getElementById('nama_jabatan').value = nama
    },
    deleteJabatan(data) {
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
          const postData = {
            code_jabatan: data.code_jabatan
          }
          
          axios.post('/jabatan/delete', postData).then(() => {
            this.$swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )

            this.$refs.jabatan.fetchData()
          }).catch(({response: {data}}) => {
            alert(data.message)
          })
        }
      })
    },
    editKlasifikasiMasalah(data) {
      this.klasifikasiMasalahModal.show()
      this.changeLabels('update', 'klasifikasi-masalah')
      
      let name = data.name

      this.tmpCode = data.code
      this.klasifikasiMasalahData.name = name

      document.getElementById('nama-klasifikasi-masalah').value = name
    },
    deleteKlasifikasiMasalah(data) {
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
          const postData = {
            code: data.code
          }
          
          axios.post('/klasifikasi-masalah/delete', postData).then(() => {
            this.$swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )

            this.$refs.klasifikasiMasalah.fetchData()
          }).catch(({response: {data}}) => {
            alert(data.message)
          })
        }
      })
    },
    editJenisSurat(data) {
      this.jenisSuratModal.show()
      this.changeLabels('update', 'jenis-surat')
      
      let name = data.name

      this.tmpCode = data.code
      this.jenisSuratData.name = name

      document.getElementById('nama-jenis-surat').value = name
    },
    deleteJenisSurat(data) {
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
          const postData = {
            code: data.code
          }
          
          axios.post('/jenis-surat/delete', postData).then(() => {
            this.$swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )

            this.$refs.jenisSurat.fetchData()
          }).catch(({response: {data}}) => {
            alert(data.message)
          })
        }
      })
    },
    editParameter(data) {
      this.allParameterModal.show()
      this.changeLabels('update', 'parameter')
      
      let type = data.type
      let code = data.code
      let name = data.name
      let description = data.description

      this.tmpCode = data.code
      this.parameterData.type = type
      this.parameterData.code = code
      this.parameterData.name = name
      this.parameterData.description = description

      document.getElementById('type-parameter').value = type
      document.getElementById('kode-parameter').value = name
      document.getElementById('nama-parameter').value = code
      document.getElementById('deskripsi-parameter').value = description
    },
    deleteParameter(data) {
      console.log(data)

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
          const postData = {
            code: data.code,
            type: data.type
          }
          
          axios.post('/parameter/delete', postData).then(() => {
            this.$swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )

            this.$refs.allParameter.fetchData()
          }).catch(({response: {data}}) => {
            alert(data.message)
          })
        }
      })
    },
    saveParameter(type, description) {
      const data = {
        'type': type,
        'code': type,
        'description': description
      }
      
      axios.post('/parameter/store', data).then((resp) => {
        this.$swal.fire({
          icon: 'success',
          title: 'Success',
          text: 'Data has been saved!'
        })
      })
    },
    preview() {
      let url = new URL(location.href).href + '/preview_home'
      window.open(url, '_blank');
    }
  },
  created() {
    /* Get page footer */
    axios.get('/parameter/fetch?type=page_footer').then((resp) => {
      this.pageFooter = resp.data.data.description
    })

    /* Get visi */
    axios.get('/parameter/fetch?type=visi').then((resp) => {
      this.visi = resp.data.data.description
    })

    /* Get misi */
    axios.get('/parameter/fetch?type=misi').then((resp) => {
      this.misi = resp.data.data.description
    })
  }
}
</script>

<style scoped>
  .mr-2 { margin-right: .4em; }
</style>