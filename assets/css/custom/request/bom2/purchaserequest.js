$(document).ready(function () {

    // **************************************** //
    var sampleRequest_vm = '';
    var sampleReference_vm = '';
    var selectCount = 0;
    var selectedArray = [];
    var requirementData = [];
    var sizeData = [];
    var mode = 'add';
    var req_id = '';
    $('#saveRequestDetails').hide();

    getPurchaseRequest();

    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    // Change function
    $('#cad_deprt').on('change', function(){
        let cad_dept = $('#cad_deprt').val();
        $('#fab_dept').val(cad_dept);
        $('#bom_dept').val(cad_dept);
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

    $('#sample').prop('checked',true);
    $('#qty_type').html('( SAMPLE QTY. )');
    
    $('input[type=radio][name=purchase_req_type]').change(function() {
        let qty_type = $(this).val();
        if(qty_type == 'sample') {
            $('#sample').prop('checked',true);
            $('#qty_type').html('( SAMPLE QTY. )');
        }
        else if(qty_type == 'bulk') {
            $('#bulk').prop('checked',true);
            $('#qty_type').html('( BULK QTY. )');
        }
        else if(qty_type == 'revised') {
            $('#revised').prop('checked',true);
            $('#qty_type').html('( REVISED QTY. )');
        }
        else if(qty_type == 'shortage') {
            $('#shortage').prop('checked',true);
            $('#qty_type').html('( SHORTAGE QTY. )');
        }
    });

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getPurchaseRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bom2request/getPurchaseRequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                if(sample_requirement_data.sourcing_result.length > 0) {
                    req_id = sample_requirement_data.req_data[0].request_id; 
                    $('#req_type').val(sample_requirement_data.req_data[0].req_type);
                    $("#req_type").trigger('change');
                    $('#req_date').val(sample_requirement_data.req_data[0].req_date);
                    $('#cutoff_date').val(sample_requirement_data.req_data[0].cutoff_date);
                    $('#merchant_note').val(sample_requirement_data.req_data[0].merchant_note);
                    $('#'+sample_requirement_data.req_data[0].purchase_req_type).prop('checked',true);
                    $('input[type=radio][name=purchase_req_type]').trigger('change');
                }
                append_sample_request(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    let req_sel_data = [];
    let table_data = [];
    let sourcing_result = [];
    let copy_sourcing_result = [];

    function append_sample_request(data) {
        // *** ASSIGING SIZEDATA VALUE FROM DATA *** //

        sourcing_result = data.sourcing_result;
        copy_sourcing_result = data.sourcing_result;
        req_sel_data = req_sel_data.concat(sourcing_result);

        // *** JEXCEL STARTS *** //
        $('#sampleRequest').html('');
        
        let list = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'bom_1_con_id', width:'0%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '5%', align: 'left' },
                { type: 'text', title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Blend (%) / Content / Material', width: '12%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', readOnly: true },
                { title: 'UOM', width: '7%', align: 'center', readOnly: true },
                { title: 'Consolidated\n Reqd. BOM Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'Excess Qty.\n (%)', width: '7%', align: 'center', readOnly: true },
                { title: 'Planned BOM Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2) 
                {
                    if(val === true) {
                        selectedArray.push(row);
                    } else {
                        selectedArray = selectedArray.filter(function(e) {return e != row})
                    }
                    getReferenceValue(data.totData[row], val, row);
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

        if(sourcing_result.length > 0) {
            mode = 'edit';
            append_attach_reference(sourcing_result);
            $('#saveRequestDetails').show();
        }
    }


    function getReferenceValue(data, status, row) {
        let bom_data = data;
        let id = bom_data.bom_1_req_consld_id;

        if(selectedArray.length === 0) {
            $('#saveRequestDetails').hide();
        } else {
            $('#saveRequestDetails').show();
        }

        if(status == true) {
            
            let count = 0;
            for (i = 0; i < sourcing_result.length; i++) {
                if(id == sourcing_result[i][1]) {
                    count++;
                    sourcing_result[i][2] = true;
                    copy_sourcing_result.push(sourcing_result[i]);
                }
            }

            if(count == 0) {
                let combineValue = [ 'add', bom_data.bom_1_req_consld_id, status, bom_data.item_desc, bom_data.bcm,
                bom_data.sourcing_advice, bom_data.vendor_location, bom_data.vendor_name_address, bom_data.contact_email, 
                bom_data.gst, bom_data.online_order_sys, bom_data.pass_expiry_date ];
                table_data.push(combineValue);
                req_sel_data.push(combineValue);
            }

            selectCount = selectCount+1;
        }
        else {
            for (i = 0; i < req_sel_data.length; i++) {
                if(id == req_sel_data[i][1]) {
                    copy_sourcing_result = copy_sourcing_result.filter(function(e) { if(e[1] !== req_sel_data[i][1]) return e });
                    req_sel_data[i][2] = false;
                }
            }
            
            table_data = table_data.filter(function(e) { if(e[1] !== data['bom_1_req_consld_id']) return e });
            selectCount = selectCount-1;
        }
        append_attach_reference(copy_sourcing_result);
    }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
 
    function append_attach_reference(data) {
        
        var tblData = data.concat(table_data);

        tblData = tblData.reduce((unique, o) => {
            if(!unique.some(obj => obj[3] === o[3] && obj[4] === o[4])) {
              unique.push(o);
            }
            return unique;
        },[]);

        // console.log(tblData)

        $('#attachReference').html('');
        let list = {
            data: tblData,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'status', width:'0%',align:'center',type:'hidden'},
                { title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content / Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Sourcing Advice', width: '8%', align: 'left', readOnly: true },
                { title: 'Vendor Location', width: '8%', align: 'left', readOnly: true },
                { title: 'Vendor Name & Address', width: '7%', align: 'left', readOnly: true },
                { title: 'Contact Person / e-mail ID /\n Phone / Mobile', width: '8%', align: 'left', readOnly: true },
                { title: 'GST / IE Code Details', width: '7%', align: 'left', readOnly: true },
                { title: 'If On-line Ordering System\n Website / User ID / Password', width: '10%', align: 'left', readOnly: true },
                { title: "Password Expiry\n Date & Time", width: '7%', align: 'left', readOnly: true },
            ],
            minDimensions: [4, 0],
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

    // ******** SAVE REQUEST DETAILS STARTS HERE **************** //

    $('#saveasdraft').click(function () {
        if(selectCount <= 0 && sourcing_result.length == 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
            updateFunction('draft');
        }
    });

    $('#getValues').click(function () {
        if(selectCount <= 0 && sourcing_result.length == 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
            updateFunction('save');
        }
    });

    function updateFunction(type) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(req_sel_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('req_type', $('#req_type').val());
        dataform.append('cutoff_date', $('#cutoff_date').val());
        dataform.append('merchant_note', $('#merchant_note').val());
        dataform.append('purchase_req_type', $("input[name='purchase_req_type']:checked").val());
        dataform.append('mode', mode);
        dataform.append('req_id', req_id);
        dataform.append('type', type);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bom2request/createPurchaseRequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                getPurchaseRequest();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
                setTimeout(() => {
                    window.location.href = base_path + 'WorkInProcess/index/' + encodeURIComponent(btoa(enquiry_id));
                }, 1000);
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    // ******** SAVE REQUEST DETAILS ENDS HERE ***************** //

    // ******** SAVE AS DRAFT ENDS HERE ***************** //
    
    
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