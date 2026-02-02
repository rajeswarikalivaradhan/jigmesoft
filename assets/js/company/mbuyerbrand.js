$(document).ready(function () {
    if(GlbBrands != "") {
        var brands = JSON.parse(GlbBrands);
        console.log(brands,'len');
        var ele='';
        if(brands.length > 0) {
            $("#frmDefaultBrand").hide();
            for(var i= 0; i < brands.length; i++) {
                ele += '<tr id="'+GlbBrandsPlus+'"><td><input class="form-control" type="text" id="'+brands[i].id+'" value="'+brands[i].brandname+'" name="frmBrands[]" class="form-control"></td><td><i class="fa fa-minus-circle" onclick="removeExtraBrand('+GlbBrandsPlus+')"></i></td></tr>'
            }
            $("#extrabrands").append(ele);

        }
    }
});

var GlbSearchParam='';
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

//var GlbfrmBrands = [];
function fnSaveBuyerInfo() {
    try {
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');
        var ProfileFormData							    = '';
        var frmBasicBuyer     							= $("#frmBasicBuyer").val();
        var frmBasicBra     							= $("#frmBasicBra").val();

        var frmBasicCompanyName     					= $("#frmBasicCompanyName").val();
        var frmBasicEmail     							= $("#frmBasicEmail").val();
        var frmBasicMobile     							= $("#frmBasicMobile").val();
        var frmBasicContactNo     						= $("#frmBasicContactNo").val();
        var frmBasicAddress     						= $("#frmBasicAddress").val();
        var frmBasicDesignation     					= $("#frmBasicDesignation").val();
        var Status        							    = $("#frmBasicStatus").val();

/*
        $("input[name='frmBrands[]']").map(function(){
            if($(this).val() != '') {
                GlbfrmBrands.push($(this).val()+'#');
            }
        }).get();
*/

        if(jsTrim(Status)== ""){
            $('#ErrBasicStatus').text("Please select the status");
            $('#frmBasicStatus').focus();
            $('#frmBasicStatus').css("border", "1px solid #B94A48");
            return false;
        }
        if (window.FormData){
            ProfileFormData								= new FormData();
            ProfileFormData.append("by",frmBasicBuyer);
            ProfileFormData.append("bra",frmBasicBra);
            //ProfileFormData.append("GlbfrmBrands",GlbfrmBrands);

            ProfileFormData.append("cn",frmBasicCompanyName);
            ProfileFormData.append("e",frmBasicEmail);
            ProfileFormData.append("m",frmBasicMobile);
            ProfileFormData.append("p",frmBasicContactNo);
            ProfileFormData.append("a",frmBasicAddress);
            ProfileFormData.append("d",frmBasicDesignation);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'mbuyerbrand/updateBuyerInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = JSON.parse(data);
                fnSaveBuyerRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveBuyerRes(data) {
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            //$('#ErrBasicMethodName').html(data.msg);
            return false;
        } else if(data.errcode==1){
            console.log(data,'data');
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Buyer has updated at successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mbuyerbrand/addedit/'+data.eid);
        }
    }
}

function fnListBuyers() {
    $("#DivTotalCntResult").html('');
    var frmSrchBuyer     							= $("#frmSrchBuyer").val();
    var frmSrchBrand     							= '';

    var Status        							= $("#frmSrchBuyerStatus").val();
    GlbSearchParam								    = "rfrom=1&buyer="+frmSrchBuyer+"&brand="+frmSrchBrand+"&s="+Status;
    $("#ResResult").text("<img src='" + base_path + "assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'mbuyerbrand/manage',GlbSearchParam,'json',fnListBuyerRes);
}

function fnListBuyerRes(data) {
    console.log(data.re,'data');

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
                        $.each(data.re,function(index,value) {

                            PageContent=PageContent+'<tr>' +
                                '<td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>' +
                                '<td><a href="'+base_path+GlbCompanyFdr+'mbuyerbrand/addedit/'+encodeURIComponent(base64_encode(value.id))+'/">'+value.buyer+'</a></td>' +
                                '<td>'+value.companyname+'</td>' +
                                '<td>'+value.bran+'</td>' +
                                '<td>'+value.s+'</td>' +
                                '<td><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDeleteBuyer('+value.id+')">Delete</a></td>';
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
                $('#tableList').append(PageContent);
            }
        }
    }
}
var GlbParam = 'rfrom=1';
/*var GlbBrandsPlus = 1;
function fnPlusBrands() {
    GlbBrandsPlus++;
    var ele = '';

    ele += '<tr id="'+GlbBrandsPlus+'"><td><input class="form-control" type="text" id="_new" name="frmBrands[]" class="form-control"></td><td><i class="fa fa-minus-circle" onclick="removeExtraBrand('+GlbBrandsPlus+')"></i></td></tr>';

    $("#extrabrands").append(ele);

}

function removeExtraBrand(id) {
    $("#"+id).remove();
}*/

function fnDeleteBuyer(buyerid) {
    if(confirm("Are you want to delete this buyer?")) {
        var Parameters = GlbParam+"&id="+buyerid;
        MakePostRequest(base_path+GlbCompanyFdr+'mbuyerbrand/delBuyerInfo',Parameters,'json',fnDeleteBuyerRes);
    }
}

function fnDeleteBuyerRes(data) {
    console.log(data,'data');
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnListBuyers();
            }
        }
    }

}
