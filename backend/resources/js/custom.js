$(document).ready(function(){
var pattern = /^[.a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/i; //firstName-_09@mail09-.ru
var mail = $('#mail');
mail.blur(function(){
 if(mail.val() != ''){
     if(mail.val().search(pattern) == 0){
         $('#valid').text('');
         $('#submit').attr('disabled', false);
         mail.removeClass('error').addClass('ok');
     }else{
         $('#valid').text('Не корректный E-mail');
         $('#submit').attr('disabled', true);
         mail.addClass('error');
     }
 }else{
     $('#valid').text('Поле e-mail не должно быть пустым!');
     mail.addClass('error');
     $('#submit').attr('disabled', true);
 }
});
});

$(function(){
	settings = {
		objModalPopupBtn: ".modalButton",
		objModalCloseBtn: ".overlay, .closeBtn",
		objModalDataAttr: "data-popup"
	}
	$(settings.objModalPopupBtn).bind("click", function () {
		if ($(this).attr(settings.objModalDataAttr)) {
			var strDataPopupName = $(this).attr(settings.objModalDataAttr);
			$(".overlay, #" + strDataPopupName).fadeIn();
		}
	});
	$(settings.objModalCloseBtn).bind("click", function () {
		$(".modal").fadeOut();
	});
});

$(function(){
	$('.submit-registration').bind("click", function () {
        $(this).closest('form').submit()
	});
});
