var reqRequestJSON;
const activeBtn = document.getElementById('btn-active');
const inactiveBtn = document.getElementById('btn-inactive');  
$(document).ready(function() {
	

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
			url: base_path+'company/mpurchaseuser/getBomPurchaseIndentListbom2',
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
                    "mDataProp": function(data, type, full, meta) {
                        return '<input type="checkbox" class="allcbox" id="'+data.request_status_id+'">';
                    }
                },	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						//return data.isriorcode;
						return '<a class="bold" href="' + base_path +'request/Bomrequest/purchaseindentdetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/' + encodeURIComponent(btoa(data.purchase_indent_id)) + '">' + data.isriorcode + '</a>';
					}
				},		
				{ "mDataProp": "brandname" },
				{ "mDataProp": "request_for" },
				// { "mDataProp": "payment_requirement" },
				
				{
					"mDataProp": function ( data, type, full, meta) {
                        
							if(data.type == 3)
							{
								return 'BOM (A1)';
							}
							else {
								return 'BOM (A2)';
							}
						
					}
				},
				
                {
					"mDataProp": function ( data, type, full, meta) {
                        return data.vendorname;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						//return '<a class="bold" href="' + base_path +'request/Bomrequest/purchaseindentdetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.pi_ref_queue_no + '</a>';
						return data.pi_ref_queue_no;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						if(data.pi_dt == "")
						return "-";
						else
						return data.pi_dt;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        return data.cutoff_date;
					}
				},
				{ "mDataProp": "exp_dod" },
				{ "mDataProp": "inv_status" },						
				// { 
				// 	"mDataProp": function ( data, type, full, meta) {
    //                     var d = new Date(data.logs); 
    //                     var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    //                     var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' +time;
				// 		return dFormat;
				// 	}
				// },
				
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        return data.logs;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.flags == "1")
                        return 'Active';
                        else if(data.flags == "2")
                        return 'Inactive';
                        else
                        return 'Active';
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
                       
						MakeAsynPostRequest(base_path + 'dashboard/changeReqStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=tbl_request_status'+'&idName=request_status_id ', 'json', function (data) {
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


     $('#btn-active').on('click', function () {
            satusval="1";
            activeBtn.classList.add('active');
            inactiveBtn.classList.remove('active');;
            const reqRequestJSON1 = reqRequestJSON.filter(item => item.flags === satusval);
            dispDetails(reqRequestJSON1);
	     });
         $('#btn-inactive').on('click', function () {
            satusval="2";
            inactiveBtn.classList.add('active');
            activeBtn.classList.remove('active');
            const reqRequestJSON1 = reqRequestJSON.filter(item => item.flags=== satusval);
            dispDetails(reqRequestJSON1);
           
	     });



});