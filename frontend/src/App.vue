<template>
    <div id="app">
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: 'app'
}
</script>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        user-select: auto;
        font-size: 16px;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
    }
    a {
        text-decoration: none;
    }
    html,body{
    	background-color: #eee;
        font-size: 16px;
    }
    .icon {
       width: 1em; height: 1em;
       vertical-align: -0.15em;
       fill: currentColor;
       overflow: hidden;
    }
    input,select,button{
        -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
        outline: 0;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }
</style>
