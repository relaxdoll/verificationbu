<template>

    <div>

        <pill :collapseonselect="collapseonselect" v-if="showPill" @select="pillSelected($event)" :data="pill.data" :default="pill.default"></pill>

        <div class="table-responsive">
            <table class="table" name="list" is="transition-group">
                <thead key="head" class=" text-primary">
                <tr :class="{'scrollable': rowScrollable}">
                    <th v-for="(column, index) in columns" :key="index" :style="getHeaderStyle(column.data, column.notSortable)"
                        :class="'text-'+column.align" @click="sort(column.data)">
                        {{column.text}}

                    </th>
                </tr>
                </thead>
                <tbody v-for="(group, groupName) in tables[property]" :key="groupName" v-if="groupName === select">
                <tr :class="{ 'scrollable': rowScrollable, 'selectable': allowselect, 'selected': (rowId === datum.id)}"
                    @click="(allowselect)?$store.dispatch('rowSelect', datum.id):false"
                    v-for="(datum, no) in group" :key="datum.id" style="font-size:0.875rem;">
                    <td v-for="(column, index) in columns" :key="index" :class="'text-'+column.align">

                        <!--                        string-->

                        <div v-if="(!column.type)">
                            {{datum[column.data]}}
                        </div>

                        <div v-if="(column.type === 'index')">
                            {{no+1}}
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
            params: {default: null},
            columns: {required: true},
            allowselect: {default: false},
            rowscrollableonselect: {default: false},
            filterable: {default: true},
            url: {required: false},
            blank: Boolean,
            collapseonselect: Boolean,
        },
        data() {
            return {
                data: {},
                select: null,
                currentRow: null,
                rowScrollable: false,
                is_number: [],
                pill: {'data': [], 'default': null},
                showPill: false,
            }
        },
        created() {
            if (!this.blank) {
                this.$store.dispatch('getTableData', {'property': this.property, 'is_group': true, 'params': this.params, 'url': this.url})
            }

        },
        mounted() {
            this.columns.forEach(column => {
                if (column.type === 'sortNumber') {
                    this.is_number.push(column.data);
                }
            })
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
                if (value[this.property]) {
                    this.$store.dispatch('initializeSortGroup', {'property': this.property});
                    this.pill.data = [];
                    for (let group in value[this.property]) {
                        if (!this.pill.default) {
                            this.pill.default = group;
                            this.pillSelected(group);
                        }
                        this.pill.data.push({'text': group, 'value': group});
                    }
                }
                this.showPill = true;
            },
            select: function (value) {
                this.$store.commit('dataCountSet', {'property': this.property, 'count': this.tables[this.property][this.select].length});
            },
            rowSelected(value) {
                if (this.rowscrollableonselect) {
                    this.rowScrollable = value;
                }
            },
            searchBar(value) {
                if (this.filterable) {
                    this.$store.commit('filterGroup', {'property': this.property, 'select': this.select});
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
            sort(columnName) {
                this.$store.dispatch('sortGroup', {
                    'property': this.property,
                    'group': this.select,
                    'key': this.is_number.includes(columnName) ? columnName + '_int' : columnName
                });
            },
            getHeaderStyle(key, notSortable) {
                if (!notSortable && this.sortSetting[this.property]) {
                    if (this.sortSetting[this.property][this.select]['key'] === key) {
                        switch (this.sortSetting[this.property][this.select]['order']) {
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

