<template>
    <div class="home">
        <input v-model="message">
        {{ message }}
        <router-link :to="{ name: 'Login'}">login</router-link>
    </div>
</template>

<script>
export default {
    name: 'home',
    data () {
        return {
            message: 'TODO'
        }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>

</style>


