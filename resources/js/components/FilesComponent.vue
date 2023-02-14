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
            <template v-slot:item="{ item }">
                <tr>
                    <td>{{ item.fileMetadata.name }}</td>
                    <td>
                        <v-img
                            :src="setBase64Prefix(item.base64Thumbnail)"
                            width="100px"
                            lazy-src="https://picsum.photos/id/11/100/60"
                            aspect-ratio="1"
                            class="grey lighten-2"
                        >
                        </v-img>
                    </td>
                    <td>
                        {{ item.fileMetadata.size }}bytes
                    </td>
                    <td>
                        <v-text-field
                            :value="stringToDate(item.fileMetadata.client_modified)"
                            type="date"
                            disabled
                        ></v-text-field>
                    </td>
                    <td>
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
                    </td>
                </tr>
            </template>
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                    <v-toolbar-title>Files</v-toolbar-title>
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
                        :scrim="false"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                color="primary"
                                dark
                                class="mb-2"
                                @click="createItem()"
                            >
                                New File
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="text-h5">{{ formTitle }}</span>
                            </v-card-title>
                            <v-card-text>
                                <v-progress-linear
                                    :indeterminate="editedItem.link === ''">
                                </v-progress-linear>
                            </v-card-text>
                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col
                                            cols="12"
                                        >
                                            <v-text-field
                                                v-if="mode !== 'create'"
                                                v-model="editedItem.fileMetadata.name"
                                                label="File name"
                                                :disabled="isDisableInput()"
                                            ></v-text-field>
                                            <v-row justify="center">

                                                <v-col
                                                    v-if="mode === 'create'"
                                                    v-for="(image, i) in images"
                                                    :key="n"
                                                    class="d-flex child-flex"
                                                    cols="4"
                                                >
                                                    <v-img
                                                        :src="getImageUrl(image)"
                                                        lazy-src="https://picsum.photos/id/11/100/60"
                                                        aspect-ratio="1"
                                                        class="grey lighten-2"
                                                    >
                                                        <template v-slot:placeholder>
                                                            <v-row
                                                                class="fill-height ma-0"
                                                                align="center"
                                                                justify="center"
                                                            >
                                                                <v-progress-circular
                                                                    indeterminate
                                                                    color="grey lighten-5"
                                                                ></v-progress-circular>
                                                            </v-row>
                                                        </template>
                                                    </v-img>
                                                </v-col>
                                                <v-col
                                                    class="d-flex child-flex"
                                                    cols="12"
                                                >
                                                    <v-file-input
                                                        v-if="mode === 'create'"
                                                        @change="selectFile"
                                                        v-model="images"
                                                        counter
                                                        show-size
                                                        small-chips
                                                        truncate-length="24"
                                                        label="Files"
                                                        multiple
                                                    ></v-file-input>
                                                    <v-img
                                                        v-show="editedItem.link"
                                                        :src="editedItem.link"
                                                        lazy-src="https://picsum.photos/id/11/100/60"
                                                        width="100%"
                                                        aspect-ratio="1"
                                                        class="grey lighten-2"
                                                    >
                                                        <template v-slot:placeholder>
                                                            <div class="d-flex align-center justify-center fill-height">
                                                                <v-progress-circular
                                                                    color="grey-lighten-4"
                                                                    indeterminate
                                                                ></v-progress-circular>
                                                            </div>
                                                        </template>
                                                    </v-img>
                                                </v-col>
                                            </v-row>

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
                            <v-card-title class="text-h5">Are you sure you want to delete this file?</v-card-title>
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

        </v-data-table>
    </v-card>
</template>

<script>

import { Editor } from "vuetify-markdown-editor";
import axios from "axios";
import moment from "moment"
moment.locale("ja")

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
                value: 'fileMetadata.name',
            },
            {
                text: 'Content',
                align: 'start',
                sortable: true,
                value: 'base64Thumbnail',
            },
            {
                text: 'Size',
                align: 'start',
                sortable: true,
                value: 'size',
            },
            {
                text: 'Date',
                align: 'start',
                sortable: true,
                value: 'clientModified',
            },
            {
                text: 'Actions',
                value: 'actions',
                sortable: false
            },
        ],
        page: 1,
        lastPage: 1,
        totalRowCount: 0,
        limit: 10,
        items: [],
        editedIndex: -1,
        editedItem: {
            fileMetadata: {
                name : ''
            },
            file: '',
            link : '',
            base64Thumbnail: '',
        },
        defaultItem: {
            fileMetadata: {
                name : ''
            },
            file: '',
            link : '',
            base64Thumbnail: '',
        },
        images: [],
        selectedFiles: [],
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
                    return 'New File'
                case 'edit':
                    return 'Edit File'
                case 'preview':
                    return 'Preview File'
            }
        },
    },
    watch: {
        dialog (val) {
            if(!val) this.editedItem = Object.assign({}, this.defaultItem)
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
        setFile(item) {
            let url = this.routes['api.files.file'].uri
            let method = this.routes['api.files.file'].methods[0]
            this.editedItem['link'] = ''
                axios[method.toLowerCase()](url,
                item.fileMetadata,
                { headers: { Authorization: "Bearer " + this.csrfToken }}
            ).then((response) => {
                if (response.data) {
                    this.editedItem = Object.assign({}, item)
                    this.editedItem['link'] = response.data.link
                }
            })
        },
        loadItems(_page = null) {
            this.tableLoading= true;
            this.items = []
            let page = this.options.page ?? 1
            let itemsPerPage = this.options.itemsPerPage ?? 10
            let sortBy = this.options.sortBy ?? ''
            let search = this.options.search ?? ''
            let url = `${this.routes['api.files.index'].uri}?skip=${skip}&limit=${itemsPerPage}&sortBy=${sortBy}&search=${search}`
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

            let method = this.routes['api.files.store'].methods[0]
            let url = this.routes['api.files.store'].uri
            const formData = new FormData()

            this.selectedFiles.forEach((image,index) => {
                formData.append(`images[${index}]`, image)
            })

            // airtable API needs the data to be placed in fields object
            if (item.id) {
                // if the item has an id, we're updating an existing item
                method = this.routes['api.files.update'].methods[0]
                url = this.routes['api.files.update'].uri.replace('{file}', item.id)
            }

            // save the record
            axios[method.toLowerCase()](url, formData,
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

                }
                this.editedItem = {}
                this.close()
            })

            console.log('todo file uploading', this.images)
        },
        createItem () {
            this.dialog = true
            this.mode = 'create'
            this.images = []
            this.selectedFiles = []
        },
        previewItem (item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.setFile(item)
            this.dialog = true
            this.mode = 'preview'
        },
        editItem (item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.setFile(item)
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
        getImageUrl(image){
            if(image===null) return
            return URL.createObjectURL(image)
        },
        close () {
            this.dialog = false
            this.dialogDelete = false
            this.editedItem = this.defaultItem
            this.editedIndex = -1
            this.$nextTick(() => {})
            this.$emit('update:activated', false);
        },
        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {

                const method = this.routes['api.files.destroy'].methods[0]
                const url = this.routes['api.files.destroy'].uri.replace('{file}', this.editedItem.id)

                axios[method.toLowerCase()](url,
                    { headers: {
                            Authorization: "Bearer " + this.csrfToken,
                            "Content-Type": "application/json"
                        }
                    }).then((response) => {
                    this.loadItems()
                    this.editedItem = this.defaultItem
                    this.editedIndex = -1
                })
            })
        },
        save () {
            this.saveItem(this.editedItem)
        },
        selectFile() {
            this.selectedFiles = event.target.files;
        },
        setBase64Prefix(base64String){
            return `data:image/png;base64,${base64String}`
        },
        stringToDate(string){
            return moment(new Date(string)).local().format('YYYY-MM-DD');
        }
    },
}
</script>
