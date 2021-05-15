<template>
    <div>

        <liffloader :show="processing" text="Processing"/>

        <div style="margin-top: 20px;">
            <div class="option-wrapper">

                <div>

                    <div :class="(darkmode)? 'input-theme-dark' : 'input-theme'">
                        <div class="text-wrapper">
                            <div class="row">
                                <div :class="(darkmode)? 'col-6 input-label-dark':'col-6 input-label'">
                                    Date
                                </div>
                                <div :class="(darkmode)? 'col-5 current-option-dark':'col-5 current-option'">
                                    {{getDate}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="purchaseData.paymentType === 'Credit Card' " style="margin-top: 20px;">

                        <div :class="(darkmode)? 'input-theme-dark' : 'input-theme'">
                            <div class="text-wrapper">
                                <div class="row">
                                    <div :class="(darkmode)? 'col-6 input-label-dark':'col-6 input-label'">
                                        Payment Method
                                    </div>
                                    <div :class="(darkmode)? 'col-5 current-option-dark':'col-5 current-option'">
                                        {{purchaseData.paymentType}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div :class="(darkmode)? 'input-theme-dark' : 'input-theme'" style="margin-top: 20px;">
                            <div class="text-wrapper">
                                <div class="row">
                                    <div :class="(darkmode)? 'col-6 input-label-dark':'col-6 input-label'">
                                        {{purchaseData.paymentType}}
                                    </div>
                                    <div :class="(darkmode)? 'col-5 current-option-dark':'col-5 current-option'">
                                        {{purchaseData.credit_card_data.number}}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div v-if="purchaseData.paymentType === 'Internet Banking'" style="margin-top: 20px;">

                        <div :class="(darkmode)? 'input-theme-dark' : 'input-theme'">
                            <div class="text-wrapper">
                                <div class="row">
                                    <div :class="(darkmode)? 'col-6 input-label-dark':'col-6 input-label'">
                                        Payment Method
                                    </div>
                                    <div :class="(darkmode)? 'col-5 current-option-dark':'col-5 current-option'">
                                        {{purchaseData.paymentType}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div :class="(darkmode)? 'input-theme-dark' : 'input-theme'" style="margin-top: 20px;">
                            <div class="text-wrapper">
                                <div class="row">
                                    <div :class="(darkmode)? 'col-6 input-label-dark':'col-6 input-label'">
                                        {{purchaseData.paymentType}}
                                    </div>
                                    <div :class="(darkmode)? 'col-5 current-option-dark':'col-5 current-option'">
                                        {{purchaseData.internet_banking_data.name}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!--End of Payment Details-->

                <!--Order-->
                <div style="margin: 20px 0 0 0; background-color: white;">
                    <!-- Order Header-->
                    <div class="row order-header">
                        <div class="col-4">
                            Plan
                        </div>
                        <div class="col-4">
                            Amount
                        </div>
                        <div class="col-4">
                            Price
                        </div>
                    </div>
                    <!--Order Details-->
                    <div v-if="productData.monthly_price != null">
                        <div class="row order-content">
                            <div class="col-4">
                                {{productData.level}}
                            </div>
                            <!--Make it dynamically-->
                            <div class="col-4">
                                1
                            </div>
                            <div class="col-4">
                                {{productData.monthly_price}}
                            </div>
                        </div>
                        <div class="row order-content">
                            <div class="col-4">
                                Prorating
                            </div>
                            <!--Make it dynamically-->
                            <div class="col-4">

                            </div>
                            <div class="col-4">
                                -{{productData.monthly_price - productData.price}}
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <div class="row order-content">
                            <div class="col-4">
                                {{productData.level}}
                            </div>
                            <!--Make it dynamically-->
                            <div class="col-4">
                                1
                            </div>
                            <div class="col-4">
                                {{productData.price}}
                            </div>
                        </div>
                    </div>
                    <!--Order Summary-->
                    <div class="row order-summary">
                        <div class="col-4">Total</div>
                        <div class="col-4">1</div>
                        <div class="col-4">{{productData.price}}</div>
                    </div>
                </div>
                <!--End of Order-->
            </div>
        </div>

        <liffbottombutton @confirm="submit" label="Confirm"/>
    </div>
</template>
<script>
    export default {
        props: {
            purchaseData: {required: true},
            productData: {required: true},
            darkmode: {default: false}
        },

        data() {
            return {
                currentOption: null,
                selected: false,
                date: null,
                processing: false,
            };
        },

        mounted() {
            this.currentOption = this.selectedoption;
            this.selected = this.isselect;
        },
        methods: {
            select(option) {
                this.currentOption = option;
                this.$emit('input', option);
                // this.selected = false;
            },
            submit() {
                this.$emit('confirm', true);
                this.processing = true;
                // this.selected = false;
            }
        },
        computed: {
            getDate() {
                return Date().substring(8, 10) + ' ' + Date().substring(4, 7) + ' ' + Date().substring(11, 15);
            }
        }
    };
</script>

<style scoped>
    .col-1, col-2, col-3, col-4, col-5, col-6, col-7, col-8, col-9, col-10, col-11, col-12 {
        padding-left: 0;
        padding-right: 0;
    }

    .input-theme {
        display: table;
        padding: 0 20px;
        height: 60px;
        width: 100vw;
        background-color: rgb(59, 55, 54);
    }

    .input-theme-dark {
        display: table;
        padding: 0 20px;
        height: 60px;
        width: 100vw;
        background-color: #3B3736;
    }

    .input-label {
        text-align: left;
        color: rgb(255, 210, 90);
        font-weight: 500;
    }

    .input-label-dark {
        font-weight: 400;
        color: #FFD25A;
    }

    .current-option {
        text-align: right;
        font-weight: 300;
        color: #929292;
        padding-right: 0;
    }

    .current-option-dark {
        text-align: right;
        font-weight: 300;
        color: #BBBBBB;
        padding-right: 0;
    }

    .next {
        text-align: left;
        padding-left: 10px;
        color: #DDDDDD;
        font-size: 25px;
        font-family: 'BenchNine', sans-serif;
    }

    .next-dark {
        text-align: left;
        padding-left: 10px;
        color: #777777;
        font-size: 25px;
        font-family: 'BenchNine', sans-serif;
    }

    .option-wrapper {
        /*border-top: #DDDDDD 0.8px solid;*/
        /*border-bottom: #DDDDDD 0.8px solid;*/

        color: #5b5553;
        font-family: "Roboto", "Helvetica", "Arial", sans-serif;
        font-size: 16px;
        font-weight: 400;
    }

    .select-option {
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
    }

    .text-wrapper {
        display: table-cell;
        vertical-align: middle;
        text-align: left;
    }

    .text-option {
        padding-left: 15px;
        text-align: left;
        font-weight: 400;
        color: #5b5553;
    }

    .checked {
        text-align: left;
        padding-left: 20px;
        color: #2D7AFF;
    }

    .disabled {
        color: #BBBBBB;
    }

    .order-header {
        /*background-color: #3B3736;*/
        /*color: #ffd25a;*/
        font-weight: 500;
        text-align: center;
        border-top: #dddddd 1px solid;
        border-bottom: #f1f2f7 1px solid;
        height: 50px;
        line-height: 50px;
    }

    .order-content {
        text-align: center;
        height: 50px;
        line-height: 50px;
        border-bottom: #f1f2f7 1px solid;
    }

    .order-summary {
        font-weight: 500;
        /*background-color: #f1f2f7;*/
        /*color: #ffd25a;*/
        border-bottom: 3px double #dddddd;
        text-align: center;
        height: 50px;
        line-height: 50px;
    }

    .order-button {
        background-color: #3B3736;
        color: #ffd25a;
        font-size: 16px;
        font-weight: 500;
        text-align: center;
        height: 60px;
        line-height: 60px;
        width: 100vw;
        margin-top: 20px;
    }

    .input-label {
        font-weight: 400;
        color: #5b5553;
    }

    .input-label-dark {
        font-weight: 400;
        color: #FFD25A;
    }

    .input-theme {
        display: table;
        padding: 0 20px;
        height: 50px;
        width: 100vw;
        background-color: white;
        border: #DDDDDD 0.8px solid;
    }

    .input-theme-dark {
        display: table;
        padding: 0 20px;
        height: 60px;
        width: 100vw;
        background-color: #3B3736;
    }

    .current-option {
        text-align: right;
        font-weight: 300;
        color: #929292;
        padding-right: 0;
    }

    .current-option-dark {
        text-align: right;
        font-weight: 300;
        color: #BBBBBB;
        padding-right: 0;
    }

    .text-wrapper {
        display: table-cell;
        vertical-align: middle;
    }
</style>
