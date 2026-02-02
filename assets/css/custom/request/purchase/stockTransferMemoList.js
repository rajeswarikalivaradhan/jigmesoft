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
			url: base_path+'request/bomrequest/getstocktransferlist',
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
					    return data.isriorcode;
					}
				},		
				{ "mDataProp": "brandname" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return "BOM (Art-1)";
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.pi_ref_no;
					}
				},
				{ "mDataProp": "pi_dt" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.cutoff_date;
					}
				},
				 { "mDataProp": "stm_ref_no" },
				// { 
				// 	"mDataProp": function ( data, type, full, meta) {
				// 		return '<a class="bold" href="' + base_path +'request/Bomrequest/stocktransferdetails' + '/' + encodeURIComponent(btoa(data.stm_ref_no)) + ' "> '+data.stm_ref_no+' </a>';
				// 	}
				// },
				{ "mDataProp": "stm_date_time" },
				{ "mDataProp": "transfer_category" },
				{ "mDataProp": "status" },
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