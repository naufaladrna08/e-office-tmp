<template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <p class="lead"> * Kepala Surat </p>

          <div class="form-group my-2">
            <label class="fw-bolder mb-2"> Jenis / No / Tanggal </label>
            
            <div class="row mt-2">
              <div class="col">
                <SelectComponent 
                  v-model="formData.jenisSurat" 
                  :options="jenisSuratOptions"
                />
              </div>
              <div class="col">
                <input type="text" placeholder="Mail Number" class="form-control" v-model="formData.mailNumber">
              </div>
              <div class="col">
                <input type="text" placeholder="Auto" class="form-control" disabled>
              </div>
            </div>
          </div>
          <div class="form-group my-2">
            <label class="fw-bolder mb-2"> Perihal </label>
            <input type="text" class="form-control" name="perihal" id="perihal" v-model="formData.subject">
          </div>
          <div class="form-group my-2">
            <label class="fw-bolder mb-2"> Lampiran / Prioritas / Klasifikasi </label>
            
            <div class="row mt-2">
              <div class="col">
                <input type="text" v-model="formData.t_lampiran" placeholder="" class="form-control" id="lampiran" name="lampiran">
              </div>
              <div class="col">
                <SelectComponent 
                  v-model="formData.prioritas" 
                  :options="prioritasOptions"
                />
              </div>
              <div class="col">
                <SelectComponent 
                  v-model="formData.klasifikasi" 
                  :options="klasifikasiOptions"
                />
              </div>
            </div>
          </div>

          <p class="lead mt-4"> * Pengirim </p>

          <div class="form-group my-2">
            <label class="fw-bolder mb-2"> Jabatan </label>
            <input type="text" :value="userdata.nama_jabatan" class="form-control" disabled>
          </div>
          <div class="form-group my-2">
            <label class="fw-bolder mb-2"> NIPP / Nama </label>

            <div class="row mt-2">
              <div class="col">
                <input type="text" :value="userdata.uid" class="form-control" disabled>
              </div>
              <div class="col">
                <input type="text" :value="userdata.name" class="form-control" disabled>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <p class="lead"> * Penerima </p>

          <div class="form-group my-2">
            <label for="t_to" class="fw-bolder mb-2"> Kepada </label>
            <textarea name="t_to" id="t_to" v-model="formData.t_to" cols="30" rows="4" class="form-control"></textarea>
          </div>
          <div class="form-group my-2">
            <label for="t_tembusan" class="fw-bolder mb-2"> Tembusan </label>
            <textarea name="t_tembusan" id="t_tembusan" v-model="formData.t_tembusan" cols="30" rows="4" class="form-control"></textarea>
          </div>
          <div class="form-group my-2">
            <label class="fw-bolder mb-2"> Tembusan (Jabatan) </label>
            
            <div class="users" v-for="(group, counter) in formData.group" v-bind:key="counter">
              <SelectComponent 
                v-model="group.gid" 
                :options="groupsOptions"
                class="mb-2"
              />
            </div>

            <div class="btn btn-primary mt-2" @click="addGroup"> + </div>
          </div>

          <p class="lead mt-4"> * Lampiran </p>

          <div class="attachments mb-2" v-for="(attachment, counter) in attachments" v-bind:key="counter">
              <input type="file" name="data[]" id="data" @change="onChangedFiles($event, counter)" class="form-control">
            </div>

            <div class="btn btn-primary mt-2" @click="addAttachment"> + </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-12">
          <div class="form-group my-2">
            <button class="btn btn-primary ml-2" @click="getTemplate(1)"> Template 1 </button>
            <button class="btn btn-primary mx-2" @click="getTemplate(2)"> Template 2 </button>
            <button class="btn btn-primary mx-2" @click="getTemplate(3)"> Template 3 </button>
            <button class="btn btn-primary mx-2" @click="getTemplate(4)"> Template 4 </button>
            <button class="btn btn-primary mx-2" @click="getTemplate(5)"> Template 5 </button>
            <button class="btn btn-primary mx-2" @click="getTemplate(6)"> Template 6 </button>
            <button class="btn btn-primary mx-2" @click="getTemplate(7)"> Template 7 </button>
            <button class="btn btn-primary mx-2" @click="addImage()"> <i class="fa fa-image"> </i></button>
            <input type="file" name="aimage" id="aimage" style="position:absolute; left: -9999px;" @change="applyImage(event)" />
          </div>
          <form @submit.prevent="send">
            <div class="form-group my-2">
              <ckeditor 
                :editor="editor" 
                v-model="editorData" 
                class="form-control"
                @ready="onReady"
              />
            </div> 
            
            <button class="btn btn-primary float-right mt-4"> Kirim </button>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" ref="confirmTemplate">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createParameterLabel"> Use this template </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="lead"> Are you sure to use this template? </p>

            <img v-bind:src="templateImageSource" class="img-thumbnail">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Close </button>
            <button type="button" class="btn btn-primary" id="create-parameter-button" @click="applyTemplate"> Use </button>
          </div>
        </div>
      </div>
    </div>
    test
  </div>
</template>

<script>
import Editor from '@ckeditor/ckeditor5-build-decoupled-document'
import axios from 'axios'
import SelectComponent from '../../components/SelectComponent.vue'
import {Modal} from 'bootstrap'

export default {
  name: 'NewMessageView',
  components: {
    SelectComponent
  },
  data() {
    return {
      editor: Editor,
      editorData: '',
      sendTo: null,
      templateImageSource: null,
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
        "cover_path": null
      },
      attachments: [],
      formData: {
        group: [ { gid: 0 } ],
        mailNumber: null,
        subject: null,
        jenisSurat: null,
        klasifikasiMasalah: null,
        prioritas: null,
        klasifikasi: null,
        t_to: null,
        t_tembusan: null,
        t_lampiran: null
      },
      jenisSuratOptions: null,
      prioritasOptions: null,
      klasifikasiOptions: null,
      groupsOptions: null,
      confirmTemplate: null,
      templateChoosed: null,
      data: new FormData
    };
  },
  methods: {
    onReady(editor)  {
      editor.ui.getEditableElement().parentElement.insertBefore(
        editor.ui.view.toolbar.element,
        editor.ui.getEditableElement()
      );
    },
    send() {
      this.data.append('description', this.editorData)

      for (let key in this.formData) {
        if (key == 'group') {
          for (let i = 0; i < this.formData[key].length; i++) {
            for (let each in this.formData[key][i]) {
              this.data.append('options['+ key +']['+ i +']'+'['+each+']', this.formData[key][i][each])
            }
          }
        } else {
          this.data.append('options['+ key +']', this.formData[key])
        }
      }

      if (this.formData.mailNumber == null || this.formData.subject == null ||
          this.formData.jenisSurat == null || this.formData.prioritas == null) {
        this.$swal.fire(
          'Error!',
          'Mohon untuk mengisi semua data pada form yang dibutuhkan.',
          'error'
        )

        return
      }

      axios.get('/csrf-cookie').then(() => {
        axios.post('/mail/create', this.data, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then((resp) => {
          if (resp.data.code == 200) {
            this.$swal.fire(
              'Success!',
              'Surat terikim.',
              'success'
            )

            this.$router.push('/dashboard')
          } else {
            this.message = "Internal Server Error"
          }
        }) 
      })
    },
    selectJenisSurat(value) {
      console.log(value)
    },
    getTemplate(number) {
      this.confirmTemplate.show()

      axios.get('/mail/template?id=' + number)
      .then((resp) => {
        this.templateImageSource = resp.data.image
        this.templateChoosed = number 
      })
    }, 
    applyTemplate() {
      axios.get('/mail/template?id=' + this.templateChoosed)
      .then((resp) => {
        this.editorData = resp.data.data
      })

      this.confirmTemplate.hide()
    },
    addReceiver() {
      this.formData.send_to.push({
        uid: 1
      })
    },
    addGroup() {
      this.formData.group.push({
        gid: 1
      })
    },
    addImage() {
      const el = document.getElementById('aimage')
      el.click()
    },
    addAttachment() {
      this.attachments.push([])
    },
    onChangedFiles(event, i) {
      const file = event.target.files[0]
      this.data.append('attachments[]', file)
    },
    applyImage(event) {
      event = event || window.event;
      const data = event.target.files
      const formData = new FormData()
      formData.append('file', data[0])

      this.$swal.showLoading()
    
      axios.post('photo/store', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then((resp) => {
        this.editorData += '<img class="img-fw" src="' + resp.data.data.url + '"/>'
        this.$swal.close()
      })
    }
  },
  created() {
    axios.get('/profile/userdata').then((resp) => {
      this.userdata = resp.data.data
    })

    /* Get jenis surat */
    axios.get('/jenis-surat/dropdown').then((resp) => {
      this.jenisSuratOptions = resp.data
    })

    /* Get jenis surat */
    axios.get('/klasifikasi-masalah/dropdown').then((resp) => {
      this.klasifikasiMasalahOptions = resp.data
    })

    /* Get prioritas */
    axios.get('/parameter/dropdown?type=prioritas').then((resp) => {
      this.prioritasOptions = resp.data
    })

    /* Get klasifikasi */
    axios.get('/parameter/dropdown?type=klasifikasi').then((resp) => {
      this.klasifikasiOptions = resp.data
    })

    /* Get users */
    axios.get('/group/dropdown').then((resp) => {
      this.groupsOptions = resp.data
    })
  },
  mounted() {
    this.confirmTemplate = new Modal(this.$refs.confirmTemplate)
  }
}
</script>

<style>
.float-right {
  float: right;
}

.ck-content { 
  height: 500px; 
  border: 1px solid #ABABAB;
}

.select2 {
  height: 38px;
}
</style>