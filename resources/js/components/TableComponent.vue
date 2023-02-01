<template>
    <v-data-table
        :page="page"
        :headers="headers"
        :items="items"
        sort-by="name"
        :server-items-length="totalRowCount"
        class="elevation-1"
        :options.sync="options"
    >
        <template v-slot:top>
            <v-toolbar
                flat
            >
                <v-toolbar-title>My CRUD</v-toolbar-title>
                <v-divider
                    class="mx-4"
                    inset
                    vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog
                    v-model="dialog"
                    max-width="500px"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            v-bind="attrs"
                            v-on="on"
                        >
                            New Item
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="text-h5">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col
                                        cols="12"
                                        sm="6"
                                        md="4"
                                    >
                                        <v-text-field
                                            v-model="editedItem.name"
                                            label="Dessert name"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="close"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="save"
                            >
                                Save
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
            <v-icon
                small
                class="mr-2"
                @click="editItem(item)"
            >
                mdi-pencil
            </v-icon>
            <v-icon
                small
                @click="deleteItem(item)"
            >
                mdi-delete
            </v-icon>
        </template>
        <template v-slot:no-data>
            <v-btn
                color="primary"
                @click="initialize"
            >
                Reset
            </v-btn>
        </template>
    </v-data-table>
</template>

<script>

import axios from "axios";

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
export default {
    data: () => ({
        dialog: false,
        dialogDelete: false,
        headers: [
            {
                text: 'Name',
                align: 'start',
                sortable: false,
                value: 'name',
            },
            // { text: 'Calories', value: 'calories' },
            // { text: 'Fat (g)', value: 'fat' },
            // { text: 'Carbs (g)', value: 'carbs' },
            // { text: 'Protein (g)', value: 'protein' },
            { text: 'Actions', value: 'actions', sortable: false },
        ],
        page: 1,
        totalRowCount: 0,
        limit: 10,
        items: [],
        editedIndex: -1,
        editedItem: {
            name: '',
            // calories: 0,
            // fat: 0,
            // carbs: 0,
            // protein: 0,
        },
        defaultItem: {
            name: '',
            // calories: 0,
            // fat: 0,
            // carbs: 0,
            // protein: 0,
        },
        options: {},
    }),
    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
    },
    watch: {
        dialog (val) {
            val || this.close()
        },
        dialogDelete (val) {
            val || this.close()
        },
        options: {
            handler() {
                this.loadItems();
            },
            deep: true
        },
        deep: true,
    },

    created () {
        this.loadItems()
    },
    methods: {
        initialize () {
            // this.items = [
            //     {
            //         name: 'Frozen Yogurt',
            //         calories: 159,
            //         fat: 6.0,
            //         carbs: 24,
            //         protein: 4.0,
            //     },
            //     {
            //         name: 'Ice cream sandwich',
            //         calories: 237,
            //         fat: 9.0,
            //         carbs: 37,
            //         protein: 4.3,
            //     },
            //     {
            //         name: 'Eclair',
            //         calories: 262,
            //         fat: 16.0,
            //         carbs: 23,
            //         protein: 6.0,
            //     },
            //     {
            //         name: 'Cupcake',
            //         calories: 305,
            //         fat: 3.7,
            //         carbs: 67,
            //         protein: 4.3,
            //     },
            //     {
            //         name: 'Gingerbread',
            //         calories: 356,
            //         fat: 16.0,
            //         carbs: 49,
            //         protein: 3.9,
            //     },
            //     {
            //         name: 'Jelly bean',
            //         calories: 375,
            //         fat: 0.0,
            //         carbs: 94,
            //         protein: 0.0,
            //     },
            //     {
            //         name: 'Lollipop',
            //         calories: 392,
            //         fat: 0.2,
            //         carbs: 98,
            //         protein: 0,
            //     },
            //     {
            //         name: 'Honeycomb',
            //         calories: 408,
            //         fat: 3.2,
            //         carbs: 87,
            //         protein: 6.5,
            //     },
            //     {
            //         name: 'Donut',
            //         calories: 452,
            //         fat: 25.0,
            //         carbs: 51,
            //         protein: 4.9,
            //     },
            //     {
            //         name: 'KitKat',
            //         calories: 518,
            //         fat: 26.0,
            //         carbs: 65,
            //         protein: 7,
            //     },
            // ]
        },
        loadItems() {
            this.items = []
            const { page, itemsPerPage } = this.options;
            const skip = (page-1) * itemsPerPage
            axios.get(
                `api/items?skip=${skip}&limit=${itemsPerPage}`,
                { headers: { Authorization: "Bearer " + csrfToken }}
                )
                .then((response) => {
                    this.totalRowCount = response.data.data.totalRowCount
                    this.items = response.data.data.rows
                })
                .catch((error) => {
                    console.log(error)
            })
        },
        saveItem(item) {
            /* this is used for both creating and updating API records
             the default method is POST for creating a new item */

            let method = "post"
            let url = `api/items`
            let id = item.id

            // airtable API needs the data to be placed in fields object

            if (id) {
                // if the item has an id, we're updating an existing item
                method = "patch"
                url = `api/items/${id}`
            }

            // save the record
            axios[method](url,
                item,
                { headers: { Authorization: "Bearer " + csrfToken }}
                ).then((response) => {
                    if (response.data) {
                        // add new item to state
                        this.editedItem = response.data.data
                        if (!id) {
                            // add the new item to items state
                            this.items.push(this.editedItem)
                        }else{
                            Object.assign(this.items[this.editedIndex], this.editedItem)
                        }
                        this.editedItem = {}
                    }
                    this.close()
            })
        },
        editItem (item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        deleteItem (item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },
        deleteItemConfirm () {
            this.closeDelete()
        },
        close () {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },
        closeDelete () {
            this.dialogDelete = false
            let method = "delete"
            this.$nextTick(() => {

                const id = this.editedItem.id
                const url = `api/items/${id}`

                axios[method](url,
                    { headers: {
                        Authorization: "Bearer " + csrfToken,
                        "Content-Type": "application/json"
                    }
                }).then((response) => {
                    this.items.splice(this.editedIndex, 1)
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            })
        },
        save () {
            this.saveItem(this.editedItem)
        },
    },
}
</script>
