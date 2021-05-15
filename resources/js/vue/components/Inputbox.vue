<template>
    <div class="row" style="margin-top: 12px; margin-bottom: 12px;">
        <label class="col-sm-3 col-form-label form-label-right">{{ label }}</label>
        <div :class="'col-xxl-7 col-sm-'+8">
            <div class="e-float-input" v-if="type == 'text' || type == 'string'">
                <input type="text" :name="placeholder" v-model="inputValue" class="e-field" required/>
                <!--<span class="e-float-line"></span>-->
                <label class="e-float-text">{{ placeholder }}</label>
            </div>

            <div class="e-float-input" v-if="type == 'textarea'">
                <textarea :name="placeholder" type="text" v-model='inputValue' required></textarea>
                <!--<span class="e-float-line"></span>-->
                <label class="e-float-text">{{ placeholder }}</label>
            </div>

            <div class="e-float-input" v-if="type == 'number'">
                <input type="number" :name="placeholder" v-model="inputValue" required/>
                <!--<span class="e-float-line"></span>-->
                <label class="e-float-text">{{ placeholder }}</label>
            </div>

            <div class="e-float-input" v-if="type == 'file' || type == 'image'" v-show="!isSelect">
                <input type="text" v-model="fileName" required @focus="isSelect = true"/>
                <!--<span class="e-float-line"></span>-->
                <label class="e-float-text">{{ placeholder }}</label>
            </div>

            <div class="e-float-input" v-if="type == 'file' || type == 'image'" v-show="isSelect">
                <input style="padding-left: 8px;"
                       type="file" ref="file" :name="placeholder" v-on:change="handleFileUpload()" @blur="isSelect = false"/>
                <!--<span class="e-float-line"></span>-->
                <span class="e-clear-icon close-icon" v-show="fileName" @click="clearFile"></span>
                <label class="e-float-text">{{ placeholder }}</label>
            </div>

            <ejs-textbox v-if="type == 'readonly'" floatLabelType="Always" :placeholder="placeholder" v-model="inputValue"
                         :readonly="true"></ejs-textbox>

            <ejs-textbox v-if="type == 'disabled'" floatLabelType="Always" :placeholder="placeholder" v-model="inputValue"
                         :enabled="false"></ejs-textbox>

            <ejs-datepicker ref="date" v-if="type == 'date'" strictMode='true' :placeholder='placeholder'
                            v-model="inputValue" @focus="dateFocus()" :enabled="enable"></ejs-datepicker>

            <ejs-daterangepicker ref="date" v-if="type == 'daterange'" floatLabelType="Auto" strictMode='true' :placeholder='placeholder'
                                 v-model="inputValue" @focus="dateFocus()"></ejs-daterangepicker>

            <ejs-datetimepicker ref="date" v-if="type == 'datetime'" floatLabelType="Auto" strictMode='true' :placeholder='placeholder'
                                v-model="inputValue" @focus="dateFocus()"></ejs-datetimepicker>

            <ejs-combobox ref="combo" v-if="type == 'select' || type == 'relation' || type == 'dropdown'" v-model="inputValue" autofill="true"
                          :allowCustom='false' @focus="selectFocus()"
                          :dataSource="option" :fields="fields" :enabled="enable"
                          floatLabelType="Auto" :placeholder='placeholder' :allowFiltering="allowfilter" :filtering="filtering" :change="onChange"
                          :blur="onBlur"></ejs-combobox>

            <ejs-multiselect v-if="type == 'multiselect'" :dataSource='option' v-model="inputValue" autofill="true" :allowCustom='false'
                             :mode="mode"
                             :fields='fields' :placeholder="placeholder" floatLabelType="Auto" :allowFiltering="allowfilter" :filtering="filtering"
                             :change="onChange"></ejs-multiselect>

            <ejs-numerictextbox v-if="type == 'integer'" format=",###" floatLabelType="Auto" :placeholder="placeholder" decimals="0"
                                v-model="inputValue" validateDecimalOnType="true"></ejs-numerictextbox>

            <ejs-numerictextbox v-if="type == 'decimal'" format=",###.########" floatLabelType="Auto" :placeholder="placeholder"
                                :decimals="decimal" v-model="inputValue" validateDecimalOnType="true" :enabled="enable"></ejs-numerictextbox>

            <ejs-numerictextbox v-if="type == 'currency'" format="฿ ,###.00" floatLabelType="Auto" :placeholder="placeholder" :decimals="decimal"
                                v-model="inputValue" validateDecimalOnType="true" :enabled="enable"></ejs-numerictextbox>

            <ejs-numerictextbox v-if="type == 'percentage'" format="p2" floatLabelType="Auto" :placeholder="placeholder" :decimals="decimal"
                                v-model="inputValue" step="0.01" validateDecimalOnType="true"></ejs-numerictextbox>

            <ejs-maskedtextbox v-if="type == 'pv'" mask='PV 99 99 999' :placeholder='placeholder' floatLabelType='Auto' promptChar="#"
                               v-model="inputValue" :enabled="enable"></ejs-maskedtextbox>

            <ejs-maskedtextbox v-if="type == 'cheque'" mask='99 999 999' :placeholder='placeholder' floatLabelType='Auto' promptChar="#"
                               v-model="inputValue" :enabled="enable"></ejs-maskedtextbox>

            <ejs-maskedtextbox v-if="type == 'phone'" mask='\\+00 90 000 0000' :placeholder='placeholder' floatLabelType='Auto' promptChar="#"
                               v-model="inputValue"></ejs-maskedtextbox>

            <ejs-maskedtextbox v-if="type == 'time'" mask='00:00:00' :placeholder='placeholder' floatLabelType='Auto' promptChar="#"
                               v-model="inputValue" :enabled="enable"></ejs-maskedtextbox>

            <div class="e-float-input" v-if="specialType == 'textSelect' || specialType == 'selectText'" v-show="specialType == 'textSelect'">
                <input type="text" :name="placeholder" v-model="inputValue" required @focus="changeType()"/>
                <!--<span class="e-float-line"></span>-->
                <label class="e-float-text">{{ placeholder }}</label>
            </div>

            <div v-if="specialType == 'textSelect' || specialType == 'selectText'" v-show="specialType == 'selectText'">
                <ejs-combobox ref="combo" v-model="inputValue" autofill="true" :allowCustom='false'
                              :dataSource="option" :fields="fields" :enabled="enable"
                              floatLabelType="Auto" :placeholder='placeholder' :allowFiltering="allowfilter" :filtering="filtering" :change="onChange"
                              :blur="onSelectBlur"></ejs-combobox>
            </div>

            <div v-if="type == 'checkbox'" class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" v-model="inputValue" :disabled="!enable">
                    {{ placeholder }}
                    <span class="form-check-sign"><span class="check"></span></span>
                </label>
            </div>

            <span class="error text-danger" v-if="error">{{ errorvalue }}</span>
        </div>
    </div>
</template>

<script>

    function isDate(x) {
        return (null != x) && !isNaN(x) && ("undefined" !== typeof x.getDate);
    }

    import {Query} from '@syncfusion/ej2-data';
    import {DatePickerPlugin, DateRangePickerPlugin} from '@syncfusion/ej2-vue-calendars';
    import {ComboBoxPlugin} from '@syncfusion/ej2-vue-dropdowns';
    import {NumericTextBoxPlugin} from "@syncfusion/ej2-vue-inputs";
    import {MaskedTextBoxPlugin} from "@syncfusion/ej2-vue-inputs";
    import {SplitButtonPlugin} from "@syncfusion/ej2-vue-splitbuttons";
    import {TextBoxPlugin} from '@syncfusion/ej2-vue-inputs';
    import {MultiSelectPlugin} from "@syncfusion/ej2-vue-dropdowns";
    import {MultiSelect, CheckBoxSelection} from '@syncfusion/ej2-dropdowns';
    import {DateTimePickerPlugin} from '@syncfusion/ej2-vue-calendars';

    MultiSelect.Inject(CheckBoxSelection);
    Vue.use(MultiSelectPlugin);
    Vue.use(TextBoxPlugin);
    Vue.use(SplitButtonPlugin);
    Vue.use(NumericTextBoxPlugin);
    Vue.use(MaskedTextBoxPlugin);
    Vue.use(DatePickerPlugin);
    Vue.use(ComboBoxPlugin);
    Vue.use(DateRangePickerPlugin);
    Vue.use(DateTimePickerPlugin);

    export default {
        data() {
            return {
                inputValue: this.value,
                specialType: null,
                fileName: null,
                isSelect: false,
                fields: {text: this.optiontext, value: this.optionvalue, groupBy: this.optiongroup},
            }
        },
        created() {
            if (this.type == 'textSelect') {
                this.specialType = 'textSelect';
            }
        },
        mounted() {
            this.placeholder = this.placeholder.charAt(0).toUpperCase() + this.placeholder.slice(1);

        },
        updated() {
            if (!this.inputValue && this.type == 'textSelect') {
                this.inputValue = this.value;
            }
        },
        props: {
            allowfilter: {default: false},
            filtertype: {required: false},
            placeholder: {default: ''},
            label: {required: false},
            value: {required: false},
            mode: {required: false},
            digit: {required: false},
            decimal: {required: false},
            type: {required: false},
            option: {required: false},
            optiontext: {default: 'text'},
            optionvalue: {default: 'value'},
            optiongroup: {default: false},
            enable: {default: true},
            error: {default: false},
            errorvalue: {default: null},
        },
        watch: {
            inputValue(newValue, oldValue) {
                if (this.type == 'textSelect') {
                    if (newValue) {
                        this.$emit('input', newValue);
                    }
                    this.$emit('clear');
                } else {
                    this.$emit('input', newValue);
                    this.$emit('clear');
                }
            },
            value(newValue) {

                if (this.type == 'textSelect') {
                    new Promise(function (resolve, reject) {
                        setTimeout(function () {
                            resolve('foo');
                        }, 0);
                    })
                        .then(response => {
                            if (newValue != this.inputValue) {
                                this.specialType = 'textSelect';
                            }
                        })
                        .then(response => {
                            this.inputValue = newValue;
                        })
                } else {

                    this.inputValue = newValue;

                    if (!newValue && (this.type == 'file' || this.type == 'image')) {
                        this.$refs.file.value = null;
                        this.fileName = null;
                    }
                }
            }
        },
        methods: {
            changeType() {
                new Promise(function (resolve, reject) {
                    setTimeout(function () {
                        resolve('foo');
                    }, 0);
                })
                    .then(response => {
                        this.setSelect()
                    })
                    .then(response => {
                        this.$refs.combo.showPopup();
                        this.$refs.combo.focusIn();
                    })
            },
            setSelect() {
                this.specialType = 'selectText';

                return true;
            },
            onSelectBlur() {
                if ((this.value == this.inputValue && typeof this.value == 'string') || !this.inputValue) {
                    this.specialType = 'textSelect';
                }
                this.$emit('blur');
            },
            dateFocus() {
                this.$refs.date.show();
            },
            selectFocus() {
                this.$refs.combo.showPopup();
            },
            handleFileUpload() {
                this.inputValue = this.$refs.file.files[0];
                this.fileName = this.$refs.file.value;
                this.$emit('input', this.exportValue);
            },
            clearFile() {
                this.inputValue = this.$refs.file.value = null;
                this.isSelect = false;
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
            }
        }
    }
</script>
<style>
    .close-icon {
        display: block;
        width: 15px;
        height: 15px;
        position: absolute;
        background-color: transparent;
        z-index: 1;
        right: 5px;
        top: 0;
        bottom: 0;
        margin: auto;
        padding: 2px;
        border-radius: 50%;
        text-align: center;
        color: #dbdbdb;
        font-weight: normal;
        font-size: 12px;
        cursor: pointer;
    }

    .e-input-group-icon.e-date-icon::before, .e-control-wrapper .e-input-group-icon.e-date-icon::before {
        font-size: 15px;
        margin: 0 10px;
    }

    .e-ddl.e-input-group.e-control-wrapper .e-ddl-icon::before {
        margin: 0 10px;
    }

    .e-input-group-icon.e-date-ico.e-activen::before, .e-control-wrapper .e-input-group-icon.e-date-icon.e-active::before {
        color: #42a5f5;
    }

    .e-field {
        padding: 6px 12px;
    }

    .e-float-input {
        margin: 0;
    }

    .e-float-input input,
    .e-float-input textarea,
    .e-float-input.e-control-wrapper input,
    .e-float-input.e-control-wrapper textarea {
        border-width: 1px;
        border-radius: 4px;
        padding: 6px 12px !important;
        color: #495057;
        width: calc(100% - 26px);
        height: 21px;
    }

    .e-input[disabled],
    .e-input-group .e-input[disabled],
    .e-input-group.e-control-wrapper .e-input[disabled],
    .e-input-group.e-disabled,
    .e-input-group.e-control-wrapper.e-disabled,
    .e-float-input input[disabled],
    .e-float-input.e-control-wrapper input[disabled],
    .e-float-input.e-disabled,
    .e-float-input.e-control-wrapper.e-disabled {
        background: #f9f9f9;
        color: #afafaf;
        background-image: linear-gradient(90deg, rgba(0, 0, 0, 0.42) 0, rgba(0, 0, 0, 0.42) 33%, transparent 0);
        background-position: bottom -1px left 0;
        background-repeat: repeat-x;
        background-size: 4px 1px;
        /*border-bottom-color: transparent !important;*/
        border-color: #2b3553 !important;
        box-shadow: none !important;
    }

    .e-input-group:not(.e-float-icon-left),
    .e-input-group.e-success:not(.e-float-icon-left),
    .e-input-group.e-warning:not(.e-float-icon-left),
    .e-input-group.e-error:not(.e-float-icon-left),
    .e-input-group.e-control-wrapper:not(.e-float-icon-left),
    .e-input-group.e-control-wrapper.e-success:not(.e-float-icon-left),
    .e-input-group.e-control-wrapper.e-warning:not(.e-float-icon-left),
    .e-input-group.e-control-wrapper.e-error:not(.e-float-icon-left) {
        border-width: 1px;
        border-radius: 4px;
        /*padding: 6px 12px !important;*/
        height: 35px;
        width: 100%;
    }

    .e-float-input:not(.e-success):not(.e-warning):not(.e-error):not(.e-input-group) input:focus,
    .e-float-input:not(.e-success):not(.e-warning):not(.e-error):not(.e-input-group) textarea:focus,
    .e-float-input.e-control-wrapper:not(.e-success):not(.e-warning):not(.e-error):not(.e-input-group) input:focus,
    .e-input-group.e-input-focus:not(.e-float-icon-left):not(.e-success):not(.e-warning):not(.e-error),
    .e-input-group.e-control-wrapper.e-input-focus:not(.e-float-icon-left):not(.e-success):not(.e-warning):not(.e-error),
    .e-float-input.e-control-wrapper:not(.e-success):not(.e-warning):not(.e-error):not(.e-input-group) textarea:focus {
        transition: color 0.3s ease-in-out, border-color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        border: 1px solid #e14eca !important;
    }

    input.e-input,
    .e-input-group input.e-input,
    .e-input-group.e-control-wrapper input.e-input,
    textarea.e-input,
    .e-input-group textarea.e-input,
    .e-input-group.e-control-wrapper textarea.e-input {
        padding: 6px 12px !important;
    }

    .e-input-group .e-input-group-icon,
    .e-input-group.e-control-wrapper .e-input-group-icon {
        /*margin-bottom: 8px;*/
        margin-right: 4px;
        margin-top: 4px;
    }

    .e-input-group:not(.e-float-icon-left):not(.e-float-input).e-input-focus::before,
    .e-input-group:not(.e-float-icon-left):not(.e-float-input).e-input-focus::after,
    .e-input-group.e-float-icon-left:not(.e-float-input).e-input-focus .e-input-in-wrap::before,
    .e-input-group.e-float-icon-left:not(.e-float-input).e-input-focus .e-input-in-wrap::after,
    .e-input-group.e-control-wrapper:not(.e-float-icon-left):not(.e-float-input).e-input-focus::before,
    .e-input-group.e-control-wrapper:not(.e-float-icon-left):not(.e-float-input).e-input-focus::after,
    .e-input-group.e-control-wrapper.e-float-icon-left:not(.e-float-input).e-input-focus .e-input-in-wrap::before,
    .e-input-group.e-control-wrapper.e-float-icon-left:not(.e-float-input).e-input-focus .e-input-in-wrap::after {
        width: 0;
    }

    .e-float-input.e-input-group:not(.e-float-icon-left).e-input-focus .e-float-line::before,
    .e-float-input.e-input-group:not(.e-float-icon-left).e-input-focus .e-float-line::after,
    .e-float-input.e-input-group.e-float-icon-left.e-input-focus .e-input-in-wrap .e-float-line::before,
    .e-float-input.e-input-group.e-float-icon-left.e-input-focus .e-input-in-wrap .e-float-line::after,
    .e-float-input.e-control-wrapper.e-input-group:not(.e-float-icon-left).e-input-focus .e-float-line::before,
    .e-float-input.e-control-wrapper.e-input-group:not(.e-float-icon-left).e-input-focus .e-float-line::after,
    .e-float-input.e-control-wrapper.e-input-group.e-float-icon-left.e-input-focus .e-input-in-wrap .e-float-line::before,
    .e-float-input.e-control-wrapper.e-input-group.e-float-icon-left.e-input-focus .e-input-in-wrap .e-float-line::after {
        width: 0;
    }

    .e-calendar .e-content td.e-today span.e-day,
    .e-calendar .e-content td.e-focused-date.e-today span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-today span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-focused-date.e-today span.e-day {
        background: none;
        border: 1px solid #42a5f5;
        border-radius: 50%;
        color: #42a5f5;
    }

    .e-calendar .e-content td.e-focused-date.e-today span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-focused-date.e-today span.e-day {
        background: rgba(225, 245, 254, 0.4);
        border: 1px solid #42a5f5;
        color: #42a5f5;
    }

    .e-calendar .e-content td.e-today:focus span.e-day,
    .e-calendar .e-content td.e-focused-date.e-today:focus span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-today:focus span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-focused-date.e-today:focus span.e-day {
        background-color: rgba(225, 245, 254, 0.4);
        border: none;
        border-radius: 50%;
        color: #42a5f5;
    }

    .e-calendar .e-content td.e-today:hover span.e-day,
    .e-calendar .e-content td.e-focused-date.e-today:hover span.e-day,
    .e-calendar .e-content td.e-focused-date.e-today:focus span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-today:hover span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-focused-date.e-today:hover span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-focused-date.e-today:focus span.e-day {
        background-color: #eee;
        border: 1px solid #42a5f5;
        color: #42a5f5;
    }

    .e-calendar .e-content td.e-today.e-selected span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-today.e-selected span.e-day {
        background-color: #42a5f5;
        border: 1px solid #42a5f5;
        box-shadow: inset 0 0 0 2px #fff;
        color: #fff;
    }

    .e-calendar .e-content td.e-selected span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-selected span.e-day {
        background-color: #42a5f5;
        border: none;
        border-radius: 50%;
        color: #fff;
    }

    .e-calendar .e-content .e-footer,
    .e-bigger.e-small .e-calendar .e-content .e-footer {
        color: #42a5f5;
    }

    .e-calendar .e-content td.e-today.e-selected:hover span.e-day,
    .e-calendar .e-content td.e-selected:hover span.e-day,
    .e-calendar .e-content td.e-selected.e-focused-date span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-today.e-selected:hover span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-selected:hover span.e-day,
    .e-bigger.e-small .e-calendar .e-content td.e-selected.e-focused-date span.e-day {
        background-color: #1565c0;
        color: #fff;
    }

    .e-btn.e-flat.e-primary,
    .e-css.e-btn.e-flat.e-primary {
        background-color: transparent;
        border-color: transparent;
        color: #42a5f5;
    }

    .e-btn.e-flat.e-primary:hover,
    .e-css.e-btn.e-flat.e-primary:hover {
        background-color: rgba(225, 245, 254, 0.4);
        border-color: transparent;
        color: #42a5f5;
    }

    .e-numeric-hidden {
        display: none !important;
    }

    .e-float-input:not(.e-input-group) .e-float-line::before,
    .e-float-input:not(.e-input-group) .e-float-line::after,
    .e-float-input.e-control-wrapper:not(.e-input-group) .e-float-line::before,
    .e-float-input.e-control-wrapper:not(.e-input-group) .e-float-line::after {
        height: 0;
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
</style>
