var GlbSearchParam = '';

var GlbSortOrder = '';
var GlbColumnId = '';
function fnSearchBuyer() {
    var frmSrchBuyer = $("#frmSrchBuyer").val();
    var Status = $("#frmSrchBuyerStatus").val();
    GlbSearchParam = "rfrom=1&buyer=" + frmSrchBuyer + "&s=" + Status;
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbuyer/manage', GlbSearchParam, 'json', fnListBuyerRes);
}
function fnSaveInfo() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var Buyer = $("#frmBasicBuyer").val();
        var Status = $("#frmBasicStatus").val();
        if (jsTrim(Buyer) == "") {
            $('#ErrfrmBasicBuyer').text("Enter Buyer Name");
            $('#frmBasicBuyer').focus();
            $('#frmBasicBuyer').css("border", "1px solid #B94A48");
            return false;
        }
        if (Status == "") {
            $('#ErrBasicStatus').text("Please select the status");
            $('#frmBasicStatus').focus();
            $('#frmBasicStatus').css("border", "1px solid #B94A48");
            return false;
        }
        var Param = "rfrom=1&by=" + Buyer + "&s=" + Status + "&id=" + GlbId;
        MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbuyer/updateBuyerInfo', Param, "json", function (data) {
            console.log(data, 'data');
            fnSaveBuyerRes(data);
        });
        return false;
    } catch (e) {
        alert(e);
    }
}
function fnSaveBuyerRes(data) {
    if (data != '') {
        if (data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if (data.errcode == -1) {
            console.log(data.msg, 'data.msg');
            $('#AnyErrElse').text(data.msg);
            return false;
        } else if (data.errcode == 1) {
            GlbId = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Updated successfully!");
            fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mbuyer/addedit/' + data.eid);
        }
    }
}
function fnListBuyers() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = "rfrom=1";
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbuyer/manage', GlbSearchParam, 'json', fnListBuyerRes);
}
function fnListBuyerRes(data) {
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
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mbuyer/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/">' + value.buyer + '</a></td>' +
                                '<td>' + value.ub + '</td>' +
                                '<td>' + value.s + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mbuyer/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '"><i class="fa fa-edit"></i>&nbsp;Edit</a></td>';
                            PageContent = PageContent + '</tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent = PageContent + '<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                if (data.pa != undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                $('tbody').empty();
                $('#buyerTblList').append(PageContent);
            }
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
                fnSearchBuyer();
            }
        }
    }
}
$('#buyerTblList').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);
    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    var frmBasicBuyer = $("#frmSrchBuyer").val();
    var Status = $("#frmSrchBuyerStatus").val();
    GlbSearchParam = "rfrom=1&buy=" + frmBasicBuyer + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbuyer/manage', GlbSearchParam, 'json', fnListBuyerRes);
});

$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            alert("Select Buyer");
        }
        if (checkBoxLength >= 1) {
            var companyid_json = JSON.stringify(cboxObj);
            if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
                GlbSearchParam = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbuyer/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
            }
        }
    }
    else {
        alert('Select either ' + GlbStatusForMaster['1'] + ' or ' + GlbStatusForMaster['2']);
    }
});
function fnPaginationBuyers(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListBuyerRes);
}