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
			url: base_path+'company/mqausers/getManagementQueueList',
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
		if ( $.fn.DataTable.isDataTable('#merchantSampleQueueTbl') ) {
		  $('#merchantSampleQueueTbl').DataTable().destroy();
		}

        var i = 1;

		$('#merchantSampleQueueTbl tbody').empty();	
		$("#merchantSampleQueueTbl").dataTable({
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
						return '<a class="bold" href="' + base_path +'request/Samplerequest/merchantqueue' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.isriorcode + '</a>';
					}
				},		
				{ "mDataProp": "brandname" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.ref_queue_no;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return 'SAMPLE Q.A.';
					}
				},
				{ "mDataProp": "sample_requirement" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        // var d = new Date(data.req_date); 
                        // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return data.req_date;
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
				{ "mDataProp": "merchant_name" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.auth_type == '')
                        return '-';
                        else
                        return data.auth_type;
					}
				},
				{ "mDataProp": "sample_status" },
				// { 
				// 	"mDataProp": function ( data, type, full, meta) {
				// 		let QAStatus = [ 'Q.A. PENDING', 'Q.A.SCHEDULED', 'Q.A. RE-SCHEDULED', 'Q.A. IN PROGRESS', 'NEED ALTERATION', 
				// 			'Q.A. PASS', 'Q.A. PASS COND.', 'Q.A. FAIL' ];
				// 		if(data.qa_status == 0)
				// 		return '<span class="text-light knOrangeColor bg-dark"><strong>'+QAStatus[data.qa_status]+'</strong></span>';
				// 		if(data.qa_status == 6)
				// 		return '<span class="text-light knRedColor bg-dark"><strong>'+QAStatus[data.qa_status]+'</strong></span>';
				// 		else
				// 		return '<span class="text-light knGreenColor bg-dark"><strong>'+QAStatus[data.qa_status]+'</strong></span>';
				// 	}
				// },
				{
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.recent_update); 
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
    
    $('#btnChangeStatus').on('click',function () {
        var StatusOptSelVal                         = $('#frmItemStatus').val();
        if(parseInt(StatusOptSelVal) > 0) {
            var ArrItemCheckBoxSel                  = commonCheckbox();
            var ObjChkSelVal                        = ArrItemCheckBoxSel[0];
            $('#ErrItemStatus').text("");
            if(parseInt(ArrItemCheckBoxSel[1]) == 0) {$('#ErrItemStatus').html("Choose a record");}
            if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
                $('#ErrItemStatus').html("");
                var StatusText                      = "Deactivate";
                if(StatusOptSelVal == '1') {
                    var StatusText                  = "Activate";
                }
                var indentTbls = ['cadindentdetails','fabindentdetails','bomindentdetails'];
                if(confirm('Do you want to '+StatusText+' this records?')) {
                    MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus',"cs=" + StatusOptSelVal +"&keyField=requestid&id="+
                        JSON.stringify(ObjChkSelVal)+"&tblname="+JSON.stringify(indentTbls),'json',fnChangeStatusRes);
                }
            }
        } else {
            $('#ErrItemStatus').text("Choose an Option");
        }
    });

});