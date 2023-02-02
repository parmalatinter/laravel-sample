<template>
    <v-app>
        <v-app-bar
            app
            color="green"
            flat
        >
            <v-app-bar-nav-icon variant="text" @click.stop="miniVariant = !miniVariant"></v-app-bar-nav-icon>
            <v-toolbar-title>Vuetify</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu
                offset-y
                offset-x
                left
                min-width="100px"
                nudge-top="-20px"
                nudge-right="40px"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        icon
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item>
                        <v-list-item-title>
                            <v-form
                                ref="form"
                                :action="routes.logout.uri"
                                :method="routes.logout.methods[0]"
                            >
                                <v-text-field
                                    name="_token"
                                    :value="csrfToken"
                                    v-show="false"
                                ></v-text-field>
                                <v-btn
                                    icon
                                    type="submit"
                                    plain
                                    block
                                >
                                    Logout
                                </v-btn>
                            </v-form>
                        </v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>

        </v-app-bar>

        <v-navigation-drawer
            v-model="drawer"
            permanent
            :mini-variant.sync="miniVariant"
            app
            flat
            color="green lighten-3"
        >

            <v-list>
                <v-list-item class="px-2">
                    <v-list-item-avatar size="34px" >
                        <v-img src="https://randomuser.me/api/portraits/women/85.jpg"></v-img>
                    </v-list-item-avatar>
                </v-list-item>
            </v-list>
            <v-divider></v-divider>
            <v-list>
                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title>
                            {{ user.name }}
                        </v-list-item-title>
                        <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
            <v-divider></v-divider>
            <v-list>
                <v-list-item
                    link
                    exact
                    v-for="(item, i) in items"
                    :key=i
                    :to=item.to
                >
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title v-text="item.title"></v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <v-container>
                <v-row>
                    <v-col
                        cols="12"
                        lg="1"
                        class="d-none d-lg-block d-xl-block"
                    >
                        <v-sheet
                            rounded="lg"
                            min-height="70vh"
                        >
                            <!--  -->
                        </v-sheet>
                    </v-col>
                    <v-col
                        cols="12"
                        md="12"
                        lg="10"
                    >
                        <v-sheet
                            rounded="lg"
                            min-height="70vh"
                        >
                            <v-snackbar
                                v-model="snackbar"
                            >
                                {{ text }}

                                <template v-slot:action="{ attrs }">
                                    <v-btn
                                        color="pink"
                                        text
                                        v-bind="attrs"
                                        @click="snackbar = false"
                                    >
                                        Close
                                    </v-btn>
                                </template>
                            </v-snackbar>
                            <router-view></router-view>
                        </v-sheet>
                    </v-col>
                    <v-col
                        cols="12"
                        lg="1"
                        class="d-none d-lg-block d-xl-block"
                    >
                        <v-sheet
                            min-height="70vh"
                            rounded="lg"
                        >
                            <!--  -->
                        </v-sheet>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>

    </v-app>
</template>

<script>

export default {
    data: () => ({
        routes : window.routes,
        user : window.user,
        snackbar: true,
        csrfToken: window.csrfToken,
        text: `You are logged in !`,
        drawer: true,
        miniVariant: true,
        items : [
            {
                title: 'Home',
                to: '/',
                icon: 'mdi-home'
            },
            {
                title: 'Account Setting',
                to: '/account',
                icon: 'mdi-account-cog'
            },
            {
                title: 'Datatables',
                to: '/datatables',
                icon: 'mdi-table'
            },
        ]
    }),
}
</script>

<style scoped>
html {
    overflow: auto !important;
}
</style>
