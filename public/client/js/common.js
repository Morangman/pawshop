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

	/*Плюс-минус*/
    $('.qtyplus').click(function(e){
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).siblings('.qty');
        // Get its current value
        var currentVal = parseInt(fieldName.val());
        var maxCount = $(".qtyplus").data("max")
        // If is not undefined
        if (!isNaN(currentVal)) {
            // Increment only if value is < 20
            if (currentVal < maxCount)
            {
             fieldName.val(currentVal + 1);
              $('.qtyminus').val("-").removeAttr('style');
							}
            else
            {
            	$(this).val("+").css('color','#aaa');
        		$(this).val("+").css('cursor','not-allowed');
            }
        } else {
            // Otherwise put a 0 there
            fieldName.val(1);

        }
    });
    // This button will decrement the value till 0
    $(".qtyminus").click(function(e) {
        // Stop acting like a button
        e.preventDefault();
        // Get the field name
        fieldName = $(this).siblings('.qty');
        // Get its current value
        var currentVal = parseInt(fieldName.val());
        // If it isn't undefined or its greater than 0
        if (!isNaN(currentVal) && currentVal > 1) {
            // Decrement one only if value is > 1
           fieldName.val(currentVal - 1);
             $('.qtyplus').val("+").removeAttr('style');
        } else {
            // Otherwise put a 0 there
            fieldName.val(1);
            $(this).val("-").css('color','#aaa');
            $(this).val("-").css('cursor','not-allowed');
        }
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
	$(".radiobox-block").click(function(){
		var tabParent = $(this).parents(".order-options-radios");
		tabParent.find(".options-radio-detail").not($(this).next(".options-radio-detail")).slideUp(300);
		$(this).next(".options-radio-detail").slideDown(300);
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

	/*Табы оплаты*/
	$(".checkout-payment-list .payment-radiobox").click(function(){
		var $searchId = $( $(this).data("tab") );
		$(".checkout-payment-content").not($searchId).css("display", "none");
		$searchId.fadeIn(0);
	});

});
