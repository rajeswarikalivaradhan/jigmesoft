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
            url: base_path + 'request/Cadrequest/getqaqueueDetails',
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
                
                if(cad_requirement_data.qa_status == 'completed') {
                    $('#qa_req').hide();
                } else {
                    $('#qa_req').show();
                }
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
                let subscriberId = imageJSON.subscriber_id;
               for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.ImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON.images[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/cad/'+subscriberId+'/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/cad/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    // '<a href="javascript:void(0);" data-id='+imageJSON[i].wip_files_id+' class="deleteImg" title="Delete">'+
                                    //     '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
                                    // '</a>'+
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
                let subscriberId = imageJSON.subscriber_id;
               for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.CADImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON.images[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/cad/'+subscriberId+'/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/cad/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    // '<a href="javascript:void(0);" data-id='+imageJSON[i].wip_files_id+' class="deleteImg" title="Delete">'+
                                    //     '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
                                    // '</a>'+
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
                let subscriberId = imageJSON.subscriber_id;
               for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.QAImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON.images[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/cad/'+subscriberId+'/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/cad/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    // '<a href="javascript:void(0);" data-id='+imageJSON[i].wip_files_id+' class="deleteImg" title="Delete">'+
                                    //     '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
                                    // '</a>'+
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
            { id: '0', name: 'IN QUEUE'}, 
            { id: '1', name: 'SCHEDULED'}, 
            { id: '2', name: 'RE-SCHEDULED'}, 
            { id: '3', name: 'Q.A.IN PROGRESS'},
            { id: '4', name: 'DISCREPANCY'}, 
            { id: '5', name: 'PASS'}, 
            { id: '6', name: 'PASS COND.'}, 
            { id: '7', name: 'FAIL'}, 
            { id: '9', name: 'IN-QUEUE RR'},
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
                { title:'Mark', type:'checkbox', width:'2%'},
                
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Assigned\n CAD Reference No.', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Q.A. Request Sent\nDate & Time', width: '7%', align: 'center', readOnly: true },
                { type: 'calendar', title: 'Q.A. Scheduled\nDate & Time', width: '7%', align: 'center', options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                { type: 'dropdown', title: 'Q.A. Status.', width: '7%', align: 'center', source: common_dd , filter: qastatus_updation,},
                { type: 'text', title: 'Q.A. Status Update\nDate & Time', width: '7%', align: 'center',readOnly: true },
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
                if(col == 11)
                {
                   if(data.qaStatusData[row][3] == true ){
                     $(cell).removeClass('readonly');
                     
                      if(data.qaStatusData[row][12] == 0 || data.qaStatusData[row][12] == "0" || data.qaStatusData[row][12] == 1 || data.qaStatusData[row][12] == "1"  || data.qaStatusData[row][12] == 2 || data.qaStatusData[row][12] == "2"  || data.qaStatusData[row][12] == "9" ) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                   }else{
                      $(cell).addClass('readonly');
                    
                   }
                    
                    
                }
                if(col == 12)
                {

                    //  if(data.qaStatusData[row][3]== false){
                    //      $(cell).addClass('readonly');
                         
                    // }else{
                    //        $(cell).removeClass('readonly');
                    // }

                    //  if(data.qaStatusData[row][12] == 0 || data.qaStatusData[row][12] == "0" || data.qaStatusData[row][12] == "5" || data.qaStatusData[row][12] == 5  || data.qaStatusData[row][12] == "6" || data.qaStatusData[row][12] == 6 || data.qaStatusData[row][12] == "7" || data.qaStatusData[row][12] == 7 ) {
                    //      $(cell).addClass('readonly');
                    // } 

                  
                    setTimeout(() => {
                    const statusId = val?.toString();

                  
                    let backgroundColor = '';
                    let textColor = 'black';
                    const group1 = ['0', '1', '2', '3'];
                  
                    const group2 = ['5', '6'];
                   
                    const group3 = ['7','4','9'];

                    if (group1.includes(statusId)) {
                        backgroundColor = '#FFA519'; // light yellow
                        } else if (group2.includes(statusId)) {
                        backgroundColor = '#5DE684'; // light green
                       
                    } else if (group3.includes(statusId)) {
                        backgroundColor = '#fc0303ff'; // light PURPLE
                       
                    }

                    
                    $(cell).css({
                        'background-color': backgroundColor,
                        'color': textColor,
                        'font-weight': 'bold'
                    });
                }, 10);

                }
                
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


    function qastatus_updation(instance, cell, c, r, source) {

        let qa_status = instance.jexcel.getValueFromCoords(12, r);
        let checkbox = instance.jexcel.getValueFromCoords(3, r);
         let filteredOptions1;
         if (checkbox === true) {
             let filteredOptions;
    if (qa_status == '0' || qa_status == '1' || qa_status == '2' || qa_status == '3'|| qa_status == '9') {
         filteredOptions = source.map(function (item) {
           
            if (item.id == '0' || item.id == '1' || item.id == '2' || item.id == '9' ) {
                return { ...item, disabled: true };
            } else {
                return { ...item, disabled: false };
            }
        });
    }else if (qa_status == '4' ) {
         filteredOptions = source.map(function (item) {
           
            if (item.id == '0' || item.id == '1' || item.id == '2'  || item.id == '3'|| item.id == '9' ) {
                return { ...item, disabled: true };
            } else {
                return { ...item, disabled: false };
            }
        });
    }else{
         filteredOptions = source.map(function (item) {
            return { ...item, disabled: true }; // Disable all options for other job statuses
        });
    }
    return filteredOptions;

         }else{
             filteredOptions1 = source.map(function (item) {
            return { ...item, disabled: true }; // Disable all options for other job statuses
        });
          //return $(cell).addClass('readonly');
           return filteredOptions1;
         }

        
    }
    
    // *********************************************************************************************************************************** 
    // QA STATUS UPDATE ENDS HERE 
    // ***********************************************************************************************************************************

    $('#getValues').click(function () {

        let QATblData = QAReference_vm.getData();

        $('.herr').hide();
        if($('#qa_dept_remarks').val() == "" || $('#qa_dept_remarks').val() == null) {
            $('#err_qa_dept_remarks').html("Fill Note");
            $('#err_qa_dept_remarks').show();
        }
        else if ($('input[type="checkbox"]:checked').length === 0) {
        swalWithBootstrapButtons.fire(
            'Error!',
            'Please select at least one QA STATUS.',
            'warning'
        );
    } 
        // else if(qaImageCount == 0 && CADUpload.selectedFiles == 0 && ( QATblData[0][11] == 5 || QATblData[0][11] == 6 || QATblData[0][11] == 7)) {
        //     swalWithBootstrapButtons.fire(
        //         // *** CONFIRMATION MESSAGE *** //
        //         alertMessageFunction('img_error')
        //     );
        // }
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
            url: base_path + 'request/Cadrequest/UpdateCadQAQueueList',
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