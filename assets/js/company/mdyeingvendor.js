var GlbSearchParam = '';

var GlbSortOrder = '';
var GlbColumnId = '';

function fnSearchBomVendor() {
    var frmBasicVendorName = $("#frmSrchVendor").val();
    var frmSrchVendorContactName = $("#frmSrchContactName").val();
    var frmSrchEmailid = $("#frmSrchEmailid").val();
    var Status = $("#frmSrchBomStatus").val();
    GlbSearchParam = "rfrom=1&vn=" + frmBasicVendorName + "&cn=" + frmSrchVendorContactName + "&e=" + frmSrchEmailid + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mdyeingvendor/managedyeingvendor', GlbSearchParam, 'json', fnListRes);
}

function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mdyeingvendor/managedyeingvendor', GlbSearchParam, 'json', fnListRes);
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
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mdyeingvendor/addedit/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.vn + '</a></td>' +
                                '<td>' + value.vcn + '</td>' +
                                '<td>' + value.addr + '</td>' +
                                '<td>' + value.e + '</td>' +
                                '<td>' + value.p + '</td>' +
                                '<td>' + value.m + '</td>' +
                                '<td>' + value.ub + '</td>' +
                                '<td>' + value.s + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mdyeingvendor/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '"><i class="fa fa-edit"></i>&nbsp;Edit</a></td>';
                            PageContent = PageContent + '</tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent = PageContent + '<tr><td colspan="11" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                if (data.pa != undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                $('tbody').empty();
                $('#mdyeingvendorList').append(PageContent);
            }
        }
    }

}

function fnSavedyeingvendorInfo() {

    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var frmBasicVendor = $("#frmBasicVendor").val();
    var frmBasicAddress = $("#frmBasicSAddr").val();
    var frmBasicContactname = $("#frmBasicContactname").val();
    var frmBasicEmailId = $("#frmBasicEmailId").val();
    var frmBasicPhone = $("#frmBasicPhone").val();
    var frmBasicMobile = $("#frmBasicMobile").val();
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
        $('#ErrfrmBasicVendor').text("Please fill the vendor name");
        $('#frmBasicVendor').focus();
        $('#frmBasicVendor').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicContactname) == "") {
        $('#ErrfrmBasicContactname').text("Please fill the contact name");
        $('#frmBasicContactname').focus();
        $('#frmBasicContactname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicAddress) == "") {
        $('#ErrfrmBasicSAddr').text("Please fill the address");
        $('#frmBasicSAddr').focus();
        $('#frmBasicSAddr').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicEmailId) == "") {
        $('#ErrfrmBasicEmailId').text("Please fill the email id");
        $('#frmBasicEmailId').focus();
        $('#frmBasicEmailId').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicPhone) == "") {
        $('#ErrfrmBasicPhone').text("Please fill the phone no.");
        $('#frmBasicPhone').focus();
        $('#frmBasicPhone').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicMobile) == "") {
        $('#ErrfrmBasicMobile').text("Please fill the mobile no.");
        $('#frmBasicMobile').focus();
        $('#frmBasicMobile').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicGstno) == "") {
        $('#ErrfrmBasicGstno').text("Please fill the GST No.");
        $('#frmBasicGstno').focus();
        $('#frmBasicGstno').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicIecode) == "") {
        $('#ErrfrmBasicIecode').text("Please fill the IE Code");
        $('#frmBasicIecode').focus();
        $('#frmBasicIecode').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicBankname) == "") {
        $('#ErrfrmBasicBankname').text("Please fill the bank name");
        $('#frmBasicBankname').focus();
        $('#frmBasicBankname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicAccountname) == "") {
        $('#ErrfrmBasicAccountname').text("Please fill the account name");
        $('#frmBasicAccountname').focus();
        $('#frmBasicAccountname').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicAccountno) == "") {
        $('#ErrfrmBasicAccountno').text("Please fill the account no.");
        $('#frmBasicAccountno').focus();
        $('#frmBasicAccountno').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicIfscode) == "") {
        $('#ErrfrmBasicIfscode').text("Please fill the IFS code");
        $('#frmBasicIfscode').focus();
        $('#frmBasicIfscode').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicRtgs) == "") {
        $('#ErrfrmBasicRtgs').text("Please fill the RTGS");
        $('#frmBasicRtgs').focus();
        $('#frmBasicRtgs').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicSwiftcode) == "") {
        $('#ErrfrmBasicSwiftcode').text("Please fill the SWIFT code");
        $('#frmBasicSwiftcode').focus();
        $('#frmBasicSwiftcode').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(frmBasicIban) == "") {
        $('#ErrfrmBasicIban').text("Please fill the IBAN");
        $('#frmBasicIban').focus();
        $('#frmBasicIban').css("border", "1px solid #B94A48");
        return false;
    }
    if (jsTrim(Status) == "") {
        $('#ErrfrmBasicStatus').text("Please select the status");
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
    MyFormData.append("gst", frmBasicGstno);
    MyFormData.append("ie", frmBasicIecode);
    MyFormData.append("bn", frmBasicBankname);
    MyFormData.append("an", frmBasicAccountname);
    MyFormData.append("ano", frmBasicAccountno);
    MyFormData.append("ifs", frmBasicIfscode);
    MyFormData.append("rtg", frmBasicRtgs);
    MyFormData.append("sc", frmBasicSwiftcode);
    MyFormData.append("iba", frmBasicIban);
    MyFormData.append("s", Status);
    MyFormData.append("id", GlbId);
    $.ajax({
        url: base_path + GlbCompanyFdr + 'mdyeingvendor/updateInfo',
        data: MyFormData,
        contentType: false,
        processData: false,
        dataType: "json",
        type: 'POST',
        success: function (data) {
            fnSaveBomRes(data);
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
            $('#AnyErrElse').text(data.msg);
            return false;
        } else if (data.errcode == 1) {
            GlbId = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Updated successfully");
            fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mdyeingvendor/addedit/' + data.eid);
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

$('#mdyeingvendorList').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);
    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    console.log(GlbSearchParam);
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mdyeingvendor/managedyeingvendor', GlbSearchParam, 'json', fnChangeStatusRes);
});



$('#btnChangeStatus').on('click', function () {
    var StatusOptSelVal = $('#frmItemStatus').val();
    if (parseInt(StatusOptSelVal) > 0) {
        var ArrItemCheckBoxSel = commonCheckbox();
        var ObkChkSelVal = ArrItemCheckBoxSel[0];
        if (parseInt(ArrItemCheckBoxSel[1]) == 0) {
            alert("Select Vendor");
        }
        if (parseInt(ArrItemCheckBoxSel[1]) >= 1) {
            if (confirm('Do you want to ' + GlbStatusForMaster[StatusOptSelVal] + ' this records?')) {
                GlbSearchParam = "rfrom=1&actdeactFabType=" + StatusOptSelVal + "&cid=" + JSON.stringify(ObkChkSelVal);
                MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mdyeingvendor/changemStatus', GlbSearchParam, 'json', fnSearchBomVendor);
            }
        }
    } else {
        alert('Select either ' + GlbStatusForMaster['1'] + ' or ' + GlbStatusForMaster['2']);
    }
});

function fnPaginationBomVendor(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListRes);
}