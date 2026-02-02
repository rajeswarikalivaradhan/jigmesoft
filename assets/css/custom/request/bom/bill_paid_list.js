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
			url: base_path+'request/bomrequest/getMgmtBillPaidList',
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
					  return '<input type="checkbox" class="allcbox" id="'+data.request_id+'">';
					}
				},	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						//return data.isriorcode;
						return '<a class="bold" href="' + base_path +'request/Bomrequest/billpaiddetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '/' + encodeURIComponent(btoa(data.purchase_indent_id))  + '">'+data.isriorcode+'</a>';
					}
				},		
				{ "mDataProp": "brandname" },
				{ "mDataProp": "pi_ref_queue_no" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.vendorname;
					}
				},	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						//return '<a class="bold" href="' + base_path +'request/Bomrequest/billpaiddetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">'+data.invoice_no+'</a>';
						return data.invoice_no;
					}
				},		
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.invoice_date;
					}
				},	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.invoice_value;
					}
				},
				
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.currency;
					}
				},
				
										
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.invoice_status;
					}
				},							
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        return data.logs;
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