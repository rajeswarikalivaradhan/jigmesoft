$(function () {
    $(".bootdtp").datetimepicker({
        format: 'DD-MM-YYYY HH:mm:ss'
    });
});
$(document).ready(function() {
    var counter = 2;
    $("#addButton").click(function () {
        if(counter>10){
            alert("Only 10 textboxes allow");
            return false;
        }
        var newformgroup = $(document.createElement('div'))
            .attr("id", 'formgroup' + counter);
        var ele = '<div class="col-md-4"><div class="form-group"><label class="col-sm-4 control-label">CAD Ref No </label><div class="col-sm-8"><input type="text" name="cadrefno' + counter +'" id="cadrefno' +
            counter + '" class="form-control"></div></div></div>' +
            '<div class="col-md-4"><div class="form-group"><label class="col-sm-4 control-label">Cutoff Date & Time</label><div class="col-sm-8">' +
            '<div class="input-group date bootdtp" id="cadindentdt'+counter+'"><input type="text" id="frmBasicCadIndentCutoffdt'+counter+'" class="form-control">' +
            '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div></div></div>' +
            '<div class="col-md-4"><div class="form-group"><label class="col-sm-4 control-label">Material Issued To</label><div class="col-sm-8"><input type="text" id="materialusedto'+counter+'" ' +
            'class="form-control"></div></div></div>';
        newformgroup.after().html(ele);

        newformgroup.appendTo("#TextBoxesGroup");
        counter++;
        $(".bootdtp").datetimepicker({
            format: 'DD-MM-YYYY HH:mm:ss'
        });
    });

    $("#removeButton").click(function () {
        if(counter==1){
            alert("No more textbox to remove");
            return false;
        }
        counter--;
        $("#formgroup" + counter).remove();
    });
});
var fabricindentcounter = 2;
function fnFabricIndentAddExtra() {

    if(fabricindentcounter>9) {
        alert("Only 10 textboxes allow");
        return false;
    }
    var newformgroup = $(document.createElement('div')).attr("id", 'formgroup' + fabricindentcounter);
    var ele = '<div class="row"><div class="col-md-3">Fabric - '+fabricindentcounter+'\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">Fabric Ref No</label>\n' +
        '                                                            <div class="col-sm-8">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicCadRefNo" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">Color</label>\n' +
        '                                                            <div class="col-sm-8">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicCadRefNo" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">Garment Parts</label>\n' +
        '                                                            <div class="col-sm-8">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicGarmentParts" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                    </div>\n' +
        '                                                    <div class="col-md-3">\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">Fabric (%) Blend</label>\n' +
        '                                                            <div class="col-sm-8">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicGarmentParts" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">Fabric Content</label>\n' +
        '                                                            <div class="col-sm-8">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicGarmentParts" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">Fabric</label>\n' +
        '                                                            <div class="col-sm-8">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicGarmentParts" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                    </div>\n' +
        '                                                    <div class="col-md-3">\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">GSM</label>\n' +
        '                                                            <div class="col-sm-8">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicGarmentParts" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">Dyeing Type</label>\n' +
        '                                                            <div class="col-sm-8">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicGarmentParts" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">Fab. Dia / Dim. (W * H)</label>\n' +
        '                                                            <div class="col-sm-4">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicGarmentParts" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                            <div class="col-sm-4">\n' +
        '                                                                <select class="form-control">\n' +
        '                                                                    <option></option>\n' +
        '                                                                    <option>Inches</option>\n' +
        '                                                                    <option>Cms.</option>\n' +
        '                                                                </select>\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                    </div>\n' +
        '                                                    <div class="col-md-3">\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">Qty.(Kgs.)</label>\n' +
        '                                                            <div class="col-sm-8">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicGarmentParts" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                        <div class="form-group">\n' +
        '                                                            <label for="inputEmail3" class="col-sm-4 control-label">Qty. (Nos.)</label>\n' +
        '                                                            <div class="col-sm-8">\n' +
        '                                                                <input type="email" class="form-control" id="frmBasicGarmentParts" value="<?php //echo $ArrOrderEntry[\'class\'] ?>">\n' +
        '                                                            </div>\n' +
        '                                                        </div>\n' +
        '                                                    </div></div>';
    newformgroup.after().html(ele);

    newformgroup.appendTo("#fnFabricIndentExtraHere");
    fabricindentcounter++;
}

function fnFabricIndentRemoveExtra() {
    if(fabricindentcounter==1){
        alert("No more textbox to remove");
        return false;
    }
    fabricindentcounter--;
    $("#formgroup" + fabricindentcounter).remove();
}

function fnSaveSampleRequest() {
    var Parameters = '';
    MakeAsynPostRequest(base_path+GlbCompanyFdr+'msamplerequest/updatesamplerequest',Parameters,'json',fnSaveSampleRequestRes);
}

function fnSaveSampleRequestRes(data) {
    if(data!=''){
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1){
            $('#ErrBasicAccessories').html(data.msg);
            return false;
        } else if(data.errcode==1) {
            GlbInsertId = data.id;
            extraObj.startUpload();

/*            $("#frmBasicReqDT").text(data.dcreated);
            $("#divSuccessBasicInfoMsgparent").removeClass('hide');
            $("#divSuccessBasicInfoMsg").text("Enquiry has been sent successfully!");
            fnRedirectPageTimeOut(base_path+GlbCompanyFdr+'menquiry/manageenquiry');*/
        }
    }
}


$(document).ready(function() {
    extraObj     = $("#uploadSampleRequestAttachment").uploadFile({
        dragDrop: true,
        multiple:true,
        url:base_path+GlbCompanyFdr+'msamplerequest/fnUploadAttachment',
        fileName:"bimage",
        returnType: "json",
        fileName:"myfile",
        dynamicFormData:function () {
            var test = {'enq':GlbInsertId};
            return test;
        },
        autoSubmit:false
    });
    console.log(extraObj,'extraObj');
});

function getOrderEntryBomData() {

}

var bomindentcounter = 2;
function fnBomIndentAddExtra() {
    var ele = '<tr>\n' +
        '                                                    <td>'+bomindentcounter+'</td>\n' +
        '                                                    <td><input type="text" id="frmBasicBomRefNo1" class="form-control"></td>\n' +
        '                                                    <td><select id="frmBasicItemDescription1" class="form-control bomitemdesc"><option value=""></option></select></td>\n' +
        '                                                    <td><input type="text" id="frmBasicItemCode1" class="form-control"></td>\n' +
        '                                                    <td><input type="text" id="frmBasicItemColorCode1" class="form-control"></td>\n' +
        '                                                    <td><input type="text" id="frmBasicSizeDimension1" class="form-control"></td>\n' +
        '                                                    <td><input type="text" id="frmBasicUnit1" class="form-control"></td>\n' +
        '                                                    <td><input type="text" id="frmBasicQty1" class="form-control"></td>\n' +
        '                                                    <td><input type="text" id="frmBasicUnitofmeasure1" class="form-control"></td>\n' +
        '                                                </tr>';

    $("table tbody").append(ele);
    bomindentcounter++;
    var myOptions = {
        val1 : 'Blue',
        val2 : 'Orange'
    };
    var mySelect = $(".bomitemdesc");
    $.each(myOptions, function(val, text) {
        mySelect.append(
            $('<option></option>').val(val).html(text)
        );
    });
}

function fnBomIndentRemoveExtra() {
    if(bomindentcounter==2){
        alert("No more textbox to remove");
        return false;
    }
    bomindentcounter--;
    $("tr:last").remove();

}