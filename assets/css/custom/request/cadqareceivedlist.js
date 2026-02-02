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
			url: base_path+'company/mqausers/getCADRequestList',
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
		if ( $.fn.DataTable.isDataTable('#QaReceivedListTbl') ) {
		  $('#QaReceivedListTbl').DataTable().destroy();
		}

        var i = 1;

		$('#QaReceivedListTbl tbody').empty();	
		$("#QaReceivedListTbl").dataTable({
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
						if(data.type == 1)
						{
							return '<a class="bold" href="' + base_path +'request/Cadrequest/qareceiveddetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/cadId/' + encodeURIComponent(btoa(data.cad_requirement_id)) + '">' + data.isriorcode + '</a>';
						}
						else if(data.type == 2)
						{
							return '<a class="bold" href="' + base_path +'request/Samplerequest/qareceiveddetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/samId/' + encodeURIComponent(btoa(data.sample_requirement_id)) + '">' + data.isriorcode + '</a>';
						}
						else {
							return data.orderenqrefno;
						}
					}
				},
				{ "mDataProp": "brandname" },
				{
					"mDataProp": function ( data, type, full, meta) {
						if(data.type == 1)
						{
							return 'CAD Q.A.';
						}
						else if(data.type == 2)
						{
							return 'Sample Q.A.';
						}
						else {
							return '-';
						}
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        // var d = new Date(data.req_date); 
                        // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return data.qa_req_date;
					}
				},							
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        // var d = new Date(data.cutoff_date); 
                        // var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        // var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return data.qa_cutoff_date;
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
				{ "mDataProp": "auth_name" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
						if(data.type == 1)
						{
							return data.cad_name;
						}
						else if(data.type == 2)
						{
							return data.sample_name;
						}
						else {
							return '-';
						}
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						if(data.type == 1)
						{
							if(data.cad_qa_status == '0' || data.cad_qa_status == '')
							return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
							else if(data.cad_qa_status == '2')
							return '<span class="text-light knRedColor bg-dark"><strong>REJECTED</strong></span>';
							else
							return '<span class="text-light knGreenColor bg-dark"><strong>APPROVED</strong></span>';						}
						else if(data.type == 2)
						{
							if(data.sam_qa_status == '0' || data.sam_qa_status == '')
							return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
							else if(data.sam_qa_status == '1')
							return '<span class="text-light knRedColor bg-dark"><strong>APPROVED</strong></span>';
							else if(data.sam_qa_status == '2')
							return '<span class="text-light knGreenColor bg-dark"><strong>REJECTED</strong></span>';
						}
					}
				},		
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
                        else if(data.flag == "2")
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