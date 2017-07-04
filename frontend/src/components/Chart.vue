<template>
    <div class="chart">
        <BottomNav active='chart' />
        
        <input v-model="message">
        {{ message }}
    </div>
</template>

<script>
import Base from './Base'
import BottomNav from './BottomNav';

export default {
    extends: Base,
    name: 'chart',
    components: {
        'BottomNav': BottomNav,
    },
    data () {
        return {
            message: 'TODO-chart'
        }
    },
    created: function () {
        let vm = this
        this.getUser(function(token){

        })
    }
}
</script>

<style lang="scss" scoped>
    @import "../assets/base.scss";

</style>


