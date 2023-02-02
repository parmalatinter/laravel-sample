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
                            <form :action="routes.login.uri" method="post" >
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
                                    ></v-text-field>
                                    <v-text-field
                                        id="password"
                                        prepend-icon="mdi-lock"
                                        name="password"
                                        label="Password"
                                        type="password"
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
                                    <v-btn type="submit" color="primary">Login</v-btn>
                                </v-card-actions>
                            </form>
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
            name: 'Login',
        }),
        props: {
            source: String,
        },
    }
</script>
