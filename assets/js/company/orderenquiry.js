var extraObj; var GlbLogId = '';
$('#frmBasicEnquiryDate').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

$(document).ready(function() {
    extraObj     = $("#uploadBusinssImg").uploadFile({
        dragDrop: true,
        multiple:true,
        url:base_path+GlbCompanyFdr+'menquiry/fnUploadAttachment',
        fileName:"bimage",
        returnType: "json",
        fileName:"myfile",
        dynamicFormData:function () {
            var test = {'enq':GlbInsertId};
            return test;
        },
        autoSubmit:false
    });

    if(GlbOrderstatus == 1 || GlbOrderstatus == 4) {
        $(".form-control").attr('readonly',true);
        $("#frmBasicCountry").attr('disabled',true);
        $("#frmBasicCurrency").attr('disabled',true);
        $("#frmBasicPs").attr('disabled',true);
        $("#frmBasicBB").attr('disabled',true);
        $("#frmBasicEType").attr('disabled',true);
        $("#frmBasicRType").attr('disabled',true);
        //$("#frmBasicMerchant").attr('disabled',true);
        $("#frmBasicEnquiryDate").attr('disabled',true);
        $("#frmBasicME").attr('disabled',true);
    }
});

function array_search( name,arr ) {
    for(var i = 0, len = arr.length; i < len; i++) {
        if( arr[ i ].key === name )
            return true;
    }
    return false;
}

$("#divLog").hide();
$("#logdetaildiv").hide();
function fnShowEnqInfo() {
    $("#basicInfoCircle").removeClass('fa fa-circle-o');
    $("#basicInfoCircle").addClass('fa fa-circle');

    $("#logcircle").removeClass('fa fa-circle');
    $("#logcircle").addClass('fa fa-circle-o');
    $("#divLog").hide();
    $("#logdetaildiv").hide();
    $("#divBasicInfo").show();

    $("#divCurrentStatus").hide();
    $("#divNewStatus").removeClass('hide');
    //$("#divNewStatus").css('background-color','orange');
    //$("#divNewStatus").text('Pending-RR');

}

function fnShowEnqLog(showdivid,hidedivid) {
    $("#logcircle").removeClass('fa fa-circle-o');
    $("#logcircle").addClass('fa fa-circle');
    $("#basicInfoCircle").removeClass('fa fa-circle');
    $("#basicInfoCircle").addClass('fa fa-circle-o');
    $("#"+showdivid).show();
    $("#"+hidedivid).hide();
    $("#logdetaildiv").hide();
    fnLogList();
}

function fnReSendEnq() {
    if (confirm("To confirm click OK, else CANCEL")) {
        try {
            var StyDesc     = $("#frmBasicStyleDesc").val();
            var StyleRefNo  = $("#frmBasicStyleRefNo").val();
            var EnquiryDate = $("#frmBasicEnquiryDate").val();
            var frmBasicEType = $("#frmBasicEType").val();
            var frmBasicME = $("#frmBasicME").val();
            var frmBasicBB = $("#frmBasicBB").val();
            var frmBasicPs = $("#frmBasicPs").val();
            var frmBasicMnote = $("#frmBasicMnote").val();
            var frmBasicCountry = $("#frmBasicCountry").val();
            var frmBasicQprice = $("#frmBasicQprice").val();
            var frmBasicBprice = $("#frmBasicBprice").val();
            var frmBasicCprice = $("#frmBasicCprice").val();
            var frmBasicCurrency = $("#frmBasicCurrency").val();
            var frmBasicPq = $("#frmBasicPq").val();
            //var frmBasicMerchant = $("#frmBasicMerchant").val();
            var frmBasicRType = $("#frmBasicRType").val();
            var frmBasicISRany = $("#frmBasicISRany").val();

            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').text('');
            if(jsTrim(StyDesc) == "") {
                $('#ErrfrmBasicStyleDesc').text("Enter Style Description");
                $('#frmBasicStyleDesc').focus();
                $('#frmBasicStyleDesc').css("border", "1px solid #B94A48");
                return false;
            }
            if(jsTrim(frmBasicMnote) == "") {
                $('#ErrfrmBasicMnote').text("Enter Merchant Note");
                $('#frmBasicMnote').focus();
                $('#frmBasicMnote').css("border", "1px solid #B94A48");
                return false;
            }
            if(jsTrim(frmBasicQprice) == "") {
                $('#ErrfrmBasicQprice').text("Enter Quoted Price");
                $('#frmBasicQprice').focus();
                $('#frmBasicQprice').css("border", "1px solid #B94A48");
                return false;
            }
            if(jsTrim(frmBasicBprice) == "") {
                $('#ErrfrmBasicBprice').text("Enter Buyer's Price");
                $('#frmBasicBprice').focus();
                $('#frmBasicBprice').css("border", "1px solid #B94A48");
                return false;
            }
            if(jsTrim(frmBasicCprice) == "") {
                $('#ErrfrmBasicCprice').text("Enter Confirmed Price");
                $('#frmBasicCprice').focus();
                $('#frmBasicCprice').css("border", "1px solid #B94A48");
                return false;
            }
            if(frmBasicCurrency == "") {
                $('#ErrfrmBasicCurrency').text("Select Currency");
                $('#frmBasicCurrency').focus();
                $('#frmBasicCurrency').css("border", "1px solid #B94A48");
                return false;
            }
            var Parameters = "rfrom=1&sd="+StyDesc+"&styref="+StyleRefNo+"&enqdt="+EnquiryDate+"&enquiryid="+GlbEnqId+"&resend=1&os=4&et="+frmBasicEType+"&me="+frmBasicME+"&bb="+frmBasicBB+"&ps="+frmBasicPs+"&mt="+frmBasicMnote+"&conty="+frmBasicCountry+"&qp="+frmBasicQprice+"&bp="+frmBasicBprice+"&cp="+frmBasicCprice+"&crncy="+frmBasicCurrency+"&proq="+frmBasicPq+"&rt="+frmBasicRType+"&israny="+frmBasicISRany;
            MakePostRequest(base_path+'merchant/updateenquiry',Parameters,'json',fnSaveEnquiryRes);
            return false;
        } catch(e) {
            alert(e);
        }
    }
    else {
        return false;
    }
}

function fnSaveEnquiryRes(data) {
    if(data!='') {
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){

            return false;
        } else if(data.errcode==1) {
            //console.log(data);
            GlbInsertId = data.id;
            extraObj.startUpload();

            $("#recentupdate").hide();
            $("#recentupdateCs").removeClass('hide');
            $("#recentupdateCs").text(data.dupdated);

            $("#frmReqDateTimeCs").removeClass('hide');
            $("#frmReqDateTimeCs").text(data.dupdated);
            $("#frmReqDateTime").hide();

            $("#resendbtn").hide();
            $("#divCurrentStatus").hide();
            $("#divNewStatus").removeClass('hide');
            $("#divNewStatus").css('background-color','orange');
            $("#divNewStatus").text('Pending-RR');
        }
    }
}

function fnLogList() {
    MakePostRequest(base_path + GlbCompanyFdr + 'menquiry/fnLogList',"rfrom=1&enquiryid="+GlbEnqId,'json',fnListEnqLogRes);
}

function fnListEnqLogRes(data) {
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent='';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Record(s) : '+data.cn+'</div>';
                    if(data.ct>0) {
                        $.each(data.re,function(index,value){
                            PageContent=PageContent+'<tr><td>'+value.sd+'</td>' +
                                '<td>'+value.as+'</td>'+
                                '<td>'+value.comm+'</td>'+
                                '<td><a href="javascript:void(0);" onclick="fnEnqDetail(this)" id="'+value.id+'" onclick="">View</a></td>';
                            PageContent=PageContent+'</tr>';
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
                $("tbody").empty();
                $("#tableLogList").append(PageContent);
            }
        }
    }
}

function fnEnqDetail(thisobj) {
    $("#divBasicInfo").hide();
    $("#divLog").hide();
    $("#logdetaildiv").show();
    $("#logcircle").removeClass('fa fa-circle');
    $("#logcircle").addClass('fa fa-circle-o');
    GlbLogId = thisobj.id;
    MakePostRequest(base_path+GlbCompanyFdr+'menquiry/enquirylogdetail',"rfrom=1&lid="+GlbLogId,'json',fnEnqLogDetailRes);
}


function fnEnqLogDetailRes(detaildata) {
    //if(detaildata.errcode == 1)
    if(detaildata!='') {
        if(detaildata.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(detaildata.errcode=='-1') {
            //alert('Err');
            return false;
        } else if(detaildata.errcode==1) {
            $("#lgstyleref").text(detaildata.logdetaildata.styleref);
            $("#lgstyledesc").text(detaildata.logdetaildata.styledesc);
            $("#lgmcomm").text(detaildata.logdetaildata.mcomm);
            $("#lgenqdt").text(detaildata.logdetaildata.enqdt);
            $("#lgenqtype").text(detaildata.logdetaildata.enqtype);
            $("#lgme").text(detaildata.logdetaildata.enqmode);
            $("#lgbb").text(detaildata.logdetaildata.bb);
            $("#lgconty").text(detaildata.logdetaildata.conty);
            $("#lgisrior").text(detaildata.logdetaildata.isrior);
            $("#lgps").text(detaildata.logdetaildata.pcsset);
            $("#lgqp").text(detaildata.logdetaildata.qp);
            $("#lgbp").text(detaildata.logdetaildata.bp);
            $("#lgcp").text(detaildata.logdetaildata.cp);
            $("#lgpqty").text(detaildata.logdetaildata.pqty);
            $("#lgcurrency").text(detaildata.logdetaildata.currency);
            $("#lgmnote").text(detaildata.logdetaildata.mnote);
            $("#lgmerc").text(detaildata.logdetaildata.mname);

            $("#lgreqdt").text(detaildata.logdetaildata.lgreqdt);

            $("#lgreupdate").text(detaildata.logdetaildata.lgreupdate);
            if(detaildata.logdetaildata.orstatus == '1') {
                $("#divLogDetailPendingCs").addClass('alert alert-warning alert-dismissable');
                $("#divLogDetailPendingCs").text(detaildata.logdetaildata.VarCurrentStatus);
            }
            if(detaildata.logdetaildata.orstatus == '2') {
                $("#divLogDetailApprovedCs").addClass('alert alert-success alert-dismissable');
                $("#divLogDetailApprovedCs").text(detaildata.logdetaildata.VarCurrentStatus);
            }
            if(detaildata.logdetaildata.orstatus == '3') {
                $("#divLogDetailRejectCs").addClass('alert alert-danger alert-dismissable');
                $("#divLogDetailRejectCs").text(detaildata.logdetaildata.VarCurrentStatus);
            }
            if(detaildata.logdetaildata.orstatus == '4') {
                $("#divLogDetailPendingRRCs").addClass('alert alert-warning alert-dismissable');
                $("#divLogDetailPendingRRCs").text(detaildata.logdetaildata.VarCurrentStatus);
            }
            var ele = 'No attachemts';
            if(detaildata.downloads.length >= 1) {
                ele = '';
                for (var i = 0; i < detaildata.downloads.length; i++) {
                    ele += '<li>' + detaildata.downloads[i].fn + '&nbsp;';

                    ele += '<a href="' + base_path + GlbCompanyFdr + 'menquiry/download?enqid=' + detaildata.downloads[i].id + '' +
                        '&filename=' + detaildata.downloads[i].fn + '"><i class="fa fa-download fa-lg" aria-hidden="true"></i></a>&nbsp;&nbsp;';

                    ele += '<a href="' + base_path + 'uploads/orderenquiry/' + detaildata.downloads[i].id + '/' + detaildata.downloads[i].fn + '">' +
                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i></a></li>'
                }
            }
            $("#downloads").html(ele);
        }
    }

}