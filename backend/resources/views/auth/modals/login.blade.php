<section class="modal modalWindow" id="autorizepopup">
    <section class="modalWrapper">
        <div class="comcreate-header">
            <h2>Авторизация</h2>
            <img src="storage/images/autorize.jpg" alt="">
            <span>
                Если у вас нет действующей учетной записи, Вы можете создать её на <a href="#">Странице регистрации</a>
            </span>
        </div>
        <register />
        <form action="{{ route('login') }}" class="autorizeform">
            @csrf
            <div class="ccforminp flex-c">
                <div class="ccinputcreate">
                    <input class="regformopt" name="email" value="{{ old('email') }}" required type="email" placeholder="E-mail или никнейм">
                </div>
            </div>
            <div class="ccforminp flex-c">
                <div class="ccinputcreate">
                    <input class="regformopt" type="password" name="password" required placeholder="Пароль">
                </div>
            </div>

            <div class="button">
                <a href="#">Авторизироваться</a>
            </div>

            <div class="lostpass">
                <a href="#" class="lostpassbtn modalButton" data-popup="lostpassmodal">Забыли пароль?</a>
            </div>
        </form>
    </section>
    <a class="closeBtn"></a>
</section>
