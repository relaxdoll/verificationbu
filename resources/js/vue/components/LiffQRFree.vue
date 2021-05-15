<template>
    <div @click="isClicked" :class="getClass()">
        <div class="text-wrapper">
            <div class="row" style="padding-top: 10px; padding-bottom: 10px;">
                <div :class="(disabled)? 'col-4 text-option disabled': 'col-4 text-option'">
                    <img style="width: 100%;" :src="'https://linebotth.s3-ap-southeast-1.amazonaws.com/FreeQR/'+image" alt="">

                </div>

                <div class="col-6" style="padding-left: 0; font-weight: 500; color: #5b5553;">

                    <div style="">
                        Type: {{value.type}}
                    </div>

                    <div v-if="value.type === 'Contact Card'" style="font-weight: 600; line-height: 20px; font-size: 13px; color: #777777;">
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Name: {{value.name}}
                        </div>
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Phone: {{value.phone}}
                        </div>
                    </div>

                    <div v-if="value.type === 'Standard'" style="font-weight: 600; line-height: 20px; font-size: 13px; color: #777777;">
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Content: {{value.standard}}
                        </div>
                    </div>

                    <div v-if="value.type === 'Wifi'" style="font-weight: 600; line-height: 20px; font-size: 13px; color: #777777;">
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Network: {{value.SSID}}
                        </div>
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Password: {{value.password}}
                        </div>
                    </div>

                    <div v-if="value.type === 'Phone'" style="font-weight: 600; line-height: 20px; font-size: 13px; color: #777777;">
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Country: {{value.country}}
                        </div>
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Phone: {{value.phone}}
                        </div>
                    </div>

                    <div v-if="value.type === 'Promptpay'" style="font-weight: 600; line-height: 20px; font-size: 13px; color: #777777;">
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Account: {{value.account}}
                        </div>
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Phone: {{value.amount}}
                        </div>
                    </div>

                    <div v-if="value.type === 'SMS'" style="font-weight: 600; line-height: 20px; font-size: 13px; color: #777777;">
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Destination: {{value.destination}}
                        </div>
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Message: {{value.body}}
                        </div>
                    </div>

                    <div v-if="value.type === 'Email'" style="font-weight: 600; line-height: 20px; font-size: 13px; color: #777777;">
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Email: {{value.email}}
                        </div>
                        <div style="white-space: nowrap; width: 100%; overflow: hidden; text-overflow: ellipsis;">
                            Body: {{value.emailBody}}
                        </div>
                    </div>

                    <div style="position: absolute; bottom: 0; font-size: 12px; color: #999999; bottom:0;">
                        Created: {{value.created_at}}
                    </div>
                </div>

                <div class="col-2">
                    <div style="display: table; height: 100%;">
                        <div class="next" style="display: table-cell; vertical-align: middle; height:100%;">
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            value: {required: true},
            image: {required: true},
            selected: {default: false},
            disabled: {default: false},
        },

        data() {
            return {
                hasParent: this.$parent.children,
            };
        },

        mounted() {
            // console.log(this.$parent.children);
            // console.log(this._uid);
        },

        methods: {
            isClicked() {
                if (!this.disabled) {
                    this.$emit('click', true);
                }
            },
            getClass() {

                if (this.hasParent) {
                    if (this.$parent.children[0]._uid === this._uid) {
                        return 'select-option-first'
                    } else {
                        return 'select-option'
                    }
                } else {
                    return 'select-option-single'
                }
            }
        },

    };
</script>
<style scoped>

    .select-option {
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
    }

    .select-option-single {
        margin-top: 20px;
        padding-left: 20px;
        display: table;
        height: 50px;
        width: 100vw;
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
        border-bottom: #DDDDDD 0.8px solid;
    }

    .select-option-first {
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        background-color: white;
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

    .next {
        text-align: right;
        padding-left: 10px;
        color: #DDDDDD;
        font-size: 40px;
        font-family: 'BenchNine', sans-serif;
    }
</style>
