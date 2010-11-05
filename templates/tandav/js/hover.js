window.addEvent('domready', function() {
		
				

	 	var list = $$('.module_menu ul.menu li a, a.mainlevel, a.sublevel');
		list.each(function(element) {
		 
			var fx = new Fx.Styles(element, {duration:200, wait:false, transition: Fx.Transitions.Expo.easeOut});
		 
			element.addEvent('mouseenter', function(){
				fx.start({
					'font-size': 14			        
				});
			});
		 
			element.addEvent('mouseleave', function(){
				fx.start({
				    'font-size': 11			        
				});
			});
		 
		});

	 	var list = $$('ul#mainlevel-nav li a');
		list.each(function(element) {
		 
			var fx = new Fx.Styles(element, {duration:200, wait:false, transition: Fx.Transitions.Expo.easeOut});
		 
			element.addEvent('mouseenter', function(){
				fx.start({
					'padding-top': 10			        
				});
			});
		 
			element.addEvent('mouseleave', function(){
				fx.start({
				    'padding-top': 0			        
				});
			});
		 
		});
		
		var Tips2 = new Tips($$('.styleswitch'), {
			initialize:function(){
				this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 500, wait: false}).set(0);
			},
			onShow: function(toolTip) {
				this.fx.start(1);
			},
			onHide: function(toolTip) {
				this.fx.start(0);
			}
		});

		var Tips3 = new Tips($$('#toggle_ss'), {
			initialize:function(){
				this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 500, wait: false}).set(0);
			},
			onShow: function(toolTip) {
				this.fx.start(1);
			},
			onHide: function(toolTip) {
				this.fx.start(0);
			}
		});

		var Tips4 = new Tips($$('#gotop'), {
			initialize:function(){
				this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 500, wait: false}).set(0);
			},
			onShow: function(toolTip) {
				this.fx.start(1);
			},
			onHide: function(toolTip) {
				this.fx.start(0);
			}
		});
		

		var scroll = new Fx.Scroll(Window, {
		    wait: false,
		    duration: 800,
		    offset: {'x': 0, 'y': 0},
		    transition: Fx.Transitions.Quad.easeInOut
		});
		 
		$('gotop').addEvent('click', function(event) {
		    event = new Event(event).stop();
		    scroll.toElement('wrapper');
		}); 

});


