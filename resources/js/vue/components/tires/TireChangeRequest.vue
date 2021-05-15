<template>
    <div v-show="mode === 'แจ้งเปลี่ยนยาง'">
        <liffloader :show="$store.state.loading" text="Saving"></liffloader>

        <liff-form name="tire_change_request">
            <liff-input-group class="spacing">
                <liffdropdown2 ref="vehicle_drop" icon="icon icon-bus-front-10" iconcolor="#fc9500" :searchbar="false" label="ทะเบียนรถ" @input="getVehicleType($event)"
                               :url="'driver/crud/getAssignedVehicle/' + $store.state.driver_id" :allowgetoption="$store.state.driver_id"
                               field="vehicle_id" optiontext="license" optionvalue="id" :vparam="['required']"></liffdropdown2>

            </liff-input-group>
        </liff-form>

        <liff-form name="tire_change_request" field="placement">

            <div class="spacing vehicle-wrapper" v-if="forms.tire_change_request.vehicle_id">

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

                <liff-form name="tire_change_request" ref="tire_change_request" v-if="mode === 'แจ้งเปลี่ยนยาง'">

                    <div v-if="active_tires[placement].tire_change_request === null">
                        <liff-input-group class="spacing">
                            <liffdropdown2 icon="icon icon-tag" iconcolor="#fc9500" :searchbar="false" label="สาเหตุที่ขอเปลี่ยนยาง" @input="reasonSelect($event)"
                                           url="reason" field="reason_id" optiontext="reason" optionvalue="id" :vparam="['required']"></liffdropdown2>

                        </liff-input-group>

                        <liff-input-group class="spacing">
                            <liff-image-upload field="link" label="ยางที่ขอเปลี่ยน" :require="true"
                                               :disable="!$store.state.forms.tire_change_request.reason_id"></liff-image-upload>
                        </liff-input-group>
                    </div>

                    <div v-else>
                        <button @click="cancelRequest()">Delete</button>
                    </div>


                </liff-form>
                <liff-submit-button @submit="submit()" label="บันทึก"></liff-submit-button>

            </div>

        </liffdrawer2>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "TireChangeRequest",
        data() {
            return {
                selectedDate: new Date(),
                frame: null,
            }
        },

        created() {
            this.$store.dispatch('populateForm', {
                'property': 'tire',
                'form': 'tire_change_request',
                'field': {
                    tire_placement_id: null,
                    reason_id: null,
                    driver_id: null,
                    vehicle_id: null,
                    link: null,
                }
            });
        },

        mounted() {
        },

        computed: {
            ...mapState([
                'forms',
                'driver_id',
                'placement',
                'active_tires',
                'mode',
            ]),
        },

        watch: {
            driver_id(value) {
                this.$store.commit('updateForm', {'form': 'tire_change_request', 'field': 'driver_id', 'value': value});
            },
            placement(value) {
                this.$store.commit('updateForm', {'form': 'tire_change_request', 'field': 'tire_placement_id', 'value': this.active_tires[value].id});
            },
        },

        methods: {
            getVehicleType(vehicle) {
                this.frame = vehicle.frame;
                this.$store.commit('setActiveTires', vehicle.active_tires);
                this.$store.commit('setHasRequests', vehicle.has_requests);
                this.$store.commit('updateForm', {'form': 'tire_change_request', 'field': 'vehicle_id', 'value': vehicle.id})
                this.$store.commit('updateForm', {'form': 'tire_change_request', 'field': 'driver_id', 'value': this.driver_id});
            },
            reasonSelect(reason) {
                this.$store.commit('updateForm', {'form': 'tire_change_request', 'field': 'reason_id', 'value': reason.id});
            },
            setMode(mode) {
                this.$store.commit('setMode', mode)
            },
            submit() {
                this.$refs.tire_change_request.validateForm();
                if (this.$refs.tire_change_request.validated) {
                    let vehicle_id = this.forms.tire_change_request.vehicle_id;
                    let driver_id = this.forms.tire_change_request.driver_id;
                    this.$store.commit('loading', true);
                    this.$store.dispatch('submitFile', {
                        'form': 'tire_change_request',
                        'url': '/api/tire_change_request',
                        'reset': false
                    }).then(response => {
                        console.log(response);
                        this.$store.commit('loading', false);
                        this.$store.commit('hideDrawer');

                        this.$store.commit('resetForm', 'tire_change_request');
                        this.$store.commit('updateForm', {'form': 'tire_change_request', 'field': 'vehicle_id', 'value': vehicle_id});
                        this.$store.commit('updateForm', {'form': 'tire_change_request', 'field': 'driver_id', 'value': driver_id});

                        new Promise((resolve, reject) => {
                            axios.get('/api/vehicle/crud/active_tires/' + vehicle_id)
                                .then(response => {
                                    console.log(response.data);
                                    this.$store.commit('setActiveTires', response.data.data[0].active_tires);
                                    this.$store.commit('setHasRequests', response.data.data[0].has_requests);
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
            cancelRequest() {
                const id = this.active_tires[this.placement].tire_change_request.id;
                let vehicle_id = this.forms.tire_change_request.vehicle_id;
                let driver_id = this.forms.tire_change_request.driver_id;
                this.$store.commit('loading', true);
                if (confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิกคำร้องขอเปลี่ยนยาง')) {
                    return new Promise((resolve, reject) => {
                        axios.delete(`/api/tire_change_request/${id}`)
                            .then(response => {
                                this.$store.commit('loading', false);
                                this.$store.commit('hideDrawer');

                                this.$store.commit('resetForm', 'tire_change_request');
                                this.$store.commit('updateForm', {'form': 'tire_change_request', 'field': 'vehicle_id', 'value': vehicle_id});
                                this.$store.commit('updateForm', {'form': 'tire_change_request', 'field': 'driver_id', 'value': driver_id});

                                new Promise((resolve, reject) => {
                                    axios.get('/api/vehicle/crud/active_tires/' + vehicle_id)
                                        .then(response => {
                                            console.log(response.data);
                                            this.$store.commit('setActiveTires', response.data.data[0].active_tires);
                                            this.$store.commit('setHasRequests', response.data.data[0].has_requests);
                                            resolve(response.data);
                                        })
                                        .catch(error => {
                                            console.log(error);

                                            reject(error.response);
                                        });
                                });
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });

                } else {
                    console.log('no');
                }
            },
            close() {
                liff.closeWindow()
            },
        },
    }
</script>

<style scoped>

</style>
