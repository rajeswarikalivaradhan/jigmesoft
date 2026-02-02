var GlbSearchParam='';
var GlbSortOrder = '';
var GlbColumnId = '';
function fnShowHideEndUserSub(VarType,VarDivShow) {
    var ArrProfileBasicList = ["divEditBasicInfo","divShowBasicInfo"];
    if(VarType==1) {
        var ArrFnalList	= ArrProfileBasicList;
    }
    //Remove Class
    for(i=0;i<ArrFnalList.length;i++) {
        $("#"+ArrFnalList[i]).removeClass('show');
        $("#"+ArrFnalList[i]).removeClass('hide');
    }
    //Add Class
    for(i=0;i<ArrFnalList.length;i++) {
        if(VarDivShow!=ArrFnalList[i]) {
            $("#"+ArrFnalList[i]).addClass('hide');
        }
    }
    $("#"+VarDivShow).addClass('show');
}

function fnSearch() {
    var WiprefNo     					            = $("#frmSrchIsrior").val();
    var IsriorType     					            = $("#frmNameSearchreqTypeIorIsr").val();
    var BrandId     					        = $("#frmSrchBrandId").val();
    var StyleDesc     					            = $("#frmSrchStyleDesc").val();
    var EnqRefPono     					        = $("#frmSrchEnqRefPono").val();
    var StyleRef     					        = $("#frmSrchStyleRef").val();
    var Merchant     					        = $("#frmSrchMerchant").val();
    var SubShipFrmDate     					    = $("#frmSrchShipFrmDate").val();
    var SubShipToDate     					    = $("#frmSrchShipToDate").val();
    var CurrentStatus        					= $("#frmSrchCurrentStatus").val();

    GlbSearchParam							    = "rfrom=1&wiprefno="+WiprefNo+"&isriortype="+IsriorType+"&stydesc="+StyleDesc+"&bid="+BrandId+"&styrefno="
        +StyleRef+"&enqrefno="+EnqRefPono+"&merchantid="+Merchant+"&subFrmDate="+SubShipFrmDate+"&subToDate="+SubShipToDate+"&afilter="+
        GlbFilterAlpha+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;
    $("#DivTotalCntResult").html('');
    //$("#ResResult").text("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'workinprogress/managewip',GlbSearchParam,'json',fnListWorkInProgressRes);
}

function fnListWorkInProgress() {
    GlbSearchParam								= "rfrom=1";
    MakePostRequest(base_path+GlbCompanyFdr+'workinprogress/managewip',GlbSearchParam,'json',fnListWorkInProgressRes);
}

function fnListWorkInProgressRes(data) {
    console.log(data,'data');
    if(data!='') {
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent = ''; var griddatas = ''; var sumpoq = ''; var pcsorset = ''; var cstatus = '';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Record(s) : '+data.cn+'</div>';
                    if(data.ct>0) {
                        console.log(GlbUsertype,'GlbUsertype');
                        $.each(data.re,function (index,value) {
                            if(GlbUsertype == 3 || GlbUserType == 15) { // Note GlbUserType == 15 included in this files at GlbUserType == 3 places
                                griddatas = '<table>';
                                sumpoq = '<table>';
                                pcsorset = '<table>';
                                cstatus = '<table>';
                                $.each(value.griddatas,function (ind,val) {
                                    if(val!='') {
                                        griddatas = griddatas + '<tr><td><a href="' + base_path + GlbCompanyFdr + 'workinprogress/wipdetailpage/' +
                                            val.ids + '/' + encodeURIComponent(val.pono) + '/' + encodeURIComponent(val.poids) + '">' + val.pono +
                                            '</a></td></tr>';
                                        sumpoq = sumpoq + '<tr><td>' + val.sumpoq + '</td>';
                                        pcsorset = pcsorset + '<tr><td>' + value.pcsorset + '</td>';
                                    }
                                    else {
                                        griddatas = griddatas + '<tr><td>-</td></tr>';
                                        sumpoq = sumpoq + '<tr><td>-</td>';
                                        pcsorset = pcsorset + '<tr><td>-</td>';
                                    }
                                });
                                $.each(value.cs,function (i,v) {
                                    if(v == '') {
                                        cstatus = cstatus +'<tr><td>-</td>';
                                    }
                                    else {
                                        cstatus = cstatus +'<tr><td>'+v+'</td>';
                                    }
                                });
                                griddatas += '</table>';
                                sumpoq += '</table>';
                                pcsorset += '</table>';
                                cstatus += '</table>';
                                PageContent = PageContent + '<tr>' +
                                    '<td><input type="checkbox" id="'+value.id+'" class="allcbox"></td>'+
                                    '<td><a href="' + base_path + 'merchant/enquiryview/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.isriorno + '</a></td>' +
                                    '<td>' + value.date + '</td>' +
                                    '<td>' + value.bb + '</td>' +
                                    '<td>' + value.styref + '</td>' +
                                    '<td>' + value.oenqrefno + '</td>'+
                                    '<td>' + griddatas + '</td>' +
                                    '<td>'+sumpoq+'</td>' +
                                    '<td>' + pcsorset + '</td>' +
                                    '<td>' + value.shipdate + '</td>' +
                                    '<td>'+cstatus+'</td>' +
                                    '<td>' + value.reupd + '</td>'+
                                    '<td>'+value.s+'</td>';
                                PageContent = PageContent + '</tr>';
                            }
                            else if(GlbUsertype == 4) {
                                griddatas = '<table>';
                                sumpoq = '<table>';
                                pcsorset = '<table>';
                                cstatus = '<table>';
                                $.each(value.griddatas,function (ind,val) {
                                    if(val!='') {
                                        griddatas = griddatas + '<tr><td><a href="' + base_path + GlbCompanyFdr + 'workinprogress/wipdetailpage/' + val.ids + '/' + encodeURIComponent(val.pono) + '/' + encodeURIComponent(val.poids) + '">' + val.pono + '</a></td></tr>';
                                        sumpoq = sumpoq + '<tr><td>' + val.sumpoq + '</td>';
                                        pcsorset = pcsorset + '<tr><td>' + value.pcsorset + '</td>';
                                    }
                                    else {
                                        griddatas = griddatas + '<tr><td>-</td></tr>';
                                        sumpoq = sumpoq + '<tr><td>-</td>';
                                        pcsorset = pcsorset + '<tr><td>-</td>';
                                    }
                                });
                                $.each(value.cs,function (i,v) {
                                    if(v == '') {
                                        cstatus = cstatus +'<tr><td>-</td>';
                                    }
                                    else {
                                        cstatus = cstatus +'<tr><td>'+v+'</td>';
                                    }
                                });
                                griddatas += '</table>';
                                sumpoq += '</table>';
                                pcsorset += '</table>';
                                cstatus += '</table>';
                                PageContent = PageContent + '<tr>' +
                                    '<td><input type="checkbox" id="'+value.id+'" class="allcbox"></td>'+
                                    '<td><a href="' + base_path + 'merchant/enquiryview/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.isriorno + '</a></td>' +
                                    '<td>' + value.date + '</td>' +
                                    '<td><div class="dropdown">\n' +
                                    '                    <a href="#" class="dropbtn">' + value.bb + '</a>\n' +
                                    '                    <div class="dropdown-content">\n' +
                                    '                        <a href="' + base_path + 'orderentryvtwo/entry/' + encodeURIComponent(base64_encode(value.id)) + '" target="_blank">Order Entry</a>\n' +
                                    '                        <a href="' + base_path + 'fabricprogramvtwo/fabricdetail/' + encodeURIComponent(base64_encode(value.id)) + '" target="_blank">Fabric Program</a>\n' +
                                    '                        <a href="' + base_path + 'merchant/addcadrequest/' + encodeURIComponent(base64_encode(value.id)) + '" target="_blank">CAD Request</a>\n' +
                                    '                        <a href="' + base_path +'msamplerequest/addeditsamplerequest/' + encodeURIComponent(base64_encode(value.id)) + '" target="_blank">Sample Request</a>\n' +
                                    '                        <a href="' + base_path +'mpurchase/addeditbompurchase/' + encodeURIComponent(base64_encode(value.id)) + '" target="_blank">BOM Request</a>\n' +
                                    '                    </div>\n' +
                                    '                </div>' +
                                    '</td>' +
                                    '<td>' + value.styref + '</td>' +
                                    '<td>' + value.oenqrefno + '</td>'+
                                    '<td>' + griddatas + '</td>' +
                                    '<td>'+sumpoq+'</td>' +
                                    '<td>' + pcsorset + '</td>' +
                                    '<td>' + value.shipdate + '</td>' +
                                    '<td>'+cstatus+'</td>' +
                                    '<td>' + value.reupd + '</td>'+
                                    '<td>'+value.s+'</td>';
                                PageContent = PageContent + '</tr>';
                            }

                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                if(data.pa!=undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                $('tbody').empty();
                $('#workInProgressTbl').append(PageContent);
            }
        }
    }
}
function fnPaginationWip(VarURL) {
    $("#DivTotalCntResult").html('');
    MakePostRequest(VarURL,GlbSearchParam,'json',fnListWorkInProgressRes);
}
function fnDelete(Id) {
    if(confirm("Are you want to delete this yarn?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCompanyFdr+'workinprogress/delInfo',Parameters,'json',fnDeleteRes);
    }
}
function fnChangeStatusRes(data) {
    if(data!='') {
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnListWorkInProgress();
            }
        }
    }
}
    $('#workInProgressTbl').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];
        var Isrior     					        = $("#frmSrchIsrior").val();
        //var FromEnqDate     					        = $("#frmSrchFromEnqDate").val();
        //var ToEnqDate     					        = $("#frmSrchToEnqDate").val();
        var BrandId     					        = $("#frmSrchBrandId").val();
        var EnqRefPono     					        = $("#frmSrchEnqRefPono").val();
        var StyleRef     					        = $("#frmSrchStyleRef").val();
        var Merchant     					        = $("#frmSrchMerchant").val();
        var SubShipFrmDate     					        = $("#frmSrchShipFrmDate").val();
        var SubShipToDate     					        = $("#frmSrchShipToDate").val();
        var CurrentStatus        							= $("#frmSrchCurrentStatus").val();
        console.log(GlbSearchParam,'GlbSearchParam');
        GlbSearchParam							    = "rfrom=1&isrior="+Isrior+"&FromEnqDate="+FromEnqDate+"&ToEnqDate="+ToEnqDate+"&bid="+BrandId+"&styrefno="
            +StyleRef+"&enqrefno="+EnqRefPono+"&merchantid="+Merchant+"&subFrmDate="+SubShipFrmDate+"&subToDate="+SubShipToDate+"&afilter="+
            GlbFilterAlpha+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;

        MakePostRequest(base_path+GlbCompanyFdr+'workinprogress/managewip',GlbSearchParam,'json',fnListWorkInProgressRes);

    });
$('#btnChangeStatus').on('click',function () {
        var dropdownOpt                                 = $('#frmItemStatus').val();
        if(dropdownOpt > 0) {
            var SewTypeIdObject = commonCheckbox();
            var checkBoxLength = SewTypeIdObject[1];
            var cboxObj = SewTypeIdObject[0];
            $('#ErrItemStatus').text("");
            if(checkBoxLength == 0) {
                $('#ErrItemStatus').text("Choose a record");
            }
            if (checkBoxLength >= 1) {
                var StatusText                      = "Deactivate";
                if (dropdownOpt == '1') {
                    StatusText                      = "Activate";
                }
                if(confirm('Do you want to '+StatusText+' this record?')) {
                    GlbSearchParam							    = "rfrom=1&cs=" + dropdownOpt + "&id=" + JSON.stringify(cboxObj)+"&tblname=kn_order_enquiry";
                    MakeAsynPostRequest(base_path+'dashboard/changeAllListActiveStatus',GlbSearchParam,'json',fnChangeStatusRes);
                }
            }
        }
        else {
            $('#ErrItemStatus').text("Select an Option");
        }
    });
$('#frmSrchFromEnqDate').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true
});

$('#frmSrchToEnqDate').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true
});