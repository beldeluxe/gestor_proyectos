$.indexTableSetup = function(){
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

    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "date-euro-pre": function ( a ) {
            var x;

            if ( $.trim(a) !== '' ) {
                var frDatea = $.trim(a).split(' ');
                var frTimea = (undefined != frDatea[1]) ? frDatea[1].split(':') : [00,00,00];
                var frDatea2 = frDatea[0].split('/');
                x = (frDatea2[2] + frDatea2[1] + frDatea2[0] + frTimea[0] + frTimea[1] + ((undefined != frTimea[2]) ? frTimea[2] : 0)) * 1;
            }
            else {
                x = Infinity;
            }

            return x;
        },

        "date-euro-asc": function ( a, b ) {
            return a - b;
        },

        "date-euro-desc": function ( a, b ) {
            return b - a;
        }
    } );

    var table = $('#receipts-table').DataTable({
        "oLanguage": {
            "sUrl": "/admin/spanish.json"
        },
        "aaSorting": [[ 1, 'desc' ]],
        "bFilter": true,
        "bPaginate": true,
        "bInfo": false,
        "aoColumnDefs": [
            { "aTargets" : ["euro-date-column"] , "sType" : "date-euro"},
            { "aTargets" : ["euro-date-column"] , "sType" : "date-euro"},
            { "aTargets" : ["euro-date-column"] , "sType" : "date-euro"}
        ],
        "aoColumns": [
            { "bSortable": true, "type": 'date-euro' },
            { "bSortable": true, "type": 'date-euro' },
            { "bSortable": true, "type": 'date-euro' },
            { "bSortable": true, "type": 'numeric' },
            { "bSortable": true, "type": 'numeric' },
            { "bSortable": true, "type": 'numeric' },
            { "bSortable": true }
        ]
    });



    // Add event listeners to the two range filtering inputs
    $('#fini').change( function() {
        $('#receipts-table').dataTable().fnDraw(this.value);
    } );
    $('#ffin').change( function() {
        $('#receipts-table').dataTable().fnDraw(this.value);
    } );


};

