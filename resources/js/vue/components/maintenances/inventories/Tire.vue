<template>

    <card>

        <cardheader count="tire" title="Tire in Stock">

        </cardheader>

        <cardbody>
            <darkgrouptable property="tire" url="tire/crud/indexAvailable" :columns="tableColumn">

            </darkgrouptable>

            <div style="bottom: 45px; right: 24px; position: fixed; ">
                <a class="btn btn-primary btn-round btn-icon" style="width: 56px; height: 56px; font-size: 22px; color: #ffffff;"
                   @click="$store.commit('showModal', 'create-vehicle-type')">
                    <i class="tim-icons icon-simple-add"></i>
                </a>
            </div>
        </cardbody>
    </card>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        name: 'vehicle-type',
        data() {
            return {
                tableColumn: [
                    // {'text': '#', 'type': 'image', 'data': 'avatar', 'align': 'center'},
                    {'text': 'Serial', 'data': 'serial'},
                    {'text': 'Tread Depth', 'data': 'tread_depth'},
                    {'text': 'Brand', 'data': 'brand'},
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
                    this.$children[0].getTableData('setting/vehicle/type');
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
