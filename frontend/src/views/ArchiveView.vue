<template>
  <div class="container">
    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true"> Mail Archive </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"> File Archive </button>
      </li>
    </ul>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <DataTables
          url="archive/read-all"
          tclass="table table-bordered w-100 p-2" 
          :columns="inboxColumn"
          useNumber="1"
          useAssigner="0"
          useOpenButton="1"
          @openClicked="openMail"
          @actionUnarchive="unarchiveMail"
          ref="inbox"
        />
      </div>
      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <ul class="nav mb-4">
          <li class="nav-item">
            <div class="nav-link px-1">
              <button class="btn btn-primary" onclick="document.getElementById('fileInput').click()"> <i class="fa fa-file"></i> New file </button>
              <input type="file" id="fileInput" @change="uploadFile" name="file" style="visibility: hidden; display: none;">
            </div>
          </li>
          <li class="nav-item">
            <div class="nav-link px-1 dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-th"></i> View
              </button>
              <ul class="dropdown-menu">
                <!-- <li><a class="dropdown-item" href="#" @click="currentViewOption = 'list'"> <i class="fa fa-list mr-4"></i> List </a></li> -->
                <li><a class="dropdown-item" href="#" @click="currentViewOption = 'grid'"> <i class="fa fa-th mr-4"></i> Grid </a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <div class="nav-link px-1 dropdown">
              <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-sort"></i> Sort By
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"> <i class="fa fa-sort-alpha-asc mr-4"></i> Name Ascending (A-z) </a></li>
                <li><a class="dropdown-item" href="#"> <i class="fa fa-sort-alpha-desc mr-4"></i> Name Descending (z-A) </a></li>
                <li><a class="dropdown-item" href="#"> <i class="fa fa-table mr-4"></i> Date and Time </a></li>
              </ul>
            </div>
          </li>
        </ul>

        <div :class="currentViewOption === 'grid' ? viewOption[0]['container'] : viewOption[1]['container']" v-if="files">
          <div class="flex-item file" v-for="file in files" v-bind:key="file.id" @click="onDownloadFile(file)">
            <div class="file-icon-bg"> </div>
            <div class="file-icon"> 
              <div class="text-center">
                <i class="fa fa-file"></i>
                <div> {{ file.extension }} </div>
              </div>
            </div>
            <p class="file-name text-center"> {{ file.name }} </p>
          </div>
        </div>
        <div class="row" v-else>
          <div class="col text-center">
            <p class="lead"> (File Archive Empty) </p>
          </div>
        </div>
      </div>
    </div>
  </div>  
</template>

<script>
import axios from 'axios'
import { Swal } from 'sweetalert2/dist/sweetalert2'
import DataTables from '../components/DataTables.vue'

export default {
  name: 'InboxView',
  components: {
    DataTables
  },
  data() {
    return {
      inboxColumn: {
        no_surat: 'No. Surat',
        perihal: 'Perihal',
        jenis_surat: 'Jenis',
        status: 'Status',
        tanggal: 'Tanggal'
      },
      files: [],
      currentViewOption: 'grid',
      viewOption: [
        {
          container: 'container-flex',
          item: 'flex-item'
        },
        {
          container: 'container-list',
          item: 'list-item'
        }
      ]
    }
  },
  mounted() {
    this.getFiles()
  },
  methods: { 
    openMail(data) {
      this.$router.push('/mail/read/' + data.no_surat)
    },
    async unarchiveMail(data) {
      await axios.post('/archive/delete', data)
      .then((resp) => {
        console.log(resp)
        if (resp.data.code == 200) {
          this.$swal.fire({
            icon: 'success',
            title: 'All good.',
            text: 'Data has been deleted!'
          })

          this.$refs.inbox.fetchData()
        } else {
          this.$swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong! Please try again later.'
          })
        }
      })
    },
    async getFiles() {
      await axios.get('archive/files')
      .then((resp) => {
        
        if (resp.data.code == 200) {
          this.files = resp.data.data
        } else {
          this.files = null
        }
      })
    },
    onDownloadFile(file) {
      window.open(file.path)
    },
    async uploadFile(e) {
      const config = {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }

      let formData = new FormData()
      formData.append('file', e.target.files[0])

      this.$swal.showLoading()

      await axios.post('archive/upload', formData, config)
        .then((resp) => {
          if (resp.data.code == 200) {
            this.$swal.fire({
              icon: 'success',
              title: 'All good.',
              text: 'Data has been uploaded!'
            })

            this.getFiles()
          } else {
            this.$swal.fire({
              icon: 'error',
              title: 'Internal Server Error.',
              text: resp.data.message
            })
          }
        })
        .catch(function (err) {
          this.$swal.fire({
            icon: 'error',
            title: 'Internal Server Error.',
            text: err
          })
        });
    }
  }
}
</script>

<style>
  .file {
    width: 10%;
    min-height: 80%;
    margin: 4px;
  }

  .file-icon-bg {
    width: 100%;
    height: 16px;
    background-color: rgb(85, 73, 190);
    margin-top: -10px;
    border-radius: 10px 10px 0 0;
  }

  .file .file-icon {
    text-transform: uppercase;
    padding: 1.8em 1.2em;
    font-size: 1vw;
    font-weight: bold;
    border-radius: 0 0 10px 10px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #ffffff;
    background-color: #5e5e5e;
  }

  .file .file-icon i {
    font-size: 1.9vw;
  }

  .file .file-icon:hover {
    cursor: pointer;
    background-color: #6c6c6c;
  }

  .file-icon-bg:hover {
    background-color: rgb(103, 91, 210);
  }

  .file .file-name {
    width: 100%;
    font-size: 12pt;
    margin-top: 8px;
    margin-bottom: 8px;
    word-break: break-all;
  }

  .row {
    height: 100%; /* expand height to allow column height works. */
  }

  .row-sm-2 {
    align-self: stretch;
    height: 200px;
  }

  .container-flex {
    display: flex;
    flex-wrap: wrap;
  }

  .container-list {
    display: inline-block;
  }
</style>