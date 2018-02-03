import Bulma from 'bulma';
import Vue from 'vue';
import Login from './containers/Login.vue';

new Vue({
    el: '#login',
    render: h => h(Login),
});