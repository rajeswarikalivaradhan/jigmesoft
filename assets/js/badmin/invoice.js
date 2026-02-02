let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
const activeBtn = document.getElementById('btn-active');
const inactiveBtn = document.getElementById('btn-inactive'); 



    if (sessionStorage.getItem('keepSearchOpen') === 'true') {
        $('.search_area').removeClass('hide'); // show search div
        $('.fa-search-plus').removeClass('fa-search-plus').addClass('fa-search');
        sessionStorage.removeItem('keepSearchOpen'); // clear flag
    }
function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = "rfrom=1";
    MakeAsynPostRequest(base_path +  'invoice/manage', GlbSearchParam, 'json', fnListRes);
}
function fnListRes(data) {
    console.log(data.re, 'data');
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent = '';
                if (data.cn > 0) {
                    let selestatus1 = "all"; // default
                    if ($('#btn-active').hasClass('active')) {
                           selestatus1 = "Active";
                         
                       } else if ($('#btn-inactive').hasClass('active')) {
                           selestatus1 = "Inactive";
                         
                       }
                    ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';
                    if (data.ct > 0) {
                        $.each(data.re, function (index, value) {
                            if (selestatus1 === "all" || value.s === selestatus1) {
                            PageContent = PageContent + '<tr>' +
                                '<td><input style="margin:0px!important" type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td style="width:13%">' + value.cmpny + '</td>' +
                                '<td style="width:10%">' + value.pckdet + '</td>' +
                                '<td style="width:10%">' + value.purchtype + '</td>' +
                                '<td style="width:9%">' + value.reqraisedby + '</td>' +
                                '<td style="width:10%">' +value.reqdatetime + '</td>' +
                                '<td style="width:10%"><a href="' + base_path  + 'invoice/proformainv/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.invoice_refno + '</a></td>' +
                                '<td style="width:11%">' + value.invoice_datetime + '</td>' +
                                '<td style="width:10%;font-weight:bold;" class="knOrangeColor">' + value.paymentstatus + '</td>' +
                                '<td style="width:11%">' + value.recent_updated_datetime + '</td>' +
                                '<td style="width:3%">' + value.s + '</td>' +
                                '<td style="width:3%"><a href="' + base_path  + 'invoice/view/' + encodeURIComponent(base64_encode(value.id)) + '">View</a></td>';
                            PageContent = PageContent + '</tr>';
                            }
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent = PageContent + '<tr><td colspan="12" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                if (data.pa != undefined) {
                    console.log(base64_decode(data.pa))
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                
                // $('tbody').empty();
                // $('#brandTblList').append(PageContent);
                //tableId
                $('#tableId tbody').empty();
                $('#tableId').append(PageContent).DataTable();
            }
        }
    }
}
var GlbSearchParam = '';
var GlbSortOrder = '';
var GlbColumnId = '';
// function fnSearch() {
   
//     var frmSrchCmpny = $("#frmSrchCmpny").val();
//     var frmSrchPckgDet = $("#frmSrchPckgDet").val();
//     var frmSrchPurchtype = $("#frmSrchPurchtype").val();
//     var frmSrchReqraisedby = $("#frmSrchReqraisedby").val();
//     var frmSrchfromdate = $("#fromdate").val();
//     var frmSrchtodate = $("#todate").val();
    
//     GlbSearchParam = "rfrom=1&cmpny=" + frmSrchCmpny + "&fromdate=" + frmSrchfromdate + "&pckdetid=" + frmSrchPckgDet + "&purchtype=" + frmSrchPurchtype + "&reqraisedby=" + frmSrchReqraisedby + "&todate=" + frmSrchtodate;
//     $("#DivTotalCntResult").html('');
//     MakeAsynPostRequest(base_path +  'invoice/manage', GlbSearchParam, 'json', fnListRes);
// }

 $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

    // Helper: parse table date format "dd/mm/yyyy hh:mm am/pm"
    function parseTableDate(str) {
        if (!str) return null;
        var parts = str.split(' ')[0].split('-'); // ["dd","mm","yyyy"]
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
    var cutoffDate  = parseTableDate(data[7]); // column index 7

    if (cutoffFrom) cutoffFrom.setHours(0,0,0,0);
    if (cutoffTo) cutoffTo.setHours(23,59,59,999);
    if (cutoffDate) cutoffDate.setHours(0,0,0,0);

    if (cutoffFrom && (!cutoffDate || cutoffDate < cutoffFrom)) return false;
    if (cutoffTo && (!cutoffDate || cutoffDate > cutoffTo)) return false;

    return true;
});

function fnSearch() {
   
      
    var frmSrchCmpny = $("#frmSrchCmpny").val();
    var frmSrchPckgDet = $("#frmSrchPckgDet").val();
    var frmSrchPurchtype = $("#frmSrchPurchtype").val();
    var frmSrchReqraisedby = $("#frmSrchReqraisedby").val();
    var frmSrchfromdate = $("#fromdate").val();
    var frmSrchtodate = $("#todate").val();

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
    
    //GlbSearchParam = "rfrom=1&cmpny=" + frmSrchCmpny + "&cty=" + frmSrchCity + "&pckdetid=" + frmSrchPckgDet + "&purchtype=" + frmSrchPurchtype + "&reqraisedby=" + frmSrchReqraisedby + "&reqstatus=" + frmSrchReqStatus;
   // $("#DivTotalCntResult").html('');
    //MakeAsynPostRequest(base_path + GlbBAdminFdr + 'mreqrcved/manage', GlbSearchParam, 'json', fnListRes);
 var table1 = $('#tableId').DataTable();

    // Columns 1–4: normal substring search (no regex)
    table1.column(1).search(frmSrchCmpny, false, true); // false = regex, true = smart
    table1.column(2).search(frmSrchPckgDet, false, true);
    table1.column(3).search(frmSrchPurchtype, false, true);
    table1.column(6).search(frmSrchReqraisedby, false, true);
    

    // // Column 5: prefix search using regex
    // if (Status1) { // only if a value is selected
    //     table.column(5).search('^' + Status1, true, false); // true = regex, false = smart
    // } else {
    //     table.column(5).search(''); // reset
    // }

    table1.draw();
}
         $('#refreshBtn').on('click', function () {
     sessionStorage.setItem('keepSearchOpen', 'true'); // remember user preference
    location.reload(); // reload the page
	});
function fnChangeStatusRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearch();
            }
        }
    }
}
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
             swalWithBootstrapButtons.fire({
                title: 'Select a record!',
                type: 'error',
                icon: 'error',
                width:460,
                customClass: {'confirmButton': 'btn btn-info'}
            });
        }
        if (checkBoxLength >= 1) {
            var idJson = JSON.stringify(cboxObj);
            
            swalWithBootstrapButtons.fire(
                {
                   
                    title: 'Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record ?',
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
				    GlbSearchParam = "type=" + dropdownOpt + "&cid=" + idJson;
                    MakeAsynPostRequest(base_path +  'invoice/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
				}
                if (result.value) {
                    location.reload();
				}
                });
        }
    }
    else {
        swalWithBootstrapButtons.fire({
                title: 'Select a option!',
                type: 'error',
                icon: 'error',
                width:460,
                customClass: {'confirmButton': 'btn btn-info'}
        });
    }
});
 
function fnPagination(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListRes);
}
function fnSave(){
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var payment_from = $("#payment_from").val();
    var transaction_no = $("#transaction_no").val();
    var transaction_date = $("#transaction_date").val();
    var payment_mode = $("#payment_mode").val();
    var transaction_value = $("#transaction_value").val();
    var payment_status = $("#payment_status").val();
    var proforma_type = $("#proforma_type").val();
    var invopurchasetype=$("#invopurchasetype").val();
    var subscriber_id = $("#subscriber_id").val();

    if (jsTrim(payment_from) == "") {
        $('#Errpayment_from').text("Enter Payment Received From");
        $('#payment_from').focus();
        $('#payment_from').css("border", "1px solid #B94A48");
        return false;
    }else{
         $('#Errpayment_from').text(""); 
    }
    if (jsTrim(transaction_no) == "") {
        $('#Errtransaction_no').text("Enter Transaction ID / Cheque No.");
        $('#transaction_no').focus();
        $('#transaction_no').css("border", "1px solid #B94A48");
        return false;
    }else{
         $('#Errtransaction_no').text("");
    }
    if (jsTrim(transaction_date) == "") {
        $('#Errtransaction_date').text("Enter Transaction / Cheque Date");
        $('#transaction_date').focus();
        $('#transaction_date').css("border", "1px solid #B94A48");
        return false;
    }else{
         $('#Errtransaction_date').text("");
    }
    if ((payment_mode == "") || (payment_mode==null)) {
        $('#Errpayment_mode').text("Enter Mode of Payment");
        $('#payment_mode').focus();
        $('#payment_mode').css("border", "1px solid #B94A48");
        return false;
    }else{
         $('#Errpayment_mode').text("");
    } 
    if (jsTrim(transaction_value) == "") {
        $('#Errtransaction_value').text("Enter Transaction Value (Rs)");
        $('#transaction_value').focus();
        $('#transaction_value').css("border", "1px solid #B94A48");
        return false;
    }else{
         $('#Errtransaction_value').text("");
    }
    swalWithBootstrapButtons.fire(
            {
               // title: 'Are you sure want to save the details ?',
               // text: "If you save You won't be able to revert this!",
                title: 'Do you want to save the details ?',
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
				     MakeAsynPostRequest(base_path  + "invoice/updateproformainvoiceInfo",
                    "save_status=1&payment_from=" + payment_from + "&transaction_no=" + transaction_no + "&transaction_date=" + transaction_date + "&payment_mode=" + payment_mode + "&transaction_value=" + transaction_value + "&invopurchasetype=" + invopurchasetype + "&proforma_type=" + proforma_type + "&payment_status=" + payment_status 
                     + "&subscriber_id=" + subscriber_id + "&id=" + GlbId, "json",function (data) {
                        if (data != '') {
                        if (data.errcode == '404') {
                            fnCallSessionExpire();
                            return false;
                        } else if (data.errcode == -1) {
                            //$('#AnyErrElse').text(data.msg);
                            swalWithBootstrapButtons.fire({
                                title: data.msg,type: 'warning',
                                icon: 'warning',
                                customClass: {'confirmButton': 'btn btn-info'}
                            });
                            return false;
                        } else if (data.errcode == 1) {
                           // console.log(data.msg);
                           // GlbId = data.id;
                            if(data.msg == 'updated'){
                                swalWithBootstrapButtons.fire({
                                            title: 'Saved!',type: 'success',
                                            icon: 'success',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                }).then((result) => {
                                                    let redirectpath = base_path + 'invoice/view/' + encodeURIComponent(base64_encode(GlbId));
                                                    window.location.href = redirectpath;
                                });
                            }else{
                                location.reload();
                                // $("#savereqbtn").prop("disabled", false);
                                // $("#enqsvbtn").hide();
                                // $("#custom_form input").prop("disabled", true);
                                // $("#custom_form select").prop("disabled", true);
                                // $("textarea").prop("disabled", true);
                            }
                        }
                    }
                });
				}
	        });	
}
function fnSaveSubscription(){
    var subscriber_id = $("#subscriber_id").val();
    var subscriber_refno = $("#subscriber_refno").val();
    var subscription_period= $("#subscription_period").val();
    var invoiceno = $("#invoiceno").val();
    var proforma_type = $("#proforma_type").val();
    var proforma_id=$("#proforma_id").val();
    var invopurchasetype=$("#invopurchasetype").val();
    var pckg_saved_status = parseInt($("#pckg_saved_status").val()); 
    var dept_saved_status = parseInt($("#dept_saved_status").val()); 
    var user_saved_status = parseInt($("#user_saved_status").val()); 
    if(invopurchasetype==2 || invopurchasetype==3){
    // Validation before proceeding
    if (pckg_saved_status === 1 && dept_saved_status === 1 && user_saved_status === 1) {
        proceedWithSubscription();
    } else if (pckg_saved_status === 1 && dept_saved_status === 2 && user_saved_status === 1) {
        showValidationAlert("Please fill department-wise user role allowed details.");
    } else if (pckg_saved_status === 2 && dept_saved_status === 1 && user_saved_status === 1) {
        showValidationAlert("Please fill package-wise user count details.");
    } else if (pckg_saved_status === 1 && dept_saved_status === 1 && user_saved_status === 2) {
        showValidationAlert("Please fill user details.");
    } else if (pckg_saved_status === 2 && dept_saved_status === 2 && user_saved_status === 1) {
        showValidationAlert("Please fill both package-wise user count details and department-wise user role allowed details.");
    } else if (pckg_saved_status === 2 && dept_saved_status === 1 && user_saved_status === 2) {
        showValidationAlert("Please fill both package-wise user count details and user details.");
    } else if (pckg_saved_status === 1 && dept_saved_status === 2 && user_saved_status === 2) {
        showValidationAlert("Please fill both department-wise user role allowed details and user details.");
    } else {
        showValidationAlert("Please fill package-wise user count details,department-wise user role allowed details, and user details.");
    }
    }else{
        proceedWithSubscription();
    }

}
// Function to proceed if validation passes
function proceedWithSubscription() {
    var subscriber_id = $("#subscriber_id").val();
    var subscriber_refno = $("#subscriber_refno").val();
    var subscription_period = $("#subscription_period").val();
    var invoiceno = $("#invoiceno").val();
    var proforma_type = $("#proforma_type").val();
    var proforma_id = $("#proforma_id").val();
    var invopurchasetype = $("#invopurchasetype").val();

    if (proforma_type == 'SPI' || invopurchasetype == 2 || invopurchasetype == 3) {
        var proformadet = getproformadetail(proforma_id, function(errors) {
            if (errors.length === 0) {
                confirmAndSave();
            } else {
                showValidationAlert(errors.join(" "));
            }
        });
    } else {
        confirmAndSave();
    }
}

// Function to confirm before saving
function confirmAndSave() {
    var subscriber_id = $("#subscriber_id").val();
    var subscriber_refno = $("#subscriber_refno").val();
    var subscription_period = $("#subscription_period").val();
    var invoiceno = $("#invoiceno").val();
    var proforma_type = $("#proforma_type").val();
    var proforma_id = $("#proforma_id").val();
    var invopurchasetype = $("#invopurchasetype").val();

    swalWithBootstrapButtons.fire({
        title: (proforma_type == 'NPI') ? 'Do you want to generate a new subscription?' : 'Do you want to generate a new invoice?',
        type: 'warning',
        showCancelButton: true,
        scrollbarPadding: false,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true,
        width: 468,
        customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
    }).then(function(result) {
        if (result.value) {
            MakeAsynPostRequest(base_path + "invoice/saveSubscriptionInfo",
                "rfrom=1&subscriber_id=" + subscriber_id + "&proforma_id=" + proforma_id + "&subscription_period=" + subscription_period + "&invopurchasetype=" + invopurchasetype + "&proforma_type=" + proforma_type + "&subscriber_refno=" + subscriber_refno + "&invoiceno=" + invoiceno + "&id=" + GlbId, 
                "json", function(data) {
                    if (data != '') {
                        if (data.errcode == '404') {
                            fnCallSessionExpire();
                        } else if (data.errcode == -1) {
                            showValidationAlert(data.msg);
                        } else if (data.errcode == 1) {
                            handleSaveSuccess(data.msg);
                        }
                    }
                });
        }
    });
}

// Function to display validation alerts
function showValidationAlert(message) {
    swalWithBootstrapButtons.fire({
        title: 'Warning',
        html: message,
        type: 'warning',
        icon: 'warning',
        width: 460,
        customClass: {
            'confirmButton': 'btn btn-info',
            'title': 'swal2-titles',
            'html': 'swal2-texts'
        }
    });
}

// Function to handle successful save
function handleSaveSuccess(msg) {
    swalWithBootstrapButtons.fire({
        title: 'Saved!',
        type: 'success',
        icon: 'success',
        customClass: { 'confirmButton': 'btn btn-info' }
    }).then((result) => {
        if (msg === 'updated') {
            window.location.href = base_path + 'invoice/manage';
        } else {
            location.reload();
        }
    });
}
function getproformadetail(proforma_id, callback) {
    var additionaluser = $("#additional_users").val();
    var additionaldata = $("#data_storage_limit").val();
    var additionalfile = $("#file_storage_limit").val();
    var purchasetype = $("#purchasetype").val();
    var packagedet = $("#invopackagedet").val();

    var errors = []; // Array to collect errors

    MakeAsynPostRequest(base_path  + "invoice/getproformadet",
                        "proforma_id=" + proforma_id,
                        "json",
                        function (data) {
                            var userMismatch = false;
                            var dataMismatch = false;
                            var fileMismatch = false;
                            if(purchasetype==2 ||purchasetype==3){
 
                            // Check if descp part that is package detail exists in the data array
                            // var packagedetMismatch = data.some(item => item.descpart !== '' && packagedet !== item.descpart);

                            // if (packagedetMismatch) {
                            //     errors.push("Edit <b class='swal2-texts'>Package Details</b>.");
                            // }
                            var allDescParts = data.map(item => item.descpart).filter(desc => desc !== '');
                            var packagedetMismatch = allDescParts.length > 0 && !allDescParts.includes(packagedet);
                            
                            if (packagedetMismatch) {
                                errors.push("Edit <b class='swal2-texts'>Package Details</b>.");
                            }
                        }
                            
                            
                            $.each(data, function(index, value) {
                                if (value.description_id == 5 && additionaluser != value.qty) {
                                    userMismatch = true;
                                }
                                if (value.description_id == 7 && additionaldata != value.detpart) {
                                    dataMismatch = true;
                                }
                                if (value.description_id == 8 && additionalfile != value.detpart) {
                                    fileMismatch = true;
                                }
                            });

                            // Check number of mismatches
                            var mismatchCount = (userMismatch ? 1 : 0) + (dataMismatch ? 1 : 0) + (fileMismatch ? 1 : 0);

                            // Generate error message based on the number of mismatches
                            if (mismatchCount === 1) {
                                if (userMismatch) {
                                    errors.push("Edit <b class='swal2-texts'>No. of Additional Users</b>.");
                                } else if (dataMismatch) {
                                    errors.push("Edit <b class='swal2-texts'>Add. Data Storage Limit</b>.");
                                } else if (fileMismatch) {
                                    errors.push("Edit <b class='swal2-texts'>Add. File Storage Limit</b>.");
                                }
                            } else if (mismatchCount === 2) {
                                if (userMismatch && dataMismatch) {
                                    errors.push("Edit <b class='swal2-texts'>No. of Additional Users</b> / <b class='swal2-texts'>Add. Data Storage Limit</b>.");
                                } else if (userMismatch && fileMismatch) {
                                    errors.push("Edit <b class='swal2-texts'>No. of Additional Users</b> / <b class='swal2-texts'>Add. File Storage Limit</b>.");
                                } else if (dataMismatch && fileMismatch) {
                                    errors.push("Edit <b class='swal2-texts'>Add. Data Storage Limit</b> / <b class='swal2-texts'>Add. File Storage Limit</b>.");
                                }
                            } else if (mismatchCount === 3) {
                                errors.push("Edit <b class='swal2-texts'>No. of Additional Users</b> / <b class='swal2-texts'>Add. Data Storage Limit</b> / <b class='swal2-texts'>Add. File Storage Limit</b>.");
                            }

                            // Pass errors to the callback function
                            if (typeof callback === 'function') {
                                callback(errors);
                            }
                        });
}

$('#btn-active').on('click', function () {
    //satusval="Active";
    activeBtn.classList.add('active');
    inactiveBtn.classList.remove('active');
    selestatus = "Inactive"; 
    
    fnList();
   
   
    
 });
 $('#btn-inactive').on('click', function () {
   
    inactiveBtn.classList.add('active');
    activeBtn.classList.remove('active');
    selestatus = "Inactive";
    fnList();
   
 });

