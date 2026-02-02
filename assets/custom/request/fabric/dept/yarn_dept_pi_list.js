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
			url: base_path+'request/Fabricrequest/getYarnMgmtPIList',
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
						//return i++;
						  return '<input type="checkbox" class="allcbox" id="'+data.id+'">';
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
						return 'Yarn';
					}
				},							
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.supply_lead_time);
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return dFormat;
					}
				},							
				{
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.pi_appl_req_date_time); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return dFormat;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '<a class="bold" href="' + base_path +'request/Fabricrequest/yarndeptpidetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqYarnId/' + encodeURIComponent(btoa(data.request_yarn_id)) + '"> P.I. No. </a>';
					}
				},	
				{ "mDataProp": "vendorname" },	
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.pi_appl_cutoff_date_time); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return dFormat;
					}
				},	
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
    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    $('#btnChangeStatus').on('click',function () {
        var StatusOptSelVal                         = $('#frmItemStatus').val();
        if(parseInt(StatusOptSelVal) > 0) {
            var ArrItemCheckBoxSel                  = commonCheckbox();
            var ObjChkSelVal                        = ArrItemCheckBoxSel[0];
            $('#ErrItemStatus').text("");
            if(parseInt(ArrItemCheckBoxSel[1]) == 0) {
               // $('#ErrItemStatus').html("Choose a record");
                swalWithBootstrapButtons.fire({
                title: 'Select a record!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
            }
            if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
                //$('#ErrItemStatus').html("");
                var StatusText                      = "Deactivate";
                if(StatusOptSelVal == '1') {
                    var StatusText                  = "Activate";
                }
                var indentTbls = ['tbl_request_yarn'];
                 swalWithBootstrapButtons.fire(
                            {
                               
                                title: 'Do you want to ' + StatusText + ' this record ?',
                                type: 'warning',
                                showCancelButton: true,
                                scrollbarPadding: false,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                reverseButtons: true,
                                width:460,
                                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                            }
				).then(function(result) {
						if (result.value) {
    					    MakeAsynPostRequest(base_path+'dashboard/fnChangeAllListStatus',"cs=" + StatusOptSelVal +"&keyField=requestid&id="+
                            JSON.stringify(ObjChkSelVal)+"&tblname="+JSON.stringify(indentTbls),'flag','json', function (data) {
                                 $.when(getReqRequestList()).done(function(){
                                dispDetails(reqRequestJSON);		
                            });
                            });
						} 
                }); 
               
                // if(confirm('Do you want to '+StatusText+' this records?')) {
                //     MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus',"cs=" + StatusOptSelVal +"&keyField=requestid&id="+
                //         JSON.stringify(ObjChkSelVal)+"&tblname="+JSON.stringify(indentTbls),'json',fnChangeStatusRes);
                // }
            }
        } else {
            //$('#ErrItemStatus').text("Choose an Option");
            swalWithBootstrapButtons.fire({
                title: 'Select a option!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
        }
    });
});