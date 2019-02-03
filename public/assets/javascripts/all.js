$(function(){
    $('#oposite_data').on('change', function(){
        var a =  $('#oposite_data').is(":checked");
        if (a == true){
            $('#robinson').val(1);
        }else{
            $('#robinson').val(0);
        }

    });

    $('.selectize').selectize();


    $('#confirm-button').on('click', function(){
        $('#confirm-button').hide();
        $('#confirm-send').show();
    });

  
});

function changeLocale(locale){
    $.ajax({
        url: "/change-locale",
        data: {"language" : locale},
        method: 'POST',
        success: function(){
            location.reload();
        }
    });
}
