/*
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2019 PrestaShop SA
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

var feedbackElement;

$(document).ready(function() {
    if (selectorFront) {
        $(selectorFront).addClass('borderClass');
        if (!isScrolledIntoView($(selectorFront))) {
            scrollToView($(selectorFront));
        }
    }
    
    function scrollToView(element){
        var offset = element.offset().top;
        if(!element.is(":visible")) {
            element.css({"visibility":"hidden"}).show();
            var offset = element.offset().top;
            element.css({"visibility":"", "display":""});
        }

        var visible_area_start = $(window).scrollTop();
        var visible_area_end = visible_area_start + window.innerHeight;

        if(offset < visible_area_start || offset > visible_area_end){
            // Not in view so scroll to it
            $('html,body').animate({scrollTop: offset - window.innerHeight/3}, 1000);
            return false;
        }
        return true;
    }
    
    /**
     * Check if cookie is set
     */
    displayByCookie();
    
    /**
     * Emoji click action
     */
    $('.ratings .em').click(function() {
        $('.ratings .em').removeClass('emoji-active');
        $(this).addClass('emoji-active');
    });
    
    /**
     * Display feedback popup at button click
     */
    $(document).on('click', "button#feedBackPRO", function(e) {
        e.preventDefault();
        showPopup("#feedbackPopOverlay");
        if ($('#specificF').val() == 0) {
            animateEmojisGeneral();
        }
    });
    
    /**
     * Close popups at button close
     */
    $(document).on('click',"#feedbackPop-close-button" ,function(e){
        e.preventDefault();
        resetEmojis();
        hidePopup('feedback');
    });
    
    $(document).on('click',"#specificFeedbackPop-close-button" ,function(e){
        e.preventDefault();
        resetEmojis();
        hidePopup('specificFeedback');
    });
    
    $(document).on('click',"#thankyouPop-close-button" ,function(){
        hidePopup('thankyou');
    });
    
    $(document).on('click',"#thankyouPopOverlay" ,function(){
        hidePopup('thankyou');
    });

    /**
     * Display specific feedback
     */
    $(document).on('click',".feedback.specific" ,function(e){
        e.preventDefault();
        hidePopup('feedback', 1);
        showPopup(".specificInstructions-overlay");
        $('.specificInstructions-overlay').addClass('instructionsVisible');
    });
    
    $(document).on('click',".feedback.general" ,function(e){
        e.preventDefault();
        $('.feedbackButtons').hide(0);
        $('.feedbackPopForm').show(0);
        animateEmojisGeneral();
    });

    /**
     * Hide feedback popup
     */
    $(document).on('click',"#feedbackPopOverlay" ,function(){
        hidePopup('feedback');
        resetEmojis();
    });

    $(document).on('click',"#feedbackPopOverlay .feedbackPopContent" ,function(e){
        e.stopPropagation();
    });
    
    /**
     * hide specific feedback instructions
     * popup
     */
    $(document).on('click',".specificInstructions-overlay" ,function(){
        hidePopup('instructions', 1);
    });

    /**
     * MOBILE LISTENER FOR SPECIFIC ELEMENT
     */
    window.addEventListener('touchstart', function() {
        if ($('.specificInstructions-overlay').hasClass('instructionsVisible')) {
            hidePopup('instructions');
            $('.feedbackProInputs').removeClass('instructionsVisible');
        }
    });

    $(document).on('click',".specificInstructions-overlay .specificInstructions" ,function(e){
        e.stopPropagation();
    });
    
    /**
     * Hide specific feedback popup
     */
    $(document).on('click',".specificFeedback-overlay" ,function(){
        resetEmojis();
        hidePopup('specificFeedback');
    });

    $(document).on('click',".specificFeedback-overlay .specificFeedbackContent" ,function(e){
        e.stopPropagation();
    });
    
    /**
     * Display select after rating emoji selected
     */
    $(document).on('click',"ul.ratings > li > label" ,function(){
        if (!$('.feedbackProInputs').hasClass('displayed')) {
            $('.feedbackProInputs').addClass('displayed');
        }
        if (!subjectsDisplayed) {
            if ($('.feedbackPopContent').is(':visible')) {
                $('.feedbackTextArea').show();
                $('.feedbackProGeneralNotes').show();
                $('#rateSubmit').show();
            } else if ($('.specificFeedbackContent').is(':visible')) {
                $('.feedbackSpecificTextArea').show();
                $('.feedbackProSpecificNotes').show();
                $('#specificSubmit').show();
            }
        }
    });
    displayInputsAtSelect();
    uniformRadio();
    uniformResize();
});

/**
 * Display comment area, email input
 * notes & submit button after selecting reason
 */
function displayInputsAtSelect() {
    $('.feedbackSubjects').on('change', function () {
        optionSelected = $('.feedbackSubjects').val();
        if (optionSelected != 'default') {
            $('.feedbackTextArea').slideDown(250);
            $('.feedbackProGeneralNotes').slideDown(200);
            $('#rateSubmit').slideDown(150);
        } else {
            $('#rateSubmit').slideUp(250);
            $('.feedbackProGeneralNotes').slideUp(200);
            $('.feedbackTextArea').slideUp(150);
        }
    });
    $('.feedbackSpecificSubjects').on('change', function () {
        optionSelected = $('.feedbackSpecificSubjects').val();
        if (optionSelected != 'default') {
            $('.feedbackSpecificTextArea').slideDown(250);
            $('.feedbackProSpecificNotes').slideDown(200);
            $('#specificSubmit').slideDown(150);
        } else {
            $('#specificSubmit').slideUp(250);
            $('.feedbackProSpecificNotes').slideUp(200);
            $('.feedbackSpecificTextArea').slideUp(150);
        }
    });
}

function showPopup($element) {
    $('button#feedBackPRO').fadeOut(0);
    $($element).show();
    $('body').addClass('noscroll');
}

function hidePopup($case, transitionNbf = 0) {
    $('ul.ratings > li > label > input').attr('checked', false);
    if (transitionNbf == 0) {
        $('button#feedBackPRO').fadeIn(0);
    }
    $('.feedbackProInputs').removeClass('displayed');
    if ($case == 'feedback') {
        $('.feedbackProGeneralNotes').removeAttr("style");
        $('#rateSubmit').removeAttr("style");
        $('.feedbackTextArea').removeAttr("style");
        $('.feedbackSubjects').val('default');
        $("#feedbackPopOverlay").hide();
        $('.feedbackButtons').slideDown(100);
        if (specificEnabled) {
            $('.feedbackPopForm').hide();
        }
    } else if ($case == 'instructions') {
        $(".specificInstructions-overlay").hide();
        borderSpecificElement();
    } else if ($case == 'specificFeedback') {
        $('.feedbackProSpecificNotes').removeAttr("style");
        $(".specificFeedback-overlay").hide();
        $('#specificSubmit').removeAttr("style");
        $('.feedbackSpecificTextArea').removeAttr("style");
        $('.feedbackSpecificSubjects').val('default');
        if (specificEnabled) {
            $('.feedbackPopForm').hide();
        }
        if ($('.mobileSpecificHidden').length) {
            $('.mobileSpecificHidden').remove();
        }
        if ($('.getMobileSpecific').length) {
            $('.getMobileSpecific').remove();
        }
        $('body').unbind('touchstart');
    } else if ($case == 'thankyou') {
        $('#thankyouPopOverlay').hide();
    }

    $('body').removeClass('noscroll');
}

function uniformRadio() {
    if (psVersion == '1.6') {
        $(window).load(function() {
            $.uniform.restore(".noUniform");
        });
    }
}

/**
 * Specific feedback border &
 * get path after submit
 */
function borderSpecificElement() {
    if (window.ui.mobile) {
        $(document).on('touchstart', function() {
            documentClick = true;
        });
        $(document).on('touchmove', function() {
            documentClick = false;
        });
        $('body').bind('touchstart',function getSpecificElement(e) {
            e.stopImmediatePropagation();
            if (!$('.mobileSpecificHidden').length) {
                e.preventDefault();
            }
            if ($('.mobileSpecific').length) {
                $('.mobileSpecific').removeClass('mobileSpecific');
            }
            
            if (!$('.mobileSpecificHidden').length) {
                if (!$(e.target).is('div')) {
                    $(e.target).closest('div').addClass('mobileSpecific');
                    var offset = $(e.target).closest('div').offset();
                    var width = $(e.target).closest('div').width();
                    var height = $(e.target).closest('div').height();
                    var centerX = offset.left + width / 2;
                    var centerY = offset.top + height / 2;
                    $('body').append("<button style='position:absolute;text-align:center;' class='getMobileSpecific'>Select</button>");
                    $('.getMobileSpecific').css('top', '' + centerY + 'px');
                    $('.getMobileSpecific').css('left', '' + centerX + 'px');
                    $(e.target).closest('div').removeClass('mobileSpecific');
                    feedbackElement = $(e.target).fullSelector();
                    $(e.target).closest('div').addClass('mobileSpecific');
                    $('body').unbind('touchstart');
                }
            }
        });
    } else {
        window.onmouseover = function (e) {
            $(e.target).addClass('borderClass');
            $(document).on('click', $(e.target), function (e) {
                if ($(e.target).hasClass('borderClass')) {
                    e.preventDefault();
                    $(e.target).removeClass('borderClass');
                    feedbackElement = $(e.target).fullSelector();
                    unbindMouseOver();
                    showPopup(".specificFeedback-overlay");
                    $('.feedbackPopForm').show();
                    animateEmojisSpecific();
                }
            });
        };
    }
    window.onmouseout=function(e) {
        $(e.target).removeClass('borderClass');
    };
}

$(document).on('click touchend', '.getMobileSpecific', function () {
    if (!$('.mobileSpecificHidden').length) {
        $('body').append("<input hidden class='mobileSpecificHidden'>");
    }
    showPopup(".specificFeedback-overlay");
    $('.feedbackPopForm').show();
    animateEmojisSpecific();
    $('.getMobileSpecific').remove();
});

/**
 * Get path on click after specific feedback
 *
 * @returns {string}
 */
$.fn.fullSelector = function () {
    var path = this.parents().addBack();
    var quickCss = path.get().map(function (item) {
        var self = $(item),
            id = item.id ? '#' + item.id : '',
            clss = item.classList.length ? item.classList.toString().split(' ').map(function (c) {
                return '.' + c;
            }).join('') : '',
            name = item.nodeName.toLowerCase(),
            index = self.siblings(name).length ? ':nth-child(' + (self.index() + 1) + ')' : '';

        if (name === 'html' || name === 'body') {
            return name;
        }
        return name + index + id + clss;

    }).join(' > ');
    
    return quickCss;
};

/**
 * After path is submitted, unbind specific feedback
 *
 */
function unbindMouseOver() {
    window.onmouseover=function() {
        return false;
    };
    window.onmouseout=function() {
        return false;
    };
}

/**
 * Submit general feedback
 * @type {*|void|jQuery}
 */
function generalAjax() {
    var ajaxData = {
        fbRating : $('input[name="feedbackRating"]:checked').val(),
        fbSubject : $('select[name="feedbackSubjects"] option:selected').text(),
        fbComment : $('textarea[name="feedbackTArea"]').val(),
        fbEmail : $('input[name="feedbackGeneralEmail"]').val(),
        fbNote : $('input[name="feedbackGeneralNotes"]:checked').val(),
        fbLink : window.location.href,
        action : 'generalFeedback',
        fbPage : pageController,
        fbLanguage: fbLanguage,
        fbView : window.ui.mobile ? "Mobile" : "Desktop",
        fbOS : window.ui.os + ' ' + parseFloat(window.ui.osversion),
        fbBrowser : window.ui.browser + ' ' + parseInt(window.ui.version),
        fbResolution : $(window).width() + 'x' + $(window).height(),
    };
    $.ajax({
        data: ajaxData,
        type: 'post',
        url: pathAjax,
        success: function() {
            removePopup('general');
            $('#thankyouPopOverlay').show();
            if (cookieMins) {
                createCookie('feedbackPRO', '1', cookieMins);
            }
        },
    });
}

/**
 * Submit specific feedback
 */
function specificAjax() {
    var ajaxData = {
        fbRating : $('input[name="feedbackRating"]:checked').val(),
        fbSubject : $('select[name="feedbackSpecificSubjects"] option:selected').text(),
        fbComment : $('textarea[name="feedbackSpecificTArea"]').val(),
        fbEmail : $('input[name="feedbackSpecificEmail"]').val(),
        fbNote : $('input[name="feedbackSpecificNotes"]:checked').val(),
        action : 'specificFeedback',
        fbLink : window.location.href,
        fbPage : pageController,
        fbLanguage: fbLanguage,
        fbSelector : feedbackElement,
        fbView : window.ui.mobile ? "Mobile" : "Desktop",
        fbOS : window.ui.os + ' ' + parseFloat(window.ui.osversion),
        fbBrowser : window.ui.browser + ' ' + parseInt(window.ui.version),
        fbResolution : $(window).width() + 'x' + $(window).height(),
    };
    $.ajax({
        data: ajaxData,
        datatype: 'json',
        type: 'post',
        url: pathAjax,
        success: function() {
            removePopup('specific');
            $('#thankyouPopOverlay').show();
            if (cookieMins) {
                createCookie('feedbackPRO', '1', cookieMins);
            }
        },
    });
}

/**
 * Remove pop-up, feedback button and
 * create cookie
 */
function removePopup($case) {
    if ($case == 'general') {
        $('#feedbackPopOverlay').remove();
    } else if ($case == 'specific') {
        $('.specificFeedback-overlay').remove();
    }
    $('button#feedBackPRO').remove();
}

/**
 * Check cookie to display
 */
function displayByCookie() {
    if (!getCookieFB('feedbackPRO')) {
        $('button#feedBackPRO').show();
    }
}

/**
 * RE-RUN ANTI UNIFORM AT WINDOW RESIZE
 * @param event
 */
function uniformResize() {
    if (psVersion == '1.6') {
        window.onresize = function (event) {
            $.uniform.restore(".noUniform");
        };
    }
}

$.fn.addBack = function (selector) {
    return this.add(selector == null ? this.prevObject : this.prevObject.filter(selector));
}

/**
 * Get all technical info at ajax submit
 *
 */
;(function (window) {
    var browser,
        version,
        mobile,
        os,
        osversion,
        bit,
        ua = window.navigator.userAgent,
        platform = window.navigator.platform;
    //Internet Explorer
    if ( /MSIE/.test(ua) ) {

        browser = 'Internet Explorer';

        if ( /IEMobile/.test(ua) ) {
            mobile = 1;
        }

        version = /MSIE \d+[.]\d+/.exec(ua)[0].split(' ')[1];

        //Google Chrome
    } else if ( /Chrome/.test(ua) ) {

        //Chromebooks
        if ( /CrOS/.test(ua) ) {
            platform = 'CrOS';
        }
        browser = 'Chrome';
        version = /Chrome\/[\d\.]+/.exec(ua)[0].split('/')[1];

        // Opera Browser
    } else if ( /Opera/.test(ua) ) {

        browser = 'Opera';

        if ( /mini/.test(ua) || /Mobile/.test(ua) ) {
            mobile = 1;
        }

        //Android Browser
    } else if ( /Android/.test(ua) ) {

        browser = 'Android Webkit Browser';
        mobile = 1;
        os = /Android\s[\.\d]+/.exec(ua)[0];

        //Mozilla firefox
    } else if ( /Firefox/.test(ua) ) {

        browser = 'Firefox';

        if ( /Fennec/.test(ua) ) {
            mobile = 1;
        }
        version = /Firefox\/[\.\d]+/.exec(ua)[0].split('/')[1];

        //Safari
    } else if ( /Safari/.test(ua) ) {

        browser = 'Safari';

        if ( (/iPhone/.test(ua)) || (/iPad/.test(ua)) || (/iPod/.test(ua)) ) {
            os = 'iOS';
            mobile = 1;
        }

    }
    if ( !version ) {

        version = /Version\/[\.\d]+/.exec(ua);

        if (version) {
            version = version[0].split('/')[1];
        } else {
            version = /Opera\/[\.\d]+/.exec(ua)[0].split('/')[1];
        }

    }

    if ( platform === 'MacIntel' || platform === 'MacPPC' ) {
        os = 'Mac OS X';
        osversion = /10[\.\_\d]+/.exec(ua)[0];
        if ( /[\_]/.test(osversion) ) {
            osversion = osversion.split('_').join('.');
        }
    } else if ( platform === 'CrOS' ) {
        os = 'ChromeOS';
    } else if ( platform === 'Win32' || platform == 'Win64' ) {
        os = 'Windows';
        bit = platform.replace(/[^0-9]+/,'');
    } else if ( !os && /Android/.test(ua) ) {
        os = 'Android';
    } else if ( !os && /Linux/.test(platform) ) {
        os = 'Linux';
    } else if ( !os && /Windows/.test(ua) ) {
        os = 'Windows';
    }
    window.ui = {
        browser : browser,
        version : version,
        mobile : mobile,
        os : os,
        osversion : osversion,
        bit: bit
    };
}(this));

/**
 * Create cookie function
 * @param name
 * @param value
 * @param minutes
 */
function createCookie(name,value,minutes) {
    var expires = "";
    // var value = "";
    if (minutes) {
        var date = new Date();
        date.setTime(date.getTime() + (minutes*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}

/**
 * Check for cookie
 * @param name
 * @returns {*}
 */
function getCookieFB(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

var elemTop;
function isScrolledIntoView(elem)
{
    docViewTop = $(window).scrollTop();
    docViewBottom = docViewTop + $(window).height();

    elemTop = $(elem).offset().top;
    elemBottom = elemTop + $(elem).height();

    return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom)
        && (elemBottom <= docViewBottom) &&  (elemTop >= docViewTop) );
}

function animateEmojisGeneral() {
    var a = $('.feedbackPopContent .ratings .em');
    $.each(a, function(index) {
        var that = this;
        setTimeout(function() {
            $(that).addClass('fadeEmoji');
        }, 80 * index);
    });
}

function animateEmojisSpecific() {
    var a = $('.specificFeedbackContent .ratings .em');
    $.each(a, function(index) {
        var that = this;
        setTimeout(function() {
            $(that).addClass('fadeEmoji');
        }, 80 * index);
    });
}

function resetEmojis() {
    $('.feedbacktext-error').remove();
    $('.ratings .em').removeClass('fadeEmoji');
    $('.ratings .em').removeClass('emoji-active');
}
