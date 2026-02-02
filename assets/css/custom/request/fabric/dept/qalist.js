$(document).ready(function() {

    var allRequestJSON;
	$.when(getAllRequestList()).done(function(){
		dispDetails(allRequestJSON);		
	});

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

	function getAllRequestList()
	{
		return $.ajax({
			url: base_path+'request/Fabricrequest/getAllQAList',
			type:'POST',
			success:function(data){
				allRequestJSON = $.parseJSON(data);
			},		
			error: function() {
				console.log("Error");  
			}
		});
	}

	function dispDetails(allRequestJSON)
	{
		if ( $.fn.DataTable.isDataTable('#mAuthorizationList') ) {
		  $('#mAuthorizationList').DataTable().destroy();
		}

        var i = 1;

		$('#mAuthorizationList tbody').empty();	
		$("#mAuthorizationList").dataTable({
            "aaData": allRequestJSON,
            "aaSorting": [],
			"aoColumns": [
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.type == 1)
						return '<a class="bold" href="' + base_path +'request/Cadrequest/qadetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.orderenqrefno + '</a>';
                        else if(data.type == 2)
						return '<a class="bold" href="' + base_path +'request/Samplerequest/qadetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.orderenqrefno + '</a>';
                        else if(data.type == 3)
						return '<a class="bold" href="' + base_path +'request/Bomrequest/qadetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.orderenqrefno + '</a>';
                        else if(data.type == 4)
						return '<a class="bold" href="' + base_path +'request/Bom2request/qadetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.orderenqrefno + '</a>';
                        else if(data.type == 5)
						return '<a class="bold" href="' + base_path +'request/Fabricrequest/qadetails' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.orderenqrefno + '</a>';
                        else
						return '<a class="bold" href="javascript:void(0);">' + data.orderenqrefno + '</a>';
					}
				},				
				{ "mDataProp": "brandname" },	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						if(data.type == 5)
							return '<a class="bold" href="' + base_path +'request/Fabricrequest/yarnrequest' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">Queue No.</a>';
                        else
							return '<a class="bold" href="javascript:void(0);">Queue No.</a>';
					}
				},		
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '';
					}
				},		
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
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '';
					}
				},
				{ "mDataProp": "auth_type" },
				{ "mDataProp": "auth_by" },
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
                    MakeAsynPostRequest(base_path + 'dashboard/changeAllListActiveStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=kn_order_enquiry', 'json', function (data) {
                        $.when(getAllRequestList()).done(function(){
                            dispDetails(allRequestJSON);		
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