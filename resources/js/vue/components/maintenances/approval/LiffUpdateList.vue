<template>
    <div>
        <selector-icon :error="error" :islast="islast" @click="touchSelector()" :isgrouped="isgrouped" :icon="icon" :iconcolor="iconcolor"
                       :label="label" :disable="disable" :valuetext="valueText"></selector-icon>

        <label v-show="error" style="font-size: 11px; color: red; margin: 0 0 0 15px;">{{ error }}</label>

        <liffdrawer2 @close="selected = false" align="right" :maskclosable="true" :label="label" :currentview="$store.page_name"
                     :closeable="true">

            <div v-if="selected">
                <liff-form ref="inventory" name="maintenance_inventory_detail">
                    <liff-input-group class="spacing" v-for="(list, index) in lists" :key="index">
                        <liffdropdown2 icon="icon icon-bus-front-10" iconcolor="#fc9500" :searchbar="false" label="ประเภทของอะไหล่" @input="getInventoryByTypeId($event)"
                                       url="inventory_type" :field="'inventory_type_id' + list.id" optiontext="text" optionvalue="value"
                                       :vparam="['']"></liffdropdown2>
                        <liffdropdown2 icon="icon icon-bus-front-10" iconcolor="#fc9500" :searchbar="false" label="อะไหล่"
                                       :forceoption="inventories" :field="'inventory_id' + list.id" optiontext="name" optionvalue="id" :vparam="['']"
                                       :disable="hasInventoryId(list.id)" @input="getMaxInventory($event, list)"></liffdropdown2>
                        <liff-input :field="'quantity' + list.id" icon="icon icon-h-dashboard" iconcolor="#fc3e30" placeholder="จำนวนอะไหล่ที่ใช้"
                                    type="number" :vparam="['']"
                                    :disable="hasInventoryId(list.id)"></liff-input>
                    </liff-input-group>

                    <div class="text-center pt-4" style="margin-bottom: 150px;">
                        <button class="btn btn-danger" @click="deleteList()">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button class="btn btn-primary" @click="addNewList()">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <liff-submit-button @submit="submit()" label="บันทึก"></liff-submit-button>

                </liff-form>
            </div>

        </liffdrawer2>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "liff-update-list",
        props: {
            label: {required: true},
            icon: {required: false},
            iconcolor: {required: false},
            detail: {required: false},
            disable: {required: false},
        },

        data() {
            return {
                inputValue: null,
                focused: false,
                fields: {text: this.optiontext, value: this.optionvalue, groupBy: this.optiongroup},
                touched: false,
                error: null,
                rule: {},
                errorsToCheck: [],
                isInvalid: false,
                forceShowError: false,
                form: null,
                options: null,
                isgrouped: false,
                islast: false,
                selected: false,
                valueText: '',

                count: 2,
                lists: [{id: 1, maxValue: null}],
                inventories: [],
            }
        },
        created() {
            this.$store.dispatch('populateForm', {
                'property': 'maintenance',
                'form': 'maintenance_inventory_detail',
                'field': {
                    maintenance_detail_id: null,
                    inventory_id1: null,
                    quantity1: null,
                }
            });
        },
        mounted() {
        },
        computed: {
            ...mapState([
                'validate',
                'forms',
            ]),
        },
        watch: {
            selected(val) {
                if (val === false) {
                    this.$store.commit('resetForm', 'maintenance_inventory_detail');
                }
            },
        },
        methods: {
            getMaxInventory(inventory, list) {
                this.$store.commit('updateForm', {'form': 'maintenance_inventory_detail', 'field': 'max_quantity' + list.id, 'value': inventory.current_quantity});
            },
            hasInventoryId(id) {
                let has = true;
                if (eval('this.forms.maintenance_inventory_detail.inventory_type_id' + id)) {
                    has = false;
                }
                return has;
            },
            getInventoryByTypeId(type) {
                return new Promise((resolve, reject) => {
                    axios.get(`api/inventory/crud/getInventoryByTypeId/${type.value}`, {})
                        .then(response => {
                            this.inventories = response.data.data;
                            console.log(response.data);
                            resolve(response.data);
                        })
                        .catch(error => {
                            console.log(error);
                            reject(error.response);
                        });
                });
            },
            addNewList() {
                this.$store.commit('updateForm', {'form': 'maintenance_inventory_detail', 'field': 'inventory_id' + this.count, 'value': null});
                this.$store.commit('updateForm', {'form': 'maintenance_inventory_detail', 'field': 'quantity' + this.count, 'value': null});

                this.lists.push({id: this.count, maxValue: null});
                this.count++;
            },
            deleteList() {
                let temp = {...this.forms.maintenance_inventory_detail};
                this.$store.commit('resetForm', 'maintenance_inventory_detail');

                if (this.count > 2) {
                    for (let i = 1; i < (this.count - 1); i++) {
                        this.$store.commit('updateForm', {'form': 'maintenance_inventory_detail', 'field': 'inventory_id' + i, 'value': eval("temp.inventory_id" + i)});
                        this.$store.commit('updateForm', {'form': 'maintenance_inventory_detail', 'field': 'quantity' + i, 'value': eval("temp.quantity" + i)});
                    }
                    this.lists.pop();
                    this.count--;
                }
            },
            touchSelector() {
                if (!this.disable) {
                    this.selected = true;
                    this.touched = true;
                }
            },
            select(option) {
                if (this.optionvalue) {
                    this.value = option[this.optionvalue];
                    this.valueText = option[this.optiontext];
                } else {
                    this.value = option;
                    this.valueText = option;
                }

                this.$emit('input', option);

                if (this.closeonselect) {
                    new Promise(function (resolve, reject) {
                        setTimeout(function () {
                            resolve('foo');
                        }, 150);
                    })
                        .then(response => {
                            this.selected = false;
                        })
                }
                // this.selected = false;
            },
            isSelected(option) {

                if (this.optionvalue) {
                    return this.value === option[this.optionvalue];
                } else {
                    return this.value === option;
                }
            },
            getSuccess() {
                switch (this.type) {
                    case 'select':
                        return !this.error && this.touched && this.value;
                    case 'multiselect':
                        if (this.value) {
                            return !this.error && this.touched && this.value.length;
                        } else {
                            return !this.error && this.touched && this.value;
                        }
                }

            },
            getOption() {
                return new Promise((resolve, reject) => {
                    axios.get('/api/' + this.url, {
                        params: this.param
                    })
                        .then(response => {
                            this.options = response.data.data;
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
                this.$store.commit('updateForm', {'form': 'maintenance_inventory_detail', 'field': 'maintenance_detail_id', 'value': this.detail.id});
                this.$store.commit('updateForm', {'form': 'maintenance_inventory_detail', 'field': 'numberList', 'value': this.lists.length});

                this.$store.dispatch('submit', {'form': 'maintenance_inventory_detail', 'url': `/api/maintenance_inventory_detail`})
                    .then(response => {
                        this.selected = false;
                        this.$emit('update-maintenance-detail');
                        this.$store.commit('resetForm', 'maintenance_inventory_detail');
                    });

            }
        }
    }
</script>

<style scoped>

</style>
