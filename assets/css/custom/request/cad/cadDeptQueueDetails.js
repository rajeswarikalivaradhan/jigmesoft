$(document).ready(function () {
    getCadRequest();
    getCadMerchantRequestImages();
    getCadQARequestImages();
    getCadRequestImages();
    var selectCount = 0;
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

    var jobStatusData = [];
    var cad_requirement_data = [];

    function getCadRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/getcadqarequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                cad_requirement_data = JSON.parse(data);
                append_cad_request(cad_requirement_data);
                append_attach_reference(cad_requirement_data);
                append_qa_status(cad_requirement_data);
                // if(cad_requirement_data.jobStatusData.length > 0)
                // {
                //     jobStatusData = cad_requirement_data.jobStatusData;
                    append_job_status(cad_requirement_data);
                // }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getCadMerchantRequestImages() {
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
                       const subscriber_id = remarkNimageJSON.subscriber_id || ''; // or get it from elsewhere
                       const fileUrl = base_path + 'uploads/request/cad/' + subscriber_id + '/' + remarkNimageJSON.filesData[i].image_url;
                    $('.ImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                   '<a href="' + fileUrl + '" title="Download" download>' +
                                     '<i class="fa fa-download fa-lg" aria-hidden="true"></i>' +
                                        '</a>' +
                    // Second link to open in a new tab
                                  '<a href="' + fileUrl + '" target="_blank" title="Open in New Tab">' +
                                  '<i class="fa fa-file fa-lg" aria-hidden="true"></i>' +
                                   '</a>' +
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

    function getCadQARequestImages() {
        $('.QAImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
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

    function getCadRequestImages() {
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

    function append_cad_request(data) {
        $('#cadRequest').html('');
        let dd = [], updatedRow = 'A', index = '', nVal = '';
        let PurposeData = [ 'Costing', 'FCC - Sample', 'FCC - Bulk', 'Cutting - Sample', 'Cutting - Bulk', 'Bit Cutting - Sample',
            'Bit Cutting - Bulk', 'Others' ];
        let list = {
            data: data.data,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                // { type: 'checkbox', title: 'Mark', width: '5%', align: 'center' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '7%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData, readOnly: true },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'],readOnly: true },
                { type: 'text', title: 'If Revised or In-line\nPrevious CAD Ref. No.', width: '10%', align: 'center', readOnly: true },
                { type: 'dropdown', title: 'Required\nSize(s)', width: '5%', align: 'left', source: data.sizeData, multiple: true,readOnly: true },
                // { type: 'text', title: 'Assigned\n CAD Reference No.', width: '10%', align: 'left', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            // onchange: function(instance, cell, col, row, val, label, cellName) {
            //     if(col == 1) 
            //     {
            //         updatedRow = row;
            //         getReferenceValue(list.data[row], val);
            //     }
            // },
            // updateTable: function(instance, cell, col, row, val, label, cellName) {
            //     if(col == 1) 
            //     {
            //         // console.log(updatedRow)
            //         // console.log(row)
            //         if(val == true && row != updatedRow)
            //         {
            //             console.log(updatedRow)
            //             console.log(row)
            //             cell.classList.add('readonly');
            //         }
            //     }
            // }
        };

        cadRequest_vm = new Vue({
            el: '#cadRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }
    
    // function getReferenceValue(data, status) {

    //     if(status == true) {
    //         let emparr = [];
    //         let length = data.length;
    //         for(let i=0; i < data.length; i++) {
    //             if(i == 0)
    //             {
    //                 emparr.push(data[i]);
    //             }
    //             if(i > 1 && i < length-5) {
    //                 emparr.push(data[i]);
    //             }
    //             if(i==11)
    //             {
    //                 emparr.push(data[i]);
    //             }
    //         }
    //         for(let i=0; i < 3; i++) {
    //             emparr.push("");
    //         }
    //         // console.log(emparr);
    //         jobStatusData.push(emparr);
    //         selectCount = selectCount+1;
    //     }
    //     else {
    //         // console.log(data[0])
    //         jobStatusData = jobStatusData.filter(function(e) { if(e[0]!== data[0]) return e  })
    //         selectCount = selectCount-1;
    //     }

    //     if(jobStatusData.length == 0)
    //     {
    //         $('#jobStatusTbl').html('');
    //     } else {
    //         append_job_status();
    //     }
    // }

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
            { id: '0', name: 'Q.A. PENDING'}, 
            { id: '1', name: 'Q.A. SCHEDULED'}, 
            { id: '2', name: 'Q.A. RE-SCHEDULED'}, 
            { id: '3', name: 'Q.A. IN PROGRESS'}, 
            { id: '4', name: 'NEED ALTERATION'}, 
            { id: '5', name: 'Q.A. PASS'}, 
            { id: '6', name: 'Q.A. PASS COND.'}, 
            { id: '7', name: 'Q.A. FAIL'}, 
        ];

        $('#qaStatusTbl').html('');
        let hai = "AM";
        let list = {
            data: data.qaStatusData,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Q.A. Request Sent\nDate & Time', width: '7%', align: 'center', readOnly: true },
                // { type: 'calendar', title: 'Q.A. Scheduled\nDate & Time', width: '7%', align: 'left', options: { format:'DD/MM/YYYY HH12:MI', time:1 } },
                { type: 'calendar', title: 'Q.A. Scheduled\nDate & Time', width: '7%', align: 'center', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                // { type: 'calendar', title: 'Q.A. Scheduled\nDate & Time', width: '7%', align: 'left', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM' , time:1 } },
                { type: 'dropdown', title: 'Q.A. Status.', width: '7%', align: 'left', source: common_dd, readOnly: true },
                { type: 'text', title: 'Q.A. Status Update\nDate & Time', width: '7%', align: 'left',readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        cadReference_vm = new Vue({
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
    
    // *********************************************************************************************************************************** 
    // JOB STATUS UPDATE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_job_status(data) {
        let common_dd = [
            { id: '0', name: 'IN - QUEUE'}, 
            { id: '1', name: 'JOB SCHEDULED'}, 
            { id: '2', name: 'JOB RE-SCHEDULED'}, 
            { id: '3', name: 'JOB IN PROGRESS'}, 
            { id: '4', name: 'Q.A. REQUEST SENT'}, 
            { id: '5', name: 'ALT. PENDING'}, 
            { id: '6', name: 'ALT. IN PROGRESS'}, 
            { id: '7', name: 'JOB COMPLETED'}, 
            { id: '8', name: 'RE-WORK'}, 
        ];

        let updatedRow = '';
        let job_sta_update = '';
        let job_re_sta_update = '';
        let job_sta = '';

        $('#jobStatusTbl').html('');
        let list = {
            data: data.jobStatusData,
            columns: [
                { title:'id', type:'hidden'},
                { title:'Job Status Update', type:'hidden'},
                { title:'Job Re Status Update', type:'hidden'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Assigned\nCAD Reference No.', width: '7%', align: 'left', readOnly: true },
                { type: 'calendar', title: 'Job Scheduled\nDate & Time', width: '7%', align: 'center', options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                { type: 'dropdown', title: 'Job Status', width: '7%', align: 'left', source: common_dd},
                { type: 'text', title: 'Job Status Update\nDate & Time', width: '7%', align: 'left', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 10) 
                {
                    updatedRow = row;
                    job_sta = val;
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 1)
                {
                    job_sta_update = val;
                }
                if(col == 2)
                {
                    job_re_sta_update = val;
                }
                if(col == 9) {
                    if(job_sta_update == 0 && (job_sta == 1 || updatedRow == ""))
                    {
                        $(cell).addClass('readonly');
                    }
                    else if(job_re_sta_update == 1 && job_sta == 2 && updatedRow == row)
                    {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 10 && val == 7 && updatedRow == "")
                {
                    $(cell).addClass('readonly');
                }
            }
        };

        job_status_tbl_vm = new Vue({
            el: '#jobStatusTbl',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });

    }
    
    // *********************************************************************************************************************************** 
    // JOB STATUS UPDATE ENDS HERE 
    // ***********************************************************************************************************************************
    

    $('#getValues').click(function () {
        
        $('.herr').hide();
        if($('#dep_remarks').val() == "" || $('#dep_remarks').val() == null ) {
            $('#err_dep_remarks').html("Fill cad dept. remarks");
            $('#err_dep_remarks').show();
        }
        else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    updateFunction();
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

    function updateFunction() {

        let dataform = new FormData();
        let job_status_data = job_status_tbl_vm.getData();
        dataform.append('job_status_data', JSON.stringify(job_status_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('request_id', reqId);
        dataform.append('dep_remarks', $('#dep_remarks').val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/UpdateCadQueueRemark',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let res = JSON.parse(data);
                if(res.status == "success")
                {
                    // getCadRequest();
                    if(CADUpload.selectedFiles == 0) {
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(okay => {
                            if(okay) {
                                window.location.href = base_path+"company/mcaduser/cadqueuelist";
                            }
                        });
                    } else {
                        CADUpload.startUpload();
                    }
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    let type = "cad_qa_request";

    let CADUpload = $("#cadReqImageUpload").uploadFile({
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
            };
        },
        afterUploadAll:function () {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('saved')
            ).then(okay => {
                if(okay) {
                    window.location.href = base_path+"company/mcaduser/cadqueuelist";
                }
            });
        },
        autoSubmit: false
    });

    $("#cadReqImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    
});