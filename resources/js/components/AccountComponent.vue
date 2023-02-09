<template>
    <v-card
        :loading="loading"
        class="mx-auto my-12"
    >
        <v-card-text>
            <v-form
                ref="form"
                v-model="valid"
                lazy-validation
            >
                <v-text-field
                    v-model="name"
                    :counter="10"
                    :rules="nameRules"
                    label="Name"
                    required
                ></v-text-field>

                <v-text-field
                    v-model="email"
                    :rules="emailRules"
                    label="E-mail"
                    required
                ></v-text-field>

                <v-select
                    v-model="select"
                    :items="items"
                    :rules="[v => !!v || 'Item is required']"
                    label="Item"
                    required
                ></v-select>

                <v-row justify="center">
                    <v-col
                        class="d-flex child-flex"
                        cols="8"
                    >
                        <video controls>
                            <source src="https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.webm" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                        </video>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col
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
                </v-row>

                <v-file-input
                    v-model="images"
                    counter
                    show-size
                    small-chips
                    truncate-length="24"
                    label="Files"
                    multiple
                ></v-file-input>

                <v-checkbox
                    v-model="checkbox"
                    :rules="[v => !!v || 'You must agree to continue!']"
                    label="Do you agree?"
                    required
                ></v-checkbox>

                <v-btn
                    :disabled="!valid"
                    color="success"
                    class="mr-4"
                    @click="validate"
                >
                    Validate
                </v-btn>

                <v-btn
                    color="error"
                    class="mr-4"
                    @click="reset"
                >
                    Reset Form
                </v-btn>

                <v-btn
                    color="warning"
                    @click="resetValidation"
                >
                    Reset Validation
                </v-btn>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    data: () => ({
        routes : window.routes,
        olds: window.olds,
        params: window.params,
        valid: true,
        name: '',
        nameRules: [
            v => !!v || 'Name is required',
            v => (v && v.length <= 10) || 'Name must be less than 10 characters',
        ],
        email: '',
        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        ],
        select: null,
        images: null,
        items: [
            'Item 1',
            'Item 2',
            'Item 3',
            'Item 4',
        ],
        checkbox: false,
        loading: false
    }),

    methods: {
        validate () {
            this.$refs.form.validate()
        },
        reset () {
            this.$refs.form.reset()
        },
        resetValidation () {
            this.$refs.form.resetValidation()
        },
        getImageUrl(image){
            if(image===null) return
            return URL.createObjectURL(image)
        }
    },
}
</script>
