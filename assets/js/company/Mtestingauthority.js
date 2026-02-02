let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});

function fnSave() {
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var Vendor_name = $("#vendor_name").val();
    var Contactperson = $("#contactperson").val();
    var Address = $("#address").val();
    var EmailId = $("#emailid").val();
    var MobileNo = $("#mobile").val();
    var PhoneNo = $("#phone").val();
    var VendorCategory = $("#vendor_categoryid").val();
    var primaryline = $("#primary_pdtline").val();
    var secondaryline = $("#secondary_pdtline").val();
    var Gstno = $("#gstno").val();
    var IECode = $("#iecode").val();
    var BankName = $("#bankname").val();
    var Accountname = $("#accountname").val();
    var Accountno = $("#accountno").val();
    var IFSCCode = $("#ifsccode").val();
    var swiftcode = $("#swiftcode").val();
    var Status = $("#status").val();
    if (jsTrim(Vendor_name) == "") {
        $('#Errvendor_name').text("Enter Testing Authority Name");
        $('#vendor_name').focus();
        $('#vendor_name').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Address) == "") {
        $('#Erraddress').text("Enter Address");
        $('#address').focus();
        $('#address').css("border", "1px solid #B94A48");
        return false;
    }
     if (jsTrim(Contactperson) == "") {
        $('#Errcontactperson').text("Enter Contact Person");
        $('#contactperson').focus();
        $('#contactperson').css("border", "1px solid #B94A48");
        return false;
    }
    
    if (EmailId == "") {
        $('#Erremailid').text("Enter Email ID");
        $('#emailid').focus();
        $('#emailid').css("border", "1px solid #B94A48");
        return false;
    }
    if (EmailId!='' && IsEmailid(EmailId) == false) {
        $('#Erremailid').text("Invalid E-mail Id,Please Enter Valid One");
        $('#emailid').focus();
        $('#emailid').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(MobileNo) == "") {
        $('#Errmobile').text("Enter Mobile No.");
        $('#mobile').focus();
        $('#mobile').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(VendorCategory) == "") {
        $('#Errvendor_categoryid').text("Select Lab Category");
        $('#vendor_categoryid').focus();
        $('#vendor_categoryid').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(primaryline) == "") {
        $('#Errprimary_pdtline').text("Enter Primary Service Line");
        $('#primary_pdtline').focus();
        $('#primary_pdtline').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Gstno) == "") {
        $('#Errgstno').text("Enter GST No");
        $('#gstno').focus();
        $('#gstno').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(IECode) == "") {
        $('#Erriecode').text("Enter IE Code");
        $('#iecode').focus();
        $('#iecode').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(BankName) == "") {
        $('#Errbankname').text("Enter Bank Name");
        $('#bankname').focus();
        $('#bankname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Accountname) == "") {
        $('#Erraccountname').text("Enter Account Name ");
        $('#accountname').focus();
        $('#accountname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Accountno) == "") {
        $('#Erraccountno').text("Enter Account No");
        $('#accountno').focus();
        $('#frmBasic').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(IFSCCode) == "") {
        $('#Errifsccode').text("Enter IFSC Code");
        $('#ifsccode').focus();
        $('#ifsccode').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(swiftcode) == "") {
        $('#Errswiftcode').text("Enter SWIFT Code");
        $('#frmswiftcode').focus();
        $('#frmswiftcode').css("border", "1px solid #B94A48");
        return false;
    }
    if (Status == "") {
        $('#Errstatus').text("Select Status");
        $('#status').focus();
        $('#status').css("border", "1px solid #B94A48");
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
                    MakeAsynPostRequest(base_path + GlbCompanyFdr + "mtestauth/updateInfo",
                    "rfrom=1&vendor_name=" + Vendor_name + "&cp=" + Contactperson + "&addr=" + Address + 
                    "&em=" + EmailId + "&phno=" + PhoneNo + "&mbno=" + MobileNo + "&fvc=" + VendorCategory +
                    "&pmpdtln=" + primaryline + "&scpdtln=" + secondaryline + "&gst=" + Gstno +  "&iec=" + IECode + 
                    "&bnk=" + BankName + "&actn=" + Accountname + "&actno=" + Accountno + "&ifsc=" + IFSCCode +
                    "&swift=" + swiftcode + "&s=" + Status + "&id=" + GlbId, "json",function (data) {
                        if (data != '') {
                        if (data.errcode == '404') {
                            fnCallSessionExpire();
                            return false;
                        } else if (data.errcode == -1) {
                           // $('#AnyErrElse').text(data.msg);
                            swalWithBootstrapButtons.fire({
                                title: data.msg,type: 'warning',
                                icon: 'warning',
                                customClass: {'confirmButton': 'btn btn-info'}
                            });
                            return false;
                        } else if (data.errcode == 1) {
                            //console.log(data,'data');
                            GlbId = data.id;
                            
                            // $("#divSuccessBasicInfoMsg").removeClass('hide');
                            // $("#divSuccessBasicInfoMsg").text("Updated successfully!");
                            // fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mtestauth/addedit/' + data.eid);
                            
                            swalWithBootstrapButtons.fire({
                                            title: 'Saved!',text: data.message,type: 'success',
                                            icon: 'success',
                                            customClass: {'confirmButton': 'btn btn-info'}
                            }).then((result) => {
                                                let redirectpath = base_path + GlbCompanyFdr + 'mtestauth/manage';
                                                window.location.href = redirectpath;
                            });
                            
                        }
                    }
                });
				}
            }); 

}
function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = "rfrom=1";
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mtestauth/manage', GlbSearchParam, 'json', fnListRes);
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
                        $.each(data.re, function (index, value) {
                            PageContent = PageContent + '<tr>' +
                                '<td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td>' + value.vendor_name + '</td>' +
                                '<td>' + value.cp + '</td>' +
                                '<td>' + value.em + '</td>' +
                                '<td>' + value.phno + '</td>' +
                                '<td>' + value.mno + '</td>' +
                                '<td>' + value.fvc + '</td>' +
                                '<td>' + value.prmypdtln + '</td>' +
                                '<td>' + value.s + '</td>' +
                                '<td>' + value.ub + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mtestauth/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">View</a></td>';
                            ;
                            PageContent = PageContent + '</tr>';
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
function fnSearch() {
    var frmSrchvendorname = $("#frmSrchvendorname").val();
    var frmSrchcontperson = $("#frmSrchcontperson").val();
    var frmSrchemailid = $("#frmSrchemailid").val();
    var frmSrchmobno = $("#frmSrchmobno").val();
    var frmSrchvendor_category = $("#frmSrchvendor_category").val();
    var frmSrchprimarypdtline = $("#frmSrch_primarypdtline").val();
    var frmSrchstatus = $("#frmSrchstatus").val();
    
    GlbSearchParam = "rfrom=1&vendor_name=" + frmSrchvendorname + "&cp=" + frmSrchcontperson + "&em=" + frmSrchemailid + "&mno=" + frmSrchmobno +  "&fvc=" + frmSrchvendor_category + "&pmrypdtln=" + frmSrchprimarypdtline + "&s=" + frmSrchstatus;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mtestauth/manage', GlbSearchParam, 'json', fnListRes);
}
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
// $('#brandTblList').on('click', 'th.sortable', function () {
//     var ReturnVal = commonTableSorting(this);
//     GlbSortOrder = ReturnVal[1];
//     GlbColumnId = ReturnVal[0];
//     var frmSrchBrand = $("#frmSrchBrand").val();
//     var Status = $("#frmSrchBrandStatus").val();
//     GlbSearchParam = "rfrom=1&br=" + frmSrchBrand + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
//     MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mtestauth/manage', GlbSearchParam, 'json', fnListRes);
// });
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
				    GlbSearchParam = "rfrom=1&status=" + dropdownOpt + "&cid=" + idJson;
                    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mtestauth/changeStatus', GlbSearchParam, 'json', fnChangeStatusRes);
				}
                });
            
            
            // if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
            //     GlbSearchParam = "actdeactFabType=" + dropdownOpt + "&cid=" + idJson;
            //     MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mtestauth/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
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
function fnPaginationBrand(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListRes);
}

$('#editEnable').on('click', function() {
    $("#custom_form input").prop("disabled", false);
    $("#custom_form select").prop("disabled", false);
    $("#custom_form textarea").prop("disabled", false);
});

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