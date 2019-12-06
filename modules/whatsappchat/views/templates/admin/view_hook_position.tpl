{**
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2019 idnovate.com
*  @license   See above
*}
<a id="hook-position" target="_blank">{l s='View hook position 🔗' mod='whatsappchat'}</a>
{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
<script type="text/javascript">
    $(document).ready(function(){
        $('a#hook-position').fancybox({
            type: 'image',
            autoDimensions: false,
            autoSize: false,
            height: 'auto',
            helpers: {
                overlay: {
                    locked: false
                }
            }
        });
    });
</script>
{/if}