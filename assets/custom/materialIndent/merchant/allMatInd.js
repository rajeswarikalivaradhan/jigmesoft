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
			url: base_path+'MerchantMaterialIndent/getAllMerchantMaterialIndent',
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
			        return '<input type="checkbox" class="allcbox" id="'+data.id+'">';
			      }
			    },		
				{ "mDataProp": "orderenqrefno" },	
                { "mDataProp": "brandname" },
                { 
					"mDataProp": function ( data, type, full, meta) {
						return '';
					}
				},	
                { 
					"mDataProp": function ( data, type, full, meta) {
						return 'Sample Department';
					}
				},	
				{ "mDataProp": "mat_ind_ref_no" },							
				{ "mDataProp": "req_date_time" },							
				{ "mDataProp": "cutoff_date" },							
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '';
					}
				},										
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '';
					}
				},										
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.status == "1")
                            return 'Accepted';
                        else if(data.status == "2")
                            return 'Rejected';
                        else
                            return 'Pending';
					}
				},
				{ "mDataProp": "recent_update_date" },	
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

    // $('#searchButton').click(function() {
    //     var form = $('#searchForm')[0];
    //     var data = new FormData(form);
    //     var url = base_path + "merchant/searchEnquiryList";
    //     $.ajax({
    //         url: url,
    //         method: "POST",
    //         data: data,
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         success: function(data) {
    //             enquiryJSON = $.parseJSON(data);
    //             dispDetails(enquiryJSON);   
    //         }
    //     });
	// });

    $('#refreshBtn').on('click', function () {
        location.reload();
	});

    
    // $('#btnChangeStatus').on('click', function () {
    //     var dropdownOpt = $('#frmItemStatus').val();
    //     // console.log(dropdownOpt,'dropdownOpt');
    //     var SelectedIdObject = commonCheckbox();
    //     var checkBoxLength   = SelectedIdObject[1];
    //     if (dropdownOpt > 0) {
    //         if (checkBoxLength >= 1) {
    //             var idJson = JSON.stringify(SelectedIdObject[0]);
    //             var StatusText = "Deactivate";
    //             if (dropdownOpt == 1) {
    //                 var StatusText = "Activate";
    //             }
    //             if (confirm('Do you want to ' + StatusText + ' this records?')) {
    //                 MakeAsynPostRequest(base_path + 'dashboard/changeAllListActiveStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
    //                     '&tblname=kn_order_enquiry', 'json', function (data) {
    //                     $.when(getEnquiryList()).done(function(){
    //                         dispDetails(enquiryJSON);		
    //                     });
    //                 });
    //             }
    //         }
    //     }
    //     else {
    //         alert('Select a option');
    //     }
    //     if(checkBoxLength == 0) {
    //         alert('Select a record');
    //     }
    // });

});