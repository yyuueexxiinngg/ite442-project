<template>
  <v-slide-y-transition mode="out-in">
    <v-container>
      <v-layout row wrap align-center justify-center>
        <v-layout row wrap justify-center>
          <v-flex xs12>
            <div class="mb-4"></div>
          </v-flex>

          <v-flex xs12>
            <template>
              <v-card class="elevation-5">
                <v-card-title>
                  Repair Form List
                  <v-spacer></v-spacer>
                  <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Search"
                    single-line
                    hide-details
                  ></v-text-field>
                  <v-progress-linear slot="progress" color="blue" indeterminate
                                     v-if="$store.state.isLoading"></v-progress-linear>
                </v-card-title>

                <v-data-table
                  :headers="headers"
                  :items="repairFormList"
                  :search="search"
                  :rows-per-page-items="[10,25,{'text':'$vuetify.dataIterator.rowsPerPageAll','value':-1}]"
                >
                  <template slot="items" slot-scope="props">
                    <tr @click="$refs.updateDialog.openUpdateDialog(props.item.repair_id)">
                      <td>{{ props.item.repair_id }}</td>
                      <td>{{ props.item.repair_form }}</td>
                      <td>{{ props.item.customername }}</td>
                      <td>{{ props.item.customertel }}</td>
                      <td>{{ props.item.cost }}</td>
                    </tr>
                  </template>
                  <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                  </v-alert>
                  <template slot="expand" slot-scope="props">
                    <v-card flat>
                      <v-card-text>{{props.item.repair_id}}</v-card-text>
                    </v-card>
                  </template>
                </v-data-table>
              </v-card>
            </template>
          </v-flex>
        </v-layout>

        <UpdateRepairComponent ref="updateDialog"/>

      </v-layout>


    </v-container>
  </v-slide-y-transition>
</template>

<script>
  import UpdateRepairComponent from './Update_Repair_Component'

  export default {
    name: 'update_repair_form',
    components: {
      UpdateRepairComponent
    },
    data: () => ({
      search: '',
      headers: [
        {text: 'Repair ID', align: 'left', value: 'repair_id'},
        {text: 'Repair Form', value: 'repair_form'},
        {text: 'Customer Name', value: 'customername'},
        {text: 'Customer Tel', value: 'customertel'},
        {text: 'Cost', value: 'cost'}
      ],
      repairFormList: []
    }),
    mounted () {
      this.init()
    },
    methods: {
      init () {
        var data = new URLSearchParams()
        data.append('request', 'updateInit')
        this.axios.post('data_manipulator.php', data).then(
          (res) => {
            var data = res.data
            this.repairFormList = data
          }
        ).catch((error) => {
          console.log(error)
        })
      }
    },
    watch: {}
  }
</script>

<style>
  .dialog.centered-dialog,
  .v-dialog.centered-dialog {
    background: #282c2dad;
    box-shadow: none;
    border-radius: 6px;
    width: auto;
    color: whitesmoke;
  }
</style>
