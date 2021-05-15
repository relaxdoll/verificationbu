@extends('layouts.liff', ['activePage' => 'indexMaintenance', 'titlePage' => __('Maintenance')])

@section('style')
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('content')

    <div class="content">

        <liff-header title="Maintenance"></liff-header>

        <div v-if="dataSet.length > 0">
            <liff-maintenance-approval-request-list v-for="data in dataSet" :key="data.id" :title="data.vehicle.license"
                                                    :subject="'วันที่เข้าซ่อม ' + formatDate(data.schedule_date)" :side_title="formatDate(data.request_date)"
                                                    :content="data.symptom" :action="true">

                <div class="text-center" style="padding-top: 5px;">
                    <button class="btn btn-primary" @click="updateForm(data, 'Accepted')">รับงาน</button>
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
                        start_date: null,
                        status_id: null,
                        technician_id: null,
                        id: null
                    }
                });
            },

            mounted() {
            },

            computed: {
                ...mapState([
                    'forms',
                    'technician_id',
                ]),
            },

            watch: {
                technician_id() {
                    this.getApprovedRequest();
                    this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'technician_id', 'value': this.technician_id});
                },
            },

            methods: {
                formatDate(date) {
                    return moment(date).format('DD-MM-YYYY');
                },
                updateForm(data, action) {
                    if (action === 'Accepted') {
                        const status_id = 4;
                        this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'status_id', 'value': status_id});
                        this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'id', 'value': data.id});
                        this.submit();
                    }
                },
                submit() {
                    this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'start_date', 'value': moment().format('YYYY-MM-DD HH:mm:ss')});

                    this.$store.dispatch('update', {'form': 'maintenance_approval', 'url': `/api/maintenance_approval/${this.forms.maintenance_approval.id}`})
                        .then(response => {
                            this.getApprovedRequest();
                            console.log(response);
                        });
                },
                getApprovedRequest() {
                    const approved_status_id = 2;
                    return new Promise((resolve, reject) => {
                        axios.get(`/api/maintenance_approval/crud/getRequestByStatusId/${approved_status_id}`, {})
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
