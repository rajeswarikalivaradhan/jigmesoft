let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
if (sessionStorage.getItem('keepSearchOpen') === 'true') {
        $('.search_area').removeClass('hide'); // show search div
        $('.fa-search-plus').removeClass('fa-search-plus').addClass('fa-search');
        sessionStorage.removeItem('keepSearchOpen'); // clear flag
    }
function fnSave() {
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').text('');
    var Descp = $("#frmDescp").val();
    var Status = $("#frmBasicStatus").val();

    if (jsTrim(Descp) == "") {
        $('#ErrfrmDescp').text("Enter Unit Rate (Rs)");
        $('#frmDescp').focus();
        $('#frmDescp').css("border", "1px solid #B94A48");
        return false;
    }
    if (Status == "") {
        $('#ErrBasicStatus').text("Select Status");
        $('#frmBasicStatus').focus();
        $('#frmBasicStatus').css("border", "1px solid #B94A48");
        return false;
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
    MakePostRequest(base_path + GlbBAdminFdr + "munitrate/updateInfo", "rfrom=1&d=" + encodeURIComponent(Descp) + "&s=" + Status + "&id=" + GlbId, "json", function (data) {
        // console.log(data, 'data');
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
                GlbId = data.id;
                swalWithBootstrapButtons.fire({
                            title: 'Saved!',text: data.message,type: 'success',
                            icon: 'success',
                            customClass: {'confirmButton': 'btn btn-info'}
                }).then((result) => {
                            let redirectpath = base_path + GlbBAdminFdr + 'munitrate/manage';
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
    MakePostRequest(base_path + GlbBAdminFdr + 'munitrate/manage/', GlbSearchParam, 'json', fnListRes);
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
                    console.log(data, 'data');
                    ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';
                    if (data.ct > 0) {
                        $.each(data.re, function (index, value) {
                            PageContent = PageContent + '<tr>' +
                                '<td><input type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                '<td>' + value.d + '</td>' +
                               
                                '<td>' + value.ub + '</td>' +
                                '<td>' + value.du + '</td>' +
                                 '<td>' + value.s + '</td>' +
                                '<td><a href="' + base_path + GlbBAdminFdr + 'munitrate/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '">View</a></td>';
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
                 $('#tableId tbody').empty();
                 $('#tableId').append(PageContent).DataTable();
            }
        }
    }
}


var GlbSortOrder = '';
var GlbColumnId = '';

// function fnSearch() {
//     var frmSrchDescp = $("#frmSrchDescp").val();
//     var Status = $("#frmSrchStatus").val();
//     GlbSearchParam = "rfrom=1&d=" + frmSrchDescp + "&s=" + Status;
//     MakePostRequest(base_path + GlbBAdminFdr + 'munitrate/manage/', GlbSearchParam, 'json', fnListRes);
// }
$('#btn-active').on('click', function () {
     $('#btn-active').addClass('btn-selected');
    $('#btn-inactive').removeClass('btn-selected');
  
    var Status1 = 'active';

    var table = $('#tableId').DataTable();

    // Column 5: prefix search using regex
    if (Status1) { // only if a value is selected
        table.column(4).search('^' + Status1, true, false); // true = regex, false = smart
    } else {
        table.column(4).search(''); // reset
    }

    table.draw();
   
   
    
 });
 $('#btn-inactive').on('click', function () {
     $('#btn-inactive').addClass('btn-selected');
    $('#btn-active').removeClass('btn-selected');

    var Status1 = 'Inactive';

    var table = $('#tableId').DataTable();

    // Column 5: prefix search using regex
    if (Status1) { // only if a value is selected
        table.column(4).search('^' + Status1, true, false); // true = regex, false = smart
    } else {
        table.column(4).search(''); // reset
    }

    table.draw();
   
 });
 $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    var min = parseFloat($('#frmSrchmin').val());
    var max = parseFloat($('#frmSrchmax').val());

    if (isNaN(min)) min = -Infinity;
    if (isNaN(max)) max = Infinity;

    // Get the value from Unit Rate column (column 1)
    var unitRateText = data[1] || "";

    // Remove commas and trim
    var unitRate = parseFloat(unitRateText.replace(/,/g, '').trim());

    if (isNaN(unitRate)) unitRate = 0;

    return unitRate >= min && unitRate <= max;
});
function fnSearch() {
    //var frmSrchDescp = $("#frmSrchDescp").val();
    var Status = $("#frmSrchStatus").val();
  

    var table1 = $('#tableId').DataTable();

    // Columns 1–4: normal substring search (no regex)
    //table1.column(1).search(frmSrchDescp, false, true); // false = regex, true = smart
    

    // Column 5: prefix search using regex
    if (Status) { // only if a value is selected
        table1.column(4).search('^' + Status, true, false); // true = regex, false = smart
    } else {
        table1.column(4).search(''); // reset
    }

    table1.draw();
}

   $('#refreshBtn').on('click', function () {
     sessionStorage.setItem('keepSearchOpen', 'true'); // remember user preference
    location.reload(); // reload the page
	});
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
    MakePostRequest(base_path + GlbBAdminFdr + 'munitrate/manage/', Param, 'json', fnListRes);

});
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
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
                   MakeAsynPostRequest(base_path + GlbBAdminFdr + 'munitrate/changemStatus', Param, 'json', fnChangeStatusRes);
    			}
                if (result.value) {
                    location.reload();
    			   //var Param = "rfrom=1&type=" + dropdownOpt + "&cid=" + companyid_json;
                   //MakeAsynPostRequest(base_path + GlbBAdminFdr + 'munitrate/changemStatus', Param, 'json', fnChangeStatusRes);
    			}
            });
        }
    }
    else {
         swalWithBootstrapButtons.fire({
                title: 'Select a option!',
                type: 'error',
                icon: 'error',
                width:460,
                customClass: {'confirmButton': 'btn btn-info'}
        });
    }
});

function fnPagination(VarURL) {
    $("#DivTotalCntResult").text('');
    GlbSearchParam = "rfrom=1";
    MakeAsynPostRequest(VarURL, GlbSearchParam, 'json', fnListRes);
}

$('#editEnable').on('click', function() {
    $("#custom_form input").prop("disabled", false);
    $("#custom_form select").prop("disabled", false);
});

function validateFloatKeyPress(el) {
        var v = parseFloat(el.value);
        el.value = (isNaN(v)) ? '' : v.toFixed(2);
    }