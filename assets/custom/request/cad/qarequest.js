$(document).ready(function () {
    
   
    getCadRequest();
    getMerchantRequestImages();
    getCadRequestImages();
    let selectCount = 0;
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
    function getCadRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        let request = $.ajax({
            type: "POST",
            //url: base_path + 'request/Cadrequest/getcadqarequestDetails',
            url: base_path + 'MerchantRequestSent/getcadqarequestsentDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
               
                cad_requirement_data = JSON.parse(data);
                append_cad_request(cad_requirement_data);
                append_attach_reference(cad_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getMerchantRequestImages() {
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
                 let subscriberId = imageJSON.subscriber_id;
                if(imageJSON.images.length > 0) {
                    $('.cadImg').show();
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
                                    //  '<a href="javascript:void(0);" data-id='+imageJSON.images[i].wip_files_id+' class="deleteImg" title="Delete">'+
                                    //     '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
                                    // '</a>'+
                                    
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                }
                }  else {
                    $('.CADImageView').hide();
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_cad_request_checkbox(data) {
        $('#cadRequest').html('');
        let filterIds = checkid.split(',');

       let filteredData = data.data.filter(row => filterIds.includes(row[0].toString()));  // Ensure toString() for proper comparison

        let dd = [], updatedRow = '', index = '', nVal = '';
        let PurposeData = [ 'Costing', 'FCC - Sample', 'FCC - Bulk', 'Cutting - Sample', 'Cutting - Bulk', 'Bit Cutting - Sample',
            'Bit Cutting - Bulk', 'Others' ];
            let list = {
            data: filteredData,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '5%', align: 'center' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '7%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData, readOnly: true },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'],readOnly: true },
                { type: 'text', title: 'If Revised or In-line\nPrevious CAD Ref. No.', width: '10%', align: 'center', readOnly: true },
                { type: 'dropdown', title: 'Required\nSize(s)', width: '5%', align: 'left', source: data.sizeData, multiple: true,readOnly: true },
                { type: 'text', title: 'Assigned\n CAD Reference No.', width: '10%', align: 'left', readOnly: true },
                { title:'jOB Status', width:'0%',align:'center',type:'hidden'},
                { title:'QA Req ID', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 1) 
                {
                    getReferenceValue(list.data[row], val);
                }
            },
        };

        cadRequest_vm = new Vue({
            el: '#cadRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }

    function append_cad_request(data) {
    $('#cadRequest').html('');
    let PurposeData = [
        'Costing', 'FCC - Sample', 'FCC - Bulk', 'Cutting - Sample', 'Cutting - Bulk',
        'Bit Cutting - Sample', 'Bit Cutting - Bulk', 'Others'
    ];
    let filterIds = checkid.split(',');
     console.log(filterIds);
    let filteredData = data.data.filter(row => filterIds.includes(row[0].toString()));

   

    

    let list = {
        data: filteredData,
        columns: [
            { title: 'ID', width: '0%', align: 'center', type: 'hidden' }, // 0
           { type: 'text', title: 'Delete', width: '5%', align: 'center', readOnly: true }, 
            { type: 'text', title: 'P.O. No.', width: '7%', align: 'left', readOnly: true }, // 3
            { type: 'text', title: 'Combo', width: '7%', align: 'left', readOnly: true },    // 4
            { type: 'text', title: 'Component', width: '7%', align: 'left', readOnly: true },// 5
            { type: 'text', title: 'Size Spec / Fit', width: '7%', align: 'left', readOnly: true }, // 6
            { type: 'text', title: 'Requirement', width: '7%', align: 'left', readOnly: true }, // 7
            { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData, readOnly: true },
            { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'], readOnly: true },
            { type: 'text', title: 'Previous CAD Ref. No.', width: '10%', align: 'center', readOnly: true },
            { type: 'dropdown', title: 'Required Size(s)', width: '5%', align: 'left', source: data.sizeData, multiple: true, readOnly: true },
            { type: 'text', title: 'Assigned CAD Ref No.', width: '10%', align: 'left', readOnly: true },
            { title: 'Job Status', width: '0%', align: 'center', type: 'hidden' },
            { title: 'QA Req ID', width: '0%', align: 'center', type: 'hidden' }
        ],
        minDimensions: [4, 1],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false
    };

    cadRequest_vm = new Vue({
        el: '#cadRequest',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, list);
            Object.assign(this, spreadsheet);

            // ✅ Inject delete icons using updateTable
          const tableRows = this.$el.querySelectorAll('tbody tr');
        tableRows.forEach((tr, rowIndex) => {
            const deleteCell = tr.querySelector('td:nth-child(3)'); // 3rd column = index 2
            if (deleteCell) {
                const cadId = spreadsheet.getRowData(rowIndex)[0];
                deleteCell.innerHTML = `<button class="remove-row-btn" 
                    data-cadid="${cadId}" 
                    style="color:red; font-weight:bold; border:none; background:none; cursor:pointer;">✖</button>`;
            }
        });
            // ✅ Handle delete button click
            this.$el.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-row-btn')) {
                    const tr = e.target.closest('tr');
                    const rowIndex = parseInt(tr.getAttribute('data-y'));
                    if (!isNaN(rowIndex)) {
                        const deletedRow = spreadsheet.getRowData(rowIndex);
                        console.log("Deleted Row:", deletedRow);
                        spreadsheet.deleteRow(rowIndex);
                    }
                }
            });
        }
    });
}

    
    function getReferenceValue(data, status) {
        if(status == true) {
            selectCount = selectCount+1;
        }
        else {
            selectCount = selectCount-1;
        }
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
    

    $('#getValues').click(function () {
        
        $('.herr').hide();
        // if(selectCount <= 0) {
        //     swalWithBootstrapButtons.fire(
        //         alertMessageFunction('selecterror')
        //     );
        // }
         if($('#qa_cutoff_date').val() == "" || $('#qa_cutoff_date').val() == null ) {
            $('#err_qa_cutoff_date').html("Select Q.A Cutoff Date");
            $('#err_qa_cutoff_date').show();
        }
        else if($('#dep_note').val() == "" || $('#dep_note').val() == null ) {
            $('#err_dep_note').html("Fill Note");
            $('#err_dep_note').show();
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
        let cad_req_data = cadRequest_vm.getData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('request_id', reqId);
        dataform.append('qa_cutoff_date', $('#qa_cutoff_date').val());
        dataform.append('dep_note', $('#dep_note').val());
        dataform.append('cad_req_data', JSON.stringify(cad_req_data));

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/updateCadQARequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let res = JSON.parse(data);
                if(res.status == "success") {
                    if(CADUpload.selectedFiles === 0) {
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(okay => {
                            if(okay) {
                                //window.location.href = base_path+"company/mcaduser/cadqueuelist";
                                  window.location.href = base_path+"company/mcaduser/cadsentlist";
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
        processfail: function (e, data) {
            window.location.href = base_path+"company/mcaduser/cadqueuelist";
        },
        autoSubmit: false
    });

    $("#cadReqImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    
});