@extends('layouts.liff', ['activePage' => 'indexReplaceTire', 'titlePage' => __('Liff Starter')])

@section('style')
    {{--    <link href="/css/liff.css" rel="stylesheet"/>--}}
    <link href="https://fonts.googleapis.com/css?family=BenchNine:700&display=swap" rel="stylesheet">
@endsection
@section('nav')
    {{--    @include('liff.qr.nav')--}}
@endsection
@section('content')

    <div class="content" v-if="show">

        <div>

            <div style="display: table; padding: 0 20px; height: 60px; width: 100vw; background-color: #3B3736; z-index: 99999;">
                <div style="display: table-cell; vertical-align: middle;">
                    <div class="col-12" style="text-align:center; font-weight: 500; color: white;">
                        Fast Track
                    </div>
                </div>
            </div>

            <liffalert @on-hide="close()" :value="showAlert" title="ส่งรายงานสำเร็จ" content="รายงานได้ถูกส่งไปยังกลุ่มที่เลือกแล้ว" buttontext="ปิด"></liffalert>

            <liffloader :show="showLoading" text="Loading"></liffloader>

            <liffloader :show="showSending" text="Sending"></liffloader>

            <div>

                <liffdropdown :searchbar="false" label="ทะเบียนรถ" currentview="กลับ" :selectedoption="fast_track.detail.vehicle"
                              @input="fast_track.detail.vehicle = $event" @index="selectVehicle($event)" :col="5"
                              :options="fast_track.info.vehicle"></liffdropdown>

                <liffdropdown v-if="hasVehicle" :searchbar="false" label="ลูกค้า" currentview="กลับ" :selectedoption="fast_track.detail.customer"
                              @input="fast_track.detail.customer = $event" @index="selectCustomer($event)" :col="3"
                              :options="fast_track.info.customer"></liffdropdown>

                <liffdropdown v-if="hasCustomer" :searchbar="false" label="รายงาน" currentview="กลับ" :selectedoption="fast_track.detail.report"
                              @input="fast_track.detail.report = $event" @index="selectReport($event)" :col="3"
                              :options="fast_track.report"></liffdropdown>

                {{--                <liffdropdown :searchbar="true" label="รายงาน" currentview="Create" :selectedoption="qr.detail.country" @input="qr.detail.country = $event" :col="3"--}}
                {{--                              :options="['Thailand (+66)', 'Afghanistan (+93)', 'Albania (+355)', 'Algeria (+213)', 'American Samoa (+1684)', 'Andorra (+376)', 'Angola (+244)', 'Anguilla (+1264)', 'Antarctica (+672)', 'Antigua and Barbuda (+1268)', 'Argentina (+54)', 'Armenia (+374)', 'Aruba (+297)', 'Australia (+61)', 'Austria (+43)', 'Azerbaijan (+994)', 'Bahamas (+1242)', 'Bahrain (+973)', 'Bangladesh (+880)', 'Barbados (+1246)', 'Belarus (+375)', 'Belgium (+32)', 'Belize (+501)', 'Benin (+229)', 'Bermuda (+1441)', 'Bhutan (+975)', 'Bolivia (+591)', 'Bosnia and Herzegovina (+387)', 'Botswana (+267)', 'Brazil (+55)', 'British Virgin Islands (+1284)', 'Brunei (+673)', 'Bulgaria (+359)', 'Burkina Faso (+226)', 'Burundi (+257)', 'Cambodia (+855)', 'Cameroon (+237)', 'Canada (+1)', 'Cape Verde (+238)', 'Cayman Islands (+1345)', 'Central African Republic (+236)', 'Chad (+235)', 'Chile (+56)', 'China (+86)', 'Christmas Island (+61)', 'Cocos Islands (+61)', 'Colombia (+57)', 'Comoros (+269)', 'Cook Islands (+682)', 'Costa Rica (+506)', 'Croatia (+385)', 'Cuba (+53)', 'Curacao (+599)', 'Cyprus (+357)', 'Czech Republic (+420)', 'Denmark (+45)', 'Djibouti (+253)', 'Dominica (+1767)', 'East Timor (+670)', 'Ecuador (+593)', 'Egypt (+20)', 'El Salvador (+503)', 'Equatorial Guinea (+240)', 'Eritrea (+291)', 'Estonia (+372)', 'Ethiopia (+251)', 'Falkland Islands (+500)', 'Faroe Islands (+298)', 'Fiji (+679)', 'Finland (+358)', 'France (+33)', 'French Polynesia (+689)', 'Gabon (+241)', 'Gambia (+220)', 'Georgia (+995)', 'Germany (+49)', 'Ghana (+233)', 'Gibraltar (+350)', 'Greece (+30)', 'Greenland (+299)', 'Grenada (+1473)', 'Guam (+1671)', 'Guatemala (+502)', 'Guernsey (+441481)', 'Guinea (+224)', 'GuineaBissau (+245)', 'Guyana (+592)', 'Haiti (+509)', 'Honduras (+504)', 'Hong Kong (+852)', 'Hungary (+36)', 'Iceland (+354)', 'India (+91)', 'Indonesia (+62)', 'Iran (+98)', 'Iraq (+964)', 'Ireland (+353)', 'Isle of Man (+441624)', 'Israel (+972)', 'Italy (+39)', 'Ivory Coast (+225)', 'Jamaica (+1876)', 'Japan (+81)', 'Jersey (+441534)', 'Jordan (+962)', 'Kazakhstan (+7)', 'Kenya (+254)', 'Kiribati (+686)', 'Kosovo (+383)', 'Kuwait (+965)', 'Kyrgyzstan (+996)', 'Laos (+856)', 'Latvia (+371)', 'Lebanon (+961)', 'Lesotho (+266)', 'Liberia (+231)', 'Libya (+218)', 'Liechtenstein (+423)', 'Lithuania (+370)', 'Luxembourg (+352)', 'Macau (+853)', 'Macedonia (+389)', 'Madagascar (+261)', 'Malawi (+265)', 'Malaysia (+60)', 'Maldives (+960)', 'Mali (+223)', 'Malta (+356)', 'Marshall Islands (+692)', 'Mauritania (+222)', 'Mauritius (+230)', 'Mayotte (+262)', 'Mexico (+52)', 'Micronesia (+691)', 'Moldova (+373)', 'Monaco (+377)', 'Mongolia (+976)', 'Montenegro (+382)', 'Montserrat (+1664)', 'Morocco (+212)', 'Mozambique (+258)', 'Myanmar (+95)', 'Namibia (+264)', 'Nauru (+674)', 'Nepal (+977)', 'Netherlands (+31)', 'Netherlands Antilles (+599)', 'New Caledonia (+687)', 'New Zealand (+64)', 'Nicaragua (+505)', 'Niger (+227)', 'Nigeria (+234)', 'Niue (+683)', 'North Korea (+850)', 'Northern Mariana Islands (+1670)', 'Norway (+47)', 'Oman (+968)', 'Pakistan (+92)', 'Palau (+680)', 'Palestine (+970)', 'Panama (+507)', 'Papua New Guinea (+675)', 'Paraguay (+595)', 'Peru (+51)', 'Philippines (+63)', 'Pitcairn (+64)', 'Poland (+48)', 'Portugal (+351)', 'Puerto Rico (+1787, 1939)', 'Qatar (+974)', 'Republic of the Congo (+242)', 'Reunion (+262)', 'Romania (+40)', 'Russia (+7)', 'Rwanda (+250)', 'Saint Barthelemy (+590)', 'Saint Helena (+290)', 'Saint Kitts and Nevis (+1869)', 'Saint Lucia (+1758)', 'Saint Martin (+590)', 'Saint Pierre and Miquelon (+508)', 'Samoa (+685)', 'San Marino (+378)', 'Sao Tome and Principe (+239)', 'Saudi Arabia (+966)', 'Senegal (+221)', 'Serbia (+381)', 'Seychelles (+248)', 'Sierra Leone (+232)', 'Singapore (+65)', 'Sint Maarten (+1721)', 'Slovakia (+421)', 'Slovenia (+386)', 'Solomon Islands (+677)', 'Somalia (+252)', 'South Africa (+27)', 'South Korea (+82)', 'South Sudan (+211)', 'Spain (+34)', 'Sri Lanka (+94)', 'Sudan (+249)', 'Suriname (+597)', 'Svalbard and Jan Mayen (+47)', 'Swaziland (+268)', 'Sweden (+46)', 'Switzerland (+41)', 'Syria (+963)', 'Taiwan (+886)', 'Tajikistan (+992)', 'Tanzania (+255)', 'Togo (+228)', 'Tokelau (+690)', 'Tonga (+676)', 'Trinidad and Tobago (+1868)', 'Tunisia (+216)', 'Turkey (+90)', 'Turkmenistan (+993)', 'Turks and Caicos Islands (+1649)', 'Tuvalu (+688)', 'U.S. Virgin Islands (+1340)', 'Uganda (+256)', 'Ukraine (+380)', 'United Arab Emirates (+971)', 'United Kingdom (+44)', 'United States (+1)', 'Uruguay (+598)', 'Uzbekistan (+998)', 'Vanuatu (+678)', 'Vatican (+379)', 'Venezuela (+58)', 'Vietnam (+84)', 'Wallis and Futuna (+681)', 'Western Sahara (+212)', 'Yemen (+967)', 'Zambia (+260)', 'Zimbabwe (+263)']"></liffdropdown>--}}

                {{--                <liffdropdown :searchbar="true" label="ทะเบียนรถ" currentview="Create" :selectedoption="qr.detail.country" @input="qr.detail.country = $event" :col="5"--}}
                {{--                              :options="['Thailand (+66)', 'Afghanistan (+93)', 'Albania (+355)', 'Algeria (+213)', 'American Samoa (+1684)', 'Andorra (+376)', 'Angola (+244)', 'Anguilla (+1264)', 'Antarctica (+672)', 'Antigua and Barbuda (+1268)', 'Argentina (+54)', 'Armenia (+374)', 'Aruba (+297)', 'Australia (+61)', 'Austria (+43)', 'Azerbaijan (+994)', 'Bahamas (+1242)', 'Bahrain (+973)', 'Bangladesh (+880)', 'Barbados (+1246)', 'Belarus (+375)', 'Belgium (+32)', 'Belize (+501)', 'Benin (+229)', 'Bermuda (+1441)', 'Bhutan (+975)', 'Bolivia (+591)', 'Bosnia and Herzegovina (+387)', 'Botswana (+267)', 'Brazil (+55)', 'British Virgin Islands (+1284)', 'Brunei (+673)', 'Bulgaria (+359)', 'Burkina Faso (+226)', 'Burundi (+257)', 'Cambodia (+855)', 'Cameroon (+237)', 'Canada (+1)', 'Cape Verde (+238)', 'Cayman Islands (+1345)', 'Central African Republic (+236)', 'Chad (+235)', 'Chile (+56)', 'China (+86)', 'Christmas Island (+61)', 'Cocos Islands (+61)', 'Colombia (+57)', 'Comoros (+269)', 'Cook Islands (+682)', 'Costa Rica (+506)', 'Croatia (+385)', 'Cuba (+53)', 'Curacao (+599)', 'Cyprus (+357)', 'Czech Republic (+420)', 'Denmark (+45)', 'Djibouti (+253)', 'Dominica (+1767)', 'East Timor (+670)', 'Ecuador (+593)', 'Egypt (+20)', 'El Salvador (+503)', 'Equatorial Guinea (+240)', 'Eritrea (+291)', 'Estonia (+372)', 'Ethiopia (+251)', 'Falkland Islands (+500)', 'Faroe Islands (+298)', 'Fiji (+679)', 'Finland (+358)', 'France (+33)', 'French Polynesia (+689)', 'Gabon (+241)', 'Gambia (+220)', 'Georgia (+995)', 'Germany (+49)', 'Ghana (+233)', 'Gibraltar (+350)', 'Greece (+30)', 'Greenland (+299)', 'Grenada (+1473)', 'Guam (+1671)', 'Guatemala (+502)', 'Guernsey (+441481)', 'Guinea (+224)', 'GuineaBissau (+245)', 'Guyana (+592)', 'Haiti (+509)', 'Honduras (+504)', 'Hong Kong (+852)', 'Hungary (+36)', 'Iceland (+354)', 'India (+91)', 'Indonesia (+62)', 'Iran (+98)', 'Iraq (+964)', 'Ireland (+353)', 'Isle of Man (+441624)', 'Israel (+972)', 'Italy (+39)', 'Ivory Coast (+225)', 'Jamaica (+1876)', 'Japan (+81)', 'Jersey (+441534)', 'Jordan (+962)', 'Kazakhstan (+7)', 'Kenya (+254)', 'Kiribati (+686)', 'Kosovo (+383)', 'Kuwait (+965)', 'Kyrgyzstan (+996)', 'Laos (+856)', 'Latvia (+371)', 'Lebanon (+961)', 'Lesotho (+266)', 'Liberia (+231)', 'Libya (+218)', 'Liechtenstein (+423)', 'Lithuania (+370)', 'Luxembourg (+352)', 'Macau (+853)', 'Macedonia (+389)', 'Madagascar (+261)', 'Malawi (+265)', 'Malaysia (+60)', 'Maldives (+960)', 'Mali (+223)', 'Malta (+356)', 'Marshall Islands (+692)', 'Mauritania (+222)', 'Mauritius (+230)', 'Mayotte (+262)', 'Mexico (+52)', 'Micronesia (+691)', 'Moldova (+373)', 'Monaco (+377)', 'Mongolia (+976)', 'Montenegro (+382)', 'Montserrat (+1664)', 'Morocco (+212)', 'Mozambique (+258)', 'Myanmar (+95)', 'Namibia (+264)', 'Nauru (+674)', 'Nepal (+977)', 'Netherlands (+31)', 'Netherlands Antilles (+599)', 'New Caledonia (+687)', 'New Zealand (+64)', 'Nicaragua (+505)', 'Niger (+227)', 'Nigeria (+234)', 'Niue (+683)', 'North Korea (+850)', 'Northern Mariana Islands (+1670)', 'Norway (+47)', 'Oman (+968)', 'Pakistan (+92)', 'Palau (+680)', 'Palestine (+970)', 'Panama (+507)', 'Papua New Guinea (+675)', 'Paraguay (+595)', 'Peru (+51)', 'Philippines (+63)', 'Pitcairn (+64)', 'Poland (+48)', 'Portugal (+351)', 'Puerto Rico (+1787, 1939)', 'Qatar (+974)', 'Republic of the Congo (+242)', 'Reunion (+262)', 'Romania (+40)', 'Russia (+7)', 'Rwanda (+250)', 'Saint Barthelemy (+590)', 'Saint Helena (+290)', 'Saint Kitts and Nevis (+1869)', 'Saint Lucia (+1758)', 'Saint Martin (+590)', 'Saint Pierre and Miquelon (+508)', 'Samoa (+685)', 'San Marino (+378)', 'Sao Tome and Principe (+239)', 'Saudi Arabia (+966)', 'Senegal (+221)', 'Serbia (+381)', 'Seychelles (+248)', 'Sierra Leone (+232)', 'Singapore (+65)', 'Sint Maarten (+1721)', 'Slovakia (+421)', 'Slovenia (+386)', 'Solomon Islands (+677)', 'Somalia (+252)', 'South Africa (+27)', 'South Korea (+82)', 'South Sudan (+211)', 'Spain (+34)', 'Sri Lanka (+94)', 'Sudan (+249)', 'Suriname (+597)', 'Svalbard and Jan Mayen (+47)', 'Swaziland (+268)', 'Sweden (+46)', 'Switzerland (+41)', 'Syria (+963)', 'Taiwan (+886)', 'Tajikistan (+992)', 'Tanzania (+255)', 'Togo (+228)', 'Tokelau (+690)', 'Tonga (+676)', 'Trinidad and Tobago (+1868)', 'Tunisia (+216)', 'Turkey (+90)', 'Turkmenistan (+993)', 'Turks and Caicos Islands (+1649)', 'Tuvalu (+688)', 'U.S. Virgin Islands (+1340)', 'Uganda (+256)', 'Ukraine (+380)', 'United Arab Emirates (+971)', 'United Kingdom (+44)', 'United States (+1)', 'Uruguay (+598)', 'Uzbekistan (+998)', 'Vanuatu (+678)', 'Vatican (+379)', 'Venezuela (+58)', 'Vietnam (+84)', 'Wallis and Futuna (+681)', 'Western Sahara (+212)', 'Yemen (+967)', 'Zambia (+260)', 'Zimbabwe (+263)']"></liffdropdown>--}}


            </div>


            <div v-if="hasReport">
                <div v-for="(image, index) in fast_track.reportDetail.image_title" :key="index">
                    <input style="display: none;" class="inputbox" type="file" :name="image.title" :id="image.title" :ref="index"
                           placeholder="Enter your website or text here"
                           @change="handleFileUpload(index)"/>

                    <label :for="image.title" style="margin-top: 20px; margin-bottom: 0;">
                        <div class="image-uploader" v-if="fast_track.imageData[index].length > 0">
                            <img style="height: 300px;" :src="fast_track.imageData[index]">
                        </div>

                        <div class="image-uploader" v-else>
                            <img src="/icon/photo.svg" style="width: 15%; margin: 100px 0 20px 0;" alt="">
                            <p style="color: #929292">กดเพื่ออัพโหลดรูปภาพ (@{{ image.title }})</p>
                        </div>

                    </label>
                </div>
            </div>

            <div style="position: fixed; bottom: 0;">
                <div style="text-align: center;">
                    <p @click="store()" href="#" class="liff-btn" style="background-color: #3B3736; color: #FFD25A; font-weight: 500;">ส่ง</p>
                    <div style="width: 100vw; height: 30px; background-color: #3b3736;"></div>
                </div>

            </div>

        </div>


        @endsection

        @section('js')
            <script src="https://static.line-scdn.net/liff/edge/2.1/sdk.js"></script>
            <script src=" {{ mix('/js/vue/liff.js') }}"></script>
            {{--    <script src=" {{ mix('/js/vue/createQR.js') }}"></script>--}}
            {{--    <script src="/js/class/flex.js"></script>--}}
            <script src="/js/form/form.js"></script>
            <script src="/js/class/fastTrack.js"></script>

            <script>

                new Vue({
                    el: '#asset',

                    data: {
                        fast_track: new FastTrack(),

                        searchfield: null,
                        profile: null,
                        lineId: null,
                        user_id: null,
                        show: true,
                        loaded: false,
                        showLoading: true,
                        showSending: false,
                        showAlert: false,
                        hasVehicle: false,
                        hasCustomer: false,
                        hasReport: false,
                        index: 0,
                    },

                    watch: {
                        loaded() {
                            this.getProfile();
                        }
                    },


                    created() {
                        // this.getUserId('Ufacb069cbbe1593c753c043c903bfe8f');
                        this.init();
                        // this.fast_track.getInfo('U3094f16f5d2775edcaebca950e013091');
                        // this.showLoading = false;
                    },


                    mounted() {
                    },

                    methods: {
                        init() {
                            liff.init({
                                liffId: '1653575237-yDkDbm1r'
                            }).then(function (response) {
                                // console.log('here');
                                this.show = true;
                                this.loaded = true;
                            }.bind(this))
                        },
                        sendMessage() {

                            liff.sendMessages([{
                                'type': 'text',
                                'text': "You've successfully sent a message! Hooray!"
                            }]).then(function (profile) {
                                liff.closeWindow();
                            }).catch(function (error) {
                                notify('error', 'danger');
                                // window.alert('Error getting profile: ' + error);
                            });
                        },
                        login() {
                            liff.login();
                        },
                        selectTab(tab) {
                            this.tab = tab;
                            switch (tab) {
                                case 'create':
                                    this.tabName = 'Create';
                                    break;
                                case 'purchase':
                                    this.tabName = 'Purchase';
                                    break;
                                case 'product':
                                    this.tabName = 'My QR Code';
                                    break;
                            }
                        },
                        labelClass(tab) {
                            if (this.tab == tab) {
                                return 'label-active'
                            } else {
                                return 'label'
                            }
                        },
                        iconClass(tab) {
                            if (this.tab == tab) {
                                return tab + '-active'
                            } else {
                                return tab
                            }
                        },
                        getProfile() {
                            liff.getProfile()
                                .then(function (profile) {
                                    // this.profile = profile;
                                    // this.lineId = profile.userId;
                                    this.fast_track.getInfo(profile.userId);
                                    this.showLoading = false;

                                    // notify(this.lineId, 'success');
                                }.bind(this)).catch(function (error) {
                                notify('Please visit using LINE OA.', 'danger');
                                // window.alert('Error getting profile: ' + error);
                            });
                        },
                        previewImage: function (args, index) {
                            //Credit to Mani Jagadeesan https://jsfiddle.net/mani04/5zyozvx8/
                            var reader = new FileReader();
                            reader.onload = (e) => {
                                this.fast_track.imageData[index] = e.target.result;
                            };
                            reader.readAsDataURL(args);
                        },
                        handleFileUpload(index) {
                            this.fast_track.detail.image_array[index] = this.$refs[index][0].files[0];
                            this.previewImage(this.$refs[index][0].files[0], index);
                        },
                        selectVehicle(value) {
                            this.fast_track.detail.vehicle_id = value;
                            this.hasVehicle = true;
                        },
                        selectCustomer(value) {
                            this.fast_track.detail.customer_id = value;
                            this.fast_track.getReport(value);
                            this.hasCustomer = true;
                        },
                        selectReport(value) {
                            this.fast_track.detail.report_id = value;
                            this.fast_track.getReportDetail(value);
                            this.hasReport = true;
                        },
                        close() {
                            liff.closeWindow()
                        },
                        store() {
                            this.showSending = true;

                            this.fast_track.detail.postImageArray('/api/fastTrack', false)
                                .then(response => {
                                    // notify(response.message, 'success');


                                    this.showSending = false;

                                    setTimeout(function () {
                                        this.showAlert = true;
                                    }.bind(this), 3000);

                                    liff.sendMessages([
                                        response.data.flex
                                    ]);

                                    liff.shareTargetPicker([
                                        response.data.flex
                                    ])
                                        .catch(function (res) {
                                            console.log("Failed to launch ShareTargetPicker")
                                        });

                                    // liff.sendMessages([
                                    //     {
                                    //         type: 'text',
                                    //         text: 'ส่งรายงานสำเร็จ'
                                    //     }
                                    // ]);
                                    console.log(response.data.flex)
                                })
                                .catch(response => {
                                    this.showSending = false;
                                    console.log(response);
                                    notify('fail', 'danger');
                                });
                        }
                    },
                });
            </script>
@endsection
