<template>
  <v-slide-y-transition mode="out-in">
    <v-container>
      <v-layout row wrap align-center justify-center>
        <v-layout row wrap justify-center>
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

              </v-layout>
            </v-container>
          </v-dialog>

          <v-dialog v-model="addProductDialog" content content-class="centered-dialog">
            <v-container fill-height>
              <v-layout column justify-center align-center>
                <v-progress-circular :indeterminate="postLoading" :value="100" :size="70" :width="7"
                                     color="primary"></v-progress-circular>
                <h1 v-if="postLoadingMsg != null">{{postLoadingMsg}}</h1>
              </v-layout>
            </v-container>
          </v-dialog>

          <v-flex xs12>
            <template>
              <v-card class="elevation-5">
                <v-card-title>
                  Product List
                  <v-spacer></v-spacer>
                  <v-text-field
                    v-model="search"
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
                    Add Product
                  </v-btn>

                  <v-progress-linear slot="progress" color="blue" indeterminate
                                     v-if="$store.state.isLoading"></v-progress-linear>
                </v-card-title>

                <v-data-table
                  :headers="headers"
                  :items="productList"
                  :search="search"
                >
                  <template slot="items" slot-scope="props">
                    <tr  @click="props.expanded = !props.expanded">
                      <td>{{ props.item.productcode }}</td>
                      <td>
                        <v-edit-dialog
                          :return-value.sync=" props.item.description"
                          large
                          lazy
                          persistent
                          @save="save('product', props.item.productcode,'description',  props.item.description)"
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
                          @save="save('product', props.item.productcode,'price', props.item.price)"
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
                          @save="save('product', props.item.productcode,'size', props.item.size)"
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
                    Your search for "{{ search }}" found no results.
                  </v-alert>
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
    name: 'manage_stock',
    components: {
      UpdateRepairComponent
    },
    data: () => ({
      snack: false,
      snackColor: '',
      snackText: '',
      search: '',
      headers: [
        {text: 'Product Cose', align: 'left', value: 'productcode'},
        {text: 'Detail', value: 'description'},
        {text: 'Prive', value: 'price'},
        {text: 'Size', value: 'size'}
      ],
      inProgressList: [],
      productList: [],
      postLoading: true,
      postLoadingDialog: false,
      postLoadingMsg: null,
      addProductDialog: false
    }),
    mounted () {
      this.init()
    },
    methods: {
      init () {
        var data = new URLSearchParams()
        data.append('request', 'getProductList')
        this.axios.post('data_manipulator.php', data).then(
          (res) => {
            var data = res.data
            this.productList = data
          }
        ).catch((error) => {
          console.log(error)
        })
      },
      save (type, id, attr, val) {
        this.postLoadingDialog = true
        this.postLoadingMsg = type + id + attr + val
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
