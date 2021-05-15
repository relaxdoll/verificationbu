<template>
    <div v-show="mode === 'แจ้งซ่อม'">
        <liffloader :show="$store.state.loading" text="Saving"></liffloader>

        <liff-form name="maintenance_approval">
            <liff-input-group class="spacing">
                <liffdropdown2 ref="vehicle_drop" icon="icon icon-bus-front-10" iconcolor="#fc9500" :searchbar="false" label="ทะเบียนรถ" @input="setDriver($event)"
                               :url="'driver/crud/getAssignedVehicle/' + $store.state.driver_id" :allowgetoption="$store.state.driver_id"
                               field="vehicle_id" optiontext="license" optionvalue="id" :vparam="['required']"></liffdropdown2>

            </liff-input-group>
        </liff-form>

        <div v-if="isFinished">
            <liff-form ref="form" name="maintenance_approval" v-if="$store.state.forms.maintenance_approval.vehicle_id">
                <liff-input-group class="spacing">
                    <liff-input field="request_mileage" icon="icon icon-h-dashboard" iconcolor="#fc3e30" placeholder="เลขไมล์" type="number"
                                :vparam="['required']"></liff-input>

                    <liff-input field="symptom" icon="icon icon-code" iconcolor="#007aff" placeholder="อาการเบื้องต้น" type="text" :vparam="[]"></liff-input>

                </liff-input-group>

<!--                <liff-input-group ref="image" class="spacing">-->
<!--                    <liff-image-upload :field="image.id" :label="'รูปที่ ' + (image.id + 1)" v-for="image in images" :key="image.id"></liff-image-upload>-->
<!--                    <div class="text-center pt-4">-->
<!--                        <button class="btn btn-danger" @click="deleteImage()">-->
<!--                            <i class="fas fa-minus"></i>-->
<!--                        </button>-->
<!--                        <button class="btn btn-primary" @click="addImage()">-->
<!--                            <i class="fas fa-plus"></i>-->
<!--                        </button>-->
<!--                    </div>-->
<!--                </liff-input-group>-->

            </liff-form>

            <liff-submit-button @submit="submit()" label="บันทึก"></liff-submit-button>

        </div>

<!--        <liff-uploader text="Uploading" :show="uploading">-->
<!--            <liff-radial-progress-bar style="width: 100%; margin-top:15px; margin-bottom:5px;" :speed="100"></liff-radial-progress-bar>-->
<!--        </liff-uploader>-->

<!--        <liff-uploader text="Processing" :show="processing">-->
<!--            <liff-radial-progress-bar style="width: 100%; margin-top:15px; margin-bottom:5px;" :speed="100"></liff-radial-progress-bar>-->
<!--        </liff-uploader>-->
    </div>
</template>

<script>
    const sleep = async (ms) => {
        if (ms <= 0) return;
        return new Promise(resolve => {
            setTimeout(resolve, ms)
        });
    };

    export default {
        name: "MaintenanceApprovalCreate",

        store,

        data() {
            return {
                images: [],
                done: false,
                isFinished: true,
            }
        },

        created() {
            this.$store.dispatch('populateForm', {
                'property': 'maintenance',
                'form': 'maintenance_approval',
                'field': {
                    request_date: null,
                    requester_id: null,
                    request_mileage: null,
                    symptom: null,
                    vehicle_id: null,
                    start_image_array: null,
                    image_number: 0,
                }
            });
        },

        computed: {
            ...mapState([
                'mode',
                'forms',
                'driver_id',
                // 'uploading',
                // 'processing',
                // 'uploadPercentage',
            ]),
        },

        watch: {
            // uploadPercentage(value) {
            //     if (value === 100 && !this.processing) {
            //         this.$store.commit('uploading', false);
            //         this.$store.commit('processing', true);
            //     }
            // },
            // processing: {
            //     async handler(value) {
            //         if (value) {
            //             while (this.uploadPercentage < 75 && !this.done) {
            //
            //                 if (this.uploadPercentage < 75) {
            //                     this.$store.state.uploadPercentage++;
            //                 }
            //
            //                 await sleep(150);
            //             }
            //
            //             while (this.uploadPercentage < 100) {
            //                 if (this.uploadPercentage > 95) {
            //                     this.$store.state.uploadPercentage = 100;
            //                 } else {
            //                     this.$store.state.uploadPercentage += 3;
            //                 }
            //
            //                 await sleep(30);
            //             }
            //             this.done = false;
            //             this.$store.commit('processing', false);
            //             this.$store.commit('resetForm', 'maintenance_approval');
            //             this.setDriver(null);
            //         }
            //     }
            // },
        },

        methods: {
            addImage: function () {
                let imageId = this.forms.maintenance_approval.image_number;
                this.images.push({
                    id: imageId,
                })
                this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': imageId, 'value': null});
                this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'image_number', 'value': ++imageId});
            },
            deleteImage: function () {
                let imageId = this.forms.maintenance_approval.image_number;
                this.images.pop();
                this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'image_number', 'value': --imageId});
            },
            setDriver(vehicle) {
                this.checkIsVehicleInRequested(vehicle);
                this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'requester_id', 'value': this.$store.state.driver_id});
            },
            checkIsVehicleInRequested(vehicle) {
                return new Promise((resolve, reject) => {
                    axios.get(`api/maintenance_approval/crud/isVehicleFinished/${vehicle.id}`, {})
                        .then(response => {
                            console.log(response);
                            this.isFinished = response.data.data;
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });

            },
            submit() {
                this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'request_date', 'value': moment().format('YYYY-MM-DD HH:mm:ss')});

                this.$refs.form.validateForm();
                if (this.$refs.form.validated) {
                    this.$store.commit('loading', true);
                    this.$store.dispatch('submitFile', {'form': 'maintenance_approval', 'url': '/api/maintenance_approval', 'reset': true})
                        .then(response => {
                            console.log(response);
                            this.$store.commit('loading', false);
                            this.$store.commit('resetForm', 'maintenance_approval');
                            this.$store.commit('setMode', 'รายการซ่อมของฉัน');
                        });
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
