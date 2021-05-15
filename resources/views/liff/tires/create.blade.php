@extends('layouts.liff', ['activePage' => 'indexReplaceTire', 'titlePage' => __('Fast Track')])

@section('style')
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
    <style>
        .wheel {
            z-index: 3;
        }
    </style>
@endsection
@section('content')
    <div v-if="isCompleted">
        <div class="banner">
            <div class="row">
                <div :class="{'nav-active': mode === 'เช็คลม/วัดดอก'}" class="nav-text col-6 nav-box" @click="setMode('เช็คลม/วัดดอก')" style="padding-right: 0;">
                    เช็คลม/วัดดอก
                </div>
                <div :class="{'nav-active': mode === 'แจ้งเปลี่ยนยาง'}" class="nav-text col-6 nav-box" @click="setMode('แจ้งเปลี่ยนยาง')" style="padding-left: 0;">
                    แจ้งเปลี่ยนยาง
                </div>
            </div>
        </div>

        <div class="content" :class="{'has-banner':$store.state.has_banner}">

            <liff-header title="Maintenance"></liff-header>

            <liff-tire-change-request></liff-tire-change-request>

            <liff-tire-pressure-and-tread></liff-tire-pressure-and-tread>

        </div>
    </div>

    <div class="content" v-else>

        <liff-header title="Maintenance"></liff-header>

        <liffloader :show="$store.state.loading" text="Saving"></liffloader>

        <liff-form name="tire_placement">
            <liff-input-group class="spacing">
                <liffdropdown2 ref="vehicle_drop" icon="icon icon-bus-front-10" iconcolor="#fc9500" :searchbar="false" label="ทะเบียนรถ" @input="getVehicleType($event)"
                               :url="'driver/crud/getAssignedVehicle/' + $store.state.driver_id" :allowgetoption="$store.state.driver_id"
                               field="vehicle_id" optiontext="license" optionvalue="id" :vparam="['required']"></liffdropdown2>

            </liff-input-group>
        </liff-form>


        <liff-form name="tire_placement" field="placement">

            <div class="spacing vehicle-wrapper" v-if="forms.tire_placement.vehicle_id">

                <liff-vehicle-10 v-if="frame ===1"></liff-vehicle-10>

                <liff-vehicle-head v-if="frame ===2"></liff-vehicle-head>

                <liff-vehicle-tail v-if="frame ===3"></liff-vehicle-tail>

                <liff-vehicle-trailer-head v-if="frame ===4"></liff-vehicle-trailer-head>

                <liff-vehicle-trailer-12 v-if="frame ===6"></liff-vehicle-trailer-12>

                <liff-vehicle-trailer-8 v-if="frame ===5"></liff-vehicle-trailer-8>

            </div>
        </liff-form>

        <liffdrawer2 @close="$store.commit('hideDrawer')" align="right" :maskclosable="true" :label="$store.state.drawer_name" :currentview="$store.page_name"
                     :closeable="true">

            <div v-if="$store.state.show_drawer">

                <liff-form name="tire" ref="tire">
                    <liff-input-group class="spacing">
                        <liffdropdown2 icon="icon icon-tag" iconcolor="#fc9500" :searchbar="false" label="ยี่ห้อ"
                                       url="brand" field="brand_id" optiontext="text" optionvalue="value" :vparam="['required']"></liffdropdown2>

                        <liffdropdown2 icon="icon icon-filter" iconcolor="#007aff" :searchbar="false" label="ชนิดยาง"
                                       field="tire_type_id" optiontext="text" optionvalue="value"
                                       :forceoption="[{'text':'ยางใหม่', 'value': 1}, {'text':'ยางหล่อดอก', 'value': 2}]" :vparam="['required']"></liffdropdown2>

                        <liff-input field="serial" icon="icon icon-code" iconcolor="#007aff" placeholder="หมายเลขยาง" type="text" :vparam="['required']"
                        ></liff-input>

                        <liff-input field="initial_tread_depth" icon="icon icon-measure-big" iconcolor="#31c85a" placeholder="ความลึกดอกยาง (มม.)" type="number"
                                    :vparam="['required', {'minValue':0}, {'maxValue':16}]"
                                    @input="inputTreadDepth($event)"
                        ></liff-input>


                    </liff-input-group>

                </liff-form>

                <liff-form name="tire_placement" ref="tire_placement">
                    <liff-input-group class="spacing">

                        <liff-datepicker icon="icon icon-calendar" iconcolor="#fc3e30" field="start_date" label="วันที่" :vparam="[]"></liff-datepicker>

                        <liff-input v-if="!forms.tire_placement.is_spare" field="start_mileage" icon="icon icon-h-dashboard" iconcolor="#fc3e30" placeholder="เลขไมล์"
                                    type="number"
                                    :vparam="['required']"></liff-input>

                    </liff-input-group>

                </liff-form>
                <liff-submit-button @submit="submit()" label="บันทึก"></liff-submit-button>

            </div>

        </liffdrawer2>

    </div>


@endsection

@section('js')
    <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <script src=" {{ mix('/js/vue/liff.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                selectedDate: new Date(),
                frame: null,
                isCompleted: false,
            },

            created() {
                this.$store.dispatch('liffInit');
                this.$store.commit('setPageName', 'Image Track');
                this.$store.dispatch('populateForm', {
                    'property': 'tire',
                    'form': 'tire',
                    'field': {
                        serial: null,
                        initial_tread_depth: null,
                        tread_depth: null,
                        price: 0,
                        fleet_id: 1,
                        purchase_id: 0,
                        brand_id: null,
                        tire_type_id: null,
                    }
                });
                this.$store.dispatch('populateForm', {
                    'property': 'tire',
                    'form': 'tire_placement',
                    'field': {
                        vehicle_id: null,
                        is_spare: null,
                        start_date: new Date(),
                        tire_id: null,
                        placement: null,
                        start_mileage: null,
                        checker_id: null,
                        start_tread_depth: null,
                    }
                });
            },


            mounted() {
            },

            computed: {
                ...mapState([
                    'forms',
                    'driver_id',
                    'mode',
                ]),
            },

            watch: {
                driver_id(value) {
                    this.$store.commit('updateForm', {'form': 'tire_placement', 'field': 'checker_id', 'value': value});

                    return new Promise((resolve, reject) => {
                        axios.get(`/api/driver/crud/getLiffType/${this.driver_id}`, {})
                            .then(response => {
                                if (response.data.data) {
                                    this.isCompleted = true;
                                    this.$store.commit('setMode', 'เช็คลม/วัดดอก');
                                    this.$store.commit('hasBanner');
                                }
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                }
            },

            methods: {
                inputTreadDepth(depth) {
                    this.$store.commit('updateForm', {'form': 'tire', 'field': 'tread_depth', 'value': depth});
                    this.$store.commit('updateForm', {'form': 'tire_placement', 'field': 'start_tread_depth', 'value': depth})
                },
                getVehicleType(vehicle) {
                    this.frame = vehicle.frame;
                    this.$store.commit('setActiveTires', vehicle.active_tires);
                },
                submit() {
                    this.$refs.tire.validateForm();
                    this.$refs.tire_placement.validateForm();
                    if (this.$refs.tire.validated && this.$refs.tire_placement.validated) {
                        let vehicle_id = this.forms.tire_placement.vehicle_id;
                        this.$store.commit('loading', true);
                        this.$store.dispatch('submit', {'form': 'tire', 'url': '/api/tire/crud/store', 'reset': true})
                            .then(response => {
                                console.log(response);
                                this.$store.commit('updateForm', {'form': 'tire_placement', 'field': 'tire_id', 'value': response.data.id});

                                this.$store.dispatch('submit', {'form': 'tire_placement', 'url': '/api/tire_placement', 'reset': true})
                                    .then(response => {
                                        console.log(response);

                                        this.$store.commit('loading', false);
                                        this.$store.commit('hideDrawer');

                                        this.$store.commit('resetForm', 'tire');
                                        this.$store.commit('resetForm', 'tire_placement');

                                        this.$store.commit('updateForm', {'form': 'tire_placement', 'field': 'vehicle_id', 'value': vehicle_id});
                                        this.$store.commit('updateForm', {'form': 'tire_placement', 'field': 'checker_id', 'value': this.driver_id});

                                        new Promise((resolve, reject) => {
                                            axios.get('/api/vehicle/crud/active_tires/' + vehicle_id)
                                                .then(response => {
                                                    console.log(response.data);
                                                    this.$store.commit('setActiveTires', response.data.data[0].active_tires);
                                                    resolve(response.data);
                                                })
                                                .catch(error => {
                                                    console.log(error);

                                                    reject(error.response);
                                                });
                                        });
                                    });
                            });
                    }
                },
                close() {
                    liff.closeWindow()
                },

                setMode(mode) {
                    if (mode === 'แจ้งเปลี่ยนยาง') {
                        this.$store.commit('setMode', 'แจ้งเปลี่ยนยาง');
                    } else {
                        this.$store.commit('setMode', 'เช็คลม/วัดดอก');
                    }
                },
                navClass(mode) {
                    if (mode === this.mode) {
                        return ' nav-active';
                    } else {
                        return 'col-6 nav-box';
                    }
                },
            },
        });
    </script>
@endsection
