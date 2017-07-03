<template>
    <div class="home">
        <input v-model="message">
        {{ message }}
    </div>
</template>

<script>
import Base from './Base';

export default {
    extends: Base,
    name: 'home',
    data () {
        return {
            message: 'TODO'
        }
    },
    created: function () {
        let vm = this;
        this.getUser(function(token){
            console.log(vm.config);
        });
    }
}
</script>

<style lang="scss" scoped>
    @import "../assets/base.scss";

</style>


