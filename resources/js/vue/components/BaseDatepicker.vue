<template>
    <div
        class="form-group"
        :class="{
      'input-group-focus': focused,
      'has-danger': error,
      'has-success': !error && touched && value,
      'has-label': label,
      'has-icon': hasIcon,
    }"
    >
        <slot name="label">
            <label v-if="label"> {{ label }} {{ required ? '*' : '' }} </label>
        </slot>
        <div class="mb-0" :class="{'input-group': hasIcon}">
            <slot name="addonLeft">
        <span v-if="addonLeftIcon" class="input-group-prepend">
          <div class="input-group-text"><i :class="addonLeftIcon"></i></div>
        </span>
            </slot>
            <slot>
                <input @click="showDatePicker()"
                    :value="dateString"
                    v-bind="$attrs"
                    v-on="listeners"
                    class="form-control"
                    aria-describedby="addon-right addon-left"
                />
            </slot>
            <slot name="addonRight">
                <span v-if="addonRightIcon" class="input-group-append">
          <div class="input-group-text"><i :class="addonRightIcon"></i></div>
        </span>
            </slot>
        </div>

        <div @click.self="showPicker = false" v-show="showPicker" style="top:0; position:fixed; z-index:1; width: 100vw; height: 100vh; background-color: transparent;"></div>
        <transition name="slide">
            <date-picker locale="th" v-show="showPicker" v-model='pickerDate' is-inline is-dark/>
        </transition>
        <slot name="error" v-if="error && (showError || forceShowError)">
            <label class="error">{{ error }}</label>
        </slot>
        <slot name="helperText"></slot>
    </div>

</template>
<script>

    // import {required, minLength, maxLength, minValue, maxValue, between, numeric, alpha, alphaNum, email, decimal, integer} from 'vuelidate/lib/validators'

    export default {
        inheritAttrs: false,
        name: 'base-datepicker',
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
            subfield: {required: false},
            forceform: {required: false},
            type: {default: 'single'},
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
                form: null,

                showPicker: false,
                dateString: null,

                pickerDate: null,
            };
        },

        created() {
            this.getValidateParam();
            if (this.forceform) {
                this.form = this.forceform;
            }
        },

        mounted() {

            this.getForm(this);

            this.pickerDate = this.value;
            this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'subfield': this.subfield, 'value': moment(this.pickerDate).toISOString()});

        },

        validations() {
            return {
                value: this.rule
            }
        },

        watch: {
            value: function (value) {
                if (this.touched) {
                    this.checkValidateError();
                }
            },
            formError(value) {
                this.error = value[0];
                this.$parent.redirectError();
            },
            pickerDate(value) {
                if (this.touched) {
                    this.checkValidateError();
                }
                this.dateString = moment(value).format('D MMM YYYY');
                this.value = value;

                new Promise(function (resolve, reject) {
                    setTimeout(function () {
                        this.showPicker = false;
                        resolve('foo');
                    }.bind(this), 100);
                }.bind(this));
            },
        },


        computed: {
            value: {
                get() {
                    return this.$store.getters.getFieldValue({'form': this.form, 'field': this.field, 'subfield': this.subfield});

                },
                set(value) {
                    this.$store.commit('clearError', this.field);
                    this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'subfield': this.subfield, 'value': moment(value).format('YYYY-MM-DD HH:mm:ss')});
                    this.$emit('input', value);
                }
            },
            hasIcon() {
                return this.hasLeftAddon || this.hasRightAddon
            },
            hasLeftAddon() {
                const {addonLeft} = this.$slots;
                return (
                    addonLeft !== undefined ||
                    this.addonLeftIcon !== undefined
                );
            },
            hasRightAddon() {
                const {addonRight} = this.$slots;
                return (
                    addonRight !== undefined ||
                    this.addonRightIcon !== undefined
                );
            },
            listeners() {
                return {
                    ...this.$listeners,
                    input: this.onInput,
                    blur: this.onBlur,
                    focus: this.onFocus
                };
            },
            formError() {
                return this.$store.getters.getFieldError(this.field);
            }
        },
        methods: {
            getForm(component) {
                if (component.$parent.$options.name == 'vForm') {
                    return this.form = component.$parent.name;
                }
                this.getForm(component.$parent);
            },
            showDatePicker(){
                this.showPicker = true;
                this.touched = true;
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
                if (this.$v.value.$invalid) {
                    this.isInvalid = true;
                    this.errorsToCheck.some((param) => {
                        if (param === 'required') {
                            return this.error = 'Please select an option.';
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
            }
        }
    };
</script>
<style>

    .slide-enter-active {
        -moz-transition-duration: 0.1s;
        -webkit-transition-duration: 0.1s;
        -o-transition-duration: 0.1s;
        transition-duration: 0.1s;
        -moz-transition-timing-function: ease-in;
        -webkit-transition-timing-function: ease-in;
        -o-transition-timing-function: ease-in;
        transition-timing-function: ease-in;
    }

    .slide-leave-active {
        -moz-transition-duration: 0.1s;
        -webkit-transition-duration: 0.3s;
        -o-transition-duration: 0.1s;
        transition-duration: 0.1s;
    }

    .slide-enter-to, .slide-leave {
        max-height: 100px;
        overflow: hidden;
    }

    .slide-enter, .slide-leave-to {
        overflow: hidden;
        max-height: 0;
    }

    .vc-text-sm {
        font-size: 16px !important;
    }

    .vc-grid-container {
        gap: 9px 0 !important;
    }

    .vc-container {
        z-index: 2;
        width: 100% !important;
        border-radius: 0 !important;
    }
</style>
