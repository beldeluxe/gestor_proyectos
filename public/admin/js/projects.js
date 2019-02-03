$(document).ready ( function(){
    tinymce.init({
        selector: "#email_text_1",
        body_id: 'email_text_1_body',
        language : 'es',
        content_css: "/admin/css/pages.css",
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
            "advlist autolink lists link charmap preview",
            "searchreplace visualblocks code",
            "insertdatetime contextmenu paste"
        ],
        removed_menuitems: 'newdocument'
    });

    tinymce.init({
        selector: "#email_text_2",
        body_id: 'email_text_2_body',
        language : 'es',
        content_css: "/admin/css/pages.css",
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
            "advlist autolink lists link charmap preview",
            "searchreplace visualblocks code",
            "insertdatetime contextmenu paste"
        ],
        removed_menuitems: 'newdocument'
    });

    tinymce.init({
        selector: "#email_text_3",
        body_id: 'email_text_3_body',
        language : 'es',
        content_css: "/admin/css/pages.css",
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
            "advlist autolink lists link charmap preview",
            "searchreplace visualblocks code",
            "insertdatetime contextmenu paste"
        ],
        removed_menuitems: 'newdocument'
    });



});



function cambiaEstado(estado){
    var myselect = document.getElementById("state");
    var select= myselect.options[myselect.selectedIndex].value;
    if (select == 3)   //Cancelado
    $('#reasonCancelBlock').removeClass('hidden');
else
    $('#reasonCancelBlock').addClass('hidden');
}


function submitForm(){

    /*$("#projectForm").validate({
        ignore: [],
        rules: {
            'budget': { required: true, number: true }

        },
        messages: {
            'budget': 'Valor incorrecto (separar con "." si introduce decimales)'

        }


    });*/

    $("#projectForm").submit();
    //if ($("#projectForm").valid()) {
//if ($("#projectForm").valid()) {
//    }
    /*if ($("#projectForm").valid()) {

        }*/
}

function deleteImage(projectId){
    $.ajax({
        url: '/admin/projects/deleteImage',
        data: {"projectId": projectId},
        success: function(result){
            location.reload();
        }
    })
}

$.indexTableSetup = function(){

    jQuery.fn.dataTableExt.oSort['numeric-comma-asc'] = function(a,b) {
        var x = (a == "-") ? 0 : a.replace( ".", '' );
        var y = (b == "-") ? 0 : b.replace( ".", '' );

        x = (a == "-") ? 0 : x.replace( /,/, "." );
        y = (b == "-") ? 0 : y.replace( /,/, "." );
        x = parseFloat( x );
        y = parseFloat( y );
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    };

    jQuery.fn.dataTableExt.oSort['numeric-comma-desc'] = function(a,b) {
        var x = (a == "-") ? 0 : a.replace( ".", "" );
        var y = (b == "-") ? 0 : b.replace( ".", "" );

        x = (a == "-") ? 0 : x.replace( /,/, "." );
        y = (b == "-") ? 0 : y.replace( /,/, "." );
        x = parseFloat( x );
        y = parseFloat( y );
        return ((x < y) ? 1 : ((x > y) ? -1 : 0));
    };

    $('#index-table').DataTable({
        "oLanguage": {
            "sUrl": "/admin/spanish.json"
        },
        "aoColumnDefs": [
            { "bVisible": false, "aTargets": [ 5 ] }
        ],
        "bFilter": false,
        "bPaginate": false,
        "bInfo": false,
        "aaSorting": [[ 0, 'desc' ]],
        "aoColumns": [
            { "bSortable": true, "sType": 'numeric'  },
            { "bSortable": true },
            { "bSortable": true },
            { "iDataSort": 5 },
            { "bSortable": true, "sType": 'numeric-comma'   },
            { "bSortable": true, "sType": 'numeric-comma'   },
            { "bSortable": true },
            { "bSortable": false }
        ]
    });
}

function resetValues(){
    $('#description').val('');
    $('#date_ini').val('');
    $('#date_ini_to').val('');
    $('#projectSearch').submit();
}
