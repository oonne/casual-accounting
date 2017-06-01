<template>
  <div class="home">
    <input v-model="message">
    {{ message }}
  </div>
</template>

<script>
export default {
  name: 'home',
  data () {
    return {
      message: 'Hello world'
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>

</style>
