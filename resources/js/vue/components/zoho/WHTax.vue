<template>


    <div v-if="rowSelected" :class="{'col-md-9':showIndex,'col-md-12':!showIndex}">
        <div class="card card-user" :class="{'sticky': pin}">
            <div class="card-body">

                <div v-if="showIndex" class="tools float-left">
                    <a @click="$store.commit('showIndex',false)" type="button" class="btn btn-icon" style="z-index: 2; color: white;">
                        <!--            <button @click="show = !show" type="button" class="btn btn-default dropdown-toggle btn-link btn-icon">-->
                        <i class="eec-icons icon-move-layer-left"></i>
                    </a>
                </div>
                <div v-else class="tools float-left">
                    <a @click="$store.commit('showIndex',true)" type="button" class="btn btn-icon" style="z-index: 2; color: white;">
                        <!--            <button @click="show = !show" type="button" class="btn btn-default dropdown-toggle btn-link btn-icon">-->
                        <i class="eec-icons icon-move-layer-right"></i>
                    </a>
                </div>
                <div v-if="showIndex" class="tools float-right">
                    <a @click="pin = !pin" type="button" class="btn btn-icon" :class="{'btn-info':pin}" style="z-index: 2; color: white;">
                        <!--            <button @click="show = !show" type="button" class="btn btn-default dropdown-toggle btn-link btn-icon">-->
                        <i class="tim-icons icon-pin"></i>
                    </a>
                </div>
                <div class="tools text-center">
                    <h3 class="card-description" style="margin:6px 0 0 0;"> {{ datum.bill_number }} </h3>
                </div>
                <div class="card-text">
                    <div class="author">
                        <div style="background-color:#1e1e2d; border: rgba(255, 255, 255, 0.1) 1px solid; border-radius: 5px;">
                            <cardheader style="text-align: left;" title="Bill"></cardheader>

                            <div class="row justify-content-center">
                                <div class="col-sm-5">
                                    <base-input :forcevalue="datum.vendor_name" placeholder="Vendor" addon-left-icon="tim-icons icon-single-02"
                                                :vparam="[]" showonly>
                                    </base-input>
                                </div>
                                <div class="col-sm-5">
                                    <base-input :forcevalue="datum.due_date" placeholder="Vendor" addon-left-icon="tim-icons icon-calendar-60"
                                                :vparam="[]" showonly>
                                    </base-input>
                                </div>
                                <div class="col-sm-10">
                                    <base-input :forcevalue="datum.total" placeholder="Amount" addon-left-icon="tim-icons  icon-money-coins"
                                                :vparam="[]" showonly>
                                    </base-input>
                                </div>
                            </div>


                            <cardheader style="text-align: left;" title="Detail"></cardheader>

                            <v-form name="whtax" class="row justify-content-center mb-3" ref="cert">

                                <div class="col-sm-10">
                                    <select-box field="tax_type" placeholder="แบบภาษี" type="dropdown"
                                                :forceoption="[{'text':'(1) ภ.ง.ด. 1 ก.'},{'text':'(2) ภ.ง.ด. 1 ก. พิเศษ'},{'text':'(3) ภ.ง.ด. 2'},{'text':'(4) ภ.ง.ด. 3'},{'text':'(5) ภ.ง.ด. 2 ก.'},{'text':'(6) ภ.ง.ด. 3 ก.'},{'text':'(7) ภ.ง.ด. 53'}]"
                                                optiontext="text" optionvalue="text" addon-left-icon="tim-icons icon-bank"
                                                :vparam="['required']"></select-box>
                                </div>
                                <!--                                <div class="col-sm-5">-->
                                <!--                                    <base-datepicker placeholder="Start Date" field="start_date" addon-left-icon="tim-icons icon-calendar-60"-->
                                <!--                                                     :vparam="['required']">-->
                                <!--                                    </base-datepicker>-->
                                <!--                                </div>-->
                                <br>

                            </v-form>

                            <v-form name="whtax" ref="whtax" v-if="$store.state.forms.whtax.tax_type">
                                <div v-for="(item, index) in datum.line_items" :key="index" class="row justify-content-center mb-3">
                                    <div class="tools float-left" v-if="(!forms.whtax[index])">
                                        <a @click="addForm(index)" type="button" class="btn btn-icon btn-info" style="z-index: 2; color: white;">
                                            <!--            <button @click="show = !show" type="button" class="btn btn-default dropdown-toggle btn-link btn-icon">-->
                                            <i class="tim-icons icon-simple-add"></i>
                                        </a>
                                    </div>
                                    <div class="col-sm-7">
                                        <base-input :forcevalue="item.description" placeholder="Item Detail" addon-left-icon="tim-icons  icon-bullet-list-67"
                                                    :vparam="[]" showonly>
                                        </base-input>
                                    </div>
                                    <div class="col-sm-3">
                                        <base-input :forcevalue="item.total" placeholder="Rate" addon-left-icon="tim-icons  icon-tag"
                                                    :vparam="[]" showonly>
                                        </base-input>
                                    </div>

                                    <div class="col-sm-7" v-if="(forms.whtax[index])">
                                        <select-box subfield="item_detail" :field="index" placeholder="ประเภทเงินได้" type="dropdown"
                                                    :forceoption="item_detail"
                                                    optiontext="text" optionvalue="value" addon-left-icon="tim-icons icon-paper"
                                                    :vparam="['required']"></select-box>
                                    </div>
                                    <div class="col-sm-3" v-if="(forms.whtax[index])">
                                        <select-box subfield="deduct" :field="index" placeholder="หัก (%)" type="dropdown"
                                                    :forceoption="[{'text':'1%', 'value':1},{'text':'2%', 'value':2},{'text':'3%', 'value':3},{'text':'5%', 'value':5}]"
                                                    optiontext="text" optionvalue="value" addon-left-icon="tim-icons icon-scissors"
                                                    :vparam="['required']"></select-box>
                                    </div>
                                </div>
                            </v-form>
                            <div class="col-sm-10 text-center">
                                <base-button type="primary" wide @click="">
                                    Save
                                </base-button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {

        props: {},
        data() {
            return {
                pin: true,
                index: true,
                submitting: false,
                item_detail: [{'text': '(1) เงินเดือน ค่าจ้าง เบี้ยเลี้ยง โบนัส ฯลฯ  ตามมาตรา 40', 'value': 1},
                    {'text': '(2) ค่าธรรมเนียม  ค่านายหน้า  ฯลฯ  ตามมาตรา 40', 'value': 2},
                    {'text': '(3) ค่าแห่งลิขสิทธิ์  ฯลฯ  ตามมาตรา 40', 'value': 3},
                    {'text': '(4) (ก) ค่าดอกเบี้ย ฯลฯ  ตามมาตรา 40', 'value': 41},
                    {'text': '(4) (ข) เงินปันผล ส่วนแบ่งของกำไร ฯลฯ ตามมาตรา 40', 'value': 42},
                    {'text': '(5) การจ่ายเงินได้ที่ต้องหักภาษี ณ. ที่จ่าย ตามคำสั่งกรมสรรพากรที่ออกตามมาตรา 3 เตรส', 'value': 5},
                    {'text': '(6) (1) ค่าจ้างขนส่ง', 'value': 61},
                    {'text': '(6) (2) ค่าเบี้ยประกันวินาศภัย', 'value': 62},
                    {'text': '(7) อื่นๆ', 'value': 7},
                ]
            }
        },
        created() {

            this.$store.dispatch('populateForm', {
                'property': 'whtax',
                'form': 'whtax',
                'field': {
                    id: null,
                    tax_type: null,
                }
            });
        },
        watch: {
            rowId: function (newVal, oldVal) {
                this.$store.commit('resetForm', 'whtax');
                this.$store.commit('updateForm', {'form': 'whtax', 'field': 'id', 'value': newVal});
            }
        },

        computed: {
            ...mapState([
                'rowSelected',
                'showIndex',
                'rowId',
                'datum',
                'forms'
            ])
        },
        methods: {
            submit() {
                if (!this.submitting) {
                    this.submitting = true;
                    this.$refs.whtax.validateForm();
                    if (this.$refs.whtax.validated) {
                        this.$store.dispatch('submit', {'form': 'whtax', 'url': '/api/whtax', 'reset': false})
                            .then(response => {
                                console.log(response);
                                Swal.fire('Complete!', 'บันทึกสำเร็จ!', 'success');
                                this.mode = null;
                                this.$store.commit('resetForm', 'whtax');
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    }
                    this.submitting = false;
                }
            },
            addForm(index) {
                let item_detail = {'item_detail': null, 'item_sub_detail': null, 'deduct': null};
                this.$store.commit("addField", {'name': 'whtax', 'field': {'property': index, 'value': item_detail}});
            },
        },
    };
</script>

<style scoped>

    .sticky {
        position: sticky;
        top: 30px;
    }

</style>
