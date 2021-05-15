@extends('layouts.liff', ['activePage' => 'indexMaintenance', 'titlePage' => __('Maintenance')])

@section('style')
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('content')

    <div class="content">

        <liff-header title="Maintenance"></liff-header>

        <div v-if="dataSet.length > 0">
            <liff-maintenance-approval-request-list v-for="data in dataSet" :key="data.id" :title="data.vehicle.license"
                                                    :side_title="formatDate(data.request_date)" :subject="data.status.nameTH"
                                                    :content="data.symptom" :action="true">

                <div class="text-center" style="padding-top: 5px;">
                    <button class="btn btn-danger" @click="updateForm(data.id, 'Rejected')">ปฏิเสธ</button>
                    <button class="btn btn-primary" @click="updateForm(data.id, 'Approved')">อนุมัติ</button>
                </div>

            </liff-maintenance-approval-request-list>
        </div>

        <div v-else>
            <div style="padding: 20px; color: #737373">
                <div class="row">
                    <div class="col-12 text-center">
                        ยังไม่มีข้อมูล
                    </div>
                </div>
            </div>
        </div>

        <liffdrawer2 @close="$store.commit('hideDrawer')" align="right" :maskclosable="true" :label="$store.state.drawer_name" :currentview="$store.page_name"
                     :closeable="true">
            <div v-if="$store.state.show_drawer">
                <liff-form ref="form" name="maintenance_approval" style="background-color:  #f4f5f6">
                    <liff-input-group class="spacing">
                        <liff-datepicker icon="icon icon-calendar" iconcolor="#fc3e30" field="schedule_date" label="วันที่นัดหมายเข้าซ่อม"
                                         :vparam="['required']"></liff-datepicker>
                    </liff-input-group>
                    <div class="text-center" style="padding: 12px 15px">
                        <button class="btn btn-danger" @click.self="$store.commit('hideDrawer')">ยกเลิก</button>
                        <button class="btn btn-primary" @click.self="submit()" :disabled="!forms.maintenance_approval.schedule_date">ยืนยัน</button>
                    </div>
                </liff-form>
            </div>
        </liffdrawer2>
    </div>

@endsection

@section('js')
    <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
    <script src=" {{ mix('/js/vue/liff_example.js') }}"></script>

    <script>
        new Vue({
            el: '#asset',

            store,

            data: {
                dataSet: [],
                isAllowed: false,
            },

            created() {
                this.$store.dispatch('liffInit');
                this.$store.commit('setPageName', 'Maintenance Approval');
                this.$store.dispatch('populateForm', {
                    'property': 'maintenance',
                    'form': 'maintenance_approval',
                    'field': {
                        approve_date: null,
                        approver_id: null,
                        status_id: null,
                        schedule_date: null,
                        id: null,
                    }
                });
            },

            mounted() {
            },

            computed: {
                ...mapState([
                    'forms',
                    'driver_id',
                    'approver_id',
                    // 'technician_id',
                ]),
            },

            watch: {
                approver_id() {
                    this.getUnapprovedJobByFleet();
                    this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'approver_id', 'value': this.approver_id});
                },
            },

            methods: {
                formatDate(date) {
                    return moment(date).format('DD-MM-YYYY');
                },
                updateForm(id, action) {
                    this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'id', 'value': id});
                    if (action === 'Approved') {
                        const status_id = 2;
                        this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'status_id', 'value': status_id});
                        this.$store.commit('showDrawer');
                        this.$store.state.drawer_name = 'Maintenance Approval';
                    } else {
                        const status_id = 3;
                        this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'approve_date', 'value': moment().format('YYYY-MM-DD HH:mm:ss')});
                        this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'status_id', 'value': status_id});
                        this.$store.dispatch('update', {'form': 'maintenance_approval', 'url': `/api/maintenance_approval/${id}`})
                            .then(response => {
                                this.getUnapprovedJobByFleet();
                                console.log(response);
                            });
                    }
                },
                submit() {
                    this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'approve_date', 'value': moment().format('YYYY-MM-DD HH:mm:ss')});

                    this.$store.dispatch('update', {'form': 'maintenance_approval', 'url': `/api/maintenance_approval/${this.forms.maintenance_approval.id}`})
                        .then(response => {
                            this.$store.commit('hideDrawer');
                            this.getUnapprovedJobByFleet();
                            this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'schedule_date', 'value': null});
                            console.log(response);
                        });
                },
                getUnapprovedJobByFleet() {
                    return new Promise((resolve, reject) => {
                        axios.get(`api/maintenance_approval/crud/getUnapprovedJobByFleet/${this.approver_id}`, {})
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
                close() {
                    liff.closeWindow()
                },
            },
        });
    </script>
@endsection
