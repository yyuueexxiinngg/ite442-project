<template>
  <v-slide-y-transition mode="out-in">
    <v-container>
      <v-layout row wrap align-center justify-center>

        <v-flex xs12>
          <div class="mb-4"></div>
        </v-flex>

        <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
          {{ snackText }}
          <v-btn flat @click="snack = false">Close</v-btn>
        </v-snackbar>

        <v-dialog v-model="postLoadingDialog" content content-class="centered-dialog">
          <v-container fill-height>
            <v-layout column justify-center align-center>
              <v-progress-circular :indeterminate="postLoading" :value="100" :size="70" :width="7"
                                   color="primary"></v-progress-circular>
              <h1 v-if="postLoadingMsg != null">{{postLoadingMsg}}</h1>
            </v-layout>
          </v-container>
        </v-dialog>

        <v-dialog v-model="addProductDialog" content content-class="centered-dialog">

          <v-container>
            <v-layout row warp justify-center align-center>
              <v-card>
                <v-card-title>
                  <span class="headline">Add Product</span>
                  <v-progress-linear v-if="postLoading" :indeterminate="true"></v-progress-linear>
                </v-card-title>
                <v-card-text>
                  <v-form ref="addProductForm">
                    <v-container grid-list-md>
                      <v-layout wrap>
                        <v-flex xs6>
                          <v-text-field
                            v-model="addProductCode"
                            label="Product Code"
                            outline
                            name="product_code"
                            :rules="[v => !!v || 'Product Code is required']"
                          ></v-text-field>
                        </v-flex>

                        <v-flex xs6>
                          <v-text-field
                            v-model="addProductDetail"
                            label="Product Detail"
                            outline
                            name="product_code"
                            :rules="[v => !!v || 'Product Detail is required']"
                          ></v-text-field>
                        </v-flex>

                        <v-flex xs6>
                          <v-text-field
                            v-model="addProductPrice"
                            label="Product Price"
                            outline
                            name="product_code"
                            :rules="[v => !!v || 'Product Price is required']"
                          ></v-text-field>
                        </v-flex>

                        <v-flex xs6>
                          <v-text-field
                            v-model="addProductSize"
                            label="Product Size"
                            outline
                            name="product_code"
                            :rules="[v => !!v || 'Product Size is required']"
                          ></v-text-field>
                        </v-flex>

                      </v-layout>
                    </v-container>
                  </v-form>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" flat @click.native="addProductDialog = false">Close</v-btn>
                  <v-btn color="blue darken-1" flat @click.native="addProduct">Submit</v-btn>
                </v-card-actions>
              </v-card>

            </v-layout>
          </v-container>
        </v-dialog>


        <v-dialog v-model="generalAddDialog" content content-class="centered-dialog">

          <v-container>
            <v-layout row warp justify-center align-center>
              <v-card>
                <v-card-title>
                  <span class="headline">Add {{generalAddType}}</span>
                </v-card-title>
                <v-card-text>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs6>
                        <v-text-field
                          v-model="generalAddID"
                          label="ID"
                          outline
                          name="id"
                        ></v-text-field>
                      </v-flex>

                      <v-flex xs6>
                        <v-text-field
                          v-model="generalAddContent"
                          label="Content"
                          outline
                          name="content"
                        ></v-text-field>
                      </v-flex>

                    </v-layout>
                  </v-container>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" flat @click.native="generalAddDialog = false">Close</v-btn>
                  <v-btn color="blue darken-1" flat @click.native="generalAddDialog = false">Submit</v-btn>
                </v-card-actions>
              </v-card>

            </v-layout>
          </v-container>
        </v-dialog>

        <v-dialog v-model="generalDeleteDialog" content content-class="centered-dialog">

          <v-container>
            <v-layout row warp justify-center align-center>
              <v-card>
                <v-card-title>
                  <span class="headline">Delete {{generalDeleteType}}</span>
                </v-card-title>
                <v-card-text>
                  <v-container grid-list-md>
                    <v-layout wrap>
                      <v-flex xs12>
                        <v-autocomplete
                          :items="generalDeleteSelections"
                          :item-text="generalDeleteDisplayName"
                          :item-value="generalDeleteDisplayName"
                        >

                        </v-autocomplete>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" flat @click.native="generalDeleteDialog = false">Close</v-btn>
                  <v-btn color="blue darken-1" flat @click.native="doDelete">Submit</v-btn>
                </v-card-actions>
              </v-card>

            </v-layout>
          </v-container>
        </v-dialog>

        <!--Product-->
        <v-flex xs12>
          <template>
            <v-card class="elevation-5">
              <v-card-title>
                Product List
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="searchProduct"
                  append-icon="search"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
                <v-spacer></v-spacer>

                <v-btn
                  color="success"
                  @click="addProductDialog = true"
                >
                  <v-icon>add_circle_outline</v-icon>
                  Add
                </v-btn>

                <v-btn
                  color="error"
                  @click="openGeneralDeleteDialog('product','productcode')"
                >
                  <v-icon>clear</v-icon>
                  Delete
                </v-btn>


                <v-progress-linear slot="progress" color="blue" indeterminate
                                   v-if="$store.state.isLoading"></v-progress-linear>
              </v-card-title>

              <v-data-table
                :headers="productTableHeaders"
                :items="productList"
                :search="searchProduct"
              >
                <template slot="items" slot-scope="props">
                  <tr @click="props.expanded = !props.expanded">
                    <td>{{ props.item.productcode }}</td>
                    <td>
                      <v-edit-dialog
                        :return-value.sync=" props.item.description"
                        large
                        lazy
                        persistent
                        @save="save('product','productcode' ,props.item.productcode,'description',  props.item.description)"
                        @cancel="cancel"
                        @open="open(props.item.productcode)"
                        @close="close"
                      >
                        <div>{{ props.item.description }}</div>
                        <div slot="input" class="mt-3 title">Update Detail</div>
                        <v-text-field
                          slot="input"
                          v-model=" props.item.description"
                          label="Edit"
                          single-line
                          counter
                          autofocus
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                    <td>
                      <v-edit-dialog
                        :return-value.sync="props.item.price "
                        large
                        lazy
                        persistent
                        @save="save('product','productcode', props.item.productcode,'price', props.item.price)"
                        @cancel="cancel"
                        @open="open(props.item.productcode)"
                        @close="close"
                      >
                        <div>{{ props.item.price }}</div>
                        <div slot="input" class="mt-3 title">Update Price</div>
                        <v-text-field
                          slot="input"
                          v-model="props.item.price "
                          label="Edit"
                          single-line
                          counter
                          autofocus
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                    <td>
                      <v-edit-dialog
                        :return-value.sync="props.item.size "
                        large
                        lazy
                        persistent
                        @save="save('product','productcode', props.item.productcode,'size', props.item.size)"
                        @cancel="cancel"
                        @open="open(props.item.productcode)"
                        @close="close"
                      >
                        <div>{{ props.item.size }}</div>
                        <div slot="input" class="mt-3 title">Update Size</div>
                        <v-text-field
                          slot="input"
                          v-model="props.item.size "
                          label="Edit"
                          single-line
                          counter
                          autofocus
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                  </tr>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                  Your search for "{{ searchProduct }}" found no results.
                </v-alert>
              </v-data-table>


            </v-card>
          </template>
        </v-flex>

        <v-flex xs12>
          <v-spacer class="mb-5"></v-spacer>
        </v-flex>

        <!--Shipping Method-->
        <v-flex xs12>
          <template>
            <v-card class="elevation-5">
              <v-card-title>
                Shipping Method List
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="searchShippingMethod"
                  append-icon="search"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
                <v-spacer></v-spacer>

                <v-btn
                  color="success"
                  @click="addGeneral('shipping_method')"
                >
                  <v-icon>add_circle_outline</v-icon>
                  Add
                </v-btn>
                <v-btn
                  color="error"
                  @click="openGeneralDeleteDialog('product','productcode')"
                >
                  <v-icon>clear</v-icon>
                  Delete
                </v-btn>

                <v-progress-linear slot="progress" color="blue" indeterminate
                                   v-if="$store.state.isLoading"></v-progress-linear>
              </v-card-title>

              <v-data-table
                :headers="shippingMethodTableHeaders"
                :items="shippingMethodList"
                :search="searchShippingMethod"
              >
                <template slot="items" slot-scope="props">
                  <tr @click="props.expanded = !props.expanded">
                    <td>{{ props.item.ship_method_id }}</td>
                    <td>
                      <v-edit-dialog
                        :return-value.sync=" props.item.Method"
                        large
                        lazy
                        persistent
                        @save="save('shipping_method','ship_method_id', props.item.ship_method_id,'Method',  props.item.Method)"
                        @cancel="cancel"
                        @open="open(props.item.ship_method_id)"
                        @close="close"
                      >
                        <div>{{ props.item.Method }}</div>
                        <div slot="input" class="mt-3 title">Update Shipping Method</div>
                        <v-text-field
                          slot="input"
                          v-model=" props.item.Method"
                          label="Edit"
                          single-line
                          counter
                          autofocus
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                  </tr>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                  Your search for "{{ searchShippingMethod }}" found no results.
                </v-alert>
              </v-data-table>


            </v-card>
          </template>
        </v-flex>

        <v-flex xs12>
          <v-spacer class="mb-5"></v-spacer>
        </v-flex>

        <!--Department-->
        <v-flex xs12>
          <template>
            <v-card class="elevation-5">
              <v-card-title>
                Department List
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="searchDepartment"
                  append-icon="search"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
                <v-spacer></v-spacer>

                <v-btn
                  color="success"
                  @click="addGeneral('department')"
                >
                  <v-icon>add_circle_outline</v-icon>
                  Add
                </v-btn>

                <v-btn
                  color="error"
                  @click="openGeneralDeleteDialog('department','department_id')"
                >
                  <v-icon>clear</v-icon>
                  Delete
                </v-btn>

                <v-progress-linear slot="progress" color="blue" indeterminate
                                   v-if="$store.state.isLoading"></v-progress-linear>
              </v-card-title>

              <v-data-table
                :headers="departmentTableHeaders"
                :items="departmentList"
                :search="searchDepartment"
              >
                <template slot="items" slot-scope="props">
                  <tr @click="props.expanded = !props.expanded">
                    <td>{{ props.item.department_id }}</td>
                    <td>
                      <v-edit-dialog
                        :return-value.sync=" props.item.deptname"
                        large
                        lazy
                        persistent
                        @save="save('department', department_id,props.item.department_id,'deptname',  props.item.deptname)"
                        @cancel="cancel"
                        @open="open(props.item.department_id)"
                        @close="close"
                      >
                        <div>{{ props.item.deptname }}</div>
                        <div slot="input" class="mt-3 title">Update Department Name</div>
                        <v-text-field
                          slot="input"
                          v-model=" props.item.deptname"
                          label="Edit"
                          single-line
                          counter
                          autofocus
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                    <td>
                      <v-edit-dialog
                        :return-value.sync="props.item.deptinfo "
                        large
                        lazy
                        persistent
                        @save="save('department','department_id',  props.item.department_id,'deptinfo', props.item.deptinfo)"
                        @cancel="cancel"
                        @open="open(props.item.department_id)"
                        @close="close"
                      >
                        <div>{{ props.item.deptinfo }}</div>
                        <div slot="input" class="mt-3 title">Update Department Info</div>
                        <v-text-field
                          slot="input"
                          v-model="props.item.deptinfo "
                          label="Edit"
                          single-line
                          counter
                          autofocus
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                  </tr>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                  Your search for "{{ searchDepartment }}" found no results.
                </v-alert>
              </v-data-table>


            </v-card>
          </template>
        </v-flex>

        <v-flex xs12>
          <v-spacer class="mb-5"></v-spacer>
        </v-flex>


        <!--Repair Location-->
        <v-flex xs12>
          <template>
            <v-card class="elevation-5">
              <v-card-title>
                Repair Location List
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="searchRepairLocation"
                  append-icon="search"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
                <v-spacer></v-spacer>

                <v-btn
                  color="success"
                  @click="addGeneral('repair_location')"
                >
                  <v-icon>add_circle_outline</v-icon>
                  Add
                </v-btn>

                <v-btn
                  color="error"
                  @click="openGeneralDeleteDialog('repairLocation','repair_loc_id')"
                >
                  <v-icon>clear</v-icon>
                  Delete
                </v-btn>

                <v-progress-linear slot="progress" color="blue" indeterminate
                                   v-if="$store.state.isLoading"></v-progress-linear>
              </v-card-title>

              <v-data-table
                :headers="repairLocationTableHeaders"
                :items="repairLocationList"
                :search="searchRepairLocation"
              >
                <template slot="items" slot-scope="props">
                  <tr @click="props.expanded = !props.expanded">
                    <td>{{ props.item.repair_loc_id }}</td>
                    <td>
                      <v-edit-dialog
                        :return-value.sync=" props.item.Address"
                        large
                        lazy
                        persistent
                        @save="save('repair_location','repair_loc_id', props.item.repair_loc_id,'Address',  props.item.Address)"
                        @cancel="cancel"
                        @open="open(props.item.repair_loc_id)"
                        @close="close"
                      >
                        <div>{{ props.item.Address }}</div>
                        <div slot="input" class="mt-3 title">Update Location Address</div>
                        <v-text-field
                          slot="input"
                          v-model=" props.item.Address"
                          label="Edit"
                          single-line
                          counter
                          autofocus
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                  </tr>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                  Your search for "{{ searchRepairLocation }}" found no results.
                </v-alert>
              </v-data-table>


            </v-card>
          </template>
        </v-flex>

        <v-flex xs12>
          <v-spacer class="mb-5"></v-spacer>
        </v-flex>

        <!--Warranty-->
        <v-flex xs12>
          <template>
            <v-card class="elevation-5">
              <v-card-title>
                Warranty List
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="searchWarranty"
                  append-icon="search"
                  label="Search"
                  single-line
                  hide-details
                ></v-text-field>
                <v-spacer></v-spacer>

                <v-progress-linear slot="progress" color="blue" indeterminate
                                   v-if="$store.state.isLoading"></v-progress-linear>
              </v-card-title>

              <v-data-table
                :headers="warrantyTableHeaders"
                :items="warrantyList"
                :search="searchWarranty"
              >
                <template slot="items" slot-scope="props">
                  <tr @click="props.expanded = !props.expanded">
                    <td>{{ props.item.warranty_id }}</td>
                    <td>
                      <v-edit-dialog
                        :return-value.sync=" props.item.warranty_type"
                        large
                        lazy
                        persistent
                        @save="save('warranty','warranty_id', props.item.warranty_id,'warranty_type',  props.item.warranty_type)"
                        @cancel="cancel"
                        @open="open(props.item.warranty_id)"
                        @close="close"
                      >
                        <div>{{ props.item.warranty_type }}</div>
                        <div slot="input" class="mt-3 title">Update Warranty type</div>
                        <v-text-field
                          slot="input"
                          v-model=" props.item.warranty_type"
                          label="Edit"
                          single-line
                          counter
                          autofocus
                        ></v-text-field>
                      </v-edit-dialog>
                    </td>
                  </tr>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                  Your search for "{{ searchWarranty }}" found no results.
                </v-alert>
              </v-data-table>


            </v-card>
          </template>
        </v-flex>

      </v-layout>

    </v-container>
  </v-slide-y-transition>
</template>

<script>
  import UpdateRepairComponent from './Update_Repair_Component'

  export default {
    name: 'manage_stock',
    components: {
      UpdateRepairComponent
    },
    data: () => ({
      snack: false,
      snackColor: '',
      snackText: '',
      searchProduct: '',
      searchDepartment: '',
      searchWarranty: '',
      searchRepairLocation: '',
      searchShippingMethod: '',
      productTableHeaders: [
        {text: 'Product Cose', align: 'left', value: 'productcode'},
        {text: 'Detail', value: 'description'},
        {text: 'Price', value: 'price'},
        {text: 'Size', value: 'size'}
      ],
      departmentTableHeaders: [
        {text: 'Department ID', align: 'left', value: 'department_id'},
        {text: 'Department Name', value: 'deptname'},
        {text: 'Department Info', value: 'deptinfo'}
      ],
      warrantyTableHeaders: [
        {text: 'Warranty ID', align: 'left', value: 'warranty_id'},
        {text: 'Warranty Type', value: 'warranty_type'}
      ],
      repairLocationTableHeaders: [
        {text: 'Repair Location ID', align: 'left', value: 'repair_loc_id'},
        {text: 'Address', value: 'Address'}
      ],
      shippingMethodTableHeaders: [
        {text: 'Shipping Method ID', align: 'left', value: 'ship_method_id'},
        {text: 'Method', value: 'Method'}
      ],
      inProgressList: [],
      productList: [],
      postLoading: false,
      postLoadingDialog: false,
      postLoadingMsg: null,
      addProductDialog: false,
      addProductCode: null,
      addProductDetail: null,
      addProductPrice: null,
      addProductSize: null,
      generalAddDialog: false,
      generalAddType: null,
      generalAddID: null,
      generalAddContent: null,
      generalDeleteDialog: false,
      generalDeleteSelections: [],
      generalDeleteType: null,
      generalDeleteID: null,
      generalDeleteDisplayName: null,
      warrantyList: [],
      departmentList: [],
      repairLocationList: [],
      shippingMethodList: []
    }),
    mounted () {
      this.init()
    },
    methods: {
      init () {
        var data = new URLSearchParams()
        data.append('request', 'manageInit')
        this.axios.post('data_manipulator.php', data).then(
          (res) => {
            var data = res.data
            this.productList = data.productList
            this.warrantyList = data.warrantyList
            this.departmentList = data.departmentList
            this.repairLocationList = data.repairLocationList
            this.shippingMethodList = data.shippingMethodList
          }
        ).catch((error) => {
          console.log(error)
        })
      },
      addGeneral (type) {
        this.generalAddType = type
        this.generalAddDialog = true
      },
      addProduct () {
        if (this.$refs.addProductForm.validate()) {
          this.addProductDialog = false
          this.postLoading = true
          var data = {
            create: false,
            update: false,
            delete: false,
            manageUpdate: false,
            manageInsert: false,
            addProduct: true,
            productCode: this.addProductCode,
            productDetail: this.addProductDetail,
            productPrice: this.addProductPrice,
            productSize: this.addProductSize
          }
          this.axios.post('data_manipulator.php', data).then(
            (res) => {
              var data = res.data
              this.postLoadingMsg = (data.msg)
              this.addProductDialog = false
              this.postLoading = false
            }
          ).catch((error) => {
            console.log(error)
          })
        }
      },
      doDelete () {
        this.generalDeleteDialog = false
        var data = {
          create: false,
          update: false,
          delete: false,
          manageUpdate: false,
          manageInsert: false,
          addProduct: true,
          generalDelete: true,
          deleteType: this.generalDeleteType,
          deleteID: this.generalDeleteID
        }
        concosle.log(dat)
        this.axios.post('data_manipulator.php', data).then(
          (res) => {
            var data = res.data
            this.postLoadingMsg = (data.msg)
            this.addProductDialog = false
            this.postLoading = false
          }
        ).catch((error) => {
          console.log(error)
        })
      },
      openGeneralDeleteDialog (type, displayName) {
        this.generalDeleteDisplayName = null
        this.generalDeleteSelections = []
        this.generalDeleteType = type
        if (type === 'product') {
          this.generalDeleteSelections = this.productList
        } else if (type === 'department') {
          this.generalDeleteSelections = this.departmentList
        } else if (type === 'warranty') {
          this.generalDeleteSelections = this.warrantyList
        } else if (type === 'repairLocation') {
          this.generalDeleteSelections = this.repairLocationList
        } else if (type === 'shippingMethod') {
          this.generalDeleteSelections = this.shippingMethodList
        }
        this.generalDeleteDialog = true
        this.generalDeleteDisplayName = displayName
      },
      save (type, idName, id, attr, val) {
        this.postLoading = true
        this.postLoadingDialog = true
        this.postLoadingMsg = 'Saving'
        var data = {
          create: false,
          update: false,
          delete: false,
          manageUpdate: true,
          manageInsert: false,
          type: type,
          idName: idName,
          id: id,
          attr: attr,
          content: val
        }
        this.axios.post('data_manipulator.php', data).then(
          (res) => {
            var data = res.data
            this.postLoadingMsg = (data.msg)
            this.postLoading = false
          }
        ).catch((error) => {
          console.log(error)
        })
      },
      cancel () {
      },
      open (val) {
        this.snack = true
        this.snackColor = 'info'
        this.snackText = 'Editing ' + val
      },
      close () {
      }
    },
    watch: {
      postLoadingDialog (val) {
        if (val === false) {
          this.init()
        }
      }
    }
  }
</script>

<style>
  .dialog.centered-dialog,
  .v-dialog.centered-dialog {
    background: rgba(255, 255, 255, 1);
    box-shadow: none;
    border-radius: 6px;
    width: auto;
  }
</style>
