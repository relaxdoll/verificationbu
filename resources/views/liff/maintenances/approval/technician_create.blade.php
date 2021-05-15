@extends('layouts.liff', ['activePage' => 'indexMaintenance', 'titlePage' => __('Maintenance')])

@section('style')
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('content')

    <div class="content">

        <liff-header title="Maintenance"></liff-header>

        <div v-if="dataSet.length > 0">
            <liff-maintenance-approval-request-list v-for="data in dataSet" :key="data.id" :title="data.vehicle.license"
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
                <liff-form ref="form" name="maintenance_detail" style="background-color: #f4f5f6">
                    <liff-input-group class="spacing">
                        <liff-input field="start_mileage" icon="icon icon-h-dashboard" iconcolor="#fc3e30" placeholder="เลขไมล์ขณะนี้" type="number"
                                    :vparam="['required']"></liff-input>
                    </liff-input-group>

                    <liff-input-group class="spacing">
                        <liff-input v-for="(title, index) in forms.maintenance_detail.titles" :key="index" icon="icon icon-code" iconcolor="#007aff"
                                    :placeholder="'รายการซ่อมที่ ' + index.substr(5,2)" type="text" field="titles" :subfield="index" :vparam="['required']"></liff-input>
                        <div class="text-center pt-4">
                            <button class="btn btn-danger" @click="deleteList()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button class="btn btn-primary" @click="addNewList()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </liff-input-group>

                    <liff-submit-button @submit="submit()" label="บันทึก"></liff-submit-button>
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
                count: 2,
            },

            created() {
                this.$store.dispatch('liffInit');
                this.$store.commit('setPageName', 'Maintenance Approval');
                this.$store.dispatch('populateForm', {
                    'property': 'maintenance',
                    'form': 'maintenance_approval',
                    'field': {
                        start_mileage: null,
                        id: null,
                    }
                });
                this.$store.dispatch('populateForm', {
                    'property': 'maintenance',
                    'form': 'maintenance_detail',
                    'field': {
                        start_date: null,
                        start_mileage: null,
                        maintenance_approval_id: null,
                        technician_id: null,
                        titles: {title1: null},
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
                    this.getProcessingRequest();
                    this.$store.commit('updateForm', {'form': 'maintenance_detail', 'field': 'technician_id', 'value': this.technician_id});
                },
            },

            methods: {
                addNewList() {
                    let temp = {};
                    temp['title' + this.count] = null;
                    let titles = {...this.forms.maintenance_detail.titles, ...temp}

                    this.count++;

                    this.$store.commit('updateForm', {'form': 'maintenance_detail', 'field': 'titles', 'value': titles});
                },
                deleteList() {
                    let temp = {};
                    let titles = null;

                    if (this.count > 2) {
                        for (let i = 1; i < (this.count - 1); i++) {
                            const key = 'title' + i;
                            temp[key] = this.forms.maintenance_detail.titles[key];
                            titles = {...temp};
                        }
                        this.$store.commit('updateForm', {'form': 'maintenance_detail', 'field': 'titles', 'value': titles})
                        this.count--;
                    }
                },
                formatDate(date) {
                    return moment(date).format('DD-MM-YYYY');
                },
                updateForm(data, action) {
                    if (action === 'Accepted') {
                        this.$store.commit('updateForm', {'form': 'maintenance_detail', 'field': 'maintenance_approval_id', 'value': data.id});
                        this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'id', 'value': data.id});
                        this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'request_mileage', 'value': data.request_mileage});
                        this.$store.commit('showDrawer');
                        this.$store.state.drawer_name = 'Maintenance Detail';
                    }
                },
                getProcessingRequest() {
                    this.dataSet = [];
                    const processing_status_id = 4;
                    return new Promise((resolve, reject) => {
                        axios.get(`api/maintenance_approval/crud/getRequestByStatusId/${processing_status_id}`, {})
                            .then(response => {
                                response.data.data.forEach((datum) => {
                                    const hasDetail = datum.detail.length > 0;
                                    if (!hasDetail) {
                                        this.dataSet.push(datum);
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
                submit() {
                    this.$refs.form.inputs = [];
                    this.$refs.form.getInput(this.$refs.form);

                    this.$store.commit('updateForm', {'form': 'maintenance_detail', 'field': 'start_date', 'value': moment().format('YYYY-MM-DD HH:mm:ss')});
                    this.$store.commit('updateForm', {'form': 'maintenance_approval', 'field': 'start_mileage', 'value': this.forms.maintenance_detail.start_mileage});

                    this.$refs.form.validateForm();
                    if (this.$refs.form.validated) {
                        this.$store.dispatch('update', {'form': 'maintenance_approval', 'url': `/api/maintenance_approval/${this.forms.maintenance_approval.id}`})
                            .then(response => {
                                console.log(response);
                                this.$store.dispatch('submit', {'form': 'maintenance_detail', 'url': '/api/maintenance_detail'})
                                    .then(response => {
                                        console.log(response);
                                        this.$store.commit('loading', false);
                                        this.$store.commit('hideDrawer');
                                        this.getProcessingRequest();
                                        this.resetForm();
                                    });
                            });
                    }
                },
                resetForm() {
                    this.$store.commit('updateForm', {'form': 'maintenance_detail', 'field': 'start_mileage', 'value': null});
                    this.$store.commit('updateForm', {'form': 'maintenance_detail', 'field': 'titles', 'value': {title1: null}});
                },
                close() {
                    liff.closeWindow()
                },
            },
        });
    </script>
@endsection
