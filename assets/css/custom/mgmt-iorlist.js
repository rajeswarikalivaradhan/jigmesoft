$(document).ready(function() {

    var wipJSON;
	$.when(getIORList()).done(function(){
		dispDetails(wipJSON);		
	});

    $(document).ajaxStart(function(a){
        $.LoadingOverlay("show",{image: "../assets/img/fullpage.gif"});
    });
    $(document).ajaxStop(function(){
        $.LoadingOverlay("hide");
    });

	function getIORList()
	{
		return $.ajax({
			url: base_path+'management/getIORList',
			type:'POST',
			success:function(data){
				wipJSON = $.parseJSON(data);
			},		
			error: function() {
				console.log("Error");  
			}
		});
	}

	function dispDetails(wipJSON)
	{
		if ( $.fn.DataTable.isDataTable('#workInProgressTbl') ) {
		  $('#workInProgressTbl').DataTable().destroy();
		}

		$('#workInProgressTbl tbody').empty();	
		$("#workInProgressTbl").dataTable({
			"aaData": wipJSON,
            "aaSorting": [],
			"aoColumns": [			
				{
			      "mDataProp": function(data, type, full, meta) {
			        return '<tr><td><input type="checkbox" id="'+data.id+'" class="allcbox"></td>';
			      }
			    },		
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '<td><a href="' + base_path + 'Merchant/wipPrecosting/' + encodeURIComponent(btoa(data.id)) + '">' + data.isriorcode + '</a></td>';
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.formattedDateCreated);
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' + time;
						return dFormat;
					}
				},	
				{ "mDataProp": "orderenqrefno" },
				{ "mDataProp": "stylenamerefno" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        
                        urlIdPart = encodeURIComponent(btoa(data.id));
                        if(data.show == 1) {
                            var Budget = '<a href="' + base_path + 'budgetCosting/index/' + urlIdPart + '" target="_blank">' + 'Budget</a>';
                            var orderEntryLink = '<a href="' + base_path + 'orderentryvtwo/entry/' + urlIdPart + '" target="_blank">' + 'Order Entry</a>';
                            var CAD = '<a href="javascript:void(0)" target="_blank">CAD Requirement</a>';
                            var bomLink = '<a href="' + base_path + 'billofmaterials/article_1/' + urlIdPart + '" target="_blank">' + 'BOM Program</a>';
                            var fabricProgramLink = '<a href="' + base_path + 'fabricprogram/home/' + urlIdPart + '" target="_blank">' + 'Fabric Programme</a>';
                            var sampleReqLink = '<a href="' + base_path + 'msamplerequest/addeditsamplerequest/' + urlIdPart + '" target="_blank">' + 'Sample Requirement</a>';
                            var bomPurchaseRequestLink = '<a href="' + base_path + 'mpurchase/addeditbompurchase/' + urlIdPart + '" target="_blank">' + 'BOM (A1) Programme</a>';
                            var bomA2 = '<a href="javascript:void(0)" target="_blank">BOM (A2) Programme</a>';
                            var establishment = '<a href="javascript:void(0)" target="_blank">Establishment Programme</a>';
                            var packing = '<a href="javascript:void(0)" target="_blank">Packing Details</a>';
                            var lab = '<a href="javascript:void(0)" target="_blank">Lab Requirement</a>';
                            var fidetails = '<a href="javascript:void(0)" target="_blank">F.I. Details</a>';
                            var documentLogistics = '<a href="javascript:void(0)" target="_blank">Documentation & Logistics</a>';
                            var checklist = '<a href="javascript:void(0)" target="_blank">Check List</a>';
                            var precosting = '<a href="javascript:void(0)" target="_blank">Pre-costing</a>';
                            var cadRequestLink = '<a href="javascript:void(0)" target="_blank">' + 'CAD Request</a>';
                            var sampleRequestLink = '<a href="javascript:void(0)" target="_blank">' + 'Sample Request</a>';
                            var bomRequestLink = '<a href="javascript:void(0)" target="_blank">' + 'BOM Request</a>';
                        }
                        else {
                            var Budget = '<a href="javascript:void(0)" target="_blank">Budget</a>';
                            // var orderEntryLink = '<a href="javascript:void(0)" target="_blank">Order Entry</a>';
                            var orderEntryLink = '<a href="'+base_path+'WorkInProcess/index/' + urlIdPart + '" target="_blank">' + 'Order Entry</a>';
                            var CAD = '<a href="javascript:void(0)" target="_blank">CAD Requirement</a>';
                            var bomLink = '<a href="javascript:void(0)" target="_blank">BOM Program</a>';
                            var fabricProgramLink = '<a href="'+base_path+'WorkInProcess/fabric_program/' + urlIdPart + '" target="_blank">Fabric Program</a>';
                            var cadRequestLink = '<a href="javascript:void(0)" target="_blank">CAD Request</a>';
                            var sampleReqLink = '<a href="javascript:void(0)" target="_blank">Sample Requirement</a>';
                            var bomPurchaseRequestLink = '<a href="javascript:void(0)" target="_blank">BOM (A1) Request</a>';
                            var bomA2 = '<a href="javascript:void(0)" target="_blank">BOM (A2) Programme</a>';
                            var establishment = '<a href="javascript:void(0)" target="_blank">Establishment Programme</a>';
                            var packing = '<a href="javascript:void(0)" target="_blank">Packing Details</a>';
                            var lab = '<a href="javascript:void(0)" target="_blank">Lab Requirement</a>';
                            var fidetails = '<a href="javascript:void(0)" target="_blank">F.I. Details</a>';
                            var documentLogistics = '<a href="javascript:void(0)" target="_blank">Documentation & Logistics</a>';
                            var checklist = '<a href="javascript:void(0)" target="_blank">Check List</a>';
                            var precosting = '<a href="javascript:void(0)" target="_blank">Pre-costing</a>';
                            var cadRequestLink = '<a href="javascript:void(0)" target="_blank">' + 'CAD Request</a>';
                            var sampleRequestLink = '<a href="javascript:void(0)" target="_blank">' + 'Sample Request</a>';
                            var bomRequestLink = '<a href="javascript:void(0)" target="_blank">' + 'BOM Request</a>';
                        }

                        if(data.reqforisrior == 1) 
                        return '<td><div class="dropdown">' +
                            '<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                            data.brandname + 
                            '<span class="caret"></span>' +
                            '</button>' +
                            '<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">' +
                            '<li>'+orderEntryLink+'</li>' +
                            '<li>'+fabricProgramLink+'</li>' +
                            '</ul></div><td>';
                        else if(data.reqforisrior == 2)
                        return '<td><div class="dropdown">' +
                            '<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                            data.brandname + 
                            '<span class="caret"></span>' +
                            '</button>' +
                            '<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">' +
                            '<li>'+orderEntryLink+'</li>' +
                            '<li>'+fabricProgramLink+'</li>' +
                            '</ul></div><td>';
					}
				},											
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '<td><a href="' + base_path + 'dashboard/wipDetailPage/' + encodeURIComponent(btoa(data.id)) + '/' +
                        encodeURIComponent(btoa(data.poNoEnqRefNo)) + '/' + encodeURIComponent(btoa(data.ids)) + '">' +
                        '' + data.poNoEnqRefNo + '</a></td>';
					}
				},												
				{ "mDataProp": "poQtySampleQty" },							
				{ "mDataProp": "pcsorset" },																			
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.formattedShipmentSubDate); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' + time;
						return dFormat;
					}
				},										
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '-';
					}
				},																	
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = new Date(data.formattedDateUpdated); 
                        var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                        var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' + time;
						return dFormat;
					}
				},
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.status == "1")
                        return 'Active';
                        else if(data.status == "2")
                        return 'Inactive';
                        else
                        return '';
					}
				},
			]  						
		});
	}

    $('#searchButton').click(function() {
        var form = $('#searchForm')[0];
        var data = new FormData(form);
        var url = base_path + "merchant/searchWIPList";
        $.ajax({
            url: url,
            method: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                wipJSON = $.parseJSON(data);
                dispDetails(wipJSON);   
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
                    MakeAsynPostRequest(base_path + 'dashboard/changeWipStatus', 'id=' + idJson + '&cs=' + dropdownOpt, 'json', function (data) {
                        $.when(getIORList()).done(function(){
                            dispDetails(wipJSON);		
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