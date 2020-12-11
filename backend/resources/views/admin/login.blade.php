<form action="login" method="POST" class="autorizeform">
    @csrf
    <div class="ccforminp flex-c">
        <div class="ccinputcreate form-group form-element-text ">
            <input class="regformopt form-control" name="email" value="{{ old('email') }}" required type="email" placeholder="E-mail или никнейм">
        </div>
    </div>
    <div class="ccforminp flex-c">
        <div class="ccinputcreate form-group form-element-text">
            <input class="regformopt form-control" type="password" name="password" required placeholder="Пароль">
        </div>
    </div>
    <div class="form-buttons card-footer">
        <input class="btn btn-primary mt-2" type="submit" value="Авторизироваться">
    </div>
</form>
