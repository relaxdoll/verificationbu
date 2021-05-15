@extends('layouts.app', ['activePage' => 'indexApprover', 'titlePage' => __('Add Approver')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Approver','href':'/approver'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="/approver"></topbutton>

        <wizard @submit="submit" title="Create Approver" description="Follow the process to dd a new approver to our system.">
            <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                <h5 class="info-text"> Pick the fleet for our new approver.</h5>

                <v-form name="approver">
                    <pill-input placeholder="" field="fleet_id" url="fleet" optiontext="name" optionvalue="id"
                                :vparam="['required']">
                    </pill-input>
                </v-form>

            </wizard-tab>
            <wizard-tab name="about" icon="tim-icons icon-single-02">
                <h5 class="info-text"> Let's start with the basic information.</h5>
                <v-form name="approver" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="First Name" field="firstName" addon-left-icon="tim-icons icon-single-02"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Last Name" field="lastName" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Email" field="email" addon-left-icon="tim-icons  icon-email-85"
                                    :vparam="['email']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Phone" field="phone" addon-left-icon="tim-icons icon-mobile"
                                    :vparam="['required', 'integer', {'minLength': 10}]">
                        </base-input>
                    </div>
                </v-form>
            </wizard-tab>
            <wizard-tab name="account" icon="tim-icons icon-settings-gear-63">
                <h5 class="info-text"> Lastly, link approver's LINE ID with us and choose his default vehicle(s).</h5>
                <v-form name="approver" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <select-box field="lineId" placeholder="LINE ID" type="select"
                                    url="line/user" optiontext="lineName" optionvalue="id" addon-left-icon="tim-icons icon-chat-33"
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
                fleetData: [],
            },

            watch: {},


            created() {
                this.getFleet();
                this.$store.dispatch('populateForm', {
                    'property': 'approver',
                    'form': 'approver',
                    'field': {
                        firstName: null,
                        fleet_id: null,
                        lastName: null,
                        phone: null,
                        email: null,
                        lineId: null,
                    }
                });
            },

            computed: {
                ...mapState([
                    'theme',
                ]),
            },

            methods: {
                getFleet() {
                    return new Promise((resolve, reject) => {
                        axios.get('/api/fleet', {})
                            .then(response => {
                                response.data.data.forEach((fleet) => {
                                    this.fleetData.push({text: fleet.name, value: fleet.id});
                                });
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
                    this.$store.dispatch('submit', {'form': 'approver', 'url': '/api/approver', 'reset': true})
                        .then(response => {
                            console.log(response);
                            Swal.fire('Complete!', 'Approver has been successfully created.', 'success')
                        });
                }
            },
        });
    </script>
@endpush
