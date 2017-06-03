<template>
    <div class="login">
        <div class="logo">
            <svg class="icon logo-bg" aria-hidden="true"><use xlink:href="#icon-hexagon"></use></svg>
            <h1 class="logo-text">FMS</h1>
        </div>
        <input v-model="username">
        <input v-model="password">
        <button class="button" @click="login">登录</button>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'login',
    data () {
        return {
            username: '',
            password: ''
        }
    },
    methods: {
        login: function(){
            axios.post('/api/user/login', {username: this.username, password: this.password})
                .then(function (response) {
                    console.log(response)
                })
                .catch(function (error) {
                    console.warn(error)
                })
        }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
    $logoSize: 120; 
    
    .login {
        width: 100%;

        .logo {
            position: relative;
            margin: 140px auto 80px;
            width: #{$logoSize}px;
            height: #{$logoSize}px;

            .logo-content{
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
            }

            .logo-text {
                @extend .logo-content;
                display: block;
                color: #fff;
                font-size: 1.6rem;
                line-height: #{$logoSize}px;
                text-align: center;
                font-weight: 400;
            }
            
            .logo-bg {
                @extend .logo-content;
                animation: rotation linear 30s infinite;
                color: #e7604a;
                background-image: linear-gradient(135deg, #e7604a, #de6262);
                -webkit-text-fill-color: transparent;
                -webkit-background-clip: text;
            }
            @keyframes rotation {
                from {
                    transform: rotate(0deg);
                    filter: hue-rotate(0deg);
                }
                to {
                    transform: rotate(360deg);
                    filter: hue-rotate(-360deg);
                }
            }
        }

        input {
            display: block;
            width: 75%;
            height: 2.4rem;
            line-height: 2.4rem;
            margin: 20px auto;
            padding: 0 14px;
            border-radius: 4px;
            border: none;
            background-color: #ddd;
            color: #333;
        }
        button {
            display: block;
            width: 75%;
            height: 2.5rem;
            line-height: 2.5rem;
            margin: 20px auto;
            background-color: #5cb85c;
            border-color: #4cae4c;
            border-radius: 4px;
            border: none;
            text-align: center;
            color: #fff;
        }
        button:active {
            background-color: #4cae4c;
        }
    }

</style>
