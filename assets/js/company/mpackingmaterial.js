var GlbSearchParam = '';
var GlbSortOrder = '';
var GlbColumnId = '';
function fnSearchPackingMaterial() {
    var PackingMaterial = $("#frmSrchPackingMaterialName").val();
    var Status = $("#frmSrchPackingMaterialStatus").val();
    GlbSearchParam = "rfrom=1&pn=" + PackingMaterial + "&s=" + Status;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mpackingmaterial/managepackingmaterial', GlbSearchParam, 'json', fnListPackingMaterialRes);
}
function fnListPackingMaterial() {
    GlbSearchParam = "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mpackingmaterial/managepackingmaterial', GlbSearchParam, 'json', fnListPackingMaterialRes);
}
function fnListPackingMaterialRes(data) {
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
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mpackingmaterial/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/">' + value.n + '</a>' +
                                '</td><td>' + value.s + '</td><td>' + value.ub + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td>' +
                                '<a href="' + base_path + GlbCompanyFdr + 'mpackingmaterial/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">' +
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
                $("tbody").empty();
                $("#packingmaterialtableList").append(PageContent);
            }
        }
    }
}
function fnPaginationPackingMaterial(VarURL) {
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, GlbSearchParam, 'json', fnListPackingMaterialRes);
}
function fnSavePackingMaterialInfo() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').html('');
        var ProfileFormData = false;
        var PackingMaterial = $("#frmBasicPackingMaterial").val();
        var Status = $("#frmBasicStatus").val();
        if (jsTrim(PackingMaterial) == "") {
            $('#ErrBasicPackingMaterial').text("Please fill the packing material");
            $('#frmBasicPackingMaterial').focus();
            $('#frmBasicPackingMaterial').css("border", "1px solid #B94A48");
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
            ProfileFormData.append("pn", PackingMaterial);
            ProfileFormData.append("s", Status);
            ProfileFormData.append("id", GlbId);
        }
        $.ajax({
            url: base_path + GlbCompanyFdr + 'mpackingmaterial/updatePackingMaterialInfo',
            data: ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                data = jQuery.parseJSON(data);
                fnSavePackingMaterialRes(data);
            }
        });
        return false;
    } catch (e) {
        alert(e);
    }
}
function fnSavePackingMaterialRes(data) {
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
            fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mpackingmaterial/addedit/' + data.eid);
        }
    }
}
$(document).ready(function () {
    $('#packingmaterialtableList').on('click', 'th.sortable', function () {
        var ReturnVal = commonTableSorting(this);
        GlbSortOrder = ReturnVal[1];
        GlbColumnId = ReturnVal[0];
        var Parameters = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mpackingmaterial/managepackingmaterial', Parameters, 'json', fnListPackingMaterialRes);
    });
});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            alert("Select Packing Material");
        }
        if (checkBoxLength >= 1) {
            var companyid_json = JSON.stringify(cboxObj);
            if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
                GlbSearchParam = "actDeact=" + dropdownOpt + "&cid=" + companyid_json;
                MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mpackingmaterial/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
            }
        }
    }
    else {
        alert('Select either ' + GlbStatusForMaster['1'] + ' or ' + GlbStatusForMaster['2']);
    }
});
function fnChangeStatusRes(data) {
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchPackingMaterial();
            }
        }
    }
}