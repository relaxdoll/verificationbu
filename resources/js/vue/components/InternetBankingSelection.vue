<template>
    <div style="margin-top: 20px;">

        <div @click="selected = true" class="input-theme">
            <div class="text-wrapper">
                <div class="row">
                    <div class="col-5 input-label">
                        {{ label }}
                    </div>
                    <div class="col-6 current-option">
                        {{ currentOption }}
                    </div>
                    <div class="col-1 next">
                        >
                    </div>
                </div>
            </div>
        </div>

        <liffdrawer @close="selected = false" align="right" :maskclosable="true" :label="label" :currentview="currentview" :closeable="true">
            <div v-if="selected" style="padding-top: 20px;">
                <div v-for="bank in banks" @click="select(bank)"
                     style="width: 100vw; height: 60px; background-color: white; border-top: #f1f1f1 0.5px solid;">
                    <div class="row">
                        <div class="col-3" style="padding-right: 0;">
                            <img style=" height: 40px; margin: 10px 0 10px 0; padding-left: 10px;" :src="'/bankicon/' + bank.image">
                        </div>
                        <div class="col-9" style="padding-left: 0;">
                            <div style="height: 60px; display: table-cell; vertical-align: middle; ">
                                <div style="display: table;">
                                    <p style="font-size: 12px; font-weight: 500; color: #444444; margin: 0;">
                                        {{bank.name}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </liffdrawer>
    </div>
</template>
<script>
    export default {
        props: {
            label: {required: true},
            currentview: {default: 'Back'},
            isselect: {required: false},
            selectedoption: {require: false},
            disabled: {required: false}
        },

        data() {
            return {
                currentOption: null,
                selected: false,
                banks: [
                    {
                        id: 1,
                        name: "Krungsri Bank",
                        image: "Krungsri.jpg",
                        return_url: "https://eecl.co.th/",
                        value: "internet_banking_bay",
                    },
                    {
                        id: 2,
                        name: "Bangkok Bank",
                        image: "Bangkok.png",
                        return_url: "https://eecl.co.th/",
                        value: "internet_banking_bbl",
                    },
                    {
                        id: 3,
                        name: "Krung Thai Bank",
                        image: "KrungThai.jpg",
                        return_url: "https://eecl.co.th/",
                        value: "internet_banking_ktb",
                    },
                    {
                        id: 4,
                        name: "Siam Commercial Bank",
                        image: "SCB.png",
                        return_url: "https://eecl.co.th/",
                        value: "internet_banking_scb",
                    },
                ]
            };
        },

        mounted() {
            this.currentOption = this.selectedoption;
            this.selected = this.isselect;
        },
        methods: {
            select(option) {
                this.currentOption = option.name;
                this.$emit('input', option);
                // this.$emit()
                this.selected = false;
            },
        },
        computed: {}
    };
</script>
<style scoped>
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

    .input-label {
        font-weight: 400;
        color: #5b5553;
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
        margin-top: 20px;
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
        border-bottom: #DDDDDD 0.8px solid;
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

    .card_save {
        font-family: Roboto, Helvetica, Arial, sans-serif;;
        font-size: 12px;
        font-weight: 500;
        color: #444444;
        height: 50px;
        width: 100vw;
        background-color: white;
        display: table-cell;
        vertical-align: middle;
    }
    .card_inputbox{
        font-size: 12px;
        border: none;
        border-top: 0.5px solid #f1f1f1;
        height: 50px;
        border-radius: 0;
        width: 100%;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        padding: 0 20px;
        line-height: 10px;
        color: #929292;
    }
</style>
