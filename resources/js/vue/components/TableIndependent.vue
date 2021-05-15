<template>

    <div>

        <pill v-if="(pill)" @select="select = $event" :data="pill.data" :default="pill.default"></pill>

        <div class="table-responsive">
            <table class="table">
                <thead class=" text-primary">
                <tr :class="{'scrollable': rowScrollable}">
                    <th v-for="(column, index) in columns" :key="index" :style="getHeaderStyle(column.data, column.notSortable)"
                        :class="'text-'+column.align" @click="sort(column.data)">
                        {{column.text}}
                    </th>
                </tr>
                </thead>
                <tbody name="list" is="transition-group">
                <tr :class="{ 'scrollable': rowScrollable, 'selectable': allowselect, 'selected': (rowId === datum.id)}"
                    @click="(allowselect)?$store.dispatch('rowSelect', datum.id):false"
                    v-for="(datum, index) in tableData" :key="datum.id" v-if="showRow(datum)" style="font-size:0.875rem;">
                    <td v-for="(column, index) in columns" :key="index" :class="'text-'+column.align">

                        <!--                        string-->

                        <div v-if="(!column.type)">
                            {{datum[column.data]}}
                        </div>

                        <div v-if="(column.type === 'index')">
                            {{no+1}}
                        </div>
                        <!--                        boolean-->

                        <div v-if="(column.type === 'boolean')">
                            <p v-if="datum[column.data]" style="cursor:default;" class="btn btn-success btn-link btn-sm btn-icon ">
                                <i class="tim-icons icon-check-2"></i>
                            </p>
                            <p v-else>-</p>
                        </div>


                        <div v-if="(column.type === 'invert-boolean')">
                            <p v-if="!datum[column.data]" style="cursor:default;" class="btn btn-success btn-link btn-sm btn-icon ">
                                <i class="tim-icons icon-check-2"></i>
                            </p>
                            <p v-else>-</p>
                        </div>

                        <!--                       image -->

                        <div v-if="(column.type === 'image')">
                            <div class="photo">
                                <img v-if="(datum[column.data])" :src="datum[column.data]" alt="Table image">
                                <img v-else src="/black/img/placeholder.jpg" alt="Table image">
                            </div>
                        </div>

                        <!--                       image XL -->

                        <div v-if="(column.type === 'imageXL')">
                            <div>
                                <img class="img-fluid rounded shadow" style="height: 100px;" v-if="(datum[column.data])" :src="datum[column.data]" alt="Table image">
                                <img v-else src="/black/img/placeholder.jpg" alt="Table image">
                            </div>
                        </div>

                        <!--                        badge-->

                        <span v-if="(column.type === 'badge')" v-for="(badge, index) in datum[column.data]" :key="index" href="#"
                              class="badge badge-primary badge-pill">{{ badge }}</span>

                        <!--                        action-->

                        <div v-if="(column.type === 'action')">
                            <a type="button" rel="tooltip" class="btn btn-success btn-link btn-sm btn-icon " data-original-title="Tooltip on top" title="Edit"
                               @click.stop="" :href="'/'+property+'/'+datum.id+'/edit'">
                                <i class="tim-icons icon-pencil"></i>
                            </a>
                            <!--                            <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm " data-original-title="Tooltip on top" title="Delete">-->
                            <!--                                <i class="tim-icons icon-simple-remove"></i>-->
                            <!--                            </button>-->
                        </div>

                        <!--                        custom-->

                        <div v-if="(column.type === 'custom')">
                            <p v-if="!datum[column.if]" type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm btn-icon " data-original-title="Tooltip on top"
                               :title="column.tooltip"
                               @click.stop="customClicked(datum.id)">
                                <i :class="column.icon"></i>
                            </p>
                            <p v-else>-</p>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            property: {required: true},
            params: {required: false},
            pill: {required: false},
            columns: {required: true},
            allowselect: {default: false},
            rowscrollableonselect: {default: false},
        },
        data() {
            return {
                data: {},
                select: null,
                currentRow: null,
                rowScrollable: false,
                showPill: false,

                tableData: null,
                sortSetting: {},
            }
        },
        created() {
            this.getTableData(this.property)
                .then(() => {
                    if (this.pill) {
                        (this.pill.default) ? this.pillSelected(this.pill.default) : false;
                    } else {
                        this.$store.commit('dataCountAll');
                    }
                });
        },
        mounted() {
        },
        computed: {
            ...mapState([
                'theme',
                'rowSelected',
                'rowId',
                'searchBar',

            ])
        },
        watch: {

            select: function (value) {
                this.$store.commit('dataCountSet', this.tableData[this.select].length);
            },
            rowSelected(value) {
                if (this.rowscrollableonselect) {
                    this.rowScrollable = value;
                }
            }
        },

        methods: {
            pillSelected(value) {
                this.select = value;
            },
            showRow(value) {
                if (!this.pill) {
                    return true;
                } else {
                    return (this.select === value[this.pill.property]);
                }

            },
            customClicked(id) {
                this.$emit('customclick', id);
            },
            getHeaderStyle(key, notSortable) {
                if (!notSortable && this.sortSetting) {
                    if (this.sortSetting['key'] === key) {
                        switch (this.sortSetting['order']) {
                            case 'asc':
                                return "cursor: pointer; background-image: url(../img/asc.gif); background-repeat: no-repeat; background-position: center right;"
                            case 'desc':
                                return "cursor: pointer; background-image: url(../img/desc.gif); background-repeat: no-repeat; background-position: center right;"
                        }
                    } else {
                        return "cursor: pointer; background-image: url(../img/bg.gif); background-repeat: no-repeat; background-position: center right;"
                    }
                }
            },
            getTableData(property, param = {}) {

                this.$store.commit("loading", true);
                return new Promise((resolve, reject) => {
                    axios.get('/api/' + property, {
                        params: param,
                    })
                        .then(response => {
                            this.$store.commit("loading", false);
                            console.log(response.data);
                            this.tableData = response.data.data;
                            resolve(response.data.data);
                        })
                        .catch(error => {
                            this.$store.commit("loading", false);
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            sort(key) {
                if (this.sortSetting['key'] !== key) {
                    this.sortSetting['key'] = key;
                    this.sortSetting['order'] = null;
                }
                let result = null;
                switch (this.sortSetting['order']) {
                    case 'asc':
                        result = _.orderBy(this.tableData, key, 'desc');
                        this.sortSetting['order'] = 'desc';
                        break;
                    default:
                        result = _.orderBy(this.tableData, key, 'asc');
                        this.sortSetting['order'] = 'asc';
                        break;
                }
                this.tableData = result;
            }
        },
    };
</script>

<style scoped>
    .list-enter-active {
        transition: all 0.5s;
    }

    .list-leave-active {
        transition: all 0s;
    }

    .list-enter, .list-leave-to {
        opacity: 0;
        transform: translateX(30px);
    }

    .list-move {
        transition: transform 0.5s;
    }

    tr:hover td {
        background: #191f3180;
    }

    @media only screen and (max-width: 576px) {
        td, th {
            white-space: nowrap;
            padding-right: 1.5rem !important;
            padding-left: 1.5rem !important;
        }
    }

    .selectable {
        cursor: pointer;
    }

    .selected {
        background: #191f3180;
    }

    .scrollable td {
        white-space: nowrap;
        padding-right: 1.5rem !important;
        padding-left: 1.5rem !important;
    }

</style>

