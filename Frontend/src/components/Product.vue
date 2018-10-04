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
        <v-layout row wrap justify-center>
          <v-flex xs12>
            <div class="mb-4"></div>
          </v-flex>

          <v-flex xs12>
            <template>
              <v-card class="elevation-5">
                <v-card-title>
                  Repair Form List
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
                  :items="repairFormList"
                  :search="search"
                >
                  <template slot="items" slot-scope="props">
                    <tr @click="openUpdateDialog(props.item.repair_id)">
                      <td>{{ props.item.repair_id }}</td>
                      <td>{{ props.item.repair_form }}</td>
                      <td>{{ props.item.customername }}</td>
                      <td>{{ props.item.customertel }}</td>
                      <td>{{ props.item.cost }}</td>
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
  export default {
    name: 'Product'
  }
</script>

<style scoped>

</style>
