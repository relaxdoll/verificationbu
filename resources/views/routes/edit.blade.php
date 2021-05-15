@extends('layouts.app', ['activePage' => 'indexRoute', 'titlePage' => __('Edit Route')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Route','href':'/route'}]" active="Edit"></breadcrumb>

        <topbutton text="Back" link="/route"></topbutton>

        <wizard @update="update" title="Edit Route" description="Edit an existing route in our system.">
            <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                <h5 class="info-text"> Pick the fleet for our new route.</h5>

                <v-form name="route">
                    <pill-input placeholder="" field="fleet_id" url="fleet" optiontext="name" optionvalue="id"
                                :vparam="['']">
                    </pill-input>
                </v-form>

            </wizard-tab>
            <wizard-tab name="customer" icon="tim-icons icon-single-02">
                <h5 class="info-text"> Customer</h5>

                <v-form name="route" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <select-box field="customer_id" placeholder="Customers" type="select"
                                    url="customer" optiontext="name" optionvalue="id" addon-left-icon="tim-icons icon-single-02"
                                    allowfilter="true" filtertype="contains" :vparam="['']"></select-box>
                    </div>
                </v-form>

            </wizard-tab>
            <wizard-tab name="station" icon="tim-icons icon-square-pin">
                <h5 class="info-text"> Please name a route and select all stop places for this route.</h5>

                <v-form name="route" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <base-input placeholder="Route Name" field="name" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['']">
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
                                    allowfilter="true" filtertype="contains" :vparam="['']"></select-box>
                    </div>
                </v-form>

                <v-form name="trip_rate" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <base-input placeholder="ค่าปรับแก้ค่าขนส่ง ทุกราคาน้ำมัน 1 บาท/ลิตร" field="diesel_adjust" addon-left-icon="tim-icons icon-money-coins"
                                    type="number"
                                    :vparam="['']">
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
                                    :vparam="['']">
                        </base-input>
                    </div>
                </v-form>
                <v-form name="route" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="ค่าทางด่วน" field="toll_fee" addon-left-icon="tim-icons icon-coins" type="number"
                                    :vparam="['']">
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
                id,
                places: [],
                stations: [],
                priceRanges: [],
                tripRateTypes: [],
            },

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
            },

            mounted() {
                this.$store.dispatch('populateEditForm', {'form': 'route', 'id': this.id})
                    .then(response => {
                        this.getRoutePivot(response.data.route_pivot[0].route_id);
                        this.getFormDataById('trip_rate', response.data.trip_rate_id);
                        this.getFormDataById('incentive', response.data.incentive_id);
                    });
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms'
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
                getFormDataById(form, id) {
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/${form}/${id}`, {})
                            .then(response => {
                                this.$store.dispatch('populateForm', {
                                    'property': 'route',
                                    'form': form,
                                    'field': response.data.data,
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
                getRoutePivot(route_id) {
                    const routePivotData = {}
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/route_pivot`, {})
                            .then(response => {
                                const routePivots = response.data.data;
                                routePivots.forEach((routePivot) => {
                                    if (routePivot.route_id === route_id) {
                                        routePivotData[routePivot.order] = routePivot.place_id;
                                    }
                                });
                                this.$store.dispatch('populateForm', {
                                    'property': 'route',
                                    'form': 'route_pivot',
                                    'field': routePivotData,
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
                update() {
                    const route_id = this.id;
                    this.$store.dispatch('update', {'form': 'route_pivot', 'url': '/api/route_pivot/' + route_id})
                    this.$store.dispatch('update', {'form': 'incentive', 'url': '/api/incentive/' + this.forms.incentive.id})
                    this.$store.dispatch('update', {'form': 'trip_rate', 'url': '/api/trip_rate/' + this.forms.trip_rate.id})
                    this.$store.dispatch('update', {'form': 'route', 'url': '/api/route/' + this.id})
                        .then(response => {
                            Swal.fire('Updated!', 'Route has been successfully updated.', 'success')
                        });

                }
            },
        });
    </script>
@endpush
