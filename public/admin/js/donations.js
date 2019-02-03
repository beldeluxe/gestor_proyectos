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

    $('#index-table').DataTable({
        "oLanguage": {
            "sUrl": "/admin/spanish.json"
        },
        "aaSorting": [[ 1, 'desc' ]],
        "bFilter": false,
        "bPaginate": false,
        "bInfo": false,
        "aoColumns": [
            { "bSortable": true,  "type": 'num-html'  },
            { "bSortable": true, "sType": 'de_datetime'  },
            { "bSortable": true },
            { "bSortable": true },
            { "bSortable": false },
            { "bSortable": true },
            { "bSortable": true },
            { "bSortable": true , "type": 'numeric' },
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

    if ($( "#source").val() == 'EURO_SOLIDARIO'){
        $( "#business_section").removeClass('hidden');
    }

    $( "#source" ).change(function() {
        if ($( "#source").val() == 'EURO_SOLIDARIO'){
            $( "#business_section").removeClass('hidden');
        }else{
            $( "#business_section").addClass('hidden');
            $( "#business").val('');
        }
    });
};

});

function cambiaLimit(limit, nombreForm){
    $('#limit').val(limit);
    $('#pag').val(1);
    $('#'+nombreForm).submit();
}

function enviarForm(nombreForm){
    $('#pag').val(1);
    $('#'+nombreForm).submit();
}

function exportDonations(){
    var date_ini     = $('#date_ini').val();
    var date_end     = $('#date_end').val();
    var user         = $('#user').val();
    var project_id   = $('#project_id').val();
    var language_id  = $('#language_id').val();
    var country_code = $('#country_code').val();
    var partner      = $('#partner').val();
    var source      = $('#source').val();

    window.location = '/admin/donations/export?date_ini='+date_ini+'&date_end='+date_end+'&user='+user+'&project_id='+project_id+'&language_id='+language_id+'&country_code='+country_code+'&partner='+partner+'&source='+source;


}


function exportDonationsAll(){

    window.location = '/admin/donations/exportall';


}

