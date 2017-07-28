<template>
    <div>
        <BottomNav active='chart' />
        <LoadMore v-show="loading"/> 

        <div class="chart" v-show="chartData">
            <div id="monthly" class="chart-content" :style="chartStyle"></div>
            <div id="balance" class="chart-content" :style="chartStyle"></div>
            <div id="category" class="chart-content" :style="chartStyle"></div>
            <div id="handler" class="chart-content" :style="chartStyle"></div>
        </div>
    </div>
</template>

<script>
import Base from './Base'
import BottomNav from './BottomNav'
import LoadMore from './LoadMore'
import echarts from '../assets/echarts.js'

export default {
    extends: Base,
    name: 'chart',
    components: {
        'BottomNav': BottomNav,
        'LoadMore': LoadMore,
    },
    data () {
        return {
            chartData: null
        }
    },
    computed: {
        chartStyle: function () {
            let width = document.documentElement.clientWidth - 20
            return 'width: '+width+'px'
        }
    },
    created: function () {
        this.getUser(this.init)
    },
    methods: {
        init: function () {
            let vm = this
            fetch('/api/chart/index', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Auth-Token': vm.token
                }
            })
            .then(function (response) {
                if (response.status == 200) {
                    return response.json()
                } else if (response.status == 401) {
                    vm.errorMsg = '未登录'
                    vm.noLog()
                } else {
                    vm.errorMsg = response.statusText
                }
            })
            .then(function (data) {
                vm.loading = false
                if (data) {
                    if (!data.Ret) {
                        vm.showChart(data.Data)
                    } else {
                        vm.errorMsg = vm.getFirstAttr(data.Data.errors)
                        console.warn(data.Data.errors)
                    }
                }
            })
            .catch(function (error) {
                vm.loading = false
                console.error(error)
                vm.errorMsg = '服务器故障'
            })
        },
        showChart: function(data){
            let vm = this
            vm.chartData = data

            // monthly expenses & monthly income
            let monthChart = echarts.init(document.getElementById('monthly'))
            monthChart.setOption({
                color: ['#72a4bb', '#add536'],
                grid: {
                    left: '2%',
                    right: '6%',
                    containLabel: true
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['月存钱', '月消费']
                },
                xAxis : [
                    {
                        type : 'category',
                        boundaryGap: false,
                        data: data['month']
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                dataZoom: [
                    {
                        type: 'inside',
                        start: 0,
                        end: 100
                    },
                ],
                series : [
                    {
                        name: '月消费',
                        type: 'line',
                        data: data['monthlyExpenses'],
                        markLine: {
                            data: [
                                {type: 'average', name: '平均消费'}
                            ],
                            label: {
                                normal: {
                                    position: 'middle'
                                }
                            }
                        },
                        markPoint: {
                            data: [
                                {type: 'max', name: '最高消费'},
                                {type: 'min', name: '最低消费'}
                            ]
                        }
                    },
                    {
                        name: '月存钱',
                        type: 'line',
                        data: data['monthlyIncome'],
                        markLine: {
                            data: [
                                {type: 'average', name: '平均消费'}
                            ],
                            label: {
                                normal: {
                                    position: 'middle'
                                }
                            }
                        }
                    }
                ]
            }, true)

            // monthly balance
            let balanceChart = echarts.init(document.getElementById('balance'))
            balanceChart.setOption({
                color: ['#ccd539'],
                grid: {
                    left: '2%',
                    right: '6%',
                    containLabel: true
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['月存钱', '月消费']
                },
                xAxis : [
                    {
                        type : 'category',
                        data: data['month']
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                dataZoom: [
                    {
                        type: 'inside',
                        start: 0,
                        end: 100
                    },
                ],
                series : [
                    {
                        name: '每月结余',
                        type: 'bar',
                        barWidth: '60%',
                        label: {
                            normal: {
                                position: 'top',
                                show: true
                            }
                        },
                        data: data['monthlyBalance']
                    }
                ]
            }, true)

            // expenses category
            let categoryChart = echarts.init(document.getElementById('category'))
            categoryChart.setOption({
                color: ['#a0c824', '#72a4bb', '#6c6669'],
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },
                series : [
                    {
                        name: '金额',
                        type: 'pie',
                        radius: ['40%', '54%'],
                        data: data['expensesCategory']
                    }
                ]
            }, true)

            // income handler
            let handlerChart = echarts.init(document.getElementById('handler'))
            handlerChart.setOption({
                color: ['#a0c824', '#72a4bb', '#6c6669'],
                tooltip: {
                    trigger: 'item',
                    formatter: '{a} <br/>{b}: {c} ({d}%)'
                },
                series : [
                    {
                        name: '金额',
                        type: 'pie',
                        radius: ['40%', '54%'],
                        data: data['incomeHandler']
                    }
                ]
            }, true)
        }
    }
}
</script>

<style lang="scss" scoped>
    @import "../assets/base.scss";

    .chart {
        padding: 10px;

        .chart-content {
            width: 100%;
            height: 380px;
        }
    }
</style>


