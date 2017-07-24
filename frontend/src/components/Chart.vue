<template>
    <div class="chart">
        <BottomNav active='chart' />
        
        <p>{{ message }}</p>

        <LoadMore v-show="loading"/> 
    </div>
</template>

<script>
import Base from './Base'
import BottomNav from './BottomNav';
import LoadMore from './LoadMore';

export default {
    extends: Base,
    name: 'chart',
    components: {
        'BottomNav': BottomNav,
        'LoadMore': LoadMore,
    },
    data () {
        return {
            message: '图表功能开发中'
        }
    },
    created: function () {
        let vm = this
        this.getUser(function(){

        })
    }
}
</script>

<style lang="scss" scoped>
    @import "../assets/base.scss";

    .chart {
        p {
            width: 100%;
            text-align: center;
        }
    }
</style>


