jQuery(document).ready(function ($) {
    var googlefont = jQuery('.font').fontselect();
    fontset(googlefont);

    function fontset(googlefont) {
        var relid = googlefont.attr('id');

        // replace + signs with spaces for css
        var font = googlefont.val().replace(/\+/g, ' ');

        // split font into family and weight
        font = font.split(':');

        // set family on example
        jQuery('#' + relid + '.example').css('font-family', font[0]);
    }
});

/*
 * jQuery.fontselect - A font selector for the Google Web Fonts api
 * Tom Moor, http://tommoor.com
 * Copyright (c) 2011 Tom Moor
 * MIT Licensed
 * @version 0.1
*/
(function($){

  $.fn.fontselect = function(options) {  

     var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

     var fonts = [
      "Aclonica",
      "Allan",
      "Annie+Use+Your+Telescope",
      "Anonymous+Pro",
      "Allerta+Stencil",
      "Allerta",
      "Amaranth",
      "Anton",
      "Architects+Daughter",
      "Arimo",
      "Artifika",
      "Arvo",
      "Asset",
      "Astloch",
      "Bangers",
      "Bentham",
      "Bevan",
      "Bigshot+One",
      "Bowlby+One",
      "Bowlby+One+SC",
      "Brawler",
      "Buda:300",
      "Cabin",
      "Calligraffitti",
      "Candal",
      "Cantarell",
      "Cardo",
      "Carter One",
      "Caudex",
      "Cedarville+Cursive",
      "Cherry+Cream+Soda",
      "Chewy",
      "Coda",
      "Coming+Soon",
      "Copse",
      "Corben:700",
      "Cousine",
      "Covered+By+Your+Grace",
      "Crafty+Girls",
      "Crimson+Text",
      "Crushed",
      "Cuprum",
      "Damion",
      "Dancing+Script",
      "Dawning+of+a+New+Day",
      "Didact+Gothic",
      "Droid+Sans",
      "Droid+Sans+Mono",
      "Droid+Serif",
      "EB+Garamond",
      "Expletus+Sans",
      "Fontdiner+Swanky",
      "Forum",
      "Francois+One",
      "Geo",
      "Give+You+Glory",
      "Goblin+One",
      "Goudy+Bookletter+1911",
      "Gravitas+One",
      "Gruppo",
      "Hammersmith+One",
      "Holtwood+One+SC",
      "Homemade+Apple",
      "Inconsolata",
      "Indie+Flower",
      "IM+Fell+DW+Pica",
      "IM+Fell+DW+Pica+SC",
      "IM+Fell+Double+Pica",
      "IM+Fell+Double+Pica+SC",
      "IM+Fell+English",
      "IM+Fell+English+SC",
      "IM+Fell+French+Canon",
      "IM+Fell+French+Canon+SC",
      "IM+Fell+Great+Primer",
      "IM+Fell+Great+Primer+SC",
      "Irish+Grover",
      "Irish+Growler",
      "Istok+Web",
      "Josefin+Sans",
      "Josefin+Slab",
      "Judson",
      "Jura",
      "Jura:500",
      "Jura:600",
      "Just+Another+Hand",
      "Just+Me+Again+Down+Here",
      "Kameron",
      "Kenia",
      "Kranky",
      "Kreon",
      "Kristi",
      "La+Belle+Aurore",
      "Lato:100",
      "Lato:100italic",
      "Lato:300", 
      "Lato",
      "Lato:bold",  
      "Lato:900",
      "League+Script",
      "Lekton",  
      "Limelight",  
      "Lobster",
      "Lobster Two",
      "Lora",
      "Love+Ya+Like+A+Sister",
      "Loved+by+the+King",
      "Luckiest+Guy",
      "Maiden+Orange",
      "Mako",
      "Maven+Pro",
      "Maven+Pro:500",
      "Maven+Pro:700",
      "Maven+Pro:900",
      "Meddon",
      "MedievalSharp",
      "Megrim",
      "Merriweather",
      "Metrophobic",
      "Michroma",
      "Miltonian Tattoo",
      "Miltonian",
      "Modern Antiqua",
      "Monofett",
      "Molengo",
      "Mountains of Christmas",
      "Muli:300", 
      "Muli", 
      "Neucha",
      "Neuton",
      "News+Cycle",
      "Nixie+One",
      "Nobile",
      "Nova+Cut",
      "Nova+Flat",
      "Nova+Mono",
      "Nova+Oval",
      "Nova+Round",
      "Nova+Script",
      "Nova+Slim",
      "Nova+Square",
      "Nunito:light",
      "Nunito",
      "OFL+Sorts+Mill+Goudy+TT",
      "Old+Standard+TT",
      "Open+Sans:300",
      "Open+Sans",
      "Open+Sans:600",
      "Open+Sans:800",
      "Open+Sans+Condensed:300",
      "Orbitron",
      "Orbitron:500",
      "Orbitron:700",
      "Orbitron:900",
      "Oswald",
      "Over+the+Rainbow",
      "Reenie+Beanie",
      "Pacifico",
      "Patrick+Hand",
      "Paytone+One", 
      "Permanent+Marker",
      "Philosopher",
      "Play",
      "Playfair+Display",
      "Podkova",
      "PT+Sans",
      "PT+Sans+Narrow",
      "PT+Sans+Narrow:regular,bold",
      "PT+Serif",
      "PT+Serif Caption",
      "Puritan",
      "Quattrocento",
      "Quattrocento+Sans",
      "Radley",
      "Raleway:100",
      "Redressed",
      "Rock+Salt",
      "Rokkitt",
      "Ruslan+Display",
      "Schoolbell",
      "Shadows+Into+Light",
      "Shanti",
      "Sigmar+One",
      "Six+Caps",
      "Slackey",
      "Smythe",
      "Sniglet:800",
      "Special+Elite",
      "Stardos+Stencil",
      "Sue+Ellen+Francisco",
      "Sunshiney",
      "Swanky+and+Moo+Moo",
      "Syncopate",
      "Tangerine",
      "Tenor+Sans",
      "Terminal+Dosis+Light",
      "The+Girl+Next+Door",
      "Tinos",
      "Ubuntu",
      "Ultra",
      "Unkempt",
      "UnifrakturCook:bold",
      "UnifrakturMaguntia",
      "Varela",
      "Varela Round",
      "Vibur",
      "Vollkorn",
      "VT323",
      "Waiting+for+the+Sunrise",
      "Wallpoet",
      "Walter+Turncoat",
      "Wire+One",
      "Yanone+Kaffeesatz",
      "Yanone+Kaffeesatz:300",
      "Yanone+Kaffeesatz:400",
      "Yanone+Kaffeesatz:700",
      "Yeseva+One",
      "Zeyada"];

    var settings = {
      style: 'font-select',
      placeholder: 'Select a font',
      lookahead: 2,
      api: 'http://fonts.googleapis.com/css?family='
    };
    
    var Fontselect = (function(){
    
      function Fontselect(original, o){
        this.$original = $(original);
        this.options = o;
        this.active = false;
        this.setupHtml();
        this.getVisibleFonts();
        this.bindEvents();

        var font = this.$original.val();
        if (font) {
          this.updateSelected();
          this.addFontLink(font);
        }
      }
      
      Fontselect.prototype.bindEvents = function(){
      
        $('li', this.$results)
        .click(__bind(this.selectFont, this))
        .mouseenter(__bind(this.activateFont, this))
        .mouseleave(__bind(this.deactivateFont, this));
        
        $('span', this.$select).click(__bind(this.toggleDrop, this));
        this.$arrow.click(__bind(this.toggleDrop, this));
      };
      
      Fontselect.prototype.toggleDrop = function(ev){
        
        if(this.active){
          this.$element.removeClass('font-select-active');
          this.$drop.hide();
          clearInterval(this.visibleInterval);
          
        } else {
          this.$element.addClass('font-select-active');
          this.$drop.show();
          this.moveToSelected();
          this.visibleInterval = setInterval(__bind(this.getVisibleFonts, this), 500);
        }
        
        this.active = !this.active;
      };
      
      Fontselect.prototype.selectFont = function(){
        
        var font = $('li.active', this.$results).data('value');
        this.$original.val(font).change();
        this.updateSelected();
        this.toggleDrop();
      };
      
      Fontselect.prototype.moveToSelected = function(){
        
        var $li, font = this.$original.val();
        
        if (font){
          $li = $("li[data-value='"+ font +"']", this.$results);
        } else {
          $li = $("li", this.$results).first();
        }

        this.$results.scrollTop($li.addClass('active').position().top);
      };
      
      Fontselect.prototype.activateFont = function(ev){
        $('li.active', this.$results).removeClass('active');
        $(ev.currentTarget).addClass('active');
      };
      
      Fontselect.prototype.deactivateFont = function(ev){
        
        $(ev.currentTarget).removeClass('active');
      };
      
      Fontselect.prototype.updateSelected = function(){
        
        var font = this.$original.val();
        $('span', this.$element).text(this.toReadable(font)).css(this.toStyle(font));
      };
      
      Fontselect.prototype.setupHtml = function(){
      
        this.$original.empty().hide();
        this.$element = $('<div>', {'class': this.options.style});
        this.$arrow = $('<div><b></b></div>');
        this.$select = $('<a><span>'+ this.options.placeholder +'</span></a>');
        this.$drop = $('<div>', {'class': 'fs-drop'});
        this.$results = $('<ul>', {'class': 'fs-results'});
        this.$original.after(this.$element.append(this.$select.append(this.$arrow)).append(this.$drop));
        this.$drop.append(this.$results.append(this.fontsAsHtml())).hide();
      };
      
      Fontselect.prototype.fontsAsHtml = function(){
        
        var l = fonts.length;
        var r, s, h = '';
        
        for(var i=0; i<l; i++){
          r = this.toReadable(fonts[i]);
          s = this.toStyle(fonts[i]);
          h += '<li data-value="'+ fonts[i] +'" style="font-family: '+s['font-family'] +'; font-weight: '+s['font-weight'] +'">'+ r +'</li>';
        }
        
        return h;
      };
      
      Fontselect.prototype.toReadable = function(font){
        return font.replace(/[\+|:]/g, ' ');
      };
      
      Fontselect.prototype.toStyle = function(font){
        var t = font.split(':');
        return {'font-family': this.toReadable(t[0]), 'font-weight': (t[1] || 400)};
      };
      
      Fontselect.prototype.getVisibleFonts = function(){
      
        if(this.$results.is(':hidden')) return;
        
        var fs = this;
        var top = this.$results.scrollTop();
        var bottom = top + this.$results.height();
        
        if(this.options.lookahead){
          var li = $('li', this.$results).first().height();
          bottom += li*this.options.lookahead;
        }
       
        $('li', this.$results).each(function(){

          var ft = $(this).position().top+top;
          var fb = ft + $(this).height();

          if ((fb >= top) && (ft <= bottom)){
            var font = $(this).data('value');
            fs.addFontLink(font);
          }
          
        });
      };
      
      Fontselect.prototype.addFontLink = function(font){
      
        var link = this.options.api + font;
      
        if ($("link[href*='" + font + "']").length === 0){
      $('link:last').after('<link href="' + link + '" rel="stylesheet" type="text/css">');
    }
      };
    
      return Fontselect;
    })();

    return this.each(function(options) {        
      // If options exist, lets merge them
      if (options) $.extend( settings, options );
      
      return new Fontselect(this, settings);
    });

  };
})(jQuery);
