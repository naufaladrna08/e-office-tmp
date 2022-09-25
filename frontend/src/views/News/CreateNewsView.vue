<template>
  <div>
    <div class="container">
      <div class="row mt-4">
        <div class="col-md-6">
          <div class="form-group my-2">
            <input 
              type="text"
              v-model="title"
              placeholder="Title"
              class="form-control"
            >
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group my-2">
            <input 
              class="form-control" 
              type="file"
              :name="cover"
              @change="onFileChanged($event.target.files); fileCount = $event.target.files.length"
            >
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group my-2">
            <input type="file" name="aimage" id="aimage" style="position:absolute; left: -9999px;" @change="applyImage(event)" />
            <button class="btn btn-primary mb-4 btn-sm" @click="addImage()"> <i class="fa fa-image"> </i></button>
            <button class="btn btn-primary mb-4 mx-2 btn-sm"> <i class="fa fa-play"></i> </button>
            <ckeditor 
              :editor="editor" 
              v-model="editorData" 
              class="form-control"
              @ready="onReady"
            />
          </div>
        </div>
      </div>

      <button class="btn btn-primary" @click="onSubmit"> Post </button>
    </div>
  </div>
</template>

<script>
import Editor from '@ckeditor/ckeditor5-build-decoupled-document'
import axios from 'axios'

export default {
  name: 'CreateNewsView',
  data() {
    return {
      editor: Editor,
      editorData: '',
      title: null,
      cover: 'cover',
      coverData: null
    }
  },
  methods: {
    onReady(editor)  {
      editor.ui.getEditableElement().parentElement.insertBefore(
        editor.ui.view.toolbar.element,
        editor.ui.getEditableElement()
      );
    },
    onFileChanged(fileList) {
      this.coverData = fileList[0]
    },
    onSubmit() {
      const data = new FormData()
      data.append('cover', this.coverData, this.coverData.name)
      data.append('title', this.title)
      data.append('description', this.editorData)

      axios.post('/news/create', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then((resp) => {
        if (resp.data.code == 200) {
          this.$swal.fire({
            icon: 'success',
            title: 'All good.',
            text: 'Data has been deleted!'
          })

          this.$router.push('/news/' + resp.data.data.id)
        } else {
          this.$swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong! Please try again later.'
          })
        }
      })
    },
    addImage() {
      const el = document.getElementById('aimage')
      el.click()
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
  }
}
</script>