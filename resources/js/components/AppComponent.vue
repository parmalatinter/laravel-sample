<template>
    <v-app>
        <v-app-bar
            color="green"
            dense
            elevation="4"
        >
            <v-app-bar-nav-icon variant="text" @click.stop="miniVariant = !miniVariant"></v-app-bar-nav-icon>
            <v-toolbar-title>Vuetify</v-toolbar-title>
        </v-app-bar>

        <v-layout fill-height>
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
        <v-navigation-drawer
            v-model="drawer"
            permanent
            :mini-variant.sync="miniVariant"
        >
            <v-list>
                <v-list-item class="px-2">
                    <v-list-item-avatar>
                        <v-img src="https://randomuser.me/api/portraits/women/85.jpg"></v-img>
                    </v-list-item-avatar>
                </v-list-item>

                <v-list-item link>
                    <v-list-item-content>
                        <v-list-item-title class="text-h6">
                            Sandra Adams
                        </v-list-item-title>
                        <v-list-item-subtitle>sandra_a88@gmail.com</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list
                nav
                dense
            >
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
            <router-view></router-view>
        </v-main>
        </v-layout>

    </v-app>
</template>

<script>

export default {
    data: () => ({
        routes : window.routes,
        snackbar: true,
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
                title: 'About',
                to: '/about',
                icon: 'mdi-text-box-edit'
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
