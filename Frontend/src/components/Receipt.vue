<template>
  <v-slide-y-transition mode="out-in">
    <v-container grid-list-xs justify-center>
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
            <div class="title">Appiporn</div>
          </v-flex>
          <v-flex xs5></v-flex>
          <v-flex xs3>
            <div class="title text-xs-right">Pro number :</div>
          </v-flex>
          <v-flex xs1>
            <div class="title">PR18/002</div>
          </v-flex>
          <!--End of 1 row-->

          <!--2 row-->
          <v-flex xs3>
            <div class="title">Department Store:</div>
          </v-flex>
          <v-flex xs3>
            <div class="title">RO บางนา</div>
          </v-flex>
          <v-flex xs2></v-flex>
          <v-flex xs3>
            <div class="title text-xs-right">Repair number :</div>
          </v-flex>
          <v-flex xs1>
            <div class="title">What??</div>
          </v-flex>
          <!--End of 2 row-->

          <!--3 row-->
          <v-flex xs3>
            <div class="title">Repair form #</div>
          </v-flex>
          <v-flex xs2>
            <div class="title">293/14608</div>
          </v-flex>
          <v-flex xs3></v-flex>
          <v-flex xs3>
            <div class="title text-xs-right">Date :</div>
          </v-flex>
          <v-flex xs1>
            <div class="title">16/09/2018</div>
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
            <tr v-for="i in dataLength" :key="`${i}`">
              <td>{{i}}</td>
              <td>ABAC0010{{i}}</td>
              <td>AC-001 KEY RING (KL-16)</td>
              <td>{{i}}00</td>
              <td>1</td>
              <td>{{i}}00</td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>{{dataLength}}</td>
              <td>{{100 + 200 + 300 + 400 + 500 + 600 + 700 + 800}}
              </td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>
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

    </v-container>

  </v-slide-y-transition>
</template>

<script>
  import 'print-js'

  export default {
    name: 'home',
    data: () => ({
      dataLength: 8
    }),
    mounted () {
    },
    methods: {
      printReceipt () {
        printJS({
          printable: 'printArea',
          type: 'html',
          targetStyles: ['*']
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
</style>
