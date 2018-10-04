<template>
  <v-slide-y-transition mode="out-in">
    <v-container>
      <UpdateRepairComponent ref="updateDialog"/>
      <v-layout row wrap align-center justify-center>
        <v-layout row wrap justify-center>
          <v-flex xs12>
            <div class="mb-4"></div>
          </v-flex>

          <v-flex xs12>
            <template>
              <v-card class="elevation-5">
                <v-card-title>
                  In Progress List
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
                  :items="inProgressList"
                  :search="search"
                  :rows-per-page-items="[10,25,{'text':'$vuetify.dataIterator.rowsPerPageAll','value':-1}]"
                >
                  <template slot="items" slot-scope="props">
                    <tr @click="$refs.updateDialog.openUpdateDialog(props.item.repair_id)">
                      <td>{{ props.item.repair_id }}</td>
                      <td>{{ props.item.prod_code }}</td>
                      <td>{{ props.item.warranty_type }}</td>
                      <td>{{ props.item.repair_loc }}</td>
                      <td>{{ props.item.day_past }}</td>
                      <td>{{ props.item.send_method? props.item.person_sent:'No Value'}}</td>
                      <td>{{ props.item.person_sent? props.item.person_sent:'No Value' }}</td>
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
        {text: 'Product Code', value: 'prod_code'},
        {text: 'Warranty', value: 'warranty_type'},
        {text: 'Repair Location', value: 'repair_loc'},
        {text: 'Days Past', value: 'day_past'},
        {text: 'Send Method', value: 'send_method'},
        {text: 'Person sent', value: 'person_sent'}
      ],
      inProgressList: []
    }),
    mounted () {
      this.init()
    },
    methods: {
      init () {
        var data = new URLSearchParams()
        data.append('request', 'inProgress')
        this.axios.post('view.php', data).then(
          (res) => {
            var data = res.data
            this.inProgressList = data
          }
        ).catch((error) => {
          console.log(error)
        })
      }
    }
  }
</script>

<style scoped>

</style>
