@extends('layouts.liff', ['activePage' => 'indexReplaceTire', 'titlePage' => __('Fast Track')])

@section('style')
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('content')

    <div class="content">

        <liff-header title="Refuel"></liff-header>

        <liff-form ref='form' name="form">
            <liff-input-group class="spacing">
                <liffdropdown2 icon="icon icon-bus-front-10" iconcolor="#fc9500" :searchbar="false" label="ทะเบียนรถ" @input="setDriver($event)"
                               :url="'driver/crud/getAssignedHead/' + $store.state.driver_id" :allowgetoption="$store.state.driver_id"
                               field="vehicle_id" optiontext="license" optionvalue="id" :vparam="['required']"></liffdropdown2>

                <liff-input field="odometer" icon="icon icon-h-dashboard" iconcolor="#007aff" placeholder="เลขไมล์" type="number" :vparam="['required', {'minValue':mileage}]"
                            :disable="!$store.state.forms.form.vehicle_id"></liff-input>

                <liff-input field="quantity" icon="icon icon-fuel-2" iconcolor="#fc3e30" placeholder="จำนวนลิตร" type="number" :vparam="['required']"
                            :disable="!$store.state.forms.form.vehicle_id"></liff-input>

            </liff-input-group>

            <liff-input-group ref="image" class="spacing">
                <liff-image-upload field="0" label="เลขไมล์" :require="true" :disable="!$store.state.forms.form.vehicle_id"></liff-image-upload>

                <liff-image-upload field="1" label="จำนวนลิตร" :require="true" :disable="!$store.state.forms.form.vehicle_id"></liff-image-upload>

                <liff-image-upload field="2" label="รูปน้ำมันเต็มถัง" :require="true" :disable="!$store.state.forms.form.vehicle_id"></liff-image-upload>
            </liff-input-group>
        </liff-form>

        <liff-submit-button @submit="submit()"></liff-submit-button>

        <liff-uploader text="Uploading" :show="uploading">
            <liff-radial-progress-bar style="width: 100%; margin-top:15px; margin-bottom:5px;" :speed="100"></liff-radial-progress-bar>
        </liff-uploader>

        <liff-uploader text="Processing" :show="processing">
            <liff-radial-progress-bar style="width: 100%; margin-top:15px; margin-bottom:5px;" :speed="100"></liff-radial-progress-bar>
        </liff-uploader>

    </div>


@endsection

@section('js')
    <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <script src=" {{ mix('/js/vue/liff.js') }}"></script>

    <script>

        const sleep = async (ms) => {
            if (ms <= 0) return;
            return new Promise(resolve => {
                setTimeout(resolve, ms)
            });
        };

        new Vue({
            el: '#asset',

            store,

            data: {
                showAlert: false,
                done: false,
                submitting: false,
                mileage: 0,
            },

            watch: {
                uploadPercentage(value) {
                    if (value === 100 && !this.processing) {
                        this.$store.commit('uploading', false);
                        this.$store.commit('processing', true);
                    }
                },
                processing: {
                    async handler(value) {
                        if (value) {
                            while (this.uploadPercentage < 75 && !this.done) {

                                if (this.uploadPercentage < 75) {
                                    this.$store.state.uploadPercentage++;
                                }

                                await sleep(150);
                            }

                            while (this.uploadPercentage < 100) {
                                if (this.uploadPercentage > 95) {
                                    this.$store.state.uploadPercentage = 100;
                                } else {
                                    this.$store.state.uploadPercentage += 3;
                                }

                                await sleep(30);
                            }
                            this.done = false;
                            this.$store.commit('processing', false);
                        }
                    }
                }

            },


            created() {
                this.$store.dispatch('liffInit');
                this.$store.commit('setPageName', 'Image Track');
                this.$store.dispatch('populateForm', {
                    'property': 'image_track',
                    'form': 'form',
                    'field': {
                        image_array: {},
                        driver_id: null,
                        vehicle_id: null,
                        odometer: null,
                        quantity: null,
                        job_id: null,
                        0: null,
                        1: null,
                        2: null,
                        image_number: 3,
                    }
                });
            },


            mounted() {
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms',
                    'validate',
                    'uploading',
                    'processing',
                    'uploadPercentage',
                ]),
            },

            methods: {
                setDriver(vehicle) {
                    this.mileage = vehicle.mileage + 1;
                    this.$store.commit('updateForm', {'form': 'form', 'field': 'driver_id', 'value': this.$store.state.driver_id});
                },
                submit() {
                    if (!this.submitting) {
                        this.submitting = true;
                        this.$refs.form.validateForm();
                        if (this.$refs.form.validated) {
                            this.$store.commit('uploading', true);
                            this.$store.dispatch('submitFile', {'form': 'form', 'url': '/api/refuel', 'reset': false})
                                .then(response => {
                                    console.log(response);
                                    this.done = true;

                                    liff.sendMessages([
                                        response.data.flex
                                    ]);

                                    liff.closeWindow();
                                })
                                .catch(error => {
                                    console.log(error);
                                    this.submitting = false;
                                });
                        } else {
                            this.submitting = false;
                        }
                    }
                },
                close() {
                    liff.closeWindow()
                },
            },
        });
    </script>
@endsection
