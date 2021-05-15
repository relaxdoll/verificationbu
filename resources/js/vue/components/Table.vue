<template>

    <div>

        <pill v-if="(pill)" @select="select = $event" :data="pill.data" :default="pill.default"></pill>

        <div class="table-responsive">
            <table class="table">
                <thead class=" text-primary">
                <tr :class="{'scrollable': rowScrollable}">
                    <th v-for="(column, index) in columns" :key="index"  :style="getHeaderStyle(column.data, column.notSortable)"
                        :class="'text-'+column.align" @click="sort(column.data)">
                        {{column.text}}
                    </th>
                </tr>
                </thead>
                <tbody name="list" is="transition-group">
                <tr :class="{ 'scrollable': rowScrollable, 'selectable': allowselect, 'selected': (rowId === datum.id)}"
                    @click="(allowselect)?$store.dispatch('rowSelect', datum.id):false"
                    v-for="(datum, index) in tables[property]" :key="datum.id" v-if="showRow(datum)" style="font-size:0.875rem;">
                    <td v-for="(column, index) in columns" :key="index" :class="'text-'+column.align">

                        <!--                        string-->

                        <div v-if="(!column.type)">
                            {{datum[column.data]}}
                        </div>

                        <!--                        number-->

                        <div v-if="(column.type === 'number')">
                            {{parseInt( datum[column.data] ).toLocaleString()}}
                        </div>

                        <div v-if="(column.type === 'sortNumber')">
                            {{ datum[column.data] }}
                        </div>

                        <!--                        boolean-->

                        <div v-if="(column.type === 'boolean')">
                            <p v-if="datum[column.data]" style="cursor:default;" class="btn btn-success btn-link btn-sm btn-icon ">
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
                               @click.stop="customClicked(datum)">
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
            filterable:{default: true},
            url: {required: false},
        },
        data() {
            return {
                data: {},
                select: null,
                currentRow: null,
                rowScrollable: false,
                tableData: {},
            }
        },
        created() {
            this.$store.dispatch('getTableData', {'property': this.property, 'url': this.url})
                .then(() => {
                    if (this.pill) {
                        (this.pill.default) ? this.pillSelected(this.pill.default) : false;
                    } else {
                        this.$store.commit('dataCountSet', {'property': this.property, 'count': this.tables[this.property].length});
                    }
                });

        },
        computed: {
            ...mapState([
                'theme',
                'tables',
                'rowSelected',
                'rowId',
                'searchBar',
                'sortSetting'

            ])
        },
        watch: {
            tables(value) {
                this.tableData = value[this.property];
                if (this.tableData) {
                    this.$store.dispatch('initializeSort', {'property': this.property});
                }
            },
            select: function (value) {
                this.$store.commit('dataCount', {'property': this.pill.property, 'value': value});
            },
            rowSelected(value) {
                if (this.rowscrollableonselect) {
                    this.rowScrollable = value;
                }
            },
            searchBar(value){
                if (this.filterable){
                    this.$store.commit('filter', this.property);
                }
            },
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
            customClicked(datum) {
                this.$emit('customclick', datum.id);
                this.$emit('customclickraw', datum);
            },
            sort(columnName) {
                this.$store.dispatch('sort', {'property': this.property, 'key': columnName});
            },
            getHeaderStyle(key, notSortable) {
                if (!notSortable && this.sortSetting[this.property]) {
                    if (this.sortSetting[this.property]['key'] === key) {
                        switch (this.sortSetting[this.property]['order']) {
                            case 'asc':
                                return "cursor: pointer; background-image: url(/img/asc.gif); background-repeat: no-repeat; background-position: center right;"
                            case 'desc':
                                return "cursor: pointer; background-image: url(/img/desc.gif); background-repeat: no-repeat; background-position: center right;"
                        }
                    } else {
                        return "cursor: pointer; background-image: url(/img/bg.gif); background-repeat: no-repeat; background-position: center right;"
                    }
                }
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

