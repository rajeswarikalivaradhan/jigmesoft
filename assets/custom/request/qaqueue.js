const activeBtn = document.getElementById('btn-active');
const inactiveBtn = document.getElementById('btn-inactive');  
var reqRequestJSON;
$(document).ready(function() {
	if (sessionStorage.getItem('keepSearchOpen') === 'true') {
        $('.search_area').removeClass('hide'); // show search div
        $('.fa-search-plus').removeClass('fa-search-plus').addClass('fa-search');
        sessionStorage.removeItem('keepSearchOpen'); // clear flag
    }

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
			url: base_path+'company/mqausers/getQAQueueList',
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
		if ( $.fn.DataTable.isDataTable('#QaQueueListTbl') ) {
		  $('#QaQueueListTbl').DataTable().destroy();
		}

        var i = 1;

		$('#QaQueueListTbl tbody').empty();	
		$("#QaQueueListTbl").dataTable({
            "aaData": reqRequestJSON,
            "aaSorting": [],
			"aoColumns": [		
				{
			      "mDataProp": function(data, type, full, meta) {
                  if(data.type == 1) {
							 return '<input type="checkbox" class="allcbox" id="'+data.cad_requirement_id+'">';
						}
						else if(data.type == 2) {
							 return '<input type="checkbox" class="allcbox" id="'+data.sample_requirement_id+'">';
						}
			       
			      }
			    },	
				{ 
					"mDataProp": function ( data, type, full, meta) {
						if(data.type == 1) {
							return '<a class="bold" href="' + base_path +'request/Cadrequest/queuelist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/cadId/' + encodeURIComponent(btoa(data.qa_req_id)) + '">' + data.isriorcode + '</a>';
						}
						else if(data.type == 2) {
							return '<a class="bold" href="' + base_path +'request/Samplerequest/queuelist' + '/' + encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/samId/' + encodeURIComponent(btoa(data.qa_req_ids)) + '">' + data.isriorcode + '</a>';
						}
					}
				},
				{ "mDataProp": "brandname" },	
				{ "mDataProp": "ref_queue_no" },	
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
						return data.item;
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
				{ "mDataProp": "auth_type" },
				{ "mDataProp": "auth_name" },
				
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return data.qa_status;
						//alert(data.type);
					// 	let QAStatus = ['IN QUEUE', 'Q.A.SCHEDULED', 'Q.A.RE-SCHEDULED', 'Q.A.IN PROGRESS', 'NEED ALTERATION', 'Q.A.PASS', 'Q.A.PASS COND', 'Q.A.FAIL' ];
				
					// 	if(data.type == 1)
					// 	{
					// 		return data.qa_status;
					// 		//alert(data.qa_status);
					// // 		if(data.qa_status == '0' || data.qa_status == '')
					// // 		return '<span class="text-light knOrangeColor bg-dark"><strong>'+QAStatus[0]+'</strong></span>';
					// // else if(data.qa_status == 5 || data.qa_status == 6)
					// // 	return '<span class="text-light knGreenColor bg-dark"><strong>'+QAStatus[data.qa_status]+'</strong></span>';
					// // else if(data.qa_status == 7)
					// // 	return '<span class="text-light knRedColor bg-dark"><strong>'+QAStatus[data.qa_status]+'</strong></span>';
					// // else
					// // return '<span class="text-light knOrangeColor bg-dark"><strong>'+QAStatus[data.qa_status]+'</strong></span>';
					 				
					// 	}
						
					// 		else if(data.type == 2)
					// 	{

					// 		if(data.qa_status == '0' || data.qa_status == '')
					// 		// return '<span class="text-light knOrangeColor bg-dark"><strong>PENDING</strong></span>';
					// 		// else if(data.sam_qa_status == '1')
					// 		// return '<span class="text-light knGreenColor bg-dark"><strong>ACCEPTED</strong></span>';
					// 		// else if(data.sam_qa_status == '2')
					// 		// return '<span class="text-light knRedColor bg-dark"><strong>REJECTED</strong></span>';
						
					// return '<span class="text-light knOrangeColor bg-dark"><strong>'+QAStatus[0]+'</strong></span>';
					// else if(data.qa_status == 5 || data.qa_status == 6)
					// 	return '<span class="text-light knGreenColor bg-dark"><strong>'+QAStatus[data.qa_status]+'</strong></span>';
					// else if(data.qa_status == 7)
					// 	return '<span class="text-light knRedColor bg-dark"><strong>'+QAStatus[data.qa_status]+'</strong></span>';
					// else
					// return '<span class="text-light knOrangeColor bg-dark"><strong>'+QAStatus[data.qa_status]+'</strong></span>';
					 
						
						
					//  	}
						
					}
				},			
				{ 
					"mDataProp": function ( data, type, full, meta) {
						// var d = new Date(data.recent_update); 
						// 	var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
						// 	var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' +time;
						// 	return dFormat;
						return data.recent_update;
					}
				},									
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.flags == "1")
                        return 'Active';
                        else if(data.flags == "2")
                        return 'Inactive';
                        else
                        return 'Active';
					}
				},							
			]  						
		});
	}
    
    // $('#btnChangeStatus').on('click',function () {
    //     var StatusOptSelVal                         = $('#frmItemStatus').val();
    //     if(parseInt(StatusOptSelVal) > 0) {
    //         var ArrItemCheckBoxSel                  = commonCheckbox();
    //         var ObjChkSelVal                        = ArrItemCheckBoxSel[0];
    //         $('#ErrItemStatus').text("");
    //         if(parseInt(ArrItemCheckBoxSel[1]) == 0) {$('#ErrItemStatus').html("Choose a record");}
    //         if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
    //             $('#ErrItemStatus').html("");
    //             var StatusText                      = "Deactivate";
    //             if(StatusOptSelVal == '1') {
    //                 var StatusText                  = "Activate";
    //             }
    //             var indentTbls = ['cadindentdetails','fabindentdetails','bomindentdetails'];
    //             if(confirm('Do you want to '+StatusText+' this records?')) {
    //                 MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus',"cs=" + StatusOptSelVal +"&keyField=requestid&id="+
    //                     JSON.stringify(ObjChkSelVal)+"&tblname="+JSON.stringify(indentTbls),'json',fnChangeStatusRes);
    //             }
    //         }
    //     } else {
    //         $('#ErrItemStatus').text("Choose an Option");
    //     }
    // });

	let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});

    $('#btnChangeStatus').on('click', function () {
	activeBtn.classList.remove('active');
	inactiveBtn.classList.remove('active');
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
                }).then(function(result) {
					
					if (result.value) {
                       
					MakeAsynPostRequest(base_path + 'dashboard/changeReqStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=tbl_sample_requirement'+'&idName=sample_requirement_id', 'json', function (data) {
                            $.when(getReqRequestList()).done(function(){
                                dispDetails(reqRequestJSON);       
                            });


                            
                        });
                        MakeAsynPostRequest(base_path + 'dashboard/changeReqStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=tbl_cad_requirement'+'&idName=cad_requirement_id', 'json', function (data) {
                            $.when(getReqRequestList()).done(function(){
                                dispDetails(reqRequestJSON);       
                            });
                        });
					} 
                }); 
              
            }
        }
        else {
            // alert('Select a option');
            swalWithBootstrapButtons.fire({
                title: 'Select a option!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
        }
        if(checkBoxLength == 0) {
            // alert('Select a record');
            swalWithBootstrapButtons.fire({
                title: 'Select a record!',
                type: 'error',
                icon: 'error',
                customClass: {'confirmButton': 'btn btn-info px-5'}
            });
        }
    });


    
         $('#btn-active').on('click', function () {
            satusval="1";
            activeBtn.classList.add('active');
            inactiveBtn.classList.remove('active');;
            const reqRequestJSON1 = reqRequestJSON.filter(item => item.flag === satusval);
            dispDetails(reqRequestJSON1);
	     });
         $('#btn-inactive').on('click', function () {
            satusval="2";
            inactiveBtn.classList.add('active');
            activeBtn.classList.remove('active');
            const reqRequestJSON1 = reqRequestJSON.filter(item => item.flag === satusval);
            dispDetails(reqRequestJSON1);
           
	     });


	 $('#refreshBtn').on('click', function () {
     sessionStorage.setItem('keepSearchOpen', 'true'); // remember user preference
    location.reload(); // reload the page
	});

	
      $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

    // Helper: parse table date format "dd/mm/yyyy hh:mm am/pm"
    function parseTableDate(str) {
        if (!str) return null;
        var parts = str.split(' ')[0].split('/'); // ["dd","mm","yyyy"]
        var timePart = str.split(' ')[1];         // "hh:mm"
        var ampm = str.split(' ')[2];             // "am" or "pm"

        var hours = 0, minutes = 0;
        if (timePart) {
            var timeParts = timePart.split(':');
            hours = parseInt(timeParts[0], 10);
            minutes = parseInt(timeParts[1], 10);
            if (ampm && ampm.toLowerCase() === 'pm' && hours < 12) hours += 12;
            if (ampm && ampm.toLowerCase() === 'am' && hours === 12) hours = 0;
        }

        return new Date(parts[2], parts[1] - 1, parts[0], hours, minutes);
    }

    // Helper: parse input date format "dd-mm-yyyy"
    function parseInputDate(str) {
        if (!str) return null;
        var parts = str.split('-'); // ["dd","mm","yyyy"]
        return new Date(parts[2], parts[1] - 1, parts[0]);
    }

    // --- Request Date filter ---
    // var requestFrom = parseInputDate($('#RequestFrom').val());
    // var requestTo   = parseInputDate($('#RequestTo').val());
    // var requestDate = parseTableDate(data[8]); // column index 6

    // if (requestFrom) requestFrom.setHours(0,0,0,0);
    // if (requestTo) requestTo.setHours(23,59,59,999);
    // if (requestDate) requestDate.setHours(0,0,0,0);

    // if (requestFrom && (!requestDate || requestDate < requestFrom)) return false;
    // if (requestTo && (!requestDate || requestDate > requestTo)) return false;

  
    var cutoffFrom  = parseInputDate($('#CutoffFrom').val());
    var cutoffTo    = parseInputDate($('#CutoffTo').val());
    var cutoffDate  = parseTableDate(data[7]); // column index 7

    if (cutoffFrom) cutoffFrom.setHours(0,0,0,0);
    if (cutoffTo) cutoffTo.setHours(23,59,59,999);
    if (cutoffDate) cutoffDate.setHours(0,0,0,0);

    if (cutoffFrom && (!cutoffDate || cutoffDate < cutoffFrom)) return false;
    if (cutoffTo && (!cutoffDate || cutoffDate > cutoffTo)) return false;

    return true;
});


  $('#searchButton').on('click', function() {


    // var fromDate = $('#RequestFrom').val().trim();
    // var toDate = $('#RequestTo').val().trim();
	
	
    var cutfromDate = $('#CutoffFrom').val().trim();
    var cuttoDate = $('#CutoffTo').val().trim();

    // Helper function to parse dd-mm-yyyy
    function parseDate(str) {
        var parts = str.split('-'); // ["dd", "mm", "yyyy"]
        return new Date(parts[2], parts[1] - 1, parts[0]); // year, month (0-based), day
    }

    // 1️⃣ Check RequestFrom / RequestTo
//     if(fromDate!==''){
//     if (fromDate === '' || toDate === '') {
//         swalWithBootstrapButtons.fire({
//             title: 'Select both From and To dates!',
//             icon: 'error',
//             customClass: { 'confirmButton': 'btn btn-info px-5' }
//         });
//         return false;
//     }
// }

//     if(fromDate!=='' && toDate!==''){
        
   
//     var from = parseDate(fromDate);
//     var to = parseDate(toDate);

//     if (from >= to) {
//         swalWithBootstrapButtons.fire({
//             title: 'Invalid date range. From date cannot be later than To date.',
//             icon: 'error',
//             customClass: { 'confirmButton': 'btn btn-info px-5' }
//         });
//         return false;
//     }
//  }
//     // 2️⃣ Check CutoffFrom / CutoffTo
   
if(cutfromDate!==''){
    if (cutfromDate === '' || cuttoDate === '') {
        swalWithBootstrapButtons.fire({
            title: 'Select both Cutoff From and To dates!',
            icon: 'error',
            customClass: { 'confirmButton': 'btn btn-info px-5' }
        });
        return false;
    }
}
  if(cutfromDate!=='' && cuttoDate!==''){
    var cutFrom = parseDate(cutfromDate);
    var cutTo = parseDate(cuttoDate); // ✅ make sure this matches your input ID exactly: CutoffTo

    if (cutFrom >= cutTo) {
        swalWithBootstrapButtons.fire({
            title: 'Invalid cutoff date range. From date cannot be later than To date.',
            icon: 'error',
            customClass: { 'confirmButton': 'btn btn-info px-5' }
        });
        return false;
    }
  } 
    var table = $('#QaQueueListTbl').DataTable();

    var wip_ref_no = $('#wip_ref_no').val().toLowerCase();
    var brandId    = $('#brandId').val().toLowerCase();
	var RequestFor   = $('#RequestFor').val().toLowerCase();
	var item_description = $('#item_description').val().toLowerCase();
	var queue_no   = $('#queue_no').val().toLowerCase();
	var QARequestby = $('#QARequestby').val().toLowerCase();
	 
   
   

    table
      .column(1).search(wip_ref_no)
      .column(2).search(brandId)	
	   .column(3).search(queue_no)
	   .column(4).search(RequestFor)
      .column(5).search(item_description)
	 
      .column(9).search(QARequestby)
	.draw(); // ✅ redraw triggers custom filter
});


});