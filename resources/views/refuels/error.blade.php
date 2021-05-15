@extends('layouts.app', ['activePage' => 'errorRefuel', 'titlePage' => __('Refuel')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Refuel', 'href':'#'}]" active="Error"></breadcrumb>

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

                            <v-form class="col-sm-5 mt-2" name="edit" v-if="mq === 'sm'">
                                <base-input placeholder="Odometer" field="odometer" addon-left-icon="eec-icons icon-speedometer"
                                            :vparam="['required']">
                                </base-input>
                            </v-form>

                            <div v-if="datum.image_array" class="col-sm-5 mt-2">
                                <div style="height: 280px; display: flex; align-items: center; justify-content: center;">
                                    <base-image :src="'https://drive.google.com/uc?export=view&id='+datum.image_array[1]"
                                         style="height:100%;">
                                    </base-image>
                                </div>
                            </div>

                            <v-form class="col-sm-5 mt-2" name="edit" v-if="mq !== 'sm'">
                                <base-input placeholder="Odometer" field="odometer" addon-left-icon="eec-icons icon-speedometer"
                                            :vparam="['required']">
                                </base-input>
                            </v-form>
                            <v-form class="col-sm-5 mt-2" name="edit">
                                <base-input placeholder="Quantity" field="quantity" addon-left-icon="eec-icons icon-fuel"
                                            :vparam="['required']">
                                </base-input>
                            </v-form>

                            <div class="col-sm-10" style="display: flex; justify-content: center; text-align: center; margin-bottom: 30px;">
                                <base-button wide @click="update()" type="info">
                                    Update
                                </base-button>
                                <base-button wide style="margin-left: 15px;" @click="destroy()" type="danger">
                                    Delete
                                </base-button>
                            </div>
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
                ],
                rowIsSelected: false,
            },

            watch: {
                rowSelected(value) {
                    if (value) {
                        this.$store.commit('showModal', 'refuel');
                    }
                },
                datum(value) {
                    if (value) {

                        this.$store.commit('updateForm', {'form': 'edit', 'field': 'odometer', 'value': value.odometer});
                        this.$store.commit('updateForm', {'form': 'edit', 'field': 'quantity', 'value': value.quantity});
                        this.$store.commit('updateForm', {'form': 'edit', 'field': 'id', 'value': value.id});
                    }
                }
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

                this.$store.dispatch('populateForm', {
                    'property': 'refuel',
                    'form': 'edit',
                    'field': {
                        id: null,
                        odometer: null,
                        quantity: null,
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
                        this.$store.dispatch('submit', {'form': 'refuel', 'url': '/api/refuel/crud/error'})
                            .then(response => {
                                console.log(response);
                                this.$store.commit("addTable", 'refuel');
                                this.$store.commit("addTableData", {'table_name': 'refuel', 'table_data': response.data});
                            });
                    }
                },
                update(){
                    this.$store.dispatch('submit', {'form': 'edit', 'url': '/api/refuel/crud/check'})
                        .then(response => {
                            console.log(response);
                            this.$store.commit('hideModal');
                            this.submit();
                        });
                },
                destroy(){
                    this.$store.dispatch('submit', {'form': 'edit', 'url': '/api/refuel/crud/hide'})
                        .then(response => {
                            console.log(response);
                            this.$store.commit('hideModal');
                            this.submit();
                        });
                }

                // refresh() {
                //     this.$store.dispatch('getTableData', 'refuel/crud/group');
                // },
            },
        });
    </script>
@endpush
