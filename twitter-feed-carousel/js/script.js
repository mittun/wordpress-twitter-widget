jQuery(document).ready(function($){
	if($('.tfc-carousel').length) {
		$('.tfc-carousel').flexslider({
			animation: tfcObj.animtion,
			animationLoop: true,
			controlNav: false,
			slideshow: (tfcObj.autoRotate!='') ? true : false,
			slideshowSpeed: (tfcObj.autoRotateTimeout!='') ? tfcObj.autoRotateTimeout : 7000,
			useCss: true,
		});
	}
});
