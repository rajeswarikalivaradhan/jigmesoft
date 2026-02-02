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
			url: base_path+'company/Mstoreuser/getSupplyclosurelist',
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
						return '<a class="bold" href="' + base_path +'request/Bomrequest/supplyclosuredetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/' + encodeURIComponent(btoa(data.purchase_indent_id)) + '">'+data.pi_ref_queue_no+'</a>';
					}
				},	
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        // var d = new Date(data.appr_dt); 
                        // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return data.appr_dt;
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
						return data.exp_dod;
					}
				},		
				{ 
					"mDataProp": function ( data, type, full, meta) {
                         if(data.supply_status == '0' || data.supply_status == '')
                        return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                         else if(data.supply_status == '1')
                         return '<span class="text-light knGreenColor bg-dark"><strong>DESC. SUPPLY - CLOSED</strong></span>';
                         else if(data.supply_status == '2')
                         return '<span class="text-light knGreenColor bg-dark"><strong>SHORT SUPPLY - CLOSED</strong></span>';
                         else if(data.supply_status == '3')
                         return '<span class="text-light knGreenColor bg-dark"><strong>FULL SUPPLY - CLOSED</strong></span>';
                         else if(data.supply_status == '4')
                         return '<span class="text-light knRedColor bg-dark"><strong>P.I. CANCELLED</strong></span>';
                         else
                         return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
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