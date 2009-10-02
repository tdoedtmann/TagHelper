/**
 * tools.tabs 1.0.2 - Tabs done right.
 * 
 * Copyright (c) 2009 Tero Piirainen
 * http://flowplayer.org/tools/tabs.html
 *
 * Dual licensed under MIT and GPL 2+ licenses
 * http://www.opensource.org/licenses
 *
 * Launch  : November 2008
 * Date: ${date}
 * Revision: ${revision} 
 */ 
(function($) {
		
	// static constructs
	$.tools = $.tools || {};
	
	$.tools.tabs = {
		version: '1.0.2',
		
		conf: {
			tabs: 'a',
			current: 'current',
			onBeforeClick: null,
			onClick: null, 
			effect: 'default',
			initialIndex: 0,			
			event: 'click',
			api:false,
			rotate: false
		},
		
		addEffect: function(name, fn) {
			effects[name] = fn;
		}
	};		
	
	
	var effects = {
		
		// simple "toggle" effect
		'default': function(i, done) { 
			this.getPanes().hide().eq(i).show();
			done.call();
		}, 
		
		/*
			configuration:
				- fadeOutSpeed (positive value does "crossfading")
				- fadeInSpeed
		*/
		fade: function(i, done) {
			var conf = this.getConf(), 
				 speed = conf.fadeOutSpeed,
				 pane = this.getCurrentPane();
							 
			if (speed) {
				pane.fadeOut(speed);	
			} else {
				pane.hide();	
			}

			this.getPanes().eq(i).fadeIn(conf.fadeInSpeed, done);	
		},
		
		// for basic accordions
		slide: function(i, done) {
			this.getCurrentPane().slideUp(200);
			this.getPanes().eq(i).slideDown(400, done);			 
		}, 

		// simple AJAX effect
		ajax: function(i, done)  {			
			this.getPanes().eq(0).load(this.getTabs().eq(i).attr("href"), done);	
		}
		
	};   	
	
	var w;
	
	// this is how you add effects
	$.tools.tabs.addEffect("horizontal", function(i, done) {
	
		// store original width of a pane into memory
		if (!w) { w = this.getPanes().eq(0).width(); }
		
		// set current pane's width to zero
		this.getCurrentPane().animate({width: 0}, function() { $(this).hide(); });
		
		// grow opened pane to it's original width
		this.getPanes().eq(i).animate({width: w}, function() { 
			$(this).show();
			done.call();
		});
		
	});	
	 

	function Tabs(tabs, panes, opts) { 
		
		var self = this, current;

		// generic binding function
		function bind(name, fn) {
			$(self).bind(name, function(e, args)  {
				if (fn && fn.call(this, args.index) === false && args) {
					args.proceed = false;	
				}	
			});
			return self;
		}
		
		// bind all callbacks from configuration
		$.each(opts, function(name, fn) {
			if ($.isFunction(fn)) { bind(name, fn); }
		});
		
		
		// public methods
		$.extend(this, {				
			click: function(i) {
				
				var pane = self.getCurrentPane();				
				var tab = tabs.eq(i);												 
				
				if (typeof i == 'string' && i.replace("#", "")) {
					tab = tabs.filter("[href*=" + i.replace("#", "") + "]");
					i = Math.max(tabs.index(tab), 0);
				}
								
				if (opts.rotate) {
					var last = tabs.length -1; 
					if (i < 0) { return self.click(last); }
					if (i > last) { return self.click(0); }						
				}
				
				if (!tab.length) { 
					if (current >= 0) { return self; }
					i = opts.initialIndex;
					tab = tabs.eq(i);
				}				
				
				// possibility to cancel click action
				var args = {index: i, proceed: true};
				$(self).triggerHandler("onBeforeClick", args);				
				if (!args.proceed) { return self; }

				
				// current tab is being clicked
				if (i === current) { return self; }																
								
				tab.addClass(opts.current);
				
				// call the effect
				effects[opts.effect].call(self, i, function() {

					// onClick callback
					$(self).triggerHandler("onClick", args);
					
				});				
	
				
				tabs.removeClass(opts.current);	
				tab.addClass(opts.current);
				current = i;
				return self;
			},
			
			getConf: function() {
				return opts;	
			},

			getTabs: function() {
				return tabs;	
			},
			
			getPanes: function() {
				return panes;	
			},
			
			getCurrentPane: function() {
				return panes.eq(current);	
			},
			
			getCurrentTab: function() {
				return tabs.eq(current);	
			},
			
			getIndex: function() {
				return current;	
			}, 
			
			next: function() {
				return self.click(current + 1);
			},
			
			prev: function() {
				return self.click(current - 1);	
			}, 
			
			onBeforeClick: function(fn) {
				return bind("onBeforeClick", fn);	
			},
			
			onClick: function(fn) {
				return bind("onClick", fn);	
			}			
		
		});
		
		
		// setup click actions for each tab
		tabs.each(function(i) { 
			$(this).bind(opts.event, function(e) {
				self.click(i);
				return e.preventDefault();
			});			
		});

		// if no pane is visible --> click on the first tab
		if (location.hash) {
			self.click(location.hash);
		} else {
			self.click(opts.initialIndex);	
		}		
		
		// cross tab anchor link
		panes.find("a[href^=#]").click(function() {
			self.click($(this).attr("href"));		
		}); 
	}
	
	
	// jQuery plugin implementation
	$.fn.tabs = function(query, conf) {
		
		// return existing instance
		var el = this.eq(typeof conf == 'number' ? conf : 0).data("tabs");
		if (el) { return el; }
		
		// setup options
		var opts = $.extend({}, $.tools.tabs.conf), len = this.length;
		$.extend(opts, conf); 
		
		if ($.isFunction(conf)) {
			conf = {onBeforeClick: conf};
		}
		
		$.extend(opts, conf);
		
		// install tabs for each items in jQuery		
		this.each(function(i) {				
			var root = $(this); 
			
			// find tabs
			var els = root.find(opts.tabs);
			
			if (!els.length) {
				els = root.children();	
			}
			
			// find panes
			var panes = root.children(query);
			if (!panes.length) {
				panes = len == 1 ? $(query) : root.parent().find(query);
			}			
			
			el = new Tabs(els, panes, opts);
			root.data("tabs", el);
			
		});		
		
		return opts.api ? el: this;		
	};		
		
}) (jQuery); 


