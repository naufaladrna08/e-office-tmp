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
        <button class="btn btn-primary mb-4"> <i class="fa fa-file"></i> New file </button>

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
    </div>
  </div>  
</template>

<script>
import axios from 'axios'
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
    }
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
    }
  }
}
</script>

<style>

</style>