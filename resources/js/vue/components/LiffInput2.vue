<template>
    <div>

        <div class="input-theme">
            <div class="text-wrapper" style="display:flex;" :class="{'bottom-border':islast || !isgrouped || error, 'top-border': !isgrouped}">
                <div style="width: 54px; display:flex; height: 50px; padding:13px 15px;" :class="{'disable':disable}">
                    <div class="left-icon" :style="'background-color: '+iconcolor+';'">
                        <i style="font-size:14px;" :class="icon"></i>
                    </div>
                </div>
                <div style="width:100%; display:flex" :class="{'bottom-border':!islast && isgrouped && !error}">
                    <div style="padding:1px 0; display:flex; height: 50px; line-height:16px; color:black; width:100%;" :class="{'disable':disable}">
                        <input v-if="(type === 'text')" type="text" :placeholder="placeholder" v-model="value" class="liff-input"/>

                        <input v-if="(type === 'number')" type="number" pattern="[0-9]*" :placeholder="placeholder" v-model="value" class="liff-input"/>

                    </div>
                    <div style="padding:0; display:flex; height: 50px; width: 50px; right:0;">
                        <span v-if="(type !== 'picker2')" v-show="value" class="liff-close-icon">&#x00d7;</span>
                        <span v-if="(type !== 'picker2')" v-show="value" @click="value = null" class="liff-close-icon-field"/>
                    </div>
                </div>
            </div>
        </div>

        <label v-show="error" style="font-size: 11px; color: red; margin: 0 0 0 15px;">{{ error }}</label>

    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        name: 'liff-input',
        props: {
            disabled: {required: false},
            icon: {required: false},
            iconcolor: {required: false},
            disable: {default: false},
            vparam: {default: false},
            showError: {default: false},
            field: {required: true},
            subfield: {required: false},

            placeholder: {required: false},
            type: {default: 'text'},
            mask: {required: false},
            maxlength: {required: false},
        },

        data() {
            return {
                inputValue: null,
                focused: false,
                touched: false,
                error: null,
                rule: {},
                errorsToCheck: [],
                isInvalid: false,
                forceShowError: false,
                form: null,
                defaultValue: null,
                isgrouped: false,
                islast: false,
            }
        },
        created() {
            this.getValidateParam();
        },
        mounted() {

            this.getForm(this);

            if (this.$parent.$options.name == 'liffInputGroup') {
                this.isgrouped = true;

                let indexId = [];
                this.$parent.$children.forEach(child => {
                    indexId.push(child._uid)
                });
                this.islast = this._uid === indexId.slice(-1)[0];
            }
        },
        computed: {
            ...mapState([
                'validate',
            ]),

            value: {
                get() {
                    return this.$store.getters.getFieldValue({'form': this.form, 'field': this.field, 'subfield': this.subfield});
                },
                set(value) {
                    this.$store.commit('clearError', this.field);
                    this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'subfield': this.subfield, 'value': value});
                    this.$emit('input', value);
                }
            },
            formError() {
                return this.$store.getters.getFieldError(this.field);
            }
        },
        validations() {
            return {
                value: this.rule
            }
        },
        watch: {
            value(value) {
                if (this.touched) {
                    this.checkValidateError();
                }
            },
            formError(value) {
                this.error = value[0];
                this.$parent.redirectError();
            },
            vparam(value){
                this.rule = {};
                this.errorsToCheck = [];
                this.getValidateParam();
            }

        },
        methods: {
            getForm(component) {
                if (component.$parent.$options.name == 'LiffForm') {
                    return this.form = component.$parent.name;
                }
                this.getForm(component.$parent);
            },
            getValidateParam() {
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
                                            return this.error = 'This field must be more or equal to ' + param[key];
                                        case 'maxValue':
                                            return this.error = 'This field must be less or equal to ' + param[key];
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
<style scopoed>

    .input-label {
        font-weight: 400;
        color: black;
    }

    .input-theme {
        /*display: table;*/
        /*padding: 0 20px;*/
        height: 50px;
        width: 100vw;
        background-color: white;
    }

    .text-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .left-icon {
        width: 24px;
        height: 24px;
        padding-top: 2px;
        text-align: center;
        font-size: 14px;
        border-radius: 5px;
        color: white;
    }

    .disable {
        opacity: 0.3;
    }

    .bottom-border {
        border-bottom: #DDDDDD 1px solid;
    }

    .top-border {
        border-top: #DDDDDD 1px solid;
    }

    .liff-close-icon-field {
        display: block;
        width: 50px;
        height: 50px;
        position: absolute;
        background-color: transparent;
        z-index: 2;
        right: 0;
        cursor: pointer;
    }

    .liff-close-icon {
        display: block;
        width: 15px;
        height: 15px;
        position: absolute;
        background-color: #CBCCCB;
        z-index: 1;
        right: 25px;
        margin-top: 17.5px;
        line-height: 16px;
        border-radius: 50%;
        text-align: center;
        color: white;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
    }

    .liff-input {
        width: 100%;
        border: none;
        padding: 0;
        font-weight: 300;
        height: 48px;
    }
</style>
