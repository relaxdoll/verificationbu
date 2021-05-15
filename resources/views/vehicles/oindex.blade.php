@extends('layouts.app', ['activePage' => 'indexVehicle', 'titlePage' => __('Vehicle Index')])

@section('style')
    <link href="/css/vehicle.css" rel="stylesheet"/>
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            {{--<Drawer @close="vehicles.tireToggle()" align="right" :maskclosable="true"--}}
            {{--:closeable="true">--}}
            {{--<div v-if="vehicles.tire_drawer">contenasdffffffffffdfasdfjaldgjal;dfjhlaejrgl;iajdlgfjaledgjft here</div>--}}
            {{--</Drawer>--}}
            <div class="row">
                <transition name="slide-fade-long" mode="out-in">
                    <div :class="getWidth()" v-if="(!vehicles.selected_wheel)" key="1" style="transition: all .3s;">
                        <div class="card">
                            <div @click="forceFullScreen()" class="card-header card-header-primary">
                                <h4 class="card-title">Vehicles</h4>
                                <p class="card-category"> Total: @{{ vehicles.table.total() }}</p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class=" text-primary">
                                        <th style="cursor: pointer;" @click="vehicles.table.sort('license')"> License</th>
                                        <th style="cursor: pointer;" @click="vehicles.table.sort('fleet')"> Fleet</th>
                                        <th style="cursor: pointer;" @click=""> Mileage</th>
                                        <th style="cursor: pointer;" @click=""> Status</th>
                                        </thead>
                                        <tbody name="list" is="transition-group">
                                        <tr v-for="(vehicle, index) in vehicles.table.data" :key="vehicle.license"
                                            style="cursor: pointer;" @click="vehicles.selectVehicle(vehicle.id)"
                                            :class="getClass(vehicle.id)">
                                            <td class="text-primary"> @{{ vehicle.license }}</td>
                                            <td> @{{ vehicle.fleet }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
                <div :class="getDetailWidth()" v-if="vehicles.selected_id" style="transition: all 0.3s;">
                    <tabs :title="vehicles.detail.vehicle.license" style="position: sticky; top: 30px;">
                        <tab name="Tire" icon="album" :selected="true">
                            <inputbox label="Average Mileage" type="readonly"></inputbox>

                            <inputbox label="Cost per Km" type="readonly"></inputbox>

                            <div class="row" style="margin-top: 25px;">
                                <div v-if="(vehicles.number_of_wheel === 10)" style="margin: auto;">

                                    <div class="wheel-set">
                                        <span :class="getWheelClass(1)" @click.stop="vehicles.selectWheel(1)">1</span>
                                        <span :class="getWheelClass(2)" @click.stop="vehicles.selectWheel(2)">2</span>
                                    </div>

                                    <div class="wheel-set">
                                        <span :class="getWheelClass(3)" @click.stop="vehicles.selectWheel(3)">3</span>
                                        <span :class="getWheelClass(4)" @click.stop="vehicles.selectWheel(4)">4</span>
                                        <span :class="getWheelClass(5)" @click.stop="vehicles.selectWheel(5)">5</span>
                                        <span :class="getWheelClass(6)" @click.stop="vehicles.selectWheel(6)">6</span>
                                    </div>

                                    <div class="wheel-set">
                                        <span :class="getWheelClass(7)" @click.stop="vehicles.selectWheel(7)">7</span>
                                        <span :class="getWheelClass(8)" @click.stop="vehicles.selectWheel(8)">8</span>
                                        <span :class="getWheelClass(9)" @click.stop="vehicles.selectWheel(9)">9</span>
                                        <span :class="getWheelClass(10)" @click.stop="vehicles.selectWheel(10)">10</span>
                                    </div>
                                </div>

                                <div v-if="(vehicles.number_of_wheel === 8 || vehicles.number_of_wheel === 12)" style="margin: auto;">
                                    <div class="wheel-set">
                                        <span :class="getWheelClass(1)" @click.stop="vehicles.selectWheel(1)">1</span>
                                        <span :class="getWheelClass(2)" @click.stop="vehicles.selectWheel(2)">2</span>
                                        <span :class="getWheelClass(3)" @click.stop="vehicles.selectWheel(3)">3</span>
                                        <span :class="getWheelClass(4)" @click.stop="vehicles.selectWheel(4)">4</span>
                                    </div>

                                    <div class="wheel-set">
                                        <span :class="getWheelClass(5)" @click.stop="vehicles.selectWheel(5)">5</span>
                                        <span :class="getWheelClass(6)" @click.stop="vehicles.selectWheel(6)">6</span>
                                        <span :class="getWheelClass(7)" @click.stop="vehicles.selectWheel(7)">7</span>
                                        <span :class="getWheelClass(8)" @click.stop="vehicles.selectWheel(8)">8</span>
                                    </div>

                                    <div class="wheel-set" v-if="(vehicles.number_of_wheel === 12)">
                                        <span :class="getWheelClass(9)" @click.stop="vehicles.selectWheel(9)">9</span>
                                        <span :class="getWheelClass(10)" @click.stop="vehicles.selectWheel(10)">10</span>
                                        <span :class="getWheelClass(11)" @click.stop="vehicles.selectWheel(11)">11</span>
                                        <span :class="getWheelClass(12)" @click.stop="vehicles.selectWheel(12)">12</span>
                                    </div>
                                </div>

                            </div>
                        </tab>
                    </tabs>
                </div>
                <transition name="slide-fade-xlong" mode="in-out">
                    <div class="col-md-7" v-if="(vehicles.selected_wheel)" key="3">

                        <tabs title="Tire Summary">
                            <tab name="Current Tire" icon="save_alt" :selected="true">
                                <div v-if="vehicles.detail.tire[vehicles.selected_wheel]">

                                    <inputbox :value.sync="vehicles.detail.tire[vehicles.selected_wheel][0].date" label="Date Installed" type="readonly"></inputbox>

                                    <inputbox :value.sync="vehicles.detail.tire[vehicles.selected_wheel][0].serial" label="Serial" type="readonly"></inputbox>

                                    <inputbox :value.sync="vehicles.detail.tire[vehicles.selected_wheel][0].vendor.name" label="Vendor" type="readonly"></inputbox>

                                    <inputbox :value.sync="vehicles.detail.tire[vehicles.selected_wheel][0].brand.name" label="Brand" type="readonly"></inputbox>

                                    <inputbox :value.sync="vehicles.detail.tire[vehicles.selected_wheel][0].type.name" label="Type" type="readonly"></inputbox>

                                    <inputbox :value.sync="vehicles.detail.tire[vehicles.selected_wheel][0].treadDepth" label="Tread Depth"
                                              type="readonly"></inputbox>

                                    <inputbox label="Current Mileage" type="readonly"></inputbox>

                                </div>
                                <div v-show="!vehicles.detail.tire[vehicles.selected_wheel]">
                                    <inputbox :value.sync="oldTire.detail.serial" label="Serial" @input="oldTire.set('serial', $event)" type="text"
                                              :error="oldTire.detail.errors.has('serial')" :errorvalue="oldTire.detail.errors.get('serial')"
                                    ></inputbox>

                                    <inputbox :value.sync="oldTire.detail.vendor_id" label="Vendor" @input="oldTire.set('vendor_id', $event)"
                                              type="select" :option="oldTire.vendor" allowfilter="true" filtertype="contains"
                                              :error="oldTire.detail.errors.has('vendor_id')"
                                              :errorvalue="oldTire.detail.errors.get('vendor_id')"></inputbox>

                                    <inputbox :value.sync="oldTire.detail.brand_id" label="Brand" @input="oldTire.set('brand_id', $event)"
                                              type="select" :option="oldTire.brand" allowfilter="true" filtertype="contains"
                                              :error="oldTire.detail.errors.has('brand_id')"
                                              :errorvalue="oldTire.detail.errors.get('brand_id')"></inputbox>

                                    <inputbox :value.sync="oldTire.detail.type_id" label="Type" @input="oldTire.set('type_id', $event)"
                                              type="select" :option="oldTire.tireType" allowfilter="true" filtertype="contains"
                                              :error="oldTire.detail.errors.has('type_id')"
                                              :errorvalue="oldTire.detail.errors.get('type_id')"></inputbox>

                                    <inputbox :value.sync="oldTire.detail.dates" label="Date" @input="oldTire.set('date', $event)"
                                              type="date" :error="oldTire.detail.errors.has('date')" :errorvalue="oldTire.detail.errors.get('date')"
                                    ></inputbox>

                                    <inputbox :value.sync="oldTire.detail.treadDepth" label="Tread Depth" @input="oldTire.set('treadDepth', $event)"
                                              type="integer" :error="oldTire.detail.errors.has('treadDepth')" :errorvalue="oldTire.detail.errors.get('treadDepth')"
                                    ></inputbox>

                                    <inputbox :value.sync="oldTire.detail.mileage" label="Mileage" @input="oldTire.set('mileage', $event)"
                                              type="integer" :error="oldTire.detail.errors.has('mileage')" :errorvalue="oldTire.detail.errors.get('mileage')"
                                    ></inputbox>

                                    <div class="text-center">
                                        <a @click="oldTire.store()" href="#" class="btn btn-primary">{{ __('Save') }}</a>
                                    </div>
                                </div>
                            </tab>
                            <tab name="History" icon="restore">

                            </tab>
                        </tabs>

                    </div>
                </transition>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="/js/vue/index.js"></script>
    <script src="/js/form/form.js"></script>
    <script src="/js/class/vehicle.js"></script>
    <script src="/js/class/notification.js"></script>
    <script src="/js/class/oldTire.js"></script>


    <script>
        new Vue({
            el: '#asset',

            store,

            data: {
                vehicles: new Vehicle(),
                sortStatus: true,
                searchfield: null,

                oldTire: new OldTire(),
            },

            watch: {
                searchfield: function (newVal, oldVal) {
                    this.vehicles.table.filter(newVal);
                },

                vehicles: {
                    deep: true,

                    handler() {
                        if (this.vehicles.selected_wheel) {
                            if (!this.vehicles.detail.tire[this.vehicles.selected_wheel]) {
                                this.oldTire.detail.vehicle_id = this.vehicles.selected_id;
                                this.oldTire.detail.placement = this.vehicles.selected_wheel;

                                this.oldTire.replace.vehicle_id = this.vehicles.selected_id;
                                this.oldTire.replace.placement = this.vehicles.selected_wheel;
                            }
                        }
                    }
                },

                oldTire: {
                    deep: true,

                    handler() {
                        if (this.oldTire.save) {
                            this.oldTire.save = false;
                            this.oldTire.detail.fleet_id = 1;
                            this.oldTire.detail.storeType = 'single';
                            this.oldTire.getUser();
                            this.vehicles.getVehicle(this.vehicles.selected_id);
                        }
                    }
                }
            },

            mounted() {
                // this.vehicles.selectVehicle(63);
                // this.vehicles.selectWheel(4);

                this.oldTire.get('vendor', {optionlist: 'th'});
                this.oldTire.get('brand');
                this.oldTire.get('tireType');
            },

            methods: {
                forceFullScreen(){
                    var elem = document.documentElement;
                    elem.requestFullscreen();
                    elem.mozRequestFullScreen();
                    elem.webkitRequestFullscreen();
                    elem.msRequestFullscreen();
                },
                getClass(id) {
                    if (this.vehicles.selected_id === id) {
                        return 'tr-active';
                    }
                },
                getWidth() {
                    if (this.vehicles.selected_id) {
                        return 'col-md-3';
                    } else {
                        return 'col-md-12';
                    }
                },
                getDetailWidth() {
                    if (this.vehicles.selected_wheel) {
                        return 'col-md-5';
                    } else {
                        return 'col-md-9';
                    }
                },
                getWheelClass(wheel) {
                    let wheelClass = '';

                    if (this.vehicles.selected_wheel === wheel) {
                        wheelClass += 'wheel-selected ';
                    }

                    switch (this.vehicles.number_of_wheel) {
                        case 10:
                            switch (wheel) {
                                case 1:
                                    wheelClass += 'wheel wheel-left wheel-first';
                                    break;
                                case 2:
                                    wheelClass += 'wheel wheel-right-first';
                                    break;
                                case 3:
                                case 4:
                                case 6:
                                case 7:
                                case 8:
                                case 10:
                                    wheelClass += 'wheel wheel-left';
                                    break;
                                case 5:
                                case 9:
                                    wheelClass += 'wheel wheel-right';
                                    break;
                            }
                            break;
                        case 8:
                        case 12:
                            switch (wheel) {
                                case 1:
                                case 2:
                                case 4:
                                case 5:
                                case 6:
                                case 8:
                                case 10:
                                case 9:
                                case 12:
                                    wheelClass += 'wheel wheel-left';
                                    break;
                                case 11:
                                case 3:
                                case 7:
                                    wheelClass += 'wheel wheel-right';
                                    break;
                            }
                            break;
                        default:
                    }

                    if (!this.vehicles.detail.tire[wheel]) {
                        wheelClass += ' wheel-error';
                    }

                    return wheelClass;
                }
            },
        });
    </script>
@endpush
