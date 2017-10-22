/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */
jQuery("form.pds").on("beforeSubmit", function(event){
    if(jQuery(this).data("submitting")) {
        event.preventDefault();
        return false;
    }
    jQuery(this).data("submitting", true);
    $(this).find(":submit").find("i").removeClass("hidden");
    $(this).find(":submit").find("span").addClass("hidden");
    $(this).find(":submit").attr("disabled", "disabled");
    return true;
});