var GlbSearchParam = '';
var GlbSortOrder = '';
var GlbColumnId = '';
let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});

function fnSearchAcceptanceLevel() {
    var ALevel = $("#frmSrchAl").val();
    var Status = $("#frmSrchStatus").val();
    GlbSearchParam = "rfrom=1&al=" + ALevel + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'macceptancelevel/manage', GlbSearchParam, 'json', fnacceptancelevelListRes);
}

function fnacceptancelevelList() {
    GlbSearchParam = "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'macceptancelevel/manage', GlbSearchParam, 'json', fnacceptancelevelListRes);
}

function fnacceptancelevelListRes(data) {
    console.log(data, 'd');
    
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent = '';
                if (data.cn > 0) {
                    
                 $('#mAcceptancelevelTbl').DataTable().destroy();
              
                    ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';
                    if (data.ct > 0) {
                        $.each(data.re, function (index, value) {
                            PageContent = PageContent + '<tr><td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td>' + value.al + '</td>' +
                                '<td>' + value.s + '</td><td>' + value.ub + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'macceptancelevel/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">View</a></td>';
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
                
                $('#mAcceptancelevelTbl tbody').empty();
                $('#mAcceptancelevelTbl').append(PageContent).DataTable();
                //"aaSorting": []
            }
        }
    }
}

function fnPaginationAcceptancelevel(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnacceptancelevelListRes);
}

function fnSaveAl() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData = false;
        var Acceptancelevel = $("#frmBasicAl").val();
        var Status = $("#frmBasicStatus").val();
        if (jsTrim(Acceptancelevel) == "") {
            $('#ErrfrmBasicAl').text("Enter Acceptance Level (%)");
            $('#frmBasicAl').focus();
            $('#frmBasicAl').css("border", "1px solid #B94A48");
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
            ProfileFormData.append("al", Acceptancelevel);
            ProfileFormData.append("s", Status);
            ProfileFormData.append("id", GlbId);
        }
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
                                        url: base_path + GlbCompanyFdr + 'macceptancelevel/updateInfo',
                                        data: ProfileFormData ? ProfileFormData : ObjForm.serialize(),
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        type: 'POST',
                                        success: function (data, textStatus, jqXHR) {
                                            data = JSON.parse(data);
                                            fnSaveCmsRes(data);
                                        }
                                    });
							    }
                        }); 

                return false;
    } catch (e) {
        alert(e);
    }
}

function fnSaveCmsRes(data) {
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
            // $("#divSuccessBasicInfoMsg").removeClass('hide');
            // $("#divSuccessBasicInfoMsg").text("Updated successfully!");
            // fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'macceptancelevel/addedit/' + data.eid);
            swalWithBootstrapButtons.fire({
                title: 'Saved!',text: data.message,type: 'success',
                icon: 'success',
                customClass: {'confirmButton': 'btn btn-info'}
            }).then((result) => {
                let redirectpath = base_path + GlbCompanyFdr + 'macceptancelevel/manage';
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
                fnacceptancelevelList();
            }
        }
    }
}

$('#mAcceptancelevelTbl').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);

    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'macceptancelevel/manage', GlbSearchParam, 'json', fnacceptancelevelListRes);

});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            //alert('Select Acceptance level');
            swalWithBootstrapButtons.fire({
                title: 'Select a record!',
                type: 'error',
                icon: 'error',
                width:460,
                customClass: {'confirmButton': 'btn btn-info'}
            });
        }
        if (checkBoxLength >= 1) {
            var companyid_json = JSON.stringify(cboxObj);
            var frmBasicBomName = $("#frmSrchBomName").val();
            var Status = $("#frmSrchBomStatus").val();
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
    						     GlbSearchParam = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                                 MakeAsynPostRequest(base_path + GlbCompanyFdr + 'macceptancelevel/changeStatus', GlbSearchParam, 'json', fnChangeStatusRes);
    						}
                            }); 
    						
            // if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
            //     GlbSearchParam = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
            //     MakeAsynPostRequest(base_path + GlbCompanyFdr + 'macceptancelevel/changeStatus', GlbSearchParam, 'json', fnChangeStatusRes);
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
    //$("#custom_form textarea").prop("disabled", false);
   // $("#frmBasicMnote").prop("disabled", false);
});