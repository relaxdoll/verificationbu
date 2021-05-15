<template>
    <div class="form-group has-icon"
         :class="{
            'input-group-focus': focused,
            'has-danger': error,
            'has-success': getSuccess()
    }">
        <div class="mb-0 input-group">
        <span class="input-group-prepend">
          <div class="input-group-text"><i :class="addonLeftIcon"></i></div>
        </span>
            <ejs-combobox ref="combo" v-if="type == 'select'" v-model="value" autofill="true"
                          :allowCustom='false' @focus="focused = true"
                          :dataSource="option" :fields="fields" :enabled="enable"
                          floatLabelType="Never" :placeholder='placeholder' :allowFiltering="allowfilter" :filtering="filtering" :change="onChange"
                          @blur="focused = false"></ejs-combobox>

            <ejs-dropdownlist ref="combo" v-if="type == 'dropdown'" v-model="value" autofill="true"
                          :allowCustom='false' @focus="focused = true"
                          :dataSource="option" :fields="fields" :enabled="enable"
                          floatLabelType="Never" :placeholder='placeholder' :allowFiltering="allowfilter" :filtering="filtering" :change="onChange"
                          @blur="focused = false"></ejs-dropdownlist>

            <ejs-multiselect class="form-control" v-if="type == 'multiselect'" :dataSource='option' v-model="value" autofill="true" :allowCustom='false'
                             :mode="mode" @focus="focused = true" @blur="focused = false" mode="Box" :enabled="enable"
                             :fields='fields' :placeholder="placeholder" floatLabelType="Never" :allowFiltering="allowfilter" :filtering="filtering"
                             :change="onChange"></ejs-multiselect>
        </div>

        <slot name="error" v-if="error && (showError || forceShowError)">
            <label class="error">{{ error }}</label>
        </slot>
        <slot name="helperText"></slot>
    </div>
</template>

<script>


    import {Query} from '@syncfusion/ej2-data';
    import {ComboBoxPlugin} from '@syncfusion/ej2-vue-dropdowns';
    import {MultiSelectPlugin} from "@syncfusion/ej2-vue-dropdowns";
    import {DropDownListPlugin} from "@syncfusion/ej2-vue-dropdowns";
    import {MultiSelect, CheckBoxSelection} from '@syncfusion/ej2-dropdowns';

    MultiSelect.Inject(CheckBoxSelection);
    Vue.use(MultiSelectPlugin);
    Vue.use(ComboBoxPlugin);
    Vue.use(DropDownListPlugin);

    export default {
        props: {
            allowfilter: {default: false},
            filtertype: {required: false},
            placeholder: {default: ''},
            field: {required: false},
            subfield: {required: false},
            // value: {required: false},
            mode: {required: false},
            type: {required: false},
            optiontext: {default: 'text'},
            optionvalue: {default: 'value'},
            optiongroup: {default: false},
            enable: {default: true},
            vparam: {default: false},
            showError: {default: false},
            addonLeftIcon: {
                type: String,
                description: 'Input icon on the left'
            },
            url: {required: false},
            param: {required: false},
            forceoption: {required: false},
            custominput: {required: false}
        },
        data() {
            return {
                inputValue: null,
                focused: false,
                fields: {text: this.optiontext, value: this.optionvalue, groupBy: this.optiongroup},
                touched: false,
                error: null,
                rule: {},
                errorsToCheck: [],
                isInvalid: false,
                forceShowError: false,
                form: null,
                option: null,
                defaultValue: null,
            }
        },
        created() {
            if (this.forceoption) {
                this.option = this.forceoption
            } else {
                this.getOption();
            }
            this.getValidateParam();
        },
        mounted() {
            this.getForm(this);

            this.placeholder = this.placeholder.charAt(0).toUpperCase() + this.placeholder.slice(1);

        },
        computed: {
            value: {
                get() {
                    if (this.option) {
                        return this.$store.getters.getFieldValue({'form': this.form, 'field': this.field});
                    } else {
                        return null;
                    }

                },
                set(value) {
                    if (!this.custominput) {
                        this.$store.commit('clearError', this.field);
                        if (this.subfield) {
                            this.$store.commit('clearError', this.field + '.' + this.subfield);
                        }
                        this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'subfield': this.subfield, 'value': value});
                    }
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
            focused(value) {
                this.touched = true;
                if (value && this.$refs.combo) {
                    this.$refs.combo.showPopup();
                }
            },
            formError(value) {
                this.error = value[0];
                this.$parent.redirectError();
            },
            forceoption(value) {
                this.option = value;
            },

        },
        methods: {
            getForm(component) {
                if (component.$parent.$options.name == 'vForm') {
                    return this.form = component.$parent.name;
                }
                this.getForm(component.$parent);
            },
            getSuccess() {
                switch (this.type) {
                    case 'select':
                        return !this.error && this.touched && this.value;
                    case 'multiselect':
                        if (this.value) {
                            return !this.error && this.touched && this.value.length;
                        } else {
                            return !this.error && this.touched && this.value;
                        }
                }

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
            filtering: function (args) {
                var searchData = this.option;
                var query = new Query();
                //frame the query based on search string with filter type.
                query = (args.text != "") ? query.where(this.optiontext, this.filtertype, args.text, true) : query;
                //pass the filter data source, filter query to updateData method.
                args.updateData(searchData, query);
            },
            onChange(args) {
                this.$emit('change', args);
            },
            onBlur(args) {
                this.$emit('blur', args);
            },
            getOption(url, param = {}) {
                return new Promise((resolve, reject) => {
                    axios.get('/api/' + this.url, {
                        params: this.param
                    })
                        .then(response => {
                            this.option = response.data.data;
                            console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            reset() {

                this.touched = false;
                this.error = null;
                this.isInvalid = false;
                this.forceShowError = false;
                this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'value': null});
            }
        }
    }
</script>
<style>

    .e-float-input.e-control-wrapper input:focus {
        transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        border-width: 1px 1px 1px 0;
        border-style: solid;
        border-color: #e14eca;
        border-top-right-radius: 0.4285rem;
        border-bottom-right-radius: 0.4285rem;
    }

    .has-success > div > span > input:focus {
        border-color: #00bf9a;
    }

    .e-float-input.e-control-wrapper input {
        height: calc(2.24999963rem + 2px) !important;
    }

    .e-input-group {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .e-input-group-icon {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .e-clear-icon {
        padding-top: 10px;
        padding-bottom: 10px;
        margin-right: 10px;
    }

    .has-danger > div > .e-input-group:not(.e-input-focus) {
        background-color: rgba(222, 222, 222, 0.1) !important;
    }

    .has-success > div > .e-input-focus {
        border-color: #00bf9a;
    }

    .has-danger > div > .e-input-focus {
        background-color: transparent;
    }

    .e-input-focus {
        border-color: #e14eca;
    }

    .e-multi-select-wrapper .e-delim-values {
        padding-left: 12px;
        line-height: 32px;
    }

    .e-float-input, .e-float-input.e-control-wrapper {
        line-height: 1.4;
        margin-bottom: 4px;
        margin-top: 0;
    }

    .e-multi-select-wrapper .e-chips {
        margin-top: 4px;
    }

    .e-multi-select-wrapper .e-delim-values {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.75rem;
    }

    .form-control {
        width: 1% !important;
    }

    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: #adb5bd !important;
        font-size: 0.75rem;
        opacity: 1; /* Firefox */
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
        color: #adb5bd !important;
    }

    ::-ms-input-placeholder { /* Microsoft Edge */
        color: #adb5bd !important;
    }

    .e-dropdownbase {
        padding-top: 4px !important;
        color: white;
    }

    .e-multiselect {
        padding-top: 2px !important;
    }

    .e-multi-select-wrapper .e-delim-values {
        padding-left: 0;
    }

    .e-multi-select-wrapper .e-chips-close.e-close-hooker {
        color: #adb5bd;
    }


    .e-input-group input.e-input, .e-input-group.e-control-wrapper input.e-input {
        min-width: 0;
        width: 100%;
        color: rgba(255, 255, 255, 0.8);
        font-family: Poppins, sans-serif;
        font-size: 0.75rem;
    }

    .e-input-group, .e-input-group.e-control-wrapper {
        margin-bottom: 0;
    }

    .e-input-group:not(.e-float-icon-left), .e-input-group.e-success:not(.e-float-icon-left), .e-input-group.e-warning:not(.e-float-icon-left), .e-input-group.e-error:not(.e-float-icon-left), .e-input-group.e-control-wrapper:not(.e-float-icon-left), .e-input-group.e-control-wrapper.e-success:not(.e-float-icon-left), .e-input-group.e-control-wrapper.e-warning:not(.e-float-icon-left), .e-input-group.e-control-wrapper.e-error:not(.e-float-icon-left) {
        border-width: 1px;
        border-left: 0;
        border-radius: 0;
        border-top-right-radius: 0.4285rem;
        border-bottom-right-radius: 0.4285rem;
        height: calc(2.24999963rem + 2px);
        width: 100%;
    }

    input.e-input, .e-input-group input.e-input, .e-input-group.e-control-wrapper input.e-input, textarea.e-input, .e-input-group textarea.e-input, .e-input-group.e-control-wrapper textarea.e-input {
        padding: 6px 12px 6px 0 !important;
        border: 0 !important;
    }


    .e-float-input .e-clear-icon, .e-float-input.e-control-wrapper .e-clear-icon, .e-input-group .e-clear-icon, .e-input-group.e-control-wrapper .e-clear-icon {
        color: #adb5bd;
        font-size: 10px;
        font-weight: 400;
    }

    .e-float-input .e-clear-icon::before,
    .e-float-input.e-control-wrapper .e-clear-icon::before {
        content: "";
        font-family: 'e-icons';
    }

    .e-input-group .e-clear-icon::before,
    .e-input-group.e-control-wrapper .e-clear-icon::before {
        content: "";
        font-family: 'e-icons';
    }

    .e-float-input.e-static-clear .e-clear-icon.e-clear-icon-hide,
    .e-float-input.e-control-wrapper.e-static-clear .e-clear-icon.e-clear-icon-hide,
    .e-input-group.e-static-clear .e-clear-icon.e-clear-icon-hide,
    .e-input-group.e-control-wrapper.e-static-clear .e-clear-icon.e-clear-icon-hide {
        cursor: pointer;
        display: -ms-flexbox;
        display: flex;
    }


    .e-input:not(:valid):first-child ~ .e-clear-icon,
    .e-input-group input.e-input:not(:valid):first-child ~ .e-clear-icon,
    .e-input-group.e-control-wrapper input.e-input:not(:valid):first-child ~ .e-clear-icon,
    .e-float-input input:not(:valid):first-child ~ .e-clear-icon,
    .e-float-input.e-control-wrapper input:not(:valid):first-child ~ .e-clear-icon,
    .e-float-input.e-input-group input:not(:valid):first-child ~ .e-clear-icon,
    .e-float-input.e-input-group.e-control-wrapper input:not(:valid):first-child ~ .e-clear-icon {
        display: none;
    }

    .e-input-group .e-clear-icon.e-clear-icon-hide,
    .e-input-group.e-control-wrapper .e-clear-icon.e-clear-icon-hide {
        display: none;
    }

    .e-input-group .e-input-in-wrap,
    .e-input-group.e-control-wrapper .e-input-in-wrap,
    .e-float-input .e-input-in-wrap,
    .e-float-input.e-control-wrapper .e-input-in-wrap {
        display: -ms-flexbox;
        display: flex;
        position: relative;
        width: 100%;
    }

    .e-input-group,
    .e-input-group.e-control-wrapper {
        display: -ms-inline-flexbox;
        display: inline-flex;
        /*vertical-align: middle;*/
    }

    .e-float-input:not(.e-input-group),
    .e-float-input.e-control-wrapper:not(.e-input-group) {
        display: inline-block;
    }

    .e-input-group .e-input-group-icon,
    .e-input-group.e-control-wrapper .e-input-group-icon {
        display: -ms-flexbox;
        display: flex;
    }

    .e-chips {
        background-color: #1d8cf8 !important;
    }

    .e-chipcontent {
        color: white !important;
        font-size: 0.75rem !important;
    }

    .e-chips-close {
        color: white !important;
    }

    .e-multi-select-wrapper .e-chips .e-chips-close::before {
        color: white;
        font-size: 6px;
        padding-top: 5px;
        margin-left: 6px;
    }

    .e-multi-select-wrapper .e-chips .e-chips-close:hover::before {
        color: #222a42 !important;
    }

    .e-multi-select-wrapper .e-chips .e-chips-close::before {
        content: '\E7A7';
        cursor: pointer;
        left: 0;
        position: relative;
        top: 0;
    }

    .e-active {
        color: #ba54f5 !important;
        font-weight: 600;
    }

    .e-popup-open {
        margin-left: -36px !important;
    }

    .e-chips-close {
        cursor: pointer;
    }
    .e-input-group.e-ddl, .e-input-group.e-ddl .e-input, .e-input-group.e-ddl .e-ddl-icon {
        background-color: #1d253b;
    }
</style>
