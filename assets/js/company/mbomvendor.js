var GlbSearchParam = '';
var GlbSortOrder = '';
var GlbColumnId = '';
let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});

function fnSearchBomVendor() {
    var frmBasicVendorName = $("#frmSrchVendor").val();
    var frmSrchVendorContactName = $("#frmSrchContactName").val();
    var frmSrchEmailid = $("#frmSrchEmailid").val();
    var frmsrchmobileno = $("#frmSrchMobNo").val();
    var frmsrchvendorcategory = $("#frmSrchVendorCategory").val();
    var frmsrchprimary_pdtline = $("#frmSrchprimary_pdtline").val();
    var Status = $("#frmSrchStatus").val();
    
    GlbSearchParam = "rfrom=1&vn=" + frmBasicVendorName + "&cn=" + frmSrchVendorContactName + "&e=" + frmSrchEmailid + "&mno=" + frmsrchmobileno + "&vc=" + frmsrchvendorcategory + "&ppl=" + frmsrchprimary_pdtline + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbomvendor/managebomvendor', GlbSearchParam, 'json', fnListRes);
}

function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbomvendor/managebomvendor', GlbSearchParam, 'json', fnListRes);
}

function fnListRes(data) {
    console.log(data);
    console.log(data.re);
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
                            PageContent = PageContent + '<tr><td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td>' + value.vn + '</td>' +
                                '<td>' + value.vcn + '</td>' +
                                '<td>' + value.e + '</td>' +
                                '<td>' + value.p + '</td>' +
                                '<td>' + value.m + '</td>' +
                                '<td>' + value.vc + '</td>' +
                                '<td>' + value.prmypdtln + '</td>' +
                                // '<td>' + value.bomitem2 + '</td>' +
                                '<td>' + value.s + '</td>' +
                                '<td>' + value.ub + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mbomvendor/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">View</a></td>';
                            PageContent = PageContent + '</tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                     PageContent = PageContent + '<tr><td colspan="12" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                if (data.pa != undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                // $('tbody').empty();
                // $('#mBomVendorList').append(PageContent);
                 $('#tableId tbody').empty();
                 $('#tableId').append(PageContent).DataTable();
            }
        }
    }

}

function fnSaveBomVendorInfo() {

    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var frmBasicVendor = $("#frmBasicVendor").val();
    var frmBasicAddress = $("#frmBasicSAddr").val();
    var frmBasicContactname = $("#frmBasicContactname").val();
    var frmBasicEmailId = $("#frmBasicEmailId").val();
    var frmBasicPhone = $("#frmBasicPhone").val();
    var frmBasicMobile = $("#frmBasicMobile").val();
    var vendorcategory = $("#frmvendor_categoryid").val();
    var primary_pdtline = $("#frmprimary_pdtline").val();
    var secondary_pdtline = $("#frmsecondary_pdtline").val();
    var frmBasicGstno = $("#frmBasicGstno").val();
    var frmBasicIecode = $("#frmBasicIecode").val();
    var frmBasicBankname = $("#frmBasicBankname").val();
    var frmBasicAccountname = $("#frmBasicAccountname").val();
    var frmBasicAccountno = $("#frmBasicAccountno").val();
    var frmBasicIfscode = $("#frmBasicIfscode").val();
    var frmBasicRtgs = $("#frmBasicRtgs").val();
    var frmBasicSwiftcode = $("#frmBasicSwiftcode").val();
    var frmBasicIban = $("#frmBasicIban").val();
    var Status = $("#frmBasicStatus").val();
    
    if (jsTrim(frmBasicVendor) == "") {
        $('#ErrfrmBasicVendor').text("Enter BOM Vendor Name");
        $('#frmBasicVendor').focus();
        $('#frmBasicVendor').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicAddress) == "") {
        $('#ErrfrmBasicSAddr').text("Enter Address");
        $('#frmBasicSAddr').focus();
        $('#frmBasicSAddr').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicContactname) == "") {
        $('#ErrfrmBasicContactname').text("Enter Contact Person");
        $('#frmBasicContactname').focus();
        $('#frmBasicContactname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicEmailId) == "") {
        $('#ErrfrmBasicEmailId').text("Enter E-mail Id");
        $('#frmBasicEmailId').focus();
        $('#frmBasicEmailId').css("border", "1px solid #B94A48");
        return false;
    }
    if (frmBasicEmailId!='' && IsEmailid(frmBasicEmailId) == false) {
        $('#ErrfrmBasicEmailId').text("Invalid E-mail Id,Please Enter Valid One");
        $('#frmBasicEmailId').focus();
        $('#frmBasicEmailId').css("border", "1px solid #B94A48");
        return false;
    }
    // if (jsTrim(frmBasicPhone) == "") {
    //     $('#ErrfrmBasicPhone').text("Enter Phone No");
    //     $('#frmBasicPhone').focus();
    //     $('#frmBasicPhone').css("border", "1px solid #B94A48");
    //     return false;
    // }
    if (jsTrim(frmBasicMobile) == "") {
        $('#ErrfrmBasicMobile').text("Enter Mobile No");
        $('#frmBasicMobile').focus();
        $('#frmBasicMobile').css("border", "1px solid #B94A48");
        return false;
    }
    // if (jsTrim(frmBasicMobile)!= "" && IsMobile(frmBasicMobile)==false) {
    //     $('#ErrfrmBasicMobile').text("Invalid Mobile No ,Please Enter Valid One");
    //     $('#frmBasicMobile').focus();
    //     $('#frmBasicMobile').css("border", "1px solid #B94A48");
    //     return false;
    // }
    
    if (jsTrim(vendorcategory) == "") {
        $('#Errfrmvendor_categoryid').text("Select Vendor Category");
        $('#frmvendor_categoryid').focus();
        $('#frmvendor_categoryid').css("border", "1px solid #B94A48");
        return false;
    }
    if(jsTrim(primary_pdtline)==""){
        $('#Errfrmprimary_pdtline').text("Enter Primary Product Line");
        $('#frmprimary_pdtline').focus();
        $('#frmprimary_pdtline').css("border", "1px solid #B94A48");
        return false;
    }
    // if(jsTrim(secondary_pdtline)==""){
    //     $('#Errfrmbom_art2_itemid').text("Select Secondary Product Line");
    //     $('#frmbom_art2_itemid').focus();
    //     $('#frmbom_art2_itemid').css("border", "1px solid #B94A48");
    //     return false;
    // }
    if (jsTrim(frmBasicGstno) == "") {
        $('#ErrfrmBasicGstno').text("Enter GST No");
        $('#frmBasicGstno').focus();
        $('#frmBasicGstno').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicIecode) == "") {
        $('#ErrfrmBasicIecode').text("Enter IE Code");
        $('#frmBasicIecode').focus();
        $('#frmBasicIecode').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicBankname) == "") {
        $('#ErrfrmBasicBankname').text("Enter Bank Name");
        $('#frmBasicBankname').focus();
        $('#frmBasicBankname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicAccountname) == "") {
        $('#ErrfrmBasicAccountname').text("Enter Account Name ");
        $('#frmBasicAccountname').focus();
        $('#frmBasicAccountname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicAccountno) == "") {
        $('#ErrfrmBasicAccountno').text("Enter Account No");
        $('#frmBasicAccountno').focus();
        $('#frmBasicAccountno').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicIfscode) == "") {
        $('#ErrfrmBasicIfscode').text("Enter IFSC Code");
        $('#frmBasicIfscode').focus();
        $('#frmBasicIfscode').css("border", "1px solid #B94A48");
        return false;
    }
    // if (jsTrim(frmBasicRtgs) == "") {
    //     $('#ErrfrmBasicRtgs').text("Please fill the RTGS");
    //     $('#frmBasicRtgs').focus();
    //     $('#frmBasicRtgs').css("border", "1px solid #B94A48");
    //     return false;
    // }
    if (jsTrim(frmBasicSwiftcode) == "") {
        $('#ErrfrmBasicSwiftcode').text("Enter SWIFT Code");
        $('#frmBasicSwiftcode').focus();
        $('#frmBasicSwiftcode').css("border", "1px solid #B94A48");
        return false;
    }
    // if (jsTrim(frmBasicIban) == "") {
    //     $('#ErrfrmBasicIban').text("Please fill the IBAN");
    //     $('#frmBasicIban').focus();
    //     $('#frmBasicIban').css("border", "1px solid #B94A48");
    //     return false;
    // }
    if (jsTrim(Status) == "") {
        $('#ErrfrmBasicStatus').text("Select Status");
        $('#frmBasicStatus').focus();
        $('#frmBasicStatus').css("border", "1px solid #B94A48");
        return false;
    }

    var MyFormData = new FormData();
    MyFormData.append("vaddr", frmBasicAddress);
    MyFormData.append("vn", frmBasicVendor);
    MyFormData.append("vcn", frmBasicContactname);
    MyFormData.append("e", frmBasicEmailId);
    MyFormData.append("p", frmBasicPhone);
    MyFormData.append("m", frmBasicMobile);
    MyFormData.append("vc", vendorcategory);
    MyFormData.append("ppl", primary_pdtline);
    MyFormData.append("spl", secondary_pdtline);
    MyFormData.append("gst", frmBasicGstno);
    MyFormData.append("ie", frmBasicIecode);
    MyFormData.append("bn", frmBasicBankname);
    MyFormData.append("an", frmBasicAccountname);
    MyFormData.append("ano", frmBasicAccountno);
    MyFormData.append("ifs", frmBasicIfscode);
   // MyFormData.append("rtg", frmBasicRtgs);
    MyFormData.append("sc", frmBasicSwiftcode);
    //MyFormData.append("iba", frmBasicIban);
    MyFormData.append("s", Status);
    MyFormData.append("id", GlbId);
    
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
                    $.ajax({
                        url: base_path + GlbCompanyFdr + 'mbomvendor/updateInfo',
                        data: MyFormData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        type: 'POST',
                        success: function (data) {
                            fnSaveBomRes(data);
                        }
                    });
				}
            }); 

    return false;

}

function fnSaveBomRes(data) {
    console.log(data, 'data');
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
            GlbId = data.id;
            
            // $("#divSuccessBasicInfoMsg").removeClass('hide');
            // $("#divSuccessBasicInfoMsg").text("Updated successfully");
            // fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mbomvendor/addedit/' + data.eid);
            
            swalWithBootstrapButtons.fire({
                                title: 'Saved!',text: data.message,type: 'success',
                                icon: 'success',
                                customClass: {'confirmButton': 'btn btn-info'}
            }).then((result) => {
                                let redirectpath = base_path + GlbCompanyFdr + 'mbomvendor/managebomvendor';
                                window.location.href = redirectpath;
            });
        }
    }
}

function fnChangeStatusRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchBomVendor();
            }
        }
    }
}

$('#mBomVendorList').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);
    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    console.log(GlbSearchParam);
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbomvendor/managebomvendor', GlbSearchParam, 'json', fnChangeStatusRes);
});



$('#btnChangeStatus').on('click', function () {
    var StatusOptSelVal = $('#frmItemStatus').val();
    if (parseInt(StatusOptSelVal) > 0) {
        var ArrItemCheckBoxSel = commonCheckbox();
        var ObkChkSelVal = ArrItemCheckBoxSel[0];
        if (parseInt(ArrItemCheckBoxSel[1]) == 0) {
            //alert("Select Vendor");
            swalWithBootstrapButtons.fire({
                title: 'Select a record!',
                type: 'error',
                icon: 'error',
                width:460,
                customClass: {'confirmButton': 'btn btn-info'}
            });
        }
        if (parseInt(ArrItemCheckBoxSel[1]) >= 1) {
            
            swalWithBootstrapButtons.fire(
                {
                   
                    title: 'Do you want to ' + GlbStatusForMaster[StatusOptSelVal] + ' this record ?',
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
				    GlbSearchParam = "rfrom=1&actdeactFabType=" + StatusOptSelVal + "&cid=" + JSON.stringify(ObkChkSelVal);
                    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbomvendor/changemStatus', GlbSearchParam, 'json', fnSearchBomVendor);
				}
                });
            
            
            // if (confirm('Do you want to ' + GlbStatusForMaster[StatusOptSelVal] + ' this records?')) {
            //     GlbSearchParam = "rfrom=1&actdeactFabType=" + StatusOptSelVal + "&cid=" + JSON.stringify(ObkChkSelVal);
            //     MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbomvendor/changemStatus', GlbSearchParam, 'json', fnSearchBomVendor);
            // }
        }
    } else {
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

function fnPaginationBomVendor(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListRes);
}

$('#editEnable').on('click', function() {
    $("#custom_form input").prop("disabled", false);
    $("#custom_form select").prop("disabled", false);
    $("#custom_form textarea").prop("disabled", false);
});

function IsEmailid(email) {
    var regex =/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    }
    else {
        return true;
    }
}
function IsMobile(mobno) {
var regex = /^(0|91)?[6-9][0-9]{9}$/;
    if (!regex.test(mobno)) {
         return false;
    } else {
        return true;
    }
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
