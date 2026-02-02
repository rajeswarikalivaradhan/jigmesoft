function fnSave() {
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var Brand = $("#frmBasicBrand").val();
    var BuyerId = $("#frmBasicBuyerId").val();
    var Status = $("#frmBasicStatus").val();
    if (jsTrim(Brand) == "") {
        $('#ErrfrmBasicBrand').text("Enter Brand Name");
        $('#frmBasicBrand').focus();
        $('#frmBasicBrand').css("border", "1px solid #B94A48");
        return false;
    }
    if (BuyerId == "") {
        $('#ErrfrmBasicBuyerId').text("Please select the Buyer");
        $('#frmBasicBuyerId').focus();
        $('#frmBasicBuyerId').css("border", "1px solid #B94A48");
        return false;
    }
    if (Status == "") {
        $('#ErrBasicStatus').text("Please select the status");
        $('#frmBasicStatus').focus();
        $('#frmBasicStatus').css("border", "1px solid #B94A48");
        return false;
    }
    MakeAsynPostRequest(base_path + GlbCompanyFdr + "mbrand/updateBrandInfo", "rfrom=1&brn=" + Brand + "&byrId=" + BuyerId + "&s=" + Status + "&id=" + GlbId, "json", function (data) {
        if (data != '') {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if (data.errcode == -1) {
                $('#AnyErrElse').text(data.msg);
                return false;
            } else if (data.errcode == 1) {
                //console.log(data,'data');
                GlbId = data.id;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Updated successfully!");
                fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mbrand/addedit/' + data.eid);
            }
        }
    });
}
function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = "rfrom=1";
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbrand/manage', GlbSearchParam, 'json', fnListBrandsRes);
}
function fnListBrandsRes(data) {
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
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mbrand/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/">' + value.brand + '</a></td>' +
                                '<td>' + value.ub + '</td>' +
                                '<td>' + value.s + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mbrand/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '"><i class="fa fa-edit"></i>&nbsp;Edit</a></td>';
                            ;
                            PageContent = PageContent + '</tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent = PageContent + '<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                if (data.pa != undefined) {
                    console.log(base64_decode(data.pa))
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                $('tbody').empty();
                $('#brandTblList').append(PageContent);
            }
        }
    }
}
var GlbSearchParam = '';
var GlbSortOrder = '';
var GlbColumnId = '';
function fnSearchBrand() {
    var frmSrchBrand = $("#frmSrchBrand").val();
    var Status = $("#frmSrchBrandStatus").val();
    GlbSearchParam = "rfrom=1&br=" + frmSrchBrand + "&s=" + Status;
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbrand/manage', GlbSearchParam, 'json', fnListBrandsRes);
}
function fnChangeStatusRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchBrand();
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
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbrand/manage', GlbSearchParam, 'json', fnListBrandsRes);
});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            alert("Select Brand");
        }
        if (checkBoxLength >= 1) {
            var idJson = JSON.stringify(cboxObj);
            if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
                GlbSearchParam = "actdeactFabType=" + dropdownOpt + "&cid=" + idJson;
                MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mbrand/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
            }
        }
    }
    else {
        alert('Select either ' + GlbStatusForMaster['1'] + ' or ' + GlbStatusForMaster['2']);
    }
});
function fnPaginationBrand(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListBrandsRes);
}