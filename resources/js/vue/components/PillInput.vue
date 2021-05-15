<template>
    <div>
        <slot name="label">
            <label v-if="label"> {{ label }} {{ required ? '*' : '' }} </label>
        </slot>
        <div v-if="windowWidth < 576">
            <div v-for="(option, index) in pillOptions" :key="index" style="text-align: center;">
                <a @click="pillSelected(option[optionvalue])" :class="getClass(option[optionvalue])"
                   style="margin-right: 10px;">
                    {{option[optiontext]}}
                </a>
            </div>
        </div>
        <div v-else class="row justify-content-center" :class="{'mt-5':!nomt}">
            <div>
                <a v-for="(option, index) in pillOptions" :key="index" @click="pillSelected(option[optionvalue])" :class="getClass(option[optionvalue])"
                   style="margin-right: 10px;">
                    {{option[optiontext]}}
                </a>
            </div>
        </div>

        <slot name="error" v-if="error && (showError || forceShowError)">
            <div class="row justify-content-center mt-5" style="margin-top:0 !important;">
                <p class="error">{{ error }}</p>
            </div>
        </slot>
        <slot name="helperText"></slot>
    </div>
</template>
<script>

    export default {
        inheritAttrs: false,
        name: 'pill-input',
        props: {
            required: Boolean,
            label: {
                type: String,
                description: 'Input label'
            },
            addonRightIcon: {
                type: String,
                description: 'Input icon on the right'
            },
            addonLeftIcon: {
                type: String,
                description: 'Input icon on the left'
            },
            vparam: {default: false},
            showError: {default: false},
            field: {required: false},
            nomt: {default: false},
            readonly: Boolean,
            options: {required: false},
            url: {required: false},
            subfield: {required: false},
            optiontext: {default: 'text'},
            optionvalue: {default: 'value'},
            param: {required: false},
        },
        model: {
            prop: 'value',
            event: 'input'
        },
        data() {
            return {
                focused: false,
                touched: false,
                error: null,
                rule: {},
                errorsToCheck: [],
                isInvalid: false,
                forceShowError: false,
                selectOption: null,
                windowWidth: 0,
                form: null,
                pillOptions: null,
            };
        },

        created() {
            if (this.options) {
                this.pillOptions = this.options
            } else {
                this.getOption();
            }

            this.getValidateParam();
            this.selectOption = this.value;
        },

        validations() {
            return {
                selectOption: this.rule
            }
        },

        watch: {
            selectOption: function (value) {
                this.checkValidateError();
            },
            value(value) {
                if (value || value === 0 || value === false) {
                    return this.selectOption = value;
                }
                return this.selectOption = null;
            }
        },

        mounted() {
            this.getForm(this);
            this.windowWidth = window.innerWidth;
            window.addEventListener('resize', () => {
                this.windowWidth = window.innerWidth
            })
        },

        computed: {
            value() {
                return this.$store.getters.getFieldValue({'form': this.form, 'field': this.field});
            },
        },
        methods: {
            getForm(component) {
                if (component.$parent.$options.name == 'vForm') {
                    return this.form = component.$parent.name;
                }
                this.getForm(component.$parent);
            },
            getValidateParam() {
                let temp = {};
                this.vparam.forEach((param) => {
                    this.errorsToCheck.push(param);
                    if (param === 'required') {
                        temp = {required};
                        this.rule = {...this.rule, ...temp};
                    }
                });
            },
            checkValidateError: function () {
                this.error = '';
                if (this.$v.selectOption.$invalid) {
                    this.isInvalid = true;
                    this.errorsToCheck.some((param) => {
                        if (param === 'required') {
                            return this.error = 'Please select an option.';
                        }
                    });
                } else {
                    this.isInvalid = false;
                    this.error = null;
                }
            },
            pillSelected(value) {
                if(!this.readonly){
                    this.selectOption = value;
                    this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'value': value});
                }
            },
            getClass(arg) {
                if (arg === this.selectOption) {
                    return 'btn btn-primary btn-round btn-lg fwhite';
                }
                if (this.error && (this.showError || this.forceShowError)) {

                    return 'btn btn-danger btn-round btn-simple btn-lg fred';
                } else {

                    return 'btn btn-default btn-round btn-simple btn-lg';
                }
            },
            onInput(evt) {
                if (!this.touched) {
                    this.touched = true;
                }
                this.$emit('input', evt.target.value);
            },
            onFocus(evt) {
                this.focused = true;
                this.$emit('focus', evt)
            },
            onBlur(evt) {
                this.focused = false;
                this.$emit('blur', evt)
            },
            reset() {

                this.touched = false;
                this.error = null;
                this.isInvalid = false;
                this.forceShowError = false;
                this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'value': null});
            },
            getOption(url, param = {}) {
                return new Promise((resolve, reject) => {
                    axios.get('/api/' + this.url, {
                        params: this.param
                    })
                        .then(response => {
                            this.pillOptions = response.data.data;
                            console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
        }
    };
</script>
<style scoped>
    .fwhite {
        color: #ffffff !important;
    }

    .error {
        color: #fd5d93 !important;
        font-size: 12px;
    }

    .fred {
        color: #fd5d93 !important;
    }
</style>
