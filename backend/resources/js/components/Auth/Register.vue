<template>
    <form  method="POST" action="">
        <h2>Быстрая регистрация</h2>
        <p v-if="errors.length">
            <b>Please correct the following error(s):</b>
            <ul>
              <li v-for="error in errors">{{ error }}</li>
            </ul>
          </p>
        <div class="regform namesurname">
            <input type="text" placeholder="Ваше Имя" v-model="user.firstname">
        </div>
        <div class="regform namesurname">
            <input type="text" placeholder="Ваша Фамилия" v-model="user.lastname">
        </div>
        <div class="regform regemail">
            <form class="e-mailenter">
                <input type="text" placeholder="Ваш E-mail" v-model="user.email"><span id="valid"></span>
            </form>
        </div>
        <div class="button flex-c">
            <a href="#" class="modalButton" v-on:click="handleSubmit" >Регистрация</a>
        </div>
    </form>
</template>

<script>
    export default {
        name : 'Register',
        data(){
            return {
                user : {
                    firstname: '',
                    lastname: '',
                    email: ''
                },
                errors: [],
            };
        },
        methods: {
            handleSubmit: function(){
                this.errors = [];
                if(this.user.firstname == null || this.user.firstname == ''){
                    this.errors.push('Firstname is required!');
                }

                if(this.user.lastname == null || this.user.lastname == ''){
                    this.errors.push('Lastname is required!');
                }

                if(this.user.email == null || this.user.email == ''){
                    this.errors.push('Firstname is required!');
                }

                if(!this.errors.length){
                    axios.post('/register', this.user).then(function(res){
                        $(".overlay, #registerpopup").fadeIn();
                    });
                }
            },
        },
        mounted() {

        }
    }
</script>
