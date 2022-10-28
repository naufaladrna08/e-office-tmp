<template>
  <div class="container">
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
            <li><a class="dropdown-item" href="#" @click="currentViewOption = 'list'"> <i class="fa fa-list mr-4"></i> List </a></li>
            <li><a class="dropdown-item" href="#" @click="currentViewOption = 'grid'"> <i class="fa fa-th mr-4"></i> Grid </a></li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <div class="nav-link px-1 dropdown">
          <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-sort"></i> Sort By
          </button>
        </div>
      </li>
    </ul>

    <div :class="currentViewOption === 'grid' ? viewOption[0]['container'] : viewOption[1]['container']" v-if="files">
      <div class="flex-item file" v-for="file in files" v-bind:key="file.id" @click="onDownloadFile(file)" @contextmenu.prevent="$refs.menu.open($event, 'Payload')">
        <div class="file-icon-bg"> </div>
        <div class="file-icon"> 
          <div class="text-center">
            <i class="fa fa-file"></i>
            <div> {{ file.extension }} </div>
          </div>
        </div>
        <p class="file-name text-center"> {{ file.name }} </p>
      </div>
      <div class="nav-item">
        <div style="width: 200px;" class="nav-link px-1 dropdown">
          <ContextMenu ref="menu">
            <template v-slot="{ contextData }">
              <ContextMenuItem @click="false">
                Action 1 {{ contextData }}
              </ContextMenuItem>
              <ContextMenuItem @click="false">
                Action 2 {{ contextData }}
              </ContextMenuItem>
            </template>
          </ContextMenu>
        </div>
      </div>
    </div>
    <div class="row" v-else>
      <div class="col text-center">
        <p class="lead"> (File Archive Empty) </p>
      </div>
    </div>
  </div>  
</template>

<script>
import axios from 'axios'
import { Swal } from 'sweetalert2/dist/sweetalert2'
import ContextMenu from '../../components/ContextMenu'
import ContextMenuItem from '../../components/ContextMenuItem'

export default {
  name: 'PersonalView',
  components: {
    ContextMenu,
    ContextMenuItem
  },
  data() {
    return {
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
    },
    contextMenuHandler() {
      
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