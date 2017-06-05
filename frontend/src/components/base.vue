<template></template>

<script>
export default {
    name: 'base',
    data () {
        return {
            config: ''
        }
    },
    methods: {
        getFirstAttr: function(object) {
            for (let i in object) return object[i];
        },
        getUser: function(callback){
            if(localStorage.getItem( 'user' )){
                let user = eval('('+localStorage.getItem( 'user' )+')');
                this.config = { headers: {'X-Auth-Token': user.access_token} };
                if (typeof callback == 'function') return callback();
            }else{
                this.noLog();
            }   
        },
        noLog: function(){
            this.$router.push('login');
        }
    }
}
</script>

<style lang="scss" scoped></style>


