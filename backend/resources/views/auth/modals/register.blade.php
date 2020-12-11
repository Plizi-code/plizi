<section class="modal modalWindow" id="registerpopup">
    <section class="modalWrapper">
        <div class="comcreate-header">
            <h2>Регистрация</h2>
            <img src="storage/images/fastreg.jpg" alt="">
            <span>
                Важно: При регистрации, вводите корректные верные данные, так как в целях безопасности вам будет легче восстановить доступ к своей странице.
            </span>
        </div>
        <form method="POST" action="{{route('verify_registration')}}" class="autorizeform">
            @CSRF
            <div class="ccforminp flex-c">
                <div class="ccinputcreate">
                    <input class="regformopt" type="text" name="password" placeholder="Введите пароль">
                </div>
            </div>
            <div class="ccforminp flex-c">
                <div class="ccinputcreate">
                    <input class="regformopt" type="text" name="password_confirm" placeholder="Подтвердите пароль">
                </div>
            </div>
            <div class="ccforminp flex-c">
                <div class="ccinputcreate">
                    <input class="regformopt" type="text"  name="confirm_code" placeholder="Код полученый на E-mail">
                </div>
            </div>
            <span class="resendcode">Не пришел код? - <a href="#">Отправить повторно</a></span>
            <div class="ccforminp flex-c">
                <div class="ccinputcreate">
                    <input class="regformopt" type="text" name="birthday" placeholder="Дата рождения">
                </div>
            </div>
            <div class="ccforminp flex-c">
                <div class="ccinputcreate">
                    <input class="regformopt" type="text" name="city" placeholder="Город">
                </div>
            </div>
            <div class="button">
                <a href="#" class="submit-registration">Зарегистрироваться</a>
            </div>

        </form>
    </section>
    <a class="closeBtn"></a>
</section>
