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

<div id="feedbackPopOverlay" class="feedbackPopOverlay">
    <div class="feedbackPopContent">
        <span id="feedbackPop-close-button">&times;</span>
        <input type="hidden" value="{if isset($specificF) && !empty($specificF)}1{else}0{/if}" id="specificF" />
        {if isset($specificF) && !empty($specificF)}
            <div class="feedbackChoices">
                <div class="feedbackButtons">
                    <div class="feedbackPopTitle" style="background: {$formColor|escape:'htmlall':'UTF-8'};">
                        {if isset($buttonIcon) && $buttonIcon != 'fa-ban'}<i class="fa {$buttonIcon|escape:'htmlall':'UTF-8'}"></i>{/if} {l s='Feedback' mod='feedbackpro'}
                    </div>
                    <div class="feedback specific">
                        <div class="icon"></div>
                        <h4>{l s='Specific Feedback' mod='feedbackpro'}</h4>
                        <span>{l s='I\'d like to give feedback about a specific part of the website' mod='feedbackpro'}</span>
                    </div>
                    <div class="feedback general">
                        <div class="icon"></div>
                        <h4>{l s='General Feedback' mod='feedbackpro'}</h4>
                        <span>{l s='I\'d like to give feedback about the entire website' mod='feedbackpro'}</span>
                    </div>
                </div>
            </div>
        {/if}
        <div class="feedbackPopForm" {if isset($specificF) && !empty($specificF)}style="display: none;" {/if}>
            <div class="feedbackFormHeader" style="background: {$formColor|escape:'htmlall':'UTF-8'};">
                {if isset($logoDisplayed) && $logoDisplayed}
                    <div class="feedbacFormLogo">
                        {assign var=unique_id value=1|mt_rand:10000}
                        <img id="feedbackPro_logo" {if !(isset($formTitle) && $formTitle)}class="feedback-notitle"{/if} src="{$logo|escape:'htmlall':'UTF-8'}?r={$unique_id|escape:'htmlall':'UTF-8'}" />
                    </div>
                {/if}
                <div class="feedbackFormTitle {if !(isset($logoDisplayed) && $logoDisplayed)}feedback-nologo{/if}">
                    {$formTitle nofilter} {* This is HTML content *}
                </div>
            </div>
            <main class="feedbackFormMain">
                <h5 class="feedbackpro-emoji-title">{l s='What is your opinion of this page' mod='feedbackpro'}</h5>
                {if isset($ratingIcons) && !empty($ratingIcons)}
                    {if $ratingIcons == 1}
                        <ul class="ratings">
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" id="angry" name="feedbackRating" value="1"> <i class="em em-angry fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="2"> <i class="em em-confused fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="3"> <i class="em em-neutral_face fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="4"> <i class="em em-smile fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="5"> <i class="em em-heart_eyes fadeTransition"></i>
                                </label>
                            </li>
                        </ul>
                    {elseif $ratingIcons == 2}
                        <ul class="ratings">
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" id="angry" name="feedbackRating" value="1"> <i class="em em-rage fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="2"> <i class="em em-face_with_rolling_eyes fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="3"> <i class="em em-face_with_raised_eyebrow fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="4"> <i class="em em-slightly_smiling_face fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="5"> <i class="em em-heart fadeTransition"></i>
                                </label>
                            </li>
                        </ul>
                    {elseif $ratingIcons == 3}
                        <ul class="ratings">
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" id="angry" name="feedbackRating" value="1"> <i class="em em-one fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="2"> <i class="em em-two fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="3"> <i class="em em-three fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="4"> <i class="em em-four fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="5"> <i class="em em-five fadeTransition"></i>
                                </label>
                            </li>
                        </ul>
                    {/if}
                {/if}
                <div class="feedbackProInputs">
                    {if isset($subjectsDisplayed) && !empty($subjectsDisplayed)}
                        <div class="formSubjects">
                            <h5 class="formSubjects-h5">{l s='Please select a subject' mod='feedbackpro'}</h5>
                            {if isset($formSubjects) && !empty($formSubjects)}
                                <select name="feedbackSubjects" class="feedbackSubjects">
                                    <option value="default">{l s='Please choose a subject' mod='feedbackpro'}</option>
                                    {foreach $formSubjects as $subject }
                                        <option class="formSubject" value="{$subject|escape:'htmlall':'UTF-8'}">{$subject|escape:'htmlall':'UTF-8'}</option>
                                    {/foreach}
                                </select>
                            {/if}
                        </div>
                    {/if}
                    <div class="feedbackTextArea">
                        <h5 class="feedbackTextArea-h5 {if !(isset($subjectsDisplayed) && !empty($subjectsDisplayed))}feedback-nosubject{/if}">{l s='What is your suggestion?' mod='feedbackpro'}</h5>
                        <textarea class="feedbackTArea" name="feedbackTArea"></textarea>
                        {if isset($emailDisplayed) && $emailDisplayed }
                            <h5 class="feedbackTextArea-emailh5">{l s='Email' mod='feedbackpro'}</h5>
                            <input class="feedbackEmail" name="feedbackGeneralEmail" type="text">
                        {/if}
                    </div>
                </div>
                {if isset($recommandations) && $recommandations}
                    <div class="feedbackProGeneralNotes">
                        <h5 class="feedbackProGeneralNotes-h5">{$formFeedbackText nofilter} {* This is HTML content *}</h5>
                        <ul class="notes">
                            {foreach $feedbackNotes as $note}
                                <li>
                                    <label>
                                        <input class="noUniform" type="radio" id="{$note|escape:'htmlall':'UTF-8'}" name="feedbackGeneralNotes" value="{$note|escape:'htmlall':'UTF-8'}"><span class="fbNotes">{$note|escape:'htmlall':'UTF-8'}</span>
                                    </label>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}
                <input class="formSubmit" type="submit" id="rateSubmit" name="generalSubmit" value="{l s='Submit' mod='feedbackpro'}" style="background: {$formColor|escape:'htmlall':'UTF-8'};border-color:{$formColor|escape:'htmlall':'UTF-8'}">
            </main>
        </div>
    </div>
</div>
<div class="specificInstructions-overlay">
    <div class="specificInstructions">
        <span>{l s='Click on the part of the page you would like to give feedback about' mod='feedbackpro'}</span>
    </div>
</div>

<div class="specificFeedback-overlay">
    <div class="specificFeedbackContent">
        <span id="specificFeedbackPop-close-button">&times;</span>
        <div class="feedbackPopForm">
            <div class="feedbackFormHeader" style="background: {$formColor|escape:'htmlall':'UTF-8'};">
                {if isset($logoDisplayed) && $logoDisplayed}
                    <div class="feedbacFormLogo">
                        {assign var=unique_id value=1|mt_rand:10000}
                        <img id="feedbackPro_logo" {if !(isset($formTitle) && $formTitle)}class="feedback-notitle"{/if} src="{$logo|escape:'htmlall':'UTF-8'}?r={$unique_id|escape:'htmlall':'UTF-8'}" />
                    </div>
                {/if}
                <div class="feedbackFormTitle {if !(isset($logoDisplayed) && $logoDisplayed)}feedback-nologo{/if}">
                    {$formTitle|escape:'htmlall':'UTF-8'}
                </div>
            </div>
            <main class="feedbackFormMain">
                <h5 class="feedbackpro-emoji-title">{l s='Please rate your experience with this specific item' mod='feedbackpro'}</h5>
                {if isset($ratingIcons) && !empty($ratingIcons)}
                    {if $ratingIcons == 1}
                        <ul class="ratings">
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" id="angry" name="feedbackRating" value="1"> <i class="em em-angry fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="2"> <i class="em em-confused fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="3"> <i class="em em-neutral_face fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="4"> <i class="em em-smile fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="5"> <i class="em em-heart_eyes fadeTransition"></i>
                                </label>
                            </li>
                        </ul>
                    {elseif $ratingIcons == 2}
                        <ul class="ratings">
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" id="angry" name="feedbackRating" value="1"> <i class="em em-rage fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="2"> <i class="em em-face_with_rolling_eyes fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="3"> <i class="em em-face_with_raised_eyebrow fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="4"> <i class="em em-slightly_smiling_face fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="5"> <i class="em em-heart fadeTransition"></i>
                                </label>
                            </li>
                        </ul>
                    {elseif $ratingIcons == 3}
                        <ul class="ratings">
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" id="angry" name="feedbackRating" value="1"> <i class="em em-one fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="2"> <i class="em em-two fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="3"> <i class="em em-three fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="4"> <i class="em em-four fadeTransition"></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input class="noUniform" type="radio" name="feedbackRating" value="5"> <i class="em em-five fadeTransition"></i>
                                </label>
                            </li>
                        </ul>
                    {/if}
                {/if}
                <div class="feedbackProInputs">
                    {if isset($subjectsDisplayed) && !empty($subjectsDisplayed)}
                        <div class="formSubjects">
                            <h5 class="formSubjects-h5">{l s='Please select a subject' mod='feedbackpro'}</h5>
                            {if isset($formSubjects) && !empty($formSubjects)}
                                <select name="feedbackSpecificSubjects" class="feedbackSpecificSubjects">
                                    <option value="default">{l s='Please choose a subject' mod='feedbackpro'}</option>
                                    {foreach $formSubjects as $subject }
                                        <option class="formSubject" value="{$subject|escape:'htmlall':'UTF-8'}">{$subject|escape:'htmlall':'UTF-8'}</option>
                                    {/foreach}
                                </select>
                            {/if}
                        </div>
                    {/if}
                    <div class="feedbackSpecificTextArea">
                        <h5 class="feedbackTextArea-h5 {if !(isset($subjectsDisplayed) && !empty($subjectsDisplayed))}feedback-nosubject{/if}">{l s='What is your suggestion?' mod='feedbackpro'}</h5>
                        <textarea class="feedbackSpecificTArea" name="feedbackSpecificTArea"></textarea>
                        {if isset($emailDisplayed) && $emailDisplayed }
                            <h5 class="feedbackTextArea-emailh5">{l s='Email' mod='feedbackpro'}</h5>
                            <input class="feedbackEmail" name="feedbackSpecificEmail" type="text">
                        {/if}
                    </div>
                </div>
                {if isset($recommandations) && $recommandations}
                    <div class="feedbackProSpecificNotes">
                        <h5 class="feedbackProGeneralNotes-h5">{$formFeedbackText nofilter} {* This is HTML content *}</h5>
                        <ul class="notes">
                            {foreach $feedbackNotes as $note}
                                <li>
                                    <label>
                                        <input class="noUniform" type="radio" id="{$note|escape:'htmlall':'UTF-8'}" name="feedbackSpecificNotes" value="{$note|escape:'htmlall':'UTF-8'}"><span class="fbNotes">{$note|escape:'htmlall':'UTF-8'}</span>
                                    </label>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}
                <input type="submit" class="formSubmit" id="specificSubmit" name="specificSubmit" value="{l s='Submit' mod='feedbackpro'}" style="background: {$formColor|escape:'htmlall':'UTF-8'};border-color:{$formColor|escape:'htmlall':'UTF-8'}">
            </main>
        </div>
    </div>
</div>
<div id="thankyouPopOverlay" class="thankyouPopOverlay" style="display: none;">
    <div class="thankyouPopContent">
        <span id="thankyouPop-close-button">×</span>
        <div class="thankyouPopForm">
            <div class="thankyouFormHeader" style="background: {$formColor|escape:'htmlall':'UTF-8'};">
                <div class="thankyouFormTitle">
                    {$thankYou|escape:'htmlall':'UTF-8'}
                </div>
            </div>
            <div class="thankyouFormDesc">
                {$submitText|escape:'htmlall':'UTF-8'}
            </div>
        </div>
    </div>
</div>
<script>
    // Vanilla document ready
    document.addEventListener('DOMContentLoaded', function() {
        $(document).on('click','input[name="generalSubmit"]' ,function(e) {
            if (! $('.feedbackTArea').val().length) {
                $('.feedbacktext-error').remove();
                $("<p class='feedbacktext-error'>{$formFieldRequired|escape:'htmlall':'UTF-8'}</p>").hide().appendTo('.feedbackTextArea-h5').fadeIn(400);
            } else {
                generalAjax();
                $('body').removeClass('noscroll');
            }
        });
        
        $(document).on('click','input[name="specificSubmit"]' ,function(e) {
            if (! $('.feedbackSpecificTArea').val().length) {
                $('.feedbacktext-error').remove();
                $("<p class='feedbacktext-error'>{$formFieldRequired|escape:'htmlall':'UTF-8'}</p>").hide().appendTo('.feedbackTextArea-h5').fadeIn(400);
            } else {
                specificAjax();
                $('body').removeClass('noscroll');
            }
        });
    });
</script>