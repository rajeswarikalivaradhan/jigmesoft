$(document).ready(function () {

    var cadRequest_vm = '';
    var cadReference_vm = '';
    var selectCount = 0;
    getCadRequest();

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
    // CAD REQUEST STARTS HERE 
    // ***********************************************************************************************************************************
    
    var requirementData = [];
    var requirementReferenceData = [];

    function getCadRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/getCadRequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                cad_requirement_data = JSON.parse(data);
                append_cad_request(cad_requirement_data);
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
                { type: 'checkbox', title: 'Mark', width: '8%', align: 'left' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'] },
                { type: 'dropdown', title: 'If Revised or In-line\nPrevious CAD Ref. No.', width: '10%', align: 'center', source: data.cadRefNo, filter: refFilter },
                { type: 'dropdown', title: 'Required\nSize(s)', width: '5%', align: 'left', source: data.sizeData, multiple: true, readOnly: true }
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
                // if(col == 8) 
                // {
                //     console.log('hai')
                // }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 8) 
                {
                    category = val;
                }
                if(col == 9) 
                {
                    if(category == "New" || category == "")
                    {
                        $(cell).addClass('readonly');
                    }
                    else {
                        $(cell).removeClass('readonly');
                    }
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

    function refFilter(instance, cell, c, r, source)
    {
        // let req_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let size_id = instance.jexcel.getValueFromCoords(c - 4, r);
        let component_id = instance.jexcel.getValueFromCoords(c - 5, r);
        let combo_id = instance.jexcel.getValueFromCoords(c - 6, r);
        let po_enq_id = instance.jexcel.getValueFromCoords(c - 7, r);

        if (size_id != "" && component_id != "" && combo_id != "" && po_enq_id != "") {
            return source.filter(function (item) {
                if ((item.size_id == size_id) && (item.component_id == component_id) &&
                    (item.combo_id == combo_id) && (item.po_enq_id == po_enq_id) ) return true;
            })
        } else {
            return [];
        }
    }

    function getReferenceValue(data, status) {

        if(status == true) {
            let emparr = [];
            let length = data.length;
            for(let i=0; i < data.length; i++) {
                if(i < length-5) {
                    emparr.push(data[i])
                }
            }
            for(let i=0; i < 5; i++) {
                emparr.push("")
            }
            // console.log(emparr);
            requirementData.push(emparr);
            selectCount = selectCount+1;
        }
        else {
            // console.log(data[0])
            requirementData = requirementData.filter(function(e) { if(e[0]!== data[0]) return e  })
            selectCount = selectCount-1;
        }
        append_attach_reference();
    }

    // *********************************************************************************************************************************** 
    // CAD REQUEST ENDS HERE 
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
                { type: 'dropdown', title: 'Approved & Graded\n Measurement Chart', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: 'Complete Artwork', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: 'How to Measure\n Details', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: 'Buyers Original \nSample or Pattern', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: "Buyer's Comments", width: '7%', align: 'left', source: common_dd },
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

    $('#getValues').click(function () {

        if(selectCount <= 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
            
            let req_data = cadRequest_vm.getData();
            let ref_data = cadReference_vm.getData();
            req_data = req_data.filter(function(e) { if(e[1] === true) return e })
            // console.log(req_data);
            let validate_filed_1 = [7,8];
            let validatedErrorCount_1 = validateForm(validate_filed_1, req_data);
            // console.log(validatedErrorCount_1);
            
            let validate_filed_2 = [6,7,8,9,10];
            let validatedErrorCount_2 = validateForm(validate_filed_2, ref_data);
            // console.log(validatedErrorCount_2);

            $('.herr').hide();
            if($('#req_type').val() == "" || $('#req_type').val() == null ) {
                $('#err_req_type').html("Select request type");
                $('#err_req_type').show();
            } 
            else if($('#merchant_note').val() == "" || $('#merchant_note').val() == null ) {
                $('#err_merchant_note').html("Fill merchant note");
                $('#err_merchant_note').show();
            }
            else if(validatedErrorCount_1 == 0 && validatedErrorCount_2 == 0) {
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
            else {
                // *** VALIDATION ERROR MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
            }
        }
    });

    let reqId = '';

    function updateFunction(finalData) {
        
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(finalData));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('req_type', $('#req_type').val());
        dataform.append('cutoff_date', $('#cutoff_date').val());
        dataform.append('merchant_note', $('#merchant_note').val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/createCadRequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                data = JSON.parse(data);
                // getCadRequest();
                // // *** SAVED MESSAGE *** //
                if(data.status == "success")
                {
                    reqId = data.requestId;
                    if(CADUpload.seletedFiles == 0)
                    {
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(okay => {
                            if(okay)
                            {
                                window.location.href = base_path + 'MerchantRequestSent/cad';
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
    
    let type = 'cad_request';

    let CADUpload = $("#reqCADImageUpload").uploadFile({
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
                if(okay)
                {
                    window.location.href = base_path + 'MerchantRequestSent/cad';
                }
            });
        },
        autoSubmit: false
    });

    $("#reqCADImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // *********************************************************************************************************************************** 
    // CAD REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});