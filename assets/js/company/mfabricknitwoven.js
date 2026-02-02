if(GlbFabType == 1) {
    console.log('knit');
    var GlbSearchParam='';
     var GlbSortOrder=''; var GlbColumnId='';
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

    function fnSearchFabricType() {
        var FabricBlend          					= $("#frmSrchFabricBlend").val();
        //var FabricLycra          					= $("#frmSrchFabricLycra").val();
        var FabricContent          					= $("#frmSrchFabricContent").val();
        var frmSrchFabric          					= $("#frmSrchFabric").val();

        var Status          						= $("#frmSrchFabricStatus").val();
        GlbSearchParam							    = "rfrom=1&co="+FabricContent+"&bl="+FabricBlend+"&fa="+frmSrchFabric+"&s="+Status;
        $("#DivTotalCntResult").html('');
        MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/managefabricknit',GlbSearchParam,'json',fnListFabricTypeRes);
    }

    function fnListFabricType() {
        GlbSearchParam								= "rfrom=1";

        $("#DivTotalCntResult").html('');
        MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/managefabricknit',GlbSearchParam,'json',fnListFabricTypeRes);
    }

    function fnListFabricTypeRes(data){
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
                                PageContent=PageContent+'<tr>' +
                                    '<td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>'+
                                    '<td>'+value.bl+' %</td>' +
                                    '<td>'+value.co+'</td>' +
                                    '<td>'+value.ly+'</td>' +
                                    '<td>'+value.fa+'</td>' +
                                    '<td>'+value.s+'</td><td>'+value.ub+'</td><td>'+value.du+'</td><td><a href="'+base_path+GlbCompanyFdr+'mfabrictype/addeditknit/'+encodeURIComponent(base64_encode(value.id))+'">Edit</a><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDeleteFabricType('+value.id+')">Delete</a></td>';
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
                    $("#mFabricList").append(PageContent)
                }
            }
        }
    }

    function fnPaginationknitWoven(VarURL) {
        var Parameters = GlbSearchParam;
        $("#DivTotalCntResult").html('');
        MakePostRequest(VarURL,Parameters,'json',fnListFabricTypeRes);
    }


    function fnDeleteFabricType(Id) {
        if(confirm("Are you want to delete this record?")) {
            var Parameters = "id="+Id;
            MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/delFabricTypeInfo',Parameters,'json',fnDeleteFabricTypeRes);
        }
    }

    function fnDeleteFabricTypeRes(data){
        if(data!=''){
            if(data.errcode!=undefined) {
                if(data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    fnListFabricType();
                }
            }
        }
    }

    function fnSaveFabricTypeInfo() {
        try{
            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').text('');
            var ProfileFormData							= false;
            var frmBasicFabricType     							= GlbFabType;
            var frmBasicFabricBlend     							= $("#frmBasicFabricBlend").val();
            var frmBasicFabricContent     							= $("#frmBasicFabricContent").val();
            //var frmBasicFabricLycra     							= $("#frmBasicFabricLycra").val();
            var frmBasicFabricFab     							= $("#frmBasicFabricFab").val();

            var Status        							= $("#frmBasicStatus").val();
            if(jsTrim(frmBasicFabricBlend)== ""){
                console.log(frmBasicFabricBlend,'kni');
                $('#ErrfrmBasicFabricBlend').text("Please fill the Blend");
                $('#frmBasicFabricBlend').focus();
                $('#frmBasicFabricBlend').css("border", "1px solid #B94A48");
                return false;
            }
            if(jsTrim(frmBasicFabricContent)== ""){
                $('#ErrfrmBasicFabricContent').text("Please fill the content");
                $('#frmBasicFabricContent').focus();
                $('#frmBasicFabricContent').css("border", "1px solid #B94A48");
                return false;
            }
/*            if(jsTrim(frmBasicFabricLycra)== ""){
                $('#ErrfrmBasicFabricLycra').text("Please fill the Lycra");
                $('#frmBasicFabricLycra').focus();
                $('#frmBasicFabricLycra').css("border", "1px solid #B94A48");
                return false;
            }*/
            if(jsTrim(frmBasicFabricFab)== ""){
                $('#ErrfrmBasicFabricFab').text("Please fill the Fabric");
                $('#frmBasicFabricFab').focus();
                $('#frmBasicFabricFab').css("border", "1px solid #B94A48");
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
                ProfileFormData.append("ty",frmBasicFabricType);
                ProfileFormData.append("bl",frmBasicFabricBlend);
                ProfileFormData.append("co",frmBasicFabricContent);
                //ProfileFormData.append("ly",frmBasicFabricLycra);
                ProfileFormData.append("fa",frmBasicFabricFab);

                ProfileFormData.append("s",Status);
                ProfileFormData.append("id",GlbId);
            }
            $.ajax({
                url 		: base_path+GlbCompanyFdr+'mfabrictype/updateFabricInfo',
                data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
                cache       : false,
                contentType : false,
                processData : false,
                type        : 'POST',
                success     : function(data, textStatus, jqXHR){
                    data = jQuery.parseJSON(data);
                    fnSaveFabricTypeRes(data);
                }
            });
            return false;
        } catch(e) {
            alert(e);
        }
    }

    function fnSaveFabricTypeRes(data){
        if(data!=''){
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if(data.errcode==-1){
                $('#ErrBasicFabricName').html(data.msg);
                return false;
            } else if(data.errcode==1){
                GlbId       = data.id;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Knit detail has updated at successfully!");
                fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mfabrictype/addeditknit/'+data.eid);
            }
        }
    }


    $( document ).ready(function() {
        $('#mFabricList').on('click', 'th.sortable', function () {
            var ReturnVal							    = commonTableSorting(this);

            
            GlbSortOrder	  							= ReturnVal[1];
            GlbColumnId									= ReturnVal[0];

            var FabricContent          					= $("#frmSrchFabricContent").val();
            var Status          						= $("#frmSrchFabricStatus").val();
            GlbSearchParam							    = "rfrom=1&fc="+FabricContent+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;
            MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/managefabricknit',GlbSearchParam,'json',fnListFabricTypeRes);

        });
        

        $('#btnChangeStatus').on('click',function () {
            var dropdownOpt                                 = $('#frmItemStatus').val();
            if(dropdownOpt > 0) {
                var SewTypeIdObject = commonCheckbox();
                var checkBoxLength = SewTypeIdObject[1];
                var cboxObj = SewTypeIdObject[0];
                $('#ErrItemStatus').text("");
                if(checkBoxLength == 0) {
                    $('#ErrItemStatus').text("Choose the Size Range");
                }
                if (checkBoxLength >= 1) {
                    $('#ErrItemStatus').text("");
                    var companyid_json = JSON.stringify(cboxObj);
                    var FabricContent          					= $("#frmSrchFabricContent").val();
                    var Status          						= $("#frmSrchFabricStatus").val();

                    if (dropdownOpt == '1') { //Activate
                        if(confirm('Do you want to activate this fabric?')) {
                            GlbSearchParam							    = "rfrom=1&fc="+FabricContent+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+"&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                            MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                        }
                    }
                    else if (dropdownOpt == '2') { //Deactivate
                        if(confirm('Do you want to Deactivate this fabric?')) {
                            GlbSearchParam							    = "rfrom=1&fc="+FabricContent+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+"&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                            MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                        }
                    }

                }
            }
            else {
                $('#ErrItemStatus').text("Select a Option");
            }
        });
    });

    function fnChangeStatusRes(data) {
        if(data!='') {
            if(data.errcode!=undefined) {
                if(data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    fnSearchFabricType();
                }
            }
        }
    }

    function fnPaginationFabric(VarURL) {
        var Parameters = GlbSearchParam;
        $("#DivTotalCntResult").html('');
        MakePostRequest(VarURL,Parameters,'json',fnListFabricTypeRes());
    }
}
else {
    console.log('woven','2');
    var GlbSearchParam='';
     var GlbSortOrder=''; var GlbColumnId='';
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

    function fnSearchFabricType() {
        var FabricBlend          					= $("#frmSrchFabricBlend").val();
        var FabricLycra          					= $("#frmSrchFabricLycra").val();
        var FabricContent          					= $("#frmSrchFabricContent").val();
        var frmSrchFabric          					= $("#frmSrchFabric").val();

        var Status          						= $("#frmSrchFabricStatus").val();
        GlbSearchParam							    = "rfrom=1&co="+FabricContent+"&bl="+FabricBlend+"&ly="+FabricLycra+"&fa="+frmSrchFabric+"&s="+Status;

        $("#DivTotalCntResult").html('');
        $("#ResResult").text("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
        MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/managefabricwoven',GlbSearchParam,'json',fnListFabricTypeRes);
    }

    function fnListFabricType() {
        GlbSearchParam								= "rfrom=1";
        console.log(GlbFabType,'GlbFabType');
        $("#DivTotalCntResult").html('');
        $("#ResResult").text("<img src='"+base_path+"assets/img/loader.gif' height='8' style='padding-left:10px'>");
        MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/managefabricwoven',GlbSearchParam,'json',fnListFabricTypeRes);
    }

    function fnListFabricTypeRes(data){
        if(data!='') {
            console.log(data.re,'check');
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
                                PageContent=PageContent+'<tr>' +
                                    '<td><input type="checkbox" class="allcbox" id="'+value.id+'"></td>'+
                                    '<td>'+value.bl+' %</td>' +
                                    '<td>'+value.co+'</td>' +
                                    '<td>'+value.fa+'</td>' +
                                    '<td>'+value.s+'</td><td>'+value.ub+'</td><td>'+value.du+'</td><td><a href="'+base_path+GlbCompanyFdr+'mfabrictype/addeditwoven/'+encodeURIComponent(base64_encode(value.id))+'">Edit</a><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="fnDeleteFabricType('+value.id+')">Delete</a></td>';
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
                    $("#mFabricList").append(PageContent)
                }
            }
        }
    }

    function fnPaginationknitWoven(VarURL) {
        var Parameters = GlbSearchParam;
        $("#DivTotalCntResult").html('');
        MakePostRequest(VarURL,Parameters,'json',fnListFabricTypeRes);
    }

    function fnDeleteFabricType(Id) {
        if(confirm("Are you want to delete this record?")) {
            var Parameters = "id="+Id;
            MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/delFabricTypeInfo',Parameters,'json',fnDeleteFabricTypeRes);
        }
    }

    function fnDeleteFabricTypeRes(data){
        if(data!=''){
            if(data.errcode!=undefined) {
                if(data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    fnListFabricType();
                }
            }
        }
    }

    function fnSaveWovenInfo() {
        try{
            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').text('');
            var ProfileFormData							= false;
            var frmBasicFabricType     					= GlbFabType;
            var frmBasicFabricBlend     				= $("#frmBasicFabricBlend").val();
            var frmBasicFabricContent     				= $("#frmBasicFabricContent").val();
            var frmBasicFabricFab     					= $("#frmBasicFabricFab").val();
            var Status        							= $("#frmBasicStatus").val();
            if(frmBasicFabricBlend == "") {
                $('#ErrfrmBasicFabricBlend').text("Please fill the Blend");
                $('#frmBasicFabricBlend').focus();
                $('#frmBasicFabricBlend').css("border", "1px solid #B94A48");
                return false;
            }
            if(jsTrim(frmBasicFabricContent)== "") {
                $('#ErrfrmBasicFabricContent').text("Please fill the content");
                $('#frmBasicFabricContent').focus();
                $('#frmBasicFabricContent').css("border", "1px solid #B94A48");
                return false;
            }
            if(jsTrim(frmBasicFabricContent)== ""){
                $('#ErrfrmBasicFabricContent').text("Please fill the content");
                $('#frmBasicFabricContent').focus();
                $('#frmBasicFabricContent').css("border", "1px solid #B94A48");
                return false;
            }
            if(jsTrim(frmBasicFabricFab)== ""){
                $('#ErrfrmBasicFabricFab').text("Please fill the Fabric");
                $('#frmBasicFabricFab').focus();
                $('#frmBasicFabricFab').css("border", "1px solid #B94A48");
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
                ProfileFormData.append("ty",frmBasicFabricType);
                ProfileFormData.append("bl",frmBasicFabricBlend);
                ProfileFormData.append("co",frmBasicFabricContent);
                ProfileFormData.append("fa",frmBasicFabricFab);

                ProfileFormData.append("s",Status);
                ProfileFormData.append("id",GlbId);
            }
            $.ajax({
                url 		: base_path+GlbCompanyFdr+'mfabrictype/updateFabricInfo',
                data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
                cache       : false,
                contentType : false,
                processData : false,
                type        : 'POST',
                success     : function(data, textStatus, jqXHR){
                    data = jQuery.parseJSON(data);
                    fnSaveFabricTypeRes(data);
                }
            });
            return false;
        } catch(e) {
            alert(e);
        }
    }

    function fnSaveFabricTypeRes(data){
        if(data!=''){
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else if(data.errcode==-1){
                $('#ErrBasicFabricName').html(data.msg);
                return false;
            } else if(data.errcode==1){
                GlbId       = data.id;
                $("#divSuccessBasicInfoMsg").removeClass('hide');
                $("#divSuccessBasicInfoMsg").text("Woven detail has updated at successfully!");
                //fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'mfabrictype/addeditwoven/'+data.eid);
            }
        }
    }


    $( document ).ready(function() {
        $('#mFabricList').on('click', 'th.sortable', function () {
            var ReturnVal							    = commonTableSorting(this);

            
            GlbSortOrder	  							= ReturnVal[1];
            GlbColumnId									= ReturnVal[0];

            var FabricContent          					= $("#frmSrchFabricContent").val();
            var Status          						= $("#frmSrchFabricStatus").val();
            GlbSearchParam							    = "rfrom=1&fc="+FabricContent+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder;
            MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/managefabricwoven',GlbSearchParam,'json',fnListFabricTypeRes);

        });
        

        $('#btnChangeStatus').on('click',function () {
            var dropdownOpt                                 = $('#frmItemStatus').val();
            if(dropdownOpt > 0) {
                var SewTypeIdObject = commonCheckbox();
                var checkBoxLength = SewTypeIdObject[1];
                var cboxObj = SewTypeIdObject[0];
                $('#ErrItemStatus').text("");
                if(checkBoxLength == 0) {
                    $('#ErrItemStatus').text("Choose the Size Range");
                }
                if (checkBoxLength >= 1) {
                    $('#ErrItemStatus').text("");
                    var companyid_json = JSON.stringify(cboxObj);
                    var FabricContent          					= $("#frmSrchFabricContent").val();
                    var Status          						= $("#frmSrchFabricStatus").val();

                    if (dropdownOpt == '1') { //Activate
                        if(confirm('Do you want to activate this fabric?')) {
                            GlbSearchParam							    = "rfrom=1&fc="+FabricContent+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+"&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                            MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                        }
                    }
                    else if (dropdownOpt == '2') { //Deactivate
                        if(confirm('Do you want to Deactivate this fabric?')) {
                            GlbSearchParam							    = "rfrom=1&fc="+FabricContent+"&s="+Status+"&columnId="+GlbColumnId+"&sortorder="+GlbSortOrder+"&actdeactFabType=" + dropdownOpt + "&cid=" + companyid_json;
                            MakePostRequest(base_path+GlbCompanyFdr+'mfabrictype/changemStatus',GlbSearchParam,'json',fnChangeStatusRes);
                        }
                    }

                }
            }
            else {
                $('#ErrItemStatus').text("Select a Option");
            }
        });
    });

    function fnChangeStatusRes(data) {
        if(data!='') {
            if(data.errcode!=undefined) {
                if(data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    fnSearchFabricType();
                }
            }
        }
    }

    function fnPaginationFabric(VarURL) {
        var Parameters = GlbSearchParam;
        $("#DivTotalCntResult").html('');
        $("#ResResult").text("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
        MakePostRequest(VarURL,Parameters,'json',fnListFabricTypeRes());
    }
}