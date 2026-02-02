var GlbSearchParam = '';
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

function fnSearchBom() {
    var frmBasicBomName     					        = $("#frmSrchBomName").val();
    var frmSrchSuppName     					        = $("#frmSrchSuppName").val();
    var Userid     					                    = $("#frmSrchUid").val();
    var PwdExpDate     					                = $("#frmSrchPwdExp").val();
    var Status        							        = $("#frmSrchBomStatus").val();
    GlbFilterAlpha                                      = $('#hiddenAlpha').val();
    GlbSearchParam = "rfrom=1&bn=" + frmBasicBomName + "&sup=" + frmSrchSuppName + "&u=" + Userid + "&sn=" + frmSrchSuppName + "&ex=" + PwdExpDate + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakePostRequest(base_path+GlbCompanyFdr+'mbomsourcing/managebomsrc',GlbSearchParam,'json',fnListRes);
}

function fnListBom() {
    $("#DivTotalCntResult").html('');
    GlbSearchParam = 'rfrom=1';
    MakePostRequest(base_path+GlbCompanyFdr+'mbomsourcing/managebomsrc',GlbSearchParam,'json',fnListRes);
}

function fnListRes(data){
    console.log(data); console.log(data.re);

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
                            PageContent=PageContent+'<tr><td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>' +
                                '<td><a href="'+base_path+GlbCompanyFdr+'mbomsourcing/addedit/'+encodeURIComponent(base64_encode(value.id))+'">'+value.sup+'</a></td>' +
                                '<td>'+value.addr+'</td>' +
                                '<td>'+value.em+'</td>' +
                                '<td>'+value.ph+'</td>' +
                                '<td>'+value.mo+'</td>' +
                                '<td>'+value.ub+'</td>' +
                                '<td>'+value.s+'</td><td>'+value.du+'</td>' +
                                '<td><i class="fa fa-trash-o"></i><a href="javascript:void(0);" onclick="fnDelete('+value.id+')">Delete</a></td>';
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
                $('tbody').empty();
                $('#mBomSrcList').append(PageContent);
            }
        }
    }

}

function fnDelete(Id) {
    if(confirm("Are you want to delete this record?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCompanyFdr+'mbomsourcing/delInfo',Parameters,'json',fnDeleteRes);
    }
}

function fnDeleteRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchBom();
            }
        }
    }
}

function fnSaveBomInfo() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData							= false;
        var frmBasicSupplier     					= $("#frmBasicSname").val();
        var frmBasicSAddr     					= $("#frmBasicSAddr").val();
        var frmBasicEmailId     					= $("#frmBasicEmailId").val();
        var frmBasicPhone     					    = $("#frmBasicPhone").val();
        var frmBasicMobile     					    = $("#frmBasicMobile").val();
        var Status        							= $("#frmBasicStatus").val();

        if(jsTrim(frmBasicSupplier) == "") {
            $('#ErrfrmBasicSname').text("Please fill the supplier name");
            $('#frmBasicSname').focus();
            $('#frmBasicSname').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(frmBasicSAddr)== "") {
            $('#ErrfrmBasicBomName').text("Please fill the Address");
            $('#frmBasicBomName').focus();
            $('#frmBasicBomName').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(Status)== ""){
            $('#ErrBasicStatus').text("Please select the status");
            $('#frmBasicStatus').focus();
            $('#frmBasicStatus').css("border", "1px solid #B94A48");
            return false;
        }
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("soaddr",frmBasicSAddr);
            ProfileFormData.append("sup",frmBasicSupplier);
            ProfileFormData.append("ema",frmBasicEmailId);
            ProfileFormData.append("pho",frmBasicPhone);
            ProfileFormData.append("mob",frmBasicMobile);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'mbomsourcing/updateInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = JSON.parse(data);
                fnSaveBomRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveBomRes(data) {
    console.log(data.msg,'oo');
    if(data!='') {
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrfrmBasicBomName').text(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Bill of Material Sourcing details has been updated successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mbomsourcing/addedit/'+data.eid);
        }
    }
}

function fnChangeStatusRes(data) {
    if(data!='') {
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchBom();
            }
        }
    }
}

    $('#mBomSrcList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];
        GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
console.log(GlbSearchParam);
        MakePostRequest(base_path+GlbCompanyFdr+'mbomsourcing/managebomsrc',GlbSearchParam,'json',fnListRes);

    });



    $('#btnChangeStatus').on('click',function () {
        var dropdownOpt                                 = $('#frmItemStatus').val();
        if(dropdownOpt > 0) {
            var SewTypeIdObject = commonCheckbox();
            var checkBoxLength = SewTypeIdObject[1];
            var cboxObj = SewTypeIdObject[0];
            $('#ErrItemStatus').text("");
            if(checkBoxLength == 0) {
                $('#ErrItemStatus').text("Choose a bill of material source");
            }
            if (checkBoxLength >= 1) {
                $('#ErrItemStatus').text("");
                var companyid_json = JSON.stringify(cboxObj);
                if (dropdownOpt == '1') { //Activate
                    if(confirm('Do you want to activate this bill of material source?')) {
                        GlbSearchParam							    = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mbomsourcing/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
                else if (dropdownOpt == '2') { //Deactivate
                    if(confirm('Do you want to Deactivate this bill of material source?')) {
                        GlbSearchParam							    = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mbomsourcing/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
            }
        }
        else {
            $('#ErrItemStatus').text("Select a Option");
        }
    });

$('#frmSrchPwdExp').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

$('#frmBasicExpPwd').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight: true,
    autoclose: true
});

function fnPaginationBomSrc(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakePostRequest(VarURL,Parameters,'json',fnListRes);
}