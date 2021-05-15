<template>

    <div>
        <slot></slot>
    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: 'LiffForm',
        props: {
            name: {required: false},
            field: {required: false},
        },
        data() {
            return {
                validated: false,
                anyError: false,
                inputs: [],
            };
        },
        created() {
        },
        watch: {},
        methods: {
            getInput(component) {

                let children = component.$children;
                children.forEach((child) => {
                    if (child.$options.name === 'liffInputGroup') {
                        this.getInput(child)
                    } else {
                        if (child.$options.name === 'liff-dropdown' || child.$options.name === 'liff-image-upload' || child.$options.name === 'liff-input') {
                            this.inputs.push(child)
                        }
                    }
                });
            },
            validateForm() {
                this.anyError = false;
                this.inputs.forEach((input) => {
                    if (input.$options.name !== 'base-button') {
                        input.checkValidateError();
                        input.forceShowError = true;
                        this.anyError = (this.anyError || input.isInvalid);
                    }
                });
                this.validated = !this.anyError;
                return this.validated;
            },
            redirectError() {
                this.$parent.redirectError();
            },
            reset() {

                this.validated = false;
                this.anyError = false;

                this.inputs.forEach((input) => {
                    if (input.$options.name !== 'base-button') {
                        input.reset();
                    }
                });
            },

        },

        mounted() {
            this.getInput(this);
        },
        computed: {
            ...mapState([
                'theme'
            ])
        }
    };
</script>

