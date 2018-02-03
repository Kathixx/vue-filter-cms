<template>
  <main>
    <div class="login-triangle"></div>
    <div class="modal is-active">
      <div class="modal-background"></div>
      <div class="modal-card">
        <span class="modal-triangle"></span>
        <header class="modal-card-head">
          <p class="modal-card-title align-center">Backend Login</p>
        </header>
        <section class="modal-card-body">
          <form class="login-form">
            <input class="input" type="username" @keyup.enter="authenticateLogin" v-model="username" placeholder="Username">
            <input class="input" type="password" @keyup.enter="authenticateLogin" v-model="password" placeholder="Password">
          </form>
        </section>
        <footer class="modal-card-foot flex-center">
          <a class="button is-primary is-outlined" href="/" title="Zurück"><i class="arrow-left"></i>Zurück</a>
          <button @click="authenticateLogin" class="button is-primary" :disabled="enabledLogin">Einloggen</button>
        </footer>
      </div>
    </div>
  </main>
</template>

<script>
import Axios from 'axios';
import JS_SHA3 from 'js-sha3';

export default {
  data(){
    return {
      username : "foo",
      password: "foobar",
      isLoading: false,
    }
  },
  computed:{
    enabledLogin(){
      return !(this.username.length >= 3 && this.password.length >= 5);
    }
  },
  methods:{
    authenticateLogin(){
      // console.log(this.hashPassword());
      if(!this.enabledLogin){
        let params = new URLSearchParams();
        params.append("action", "authenticate");
        params.append("username", this.username);
        
        params.append("password", this.hashPassword());
        Axios.post("./actions.php", params)
        .then(response => {
          console.log("Action has been handled ", response.data);
          
          if(response.data===true){
           window.location.href = "/backend.php";
          }
        })
        .catch(error => {
          console.log("An error has occurred. \n" + error);
        });
      }
    },
    hashPassword(){
      return JS_SHA3.sha3_512(this.password);
    }
  },
  mounted(){
  }
}
</script>

<style lang="scss" scoped>
  .modal-card{
    top: -10%;
    .align-center{
      text-align: center;
    }
    .flex-center{
      display: flex;
      justify-content: center;
    }
    .button{
      min-width: 110px;
    }
    .modal-triangle{
      width: 0;
      margin-right: auto;
      margin-left: auto;
      border: 12px solid transparent;
      border-bottom-color: whitesmoke;
    }
    width: 500px;
    .modal-card-body{
      .login-form{
        width: 100%;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        input {
          border: none;
          font-size: 16px;
          text-align: center;
          width: 300px;
          height: 30px;
          margin: 10px;
          box-shadow: none;
          transition: border-color .4s ease-in-out;
          border-bottom: 3px solid #00d1b2;
          &:hover, &:focus, &:active {
            border-color: #209cee !important;
            outline: none;
            outline-width: 0; 
          }
        }
      }
    }
  }
</style>

    