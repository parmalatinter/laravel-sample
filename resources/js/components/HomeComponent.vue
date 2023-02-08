<template>
    <v-card
        :loading="loading"
        class="mx-auto my-12"
    >
        <v-card-text>
            <v-carousel
                :continuous="false"
                :cycle="cycle"
                :show-arrows="false"
                hide-delimiter-background
                delimiter-icon="mdi-minus"
                height="300"
            >
                <v-carousel-item
                    v-for="(slide, i) in slides"
                    :key="i"
                >
                    <v-sheet
                        :color="colors[i]"
                        height="100%"
                        tile
                    >
                        <v-row
                            class="fill-height"
                            align="center"
                            justify="center"
                        >
                            <div class="text-h2">
                                {{ slide }} Slide
                            </div>
                        </v-row>
                    </v-sheet>
                </v-carousel-item>
            </v-carousel>
            <v-list two-line>
                <v-list-item>
                    <v-list-item-avatar>
                        <v-img src="https://cdn.vuetifyjs.com/images/john.png"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <v-list-item-title>John Leider</v-list-item-title>
                        <v-list-item-subtitle>Author</v-list-item-subtitle>
                    </v-list-item-content>
                    <v-list-item-action>
                        <v-switch
                            v-model="cycle"
                            label="Cycle Slides"
                            inset
                        ></v-switch>
                    </v-list-item-action>
                </v-list-item>
            </v-list>
            <span class="markdown-body">
                <Editor
                    mode="viewer"
                    ref="editor"
                    :render-config="renderConfig"
                    v-model="text"
                />
            </span>
        </v-card-text>
    </v-card>
</template>

<script>
import { Editor } from "vuetify-markdown-editor";
// CSS for Editor
// import 'vuetify-markdown-editor/dist/vuetify-markdown-editor.css';
import helloWorld from 'raw-loader!../../markdown/markdown-cheatsheet.md'

export default {
    components: {
        Editor
    },
    data: () => ({
        routes : window.routes,
        olds: window.olds,
        params: window.params,
        colors: [
            'green',
            'secondary',
            'yellow darken-4',
            'red lighten-2',
            'orange darken-1',
        ],
        cycle: false,
        slides: [
            'First',
            'Second',
            'Third',
            'Fourth',
            'Fifth',
        ],
        text: helloWorld,
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
        loading: false,
    }),
}
</script>
