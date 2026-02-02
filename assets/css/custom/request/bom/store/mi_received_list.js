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
			url: base_path+'company/Mstoreuser/getBOMMIReceivedList',
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
				{ "mDataProp": "isriorcode" },	
				{ "mDataProp": "brandname" },	
				{ "mDataProp": "ref_queue_no" },
				{
					"mDataProp": function ( data, type, full, meta) {
                        
							if(data.type == 2)
							{
								return 'SAMPLE';
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
						return '<a class="bold" href="' + base_path +'request/Bomrequest/mireceiveddetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '"> '+data.bom_ref_no+' </a>';
					}
				},	
				{ "mDataProp": "req_date" },
				{ "mDataProp": "cutoff_date" },
				// { "mDataProp": "merchant_name" },
				{ "mDataProp": "auth_type" },
				{ "mDataProp": "auth_name" },	
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        // if(data.mgmt_approval == '0' || data.mgmt_approval == '')
                        return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                        // else if(data.mgmt_approval == '2')
                        // return '<span class="text-light knRedColor bg-dark"><strong>ISSUED - PART</strong></span>';
                        // else
                        // return '<span class="text-light knGreenColor bg-dark"><strong>ISSUED - FULL</strong></span>';
					}
				},						
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

});