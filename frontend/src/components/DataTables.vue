<template>
  <div id="datatable">
    <div class="row mb-3">
      <div class="col-md-3">
        <div class="input-group">
          <input 
            v-model="search"
            class="form-control"
            placeholder="Search..."
            type="text"
          />
          
          <div class="input-group-append">
            <button 
              class="btn btn-primary"
              type="button"
              @click.prevent="handleSearch"
            >
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1"> Show </span>
          </div>

          <select
            class="form-control"
            v-model="perPage"
            @change="handlePerPage"
            id="pageOptions"
          >
            <option
              v-for="page in pageOptions"
              :key="page"
              :value="page"
            >
              {{ page }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <table v-bind:class="tclass">
      <thead>
        <tr>
          <template v-if="number == 1">
            <th width="4%"> # </th> 
          </template>
          <template v-if="action == 1">
            <th width="7%"> Action </th> 
          </template>
          <th v-for="column in columnList" :key="column" @click="sortByColumn(column)"> 
            {{ column.toUpperCase() }} 
            
            <span v-if="column === sortField">
              <i v-if="sortOrder === 'asc'" class="fa fa-arrow-up"></i> 
              <i v-else class="fa fa-arrow-down"></i> 
            </span>
          </th>
          <template v-if="assigner == 1">
            <th> Action </th> 
          </template>
          <template v-if="openButton == 1">
            <th> Action </th> 
          </template>
        </tr>
      </thead>
      <tbody>
        <tr v-if="tableData.length == 0">
          <td colspan="100%" class="text-center m-4 w-100">
            No records found
          </td>
        </tr>
        <tr v-for="(data, index) in tableData" :key="data.data">
          <template v-if="number == 1">
            <td> {{ index + 1 }} </td>
          </template>
          <template v-if="action == 1">
            <a @click.prevent="handleActionClicked(data)" class="btn btn-primary mx-2 my-2"> 
              <i class="fa fa-eye"></i> 
            </a>
            <a @click.prevent="handleDeleteClicked(data)" class="btn btn-danger"> 
              <i class="fa fa-trash"></i> 
            </a>
          </template>
          <td v-for="col in showData[index]" :key="col"> 
            {{ col }}
          </td>
          <template v-if="assigner == 1">
            <a @click.prevent="handleActionClicked(data)" class="btn btn-primary mx-2 my-2"> 
              <i class="fa fa-tasks"></i> Assign / Change
            </a>
          </template>
          <template v-if="openButton == 1">
            <a @click.prevent="handleOpenButton(data)" class="btn btn-primary mx-2 my-2"> 
              <i class="fa fa-tasks"></i> Open
            </a>

            <template v-if="data.archived === false">
              <a @click.prevent="handleArchiveButton(data)" class="btn btn-primary mx-2 my-2"> 
                <i class="fa fa-archive"></i> Archive
              </a>
            </template>
            <template v-else>
              <a @click.prevent="handleUnarchiveButton(data)" class="btn btn-danger mx-2 my-2"> 
                <i class="fa fa-archive"></i> Unarchive
              </a>
            </template>
          </template>
        </tr>
      </tbody>
    </table>

    <PaginatorTable 
      v-if="tableData.length > 0"
      :pagination="pagination"
      :total="tableData.length"
      @pageChanged="handlePageChange"
    /> 
  </div>
</template>

<script>
import axios from 'axios'
import PaginatorTable from './PaginatorTable.vue'

export default {
  name: 'DatatablesComponent',
  components: {
    PaginatorTable
  },
  props: {
    columns: { type: Array, required: true },
    tclass: { type: Number, required: true },
    url: { type: String, required: true },
    useNumber: { type: Boolean, required: false },
    useAction: { type: Number, required: false },
    useAssigner: { type: Number, required: false },
    useOpenButton: { type: Number, required: false },
  },
  data() {
    return {
      tableData: [],
      showData: [],
      sortField: this.columns[1],
      sortOrder: "asc",
      search: null,
      pageOptions: [5, 10, 20, 50],
      perPage: 5,
      pagination: { to: 1, from: 1  },
      page: 1,
      total: 1,
      columnList: this.columns,
      number: this.useNumber,
      action: this.useAction,
      assigner: this.useAssigner,
      openButton: this.useOpenButton,
    }
  },
  created() {
    this.fetchData()
  },
  methods: {
    async fetchData() {
      try {
        const params = {
          field: this.sortField,
          order: this.sortOrder,
          search: this.search,
          per_page: this.perPage,
          page: this.page
        }

        const {data} = await axios.get(this.url, {params})
        this.tableData = data.data 
        this.pagination = data.meta
        const newData = structuredClone(this.tableData)

        newData.forEach(each => {
          delete each.archived
        })

        this.showData = newData

      } catch (e) {
        console.log(e)
      }
    },
    sortByColumn(col) {
      if (col === this.sortField) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc'
      } else {
        this.sortField = col
      }

      this.fetchData()  
    },
    handleSearch() {
      this.sortField = this.columns[0]
      this.sortOrder = "asc"
      this.page = 1
      this.fetchData()
    },
    handlePerPage(e) {
      this.perPage = e.target.value
      this.fetchData()
    },
    handlePageChange(number) {
      this.page = number
      this.fetchData()      
    },
    handleActionClicked(data) {
      this.$emit("actionClicked", data)
    },
    handleDeleteClicked(data) {
      this.$emit("deleteClicked", data)
    },
    handleOpenButton(data) {
      this.$emit("openClicked", data)
    },
    handleArchiveButton(data) {
      this.$emit("actionArchive", data)
    },
    handleUnarchiveButton(data) {
      this.$emit("actionUnarchive", data)
    }
  }
}
</script>

<style scoped>
thead tr th {
  cursor: pointer;
}

tr {
  min-height: 60px;
  vertical-align: middle; 
}

table .btn {
  border-radius: .5rem;
}

table tbody tr a {
  color: white;
  transition: 0.5s
}
table tbody tr:hover a {
  color: white;
}

table .btn-primary {
  border-color: #0d6efd;
  background-color: #0d6efd;
}

table .btn-primary:hover {
  background-color: #198cf0;
}


table .btn-danger {
  border-color: #dc3545;
  background-color: #dc3545;
}

table .btn-danger:hover {
  background-color: #eb4757;
}

.btn {
  border-radius: 0 0.25rem 0.25rem 0;
}
</style>