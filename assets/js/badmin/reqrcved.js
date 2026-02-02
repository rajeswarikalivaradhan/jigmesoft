
let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
if (sessionStorage.getItem('keepSearchOpen') === 'true') {
        $('.search_area').removeClass('hide'); // show search div
        $('.fa-search-plus').removeClass('fa-search-plus').addClass('fa-search');
        sessionStorage.removeItem('keepSearchOpen'); // clear flag
    }
const activeBtn = document.getElementById('btn-active');
const inactiveBtn = document.getElementById('btn-inactive');  
function fnSave() {
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var Company = $("#companyname").val();
    var BusinessType = $("#businesstype").val();
    var Contactperson = $("#contactperson").val();
    var Designation = $("#designation").val();
    var EmailId = $("#email_id").val();
    var MobileNo = $("#mobile_no").val();
    var Gstno = $("#gst_no").val();
    var Iecode = $("#iecode_no").val();
    var Address = $("#address").val();
    var City = $("#city").val();
    var State = $("#state").val();
    var Country = $("#country").val();
    var Pincode = $("#pincode").val();
    var Subcription_Category = $("#subscription_category").val();
    var Pckgdet_id = $("#package_id").val();
    var Purchase_type = $("#purchasetype").val();
    var Additional_users = $("#additional_users").val();
    var Data_storage_limit = $("#data_storage_limit").val();
    var File_storage_limit = $("#file_storage_limit").val();
    var Request_status = ($('#request_status').val()===null)?0:$('#request_status').val();
    var Remarks = $("#remarks").val();
    var Mrkt_dept_userid = $("#mrkt_dept_userid").val();
    
    if (jsTrim(Company) == "") {
        $('#Errcompanyname').text("Enter Company Name");
        $('#companyname').focus();
        $('#companyname').css("border", "1px solid #B94A48");
        return false;
    }
    if (BusinessType == "" || BusinessType===null) {
        $('#Errbusinesstype').text("Select Business Type");
        $('#businesstype').focus();
        $('#businesstype').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Contactperson) == "") {
        $('#Errcontactperson').text("Enter Contact Person");
        $('#contactperson').focus();
        $('#contactperson').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Designation) == "") {
        $('#Errdesignation').text("Enter Designation");
        $('#designation').focus();
        $('#designation').css("border", "1px solid #B94A48");
        return false;
    }
    if (EmailId!='' && IsEmailid(EmailId) == false) {
        $('#Erremail_id').text("Invalid E-mail Id,Please Enter Valid One");
        $('#email_id').focus();
        $('#email_id').css("border", "1px solid #B94A48");
        return false;
    }
    if (EmailId == "") {
        $('#Erremail_id').text("Enter Email ID");
        $('#email_id').focus();
        $('#email_id').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(MobileNo) == "") {
        $('#Errmobile_no').text("Enter Mobile No.");
        $('#mobile_no').focus();
        $('#mobile_no').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Gstno) == "") {
        $('#Errgst_no').text("Enter GST No");
        $('#gst_no').focus();
        $('#gst_no').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Iecode) == "") {
        $('#Erriecode_no').text("Enter IE Code No");
        $('#iecode_no').focus();
        $('#iecode_no').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Address) == "") {
        $('#Erraddress').text("Enter Address");
        $('#address').focus();
        $('#address').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(City) == "") {
        $('#Errcity').text("Enter City");
        $('#city').focus();
        $('#city').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(State) == "") {
        $('#Errstate').text("Enter State");
        $('#state').focus();
        $('#state').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Country) == "") {
        $('#Errcountry').text("Enter Country");
        $('#country').focus();
        $('#country').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Pincode) == "") {
        $('#Errpincode').text("Enter Pin Code");
        $('#pincode').focus();
        $('#pincode').css("border", "1px solid #B94A48");
        return false;
    }
    if (Subcription_Category== "" || Subcription_Category==null) {
        $('#Errsubscription_category').text("Select Subcription Category");
        $('#subscription_category').focus();
        $('#subscription_category').css("border", "1px solid #B94A48");
        return false;
    }
    if (Pckgdet_id == "" || Pckgdet_id==null) {
        $('#Errpackage_id').text("Select Package Detail");
        $('#package_id').focus();
        $('#package_id').css("border", "1px solid #B94A48");
        return false;
    }
    if (Purchase_type == "" || Purchase_type==null) {
        $('#Errpurchasetype').text("Select Purchase Type");
        $('#purchasetype').focus();
        $('#purchasetype').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Additional_users) == "") {
        $('#Erradditional_users').text("Enter No. of Additional Users (Chargeable)");
        $('#additional_users').focus();
        $('#additional_users').css("border", "1px solid #B94A48");
        return false;
    }
    if (Data_storage_limit == "" || Data_storage_limit==null) {
        $('#Errdata_storage_limit').text("Select Data Storage Limit");
        $('#data_storage_limit').focus();
        $('#data_storage_limit').css("border", "1px solid #B94A48");
        return false;
    }
    if (File_storage_limit == "" || File_storage_limit==null) {
        $('#Errfile_storage_limit').text("Select File Storage Limit");
        $('#file_storage_limit').focus();
        $('#file_storage_limit').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Remarks) == "") {
        $('#Errremarks').text("Enter Remarks");
        $('#remarks').focus();
        $('#remarks').css("border", "1px solid #B94A48");
        return false;
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
                    MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/updateInfo",
                    "rfrom=1&draftstatus=2&cmpny=" + Company + "&bt=" + BusinessType + "&cp=" + Contactperson + "&desgn=" + Designation + "&em=" + EmailId + "&mbno=" + MobileNo + "&gstno=" + Gstno +"&iecode=" + Iecode +
                    "&addrs=" + Address + "&cty=" + City + "&st=" + State + "&ctry=" + Country + "&pin=" + Pincode + "&subctgy=" + Subcription_Category + "&pckdetid=" + Pckgdet_id + 
                    "&purchtype=" + Purchase_type + "&additionalusers=" + Additional_users + "&datastrlimit=" + Data_storage_limit + "&filestrlimit=" + File_storage_limit + "&mrkt_dept_userid=" + Mrkt_dept_userid +
                    "&remarks=" + Remarks + "&id=" + GlbId, "json",function (data) {
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
                            //console.log(data,'data');
                            GlbId = data.id;
                            if(data.mode == 'inserted'){
                                swalWithBootstrapButtons.fire({
                                            title: 'Saved!',type: 'success',
                                            icon: 'success',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                }).then((result) => {
                                                    let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/manage';
                                                    window.location.href = redirectpath;
                                });
                            }else{
                                $("#savereqbtn").prop("disabled", false);
                                $("#enqsvbtn").hide();
                                $("#custom_form input").prop("disabled", true);
                                $("#custom_form select").prop("disabled", true);
                                $("textarea").prop("disabled", true);
                               // $("#editEnable").hide();
                            }
                        }
                    }
                });
				}else if (result.dismiss === Swal.DismissReason.cancel) {
					$("#enqsvbtn").hide();	
					$("#savereqbtn").prop("disabled", false);
					$("#custom_form input").prop("disabled", true);
                    $("#custom_form select").prop("disabled", true);
                    $("textarea").prop("disabled", true);
				}
            }); 

}
function fnSaveEnquiryDraft(id) {
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var Company = $("#companyname").val();
    var BusinessType = $("#businesstype").val();
    var Contactperson = $("#contactperson").val();
    var Designation = $("#designation").val();
    var EmailId = $("#email_id").val();
    var MobileNo = $("#mobile_no").val();
    var Gstno = $("#gst_no").val();
    var Iecode = $("#iecode_no").val();
    var Address = $("#address").val();
    var City = $("#city").val();
    var State = $("#state").val();
    var Country = $("#country").val();
    var Pincode = $("#pincode").val();
    var Subcription_Category = $("#subscription_category").val();
    var Pckgdet_id = $("#package_id").val();
    var Purchase_type = $("#purchasetype").val();
    var Additional_users = $("#additional_users").val();
    var Data_storage_limit = $("#data_storage_limit").val();
    var File_storage_limit = $("#file_storage_limit").val();
    var Request_status = ($('#request_status').val()==='')?0:$('#request_status').val();
    var Remarks = $("#remarks").val();
    
        if(Company!='' || BusinessType!=null || Contactperson!='' || Designation!='' || EmailId!='' || MobileNo!='' || 
           Gstno!='' || Iecode!='' || Address!='' || City!='' || State!='' || Country!='' || Pincode!='' || 
           Subcription_Category!=null ||  Pckgdet_id!=null || Purchase_type!=null || Additional_users!='' || 
           Data_storage_limit!=null || File_storage_limit!=null || Remarks!=''){
            if(id=='savedraftbtn'){
            swalWithBootstrapButtons.fire(
            {
               // title: 'Are you sure want to save the details ?',
               // text: "If you save You won't be able to revert this!",
                title: 'Do you want to save the draft details ?',
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
                    MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/updateInfo",
                    "rfrom=1&draftstatus=1&cmpny=" + Company + "&bt=" + BusinessType + "&cp=" + Contactperson + "&desgn=" + Designation + "&em=" + EmailId + "&mbno=" + MobileNo + "&gstno=" + Gstno +"&iecode=" +Iecode +
                    "&addrs=" + Address + "&cty=" + City + "&st=" + State + "&ctry=" + Country + "&pin=" + Pincode + "&subctgy=" + Subcription_Category + "&pckdetid=" + Pckgdet_id + 
                    "&purchtype=" + Purchase_type + "&additionalusers=" + Additional_users + "&datastrlimit=" + Data_storage_limit + "&filestrlimit=" + File_storage_limit +
                    "&remarks=" + Remarks + "&id=" + GlbId, "json",function (data) {
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
                            //console.log(data,'data');
                            GlbId = data.id;
                            swalWithBootstrapButtons.fire({
                                            title: 'Saved!',type: 'success',
                                            icon: 'success',
                                            customClass: {'confirmButton': 'btn btn-info'}
                            }).then((result) => {
                                                let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/manage';
                                                window.location.href = redirectpath;
                            });
                            
                        }
                    }
                });
				}
            }); 
            }else{
               MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/updateInfo",
                    "rfrom=1&draftstatus=1&cmpny=" + Company + "&bt=" + BusinessType + "&cp=" + Contactperson + "&desgn=" + Designation + "&em=" + EmailId + "&mbno=" + MobileNo + "&gstno=" + Gstno +
                    "&addrs=" + Address + "&cty=" + City + "&st=" + State + "&ctry=" + Country + "&pin=" + Pincode + "&subctgy=" + Subcription_Category + "&pckdetid=" + Pckgdet_id + 
                    "&purchtype=" + Purchase_type + "&additionalusers=" + Additional_users + "&datastrlimit=" + Data_storage_limit + "&filestrlimit=" + File_storage_limit +
                    "&remarks=" + Remarks + "&id=" + GlbId, "json",function (data) {
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
                             let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/manage';
                                                window.location.href = redirectpath;
                        }
                    }
                }); 
            }
          }else{
                 let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/manage';
                 window.location.href = redirectpath;
            }
           
}
function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = "rfrom=1";
    MakeAsynPostRequest(base_path + GlbBAdminFdr + 'mreqrcved/manage', GlbSearchParam, 'json', fnListRes);
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
                    ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';
                    if (data.ct > 0) {
                        let selestatus1 = "all"; // default
                        if ($('#btn-active').hasClass('active')) {
                               selestatus1 = "Active";
                             
                           } else if ($('#btn-inactive').hasClass('active')) {
                               selestatus1 = "Inactive";
                             
                           }
                        $.each(data.re, function (index, value) {
                             
                            if (selestatus1 === "all" || value.s === selestatus1) {
                            PageContent = PageContent + '<tr>' +
                                '<td><input style="margin:0px!important" type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td style="width:13%">' + value.cmpny + '</td>' +
                                '<td style="width:10%">' + value.cty + '</td>' +
                                '<td style="width:10%">' + value.st + '</td>' +
                                '<td style="width:9%">' + value.pckdet + '</td>' +
                                '<td style="width:10%">' + value.purchtype + '</td>' +
                                '<td style="width:10%">' + value.reqraisedby + '</td>' +
                                '<td style="width:11%">' + value.reqdatetime + '</td>' +
                                '<td style="width:10%;font-weight:bold;" class="knOrangeColor">' + value.reqstatus + '</td>' +
                                '<td style="width:11%">' + value.recent_updated_datetime + '</td>' +
                                '<td style="width:3%">' + value.s + '</td>' +
                                '<td style="width:3%"><a href="' + base_path + GlbBAdminFdr + 'mreqrcved/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">View</a></td>';
                            ;
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

// var table = $('#tableId').DataTable();

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
    var requestDate = parseTableDate(data[7]); // column index 6

    if (requestFrom) requestFrom.setHours(0,0,0,0);
    if (requestTo) requestTo.setHours(23,59,59,999);
    if (requestDate) requestDate.setHours(0,0,0,0);

    if (requestFrom && (!requestDate || requestDate < requestFrom)) return false;
    if (requestTo && (!requestDate || requestDate > requestTo)) return false;

    // --- Cutoff Date filter ---
    // var cutoffFrom  = parseInputDate($('#CutoffFrom').val());
    // var cutoffTo    = parseInputDate($('#CutoffTo').val());
    // var cutoffDate  = parseTableDate(data[6]); // column index 7

    // if (cutoffFrom) cutoffFrom.setHours(0,0,0,0);
    // if (cutoffTo) cutoffTo.setHours(23,59,59,999);
    // if (cutoffDate) cutoffDate.setHours(0,0,0,0);

    // if (cutoffFrom && (!cutoffDate || cutoffDate < cutoffFrom)) return false;
    // if (cutoffTo && (!cutoffDate || cutoffDate > cutoffTo)) return false;

    return true;
});

function fnSearch() {
   
    var frmSrchCmpny = $("#frmSrchCmpny").val();
    var frmSrchCity = $("#frmSrchCity").val();
    var frmSrchPckgDet = $("#frmSrchPckgDet").val();
    var frmSrchPurchtype = $("#frmSrchPurchtype").val();
    var frmSrchReqraisedby = $("#frmSrchReqraisedby").val();
    var frmSrchReqStatus = $("#frmSrchReqStatus").val();

    var fromDate = $('#RequestFrom').val().trim();
    var toDate = $('#RequestTo').val().trim();
  

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
    
    //GlbSearchParam = "rfrom=1&cmpny=" + frmSrchCmpny + "&cty=" + frmSrchCity + "&pckdetid=" + frmSrchPckgDet + "&purchtype=" + frmSrchPurchtype + "&reqraisedby=" + frmSrchReqraisedby + "&reqstatus=" + frmSrchReqStatus;
   // $("#DivTotalCntResult").html('');
    //MakeAsynPostRequest(base_path + GlbBAdminFdr + 'mreqrcved/manage', GlbSearchParam, 'json', fnListRes);
 var table1 = $('#tableId').DataTable();

    // Columns 1–4: normal substring search (no regex)
    table1.column(1).search(frmSrchCmpny, false, true); // false = regex, true = smart
    table1.column(2).search(frmSrchCity, false, true);
    table1.column(4).search(frmSrchPckgDet, false, true);
    table1.column(5).search(frmSrchPurchtype, false, true);
    table1.column(6).search(frmSrchReqraisedby, false, true);
    table1.column(8).search(frmSrchReqStatus, false, true);

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
$('#brandTblList').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);
    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    var frmSrchBrand = $("#frmSrchBrand").val();
    var Status = $("#frmSrchBrandStatus").val();
    GlbSearchParam = "rfrom=1&br=" + frmSrchBrand + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    MakeAsynPostRequest(base_path + GlbBAdminFdr + 'mreqrcved/manage', GlbSearchParam, 'json', fnListRes);
});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            // alert("Select Brand");
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
                    MakeAsynPostRequest(base_path + GlbBAdminFdr + 'mreqrcved/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
				}
                if (result.value) {
                    location.reload();
				}
                });
            
            
            // if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
            //     GlbSearchParam = "actdeactFabType=" + dropdownOpt + "&cid=" + idJson;
            //     MakeAsynPostRequest(base_path + GlbBAdminFdr + 'mreqrcved/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
            // }
        }
    }
    else {
        // alert('Select either ' + GlbStatusForMaster['1'] + ' or ' + GlbStatusForMaster['2']);
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



function onlyNumbernodecimal(evt) {  /// for allowing only number 

    // Only ASCII charactar in that range allowed
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    // console.log(ASCIICode);

    if (ASCIICode>46 && ASCIICode<58) {
        return true; 
    }

    return false; 
} 
    
function IsEmailid(email) {
var regex =/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
if (!regex.test(email)) {
    return false;
}
else {
    return true;
}
}
////////// package detail information /////////////////////////
$("#package_id").change(function () {
    var PckgId = $(this).val();
    $("#no_of_users").val('');
    $("#data_limit").val('');
    $("#file_limit").val('');
    MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/getPackageInfoByPckgId", "rFrom=1&id=" + PckgId, "json", function (data) {
        if (data.no_of_users !== ''){
        $("#no_of_users").val(data.no_of_users);
        }
        if(data.data_limit !== ''){
        $("#data_limit").val(data.data_limit); 
        }    
        if(data.file_limit !== ''){
        $("#file_limit").val(data.file_limit); 
        }   
    });
});
////////// end /////////////////////////

// $('#savereqbtn').on('click', function() {
//      swalWithBootstrapButtons.fire(
//             {
//               // title: 'Are you sure want to save the details ?',
//               // text: "If you save You won't be able to revert this!",
//                 title: 'Do you want to sent the request ?',
//                 type: 'warning',
//                 showCancelButton: true,
//                 scrollbarPadding: false,
//                 confirmButtonText: 'Yes',
//                 cancelButtonText: 'No',
//                 reverseButtons: true,
//                 width:460,
//                 customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
//             }
// 	        ).then(function(result) {
// 				if (result.value) {
// 				    let idValue = $('#subscriber_id').val();
//                                 let reqstatus = 1;
//                                 let status = 1;
//                     MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/proformareq", "rFrom=1&id="+idValue+"&reqstatus="+reqstatus+"&status="+status, "json", function (data) {
//                                     // console.log(data);
//                                     if(data.statusCode == "200") {
//                                         swalWithBootstrapButtons.fire({
//                                             title: data.message,type: 'success',
//                                             icon: 'success',
//                                             customClass: {'confirmButton': 'btn btn-info'}
//                                         }).then((result) => {
//                                             let enquiryListPath = GlbBAdminFdr +"mreqrcved/manage";
//                                             enquiryListPath = base_path+enquiryListPath;
//                                             window.location.href = enquiryListPath;
//                                         });
                                        
//                                     }
//                                     else if(data.statusCode == "203") {
//                                         swalWithBootstrapButtons.fire({
//                                             title:data.message, type: 'success',
//                                             icon: 'info',
//                                             customClass: {'confirmButton': 'btn btn-info'}
//                                         }).then((result) => {
//                                             let enquiryListPath = GlbBAdminFdr +"mreqrcved/manage";
//                                             enquiryListPath = base_path+enquiryListPath;
//                                             window.location.href = enquiryListPath;
//                                         });
                                        
//                                     }
//                                     else {
//                                         swalWithBootstrapButtons.fire({title:data.message,type: 'error',icon: 'error',customClass: {'confirmButton': 'btn btn-info px-5'}});
//                                     }
//                                 });

// 				}
//             }); 
// });  
function fncleardraft(val) {
    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    if(val!='') {
      swalWithBootstrapButtons.fire(
                            {
                               // title: 'Are you sure want to save the details ?',
                               // text: "If you save You won't be able to revert this!",
                                title: 'Do you want to clear the draft details ?',
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
								MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/getcleardraftstatus", "id=" + val, "json", function (data) {
								    console.log('clear'+data);
                                    if(data.success==1) {
                                    let enquiryListPath = GlbBAdminFdr +"mreqrcved/manage";
                                    enquiryListPath = base_path+enquiryListPath;
                                    window.location.href = enquiryListPath;
                                    }
                                });
								} 
								else if (result.dismiss === Swal.DismissReason.cancel) {
								
								}
                            }); 
    }
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
