<template>
  <v-slide-y-transition mode="out-in">
    <v-layout>
      <v-layout row wrap justify-center>
        <v-flex xs8 md6 offset-xs2 offset-md3>
          <v-img
            :src="`https://via.placeholder.com/350x350`"
            :lazy-src="`https://via.placeholder.com/50x50`"
            aspect-ratio="1"
            max-height="250"
            max-width="250"
            contain
            class="grey lighten-2"
          >
            <v-layout
              slot="placeholder"
              fill-height
              align-center
              justify-center
              ma-0
            >
              <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
            </v-layout>
          </v-img>
        </v-flex>
        <v-flex xs8 md6>
          <v-alert
            :value="true"
            color="error"
            icon="warning"
            outline
            v-if="$store.state.generalAlert"
          >
            {{$store.state.generalAlert}}
          </v-alert>
          <v-form ref="form" v-model="valid">
            <v-text-field
              v-model="username"
              :rules="usernameRules"
              label="Username"
              required
              :error-messages="usernameError"
            ></v-text-field>
            <v-text-field
              v-model="password"
              :rules="passwordRules"
              :append-icon="showPassword ? 'visibility_off' : 'visibility'"
              :type="showPassword ? 'text' : 'password'"
              @click:append="showPassword = !showPassword"
              label="Password"
              required
              :error-messages="passwordError"
            ></v-text-field>
            <v-btn
              :disabled="!valid"
              color="success"
              @click="submit"
            >
              LOGIN
            </v-btn>
            <v-btn @click="clear">clear</v-btn>
            <v-progress-linear :indeterminate="$store.state.isLoading" v-if="$store.state.isLoading"></v-progress-linear>
          </v-form>
        </v-flex>
      </v-layout>
    </v-layout>
  </v-slide-y-transition>
</template>

<script>
  export default {
    name: 'home',
    data: () => ({
      valid: false,
      username: '',
      usernameError: '',
      usernameRules: [
        v => !!v || 'Username is required'
      ],
      password: '',
      passwordError: '',
      passwordRules: [
        v => !!v || 'Password is required'
      ],
      showPassword: false,
      errorMsg: '',
      loading: false
    }),
    mounted () {
    },
    watch: {
      username: function (newVar, oldVar) {
        this.usernameError = ''
      },
      password: function (newVar, oldVar) {
        this.passwordError = ''
      }
    },
    methods: {
      clear () {
        this.$refs.form.reset()
        this.usernameError = ''
        this.passwordError = ''
      },
      submit () {
        if (this.$refs.form.validate()) {
          var data = new URLSearchParams()
          data.append('username', this.username)
          data.append('password', this.password)
          this.axios.post('login.php', data).then(
            (res) => {
              var data = res.data
              if (data.status === 'error') {
                if (data.errorType === '0') {
                  this.usernameError = data.msg
                } else if (data.errorType === '1') {
                  this.passwordError = data.msg
                }
              } else if (data.status === 'success') {
                var user = {}
                user.userName = data.userInfo.username
                user.email = data.userInfo.email
                user.position = data.userInfo.position
                user.token = data.token
                this.$store.commit('ADD_COUNT', user)
                let redirect = decodeURIComponent(this.$route.query.redirect || '/')
                this.$router.push({
                  path: redirect
                })
              }
            }
          ).catch((error) => {
            console.log(error)
          })
        }
      }
    }
  }
</script>

<style lang="stylus">
</style>
