@extends('layouts.app', ['activePage' => 'indexJob', 'titlePage' => __('Edit Job')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Job','href':'/job'}]" active="Edit"></breadcrumb>

        <topbutton text="Back" link="/job"></topbutton>

        <wizard @update="update" title="Edit Job" description="Edit an existing job in our system.">
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
                                    url="route" optiontext="name" optionvalue="id" addon-left-icon="tim-icons icon-single-02"
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
                                    url="vehicle" optiontext="license" optionvalue="id" addon-left-icon="tim-icons icon-single-02"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                    <div class="col-sm-5">
                        <select-box field="tail_id" placeholder="Vehicle Tail" type="select"
                                    url="vehicle" optiontext="license" optionvalue="id" addon-left-icon="tim-icons icon-single-02"
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
                id,
            },

            created() {
                this.$store.dispatch('populateForm', {
                    'form': 'job',
                    'property': 'job',
                    'field': {
                        id: null,
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

            mounted() {
                this.$store.dispatch('populateEditForm', {'form': 'job', 'id': this.id});
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms'
                ]),
            },

            methods: {
                update() {
                    this.$store.dispatch('update', {'form': 'job', 'url': '/api/job/' + this.id})
                        .then(response => {
                            Swal.fire('Updated!', 'Job has been successfully updated.', 'success')
                        });
                }
            },
        });
    </script>
@endpush
