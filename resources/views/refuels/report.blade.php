@extends('layouts.app', ['activePage' => 'reportRefuel', 'titlePage' => __('Refuel')])
@section('head_script')
    <script src=" {{ mix('/js/before.js') }}"></script>
@endsection
@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Refuel', 'href':'#'}]" active="Report"></breadcrumb>

    </div>

    <tabs title="Title" v-if="fleetData.length > 0">
        <tab :name="fleet.text" v-for="fleet in fleetData" :key="fleet.value">
            <cardheader :title="byType.toUpperCase()" count="">

                <gear>
                    <a class="dropdown-item" v-for="(byTypeData,byTypeIndex) in ['byLicense', 'byDriver']" :key="byTypeIndex"
                       @click="insertDataSetOption('byType', byTypeData)" style="cursor: pointer;"> @{{ byTypeData }}</a>
                </gear>


            </cardheader>

            <div style="overflow-x: auto; padding-left: 10px;">
                <ul class="nav nav-pills nav-pills-primary">
                    <li v-for="(vehicleType,vehicleTypeIndex) in vehicleTypes" :key="vehicleTypeIndex" class="nav-item" ref="lists">
                        <a style="cursor: pointer" :class="(vehicle_type_id === vehicleType.id )? 'active nav-link' : 'nav-link'"
                           @click="insertDataSetOption('vehicle_type_id', vehicleType.id)">
                            @{{ vehicleType.name }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="text-center" style="cursor: pointer; padding: 25px 0 15px 0;">
                <a @click="insertDataSetOption('byDataType','overall')" :class="{'type-active': byDataType === 'overall'}" style="padding-left: 10px;">Overall</a>
                <span v-for="(zone, zoneIndex) in zones" @click="insertDataSetOption('byDataType', zone)" style="padding-left: 10px;">
                    <a v-if="zoneIndex === (zones.length - 1)" :class="{'type-active': byDataType === zone}">More than @{{ zones[zoneIndex - 1] }} km.</a>
                    <a v-else-if="zoneIndex === 0" :class="{'type-active': byDataType === zone}">0 - @{{ zone }} km.</a>
                    <a v-else :class="{'type-active': byDataType === zone}">@{{ +zones[zoneIndex - 1] + 1 }} - @{{ zone }} km.</a>
                </span>
            </div>
            <div class="text-center dataTable">
                <div class="row dataHeader">
                    <div class="col-1-7">@{{byType.substring(2,20)}}</div>
                    <div class="col-1-7">Number of trips</div>
                    <div class="col-1-7">Max</div>
                    <div class="col-1-7">Min</div>
                    <div class="col-1-7 dataTooltip"
                         title="Mean(ค่าเฉลี่ย) คือ ค่ากลางหรือตัวแทนของข้อมูลที่ได้จากผลหารระหว่างผลรวมของข้อมูลทั้งหมดกับจำนวนของข้อมูลทั้งหมด">Mean
                    </div>
                    <div class="col-1-7 dataTooltip"
                         title="Median(มัธยฐาน) คือ ค่าที่อยู่ตรงกลางของชุดข้อมูลหลังจากทำการเรียงลำดับแล้ว สามารถเรียงลำดับได้ทั้งน้อยไปหามาก และมากมาหาน้อย">Median
                    </div>
                    <div class="col-1-7 dataTooltip" title="Mode(ฐานนิยม)  หมายถึง ค่าของข้อมูลในชุดใดชุดหนึ่ง ที่ซ้ำกันมากที่สุด">Mode</div>
                </div>
                <div v-for="(data,dataName, dataIndex) in dataSet" v-if="byDataType === 'overall'">
                    <div v-if="data.max && data.fleet_id === fleet.value" class="row dataBody" @click="showDetails(dataIndex)" style="cursor: pointer">
                        <div class="col-1-7">@{{ dataName }}</div>
                        <div class="col-1-7">@{{ data.data_set.length - 1 }}</div>
                        <div class="col-1-7">@{{ onlyTwoDecimal(+data.max) }}</div>
                        <div class="col-1-7">@{{ onlyTwoDecimal(+data.min) }}</div>
                        <div class="col-1-7">@{{ onlyTwoDecimal(+data.mean) }}</div>
                        <div class="col-1-7">@{{ onlyTwoDecimal(+data.median) }}</div>
                        <div class="col-1-7">@{{ onlyTwoDecimal(+data.mode) }}</div>
                    </div>
                </div>
                <div v-for="(data,dataName, dataIndex) in zoneDataSet" v-if="byDataType !== 'overall'">
                    <div v-if="data.max && data.fleet_id === fleet.value" class="row dataBody">
                        <div class="col-1-7">@{{ dataName }}</div>
                        <div class="col-1-7">@{{ data.data_set.length - 1 }}</div>
                        <div class="col-1-7">@{{ onlyTwoDecimal(+data.max) }}</div>
                        <div class="col-1-7">@{{ onlyTwoDecimal(+data.min) }}</div>
                        <div class="col-1-7">@{{ onlyTwoDecimal(+data.mean) }}</div>
                        <div class="col-1-7">@{{ onlyTwoDecimal(+data.median) }}</div>
                        <div class="col-1-7">@{{ onlyTwoDecimal(+data.mode) }}</div>
                    </div>
                </div>
            </div>
        </tab>
    </tabs>

    <modal>
        <div class="adjustSize">
            <card v-if="selectedData">
                <cardheader :title="selectedData.name.toUpperCase()" class="text-center"></cardheader>
                <cardbody>
                    <div class="text-center dataTable">
                        <div class="row dataHeader">
                            <div class="col-1-7">Zones</div>
                            <div class="col-1-7">Number of trips</div>
                            <div class="col-1-7">Max</div>
                            <div class="col-1-7">Min</div>
                            <div class="col-1-7">Mean</div>
                            <div class="col-1-7">Median</div>
                            <div class="col-1-7">Mode</div>
                        </div>
                        <div v-for="(data,dataName, dataIndex) in selectedData.data.zone">
                            <div v-if="data.max" class="row dataBody">
                                <div class="col-1-7"> @{{ zoneDisplaying(dataName) }}</div>
                                <div class="col-1-7">@{{ data.data_set.length - 1 }}</div>
                                <div class="col-1-7">@{{ onlyTwoDecimal(+data.max) }}</div>
                                <div class="col-1-7">@{{ onlyTwoDecimal(+data.min) }}</div>
                                <div class="col-1-7">@{{ onlyTwoDecimal(+data.mean) }}</div>
                                <div class="col-1-7">@{{ onlyTwoDecimal(+data.median) }}</div>
                                <div class="col-1-7">@{{ onlyTwoDecimal(+data.mode) }}</div>
                            </div>
                        </div>
                    </div>
                </cardbody>
            </card>
        </div>
    </modal>

    {{--    <div id="chart_div" style="width: 900px; height: 500px;"></div>--}}




@endsection

@push('js')
    <script src=" {{ mix('/js/vue/index.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                dataSet: null,
                zoneDataSet: {},
                graphData: null,
                vehicleTypes: [],
                zones: [],

                // DataSet Option
                vehicle_type_id: 1,
                byType: 'byLicense',
                byDataType: null,
                detail: null,

                select: null,
                count: null,
                selectedData: null,
                fleetData: [],
            },

            watch: {
                byDataType(val) {
                    // if (this.graphData) {
                    //     if (val === 'overall') {
                    //         this.plotGraph(this.graphData.data_set)
                    //     } else {
                    //         for (const [key, value] of Object.entries(this.graphData.zone)) {
                    //             if (key === val && value.data_set.length > 1) {
                    //                 this.plotGraph(value.data_set)
                    //             }
                    //         }
                    //     }
                    // }
                    this.zoneDataSet = {};
                    if (val !== 'overall') {
                        for (const [dataSetKey, dataSetValue] of Object.entries(this.dataSet)) {
                            for (const [zoneKey, zoneValue] of Object.entries(dataSetValue.zone)) {
                                const fleet_id = dataSetValue.fleet_id;
                                if (zoneKey === val) {
                                    zoneValue['fleet_id'] = fleet_id;
                                    this.zoneDataSet[dataSetKey] = zoneValue;
                                }
                            }
                        }
                    }
                },
                byType(val) {
                    this.getRefuelReport(val, this.vehicle_type_id);
                },
                detail(val) {
                    // for (const [key, value] of Object.entries(this.dataSet)) {
                    //     (key === val) ? this.graphData = value : null;
                    // }
                    // (this.byDataType === 'overall') ? this.plotGraph(this.graphData.data_set) : this.byDataType = 'overall';
                },
                vehicle_type_id(val) {
                    this.getRefuelReport(this.byType, val);
                },
            },

            created() {
                this.getFleet();
                this.getVehicleType();
                this.getRefuelReport();
            },

            computed: {
                ...mapState([
                    'showModal',
                ]),
            },

            mounted() {
            },

            methods: {
                zoneDisplaying(endZone) {
                    let displayZone;
                    this.zones.forEach((zone, zoneIndex) => {
                        if (endZone === zone) {
                            if (zoneIndex === (this.zones.length - 1)) {
                                displayZone = endZone;
                            } else if (zoneIndex === 0) {
                                displayZone = `0 - ${endZone}`;
                            } else {
                                displayZone = `${+this.zones[zoneIndex - 1] + 1} - ${endZone}`;
                            }
                        }
                    });
                    return displayZone;
                },
                showDetails(dataIndex) {
                    this.$store.commit('showModal', 'vehicle-consumption-rate');

                    const showData = Object.entries(this.dataSet)[dataIndex];
                    this.selectedData = {};
                    this.selectedData.name = showData[0];
                    this.selectedData.data = showData[1];
                },
                pillSelected(value) {
                    this.select = value;
                },
                onlyTwoDecimal(num) {
                    return num === null ? 0 : (Math.round(num * 100) / 100).toFixed(2);
                },
                assignDefaultDataOption(data) {
                    this.detail = Object.keys(data)[0];
                    this.byDataType = 'overall';
                },
                plotGraph(newVar) {
                    if (newVar.length > 1) {
                        google.charts.load('current', {'packages': ['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            const data = google.visualization.arrayToDataTable(newVar);

                            const options = {
                                legend: 'none',
                                hAxis: {ticks: []},
                                vAxis: {ticks: []},
                                pointShape: 'diamond',
                                trendlines: {
                                    0: {
                                        type: 'linear',
                                        visibleInLegend: true,
                                        pointSize: 1,
                                    }
                                }
                            };

                            const chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
                            chart.draw(data, options);
                        }
                    }
                },
                getRefuelReport(byType = 'byLicense', vehicle_type_id = 1) {
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/refuel/crud/zoning`, {
                            params: {vehicle_type_id, byType}
                        })
                            .then(response => {
                                this.$store.commit('loading', false);
                                this.assignDefaultDataOption(response.data.data);
                                this.dataSet = response.data.data;
                                this.getEachZones(response.data.data);
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                insertDataSetOption(option, data) {
                    switch (option) {
                        case 'vehicle_type_id':
                            this.vehicle_type_id = data;
                            break;
                        case 'byType':
                            this.byType = data;
                            break;
                        case 'byDataType':
                            this.byDataType = data;
                            break;
                        case 'detail':
                            this.detail = data;
                            break;
                        default:
                            break;
                    }
                },
                getFleet() {
                    return new Promise((resolve, reject) => {
                        axios.get('/api/fleet', {})
                            .then(response => {
                                response.data.data.forEach((fleet) => {
                                    this.fleetData.push({text: fleet.name, value: fleet.id});
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
                getVehicleType() {
                    return new Promise((resolve, reject) => {
                        axios.get('/api/setting/vehicle/type', {})
                            .then(response => {
                                response.data.data.forEach((item) => {
                                    if (item.is_head === 1 || (item.is_independent && item.name !== 'กระบะ')) {
                                        this.vehicleTypes.push(item);
                                    }
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
                getEachZones(data) {
                    Object.entries(data).forEach((datum, datumIndex) => {
                        if (datumIndex === 0) {
                            const zoneObject = Object.values(datum)[1].zone;
                            this.zones = Object.keys(zoneObject);
                        }
                    });
                },
            },
        });
    </script>

    <style scoped>
        .type-active {
            color: white !important;
            font-weight: 700;
            text-decoration: underline !important;
            text-decoration-color: white !important;
        }

        .dataTable {
            background-color: #282a3d;
            padding: 0 15px;
            color: white;
            font-size: 14px;
        }

        .dataHeader {
            text-transform: uppercase;
            font-weight: 700;
            border-bottom: 1px solid #ffffff10;
            padding: 10px 0;
        }

        .dataBody {
            border-bottom: 1px solid #ffffff10;
            padding: 10px 0;
        }

        .col-1-7 {
            -webkit-box-flex: 0;
            flex: 0 0 14.285714285714285714285714285714%;
            max-width: 14.285714285714285714285714285714%;
        }

        .adjustSize {
            width: 80vw;
        }

        .dataTooltip {
            cursor: context-menu;
        }

    </style>
@endpush
