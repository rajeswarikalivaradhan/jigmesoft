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
    var frmSrchLevel     					        = $("#frmSrchLevel").val();
    var Status        							= $("#frmSrchYarnStatus").val();
    GlbSearchParam = "rfrom=1&l=" + frmSrchLevel + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'mlotinspection/manage',GlbSearchParam,'json',fnListRes);
}

function fnList() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'mlotinspection/manage',GlbSearchParam,'json',fnListRes);
}

function fnListRes(data){
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
                                '<td><a href="'+base_path+GlbCompanyFdr+'mlotinspection/addedit/'+encodeURIComponent(base64_encode(value.id))+'/">'+value.l+'</a></td>' +
                                '<td>'+value.cl+'</td><td>'+value.aq+'</td><td>'+value.ss+'</td><td>'+value.s+'</td><td>'+value.ub+'</td><td>'+value.du+'</td>' +
                                '<td><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDelete('+value.id+')">Delete</a></td>';
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
                $('#mLotInspectionList').append(PageContent);
            }
        }
    }
}

function fnPaginationInspection(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListRes);
}

function fnDelete(Id) {
    if(confirm("Are you want to delete this Lot inspection detail?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCompanyFdr+'mlotinspection/delInfo',Parameters,'json',fnDeleteRes);
    }
}

function fnDeleteRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearch();
            }
        }
    }
}

function fnSaveLotInfo() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData							    = false;
        var frmBasicLevel     					        = $("#frmBasicLevel").val();
        var frmBasicCodeletter     					    = $("#frmBasicCodeLetter").val();
        var frmBasicAql     					        = $("#frmBasicAql").val();
        var frmBasicSampleSize     					    = $("#frmBasicSampleSize").val();
        var Status        							    = $("#frmBasicStatus").val();

        if(jsTrim(Status)== ""){
            $('#ErrBasicStatus').text("Please select the status");
            $('#frmBasicStatus').focus();
            $('#frmBasicStatus').css("border", "1px solid #B94A48");
            return false;
        }
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("l",frmBasicLevel);
            ProfileFormData.append("cl",frmBasicCodeletter);
            ProfileFormData.append("aq",frmBasicAql);
            ProfileFormData.append("ssize",frmBasicSampleSize);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }

        $.ajax({
            url 		: base_path+GlbCompanyFdr+'mlotinspection/updateInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = JSON.parse(data);
                fnSaveYarnRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveYarnRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrfrmBasicYarnName').text(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Lot Inspection has been updated at successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mlotinspection/addedit/'+data.eid);
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
                fnSearch();
            }
        }
    }
}

    $('#mLotInspectionList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];

        var frmSrchLevel     					        = $("#frmSrchLevel").val();
        var Status        							= $("#frmSrchYarnStatus").val();
        GlbSearchParam = "rfrom=1&l=" + frmSrchLevel + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        MakePostRequest(base_path + GlbCompanyFdr + 'mlotinspection/manage', GlbSearchParam, 'json', fnListRes);

    });


    $('#btnChangeStatus').on('click',function () {
        var StatusOptSelVal                         = $('#frmItemStatus').val();
        if(parseInt(StatusOptSelVal) > 0) {
            var ArrItemCheckBoxSel                  = commonCheckbox();
            var ObkChkSelVal                        = ArrItemCheckBoxSel[0];
            $('#ErrOption').text("");
            if (parseInt(ArrItemCheckBoxSel[1]) == 0) {
                $('#ErrItemStatus').text("Select the Checkbox");
            }
            if(parseInt(ArrItemCheckBoxSel[1]) >= 1) {
                var frmSrchLevel     					    = $("#frmSrchLevel").val();
                var Status        							= $("#frmSrchYarnStatus").val();
                $('#ErrItemStatus').text("");
                var StatusText                      = "Deactivate";
                if(StatusOptSelVal == '1') {
                    var StatusText                  = "Activate";
                }
                if(confirm('Do you want to '+StatusText+' this records?')) {
                    MakePostRequest(base_path + GlbCompanyFdr + 'mlotinspection/changemStatus', "rfrom=1&l=" + frmSrchLevel + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder + "&actdeactFabType=" + StatusOptSelVal + "&cid=" + JSON.stringify(ObkChkSelVal), 'json', fnChangeStatusRes);
                }
            }
        } else {
            $('#ErrOption').text("Select a Option");
        }
    });