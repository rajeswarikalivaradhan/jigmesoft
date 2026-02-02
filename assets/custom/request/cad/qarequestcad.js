$(document).ready(function () {
    let qaImageCount = 0;
    getCadRequest();
    getCadRequestImages();
    getCadQARequestImages();
    getQARequestImages();
    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    function alertMessageFunction(mode) {
        if(mode === 'confirmation_save') {
            return {
                title: 'Are you sure want to \n save the details ?',
                text: "If you save You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                scrollbarPadding: false,
                confirmButtonText: 'Yes, do it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true,
                customClass: {
                    'confirmButton': 'btn btn-green mx-2 px-3',
                    'cancelButton': 'btn btn-red mx-2 px-3'
                }
            }
        }

        if(mode == "saved") {
            return {
                title: 'Saved!',
                text: 'Operation completed successfully.',
                type: 'success',
                icon: 'success',
                customClass: {
                    'confirmButton': 'btn btn-info px-5'
                }
            }
        }
        
        if(mode == "cancelled") {
            return {
                title: 'Cancelled',
                text: 'Cancelled successfully.',
                type: 'error',
                icon: 'error',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        if(mode == "validation_error") {
            return {
                title: 'Warning',
                text: "Please fill all free text and select fields",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        if(mode == "status_error") {
            return {
                title: 'Warning',
                text: "No Pending Status Update",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        if(mode == "img_error") {
            return {
                title: 'Warning',
                text: "Please Upload QA Report",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        if(mode == "selecterror") {
            return {
                title: 'Warning',
                text: "Please select atleast one requirement",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
    }

    // *********************************************************************************************************************************** 
    // CAD REQUEST STARTS HERE 
    // ***********************************************************************************************************************************
    function getCadRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('cadId', cadId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/getqacadqueueDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                cad_requirement_data = JSON.parse(data);
                // console.log(cad_requirement_data.cad_data[0].qa_dept_remarks);
                $('#qa_req_status').val(cad_requirement_data.cad_data[0].qa_approval);
                $('#qa_req_status').trigger('change');
                $('#qa_queue_no').val(cad_requirement_data.cad_data[0].ref_queue_no);
                $('#qa_assign_dt').val(cad_requirement_data.cad_data[0].qno_assign_dt);
                $('#qa_dept_remarks').val(cad_requirement_data.cad_data[0].qa_dept_remarks);
                append_cad_request(cad_requirement_data);
                append_attach_reference(cad_requirement_data);
                append_qa_status(cad_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    function getCadRequestImages() {
        $('.ImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'cad_request');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getcadrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                for (let i = 0; i < imageJSON.length; i++) {
                    $('.ImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/cad/'+imageJSON[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/cad/'+imageJSON[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    function getCadQARequestImages() {
        $('.CADImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'cad_qa_request');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getcadrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                for (let i = 0; i < imageJSON.length; i++) {
                    $('.CADImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/cad/'+imageJSON[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/cad/'+imageJSON[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    function getQARequestImages() {
        $('.QAImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('deptId', cadId);
        data.append('type', 'qa_request');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getcadrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                qaImageCount = imageJSON.length;
                for (let i = 0; i < imageJSON.length; i++) {
                    $('.QAImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/cad/'+imageJSON[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/cad/'+imageJSON[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_cad_request(data) {
        $('#cadRequest').html('');
        let dd = [], updatedRow = '', index = '', nVal = '';
        let PurposeData = [ 'Costing', 'FCC - Sample', 'FCC - Bulk', 'Cutting - Sample', 'Cutting - Bulk', 'Bit Cutting - Sample',
            'Bit Cutting - Bulk', 'Others' ];
        let list = {
            data: data.data,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData, readOnly: true },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'],readOnly: true },
                { type: 'text', title: 'If Revised or In-line\nPrevious CAD Ref. No.', width: '10%', align: 'center', readOnly: true },
                { type: 'dropdown', title: 'Required\nSize(s)', width: '5%', align: 'left', source: data.sizeData, multiple: true,readOnly: true },
                { type: 'text', title: 'Assigned\n CAD Reference No.', width: '7%', align: 'left', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        cadRequest_vm = new Vue({
            el: '#cadRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }
    // *********************************************************************************************************************************** 
    // CAD REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_attach_reference(data) {
        let common_dd = [
            {id: '1', name: 'Attached'}, 
            {id: '2', name: 'Pending'}, 
            {id: '3', name: 'N.A.'}, 
        ];
        $('#attachReference').html('');
        let list = {
            data: data.requestData,
            columns: [
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Approved & Graded\n Measurement Chart', width: '7%', align: 'left', source: common_dd,readOnly: true },
                { type: 'dropdown', title: 'Complete Artwork', width: '7%', align: 'left', source: common_dd,readOnly: true },
                { type: 'dropdown', title: 'How to Measure\n Details', width: '7%', align: 'left', source: common_dd,readOnly: true },
                { type: 'dropdown', title: 'Buyers Original \nSample or Pattern', width: '7%', align: 'left', source: common_dd,readOnly: true },
                { type: 'dropdown', title: "Buyer's Comments", width: '7%', align: 'left', source: common_dd,readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        cadReference_vm = new Vue({
            el: '#attachReference',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // QA STATUS UPDATE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_qa_status(data) {
        let common_dd = [
            { id: '4', name: 'DISCREPANCY'}, 
            { id: '5', name: 'PASS'}, 
            { id: '6', name: 'PASS COND.'}, 
            { id: '7', name: 'FAIL'}, 
        ];

        let updatedRow = '';
        let qa_sta_update = '';
        let qa_sta = '';
        let qa_re_sta_update = '';

        $('#qaStatusTbl').html(''); 
        let list = {
            data: data.qaStatusData,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'hidden', title: 'QA Status Update' },
                { type: 'hidden', title: 'QA Re Status Update' },
                { type: 'checkbox', title: 'Check',width:'2%' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Q.A. Request Sent\nDate & Time', width: '7%', align: 'center', readOnly: true },
                { type: 'calendar', title: 'Q.A. Scheduled\nDate & Time', width: '7%', align: 'center', options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 }, readOnly: true },
                { type: 'dropdown', title: 'Q.A. Status.', width: '7%', align: 'left', source: common_dd },
                { type: 'text', title: 'Q.A. Status Update\nDate & Time', width: '7%', align: 'left',readOnly: true },
                { type: 'hidden', title: 'Edit Status' },
                { type: 'hidden', title: 'QA Status' },
                { type: 'hidden', title: 'mode' },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 11) 
                {
                    updatedRow = row;
                    qa_sta = val;
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                if(col==3) {
                    checkVal = val;
                    if(data.qaStatusData[row][14] == 1) {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 1)
                {
                    qa_sta_update = val;
                }
                if(col == 2)
                {
                    qa_re_sta_update = val;
                }
                if(col == 9) {
                    if(qa_sta_update == 0 && (qa_sta == 1 || updatedRow == ""))
                    {
                        $(cell).addClass('readonly');
                    }
                    else if(qa_re_sta_update == 1 && qa_sta == 2 && updatedRow == row)
                    {
                        $(cell).removeClass('readonly');
                    }
                }
                // if(col == 11 && (val == 5 || val == 6 || val == 7) && updatedRow == "")
                // {
                //     $(cell).addClass('readonly');
                // }
                if(col==11) {
                    if(data.qaStatusData[row][14] == 1 ) {
                        $(cell).addClass('readonly');
                    }
                    else {
                        if(checkVal == true) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                        
                    }
                }
                
                // if(col == 15) {  
                    
                // if(updatedRow == row) {
                //     console.log(updatedRow);
                //      $(cell).text(1);
                //      instance.jexcel.options.data[updatedRow][col] = 1;
                // }
                // }
            }
        };

        QAReference_vm = new Vue({
            el: '#qaStatusTbl',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    // *********************************************************************************************************************************** 
    // QA STATUS UPDATE ENDS HERE 
    // ***********************************************************************************************************************************
    
    function validateAcceptForm(dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][3] == 1 && (dataValue[i][11] == 1 || dataValue[i][11] == 2 || dataValue[i][11] == 3 )) {
                errorCount++;
            }
        }
        return errorCount;
    }
    function validateImgForm(dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][3] == 1 && dataValue[i][14] == 0 && (dataValue[i][11] == 5 || dataValue[i][11] == 6)) {
                errorCount++;
            }
        }
        return errorCount;
    }
    
    function validateSelectForm(dataValue ) {
        let errorCount = 0;
        let count = 0;
        let count1 = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][14] == 0) {
                count++;
                if(dataValue[i][3] == 0 ) {
                    count1++;
                }
            } 
        }
        if(count == count1) {
            errorCount++;
        } 
        
        return errorCount;
    }
    
    function validateUncheckForm(dataValue ) {
        let errorCount = 0;
        let count1 = 0;
        let count = dataValue.length;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][14] == 0) {
                errorCount++;
            } 
        }
        
        return errorCount;
    }

    $('#getValues').click(function () {

        let QATblData = QAReference_vm.getData();
        let validatedErrorCount = validateAcceptForm(QATblData);
        let validateImgCount = validateImgForm(QATblData);
        let validateSelectCount = validateSelectForm(QATblData);
        let validateUncheckCount = validateUncheckForm(QATblData);
        $('.herr').hide();
        if($('#qa_dept_remarks').val() == "" || $('#qa_dept_remarks').val() == null) {
            $('#err_qa_dept_remarks').html("Fill cad note");
            $('#err_qa_dept_remarks').show();
        }
        else if(validatedErrorCount > 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
        }
        else if(validateSelectCount > 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('status_error')
            );
        }
        // else if(validateUncheckCount == 0) {
        //     swalWithBootstrapButtons.fire(
        //         alertMessageFunction('validation_error')
        //     );
        // }
        else if(qaImageCount == 0 && CADUpload.selectedFiles == 0 && validateImgCount > 0) {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('img_error')
            );
        }
        else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    let qaStatusData = QAReference_vm.getData();
                    updateFunction(qaStatusData);
                } 
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
        }
        
    });

    function updateFunction(data) {

        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('request_id', reqId);
        dataform.append('qa_dept_remarks', $('#qa_dept_remarks').val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/UpdateCadQAQueueData',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let fdata = JSON.parse(data);
                if(fdata.status == "success")
                {
                    if(CADUpload.selectedFiles == 0)
                    {
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(okay => {
                            if(okay) {
                                window.location.href = base_path+"company/mqausers/cadqaqueuelist";
                            }
                        });
                    }
                    else {
                        CADUpload.startUpload();
                    }
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    let type = 'qa_request';

    let CADUpload = $("#QAImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'request/cadrequest/uploadCADReqImages',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            return {
                enquiry_id: enquiry_id,
                type: type,
                request_id: reqId,
                deptId: cadId,
            };
        },
        afterUploadAll:function () {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('saved')
            ).then(okay => {
                if(okay) {
                    window.location.href = base_path+"company/mqausers/cadqaqueuelist";
                }
            });
        },
        autoSubmit: false
    });

    $("#QAImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    
});