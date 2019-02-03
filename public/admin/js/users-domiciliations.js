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

        $.fn.dataTableExt.afnFiltering.push(
            function( oSettings, aData, iDataIndex ) {
                var iFini = $('#fini-dom').val();
                var iFfin = $('#ffin-dom').val();

                iFini=iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
                iFfin=iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);

                var dateFecha =aData[0].substring(6,10) + aData[0].substring(3,5)+ aData[0].substring(0,2);

                if ( iFini === "" && iFfin === "" )
                {
                    return true;
                }
                else if ( iFini <= dateFecha && iFfin === "")
                {
                    return true;
                }
                else if ( iFfin >= dateFecha && iFini === "")
                {
                    return true;
                }
                else if (iFini <= dateFecha && iFfin >= dateFecha)
                {
                    return true;
                }
                return false;
            }
        );

        $.fn.dataTableExt.afnFiltering.push(
            function( oSettings, aData, iDataIndex ) {
                var iFini = $('#fini').val();
                var iFfin = $('#ffin').val();

                iFini=iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
                iFfin=iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);

                var dateFecha =aData[0].substring(6,10) + aData[0].substring(3,5)+ aData[0].substring(0,2);

                if ( iFini === "" && iFfin === "" )
                {
                    return true;
                }
                else if ( iFini <= dateFecha && iFfin === "")
                {
                    return true;
                }
                else if ( iFfin >= dateFecha && iFini === "")
                {
                    return true;
                }
                else if (iFini <= dateFecha && iFfin >= dateFecha)
                {
                    return true;
                }
                return false;
            }
        );

        var domiciliations_table = $('#domiciliations-table').DataTable({
            "oLanguage": {
                "sUrl": "/admin/spanish.json"
            },
            "aaSorting": [[ 0, 'desc' ]],
            "bFilter": true,
            "bPaginate": true,
            "bInfo": false,
            "aoColumns": [
                { "bSortable": true, "sType": 'de_datetime' },
                { "bSortable": true },
                { "bSortable": true , "type": 'numeric' },
                { "bSortable": true  },
                { "bSortable": true,  "type": 'numeric' },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": false}
            ],
            "scrollX":        true,
            "scrollCollapse": true,
            "fixedColumns":   {
                leftColumns: 3
            }
        });

        $('#donations-table').DataTable({
            "oLanguage": {
                "sUrl": "/admin/spanish.json"
            },
            "aaSorting": [[ 0, 'desc' ]],
            "bFilter": true,
            "bPaginate": true,
            "bInfo": false,
            "aoColumns": [
                { "bSortable": true, "sType": 'de_datetime'  },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true , "type": 'numeric' },
                { "bSortable": true , "type": 'numeric' },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": false}
            ],
            "scrollX":        true,
            "scrollCollapse": true,
            "fixedColumns":   {
                leftColumns: 3
            }
        });

        // Add event listeners to the two range filtering inputs
        $('#fini').change( function() {
            $('#donations-table').dataTable().fnDraw(this.value);
        } );
        $('#ffin').change( function() {
            $('#donations-table').dataTable().fnDraw(this.value);
        } );

        $('#fini-dom').change( function() {
            $('#domiciliations-table').dataTable().fnDraw(this.value);
        } );
        $('#ffin-dom').change( function() {
            $('#domiciliations-table').dataTable().fnDraw(this.value);
        } );
    };
});

