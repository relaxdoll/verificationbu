@extends('layouts.app', ['activePage' => 'indexPurchase', 'titlePage' => __('Purchase')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}]" active="Purchase"></breadcrumb>

    </div>

    <card style="padding: 0">

        <cardheader :count="currentProperty" title="Tire">

        </cardheader>

        <cardbody>
            <v-form name="purchase" class="col-12 row">
                <div class="col-4">
                    <base-daterangepicker placeholder="Date Range" field="date" addon-left-icon="eec-icons icon-clock" :vparam="[]"></base-daterangepicker>
                </div>
                <div class="col-2">
                    <base-button style="margin:0 !important;" @click="getPurchaseByDate()" type="primary">
                        Search
                    </base-button>
                </div>
            </v-form>

            <darkgrouptable style="padding-top: 10px;" blank property="purchase" :columns="tableColumn" :allowselect="true">

            </darkgrouptable>
        </cardbody>

        <modal>
            <tabs v-if="modalName === 'purchase-tire'" style="width: 80%">
                <tab name="Total" :selected="true">
                    <panels>
                        <panel :header="name" v-for="(value,name,index) in dataSet" :key="index">
                            <p v-if="name === 'Available'"> จำนวนคงเหลือ : @{{ value.count }} เส้น, @{{ value.price }} บาท</p>
                            <p v-else-if="name === 'Wating for Sale'"> จำนวนยางที่รอขาย : @{{ value.count }} เส้น, @{{ value.price }} บาท</p>
                            <p v-else-if="name === 'Sold'"> จำนวนยางที่ขายไปแล้ว : @{{ value.count }} เส้น, @{{ value.price }} บาท</p>
                            <p v-else> จำนวนยางที่ใช้ไป : @{{ value.count }} เส้น, @{{ value.price }} บาท</p>
                        </panel>
                    </panels>
                </tab>
                <tab name="Detail">
                    <darkgrouptable property="tireDetail" blank :columns="tireDetailTableColumn">

                    </darkgrouptable>
                </tab>
            </tabs>

            <inventory-tire-create v-if="modalName === 'create-tire'"></inventory-tire-create>

        </modal>

        <div style="bottom: 45px; right: 24px; position: fixed; ">
            <a class="btn btn-primary btn-round btn-icon" style="width: 56px; height: 56px; font-size: 22px; color: #ffffff;"
               @click="$store.commit('showModal', 'create-tire')">
                <i class="tim-icons icon-simple-add"></i>
            </a>
        </div>
    </card>

@endsection

@push('js')
    <script src=" {{ mix('/js/vue/maintenance_inventories.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                dataSet: null,
                selectedData: null,
                tableColumn: [
                    {'text': '#', 'type': 'index'},
                    {'text': 'PO No.', 'data': 'purchase'},
                    {'text': 'Vendor', 'data': 'vendor'},
                    {'text': 'Fleet', 'data': 'fleet'},
                    {'text': 'Brand', 'data': 'brand'},
                    {'text': 'Date Created', 'data': 'date'},
                    {'text': 'Price', 'data': 'price'},
                    {'text': 'Type', 'data': 'type'},
                    {'text': 'Size', 'data': 'size'},
                    {'text': 'Amount', 'data': 'amount', 'align': 'center'},
                    {'text': 'User Created', 'data': 'user'},
                ],
                tireDetailTableColumn: [
                    {'text': '#', 'type': 'index'},
                    {'text': 'Size', 'data': 'size'},
                    {'text': 'Type', 'data': 'type'},
                    {'text': 'Serial', 'data': 'serial'},
                    {'text': 'Price', 'data': 'price'},
                    {'text': 'License', 'data': 'license'},
                    {'text': 'Placement', 'data': 'placement'},
                    {'text': 'Start Date', 'data': 'start_date'},
                    {'text': 'Start Mileage', 'data': 'start_mileage', 'type': 'sortNumber'},
                ],
                purchase_id: null,
                show_panel: null,
                currentProperty: null,
            },

            watch: {
                propertyType(val) {
                    this.dataSet = {};
                },
                rowSelected(value) {
                    if (value) {
                        this.$store.commit('showModal', 'purchase-tire');
                    }
                },
                showModal(value) {
                    if (!value) {
                        this.$store.commit('rowDeSelected');
                    }
                },
                datum(value) {
                    this.getTireByPurchaseId();
                    this.getTotalUsedTireByPurchaseId();
                },
            },

            created() {
                this.$store.commit('setModel', 'purchase');
                this.$store.dispatch('populateForm', {
                    'property': 'purchase',
                    'form': 'purchase',
                    'field': {
                        date: null,
                    }
                });
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

            mounted() {
            },

            methods: {
                getPurchaseByDate() {
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/purchase/crud/purchaseByDate`, {
                            params: {from: this.forms.purchase.date[0], to: this.forms.purchase.date[1]}
                        })
                            .then(response => {
                                this.$store.commit('loading', false);
                                this.$store.commit("addTable", 'purchase');
                                this.$store.commit("addTableData", {'table_name': 'purchase', 'table_data': response.data.data});
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                getTireByPurchaseId() {
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/purchase/crud/tiresByPurchaseId/${this.datum.id}`, {})
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
                getTotalUsedTireByPurchaseId() {
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/purchase/crud/totalUsedTiresByPurchaseId/${this.datum.id}`, {})
                            .then(response => {
                                this.$store.commit('loading', false);
                                this.dataSet = response.data.data
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
            },
        });
    </script>

    <style scoped>

    </style>
@endpush
