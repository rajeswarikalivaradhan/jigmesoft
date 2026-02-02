$(document).ready(function() {

    var reqRequestJSON;
	$.when(getQueueList()).done(function(){
		dispDetails(reqRequestJSON);		
	});

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

	function getQueueList()
	{
		return $.ajax({
			url: base_path+'company/msamplinguser/getGarmentIssuedList',
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
		if ( $.fn.DataTable.isDataTable('#sampleQueueListTbl') ) {
		  $('#sampleQueueListTbl').DataTable().destroy();
		}

        var i = 1;

		$('#sampleQueueListTbl tbody').empty();	
		$("#sampleQueueListTbl").dataTable({
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
						return data.isriorcode;
					}
				},		
                { "mDataProp": "brandname" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.r_cutoff_date;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '<a class="bold" href="' + base_path +'request/Samplerequest/garmentreceiveddetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/samId/' + encodeURIComponent(btoa(data.sample_requirement_id)) + '">' + data.dc_ref_queue_no + '</a>';
					}
				},
				{
					"mDataProp": function ( data, type, full, meta) {
                        // var d = new Date(data.dc_dt); 
                        // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' +time;
						return data.dc_dt;
					}
				},
				{ "mDataProp": "sam_ref_no" },
				{
					"mDataProp": function ( data, type, full, meta) {
						return data.merchant_name;
					}
				},
				{
					"mDataProp": function ( data, type, full, meta) {
                        if(data.item_received_status == '0' || data.item_received_status == '')
                        return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                        else if(data.item_received_status == '1')
                        return '<span class="text-light knGreenColor bg-dark"><strong>RECEIVED</strong></span>';
                        else if(data.item_received_status == '2')
                        return '<span class="text-light knRedColor bg-dark"><strong>DISCREPANCY</strong></span>';
                        else if(data.item_received_status == '3')
                        return '<span class="text-light knRedColor bg-dark"><strong>MISSING</strong></span>';
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