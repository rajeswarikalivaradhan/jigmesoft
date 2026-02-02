var GlbSearchParam = '';
var GlbSortOrder = '';
var GlbColumnId = '';
function fnSearch() {
    var frmBasicYarnName = $("#frmSrchYarnName").val();
    var Status = $("#frmSrchYarnStatus").val();
    GlbSearchParam = "rfrom=1&yn=" + frmBasicYarnName + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path + GlbCompanyFdr + 'myarnvendor/manage', GlbSearchParam, 'json', fnListRes);
}
function fnListYarn() {
    GlbSearchParam = "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'myarnvendor/manage', GlbSearchParam, 'json', fnListRes);
}
function fnListRes(data) {
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
                                '<td><a href="' + base_path + GlbCompanyFdr + 'myarnvendor/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/">' + value.yv + '</a></td>' +
                                '<td>' + value.s + '</td><td>' + value.ub + '</td><td>' + value.du + '</td>' +
                                '<td>' +
                                '<a href="' + base_path + GlbCompanyFdr + 'myarnvendor/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">' +
                                '<i class="fa fa-edit"></i>&nbsp;Edit</a>' +
                                '</td>';
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
                $('#mYarnvendorList').append(PageContent);
            }
        }
    }
}
function fnPaginationYarnVendor(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListRes);
}
function fnSaveYarnVendor() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData = false;
        var frmBasicYarnvendor = $("#frmBasicYarnVendor").val();
        var Status = $("#frmBasicStatus").val();
        if (jsTrim(frmBasicYarnvendor) == "") {
            $('#ErrfrmBasicYarnVendor').text("Please fill the yarn vendor");
            $('#frmBasicYarnVendor').focus();
            $('#frmBasicYarnVendor').css("border", "1px solid #B94A48");
            return false;
        }
        if (jsTrim(Status) == "") {
            $('#ErrBasicStatus').text("Please select the status");
            $('#frmBasicStatus').focus();
            $('#frmBasicStatus').css("border", "1px solid #B94A48");
            return false;
        }
        if (window.FormData) {
            ProfileFormData = new FormData();
            ProfileFormData.append("yv", frmBasicYarnvendor);
            ProfileFormData.append("s", Status);
            ProfileFormData.append("id", GlbId);
        }
        $.ajax({
            url: base_path + GlbCompanyFdr + 'myarnvendor/updateInfo',
            data: ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                data = JSON.parse(data);
                fnSaveYarnRes(data);
            }
        });
        return false;
    } catch (e) {
        alert(e);
    }
}
function fnSaveYarnRes(data) {
    if (data != '') {
        if (data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if (data.errcode == -1) {
            $('#AnyOtherErr').text(data.msg);
            return false;
        } else if (data.errcode == 1) {
            GlbId = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Updated successfully!");
            fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'myarnvendor/addedit/' + data.eid);
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
                fnSearch();
            }
        }
    }
}
$('#mYarnvendorList').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);
    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    var frmBasicYarnName = $("#frmSrchYarnName").val();
    var Status = $("#frmSrchYarnStatus").val();
    GlbSearchParam = "rfrom=1&yn=" + frmBasicYarnName + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'myarnvendor/manage', GlbSearchParam, 'json', fnListRes);
});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            alert("Select Yarn Vendor");
        }
        if (checkBoxLength >= 1) {
            var companyid_json = JSON.stringify(cboxObj);
            if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
                GlbSearchParam = "rfrom=1&actDeact=" + dropdownOpt + "&cid=" + companyid_json;
                MakeAsynPostRequest(base_path + GlbCompanyFdr + 'myarnvendor/changeStatus', GlbSearchParam, 'json', fnChangeStatusRes);
            }
        }
    }
    else {
        alert('Select either '+GlbStatusForMaster['1']+' or '+GlbStatusForMaster['2']);
    }
});