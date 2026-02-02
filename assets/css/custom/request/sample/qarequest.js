$(document).ready(function () {

    var sampleRequest_vm = '';
    var sampleReference_vm = '';
    var selectCount = 0;

    getSampleRequest();
    getSampleRequestImages();

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

    function getSampleRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('request_id', reqId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/getQARequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                $('#attachReference').html('');
                sample_requirement_data = JSON.parse(data);
                append_sample_request(sample_requirement_data);
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

    function append_sample_request(data) {
        $('#sampleRequest').html('');
        let PurposeData = [ 'Development', 'Order Conf.', 'Shipment' ];;

        let qa_data = data.data;
        let table_data = [];
        for (let i = 0; i < qa_data.length; i++) {
            let combineValue = [ qa_data[i].sample_requirement_id, qa_data[i].po_enq_ref_id, qa_data[i].combo_id, qa_data[i].component_id,
                qa_data[i].color_id, qa_data[i].spec_code_id, qa_data[i].sample_requirement, qa_data[i].purpose, qa_data[i].category, qa_data[i].if_revised,
                qa_data[i].req_size, qa_data[i].req_qty ];
            table_data.push(combineValue);
        }

        let list = {
            data: table_data,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '7%', align: 'left' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData, readOnly: true },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'], readOnly: true },
                { type: 'dropdown', title: 'If Revised or In-line\n Prev. Sample Ref. No.', width: '8%', align: 'left', source: data.sampleRefNo, readOnly: true },
                { type: 'dropdown', title: 'Required\n Size(s)', width: '7%', align: 'left', source: data.sizeData, multiple: true, readOnly: true },
                { title: 'Qty. (Pcs.)', width: '5%', align: 'center', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 1) 
                {
                    getReferenceValue(list.data[row][0], val, data.data);
                }
            }
        };

        sampleRequest_vm = new Vue({
            el: '#sampleRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }

    function getReferenceValue(id, status, data) {
        let f_arr = data.filter(x => x.sample_requirement_id == id).map(x=> x);
        if(status == true) {
            let combineValue = [ f_arr[0].sample_requirement_id, f_arr[0].po_enq_ref_id, f_arr[0].combo_id, f_arr[0].component_id,
                f_arr[0].color_id, f_arr[0].spec_code_id, f_arr[0].grad_measure_chart , f_arr[0].artwork, f_arr[0].measure_details, 
                f_arr[0].buyer_sample, f_arr[0].buyer_comment ];
            requirementData.push(combineValue);
            selectCount = selectCount+1;
        }
        else {
            requirementData = requirementData.filter(x => {
                return x[0] != id;
            });
            selectCount = selectCount-1;
        }
        append_attach_reference();
    }

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_attach_reference() {
        let data = requirementData;
        let common_dd = [
            {id: '1', name: 'Attached'}, 
            {id: '2', name: 'Pending'}, 
            {id: '3', name: 'N.A.'}, 
        ];
        $('#attachReference').html('');
        let list = {
            data: data,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'hidden', title: 'Mark', width: '8%', align: 'left' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Approved & Graded\n Measurement Chart', width: '7%', align: 'left', source: common_dd, readOnly: true },
                { type: 'dropdown', title: 'Complete Artwork', width: '7%', align: 'left', source: common_dd, readOnly: true },
                { type: 'dropdown', title: 'How to Measure\n Details', width: '7%', align: 'left', source: common_dd, readOnly: true },
                { type: 'dropdown', title: 'Buyers Original \nSample or Pattern', width: '7%', align: 'left', source: common_dd, readOnly: true },
                { type: 'dropdown', title: "Buyer's Comments", width: '7%', align: 'left', source: common_dd, readOnly: true },
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

    $('#getValues').click(function () {
        if(selectCount <= 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
            
            let req_data = sampleRequest_vm.getData();
            let ref_data = sampleReference_vm.getData();
            req_data = req_data.filter(function(e) { if(e[1] === true) return e });

            $('.herr').hide();
            if($('#qa_cutoff_date').val() == "" || $('#qa_cutoff_date').val() == null ) {
                $('#err_qa_cutoff_date').html("Select qa cutoff date");
                $('#err_qa_cutoff_date').show();
            } 
            else if($('#sam_dept_note').val() == "" || $('#sam_dept_note').val() == null ) {
                $('#err_sam_dept_note').html("Fill sample department note");
                $('#err_sam_dept_note').show();
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
                                if(j >= 7) {
                                    decEmpAr.push(data_a[j]);
                                }
                            }
                            req_empArr.push(decEmpAr);
                        }

                        let ref_empArr = [];
                        for(let i=0; i < ref_data.length; i++) {
                            let data_a = ref_data[i];
                            let decEmpAr = [];
                            for(let j=0; j < data_a.length; j++) {
                                if(j == 0) {
                                    decEmpAr.push(data_a[j]);
                                }
                                if(j >= 6) {
                                    decEmpAr.push(data_a[j]);
                                }
                            }
                            ref_empArr.push(decEmpAr);
                        }

                        let finalArray = [];
                        for(let i=0; i < req_empArr.length; i++)  {
                            const arr1 = req_empArr[i];
                            const arr2 = ref_empArr[i];
                            const conArr = arr1.concat(arr2);
                            finalArray.push(conArr);
                        }

                        updateFunction(finalArray);
                    } 
                    else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
                    }
                });
            }
        }
    });

    function updateFunction(finalData) {

        let dataform = new FormData();
        dataform.append('data', JSON.stringify(finalData));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('qa_cutoff_date', $('#qa_cutoff_date').val());
        dataform.append('sam_dept_note', $('#sam_dept_note').val());
        dataform.append('request_id', reqId);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/updateQARequestDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay) {
                        window.location.href = base_path + 'company/msamplinguser/samplequeuelist';
                    }
                });
                // data= JSON.parse(data);
                // if(data.status == '201') {
                //     getSampleRequest();
                // }
                // else {
                    // setTimeout(() => {
                    //     window.location.href = base_path + 'company/msamplinguser/samplequeuelist';
                    // }, 1000);
                // }
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    let bom1Upload = $("#bom1ImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        // url:base_path+'merchant/enqFileUpload',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        // dynamicFormData:function () {
        //     return {'id':GlbInsertId};
        // },
        autoSubmit: false
    });

    $("#bom1ImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});