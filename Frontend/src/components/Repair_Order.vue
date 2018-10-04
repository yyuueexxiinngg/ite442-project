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
                  Display Repair Order
                  <v-spacer></v-spacer>
                  <v-autocomplete
                    v-model="repairID"
                    :items="repairOrder"
                    item-text="repair_id"
                    item-value="repair_id"
                    label="Select Rapair ID"
                    single-line
                    hide-details
                    prepend-icon="search"
                  >
                    <v-slide-x-reverse-transition
                      slot="append-outer"
                      mode="out-in"
                    >
                    </v-slide-x-reverse-transition>
                  </v-autocomplete>
                  <v-progress-linear slot="progress" color="blue" indeterminate
                                     v-if="$store.state.isLoading"></v-progress-linear>
                </v-card-title>
                <v-container>
                  <v-layout row wrap id="printArea">
                    <v-flex xs12 class="text-xs-center">
                      <span class="display-1"><b>MBP LEATHER INDUSTRES CO., LTD.</b></span>
                      <br/>
                      174 หมู่ 1 ถ.แพรกษา ต.แพรกษา อ.เมือง สมุทรปราการ 10280
                      <br>
                      โทร 388-0176-9 แฟกซ์ 388-0180 E-mail : suphannee_albedo@hotmail.com , su@albedo-co.com
                      <br><br>
                      <span class="display-1"><u>Repair Order</u></span>
                    </v-flex>


                    <v-flex xs8></v-flex>
                    <v-flex xs2 class="text-xs-right">
                      <v-spacer></v-spacer>
                      <b>Document no.</b>
                    </v-flex>
                    <v-flex xs2 class="text-xs-center">
                      <b>18/002</b>
                    </v-flex>

                    <v-flex xs8>
                      <b>Company:</b>
                    </v-flex>
                    <v-flex xs2 class="text-xs-right">
                      <b>Date</b>
                    </v-flex>
                    <v-flex xs2 class="text-xs-center">
                      <b>{{repairOrderItem.Date}}</b>
                    </v-flex>

                    <v-flex xs12>
                      <v-spacer class="mb-4"></v-spacer>
                    </v-flex>

                    <v-flex xs12>
                      <table>
                        <tr class="title text-xs-center">
                          <th width="8%">#</th>
                          <th width="15%">Receipt Form #</th>
                          <th width="15%">Product code</th>
                          <th width="50%">Repair Details</th>
                          <th>Note</th>
                        </tr>
                        <tr class="text-xs-center" v-if="repairOrderItem.repair_id">
                          <td>1</td>
                          <td>{{repairOrderItem.repair_form}}</td>
                          <td>{{repairOrderItem.prod_code}}</td>
                          <td class="text-xs-left">{{repairOrderItem.repair_details}}</td>
                          <td>1</td>
                        </tr>
                        <tr v-for="i in 10" :key="`${i}`">
                          <td v-for="i in 5" :key="i"></td>
                        </tr>
                      </table>
                    </v-flex>

                    <v-flex xs12>
                      <v-spacer class="mb-5"></v-spacer>
                    </v-flex>

                    <v-flex xs1></v-flex>
                    <v-flex xs2>ลงชื่อ…………………………………….</v-flex>
                    <v-flex xs6></v-flex>
                    <v-flex xs2>ลงชื่อ…………………………………….</v-flex>
                    <v-flex xs1></v-flex>

                    <v-flex xs1></v-flex>
                    <v-flex xs2 class="text-xs-center">( ผู้ส่งซ่อมสินค้า )</v-flex>
                    <v-flex xs6></v-flex>
                    <v-flex xs2 class="text-xs-center">( ผู้ส่งซ่อมสินค้า )</v-flex>
                    <v-flex xs1></v-flex>

                    <v-flex xs12>
                      <v-spacer class="mb-5"></v-spacer>
                    </v-flex>

                  </v-layout>
                </v-container>
              </v-card>
            </template>
          </v-flex>
        </v-layout>

        <v-flex xs12>
          <v-spacer class="mb-3"></v-spacer>
        </v-flex>

        <v-flex xs6>
          <v-btn block color="success" @click="printReapirOrder">Print</v-btn>
        </v-flex>

      </v-layout>
    </v-container>
  </v-slide-y-transition>
</template>

<script>
  import 'print-js'

  export default {
    name: 'repair_order',
    data: () => ({
      repairID: '',
      headers: [
        {text: 'Repair ID', align: 'left', value: 'repair_id'},
        {text: 'Product Code', value: 'prod_code'},
        {text: 'Warranty', value: 'warranty_type'},
        {text: 'Repair Location', value: 'repair_loc'},
        {text: 'Days Past', value: 'day_past'},
        {text: 'Send Method', value: 'send_method'},
        {text: 'Person sent', value: 'person_sent'}
      ],
      repairOrder: null,
      repairOrderItem: {}
    }),
    mounted () {
      this.init()
    },
    watch: {
      repairID (val) {
        this.getRepairOrder(val)
      }
    },
    methods: {
      printReapirOrder () {
        printJS({
          printable: 'printArea',
          type: 'html',
          targetStyles: ['*']
        })
      },

      init () {
        var data = new URLSearchParams()
        data.append('request', 'repairOrder')
        this.axios.post('view.php', data).then(
          (res) => {
            var data = res.data
            this.repairOrder = data
          }
        ).catch((error) => {
          console.log(error)
        })
      },
      getRepairOrder (val) {
        var data = new URLSearchParams()
        data.append('request', 'repairOrder')
        data.append('get', val)
        this.axios.post('view.php', data).then(
          (res) => {
            var data = res.data
            this.repairOrderItem = data
          }
        ).catch((error) => {
          console.log(error)
        })
      }
    }
  }
</script>

<style lang="stylus">
  table
    border-collapse collapse
    width 100%

  td, th
    border 1px solid #dddddd
    padding 8px
    height 37px
  #red
    color red
</style>
