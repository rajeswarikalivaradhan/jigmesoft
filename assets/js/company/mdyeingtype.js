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
            //   alert("#"+ArrFnalList[i]);
            $("#"+ArrFnalList[i]).addClass('hide');
        }
        //alert("#"+ArrFnalList[i]);
    }
    $("#"+VarDivShow).addClass('show');
}

function fnSearchDyeingType() {
    var TypeName          					= $("#frmSrchDyeingName").val();
    var Status          						= $("#frmSrchDyeingStatus").val();

    GlbSearchParam							    = "rfrom=1&mn="+TypeName+"&s="+Status+"&afilter="+GlbFilterAlpha;
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'/mdyeintype/managedyeintype',GlbSearchParam,'json',fnListDyeingTypeRes);
}

function fnListDyeingType() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    //$("#ResResult").text("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+GlbCompanyFdr+'mdyeintype/managedyeintype',GlbSearchParam,'json',fnListDyeingTypeRes);
}

function fnListDyeingTypeRes(data) {
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
                            PageContent=PageContent+'<tr><td><input type="checkbox" id="'+value.id+'" class="allcbox"></td><td>' +
                                '<a href="'+base_path+GlbCompanyFdr+'mdyeintype/addeditdyeintype/'+escape(base64_encode(value.id))+'/">'+value.n+'</a></td>' +
                                '<td>'+value.s+'</td><td>'+value.ub+'</td><td>'+value.du+'</td><td><i class="fa fa-trash-o"></i>' +
                                '&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDeleteDyeingType('+value.id+')">Delete</a></td>';
                            PageContent=PageContent+'</tr>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                //PageContent	= PageContent+'</tbody></table>';
                if(data.pa!=undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
//                $("#ResResult").html(PageContent);
                $("tbody").empty();
                $("#dyeingTypeTblList").append(PageContent);
            }
        }
    }
}

function fnPaginationDyeingType(VarURL) {
    var Parameters = GlbSearchParam;
    console.log(Parameters);
    $("#DivTotalCntResult").html('');
    $("#ResResult").text("<img src='" + base_path + "/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListDyeingTypeRes);
}

function fnDeleteDyeingType(Id) {
    if(confirm("Are you want to delete this dyeing method?")) {
        var Parameters = "id="+Id;
        MakePostRequest(base_path+GlbCompanyFdr+'/mdyeintype/delDeyingTypeInfo',Parameters,'json',fnDeleteDyeingTypeRes);
    }
}

function fnDeleteDyeingTypeRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                fnSearchDyeingType();
            }
        }
    }
}

function fnSaveDyeingTypeInfo() {
    try{
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').html('');
        var ProfileFormData							= false;
        var TypeName     							= $("#frmBasicTypeName").val();
        var Status        							= $("#frmBasicStatus").val();
        if(jsTrim(TypeName)== ""){
            $('#ErrBasicTypeName').text("Please fill the method name");
            $('#frmBasicTypeName').focus();
            $('#frmBasicTypeName').css("border", "1px solid #B94A48");
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
            ProfileFormData.append("mn",TypeName);
            ProfileFormData.append("s",Status);
            ProfileFormData.append("id",GlbId);
        }
        $.ajax({
            url 		: base_path+GlbCompanyFdr+'mdyeintype/updateDyeingInfo',
            data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
            cache       : false,
            contentType : false,
            processData : false,
            type        : 'POST',
            success     : function(data, textStatus, jqXHR){
                data = jQuery.parseJSON(data);
                fnSaveDyeingTypeRes(data);
            }
        });
        return false;
    } catch(e) {
        alert(e);
    }
}

function fnSaveDyeingTypeRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrBasicTypeName').html(data.msg);
            return false;
        } else if(data.errcode==1){
            GlbId       = data.id;
            $("#divSuccessBasicInfoMsg").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Dyeing method has updated at successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mdyeintype/addeditdyeintype/'+data.eid);
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
                fnSearchDyeingType();
            }
        }
    }
}

$( document ).ready(function() {
    $('#dyeingTypeTblList').on('click', 'th.sortable', function () {
        var ReturnVal							    = commonTableSorting(this);


        GlbSortOrder	  							= ReturnVal[1];
        GlbColumnId									= ReturnVal[0];

        var TypeName          					= $("#frmSrchDyeingName").val();
        var Status          						= $("#frmSrchDyeingStatus").val();
        GlbSearchParam = "rfrom=1&mn=" + TypeName + "&s=" + Status + "&columnId=" + GlbColumnId + "&sortorder=" + GlbSortOrder;
        MakePostRequest(base_path+GlbCompanyFdr+'mdyeintype/managedyeintype',GlbSearchParam,'json',fnListDyeingTypeRes);

    });


    $('#btnChangeStatus').on('click',function () {
        var dropdownOpt                                 = $('#frmItemStatus').val();
        if(dropdownOpt > 0) {
            var SewTypeIdObject = commonCheckbox();
            var checkBoxLength = SewTypeIdObject[1];
            var cboxObj = SewTypeIdObject[0];
            $('#ErrItemStatus').text("");
            if(checkBoxLength == 0) {
                $('#ErrItemStatus').text("Choose the dyeing method");
            }
            if (checkBoxLength >= 1) {
                $('#ErrItemStatus').text("");
                var companyid_json = JSON.stringify(cboxObj);
                var TypeName          					= $("#frmSrchDyeingName").val();
                var Status          						= $("#frmSrchDyeingStatus").val();


                if (dropdownOpt == '1') { //Activate
                    if(confirm('Do you want to activate this dyeing type?')) {
                        GlbSearchParam = "rfrom=1&mn=" + TypeName + "&s=" + Status + "&columnId=" + GlbColumnId
                        +"&sortorder="+GlbSortOrder+"&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mdyeintype/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
                else if (dropdownOpt == '2') { //Deactivate
                    if(confirm('Do you want to Deactivate this dyeing?')) {
                        GlbSearchParam = "rfrom=1&mn=" + TypeName + "&s=" + Status + "&columnId=" + GlbColumnId
                            +"&sortorder="+GlbSortOrder+"&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                        MakePostRequest(base_path+GlbCompanyFdr+'mdyeintype/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                    }
                }
            }
        }
        else {
            $('#ErrItemStatus').text("Select a Option");
        }

    });

});
