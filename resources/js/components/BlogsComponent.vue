<template>
    <v-card
        :loading="loading"
        class="mx-auto my-12"
    >
        <v-data-table
            :page="page"
            :headers="headers"
            :items="items"
            sort-by="name"
            :server-items-length="totalRowCount"
            class="elevation-1"
            :options.sync="options"
            :loading="tableLoading"
            loading-text="Now loading..."
            fixed-header
            height="50vh"
            :search="options.search"
        >
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title>Blogs</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog
                        v-model="dialog"
                        scrollable
                        max-width="500px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                dark
                                class="mb-2"
                                @click="createItem()"
                            >
                                New Blog
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
                                        >
                                            <v-text-field
                                                v-model="editedItem.name"
                                                label="Blog name"
                                                :disabled="isDisableInput()"
                                            ></v-text-field>
                                            <Editor v-if="isDisableInput() === false"
                                                mode="editor"
                                                ref="editor"
                                                :render-config="renderConfig"
                                                v-model="editedItem.content"
                                                :emoji="false"
                                                outline
                                            />
                                            <span class="markdown-body">
                                                <Editor
                                                    mode="viewer"
                                                    ref="editorViewer"
                                                    :render-config="renderConfig"
                                                    v-model="editedItem.content"
                                                    :outline="false"
                                                />
                                            </span>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions v-if="isDisableInput() === false">
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
                    <v-dialog
                        v-model="dialogDelete"
                        max-width="500px">
                        <v-card>
                            <v-card-title class="text-h5">Are you sure you want to delete this blog?</v-card-title>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                                <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                <v-spacer></v-spacer>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
                <v-card-title>
                    <v-text-field
                        v-model="options.search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
                </v-card-title>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click="previewItem(item)"
                >
                    mdi-eye
                </v-icon>
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
        </v-data-table>
    </v-card>
</template>

<script>

import { Editor } from "vuetify-markdown-editor";
import axios from "axios";

export default {
    components: {
        Editor
    },
    data: () => ({
        routes : window.routes,
        csrfToken : window.csrfToken,
        dialog: false,
        dialogDelete: false,
        mode : '',
        headers: [
            {
                text: 'Name',
                align: 'start',
                sortable: true,
                value: 'name',
            },
            {
                text: 'Content',
                align: 'start',
                sortable: true,
                value: 'content',
            },
            { text: 'Actions', value: 'actions', sortable: false },
        ],
        page: 1,
        lastPage: 1,
        totalRowCount: 0,
        limit: 10,
        items: [],
        editedIndex: -1,
        editedItem: {
            name: '',
            content: '',
        },
        defaultItem: {
            name: '',
            content: '',
        },
        options: {search: ''},
        tableLoading:true,
        loading: false,
        renderConfig: {
            // Mermaid config
            mermaid: {
                theme: "dark"
            },
            // markdown-it-code-copy config
            codeCopy: {
                buttonClass: 'v-icon theme--dark'
            },
        },
    }),
    computed: {
        formTitle () {
            switch (this.mode){
                case 'create':
                    return 'New Blog'
                case 'edit':
                    return 'Edit Blog'
                case 'preview':
                    return 'Preview Blog'
            }
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
    },
    created () {
        this.loadItems()
    },
    methods: {
        initialize () {},
        loadItems(_page = null) {
            this.tableLoading= true;
            this.items = []
            let page = this.options.page ?? 1
            let itemsPerPage = this.options.itemsPerPage ?? 10
            let sortBy = this.options.sortBy ?? ''
            let search = this.options.search ?? ''
            let url = `${this.routes['api.blogs.index'].uri}?skip=${skip}&limit=${itemsPerPage}&sortBy=${sortBy}&search=${search}`
            if(_page){
                page = _page
            }
            const skip = (page-1) * itemsPerPage
            axios.get(
                url,
                { headers: { Authorization: "Bearer " + this.csrfToken }}
            )
                .then((response) => {
                    this.totalRowCount = response.data.data.totalRowCount
                    this.items = response.data.data.rows
                    this.tableLoading = false
                    this.setLast();
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        isDisableInput () {
            switch (this.mode){
                case 'create':
                case 'edit':
                    return false
                default:
                    return true
            }
        },
        setLast(){
            this.lastPage = Math.ceil(this.totalRowCount / this.options.itemsPerPage)
        },
        saveItem(item) {
            /* this is used for both creating and updating API records
             the default method is POST for creating a new item */

            let method = this.routes['api.blogs.store'].methods[0]
            let url = this.routes['api.blogs.store'].uri

            // airtable API needs the data to be placed in fields object
            if (item.id) {
                // if the item has an id, we're updating an existing item
                method = this.routes['api.blogs.update'].methods[0]
                url = this.routes['api.blogs.update'].uri.replace('{blog}', item.id)
            }

            // save the record
            axios[method.toLowerCase()](url,
                item,
                { headers: { Authorization: "Bearer " + this.csrfToken }}
            ).then((response) => {
                if (response.data) {
                    // add new item to state
                    this.editedItem = response.data.data
                    if (!item.id) {
                        this.totalRowCount++;
                        this.setLast();
                        this.loadItems(this.lastPage)
                    }else{
                        this.loadItems()
                    }
                    this.editedItem = {}
                }
                this.close()
            })

            console.log('todo file uploading', this.$refs.editor.files)
        },
        createItem () {
            this.dialog = true
            this.mode = 'create'
        },
        previewItem (item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
            this.mode = 'preview'
        },
        editItem (item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
            this.mode = 'edit'
        },
        deleteItem (item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
            this.mode = 'delete'
        },
        deleteItemConfirm () {
            this.closeDelete()
        },
        close () {
            this.dialog = false
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },
        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {

                const method = this.routes['api.blogs.destroy'].methods[0]
                const url = this.routes['api.blogs.destroy'].uri.replace('{blog}', this.editedItem.id)

                axios[method.toLowerCase()](url,
                    { headers: {
                            Authorization: "Bearer " + this.csrfToken,
                            "Content-Type": "application/json"
                        }
                    }).then((response) => {
                    this.loadItems()
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
