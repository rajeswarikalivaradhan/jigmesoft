let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
function fnSave() {
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var Blend = $("#frmBasicBlend").val();
    var Status = $("#frmBasicStatus").val();

    if (jsTrim(Blend) == "") {
        // $('#ErrfrmBasicBlend').text("Please fill yarn Blend");
        $('#ErrfrmBasicBlend').text("Enter BOM (Art-1) - Material");
        $('#frmBasicBlend').focus();
        $('#frmBasicBlend').css("border", "1px solid #B94A48");
        return false;
    }
    if (Status == "") {
        $('#ErrBasicStatus').text("Select Status");
        $('#frmBasicStatus').focus();
        $('#frmBasicStatus').css("border", "1px solid #B94A48");
        return false;
    }
   // console.log(Blend, 'Blend');
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
                            MakePostRequest(base_path + GlbCompanyFdr + "bom1/Bommaterial/updateInfo", 
                            "rfrom=1&b=" + encodeURIComponent(Blend) + "&s=" + Status + "&id=" + GlbId, "json", function (data) {
                            console.log(data, 'data');
                            if (data != '') {
                                if (data.errcode == '404') {
                                    fnCallSessionExpire();
                                    return false;
                                } else if (data.errcode == -1) {
                                    // $('#AnyErrElse').text(data.msg);
                                    swalWithBootstrapButtons.fire({
                                            title: data.msg,type: 'warning',
                                            icon: 'warning',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                    });
                                    return false;
                                } else if (data.errcode == 1) {
                                    //console.log(data,'data');
                                    GlbId = data.id;
                                    // $("#divSuccessBasicInfoMsg").removeClass('hide');
                                    // $("#divSuccessBasicInfoMsg").text("Updated successfully");
                                    // fnRedirectPageTimeOut(base_path + GlbCompanyFdr + 'bom1/Bommaterial/addedit/' + data.eid);
                                        
                                        swalWithBootstrapButtons.fire({
                                                title: 'Saved!',text: data.message,type: 'success',
                                                icon: 'success',
                                                customClass: {'confirmButton': 'btn btn-info'}
                                            }).then((result) => {
                                                let redirectpath = base_path + GlbCompanyFdr + 'bom1/Bommaterial/manage';
                                                window.location.href = redirectpath;
                                            });
                                }
                            }
                            });
        				}
                    }); 
}

function fnList() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = "rfrom=1";
    MakePostRequest(base_path + GlbCompanyFdr + 'bom1/Bommaterial/manage/', GlbSearchParam, 'json', fnListRes);
}

function fnListRes(data) {
    console.log(data.re, 'data');
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent = '';
                if (data.cn > 0) {
                    console.log(data, 'data');
                    ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';
                    if (data.ct > 0) {
                        $.each(data.re, function (index, value) {
                            PageContent = PageContent + '<tr>' +
                                '<td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td>' + value.b + '</td>' +
                                '<td>' + value.s + '</td>' +
                                '<td>' + value.ub + '</td>' +
                                '<td>' + value.du + '</td>' +
                                '<td><a href="' + base_path + GlbCompanyFdr + 'bom1/Bommaterial/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">View</a></td>';
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
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                // $('tbody').empty();
                // $('#tableId').append(PageContent);
                $('#tableId tbody').empty();
                $('#tableId').append(PageContent).DataTable();
            }
        }
    }
}


var GlbSortOrder = '';
var GlbColumnId = '';

function fnSearch() {
    var frmSrchBlend = $("#frmSrchBlend").val();
    var Status = $("#frmSrchStatus").val();
    GlbSearchParam = "rfrom=1&b=" + frmSrchBlend + "&s=" + Status;
    MakePostRequest(base_path + GlbCompanyFdr + 'bom1/Bommaterial/manage/', GlbSearchParam, 'json', fnListRes);
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

$('#tableId').on('click', 'th.sortable', function () {
    var ReturnVal = commonTableSorting(this);

    GlbSortOrder = ReturnVal[1];
    GlbColumnId = ReturnVal[0];
    var Param = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    MakePostRequest(base_path + GlbCompanyFdr + 'bom1/Bommaterial/manage/', Param, 'json', fnListRes);

});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            // alert("Select Yarn Blend");
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
                                 var Param = "rfrom=1&type=" + dropdownOpt + "&cid=" + companyid_json;
                                 MakeAsynPostRequest(base_path + GlbCompanyFdr + 'bom1/Bommaterial/changemStatus', Param, 'json', fnChangeStatusRes);
    					    }
                            });
            
            // if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
            //     var Param = "rfrom=1&type=" + dropdownOpt + "&cid=" + companyid_json;
            //     MakeAsynPostRequest(base_path + GlbCompanyFdr + 'bom1/Bommaterial/changemStatus', Param, 'json', fnChangeStatusRes);
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

function fnPaginationYarnBlend(VarURL) {
    $("#DivTotalCntResult").text('');
    GlbSearchParam = "rfrom=1";
    MakeAsynPostRequest(VarURL, GlbSearchParam, 'json', fnListRes);
}

$('#editEnable').on('click', function() {
    $("#custom_form input").prop("disabled", false);
    $("#custom_form select").prop("disabled", false);
});