<template>
    <div v-show="mode === 'เช็คลม/วัดดอก'">
        <liffloader :show="$store.state.loading" text="Saving"></liffloader>

        <liff-form name="tire_pressure_and_tread">
            <liff-input-group class="spacing">
                <liffdropdown2 ref="vehicle_drop" icon="icon icon-bus-front-10" iconcolor="#fc9500" :searchbar="false" label="ทะเบียนรถ" @input="getVehicleType($event)"
                               :url="'driver/crud/getAssignedVehicle/' + $store.state.driver_id" :allowgetoption="$store.state.driver_id"
                               field="vehicle_id" optiontext="license" optionvalue="id" :vparam="['required']"></liffdropdown2>

            </liff-input-group>

        </liff-form>


        <liff-form class="spacing" name="tire_pressure_and_tread" field="placement">


            <div class="vehicle-wrapper" v-if="forms.tire_pressure_and_tread.vehicle_id">

                <div style="text-align: center; margin-bottom: 20px;">

                    <div style="display: flex; justify-content: center;">
                        <div @click="dataType = 'tread'" class="liff-toggle liff-toggle-left" :class="{'liff-toggle-active':dataType === 'tread'}">ดอกยาง</div>
                        <div @click="dataType = 'pressure'" class="liff-toggle liff-toggle-right" :class="{'liff-toggle-active':dataType === 'pressure'}">ลมยาง</div>
                    </div>

                </div>


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

                <liff-form name="tire_pressure_and_tread" ref="tire_pressure_and_tread" v-if="mode === 'เช็คลม/วัดดอก'">
                    <liff-input-group class="spacing">
                        <liff-input field="mileage" icon="icon icon-h-dashboard" iconcolor="#fc3e30" placeholder="เลขไมล์" type="number"
                                    :vparam="['required']"></liff-input>

                        <liff-input field="tread_depth" icon="icon icon-measure-big" iconcolor="#007aff" placeholder="ความลึกดอกยาง (มม.)" type="number"
                                    :vparam="['required', {'minValue':0}, {'maxValue':16}]"

                        ></liff-input>

                        <liff-input field="tire_pressure" icon="win-icons icon-f-dashboard" iconcolor="#1d2a44" placeholder="แรงดันลมยางก่อนเติม (psi)" type="number"
                                    :vparam="['required', {'minValue':0}, {'maxValue':200}]"

                        ></liff-input>

                    </liff-input-group>

                </liff-form>
                <liff-submit-button @submit="submit()" label="บันทึก"></liff-submit-button>

            </div>

        </liffdrawer2>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "TirePressureAndTread",
        data() {
            return {
                selectedDate: new Date(),
                frame: null,
                dataType: null,
            }
        },

        created() {
            this.$store.dispatch('populateForm', {
                'property': 'tire',
                'form': 'tire_pressure_and_tread',
                'field': {
                    tire_id: null,
                    tire_placement_id: null,
                    tire_pressure: null,
                    tread_depth: null,
                    driver_id: null,
                    vehicle_id: null,
                    mileage: null,
                    is_updated: 1,
                }
            });
        },

        mounted() {
            this.dataType = 'tread';
        },

        computed: {
            ...mapState([
                'forms',
                'driver_id',
                'placement',
                'active_tires',
                'mode',
                'has_updated'
            ]),
        },

        watch: {
            driver_id(value) {
                this.$store.commit('updateForm', {'form': 'tire_pressure_and_tread', 'field': 'driver_id', 'value': value});
            },
            placement(value){
                this.$store.commit('updateForm', {'form': 'tire_pressure_and_tread', 'field': 'tire_placement_id', 'value': this.active_tires[value].id});
                this.$store.commit('updateForm', {'form': 'tire_pressure_and_tread', 'field': 'tire_id', 'value': this.active_tires[value].tire_id});
            },
            dataType(value) {
                this.$store.commit('setWheelText', value);
            }

        },

        methods: {
            getTireUpdateThisWeek() {
                return new Promise((resolve, reject) => {
                    axios.get(`api/tirePressureAndTread/tireUpdateThisWeek/${this.$store.state.forms.tire_pressure_and_tread.vehicle_id}`, {})
                        .then(response => {
                            this.$store.commit('setUpdateTire', response.data.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            getVehicleType(vehicle) {
                // console.log(vehicle);
                this.frame = vehicle.frame;
                this.$store.commit('setActiveTires', vehicle.active_tires);
                this.$store.commit('updateForm', {'form': 'tire_pressure_and_tread', 'field': 'vehicle_id', 'value': vehicle.id})
                this.$store.commit('updateForm', {'form': 'tire_pressure_and_tread', 'field': 'driver_id', 'value': this.driver_id});
                this.getTireUpdateThisWeek();

            },
            setMode(mode) {
                this.$store.commit('setMode', mode)
            },
            submit() {
                this.$refs.tire_pressure_and_tread.validateForm();
                if (this.$refs.tire_pressure_and_tread.validated) {
                    let vehicle_id = this.forms.tire_pressure_and_tread.vehicle_id;
                    let driver_id = this.forms.tire_pressure_and_tread.driver_id;
                    this.$store.commit('loading', true);
                    this.$store.dispatch('submit', {
                        'form': 'tire_pressure_and_tread',
                        'url': '/api/tire_pressure_and_tread',
                    }).then(response => {
                        console.log(response);
                        this.$store.commit('loading', false);
                        this.$store.commit('hideDrawer');

                        this.$store.commit('resetForm', 'tire_pressure_and_tread');
                        this.$store.commit('updateForm', {'form': 'tire_pressure_and_tread', 'field': 'vehicle_id', 'value': vehicle_id});
                        this.$store.commit('updateForm', {'form': 'tire_pressure_and_tread', 'field': 'driver_id', 'value': driver_id});

                        new Promise((resolve, reject) => {
                            axios.get('/api/vehicle/crud/active_tires/' + vehicle_id)
                                .then(response => {
                                    console.log(response.data);
                                    this.getTireUpdateThisWeek();
                                    resolve(response.data);
                                })
                                .catch(error => {
                                    console.log(error);

                                    reject(error.response);
                                });
                        });
                    });
                }
            },
            close() {
                liff.closeWindow()
            },
        },
    }
</script>

<style lang="scss" scoped>

    $liff-red: #c2000bcc;

    .liff-toggle {
        border: 1px solid $liff-red;
        background-color: transparent;
        color: $liff-red;
        padding: 5px 20px;
        line-height: 14px !important;
        font-size: 14px;
    }

    .liff-toggle-active {
        background-color: $liff-red;
        color: white;
    }

    .liff-toggle-left {
        border-radius: 5px 0 0 5px;
        border-right: 0;
    }

    .liff-toggle-right {
        border-radius: 0 5px 5px 0;
        border-left: 0;
    }

</style>
