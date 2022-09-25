<template>
  <div class="root">
    <div class="main">
      <div class="dashboard">
        <div class="container">
          <!-- <div class="row">
            <div class="col-md-3">
              <div class="card">
                <div class="card-body row">
                  <div class="col-6 text-center">
                    <i class="fa-solid fa-inbox"></i>
                    Kotak Masuk
                  </div>
                  <div class="col-6">

                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card">

              </div>
            </div>
            <div class="col-md-6">
              <div class="card">

              </div>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import store from '../store'
// import DataTables from '../components/DataTables.vue'

export default {
  name: 'DashboardView',
  data() {
    return {
      user: store.state.auth.user,
      arr: [
        'no_surat',
        'kepada',
        'perihal', 
        'tanggal',
        'jenis_surat',
        'status',
        'keterangan'
      ]
    }
  },
  components: {
    // DataTables
  }
};
</script>

<style scoped>
.sidebar {
  background-color: rgba(115, 24, 152, 1);
  position: fixed;
  height: 100vh !important;
  z-index: 9999 !important;
}

.img-circle {
  border-radius: 100%;
}

.main {
  display: flex;
  flex-wrap: nowrap;
  height: -webkit-fill-available;
}

.dashboard {
  width: 100%;
}

.bg-green {
  background-color: #528A8C;
}

.bg-lightblue {
  background: #F4F7FC;
}

.btn-white {
  color: #222;
  background: #fff;
  border: 2px solid white;
}

.btn-white:hover {
  color: #222;
  background: #fff;
  border: 2px solid white;
}

.btn-outline-white {
  color: #fff;
  border: 2px solid white;
}

.btn-outline-white:hover {
  color: #222;
  background: #fff;
  border: 2px solid white;
}

ul.list-group.list-group-striped li:nth-of-type(odd){
  background: white;
}
ul.list-group.list-group-striped li:nth-of-type(even){
  background: #ebebeb;
}
ul.list-group.list-group-hover li:hover{
  background: red;
}

.radius-16 {
  border-radius: 16px 16px 0 0;
}

.nvihre .btn {
  border-radius: 12px;
  margin-right: 4px;
}

#overview-table {
  overflow: auto;
  white-space: nowrap;
}

#inbox-table {
  overflow: auto;
  white-space: nowrap;
}
</style>