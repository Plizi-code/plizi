@extends('layouts.login')

@section('content')
<section class="reglogin flex-b">
    <div class="rl-left">
        <div class="logwrap">
            <div class="logcontent">
                <div class="login-logo">
                    <a href="/"><img src="storage/images/logobig.png" alt=""></a>
                </div>

                <register></register>

                <span>или</span>
                <div class="button-v2">
                    <a href="#" class="modalButton" data-popup="autorizepopup">авторизация</a>
                </div>
                <div class="importacc-text">
                    <h3>Импорт аккаунта</h3>
                    Импортируйте свой аккаунт из списка следующих социальных сетей:
                </div>
                <div class="socialicons">
                    <ul>
                        <li class="twitter"><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li class="vk"><a href="#"><i class="fa fa-vk"></i></a></li>
                        <li class="facebook"><a href="#"><i class="fa fa-facebook-official"></i></a></li>
                        <li class="instagram"><a href="#"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="loginfooter flex">
                <span>&copy; 2019 Plizi.com</span>
                <ul class="login-nav flex">
                    <li><a href="#">Соглашение</a></li>
                    <li><a href="#">О проекте</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="rl-right">
        <div class="logwrap">
            <div class="logcontent">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <h2>Удобное общение</h2>
                            <img src="storage/images/slider/001.png" alt="">
                            <div class="slidertext">
                                Это страница с описанием и руководством, и это приложение о сообществе изучения языка. Вы можете общаться со всеми людьми, которые изучают язык в мире.
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <h2>Удобное общение</h2>
                            <img src="storage/images/slider/001.png" alt="">
                            <div class="slidertext">
                                Это страница с описанием и руководством, и это приложение о сообществе изучения языка. Вы можете общаться со всеми людьми, которые изучают язык в мире.
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <h2>Удобное общение</h2>
                            <img src="storage/images/slider/001.png" alt="">
                            <div class="slidertext">
                                Это страница с описанием и руководством, и это приложение о сообществе изучения языка. Вы можете общаться со всеми людьми, которые изучают язык в мире.
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
            <div class="loginfooter flex">
                <ul class="login-nav flex">
                    <li><a href="#">Правила</a></li>
                    <li><a href="#">Реклама</a></li>
                    <li><a href="#">Для разработчиков</a></li>
                </ul>
                <span>
                    <div class="language-select">
                        <select name="lang" id="lang">
                            <option value="English">English</option>
                            <option value="Русский">Русский</option>
                        </select>
                    </div>
                </span>
            </div>
        </div>
    </div>
</section>
@include('auth.modals.login')
@include('auth.modals.confirm-email')
@include('auth.modals.lost-password')
@include('auth.modals.register')
@endsection
