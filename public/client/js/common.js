$(document).ready(function() {

	let ctn = 0;
	let orders = JSON.parse(localStorage.getItem("orders"));

	$.each(orders.order, (key, value) => {
		if (value) {
			ctn += parseInt(value.ctn);
		}
	});

	$('#header-cart-count').html(ctn);

	/*Запрет перехода по ссылке*/
	$(".has-drop").click(function(e){
		e.preventDefault();
	});

	/*Вызов меню*/
	$('.mobile-buter').click(function(){
	    $(".header-flex").fadeIn(300);
	});

	$('.header-close').click(function(){
	    $(".header-flex").fadeOut(300);
	});

	/*модалка*/
	$('.popup-open').magnificPopup({
	  removalDelay: 300,
	  fixedContentPos: true,
	  callbacks: {
	    beforeOpen: function() {
	       this.st.mainClass = this.st.el.attr('data-effect');
	    }
	  },
	  midClick: true
	});

	/*появление окна поиска*/
	$('.order-search-form input').keyup(function(){
		if( $(this).val() !== '' && $(this).val().length > 1 ){
			$(".order-search-popup").fadeIn(300);
		}
		else{
	   		$(".order-search-popup").fadeOut(300);
		}
	});

	// Работа поиска в шапке
	$('.header-search-toggle').click(function(){
	    $(".header-search").addClass('opened');
	});

	$(document).mouseup(function (e) {
		var container = $(".header-search");
	    if (container.has(e.target).length === 0){
	        $(".header-search").removeClass("opened");
	    }

	    $('.header-search-popup').hide();

	    var container2 = $(".order-search");
	    if (container2.has(e.target).length === 0){
	        $(".order-search-popup").fadeOut(300);
	    }
	});

	// работа faq
	$(".faqs-question").click(function(){
		$(this).toggleClass("opened");
		$(this).next(".faqs-answer").slideToggle(300);
	});

    // Видео
    if($('.popup-youtube').length){
		$('.popup-youtube').magnificPopup({
          type: 'iframe',
          mainClass: 'mfp-fade',
          removalDelay: 160,
          preloader: false,
          fixedContentPos: false
	    });
	}

	/*Табы повреждений*/
	$(".condition-popup-tabs a").click(function(e){
		e.preventDefault();
		
		var $searchId = $( $(this).attr("href") );
		$(".condition-popup-tabs a").not($(this)).removeClass("active-tab");
		$(this).addClass("active-tab");
		$(".condition-tabs-content").not($searchId).css("display", "none");
		$searchId.fadeIn(0);
	});

    // Кастомизация селекта
	$('.states-select').click( () => {
		console.log("click!");
	});

	$('#phone-number').keyup(function (e) {
		let val = $(this).val();

		let x = val.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);

		let newVal = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');

		$(this).val(newVal);
	});

	$('.go-sell').click(function(e) {
		e.preventDefault();
		let id = $(this).attr('href');
		let top = $(id).offset().top - 30;
		$('body, html').animate({scrollTop: top}, 600);
	});

	$("#header-search-input").keyup(function (e) {
		$value = $(this).val();
		
		if ($value) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				}
			});

			e.preventDefault();

			let formData = {
				name: $value,
			};

			let type = 'GET';
			let ajaxurl = 'header-search';

			let htmlData = '';

			$.ajax({
				type: type,
				url: ajaxurl,
				data: formData,
				dataType: 'json',
				success: function (data) {
					htmlData = '';

					if (data.length) {
						$.each(data, (index, value) => {
							htmlData += '<li style="margin-bottom: 10px;">' + '<a href="/sell-my-' + value.slug + '" class="link"><span class="name">' + value.name + '</span></a></li>'
						});
					} else {
						htmlData = 'No results';
					}

					$('#header-search-popup-list').html(htmlData);

					$('.header-search-popup').show();
				},
				error: function (data) {
					console.log(data);
				}
			});
		}
    });

	$("#main-search-input").keyup(function (e) {
		$value = $(this).val();
		
		if ($value) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
				}
			});

			e.preventDefault();

			let formData = {
				name: $value,
			};

			let type = 'GET';
			let ajaxurl = 'header-search';

			let htmlData = '';

			$.ajax({
				type: type,
				url: ajaxurl,
				data: formData,
				dataType: 'json',
				success: function (data) {
					htmlData = '';

					if (data.length) {
						$.each(data, (index, value) => {
							htmlData += '<li><a href="/sell-my-' + value.slug + '" class="link">' + '<div class="image"><img src="' + value.image +'" alt=""></div><span class="name">' + value.name + '</span></a><div class="price">up to <strong>' + value.custom_text + '</strong></div></li>'
						});
					} else {
						htmlData = '<li>No results</li>';
					}

					$('#order-search-popup-list').html(htmlData);

					$('.order-search-popup').show();
				},
				error: function (data) {
					console.log(data);
				}
			});
		}
    });
});
