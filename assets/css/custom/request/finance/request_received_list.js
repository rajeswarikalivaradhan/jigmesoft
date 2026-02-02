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
			url: base_path+'request/bomrequest/getFinanceReqRecList',
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
						//return data.isriorcode;
						return '<a class="bold" href="' + base_path +'request/Bomrequest/financereqreceiveddetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/' + encodeURIComponent(btoa(data.purchase_indent_id)) + '"> '+ data.isriorcode +' </a>';
					}
				},		
				{ "mDataProp": "brandname" },
				{ "mDataProp": "request_for" },
				{ "mDataProp": "payment_requirement" },
				
				
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
						//return '<a class="bold" href="' + base_path +'request/Bomrequest/financereqreceiveddetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/' + encodeURIComponent(btoa(data.purchase_indent_id)) + '"> '+ data.pi_ref_queue_no +' </a>';
						return data.pi_ref_queue_no;
					}
				},
				// { 
				// 	"mDataProp": function ( data, type, full, meta) {
				// 		if(data.invoice_no == "")
				// 		return "-";
				// 		else
				// 		return data.invoice_no;
				// 	}
				// },		
				{ 
					"mDataProp": function ( data, type, full, meta) {
						if(data.pi_dt == "")
						return "-";
						else
						return data.pi_dt;
					}
				},		
				// { 
				// 	"mDataProp": function ( data, type, full, meta) {
				// 		return data.proforma_value;
				// 	}
				// },		
				// { 
				// 	"mDataProp": function ( data, type, full, meta) {
				// 		if(data.pay_currency == "")
				// 		return "-";
				// 		else
				// 		return data.pay_currency;
				// 	}
				// },		
				// { "mDataProp": "pay_by_date" },	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						if(data.pay_by_date == "01-01-1970")
						return "-";
						else
						return data.pay_by_date;
					}
				},
				{ "mDataProp": "inv_status" },						
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