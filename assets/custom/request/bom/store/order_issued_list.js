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
			url: base_path+'company/Mstoreuser/getOrderIssuedList',
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
				{ "mDataProp": "item_desc" },
				//{ "mDataProp": "bcm" },
				{ "mDataProp": "garment_size" },
				{ "mDataProp": "appr_item_code" },
				{ "mDataProp": "appr_item_color_code" },
				{ "mDataProp": "size_dim" },
				{ "mDataProp": "uom" },	
				{ "mDataProp": "received_qtys" },
				{ "mDataProp": "received_uom" },
				{ "mDataProp": "bom_ref_no" },
				{ "mDataProp": "bom_cutoff_date" },
				{ "mDataProp": "item_status" },
					
				
				{ "mDataProp": "log" },
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