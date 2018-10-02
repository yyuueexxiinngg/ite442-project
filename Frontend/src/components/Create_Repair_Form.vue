<template>
  <v-slide-y-transition mode="out-in">
    <v-container>
      <v-layout row wrap align-center justify-center>

        <v-layout row wrap>
          <v-flex xs12>
            <v-hover>
              <v-card
                slot-scope="{ hover }"
                :class="`elevation-${hover ? 12 : 5}`"
              >
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

                      <v-flex xs6 md3>
                        <v-select
                          v-model="repairLocationSelected"
                          :items="repairLocation"
                          item-text="Address"
                          item-value="repair_loc_id"
                          label="Repair Location"
                          outline
                          name="repair_location"
                        ></v-select>
                      </v-flex>


                      <v-flex xs6 md3>
                        <v-menu
                          ref="purchaseDate"
                          :close-on-content-click="false"
                          v-model="purchaseDateMenu"
                          :nudge-right="40"
                          :return-value.sync="purchaseDate"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          min-width="290px"
                        >
                          <v-text-field
                            outline
                            slot="activator"
                            v-model="purchaseDate"
                            label="Purchase Date"
                            append-icon="event"
                            readonly
                          ></v-text-field>
                          <v-date-picker v-model="purchaseDate"
                                         @input="$refs.purchaseDate.save(purchaseDate)"></v-date-picker>
                        </v-menu>
                      </v-flex>


                      <v-flex xs12 md6>
                        <v-menu
                          ref="receiveFromCustomer"
                          :close-on-content-click="false"
                          v-model="receiveFromCustomerMenu"
                          :nudge-right="40"
                          :return-value.sync="receiveFromCustomer"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          min-width="290px"
                        >
                          <v-text-field
                            outline
                            slot="activator"
                            v-model="receiveFromCustomer"
                            label="Date Receive From Customer"
                            append-icon="event"
                            readonly
                          ></v-text-field>
                          <v-date-picker v-model="receiveFromCustomer"
                                         @input="$refs.receiveFromCustomer.save(receiveFromCustomer)"></v-date-picker>
                        </v-menu>
                      </v-flex>

                      <v-flex xs12 md6>
                        <v-menu
                          ref="dateArrivedCompany"
                          :close-on-content-click="false"
                          v-model="dateArrivedCompanyMenu"
                          :nudge-right="40"
                          :return-value.sync="dateArrivedCompany"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          min-width="290px"
                        >
                          <v-text-field
                            required
                            outline
                            slot="activator"
                            v-model="dateArrivedCompany"
                            label="Date Arrived at Company"
                            append-icon="event"
                            readonly
                          ></v-text-field>
                          <v-date-picker v-model="dateArrivedCompany"
                                         @input="$refs.dateArrivedCompany.save(dateArrivedCompany)"></v-date-picker>
                        </v-menu>
                      </v-flex>


                      <v-flex xs12 md6>
                        <v-menu
                          ref="dateSentFactory"
                          :close-on-content-click="false"
                          v-model="dateSentFactoryMenu"
                          :nudge-right="40"
                          :return-value.sync="dateSentFactory"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          min-width="290px"
                        >
                          <v-text-field
                            required
                            outline
                            slot="activator"
                            v-model="dateSentFactory"
                            label="Date Sent to Factory"
                            append-icon="event"
                            readonly
                          ></v-text-field>
                          <v-date-picker v-model="dateSentFactory"
                                         @input="$refs.dateSentFactory.save(dateSentFactory)"></v-date-picker>
                        </v-menu>
                      </v-flex>

                      <v-flex xs12 md6>
                        <v-menu
                          ref="dateReceiveFromFactory"
                          :close-on-content-click="false"
                          v-model="dateReceiveFromFactoryMenu"
                          :nudge-right="40"
                          :return-value.sync="dateReceiveFromFactory"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          min-width="290px"
                        >
                          <v-text-field
                            required
                            outline
                            slot="activator"
                            v-model="dateReceiveFromFactory"
                            label="Date Receive from Factory"
                            append-icon="event"
                            readonly
                          ></v-text-field>
                          <v-date-picker v-model="dateReceiveFromFactory"
                                         @input="$refs.dateReceiveFromFactory.save(dateReceiveFromFactory)"></v-date-picker>
                        </v-menu>
                      </v-flex>

                      <v-flex xs12 md4>
                        <v-menu
                          ref="dateReturnToStore"
                          :close-on-content-click="false"
                          v-model="dateReturnToStoreMenu"
                          :nudge-right="40"
                          :return-value.sync="dateReturnToStore"
                          lazy
                          transition="scale-transition"
                          offset-y
                          full-width
                          min-width="290px"
                        >
                          <v-text-field
                            required
                            outline
                            slot="activator"
                            v-model="dateReturnToStore"
                            label="Date Return to Store"
                            append-icon="event"
                            readonly
                          ></v-text-field>
                          <v-date-picker v-model="dateReturnToStore"
                                         @input="$refs.dateReturnToStore.save(dateReturnToStore)"></v-date-picker>
                        </v-menu>
                      </v-flex>

                      <v-flex xs6 md4>
                        <v-select
                          v-model="shippingMethodSelected"
                          :items="shippingMethods"
                          item-text="Method"
                          item-value="ship_method_id"
                          label="Shipping method"
                          outline
                          name="shipping_method"
                        ></v-select>
                      </v-flex>

                      <v-flex xs6 md4>
                        <v-text-field
                          v-model="personSent"
                          label="Person Sent"
                          outline
                          name="person_sent"
                        ></v-text-field>
                      </v-flex>

                    </v-layout>
                  </v-container>
                </v-form>
              </v-card>
            </v-hover>
          </v-flex>
          <v-flex xs12>
            <v-divider class="mb-4"></v-divider>
          </v-flex>
          <v-flex xs12 v-if="!costDisable">
            <v-hover>
              <v-card
                slot-scope="{ hover }"
                :class="`elevation-${hover ? 12 : 5}`"
              >
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
            </v-hover>
          </v-flex>
          <v-flex xs12>
            <v-divider v-if="!costDisable" class="mb-4"></v-divider>
          </v-flex>
          <v-flex xs12>
            <v-hover>
              <v-card
                slot-scope="{ hover }"
                :class="`elevation-${hover ? 12 : 5}`"
              >
                <v-card-title primary class="title">{{newCustomer?'Create Customer':'Select Customer'}}</v-card-title>
                <v-form ref="receipt">
                  <v-container>

                    <v-layout row wrap v-if="!newCustomer">
                      <v-flex xs12 md6>
                        <v-autocomplete
                          v-model="model"
                          :items="customers"
                          :search-input.sync="searchCustomerByName"
                          :loading="customersLoading"
                          chips
                          clearable
                          hide-details
                          hide-selected
                          item-text="customername"
                          item-value="customer_id"
                          label="Search for a customer by name"
                          solo
                        >
                          <template slot="no-data">
                            <v-list-tile>
                              <v-list-tile-title>
                                Search for a customer by
                                <strong>name</strong>
                              </v-list-tile-title>
                            </v-list-tile>
                          </template>
                          <template
                            slot="selection"
                            slot-scope="{ item, selected }"
                          >
                            <v-chip
                              :selected="selected"
                              color="primary"
                              label
                              outline
                            >
                              <v-icon left>account_circle</v-icon>
                              {{item.customername}}
                            </v-chip>
                          </template>
                          <template
                            slot="item"
                            slot-scope="{ item, tile }"
                          >
                            <v-list-tile-avatar
                              color="indigo"
                              class="headline font-weight-light white--text"
                            >
                              {{ item.customername.charAt(0) }}
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                              <v-list-tile-title v-text="item.customername"></v-list-tile-title>
                              <v-list-tile-sub-title v-text="item.customertel"></v-list-tile-sub-title>
                            </v-list-tile-content>
                            <v-list-tile-action>
                              <v-icon>mdi-coin</v-icon>
                            </v-list-tile-action>
                          </template>
                        </v-autocomplete>
                      </v-flex>
                      <v-flex xs12 md6>
                        <v-autocomplete
                          v-model="model"
                          :items="customers"
                          :search-input.sync="searchCustomerByTel"
                          :loading="customersLoading"
                          chips
                          clearable
                          hide-details
                          hide-selected
                          item-text="customertel"
                          item-value="customer_id"
                          label="Search for a customer by tel"
                          solo
                        >
                          <template slot="no-data">
                            <v-list-tile>
                              <v-list-tile-title>
                                Search for a customer by
                                <strong>telephone</strong>
                              </v-list-tile-title>
                            </v-list-tile>
                          </template>
                          <template
                            slot="selection"
                            slot-scope="{ item, selected }"
                          >
                            <v-chip
                              :selected="selected"
                              color="primary"
                              label
                              outline
                            >
                              <v-icon left>contact_phone</v-icon>
                              {{item.customertel}}
                            </v-chip>
                          </template>
                          <template
                            slot="item"
                            slot-scope="{ item, tile }"
                          >
                            <v-list-tile-avatar
                              color="indigo"
                              class="headline font-weight-light white--text"
                            >
                              {{ item.customername.charAt(0) }}
                            </v-list-tile-avatar>
                            <v-list-tile-content>
                              <v-list-tile-title v-text="item.customername"></v-list-tile-title>
                              <v-list-tile-sub-title v-text="item.customertel"></v-list-tile-sub-title>
                            </v-list-tile-content>
                            <v-list-tile-action>
                              <v-icon>mdi-coin</v-icon>
                            </v-list-tile-action>
                          </template>
                        </v-autocomplete>
                      </v-flex>
                    </v-layout>

                    <v-layout row wrap v-if="newCustomer">
                      <v-flex xs12 md6>
                      </v-flex>
                    </v-layout>
                    <v-switch
                      label="New customer"
                      v-model="newCustomer"
                    ></v-switch>
                  </v-container>
                </v-form>
              </v-card>
            </v-hover>
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
      customersLoading: false,
      warranty: [],
      warrantySelected: null,
      repairDetail: null,
      model: null,
      searchCustomerByName: null,
      searchCustomerByTel: null,
      costDisable: true,
      cost: 0,
      proNumber: null,
      proNumberRules: [
        v => !!v || 'Pro number is required',
        v => /.+\/.+/.test(v) || 'Pro number must be valid'
      ],
      proDate: null,
      proDateMenu: false,
      repairLocation: [],
      repairLocationSelected: null,
      shippingMethods: [],
      shippingMethodSelected: null,
      personSent: null,
      purchaseDate: null,
      purchaseDateMenu: false,
      receiveFromCustomerMenu: false,
      receiveFromCustomer: null,
      dateArrivedCompanyMenu: false,
      dateArrivedCompany: null,
      dateSentFactoryMenu: false,
      dateSentFactory: null,
      dateReceiveFromFactoryMenu: false,
      dateReceiveFromFactory: null,
      dateReturnToStoreMenu: false,
      dateReturnToStore: null
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
            this.repairLocation = data.repair_location
            this.shippingMethods = data.shipping_method
          }
        ).catch((error) => {
          console.log(error)
        })
      }
    },
    watch: {
      searchCustomerByName (val) {
        if (val) {
          this.customersLoading = true
          var data = new URLSearchParams()
          data.append('request', 'customer')
          data.append('customerName', val)

          this.axios.post('insert.php', data, {disableLoading: true})
            .then(res => {
              this.customers = res.data
            })
            .catch(err => {
              console.log(err)
            })
            .finally(() => (this.customersLoading = false))
        }
      },
      searchCustomerByTel (val) {
        if (val) {
          this.customersLoading = true
          var data = new URLSearchParams()
          data.append('request', 'customer')
          data.append('customerTel', val)

          this.axios.post('insert.php', data, {disableLoading: true})
            .then(res => {
              this.customers = res.data
            })
            .catch(err => {
              console.log(err)
            })
            .finally(() => (this.customersLoading = false))
        }
      },
      warrantySelected (val) {
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
