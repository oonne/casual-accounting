<template>
    <div class="login">
        <div class="logo">
            <svg class="icon logo-bg" aria-hidden="true"><use xlink:href="#icon-hexagon"></use></svg>
            <h1 class="logo-text">FMS</h1>
        </div>
        <div class="form" :class="{shake: error }">
            <input v-model="username" placeholder="帐号">
            <input v-model="password" placeholder="密码">
            <button class="button" @click="login">登录</button>
            <div class="error-msg">{{errorMsg}}</div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Base from './Base';

export default {
    extends: Base,
    name: 'login',
    data () {
        return {
            username: '',
            password: '',
            error: false,
            errorMsg: ''
        }
    },
    watch: {
        error: function (error) {
            let vm = this;
            if (vm.error) {
                setTimeout(function(){
                    vm.error = false;
                }, 1000);
            }
        }
    },
    methods: {
        login: function(){
            let vm = this;
            if ( !vm.username || !vm.password) {
                vm.error = true;
                vm.errorMsg = '请填写帐号和密码';
            } else {
                axios.post('/api/user/login', {username: vm.username, password: vm.password})
                    .then(function (response) {
                        if (response.status == 200) {
                            if (response.data.Ret) {
                                vm.error = true;
                                vm.errorMsg = vm.getFirstAttr(response.data.Data.errors);
                                console.warn(response.data.Data.errors);
                            } else {
                                localStorage.setItem('user', JSON.stringify(response.data.Data));
                                vm.$router.push('/');
                            }
                        } else {
                            vm.error = true;
                            vm.errorMsg = response.statusText;
                        }
                    })
                    .catch(function (error) {
                        vm.error = true;
                        vm.errorMsg = '服务器故障';
                        console.error(error);
                    });
            }
        }
    },
}
</script>

<style lang="scss" scoped>
    @import "../assets/base.scss";

    $logoSize: 120; 
    
    .login {
        width: 100%;

        .logo {
            position: relative;
            margin: 18vh auto 80px;
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

        .form{
            width: 300px;
            margin: auto;

            input {
                display: block;
                width: 100%;
                height: 2.4rem;
                line-height: 2.4rem;
                margin: 20px 0;
                padding: 0 14px;
                border-radius: 4px;
                border: none;
                background-color: #ddd;
                color: #333;
            }
            button {
                display: block;
                width: 100%;
                height: 2.5rem;
                line-height: 2.5rem;
                margin: 20px 0;
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

            .error-msg {
                color: #a94442;
                text-align: center;
                font-style: 0.8rem;
            }
        }

        // error shake
        .shake {
            animation: shake linear .4s;
        }
        @keyframes shake {
            0% {
                transform: translateX(-10px);
            }
            20% {
                transform: translateX(8px);
            }
            40% {
                transform: translateX(-6px);
            }
            60% {
                transform: translateX(4px);
            }
            80% {
                transform: translateX(-2px);
            }
            100% {
                transform: translateX(0);
            }
        }

    }

</style>
