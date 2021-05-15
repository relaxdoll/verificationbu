<template>


    <div v-if="rowSelected" :class="{'col-md-9':showIndex,'col-md-12':!showIndex}">
        <div class="card card-user" :class="{'sticky': pin}">
            <div class="card-body">

                <div v-if="showIndex" class="tools float-left">
                    <a @click="$store.commit('showIndex',false)" type="button" class="btn btn-icon" style="z-index: 2; color: white;">
                        <!--            <button @click="show = !show" type="button" class="btn btn-default dropdown-toggle btn-link btn-icon">-->
                        <i class="eec-icons icon-move-layer-left"></i>
                    </a>
                </div>
                <div v-else class="tools float-left">
                    <a @click="$store.commit('showIndex',true)" type="button" class="btn btn-icon" style="z-index: 2; color: white;">
                        <!--            <button @click="show = !show" type="button" class="btn btn-default dropdown-toggle btn-link btn-icon">-->
                        <i class="eec-icons icon-move-layer-right"></i>
                    </a>
                </div>
                <div v-if="showIndex" class="tools float-right">
                    <a @click="pin = !pin" type="button" class="btn btn-icon" :class="{'btn-info':pin}" style="z-index: 2; color: white;">
                        <!--            <button @click="show = !show" type="button" class="btn btn-default dropdown-toggle btn-link btn-icon">-->
                        <i class="tim-icons icon-pin"></i>
                    </a>
                </div>
                <div v-if="showIndex" class="tools text-center">
                    <h3 class="card-description" style="margin:6px 0 0 0;"> 71-7813 </h3>
                </div>
                <div class="card-text">
                    <div class="author">
                        <div class="block block-one"></div>
                        <div class="block block-two"></div>
                        <div class="block block-three"></div>
                        <div class="block block-four"></div>
                        <tabs background="transparent">
                            <tab name="Payment" icon="tim-icons icon-money-coins">
                                <div style="background-color:#1e1e2d; border: rgba(255, 255, 255, 0.1) 1px solid; border-radius: 5px;">
                                    <cardheader style="text-align: left;" title="Create Payment">
                                    </cardheader>

                                    <cardbody>
                                        <v-form name="payment" class="row justify-content-center" ref="cert">
                                            <div class="col-sm-5">
                                                <select-box field="payment_id" placeholder="Payment Type" type="dropdown" url="setting/payment/type"
                                                            optiontext="name" optionvalue="id" addon-left-icon="tim-icons  icon-money-coins"
                                                            :vparam="['required']"></select-box>
                                            </div>
                                            <div class="col-sm-5">
                                                <base-input placeholder="Amount" field="rate" type="number" addon-left-icon="eec-icons icon-money-11"
                                                            :vparam="['required']">
                                                </base-input>
                                            </div>
                                            <div class="col-sm-5">
                                                <base-datepicker placeholder="Start Date" field="start_date" addon-left-icon="tim-icons icon-calendar-60"
                                                                 :vparam="['required']">
                                                </base-datepicker>
                                            </div>
                                            <div class="col-sm-5">
                                                <base-datepicker placeholder="Expired Date" field="expired_date" addon-left-icon="tim-icons icon-calendar-60"
                                                                 :vparam="['required']">
                                                </base-datepicker>
                                            </div>
                                            <div class="col-sm-10 text-center">
                                                <base-button type="primary" wide @click="">
                                                    Save
                                                </base-button>
                                            </div>
                                        </v-form>

                                    </cardbody>
                                </div>

                                <!--                                <payment-type></payment-type>-->
                            </tab>
                            <tab name="Summary" icon="tim-icons icon-notes">
                                <!--                                <vehicle-type></vehicle-type>-->
                            </tab>
                            <tab name="Maintenance" icon="tim-icons icon-badge">
                            </tab>
                            <tab name="Document" icon="tim-icons icon-book-bookmark">
                            </tab>
                        </tabs>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {

        props: {},
        data() {
            return {
                pin: true,
                index: true,
            }
        },
        created() {

            this.$store.dispatch('populateForm', {
                'property': 'payment',
                'form': 'payment',
                'field': {
                    payment_type_id: null,
                    rate: null,
                    start_date: null,
                    expired_date: null,
                }
            });
        },
        watch: {},
        computed: {
            ...mapState([
                'rowSelected',
                'showIndex',
                'rowId',
                'datum'
            ])
        }
    };
</script>

<style scoped>

    .sticky {
        position: sticky;
        top: 30px;
    }

</style>
