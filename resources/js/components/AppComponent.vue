<template>
    <v-app>
        <v-app-bar
            color="green"
            dense
            elevation="4"
        >
            <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>Vuetify</v-toolbar-title>
        </v-app-bar>

        <v-layout fill-height>
        <v-navigation-drawer
            permanent
            expand-on-hover
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
                <v-list-item link>
                    <v-list-item-icon>
                        <v-icon>mdi-folder</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>My Files</v-list-item-title>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-icon>
                        <v-icon>mdi-account-multiple</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Shared with me</v-list-item-title>
                </v-list-item>
                <v-list-item link>
                    <v-list-item-icon>
                        <v-icon>mdi-star</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title>Starred</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

<!--        <v-navigation-drawer-->
<!--            v-model="drawer"-->
<!--            clipped-->
<!--            theme="dark"-->
<!--            color="deep-orange darken-4"-->
<!--        >-->
<!--            <v-list-->
<!--            >-->
<!--                <v-list-item-->
<!--                    exact-->
<!--                    v-for="(item, i) in items"-->
<!--                    :key=i-->
<!--                    :to=item.to-->
<!--                >-->
<!--                    <v-list-item-title v-text="item.title"></v-list-item-title>-->
<!--                </v-list-item>-->
<!--            </v-list>-->
<!--        </v-navigation-drawer>-->

        <v-main>
            <router-view></router-view>
        </v-main>
        </v-layout>

    </v-app>
</template>

<script setup>
import { ref } from 'vue'

const drawer = ref(null)
const items = [
    {
        title: 'Home',
        to: '/',
    },
    {
        title: 'About',
        to: '/about',
    },
    {
        title: 'Datatables',
        to: '/datatables',
    },
]

</script>

<style scoped>
html {
    overflow: auto !important;
}
</style>
