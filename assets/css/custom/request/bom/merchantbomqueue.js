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
			url: base_path+'company/mqausers/getBomQueueList',
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
		if ( $.fn.DataTable.isDataTable('#merchantBomQueueTbl') ) {
		  $('#merchantBomQueueTbl').DataTable().destroy();
		}

        var i = 1;

		$('#merchantBomQueueTbl tbody').empty();	
		$("#merchantBomQueueTbl").dataTable({
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
						//return data.isriorcode;
						return '<a class="bold" href="' + base_path +'request/Bomrequest/merchantqueue' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">'+data.isriorcode+'</a>';
					}
				},			
				{ "mDataProp": "brandname" },	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						//return '<a class="bold" href="' + base_path +'request/Bomrequest/merchantqueue' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">'+data.ref_queue_no+'</a>';
						return data.ref_queue_no;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return 'PURCHASE REQ.';
					}
				},	
                {
					"mDataProp": function ( data, type, full, meta) {
						if(data.type == 3)
						{
							return 'BOM (A1)';
						}
						else {
							return 'BOM (A2)';
						}
					}
				},
				{
					"mDataProp": function ( data, type, full, meta) {
						return data.req_date;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.cutoff_date;
					}
				},{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.auth_type == '')
                        return '-';
                        else
                        return data.auth_type;
					}
				},
				{ "mDataProp": "auth_name" },
				// { "mDataProp": "merchant_name" },					
				{ "mDataProp": "bom_status" },					
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
                        $.when(getBomQueueList()).done(function(){
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