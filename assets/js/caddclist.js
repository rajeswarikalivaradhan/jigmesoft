
var reqRequestJSON;
const activeBtn = document.getElementById('btn-active');
const inactiveBtn = document.getElementById('btn-inactive');  
    
    $(document).ready(function() {
       
          
      $('.date').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
});

 if (sessionStorage.getItem('keepSearchOpen') === 'true') {
        $('.search_area').removeClass('hide'); // show search div
        $('.fa-search-plus').removeClass('fa-search-plus').addClass('fa-search');
        sessionStorage.removeItem('keepSearchOpen'); // clear flag
    }



       // var reqRequestJSON;
       //toggleStatus('active');
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
                url: base_path+'company/mcaduser/getcadDClist',
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

            if ( $.fn.DataTable.isDataTable('#mCadQueueList') ) {
                $('#mCadQueueList').DataTable().destroy();
            }
            var i = 1;
            $('#mCadQueueList tbody').empty();	
            $("#mCadQueueList").dataTable({
                "aaData": reqRequestJSON,
                "aaSorting": [],
                "aoColumns": [	
                    {
                        "mDataProp": function(data, type, full, meta) {
                            return '<input type="checkbox" class="allcbox" id="'+data.request_id+'">';
                        }
                    },
                    { "mDataProp": "isriorcode" },	
                    { "mDataProp": "brandname" },	
                    // { "mDataProp": "ref_queue_no" },	
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            return 'CAD';
                        }
                    },
                    { "mDataProp": "cad_ref_no" },	
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            return data.req_date;
                        }
                    },
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            return data.cutoff_date;
                        }
                    },
                    {
                        "mDataProp": function ( data, type, full, meta) {
                            return '<a class="bold" href="' + base_path +'request/Cadrequest/cadDCDetails/'+ encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '/miId/' + encodeURIComponent(btoa(data.mi_id)) + '/dc/' + encodeURIComponent(btoa(data.dc_ref_queue_no)) + '">' + data.dc_ref_queue_no + '</a>';
                        }
                    },
                    { "mDataProp": "dc_dt" },
                    { "mDataProp": "cad_depts" },
                    // { "mDataProp": "mi_issued_by" },
                    // { "mDataProp": "auth_type" },
                    // { "mDataProp": "auth_name" },
                    { 
                        "mDataProp": function ( data, type, full, meta) {
                            var Status = ['PENDING', 'RECEIVED', 'DISCREPANCY', 'MISSING'];
                            if(data.item_received_status == '0')
                            return '<span class="text-light knOrangeColor bg-dark"><strong>'+Status[data.item_received_status]+'</strong></span>';
                            if(data.item_received_status == '1')
                            return '<span class="text-light knGreenColor bg-dark"><strong>'+Status[data.item_received_status]+'</strong></span>';
                            else
                            return '<span class="text-light knRedColor bg-dark"><strong>'+Status[data.item_received_status]+'</strong></span>';
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
				//alert(idJson);
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
                            }
				).then(function(result) {
					if (result.value) {
                       //alert(idJson);
						MakeAsynPostRequest(base_path + 'dashboard/changeReqStatus', 'id=' + idJson + '&cs=' + dropdownOpt +
                        '&tblname=tbl_mi_cad_details'+'&idName=request_id ', 'json', function (data) {
                            $.when(getReqRequestList()).done(function(){
                                dispDetails(reqRequestJSON)
								 
								   
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

    $('#refreshBtn').on('click', function () {
        location.reload();
	     });

        //  $('#btn-active').on('click', function () {
        //     satusval="1";
        //     activeBtn.classList.add('active');
        //     inactiveBtn.classList.remove('active');;
        //     const reqRequestJSON1 = reqRequestJSON.filter(item => item.flag === satusval);
        //     dispDetails(reqRequestJSON1);
	    //  });
        //  $('#btn-inactive').on('click', function () {
        //     satusval="2";
        //     inactiveBtn.classList.add('active');
        //     activeBtn.classList.remove('active');
        //     const reqRequestJSON1 = reqRequestJSON.filter(item => item.flag === satusval);
        //     dispDetails(reqRequestJSON1);
           
	    //  });

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
    var requestFrom = parseInputDate($('#RequestFrom').val());
    var requestTo   = parseInputDate($('#RequestTo').val());
    var requestDate = parseTableDate(data[5]); // column index 6

    if (requestFrom) requestFrom.setHours(0,0,0,0);
    if (requestTo) requestTo.setHours(23,59,59,999);
    if (requestDate) requestDate.setHours(0,0,0,0);

    if (requestFrom && (!requestDate || requestDate < requestFrom)) return false;
    if (requestTo && (!requestDate || requestDate > requestTo)) return false;

    // --- Cutoff Date filter ---
    var cutoffFrom  = parseInputDate($('#CutoffFrom').val());
    var cutoffTo    = parseInputDate($('#CutoffTo').val());
    var cutoffDate  = parseTableDate(data[6]); // column index 7

    if (cutoffFrom) cutoffFrom.setHours(0,0,0,0);
    if (cutoffTo) cutoffTo.setHours(23,59,59,999);
    if (cutoffDate) cutoffDate.setHours(0,0,0,0);

    if (cutoffFrom && (!cutoffDate || cutoffDate < cutoffFrom)) return false;
    if (cutoffTo && (!cutoffDate || cutoffDate > cutoffTo)) return false;

    return true;
});

var GlbSortOrder = '';
var GlbColumnId = '';
$('#btn-active').on('click', function () {
     $('#btn-active').addClass('btn-selected');
    $('#btn-inactive').removeClass('btn-selected');
  
    var Status1 = 'active';

    var table = $('#mCadQueueList').DataTable();

    // Column 5: prefix search using regex
    if (Status1) { // only if a value is selected
        table.column(12).search('^' + Status1, true, false); // true = regex, false = smart
    } else {
        table.column(12).search(''); // reset
    }

    table.draw();
   
   
    
 });
 $('#btn-inactive').on('click', function () {
     $('#btn-inactive').addClass('btn-selected');
    $('#btn-active').removeClass('btn-selected');

    var Status1 = 'Inactive';

    var table = $('#mCadQueueList').DataTable();

    // Column 5: prefix search using regex
    if (Status1) { // only if a value is selected
        table.column(12).search('^' + Status1, true, false); // true = regex, false = smart
    } else {
        table.column(12).search(''); // reset
    }

    table.draw();
   
 });

  $('#searchButton').on('click', function() {


    var fromDate = $('#RequestFrom').val().trim();
    var toDate = $('#RequestTo').val().trim();
    var cutfromDate = $('#CutoffFrom').val().trim();
    var cuttoDate = $('#CutoffTo').val().trim();

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
    var table = $('#mCadQueueList').DataTable();

    var wip_ref_no = $('#wip_ref_no').val().toLowerCase();
    var brandId    = $('#brandId').val().toLowerCase();

    var mi_ref_no   = $('#mi_ref_no').val().toLowerCase();
    	var dc_no = $('#dc_no').val().toLowerCase();
   

    table
      .column(1).search(wip_ref_no)
      .column(2).search(brandId)
      .column(4).search(mi_ref_no)
      .column(7).search(dc_no)
     
      .draw(); // ✅ redraw triggers custom filter
});



    });