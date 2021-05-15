<template>
    <div>

        <selector-icon :error="error" :islast="islast" @click="touchSelector()" :isgrouped="isgrouped" :disable="disable" :icon="icon" :iconcolor="iconcolor" :label="label"
                       :valuetext="valueText"></selector-icon>

        <label v-show="error" style="font-size: 11px; color: red; margin: 0 0 0 15px;">{{ error }}</label>

        <liffdrawer2 @close="selected = false" align="right" :maskclosable="true" :label="label" :currentview="$store.page_name"
                     :closeable="true">

            <div v-if="selected">

                <lifftopinput v-if="searchbar" placeholder="Search" :value.sync="searchText"
                              @input="searchText = $event"/>

                <liffgroup style="margin-bottom:60px;">
                    <liffoption @click="select(option)" v-for="(option, index) in options" :key="index"
                                :text="(optiontext)?option[optiontext]:option"
                                :selected="isSelected(option)"></liffoption>
                </liffgroup>

                <liffgroup v-if="disable" style="margin-top:-40px;">
                    <liffoption v-for="(option, index) in disable" :key="index" :value="option+' (Coming Soon)'" :disabled="true"></liffoption>
                </liffgroup>

            </div>

        </liffdrawer2>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        name: 'liff-dropdown',
        props: {
            label: {required: true},
            optiontext: {required: false},
            optionvalue: {required: false},
            closeonselect: {default: true},
            icon: {required: false},
            iconcolor: {required: false},
            disable: {default: false},
            url: {required: false},
            param: {required: false},
            showError: {default: false},
            searchbar: {default: false},
            forceoption: {required: false},
            hasforceoption: {required: false},
            custominput: {required: false},
            vparam: {required: true},
            field: {required: true},
            subfield: {required: false},
            allowgetoption: {default: true},
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
                options: null,
                defaultValue: null,
                isgrouped: false,
                islast: false,
                selected: false,
                valueText: '',
            }
        },
        created() {
            if (this.forceoption) {
                this.options = this.forceoption
            } else {
                if (this.allowgetoption) {
                    this.getOption();
                }
            }
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
                    if (this.options) {
                        return this.$store.getters.getFieldValue({'form': this.form, 'field': this.field, 'subfield': this.subfield});
                    } else {
                        return null;
                    }

                },
                set(value) {
                    if (!this.custominput) {
                        this.$store.commit('clearError', this.field);
                        this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'subfield': this.subfield, 'value': value});
                    }
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
                this.options = value;
            },
            allowgetoption(value) {
                if (value) {
                    this.getOption();
                }
            },


        },
        methods: {
            getForm(component) {
                if (component.$parent.$options.name == 'LiffForm') {
                    return this.form = component.$parent.name;
                }
                this.getForm(component.$parent);
            },
            touchSelector() {
                if (!this.disable) {
                    this.selected = true;
                    this.touched = true;
                }
            },
            select(option) {
                if (this.optionvalue) {
                    this.value = option[this.optionvalue];
                    this.valueText = option[this.optiontext];
                } else {
                    this.value = option;
                    this.valueText = option;
                }

                this.$emit('input', option);

                if (this.closeonselect) {
                    new Promise(function (resolve, reject) {
                        setTimeout(function () {
                            resolve('foo');
                        }, 150);
                    })
                        .then(response => {
                            this.selected = false;
                        })
                }
                // this.selected = false;
            },
            isSelected(option) {

                if (this.optionvalue) {
                    return this.value === option[this.optionvalue];
                } else {
                    return this.value === option;
                }
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
            onChange(args) {
                this.$emit('change', args);
            },
            onBlur(args) {
                this.$emit('blur', args);
            },
            getOption() {
                return new Promise((resolve, reject) => {
                    axios.get('/api/' + this.url, {
                        params: this.param
                    })
                        .then(response => {
                            this.options = response.data.data;
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
    };
</script>
<style>

</style>
