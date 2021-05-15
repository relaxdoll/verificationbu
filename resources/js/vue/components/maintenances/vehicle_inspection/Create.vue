<template>
    <div>
        <liffgroup style="margin-bottom:60px;">
            <liffoption @click="select(option)" v-for="(option, index) in options" :key="index" :text="option.license"></liffoption>
        </liffgroup>

        <liffdrawer2 @close="selected1 = false" align="right" :maskclosable="true" label="รายการตรวจสภาพรถ" :currentview="$store.page_name" :closeable="true">
            <div v-if="selected1">
                <liff-input-group class="spacing">
                    <div v-for="(data, index) in dataSet" :key="index" :style="[index === (dataSet.length - 1) ? {'margin-bottom': '110px'} : {}]">
                        <div class="text-wrapper" :class="getClass(index, dataSet.length)">
                            <div class="row" @click="selectInspection(data)">
                                <div class="col-8 text-option">
                                    {{data.name}}
                                </div>
                                <div class="col-4 checked">
                                    {{ data.value }}
                                    <span class="next">></span>
                                </div>
                            </div>
                        </div>
                        <label v-if="data.error" class="error" style="color: #ec250d; font-size: 12px; padding-left: 20px;">{{ data.error }}</label>
                    </div>

                </liff-input-group>

                <liff-submit-button @submit="submit()" label="บันทึก"></liff-submit-button>
            </div>
        </liffdrawer2>

        <liffdrawer2 @close="selected2 = false" align="right" :maskclosable="true" :label="inspection.label" :currentview="$store.page_name" :closeable="true">
            <div v-if="selected2">
                <liff-form ref="form" name="vehicle_inspection_log">
                    <liff-input-group class="spacing">
                        <div v-for="(data, index) in [{text: 'ปกติ', value: 1}, {text: 'ไม่ปกติ', value: 0}]" :key="index">
                            <div class="text-wrapper" :class="getClass(index, 2)">
                                <div class="row" @click="updateInspection(data)">
                                    <div class="col-12 text-option">
                                        {{data.text}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </liff-input-group>

                    <liff-input-group class="spacing">
                        <div class="text-wrapper inspection-select-option-first bg-primary" style="border-bottom: #DDDDDD 0.8px solid; color: white">
                            <div class="row">
                                <div class="col-12" style="font-size: 12px;">
                                    ค่ามาตรฐาน : {{inspection.standard}}
                                </div>
                            </div>
                        </div>
                    </liff-input-group>

                </liff-form>
            </div>
        </liffdrawer2>

        <liffdrawer2 @close="selected3 = false" align="right" :maskclosable="true" :label="inspection.label" :currentview="$store.page_name" :closeable="true">
            <div v-if="selected3">
                <liff-form ref="need_repair" name="need_repair">
                    <liff-input-group class="spacing">
                        <liff-input field="symptom" icon="icon icon-measure-big" iconcolor="#007aff" placeholder="ไม่ปกติอย่างไร จงอธิบาย" type="text"
                                    :vparam="['required']"

                        ></liff-input>
                    </liff-input-group>

                    <liff-submit-button @submit="updateNeedRepairInspection()" label="บันทึก"></liff-submit-button>
                </liff-form>
            </div>
        </liffdrawer2>

    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "Create",
        props: {},

        data() {
            return {
                selected1: false,
                selected2: false,
                selected3: false,
                options: null,
                dataSet: null,
                logs: [],

                inspection: {
                    id: null,
                    label: '',
                    standard: '',
                },
            }
        },
        created() {
            this.$store.dispatch('populateForm', {
                'property': 'vehicle_inspection',
                'form': 'vehicle_inspection_log',
                'field': {
                    log: null,
                    driver_id: this.driver_id,
                    vehicle_id: null,
                }
            });

            this.$store.dispatch('populateForm', {
                'property': 'vehicle_inspection',
                'form': 'need_repair',
                'field': {
                    symptom: null,
                }
            });

            this.getAssignedVehicle();
        },
        mounted() {
        },
        computed: {
            ...mapState([
                'forms',
                'driver_id',
            ]),
        },
        watch: {
            selected3() {
                this.$store.commit('updateForm', {'form': 'need_repair', 'field': 'symptom', 'value': null});
            },
        },
        methods: {
            select(option) {
                this.selected1 = true;
                this.$store.commit('updateForm', {'form': 'vehicle_inspection_log', 'field': 'vehicle_id', 'value': option.id});
                this.getInspectionList(option);
            },
            selectInspection(inspection) {
                this.selected2 = true;
                this.inspection.id = inspection.id;
                this.inspection.label = inspection.name;
                this.inspection.standard = inspection.standard;
            },
            updateDataSetSelectedValue(text) {
                this.dataSet.forEach((data) => {
                    if (data.id === this.inspection.id) {
                        data.value = text;
                        data.error = null;
                    }
                });
            },
            updateInspection(data) {
                if (data.text === 'ปกติ') {
                    this.logs.push({inspection_list_id: this.inspection.id, value: data.value});
                    this.updateDataSetSelectedValue(data.text);
                    this.selected2 = false;
                    this.inspection = {};
                } else {
                    this.selected3 = true;
                    this.inspection.value = data.value;
                    this.inspection.text = data.text;
                }
            },
            updateNeedRepairInspection() {
                this.$refs.need_repair.validateForm();
                if (this.$refs.need_repair.validated) {
                    this.updateDataSetSelectedValue(this.inspection.text);
                    this.logs.push({inspection_list_id: this.inspection.id, value: this.inspection.value, symptom: this.forms.need_repair.symptom});

                    this.selected2 = false;
                    this.selected3 = false;
                    this.inspection = {};
                }
            },
            getInspectionList(vehicle) {
                return new Promise((resolve, reject) => {
                    axios.get(`/api/vehicle_inspection_list/crud/getInspectionListByVehicleType/${vehicle.vehicle_type_id}`, {})
                        .then(response => {
                            this.dataSet = response.data.data;
                            console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            getAssignedVehicle() {
                return new Promise((resolve, reject) => {
                    axios.get(`/api/driver/crud/getAssignedVehicle/${this.driver_id}`, {})
                        .then(response => {
                            this.options = response.data.data;
                            console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            getClass(index, length = 0) {
                let classText = 'inspection-select-option';
                if (index === 0) {
                    classText = 'inspection-select-option-first';
                } else if (index === (length - 1)) {
                    classText = 'inspection-select-option-last';
                }
                return classText;
            },
            validated() {
                let isValidated = false;
                if (this.logs.length === this.dataSet.length) {
                    isValidated = true;
                } else {
                    this.dataSet.forEach((data) => {
                        if (!data.value) {
                            data.error = 'This field is require';
                        }
                    });
                }
                this.$forceUpdate();
                return isValidated;
            },
            submit() {
                const validated = this.validated();
                if (validated) {
                    this.$store.commit('updateForm', {'form': 'vehicle_inspection_log', 'field': 'log', 'value': this.logs});

                    this.$store.commit('loading', true);
                    this.$store.dispatch('submit', {'form': 'vehicle_inspection_log', 'url': '/api/vehicle_inspection_log', 'reset': false})
                        .then(response => {
                            console.log(response);
                            this.$store.commit('loading', false);
                            this.$store.commit('hideDrawer');
                            this.selected1 = false;
                            this.selected2 = false;

                            this.$store.commit('resetForm', 'vehicle_inspection_log');
                            this.$store.commit('updateForm', {'form': 'vehicle_inspection_log', 'field': 'driver_id', 'value': this.driver_id});
                        });
                }
            },
        }

    }
</script>

<style scoped>
    .inspection-select-option {
        padding-left: 20px;
        display: table;
        height: 50px;
        width: 100vw;
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
    }

    .inspection-select-option-last {
        padding-left: 20px;
        display: table;
        height: 50px;
        width: 100vw;
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
        border-bottom: #DDDDDD 0.8px solid;
    }

    .inspection-select-option-first {
        padding-left: 20px;
        display: table;
        height: 50px;
        width: 100vw;
        background-color: white;
    }

    .checked {
        text-align: right;
        padding-right: 20px;
        color: #727272;
    }

    .text-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .next {
        text-align: left;
        padding: 12px 10px;
        color: #DDDDDD;
        font-size: 26px;
        font-family: 'BenchNine', sans-serif;
    }
</style>
