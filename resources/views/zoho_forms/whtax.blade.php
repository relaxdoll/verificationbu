@extends('layouts.plain', ['activePage' => 'indexDriver', 'titlePage' => __('Driver')])

@section('style')
    <link href="http://fonts.cdnfonts.com/css/leelawadee" rel="stylesheet">
    <style>
        .print-text {
            position: absolute;
        }

        @media print {
            @page {
                margin: 0;
                size: 210mm 297.1mm;
            }

            .non-print-css {
                display: none;
            }
        }
    </style>

@endsection
@section('content')



    <div id="printMe" style="font-family: 'Leelawadee', sans-serif; font-size: 13px; font-weight: 400;">

        <img src="/img/whtax.png" alt="" style="height: 297mm; width: 210mm">

        <div id="order">
            <div class="print-text" style="top: 8.5mm; left: 188mm;">
                1
            </div>
            <div class="print-text" style="top: 60mm; left: 35mm;">
                1
            </div>
            <div class="print-text" style="top: 66mm; left: 139mm;">
                X
            </div>
            <div class="print-text" style="top: 220.5mm; left: 36mm;">
                X
            </div>
        </div>

        <div id="company">
            <div class="print-text" style="top: 19.5mm; left: 21mm;">
                บริษัท อีอีซีไลน์ จำกัด (สำนักงานใหญ่)
            </div>
            <div class="print-text" style="top: 26mm; left: 21mm;">
               47/9 ถนนสำนักอ้ายงอน ตำบลมาบข่า อำเภอนิคมพัฒนา จังหวัดระยอง 21180
            </div>
            <div class="print-text" style="top: 21mm; left: 148.5mm; font-size: 14px; font-weight: 600; width: 50mm; text-align: center;">
                0215560010314
            </div>
        </div>

        <div id="customer">
            <div class="print-text" style="top: 42.5mm; left: 21mm;">
                บริษัท อีอีซีไลน์ จำกัด (สำนักงานใหญ่)
            </div>
            <div class="print-text" style="top: 49mm; left: 21mm;">
                47/9 ถนนสำนักอ้ายงอน ตำบลมาบข่า อำเภอนิคมพัฒนา จังหวัดระยอง 21180
            </div>
                <div class="print-text" style="top: 44mm; left: 148.5mm; font-size: 14px; font-weight: 600; width: 50mm; text-align: center;">
                0215560010314
            </div>
        </div>

        <div id="detail">
            <div class="print-text" style="top: 160.5mm; left: 120mm;">
                16/12/2020
            </div>
            <div class="print-text" style="top: 160.5mm; left: 144mm; text-align: right; width: 26mm;">
                5,196.29
            </div>
            <div class="print-text" style="top: 160.5mm; left: 174mm; text-align: right; width: 26mm;">
                51.96
            </div>
        </div>

        <div id="summary">
            <div class="print-text" style="top: 199mm; left: 144mm; text-align: right; width: 26mm;">
                5,196.29
            </div>
            <div class="print-text" style="top: 199mm; left: 174mm; text-align: right; width: 26mm;">
                51.96
            </div>
            <div class="print-text" style="top: 209mm; left: 10mm; width: 140mm; text-align: center; font-size: 15px; font-weight: 600;">
                ห้าสิบเอ็ดบาทเก้าสิบหกสตางค์
            </div>
        </div>

        <div id="date">
            <div class="print-text" style="top: 245.5mm; left: 85mm;">
                16/12/2020
            </div>
        </div>
    </div>
    <!-- OUTPUT -->

{{--    <div class="non-print-css">--}}
{{--        <h1>Print me!</h1>--}}
{{--        <button @click="print"></button>--}}
{{--    </div>--}}


@endsection

@push('js')
    <script src=" {{ mix('/js/vue/index.js') }}"></script>


    <script>

        new Vue({
            el: '#asset',


            store,

            data: {
                rowIsSelected: false,
                output: null,
            },

            watch: {},

            created() {
                this.$store.commit('setModel', 'driver');

            },

            computed: {
                ...mapState([
                    'rowSelected',
                    'rowId',
                    'datum',
                    'dataCount'
                ]),
            },

            mounted() {
                this.print();
            },

            methods: {
                print() {
                    // Pass the element id here
                    window.print();
                }
            },
        });
    </script>
@endpush
