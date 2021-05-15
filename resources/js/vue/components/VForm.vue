<template>

    <div>
        <slot></slot>
    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: 'vForm',
        props: {
            name: {required: false},
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
            validateForm() {
                this.anyError = false;
                this.inputs.forEach((input) => {
                    if (input.$options.name !== 'base-button' && input.$options.name !== 'VueTimepicker') {
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
            }
        },

        mounted() {
            this.inputs = this.$children;
        },
        computed: {
            ...mapState([
                'theme'
            ])
        }
    };
</script>

