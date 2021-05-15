@extends('layouts.app', ['activePage' => 'indexLocation', 'titlePage' => __('Import Job')])

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
                                                <a :class="(customer.detail.fleet_id == 1)? 'active nav-link' : 'nav-link'" @click="customer.detail.fleet_id = 1">
                                                    <i class="material-icons">local_shipping</i> Mapkha
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                        </transition>
                                        <transition name="slide-fade" mode="out-in">
                                            <li class="nav-item">
                                                <a :class="(customer.detail.fleet_id == 2)? 'active nav-link' : 'nav-link'" @click="customer.detail.fleet_id = 2">
                                                    <i class="material-icons">whatshot</i> Suksawat
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                        </transition>
                                        <transition name="slide-fade" mode="out-in">
                                            <li class="nav-item">
                                                <a :class="(customer.detail.fleet_id == 3)? 'active nav-link' : 'nav-link'" @click="customer.detail.fleet_id = 3">
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
                                        <a href="{{ route('location.index') }}" class="btn btn-sm btn-primary">{{ __('Back to list') }}</a>
                                    </div>
                                </div>
                                <transition name="slide-fade-reverse-long" mode="out-in">
                                    <div style="padding: 15px 0;">

                                        <inputbox label="File (csv)" @input="customer.set('file', $event)" type="file"
                                                  :error="customer.detail.errors.has('file')" :errorvalue="customer.detail.errors.get('file')"></inputbox>

                                        <div class="col-md-12 text-center">
                                            <a @click="customer.store()" href="#" class="btn btn-primary">{{ __('Import') }}</a>
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
    <script src="/js/vue/addCheque.js"></script>
    <script src="/js/form/form.js"></script>
    <script src="/js/class/location.js"></script>
    <script src="/js/class/notification.js"></script>


    <script>

        new Vue({
            el: '#asset',

            data: {
                customer: new Customer(),
                searchfield: null,
            },

            watch: {
                customer: {
                    deep: true,

                    handler() {
                        if (this.customer.isImported) {
                            setTimeout(function () {
                                window.location.href = "/location";
                            }, 500);
                        }
                    }
                }
            },

            mounted() {
                this.customer.get('line');
            },

            methods: {},
        });
    </script>
@endpush
