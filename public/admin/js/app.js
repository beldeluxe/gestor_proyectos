$.appReady = function(){
    /**Array*/
    // Production steps of ECMA-262, Edition 5, 15.4.4.14
    // Reference: http://es5.github.io/#x15.4.4.14
    if (!Array.prototype.indexOf) {
        Array.prototype.indexOf = function(searchElement, fromIndex) {
            var k;
            // 1. Let O be the result of calling ToObject passing
            //    the this value as the argument.
            if (null === this || undefined === this) {
                throw new TypeError('"this" is null or not defined');
            }
            var O = Object(this);
            // 2. Let lenValue be the result of calling the Get
            //    internal method of O with the argument "length".
            // 3. Let len be ToUint32(lenValue).
            var len = O.length >>> 0;
            // 4. If len is 0, return -1.
            if (len === 0) {
                return -1;
            }
            // 5. If argument fromIndex was passed let n be
            //    ToInteger(fromIndex); else let n be 0.
            var n = +fromIndex || 0;
            if (Math.abs(n) === Infinity) {
                n = 0;
            }
            // 6. If n >= len, return -1.
            if (n >= len) {
                return -1;
            }
            // 7. If n >= 0, then Let k be n.
            // 8. Else, n<0, Let k be len - abs(n).
            //    If k is less than 0, then let k be 0.
            k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);
            // 9. Repeat, while k < len
            while (k < len) {
                // a. Let Pk be ToString(k).
                //   This is implicit for LHS operands of the in operator
                // b. Let kPresent be the result of calling the
                //    HasProperty internal method of O with argument Pk.
                //   This step can be combined with c
                // c. If kPresent is true, then
                //    i.  Let elementK be the result of calling the Get
                //        internal method of O with the argument ToString(k).
                //   ii.  Let same be the result of applying the
                //        Strict Equality Comparison Algorithm to
                //        searchElement and elementK.
                //  iii.  If same is true, return k.
                if (k in O && O[k] === searchElement) {
                    return k;
                }
                k++;
            }
            return -1;
        };
    }

    /*
     * Fire tooltips
     */
    if ($("[rel=tooltip]").length) {
        $("[rel=tooltip]").tooltip();
    }

    //TODO: was moved from window.load due to IE not firing consist
    nav_page_height();

    // INITIALIZE LEFT NAV
    if (!null) {
        $('nav ul').jarvismenu({
            accordion : true,
            speed : $.menu_speed,
            closedSign : '<em class="fa fa-expand-o"></em>',
            openedSign : '<em class="fa fa-collapse-o"></em>'
        });
    } else {
        alert("Error - menu anchor does not exist");
    }

    // HIDE MENU
    $('#hide-menu >:first-child > a').click(function(e) {
        $('body').toggleClass("hidden-menu");
        e.preventDefault();
    });

    $('#show-shortcut').click(function(e) {
        if ($.shortcut_dropdown.is(":visible")) {
            shortcut_buttons_hide();
        } else {
            shortcut_buttons_show();
        }
        e.preventDefault();
    });

    // SHOW & HIDE MOBILE SEARCH FIELD
    $('#search-mobile').click(function() {
        $.root_.addClass('search-mobile');
    });

    $('#cancel-search-js').click(function() {
        $.root_.removeClass('search-mobile');
    });

    // ACTIVITY
    // ajax drop
    $('#activity').click(function(e) {
        var self = $(this);

        if (self.find('.badge').hasClass('bg-color-red')) {
            self.find('.badge').removeClassPrefix('bg-color-');
            self.find('.badge').text("0");
            // console.log("Ajax call for activity")
        }

        if (!self.next('.ajax-dropdown').is(':visible')) {
            self.next('.ajax-dropdown').fadeIn(150);
            self.addClass('active');
        } else {
            self.next('.ajax-dropdown').fadeOut(150);
            self.removeClass('active')
        }

        var mytest = self.next('.ajax-dropdown').find('.btn-group > .active > input').attr('id');
        //console.log(mytest)

        e.preventDefault();
    });

    $('input[name="activity"]').change(function() {
        //alert($(this).val())
        var self = $(this);

        var url = self.attr('id');
        var container = $('.ajax-notifications');

        loadURL(url, container);

    });

    $(document).mouseup(function(e) {
        if (!$('.ajax-dropdown').is(e.target)// if the target of the click isn't the container...
            && $('.ajax-dropdown').has(e.target).length === 0) {
            $('.ajax-dropdown').fadeOut(150);
            $('.ajax-dropdown').prev().removeClass("active")
        }
    });

    $('button[data-loading-text]').on('click', function() {
        var btn = $(this)
        btn.button('loading')
        setTimeout(function() {
            btn.button('reset')
        }, 3000)
    });

    // NOTIFICATION IS PRESENT

    function notification_check() {
        var self = $('#activity > .badge');

        if (parseInt(self.text()) > 0) {
            self.addClass("bg-color-red bounceIn animated")
        }
    }

    notification_check();

    // RESET WIDGETS
    $('#refresh').click(function(e) {
        $.SmartMessageBox({
            title : "<i class='fa fa-refresh' style='color:green'></i> Clear Local Storage",
            content : "Would you like to RESET all your saved widgets and clear LocalStorage?",
            buttons : '[No][Yes]'
        }, function(ButtonPressed) {
            if (ButtonPressed == "Yes" && localStorage) {
                localStorage.clear();
                location.reload();
            }

        });
        e.preventDefault();
    });

    // LOGOUT BUTTON
    $('#logout a').click(function(e) {
        //get the link
        $.loginURL = $(this).attr('href');

        // ask verification
        $.SmartMessageBox({
            title : "<i class='fa fa-sign-out txt-color-orangeDark'></i> Logout <span class='txt-color-orangeDark'><strong>" + $('#show-shortcut').text() + "</strong></span> ?",
            content : "You can improve your security further after logging out by closing this opened browser",
            buttons : '[No][Yes]'

        }, function(ButtonPressed) {
            if (ButtonPressed == "Yes") {
                $.root_.addClass('animated fadeOutUp');
                setTimeout(logout, 1000)
            }

        });
        e.preventDefault();
    });

    /*
     * LOGOUT ACTION
     */

    function logout() {
        window.location = $.loginURL;
    }

    /*
     * SHORTCUTS
     */

    // SHORT CUT (buttons that appear when clicked on user name)
    $.shortcut_dropdown.find('a').click(function(e) {

        e.preventDefault();

        window.location = $(this).attr('href');
        setTimeout(shortcut_buttons_hide, 300);

    });

    // SHORTCUT buttons goes away if mouse is clicked outside of the area
    $(document).mouseup(function(e) {
        if (!$.shortcut_dropdown.is(e.target)// if the target of the click isn't the container...
            && $.shortcut_dropdown.has(e.target).length === 0) {
            shortcut_buttons_hide()
        }
    });

    // SHORTCUT ANIMATE HIDE
    function shortcut_buttons_hide() {
        $.shortcut_dropdown.animate({
            height : "hide"
        }, 300, "easeOutCirc");
        $.root_.removeClass('shortcut-on');

    }

    // SHORTCUT ANIMATE SHOW
    function shortcut_buttons_show() {
        $.shortcut_dropdown.animate({
            height : "show"
        }, 200, "easeOutCirc")
        $.root_.addClass('shortcut-on');
    }

    /* ACTIONS WITH MULTIPLES ROWS */
    // CheckBox para seleccionar o deseleccionar todo.
    $('#select_all_checkbox').click(function() {
        active_all_rows(this.checked);
    });

    $('.checkbox_row').on('change', function(){
        if ($(this).is(":checked")){
            $(this).parents("tr").addClass("highlight");
            selectedRows.push($(this).val());
        }else{
            $(this).parents("tr").removeClass("highlight");
            selectedRows.splice(selectedRows.indexOf($(this).attr('value')),1);
            if ($('#select_all_checkbox').is(':checked')){
                $('#select_all_checkbox').prop('checked', false);
            }
        }
    });

    'use strict';
    var originalSplice = Array.prototype.splice;
    Array.prototype.splice = function (start, deleteCount) {
        // turn arguments into a real, live array
        var args = Array.prototype.slice.call(arguments);
        // IE requires deleteCount; set default value if it doesn't exist
        if (deleteCount === undefined) {
            args[1] = this.length - start;
        }
        // call the original function with the patched arguments
        return originalSplice.apply(this, args);
    };
};

/* END DOCUMENT READY */

/*
 * VARIABLES
 * Description: All Global Vars
 */
	// Impacts the responce rate of some of the responsive elements (lower value affects CPU but improves speed)
	$.throttle_delay = 350;
	
	// The rate at which the menu expands revealing child elements on click
	$.menu_speed = 235;
	
	// Note: You will also need to change this variable in the "variable.less" file.
	$.navbar_height = 49; 

/*
 * APP DOM REFERENCES
 * Description: Obj DOM reference, please try to avoid changing these
 */	
	$.root_ = $('body');
	$.left_panel = $('#left-panel');
	$.shortcut_dropdown = $('#shortcut');

/*
 * APP CONFIGURATION
 * Description: Enable / disable certain theme features here
 */		
	$.navAsAjax = false; // Your left nav in your app will no longer fire ajax calls
	
	// Please make sure you have included "jarvis.widget.js" for this below feature to work
	$.enableJarvisWidgets = true;
	// $.enableJarvisWidgets needs to be true it to work (could potentially 
	// crash your webApp if you have too many widgets running on mobile view)	
	$.enableMobileWidgets = false;
	
	// Plugin dependency "smartclick.js"
	$.enableFastClick = false; // remove the 300 ms delay in iDevices


/*
 * DETECT MOBILE DEVICES
 * Description: Detects mobile device - if any of the listed device is detected
 * a class is inserted to $.root_ and the variable $.device is decleard. 
 */	

/* so far this is covering most hand held devices */
var ismobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));

	if (!ismobile) {
		// Desktop
		$.root_.addClass("desktop-detected");
		$.device = "desktop";
	} else {
		// Mobile
		$.root_.addClass("mobile-detected");
		$.device = "mobile";
		
		// remove 300ms delay from apple touch devices
		// dependency: plugin/smartclick/smartclick.js
		if ($.enableFastClick){
			$('nav ul a').noClickDelay();
			$('#hide-menu a').noClickDelay();
		}
	}

/* ~ END: CHECK MOBILE DEVICE */

/*
 * RESIZER WITH THROTTLE
 * Source: http://benalman.com/code/projects/jquery-resize/examples/resize/
 */

(function($, window, undefined) {

	var elems = $([]), jq_resize = $.resize = $.extend($.resize, {}), timeout_id, str_setTimeout = 'setTimeout', str_resize = 'resize', str_data = str_resize + '-special-event', str_delay = 'delay', str_throttle = 'throttleWindow';

	jq_resize[str_delay] = $.throttle_delay;

	jq_resize[str_throttle] = true;

	$.event.special[str_resize] = {

		setup : function() {
			if (!jq_resize[str_throttle] && this[str_setTimeout]) {
				return false;
			}

			var elem = $(this);
			elems = elems.add(elem);
			$.data(this, str_data, {
				w : elem.width(),
				h : elem.height()
			});
			if (elems.length === 1) {
				loopy();
			}
		},
		teardown : function() {
			if (!jq_resize[str_throttle] && this[str_setTimeout]) {
				return false;
			}

			var elem = $(this);
			elems = elems.not(elem);
			elem.removeData(str_data);
			if (!elems.length) {
				clearTimeout(timeout_id);
			}
		},

		add : function(handleObj) {
			if (!jq_resize[str_throttle] && this[str_setTimeout]) {
				return false;
			}
			var old_handler;

			function new_handler(e, w, h) {
				var elem = $(this), data = $.data(this, str_data);
				data.w = w !== undefined ? w : elem.width();
				data.h = h !== undefined ? h : elem.height();

				old_handler.apply(this, arguments);
			};
			if ($.isFunction(handleObj)) {
				old_handler = handleObj;
				return new_handler;
			} else {
				old_handler = handleObj.handler;
				handleObj.handler = new_handler;
			}
		}
	};

	function loopy() {
		timeout_id = window[str_setTimeout](function() {
			elems.each(function() {
				var elem = $(this), width = elem.width(), height = elem.height(), data = $.data(this, str_data);
				if (width !== data.w || height !== data.h) {
					elem.trigger(str_resize, [data.w = width, data.h = height]);
				}

			});
			loopy();

		}, jq_resize[str_delay]);

	};

})(jQuery, this);

/*
* NAV OR #LEFT-BAR RESIZE DETECT
* Description: changes the page min-width of #CONTENT and NAV when navigation is resized.
* This is to counter bugs for min page width on many desktop and mobile devices.
* Note: This script uses JSthrottle technique so don't worry about memory/CPU usage
*/

// Fix page and nav height
function nav_page_height() {
	setHeight = $('#main').height();
	menuHeight = $.left_panel.height();
	windowHeight = $(window).height() - $.navbar_height;
	//set height

	if (setHeight > windowHeight) {// if content height exceedes actual window height and menuHeight
		$.left_panel.css('min-height', setHeight + 'px');
		$.root_.css('min-height', setHeight + $.navbar_height + 'px');

	} else {
		$.left_panel.css('min-height', windowHeight + 'px');
		$.root_.css('min-height', windowHeight + 'px');
	}
}

$('#main').resize(function() {
	nav_page_height();
	check_if_mobile_width();
})

$('nav').resize(function() {
	nav_page_height();
})

function check_if_mobile_width() {
	if ($(window).width() < 979) {
		$.root_.addClass('mobile-view-activated')
	} else if ($.root_.hasClass('mobile-view-activated')) {
		$.root_.removeClass('mobile-view-activated');
	}
}

/* ~ END: NAV OR #LEFT-BAR RESIZE DETECT */

/*
 * DETECT IE VERSION
 * Description: A short snippet for detecting versions of IE in JavaScript
 * without resorting to user-agent sniffing
 * RETURNS:
 * If you're not in IE (or IE version is less than 5) then:
 * //ie === undefined
 *
 * If you're in IE (>=5) then you can determine which version:
 * // ie === 7; // IE7
 *
 * Thus, to detect IE:
 * // if (ie) {}
 *
 * And to detect the version:
 * ie === 6 // IE6
 * ie > 7 // IE8, IE9 ...
 * ie < 9 // Anything less than IE9
 */

// TODO: delete this function later on - no longer needed (?)
var ie = ( function() {

		var undef, v = 3, div = document.createElement('div'), all = div.getElementsByTagName('i');

		while (div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->', all[0]);

		return v > 4 ? v : undef;

	}()); // do we need this? 

/* ~ END: DETECT IE VERSION */

/*
 * CUSTOM MENU PLUGIN
 */

$.fn.extend({

	//pass the options variable to the function
	jarvismenu : function(options) {

		var defaults = {
			accordion : 'true',
			speed : 200,
			closedSign : '[+]',
			openedSign : '[-]'
		};

		// Extend our default options with those provided.
		var opts = $.extend(defaults, options);
		//Assign current element to variable, in this case is UL element
		var $this = $(this);

		//add a mark [+] to a multilevel menu
		$this.find("li").each(function() {
			if ($(this).find("ul").size() != 0) {
				//add the multilevel sign next to the link
				$(this).find("a:first").append("<b class='collapse-sign'>" + opts.closedSign + "</b>");

				//avoid jumping to the top of the page when the href is an #
				if ($(this).find("a:first").attr('href') == "#") {
					$(this).find("a:first").click(function() {
						return false;
					});
				}
			}
		});

		//open active level
		$this.find("li.active").each(function() {
			$(this).parents("ul").slideDown(opts.speed);
			$(this).parents("ul").parent("li").find("b:first").html(opts.openedSign);
			$(this).parents("ul").parent("li").addClass("open")
		});

		$this.find("li a").click(function() {

			if ($(this).parent().find("ul").size() != 0) {

				if (opts.accordion) {
					//Do nothing when the list is open
					if (!$(this).parent().find("ul").is(':visible')) {
						parents = $(this).parent().parents("ul");
						visible = $this.find("ul:visible");
						visible.each(function(visibleIndex) {
							var close = true;
							parents.each(function(parentIndex) {
								if (parents[parentIndex] == visible[visibleIndex]) {
									close = false;
									return false;
								}
							});
							if (close) {
								if ($(this).parent().find("ul") != visible[visibleIndex]) {
									$(visible[visibleIndex]).slideUp(opts.speed, function() {
										$(this).parent("li").find("b:first").html(opts.closedSign);
										$(this).parent("li").removeClass("open");
									});

								}
							}
						});
					}
				}// end if
				if ($(this).parent().find("ul:first").is(":visible") && !$(this).parent().find("ul:first").hasClass("active")) {
					$(this).parent().find("ul:first").slideUp(opts.speed, function() {
						$(this).parent("li").removeClass("open");
						$(this).parent("li").find("b:first").delay(opts.speed).html(opts.closedSign);
					});

				} else {
					$(this).parent().find("ul:first").slideDown(opts.speed, function() {
						/*$(this).effect("highlight", {color : '#616161'}, 500); - disabled due to CPU clocking on phones*/
						$(this).parent("li").addClass("open");
						$(this).parent("li").find("b:first").delay(opts.speed).html(opts.openedSign);
					});
				} // end else
			} // end if
		});
	} // end function
});

/* ~ END: CUSTOM MENU PLUGIN */

/*
 * ELEMENT EXIST OR NOT
 * Description: returns true or false
 * Usage: $('#myDiv').doesExist();
 */

jQuery.fn.doesExist = function() {
	return jQuery(this).length > 0;
};

/* ~ END: ELEMENT EXIST OR NOT */

/*
 * INITIALIZE FORMS
 * Description: Select2, Masking, Datepicker, Autocomplete
 */

function runAllForms() {

	/*
	 * BOOTSTRAP SLIDER PLUGIN
	 * Usage:
	 * Dependency: js/plugin/bootstrap-slider
	 */
	if ($.fn.slider) {
		$('.slider').slider();
	}

	/*
	 * SELECT2 PLUGIN
	 * Usage:
	 * Dependency: js/plugin/select2/
	 */
	if ($.fn.select2) {
		$('.select2').each(function() {
			$this = $(this);
			var width = $this.attr('data-select-width') || '100%';
			//, _showSearchInput = $this.attr('data-select-search') === 'true';
			$this.select2({
				//showSearchInput : _showSearchInput,
				allowClear : true,
				width : width
			})
		})
	}

	/*
	 * MASKING
	 * Dependency: js/plugin/masked-input/
	 */
	if ($.fn.mask) {
		$('[data-mask]').each(function() {

			$this = $(this);
			var mask = $this.attr('data-mask') || 'error...', mask_placeholder = $this.attr('data-mask-placeholder') || 'X';

			$this.mask(mask, {
				placeholder : mask_placeholder
			});
		})
	}

	/*
	 * Autocomplete
	 * Dependency: js/jqui
	 */
	if ($.fn.autocomplete) {
		$('[data-autocomplete]').each(function() {

			$this = $(this);
			var availableTags = $this.data('autocomplete') || ["The", "Quick", "Brown", "Fox", "Jumps", "Over", "Three", "Lazy", "Dogs"];

			$this.autocomplete({
				source : availableTags
			});
		})
	}

	/*
	 * JQUERY UI DATE
	 * Dependency: js/libs/jquery-ui-1.10.3.min.js
	 * Usage:
	 */
	if ($.fn.datepicker) {
		$('.datepicker').each(function() {

			$this = $(this);
			var dataDateFormat = $this.attr('data-dateformat') || 'dd.mm.yy';

			$this.datepicker({
				dateFormat : dataDateFormat,
				prevText : '<i class="fa fa-chevron-left"></i>',
				nextText : '<i class="fa fa-chevron-right"></i>'
			});
		})
	}

	/*
	 * AJAX BUTTON LOADING TEXT
	 * Usage: <button type="button" data-loading-text="Loading..." class="btn btn-xs btn-default ajax-refresh"> .. </button>
	 */
	$('button[data-loading-text]').on('click', function() {
		var btn = $(this)
		btn.button('loading')
		setTimeout(function() {
			btn.button('reset')
		}, 3000)
	});

}

/* ~ END: INITIALIZE FORMS */


/*
 * INITIALIZE JARVIS WIDGETS
 */

// Setup Desktop Widgets
function setup_widgets_desktop() {

	if ($.fn.jarvisWidgets && $.enableJarvisWidgets) {

		$('#widget-grid').jarvisWidgets({

			grid : 'article',
			widgets : '.jarviswidget',
			localStorage : true,
			deleteSettingsKey : '#deletesettingskey-options',
			settingsKeyLabel : 'Reset settings?',
			deletePositionKey : '#deletepositionkey-options',
			positionKeyLabel : 'Reset position?',
			sortable : true,
			buttonsHidden : false,
			// toggle button
			toggleButton : true,
			toggleClass : 'fa fa-minus | fa fa-plus',
			toggleSpeed : 200,
			onToggle : function() {
			},
			// delete btn
			deleteButton : true,
			deleteClass : 'fa fa-times',
			deleteSpeed : 200,
			onDelete : function() {
			},
			// edit btn
			editButton : true,
			editPlaceholder : '.jarviswidget-editbox',
			editClass : 'fa fa-cog | fa fa-save',
			editSpeed : 200,
			onEdit : function() {
			},
			// color button
			colorButton : true,
			// full screen
			fullscreenButton : true,
			fullscreenClass : 'fa fa-resize-full | fa fa-resize-small',
			fullscreenDiff : 3,
			onFullscreen : function() {
			},
			// custom btn
			customButton : false,
			customClass : 'folder-10 | next-10',
			customStart : function() {
				alert('Hello you, this is a custom button...')
			},
			customEnd : function() {
				alert('bye, till next time...')
			},
			// order
			buttonOrder : '%refresh% %custom% %edit% %toggle% %fullscreen% %delete%',
			opacity : 1.0,
			dragHandle : '> header',
			placeholderClass : 'jarviswidget-placeholder',
			indicator : true,
			indicatorTime : 600,
			ajax : true,
			timestampPlaceholder : '.jarviswidget-timestamp',
			timestampFormat : 'Last update: %m%/%d%/%y% %h%:%i%:%s%',
			refreshButton : true,
			refreshButtonClass : 'fa fa-refresh',
			labelError : 'Sorry but there was a error:',
			labelUpdated : 'Last Update:',
			labelRefresh : 'Refresh',
			labelDelete : 'Delete widget:',
			afterLoad : function() {
			},
			rtl : false, // best not to toggle this!
			onChange : function() {
				
			},
			onSave : function() {
				
			},
			ajaxnav : $.navAsAjax // declears how the localstorage should be saved

		});

	}

}

// Setup Desktop Widgets
function setup_widgets_mobile() {

	if ($.enableMobileWidgets && $.enableJarvisWidgets) {
		setup_widgets_desktop();
	}

}

/* ~ END: INITIALIZE JARVIS WIDGETS */



/*
* APP AJAX REQUEST SETUP
* Description: Executes and fetches all ajax requests also
* updates naivgation elements to active
*/
if($.navAsAjax)
{
    // fire this on page load if nav exists
    if ($('nav').length) {
	    checkURL();
    };

    $(document).on('click', 'nav a[href!="#"]', function(e) {
	    e.preventDefault();
	    $this = $(e.currentTarget);

	    // if parent is not active then get hash, or else page is assumed to be loaded
	    if (!$this.parent().hasClass("active") && !$this.attr('target')) {

		    // update window with hash
		    // you could also do here:  $.device === "mobile" - and save a little more memory
		
		    if ($.root_.hasClass('mobile-view-activated')) {
			    $.root_.removeClass('hidden-menu');
			    window.setTimeout(function() {
				    window.location.hash = $this.attr('href')
			    }, 150);
			    // it may not need this delay...
		    } else {
			    window.location.hash = $this.attr('href');
		    }
	    }

    });

    // fire links with targets on different window
    $(document).on('click', 'nav a[target="_blank"]', function(e) {
	    e.preventDefault();
	    $this = $(e.currentTarget);

	    window.open($this.attr('href'));
    });

    // fire links with targets on same window
    $(document).on('click', 'nav a[target="_top"]', function(e) {
	    e.preventDefault();
	    $this = $(e.currentTarget);

	    window.location = ($this.attr('href'));
    });

    // all links with hash tags are ignored
    $(document).on('click', 'nav a[href="#"]', function(e) {
	    e.preventDefault();
    });

    // DO on hash change
    $(window).on('hashchange', function() {
	    checkURL();
    });
}

// CHECK TO SEE IF URL EXISTS
function checkURL() {

	//get the url by removing the hash
	url = location.hash.replace(/^#/, '');

	container = $('#content');
	// Do this if url exists (for page refresh, etc...)
	if (url) {
		// remove all active class
		$('nav li.active').removeClass("active");
		// match the url and add the active class
		$('nav li:has(a[href="' + url + '"])').addClass("active");
		title = ($('nav a[href="' + url + '"]').attr('title'))

		// change page title from global var
		document.title = (title || document.title);
		//console.log("page title: " + document.title);

		// parse url to jquery
		loadURL(url, container);
	} else {

		// grab the first URL from nav
		$this = $('nav > ul > li:first-child > a[href!="#"]');

		//update hash
		window.location.hash = $this.attr('href');

	}

}

// LOAD AJAX PAGES

function loadURL(url, container) {
	//console.log(container)

	$.ajax({
		type : "GET",
		url : url,
		dataType : 'html',
		cache : true, // (warning: this will cause a timestamp and will call the request twice)
		beforeSend : function() {
			// cog placed
			container.html('<h1><i class="fa fa-cog fa-spin"></i> Loading...</h1>');

			// only draw breadcrumb if it is content material
			// TODO: check if document title injection refreshes in IE...
			// TODO: see the framerate for the animation in touch devices
			if (container[0] == $("#content")[0]) {
				drawBreadCrumb();
				// update title with breadcrumb...
				document.title = $(".breadcrumb li:last-child").text();
				// scroll up
				$("html, body").animate({
					scrollTop : 0
				}, "fast");
			} else {
				container.animate({
					scrollTop : 0
				}, "fast");
			}
		},
		/*complete: function(){
	    	// Handle the complete event
	    	// alert("complete")
		},*/
		success : function(data) {
			// cog replaced here...
			// alert("success")
			
			container.css({
				opacity : '0.0'
			}).html(data).delay(50).animate({
				opacity : '1.0'
			}, 300);
			

		},
		error : function(xhr, ajaxOptions, thrownError) {
			container.html('<h4 style="margin-top:10px; display:block; text-align:left"><i class="fa fa-warning txt-color-orangeDark"></i> Error 404! Page not found.</h4> <br>Or you are running this page from your hard drive. Please make sure for all ajax calls your page needs to be hosted in a server');
		},
		async : false
	});

	//console.log("ajax request sent");
}

// UPDATE BREADCRUMB
function drawBreadCrumb() {

	//console.log("breadcrumb")
	$("#ribbon ol.breadcrumb").empty();
	$("#ribbon ol.breadcrumb").append($("<li>Home</li>"));
	$('nav li.active > a').each(function() {
		$("#ribbon ol.breadcrumb").append($("<li></li>").html($.trim($(this).clone().children(".badge").remove().end().text())));
	});

}

/* ~ END: APP AJAX REQUEST SETUP */

/*
 * PAGE SETUP
 * Description: fire certain scripts that run through the page
 * to check for form elements, tooltip activation, popovers, etc...
 */
function pageSetUp() {

	if ($.device === "desktop"){
		// is desktop
		
		// activate tooltips
		$("[rel=tooltip]").tooltip();
	
		// activate popovers
		$("[rel=popover]").popover();
	
		// activate popovers with hover states
		$("[rel=popover-hover]").popover({
			trigger : "hover"
		});
	
		// setup widgets
		setup_widgets_desktop();
	
		//setup nav height (dynamic)
		nav_page_height();
	
		// run form elements
		runAllForms();

	} else {
		
		// is mobile
		
		// activate popovers
		$("[rel=popover]").popover();
	
		// activate popovers with hover states
		$("[rel=popover-hover]").popover({
			trigger : "hover"
		});
	
		// setup widgets
		setup_widgets_mobile();
	
		//setup nav height (dynamic)
		nav_page_height();
	
		// run form elements
		runAllForms();
		
	}

}

// Keep only 1 active popover per trigger - also check and hide active popover if user clicks on document
$('body').on('click', function(e) {
	$('[rel="popover"]').each(function() {
		//the 'is' for buttons that trigger popups
		//the 'has' for icons within a button that triggers a popup
		if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
			$(this).popover('hide');
		}
	});
});

/* ACTIONS WITH MULTIPLES ROWS */
function active_all_rows(d){
    selectedRows = []; // Reinicio el array

    $('.checkbox_row').prop('checked',d);
    if (d){
        $('.checkbox_row').parents("tr").addClass("highlight");
        $('.checkbox_row').each(function(){
            selectedRows.push($(this).attr('value'));
        });
    }else {
        $('.checkbox_row').parents("tr").removeClass("highlight");
    }
}

function remove_selected_rows(){
    if (selectedRows.length !== 0){
        $("#dialog-confirm").html(DELETE_SELECTED_ROWS_MSG);
        $("#dialog-confirm").dialog({
            resizable: false,
            modal: false,
            title: DELETE_SELECTED_ROWS_MSG_TITLE,
            height: 300,
            width: 400,
            buttons: {
                "Yes": function () {
                    $(this).dialog('close');
                    for (var i=0; i<selectedRows.length; i++){
                        window.location.href = DELETE_URL_BASE+"/"+selectedRows[i];
                    }
                },
                "No": function () {
                    $(this).dialog('close');
                }
            }
        });
    }
}