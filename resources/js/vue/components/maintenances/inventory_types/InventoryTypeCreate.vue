<template>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add Inventories Type</h4>
            </div>
            <div class="card-body">
                <v-form name="inventory_type" class="row" ref="form">
                    <div class="col-12">
                        <base-input placeholder="Name" field="name" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-12">
                        <select-box field="category" placeholder="Category" type="select" :forceoption="typeData"
                                    optiontext="text" optionvalue="text" addon-left-icon="tim-icons  icon-support-17"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                    <div class="col-12">
                        <base-radio-input field="has_serial" label="Serial Number" optiontext="text" optionvalue="text" :vparam="['required']"
                                          :forceoption="[{'text':'มี', 'value': true},{'text':'ไม่มี', 'value': false}]"></base-radio-input>
                    </div>
                    <div class="col-12">
                        <base-radio-input field="quantable" label="นับได้หรือไม่" optiontext="text" optionvalue="text" :vparam="['required']"
                                          :forceoption="[{'text':'นับได้', 'value': true},{'text':'นับไม่ได้', 'value': false}]"></base-radio-input>
                    </div>
                    <div class="col-12">
                        <base-radio-input field="sellable" label="ขายเป็นซากได้หรือไม่" optiontext="text" optionvalue="text" :vparam="['required']"
                                          :forceoption="[{'text':'ได้', 'value': true},{'text':'ไม่ได้', 'value': false}]"></base-radio-input>
                    </div>
                </v-form>
            </div>
            <div class="card-footer" style="text-align: center;">
                <button class="btn btn-fill btn-primary" @click="submit()">Create</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "InventoryTypeCreate",
        data() {
            return {
                typeData: [
                    {'text': 'ระบบแอร์', 'value': 1},
                    {'text': 'ระบบเบรค', 'value': 2},
                    {'text': 'แชสซี', 'value': 3},
                    {'text': 'ระบบคลัทช์', 'value': 3},
                    {'text': 'ระบบระบายความร้อน', 'value': 3},
                    {'text': 'ระบบไฟฟ้า', 'value': 3},
                    {'text': 'ระบบเครื่องยนต์', 'value': 3},
                    {'text': 'ระบบไอเสีย', 'value': 3},
                    {'text': 'ตัวถังรถบรรทุก', 'value': 3},
                    {'text': 'เฟืองท้าย', 'value': 3},
                    {'text': 'จานเทรลเลอร์', 'value': 3},
                    {'text': 'ระบบไฮดรอลิค', 'value': 3},
                    {'text': 'เพลา', 'value': 3},
                    {'text': 'ระบบขับเคลื่อน', 'value': 3},
                    {'text': 'ระบบลม', 'value': 3},
                    {'text': 'อื่นๆ', 'value': 3},
                ],
                forms: [],
            }
        },
        watch: {
            rowSelected(value) {
                this.$store.commit('forceNavMini', value);
            },
        },

        created() {
            this.$store.dispatch('populateForm', {
                'property': 'inventory',
                'form': 'inventory_type',
                'field': {
                    name: null,
                    category: null,
                    has_serial: null,
                    sellable: null,
                    quantable: null,
                }
            });

        },

        computed: {},

        mounted() {
            this.$children.forEach((child) => {
                if (child.$options.name === 'vForm') {
                    this.forms.push(child)
                }
            });
        },

        methods: {
            submit() {

                this.$refs.form.validateForm();
                if (this.$refs.form.validated) {
                    this.$store.dispatch('submit', {'form': 'inventory_type', 'url': '/api/inventory_type'})
                        .then(response => {
                            this.forms.forEach((form) => {
                                form.reset();
                            });
                            this.$store.commit('hideModal');
                            this.$store.state.refreshType = true;
                            this.$store.dispatch('getTableData', {'property': 'inventory_types', 'is_group': true})
                            console.log(response);
                        });
                }
            }
        },
    }
</script>

<style scoped>

</style>
