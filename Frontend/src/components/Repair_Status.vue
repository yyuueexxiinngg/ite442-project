<template>
  <v-slide-y-transition mode="out-in">
    <v-container grid-list-xs justify-center>
      <v-layout row justify-center>
        <v-flex xs12 offset-xs-3>
          <v-form ref="form" v-model="valid">
            <v-text-field
              v-model="proNumber"
              label="Telephone Number"
              outline
              :rules="proNumberRules"
              placeholder="Please enter your telephone number to check the progress"
              required
            ></v-text-field>
          </v-form>
        </v-flex>
      </v-layout>
      <v-layout row warp justify-center>
        <v-flex xs6 offset-xs-3>
          <v-btn block color="secondary" @click="getReceipt" :disabled="!valid">Check</v-btn>
        </v-flex>
      </v-layout>
      <div v-if="receiptItem">
        <v-layout id="printArea">
          <v-layout row wrap>
            <v-flex xs12>
              <div class="headline text-xs-center">Receipt</div>
            </v-flex>

            <br><br><br>

            <!--1 row-->
            <v-flex xs1>
              <div class="title">Name:</div>
            </v-flex>
            <v-flex xs2>
              <div class="title">{{receiptItem.name}}</div>
            </v-flex>
            <v-flex xs5></v-flex>
            <v-flex xs3>
              <div class="title text-xs-right">Pro number :</div>
            </v-flex>
            <v-flex xs1>
              <div class="title">{{receiptItem.pro_number}}</div>
            </v-flex>
            <!--End of 1 row-->

            <!--2 row-->
            <v-flex xs3>
              <div class="title">Department Store:</div>
            </v-flex>
            <v-flex xs3>
              <div class="title">{{receiptItem.department_name}}</div>
            </v-flex>
            <v-flex xs2></v-flex>
            <v-flex xs3>
              <div class="title text-xs-right">Repair number :</div>
            </v-flex>
            <v-flex xs1>
              <div class="title">{{receiptItem.repair_id}}</div>
            </v-flex>
            <!--End of 2 row-->

            <!--3 row-->
            <v-flex xs3>
              <div class="title">Repair form #</div>
            </v-flex>
            <v-flex xs2>
              <div class="title">{{receiptItem.repair_form}}</div>
            </v-flex>
            <v-flex xs3></v-flex>
            <v-flex xs3>
              <div class="title text-xs-right">Date :</div>
            </v-flex>
            <v-flex xs1>
              <div class="title">{{receiptItem.date}}</div>
            </v-flex>
            <!--End of 3 row-->

            <br><br><br>

            <table>
              <tr class="title text-xs-center">
                <th>#</th>
                <th>Product Code</th>
                <th>Product description</th>
                <th>Cost</th>
                <th>Qty</th>
                <th>Total</th>
              </tr>
              <tr>
                <td>1</td>
                <td>{{receiptItem.product_code}}</td>
                <td>{{receiptItem.description}}</td>
                <td>{{receiptItem.cost}}</td>
                <td>1</td>
                <td>{{receiptItem.cost}}</td>
              </tr>
              <tr v-for="i in 7" :key="`${i}`">
                <td v-for="i in 6" :key="i"></td>
              </tr>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>1</td>
                <td>{{receiptItem.cost}}
                </td>
              </tr>
            </table>
          </v-layout>

        </v-layout>
        <v-layout justify-center>
          <v-flex xs5>
            <v-btn block color="success" @click="printReceipt">Print</v-btn>
          </v-flex>

        </v-layout>
      </div>
      <v-progress-linear :indeterminate="$store.state.isLoading" v-if="$store.state.isLoading"></v-progress-linear>
      <v-alert
        :value="true"
        color="error"
        icon="warning"
        outline
        v-if="$store.state.generalAlert"
      >
        {{$store.state.generalAlert}}
      </v-alert>
    </v-container>

  </v-slide-y-transition>
</template>

<script>
  import 'print-js'

  export default {
    name: 'repair_status',
    data: () => ({
      valid: true,
      receiptItem: null,
      proNumber: '',
      proNumberRules: [
        v => !!v || 'Telephone number is required',
        v => /.+\/.+/.test(v) || 'Telephone number must be valid'
      ]
    }),
    mounted () {
    },
    watch: {},
    methods: {
      printReceipt () {
        printJS({
          printable: 'printArea',
          type: 'html',
          targetStyles: ['*']
        })
      },
      getReceipt () {
        this.receiptItem = null
        this.proNumber = this.proNumber.toUpperCase()
        var data = new URLSearchParams()
        data.append('request', 'receipt')
        data.append('pro_number', this.proNumber)
        this.axios.post('view.php', data).then(
          (res) => {
            this.receiptItem = res.data
          }
        ).catch(
          (error) => {
            if (error.response) {
              var errorData = error.response.data
              this.$store.state.generalAlert = errorData.msg
            }
          }
        )
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
</style>
