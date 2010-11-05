window.addEvent('domready', function() {
		
				

		var theSlide = new Fx.Slide('styleswitcher').hide();
		$('toggle_ss').addEvent('click', function(e){
			e = new Event(e);
			theSlide.toggle();
			e.stop();
		});


});


