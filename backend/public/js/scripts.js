$(function(){
		$('.topnavsearch').click(function(){
			$('.nav-bar').addClass('hidenav-bar');
			$('.topnavsearchform').addClass('shownavsearch');
		});
});

$(document).mouseup(function (e){
	var div = $('.topnavsearch, .topnavsearchform');
	if (!div.is(e.target)
		&& div.has(e.target).length === 0) {
		var div = $('.nav-bar').removeClass('hidenav-bar');
	var div = $('.topnavsearchform').removeClass('shownavsearch');
}
});




$(function(){
	$('#btn-register').bind("click", function () {
		if ($(this).attr(settings.objModalDataAttr)) {
			var strDataPopupName = $(this).attr(settings.objModalDataAttr);
			$(".overlay, #" + strDataPopupName).fadeIn();
		}
	});
});

$(function(){
		$('.lostpassbtn').click(function(){
			$('#autorizepopup').addClass('popupnodisplay');
		});

		$('.sendlostpass').click(function(){
			$('#lostpassmodal').addClass('popupnodisplay');
		});

		$('#lostpassmodal .closeBtn, .overlay').click(function(){
			$('#autorizepopup').removeClass('popupnodisplay');
		});

		$('#confirmemail .closeBtn, .overlay').click(function(){
			$('#autorizepopup').removeClass('popupnodisplay');
			$('#lostpassmodal').removeClass('popupnodisplay');
		});
});

$(function() {
	Vue.component('chart', {
		template: '#stchart',
		props: [
		'size',
		'sectors'],
		data()
		{
			return {
				styleObj: {
					width: `${this.size * 1.1}px`,
					height: `${this.size * 1.1}px` },
					processedSectors: [],
					text: '',
					value: ''
				};
			},
			computed:
			{
				total() {
					return this.sectors.reduce((t, s) => t + s.value, 0);
				}
			},
			watch:
			{
				sectors:
				{
					handler(val) {
						this.calculateSectors();
					},
					deep: true,
					immediate: true
				}
			},
			methods: {
				calculateSectors() {
					let l = this.size / 2;
					let rotation = 0;
					if (this.sectors.length === 1) {
						let item = this.sectors[0];
						this.processedSectors.push({
							value: item.value,
							percentage: 1,
							label: item.label,
							color: item.color,
							d: `M${l},0 A${l},${l} 0 ${arcSweep},1 ${x}, ${y} z`,
							transform: `translate(${this.size * 0.05}, ${this.size * 0.05}) rotate(${rotation}, ${l}, ${l})`
						});
					} else {
						this.sectors.forEach((item, key) => {
							let angle = 360 * item.value / this.total;
							let aCalc = angle > 180 ? 360 - angle : angle;
							let angleRad = aCalc * Math.PI / 180;
							let sizeZ = Math.sqrt(2 * l * l - 2 * l * l * Math.cos(angleRad));
							let sideX;
							if (aCalc <= 90) {
								sideX = l * Math.sin(angleRad);
							} else {
								sideX = l * Math.sin((180 - aCalc) * Math.PI / 180);
							}
							let sideY = Math.sqrt(sizeZ * sizeZ - sideX * sideX);
							let y = sideY;
							let x;
							let arcSweep;
							if (angle <= 180) {
								x = l + sideX;
								arcSweep = 0;
							} else {
								x = l - sideX;
								arcSweep = 1;
							}
							this.processedSectors.push({
								value: item.value,
								percentage: item.value / this.total,
								label: item.label,
								color: item.color,
								d: `M${l},${l} L${l},0 A${l},${l} 0 ${arcSweep},1 ${x}, ${y} z`,
								transform: `translate(${this.size * 0.05}, ${this.size * 0.05}) rotate(${rotation}, ${l}, ${l})`
							});
							rotation = rotation + angle;
						});
					}
				}
			}
		});
	new Vue({
		el: '#app',
		data()
		{
			return {
				size: 182,
				sectors: [{
					value: 540,
					label: '',
					color: '#4d54e5'
				},
				{
					value: 220,
					label: '',
					color: '#9498ef'
				},
				{
					value: 180,
					label: '',
					color: '#c9cbf7'
				},
				{
					value: 320,
					label: '',
					color: '#edeefc'
				}]
			};
		}
	});
});

$(function() {
	new Morris.Line({
		element: 'pushups',
		data: [
		{ time: '4:00', Добавлено: 40, Удалено: 15 },
		{ time: '10:00', Добавлено: 10, Удалено: 20 },
		{ time: '16:00', Добавлено: 30, Удалено: 3 },
		{ time: '20:00', Добавлено: 20, Удалено: 30 },
		{ time: '00:00', Добавлено: 20, Удалено: 1 }
		],
		xkey: 'time',
		parseTime: false,
		ykeys: ['Добавлено','Удалено'],
		labels: ['Добавлено','Удалено'],
		lineColors: ['#4d54e5','#bfc8e5']
	});
});

var waveBtn = (function () {
	'use strict';
	var btn = document.querySelectorAll('.wave'),
	tab = document.querySelector('.tab-bar'),
	indicator = document.querySelector('.indicator'),
	indi = 0;
	indicator.style.marginLeft = indi + 'px';
	for(var i = 0; i < btn.length; i++) {
		btn[i].onmousedown = function (e) {
			var newRound = document.createElement('div'),x,y;
			newRound.className = 'cercle';
			this.appendChild(newRound);
			x = e.pageX - this.offsetLeft;
			y = e.pageY - this.offsetTop;
			newRound.style.left = x + "px";
			newRound.style.top = y + "px";
			newRound.className += " anim";
			indicator.style.marginLeft = indi + (this.dataset.num-1) * 70 + 'px';
			setTimeout(function() {
				newRound.remove();
			}, 1200);
		};
	}
}());

$(function(){
	$('.n01').click(function(){
		$('.n01').addClass('active');
		$('.n02, .n03, .n04, .n05, .n06, .n07, .n08').removeClass('active');
	});
	$('.n02').click(function(){
		$('.n02').addClass('active');
		$('.n01, .n03, .n04, .n05, .n06, .n07, .n08').removeClass('active');
	});
	$('.n03').click(function(){
		$('.n03').addClass('active');
		$('.n01, .n02, .n04, .n05, .n06, .n07, .n08').removeClass('active');
	});
	$('.n04').click(function(){
		$('.n04').addClass('active');
		$('.n01, .n02, .n03, .n05, .n06, .n07, .n08').removeClass('active');
	});
	$('.n05').click(function(){
		$('.n05').addClass('active');
		$('.n01, .n02, .n03, .n04, .n06, .n07, .n08').removeClass('active');
	});
	$('.n06').click(function(){
		$('.n06').addClass('active');
		$('.n01, .n02, .n03, .n04, .n05, .n07, .n08').removeClass('active');
	});
	$('.n07').click(function(){
		$('.n07').addClass('active');
		$('.n01, .n02, .n03, .n04, .n05, .n06, .n08').removeClass('active');
	});
	$('.n08').click(function(){
		$('.n08').addClass('active');
		$('.n01, .n02, .n03, .n04, .n05, .n06, .n07').removeClass('active');
	});
});

$(function(){
	$('.profile').click(function(){
		$('.profile .dropmenu').toggleClass('show');
	});
	$('.discuss-nav').click(function(){
		$('.discuss-nav .dropmenu').toggleClass('show');
	});

	$('.discuss-search img').click(function(){
		$('.disc-search').toggleClass('widesearch');
	});
});

$(".custom-select").each(function() {
	var classes = $(this).attr("class"),
	id      = $(this).attr("id"),
	name    = $(this).attr("name");
	var template =  '<div class="' + classes + '">';
	template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
	template += '<div class="custom-options">';
	$(this).find("option").each(function() {
		template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
	});
	template += '</div></div>';

	$(this).wrap('<div class="custom-select-wrapper"></div>');
	$(this).hide();
	$(this).after(template);
});
$(".custom-option:first-of-type").hover(function() {
	$(this).parents(".custom-options").addClass("option-hover");
}, function() {
	$(this).parents(".custom-options").removeClass("option-hover");
});
$(".custom-select-trigger").on("click", function() {
	$('html').one('click',function() {
		$(".custom-select").removeClass("opened");
	});
	$(this).parents(".custom-select").toggleClass("opened");
	event.stopPropagation();
});
$(".custom-option").on("click", function() {
	$(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
	$(this).parents(".custom-options").find(".custom-option").removeClass("selection");
	$(this).addClass("selection");
	$(this).parents(".custom-select").removeClass("opened");
	$(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
});

$(document).mouseup(function (e){
	var div = $('.profile');
	if (!div.is(e.target)
		&& div.has(e.target).length === 0) {
		var div = $('.profile .dropmenu').removeClass('show');
}
var div = $('.discuss-nav');
if (!div.is(e.target)
	&& div.has(e.target).length === 0) {
	var div = $('.discuss-nav .dropmenu').removeClass('show');
}
var div = $('.discuss-search');
if (!div.is(e.target)
	&& div.has(e.target).length === 0) {
	var div = $('.disc-search').removeClass('widesearch');
}

var div = $('.comcontroll');
if (!div.is(e.target)
	&& div.has(e.target).length === 0) {
	var div = $('.view-nav').css({display: 'none'});;
}
});

window.onload = function(){
	document.getElementById('messcontent').scrollTop = 9999999;
}

$(function(){
	function showBox(){
		var $this=$(this);
		var from=$this.data('from');
		var $box=$('#'+from);
		if($box.length==0)return;
		$box.toggle()
	}

	$('.show-box').on('click',showBox)
});

$(function() {
	$("#bars li .bar").each( function( key, bar ) {
		var percentage = $(this).data('percentage');
		$(this).css('height', percentage + '%');
	});
});

var main = document.querySelector('#checkall > legend [type="checkbox"]'),
all = document.querySelectorAll('#checkall > .inpcheck > [type="checkbox"]');

for(var i=0; i<all.length; i++) {
	all[i].onclick = function() {
		var allChecked = document.querySelectorAll('#checkall > .inpcheck > [type="checkbox"]:checked').length;
		main.checked = allChecked == all.length;
		main.indeterminate = allChecked > 0 && allChecked < all.length;
	}
}
main.onclick = function() {
	for(var i=0; i<all.length; i++) {
		all[i].checked = this.checked;
	}
}
