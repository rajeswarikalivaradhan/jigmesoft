$(document).ready(function() {

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
			url: base_path+'MerchantRequestSent/getProductionList',
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
				{ "mDataProp": "orderenqrefno" },	
                { "mDataProp": "brandname" },
                { 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.type == 1) {
                            return '<a class="bold" href="' + base_path +'MerchantRequestSent/cadrequestlist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '">CAD</a>';
                        }
                        else if(data.type == 2) {
                            return '<a class="bold" href="' + base_path +'MerchantRequestSent/requestlist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '">SAMPLE</a>';
                        }
                        else if(data.type == 3) {
                            return '<a class="bold" href="' + base_path +'MerchantRequestSent/requestlist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '">BOM</a>';
                        }
                        else if(data.type == 4) {
                            return '<a class="bold" href="' + base_path +'MerchantRequestSent/requestlist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '">BOM 2</a>';
                        }
                        else if(data.type == 5) {
                            return '<a class="bold" href="' + base_path +'MerchantRequestSent/fabricrequestlist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id))+ '">FABRIC</a>';
                        }
                        else {
                            return '<a class="bold" href="javascript:void(0);">Request For</a>';
                        }
					}
				},	
                { 
					"mDataProp": function ( data, type, full, meta) {
						return '';
					}
				},	
				{ "mDataProp": "req_type" },							
				{ "mDataProp": "req_date" },							
				{ "mDataProp": "cutoff_date" },							
				{ "mDataProp": "auth_type" },							
				{ "mDataProp": "auth_by" },							
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '';
					}
				},										
				{ "mDataProp": "log" },																										
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