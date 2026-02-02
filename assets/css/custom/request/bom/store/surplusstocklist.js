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
			url: base_path+'request/bomrequest/getsurplusstocklist',
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
				{ "mDataProp": "brandname" },	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '<a class="bold" href="' + base_path +'request/Bomrequest/surplusstockdetails' + '/' + encodeURIComponent(btoa(data.enq_id)) + '/reqId/' + encodeURIComponent(btoa(data.req_id)) +  '/itemCode/' + encodeURIComponent(btoa(data.item_code)) + '/pId/' + encodeURIComponent(btoa(data.pId)) + ' "> '+data.item_desc+' </a>';
					}
				},		
								
				{ "mDataProp": "garment_size" },
				{ "mDataProp": "item_code" },
				{ "mDataProp": "item_col_code" },
				{ "mDataProp": "size_dim" },
				{ "mDataProp": "uom" },
				{ "mDataProp": "surplus_qtys" },
				{ "mDataProp": "uom" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.pi_ref_no;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.cutoff_date;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        //var ORDERENQUIRYSTATUS = ['', 'PENDING', 'APPROVED', 'REJECTED', 'PENDING-RR'];
                        return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                        
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
                        else if(data.flag == "2")
                        return 'Inactive';
                        else
                        return 'Active';
					}
				},							
			] 					
		});
	}

});