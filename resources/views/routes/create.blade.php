@extends('layouts.app', ['activePage' => 'indexRoute', 'titlePage' => __('Add Route')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Route','href':'/route'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="/route"></topbutton>

        <wizard @submit="submit" title="Create Route" description="Follow the process to dd a new route to our system.">
            <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                <h5 class="info-text"> Pick the fleet for our new route.</h5>

                <v-form name="route">
                    <pill-input placeholder="" field="fleet_id" url="fleet" optiontext="name" optionvalue="id"
                                :vparam="['required']">
                    </pill-input>
                </v-form>

            </wizard-tab>
            <wizard-tab name="customer" icon="tim-icons icon-single-02">
                <h5 class="info-text"> Customer</h5>

                <v-form name="route" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <select-box field="customer_id" placeholder="Customers" type="select"
                                    url="customer" optiontext="name" optionvalue="id" addon-left-icon="tim-icons icon-single-02"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                </v-form>

            </wizard-tab>
            <wizard-tab name="station" icon="tim-icons icon-square-pin">
                <h5 class="info-text"> Please name a route and select all stop places for this route.</h5>

                <v-form name="route" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <base-input placeholder="Route Name" field="name" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                </v-form>

                <v-form name="route_pivot" class="row justify-content-center mt-5">
                    <div class="col-sm-5" v-for="(station, index) in stations" :key="index">
                        <select-box :field="station.orderNo" :placeholder="'Station ' + station.orderNo" type="select"
                                    :forceoption="places" optiontext="geofence_name_with_province" optionvalue="id" addon-left-icon="tim-icons icon-square-pin"
                                    allowfilter="true" filtertype="contains" :vparam="['']"></select-box>
                    </div>
                </v-form>

            </wizard-tab>
            <wizard-tab name="income" icon="tim-icons icon-money-coins">
                <h5 class="info-text"> Income.</h5>
                <v-form name="trip_rate" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <select-box field="trip_rate_type_id" placeholder="การคำนวณค่าขนส่ง" type="select"
                                    url="trip_rate_type" optiontext="name" optionvalue="id" addon-left-icon="tim-icons icon-money-coins"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                </v-form>

                <v-form name="trip_rate" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <base-input placeholder="ค่าปรับแก้ค่าขนส่ง ทุกราคาน้ำมัน 1 บาท/ลิตร" field="diesel_adjust" addon-left-icon="tim-icons icon-money-coins"
                                    type="number"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                </v-form>

                <v-form name="trip_rate" class="row justify-content-center mt-5">
                    <div class="col-sm-3" v-for="(priceRange, index) in priceRanges" :key="index">
                        <base-input :placeholder="priceRange.placeholder" :field="priceRange.field" addon-left-icon="tim-icons icon-money-coins" type="number"
                                    :vparam="['']">
                        </base-input>
                    </div>
                </v-form>
            </wizard-tab>
            <wizard-tab name="outcome" icon="tim-icons icon-coins">
                <h5 class="info-text"> Lastly, outcome.</h5>
                <v-form name="incentive" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="ค่าเที่ยว" field="price" addon-left-icon="tim-icons icon-coins" type="number"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                </v-form>
                <v-form name="route" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="ค่าทางด่วน" field="toll_fee" addon-left-icon="tim-icons icon-coins" type="number"
                                    :vparam="['required']">
                        </base-input>
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
                places: [],
                stations: [],
                priceRanges: [],
                tripRateTypes: [],
            },

            watch: {},


            created() {
                this.getAllPlaces();
                this.addStationInput();
                this.addPriceRangeInput();

                this.$store.dispatch('populateForm', {
                    'property': 'route',
                    'form': 'route',
                    'field': {
                        name: null,
                        fleet_id: null,
                        trip_rate_id: null,
                        incentive_id: null,
                        customer_id: null,
                        toll_fee: null,
                    }
                });

                this.$store.dispatch('populateForm', {
                    'property': 'route',
                    'form': 'route_pivot',
                    'field': {
                        route_id: null,
                    }
                });

                this.$store.dispatch('populateForm', {
                    'property': 'route',
                    'form': 'trip_rate',
                    'field': {
                        trip_rate_type_id: null,
                        diesel_adjust: null,
                        '10-11': null,
                        '11-12': null,
                        '12-13': null,
                        '13-14': null,
                        '14-15': null,
                        '15-16': null,
                        '16-17': null,
                        '17-18': null,
                        '18-19': null,
                        '19-20': null,
                        '20-21': null,
                        '21-22': null,
                        '22-23': null,
                        '23-24': null,
                        '24-25': null,
                        '25-26': null,
                        '26-27': null,
                        '27-28': null,
                        '28-29': null,
                        '29-30': null,
                    }
                });

                this.$store.dispatch('populateForm', {
                    'property': 'route',
                    'form': 'incentive',
                    'field': {
                        route_id: null,
                        price: null,
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
                addPriceRangeInput() {
                    for (let i = 10; i < 30; i++) {
                        let field = i + '-' + (i + 1);
                        let placeholder = i + '.01 - ' + (i + 1) + '.00';

                        this.priceRanges.push({field, placeholder});
                    }
                },
                addStationInput() {
                    for (let i = 1; i <= 10; i++) {
                        this.stations.push({orderNo: i});
                    }
                },
                geofenceNameWithProvince(data) {
                    data.forEach((datum) => {
                        datum.geofence_name_with_province = datum.geofence_name + " (" + datum.province + ")";
                    });
                    this.places = data;
                },
                getAllPlaces() {
                    return new Promise((resolve, reject) => {
                        axios.get('/api/place', {})
                            .then(response => {
                                this.geofenceNameWithProvince(response.data.data);
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
                    this.$store.dispatch('submit', {'form': 'route', 'url': '/api/route'})
                        .then(response => {
                            const route_id = response.data.id;
                            this.$store.commit('updateForm', {'form': 'route_pivot', 'field': 'route_id', 'value': route_id});
                            this.$store.commit('updateForm', {'form': 'trip_rate', 'field': 'route_id', 'value': route_id});
                            this.$store.commit('updateForm', {'form': 'incentive', 'field': 'route_id', 'value': route_id});

                            this.$store.dispatch('submit', {'form': 'route_pivot', 'url': '/api/route_pivot', 'reset': true})

                            this.$store.dispatch('submit', {'form': 'trip_rate', 'url': '/api/trip_rate', 'reset': true})
                                .then(response => {
                                    this.$store.commit('updateForm', {'form': 'route', 'field': 'trip_rate_id', 'value': response.data.id});
                                    this.$store.dispatch('update', {'form': 'route', 'url': '/api/route/' + route_id})
                                });

                            this.$store.dispatch('submit', {'form': 'incentive', 'url': '/api/incentive', 'reset': true})
                                .then(response => {
                                    this.$store.commit('updateForm', {'form': 'route', 'field': 'incentive_id', 'value': response.data.id});
                                    this.$store.dispatch('update', {'form': 'route', 'url': '/api/route/' + route_id})
                                });

                            console.log(response);
                            Swal.fire('Complete!', 'Route has been successfully created.', 'success')
                        });
                }
            },
        });
    </script>
@endpush
