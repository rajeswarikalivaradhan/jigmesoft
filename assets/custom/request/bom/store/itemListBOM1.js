var reqRequestJSON;
const activeBtn = document.getElementById('btn-active');
const inactiveBtn = document.getElementById('btn-inactive'); 

$(document).ready(function() {
     if (sessionStorage.getItem('keepSearchOpen') === 'true') {
        $('.search_area').removeClass('hide'); // show search div
        $('.fa-search-plus').removeClass('fa-search-plus').addClass('fa-search');
        sessionStorage.removeItem('keepSearchOpen'); // clear flag
    }


    var reqRequestJSON;
	$.when(getReqRequestList()).done(function(){
		dispDetails(reqRequestJSON);		
	});

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

	function getReqRequestList()
	{
		return $.ajax({
			url: base_path+'company/Mstoreuser/getItemListBOM1',
			type:'POST',
			success:function(data){
				reqRequestJSON = $.parseJSON(data);
			},		
			error: function() {
				console.log("Error");  
			}
		});
	}

	function dispDetails(reqRequestJSON)
	{
		if ( $.fn.DataTable.isDataTable('#bomPurchaseReceivedListTbl') ) {
		  $('#bomPurchaseReceivedListTbl').DataTable().destroy();
		}

        var i = 1;

		$('#bomPurchaseReceivedListTbl tbody').empty();	
		$("#bomPurchaseReceivedListTbl").dataTable({
            "aaData": reqRequestJSON,
            "aaSorting": [],
			"aoColumns": [	
				{ 
					// "mDataProp": function ( data, type, full, meta) {
					// 	return i++;
					// }
					"mDataProp": function(data, type, full, meta) {
						return '<input type="checkbox" class="allcbox" id="'+data.request_id+'">';
					}
					
				},
				{ "mDataProp": "isriorcode" },
				{ "mDataProp": "brandname" },
				{ "mDataProp": "item_desc" },
				//{ "mDataProp": "bcm" },
				{ "mDataProp": "garment_size" },
				{ "mDataProp": "appr_item_code" },
				{ "mDataProp": "appr_item_color_code" },
				
				{ "mDataProp": "received_qtys" },
				{ "mDataProp": "received_uom" },
				//{ "mDataProp": "bom_ref_no" },
				//{ "mDataProp": "bom_cutoff_date" },
				{ "mDataProp": "item_status" },
					
				
				{ "mDataProp": "log" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.flag == "1")
                        return 'Active';
                        else if(data.flag == "2")
                        return 'Inactive';
                        else
                        return 'Active';
                        //return data.flag;
					}
				},								
			]  						
		});
	}

	let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    $('#btnChangeStatus').on('click', function () {
        activeBtn.classList.remove('active');
        inactiveBtn.classList.remove('active');

        var dropdownOpt = $('#frmItemStatus').val();
        console.log(dropdownOpt,'dropdownOpt');
        var SelectedIdObject = commonCheckbox();
        var checkBoxLength   = SelectedIdObject[1];
        if (dropdownOpt > 0) {
            if (checkBoxLength >= 1) {
                var idJson = JSON.stringify(SelectedIdObject[0]);
                var StatusText = "Deactivate";
                if (dropdownOpt == 1) {
                    var StatusText = "Activate";
                }
                swalWithBootstrapButtons.fire(
                            {
                               
                                title: 'Do you want to ' + StatusText + ' this record ?',
                                type: 'warning',
                                showCancelButton: true,
                                scrollbarPadding: false,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                reverseButtons: true,
                                width:460,
                                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                            }
				).then(function(result) {
					if (result.value) {
                      // alert(idJson);
						MakeAsynPostRequest(base_path + 'dashboard/changeReqStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=tbl_request'+'&idName=request_id ', 'json', function (data) {
                            $.when(getReqRequestList()).done(function(){
								dispDetails(reqRequestJSON)
								 
								   
                            });
                        });
					} 
                   
                   
                }); 
                
              
            }
        }
        else {
            // alert('Select a option');
            swalWithBootstrapButtons.fire({
                title: 'Select a option!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
        }
        if(checkBoxLength == 0) {
            // alert('Select a record');
            swalWithBootstrapButtons.fire({
                title: 'Select a record!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
        }
    });

    $('#refreshBtn').on('click', function () {
        location.reload();
	     });

         $('#btn-active').on('click', function () {
            satusval="1";
            activeBtn.classList.add('active');
            inactiveBtn.classList.remove('active');;
            const reqRequestJSON1 = reqRequestJSON.filter(item => item.flag === satusval);
            dispDetails(reqRequestJSON1);
	     });
         $('#btn-inactive').on('click', function () {
            satusval="2";
            inactiveBtn.classList.add('active');
            activeBtn.classList.remove('active');
            const reqRequestJSON1 = reqRequestJSON.filter(item => item.flag === satusval);
            dispDetails(reqRequestJSON1);
           
	     });

         
         $('#refreshBtn').on('click', function () {
     sessionStorage.setItem('keepSearchOpen', 'true'); // remember user preference
    location.reload(); // reload the page
	});

         $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

    var min = parseFloat($('#available_qty_min').val());
    var max = parseFloat($('#available_qty_max').val());

    if (isNaN(min)) min = 0;
    if (isNaN(max)) max = Infinity;

    // Get the cell HTML/text
    var qtyCellHtml = data[7] || "";
    var qtyCellText = $('<div>').html(qtyCellHtml).text().trim();

    // Extract all numbers
    var numbers = qtyCellText.match(/\d+(\.\d+)?/g) || [];

    // Convert to float
    numbers = numbers.map(function(n) { return parseFloat(n); });

    // ✅ Return true if ANY number in the cell is within min/max
    return numbers.some(function(n) {
        return n >= min && n <= max;
    });
});
 $('#searchButton').on('click', function() {


    var fromDate = $('#available_qty_min').val().trim();
    var toDate = $('#available_qty_max').val().trim();
   

    // Helper function to parse dd-mm-yyyy
    function parseDate(str) {
        var parts = str.split('-'); // ["dd", "mm", "yyyy"]
        return new Date(parts[2], parts[1] - 1, parts[0]); // year, month (0-based), day
    }

    // 1️⃣ Check RequestFrom / RequestTo
    if (fromDate && toDate) {
        var from = parseDate(fromDate);
        var to = parseDate(toDate);
        if (from > to) {
            // alert('Invalid date range');
            swalWithBootstrapButtons.fire({
                title: 'Invalid date range!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
            return;
        }
    }
    var table = $('#bomPurchaseReceivedListTbl').DataTable();

    var wip_ref_no = $('#wip_ref_no').val().toLowerCase();
    var brandId    = $('#brandId').val().toLowerCase();
	var item_description = $('#item_description').val().toLowerCase();
    var item_code   = $('#item_code').val().toLowerCase();
     var item_colour_code   = $('#item_colour_code').val().toLowerCase();
	var mi_status = $('#mi_status').val().toLowerCase();
    var item_code   = $('#item_code').val().toLowerCase();
   

    table
      .column(1).search(wip_ref_no)
      .column(2).search(brandId)
      .column(3).search(item_description)
      .column(5).search(item_code)
       .column(6).search(item_colour_code)
      .column(9).search(mi_status)
     
      .draw(); // ✅ redraw triggers custom filter
});



});