<template>
    <div class="form-group">
        <div class="row">
            <label style="margin: 11px 0 0 0;" class="col-4">{{label}}</label>
            <div class="form-check form-check-radio col-4" v-for="option in forceoption" style="display: flex">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" :value="option.value" v-model="value" :id="option.value">
                    <span class="form-check-sign" :class="{'red':error && (showError || forceShowError)}"></span>
                    {{option.text}}
                </label>
            </div>

        </div>
        <slot name="error" v-if="error && (showError || forceShowError)">
            <label class="error" style="color: #ec250d; font-size: 12px;">{{ error }}</label>
        </slot>
    </div>


</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "base-radio-input",
        props: {
            label: {
                type: String,
                default: 'Radio Input label'
            },
            showError: {default: false},
            optiontext: {default: 'text'},
            optionvalue: {default: 'value'},
            subfield: {required: false},
            field: {required: false},
            vparam: {default: false},
            forceoption: {default: false},
            forceform: {required: false},
        },

        data() {
            return {
                form: null,
                error: null,
                rule: {},
                errorsToCheck: [],
                isInvalid: false,
                forceShowError: false,
            };
        },

        created() {
            this.getValidateParam();
            if (this.forceform) {
                this.form = this.forceform;
            }
        },

        validations() {
            return {
                value: this.rule
            }
        },

        mounted() {
            this.getForm(this);
        },

        watch: {
            value: function (value) {
                this.checkValidateError();
            },
            formError(value) {
                if (value) {
                    this.error = value[0];
                    this.$parent.redirectError();
                }
            },
            vparam: {
                deep: true,

                handler() {
                    this.getValidateParam();
                    this.checkValidateError();
                }
            }
        },
        methods: {
            getCheckMark() {

            },
            getForm(component) {
                if (component.$parent.$options.name == 'vForm') {
                    return this.form = component.$parent.name;
                }
                this.getForm(component.$parent);
            },
            updateForm(value) {
                this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'value': value});
            },
            getValidateParam() {
                this.errorsToCheck = [];
                let temp = {};
                this.vparam.forEach((param) => {
                    this.errorsToCheck.push(param);
                    switch (param) {
                        case 'required':
                            temp = {required};
                            this.rule = {...this.rule, ...temp};
                            break;
                    }
                });
            },
            checkValidateError() {
                this.error = '';
                if (this.$v.value.$invalid) {
                    this.isInvalid = true;
                    this.errorsToCheck.some((param) => {
                        if (!this.$v.value[param]) {
                            switch (param) {
                                case 'required':
                                    return this.error = 'This field is required';
                            }
                        }
                    });
                } else {
                    if (this.formError) {
                        this.isInvalid = true;
                        this.error = this.formError[0];
                    } else {
                        this.isInvalid = false;
                        this.error = null;
                    }
                }
            },
            reset() {
                this.error = null;
                this.isInvalid = false;
                this.forceShowError = false;
                this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'value': null});
            }
        },
        computed: {
            ...mapState([
                'forms',
            ]),
            formError() {
                if (this.subfield) {
                    return this.$store.getters.getFieldError(this.field + '.' + this.subfield);
                } else {
                    return this.$store.getters.getFieldError(this.field);
                }
            },
            value: {
                get() {
                    return this.$store.getters.getFieldValue({'form': this.form, 'field': this.field, 'subfield': this.subfield});
                },
                set(value) {
                    if (this.subfield) {
                        this.$store.commit('clearError', this.field + '.' + this.subfield);
                    }
                    this.$store.commit('clearError', this.field);
                    this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'subfield': this.subfield, 'value': value});

                    this.$emit('input', value);
                }

            }
        },
    }
</script>

<style scoped>
   .form-check-radio .red::before , .form-check-radio .red::after  {

        border: 1px solid #ec250d;

    }
</style>
