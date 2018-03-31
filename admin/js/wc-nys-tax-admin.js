(function( $ ) {
	'use strict';

	var table =  $('#report').DataTable({
    "pageLength": 50,
    dom: 'lf<"export-buttons"B>rtip',
    "order": [ 0, 'desc' ],
    "language": {
        "search": "Search by any field:",
        "searchPlaceholder": "Search"
    },
    buttons: [{
            extend: 'copy',
            text: 'Copy to clipboard'
        },
        'excel',
        'pdf',
        'csv'
        ],
    });
     
     
     // jQuery UI Datepicker
     $(function() {
         $( ".range_datepicker.from" ).datepicker({
            // The format you want
            altFormat: "yy-mm-dd",
            // The format the user actually sees
            dateFormat: "yy-mm-dd",
            });
        
        
        $(".range_datepicker.to").datepicker({
            dateFormat: "yy-mm-dd",
        });
 
    
    $(".export-buttons .dt-buttons").before('<span class="export-label">Export the current view / report: </span>');
     
 
     
     $('input[name="range"]').change(function(){
         var chk = $('input[name="range"]:checked').val();
            if (chk === 'multi'){
                $('span.endDate').html('<input type="text" id="endDate" name="endDate" placeholder="Choose End Date">');
                $('#startDate').attr('placeholder', "Choose Start Date");
                    $('#endDate').datepicker({
                        altField: "#endDate",
                        altFormat: "mm-dd-y",
                        onSelect: function(){
                            return;
                        }
                });
            }
         else {
             $('span.endDate').html('');
             $('#startDate').attr('placeholder', "Choose Date");
         }
     });
    });
})( jQuery );