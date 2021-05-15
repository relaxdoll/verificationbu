@extends('layouts.app', ['activePage' => 'indexTire', 'titlePage' => __('Tire')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Maintenance','href':'#'}]" active="Tire"></breadcrumb>

        <pill @select="pillSelected($event)" class="col-12" :data="reasonData" :default="0" color="info" style="padding-bottom: 10px;"></pill>

        <card>

            <cardheader count="tire" title="Tire Lists"></cardheader>

            <cardbody>

                <darkgrouptable blank property="tire" :columns="tableColumn">

                </darkgrouptable>

            </cardbody>
        </card>
    </div>

    <modal>

        <div class="card" style="padding: 30px; width: auto; background-color: #1e1e2f;">
            <v-form name="find_tire" class="row">
                <div class="col-4">
                    <base-input placeholder="Serial" field="serial" addon-left-icon="tim-icons icon-zoom-split" :vparam="[]"></base-input>
                </div>
                <div class="col-2">
                    <base-button style="margin:0 !important;" @click="findTire()" type="primary">
                        Search
                    </base-button>
                </div>
            </v-form>

            <darkgrouptable @customclick="edit($event)" property="tireDetail" blank :columns="tireDetailTableColumn">

            </darkgrouptable>

        </div>

    </modal>

    <div style="bottom: 45px; right: 24px; position: fixed; ">
        <a class="btn btn-primary btn-round btn-icon" style="width: 56px; height: 56px; font-size: 22px; color: #ffffff;"
           @click="$store.commit('showModal', 'find-tire')">
            <i class="tim-icons icon-zoom-split"></i>
        </a>
    </div>

@endsection

@push('js')
    <script src=" {{ mix('/js/vue/maintenance_inventories.js') }}"></script>


    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                reasonData: [
                    {'text': 'Available', 'value': 0},
                    {'text': 'ยางระเบิด', 'value': 1},
                    {'text': 'ยางบวม', 'value': 2},
                    {'text': 'ยางรั่ว', 'value': 3},
                    {'text': 'หน้ายางฉีก', 'value': 4},
                    {'text': 'หมดดอก', 'value': 5}
                ],
                searchfield: null,
                data: null,
                tableColumn: [
                    {'text': 'Serial', 'data': 'serial'},
                    {'text': 'Tread Depth', 'data': 'tread_depth'},
                    {'text': 'Brand', 'data': 'brand'},
                    {'text': 'Action', 'type': 'action', 'data': 'phone', 'align': 'right'},
                ],

                tireDetailTableColumn: [
                    {'text': 'Purchase', 'data': 'purchase'},
                    {'text': 'Serial', 'data': 'serial'},
                    {'text': 'Type', 'data': 'type'},
                    {'text': 'Size', 'data': 'size'},
                    {'text': 'Brand', 'data': 'brand'},
                    {'text': 'price', 'data': 'price'},
                    {'text': 'Insert Date', 'data': 'insert_date'},
                    {'text': 'License', 'data': 'license'},
                    {'text': 'Placement', 'data': 'placement'},
                    {'text': 'Tread Depth', 'data': 'tread_depth'},
                    {'text': 'Action', 'type': 'custom', 'icon': 'tim-icons icon-pencil', 'align': 'center', 'tooltip': 'Action'},
                ]
            },

            watch: {
                searchfield: function (newVal, oldVal) {
                    this.drivers.filter(newVal);
                }
            },


            computed: {
                ...mapState([
                    'showModal',
                    'tables',
                    'forms',
                    'rowSelected',
                    'datum',
                    'modalName',
                ]),
            },

            created() {
                this.$store.commit('setModel', 'Tire');
                this.$store.dispatch('populateForm', {
                    'property': 'tire',
                    'form': 'find_tire',
                    'field': {
                        serial: null,
                    }
                });

                this.getAllTiresByReason();
            },

            mounted() {
            },

            methods: {
                findTire() {
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/tire/crud/findWhereTireRegister/${this.forms.find_tire.serial}`, {})
                            .then(response => {
                                this.$store.commit('loading', false);
                                this.$store.commit("addTable", 'tireDetail');
                                this.$store.commit("addTableData", {'table_name': 'tireDetail', 'table_data': response.data.data});
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                getAllTiresByReason() {
                    return new Promise((resolve, reject) => {
                        axios.get('/api/tire/crud/indexByReason', {})
                            .then(response => {
                                this.data = response.data.data;
                                this.$store.commit("addTable", 'tire');
                                this.$store.commit("addTableData", {'table_name': 'tire', 'table_data': this.data[0]});
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                refresh() {
                    this.$store.dispatch('getTableData', 'customer');
                },

                deleteData(id) {
                    if (confirm('{{ __("Are you sure you want to delete this driver?") }}')) {
                        return new Promise((resolve, reject) => {
                            axios.delete('/api/driver/' + id)
                                .then(response => {
                                    notify('Driver deleted successfully', 'warning');
                                    this.drivers.get();
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
                pillSelected(reason_id) {
                    this.$store.commit("addTable", 'tire');
                    this.$store.commit("addTableData", {'table_name': 'tire', 'table_data': this.data[reason_id]});
                },
                edit(id) {
                    window.location.replace(`/tire/${id}/edit`);
                }
            },
        });
    </script>
@endpush
