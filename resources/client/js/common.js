$(document).ready(function() {

	/*Убирание placeholder*/
	 $('input, textarea').focus(function(){
	   $(this).data('placeholder',$(this).attr('placeholder'))
	   $(this).attr('placeholder','');
	 });
	 $('input, textarea').blur(function(){
	   $(this).attr('placeholder',$(this).data('placeholder'));
	 });

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
        $('.header-search-popup').show();
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

	// Появление скрытого поля радиокнопки
	$(".step-button").click(function(){
        document.getElementById("step").scrollIntoView(true);
        window.scrollBy(0, -70);
	});

	/*Табы повреждений*/
	$(".condition-popup-tabs a").click(function(e){
		e.preventDefault();
		var $searchId = $( $(this).attr("href") );
		$(".condition-popup-tabs a").not($(this)).removeClass("active-tab");
		$(this).addClass("active-tab");
		$(".condition-tabs-content").not($searchId).css("display", "none");
		$searchId.fadeIn(0);
	});
});
