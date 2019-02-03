// Botones de acción en tablas y diálogos modales:

$(document).ready( function() {

   /* var CONFIRM_MODAL_FUNCTION;

    function showConfirmModal( strTitle, strBody, confirmFunction ) {
        $('#confirm-modal-title').text(strTitle);
        $('#confirm-modal-body').text(strBody);
        $('#confirm-modal-dialog').modal('show');
        CONFIRM_MODAL_FUNCTION = confirmFunction;
    }

    $("#confirm-modal-button").on('click', function (e) {
        CONFIRM_MODAL_FUNCTION();
    });

    $(".act-des-action").on('click', function (e) {

        e.preventDefault();

        var tgtId = $(this).data('target-id');
        var url = $(this).data('action-url');
        var targetIcon = '#' + $(this).data('target-icon-id');
        var iconToggleClass = $(this).data('target-icon-toggle-class');

        $.ajax({
            url: url + "/" + tgtId + "?token=" + SESSION_TOKEN
        }).done( function( msg ) {
            if( msg.success ) {
                $(targetIcon).toggleClass(iconToggleClass);
            }
        });

    });

    $(".edit-action").on('click', function (e) {

        e.preventDefault();

        var tgtId = $(this).data('target-id');
        var url = $(this).data('action-url');

        window.location = url + "/" + tgtId + "?token=" + SESSION_TOKEN;

    });
    $(document).on('click','.delete-action', function(e) {

        e.preventDefault();

        var tgtId = $(this).data('target-id');
        var url = $(this).data('action-url');
        var urlSend;
        var elementId = $(this).data('element-id');

        if (elementId == ''){
            urlSend = url + "/" + tgtId + "?token=" + SESSION_TOKEN
        }else{
            urlSend = url + "/" + tgtId + "?token=" + SESSION_TOKEN + '&newId=' + elementId
        }

        showConfirmModal( 'Borrar Elemento', 'Por favor, confirme la eliminación del elemento', function (e) {
            window.location = urlSend;
        } );

    });*/


    //contenido multimedia en noticias/ofertas/servicios

    // Función para devolver el valor del input sin el placeholder de IE8
    /*$.fn.pVal = function() {
        var $this = $(this),
            val = $this.eq(0).val();
        if(val == $this.attr('placeholder'))
            return '';
        else
            return val;
    };

    var VIDEO_URLS_COUNTER = 0;
    var VIDEO_URLS_ID      = VIDEO_URLS_COUNTER;

    var DELETE_VIDEO_FUNC  = function (e) {

        if (VIDEO_URLS_COUNTER>0) {
            VIDEO_URLS_COUNTER--;
            $('#'+$(this).data('video-url-id')).remove();
        }

    };

    $('.add-video-url').on('click', function (e) {

        if (VIDEO_URLS_COUNTER>=5) return;

        var strUrl = $('#video-url').pVal().trim();

        if (strUrl.length==0) return;

        if(!parseVideoURL(strUrl)) {
            alert('Debe introducir una URL válida');
            return;
        }

        VIDEO_URLS_COUNTER++;
        VIDEO_URLS_ID++;

        var newUrlInputStr = '<div class="row mbm" id="video-url-'+VIDEO_URLS_ID+'">';
        newUrlInputStr += '<section class="col col-8">';
        newUrlInputStr += '<input type="text" name="videourls[]" value="'+strUrl+'">';
        newUrlInputStr += '</section>';
        newUrlInputStr += '<section class="col col-2">';
        newUrlInputStr += '<a class="mts block delete-video-url" href="#videos" data-video-url-id="video-url-'+VIDEO_URLS_ID+'"><span class="icon icon__delete">Eliminar</span></a>';
        newUrlInputStr += '</section>';
        newUrlInputStr += '</div>';

        $('#video-urls').append(newUrlInputStr);

        $('#video-url').val('');

        $(".delete-video-url").off('click').on('click', DELETE_VIDEO_FUNC );

    });

    $('.calendar').datepicker({
        format: "dd-mm-yyyy",
        weekStart: 1,
        language: "es"
    });*/


    //events

    /*$('.type_view').on('change', function(){
        var typeView = $('.type_view').val();
        if (typeView == 0) {
            $('#centers_view').addClass('hidden');
        }else{
            $('#centers_view').removeClass('hidden');
        }
    });*/

} );

function isIE () {
    var myNav = navigator.userAgent.toLowerCase();
    return (myNav.indexOf('msie') != -1) ? parseInt(myNav.split('msie')[1]) : false;
}





function RoxyFileBrowser(field_name, url, type, win) {
    var roxyFileman = '/admin/fileman/index.html';
    if (roxyFileman.indexOf("?") < 0) {
        roxyFileman += "?type=" + type;
    }
    else {
        roxyFileman += "&type=" + type;
    }
    roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
    if(tinyMCE.activeEditor.settings.language){
        roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
    }
    tinyMCE.activeEditor.windowManager.open({
        file: roxyFileman,
        title: 'Explorador de ficheros',
        width: 850,
        height: 650,
        resizable: "yes",
        plugins: "media",
        inline: "yes",
        close_previous: "no"
    }, {     window: win,     input: field_name    });
    return false;
}




function init_editors(){
    tinymce.init({
        mode : "exact",
        elements : "comments",
        language : 'es',
        content_css: "/admin/css/fixes.css",
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
        plugins: [
        ],
        toolbar: "styleselect | undo redo | removeformat | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link  | fontsizeselect ",
        file_browser_callback: RoxyFileBrowser,
        file_picker_types: 'image media',
        removed_menuitems: 'newdocument tableprops',
        height : 100
    });

}


//functions block

function blockActions(typeElement){

    if (selectedRows.length<1){
        alert ("No has seleccionado ningún elemento.");
    }else{
        var value = $("#blockActionSelect").val();
        if (value!=2) activeElements(value, typeElement);
        else deleteElements(typeElement);
    }

    $('#blockActionSelect option[value="-1"]').prop('selected', true);
    $('.datatable_elements .checkbox_row').prop('checked', false);
    $('.datatable_elements tbody tr').removeClass('highlight');
    $('#select_all_checkbox').prop('checked', false);
    selectedRows = [];
}

function activeElements(value, typeElement){
    var active = value;
    var activeElementsString = "";

    for (var i=0; i<selectedRows.length; i++){
        if (activeElementsString == "") activeElementsString = selectedRows[i];
        else activeElementsString = activeElementsString + "-" + selectedRows[i];
    }

    $.ajax({
        url: "/admin/"+typeElement+"/activeSelected",
        data:{active:active, selectedElements: activeElementsString},
        async: false,
        success: function (result) {
            if (!result.success) {
                alert("Error en la consulta: conector BD");
            } else {
                if (typeElement == 'admins'){
                    result.ids.forEach(function (entry) {
                        if (active == 0) $('#element_anchor_' + entry).html('<i class="fa fa-lg fa-thumbs-o-up"></i><span style="visibility: hidden;">ACTIVE</span>');
                        else $('#element_anchor_' + entry).html('<i class="fa fa-lg fa-thumbs-down"></i><span style="visibility: hidden;">ACTIVE</span>');
                    });
                }else {
                    result.ids.forEach(function (entry) {
                        if (active == 0) $('#element_anchor_' + entry).html('<i class="fa fa-lg fa fa-eye"></i><span style="display: none;">0</span>');
                        else $('#element_anchor_' + entry).html('<i class="fa fa-lg fa fa-eye-slash"></i><span style="display: none;">1</span>');
                    });
                }
            }
        },
        error: function (xhr) {
            alert("Error en la consulta: SERVIDOR (" + xhr.status + " " + xhr.statusText + ")");
        }
    });

}

function deleteElements(typeElement){
    var confirmOk;

    if(selectedRows.length>1)
        confirmOk = confirm('¿Está seguro que desea eliminar los elementos seleccionados?');
    else confirmOk = confirm('¿Está seguro que desea eliminar el elemento seleccionado?');
    if (confirmOk == true) {
        var activeElementsString = "";

        for (var i = 0; i < selectedRows.length; i++) {
            if (activeElementsString == "") activeElementsString = selectedRows[i];
            else activeElementsString = activeElementsString + "-" + selectedRows[i];
        }

        window.location.href = "/admin/"+typeElement+"/deleteSelected" + "/?selectedElements=" + activeElementsString;
    }
}



