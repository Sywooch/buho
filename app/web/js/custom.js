jQuery(document).ready(function () {
	GUI.init();

	jQuery('ul > li:first-child').addClass('li-first');
	jQuery('ul > li:last-child').addClass('li-last');
	jQuery("ul li:nth-child(1n)").addClass('one');
	jQuery("ul li:nth-child(2n)").addClass('two');
	jQuery("ul li:nth-child(3n)").addClass('three');
	jQuery("ul li:nth-child(4n)").addClass('fwo');
	jQuery("ul li:nth-child(5n)").addClass('five');
	jQuery('table').find('th:first').addClass('th-first');
	jQuery('table').find('tr:odd').addClass("odd");
	jQuery('table').find('tr:even').addClass("even");
	jQuery('table').find('tr:first').addClass('tr-header');
	jQuery('table tr').find('td:first').addClass('td-first');
	jQuery('table tr').find('td:last').addClass('td-last');
	jQuery('.tb .tr').find('.td:first').addClass('td-first');
	jQuery('.tb .tr').find('.td:last').addClass('td-last');
	jQuery(".ui-tabs").tabs();

	// PRICE SLIDER
	var price_slider = jQuery("#slider");
	var price_min = $('input#minCost');
	var price_max = $('input#maxCost');
	var price_url = jQuery("#price-filter-url");
	price_slider.slider({
		min: parseInt(price_min.attr('data-limit')),
		max: parseInt(price_max.attr('data-limit')),
		values: [parseInt(price_min.val()), parseInt(price_max.val())],
		range: true,
		change: function (event, ui) {
			var min = price_slider.slider("values", 0);
			var max = price_slider.slider("values", 1);
			price_url.attr("href", price_url.attr("data-href").replace('price=min-max', 'price=' + min + '-' + max));
		},
		stop: function (event, ui) {
			var min = price_slider.slider("values", 0);
			var max = price_slider.slider("values", 1);
			price_min.val(min);
			price_max.val(max);
		},
		slide: function (event, ui) {
			price_min.val(price_slider.slider("values", 0));
			price_max.val(price_slider.slider("values", 1));
		}
	});

	$('input#minCost, input#maxCost').on('change', function(){
		price_slider
			.slider('values', 0, parseInt(price_min.val()))
			.slider('values', 1, parseInt(price_max.val()));
	});

	// SLIDER
	jQuery(".slider ul").bxSlider({
		auto: true,
		controls: false,
		pager: true,
		pause: 8000,
		mode: 'horizontal'
	});

	jQuery(".feedbacks-slider .feedbacks-slider-list").bxSlider({
		auto: true,
		controls: true,
		pager: true,
		pause: 8000,
		mode: 'horizontal',
		adaptiveHeight: true
	});

	jQuery('.news-slider ul').bxSlider({
		auto: true,
		pager: true,
		controls: true,
		pause: 8000,
		minSlides: 1,
		maxSlides: 4,
		moveSlides: 1,
		slideWidth: 275,
		adaptiveHeight: false,
		mode: 'horizontal'
	});

	jQuery(".productpage-slider ul").bxSlider({
		pagerCustom: ".productpage-slider-trumbs ul",
		auto: false,
		controls: true,
		mode: 'fade',
		infiniteLoop: false
	});

	jQuery('.productpage-slider-trumbs ul').bxSlider({
		auto: false,
		pager: false,
		controls: true,
		pause: 8000,
		minSlides: 1,
		maxSlides: 3,
		moveSlides: 1,
		slideWidth: 90,
		slideMargin: 15,
		adaptiveHeight: false,
		mode: 'vertical',
		infiniteLoop: false
	});

	var count = 0;
	jQuery(".productpage-slider-trumbs a").each(function () {
		jQuery(this).attr('data-slide-index', count++);
	});

    jQuery('.productpage-slider ul').magnificPopup({
        'type':'image',
        'delegate':'a',
        'gallery': {
            'enabled': true,
            'navigateByImgClick': true
        }
    });

	// MOBILE NAVI
	jQuery(".mobile-navi-button span").click(function () {
		if (jQuery(".mmenu-button").hasClass("hide"))
			jQuery(".mmenu-button").removeClass("hide").addClass("show");
		else
			jQuery(".mmenu-button").removeClass("show").addClass("hide");
	});

	$menuLeft = jQuery('.pushmenu-left');
	$nav_list = jQuery('.mobile-navi-button span');

	$nav_list.click(function () {
		jQuery(this).toggleClass('active');
		jQuery('.pushmenu-push').toggleClass('pushmenu-push-toright');
		$menuLeft.toggleClass('pushmenu-open');
	});

	// LANGS
	jQuery(".langs select").click(function () {
		jQuery('.langs').toggleClass("active");
	});

	// LOGIN
	jQuery(".login-btn").click(function () {
		jQuery('.login-popup').toggle();
		jQuery('.login-btn').toggleClass("active");
	});

	// FILTER ADD MORE
	jQuery(".side-filter .filter-loadmore").click(function () {
		var btn = jQuery(this);
		var filter = btn.closest(".side-filter");
		var showEl = filter.find('.hide-filters');
		showEl.toggleClass('showEl');
		btn.toggleClass('active');
		if (showEl.hasClass('showEl')) {
			showEl.slideDown();
		} else {
			showEl.slideUp();
		}
	});

	// SELECT
	jQuery(".select").click(function () {
		jQuery(this).toggleClass("active");
	});

	// CUSTOM INPUT NUMBERS
	jQuery(".button").on("click", function () {
		var $button = jQuery(this);
		var oldValue = $button.parent().find("input").val();
        var maxValue = parseFloat($button.parent().find("input").attr('max'));
        if (maxValue < 1)
        {
            maxValue = 1;
        }
        var newVal = 1;

		if ($button.text() == "+") {
			newVal = parseFloat(oldValue) + 1;
            if (newVal > maxValue)
            {
                newVal = maxValue;
            }
		}
        else
        {
			if (oldValue > 1)
            {
				newVal = parseFloat(oldValue) - 1;
			}
            else
            {
				newVal = 1;
			}
		}
		$button.parent().find("input").val(newVal).trigger('change');
	});

	// PHONE FORMAT
	jQuery('input[type=tel]').mask("+38 (099) 999-99-99");
	jQuery('body').on('popup-loaded', function(){
		jQuery('input[type=tel]').mask("+38 (099) 999-99-99");
	});

	// SCROLL TO TOP
	jQuery('.up-btn a').click(function () {
		jQuery('html, body').animate({scrollTop: 0}, 800);
		return false;
	});

	var $list = $('.orders-table');
	if ($list.length)
	{
		$list.find("tr").not('.accordion').hide();
		$list.find("tr").eq(0).show();
		$list.find(".accordion").on('click', function(){

			//$('.accordion').removeClass('active');
			var $current   = $(this),
				parrent_block = $current.closest('.orders-table'),
				accordion = parrent_block.find('.accordion'),
				accordion_tb = $current.closest('tbody'),
				accordion_tb_sibl = accordion_tb.siblings('tbody'),
				accordion_tb_sibl_btn = accordion_tb_sibl.find('.accordion'),
				accordion_show_el_all = accordion_tb_sibl.find('.accordion').siblings(),
				accordion_show_el = $current.siblings();

			/**/
			$current.toggleClass('active');

			if($current.hasClass('active')){
				accordion_show_el.fadeIn();
				accordion_show_el_all.fadeOut();
				accordion_tb_sibl_btn.removeClass('active');
			}else{
				accordion_show_el.fadeOut();
				accordion_tb_sibl_btn.removeClass('active');
			}

		});
	}

	//	Compare table actions
	$('.compare-navi li a').on('click', function(){
		$(this).closest('li').addClass('active').siblings('li').removeClass('active');
		if ($(this).hasClass('diff'))
		{
			$('.compare-params.same').hide();
		}
		else
		{
			$('.compare-params').show();
		}
	});

	//	Wishlist checkbox actions
	$('.wishlist-list input.product-check').on('click change', function(){
		var count = 0;
		var cost = 0;
		var check = $('.wishlist-list input.product-check');

		if (check.filter(':checked').length)
		{
			check = check.filter(':checked');
		}
		check.each(function(){
			count++;
			cost += parseFloat($(this).closest('li').attr('data-price'));
		});
		$('.buy-count').html(parseInt(count));
		$('.buy-cost').html(Math.round(cost*100)/100);
	});
});
