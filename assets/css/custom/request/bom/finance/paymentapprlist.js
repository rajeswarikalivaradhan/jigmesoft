$(document).ready(function() {

    var reqRequestJSON;
	$.when(getBomQueueList()).done(function(){
		dispDetails(reqRequestJSON);		
	});

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

	function getBomQueueList()
	{
		return $.ajax({
			url: base_path+'request/Bomrequest/getPaymentReceivedList',
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
		if ( $.fn.DataTable.isDataTable('#paymentReceivedList') ) {
		  $('#paymentReceivedList').DataTable().destroy();
		}

        var i = 1;

		$('#paymentReceivedList tbody').empty();	
		$("#paymentReceivedList").dataTable({
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
						return '<a class="bold" href="' + base_path +'request/Bomrequest/paymentReceiveList' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.orderenqrefno + '</a>';
					}
				},		
				{ "mDataProp": "brandname" },	
				{ "mDataProp": "pi_date" },		
				{ "mDataProp": "pi_ref_no" },		
				{ "mDataProp": "vendorname" },			
				{ "mDataProp": "req_type" },			
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.req_date); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return dFormat;
					}
				},							
				{ "mDataProp": "amount" },
				{ "mDataProp": "currency" },
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
    
    // $('#btnChangeStatus').on('click',function () {
    //     var StatusOptSelVal                         = $('#frmItemStatus').val();
    //     if(parseInt(StatusOptSelVal) > 0) {
    //         var ArrItemCheckBoxSel                  = commonCheckbox();
    //         var ObjChkSelVal                        = ArrItemCheckBoxSel[0];
    //         $('#ErrItemStatus').text("");
    //         if(parseInt(ArrItemCheckBoxSel[1]) == 0) {$('#ErrItemStatus').html("Choose a record");}
    //         if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
    //             $('#ErrItemStatus').html("");
    //             var StatusText                      = "Deactivate";
    //             if(StatusOptSelVal == '1') {
    //                 var StatusText                  = "Activate";
    //             }
    //             var indentTbls = ['cadindentdetails','fabindentdetails','bomindentdetails'];
    //             if(confirm('Do you want to '+StatusText+' this records?')) {
    //                 MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus',"cs=" + StatusOptSelVal +"&keyField=requestid&id="+
    //                     JSON.stringify(ObjChkSelVal)+"&tblname="+JSON.stringify(indentTbls),'json',fnChangeStatusRes);
    //             }
    //         }
    //     } else {
    //         $('#ErrItemStatus').text("Choose an Option");
    //     }
    // });

});