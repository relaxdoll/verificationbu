@extends('layouts.app', ['activePage' => 'createReplaceTire', 'titlePage' => __('Replace Tire')])

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

        .list-enter-active, .list-leave-active {
            transition: all 0.5s;
        }

        .list-enter, .list-leave-to {
            opacity: 0;
            transform: translateX(30px);
        }

        .list-move {
            transition: transform 0.5s;
        }

        .tr-active {
            background-color: #e3f2fd;
            border: 2px solid #9acffa;
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
                                            <li class="nav-item" v-if="replacement.order.fleet_id == 1 || replacement.tap == 1">
                                                <a :class="(replacement.order.fleet_id == 1)? 'active nav-link' : 'nav-link'" @click="replacement.set('fleet_id', 1)">
                                                    <i class="material-icons">local_shipping</i> Mapkha
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                        </transition>
                                        <transition name="slide-fade" mode="out-in">
                                            <li class="nav-item" v-if="replacement.order.fleet_id == 2 || replacement.tap == 1" key="1">
                                                <a :class="(replacement.order.fleet_id == 2)? 'active nav-link' : 'nav-link'" @click="replacement.set('fleet_id', 2)">
                                                    <i class="material-icons">whatshot</i> Suksawat
                                                    <div class="ripple-container"></div>
                                                </a>
                                            </li>
                                        </transition>
                                        <transition name="slide-fade" mode="out-in">
                                            <li class="nav-item" v-if="replacement.order.fleet_id == 3 || replacement.tap == 1">
                                                <a :class="(replacement.order.fleet_id == 3)? 'active nav-link' : 'nav-link'" @click="replacement.set('fleet_id', 3)">
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
                                <transition name="slide-fade-reverse-long" mode="out-in">
                                    <div v-show="replacement.tap == 1" key="4" :class="(replacement.tap == 1)? 'tab-pane active' : 'tab-pane'" style="padding: 15px 0;">

                                        <inputbox :value.sync="replacement.order.vehicle_id" label="Vehicle" @input="replacement.set('vehicle_id', $event)" type="select"
                                                  :option="replacement.vehicleList"
                                                  allowfilter="true" filtertype="contains" :error="replacement.order.errors.has('vehicle_id')"
                                                  :errorvalue="replacement.order.errors.get('vehicle_id')"></inputbox>

                                        <inputbox :value.sync="replacement.order.placement" label="Placement" @input="replacement.set('placement', $event)" type="select"
                                                  :option="replacement.placement"
                                                  allowfilter="true" filtertype="contains" :error="replacement.order.errors.has('placement')"
                                                  :errorvalue="replacement.order.errors.get('placement')"></inputbox>

                                        {{--<inputbox :value.sync="replacement.order.treadDepth" label="Tread Depth" @input="replacement.set('treadDepth', $event)"--}}
                                        {{--type="integer"--}}
                                        {{--:error="replacement.order.errors.has('treadDepth')" :errorvalue="replacement.order.errors.get('treadDepth')"></inputbox>--}}

                                        {{--<inputbox :value.sync="replacement.order.price" label="Price" @input="replacement.set('price', $event)" type="currency"--}}
                                        {{--decimal="2"--}}
                                        {{--:error="replacement.order.errors.has('price')" :errorvalue="replacement.order.errors.get('price')"></inputbox>--}}

                                        {{--<inputbox :value.sync="replacement.order.amount" label="Amount" @input="replacement.set('amount', $event)" type="integer"--}}
                                        {{--:error="replacement.order.errors.has('amount')" :errorvalue="replacement.order.errors.get('amount')"></inputbox>--}}

                                        <div class="col-md-12 text-center" v-if="(replacement.order.placement)">
                                            <a @click="replacement.setOldTire()" href="#" class="btn btn-primary">{{ __('Next') }}</a>
                                        </div>
                                    </div>

                                </transition>
                                <transition name="slide-fade-long" mode="out-in">
                                    <div v-if="replacement.tap == 2" key="3" :class="(replacement.tap == 2)? 'tab-pane active' : 'tab-pane'"
                                         style="padding: 15px 0;">

                                        <inputbox :value.sync="replacement.oldData.serial" label="Serial (Old)"
                                                  @input="replacement.setOld('serial', $event)" :type="(this.replacement.hasOldTire)?'disabled':'text'"
                                                  :error="replacement.oldData.errors.has('serial')"
                                                  :errorvalue="replacement.oldData.errors.get('serial')"
                                        ></inputbox>

                                        <inputbox :value.sync="replacement.oldData.vendor_id" label="Vendor (Old)" @input="replacement.setOld('vendor_id', $event)"
                                                  type="select"
                                                  :option="replacement.vendor" :enable="!this.replacement.hasOldTire"
                                                  allowfilter="true" filtertype="contains" :error="replacement.oldData.errors.has('vendor_id')"
                                                  :errorvalue="replacement.oldData.errors.get('vendor_id')"></inputbox>

                                        <inputbox :value.sync="replacement.oldData.brand_id" label="Brand (Old)" @input="replacement.setOld('brand_id', $event)"
                                                  type="select"
                                                  :option="replacement.brand" :enable="!this.replacement.hasOldTire"
                                                  allowfilter="true" filtertype="contains" :error="replacement.oldData.errors.has('brand_id')"
                                                  :errorvalue="replacement.oldData.errors.get('brand_id')"></inputbox>

                                        <inputbox :value.sync="replacement.oldData.type_id" label="Type (Old)" @input="replacement.setOld('type_id', $event)"
                                                  type="select"
                                                  :option="replacement.tireType" :enable="!this.replacement.hasOldTire"
                                                  allowfilter="true" filtertype="contains" :error="replacement.oldData.errors.has('type_id')"
                                                  :errorvalue="replacement.oldData.errors.get('type_id')"></inputbox>

                                        <inputbox :value.sync="replacement.oldData.treadDepth" label="Tread Depth (Old)" @input="replacement.setOld('treadDepth', $event)"
                                                  :type="(this.replacement.hasOldTire)?'disabled':'integer'"
                                                  :error="replacement.oldData.errors.has('treadDepth')" :errorvalue="replacement.oldData.errors.get('treadDepth')"
                                        ></inputbox>


                                        <div class="col-md-12 text-center">
                                            <a @click="replacement.selectTire()" href="#" class="btn btn-primary">{{ __('Back') }}</a>
                                            <a @click="replacement.setReplace()" href="#" class="btn btn-primary">{{ __('Next') }}</a>
                                        </div>
                                    </div>
                                </transition>
                                <transition name="slide-fade-long" mode="out-in">
                                    <div v-if="replacement.tap == 3" key="4" :class="(replacement.tap == 3)? 'tab-pane active' : 'tab-pane'"
                                         style="padding: 15px 0;">

                                        <inputbox :value.sync="replacement.order.dates" label="Date" @input="replacement.set('date', $event)" type="date"
                                                  :error="replacement.order.errors.has('date')" :errorvalue="replacement.order.errors.get('date')"></inputbox>

                                        <inputbox :value.sync="replacement.oldData.mileage_current" label="Mileage"
                                                  @input="replacement.setOld('mileage_current', $event)" type="decimal" decimal="1"
                                                  :error="replacement.oldData.errors.has('mileage_current')"
                                                  :errorvalue="replacement.oldData.errors.get('mileage_current')"
                                        ></inputbox>

                                        <inputbox :value.sync="replacement.oldData.currentTreadDepth" label="Current Tread Depth (Old)"
                                                  @input="replacement.setOld('currentTreadDepth', $event)"
                                                  type="integer"
                                                  :error="replacement.oldData.errors.has('currentTreadDepth')"
                                                  :errorvalue="replacement.oldData.errors.get('currentTreadDepth')"
                                        ></inputbox>

                                        <inputbox :value.sync="replacement.oldData.reason_id" label="Reason" @input="replacement.set('reason_id', $event)"
                                                  type="select" :option="replacement.reason" allowfilter="true" filtertype="contains"
                                                  :error="replacement.oldData.errors.has('reason_id')"
                                                  :errorvalue="replacement.oldData.errors.get('reason_id')"></inputbox>


                                        <div class="row" style="margin-top: 15px;">
                                            <div class="col-md-12">
                                                <div class="card card-plain">
                                                    <div class="card-header card-header-primary">
                                                        <h4 class="card-title mt-0">Tire Inventory</h4>
                                                        <p class="card-category"> In stock: @{{ inventory.inStock() }}</p>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-hover">
                                                                <thead class=" text-primary">
                                                                <th> No</th>
                                                                <th style="cursor: pointer;" @click="inventory.sort('serial')"> Serial</th>
                                                                <th style="cursor: pointer;" @click="inventory.sort('brand')"> Brand</th>
                                                                <th style="cursor: pointer;" @click="inventory.sort('type')"> Type</th>
                                                                <th style="cursor: pointer;" @click="inventory.sort('treadDepth')"> Depth</th>
                                                                <th style="cursor: pointer;" @click="inventory.sort('date')"> Date</th>
                                                                </thead>
                                                                <tbody name="list" is="transition-group">
                                                                <tr v-for="(stock, index) in inventory.data" :key="stock.id"
                                                                    @click="replacement.set('replace_id', stock.id)" style="cursor: pointer;"
                                                                    :class="getClass(stock.id)">
                                                                    <td> @{{ index + 1 }}</td>
                                                                    <td class="text-primary"> @{{ stock.serial }}</td>
                                                                    <td> @{{ stock.brand }}</td>
                                                                    <td> @{{ stock.type }}</td>
                                                                    <td> @{{ stock.treadDepth }}</td>
                                                                    <td> @{{ stock.date }}</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 text-center">
                                            <a @click="replacement.selectTire()" href="#" class="btn btn-primary">{{ __('Back') }}</a>
                                            <a @click="replacement.save()" href="#" class="btn btn-primary">{{ __('Save') }}</a>
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
    <script src="/js/class/replaceTire.js"></script>
    <script src="/js/class/inventory.js"></script>
    <script src="/js/class/notification.js"></script>


    <script>

        new Vue({
            el: '#asset',

            data: {
                replacement: new ReplaceTire(),
                inventory: new Table(),
                searchfield: null,
            },

            watch: {
                searchfield: function (newVal, oldVal) {
                    this.inventory.filter(newVal);
                }
            },

            mounted() {
                this.replacement.get('vehicle', {'where': {'isActive': 1}, 'orderBy': {'license': 'asc'}});
                this.replacement.get('vehicleList', {'where': {'fleet_id': 1}, 'orderBy': {'license': 'asc'}}, 'vehicle/get/list');
                this.replacement.get('vendor', {optionlist: 'th'});
                this.replacement.get('brand');
                this.replacement.get('tireType');
                this.replacement.get('reason');
            },

            methods: {

                getClass(id) {
                    if (this.replacement.order.replace_id === id) {
                        return 'tr-active';
                    }
                }

            },
        });
    </script>
@endpush