var GlbSearchParam = '';
var GlbSortOrder = '';
var GlbColumnId = '';

function fnSearchLogistics() {
    var Cse     					                    = $("#frmSrchCse").val();
    var FAgent                                          = $("#frmSrchFAgent").val();
    var CAgent                                          = $("#frmSrchCAgent").val();
    var Importer                                        = $("#frmSrchImporter").val();
    var Consignee                                       = $("#frmSrchConsignee").val();
    var Status        							        = $("#frmSrchCStatus").val();
    GlbSearchParam = "rfrom=1&cse=" + Cse + "&fa=" + FAgent + "&ca=" + CAgent + "&i=" + Importer + "&cnee=" + Consignee + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/manage', GlbSearchParam, 'json', fnLogisticsListRes);
}

function fnLogisticsList() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/manage', GlbSearchParam, 'json', fnLogisticsListRes);
}

function fnLogisticsListRes(data){
    if(data!='') {
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
                                '<td><a href="'+base_path+GlbCompanyFdr+'mlogistics/addedit/'+encodeURIComponent(base64_encode(value.id))+'">'+value.cse+'</a></td>' +
                                '<td>'+value.fa+'</td>'+
                                '<td>'+value.ca+'</td>'+
                                '<td>'+value.i+'</td>'+
                                '<td>'+value.cnee+'</td>'+
                                '<td>'+value.s+'</td><td>'+value.ub+'</td><td>'+value.du+'</td>' +
                                '<td>' +
                                '<a href="' + base_path + GlbCompanyFdr + 'mlogistics/addedit/' + encodeURIComponent(base64_encode(value.id)) + '/edit' + '"><i class="fa fa-edit"></i>&nbsp;Edit</a></td>';
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
                $('#mlogisticsTbl').append(PageContent);
            }
        }
    }
}

function fnPaginationLogistics(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnLogisticsListRes);
}

function fnDelete(Id) {
    if(confirm("Are you want to delete this logistics?")) {
        var Parameters = "id="+Id;
        MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/delInfo', Parameters, 'json', fnDeleteRes);
    }
}

function fnDeleteRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchLogistics();
            }
        }
    }
}

function fnSaveLogisticsInfo() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData						= false;
        var Cse     					        = $("#frmBasicCse").val();
        var FAgent     					        = $("#frmBasicFAgent").val();
        var CAgent     					        = $("#frmBasicCAgent").val();
        var Importer     					    = $("#frmBasicImporter").val();
        var Consignee                           = $("#frmBasicConsignee").val();
        var Status        						= $("#frmBasicStatus").val();
        /*if(jsTrim(frmBasicBomName)== ""){
            $('#ErrfrmBasicBomName').text("Please fill the Item Description");
            $('#frmBasicBomName').focus();
            $('#frmBasicBomName').css("border", "1px solid #B94A48");
            return false;
        }

        if(jsTrim(Blend) == "") {
            $('#ErrfrmBasicBlend').text("Please fill the Blend (%)");
            $('#frmBasicBlend').focus();
            $('#frmBasicBlend').css("border", "1px solid #B94A48");
            return false;
        }*/

        if(jsTrim(Status)== ""){
            $('#ErrBasicStatus').text("Please select the status");
            $('#frmBasicStatus').focus();
            $('#frmBasicStatus').css("border", "1px solid #B94A48");
            return false;
        }
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("cse",Cse);
            ProfileFormData.append("fa",FAgent);
            ProfileFormData.append("ca",CAgent);
            ProfileFormData.append("i",Importer);
            ProfileFormData.append("cnee",Consignee);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'mlogistics/updateInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = JSON.parse(data);
                fnSaveLogisticsInfoRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveLogisticsInfoRes(data) {
    console.log(data.msg,'oo');
    if(data!='') {
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#AnyOtherErr').text(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Updated successfully");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mlogistics/addedit/'+data.eid);
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
                fnSearchLogistics();
            }
        }
    }
}

    $('#mlogisticsTbl').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);
        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];
        GlbSearchParam = GlbSearchParam + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/manage', GlbSearchParam, 'json', fnLogisticsListRes);

    });
$('#btnChangeStatus').on('click', function () {
    var dropdownOpt = $('#frmItemStatus').val();
    if (dropdownOpt > 0) {
        var SewTypeIdObject = commonCheckbox();
        var checkBoxLength = SewTypeIdObject[1];
        var cboxObj = SewTypeIdObject[0];
        if (checkBoxLength == 0) {
            alert("Select Logistics");
        }
        if (checkBoxLength >= 1) {
            var companyid_json = JSON.stringify(cboxObj);
            if (confirm('Do you want to ' + GlbStatusForMaster[dropdownOpt] + ' this record?')) {
                GlbSearchParam = "rfrom=1&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                MakeAsynPostRequest(base_path + GlbCompanyFdr + 'mlogistics/changemStatus', GlbSearchParam, 'json', fnChangeStatusRes);
            }
        }
    }
    else {
        alert('Select either ' + GlbStatusForMaster['1'] + ' or ' + GlbStatusForMaster['2']);
    }
});