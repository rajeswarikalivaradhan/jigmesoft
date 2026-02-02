GlbColumnId = '';
GlbSortOrder = '';
function fnSearch() {
    var Imp = $("#frmSrchImp").val();
    var Status = $("#frmSrchStatus").val();
    GlbSearchParam = "rfrom=1&imp=" + Imp + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/importer', GlbSearchParam, 'json', fnListRes);
}
function fnList() {
    GlbSearchParam = "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/importer', GlbSearchParam, 'json', fnListRes);
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
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mlogistics/addEditImporter/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.imp + '</a>' +
                                '</td>' +
                                '<td>' + value.s + '</td><td>' + value.ub + '</td><td>' + value.du + '</td>' +
                                '<td>' +
                                '<a href="' + base_path + GlbCompanyFdr + 'mlogistics/addEditImporter/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">' +
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
function fnPaginationImporter(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListRes);
}
function fnSaveInfo() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData = false;
        var Importer = $("#frmBasicImp").val();
        var Status = $("#frmBasicStatus").val();
        if (jsTrim(Importer) == "") {
            $('#ErrfrmBasicImp').text("Please fill the Importer");
            $('#frmBasicImp').focus();
            $('#frmBasicImp').css("border", "1px solid #B94A48");
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
            ProfileFormData.append("imp", Importer);
            ProfileFormData.append("s", Status);
            ProfileFormData.append("id", GlbId);
        }
        $.ajax({
            url: base_path + GlbCompanyFdr + 'mlogistics/updateImporter',
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
            fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mlogistics/addEditImporter/' + data.eid);
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
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/importer', GlbSearchParam, 'json', fnListRes);
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