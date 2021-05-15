@extends('layouts.app', ['activePage' => 'indexPhotoReport', 'titlePage' => __('Add Fast Track')])

@section('style')
    <style>
        .slide-fade-enter-active, .slide-fade-long-enter-active, .slide-fade-reverse-long-enter-active {
            transition: all .3s ease;
        }

        .slide-fade-leave-active {
            transition: all .3s cubic-bezier(0.5, 0.5, 0.8, 1.0);
        }

        .slide-fade-enter, .slide-fade-leave-to
            /* .slide-fade-leave-active below version 2.1.8 */
        {
            transform: translateX(-20px);
        }

        .slide-fade-long-enter
            /* .slide-fade-leave-active below version 2.1.8 */
        {
            transform: translateX(-200px);
        }

        .slide-fade-reverse-long-enter
            /* .slide-fade-leave-active below version 2.1.8 */
        {
            transform: translateX(200px);
        }

    </style>
@endsection
@section('content')
    <div id="asset" class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xxl-8 col-xl-10 col-md-12">
                    <div class="card">
                        <div class="card-header card-header-tabs card-header-primary">
                            <div class="nav-tabs-navigation">
                                <div class="nav-tabs-wrapper">
                                    <span class="nav-tabs-title">Fleet:</span>
                                    <ul class="nav nav-tabs">
                                        <transition name="slide-fade" mode="out-in">
                                            <li class="nav-item">
                                                <a :class="(model.detail.fleet_id == 1)? 'active nav-link' : 'nav-link'" @click="model.detail.fleet_id = 1">
                                                    <i class="material-icons">local_shipping</i> Mapkha
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                        </transition>
                                        <transition name="slide-fade" mode="out-in">
                                            <li class="nav-item">
                                                <a :class="(model.detail.fleet_id == 2)? 'active nav-link' : 'nav-link'" @click="model.detail.fleet_id = 2">
                                                    <i class="material-icons">whatshot</i> Suksawat
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                        </transition>
                                        <transition name="slide-fade" mode="out-in">
                                            <li class="nav-item">
                                                <a :class="(model.detail.fleet_id == 3)? 'active nav-link' : 'nav-link'" @click="model.detail.fleet_id = 3">
                                                    <i class="material-icons">business</i> Laem Chabang
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                        </transition>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <a href="{{ route('lineReport.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                                    </div>
                                </div>
                                <transition name="slide-fade-reverse-long" mode="out-in">
                                    <div style="padding: 15px 0;" v-show="tab ===1" :class="(tab == 1)? 'tab-pane active' : 'tab-pane'">

                                        <inputbox :value.sync="model.detail.title" label="Report Title" @input="model.set('title', $event)" type="text"
                                                  :error="model.detail.errors.has('title')" :errorvalue="model.detail.errors.get('title')"></inputbox>

                                        <inputbox :value.sync="model.detail.description" label="Description" @input="model.set('description', $event)" type="text"
                                                  :error="model.detail.errors.has('description')" :errorvalue="model.detail.errors.get('description')"></inputbox>

                                        <inputbox :value.sync="model.detail.image_number" label="Image No." @input="model.setImageNumber($event)" type="integer"
                                                  :error="model.detail.errors.has('image_number')" :errorvalue="model.detail.errors.get('image_number')"></inputbox>

                                        <inputbox :value.sync="model.detail.type" label="Type" @input="model.set('type', $event)" type="select"
                                                  :option="model.type" optiontext="text" optionvalue="value"
                                                  allowfilter="true" filtertype="contains" :error="model.detail.errors.has('type')"
                                                  :errorvalue="model.detail.errors.get('type')"></inputbox>

                                        <inputbox :value.sync="model.detail.customer_id" label="Customer(s)" @input="model.set('customer_id', $event)" type="multiselect"
                                                  :option="model.customer" optiontext="name" optionvalue="id" optiongroup="fleet"
                                                  allowfilter="true" filtertype="contains" :error="model.detail.errors.has('customer_id')"
                                                  :errorvalue="model.detail.errors.get('customer_id')"></inputbox>

                                        <div class="col-md-12 text-center">
                                            <a v-if="!model.detail.image_number" @click="model.store()" href="#" class="btn btn-primary">{{ __('Save') }}</a>
                                            <a v-else @click="tab++" href="#" class="btn btn-primary">{{ __('Next') }}</a>
                                        </div>
                                    </div>

                                </transition>
                                <transition name="slide-fade-reverse-long" mode="out-in">
                                    <div style="padding: 15px 0;" v-if="tab ===2" :class="(tab == 2)? 'tab-pane active' : 'tab-pane'">

                                        <inputbox v-for="index in model.detail.image_number" :key="index" :value.sync="model.detail.image_titles[index].title"
                                                  :label="'Image Title (' +index+')'"
                                                  @input="model.detail.image_titles[index].title = $event" type="text"
                                            {{--                                                  :error="model.detail.errors.has('title')" :errorvalue="model.detail.errors.get('title')"--}}
                                        ></inputbox>


                                        <div class="col-md-12 text-center">
                                            <a @click="tab--" href="#" class="btn btn-primary">{{ __('Back') }}</a>
                                            <a @click="store()" href="#" class="btn btn-primary">{{ __('Save') }}</a>
                                        </div>
                                    </div>

                                </transition>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script src=" {{ mix('/js/vue/create.js') }}"></script>
{{--    <script src="/js/vue/addCheque.js"></script>--}}
    <script src="/js/form/form.js"></script>
    <script src="/js/class/lineReport.js"></script>
{{--    <script src="/js/class/notification.js"></script>--}}


    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                model: new LineReport(),
                searchfield: null,
                tab: 1,

            },

            computed: {
                ...mapState([
                    'theme',
                ]),
            },

            watch: {},

            created() {
                this.model.get('customer');
            },

            methods: {
                store() {
                    this.model.store();
                    this.tab = 1;
                }
            },
        });
    </script>
@endpush
