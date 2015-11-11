/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        // JavaScript to be fired on all pages
        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        // Debounced resize
        var debounce = function(func, wait, immediate) {
          var timeout;
          return function() {
            var context = this, args = arguments;
            var later = function() {
              timeout = null;
              if (!immediate){
                func.apply(context, args);
              }
            };
            if (immediate && !timeout){
              func.apply(context, args);
            }
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
          };
        };

        // Disable 300ms click delay on mobile
        FastClick.attach(document.body);

        // WP admin bar fix
        (function(adminbar){
            if(document.getElementById(adminbar)){
                $('.navbar-fixed-top').css({
                    'margin-top' : $('#'+adminbar).height()
                });
            }
        })('wpadminbar');


        // Tooltip
        $('.bs-tooltip').tooltip();

        // Popover
        $(".bs-popover")
          .on('click', function(e) {e.preventDefault(); return true;})
          .popover();

        // Responsive video
        $('.main').fitVids();

        // Video lightbox
        $('.video-lightbox').magnificPopup({
            type: 'iframe'
        });

        // Image gallery lightbox
        $('.gallery').each(function(){
            $(this).find('a.thumbnail')
            .magnificPopup({
                type:       'image',
                enableEscapeKey: true,
                gallery:    {
                    enabled:    true,
                    tPrev:      '',
                    tNext:      '',
                    tCounter: '%curr% | %total%'
                },
                mainClass: 'mfp-with-zoom',
                zoom: {
                  enabled: true,
                  duration: 300,
                  easing: 'ease-in-out',
                }
            });
        });

        /**
         * ripples
         */
        var ripples = [
          ".carousel-control",
          ".btn:not(.btn-link)",
          ".card-image",
          ".navbar a:not(.withoutripple)",
          ".dropdown-menu a",
          ".nav-tabs a:not(.withoutripple)",
          ".withripple",
          ".pagination li:not(.active, .disabled) a:not(.withoutripple)"
        ].join(",");

        $(ripples).ripples();


        // File input replacement
        $("input[type=file]").fileinput({
            uploadExtraData: {kvId: '10'},
        });


        // Material choices
        var materialChoices = function(){
            $('.checkbox input[type=checkbox]').after("<span class=checkbox-material><span class=check></span></span>");
            $('.radio input[type=radio]').after("<span class=radio-material><span class=circle></span><span class=check></span></span>");
            $('.togglebutton input[type=checkbox]').after("<span class=toggle></span>");
            $('select.form-control').dropdownjs();
        };

        // Gravity Forms render tweak
        var gravityChoices = function(){
            $('.gfield_checkbox > li').addClass('checkbox');
            $('.gfield_radio > li').addClass('radio');
            $('select.gfield_select').addClass('form-control');
        };

        gravityChoices();
        materialChoices();

        $(document).bind('gform_post_render', function(event, form_id, cur_page){
            var form = $('#gform_' + form_id);
            gravityChoices();
            materialChoices();
        });

        // Set .wrap padding-top equal to navbar height
        var wrapper = function(){
          return {
            spaceTop: function(){
                $('.navbar-fixed-top').each(function(){
                    var $self = $(this);
                    $('.wrap >.inner').css('padding-top', $self.height());
                });
            }
          };
        };

        // Set .navbar margin-top equal to #wpadminbar height
        var navbar = function(){
          return {
            spaceTop: function(){
                $('.navbar-fixed-top').each(function(){
                    var $self = $(this);
                    var adminbar = $('#wpadminbar');
                    if(adminbar.length){
                      $self.css('margin-top', adminbar.height());
                    }
                });
            },
            getHeight: function(){
                [].forEach.call(document.querySelectorAll('.banner'), function(object){
                  return object.clientHeight;
                });
            }
          };
        };

        // Set slider min-height equal to screen height
        var slider = function(){
          return {
            setHeight: function(){
                [].forEach.call(document.querySelectorAll('.carousel-fullscreen .item'), function(object){
                    var navbarHeight;
                    [].forEach.call(document.querySelectorAll('.banner'), function(object){
                        navbarHeight = object.clientHeight;
                    });
                    object.style.height = window.innerHeight - navbarHeight + 'px';
                });
            },
            setBgPos: function(){
                $('.carousel-inline[data-type="parallax"]').each(function(){
                    var $self = $(this);
                    var offset = $self.offset();
                    $self.find('.item').css('background-position', '50% '+ (offset.top - $(window).scrollTop()/10) + 'px');
                });
            }
          };
        };

        // wait until users finishes resizing the browser
        var debouncedResize = debounce(function() {
            wrapper().spaceTop();
            navbar().spaceTop();
            slider().setHeight();
            slider().setBgPos();
            watchDropdowns('.dropdown');
        }, 100);

        function watchDropdowns(selector){
            $(selector).each(function(){
                var $self = $(this),
                    $dm = $self.find('>.dropdown-menu');

                if($dm.length){
                    if(($self.offset().left + $self.width() + $dm.width()) < $(window).width()){
                        $self.removeClass('dropdown-rtl');
                    } else{
                        $self.addClass('dropdown-rtl');
                    }
                }
            });
        }

        function prepareAnimations(elems) {

            elems.each(function() {
                var $this = $(this),
                    $animationType = $this.data('animation');
                $this.removeClass('animated ' + $animationType);
            });
        }

        function doAnimations(elems) {
            var animEndEv = 'webkitAnimationEnd animationend';

            elems.each(function() {
                var $this = $(this),
                    $animationType = $this.data('animation');
                $this.addClass('animated ' + $animationType).one(animEndEv, function() {
                    $this.removeClass($animationType);
                });
            });
        }

        $(window).load(function(){
            // needed by preloaded
            $('body').addClass('loaded');

            // when the window resizes, redraw the grid
            $(window).resize(debouncedResize).trigger('resize');

            //Init carousel
            $('.carousel-inline').each(function() {
                var $myCarousel = $(this),
                    $firstAnimatingElems = $myCarousel.find('.item:first').find("[data-animated = 'true']");

                //Animate captions in first slide on page load
                doAnimations($firstAnimatingElems);

                //Other slides to be animated on carousel slide event
                $myCarousel.on('slide.bs.carousel', function(e) {
                    var $animatingElems = $(e.relatedTarget).find("[data-animated = 'true']");
                    prepareAnimations($animatingElems);
                }).on('slid.bs.carousel', function(e){
                    var $animatingElems = $(e.relatedTarget).find("[data-animated = 'true']");
                    doAnimations($animatingElems);
                });
            });

        });

        // Parallax effect for Bootstrap Carousel
        $(window).on('scroll', function(){
           slider().setBgPos();
        }).trigger('scroll');

        // Parallax-bg  section
        $('[data-ride="parallax"]').each(function(){
         // declare the variable to affect the defined data-type
         var $scroll = $(this);
         if(!$scroll.data('speed')){
           $scroll.data('speed', 100);
         }
         if(!$scroll.data('direction')){
           $scroll.data('direction', 'bottom');
         }

          $(window).scroll(function() {
            var octothorpe = 1;
            // also, negative value because we're scrolling upwards
            if($scroll.data('direction') === 'top'){
              octothorpe =  -1;
            }else if($scroll.data('direction') === 'bottom'){
              octothorpe =  1;
            }

            var height = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
            var yPos = octothorpe * (($(window).scrollTop() - $scroll.offset().top + height/2 ) / (100 / $scroll.data('speed')));
            yPos = Math.round(yPos);

            // move the background
            $scroll.css({
              '-webkit-transform' : 'translate3d(0, ' + yPos + 'px, 0)',
              '-moz-transform'    : 'translate3d(0, ' + yPos + 'px, 0)',
              '-ms-transform'     : 'translate3d(0, ' + yPos + 'px, 0)',
              'transform'         : 'translate3d(0, ' + yPos + 'px, 0)'
            });
          }).trigger('scroll');

        });

        //Scroll Top Link
        $('#scrollTopLink').on('click', function(e){
            e.preventDefault();
            $('html,body').animate({ scrollTop: 0 }, 1000, 'easeOutExpo');
        });

        // Handle hash anchors
        $('.main p >a[href^="#"], .main li >a[href^="#"]').on('click', function(e){
            e.preventDefault();
            var target = $($(this).attr('href'));

            if(target.length){
                var offset = Math.round(target.offset().top - $('.navbar-fixed-top').outerHeight() - $('#wpadminbar').outerHeight());
                $('html,body').animate({ scrollTop: offset }, 1000, 'easeOutExpo');
            }
        });

        // Handle menu sections
        $('.dropdown .dropdown >a[href^="#"]').on('click', function(e){
            e.preventDefault();
            e.stopPropagation();
        });

        $('.background-video').each(function(){
            var $self = $(this),
                opts = {},
                ratio = $self.data('youtube_video_ratio').split("/");

            opts.fitbg = ($self.data('youtube_video_fitbg') === 'on') ? true : false;
            opts.mute = ($self.data('youtube_video_mute') === 'on') ? true : false;
            opts.pauseOnScroll = ($self.data('youtube_video_pause') === 'on') ? true : false;
            opts.repeat = ($self.data('youtube_video_repeat') === 'on') ? true : false;
            opts.start = $self.data('youtube_video_start');
            opts.ratio = parseInt(ratio[0],10)/parseInt(ratio[1],10);
            opts.videoId = $self.data('youtube_video_id');

            $self.YTPlayer({
                videoId: opts.videoId,
                fitToBackground: opts.fitbg,
                ratio: opts.ratio,
                mute: opts.mute,
                pauseOnScroll: opts.pauseOnScroll,
                repeat: opts.repeat,
                start: opts.start
            });

        });

      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
