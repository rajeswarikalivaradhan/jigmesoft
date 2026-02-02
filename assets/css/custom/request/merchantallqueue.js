$(document).ready(function() {

    var reqRequestJSON;
	$.when(getAllQueueList()).done(function(){
		dispDetails(reqRequestJSON);		
	});

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

	function getAllQueueList()
	{
		return $.ajax({
			url: base_path+'company/mqausers/getAllQueueList',
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
                        if(data.type == 1) {
                            return '<a class="bold" href="' + base_path +'request/Cadrequest/merchantqueue' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.isriorcode + '</a>';
                        }
                        else if(data.type == 2) {
                            return '<a class="bold" href="' + base_path +'request/Samplerequest/merchantqueue' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.isriorcode + '</a>';
                        }
                        else if(data.type == 3) {
                            return '<a class="bold" href="' + base_path +'request/Bomrequest/merchantqueue' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.isriorcode + '</a>';
                        }
                        else if(data.type == 4) {
                            return '<a class="bold" href="' + base_path +'request/Bomrequest/merchantqueue' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.isriorcode + '</a>';
                        }
                        else if(data.type == 5) {
                            return '<a class="bold" href="' + base_path +'request/Fabricrequest/merchantqueue' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.isriorcode + '</a>';
                        }
                        else {
                            return '<a class="bold" href="javascript:void(0);">-</a>';
                        }
					}
				},
				{ "mDataProp": "brandname" },	
				{ "mDataProp": "ref_queue_no" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.type == 1) {
                            return 'CAD';
                        }
                        else if(data.type == 2) {
                            return 'SAMPLE';
                        }
                        else if(data.type == 3) {
                            return 'PURCHASE REQ.';
                        }
                        else if(data.type == 4) {
                            return 'BOM 2';
                        }
                        else if(data.type == 5) {
                            return 'Fabric';
                        }
                        else {
                            return '-';
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
                            return'BOM (A1)';
                        }
                        else if(data.type == 4) {
                            return 'BOM (A2)';
                        }
                        else {
                            return '-';
                        }
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.req_date); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return data.req_date;
					}
				},							
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.cutoff_date); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
						return data.cutoff_date;
					}
				},
				{ "mDataProp": "auth_type" },
				{ "mDataProp": "auth_name" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.type == 1) {
                            return data.job_status;
                        }
                        else if(data.type == 2) {
                            return data.sample_status;
                        } 
                        else if(data.type == 3) {
                            return '<span class="text-light knOrangeColor bg-dark"><strong>IN QUEUE</strong></span>';
                        }
                        else {
                            return '-';
                        }
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
                    MakeAsynPostRequest(base_path + 'dashboard/changeReqStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=tbl_request'+'&idName=request_id', 'json', function (data) {
                        $.when(getAllQueueList()).done(function(){
                            dispDetails(reqRequestJSON);        
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