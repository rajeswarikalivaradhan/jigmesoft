$(document).ready(function () {

    getSampleRequest();
    getSampleRequestImages();
    getSampleRequestQAImages();
    let qaImageCount = 0;

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

    function validateForm(validateField, dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            for(let j = 0; j < validateField.length; j++) {
                let col = validateField[j]
                if(dataValue[i][col] == "") {
                    errorCount++;
                }
            }
        }
        return errorCount;
    }

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST STARTS HERE 
    // ***********************************************************************************************************************************
    
    var requirementData = [];
    var requirementReferenceData = [];
    var readOnly = false;

    function getSampleRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('samId', samId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/getQAQueueDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                $('#attachReference').html('');
                sample_requirement_data = JSON.parse(data);

                // let qa_status = sample_requirement_data.req_data[0]['qa_status'];
                
                // if(qa_status == 'Q.A. PASS') {
                //     $('#qa_dept_remarks').prop('disabled', true);
                //     readOnly = true;
                //     $('#getValues').hide();
                // }

                append_sample_request(sample_requirement_data);
                append_attach_reference(sample_requirement_data);
                append_qa_status_updates(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    function getSampleRequestImages() {
        $('.ImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'sample_request');
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
                                    '<a href='+base_path+'uploads/request/sample/'+imageJSON[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/sample/'+imageJSON[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href="javascript:void(0);" data-id='+imageJSON[i].wip_files_id+' class="deleteImg" title="Delete">'+
                                        '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
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
    
    function getSampleRequestQAImages() {
        $('.QAImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('deptId', samId);
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
                                    '<a href='+base_path+'uploads/request/sample/'+imageJSON[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/sample/'+imageJSON[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href="javascript:void(0);" data-id='+imageJSON[i].wip_files_id+' class="deleteImg" title="Delete">'+
                                        '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
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

    function append_sample_request(data) {
        $('#sampleRequest').html('');
        let PurposeData = [ 'Development', 'Order Conf.', 'Shipment' ];;

        let list = {
            data: data.data,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData, readOnly: true },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'], readOnly: true },
                { type: 'dropdown', title: 'If Revised or In-line\n Prev. Sample Ref. No.', width: '8%', align: 'left', source: data.sampleRefNo, readOnly: true },
                { type: 'dropdown', title: 'Required\n Size(s)', width: '7%', align: 'center', source: data.sizeData, multiple: true, readOnly: true },
                { title: 'Qty. (Pcs.)', width: '5%', align: 'center', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        sampleRequest_vm = new Vue({
            el: '#sampleRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
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
            data: data.attachmentdata,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Approved & Graded\n Measurement Chart', width: '7%', align: 'center', source: common_dd, readOnly: true },
                { type: 'dropdown', title: 'Complete Artwork', width: '7%', align: 'center', source: common_dd, readOnly: true },
                { type: 'dropdown', title: 'How to Measure\n Details', width: '7%', align: 'center', source: common_dd, readOnly: true },
                { type: 'dropdown', title: 'Buyers Original \nSample or Pattern', width: '7%', align: 'center', source: common_dd, readOnly: true },
                { type: 'dropdown', title: "Buyer's Comments", width: '7%', align: 'center', source: common_dd, readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        sampleReference_vm = new Vue({
            el: '#attachReference',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_qa_status_updates(data) {
        
        let common_dd = [
            { id: '0', name: 'Q.A. PENDING'}, 
            { id: '1', name: 'Q.A. SCHEDULED'}, 
            { id: '2', name: 'Q.A. RE-SCHEDULED'}, 
            { id: '3', name: 'Q.A. IN PROGRESS'}, 
            { id: '4', name: 'NEED ALTERATION'}, 
            { id: '5', name: 'Q.A. PASS'}, 
            { id: '6', name: 'Q.A. PASS COND.'}, 
            { id: '7', name: 'Q.A. FAIL'}, 
        ];

        let updatedRow = '';
        let qa_sta_update = '';
        let qa_sta = '';
        let qa_re_sta_update = '';

        $('#qaStatusUpdates').html('');
        let list = {
            data: data.qa_status_data,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'hidden', title: 'QA Status Update' },
                { type: 'hidden', title: 'QA Re Status Update' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { title: 'Requirement', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Assigned Sample Ref. Np', width: '10%', align: 'center', readOnly: true },
                { type: 'calendar', title: 'Q.A. Scheduled\n Date & Time', width: '7%', align: 'center', options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                { type: 'dropdown', title: 'Q.A. Status', width: '7%', align: 'center', source: common_dd },
                { type: 'text', title: "Q.A. Status Update\n Date & Time", width: '7%', align: 'center', readOnly: true },
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
                if(col == 1)
                {
                    qa_sta_update = val;
                }
                if(col == 2)
                {
                    qa_re_sta_update = val;
                }
                if(col == 10) {
                    if(qa_sta_update == 0 && (qa_sta == 1 || updatedRow == ""))
                    {
                        $(cell).addClass('readonly');
                    }
                    else if(qa_re_sta_update == 1 && qa_sta == 2 && updatedRow == row)
                    {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 11 && (val == 5 || val == 6 || val == 7) && updatedRow == "")
                {
                    $(cell).addClass('readonly');
                }
            }
        };

        qaStatus_vm = new Vue({
            el: '#qaStatusUpdates',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    $('#getValues').click(function () {            
        let req_data = qaStatus_vm.getData();

        $('.herr').hide();
        if($('#qa_dept_remarks').val() == "" || $('#qa_dept_remarks').val() == null ) {
            $('#err_qa_dept_remarks').html("Fill qa department remarks");
            $('#err_qa_dept_remarks').show();
        }
        else if(qaImageCount == 0 && QAUpload.selectedFiles == 0 && ( req_data[0][11] == 5 || req_data[0][11] == 6 || req_data[0][11] == 7)) {
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
                    let req_empArr = [];
                    for(let i=0; i < req_data.length; i++) {
                        let data_a = req_data[i];
                        let decEmpAr = [];
                        for(let j=0; j < data_a.length; j++) {
                            if(j == 0) {
                                decEmpAr.push(data_a[j]);
                            }
                            if(j >= 2) {
                                decEmpAr.push(data_a[j]);
                            }
                        }
                        req_empArr.push(decEmpAr);
                    }

                    updateFunction(req_empArr);
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

    function updateFunction(finalData) {

        let dataform = new FormData();
        dataform.append('data', JSON.stringify(finalData));
        dataform.append('qa_dept_remarks', $('#qa_dept_remarks').val());
        dataform.append('samRefId', samId);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/updateQAQueueDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if(QAUpload.selectedFiles == 0)
                {
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('saved')
                    ).then(okay => {
                        if(okay)
                        {
                            window.location.href = base_path + 'company/mqausers/sampleqaqueuelist';
                        }
                    });
                }
                else {
                    QAUpload.startUpload();
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    let QAUpload = $("#QAImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'request/Samplerequest/imageUploadDetails',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            return {
                enquiry_id: enquiry_id,
                type: 'qa_request',
                request_id: reqId,
                deptId: samId,
            };
        },
        afterUploadAll: function () {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('saved')
            ).then(okay => {
                if(okay)
                {
                    window.location.href = base_path + 'company/mqausers/sampleqaqueuelist';
                }
            });
        },
        autoSubmit: false
    });

    $("#QAImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});