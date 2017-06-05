<template>
    <div class="home">
        <input v-model="message">
        {{ message }}
    </div>
</template>

<script>
import base from './base';

export default {
    extends: base,
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

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>

</style>


