function fnSearchStatement() {
    var MonthName							    = $("#frmNameMonthName").val();
    var YearName								= $("#frmNameYearName").val();
    GlbSearchParam							    = "rfrom=1&mn="+MonthName+"&yn="+YearName;
    $("#DivTotalCntResult").html('');
    $("#ResResult").html("<img src='"+base_path+"/assets/img/loader.gif' height='8' style='padding-left:10px'>");
    MakePostRequest(base_path+'dashboard/statementofaccount',GlbSearchParam,'json',fnSearchStatementRes);
}

function fnSearchStatementRes(data){
    if(data!=''){
        if(data.errcode!=undefined) {
            if(data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {

            }
        }
    }
}