@extends('layouts.app', ['activePage' => 'indexRefuel', 'titlePage' => __('Refuel')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Refuel', 'href':'#'}]" active="View"></breadcrumb>

        <v-form name="refuel" ref="form" class="col-12" :class="{'row':mq !== 'sm'}">
            <div class="col-md-4">
                <base-daterangepicker placeholder="Date Range" field="date" addon-left-icon="eec-icons icon-clock" :vparam="['required']"></base-daterangepicker>
            </div>
            <div class="col-md-3">
                <select-box field="vehicle_id" placeholder="Vehicle" type="select"
                            url="vehicle" optiontext="license" optionvalue="id" optiongroup="fleet" addon-left-icon="tim-icons icon-bus-front-12"
                            allowfilter="true" filtertype="contains" :vparam="[]"></select-box>
            </div>
            <div class="col-md-3">
                <select-box field="driver_id" placeholder="Driver" type="select"
                            url="driver" optiontext="name" optionvalue="id" optiongroup="fleet" addon-left-icon="tim-icons icon-single-02"
                            allowfilter="true" filtertype="contains" :vparam="[]"></select-box>
            </div>
            <div class="col-md-2">
                <base-button style="margin:0 !important;" @click="submit()" type="primary">
                    Search
                </base-button>
            </div>
        </v-form>

        <card :class="{'mt-2': mq === 'sm'}">
            <cardheader count="refuel" title="Refuel Lists">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable blank property="refuel" :columns="tableColumn" :allowselect="true">

                </darkgrouptable>

            </cardbody>
        </card>

        <modal>
            <div class="col-md-9">
                <div class="card card-user">
                    <div class="card-body" style="padding-top: 40px;">
                        <div class="card-text">
                            <div class="author">
                                <div class="block block-one"></div>
                                <div class="block block-two"></div>
                                <div class="block block-three"></div>
                                <div class="block block-four"></div>
                                <a>
                                    <img class="avatar" style="width: 140px; height: 140px;" :src="datum.avatar">
                                </a>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="col-sm-5" style="text-align: center;">
                                <h3>Rate: @{{ datum.rate }}</h3>
                            </div>
                            <div class="col-sm-5" style="text-align: center;">
                                <h3>Distance: @{{ datum.distance }}</h3>
                            </div>
                            <base-input class="col-sm-5" :forcevalue="datum.vehicle" placeholder="Vehicle License" addon-left-icon="tim-icons icon-bus-front-12"
                                        :vparam="[]" showonly>
                            </base-input>
                            <base-input class="col-sm-5" :forcevalue="datum.driver" placeholder="Driver" addon-left-icon="tim-icons icon-single-02"
                                        :vparam="[]" showonly>
                            </base-input>
                            <base-input class="col-sm-5" :forcevalue="datum.date" placeholder="Date" addon-left-icon="tim-icons icon-calendar-60"
                                        :vparam="[]" showonly>
                            </base-input>
                            <base-input class="col-sm-5" :forcevalue="datum.time" placeholder="Time" addon-left-icon="eec-icons icon-clock"
                                        :vparam="[]" showonly>
                            </base-input>

                            <div v-if="datum.image_array" class="col-sm-5 mt-2">
                                <div style="height: 280px; display: flex; align-items: center; justify-content: center;">
                                    <base-image :src="'https://drive.google.com/uc?export=view&id='+datum.image_array[0]"
                                                style="height:100%;">
                                    </base-image>
                                </div>
                            </div>

                            <base-input class="col-sm-5 mt-2" v-if="mq === 'sm'" :forcevalue="datum.odometer" placeholder="Time"
                                        addon-left-icon="eec-icons icon-speedometer"
                                        :vparam="[]" showonly>
                            </base-input>

                            <div v-if="datum.image_array" class="col-sm-5 mt-2">
                                <div style="height: 280px; display: flex; align-items: center; justify-content: center;">
                                    <base-image :src="'https://drive.google.com/uc?export=view&id='+datum.image_array[1]"
                                                style="height:100%;">
                                    </base-image>
                                </div>
                            </div>

                            <base-input class="col-sm-5 mt-2" v-if="mq !== 'sm'" :forcevalue="datum.odometer" placeholder="Time"
                                        addon-left-icon="eec-icons icon-speedometer"
                                        :vparam="[]" showonly>
                            </base-input>

                            <base-input class="col-sm-5 mt-2" :forcevalue="datum.quantity" placeholder="Time" addon-left-icon="eec-icons icon-fuel"
                                        :vparam="[]" showonly>
                            </base-input>
                        </div>
                    </div>
                </div>
            </div>
        </modal>

    </div>

@endsection

@push('js')
    <script src=" {{ mix('/js/vue/index.js') }}"></script>


    <script>

        new Vue({
            el: '#asset',


            store,

            data: {
                tableColumn: [
                    {'text': '#', 'data': 'id', 'align': 'center'},
                    {'text': 'Driver', 'data': 'driver'},
                    {'text': 'Vehicle', 'data': 'vehicle'},
                    {'text': 'Odometer', 'data': 'odometer', 'type': 'number'},
                    {'text': 'Quantity', 'data': 'quantity'},
                    {'text': 'Distance', 'data': 'distance', 'type': 'number'},
                    {'text': 'Rate', 'data': 'rate'},
                    {'text': 'Date', 'data': 'date'},
                ],
                rowIsSelected: false,
            },

            watch: {
                rowSelected(value) {
                    if (value) {
                        this.$store.commit('showModal', 'refuel');
                    }
                },
            },

            created() {
                this.$store.commit('setModel', 'refuel/crud/view');

                this.$store.dispatch('populateForm', {
                    'property': 'refuel',
                    'form': 'refuel',
                    'field': {
                        date: null,
                        vehicle_id: null,
                        driver_id: null,
                    }
                });

            },

            computed: {
                ...mapState([
                    'rowSelected',
                    'rowId',
                    'datum',
                    'mq',
                    'forms'

                ]),
                dataCount() {
                    return this.$store.getters.getRowCount;
                },
            },

            mounted() {
            },

            methods: {
                submit() {
                    this.$refs.form.validateForm();
                    if (this.$refs.form.validated) {
                        this.$store.dispatch('submit', {'form': 'refuel', 'url': '/api/refuel/crud/query'})
                            .then(response => {
                                console.log(response);
                                this.$store.commit("addTable", 'refuel');
                                this.$store.commit("addTableData", {'table_name': 'refuel', 'table_data': response.data});
                            });
                    }
                },

                // refresh() {
                //     this.$store.dispatch('getTableData', 'refuel/crud/group');
                // },
            },
        });
    </script>
@endpush
