<template>
    <v-app id="inspire">
        <v-main>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="primary">
                                <v-toolbar-title>Login form</v-toolbar-title>
                            </v-toolbar>
                            <v-form
                                ref="form"
                                v-model="valid"
                                lazy-validation
                                :action="routes.login.uri"
                                method="post"
                            >
                                <v-card-text>
                                    <v-item v-if="!!olds.email">
                                        <p class="red--text">Login Failed</p>
                                    </v-item>
                                    <v-text-field
                                        name="_token"
                                        :value="csrfToken"
                                        v-show="false"
                                    ></v-text-field>
                                    <v-text-field
                                        prepend-icon="mdi-email"
                                        name="email"
                                        label="Email"
                                        type="text"
                                        :value="olds.email"
                                        v-model="email"
                                        :counter="10"
                                        :rules="emailRules"
                                        required
                                    ></v-text-field>
                                    <v-text-field
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
                                        name="remember"
                                        label="Remember Me"
                                        value="1"
                                    ></v-checkbox>
                                    <p>
                                        <a :href="routes.passwordRequest.uri" class="text-decoration-none">I forgot my password</a>
                                    </p>
                                    <p>
                                        <a :href="routes.register.uri" class="text-decoration-none">Register a new membership</a>
                                    </p>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        :disabled="!valid"
                                        @click="validate"
                                        type="submit"
                                        color="primary"
                                    >
                                        Login
                                    </v-btn>

                                </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
    export default {
        data: () => ({
            routes : window.routes,
            csrfToken: window.csrfToken,
            olds: window.olds,
            valid: true,
            email: window.olds ?  window.olds.email : '',
            emailRules: [
                v => !!v || 'Email is required',
                v => (v && v.length <= 10) || 'Name must be less than 10 characters',
            ],
            password: '',
            passwordRules: [
                v => !!v || 'Email is required',
                v => (v && v.length <= 10) || 'Name must be less than 10 characters',
            ],
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
