@extends('layouts.app', ['activePage' => 'indexLocation', 'titlePage' => __('Location Service')])

@section('style')
    <style>
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

    </style>
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Service List</h4>
                            <p class="card-category"> Total: @{{ locations.inStock() }}</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 text-right">
                                    <a href="{{ route('location.create') }}" class="btn btn-sm btn-primary">{{ __('Import Job') }}</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class=" text-primary">
                                    <th> No</th>
                                    <th style="cursor: pointer;" @click="locations.sort('vehicle')"> Vehicle License</th>
                                    <th style="cursor: pointer;" @click="locations.sort('fleet')"> Fleet</th>
                                    <th style="cursor: pointer;" @click="locations.sort('customer')"> Customer</th>
                                    <th style="cursor: pointer;" @click="locations.sort('user')"> User Set</th>
                                    <th class="text-right" style="cursor: pointer;"> Action</th>
                                    </thead>
                                    <tbody name="list" is="transition-group">
                                    <tr v-for="(location, index) in locations.data" :key="location.vehicle">
                                        <td> @{{ index + 1 }}</td>
                                        <td class="text-primary"> @{{ location.vehicle }}</td>
                                        <td> @{{ location.fleet }}</td>
                                        <td> @{{ location.customer }}</td>
                                        <td> @{{ location.user }}</td>
                                        <td class="td-actions text-right">

                                            <a rel="tooltip" class="btn btn-success btn-link" @click="sendLocation(location.customer_id)" data-original-title="" title="">
                                                <i class="material-icons">near_me</i>
                                                <div class="ripple-container"></div>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-link" data-original-title="" title=""
                                                    @click="deleteLocation(location.id)">
                                                <i class="material-icons">close</i>
                                                <div class="ripple-container"></div>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('js')
    <script src="/js/vue/index.js"></script>
    <script src="/js/form/form.js"></script>
    <script src="/js/class/location.js"></script>
    <script src="/js/class/notification.js"></script>


    <script>

        new Vue({
            el: '#asset',

            data: {
                locations: new Table(),
                sortStatus: true,
                searchfield: null
            },

            watch: {
                searchfield: function (newVal, oldVal) {
                    this.locations.filter(newVal);
                }
            },


            mounted() {
            },

            methods: {
                sendLocation(customer_id) {
                    return new Promise((resolve, reject) => {
                        axios.get('/api/location/get/sendOne', {
                            params: {
                                'where': {'customer_id': customer_id},
                            },
                            paramsSerializer: params => {
                                return qs.stringify(params)
                            }
                        })
                            .then(response => {
                                notify('Location sent successfully', 'success');
                                console.log(response.data);
                                resolve(response.data);
                            })
                            .catch(error => {
                                console.log(error);
                                reject(error.response);
                            });
                    });

                },
                deleteLocation(id) {
                    if (confirm('{{ __("Are you sure you want to delete this service?") }}')) {
                        return new Promise((resolve, reject) => {
                            axios.delete('/api/location/' + id)
                                .then(response => {
                                    notify('Service deleted successfully', 'warning');
                                    this.locations.get();
                                    console.log(response.data);
                                    resolve(response.data);
                                })
                                .catch(error => {
                                    console.log(error);
                                    reject(error.response);
                                });
                        });

                    } else {
                        console.log('no');
                    }
                }
            },
        });
    </script>
@endpush
