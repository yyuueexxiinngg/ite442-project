<template>
  <v-slide-y-transition mode="out-in">
    <v-container>
      <v-layout row wrap align-center justify-center>

        <v-layout row wrap>
          <v-flex xs12>
            <v-card class="elevation-5">
              <v-card-title primary class="title">Create Repair Form</v-card-title>
              <v-progress-linear :indeterminate="true" v-if="$store.state.isLoading"></v-progress-linear>
              <v-form ref="form">
                <v-container>
                  <v-layout row wrap>
                    <v-flex xs6 md3>
                      <v-text-field
                        v-model="repairForm"
                        label="Repair Form"
                        placeholder="000/00000"
                        outline
                        name="repair_form"
                      ></v-text-field>
                    </v-flex>

                    <v-flex xs6 md3>
                      <v-select
                        v-model="departmentSelected"
                        :items="departments"
                        item-text="deptname"
                        item-value="department_id"
                        label="Department store"
                        outline
                        name="department"
                      ></v-select>
                    </v-flex>

                    <v-flex xs6 md3>
                      <v-select
                        v-model="productSelected"
                        :items="products"
                        item-text="productcode"
                        item-value="productcode"
                        label="Product code"
                        outline
                        name="product_code"
                      ></v-select>
                    </v-flex>

                    <!--<v-flex xs6 md3>-->
                    <!--<v-switch-->
                    <!--label="New customer"-->
                    <!--v-model="newCustomer"-->
                    <!--&gt;</v-switch>-->
                    <!--<v-autocomplete-->
                    <!--v-model="model"-->
                    <!--:items="customers"-->
                    <!--:search-input.sync="search"-->
                    <!--chips-->
                    <!--clearable-->
                    <!--hide-details-->
                    <!--hide-selected-->
                    <!--item-text="name"-->
                    <!--item-value="symbol"-->
                    <!--label="Search for a customer"-->
                    <!--solo-->
                    <!--&gt;-->
                    <!--<template slot="no-data">-->
                    <!--<v-list-tile>-->
                    <!--<v-list-tile-title>-->
                    <!--Search for a customer by-->
                    <!--<strong>name</strong>-->
                    <!--</v-list-tile-title>-->
                    <!--</v-list-tile>-->
                    <!--</template>-->
                    <!--<template-->
                    <!--slot="selection"-->
                    <!--slot-scope="{ item, selected }"-->
                    <!--&gt;-->
                    <!--<v-chip-->
                    <!--:selected="selected"-->
                    <!--color="blue-grey"-->
                    <!--class="white&#45;&#45;text"-->
                    <!--&gt;-->
                    <!--<v-icon left>mdi-coin</v-icon>-->
                    <!--<span v-text="item.name"></span>-->
                    <!--</v-chip>-->
                    <!--</template>-->
                    <!--<template-->
                    <!--slot="item"-->
                    <!--slot-scope="{ item, tile }"-->
                    <!--&gt;-->
                    <!--<v-list-tile-avatar-->
                    <!--color="indigo"-->
                    <!--class="headline font-weight-light white&#45;&#45;text"-->
                    <!--&gt;-->
                    <!--{{ item.name.charAt(0) }}-->
                    <!--</v-list-tile-avatar>-->
                    <!--<v-list-tile-content>-->
                    <!--<v-list-tile-title v-text="item.name"></v-list-tile-title>-->
                    <!--<v-list-tile-sub-title v-text="item.symbol"></v-list-tile-sub-title>-->
                    <!--</v-list-tile-content>-->
                    <!--<v-list-tile-action>-->
                    <!--<v-icon>mdi-coin</v-icon>-->
                    <!--</v-list-tile-action>-->
                    <!--</template>-->
                    <!--</v-autocomplete>-->
                    <!--</v-flex>-->


                    <v-flex xs6 md3>
                      <v-text-field
                        v-model="repairDetail"
                        label="Repair Details"
                        outline
                        name="repair_details"
                      ></v-text-field>
                    </v-flex>


                    <v-flex xs6 md3>
                      <v-select
                        v-model="warrantySelected"
                        :items="warranty"
                        item-text="warranty_type"
                        item-value="warranty_id"
                        label="Warranty Type"
                        outline
                        name="warranty_type"
                        ref="warranty_type"
                        return-object
                      ></v-select>
                    </v-flex>

                    <v-flex xs6 md3>
                      <v-text-field
                        v-model="cost"
                        label="cost"
                        outline
                        name="cost"
                        :disabled="costDisable"
                      ></v-text-field>
                    </v-flex>


                  </v-layout>
                </v-container>
              </v-form>
            </v-card>
          </v-flex>
          <v-flex xs12>
            <v-divider class="mb-5"></v-divider>
          </v-flex>
          <v-flex xs12 v-if="!costDisable">
            <v-card class="elevation-5">
              <v-card-title primary class="title">Create Receipt</v-card-title>
              <v-form ref="receipt">
                <v-container>
                  <v-layout row wrap>
                    <v-flex xs6 md6>
                      <v-text-field
                        v-model="proNumber"
                        label="Pro number"
                        placeholder="PR00/000"
                        :rules="proNumberRules"
                        outline
                        hint="PR**/***"
                        name="pro_number"
                      ></v-text-field>
                    </v-flex>

                    <v-flex xs6 md6>
                      <v-menu
                        ref="proDate"
                        :close-on-content-click="false"
                        v-model="proDateMenu"
                        :nudge-right="40"
                        :return-value.sync="proDate"
                        lazy
                        transition="scale-transition"
                        offset-y
                        full-width
                        min-width="290px"
                      >
                        <v-text-field
                          outline
                          slot="activator"
                          v-model="proDate"
                          label="Date"
                          append-icon="event"
                          readonly
                        ></v-text-field>
                        <v-date-picker v-model="proDate" @input="$refs.proDate.save(proDate)"></v-date-picker>

                      </v-menu>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-form>
            </v-card>
          </v-flex>
          <v-flex xs12>
            <v-alert
              :value="true"
              color="error"
              icon="warning"
              outline
              v-if="$store.state.generalAlert"

            >
              {{$store.state.generalAlert}}
              <div class="mb-2">
                <v-btn color="success" @click="init">Retry</v-btn>
              </div>
            </v-alert>
          </v-flex>
        </v-layout>

      </v-layout>
    </v-container>
  </v-slide-y-transition>
</template>

<script>
  export default {
    name: 'create_repair_form',
    data: () => ({
      valid: false,
      repairForm: null,
      departments: [],
      departmentSelected: null,
      products: [],
      productSelected: null,
      newCustomer: false,
      customers: [],
      customerSelected: null,
      warranty: [],
      warrantySelected: null,
      repairDetail: null,
      model: null,
      search: null,
      costDisable: true,
      cost: 0,
      proNumber: null,
      proNumberRules: [
        v => !!v || 'Pro number is required',
        v => /.+\/.+/.test(v) || 'Pro number must be valid'
      ],
      proDate: null,
      proDateMenu: false
    }),
    mounted () {
      this.init()
    },
    methods: {
      clear () {
        this.$refs.form.reset()
      },
      submit () {
        if (this.$refs.form.validate()) {
          var data = new URLSearchParams()
          data.append('username', this.username)
          data.append('password', this.password)
          this.axios.post('login.php', data).then(
            (res) => {
            }
          ).catch((error) => {
            console.log(error)
          })
        }
      },
      init () {
        var data = new URLSearchParams()
        data.append('request', 'init')
        this.axios.post('insert.php', data).then(
          (res) => {
            var data = res.data
            this.departments = data.departments
            this.products = data.products
            this.warranty = data.warranty
          }
        ).catch((error) => {
          console.log(error)
        })
      }
    },
    watch: {
      search (val) {
        // Items have already been loaded
        if (this.customers.length > 0) return

        this.isLoading = true

        // Lazily load input items
        this.axios.get('https://api.coinmarketcap.com/v2/listings/')
          .then(res => {
            this.customers = res.data.data
          })
          .catch(err => {
            console.log(err)
          })
          .finally(() => (this.isLoading = false))
      },
      warrantySelected (val) {
        console.log(val)
        if (val.warranty_type === 'in warranty') {
          this.costDisable = true
          this.cost = 0
        } else {
          this.costDisable = false
        }
      },
      proNumber (val) {
        this.proNumber = val.toUpperCase()
      }
    }
  }
</script>

<style lang="stylus">
</style>
