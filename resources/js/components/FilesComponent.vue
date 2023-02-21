<template>
    <v-card
        :loading="loading"
        class="mx-auto my-12"
    >
        <v-data-table
            class="elevation-1 fileTable"
            :page="page"
            :headers="headers"
            :items="items"
            sort-by="name"
            :server-items-length="totalRowCount"
            :options.sync="options"
            :loading="tableLoading"
            loading-text="Now loading..."
            fixed-header
            height="50vh"
            :search="options.search"
            show-current-page="false"
            :footer-props="{
                'items-per-page-options': [10],
                'show-current-page': true,
                'show-first-last-page': true,
            }"
        >
            <template v-slot:item="{ item }">
                <tr
                    @click="previewItem($event, item)">
                    <td>{{ item.name }}</td>
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
                        <v-icon
                            v-if="getFileType( item.name, item['.tag']) === 'image'"
                            class="mr-2"
                        >
                            mdi-image
                        </v-icon>
                        <v-icon
                            v-if="getFileType( item.name, item['.tag']) === 'movie'"
                            class="mr-2"
                        >
                            mdi-movie-play
                        </v-icon>
                        <v-icon
                            v-if="getFileType( item.name, item['.tag']) === ''"
                            class="mr-2"
                        >
                            mdi-file
                        </v-icon>
                        <v-icon
                            v-if="getFileType( item.name, item['.tag']) === 'pdf'"
                            class="mr-2"
                        >
                            mdi-file
                        </v-icon>
                        <v-icon
                            v-if="getFileType( item.name, item['.tag']) === 'folder'"
                            class="mr-2"
                            @click="moveNFolder(item.name)"
                        >
                            mdi-folder
                        </v-icon>
                    </td>
                    <td>
                        {{ item.size }}bytes
                    </td>
                    <td>
                        <v-text-field
                            :value="stringToDate(item.client_modified)"
                            type="date"
                            disabled
                        ></v-text-field>
                    </td>
                    <td>
                        <v-icon
                            small
                            class="mr-2"
                            @click="previewItem(null, item)"
                        >
                            mdi-eye
                        </v-icon>
<!--                        <v-icon-->
<!--                            small-->
<!--                            class="mr-2"-->
<!--                            @click="editItem(item)"-->
<!--                        >-->
<!--                            mdi-pencil-->
<!--                        </v-icon>-->
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
                    <v-toolbar-title>{{ path }}</v-toolbar-title>
                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <template>
                        <div>
                        <v-breadcrumbs :items="breadcrumbs">
                            <template v-slot:item="{ item }">
                                <v-breadcrumbs-item
                                    :href="item.href"
                                    :disabled="item.disabled"
                                >
                                    {{ item.title }}
                                </v-breadcrumbs-item>
                            </template>
                        </v-breadcrumbs>
                        </div>
                    </template>
                    <!-- dialog edit, preview, create -->
                    <v-dialog
                        v-model="dialog"
                        scrollable
                        max-width="800px"
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
                                <v-spacer></v-spacer>
                                <v-btn icon @click="dialog = false" >
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                            </v-card-title>
                            <v-card-text>
                                <v-progress-linear
                                    v-if="mode !== 'create'"
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
                                                v-model="editedItem.name"
                                                label="File name"
                                                :disabled="isDisableInput()"
                                            ></v-text-field>
                                            <v-row justify="center">
                                                <v-col
                                                    v-if="mode === 'create'"
                                                    class="d-flex child-flex"
                                                    cols="8"
                                                >
                                                    <v-file-input
                                                        @change="selectFile"
                                                        v-model="files"
                                                        counter
                                                        show-size
                                                        small-chips
                                                        truncate-length="24"
                                                        label="Files"
                                                        multiple
                                                    ></v-file-input>
                                                </v-col>
                                                <v-col
                                                    v-if="mode === 'create'"
                                                    class="d-flex child-flex"
                                                    cols="6"
                                                    v-for="(file, i) in files"
                                                    :key="file"
                                                >
                                                    <span
                                                        v-if="getFileType(file.name) === ''"
                                                        class="markdown-body">
                                                        <Editor
                                                            mode="viewer"
                                                            ref="editor"
                                                            :render-config="renderConfig"
                                                            v-model="file.name"
                                                        />
                                                    </span>
                                                    <video
                                                        v-if="getFileType(file.name) === 'movie'"
                                                        width="100%"
                                                        controls
                                                        :src="getFileUrl(file)">
                                                    </video>
                                                    <v-img
                                                        height="300"
                                                        max-width="500"
                                                        v-if="getFileType(file.name) === 'image'"
                                                        :src="getFileUrl(file)"
                                                        lazy-src="https://picsum.photos/id/11/100/60"
                                                        class="grey lighten-2 mx-auto"
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
                                                    <iframe
                                                        v-if="getFileType(file.name) === 'pdf'"
                                                        :src="getFileUrl(file)"
                                                    >
                                                    </iframe>
                                                </v-col>
                                                <v-col
                                                    v-if="mode === 'preview' || mode === 'edit'"
                                                    cols="12"
                                                >
                                                    <span
                                                        v-if="getFileType( editedItem.name) === ''"
                                                        class="markdown-body">
                                                        <Editor
                                                            style="white-space: pre-wrap;"
                                                            mode="viewer"
                                                            ref="editor"
                                                            :render-config="renderConfig"
                                                            v-model="editedItem.fileText"
                                                        />
                                                    </span>
                                                    <video
                                                        v-if="getFileType( editedItem.name) === 'movie'"
                                                        v-show="editedItem.link"
                                                        width="100%"
                                                        controls
                                                        :src="editedItem.link">
                                                    </video>
                                                    <v-img
                                                        v-if="getFileType( editedItem.name) === 'image'"
                                                        v-show="editedItem.link"
                                                        :src="editedItem.link"
                                                        contain
                                                        aspect-ratio="1"
                                                        lazy-src="https://picsum.photos/id/11/100/60"
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
                                                    <iframe
                                                        v-if="getFileType( editedItem.name) === 'pdf' && editedItem.link"
                                                        :src="getPdfUrl(editedItem.link)"
                                                        frameborder="0"
                                                        class="pdf">
                                                    </iframe>

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

                    <!-- dialog delete -->
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
                        :value="search" @change="value => search = value"
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

<style>
    .fileTable .v-data-footer__pagination{
        display: none;
    }
    .fileTable .v-data-footer__icons-before{
        margin-left: auto;
    }
    .fileTable table > tbody > tr{
        cursor: pointer;
    }
    .pdf{
        height: calc(65vh - 30px);
        width: 100%;
    }
</style>
<script>

import {Editor} from "vuetify-markdown-editor";
import axios from "axios";
import moment from "moment"

moment.locale("ja")

export default {
    components: {
        Editor
    },
    data: () => ({
        routes: window.routes,
        csrfToken: window.csrfToken,
        dialog: false,
        dialogDelete: false,
        mode: '',
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
                value: 'base64Thumbnail',
            },
            {
                text: 'Type',
                align: 'start',
                sortable: true,
                value: 'type',
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
        breadcrumbs: [],
        breadcrumbsDefault: [
            {
                title: 'root',
                disabled: true,
                href: `/home#/files`,
            }
        ],
        cursor: '',
        cursorList: [
            '',
        ],
        hasMore: '',
        editedIndex: -1,
        editedItem: {
            fileMetadata: {},
            file: '',
            link: '',
            base64Thumbnail: '',
            fileText: '',
        },
        defaultItem: {
            fileMetadata: {},
            file: '',
            link: '',
            base64Thumbnail: '',
            fileText: '',
        },
        files: [],
        selectedFiles: [],
        options: {search: '',},
        tableLoading: true,
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
        formTitle() {
            switch (this.mode) {
                case 'create':
                    return 'New File'
                case 'edit':
                    return 'Edit File'
                case 'preview':
                    return 'Preview File'
            }
        },
        search: {
            set(search) {
                this.$emit('change', search)
                this.options.search = search
                this.loadItems();
            },
            get() {
                return this.options.search
            }
        }
    },
    mounted() {
        console.log(this.$route.params)
        this.breadcrumbs = this.breadcrumbsDefault.concat()
    },
    watch: {
        dialog(val) {
            if (!val) this.editedItem = Object.assign({}, this.defaultItem)
            val || this.close()
        },
        dialogDelete(val) {
            val || this.close()
        },
        options: {
            handler() {
                this.loadItems();
            },
            deep: true
        },
        path: {
            handler() {
                this.setBreadcrumbs()
            },
            deep: true
        },
        $route(to, from) {
            this.loadItems()
            this.setBreadcrumbs()
        }
    },
    created() {
        this.path = this.$route.query.path
        this.loadItems()
        this.setBreadcrumbs()
    },
    methods: {
        initialize() {
        },
        setFile(item) {
            let url = this.routes['api.files.link'].uri
            let method = this.routes['api.files.link'].methods[0]
            axios[method.toLowerCase()](url,
                item,
                {headers: {Authorization: "Bearer " + this.csrfToken}}
            ).then((response) => {
                if (response.data) {
                    this.editedItem = Object.assign(this.defaultItem, item)
                    this.editedItem.link = response.data.data.link
                    this.editedItem.fileText = ''
                    if (this.getFileType(this.editedItem.name, this.editedItem['.tag']) === '') {
                        axios['get'](this.editedItem.link).then((response) => {
                            if (response.data) {
                                this.editedItem.fileText = response.data
                            }
                        })
                    }
                }
            })
        },
        loadItems(_page = null) {
            this.tableLoading = true;
            this.items = []
            let page = this.options.page ?? 1
            let itemsPerPage = this.options.itemsPerPage ?? 10
            let sortBy = this.options.sortBy ?? ''
            let search = this.options.search ?? ''
            let cursor = this.cursorList[page - 1] ?? ''
            let path = this.$route.query.path ?? ''
            let url = `${this.routes['api.files.index'].uri}?skip=${skip}&limit=${itemsPerPage}&sortBy=${sortBy}&search=${search}&cursor=${cursor}&path=${path}`
            if (_page) {
                page = _page
            }
            const skip = (page - 1) * itemsPerPage
            axios.get(
                url,
                {headers: {Authorization: "Bearer " + this.csrfToken}}
            )
                .then((response) => {
                    this.totalRowCount = response.data.data.totalRowCount
                    this.items = response.data.data.rows
                    this.hasMore = response.data.data.hasMore
                    this.cursorList[page] = response.data.data.cursor
                    this.tableLoading = false
                    this.setLast();
                    console.log('response.data.data', response.data.data)
                })
                .catch((error) => {
                    console.log(error)
                })
        },
        isDisableInput() {
            switch (this.mode) {
                case 'create':
                case 'edit':
                    return false
                default:
                    return true
            }
        },
        setLast() {
            this.lastPage = Math.ceil(this.totalRowCount / this.options.itemsPerPage)
        },
        saveItem(item) {
            /* this is used for both creating and updating API records
             the default method is POST for creating a new item */

            let method = this.routes['api.files.store'].methods[0]
            let url = this.routes['api.files.store'].uri
            const formData = new FormData()

            this.selectedFiles.forEach((file, index) => {
                formData.append(`files[${index}]`, file)
            })

            // airtable API needs the data to be placed in fields object
            if (item.id) {
                // if the item has an id, we're updating an existing item
                method = this.routes['api.files.update'].methods[0]
                url = this.routes['api.files.update'].uri.replace('{file}', item.id)
            }

            // save the record
            axios[method.toLowerCase()](url, formData,
                {headers: {Authorization: "Bearer " + this.csrfToken}}
            ).then((response) => {
                if (response.data) {
                    // add new item to state
                    this.editedItem = response.data.data
                    if (!item.id) {
                        this.totalRowCount++;
                        this.setLast();
                        this.loadItems(this.lastPage)
                    } else {
                        this.loadItems()
                    }

                }
                this.editedItem = {}
                this.close()
            })

            console.log('todo file uploading', this.files)
        },
        createItem() {
            this.dialog = true
            this.mode = 'create'
            this.files = []
            this.selectedFiles = []
        },
        previewItem(event, item) {
            if(event && event.target.tagName === 'BUTTON') return;
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.setFile(item)
            this.dialog = true
            this.mode = 'preview'
        },
        editItem(item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.setFile(item)
            this.dialog = true
            this.mode = 'edit'
        },
        deleteItem(item) {
            this.editedIndex = this.items.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
            this.mode = 'delete'
        },
        deleteItemConfirm() {
            this.closeDelete()
        },
        getFileUrl(file) {
            if (file === null) return
            return URL.createObjectURL(file)
        },
        getFileText(file) {
            if (file === null) return
            return URL.createObjectURL(file)
        },
        close() {
            this.dialog = false
            this.dialogDelete = false
            this.editedItem = this.defaultItem
            this.editedIndex = -1
            this.$nextTick(() => {
            })
            this.$emit('update:activated', false);
        },
        closeDelete() {
            this.$nextTick(() => {

                const method = this.routes['api.files.delete'].methods[0]
                const url = this.routes['api.files.delete'].uri
                const formData = new FormData()

                formData.append('path', this.editedItem.path_display)

                axios[method.toLowerCase()](url, formData,
                    {
                        headers: {
                            Authorization: "Bearer " + this.csrfToken,
                            "Content-Type": "application/json"
                        }
                    }).then((response) => {
                    this.loadItems()
                    this.editedItem = this.defaultItem
                    this.editedIndex = -1
                    this.dialogDelete = false
                })
            })
        },
        save() {
            this.saveItem(this.editedItem)
        },
        selectFile() {
            this.selectedFiles = event.target.files;
        },
        setBase64Prefix(base64String) {
            return `data:image/png;base64,${base64String}`
        },
        getPdfUrl(url) {
            return `https://docs.google.com/gview?url=${url}&embedded=true`
        },
        getFileExtension(name) {
            return name.split('.').slice(-1)[0].toLowerCase();

        },
        getFileType(name, tag = 'file') {
            if (name === undefined) return '';
            if (tag !== 'file') return tag;
            let extension = this.getFileExtension(name)
            let type = '';

            switch (extension) {
                case 'mov':
                    type = 'movie'
                    break
                case 'png':
                case 'jpg':
                    type = 'image'
                    break
                case 'pdf':
                    type = 'pdf'
                    break
                default:
                // nothing todo
            }
            return type
        },
        stringToDate(string) {
            return moment(new Date(string)).local().format('YYYY-MM-DD');
        },
        moveNFolder: function (path) {
            this.$router.replace({path: this.$route.path, query: {...this.$route.query, path: path}})
        },
        setBreadcrumbs: function () {
            this.breadcrumbs = this.breadcrumbsDefault.concat()
            let pathList = [];
            if (this.$route.query.path) {
                pathList = this.$route.query.path.split('/');
            }

            for (let paramsKey in pathList) {
                let param = pathList[paramsKey] ?? ''
                let breadcrumbs = {
                    title: param,
                    disabled: false,
                    href: `/home#/files/${param}`,
                };
                this.breadcrumbs.push(breadcrumbs)
            }

            let first = this.breadcrumbs[0];
            let last = this.breadcrumbs.slice(-1)[0];
            first.disabled = false
            last.disabled = true
        }
    },
}
</script>
