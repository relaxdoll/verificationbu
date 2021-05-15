@extends('layouts.liff', ['activePage' => 'indexReplaceTire', 'titlePage' => __('Fast Track')])

@section('style')
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('content')

    <div class="content">

        <liff-header title="Fast Track"></liff-header>

        <liff-form ref='form' name="form">
            <liff-input-group class="spacing">
                <liffdropdown2 icon="icon icon-bus-front-10" iconcolor="#fc9500" :searchbar="false" label="ทะเบียนรถ"
                               :url="'driver/crud/getAssignedHead/' + $store.state.driver_id" :allowgetoption="$store.state.driver_id"
                               field="vehicle_id" optiontext="license" optionvalue="id" :vparam="['required']"></liffdropdown2>

                <liffdropdown2 icon="icon icon-single-05" iconcolor="#007aff" :searchbar="false" label="ลูกค้า" optiontext="nameTH" optionvalue="id"
                               :url="'customer/crud/getCustomerByFleetId/' + $store.state.forms.form.fleet_id" :allowgetoption="$store.state.forms.form.fleet_id"
                               field="customer_id" :disable="!allowCustomer" :vparam="['required']"></liffdropdown2>

                <liffdropdown2 icon="icon icon-document" iconcolor="#31c85a" :searchbar="false" label="รายงาน" optiontext="title" optionvalue="id"
                               @input="reportSelected($event)"
                               :url="'customer/crud/getCustomerReport/' + $store.state.forms.form.customer_id" :allowgetoption="$store.state.forms.form.customer_id"
                               field="report_id" :col="5" :disable="!allowReport" :vparam="['required']"></liffdropdown2>
            </liff-input-group>

            <liff-input-group ref="image" class="spacing" v-show="$store.state.forms.form.report_id">
                <liff-image-upload v-for="(image_title, index) in image_titles" :key="index" :field="index" :label="image_title.title"
                                   :require="true"></liff-image-upload>
            </liff-input-group>
        </liff-form>

        <liff-submit-button @submit="submit()"></liff-submit-button>

        <liff-uploader text="Uploading" :show="uploading">
            <liff-radial-progress-bar style="width: 100%; margin-top:15px; margin-bottom:5px;" :speed="100"></liff-radial-progress-bar>
        </liff-uploader>

        <liff-uploader text="Processing" :show="processing">
            <liff-radial-progress-bar style="width: 100%; margin-top:15px; margin-bottom:5px;" :speed="100"></liff-radial-progress-bar>
        </liff-uploader>

        <liffalert @on-hide="close()" :value="showAlert" title="ส่งรายงานสำเร็จ" content="รายงานได้ถูกส่งไปยังกลุ่มที่เลือกแล้ว" buttontext="ปิด"></liffalert>
    </div>


@endsection

@section('js')
    <script src="https://static.line-scdn.net/liff/edge/versions/2.5.0/sdk.js"></script>
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
                allowCustomer: false,
                allowReport: false,

                submitting: false,
                image_titles: {},
                showAlert: false,
                done: false,
            },

            created() {
                this.$store.dispatch('liffInit');
                this.$store.commit('setPageName', 'Image Track');
                this.$store.dispatch('populateForm', {
                    'property': 'image_track',
                    'form': 'form',
                    'field': {
                        note: null,
                        image_array: {},
                        customer_id: null,
                        driver_id: null,
                        fleet_id: null,
                        report_id: null,
                        vehicle_id: null,
                        job_id: null,
                        image_number: null,
                    }
                });
            },


            mounted() {
            },

            computed: {
                ...mapState([
                    'forms',
                    'driver',
                    'uploading',
                    'processing',
                    'uploadPercentage',
                ]),
            },

            watch: {
                driver(value) {
                    this.$store.commit('updateForm', {'form': 'form', 'field': 'fleet_id', 'value': value.fleet_id});
                },
                forms: {
                    deep: true,

                    handler() {
                        if (this.forms.form.vehicle_id) {
                            this.allowCustomer = true;
                        }
                        if (this.forms.form.customer_id) {
                            this.allowReport = true;
                        }
                    }
                },
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


            methods: {
                reportSelected(report) {
                    new Promise(function (resolve, reject) {
                        setTimeout(function () {
                            this.$store.commit('updateForm', {'form': 'form', 'field': 'driver_id', 'value': this.$store.state.driver_id});

                            let image_titles = {};

                            for (let i = 1; i <= report.image_number; i++) {
                                image_titles[i] = null;
                            }

                            this.image_titles = report.image_title;

                            this.$store.commit('updateForm', {'form': 'form', 'field': 'image_number', 'value': report.image_number});
                            this.$store.commit('updateForm', {'form': 'form', 'field': 'note', 'value': report.description});
                            this.$store.commit('updateForm', {'form': 'form', 'field': 'image_array', 'value': image_titles});
                            resolve('foo');
                        }.bind(this), 0);
                    }.bind(this))
                        .then(response => {

                            this.$refs.form.inputs = [];
                            this.$refs.form.getInput(this.$refs.form);
                        });


                },
                submit() {
                    if (!this.submitting) {
                        this.submitting = true;
                        this.$refs.form.validateForm();
                        if (this.$refs.form.validated) {
                            this.$store.commit('uploading', true);
                            this.$store.dispatch('submitFile', {'form': 'form', 'url': '/api/image_track_report', 'reset': false})
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
