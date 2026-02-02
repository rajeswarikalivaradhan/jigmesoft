function fnSaveQueries() {
    $('.input-text').css("border", "1px solid #cccccc");
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').html('');
    var ProfileFormData							= false;
    var TopicName  			    				= $("#frmHelpTopic").val();
    var Title		    						= $("#frmHelpTitle").val();
    var Message      							= $("#frmHelpMessage").val();
    if(jsTrim(TopicName)== ""){
        $('#ErrHelpTopic').html("Please fill the Topic Name");
        $('#frmHelpTopic').focus();
        $('#frmHelpTopic').css("border", "1px solid #B94A48");
        return false;
    }
    if(jsTrim(Title)== ""){
        $('#ErrHelpTitle').html("Please fill the Title");
        $('#frmHelpTitle').focus();
        $('#frmHelpTitle').css("border", "1px solid #B94A48");
        return false;
    }
    if(jsTrim(Message)== ""){
        $('#ErrHelpMessage').html("Please fill the Message");
        $('#frmHelpMessage').focus();
        $('#frmHelpMessage').css("border", "1px solid #B94A48");
        return false;
    }
    if (window.FormData){
        ProfileFormData								= new FormData();
        ProfileFormData.append("tn",TopicName);
        ProfileFormData.append("t",Title);
        ProfileFormData.append("m",Message);
    }
    $.ajax({
        url 		: base_path+'seller/slqueries/sendRequestQueries',
        data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
        cache       : false,
        contentType : false,
        processData : false,
        type        : 'POST',
        success     : function(data, textStatus, jqXHR){
            data = jQuery.parseJSON(data);
            fnSaveQueriesRes(data);
        }
    });
    return false;
}

function fnSaveQueriesRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrEmailId').html(data.msg);
            return false;
        } else if(data.errcode==1){
            $("#divSuccessQueriesMsg").removeClass('hide');
            $("#divSuccessQueriesMsg").html("Thanks for contacting us!.<br><br>Your Ticket No is <b>"+data.tc+"</b>. <br> We Will Solve your Query Within 1-2 Business Working Days.");
            resetForm("frmNameHelpCenter");
        }
    }
}

var GlbSearchParam='';
function fnSearchTicketInfo() {
    var TicketStatus							= $("#frmSrchTicketStatus").val();
    var TicketCode								= $("#frmSrchTicketCode").val();
    var GlbSearchParam							= "rfrom=1&ts="+TicketStatus+"&tc="+TicketCode;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+'seller/slqueries/manageticket',GlbSearchParam,'json',fnListAllTicketRes);
}

function fnListAllTicket() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+'seller/slqueries/manageticket',GlbSearchParam,'json',fnListAllTicketRes);
}

function fnListAllTicketRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent='';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Queries(s) : '+data.cn+'</div>';
                    PageContent	= "<table id='example1' class='table table-bordered table-hover'><thead><tr><th>Ticket Code</th><th>Title</th><th>Ticket Status</th><th>Date Created</th><th>Action</th></tr></thead><tbody>";
                    if(data.ct>0) {
                        $.each(data.re,function(index,value){
                            PageContent=PageContent+'<tr><td>'+value.tc+'</td><td>'+value.t+'</td><td>'+value.ts+'</td><td>'+value.dc+'</td><td>';
                            PageContent=PageContent+'<a href="'+base_path+'seller/slqueries/ticketdetails/'+value.id+'/"><i class="fa fa-file-text-o"></i> View Status</a>&nbsp;&nbsp;';
                            PageContent=PageContent+'</td></td>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Tickets found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                PageContent	= PageContent+'</tbody></table>';
                if(data.pa!=undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                $("#ResResult").html(PageContent);
            }
        }
    }
}

function fnPagination(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListAllTicketRes);
}

function fnShowProfileCont(VarDivShow) {
    var ArrProfileContList = ["DivContTicketInfo","DivContTicketReplies","DivContTicketRepliesForm","DivContTicketAssignForm"];
    //Remove Class
    for(i=0;i<ArrProfileContList.length;i++) {
        $("#"+ArrProfileContList[i]).removeClass('show');
        $("#"+ArrProfileContList[i]).removeClass('hide');
    }
    //Add Class
    for(i=0;i<ArrProfileContList.length;i++) {
        if(VarDivShow!=ArrProfileContList[i]) {
            $("#"+ArrProfileContList[i]).addClass('hide');
        }
    }
    $("#"+VarDivShow).addClass('show');
}

function fnShowHideEndUserSub(VarType,VarDivShow) {
    var ArrInvoiceBasicList = ["divShowTicketInfo","divAddEditConvInfo"];

    if(VarType==1) {
        var ArrFnalList	= ArrInvoiceBasicList;
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

function fnSaveTicketRepliedMsg() {
    $('.input-text').css("border", "1px solid #cccccc");
    $('.form-control').css("border", "1px solid #cccccc");
    $('div.herr').html('');
    var ProfileFormData							= false;
    var RepliedMessage		    				= $("#frmRepliedMessage").val();
    var TicketStatus                            = '';
    if($("#frmRepliedMessage").prop('checked')) {
        TicketStatus                            = 4;
    }
    if(jsTrim(RepliedMessage)== ""){
        $('#ErrRepliedMessage').html("Please fill the Message");
        $('#frmRepliedMessage').focus();
        $('#frmRepliedMessage').css("border", "1px solid #B94A48");
        return false;
    }
    if (window.FormData){
        ProfileFormData								= new FormData();
        ProfileFormData.append("ts",TicketStatus);
        ProfileFormData.append("tc",GlbTicketCode);
        ProfileFormData.append("tid",GlbTicketId);
        ProfileFormData.append("m",RepliedMessage);
    }
    $.ajax({
        url 		: base_path+'seller/slqueries/sendRepliedQueries',
        data        : ProfileFormData ? ProfileFormData : ObjForm.serialize(),
        cache       : false,
        contentType : false,
        processData : false,
        type        : 'POST',
        success     : function(data, textStatus, jqXHR){
            data = jQuery.parseJSON(data);
            fnSaveTicketRepliedMsgRes(data);
        }
    });
    return false;
}

function fnSaveTicketRepliedMsgRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrEmailId').html(data.msg);
            return false;
        } else if(data.errcode==1){
            $("#divSuccessRepliedMsg").removeClass('hide');
            $("#divSuccessRepliedMsg").addClass('show');
            $("#divSuccessRepliedMsg").html("Thanks for contacting us!.<br><br>Your Ticket No is <b>"+data.tc+"</b>. <br> We Will Solve your Query Within 1-2 Business Working Days.");
            //resetForm("frmNameRepliedTicket");
            window.location.href=window.location.href;
        }
    }
}


function fnShowTicketRepliedInfo(RepliedId,UserId) {
    var Parameters = "id="+RepliedId;
    MakePostRequest(base_path+"seller/slqueries/GetQueriesRepliedDetails/",Parameters,'json',fnShowTicketRepliedInfoRes);
}

function fnShowTicketRepliedInfoRes(data){
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            //$('#ErrPaymentNotes').html(data.msg);
            return false;
        } else if(data.errcode==1){
            var PageContent='';
            $.each(data.re,function(index,value){
                $("#divDispRepMsg").html(value.m);
                $("#divDispRepTicketStatus").html(value.ts);
                $("#divDispRepliedDate").html(value.dc);
            });
            fnShowHideEndUserSub(1,'divAddEditConvInfo');
        }
    }
}



var GlbSearchParam='';
function fnSearchUserTicketInfo() {
    var TicketStatus							= $("#frmSrchTicketStatus").val();
    var TicketCode								= $("#frmSrchTicketCode").val();
    var GlbSearchParam							= "rfrom=1&ts="+TicketStatus+"&tc="+TicketCode;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+'seller/slqueries/manageuserticket',GlbSearchParam,'json',fnListAllTicketRes);
}

function fnListAllUserTicket() {
    GlbSearchParam								= "rfrom=1";
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+'seller/slqueries/manageuserticket',GlbSearchParam,'json',fnListAllUserTicketRes);
}

function fnListAllUserTicketRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent='';
                if(data.cn>0) {
                    ListCount	= '<div style="font-weight:bold;">Number of Queries(s) : '+data.cn+'</div>';
                    PageContent	= "<table id='example1' class='table table-bordered table-hover'><thead><tr><th>Ticket Code</th><th>Title</th><th>Ticket Status</th><th>Date Created</th><th>Action</th></tr></thead><tbody>";
                    if(data.ct>0) {
                        $.each(data.re,function(index,value){
                            PageContent=PageContent+'<tr><td>'+value.tc+'</td><td>'+value.t+'</td><td>'+value.ts+'</td><td>'+value.dc+'</td><td>';
                            PageContent=PageContent+'<a href="'+base_path+'seller/slqueries/ticketdetails/'+value.id+'/1/"><i class="fa fa-file-text-o"></i> View Status</a>&nbsp;&nbsp;';
                            PageContent=PageContent+'</td></td>';
                        });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent	= PageContent+'<tr><td colspan="6" class="pdl15 herr text-center" style="padding-left:10px;">No Tickets found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                PageContent	= PageContent+'</tbody></table>';
                if(data.pa!=undefined) {
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                $("#ResResult").html(PageContent);
            }
        }
    }
}

function fnPaginationUserTicket(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(VarURL,Parameters,'json',fnListAllUserTicketRes);
}