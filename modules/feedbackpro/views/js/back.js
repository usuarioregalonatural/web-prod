/**
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
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2019 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 *
 * Don't forget to prefix your containers with your own identifier
 * to avoid any conflicts with others containers.
 */

$(document).ready(function() {
    showBubbleInputsReady();
    loadLogo();
    showSubjectInputs();
    showRecommandationText();
    showLogoInput();
    $('#configuration_form_submit_btn').parent().show();

    if (!getCookie('tabClicked')) {
        document.cookie = "tabClicked=0";
    }
    
    $('div#chartContainer').append('<div class="filler"></div>');
    
    $('body').on('click', 'input#FEEDBACK_BUBBLE_on, input#FEEDBACK_BUBBLE_off, input[name="FEEDBACK_BUBBLE"]#active_on, input[name="FEEDBACK_BUBBLE"]#active_off', function() {
        showBubbleInputs();
    });
    
    $('body').on('click', 'input#FEEDBACK_SUBJECT_on, input#FEEDBACK_SUBJECT_off, input[name="FEEDBACK_SUBJECT"]#active_on, input[name="FEEDBACK_SUBJECT"]#active_off', function() {
        showSubjectInputs();
    });
    
    $('body').on('click', 'input#FEEDBACK_SHOPLOGO_on, input#FEEDBACK_SHOPLOGO_off, input[name="FEEDBACK_SHOPLOGO"]#active_on, input[name="FEEDBACK_SHOPLOGO"]#active_off', function() {
        showLogoInput();
    });
    
    $('body').on('click', 'input#FEEDBACK_FORMRECOMANDATIONS_on, input#FEEDBACK_FORMRECOMANDATIONS_off, input[name="FEEDBACK_FORMRECOMANDATIONS"]#active_on, input[name="FEEDBACK_FORMRECOMANDATIONS"]#active_off', function() {
        showRecommandationText();
    });
    
    $('body').on('click', '.feedback-table tbody tr', function() {
        if ($(this).attr('data-open') == '0') {
            $('#row-details-' + $(this).attr('data-id')).fadeIn(100);
            $(this).attr('data-open', '1');
        } else if ($(this).attr('data-open') == '1') {
            $('#row-details-' + $(this).attr('data-id')).fadeOut(100);
            $(this).attr('data-open', '0');
        }
    });
    
    /**
     * Switch tabs content and active state.
     * @type {number}
     */
    var previousActiveTabIndex = 0;
    var a = ['0','1','2','3','4','5'];
    a.forEach(function(entry) {
        if (getCookie('tabClicked') && entry == getCookie('tabClicked')) {
            $(".feedbackProTabsContent .feedbackProTab").each(function () {
                if($(this).data("tab-index") == entry) {
                    $(".feedbackProTab").hide();
                    $(this).show();
                    $(".feedbackProUl li").removeClass('active');
                    // $(this).closest('li').addClass('active');
                    previousActiveTabIndex = $(this).data("tab-index");
                    return;
                }
            });
            $("#feedbackProTabs > ul > li").each(function () {
                if($(this).data("tab-index") == entry) {
                    $(this).closest('li').addClass('active');
                }
            });
        }
    });
    
    $("#feedbackProTabs > ul > li").on('click', function () {
        var $this = $(this);
        var tabClicked = $this.closest('li').data("tab-index");
        document.cookie = "tabClicked="+tabClicked+"";
        if(tabClicked != previousActiveTabIndex) {
            $(".feedbackProTabsContent .feedbackProTab").each(function () {
                if($(this).data("tab-index") == tabClicked) {
                    $(".feedbackProTab").hide();
                    $(this).show();
                    $(".feedbackProUl li").removeClass('active');
                    $this.closest('li').addClass('active');
                    previousActiveTabIndex = $(this).data("tab-index");
                    return;
                }
            });
        }
    });
    
    langsIds.forEach(function (element) {
        $("#FEEDBACK_SUBJECTS_"+element+"").select2({
            tags:[""],
            tokenSeparators: [",", ";"]
        });
    });
    $('.col-sm-6.col-md-4.seen').hide();

});

function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}

function showBubbleInputs() {
    if ($('input[name="FEEDBACK_BUBBLE"]#FEEDBACK_BUBBLE_on').length) {
        if ($('input[name="FEEDBACK_BUBBLE"]#FEEDBACK_BUBBLE_on').prop('checked')) {
            $('#fieldset_0 > div.form-wrapper > div:nth-child(8)').slideDown(400);
            $('#fieldset_0 > div.form-wrapper > div:nth-child(9)').slideDown(400);
        } else {
            $('#fieldset_0 > div.form-wrapper > div:nth-child(8)').slideUp(400);
            $('#fieldset_0 > div.form-wrapper > div:nth-child(9)').slideUp(400);
        }
    } else if ($('input[name="FEEDBACK_BUBBLE"]#active_on').length) {
        if ($('input[name="FEEDBACK_BUBBLE"]#active_on').prop('checked')) {
            $('#fieldset_0 > label:nth-child(23)').fadeIn(400);
            $('#fieldset_0 > div:nth-child(24)').fadeIn(400);
            $('#fieldset_0 > label:nth-child(26)').fadeIn(400);
            $('#fieldset_0 > div:nth-child(27)').fadeIn(400);
        } else {
            $('#fieldset_0 > label:nth-child(23)').hide();
            $('#fieldset_0 > div:nth-child(24)').hide();
            $('#fieldset_0 > label:nth-child(26)').hide();
            $('#fieldset_0 > div:nth-child(27)').hide();
        }
    }
}

function showBubbleInputsReady() {
    if ($('input[name="FEEDBACK_BUBBLE"]#FEEDBACK_BUBBLE_on').length) {
        if (! $('input[name="FEEDBACK_BUBBLE"]#FEEDBACK_BUBBLE_on').prop('checked')) {
            $('#fieldset_0 > div.form-wrapper > div:nth-child(8)').slideUp(400);
            $('#fieldset_0 > div.form-wrapper > div:nth-child(9)').slideUp(400);
        }
    } else if ($('input[name="FEEDBACK_BUBBLE"]#active_on').length) {
        if (! $('input[name="FEEDBACK_BUBBLE"]#active_on').prop('checked')) {
            $('#fieldset_0 > label:nth-child(23)').hide();
            $('#fieldset_0 > div:nth-child(24)').hide();
            $('#fieldset_0 > label:nth-child(26)').hide();
            $('#fieldset_0 > div:nth-child(27)').hide();
        }
    }
}

function showSubjectInputs() {
    if ($('input[name="FEEDBACK_SUBJECT"]#FEEDBACK_SUBJECT_on').length) {
        if ($('input[name="FEEDBACK_SUBJECT"]#FEEDBACK_SUBJECT_on').prop('checked')) {
            $('#fieldset_0_1 > div.form-wrapper > div:nth-child(8)').slideDown(400);
        } else {
            $('#fieldset_0_1 > div.form-wrapper > div:nth-child(8)').slideUp(400);
        }
    } else if ($('input[name="FEEDBACK_SUBJECT"]#active_on').length) {
        if ($('input[name="FEEDBACK_SUBJECT"]#active_on').prop('checked')) {
            $('#fieldset_0 > label:nth-child(22)').fadeIn(400);
            $('#fieldset_0 > div:nth-child(23)').fadeIn(400);
            $('#configuration_form_submit_btn').parent().show();
        } else {
            $('#fieldset_0 > label:nth-child(22)').hide();
            $('#fieldset_0 > div:nth-child(23)').hide();
            $('#configuration_form_submit_btn').parent().show();
        }
    }
}

function showRecommandationText() {
    if ($('input[name="FEEDBACK_FORMRECOMANDATIONS"]#FEEDBACK_FORMRECOMANDATIONS_on').length) {
        if ($('input[name="FEEDBACK_FORMRECOMANDATIONS"]#FEEDBACK_FORMRECOMANDATIONS_on').prop('checked')) {
            $('#fieldset_0_1 > div.form-wrapper > div:nth-child(11)').slideDown(400);
        } else {
            $('#fieldset_0_1 > div.form-wrapper > div:nth-child(11)').slideUp(400);
        }
    } else if ($('input[name="FEEDBACK_FORMRECOMANDATIONS"]#active_on').length) {
        if ($('input[name="FEEDBACK_FORMRECOMANDATIONS"]#active_on').prop('checked')) {
            $('#fieldset_0 > label:nth-child(29)').fadeIn(400);
            $('#fieldset_0 > div:nth-child(30)').fadeIn(400);
        } else {
            $('#fieldset_0 > label:nth-child(29)').hide();
            $('#fieldset_0 > div:nth-child(30)').hide();
        }
    }
}

function showLogoInput() {
    if ($('input[name="FEEDBACK_SHOPLOGO"]#FEEDBACK_SHOPLOGO_on').length) {
        if ($('input[name="FEEDBACK_SHOPLOGO"]#FEEDBACK_SHOPLOGO_on').prop('checked')) {
            $('#fieldset_0_1 > div.form-wrapper > div:nth-child(2)').slideDown(400);
        } else {
            $('#fieldset_0_1 > div.form-wrapper > div:nth-child(2)').slideUp(400);
        }
    } else if ($('input[name="FEEDBACK_SHOPLOGO"]#active_on').length) {
        if ($('input[name="FEEDBACK_SHOPLOGO"]#active_on').prop('checked')) {
            $('#fieldset_0 > div:nth-child(5)').fadeIn(400);
        } else {
            $('#fieldset_0 > div:nth-child(5)').hide();
        }
    }
}

function loadLogo() {
    var random = Math.floor((Math.random() * 10000) + 1);
    gif = '<div class="logoPreview"><img class="logoPreview" src="'+uri+'?r='+random+'"/></div>';
    if ($('.dummyfile.input-group').length) {
        $('.dummyfile.input-group').before(gif);
    } else {
        $('input[type="file"]').before(gif)
    }
}

$(document).on('click', 'li.general', function() {
    $('.col-sm-6.col-md-4.Specific').hide();
    $('.col-sm-6.col-md-4.General').show();
    $('.col-sm-6.col-md-4.seen').hide();
});

$(document).on('click', 'li.specific', function() {
    $('.col-sm-6.col-md-4.Specific').show();
    $('.col-sm-6.col-md-4.General').hide();
    $('.col-sm-6.col-md-4.seen').hide();
});

$(document).on('click', 'li.all', function() {
    $('.col-sm-6.col-md-4.Specific').show();
    $('.col-sm-6.col-md-4.General').show();
    $('.col-sm-6.col-md-4.seen').hide();
});

$(document).on('click', 'li.seen', function() {
    $( '.col-sm-6.col-md-4' ).hide();
    $( '.col-sm-6.col-md-4.seen' ).show();

});

$(document).on('click', 'li.sort', function() {
    $('.row-details').fadeOut(0);
    $('.feedback-table tr').attr('data-open', '0');
    switch($(this).attr('data-type')) {
        case 'all':
            $('.feedback-table tr[data-type="General"]').fadeIn(0);
            $('.feedback-table tr[data-type="Specific"]').fadeIn(0);
            $('.feedback-table tr[data-seen="1"]').fadeOut(0);
            break;
        case 'general':
            $('.feedback-table tr[data-type="Specific"]').fadeOut(0);
            $('.feedback-table tr[data-type="General"]').fadeIn(0);
            $('.feedback-table tr[data-seen="1"]').fadeOut(0);
            break;
        case 'specific':
            $('.feedback-table tr[data-type="General"]').fadeOut(0);
            $('.feedback-table tr[data-type="Specific"]').fadeIn(0);
            $('.feedback-table tr[data-seen="1"]').fadeOut(0);
            break;
        case 'seen':
            $('.feedback-table tr[data-type="General"]').fadeOut(0);
            $('.feedback-table tr[data-type="Specific"]').fadeOut(0);
            $('.feedback-table tr[data-seen="1"]').fadeIn(0);
            break;
    }
    $("li.sort").removeClass('active');
    $(this).closest('li').addClass('active');
});
