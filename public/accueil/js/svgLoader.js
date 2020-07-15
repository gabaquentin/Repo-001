/**
 * svgLoader.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2014, Codrops
 * http://www.codrops.com
 */
;( function( window ) {
	
	'use strict';

	function extend( a, b ) {
		for( var key in b ) { 
			if( b.hasOwnProperty( key ) ) {
				a[key] = b[key];
			}
		}
		return a;
	}

	function SVGLoader( el, options ) {
		this.el = el;
		this.options = extend( {}, this.options );
		extend( this.options, options );
		this._init();
	}
	
	SVGLoader.prototype.options = {
		speedIn : 500,
		easingIn : mina.linear
	}

	SVGLoader.prototype._init = function() {
		var s = Snap( this.el.querySelector( 'svg' ) );
		this.path = s.select( 'path' );
		this.initialPath = this.path.attr('d');
		
		/*/-- define opening and closing steps
		var openingStepsStr = "";
		var closingStepsStr = "";
		
		if($(window).width() < 992){
			openingStepsStr = "M80,58 80,58 80,60 80,60 Z;M80,58 75,55 70,60 80,60 Z;M80,45 70,56 65,60 80,60 Z;M80,30 50,36 40,60 80,60 Z;M80,10 40,50 20,60 80,60 Z;M80,5 50,16 10,60 80,60 Z;M80,0 30,10 0,60 80,60 Z;M80,0 0,0 0,60 80,60 Z";
			closingStepsStr = "M80,0 0,0 0,60 80,60 Z;M80,0 30,10 0,60 80,60 Z;M80,5 50,16 10,60 80,60 Z;M80,10 40,50 20,60 80,60 Z;M80,30 50,36 40,60 80,60 Z;M80,45 70,56 65,60 80,60 Z;M80,58 75,55 70,60 80,60 Z;M80,58 80,58 80,60 80,60 Z";
		}
		else{
			openingStepsStr = "M80,58 80,58 80,60 80,60 Z;M80,58 79,58 78,60 80,60 Z;M80,50 76,56 70,60 80,60 Z;M80,40 74,36 60,60 80,60 Z;M80,30 60,36 40,60 80,60 Z;M80,20 70,36 35,60 80,60 Z;M80,10 75,20 35,60 80,60 Z;M80,0 50,0 30,60 80,60 Z";
			closingStepsStr = "M80,0 50,0 30,60 80,60 Z;M80,10 75,20 35,60 80,60 Z;M80,20 70,36 35,60 80,60 Z;M80,30 60,36 40,60 80,60 Z;M80,40 74,36 60,60 80,60 Z;M80,50 76,56 70,60 80,60 Z;M80,58 79,58 78,60 80,60 Z;M80,58 80,58 80,60 80,60 Z";
		}
		
		this.openingSteps = openingStepsStr ? openingStepsStr.split(';') : '';
		this.openingStepsTotal = openingStepsStr ? this.openingSteps.length : 0;
		if( this.openingStepsTotal === 0 ) return;

		// if data-closing is not defined then the path will animate to its original shape
		this.closingSteps = closingStepsStr ? closingStepsStr.split(';') : '';
		this.closingStepsTotal = closingStepsStr ? this.closingSteps.length : 0;
		**/
		
		this.isAnimating = false;

		if( !this.options.speedOut ) {
			this.options.speedOut = this.options.speedIn;
		}
		if( !this.options.easingOut ) {
			this.options.easingOut = this.options.easingIn;
		}
	}

	SVGLoader.prototype.show = function() {
		if( this.isAnimating ) return false;
		this.isAnimating = true;
		// animate svg
		var self = this,
			onEndAnimation = function() {
				classie.addClass( self.el, 'pageload-loading' );
			};
		this._animateSVG( 'in', onEndAnimation );
		classie.add( this.el, 'show' );
	}

	SVGLoader.prototype.hide = function() {
		var self = this;
		classie.removeClass( this.el, 'pageload-loading' );
		this._animateSVG( 'out', function() { 
			// reset path
			self.path.attr( 'd', self.initialPath );
			classie.removeClass( self.el, 'show' );
			self.isAnimating = false; 
		} );
	}

	SVGLoader.prototype._animateSVG = function( dir, callback ) {
		//-- define opening and closing steps based on window size
		var openingStepsStr = "";
		var closingStepsStr = "";
		
		if($(window).width() < 992){
			openingStepsStr = "M80,58 80,58 80,60 80,60 Z;M80,58 75,55 70,60 80,60 Z;M80,45 70,56 65,60 80,60 Z;M80,30 50,36 40,60 80,60 Z;M80,10 40,50 20,60 80,60 Z;M80,5 50,16 10,60 80,60 Z;M80,0 30,10 0,60 80,60 Z;M80,0 0,0 0,60 80,60 Z";
			closingStepsStr = "M80,0 0,0 0,60 80,60 Z;M80,0 30,10 0,60 80,60 Z;M80,5 50,16 10,60 80,60 Z;M80,10 40,50 20,60 80,60 Z;M80,30 50,36 40,60 80,60 Z;M80,45 70,56 65,60 80,60 Z;M80,58 75,55 70,60 80,60 Z;M80,58 80,58 80,60 80,60 Z";
		}
		else{
			openingStepsStr = "M80,58 80,58 80,60 80,60 Z;M80,58 79,58 78,60 80,60 Z;M80,50 76,56 70,60 80,60 Z;M80,40 74,36 60,60 80,60 Z;M80,30 60,36 40,60 80,60 Z;M80,20 70,36 35,60 80,60 Z;M80,10 75,20 35,60 80,60 Z;M80,0 50,0 30,60 80,60 Z";
			closingStepsStr = "M80,0 50,0 30,60 80,60 Z;M80,10 75,20 35,60 80,60 Z;M80,20 70,36 35,60 80,60 Z;M80,30 60,36 40,60 80,60 Z;M80,40 74,36 60,60 80,60 Z;M80,50 76,56 70,60 80,60 Z;M80,58 79,58 78,60 80,60 Z;M80,58 80,58 80,60 80,60 Z";
		}
		
		this.openingSteps = openingStepsStr ? openingStepsStr.split(';') : '';
		this.openingStepsTotal = openingStepsStr ? this.openingSteps.length : 0;
		if( this.openingStepsTotal === 0 ) return;

		// if data-closing is not defined then the path will animate to its original shape
		this.closingSteps = closingStepsStr ? closingStepsStr.split(';') : '';
		this.closingStepsTotal = closingStepsStr ? this.closingSteps.length : 0;
		
		
		var self = this,
			pos = 0,
			steps = dir === 'out' ? this.closingSteps : this.openingSteps,
			stepsTotal = dir === 'out' ? this.closingStepsTotal : this.openingStepsTotal,
			speed = dir === 'out' ? self.options.speedOut : self.options.speedIn,
			easing = dir === 'out' ? self.options.easingOut : self.options.easingIn,
			nextStep = function( pos ) {
				if( pos > stepsTotal - 1 ) {
					if( callback && typeof callback == 'function' ) {
						callback();
					}
					return;
				}
				self.path.animate( { 'path' : steps[pos] }, speed, easing, function() { nextStep(pos); } );
				pos++;
			};

		nextStep(pos);
	}

	// add to global namespace
	window.SVGLoader = SVGLoader;

})( window );