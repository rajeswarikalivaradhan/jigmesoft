var GlbSearchParam = '';
var GlbSortOrder = '';
var GlbColumnId = '';
function fnSearchTestingAuth() {
    var TestingAuth = $("#frmSrchAuths").val();
    var Status = $("#frmSrchStatus").val();
    GlbSearchParam = "rfrom=1&ta=" + TestingAuth + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mauth/managetauth', GlbSearchParam, 'json', fnTestingListRes);
}
function fnTestingList() {
    GlbSearchParam = "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mauth/managetauth', GlbSearchParam, 'json', fnTestingListRes);
}
function fnTestingListRes(data) {
    console.log(data, 'd');
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
                            console.log();
                            PageContent = PageContent + '<tr>' +
                                '<td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td>' +
                                '<a href="' + base_path + GlbCompanyFdr + 'mauth/addedittestingauth/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.i +
                                '</a>' +
                                '</td>' +
                                '<td>' + value.s + '</td><td>' + value.ub + '</td><td>' + value.du + '</td>' +
                                '<td>' +
                                '<a href="' + base_path + GlbCompanyFdr + 'mauth/addedittestingauth/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">' +
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
                $('#mTableId').append(PageContent);
            }
        }
    }
}
function fnPaginationTestingauth(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnTestingListRes);
}

function fnSaveTAuth() {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData = false;
        let frmBasicTestingA = $("#frmBasicTestingA").val();
        let name = $("#frmBasicName").val();
        let email = $("#frmBasicEmail").val();
        let phone = $("#frmBasicPhoneNo").val();
        let mobile = $("#frmBasicMobileNo").val();
        let gst = $("#frmBasicGstNo").val();
        let addr = $("#frmBasicAddress").val();
        let Status = $("#frmBasicStatus").val();
        if (jsTrim(frmBasicTestingA) == "") {
            $('#ErrTestingA').text("Please fill the Testing Authority");
            $('#frmBasicTestingA').focus();
            $('#frmBasicTestingA').css("border", "1px solid #B94A48");
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
            ProfileFormData.append("ta", frmBasicTestingA);
            ProfileFormData.append("n", name);
            ProfileFormData.append("e", email);
            ProfileFormData.append("p", phone);
            ProfileFormData.append("m", mobile);
            ProfileFormData.append("a", addr);
            ProfileFormData.append("g", gst);
            ProfileFormData.append("s", Status);
            ProfileFormData.append("id", GlbId);
        }
        console.log(ProfileFormData,'ProfileFormData');
    $.ajax({
        url: base_path + GlbCompanyFdr + 'mauth/updatetestingauthInfo',
        data: ProfileFormData,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data, textStatus, jqXHR) {
            data = JSON.parse(data);
            fnSaveTAuthRes(data);
        }
    });
        /*MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mauth/updatetestingauthInfo',ProfileFormData,"json",function (data) {
            console.log(data,'data');
            fnSaveTAuthRes(data);
        });*/
        return false;

}
function fnSaveTAuthRes(data) {
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
            //fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mauth/addedittestingauth/' + data.eid);
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
                fnSearchTestingAuth();
            }
        }
    }
}
$('#mTableId').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);
    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mauth/managetauth', GlbSearchParam, 'json', fnTestingListRes);
});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            alert('Select Approval Authority');
        }
        if (checkBoxLength >= 1) {
            var companyid_json = JSON.stringify(cboxObj);
            var frmBasicBomName = $("#frmSrchBomName").val();
            var Status = $("#frmSrchBomStatus").val();
            if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
                GlbSearchParam = "rfrom=1&actDeact=" + dropdownOpt + "&cid=" + companyid_json;
                MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mauth/changeStatus', GlbSearchParam, 'json', fnChangeStatusRes);
            }
        }
    }
    else {
        alert('Select either ' + GlbStatusForMaster['1'] + ' or ' + GlbStatusForMaster['2']);
    }
});