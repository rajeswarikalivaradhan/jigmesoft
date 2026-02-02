var reqRequestJSON;
const activeBtn = document.getElementById('btn-active');
const inactiveBtn = document.getElementById('btn-inactive');  
$(document).ready(function() {
     if (sessionStorage.getItem('keepSearchOpen') === 'true') {
        $('.search_area').removeClass('hide'); // show search div
        $('.fa-search-plus').removeClass('fa-search-plus').addClass('fa-search');
        sessionStorage.removeItem('keepSearchOpen'); // clear flag
    }

    var enquiryJSON;
	
	$.when(getIORList()).done(function(){
		dispDetails(enquiryJSON);		
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
			url: base_path+'merchant/getEnquiryIORList',
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
		if ( $.fn.DataTable.isDataTable('#orderEnquiryListTbl') ) {
		  $('#orderEnquiryListTbl').DataTable().destroy();
		}

		$('#orderEnquiryListTbl tbody').empty();	
		$("#orderEnquiryListTbl").dataTable({
            "aaData": enquiryJSON,
            "aaSorting": [],
			"aoColumns": [			
				{
			      "mDataProp": function(data, type, full, meta) {
			        return '<input type="checkbox" class="allcbox" id="'+data.id+'">';
			      }
			    },		
				{ 
					"mDataProp": function ( data, type, full, meta) {
						return '<a class="boldfont bold" href="' + base_path +'merchant/addenquiry' + '/' + encodeURIComponent(btoa(data.id)) + '">' + data.orderenqrefno + '</a>';
					}
				},
				{ "mDataProp": "stylenamerefno" },	
				{ "mDataProp": "brandname" },
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        if(data.total_comp<1) 
                        return '<a class="boldfont bold" href="' + base_path +'components/componentCreation' + '/' + encodeURIComponent(btoa(data.id)) + '">' + data.isr_ior + '</a>';
                        else 
                        return '<a class="boldfont"href="' + base_path + 'preCosting/index' + '/' + encodeURIComponent(btoa(data.id)) + '">' + data.isr_ior + '</a>';
					}
				},	
				{ 
					"mDataProp": function ( data, type, full, meta) {
                         // commmented by me var d = new Date(data.formattedDateCreated);
                        var d=(data.reqdatetime!==null && data.reqdatetime !=='0000-00-00 00:00:00')?new Date(data.reqdatetime):'';
                        var time = (d!=='')? d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }) : '';
                        var dFormat =  (d!=='')? ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' + time:'-';
						return dFormat;
					}
				},													
				{ "mDataProp": "enquirytype" },							
				{ "mDataProp": "totalcomponents" },	
				{ "mDataProp": "totalcombo" },				
				// { 
				// 	"mDataProp": function ( data, type, full, meta) {
                //         var ARRCURRENCYLIST = ['Select', 'INR', 'SGD', 'HKD', 'MYR', 'USD', 'EUR', 'JPY', 'GBP', 'AUD', 'CAD', 'CHF', 'CNH', 'SEK', 'NZD'];
                //         return ARRCURRENCYLIST[data.currency];
				// 	}
				// },							
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var ORDERENQUIRYSTATUS = ['-', 'PENDING', 'APPROVED', 'DECLINED', 'PENDING-RR'];
                        if(data.orderstatus == '0')
                        return ORDERENQUIRYSTATUS[data.orderstatus];
                        else if(data.orderstatus == '1')
                        return '<span class="text-light knOrangeColor bg-dark"><strong>' + ORDERENQUIRYSTATUS[data.orderstatus] + '</strong></span>';
                        else if(data.orderstatus == '2')
                        return ORDERENQUIRYSTATUS[data.orderstatus];
                        else if(data.orderstatus == '3')
                        return '<span class="knRedColor"><strong>' + ORDERENQUIRYSTATUS[data.orderstatus] + '</strong></span>';
                        else if(data.orderstatus == '4')
                        return '<span class="p-0 mb-0 knOrangeColor text-dark"><strong>' + ORDERENQUIRYSTATUS[data.orderstatus] + '</strong></span>';
					}
				},								
				{ 
					"mDataProp": function ( data, type, full, meta) {
                        var d = (data.dateauthorized !==null && data.dateauthorized !=='0000-00-00 00:00:00')?new Date(data.dateauthorized):''; 
                        var time = (d!=='')?d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }):'';
                        var dFormat =(d!=='')?("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' + time:'-';
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
                        return 'Active';
					}
				},							
			]  						
		});
	}

    // $('#searchButton').click(function() {
    //      $('.allenquiry li.active').removeClass('active');
    //     var form = $('#searchForm')[0];
    //     var data = new FormData(form);
    //     var url = base_path + "merchant/searchEnquiryIORList";
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

    // $('#refreshBtn').on('click', function () {
    //     // location.reload();
    //     var element = document.getElementById('searchForm').reset();
    //         $('.js-example-basic-single').val(null).trigger('change');
    //         $('#searchButton').trigger('click');
	// });

    $('#refreshBtn').on('click', function () {
     sessionStorage.setItem('keepSearchOpen', 'true'); // remember user preference
    location.reload(); // reload the page
	});

    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    
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
						MakeAsynPostRequest(base_path + 'dashboard/changeAllListActiveStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=kn_order_enquiry', 'json', function (data) {
                        $.when(getIORList()).done(function(){
                            dispDetails(enquiryJSON);		
                        });
                    });
					}
                }); 
                // if (confirm('Do you want to ' + StatusText + ' this records?')) {
                //     MakeAsynPostRequest(base_path + 'dashboard/changeAllListActiveStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                //         '&tblname=kn_order_enquiry', 'json', function (data) {
                //         $.when(getIORList()).done(function(){
                //             dispDetails(enquiryJSON);		
                //         });
                //     });
                // }
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
    
   
    $('#refreshBtn').on('click', function () {
        location.reload();
	     });

         $('#btn-active').on('click', function () {
            satusval="1";
            activeBtn.classList.add('active');
            inactiveBtn.classList.remove('active');;
            const enquiryJSON1 = enquiryJSON.filter(item => item.status === satusval);
            dispDetails(enquiryJSON1);
	     });
         $('#btn-inactive').on('click', function () {
            satusval="2";
            inactiveBtn.classList.add('active');
            activeBtn.classList.remove('active');
            const enquiryJSON1 = enquiryJSON.filter(item => item.status === satusval);
            dispDetails(enquiryJSON1);
           
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
    var requestFrom = parseInputDate($('#RequestFrom').val());
    var requestTo   = parseInputDate($('#RequestTo').val());
    var requestDate = parseTableDate(data[6]); // column index 6

    if (requestFrom) requestFrom.setHours(0,0,0,0);
    if (requestTo) requestTo.setHours(23,59,59,999);
    if (requestDate) requestDate.setHours(0,0,0,0);

    if (requestFrom && (!requestDate || requestDate < requestFrom)) return false;
    if (requestTo && (!requestDate || requestDate > requestTo)) return false;

    // --- Cutoff Date filter ---
    var cutoffFrom  = parseInputDate($('#CutoffFrom').val());
    var cutoffTo    = parseInputDate($('#CutoffTo').val());
    var cutoffDate  = parseTableDate(data[5]); // column index 7

    if (cutoffFrom) cutoffFrom.setHours(0,0,0,0);
    if (cutoffTo) cutoffTo.setHours(23,59,59,999);
    if (cutoffDate) cutoffDate.setHours(0,0,0,0);

    if (cutoffFrom && (!cutoffDate || cutoffDate < cutoffFrom)) return false;
    if (cutoffTo && (!cutoffDate || cutoffDate > cutoffTo)) return false;

    return true;
});

  $('#searchButton').on('click', function() {


    var fromDate = $('#RequestFrom').val().trim();
    var toDate = $('#RequestTo').val().trim();
    // var cutfromDate = $('#CutoffFrom').val().trim();
    // var cuttoDate = $('#CutoffTo').val().trim();

    // Helper function to parse dd-mm-yyyy
    function parseDate(str) {
        var parts = str.split('-'); // ["dd", "mm", "yyyy"]
        return new Date(parts[2], parts[1] - 1, parts[0]); // year, month (0-based), day
    }

    // 1️⃣ Check RequestFrom / RequestTo
    if(fromDate!==''){
    if (fromDate === '' || toDate === '') {
        swalWithBootstrapButtons.fire({
            title: 'Select both From and To dates!',
            icon: 'error',
            customClass: { 'confirmButton': 'btn btn-info px-5' }
        });
        return false;
    }
}

    if(fromDate!=='' && toDate!==''){
        
   
    var from = parseDate(fromDate);
    var to = parseDate(toDate);

    if (from >= to) {
        swalWithBootstrapButtons.fire({
            title: 'Invalid date range. From date cannot be later than To date.',
            icon: 'error',
            customClass: { 'confirmButton': 'btn btn-info px-5' }
        });
        return false;
    }
 }
    // 2️⃣ Check CutoffFrom / CutoffTo
//     if(cutfromDate!==''){
//     if (cutfromDate === '' || cuttoDate === '') {
//         swalWithBootstrapButtons.fire({
//             title: 'Select both Cutoff From and To dates!',
//             icon: 'error',
//             customClass: { 'confirmButton': 'btn btn-info px-5' }
//         });
//         return false;
//     }
// }
//   if(cutfromDate!=='' && cuttoDate!==''){
//     var cutFrom = parseDate(cutfromDate);
//     var cutTo = parseDate(cuttoDate); // ✅ make sure this matches your input ID exactly: CutoffTo

//     if (cutFrom >= cutTo) {
//         swalWithBootstrapButtons.fire({
//             title: 'Invalid cutoff date range. From date cannot be later than To date.',
//             icon: 'error',
//             customClass: { 'confirmButton': 'btn btn-info px-5' }
//         });
//         return false;
//     }
//   }
    var table = $('#orderEnquiryListTbl').DataTable();

    var order_enq_ref_no = $('#order_enq_ref_no').val().toLowerCase();
    var brandId    = $('#brandId').val().toLowerCase();
	var enquirytype = $('#enquirytype').val().toLowerCase();
    var totalcomponents   = $('#totalcomponents').val().toLowerCase();
    var totalcombo = $('#totalcombo').val().toLowerCase();
   
   

    table
      .column(1).search(order_enq_ref_no)
      .column(3).search(brandId)
      .column(6).search(enquirytype)
      .column(7).search(totalcomponents)
       .column(8).search(totalcombo)
      
     
      .draw(); // ✅ redraw triggers custom filter
});


    


});

 