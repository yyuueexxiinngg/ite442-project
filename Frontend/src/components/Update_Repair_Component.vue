<template>
  <v-slide-y-transition mode="out-in">
    <v-container>
      <v-layout row wrap align-center justify-center>
        <v-dialog v-model="postLoadingDialog" content content-class="centered-dialog">
          <v-container fill-height>
            <v-layout column justify-center align-center>
              <v-progress-circular :indeterminate="postLoading" :value="100" :size="70" :width="7"
                                   color="primary"></v-progress-circular>
              <h1 v-if="postLoadingMsg != null">{{postLoadingMsg}}</h1>
            </v-layout>
          </v-container>
        </v-dialog>
        <v-dialog v-model="updateDialog" fullscreen hide-overlay transition="dialog-bottom-transition">
          <v-card>
            <v-toolbar dark color="primary">
              <v-btn icon dark @click="closeUpdateDialog">
                <v-icon>close</v-icon>
              </v-btn>
              <v-toolbar-title>Update Information for {{updatingRepairID }}</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-toolbar-items>
                <v-btn dark flat @click="closeUpdateDialog">Close</v-btn>
              </v-toolbar-items>
            </v-toolbar>

            <v-flex xs12>
              <v-hover>
                <v-card
                  slot-scope="{ hover }"
                  :class="`elevation-${hover ? 12 : 5}`"
                >
                  <v-card-title primary class="title">Update Repair Form</v-card-title>
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
                            label="Department Store"
                            outline
                            name="department"
                            :rules="[v => !!v || 'Department Store is required']"
                            hint="*Required"
                            persistent-hint
                          ></v-select>
                        </v-flex>

                        <v-flex xs6 md3>
                          <v-select
                            v-model="productSelected"
                            :items="products"
                            item-text="productcode"
                            item-value="productcode"
                            label="Product Code"
                            outline
                            name="product_code"
                            :rules="[v => !!v || 'Product Code is required']"
                            hint="*Required"
                            persistent-hint
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
                            :rules="[v => !!v || 'Warranty Type is required']"
                            hint="*Required"
                            persistent-hint
                            disabled
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
                            :rules="[v => !!v || 'Repair Location is required']"
                            hint="*Required"
                            persistent-hint
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
                                           :min="receiveFromCustomer"
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
                                           :min="dateArrivedCompany"
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
                                           :min="dateSentFactory"
                                           @input="$refs.dateReceiveFromFactory.save(dateReceiveFromFactory)"></v-date-picker>
                          </v-menu>
                        </v-flex>

                        <v-flex xs12 md6>
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
                                           :min="dateReceiveFromFactory"
                                           @input="$refs.dateReturnToStore.save(dateReturnToStore)"></v-date-picker>
                          </v-menu>
                        </v-flex>

                        <v-flex xs6 md6>
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

                        <v-flex xs6 md6>
                          <v-text-field
                            v-model="personSent"
                            label="Person Sent"
                            outline
                            name="person_sent"
                          ></v-text-field>
                        </v-flex>

                        <v-flex xs6 md6>
                          <v-text-field
                            v-model="trackingCode"
                            label="Tracking Code"
                            outline
                            name="tracking_code"
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
                  <v-card-title primary class="title">Update Receipt</v-card-title>
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
                            disabled
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
                              :rules="[v => !!v || 'Repair Location is required']"
                              hint="*Required"
                              persistent-hint
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
                  <v-form ref="customer">
                    <v-container>

                      <v-layout row wrap v-if="!newCustomer">
                        <v-flex xs12 md6>
                          <v-autocomplete
                            v-model="customerSelected"
                            :items="customers"
                            :search-input.sync="searchCustomerByName"
                            :loading="customersLoading"
                            chips
                            clearable
                            prepend-inner-icon="search"
                            hide-selected
                            item-text="customername"
                            item-value="customer_id"
                            label="Search for a customer by name"
                            solo
                            :rules="[v => !!v || 'Customer is required']"
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
                            v-model="customerSelected"
                            :items="customers"
                            :search-input.sync="searchCustomerByTel"
                            :loading="customersLoading"
                            chips
                            clearable
                            prepend-inner-icon="search"
                            hide-selected
                            item-text="customertel"
                            item-value="customer_id"
                            label="Search for a customer by tel"
                            solo
                            :rules="[v => !!v || 'Customer is required']"
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
                        <v-flex xs6 md6>
                          <v-text-field
                            v-model="customerName"
                            label="Customer Name"
                            outline
                            name="customer_name"
                            :rules="[v => !!v || 'Repair Location is required']"
                            hint="*Required"
                            persistent-hint
                          ></v-text-field>
                        </v-flex>

                        <v-flex xs6 md6>
                          <v-text-field
                            v-model="customerTel"
                            label="Customer Tel"
                            outline
                            name="customer_tel"
                            :rules="[v => !!v || 'Repair Location is required']"
                            hint="*Required"
                            persistent-hint
                          ></v-text-field>
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

            <v-flex xs12>
              <v-divider class="mb-4"></v-divider>
            </v-flex>

            <v-flex xs12>
              <v-hover>
                <!--<v-card-->
                <!--slot-scope="{ hover }"-->
                <!--:class="`elevation-${hover ? 12 : 5}`"-->
                <!--&gt;-->
                <v-form ref="submit"
                        slot-scope="{ hover }"
                        :class="`elevation-${hover ? 12 : 5}`">
                  <v-container>
                    <v-layout row wrap>
                      <v-flex xs6 md6>
                        <v-btn
                          color="success"
                          large
                          block
                          @click="submit"
                        >
                          Submit
                        </v-btn>
                      </v-flex>

                      <v-flex xs6 md6>


                        <v-btn
                          color="error"
                          large
                          block
                          @click="deleteConfirmDialog=!deleteConfirmDialog"
                        >
                          Delete
                        </v-btn>
                        <v-dialog v-model="deleteConfirmDialog" persistent max-width="300">
                          <v-card dark>
                            <v-card-title class="headline">Are you sure you want to delete form {{updatingRepairID}} ?
                            </v-card-title>
                            <v-card-actions>
                              <v-spacer></v-spacer>
                              <v-btn dark color="green" @click.native="deleteConfirmDialog = false">Cancel
                              </v-btn>
                              <v-btn dark color="red" @click.native="deleteForm(updatingRepairID)">Delete
                              </v-btn>
                            </v-card-actions>
                          </v-card>
                        </v-dialog>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-form>
                <!--</v-card>-->
              </v-hover>
            </v-flex>
          </v-card>
        </v-dialog>
      </v-layout>
    </v-container>
  </v-slide-y-transition>
</template>

<script>
  export default {
    name: 'UpdateRepairComponent',
    data: () => ({
      search: '',
      updateDialog: false,
      updatingRepairID: null,
      deleteConfirmDialog: false,
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
      trackingCode: null,
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
      dateReturnToStore: null,
      customerName: null,
      customerTel: null,
      postLoading: true,
      postLoadingDialog: false,
      postLoadingMsg: null
    }),
    mounted () {
      this.init()
    },
    methods: {
      deleteForm (repairID) {
        this.deleteConfirmDialog = false
        this.closeUpdateDialog()
        this.init()
      },
      openUpdateDialog (repairID) {
        this.updatingRepairID = repairID
        this.updateDialog = true
        this.init()
        var data = new URLSearchParams()
        data.append('request', 'getRepairForm')
        data.append('repairID', repairID)
        this.axios.post('data_manipulator.php', data).then(
          (res) => {
            var data = res.data
            this.repairForm = data.repair_form
            this.departmentSelected = data.dept_id
            this.productSelected = data.prod_code
            this.repairDetail = data.repair_details
            this.warrantySelected = {warranty_id: data.warranty_id, warranty_type: data.warranty_type}
            this.cost = data.cost
            this.proNumber = data.pro_number
            this.proDate = data.date
            this.repairLocationSelected = data.repair_loc
            this.purchaseDate = data.date_purchased
            this.receiveFromCustomer = data.date_rec_store
            this.dateArrivedCompany = data.date_arrive_company
            this.dateSentFactory = data.date_sent_factory
            this.dateReceiveFromFactory = data.date_received_factory
            this.dateReturnToStore = data.date_returned_store
            this.shippingMethodSelected = data.send_method
            this.personSent = data.person_sent
            this.trackingCode = data.tracking_code
            this.searchCustomerByTel = data.customertel
            this.customerSelected = data.cust_id
          }
        ).catch((error) => {
          console.log(error)
        })
      },
      closeUpdateDialog () {
        this.updateDialog = false
        this.updatingRepairID = null
        this.repairForm = null
        this.departmentSelected = null
        this.productSelected = null
        this.repairDetail = null
        this.warrantySelected = {}
        this.cost = null
        this.proNumber = null
        this.proDate = null
        this.repairLocationSelected = null
        this.purchaseDate = null
        this.receiveFromCustomer = null
        this.dateArrivedCompany = null
        this.dateSentFactory = null
        this.dateReceiveFromFactory = null
        this.dateReturnToStore = null
        this.shippingMethodSelected = null
        this.personSent = null
        this.trackingCode = null
        this.searchCustomerByTel = null
        this.customerSelected = null
      },
      clear () {
        this.$refs.form.reset()
        this.cost = 0
      },
      submit () {
        if (this.$refs.form.validate() && this.$refs.customer.validate()) {
          var validate = true
          if (this.$refs.receipt) {
            if (!this.$refs.receipt.validate()) {
              validate = false
            }
          }
          if (validate) {
            var data = {
              create: false,
              update: true,
              repairID: this.updatingRepairID,
              department: this.departmentSelected,
              productCode: this.productSelected,
              warranty: this.warrantySelected,
              repairLocation: this.repairLocationSelected,
              repairDetail: this.repairDetail,
              shippingMethod: this.shippingMethodSelected,
              personSent: this.personSent,
              trackingCode: this.trackingCode,
              repairForm: this.repairForm
            }
            // IF the customer is new
            if (!this.newCustomer) {
              data.newCustomer = false
              data.customer = this.customerSelected
            } else {
              data.newCustomer = true
              data.customer = {}
              data.customer.customerName = this.customerName
              data.customer.customerTel = this.customerTel
            }
            // end of new customer
            // if is in warranty
            if (this.costDisable) {
              data.newReceipt = false
              data.cost = 0
            } else {
              data.newReceipt = true
              data.cost = this.cost
              data.receipt = {}
              data.receipt.proNumber = this.proNumber.toUpperCase()
              data.receipt.date = this.proDate
            }
            // end of warranty
            // Fill dates
            data.dates = {}
            data.dates.purchaseDate = this.purchaseDate
            data.dates.receiveFromCustomer = this.receiveFromCustomer
            data.dates.dateArrivedCompany = this.dateArrivedCompany
            data.dates.dateSentFactory = this.dateSentFactory
            data.dates.receiveFromCustomer = this.receiveFromCustomer
            data.dates.dateReceiveFromFactory = this.dateReceiveFromFactory
            data.dates.dateReturnToStore = this.dateReturnToStore
            // end Filling dates
            this.postLoading = true
            this.postLoadingDialog = true
            this.postLoadingMsg = 'Updating repair form...'
            this.axios.post('data_manipulator.php', data, {disableLoading: true}).then(
              (res) => {
                console.log(res.data)
                this.postLoadingMsg = res.data.msg
              }
            ).catch((error) => {
              console.log(error)
              this.postLoadingMsg = error.data.msg
            }).finally(() => {
              this.postLoading = false
            })
          } else {
            console.log('Receipt not Validate')
          }
        } else {
          console.log('Not Validate')
        }
      },
      init () {
        var data = new URLSearchParams()
        data.append('request', 'init')
        this.axios.post('data_manipulator.php', data).then(
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

          this.axios.post('data_manipulator.php', data, {disableLoading: true})
            .then(res => {
              this.customers = res.data
            })
            .catch(err => {
              console.log(err)
            })
            .finally(() => {
              this.customersLoading = false
            })
        }
      },
      searchCustomerByTel (val) {
        if (val) {
          this.customersLoading = true
          var data = new URLSearchParams()
          data.append('request', 'customer')
          data.append('customerTel', val)

          this.axios.post('data_manipulator.php', data, {disableLoading: true})
            .then(res => {
              this.customers = res.data
            })
            .catch(err => {
              console.log(err)
            })
            .finally(() => {
              this.customersLoading = false
            })
        }
      },
      warrantySelected (val) {
        if (val) {
          if (val.warranty_type === 'in warranty') {
            this.costDisable = true
            this.cost = 0
          } else {
            this.costDisable = false
          }
        }
      }
    }
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

