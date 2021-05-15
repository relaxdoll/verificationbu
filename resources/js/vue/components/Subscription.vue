<template>
    <div style="margin-top: 20px;">

        <liffloader :show="processing" text="Processing"/>

        <selector @click="selected = !selected" :label="label" :currentoption="currentOption"/>

        <liffdrawer @close="selected = false" align="right" :maskclosable="true" :label="label" :currentview="currentview" :closeable="true">
            <div v-if="selected">
                <div v-if="subscription.status == 'running' ">
                    <liffgroup>
                        <div style="padding: 20px;">
                            <div class="row">
                                <div class="col-3">
                                    <img src="https://linebotth.s3-ap-southeast-1.amazonaws.com/Subscription/Logo.png" style="height: 60px;" alt="">
                                </div>
                                <div class="col-9" style="padding: 0px;">
                                    <div style="font-weight: 600; line-height: 20px;">{{subscription.level}}</div>
                                    <div style="line-height: 20px;">{{subscription.content}}</div>
                                    <div style="line-height: 20px;">฿ {{subscription.price}}/month</div>
                                </div>
                            </div>
                        </div>

                        <!--Upgrade Plan-->
                        <selector @click="upgradeSelected = !upgradeSelected" label="Upgrade" style="border-bottom: none;"/>
                        <liffdrawer @close="upgradeSelected = false" align="right" :maskclosable="true" label="Upgrade" currentview="Subscription" :closeable="true">
                            <div v-if="upgradeSelected">
                                <product-upgrade-selection @update-upgrade-subscription="getSubscriberData" :user="user"/>
                            </div>
                        </liffdrawer>
                    </liffgroup>

                    <liffgroup>
                        <div>
                            <div class="row remaining-header">
                                <div class="col-10" style="padding: 0px;">User Account</div>
                                <div class="col-2 remaining-content">{{subscription.user_accounts}}</div>
                            </div>
                            <div class="row remaining-header">
                                <div class="col-6" style="padding: 0;">Create</div>
                                <div class="col-6 remaining-content">{{subscription.create_amounts}}</div>
                            </div>
                            <div class="row remaining-header-last">
                                <div class="col-6" style="padding : 0">Scan</div>
                                <div v-if="subscription.scan_amounts > '100000' " class="col-6 remaining-content">Unlimited</div>
                                <div v-else class="col-6 remaining-content">{{subscription.scan_amounts}}</div>
                            </div>
                        </div>
                    </liffgroup>

                    <liffgroup>
                        <div @click="cancelSubscription" style="text-align: center; line-height: 60px; color: #e2564b; font-weight: 400;">Cancel Subscription</div>
                    </liffgroup>
                </div>
                <div v-else>
                    <product-select @update-subscription="getSubscriberData" :user="user"/>
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
            user: {require: true},
            subscription: {require: true},
        },

        data() {
            return {
                currentOption: null,
                selected: false,
                upgradeSelected: false,

                subscriptionData: new Form({
                    user_id: null,
                    payment_id: null,
                    status: null,
                    level: null,
                    content: null,
                    price: null,
                    create_amounts: null,
                    scan_amounts: null,
                    user_accounts: null,
                }),

                processing: false,

            };
        },

        mounted() {
            this.selected = this.isselect;
            // this.selected = true;
        },
        methods: {
            cancelSubscription() {
                if (confirm('Do you want to cancel your subscription?') === true) {
                    this.processing = true;
                    this.subscription.status = 'canceled';
                    this.storeSubscriptionData();
                } else
                    return;
            },
            storeSubscriptionData() {
                // this.subscriptionData.post('/api/subscription', false)
                axios.post('/api/subscription', this.subscription)
                    .then(response => {
                        notify('success', 'success');
                        // this.selected = false;
                        this.getSubscriberData();
                        this.processing = false;
                        // console.log(response);
                    })
                    .catch(response => {
                        notify(response.message, 'danger');
                        // console.log(response);
                    });
            },
            getSubscriberData() {
                this.upgradeSelected = false;
                this.$emit('update-subscription', 'success');
            }
        },
    }
</script>

<style scoped>
    body {
        color: #3B3736;
        font-size: 16px;
        font-family: "Roboto", "Helvetica", "Arial", sans-serif;
        font-weight: 400 !important;
    }

    .stuff {
        border-top: #DDDDDD 0.8px solid;
        border-bottom: #DDDDDD 0.8px solid;
    }

    .remaining-header {
        line-height: 50px;
        border-bottom: #DDDDDD 0.8px solid;
        margin-left: 20px;
    }

    .remaining-header-last {
        line-height: 50px;
        margin-left: 20px;
    }

    .remaining-content {
        text-align: right;
        padding: 0 40px 0 0;
    }

</style>
