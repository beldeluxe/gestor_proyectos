$(document).ready(function(){
    if (!$.support.placeholder) {
        $("[placeholder]").focus(function () {
            if ($(this).val() == $(this).attr("placeholder")) $(this).val("");
            $(this).css('color', 'black');
        }).blur(function () {
            if ($(this).val() == "") $(this).val($(this).attr("placeholder"));
            $(this).css('color', 'grey');
        }).blur();

        $("[placeholder]").parents("form").submit(function () {
            $(this).find('[placeholder]').each(function() {
                if ($(this).val() == $(this).attr("placeholder")) {
                    $(this).val("");
                }
            });
        });
    }
});