<template>
  <div class="container">
    <div id="header" class="row">
      <div class="col-6 lead">
        <p class="lead"> <b> Kepala Surat </b> </p>
        
        <div class="row mb-2">
          <div class="col-4">
            Jenis / No / Tgl
          </div>
          <div class="col-8">
            <b> : {{ data.type }} / {{ data.mail_number }} / {{ data.updated_at }} </b>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-4">
            Perihal
          </div>
          <div class="col-8">
            <b> : {{ data.subject }} </b>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-2">
            Lampiran
          </div>
          <div class="col-2">
            <b> : {{ data.t_lampiran }} </b>
          </div>
          <div class="col-2">
            Prioritas
          </div>
          <div class="col-2">
            <b> : {{ data.prioritas }} </b>
          </div>
          <div class="col-2">
            Klasifikasi
          </div>
          <div class="col-2">
            <b> : {{ data.klasifikasi }} </b>
          </div>
        </div>
      </div>
      <div class="col-6 lead">
        <div class="row mb-2">
          <div class="col-md-12">
            <p class="lead"> <b> Kepada : </b> </p>
    
            <p class="lead"> {{ data.t_to }} </p>
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-md-12">
            <p class="lead"> <b> Tembusan : </b> </p>
    
            <p class="lead"> {{ data.t_tembusan }} </p>
          </div>
        </div>
      </div>
    </div>

    <div id="body" class="card"> 
      <div class="card-body" v-html="data.description">
        
      </div>
    </div>

    <div id="log" class="card mt-4" width="100%">
      <div class="card-body p-4">
        <ul v-for="(item, id) in logs" v-bind:key="id">
          <li> {{ item.description }} </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<style>
  .img-fw {
    width: 100%;
    border-radius: 8px;
  }

  #log {
    color: #fff;
    background: #2d3436
  }
</style>

<script>
import axios from 'axios'

export default { 
  name: 'ReadView',
  data() {
    return {
      id: this.$route.params.id,
      data: null,
      userdata: null,
      logs: null
    }
  },
  created() {
    axios.get('/mail/read/' + this.id).then((resp) => {
      this.data = resp.data.data
      
      axios.get('/mail/get_log?id=' + this.data.id).then((resp) => {
        this.logs = resp.data
      })   
    })

    axios.get('/mail/receiver-cc?item=' + this.id).then((resp) => {
      this.userdata = resp.data.data
    }) 
  }
}
</script>