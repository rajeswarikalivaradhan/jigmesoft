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
			url: base_path+'request/Fabricrequest/getReqSentList',
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
						return '1';
					}
				},	
				{
					"mDataProp": function ( data, type, full, meta) {
						return data.orderenqrefno;
					}
				},		
				{ "mDataProp": "brandname" },	
				{
					"mDataProp": function ( data, type, full, meta) {
						return '<a class="bold" href="' + base_path +'request/Fabricrequest/reqsentdetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqYarnId/' + encodeURIComponent(btoa(data.request_yarn_id)) + '"> P.I. APPROVAL </a>';
					}
				},	
				{ "mDataProp": "req_type" },	
				{
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.req_date); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return dFormat;
					}
				},							
				{
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.cutoff_date); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return dFormat;
					}
				},
				{ "mDataProp": "auth_type" },
				{ "mDataProp": "auth_by" },
				{
					"mDataProp": function ( data, type, full, meta) {
						return '';
					}
				},
				{
					"mDataProp": function ( data, type, full, meta) {
                        var ORDERENQUIRYSTATUS = ['', 'PENDING', 'APPROVED', 'REJECTED', 'PENDING-RR'];
                        if(data.req_status == '0')
                        return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                        else
                        return ORDERENQUIRYSTATUS[2];
					}
				},								
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.log); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
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