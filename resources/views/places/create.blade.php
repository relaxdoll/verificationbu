@extends('layouts.app', ['activePage' => 'indexPlace', 'titlePage' => __('Create')])

@section('head_script')
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBfqPDkL6a4R1R7S6S3FOWV-l3C0pX7a_g&libraries=places&v=weekly"
    ></script>
@endsection

@section('content')
    <div v-show="mapOptions.center">
        <div style="display: flex; margin-bottom: 10px">
            <input v-model="address" type="text" ref="autocomplete" size="75" class="searchbox" :style="[validation ? {'border': '2px solid #c2000b'} : {} ]">
            <a @click="submit()" type="button" class="btn btn-sm btn-primary saveButton">Create</a>
        </div>
        <label v-show="validation" class="validation">This field is required</label>
        <div ref="map" style="height: 80%; width: 100%;"></div>
    </div>
@endsection

@push('js')

    <script src=" {{ mix('/js/vue/create.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                autocompleteOptions: {componentRestrictions: {country: 'th'}},
                mapOptions: {center: null, zoom: 15,},
                address: null,
                validation: null,

            },

            watch: {
                mapOptions: {
                    deep: true,

                    handler(val) {
                        this.initAutocomplete(val.center.lat, val.center.lng);
                    }
                },
                address() {
                    this.validation = false;
                },
            },

            created() {
                this.$store.dispatch('populateForm', {
                    'property': 'place',
                    'form': 'place',
                    'field': {
                        name: null,
                        lat: null,
                        lng: null,
                    }
                });
                this.$store.commit('loading', true);
                this.geoLocate();
            },

            computed: {},

            mounted() {
            },

            methods: {
                initAutocomplete(lat, lng) {
                    const map = new google.maps.Map(this.$refs.map, {center: {lat: lat, lng: lng}, zoom: 15});
                    const marker = new google.maps.Marker({
                        map,
                        anchorPoint: new google.maps.Point(0, -29),
                    });

                    const input = this.$refs.autocomplete;
                    const autocomplete = new google.maps.places.Autocomplete(input, this.autocompleteOptions);
                    autocomplete.setFields(["address_components", "geometry"]);
                    autocomplete.addListener('place_changed', () => {
                        marker.setVisible(false);
                        const place = autocomplete.getPlace();
                        this.updateForm(place, input.value);
                        if (place.geometry.viewport) {
                            map.fitBounds(place.geometry.viewport);
                        } else {
                            map.setCenter(place.geometry.location);
                            map.setZoom(17);
                        }
                        marker.setPosition(place.geometry.location);
                        marker.setVisible(true);
                    });
                    this.$store.commit('loading', false);
                },
                geoLocate() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition((position) => {
                            this.mapOptions.center = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                        });
                    }
                },
                updateForm(data, placeAddress) {
                    const placeNameIndex = placeAddress.search(',');
                    this.$store.commit('updateForm', {'form': 'place', 'field': 'name', 'value': placeAddress.substring(0, placeNameIndex)});
                    this.$store.commit('updateForm', {'form': 'place', 'field': 'lat', 'value': data.geometry.location.lat()});
                    this.$store.commit('updateForm', {'form': 'place', 'field': 'lng', 'value': data.geometry.location.lng()});
                },
                submit() {
                    if (this.address !== '' && this.address !== null) {
                        this.$store.dispatch('submit', {'form': 'place', 'url': '/api/place', 'reset': true})
                            .then(response => {
                                console.log(response);
                                Swal.fire('Complete!', 'Place has been successfully created.', 'success')
                            });
                    } else {
                        this.validation = true;
                    }
                },
            },
        });
    </script>

    <style scoped>
        input[type="text"]:focus {
            border: 2px solid #e14eca;
            outline: none;
        }

        .searchbox {
            font-size: 14px;
            background: #fff;
            border-radius: 8px;
            box-sizing: border-box;
            width: 100%;
            height: 48px;
            line-height: 48px;
            border: 1px solid transparent;
            padding: 0 10px;
            transition-property: background, box-shadow;
            transition-duration: 0.3s;
        }

        .saveButton {
            font-size: 14px;
            color: white !important;
            line-height: 38px;
            margin: 0 0 0 10px;
        }

        .validation {
            margin-bottom: 10px;
            margin-top: -10px;
            color: #ec250d;
            font-size: 12px;
        }
    </style>
@endpush
