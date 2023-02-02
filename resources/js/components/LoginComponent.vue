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
                                    <v-text-field
                                        name="_token"
                                        :value="csrfToken"
                                        type="hidden"
                                    ></v-text-field>
                                    <v-text-field
                                        prepend-icon="mdi-email"
                                        name="email"
                                        label="Email"
                                        type="text"
                                    ></v-text-field>
                                    <v-text-field
                                        id="password"
                                        prepend-icon="mdi-lock"
                                        name="password"
                                        label="Password"
                                        type="password"
                                    ></v-text-field>
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    export default {
        data: () => ({
            routes : window.routes,
            name: 'Login',
            csrfToken: csrfToken
        }),
        props: {
            source: String,
        },
    }
</script>
