<template>
    <div>

        <div @click.self="showPicker = false" v-show="showPicker" style="top:0; position:fixed; z-index:1; width: 100vw; height: 100vh; background-color: transparent;"></div>

        <selector-icon :error="error" :islast="islast" @click="showPicker = true" :isgrouped="isgrouped" :disable="disable" :icon="icon" :iconcolor="iconcolor"
                       :label="label"
                       :valuetext="dateString"></selector-icon>

        <transition name="slide">
            <date-picker locale="th" v-show="showPicker" v-model='pickerDate' is-inline/>
        </transition>

    </div>
</template>
<script>
    import {mapState} from 'vuex'


    export default {
        name: 'liff-datepicker',
        props: {
            label: {required: true},
            icon: {required: false},
            iconcolor: {required: false},
            disable: {default: false},
            showError: {default: false},
            vparam: {required: true},
            field: {required: true},
            subfield: {required: false},
        },

        data() {
            return {
                focused: false,
                fields: {text: this.optiontext, value: this.optionvalue, groupBy: this.optiongroup},
                touched: false,
                error: null,
                rule: {},
                errorsToCheck: [],
                isInvalid: false,
                forceShowError: false,
                form: null,
                isgrouped: false,
                islast: false,

                showPicker: false,
                dateString: null,

                pickerDate: null,
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

            this.pickerDate = this.value;
            this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'subfield': this.subfield, 'value': moment(this.pickerDate).toISOString()});

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
                    this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'subfield': this.subfield, 'value': moment(value).format('YYYY-MM-DD HH:mm:ss')});
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
            getOption(url, param = {}) {
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
