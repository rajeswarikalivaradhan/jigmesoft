$(document).ready(function() {

    var reqRequestJSON;
	$.when(getCADQueueList()).done(function(){
		dispDetails(reqRequestJSON);		
	});

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

	function getCADQueueList()
	{
		return $.ajax({
			url: base_path+'company/mqausers/getCADQueueList',
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
						return '<a class="bold" href="' + base_path +'request/Cadrequest/merchantqueue' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.isriorcode + '</a>';
					}
				},		
				{ "mDataProp": "brandname" },
				{ "mDataProp": "ref_queue_no" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return 'CAD';
					}
				},	
				{ "mDataProp": "cad_requirement" },	
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
				{ "mDataProp": "auth_type" },
				{ "mDataProp": "auth_name" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.job_status;
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
                        $.when(getCADQueueList()).done(function(){
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