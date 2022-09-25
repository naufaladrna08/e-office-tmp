<template>
  <div class="container">
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