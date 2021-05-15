<template>

    <div v-if="rowSelected" class="col-md-9">
        <div class="card card-user" :class="{'sticky': pin}">
            <div class="card-body">

                <div class="tools float-right">
                    <a @click="pin = !pin" type="button" class="btn btn-icon" :class="{'btn-info':pin}" style="z-index: 2; color: white;">
                        <!--            <button @click="show = !show" type="button" class="btn btn-default dropdown-toggle btn-link btn-icon">-->
                        <i class="tim-icons icon-pin"></i>
                    </a>
                </div>
                <div class="card-text">
                    <div class="author">
                        <div class="block block-one"></div>
                        <div class="block block-two"></div>
                        <div class="block block-three"></div>
                        <div class="block block-four"></div>
                        <a>
                            <img class="avatar" style="margin-left: 38px;" :src="datum.avatar">
                            <h5 class="title"> {{ datum.name}}</h5>
                        </a>
                    </div>
                </div>
                <div class="chart-area">
                    <h5 class="card-category">Monthly Image Track</h5>
                    <h3 class="card-title">
                        <i class="tim-icons icon-shape-star text-info "></i> {{sum}}
                    </h3>
                    <line-chart
                        :chart-data="lineChart1.chartData"
                        :gradient-colors="lineChart1.gradientColors"
                        :gradient-stops="lineChart1.gradientStops"
                        :extra-options="lineChart1.extraOptions"
                        :height="200"
                    >
                    </line-chart>
                </div>
                <div class="chart-area" style="margin-top: 15px;">
                    <h5 class="card-category">Monthly Fuel Consumption</h5>
                    <h3 class="card-title">
                        <i class="eec-icons icon-fuel text-danger "></i> {{average}}
                    </h3>
                    <line-chart
                        :chart-data="lineChart2.chartData"
                        :gradient-colors="lineChart1.gradientColors"
                        :gradient-stops="lineChart1.gradientStops"
                        :extra-options="lineChart1.extraOptions"
                        :height="200"
                    >
                    </line-chart>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        components: {
            LineChart,
        },

        props: {},
        data() {
            return {
                lineChart1: {},
                lineChart2: {},
                pin: true,
                sum: 0,
                average: 0,
            }
        },
        created() {
        },
        watch: {

            datum(value) {
                this.sum = this.datum.image_tracks.graph.byDay.y_axis.reduce(function (a, b) {
                    return a + b;
                }, 0);
                // this.average = (array) => this.datum.refuel.graph.y.reduce((a, b) => a + b) / array.length;

                let total = 0;
                for (let i = 0; i < this.datum.refuel.graph.y.length; i++) {
                    total += parseFloat(this.datum.refuel.graph.y[i]);
                }

                this.average = Math.round(total / this.datum.refuel.graph.y.length * 10000) / 10000;

                this.lineChart1 = {
                    chartData: {
                        labels: this.datum.image_tracks.graph.byDay.x_axis,
                        datasets: [
                            {
                                label: 'Data',
                                fill: true,
                                borderColor: '#42b883',
                                borderWidth: 2,
                                borderDash: [],
                                borderDashOffset: 0.0,
                                pointBackgroundColor: '#42b883',
                                pointBorderColor: 'rgba(255,255,255,0)',
                                pointHoverBackgroundColor: '#be55ed',
                                pointBorderWidth: 20,
                                pointHoverRadius: 4,
                                pointHoverBorderWidth: 15,
                                pointRadius: 4,
                                data: this.datum.image_tracks.graph.byDay.y_axis
                            }
                        ]
                    },
                    extraOptions: chartConfigs.purpleChartOptions,
                    gradientColors: [
                        'rgba(76, 211, 150, 0.1)',
                        'rgba(53, 183, 125, 0)',
                        'rgba(119,52,169,0)'
                    ],
                    gradientStops: [1, 0.4, 0]
                };

                this.lineChart2 = {
                    chartData: {
                        labels: this.datum.refuel.graph.x,
                        datasets: [
                            {
                                label: 'Data',
                                fill: true,
                                borderColor: '#42b883',
                                borderWidth: 2,
                                borderDash: [],
                                borderDashOffset: 0.0,
                                pointBackgroundColor: '#42b883',
                                pointBorderColor: 'rgba(255,255,255,0)',
                                pointHoverBackgroundColor: '#be55ed',
                                pointBorderWidth: 20,
                                pointHoverRadius: 4,
                                pointHoverBorderWidth: 15,
                                pointRadius: 4,
                                data: this.datum.refuel.graph.y
                            }
                        ]
                    },
                    extraOptions: chartConfigs.purpleChartOptions,
                    gradientColors: [
                        'rgba(76, 211, 150, 0.1)',
                        'rgba(53, 183, 125, 0)',
                        'rgba(119,52,169,0)'
                    ],
                    gradientStops: [1, 0.4, 0]
                };
            }
        },
        computed: {
            ...mapState([
                'rowSelected',
                'rowId',
                'datum'
            ])
        }
    };
</script>

<style scoped>

    .sticky {
        position: sticky;
        top: 30px;
    }
</style>
