<template>
  <v-slide-y-transition mode="out-in">
    <v-layout column align-center>
      <img src="/static/images/logo.png" alt="西城飘雪" class="mb-5">
      <v-divider class="mb-2"></v-divider>
      <v-layout row wrap>
        <v-flex xs12>
          <v-card>
            <v-card-title primary class="title">Home</v-card-title>
            <v-card-text>
              Hello world!
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
      <v-divider class="mb-2"></v-divider>
      <v-layout>
      </v-layout>
    </v-layout>
  </v-slide-y-transition>
</template>

<script>
  export default {
    name: 'home',
    data: () => ({
      banners: []
    }),
    mounted () {
    },
    methods: {
    }
  }
</script>

<style lang="stylus">
</style>
