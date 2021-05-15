<template>
    <div class="col-md-12">


        <darktable property="setting/payment/type" :columns="tableColumn">

        </darktable>

        <div style="bottom: 45px; right: 24px; position: fixed; ">
            <a class="btn btn-primary btn-round btn-icon" style="width: 56px; height: 56px; font-size: 22px; color: #ffffff;"
               @click="$store.commit('showModal', 'create-payment-type')">
                <i class="tim-icons icon-simple-add"></i>
            </a>
        </div>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        name: 'vehicle-type',
        data() {
            return {
                tableColumn: [
                    // {'text': '#', 'type': 'image', 'data': 'avatar', 'align': 'center'},
                    {'text': 'Name', 'data': 'name'},
                    {'text': 'Monthly', 'type': 'boolean', 'data': 'is_monthly', 'align': 'center'},
                    {'text': 'Annually', 'type': 'boolean', 'data': 'is_annually', 'align': 'center'},
                    {'text': 'Warning (Month)', 'data': 'warning'},
                    // {'text': 'Action', 'type': 'action', 'data': 'phone', 'align': 'right'},
                ],
            }
        },
        watch: {
            rowSelected(value) {
                this.$store.commit('forceNavMini', value);
            },
            refreshType(val) {
                if (val) {
                    this.$children[0].getTableData('setting/payment/type');
                    this.$store.state.refreshType = false;
                }
            }
        },

        created() {

        },

        computed: {
            ...mapState([
                'refreshType',

            ]),
        },

        mounted() {
        },

        methods: {

            refresh() {
            },
        },
    };
</script>
