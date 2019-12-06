{*
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

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $confirmation}
    {if $psVersion != '1.5'}
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            <p>{$confirmation|escape:'htmlall':'UTF-8'}</p>
        </div>
    {else}
        <div class="module_confirmation conf confirm">
            {$confirmation|escape:'htmlall':'UTF-8'}
        </div>
    {/if}
{/if}

{if $errors}
    {if $psVersion != '1.5'}
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
            {if count($errors) == 1}
                <p>{$errors[0]|escape:'htmlall':'UTF-8'}</p>
            {else}
                <ol>
                    {foreach $errors as $error}
                        <li>{$error|escape:'htmlall':'UTF-8'}</li>
                    {/foreach}
                </ol>
            {/if}
        </div>
    {else}
        <div class="alert error">
            {foreach $errors as $error}
                {$error|escape:'htmlall':'UTF-8'}<br/>
            {/foreach}
        </div>
    {/if}
{/if}

<div id="feedbackProTabs">
    <ul class="feedbackProUl">
        <li data-tab-index="0" class=""><a><i class="fa fa-cogs"></i> {l s='Button configuration' mod='feedbackpro'}</a></li>
        <li data-tab-index="1"><a><i class="fa fa-wpforms"></i> {l s='Form configuration' mod='feedbackpro'}</a></li>
        <li data-tab-index="2"><a><i class="fa fa-comments"></i> {l s='Feedback' mod='feedbackpro'}</a></li>
        <li data-tab-index="3"><a><i class="fa fa-bar-chart"></i> {l s='Statistics' mod='feedbackpro'}</a></li>
        <li data-tab-index="4"><a><i class="fa fa-at"></i> {l s='Email notification' mod='feedbackpro'}</a></li>
    </ul>
    <div class="feedbackProTabsContent">
        <div data-tab-index="0" class="feedbackProTab" style="display: none;">
            {$settingsForm} {*This is HTML CONTENT*}
        </div>
        <div data-tab-index="1" class="feedbackProTab" style="display: none;">
            {$settingsForm2} {*This is HTML CONTENT*}
        </div>
        <div data-tab-index="2" class="feedbackProTab" style="display: none;">
            <{if $psVersion != '1.5'}div{else}form{/if} class="panel" id="feedbackPanel">
                <div class="panel-heading">
                    <i class="fa fa-comments"></i>{$f_feedback|escape:'htmlall':'UTF-8'}
                </div>
                {if $psVersion == '1.5'}<fieldset>{/if}
                    <ul id="sortButtons" role="tablist">
                        <li data-type="all" class="sort all active"><a>{l s='All' mod='feedbackpro'} <span>({$fbCountAll|escape:'htmlall':'UTF-8'})</span></a></li>
                        <li data-type="general" class="sort general"><a>{l s='General' mod='feedbackpro'} <span>({$fbCountGeneral|escape:'htmlall':'UTF-8'})</span></a></li>
                        <li data-type="specific" class="sort specific"><a>{l s='Specific' mod='feedbackpro'} <span>({$fbCountSpecific|escape:'htmlall':'UTF-8'})</span></a></li>
                        <li data-type="seen" class="sort seen"><a>{l s='Seen' mod='feedbackpro'} <span>({$fbCountSeen|escape:'htmlall':'UTF-8'})</span></a></li>
                    </ul>
                    <div class="card feedback-table">
                        <div class="table-responsive">
                            <table class="table table-hover table-outline table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th class="text-center w-1"><i class="icon-people"></i></th>
                                        <th>{$f_subject|escape:'htmlall':'UTF-8'}</th>
                                        <th>{$f_message|escape:'htmlall':'UTF-8'}</th>
                                        <th>{$f_date|escape:'htmlall':'UTF-8'}</th>
                                        <th>{$f_category|escape:'htmlall':'UTF-8'}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach $feedbacks as $feedback}
                                        <tr data-id="{$feedback.id|escape:'htmlall':'UTF-8'}" data-open="0" data-type="{$feedback.type|escape:'htmlall':'UTF-8'}" data-seen="{if $feedback.seen == '' || $feedback.seen == '0'}0{else}1{/if}" {if !($feedback.seen == '' || $feedback.seen == '0')}style="display: none;"{/if}>
                                            <td class="text-center feedback-relative">
                                                {if $feedback.new == '1'}
                                                    <div class="shape">
                                                        <div class="shape-text">
                                                            {$f_new|escape:'htmlall':'UTF-8'}
                                                        </div>
                                                    </div>
                                                {/if}
                                                <span class="rate">
                                                    {if isset($feedback.rating) && $feedback.rating && isset($emojis) && $emojis}
                                                        {if $emojis == 1}
                                                            {if $feedback.rating == 1}
                                                                <icon class="em em-angry"></icon>
                                                            {elseif $feedback.rating == 2}
                                                                <icon class="em em-confused"></icon>
                                                            {elseif $feedback.rating == 3}
                                                                <icon class="em em-neutral_face"></icon>
                                                            {elseif $feedback.rating == 4}
                                                                <icon class="em em-smile"></icon>
                                                            {elseif $feedback.rating == 5}
                                                                <icon class="em em-heart_eyes"></icon>
                                                            {/if}
                                                        {elseif $emojis == 2}
                                                            {if $feedback.rating == 1}
                                                                <icon class="em em-rage"></icon>
                                                            {elseif $feedback.rating == 2}
                                                                <icon class="em em-face_with_rolling_eyes"></icon>
                                                            {elseif $feedback.rating == 3}
                                                                <icon class="em em-face_with_raised_eyebrow"></icon>
                                                            {elseif $feedback.rating == 4}
                                                                <icon class="em em-slightly_smiling_face"></icon>
                                                            {elseif $feedback.rating == 5}
                                                                <icon class="em em-heart"></icon>
                                                            {/if}
                                                        {elseif $emojis == 3}
                                                            {if $feedback.rating == 1}
                                                                <icon class="em em-one"></icon>
                                                            {elseif $feedback.rating == 2}
                                                                <icon class="em em-two"></icon>
                                                            {elseif $feedback.rating == 3}
                                                                <icon class="em em-three"></icon>
                                                            {elseif $feedback.rating == 4}
                                                                <icon class="em em-four"></icon>
                                                            {elseif $feedback.rating == 5}
                                                                <icon class="em em-five"></icon>
                                                            {/if}
                                                        {/if}
                                                    {/if}
                                                </span>
                                            </td>
                                            <td>
                                                <div>
                                                    {if isset($feedback.subject) && $feedback.subject}
                                                        {$feedback.subject|escape:'htmlall':'UTF-8'}
                                                    {else}
                                                        ---
                                                    {/if}
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    {if isset($feedback.comment) && $feedback.comment}
                                                        {$feedback.comment|escape:'htmlall':'UTF-8'}
                                                    {else}
                                                        ---
                                                    {/if}
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    {if isset($today) && $today && isset($yesterday) && $yesterday && isset($feedback.date) && $feedback.date && isset($feedback.hour) && $feedback.hour}
                                                        {if $today == $feedback.date}
                                                            {l s='Today' mod='feedbackpro'}
                                                        {elseif $yesterday == $feedback.date }
                                                            {l s='Yesterday' mod='feedbackpro'}
                                                        {else}
                                                            {$feedback.date|escape:'htmlall':'UTF-8'}
                                                        {/if}
                                                        {$feedback.hour|escape:'htmlall':'UTF-8'}
                                                    {/if}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {if $feedback.type == 'General'}badge-general{else}badge-specific{/if}">{if $feedback.type == 'General'}{$f_general|escape:'htmlall':'UTF-8'}{else}{$f_specific|escape:'htmlall':'UTF-8'}{/if}</span>
                                            </td>
                                            <td>
                                                <i class="fa fa-ellipsis-v"></i>
                                            </td>
                                        </tr>
                                        <tr class="row-details" id="row-details-{$feedback.id|escape:'htmlall':'UTF-8'}">
                                            <td colspan="6">
                                                <div class="frame-preview">
                                                    <a class="frame-overlay" href="{$feedback.link|escape:'htmlall':'UTF-8'}?specific&fbId={$feedback.id|escape:'htmlall':'UTF-8'}" target="_blank">
                                                        {l s='Click for preview' mod='feedbackpro'}
                                                    </a>
                                                </div>
                                                <div class="row-details-box">
                                                    <p>
                                                        <span class="row-details-title">{l s='Email:' mod='feedbackpro'}</span>
                                                        <span>
                                                            {if isset($feedback.email) && $feedback.email}
                                                                {$feedback.email|escape:'htmlall':'UTF-8'}
                                                            {else}
                                                                ---
                                                            {/if}
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="row-details-title">{l s='Resolution:' mod='feedbackpro'}</span>
                                                        <span>
                                                            {if isset($feedback.resolution) && $feedback.resolution}
                                                                {$feedback.resolution|escape:'htmlall':'UTF-8'}
                                                            {else}
                                                                ---
                                                            {/if}
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="row-details-title">{l s='Recommandation note:' mod='feedbackpro'}</span>
                                                        <span>
                                                            {if $feedback.note != '0'}
                                                                {$feedback.note|escape:'htmlall':'UTF-8'}
                                                            {else}
                                                                ---
                                                            {/if}
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="row-details-box">
                                                    <p>
                                                        <span class="row-details-title">{l s='Operating system:' mod='feedbackpro'}</span>
                                                        <span>
                                                            {if isset($feedback.os) && $feedback.os}
                                                                {$feedback.os|escape:'htmlall':'UTF-8'}
                                                            {else}
                                                                ---
                                                            {/if}
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="row-details-title">{l s='Browser:' mod='feedbackpro'}</span>
                                                        <span>
                                                            {if isset($feedback.browser) && $feedback.browser}
                                                                {$feedback.browser|escape:'htmlall':'UTF-8'}
                                                            {else}
                                                                ---
                                                            {/if}
                                                        </span>
                                                    </p>
                                                    <p>
                                                        <span class="row-details-title">{l s='Language:' mod='feedbackpro'}</span>
                                                        <span>
                                                            {if isset($feedback.language) && $feedback.language}
                                                                {$feedback.language|escape:'htmlall':'UTF-8'}
                                                            {else}
                                                                ---
                                                            {/if}
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="row-details-actions">
                                                    <div class="actionButtons">
                                                        <a class="markAsSeen {if ! ($feedback.seen == '' || $feedback.seen == '0')}feedbackSeen{/if}" href="{$feedbackActionLink|escape:'htmlall':'UTF-8'}&idFeedback={$feedback.id|escape:'htmlall':'UTF-8'}&febMarkAsSeen=true"><i class="fa fa-check" style=""></i></a>
                                                        <a class="markAsSeen" href="{$feedbackActionLink|escape:'htmlall':'UTF-8'}&idFeedback={$feedback.id|escape:'htmlall':'UTF-8'}&febDelete=true"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </td>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </div>
                {if $psVersion == '1.5'}</fieldset>{/if}
            </{if $psVersion != '1.5'}div{else}form{/if}>
        </div>
        <div data-tab-index="3" class="feedbackProTab" style="display: none;">
            <{if $psVersion != '1.5'}div{else}form{/if} class="statisticsPies panel">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart"></i>{$f_statistics|escape:'htmlall':'UTF-8'}
                </div>
                {if $psVersion == '1.5'}<fieldset>{/if}
                    <div class="chart">
                        <span class="chartTitle" id="totalChart-span">{l s='Total Feedbacks' mod='feedbackpro'}</span>
                        <canvas id="totalChart" ></canvas>
                        <div class="chartLegend" id="totalChart-legend" class="noselect"></div>
                    </div>
                    <div class="chart">
                        <span class="chartTitle" id="totalChart-span">{l s='Desktop Feedbacks' mod='feedbackpro'}</span>
                        <canvas id="notesChartDesktop" ></canvas>
                        <div class="chartLegend" id="notesChartDesktop-legend" class="noselect"></div>
                    </div>
                    <div class="chart">
                        <span class="chartTitle" id="totalChart-span">{l s='Mobile Feedbacks' mod='feedbackpro'}</span>
                        <canvas id="viewChart" ></canvas>
                        <div class="chartLegend" id="viewChart-legend" class="noselect"></div>
                    </div>
                {if $psVersion == '1.5'}</fieldset>{/if}
            </{if $psVersion != '1.5'}div{else}form{/if}>
            <script>
                /**
                 * Ajax for mark as not new on Feedback Tab
                 */
                document.addEventListener('DOMContentLoaded', function() {
                    $('body').on('click', '.feedback-table tbody tr', function() {
                        var that = $(this);
                        
                        $.ajax({
                            url: '{$uri|escape:"htmlall":"UTF-8"}ajax/markAsNew.php',
                            type: 'post',
                            data: 'feedback_id=' + $(this).attr('data-id') + '&token={$ajax_token|escape:"html":"UTF-8"}',
                            success: function(data) {
                                that.find('.shape').fadeOut(100);
                            }
                        });
                    });
                });
                
                /**
                 * SCRIPT FOR TOTAL CHART
                 */
                var data = {
                    labels: [
                        '{l s='General' mod='feedbackpro'}',
                        '{l s='Specific' mod='feedbackpro'}',
                    ],
                    datasets: [{
                        data: [{$fbChartGeneral|escape:'htmlall':'UTF-8'}, {$fbChartSpecific|escape:'htmlall':'UTF-8'}],
                        backgroundColor: [
                            "#6aa1d5",
                            "#7bdc88",
                        ],
                        hoverBackgroundColor: [
                            "#6aa1d5",
                            "#7bdc88",
                        ],
                        borderWidth: 1
                    }]
                };
    
                var options = {
                    animation: {
                        animateRotate: true,
                        animateScale: true
                    },
                    responsive: false,
                    // cutoutPercentage: 85,
                    legend: false,
                    legendCallback: function(chart) {
                        var text = [];
                        text.push('<ul class="' + chart.id + '-legend">');
                        for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
                            text.push('<li><span style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '">');
                            if (chart.data.labels[i]) {
                                text.push(chart.data.labels[i]+': '+parseInt(chart.data.datasets[0].data[i]));
                            }
                            text.push('</span></li>');
                        }
                        text.push('<li><span  style="background-color:#a2a2a2;">{l s='Total' mod='feedbackpro'}: {$fbChartAll|escape:'htmlall':'UTF-8'}</span></li></ul>');
                        return text.join("");
                    },
                    tooltips: {
                        custom: function(tooltip) {
                        },
                        mode: 'single',
                        callbacks: {
                            label: function(tooltipItems, data) {
                                var sum = data.datasets[0].data.reduce(add, 0);
    
                                function add(a, b) {
                                    return a + b;
                                }
                                return parseInt((data.datasets[0].data[tooltipItems.index] / sum * 100), 10) + ' %';
                            }
                        }
                    }
                }
                
                var ctx = $("#totalChart");
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: options
                });
                $("#totalChart-legend").html(myChart.generateLegend());
                /**
                 * SCRIPT FOR NOTES CHART DESKTOP
                 */
                var data = {
                    labels: [
                        '{l s='Positive' mod='feedbackpro'}',
                        '{l s='Negative' mod='feedbackpro'}',
                    ],
                    datasets: [{
                        data: [{$fbDesktopPositive|escape:'htmlall':'UTF-8'}, {$fbDesktopNegative|escape:'htmlall':'UTF-8'}],
                        backgroundColor: [
                            "#7bdc88",
                            "#FF6384",
                        ],
                        hoverBackgroundColor: [
                            "#7bdc88",
                            "#FF6384",
                        ],
                        borderWidth: 1
                    }]
                };
                var options = {
                    animation: {
                        animateRotate: true,
                        animateScale: true
                    },
                    responsive: false,
                    // cutoutPercentage: 85,
                    legend: false,
                    legendCallback: function(chart) {
                        var text = [];
                        text.push('<ul class="' + chart.id + '-legend">');
                        for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
                            text.push('<li><span style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '">');
                            if (chart.data.labels[i]) {
                                text.push(chart.data.labels[i]+': '+parseInt(chart.data.datasets[0].data[i]));
                            }
                            text.push('</span></li>');
                        }
                        text.push('<li><span  style="background-color:#a2a2a2;">{l s='Average' mod='feedbackpro'}: {$fbDesktopAverage|escape:'htmlall':'UTF-8'}</span></li></ul>');
                        return text.join("");
                    },
                    tooltips: {
                        custom: function(tooltip) {
                        },
                        mode: 'single',
                        callbacks: {
                            label: function(tooltipItems, data) {
                                var sum = data.datasets[0].data.reduce(add, 0);
    
                                function add(a, b) {
                                    return a + b;
                                }
                                return parseInt((data.datasets[0].data[tooltipItems.index] / sum * 100), 10) + ' %';
                            }
                        }
                    }
                }
                
                var ctx = $("#notesChartDesktop");
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: options
                });
                $("#notesChartDesktop-legend").html(myChart.generateLegend());
                    /**
                     * SCRIPT FOR NOTES CHART MOBILE
                     */
                    var data = {
                        labels: [
                            '{l s='Positive' mod='feedbackpro'}',
                            '{l s='Negative' mod='feedbackpro'}',
                        ],
                        datasets: [{
                            data: [{$fbMobilePositive|escape:'htmlall':'UTF-8'}, {$fbMobileNegative|escape:'htmlall':'UTF-8'}],
                            backgroundColor: [
                                "#7bdc88",
                                "#FF6384",
                            ],
                            hoverBackgroundColor: [
                                "#7bdc88",
                                "#FF6384",
                            ],
                            borderWidth: 1
                        }]
                    };
    
                    var options = {
                        animation: {
                            animateRotate: true,
                            animateScale: true
                        },
                        responsive: false,
                        // cutoutPercentage: 85,
                        legend: false,
                        legendCallback: function(chart) {
                            var text = [];
                            text.push('<ul class="' + chart.id + '-legend">');
                            for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
                                text.push('<li><span style="background-color:' + chart.data.datasets[0].backgroundColor[i] + '">');
                                if (chart.data.labels[i]) {
                                    text.push(chart.data.labels[i]+': '+parseInt(chart.data.datasets[0].data[i]));
                                }
                                text.push('</span></li>');
                            }
                            text.push('<li><span  style="background-color:#a2a2a2;">{l s='Average' mod='feedbackpro'}: {$fbMobileAverage|escape:'htmlall':'UTF-8'}</span></li></ul>');
                            return text.join("");
                        },
                        tooltips: {
                            custom: function(tooltip) {
                            },
                            mode: 'single',
                            callbacks: {
                                label: function(tooltipItems, data) {
                                    var sum = data.datasets[0].data.reduce(add, 0);
    
                                    function add(a, b) {
                                        return a + b;
                                    }
                                    return parseInt((data.datasets[0].data[tooltipItems.index] / sum * 100), 10) + ' %';
                                }
                            }
                        }
                    }
                    var ctx = $("#viewChart");
                    var myChart = new Chart(ctx, {
                        type: 'pie',
                        data: data,
                        options: options
                    });
                    $("#viewChart-legend").html(myChart.generateLegend());
                </script>
        </div>
        <div data-tab-index="4" class="feedbackProTab" style="display: none;">
            {$settingsForm5} {*This is HTML CONTENT*}
        </div>
    </div>
</div>
<div class="re_clearfix"></div>
<div id="re_footer" class="text-center text-muted mt-3">
    <ol>
        <li>
            {$created_by_l|escape:'htmlall':'UTF-8'} <strong>PrestaBucket</strong>
        </li>
        <li>
            {$current_version_l|escape:'htmlall':'UTF-8'}: <strong>{$module_version_f|escape:'htmlall':'UTF-8'}</strong>
        </li>
        <li>
            <a href="https://addons.prestashop.com/en/ratings.php" target="_blank" class="re-rate">
                <i class="icon-star"></i>{$rate_us_l|escape:'htmlall':'UTF-8'}
            </a>
        </li>
        <li>
            <a href="{$documentation_url|escape:'htmlall':'UTF-8'}modules/feedbackpro/docs/readme_en.pdf" target="_blank" class="documentation">
                <i class="icon-book"></i>{$documentation_l|escape:'htmlall':'UTF-8'}
            </a>
        </li>
        <li>
            <a href="https://addons.prestashop.com/en/contact-us?id_product=43813" target="_blank" class="help">
                <i class="icon-question-circle"></i>{$need_help_l|escape:'htmlall':'UTF-8'} ?
            </a>
        </li>
    </ol>
</div>
