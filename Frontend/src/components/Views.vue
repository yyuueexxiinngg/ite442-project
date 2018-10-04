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
            <v-select
              v-model="viewSelected"
              :items="viewSelections"
              item-text="text"
              item-value="id"
              label="Select a View"
              single-line
              hide-details
              solo
              return-object
            >
              <v-slide-x-reverse-transition
                slot="append-outer"
                mode="out-in"
              >
              </v-slide-x-reverse-transition>
            </v-select>

          </v-flex>

          <v-flex xs12>
            <div>
              <v-date-picker
                v-if="viewSelected.id==2"
                v-model="dateSentFactory"
                :allowed-dates="limitDateSentFactory"
                full-width
                landscape
                class="mt-3"
              ></v-date-picker>
            </div>

            <div>
              <v-date-picker
                v-if="viewSelected.id==3"
                v-model="dateArrivedCompany"
                :allowed-dates="limitDateArrivedCompany"
                full-width
                landscape
                class="mt-3"
              ></v-date-picker>
            </div>
          </v-flex>


          <v-flex xs12>
            <template>
              <v-card class="elevation-5">
                <v-spacer class="mb-4"></v-spacer>
                <v-card-title>
                  {{viewSelected?viewSelected.text:'View'}} List
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
                  :headers="headerSelected"
                  :items="viewItemList"
                  :search="search"
                  :rows-per-page-items="[10,25,{'text':'$vuetify.dataIterator.rowsPerPageAll','value':-1}]"
                >
                  <template slot="items" slot-scope="props">
                    <tr
                      @click="$refs.updateDialog.openUpdateDialog(props.item.repair_id)"
                      v-if="viewSelected.id==1"
                    >
                      <td>{{ props.item.repair_id }}</td>
                      <td>{{ props.item.repair_form }}</td>
                      <td>{{ props.item.prod_code }}</td>
                      <td>{{ props.item.repair_details }}</td>
                      <td>{{ props.item.deptname }}</td>
                      <td>{{ props.item.customername}}</td>
                      <td>{{ props.item.days}}</td>
                      <td>{{ props.item.date_rec_store}}</td>
                      <td>{{ props.item.date_sent_factory}}</td>
                    </tr>

                    <tr
                      @click="$refs.updateDialog.openUpdateDialog(props.item.repair_id)"
                      v-else-if="viewSelected.id==2"
                    >
                      <td>{{ props.item.repair_id }}</td>
                      <td>{{ props.item.repair_form }}</td>
                      <td>{{ props.item.repair_details }}</td>
                      <td>{{ props.item.prod_code }}</td>
                      <td>{{ props.item.deptname }}</td>
                      <td>{{ props.item.customername}}</td>
                      <td>{{ props.item.date_rec_store}}</td>
                    </tr>

                    <tr
                      @click="$refs.updateDialog.openUpdateDialog(props.item.repair_id)"
                      v-else-if="viewSelected.id==3"
                    >
                      <td>{{ props.item.repair_id }}</td>
                      <td>{{ props.item.repair_form }}</td>
                      <td>{{ props.item.prod_code }}</td>
                      <td>{{ props.item.repair_details }}</td>
                      <td>{{ props.item.deptname }}</td>
                      <td>{{ props.item.customername}}</td>
                      <td>{{ props.item.date_rec_store}}</td>
                      <td>{{ props.item.date_sent_factory}}</td>
                    </tr>

                    <tr
                      @click="$refs.updateDialog.openUpdateDialog(props.item.repair_id)"
                      v-else-if="viewSelected.id==4"
                    >
                      <td>{{ props.item.prod_code }}</td>
                      <td>{{ props.item.warranty_type }}</td>
                      <td>{{ props.item.deptname }}</td>
                      <td>{{ props.item.customername }}</td>
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
    name: 'views',
    components: {
      UpdateRepairComponent
    },
    data: () => ({
      viewSelections: [
        {id: 1, text: 'complain'},
        {id: 2, text: 'ส่งพี่พงษ์2'},
        {id: 3, text: 'OutsourceRepair'},
        {id: 4, text: 'FollowUpReport'}
      ],
      viewSelected: {id: 1, text: 'complain'},
      search: '',
      headers: {
        complain: [
          {text: 'Send to fix', align: 'left', value: 'repair_id'},
          {text: 'Repair number', value: 'repair_form'},
          {text: 'Product code', value: 'prod_code'},
          {text: 'Detailed description', value: 'repair_details'},
          {text: 'Department store', value: 'deptname'},
          {text: 'Customer\'s name', value: 'customername'},
          {text: 'Days', value: 'send_method'},
          {text: 'Date accept from customer', value: 'date_rec_store'},
          {text: 'Date send to factory', value: 'date_sent_factory'}
        ],
        ส่งพี่พงษ์2: [
          {text: 'Send to repair', align: 'left', value: 'repair_id'},
          {text: 'Repair number', value: 'repair_form'},
          {text: 'Detailed description', value: 'repair_details'},
          {text: 'Product code', value: 'prod_code'},
          {text: 'Department store', value: 'deptname'},
          {text: 'Customer\'s name', value: 'customername'},
          {text: 'Date accept from customer', value: 'date_rec_store'}

        ],
        OutsourceRepair: [
          {text: 'Send to repair', align: 'left', value: 'repair_id'},
          {text: 'Repair number', value: 'repair_form'},
          {text: 'Product code', value: 'prod_code'},
          {text: 'Detailed description', value: 'repair_details'},
          {text: 'Department store', value: 'deptname'},
          {text: 'Customer\'s name', value: 'customername'},
          {text: 'Date accept from customer', value: 'date_rec_store'},
          {text: 'Sent to factory', value: 'date_sent_factory'}
        ],
        FollowUpReport: [
          {text: 'Product code', value: 'prod_code'},
          {text: 'Warranty', value: 'prod_code'},
          {text: 'Department store', value: 'deptname'},
          {text: 'Customer\'s name', value: 'customername'}
        ]
      },
      headerSelected: [
        {text: 'Send to fix', align: 'left', value: 'repair_id'},
        {text: 'Repair number', value: 'repair_form'},
        {text: 'Product code', value: 'prod_code'},
        {text: 'Detailed description', value: 'repair_details'},
        {text: 'Department store', value: 'deptname'},
        {text: 'Customer\'s name', value: 'customername'},
        {text: 'Days', value: 'send_method'},
        {text: 'Date accept from customer', value: 'date_rec_store'},
        {text: 'Date send to factory', value: 'date_sent_factory'}
      ],
      viewItemList: [],
      dateSentFactory: null,
      dateSentFactoryLimit: [],
      dateArrivedCompany: null,
      dateArrivedCompanyLimit: []
    }),
    mounted () {
      this.init()
    },
    watch: {
      viewSelected (val) {
        if (val.id === 1 || val.id === 4) {
          var data = new URLSearchParams()
          data.append('request', 'views')
          data.append('view', this.viewSelected.text)
          this.axios.post('view.php', data).then(
            (res) => {
              var data = res.data
              this.viewItemList = data
            }
          ).catch((error) => {
            console.log(error)
          })
        }
        this.viewItemList = []
        var selected = 'this.headers.' + val.text.toString()
        this.headerSelected = eval(selected)
      },
      dateSentFactory (val) {
        this.viewItemList = []
        var data = new URLSearchParams()
        data.append('request', 'views')
        data.append('view', this.viewSelected.text)
        data.append('dateSentFactory', val)
        this.axios.post('view.php', data).then(
          (res) => {
            var data = res.data
            this.viewItemList = data
          }
        ).catch((error) => {
          console.log(error)
        })
      },
      dateArrivedCompany (val) {
        this.viewItemList = []
        var data = new URLSearchParams()
        data.append('request', 'views')
        data.append('view', this.viewSelected.text)
        data.append('dateArrivedCompany', val)
        this.axios.post('view.php', data).then(
          (res) => {
            var data = res.data
            this.viewItemList = data
          }
        ).catch((error) => {
          console.log(error)
        })
      }
    },
    methods: {
      init () {
        var data = new URLSearchParams()
        data.append('request', 'views')
        data.append('view', this.viewSelected.text)
        this.axios.post('view.php', data).then(
          (res) => {
            var data = res.data
            this.viewItemList = data
          }
        ).catch((error) => {
          console.log(error)
        })

        data = new URLSearchParams()
        data.append('request', 'views')
        data.append('view', 'init')
        this.axios.post('view.php', data).then(
          (res) => {
            var data = res.data
            this.dateSentFactoryLimit = data.dataSentFactory
            this.dateArrivedCompanyLimit = data.dateArrivedCompany
          }
        ).catch((error) => {
          console.log(error)
        })
      },
      limitDateSentFactory (val) {
        return this.dateSentFactoryLimit.indexOf(val) !== -1
      },
      limitDateArrivedCompany (val) {
        return this.dateArrivedCompanyLimit.indexOf(val) !== -1
      }
    }
  }
</script>

<style scoped>

</style>
