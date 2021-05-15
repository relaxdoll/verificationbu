@extends('layouts.app', ['activePage' => 'indexLineGroup', 'titlePage' => __('Add Group')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <liffloader :show="showSending" text="Initiating.."></liffloader>
    <liffloader :show="showConfirming" text="Confirming.."></liffloader>

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'LINE','href':'#'}, {'text':'Group','href':'./'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="./"></topbutton>

        <wizard :allowsubmit="allowSubmit" @submit="submit" title="Create Line Group" description="Follow the process to add a new LINE Group to our system.">
            <wizard-tab name="fleet" icon="tim-icons icon-delivery-fast">
                <h5 class="info-text"> Pick the fleet for this group.</h5>

                <v-form name="lineGroup">
                    <pill-input placeholder="" field="fleet_id" :options="fleetData"
                                :vparam="['required']">
                    </pill-input>
                </v-form>

            </wizard-tab>
            <wizard-tab name="link" icon="tim-icons icon-link-72">

                <h5 class="info-text"> Add 'EECL Robot' to LINE group then search for the group name.</h5>
                <v-form name="lineGroup" class="row justify-content-center mt-5">
                    <div class="col-sm-10">
                        <base-input placeholder="Search Group Name" field="search" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-10">
                        <div style="text-align: center; margin:15px 0;">
                            <base-button round type="info" @click.native="searchGroup" class="btn-previous">
                                Search
                            </base-button>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <select-box field="group" placeholder="Select Group" type="select" :custominput="true" @input="groupSelected($event)" v-if="showSelect"
                                    :forceoption="options" optiontext="text" optionvalue="value" addon-left-icon="tim-icons icon-chat-33"
                                    allowfilter="true" filtertype="contains" :vparam="[]"></select-box>
                    </div>
                </v-form>
            </wizard-tab>
            <wizard-tab name="about" icon="tim-icons icon-istanbul">
                <h5 class="info-text"> Now, add 'EECLINE' to LINE group then confirm this group link.</h5>

                <v-form name="lineGroup" class="row justify-content-center mt-5">
                    <div class="card card-user col-md-4 col-sm-10">
                        <div class="card-body">
                            <div class="card-text">
                                <div class="author">
                                    <div class="block block-one"></div>
                                    <div class="block block-two"></div>
                                    <div class="block block-three"></div>
                                    <div class="block block-four"></div>
                                    <a>
                                        <img class="avatar" :src="forms.lineGroup.avatar">
                                        <h5 class="title">Group</h5>
                                    </a>
                                    <p class="description">
                                        @{{ forms.lineGroup.name }}
                                    </p>
                                </div>
                            </div>
                            <div class="card-description">
                                Reference: @{{ reference }}
                            </div>
                            <div style="text-align: center; margin:25px 0;">
                                <base-button round type="info" @click.native="shareMessage" class="btn-previous">
                                    Confirm
                                </base-button>
                            </div>
                        </div>
                    </div>
                </v-form>
            </wizard-tab>
        </wizard>
    </div>


@endsection

@push('js')
    <script src=" {{ mix('/js/vue/create.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                fleetData: [{'text': 'Mapkha', 'value': 1}, {'text': 'Laem Chabang', 'value': 3}, {'text': 'Suksawat', 'value': 2}],
                showSending: false,
                showConfirming: false,
                initScrap: false,
                options: [],
                showSelect: false,
                reference: null,
                allowSubmit: false,
            },

            created() {
                this.$store.dispatch('populateForm', {
                    'name': 'lineGroup',
                    'field': {
                        search: null,
                        name: null,
                        fleet_id: null,
                        avatar: null,
                        type: null,
                        user: this.$store.state.user,
                    }
                });
                window.addEventListener('beforeunload', this.closeScrapper);
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms'
                ]),
            },

            methods: {
                groupSelected(value) {
                    this.$store.commit('updateForm', {'form': 'lineGroup', 'field': 'name', 'value': this.options[value].text});
                    this.$store.commit('updateForm', {'form': 'lineGroup', 'field': 'avatar', 'value': this.options[value].avatar});
                },
                searchGroup() {

                    if (!this.initScrap) {
                        this.$store.commit('loading', true);
                        this.showSending = true;
                        this.initScrap = true;
                        this.$store.dispatch('submitWithoutLoading', {'form': 'lineGroup', 'url': '/api/line/group/post/initScrapGroup'})
                            .then(response => {
                                this.getGroupName();
                                console.log(response);
                            });
                    } else {
                        this.$store.dispatch('submit', {'form': 'lineGroup', 'url': '/api/line/group/post/editGroupName'})
                            .then(response => {
                                this.getGroupName();
                                console.log(response);
                            });
                    }

                },
                shareMessage() {

                    this.$store.dispatch('submitWithoutLoading', {'form': 'lineGroup', 'url': '/api/line/group/post/shareMessage'})
                        .then(response => {
                            this.initScrap = false;
                            this.getGroupId();
                            console.log(response);
                        });

                },
                getGroupName() {
                    this.showSelect = false;
                    this.$store.commit('updateForm', {'form': 'lineGroup', 'field': 'group', 'value': null});
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get('/api/line/group/get/group/' + this.$store.state.user.id)
                            .then(response => {
                                if (!response.data.data.updated) {
                                    setTimeout(async function () {
                                        await this.getGroupName();
                                    }.bind(this), 1000);
                                    console.log(response.data);
                                } else {
                                    this.$store.commit('loading', false);
                                    this.showSending = false;
                                    this.showSelect = true;
                                    this.options = response.data.data.options;
                                    this.reference = response.data.data.code;
                                    console.log(response.data);
                                }
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });

                    });
                },
                getGroupId() {
                    this.showConfirming = true;
                    this.$store.commit('loading', true);
                    return new Promise((resolve, reject) => {
                        axios.get('/api/line/group/get/getGroupId/' + this.reference)
                            .then(response => {
                                if (!response.data.data) {
                                    setTimeout(async function () {
                                        await this.getGroupId();
                                    }.bind(this), 500);
                                    console.log(response.data);
                                } else {
                                    this.$store.commit('loading', false);
                                    this.showConfirming = false;
                                    this.$store.commit('updateForm', {'form': 'lineGroup', 'field': 'lineId', 'value': response.data.data});
                                    this.allowSubmit = true;
                                    console.log(response.data);
                                }
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });

                    });

                },
                closeScrapper() {
                    return new Promise((resolve, reject) => {
                        axios.get('/api/line/group/get/closeScrapper/' + this.$store.state.user.id)
                            .then(response => {
                                this.initScrap = true;
                                resolve(response.data);
                            })
                    });
                },
                submit() {
                    this.$store.dispatch('submit', {'form': 'lineGroup', 'url': '/api/line/group', 'reset': true})
                        .then(response => {
                            Swal.fire({
                                title: 'Complete!',
                                text: "Line Group has been successfully created.",
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result) {
                                    window.location.href = "./"
                                }
                            });
                        });
                }
            },
        });

    </script>
@endpush
