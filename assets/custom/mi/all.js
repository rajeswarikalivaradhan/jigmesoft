$(document).ready(function() {
    alert("test");

    var reqRequestJSON;
	$.when(getAllMIList()).done(function(){
		dispDetails(reqRequestJSON);		
	});

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

	function getAllMIList()
	{
		return $.ajax({
			url: base_path+'merchant/getAllMIList',
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
		if ( $.fn.DataTable.isDataTable('#merchantSampleQueueTbl') ) {
		  $('#merchantSampleQueueTbl').DataTable().destroy();
		}

        var i = 1;

		$('#merchantSampleQueueTbl tbody').empty();	
		$("#merchantSampleQueueTbl").dataTable({
            "aaData": reqRequestJSON,
            "aaSorting": [],
			"aoColumns": [	
				{
					"mDataProp": function(data, type, full, meta) {
					  return '<input type="checkbox" class="allcbox" id="'+data.request_id+'">';
					}
				},			
				{
    "mDataProp": function (data, type, full, meta) {
        // Check if 'isriorcode' exists, otherwise return '-'
        return data.isriorcode != null && data.isriorcode != '' ? data.isriorcode : '-';
    }
},	
{
    "mDataProp": function (data, type, full, meta) {
        // Check if 'isriorcode' exists, otherwise return '-'
        return data.brandname != null && data.brandname != '' ? data.brandname : '-';
    }
},	
				
				{
					"mDataProp": function ( data, type, full, meta) {
                           if(data.type == 1)
							{
								return 'CAD';
							}
                        
							if(data.type == 2)
							{
								return 'CAD';
							}
							else if(data.type == 3)
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
                         if(data.type == 2)
							{
								return '<a class="bold" href="' + base_path +'request/Cadrequest/cadIndentDetails/'+ encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/miId/' + encodeURIComponent(btoa(data.mi_id)) + '">' + data.cad_ref_no + '</a>';
							}else{
                                return '-';
                            }
                            //  if(data.type == 3)
							// {
                            //     if(data.bom_ref_no !=null){
                            // return '<a class="bold" href="' + base_path +'request/Bomrequest/mireceiveddetails'+ encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/miId/' + encodeURIComponent(btoa(data.mi_id)) + '">' + data.bom_ref_no + '</a>';
                            //     }
                            //      return '-'
								
							// }
						
					}
				},	
				
				
                {
                     "mDataProp": function (data, type, full, meta) {
        // Check if 'isriorcode' exists, otherwise return '-'
                     return data.req_date != null && data.req_date != '' ? data.req_date : '-';
                     }
               },
                {
                     "mDataProp": function (data, type, full, meta) {
        // Check if 'isriorcode' exists, otherwise return '-'
                     return data.cutoff_date != null && data.cutoff_date != '' ? data.cutoff_date : '-';
                     }
               },
                						
				
                {
    "mDataProp": function (data, type, full, meta) {
        // Check if 'isriorcode' exists, otherwise return '-'
        return data.auth_type != null && data.auth_type != '' ? data.auth_type : '-';
    }
},
				// { "mDataProp": "auth_type" },
				// { "mDataProp": "merchant_note" },
                  {
    "mDataProp": function (data, type, full, meta) {
        // Check if 'isriorcode' exists, otherwise return '-'
        return data.auth_name != null && data.auth_name != '' ? data.auth_name : '-';
    }
},

				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var ORDERENQUIRYSTATUS = ['', 'PENDING', 'APPROVED', 'REJECTED', 'PENDING-RR'];
                        if(data.req_status == '0')
                        return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                        else
                        return ORDERENQUIRYSTATUS[2];
					}
				},								
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.log); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return dFormat;
					}
				},									
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.flag == "1")
                        return 'Active';
                        else if(data.flag == "2")
                        return 'Inactive';
                        else
                        return 'Active';
					}
				},							
			]  						
		});
	}
    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    $('#btnChangeStatus').on('click',function () {
        var StatusOptSelVal                         = $('#frmItemStatus').val();
        if(parseInt(StatusOptSelVal) > 0) {
            var ArrItemCheckBoxSel                  = commonCheckbox();
            var ObjChkSelVal                        = ArrItemCheckBoxSel[0];
            $('#ErrItemStatus').text("");
            if(parseInt(ArrItemCheckBoxSel[1]) == 0) {
               // $('#ErrItemStatus').html("Choose a record");
                swalWithBootstrapButtons.fire({
                title: 'Select a record!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
            }
            if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
                //$('#ErrItemStatus').html("");
                var StatusText                      = "Deactivate";
                if(StatusOptSelVal == '1') {
                    var StatusText                  = "Activate";
                }
                var indentTbls = ['cadindentdetails','fabindentdetails','bomindentdetails'];
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
    					    MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus',"cs=" + StatusOptSelVal +"&keyField=requestid&id="+
                            JSON.stringify(ObjChkSelVal)+"&tblname="+JSON.stringify(indentTbls),'json',fnChangeStatusRes);
						} 
                }); 
               
                // if(confirm('Do you want to '+StatusText+' this records?')) {
                //     MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus',"cs=" + StatusOptSelVal +"&keyField=requestid&id="+
                //         JSON.stringify(ObjChkSelVal)+"&tblname="+JSON.stringify(indentTbls),'json',fnChangeStatusRes);
                // }
            }
        } else {
            //$('#ErrItemStatus').text("Choose an Option");
            swalWithBootstrapButtons.fire({
                title: 'Select a option!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
        }
    });

});