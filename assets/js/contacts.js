//
//Updates "Select all" control in a data table
//
function updateDataTableSelectAllCtrl(table) {
var $table = table.table().container();
var $chkbox_all = $('tbody input[type="checkbox"]', $table);
var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
var chkbox_select_all = $('thead input[type="checkbox"]', $table).get(0);

// If none of the checkboxes are checked
if ($chkbox_checked.length === 0) {
   chkbox_select_all.checked = false;
   if ('indeterminate' in chkbox_select_all) {
      chkbox_select_all.indeterminate = false;
   }

// If all of the checkboxes are checked
} else if ($chkbox_checked.length === $chkbox_all.length) {
   chkbox_select_all.checked = true;
   if ('indeterminate' in chkbox_select_all) {
      chkbox_select_all.indeterminate = false;
   }

// If some of the checkboxes are checked
} else {
   chkbox_select_all.checked = true;
   if ('indeterminate' in chkbox_select_all) {
      chkbox_select_all.indeterminate = true;
   }
}
}

$(document).ready(function() {
// Initialize the table
var uid = document.getElementById("uid").value;
var table = $('#contactsTable').DataTable({
	autoFill: true,
	"language": {
        "lengthMenu": "Display _MENU_ records per page",
        "zeroRecords": "Nothing found - sorry",
        "info": "Showing page _PAGE_ of _PAGES_",
        "infoEmpty": "No records available",
        "infoFiltered": "(filtered from _MAX_ total records)"
    		},
	 	"processing": true,
	    "serverSide": true,
	    "ajax":{
     	type: "POST",								// method  , by default get
         url :"helpers/dataTablesHelper.php", 		// json datasource
         data: {uid : uid},		
         error: function(){  						// error handling
         $(".employee-grid-error").html("");
         $("#contactsTable").append('<tbody class="danger"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
         $("#employee-grid_processing").css("display","none");
         // alert(uid);
         }
     },
   columnDefs: [
   	{
      	targets: 0,
      	orderable: false,
      	searchable: false,
      	className: 'dt-body-center',
      	render: function(data, type, full, meta) {
         	return '<input type="checkbox">';
	         }
	   }
   ],
   select: {
      style: 'multi'
   },
   order: [[1, 'asc']]
});

// Handle row selection event
$('#contactsTable').on('select.dt deselect.dt', function(e, api, type, items) {
   if (e.type === 'select') {
      $('tr.selected input[type="checkbox"]', api.table().container()).prop('checked', true);
   } else {
      $('tr:not(.selected) input[type="checkbox"]', api.table().container()).prop('checked', false);
   }

   // Update state of "Select all" control
   updateDataTableSelectAllCtrl(table);      
});

// Handle click on "Select all" control
$('#contactsTable thead').on('click', 'input[type="checkbox"]', function(e) {
   if (this.checked) {
      table.rows({ page: 'current' }).select();        
   } else {
      table.rows({ page: 'current' }).deselect();
   }
   
   e.stopPropagation();
});

// Handle click on heading containing "Select all" control
$('thead', table.table().container()).on('click', 'th:first-child', function(e) {
   $('input[type="checkbox"]', this).trigger('click');
});

// Handle table draw event
$('#contactsTable').on('draw.dt', function() {
   // Update state of "Select all" control
   updateDataTableSelectAllCtrl(table);
});

// Handle form submission event 
 $('#contactsForm').on('submit', function(e){
   var form = this;
   var data = [];
     // Iterate over all selected checkboxes
   table.rows({ selected: true }).every(function(index){
      // Get row ID
      var rowId = this.data()[0];
      data.push(this.data()[0]);
          
   //   Create a hidden element 
    $(form).append($('<input>').attr('type', 'hidden').attr('name', 'id[]').val(rowId));
    });

	//var rids= $(form).find('input[name="id\[\]"]').serialize();

	// alert(data);

	data = data.toString();
	   $.ajax({
		type: "POST",
		url: "helpers/contactDelete.php",
		data: {rids: data, uid: uid},			
		success: function(result) {
			// dataTable.draw(); // redrawing datatable
			alert(done);
		},
		error: function(result) {
			alert("something went wrong");
		},
		async: false
		
	});
    
//    // FOR DEMONSTRATION ONLY
//    // The code below is not needed in production
   
//    // Output form data to a console     
   //$('#console').text($(form).serialize());
   
   //console.log("Form submission", $(form).serialize());
   
   //$('#console').text($(form).find('input[name="id\[\]"]').serialize());
 //  $('#console').text(rids);
    
   // Remove added elements
 //  $('input[name="id\[\]"]', form).remove();
    
   // Prevent actual form submission
 	// e.preventDefault();
	});   

 
});
