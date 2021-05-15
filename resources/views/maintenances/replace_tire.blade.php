@extends('layouts.app', ['activePage' => 'indexReplaceTire', 'titlePage' => __('Replace Tire')])

@section('style')

    <style>

        .toggle {
            border: 1px solid #e14eca;
            background-color: transparent;
            color: #e14eca;
            padding: 5px 20px;
            line-height: 14px !important;
            font-size: 14px;
            cursor: pointer;
        }

        .toggle-active {
            background-color: #e14eca;
            color: white;
        }

        .toggle-left {
            border-radius: 5px 0 0 5px;
            border-right: 0;
        }

        .toggle-right {
            border-radius: 0 5px 5px 0;
            border-left: 0;
        }

        .inactive {
            opacity: 0.5 !important;
        }

    </style>

    <link href="/css/syncfusion.css" rel="stylesheet"/>

@endsection
@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Replace Tire"></breadcrumb>


        <card>

            <cardheader title="Vehicle Lists" count="vehicle">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darkgrouptable property="vehicle" :columns="tableColumn" :allowselect="true">

                </darkgrouptable>

            </cardbody>
        </card>

    </div>

    <modal>

        <div v-if="datum" class="card" style="padding: 30px; width: auto; background-color: #1e1e2f;">
            <div style="display: flex;">
                <div style="width: 400px;">
                    <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                        <div @click="$store.state.wheel_text = 'placement'" class="toggle toggle-left" :class="{'toggle-active': wheel_text === 'placement'}">
                            ตำแน่ง
                        </div>
                        <div @click="$store.state.wheel_text = 'tread'" class="toggle" :class="{'toggle-active': wheel_text === 'tread'}">
                            ดอกยาง
                        </div>
                        <div @click="$store.state.wheel_text = 'pressure'" class="toggle toggle-right" :class="{'toggle-active': wheel_text === 'pressure'}">
                            ลมยาง
                        </div>
                    </div>
                    <v-form name="swap">
                        <select-box v-if="swap_mode" field="vehicle_id_2" placeholder="ทะเบียนที่จะสลับ (ถ้ามี)" type="select" @input="vehicle2Selected($event)"
                                    url="vehicle" optiontext="license" optionvalue="id" optiongroup="fleet" addon-left-icon="tim-icons icon-bus-front-12"
                                    allowfilter="true" filtertype="contains" :vparam="[]"></select-box>
                    </v-form>

                    <div v-if="show_vehicle_2" style="width:100%; padding: 30px; background-color: #eeeeee; border-radius: 10px;">
                        <v-form name="swap">
                            <trailer-head v-if="datum_2.frame === 4"></trailer-head>
                            <trailer-8 v-if="datum_2.frame === 5"></trailer-8>
                            <trailer-12 v-if="datum_2.frame === 6"></trailer-12>
                            <vehicle-10 v-if="datum_2.frame === 1"></vehicle-10>
                            <vehicle-tail v-if="datum_2.frame === 3"></vehicle-tail>
                            <vehicle-head v-if="datum_2.frame === 2"></vehicle-head>
                        </v-form>

                    </div>
                    <div v-else style="width:100%; padding: 30px; background-color: #eeeeee; border-radius: 10px;">
                        <v-form name="replace">
                            <trailer-head v-if="datum.frame === 4"></trailer-head>
                            <trailer-8 v-if="datum.frame === 5"></trailer-8>
                            <trailer-12 v-if="datum.frame === 6"></trailer-12>
                            <vehicle-10 v-if="datum.frame === 1"></vehicle-10>
                            <vehicle-tail v-if="datum.frame === 3"></vehicle-tail>
                            <vehicle-head v-if="datum.frame === 2"></vehicle-head>
                        </v-form>

                    </div>

                </div>
                <div style="width: 450px;">
                    <div style="margin-top: 46px">
                        <div style="margin-left: 30px; padding: 30px;background-color: #222a42; border-radius: 10px;">
                            <base-input :forcevalue="getDetail('brand')" placeholder="ยี่ห้อยาง" addon-left-icon="tim-icons icon-tag"
                                        :vparam="[]" showonly>
                            </base-input>
                            <base-input :forcevalue="getDetail('serial')" placeholder="หมายเลขยาง" addon-left-icon="eec-icons icon-code"
                                        :vparam="[]" showonly>
                            </base-input>
                            <base-input :forcevalue="getDetail('tread')" placeholder="ดอกยางล่าสุด" addon-left-icon="eec-icons icon-measure-big"
                                        :vparam="[]" showonly>
                            </base-input>
                        </div>
                        <div style="margin: 15px 0 0 30px; display: flex; justify-content: center;">
                            <base-button @click="swapInit()" wide type="primary" :class="{'disabled': !wheel_select, 'inactive':mode === 'replace'}"
                                         class="btn-previous">
                                สลับยาง
                            </base-button>
                            <base-button @click="mode = 'replace'" style="margin-left: 15px;" wide type="primary"
                                         :class="{'disabled': !wheel_select, 'inactive':mode === 'swap'}" class="btn-previous">
                                เปลี่ยนยาง
                            </base-button>
                        </div>
                        <div style="margin: 15px 0 0 30px; padding: 30px;background-color: #222a42; border-radius: 10px;">
                            <div v-if="mode === 'replace'">
                                <v-form ref="replace" name="replace" style="width: 100%;" class="justify-content-center">
                                    <base-datepicker placeholder="วันที่" field="date" addon-left-icon="eec-icons icon-clock"
                                                     :vparam="['required']">
                                    </base-datepicker>

                                    <select-box placeholder="หมายเลขยางใหม่" field="tire_id" type="select" url="tire/crud/indexAvailableOption" optiontext="serial"
                                                optionvalue="id" addon-left-icon="eec-icons icon-brakes" allowfilter="true" filtertype="contains"
                                                :vparam="['required']"></select-box>

                                    <base-input v-if="!forms.replace.is_spare" placeholder="เลขไมล์" field="mileage" type="number"
                                                addon-left-icon="tim-icons icon-user-run"
                                                :vparam="['required', {'minValue':mileage_1}]">
                                    </base-input>

                                    <base-input v-if="!forms.replace.is_spare" placeholder="ดอกยางตอนเปลี่ยนออก" type="number" field="end_tread_depth"
                                                addon-left-icon="eec-icons icon-measure-big"
                                                :vparam="['required', {'maxValue': tread_1}]">
                                    </base-input>

                                    <select-box placeholder="เหตุผลที่เปลี่ยน" field="reason_id" type="select" url="reason" optiontext="reason"
                                                optionvalue="id" addon-left-icon="eec-icons icon-c-info" allowfilter="true" filtertype="contains"
                                                :vparam="['required']"></select-box>
                                </v-form>
                                <div style="margin: 15px 0 0 30px; display: flex; justify-content: center;">
                                    <base-button @click="submit()" wide type="primary">
                                        ยืนยัน
                                    </base-button>
                                </div>
                            </div>
                            <div v-if="(mode === 'swap')">
                                <div style="margin-bottom: 10px; display: flex; justify-content: center;">
                                    <base-button @click="swapMode()" :class="{'inactive':(swap_mode || !wheel_select_id)}" wide type="danger">
                                        กดเพื่อเลือกยางที่จะสลับ
                                    </base-button>
                                </div>
                                <v-form ref="swap" name="swap" class="row justify-content-center">
                                    <div class="col-12">
                                        <base-datepicker placeholder="วันที่" field="date" addon-left-icon="eec-icons icon-clock"
                                                         :vparam="['required']">
                                        </base-datepicker>
                                    </div>

                                    <div class="col-12" style="padding: 5px 5px 0 5px; border: solid #1d8cf8 1px; border-radius: 5px;">
                                        <base-input placeholder="เลขไมล์" field="end_mileage" type="number" addon-left-icon="tim-icons icon-user-run"
                                                    :vparam="['required', {'minValue':mileage_1}]">
                                        </base-input>
                                        <base-input v-if="!forms.swap.is_spare_1" placeholder="ดอกยางตอนสลับออก (สีฟ้า)" type="number" field="end_tread_depth_1"
                                                    addon-left-icon="eec-icons icon-measure-big"
                                                    :vparam="['required', {'maxValue': tread_1}]">
                                        </base-input>
                                    </div>

                                    <div class="col-12" style="margin-top: 10px; padding: 5px 5px 0 5px; border: solid #c2000b 1px; border-radius: 5px;"
                                         v-if="wheel_swap">
                                        <base-input :forcevalue="getDetailSwap('brand')" placeholder="ยี่ห้อ (สีแดง)" addon-left-icon="tim-icons icon-tag"
                                                    :vparam="[]" showonly>
                                        </base-input>
                                        <base-input :forcevalue="getDetailSwap('serial')" placeholder="หมายเลขยาง (สีแดง)" addon-left-icon="eec-icons icon-code"
                                                    :vparam="[]" showonly>
                                        </base-input>
                                        <base-input v-if="swap_vehicle_2" placeholder="เลขไมล์" field="end_mileage_2" type="number"
                                                    addon-left-icon="tim-icons icon-user-run"
                                                    :vparam="['required', {'minValue':mileage_2}]">
                                        </base-input>
                                        <base-input v-if="!forms.swap.is_spare_2" placeholder="ดอกยางตอนสลับออก (สีแดง)" type="number" field="end_tread_depth_2"
                                                    addon-left-icon="eec-icons icon-measure-big"
                                                    :vparam="['required', {'maxValue': tread_2}]">
                                        </base-input>
                                    </div>

                                </v-form>
                                <div style="margin: 15px 0 0 30px; display: flex; justify-content: center;">
                                    <base-button @click="swap()" wide type="info">
                                        ยืนยัน
                                    </base-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </modal>
@endsection

@push('js')
    <script src=" {{ mix('/js/vue/replace_tire.js') }}"></script>


    <script>

        new Vue({
            el: '#asset',


            store,

            data: {
                tableColumn: [
                    {'text': '#', 'type': 'index'},
                    {'text': 'License', 'data': 'license'},
                    {'text': 'Type', 'data': 'type'},
                    {'text': 'Odometer', 'data': 'mileage', 'type': 'sortNumber'},
                    {'text': 'Action', 'type': 'action', 'data': 'phone', 'align': 'right'},
                ],
                rowIsSelected: false,
                collapseOnSelect: false,
                mode: null,
                submitting: false,
            },

            computed: {
                ...mapState([
                    'rowSelected',
                    'rowId',
                    'datum',
                    'wheel_text',
                    'wheel_select',
                    'wheel_select_id',
                    'wheel_swap',
                    'swap_mode',
                    'showModal',
                    'forms',
                    'swap_vehicle_2',
                    'datum_2'
                ]),
                show_vehicle_2: function () {
                    return this.swap_mode && this.datum_2.frame;
                },
                mileage_1: function () {
                    if (this.datum) {
                        return this.datum.vehicle.mileage;
                    }
                },
                tread_1: function () {
                    if (this.wheel_select) {
                        if (this.datum.activeTire[this.wheel_select]) {
                            if (this.datum.activeTire[this.wheel_select].end_tread_depth) {
                                return this.datum.activeTire[this.wheel_select].end_tread_depth;
                            }
                            return this.datum.activeTire[this.wheel_select].start_tread_depth;
                        }
                    }
                },
                mileage_2: function () {
                    if (this.datum_2.vehicle) {
                        return this.datum_2.vehicle.mileage;
                    }
                },
                tread_2: function () {
                    if (this.datum_2.vehicle) {
                        if (this.datum_2.activeTire[this.wheel_swap].end_tread_depth) {
                            return this.datum_2.activeTire[this.wheel_swap].end_tread_depth;
                        }
                        return this.datum_2.activeTire[this.wheel_swap].start_tread_depth;
                    } else {
                        if (this.datum.activeTire[this.wheel_swap].end_tread_depth) {
                            return this.datum.activeTire[this.wheel_swap].end_tread_depth;
                        }
                        return this.datum.activeTire[this.wheel_swap].start_tread_depth;
                    }
                },
            },

            watch: {
                rowSelected(value) {
                    if (value) {
                        this.$store.commit('showModal', 'replace-tire');
                    } else {
                        this.mode = null;
                    }
                },
                rowId(value) {
                    if (value) {
                        this.$store.commit('updateForm', {'form': 'replace', 'field': 'vehicle_id', 'value': value});
                        this.$store.commit('updateForm', {'form': 'swap', 'field': 'vehicle_id', 'value': value});
                    }
                },
                showModal(value) {
                    if (!value) {
                        this.$store.commit('rowDeSelected');
                        this.$store.state.wheel_select = null;
                        this.$store.state.wheel_select_id = null;
                        this.$store.commit('resetForm', 'replace');
                        this.$store.commit('resetForm', 'swap');

                        this.$store.state.wheel_swap = null;
                        this.$store.state.wheel_swap_id = null;
                        this.$store.state.swap_mode = false;
                        this.$store.state.swap_vehicle_2 = false;
                    }
                },
                mode(value) {
                    if (value === 'replace') {
                        this.$store.state.wheel_swap = null;
                        this.$store.state.wheel_swap_id = null;
                    }
                }
            },

            created() {
                this.$store.commit('setModel', 'vehicle');

                this.$store.dispatch('populateForm', {
                    'property': 'replace',
                    'form': 'replace',
                    'field': {
                        vehicle_id: null,
                        date: moment().format('YYYY-MM-DD HH:mm:ss'),
                        tire_id: null,
                        placement: null,
                        placement_id: null,
                        mileage: null,
                        is_spare: null,
                        end_tread_depth: null,
                        reason_id: null,
                    }
                });

                this.$store.dispatch('populateForm', {
                    'property': 'swap',
                    'form': 'swap',
                    'field': {
                        date: moment().format('YYYY-MM-DD HH:mm:ss'),
                        placement_id_1: null,
                        placement_id_2: null,
                        end_mileage: null,
                        end_mileage_2: null,
                        is_spare_1: null,
                        is_spare_2: null,
                        end_tread_depth_1: null,
                        end_tread_depth_2: null,
                    }
                });
            },

            mounted() {
            },

            methods: {

                refresh() {
                    this.$store.dispatch('getTableData', {'property': 'vehicle', 'is_group': true});
                },

                getDetail(property) {
                    if (this.wheel_select) {
                        if (this.datum.activeTire[this.wheel_select]) {
                            switch (property) {
                                case 'tread':
                                    if (this.datum.activeTire[this.wheel_select]['end_tread_depth']) {
                                        return this.datum.activeTire[this.wheel_select]['end_tread_depth'] + ' mm';
                                    }
                                    return this.datum.activeTire[this.wheel_select]['start_tread_depth'] + ' mm';
                                default:
                                    return this.datum.activeTire[this.wheel_select][property];
                            }
                        }
                    }
                    return null;
                },
                getDetailSwap(property) {
                    if (this.wheel_swap) {
                        if (this.swap_vehicle_2) {
                            switch (property) {
                                case 'tread':
                                    if (this.datum_2.activeTire[this.wheel_swap]['end_tread_depth']) {
                                        return this.datum_2.activeTire[this.wheel_swap]['end_tread_depth'] + ' mm';
                                    }
                                    return this.datum_2.activeTire[this.wheel_swap]['start_tread_depth'] + ' mm';
                                default:
                                    return this.datum_2.activeTire[this.wheel_swap][property];
                            }
                        } else {
                            switch (property) {
                                case 'tread':
                                    if (this.datum.activeTire[this.wheel_swap]['end_tread_depth']) {
                                        return this.datum.activeTire[this.wheel_swap]['end_tread_depth'] + ' mm';
                                    }
                                    return this.datum.activeTire[this.wheel_swap]['start_tread_depth'] + ' mm';
                                default:
                                    return this.datum.activeTire[this.wheel_swap][property];
                            }
                        }
                    }
                    return null;
                },

                submit() {
                    if (!this.submitting) {
                        this.submitting = true;
                        this.$refs.replace.validateForm();
                        if (this.$refs.replace.validated) {
                            this.$store.dispatch('submit', {'form': 'replace', 'url': '/api/tire_placement/crud/replace', 'reset': false})
                                .then(response => {
                                    console.log(response);
                                    Swal.fire('Complete!', 'เปลี่ยนยางสำเร็จ!', 'success');
                                    this.mode = null;
                                    // this.$store.commit('resetForm', 'replace');

                                    this.$store.dispatch('getRowData', this.rowId);
                                    this.$store.commit('updateForm', {'form': 'replace', 'field': 'vehicle_id', 'value': this.rowId});
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        }
                        this.submitting = false;
                    }
                },
                swap() {
                    if (!this.submitting) {
                        this.submitting = true;
                        this.$refs.swap.validateForm();
                        if (this.$refs.swap.validated) {
                            this.$store.commit('updateForm', {'form': 'swap', 'field': 'is_on_vehicle', 'value': 0});
                            this.$store.commit('updateForm', {'form': 'swap', 'field': 'reason_id', 'value': 6});
                            this.$store.dispatch('submit', {'form': 'swap', 'url': '/api/tire_placement/crud/swap', 'reset': false})
                                .then(response => {
                                    console.log(response);
                                    this.$store.dispatch('getRowData', this.rowId);
                                    this.mode = null;
                                    this.$store.commit('resetForm', 'swap');
                                    this.$store.state.wheel_swap = null;

                                    this.$store.commit('updateForm', {'form': 'swap', 'field': 'vehicle_id', 'value': this.rowId});
                                    Swal.fire('Complete!', 'สลับยางสำเร็จ!', 'success');
                                })
                                .catch(error => {
                                    console.log(error);
                                });
                        }
                        this.submitting = false;
                    }
                },
                swapMode() {
                    if (this.datum.activeTire[this.wheel_select].brand) {
                        this.$store.commit('swapMode', true);
                    }
                },
                swapInit() {
                    if (this.wheel_select_id) {
                        this.mode = 'swap';
                    }
                },
                vehicle2Selected(vehicle_id) {

                    if (vehicle_id) {
                        this.$store.commit("loading", true);
                        return new Promise((resolve, reject) => {
                            axios.get('/api/vehicle/' + vehicle_id)
                                .then(response => {
                                    this.$store.state.datum_2 = response.data.data;

                                    this.$store.commit("loading", false);

                                    console.log(response.data.data);
                                    resolve(response.data);
                                })
                                .catch(error => {
                                    console.log(error);
                                    reject(error.response);
                                });
                        });
                    } else {
                        this.$store.state.datum_2 = {};
                        this.$store.state.swap_vehicle_2 = false;
                        this.$store.state.wheel_swap = null;
                        this.$store.state.wheel_swap_id = null;
                        this.$store.commit('updateForm', {'form': 'swap', 'field': 'end_mileage_2', 'value': null});
                    }
                }
            }
        });
    </script>
@endpush
