(function( $ ) {
	'use strict';

	var table =  $('#report').DataTable({
    "pageLength": 25,
    dom: 'lf<"export-buttons"B>rtip',
    "order": [ 1, 'desc' ],
    "language": {
        "search": "Search by any field:",
        "searchPlaceholder": "Search"
    },
    buttons: [{
            extend: 'copy',
            text: 'Copy to clipboard'
        }, {
            extend: 'colvis',
            text: 'Show Hide Cols'
        },
        'excel',
        'pdf',
        'csv'
    ],
        "footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
	 
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
	 
				// Total over all pages
			   var total = api
					.column( 5 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Total over this page
			   var pageTotal = api
					.column( 5, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
	 
				// Update footer
				$( api.column( 5 ).footer() ).html(
					'$'+pageTotal.toFixed(2) +' Page Total<br />($'+ total.toFixed(2) +' Grand Total)'
				);
       		 }
		});
     
     
     // jQuery UI Datepicker
     $(function() {
         $( "#datepicker" ).datepicker({
                altField: "#datepicker",
        // The format you want
        altFormat: "mm-dd-y",
        // The format the user actually sees
        dateFormat: "dd/mm/yy",
        onSelect: function () {
            var table = $('#report').DataTable();
            table.search( $(this).val() ).draw();
                }
            });
        });
     
//     $(".datefilter").html( '<label>Redemption Date</label>&nbsp; &nbsp;<input type="text" id="datepicker" placeholder="Click to Choose Date" />' );
     $(".export-buttons .dt-buttons").before('<span class="export-label">Export the current view / report: </span>');
     
     $('#startDate').datepicker({
        altField: "#startDate",
        altFormat: "mm-dd-y",
        onSelect: function(){
            return;
        }
     });
     
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
})( jQuery );
