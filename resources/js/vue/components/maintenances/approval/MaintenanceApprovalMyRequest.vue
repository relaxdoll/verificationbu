<template>
    <div v-if="mode === 'รายการซ่อมของฉัน'">
        <div v-if="dataSet.length > 0">
            <liff-maintenance-approval-request-list v-for="data in dataSet" :key="data.id" :title="data.vehicle.license" :header="'เลขที่แจ้งซ่อม: ' + data.id"
                                                    :side_title="formatDate(data.request_date)" :subject="data.status.nameTH" :content="data.symptom"/>
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
</template>

<script>
    export default {
        name: "MaintenanceApprovalMyRequest",
        store,

        data() {
            return {
                dataSet: [],
            }
        },

        created() {
        },

        computed: {
            ...mapState([
                'mode',
                'driver_id',
            ]),
        },

        watch: {
            driver_id() {
                this.getRequestByDriver();
            },
            mode() {
                this.getRequestByDriver();
            },
        },

        methods: {
            formatDate(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            getRequestByDriver() {
                return new Promise((resolve, reject) => {
                    axios.get(`api/maintenance_approval/crud/getRequestByDriver/${this.driver_id}`, {})
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
        },
    }
</script>

