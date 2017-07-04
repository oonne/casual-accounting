<template>
    <div class="income">
        <BottomNav active='income' />

        <input v-model="message">
        {{ message }}
    </div>
</template>

<script>
import Base from './Base'
import BottomNav from './BottomNav';

export default {
    extends: Base,
    name: 'income',
    components: {
        'BottomNav': BottomNav,
    },
    data () {
        return {
            message: 'TODO-income'
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


