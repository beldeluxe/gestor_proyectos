$(document).ready(function(){
    $.indexTableSetup = function(){
        var users_table = $('#users-table').DataTable({
            "oLanguage": {
                "sUrl": "/admin/spanish.json"
            },
            "aaSorting": [[ 1, 'desc' ]],
            "bFilter": false,
            "bPaginate": false,
            "bInfo": false,
            "aoColumns": [
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": false },
                { "bSortable": true },
                { "bSortable": false }
            ]
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

