@extends('layouts.app', ['activePage' => 'indexJob', 'titlePage' => __('Add Job')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Job','href':'/job'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="/job"></topbutton>

        <wizard @submit="submit" title="Create Job" description="Follow the process to add a new job to our system.">
            <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                <h5 class="info-text"> Pick the fleet for our new job.</h5>

                <v-form name="job">
                    <pill-input placeholder="" field="fleet_id" url="fleet" optiontext="name" optionvalue="id"
                                :vparam="['required']">
                    </pill-input>
                </v-form>

            </wizard-tab>
            <wizard-tab name="customer" icon="tim-icons icon-single-02">
                <h5 class="info-text"> Pick the customer for our new job.</h5>
                <v-form name="job" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <select-box field="customer_id" placeholder="Customers" type="select"
                                    url="customer" optiontext="name" optionvalue="id" addon-left-icon="tim-icons icon-single-02"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                </v-form>
            </wizard-tab>
            <wizard-tab name="Detail" icon="tim-icons icon-settings-gear-63">
                <h5 class="info-text"> Let's start with the basic information.</h5>
                <v-form name="job" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <select-box field="route_id" placeholder="Route" type="select"
                                    :forceoption="routeData" optiontext="name" optionvalue="id" addon-left-icon="tim-icons icon-single-02"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                    <div class="col-sm-5">
                        <base-datepicker placeholder="Delivery Date" field="date_time" addon-left-icon="eec-icons icon-clock"
                                         :vparam="['required']">
                        </base-datepicker>
                    </div>
                    <div class="col-sm-5">
                        <select-box field="driver_id" placeholder="Driver" type="select"
                                    url="driver" optiontext="name" optionvalue="id" addon-left-icon="tim-icons icon-single-02"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                    <div class="col-sm-5">
                        <select-box field="head_id" placeholder="Vehicle Head" type="select"
                                    :forceoption="headData" optiontext="license" optionvalue="id" addon-left-icon="tim-icons icon-single-02"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                    <div class="col-sm-5">
                        <select-box field="tail_id" placeholder="Vehicle Tail" type="select"
                                    :forceoption="tailData" optiontext="license" optionvalue="id" addon-left-icon="tim-icons icon-single-02"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                </v-form>
            </wizard-tab>
        </wizard>
    </div>


@endsection

@push('js')
    <script src=" {{ mix('/js/vue/create.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                routeData: [],
                headData: [],
                tailData: [],
            },

            watch: {
                'forms.job.customer_id': function (val) {
                    if (val) {
                        this.getRouteByCustomerId(val)
                    }
                },
                'forms.job.driver_id': function (val) {
                    if (val) {
                        this.getVehicleByDriver(val)
                    }
                }
            },


            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'job',
                    'form': 'job',
                    'field': {
                        date_time: null,
                        start_time: null,
                        end_time: null,
                        customer_id: null,
                        route_id: null,
                        driver_id: null,
                        head_id: null,
                        tail_id: null,
                        fleet_id: null,
                    }
                });
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms',
                ]),
            },

            methods: {
                getVehicleByDriver(id) {
                    new Promise((resolve, reject) => {
                        axios.get(`/api/driver/crud/getAssignedHead/${id}`, {})
                            .then(response => {
                                this.headData = response.data.data;
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/driver/crud/getAssignedTail/${id}`, {})
                            .then(response => {
                                this.tailData = response.data.data;
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                getRouteByCustomerId(id) {
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/route/crud/getRouteByCustomerId/${id}`, {})
                            .then(response => {
                                this.routeData = response.data.data;
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                submit() {
                    this.$store.dispatch('submit', {'form': 'job', 'url': '/api/job', 'reset': true})
                        .then(response => {
                            console.log(response);
                            Swal.fire('Complete!', 'Job has been successfully created.', 'success')
                        });
                }
            },
        });
    </script>
@endpush
