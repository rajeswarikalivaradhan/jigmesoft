var GlbSearchParam = '';
var GlbFilterAlpha=''; var GlbSortOrder=''; var GlbColumnId='';
function fnSaveChanges() {
    if (confirm("To confirm click OK, else CANCEL")) {
        try {
            $('.form-control').css("border", "1px solid #cccccc");
            $('div.herr').text('');
            var ProfileFormData							= false;
            var frmPono     					= $("#frmBasicPono").val();
            var frmCombo = $("#frmBasicCombo").val();
            var frmComponent = $("#frmBasicComponent").val();
            var frmColor = $("#frmBasicColor").val();
            var frmSpc = $("#frmBasicSpc").val();
            var frmRequirement = $("#frmBasicRequirement").val();
            var frmPurpose     					    = $("#frmBasicPurpose").val();
            var frmCategory     					    = $("#frmBasicCategory").val();
            var frmPrevSamRefNo     					    = $("#frmBasicPrevSamRefNo").val();
            var frmRequestType     					    = $("#frmBasicRequestType").val();
            var frmRequiredSize     					    = $("#frmBasicReqSize").val();
            var RequiredTotNoofSam     					    = $("#frmBasicRequiredTotNoofSam").val();
            var frmCutoffdatetime     					    = $("#frmBasicCutoffdatetime").val();
            var frmMerchantNote     					    = $("#frmBasicMerchantNote").val();
            var frmBuyersOriginalSample     					    = $("#frmBuyersOriginalSample").val();
            var frmInlineRefSample     					    = $("#frmInlineRefSample").val();
            var frmBuyersComments     					    = $("#frmBuyersComments").val();
            var frmCadIndent     					    = $("#frmCadIndent").val();
            var frmFabIndent     					    = $("#frmFabIndent").val();
            var frmBomIndent     					    = $("#frmBomIndent").val();
            var frmAppGradMeasChartDd     					    = $("#frmAppGradMeasChartDd").val();
            var frmCompleteArtwork     					    = $("#frmCompleteArtwork").val();
            var frmMeasureDetailsArtwork     					    = $("#frmMeasureDetailsArtwork").val();
            if(jsTrim(frmCutoffdatetime)== "") {
                $('#ErrfrmBasicCutoffdatetime').html("Please fill the Cutoff date time");
                $('#frmBasicCutoffdatetime').focus();
                $('#frmBasicCutoffdatetime').css("border", "1px solid #B94A48");
                return false;
            }
            MakeAsynPostRequest(base_path+'msamplerequest/updateInfo',"rfrom=1&id="+GlbId+"&pono="+frmPono+"&comboid="+frmCombo+"&componentid="+frmComponent+"&colorid="+frmColor+
                    "&spc="+frmSpc+"&req="+frmRequirement+"&pur="+frmPurpose+"&cat="+frmCategory+"&prevsamrefno="+frmPrevSamRefNo+"&reqtype="+frmRequestType+"&reqsize="+frmRequiredSize+"&cutoff="+frmCutoffdatetime+"&mnote="+frmMerchantNote+"&oid="+GlbOrderId+"&frmBuyersOriginalSample="+
                    frmBuyersOriginalSample+"&frmInlineRefSample="+frmInlineRefSample+"&frmBuyersComments="+frmBuyersComments+"&frmCadIndent="+frmCadIndent+"&frmFabIndent="+
                    frmFabIndent+"&frmBomIndent="+frmBomIndent+"&frmAppGradMeasChartDd="+frmAppGradMeasChartDd+"&frmCompleteArtwork="+frmCompleteArtwork+"&frmMeasureDetailsArtwork="+
                    frmMeasureDetailsArtwork+"&reqtotalsam="+RequiredTotNoofSam,'json',fnSaveRes);
        } catch(e) {
            alert(e);
        }
    }
    else return false;
}
function fnSaveRes(data) {
    if(data!='') {
        if (data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if (data.errcode == '-1') {
            //$('#ErrfrmBasicBomName').text(data.msg);
            return false;
        } else if (data.errcode == 1) {
            $("#frmCommonErr").text('');
            InsertId = data.id;
            var cadgridData = $('#gridCadIndent').jexcel('getData');
            var gridFabIndent = $('#gridFabIndent').jexcel('getData');
            var gridBomIndent = $('#gridBomIndent').jexcel('getData');
            var CadMaterialIssuedTo = $("#MatIssuedToCadIndent").val();
            var Cadcutoff = $("#CutoffCadIndent").val();
            var FabMaterialIssuedTo = $("#MatIssuedToFabIndent").val();
            var Fabcutoff = $("#CutoffFabIndent").val();
            var BomMaterialIssuedTo = $("#MatIssuedToBomIndent").val();
            var Bomcutoff = $("#CutoffBomIndent").val();
            var sampleororder_neworexcess_stock = $("#sampleororder_neworexcess_stock").val();
            if(BomMaterialIssuedTo == "") {
                $("#frmCommonErr").text('Choose Department to issue');
                return false;
            }
            if(Bomcutoff == "") {
                $("#frmCommonErr").text('Enter Cutoff datetime');
                return false;
            }
            extraObj.startUpload();
            MakeAsynPostRequest(base_path + 'msamplerequest/updateIndentGridInfo', "rfrom=1&crid="+InsertId+
                "&cadgridData="+JSON.stringify(cadgridData)+
                "&gridFabIndent=" + JSON.stringify(gridFabIndent) + "&gridBomIndent=" + JSON.stringify(gridBomIndent)+
                "&Cadcutoff="+Cadcutoff+
                "&CadMaterialIssuedTo="+CadMaterialIssuedTo+
                "&FabMaterialIssuedTo="+FabMaterialIssuedTo+
                "&Fabcutoff="+Fabcutoff+
                "&BomMaterialIssuedTo="+BomMaterialIssuedTo+
                "&Bomcutoff="+Bomcutoff+
                "&sampleororder_neworexcess_stock="+sampleororder_neworexcess_stock+"&oid="+GlbOrderId,'json',fnSaveRequestIndentsRes);
        }
    }
}
function fnSaveRequestIndentsRes(data) {
    var currenturl = window.location.href;
    fnRedirectPageTimeOut(currenturl+'/'+InsertId);
}
$(document).ready(function() {
    extraObj     = $("#uploadsamplerequest").uploadFile({
        dragDrop: true,
        multiple:true,
        url:base_path+'msamplerequest/fnUploadFilesRequest',
        fileName:"bimage",
        returnType: "json",
        fileName:"myfile",
        dynamicFormData:function () {
            var test = {'samrequestid':InsertId};
            return test;
        },
        autoSubmit:false
    });
});