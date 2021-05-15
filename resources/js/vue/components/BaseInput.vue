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
                <input
                    v-if="showonly"
                    :value="forcevalue"
                    readonly
                    v-bind="$attrs"
                    v-on="listeners"
                    class="form-control"
                    aria-describedby="addon-right addon-left"
                    style="color: rgba(255, 255, 255, 0.8) !important;"
                />

                <input
                    v-if="(type === 'text' && !showonly)"
                    v-model="value"
                    v-bind="$attrs"
                    v-on="listeners"
                    class="form-control"
                    aria-describedby="addon-right addon-left"
                />

                <input
                    v-if="(type === 'upper')"
                    v-model="value"
                    v-bind="$attrs"
                    v-on="listeners"
                    class="form-control"
                    style="text-transform: uppercase;"
                    aria-describedby="addon-right addon-left"
                />

                <input v-if="(type === 'number')" type="number" pattern="[0-9]*" v-model="value"
                       v-bind="$attrs"
                       v-on="listeners"
                       class="form-control"
                       aria-describedby="addon-right addon-left"/>
            </slot>
            <slot name="addonRight">
        <span v-if="addonRightIcon" class="input-group-append">
          <div class="input-group-text"><i :class="addonRightIcon"></i></div>
        </span>
            </slot>
        </div>

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
        name: 'base-input',
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
            type: {default: 'text'},
            forcevalue: {required: false},
            showonly: Boolean,
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
            };
        },

        created() {
            this.getValidateParam();
            if (this.forceform) {
                this.form = this.forceform;
            }
        },

        mounted() {

            if (!this.showonly) {
                this.getForm(this);
            }

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


        computed: {
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
                if (this.subfield) {
                    return this.$store.getters.getFieldError(this.field + '.' + this.subfield);
                } else {
                    return this.$store.getters.getFieldError(this.field);
                }
            }
        },
        methods: {
            getForm(component) {
                if (component.$parent.$options.name == 'vForm') {
                    return this.form = component.$parent.name;
                }
                this.getForm(component.$parent);
            },
            getValidateParam() {
                this.errorsToCheck = [];
                let temp = {};
                this.vparam.forEach((param) => {
                    this.errorsToCheck.push(param);
                    switch (typeof param) {
                        case 'string':
                            switch (param) {
                                case 'required':
                                    temp = {required};
                                    this.rule = {...this.rule, ...temp};
                                    break;
                                case 'numeric':
                                    temp = {numeric};
                                    this.rule = {...this.rule, ...temp};
                                    break;
                                case 'alpha':
                                    temp = {alpha};
                                    this.rule = {...this.rule, ...temp};
                                    break;
                                case 'alphaNum':
                                    temp = {alphaNum};
                                    this.rule = {...this.rule, ...temp};
                                    break;
                                case 'decimal':
                                    temp = {decimal};
                                    this.rule = {...this.rule, ...temp};
                                    break;
                                case 'integer':
                                    temp = {integer};
                                    this.rule = {...this.rule, ...temp};
                                    break;
                                case 'email':
                                    temp = {email};
                                    this.rule = {...this.rule, ...temp};
                                    break;
                                case 'url':
                                    temp = {url};
                                    this.rule = {...this.rule, ...temp};
                                    break;
                            }
                            break;
                        case 'object':
                            let key = Object.keys(param)[0];
                            switch (key) {
                                case 'minLength':
                                    this.rule[key] = minLength(param[key]);
                                    break;
                                case 'maxLength':
                                    this.rule[key] = maxLength(param[key]);
                                    break;
                                case 'minValue':
                                    this.rule[key] = minValue(param[key]);
                                    break;
                                case 'maxValue':
                                    this.rule[key] = maxValue(param[key]);
                                    break;
                            }
                            break;
                    }
                });
            },
            checkValidateError() {
                this.error = '';
                if (this.$v.value.$invalid) {
                    this.isInvalid = true;
                    this.errorsToCheck.some((param) => {
                        switch (typeof param) {
                            case 'string':
                                if (!this.$v.value[param]) {
                                    switch (param) {
                                        case 'required':
                                            return this.error = 'This field is required';
                                        case 'numeric':
                                            return this.error = 'Please enter only numbers';
                                        case 'alpha':
                                            return this.error = 'Please enter only letters';
                                        case 'alphaNum':
                                            return this.error = 'Please enter only letters and numbers';
                                        case 'decimal':
                                            return this.error = 'Please enter only numbers';
                                        case 'integer':
                                            return this.error = 'Please enter only numbers';
                                        case 'email':
                                            return this.error = 'Please enter a valid email.';
                                        case 'url':
                                            return this.error = 'Please enter a valid url.';
                                    }
                                }
                                break;
                            case 'object':
                                let key = Object.keys(param)[0];
                                if (!this.$v.value[key]) {
                                    switch (key) {
                                        case 'minLength':
                                            return this.error = 'This field must have at least ' + param[key] + ' characters.';
                                        case 'maxLength':
                                            return this.error = 'This field must have no more than ' + param[key] + ' characters.';
                                        case 'minValue':
                                            return this.error = 'This field must have at least ' + param[key];
                                        case 'maxValue':
                                            return this.error = 'This field must be no more than ' + param[key];
                                    }
                                }
                                break;
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
    .input-group-append, .input-group-prepend .input-group-text, .input-group-prepend .input-group-text {
        background-color: #1d253b;
    }

    .form-control {
        background-color: #1d253b;
    }
</style>
