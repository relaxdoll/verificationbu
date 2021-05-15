@extends('layouts.liff', ['activePage' => 'indexMaintenance', 'titlePage' => __('Maintenance')])

@section('style')
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('content')

    <div class="content">

        <liff-header title="Maintenance"></liff-header>

        <div v-if="maintenance_approvals.length > 0">
            <liff-maintenance-approval-request-list v-for="data in maintenance_approvals" :key="data.id" :title="data.vehicle.license"
                                                    :side_title="formatDate(data.request_date)" :content="data.symptom" :action="true">
                <div class="text-center" style="padding-top: 5px;">
                    <button class="btn btn-primary" @click="updateForm(data, 'Accepted')">เพิ่มรายละเอียด</button>
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
                <liff-form ref="form" name="maintenance_approval" style="background-color: #f4f5f6">
                    <liff-input-group class="spacing">
                        <liff-input field="end_mileage" icon="icon icon-h-dashboard" iconcolor="#fc3e30" placeholder="เลขไมล์ขณะนี้" type="number"
                                    :vparam="['required']"></liff-input>
                    </liff-input-group>

                    <liff-input-group class="spacing">
                        <liff-maintenance-approval-update-list v-for="(detail, index) in maintenance_details" :key="index" icon="icon icon-code" iconcolor="#007aff"
                                                               :label="detail.name" :detail="detail" :disable="detail.is_update"
                                                               @update-maintenance-detail="getMaintenanceDetailByApprovalId()"></liff-maintenance-approval-update-list>
                    </liff-input-group>

                    <liff-submit-button @submit="submit()" label="เสร็จสิ้น"></liff-submit-button>
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
                maintenance_approvals: [],
                maintenance_details: null,
                maintenance_approval_id: null,
            },

            created() {
                this.$store.dispatch('liffInit');
                this.$store.commit('setPageName', 'Maintenance Approval');
                this.$store.dispatch('populateForm', {
                    'property': 'maintenance',
                    'form': 'maintenance_approval',
                    'field': {
                        end_date: null,
                        end_mileage: null,
                    }
                });
            },

            mounted() {
            },

            computed: {
                ...mapState([
                    'forms',
                    'technician_id',
                    'show_drawer',
                ]),
                isAllMaintenanceDetailUpdated: function () {
                    let updated = false;
                    this.maintenance_details.every((detail) => {
                        detail.is_update === 1 ? updated = true : updated = false;
                    });
                    return updated;
                }
            },

            watch: {
                technician_id() {
                    this.getProcessingRequest();
                },

                show_drawer(val) {
                    if (val === false) {
                        this.$store.commit('resetForm', 'maintenance_approval');
                    }
                },
            },

            methods: {
                setInventory(event) {
                    console.log(event);
                },
                formatDate(date) {
                    return moment(date).format('DD-MM-YYYY');
                },
                updateForm(data, action) {
                    if (action === 'Accepted') {
                        this.maintenance_approval_id = data.id;
                        this.$store.commit('showDrawer');
                        this.$store.state.drawer_name = 'Maintenance Detail';
                        this.getMaintenanceDetailByApprovalId();
                        this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'start_mileage', 'value': data.start_mileage});
                    }
                },
                getMaintenanceDetailByApprovalId() {
                    return new Promise((resolve, reject) => {
                        axios.get(`api/maintenance_approval/crud/getDetailByApprovalId/${this.maintenance_approval_id}`, {})
                            .then(response => {
                                this.maintenance_details = response.data.data.details;
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                getApprovalThatHasDetail(approvals) {
                    this.maintenance_approvals = [];
                    approvals.forEach((approval) => {
                        if (approval.detail.length > 0) {
                            this.maintenance_approvals.push(approval);
                        }
                    });
                },
                getProcessingRequest() {
                    const processing_status_id = 4;
                    return new Promise((resolve, reject) => {
                        axios.get(`api/maintenance_approval/crud/getRequestByStatusId/${processing_status_id}`, {})
                            .then(response => {
                                this.getApprovalThatHasDetail(response.data.data);
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });
                },
                submit() {
                    this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'end_date', 'value': moment().format('YYYY-MM-DD HH:mm:ss')});
                    this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'status_id', 'value': 5});

                    this.$refs.form.validateForm();
                    if (this.$refs.form.validated && this.isAllMaintenanceDetailUpdated) {
                        this.$store.dispatch('update', {'form': 'maintenance_approval', 'url': `/api/maintenance_approval/${this.maintenance_approval_id}`})
                            .then(response => {
                                console.log(response);
                                this.getProcessingRequest();
                                this.$store.commit('loading', false);
                                this.$store.commit('hideDrawer');
                            });
                    }
                },
                close() {
                    liff.closeWindow()
                },
            },
        });
    </script>
@endsection
