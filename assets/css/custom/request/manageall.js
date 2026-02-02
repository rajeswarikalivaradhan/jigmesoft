$(document).ready(function() {
    alert("33");

    var enquiryJSON;
	$.when(getEnquiryList()).done(function(){
		dispDetails(enquiryJSON);		
	});

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

	function getEnquiryList()
	{
		return $.ajax({
			url: base_path+'management/getManageAllList',
			type:'POST',
			success:function(data){
				enquiryJSON = $.parseJSON(data);
			},		
			error: function() {
				console.log("Error");  
			}
		});
	}

	function dispDetails(enquiryJSON)
	{
		if ( $.fn.DataTable.isDataTable('#MerAllReqSentAllList') ) {
		  $('#MerAllReqSentAllList').DataTable().destroy();
		}
        let userType = $('#userType').val();
		$('#MerAllReqSentAllList tbody').empty();	
		$("#MerAllReqSentAllList").dataTable({
            "aaData": enquiryJSON,
            "aaSorting": [],
			"aoColumns": [			
				{
			      "mDataProp": function(data, type, full, meta) {
			        return '<input type="checkbox" class="allcbox" id="'+data.request_id+'">';
			      }
			    },
				{
                    "mDataProp": function ( data, type, full, meta) {
                        
                        if(data.type == 1) {
                            return '<a class="bold" href="' + base_path +'MerchantRequestSent/cadrequestlist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '">'+data.isriorcode+'</a>';
                        }
                        else if(data.type == 2) {
                            return '<a class="bold" href="' + base_path +'MerchantRequestSent/samplerequestlist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '">'+data.isriorcode+'</a>';
                        }
                        else if(data.type == 3) {
                            return '<a class="bold" href="' + base_path +'MerchantRequestSent/bomrequestlist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '">'+data.isriorcode+'</a>';
                        }
                        else if(data.type == 4) {
                            return '<a class="bold" href="' + base_path +'MerchantRequestSent/bomrequestlist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '">'+data.isriorcode+'</a>';
                        }
                        else if(data.type == 5) {
                            return '<a class="bold" href="' + base_path +'MerchantRequestSent/fabricrequestlist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '">'+data.isriorcode+'</a>';
                        }
                        else {
                            return '<a class="bold" href="javascript:void(0);">Request For</a>';
                        }
                    }
                },
                { "mDataProp": "brandname" },
                { 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.type == 1) {
                            return 'CAD';
                        } else if(data.type == 2) {
                            return 'SAMPLE';
                        } else if(data.type == 3) {
                            return 'PURCHASE';
                        } else if(data.type == 4) {
                            return 'PURCHASE';
                        } else if(data.type == 5) {
                            return 'FABRIC';
                        } else  {
                            return 'Request For';
                        }
					}
				},	
                { 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.type == 1) {
                            return data.cad_requirement;                        
                        }
                        else if(data.type == 2) {
                            return data.sample_requirement; 
                        }
                        else if(data.type == 3) {
                            return data.bom_requirement;
                        }
                        else {
                            return '-';
                        }
					}
				},
				{ "mDataProp": "req_type" },
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
                { 
					"mDataProp": function ( data, type, full, meta) {
                        if(userType == 2) 
                            return data.auth_name;
                         else 
                            if(data.auth_type == "" || data.auth_type == null)
                            return '-';
                            else
                            return data.auth_type;
                        
                        
					}
				},
                {
					"mDataProp": function ( data, type, full, meta) {
                        if(userType == 2) 
                        if(data.auth_type == "" || data.auth_type == null)
                              return '-';
                            else
                              return data.auth_type;
                        else 
                            if(data.auth_name == "" || data.auth_name == null)
						      return '-';
                            else
                              return data.auth_name;
					}
				},
                { 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.type == 1) {
                            return data.cad_status;                        
                        }
                        else if(data.type == 2) {
                            return data.sample_status; 
                        }
                        else if(data.type == 3) {
                            return data.bom_status; 
                        }
                        else {
                            return '-';
                        }
					}
				},
				// { 
				// 	"mDataProp": function ( data, type, full, meta) {
                //         if(data.mgmt_approval == '0' || data.mgmt_approval == '')
                //         return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
                //         else if(data.mgmt_approval == '2')
                //         return '<span class="text-light knGreenColor bg-dark"><strong>AUTHORIZED</strong></span>';
                //         else if(data.mgmt_approval == '3')
                //         return '<span class="text-light knOrangeColor bg-dark"><strong>PENGING-RR</strong></span>';
                //         else
                //         return '<span class="text-light knRedColor bg-dark"><strong>DECLINIED</strong></span>';
				// 	}
				// },
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

    $('#searchButton').click(function() {
        var form = $('#searchForm')[0];
        var data = new FormData(form);
        var url = base_path + "merchant/searchEnquiryList";
        $.ajax({
            url: url,
            method: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                enquiryJSON = $.parseJSON(data);
                dispDetails(enquiryJSON);   
            }
        });
	});

    $('#refreshBtn').on('click', function () {
        location.reload();
	});

    
    $('#btnChangeStatus').on('click', function () {
        var dropdownOpt = $('#frmItemStatus').val();
        console.log(dropdownOpt,'dropdownOpt');
        var SelectedIdObject = commonCheckbox();
        var checkBoxLength   = SelectedIdObject[1];
        if (dropdownOpt > 0) {
            if (checkBoxLength >= 1) {
                var idJson = JSON.stringify(SelectedIdObject[0]);
                var StatusText = "Deactivate";
                if (dropdownOpt == 1) {
                    var StatusText = "Activate";
                }
                if (confirm('Do you want to ' + StatusText + ' this records?')) {
                    MakeAsynPostRequest(base_path + 'dashboard/changeReqStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=tbl_request'+'&idName=request_id', 'json', function (data) {
                        $.when(getEnquiryList()).done(function(){
                            dispDetails(enquiryJSON);       
                        });
                    });
                }
            }
        }
        else {
            alert('Select a option');
        }
        if(checkBoxLength == 0) {
            alert('Select a record');
        }
    });

});