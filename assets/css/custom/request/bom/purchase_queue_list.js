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
			url: base_path+'company/mpurchaseuser/getBomQueueList',
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
		if ( $.fn.DataTable.isDataTable('#mBomPurReqQList') ) {
		  $('#mBomPurReqQList').DataTable().destroy();
		}
        var i = 1;
		$('#mBomPurReqQList tbody').empty();	
		$("#mBomPurReqQList").dataTable({
            "aaData": reqRequestJSON,
            "aaSorting": [],
			"aoColumns": [	
                {
                    "mDataProp": function(data, type, full, meta) {
                        return '<input type="checkbox" class="allcbox" id="'+data.request_id+'">';
                    }
                },	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						//return data.isriorcode;
						return '<a class="bold" href="' + base_path +'request/Bomrequest/purchaseQueueDetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">'+data.isriorcode+'</a>';
					}
				},		
				{ "mDataProp": "brandname" },	
				
				{ 
					"mDataProp": function ( data, type, full, meta) {
						//return '<a class="bold" href="' + base_path +'request/Bomrequest/purchaseQueueDetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">'+data.ref_queue_no+'</a>';
						return data.ref_queue_no;
					}
				},	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						// if(data.type == 1)
						// return 'CAD';
						// else if(data.type == 2)
						// return 'SAMPLE';
						// else if(data.type == 3)
						// return 'BOM Article - 1';
						// else if(data.type == 4)
						// return 'BOM Article - 2';
						// else
						return 'PURCHASE REQ.';
					}
				},	
                {
					"mDataProp": function ( data, type, full, meta) {
                        // if(data.request_for == "P.I.APPROVAL")
						// {
							if(data.type == 3)
							{
								return 'BOM (A1)';
							}
							else {
								return 'BOM (A2)';
							}
						// }
                        // else if(data.request_for == "PAYMENT")
                        // return data.payment_requirement;
					}
				},
				{
					"mDataProp": function ( data, type, full, meta) {
                        // var d = new Date(data.req_date); 
                        // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return data.req_date;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        // var d = new Date(data.cutoff_date); 
                        // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return data.cutoff_date;
					}
				},{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.auth_type == '')
                        return '-';
                        else
                        return data.auth_type;
					}
				},
				{ "mDataProp": "auth_name" },
			//	{ "mDataProp": "merchant_name" },					
				{ "mDataProp": "bom_status" },					
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.log); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' +time;
						return dFormat;
					}
				},					
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.flag == "1")
                        return 'Active';
                        else if(data.flag == "0")
                        return 'Inactive';
                        else
                        return 'Active';
					}
				},
			]  						
		});
	}

    // $('#searchButton').click(function() {
    //     var form = $('#searchForm')[0];
    //     var data = new FormData(form);
    //     var url = base_path + "merchant/searchEnquiryList";
    //     $.ajax({
    //         url: url,
    //         method: "POST",
    //         data: data,
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         success: function(data) {
    //             reqRequestJSON = $.parseJSON(data);
    //             dispDetails(reqRequestJSON);   
    //         }
    //     });
	// });

    // $('#refreshBtn').on('click', function () {
    //     location.reload();
	// });

    
    // $('#btnChangeStatus').on('click',function () {
    //     var StatusOptSelVal                         = $('#frmItemStatus').val();
    //     if(parseInt(StatusOptSelVal) > 0) {
    //         var ArrItemCheckBoxSel                  = commonCheckbox();
    //         var ObjChkSelVal                        = ArrItemCheckBoxSel[0];
    //         $('#ErrItemStatus').text("");
    //         if(parseInt(ArrItemCheckBoxSel[1]) == 0) {$('#ErrItemStatus').html("Choose a record");}
    //         if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
    //             $('#ErrItemStatus').html("");
    //             var StatusText                      = "Deactivate";
    //             if(StatusOptSelVal == '1') {
    //                 var StatusText                  = "Activate";
    //             }
    //             var indentTbls = ['cadindentdetails','fabindentdetails','bomindentdetails'];
    //             if(confirm('Do you want to '+StatusText+' this records?')) {
    //                 MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus',"cs=" + StatusOptSelVal +"&keyField=requestid&id="+
    //                     JSON.stringify(ObjChkSelVal)+"&tblname="+JSON.stringify(indentTbls),'json',fnChangeStatusRes);
    //             }
    //         }
    //     } else {
    //         $('#ErrItemStatus').text("Choose an Option");
    //     }
    // });

});