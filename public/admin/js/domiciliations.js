$(document).ready(function(){

    $.indexTableSetup = function(){
        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "de_datetime-asc": function ( a, b ) {
                var x, y;
                if (jQuery.trim(a) !== '') {
                    var deDatea = jQuery.trim(a).split(' ');
                    var deTimea = deDatea[1].split(':');
                    var deDatea2 = deDatea[0].split('/');
                    if(typeof deTimea[2] != 'undefined') {
                        x = (deDatea2[2] + deDatea2[1] + deDatea2[0] + deTimea[0] + deTimea[1] + deTimea[2]) * 1;
                    } else {
                        x = (deDatea2[2] + deDatea2[1] + deDatea2[0] + deTimea[0] + deTimea[1]) * 1;
                    }
                } else {
                    x = Infinity; // = l'an 1000 ...
                }

                if (jQuery.trim(b) !== '') {
                    var deDateb = jQuery.trim(b).split(' ');
                    var deTimeb = deDateb[1].split(':');
                    deDateb = deDateb[0].split('/');
                    if(typeof deTimeb[2] != 'undefined') {
                        y = (deDateb[2] + deDateb[1] + deDateb[0] + deTimeb[0] + deTimeb[1] + deTimeb[2]) * 1;
                    } else {
                        y = (deDateb[2] + deDateb[1] + deDateb[0] + deTimeb[0] + deTimeb[1]) * 1;
                    }
                } else {
                    y = Infinity;
                }
                var z = ((x < y) ? -1 : ((x > y) ? 1 : 0));
                return z;
            },

            "de_datetime-desc": function ( a, b ) {
                var x, y;
                if (jQuery.trim(a) !== '') {
                    var deDatea = jQuery.trim(a).split(' ');
                    var deTimea = deDatea[1].split(':');
                    var deDatea2 = deDatea[0].split('/');
                    if(typeof deTimea[2] != 'undefined') {
                        x = (deDatea2[2] + deDatea2[1] + deDatea2[0] + deTimea[0] + deTimea[1] + deTimea[2]) * 1;
                    } else {
                        x = (deDatea2[2] + deDatea2[1] + deDatea2[0] + deTimea[0] + deTimea[1]) * 1;
                    }
                } else {
                    x = Infinity;
                }

                if (jQuery.trim(b) !== '') {
                    var deDateb = jQuery.trim(b).split(' ');
                    var deTimeb = deDateb[1].split(':');
                    deDateb = deDateb[0].split('/');
                    if(typeof deTimeb[2] != 'undefined') {
                        y = (deDateb[2] + deDateb[1] + deDateb[0] + deTimeb[0] + deTimeb[1] + deTimeb[2]) * 1;
                    } else {
                        y = (deDateb[2] + deDateb[1] + deDateb[0] + deTimeb[0] + deTimeb[1]) * 1;
                    }
                } else {
                    y = Infinity;
                }
                var z = ((x < y) ? 1 : ((x > y) ? -1 : 0));
                return z;
            }
        } );

        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "num-html-pre": function ( a ) {
                var x = String(a).replace( /<[\s\S]*?>/g, "" );
                return parseFloat( x );
            },

            "num-html-asc": function ( a, b ) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },

            "num-html-desc": function ( a, b ) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        } );

        $('#domiciliations-table').DataTable({
            "oLanguage": {
                "sUrl": "/admin/spanish.json"
            },
            "aaSorting": [[ 0, 'desc' ]],
            "bFilter": false,
            "bPaginate": false,
            "bInfo": false,
            "aoColumns": [
                { "bSortable": true, "sType": 'de_datetime'  },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true , "type": 'numeric' },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true , "type": 'numeric' },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": false }
            ],
            "scrollX":        true,
            "scrollCollapse": true,
            "fixedColumns":   {
                leftColumns: 3
            }
        });


    };



    var formValidationSettings = {
        ignore: [],
        rules: {
            'amount': { number: true, min: 5.00, max: 8000.00 },
            'phone': { phonegeneric: true, minlength: 9  },
            'address_type': {
                required: true
            },
            'province_code' : {
                required: true
            },
            account_number: {  exactlength: 24  }

        },
        messages: {
            'amount': 'Debes elegir una cantidad entre 5 y 8.000€ (Separar con "." cantidades decimales)',
            'address': 'Campo incompleto o erróneo',
            'phone': 'Sólo dígitos, usar "00" en vez de "+" para números fuera de España',
            'province_code': 'Campo incompleto o erróneo',
            'address_type' : {
                'required': 'Campo incompleto o erróneo'
            },
            'account_number': 'La cuenta debe tener 24 caracteres'
        }
    };

    //EVENTOS

    $("#domiciliationUserForm").validate(formValidationSettings);

});

function resetForm(){
    $('#date_ini').val('');
    $('#date_end').val('');
    $('#user').val('');
    $('#status').val('');
    $('#reset').val('1');
    $('#domiciliationsSearch').submit();
}

function submitFormDomiciliation(){
    $('#domiciliationUserForm').submit();
}




function exportDomiciliations(){
    var date_ini     = $('#date_ini').val();
    var date_end     = $('#date_end').val();
    var user         = $('#user').val();
    var status   = $('#status').val();

    window.location = '/admin/domiciliations/export?date_ini='+date_ini+'&date_end='+date_end+'&user='+user+'&status='+status;


}



function exportDomiciliationsAll(){

    window.location = '/admin/domiciliations/exportall';


}


function exportReceipts(){
    var date_ini     = $('#date_ini').val();
    var date_end     = $('#date_end').val();
    var user         = $('#user').val();
    var status   = $('#status').val();

    window.location = '/admin/domiciliations/exportReceipts?date_ini='+date_ini+'&date_end='+date_end+'&user='+user+'&status='+status;


}

function exportReceiptsAll(){

    window.location = '/admin/domiciliations/exportReceiptsAll';


}



function cambiaLimit(limit, nombreForm){

    $('#limit').val(limit);
    $('#pag').val(1);
    $('#'+nombreForm).submit();
}


function enviarForm(nombreForm){
    $('#pag').val(1);
    $('#'+nombreForm).submit();
}