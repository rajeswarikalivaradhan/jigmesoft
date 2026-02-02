let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
var GlbSearchParam = '';
var GlbSortOrder = '';
var GlbColumnId = '';
function fnSearchDSR() {
    var DSR = $("#frmSrchDSR").val();
    var Status = $("#frmSrchDSRStatus").val();
    GlbFilterAlpha = $("#hiddenAlpha").val();
    GlbSearchParam = "rfrom=1&dn=" + DSR + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mdsr/managedsr', GlbSearchParam, 'json', fnListDSRRes);
}
function fnListDSR() {
    GlbSearchParam = "rfrom=1";
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mdsr/managedsr', GlbSearchParam, 'json', fnListDSRRes);
}
function fnListDSRRes(data) {
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
                                '<td>' + value.n + '</td>' +
                                '<td>' + value.s + '</td><td>' + value.ub + '</td><td>' + value.du + '</td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'mdsr/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">View</a></td>';
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
                // $('tbody').empty();
                // $('#tableList').append(PageContent);
                $('#tableId tbody').empty();
                $('#tableId').append(PageContent).DataTable();
            }
        }
    }
}
function fnPaginationDSR(VarURL) {
    var Parameters = GlbSearchParam;
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListDSRRes);
}
function fnSaveDSRInfo() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData = false;
        var DSR = $("#frmBasicDSR").val();
        var Status = $("#frmBasicStatus").val();
        if (jsTrim(DSR) == "") {
            $('#ErrBasicDSR').text("Enter Dyeing Special Request");
            $('#frmBasicDSR').focus();
            $('#frmBasicDSR').css("border", "1px solid #B94A48");
            return false;
        }
        if (jsTrim(Status) == "") {
            $('#ErrBasicStatus').text("Select Status");
            $('#frmBasicStatus').focus();
            $('#frmBasicStatus').css("border", "1px solid #B94A48");
            return false;
        }
        if (window.FormData) {
            ProfileFormData = new FormData();
            ProfileFormData.append("dn", DSR);
            ProfileFormData.append("s", Status);
            ProfileFormData.append("id", GlbId);
        }
        swalWithBootstrapButtons.fire(
            {
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
                        url: base_path + GlbCompanyFdr + 'mdsr/updateDSRInfo',
                        data: ProfileFormData ? ProfileFormData : ObjForm.serialize(),
                        cache: false,
                        contentType: false,
                        processData: false,
                        type: 'POST',
                        success: function (data, textStatus, jqXHR) {
                            data = jQuery.parseJSON(data);
                            fnSaveDSRRes(data);
                        }
                    });
				}
            }); 
        return false;
    } catch (e) {
        alert(e);
    }
}
function fnSaveDSRRes(data) {
    if (data != '') {
        if (data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if (data.errcode == -1) {
            //$('#AnyOtherErr').text(data.msg);
            swalWithBootstrapButtons.fire({
                title: data.msg,type: 'warning',
                icon: 'warning',
                customClass: {'confirmButton': 'btn btn-info'}
            });
            return false;
        } else if (data.errcode == 1) {
            GlbId = data.id;
            
            swalWithBootstrapButtons.fire({
                title: 'Saved!',text: data.message,type: 'success',
                icon: 'success',
                customClass: {'confirmButton': 'btn btn-info'}
            }).then((result) => {
                let redirectpath = base_path + GlbCompanyFdr + 'mdsr/managedsr';
                window.location.href = redirectpath;
            });
            
            // $("#divSuccessBasicInfoMsg").removeClass('hide');
            // $("#divSuccessBasicInfoMsg").text("Updated successfully");
            // fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'mdsr/addedit/' + data.eid);
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
                fnSearchDSR();
            }
        }
    }
}
$(document).ready(function () {
    $('#tableList').on('click', 'th.sortable', function () {
        var ReturnVal = commonTableSorting(this);
        GlbSortOrder = ReturnVal[1];
        GlbColumnId = ReturnVal[0];
        var DSR = $("#frmSrchDSR").val();
        var Status = $("#frmSrchDSRStatus").val();
        GlbSearchParam = "rfrom=1&dn=" + DSR + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mdsr/managedsr', GlbSearchParam, 'json', fnListDSRRes);
    });
});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            //alert("Select Dyeing special request");
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
                    GlbSearchParam = "actDeact=" + dropdownOpt + "&cid=" + idJson;
                    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mdsr/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
				}
                });
                
            // if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
            //     GlbSearchParam = "actDeact=" + dropdownOpt + "&cid=" + idJson;
            //     MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mdsr/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
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
$('#editEnable').on('click', function() {
    $("#custom_form input").prop("disabled", false);
    $("#custom_form select").prop("disabled", false);
});