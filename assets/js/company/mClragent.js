GlbColumnId = '';
GlbSortOrder = '';
function fnSearch() {
    var CAgent = $("#frmSrchCAgent").val();
    var Status = $("#frmSrchStatus").val();
    GlbSearchParam = "rfrom=1&ca=" + CAgent + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/clearingAgent', GlbSearchParam, 'json', fnListRes);
}
function fnList() {
    GlbSearchParam = "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/clearingAgent', GlbSearchParam, 'json', fnListRes);
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
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mlogistics/addEditClragent/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.ca + '</a>' +
                                '</td>' +
                                '<td>' + value.s + '</td><td>' + value.ub + '</td><td>' + value.du + '</td>' +
                                '<td>' +
                                '<a href="' + base_path + GlbCompanyFdr + 'mlogistics/addEditClragent/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">' +
                                '<i class="fa fa-edit"></i>&nbsp;Edit</a></td>';
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
                $('#mTableId').append(PageContent);
            }
        }
    }
}
function fnPaginationclearingAgent(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListRes);
}
function fnSaveInfo() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData = false;
        var CAgent = $("#frmBasicCAgent").val();
        var Status = $("#frmBasicStatus").val();
        if (jsTrim(CAgent) == "") {
            $('#ErrfrmBasicCAgent').text("Please fill the Clearing Agent");
            $('#frmBasicCAgent').focus();
            $('#frmBasicCAgent').css("border", "1px solid #B94A48");
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
            ProfileFormData.append("ca", CAgent);
            ProfileFormData.append("s", Status);
            ProfileFormData.append("id", GlbId);
        }
        $.ajax({
            url: base_path + GlbCompanyFdr + 'mlogistics/updateClrAgent',
            data: ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                data = JSON.parse(data);
                fnSaveInfoRes(data);
            }
        });
        return false;
    } catch (e) {
        alert(e);
    }
}
function fnSaveInfoRes(data) {
    console.log(data.msg, 'oo');
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
            $("#divSuccessBasicInfoMsg").text("Updated successfully");
            fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mlogistics/addEditClragent/' + data.eid);
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
$('#mTableId').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);
    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/clearingAgent', GlbSearchParam, 'json', fnListRes);
});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            alert("Select Clearing Agent");
        }
        if (checkBoxLength >= 1) {
            var companyid_json = JSON.stringify(cboxObj);
            if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
                GlbSearchParam = "rfrom=1&status=" + dropdownOpt + "&cid=" + companyid_json;
                MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/changeStatus', GlbSearchParam, 'json', fnChangeStatusRes);
            }
        }
    }
    else {
        alert('Select either ' + GlbStatusForMaster['1'] + ' or ' + GlbStatusForMaster['2']);
    }
});