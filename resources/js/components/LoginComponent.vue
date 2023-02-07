<template>
    <v-app id="inspire">
        <v-parallax
            height="100vh"
            src="https://cdn.vuetifyjs.com/images/parallax/material2.jpg"
        >
            <v-main>
                <v-container fluid fill-height>
                    <v-layout align-center justify-center>
                        <v-flex xs12 sm8 md4>
                            <v-card class="elevation-12">
                                <v-toolbar dark color="primary">
                                    <v-toolbar-title>{{title}}</v-toolbar-title>
                                </v-toolbar>
                                <v-form
                                    ref="form"
                                    v-model="valid"
                                    lazy-validation
                                    :action="uri"
                                    method="post"
                                >
                                    <v-card-text>
                                        <v-item v-if="!!olds.email">
                                            <p class="red--text">{{ failedMessage }}</p>
                                        </v-item>
                                        <v-item v-if="explainMessage">
                                            <p>{{ explainMessage }}</p>
                                        </v-item>
                                        <v-text-field
                                            name="_token"
                                            :value="csrfToken"
                                            v-show="false"
                                        ></v-text-field>
                                        <v-text-field
                                            v-if="isRegister && params.action !== 'passwordReset' && params.action !== 'login'"
                                            prepend-icon="mdi-account"
                                            name="name"
                                            label="Name"
                                            type="text"
                                            :value="olds.name"
                                            v-model="name"
                                            :counter="10"
                                            :rules="nameRules"
                                            required
                                        ></v-text-field>
                                        <v-text-field
                                            prepend-icon="mdi-email"
                                            name="email"
                                            label="Email"
                                            type="text"
                                            :value="olds.email"
                                            v-model="email"
                                            :counter="30"
                                            :rules="emailRules"
                                            required
                                        ></v-text-field>
                                        <v-text-field
                                            v-if="params.action !== 'passwordReset'"
                                            id="password"
                                            prepend-icon="mdi-lock"
                                            name="password"
                                            label="Password"
                                            type="password"
                                            v-model="password"
                                            :counter="10"
                                            :rules="passwordRules"
                                            required
                                        ></v-text-field>
                                        <v-checkbox
                                            v-if="params.action === 'login'"
                                            name="remember"
                                            label="Remember Me"
                                            value="1"
                                        ></v-checkbox>
                                        <v-spacer v-if="params.action === 'login'">
                                            <p>
                                                <a :href="routes.passwordRequest.uri" class="text-decoration-none">I forgot my password</a>
                                            </p>
                                            <p>
                                                <a :href="routes.register.uri" class="text-decoration-none">Register a new membership</a>
                                            </p>
                                        </v-spacer>

                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            :disabled="!valid"
                                            @click="validate"
                                            type="submit"
                                            color="primary"
                                        >
                                            {{ btnTitle }}
                                        </v-btn>

                                    </v-card-actions>
                                </v-form>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-main>
        </v-parallax>
    </v-app>
</template>

<script>
export default {
    mounted() {
        switch (window.params.action){
            case 'register':
                this.uri = window.routes.register.uri
                this.title = 'Register Form'
                this.faildMessage = 'Register Failed'
                this.btnTitle = 'Register'
                break
            case 'login':
                this.uri = window.routes.login.uri
                this.title = 'Login Form'
                this.failedMessage = 'Login Failed'
                this.btnTitle = 'Login'
                break
            case 'passwordReset':
                this.uri = window.routes.passwordRequest.uri
                this.title = 'Reset Password'
                this.failedMessage = 'Reset Password Failed'
                this.btnTitle = 'Reset Password'
                this.explainMessage = 'You are only one step a way from your new password, recover your password now.'
                break
        }
    },
    data: () => ({
        routes : window.routes,
        uri : '',
        title : '',
        btnTitle : '',
        failedMessage : '',
        explainMessage : '',
        csrfToken: window.csrfToken,
        olds: window.olds,
        params: window.params,
        valid: true,
        name: window.olds ?  window.olds.name : '',
        nameRules: [
            v => !!v || 'Name is required',
            v => (v && v.length <= 10) || 'Name must be less than 10 characters',
        ],
        email: window.olds ?  window.olds.email : '',
        emailRules: [
            v => !!v || 'Email is required',
            v => (v && v.length <= 30) || 'Name must be less than 30 characters',
        ],
        password: '',
        passwordRules: [
            v => !!v || 'Password is required',
            v => (v && v.length <= 10) || 'Name must be less than 10 characters',
        ],
        isRegister: true
    }),
    props: {
        source: String,
    },
    methods: {
        validate () {
            this.$refs.form.validate()
        },
        reset () {
            this.$refs.form.reset()
        },
        resetValidation () {
            this.$refs.form.resetValidation()
        },
    },
}
</script>
