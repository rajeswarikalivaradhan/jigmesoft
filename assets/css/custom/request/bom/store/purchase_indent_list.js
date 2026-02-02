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
			url: base_path+'company/Mstoreuser/getPurchaseIndentList',
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
					"mDataProp": function ( data, type, full, meta) {
						return i++;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '<a class="bold" href="' + base_path +'request/Bomrequest/storepurchaseindentdetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/' + encodeURIComponent(btoa(data.purchase_indent_id)) + '"> '+data.isriorcode+' </a>';
					}
				},	
				{ "mDataProp": "brandname" },	
				// { 
				// 	"mDataProp": function ( data, type, full, meta) {
				// 		if(data.purchase_req_type == 'sample') 
				// 		return 'Sample Qty.';
				// 		else if(data.purchase_req_type == 'bulk') 
				// 		return 'Bulk Qty.';
				// 		else if(data.purchase_req_type == 'revised') 
				// 		return 'Revised Qty.';
				// 		else if(data.purchase_req_type == 'shortage') 
				// 		return 'Shortage Qty.';
				// 	}
				// },		
				
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
						return '<a class="bold" href="' + base_path +'request/Bomrequest/storepiupdate' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/' + encodeURIComponent(btoa(data.purchase_indent_id)) + '">'+data.pi_ref_queue_no+'</a>';
					}
				},	
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        // var d = new Date(data.cutoff_date); 
                        // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return data.cutoff_date;
					}
				},	
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        // var d = new Date(data.appr_dt); 
                        // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return data.cutoff_date;
					}
				},	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.exp_dod;
					}
				},		
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        // if(data.mgmt_approval == '0' || data.mgmt_approval == '')
                        return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                        // else if(data.mgmt_approval == '2')
                        // return '<span class="text-light knRedColor bg-dark"><strong>APPROVED</strong></span>';
                        // else
                        // return '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>';
					}
				},						
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.logs); 
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

});