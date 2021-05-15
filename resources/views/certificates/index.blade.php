@extends('layouts.app', ['activePage' => 'indexCertificate', 'titlePage' => __('Certificate')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'Driver','href':'/driver'}]" active="Certificate"></breadcrumb>

        <topbutton text="Back" link="/driver"></topbutton>

        <card>

            <cardheader title="Create Certificate">

            </cardheader>

            <cardbody>
                <v-form name="certificate" class="row justify-content-center" ref="cert">
                    <div class="col-sm-5">
                        <select-box field="sex" placeholder="เพศ" type="select" :forceoption="typeData"
                                    optiontext="text" optionvalue="text" addon-left-icon="tim-icons  icon-caps-small"
                                    allowfilter="true" filtertype="contains" :vparam="['required']"></select-box>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="ชื่อ-นามสกุล" field="name" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['required']">
                        </base-input>
                    </div>

                    <div class="col-sm-5">
                        <base-input placeholder="Course" field="course" addon-left-icon="tim-icons  icon-book-bookmark"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-datepicker placeholder="วันที่" field="givenDate" addon-left-icon="tim-icons icon-calendar-60"
                                         :vparam="['required']">
                        </base-datepicker>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Lecturer Name" field="lecturerName" addon-left-icon="tim-icons  icon-caps-small"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Lecturer Position" field="lecturerPosition" addon-left-icon="tim-icons icon-single-02"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-10 text-center">
                        <base-button type="primary" wide @click="submit">
                            Create
                        </base-button>
                    </div>
                </v-form>

            </cardbody>
        </card>

        <card>

            <cardheader count="certificate" title="Certificate Lists">

                <gear>
                    <a class="dropdown-item" @click="refresh()" style="cursor: pointer;">Refresh</a>
                </gear>

            </cardheader>

            <cardbody>

                <darktable @customclick="print($event)" property="certificate" :columns="tableColumn">

                </darktable>

            </cardbody>
        </card>

    </div>

@endsection

@push('js')
    <script src=" {{ mix('/js/vue/index.js') }}"></script>


    <script>

        new Vue({
            el: '#asset',


            store,

            data: {
                rowIsSelected: false,
                typeData: [
                    {'text': 'นาย', 'value': 1},
                    {'text': 'นางสาว', 'value': 2},
                    {'text': 'นาง', 'value': 3}
                ],
                tableColumn: [
                    {'text': '#', 'type': 'index'},
                    {'text': 'Name', 'data': 'name'},
                    {'text': 'Course', 'data': 'course'},
                    {'text': 'Date', 'data': 'givenDate'},
                    {'text': 'Lecturer', 'data': 'lecturerName'},
                    {'text': 'Print', 'type': 'custom', 'icon': 'eec-icons icon-print', 'align': 'center', 'tooltip': 'Print'},
                ],
            },

            watch: {},

            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'certificate',
                    'form': 'certificate',
                    'field': {
                        sex: null,
                        name: null,
                        course: 'ความปลอดภัย อาชีวอนามัย และสภาพแวดล้อมในการทำงาน',
                        givenDate: moment().format('YYYY-MM-DD HH:mm:ss'),
                        lecturerName: '',
                        lecturerPosition: 'เจ้าหน้าที่ความปลอดภัยระดับวิชาชีพ',
                    }
                });

            },

            computed: {
                ...mapState([
                    'forms'
                ]),
            },

            mounted() {
            },

            methods: {
                submit() {
                    this.$refs.cert.validateForm();
                    if (this.$refs.cert.validated) {

                        this.$store.dispatch('submit', {'form': 'certificate', 'url': '/api/certificate', 'reset': false})
                            .then(response => {
                                this.refresh();
                                console.log(response);
                            })
                            .catch(error => {
                                console.log(error);
                            });

                    }
                },
                refresh() {

                    this.$store.dispatch('getTableData', {'property': 'certificate'})
                        .then(() => {
                            this.$store.commit('dataCountSet', {'property': 'certificate', 'count': this.$store.state.tables.certificate.length});
                        });
                },
                print(id) {
                    window.open(`/certificate/${id}`, '_blank');
                }

            },
        });
    </script>
@endpush
