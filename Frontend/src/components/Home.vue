<template>
  <v-slide-y-transition mode="out-in">
    <v-layout column align-center>
      <img src="/static/images/logo.png" class="mb-5">
      <v-divider class="mb-2"></v-divider>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card>
            <v-card-title primary class="title">Home</v-card-title>
            <v-card-text>
              You are login as "{{$store.state.userName}}"
            </v-card-text>
            <v-card-text>
              Your position is "{{$store.state.position}}"
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
      <v-divider class="mb-2"></v-divider>
      <v-layout>
      </v-layout>
      <UpdateRepairComponent ref="updateDialog" />
    </v-layout>
  </v-slide-y-transition>
</template>

<script>
  import UpdateRepairComponent from './Update_Repair_Component'
  export default {
    name: 'home',
    data: () => ({
    }),
    mounted () {
    },
    methods: {
    },
    components: {
      UpdateRepairComponent
    }
  }
</script>

<style lang="stylus">
</style>
