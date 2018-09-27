<template>
  <v-app id="app">
    <v-navigation-drawer :clipped="clipped" v-model="drawer" enable-resize-watcher fixed app>
      <v-toolbar
        color="white"
        flat>
        <v-list>
          <v-list-tile>
            <v-list-tile-title class="title">
              Navigation
            </v-list-tile-title>
          </v-list-tile>
        </v-list>
      </v-toolbar>
      <v-divider></v-divider>
      <v-list dense class="pt-0">
        <v-list-tile v-for="item in items" :key="item.title" @click="redirect(item.route)" v-if="checkAuth(item)">
          <v-list-tile-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <v-toolbar
      fixed
      app
      dark
      :clipped-left="clipped"
      color="primary"
    >
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
      <v-toolbar-title v-text="title" class="white--text"></v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn flat @click="logFunc">{{this.$store.state.token ? 'LOGOUT' : 'LOGIN'}}</v-btn>
    </v-toolbar>
    <main>
      <v-content>
        <v-container fluid>
          <router-view></router-view>
        </v-container>
      </v-content>
    </main>
    <div class="text-xs-center">
      <v-dialog
        v-model="logoutDialog"
        width="500"
      >
        <v-card>
          <v-card-title
            class="headline grey lighten-2"
            primary-title
          >
            A
          </v-card-title>

          <v-card-text>
            Are you sure you want to logout?
          </v-card-text>

          <v-divider></v-divider>
        </v-card>
      </v-dialog>
    </div>

    <v-footer color="white" height="auto" inset absolute app>
      <v-container
        grey
        darken-3
        fluid
        grid-list-md
      >
        <v-layout
          justify-center
          row
          wrap
        >
          <v-flex
            grey
            darken-3
            py-3
            text-xs-center
            white--text
            xs12
          >
            &copy;2018 — STIU
          </v-flex>
        </v-layout>
      </v-container>
    </v-footer>


  </v-app>
</template>

<script>
  export default {
    data () {
      return {
        clipped: true,
        drawer: false, // Auto open the navigation drawer
        fixed: true,
        items: [],
        miniVariant: false,
        right: true,
        rightDrawer: false,
        title: 'DB Project',
        networkStatus: null,
        logoutDialog: false
      }
    },
    created () {
    },
    methods: {
      redirect (routeName) {
        this.$router.push({name: routeName})
      },
      logFunc () {
        if (this.$store.state.token !== '' && this.$store.state.token !== null) {
          this.$store.commit('REMOVE_COUNT', this.$store.state.token)
          this.redirect('login')
        } else {
          this.redirect('login')
        }
      },
      checkAuth (route) {
        let permissionRequired = route.permission
        let position = this.$store.state.position
        if (!permissionRequired) {
          return true
        } else if (permissionRequired === 'admin' && position === 'admin') {
          return true
        } else if (permissionRequired === 'manager' && (position === 'admin' || position === 'manager')) {
          return true
        } else if (permissionRequired === 'employee' && (position === 'admin' || position === 'manager' || position === 'employee')) {
          return true
        } else {
          return false
        }
      }
    },
    mounted () {
      this.$router.options.routes.forEach(route => {
        if (!route.meta.notInNav) {
          this.items.push({
            title: route.meta.title,
            icon: route.meta.icon,
            route: route.name,
            permission: route.meta.requireAuth
          })
        }
      })
    }
  }
</script>

<style lang="stylus">
  @import './stylus/main'
  #app
    background-color #fff;

  .v-footer
    ul
      list-style-type none
      margin 0
      padding 0
      color #E0E0E0
    a:link, a:visited
      text-decoration none
      color #E0E0E0

  .QRCode_body
    color #999
</style>
