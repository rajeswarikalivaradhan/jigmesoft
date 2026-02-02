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

    get_draft_value();

    //getPurchaseRequest();
    getBomRequestImages();

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
                if(col == 2) {
                    if(dataValue[i][col] === true) {
                        if(dataValue[i][10] === "") {
                            errorCount++;
                        }
                        if(dataValue[i][11] === "") {
                            errorCount++;
                        } 
                    }
                }
                
            }
        }
        return errorCount;
    }

    $('#SAMPLE').prop('checked',true);
    $('#qty_type').html('( SAMPLE QTY. )');
    
    $('input[type=radio][name=purchase_req_type]').change(function() {
        let qty_type = $(this).val();
        if(qty_type == 'SAMPLE') {
            $('#SAMPLE').prop('checked',true);
            $('#qty_type').html('( SAMPLE QTY. )');
            getPurchaseRequest();
        }
        else if(qty_type == 'BULK') {
            $('#BULK').prop('checked',true);
            $('#qty_type').html('( BULK QTY. )');
            getPurchaseRequest_bulk();
        }
        else if(qty_type == 'DISCREPANCY') {
            $('#DISCREPANCY').prop('checked',true);
            $('#qty_type').html('( DISCREPANCY QTY. )');
            getPurchaseRequest();
        }
        else if(qty_type == 'SHORTAGE') {
            $('#SHORTAGE').prop('checked',true);
            $('#qty_type').html('( SHORTAGE QTY. )');
            getPurchaseRequest();
        }
    });

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST STARTS HERE 
    // ***********************************************************************************************************************************
    
    
    function get_draft_value() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/get_draft_value',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                draft_val = JSON.parse(data);
                if(draft_val.type == "BULK") {
                    getPurchaseRequest_bulk();
                } else {
                    getPurchaseRequest();
                }
                
                if(draft_val.total > 0) {
                    $('#saveasdraft').hide();
                    $('#cleardraft').show();
                } else {
                    $('#saveasdraft').show();
                    $('#cleardraft').hide();
                }
                
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    function getPurchaseRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getPurchaseRequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                if(sample_requirement_data.sourcing_result.length > 0) {
                    if(sample_requirement_data.req_data.length > 0)
                    {
                        req_id = sample_requirement_data.req_data[0].request_id; 
                        $('#req_type').val(sample_requirement_data.req_data[0].req_type);
                        $("#req_type").trigger('change');
                        $('#req_date').val(sample_requirement_data.req_data[0].req_date);
                        $('#cutoff_date').val(sample_requirement_data.req_data[0].cutoff_date);
                        $('#merchant_note').val(sample_requirement_data.req_data[0].merchant_note);
                        $('#'+sample_requirement_data.req_data[0].purchase_req_type).prop('checked',true);
                        if(sample_requirement_data.sourcing_result.length > 0) {
                            $('input[type=radio][name=purchase_req_type]').attr('disabled',true);
                            $('#'+sample_requirement_data.req_data[0].purchase_req_type).attr('disabled',false);
                        }
                        
                    }
                }
                
                if(sample_requirement_data.bulk_count > 0) {
                    $('input[type=radio][id="DISCREPANCY"]').prop('disabled',false);
                    $('input[type=radio][id="SHORTAGE"]').prop('disabled',false);
                } else {
                     $('input[type=radio][id="DISCREPANCY"]').prop('disabled',true);
                     $('input[type=radio][id="SHORTAGE"]').prop('disabled',true);
                }
                
                append_sample_request(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getPurchaseRequest_bulk() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getPurchaseRequestDetails_bulk',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                if(sample_requirement_data.sourcing_result.length > 0) {
                    if(sample_requirement_data.req_data.length > 0)
                    {
                        req_id = sample_requirement_data.req_data[0].request_id; 
                        $('#req_type').val(sample_requirement_data.req_data[0].req_type);
                        $("#req_type").trigger('change');
                        $('#req_date').val(sample_requirement_data.req_data[0].req_date);
                        $('#cutoff_date').val(sample_requirement_data.req_data[0].cutoff_date);
                        $('#merchant_note').val(sample_requirement_data.req_data[0].merchant_note);
                        $('#'+sample_requirement_data.req_data[0].purchase_req_type).prop('checked',true);
                        if(sample_requirement_data.sourcing_result.length > 0) {
                            $('input[type=radio][name=purchase_req_type]').attr('disabled',true);
                            $('#'+sample_requirement_data.req_data[0].purchase_req_type).attr('disabled',false);
                        }
                        if(sample_requirement_data.bulk_count > 0) {
                            $('input[type=radio][id="DISCREPANCY"]').prop('disabled',false);
                            $('input[type=radio][id="SHORTAGE"]').prop('disabled',false);
                        } else {
                             $('input[type=radio][id="DISCREPANCY"]').prop('disabled',true);
                             $('input[type=radio][id="SHORTAGE"]').prop('disabled',true);
                        }
                    }
                }
                append_sample_request_bulk(sample_requirement_data);
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
        let qty_type = $("input[name='purchase_req_type']:checked").val();
        
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
                { title: 'Consolidated\n Reqd. BOM Qty.', width: '8%', align: 'right' },
                { title: 'Excess Qty.\n (%)', width: '7%', align: 'right' },
                { title: 'Planned BOM Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title:'b_count', width:'0%',align:'center',type:'hidden'},
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
                        getReferenceValue(data.totData[row], val, row);
                        $('#cleardraft').hide();
                        $('#saveasdraft').show();
                        $('input[type=radio][name=purchase_req_type]').prop('disabled',true);

                    } else {
                        var check_count = $("[type='checkbox']:checked").length;
                        if(check_count == 0 ) {
                            $('input[type=radio][name=purchase_req_type]').prop('disabled',false);
                        }
                        //selectedArray = selectedArray.filter(function(e) {return e != row})
                        remove_row(row);
                        $('#cleardraft').hide();
                        $('#saveasdraft').show();

                    }
                    //console.log(selectedArray);

                }

                if(col == 10) 
                {
                    if(checkVal === true) {
                        con_val = val;
                    } else {
                        con_val = '';
                    }
                    $('#cleardraft').hide();
                    $('#saveasdraft').show();
                    if(val == '') {
                        con_val = '';
                    } else {
                        con_val = val;
                    }
                    
                }
                if(col == 11) 
                {
                    if(checkVal === true) {
                        ex_val = val;
                    } else {
                        ex_val = '';
                    }
                    $('#cleardraft').hide();
                    $('#saveasdraft').show();
                    if(val == '') {
                        ex_val = '';
                    } else {
                        ex_val = val;
                    }
                }
                
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2) 
                {
                    checkVal = val;
                    if(qty_type =='SAMPLE') {
                        if(data.data[row][14] == 1) {
                            $(cell).addClass('readonly');
                        }  else  {
                            $(cell).removeClass('readonly');
                        }
                    } else {
                        if(data.data[row][14] == 1) {
                            $(cell).removeClass('readonly');
                        }  else  {
                            $(cell).addClass('readonly');
                        }
                    }
                }
                if(col == 10) 
                {
                    if(checkVal === true) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                        
                    }
                    if(val == '') {
                        con_val = '';
                    } else {
                        con_val = val;
                    }
                    
                    //con_val = val;
                }
                if(col == 11) 
                {
                    if(checkVal === true) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                        $(cell).addClass('readonly');
                        
                    }
                    if(val == '') {
                        ex_val = '';
                    } else {
                        ex_val = val;
                    }
                }

                if(col == 12) {
                    if(checkVal === true) {
                        if(con_val != '' && ex_val != '') {
                        amount = parseFloat(con_val) + (parseFloat(con_val) * parseFloat(ex_val) / 100);
                        //amount1 = Math.round(amount);
                        tot = numeral(amount).format('0.00');
                        //console.log(tot);
                        $(cell).text(tot);
                        instance.jexcel.options.data[row][col] = tot;
                        } else {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = 0.00;
                        }
                    } else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = 0.00;
                    }                        
                    
                }
            }
        };

        // if($('.qty_check').is(':checked')) { 
        //     let qty_type = $("input[name='purchase_req_type']:checked").val();
        //     if(qty_type == 'bulk') {
                
        //     } else {


        //     }
        // }



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


    function append_sample_request_bulk(data) {
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
                { title: 'Consolidated\n Reqd. BOM Qty.', width: '8%', align: 'right',readOnly: true },
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
                        getReferenceValue(data.totData[row], val, row);
                        $('#cleardraft').hide();
                        $('#saveasdraft').show();
                        $('input[type=radio][name=purchase_req_type]').prop('disabled',true);
                    } else {
                        selectedArray = selectedArray.filter(function(e) {return e != row})
                        var check_count = $("[type='checkbox']:checked").length;
                        if(check_count == 0 ) {
                            $('input[type=radio][name=purchase_req_type]').prop('disabled',false);
                        }
                        remove_row(row);
                        $('#cleardraft').hide();
                        $('#saveasdraft').show();
                    }

                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {

                if(col == 8) {
                    //console.log(col);
                    $(col).text('');
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

    function remove_row(row)
    {
        // console.log(row);
        var duplicate_req_sel_data=req_sel_data;
        for(var i=0;i<req_sel_data.length;i++)
        {
            //console.log(req_sel_data[i]);
            if(row==req_sel_data[i][1])
            {
                //console.log(req_sel_data[i]);
                duplicate_req_sel_data.splice(i,1);

            }
        }
        table_data = duplicate_req_sel_data;
        req_sel_data = duplicate_req_sel_data;
        // console.log(duplicate_req_sel_data);
        remove_attach_reference(duplicate_req_sel_data);
        duplicate_req_sel_data=undefined;
    }

    function getReferenceValue(data, status, row) {
      
        let bom_data = data;
        
        let id = bom_data.bom_1_req_consld_id;
        //console.log(id);
        if(selectedArray.length === 0) {
            $('#saveRequestDetails').hide();
        } else {
            $('#saveRequestDetails').show();
        }

        if(status == true) {
            
            let count = 0;
            // for (i = 0; i < sourcing_result.length; i++) {
            //     if(id == sourcing_result[i][2]) {
            //         count++;
            //         sourcing_result[i][3] = true;
            //         copy_sourcing_result.push(sourcing_result[i]);
            //     }
            // }

            if(count == 0) {

                let combineValue = [ 'add', row , bom_data.vendor_id, status, bom_data.item_desc, bom_data.appr_item_code, bom_data.appr_item_col_code,
                bom_data.sourcing_advice, bom_data.vendor_location, bom_data.vendor_name_address, bom_data.contact_email, 
                bom_data.gst, bom_data.online_order_sys, bom_data.pass_expiry_date ];
                //table_data.push(combineValue);
                req_sel_data.push(combineValue);
            }
            //console.log(table_data);

            selectCount = selectCount+1;
            append_attach_reference(req_sel_data);
        }
        else {
            // console.log(table_data);
            for (i = 0; i < req_sel_data.length; i++) {
                if(id == req_sel_data[i][1]) {
                    copy_sourcing_result = copy_sourcing_result.filter(function(e) { if(e[1] !== req_sel_data[i][1]) return e });
                    req_sel_data[i][2] = false;
                }
            }
            
            table_data = table_data.filter(function(e) { if(e[1] !== data['bom_1_req_consld_id']) return e });
            //console.log(table_data);
            selectCount = selectCount-1;
            append_attach_reference(req_sel_data);
        }
        
    }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
 
    function append_attach_reference(data) {
        //console.log(data);
        var tblData = data.concat(table_data);

        tblData = tblData.reduce((unique, o) => {
            if(!unique.some(obj => obj[4] === o[4] && obj[5] === o[5] && obj[7] === o[7])) {
              unique.push(o);
            }
            return unique;
        },[]);

        $('#attachReference').html('');
        let list = {
            data: tblData,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'row', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'status', width:'0%',align:'center',type:'hidden'},
                { title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { title: 'Approved \n Item Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Approved Item \n Colour Code', width: '8%', align: 'left', readOnly: true },
                // { title: 'Blend (%) / Content / Material', width: '12%', align: 'left', readOnly: true },
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

    function remove_attach_reference(data) {
       // console.log(data);
        //req_sel_data = data;
        //table_data = data;
        let tblData = data;
        tblData = tblData.reduce((unique, o) => {
            if(!unique.some(obj => obj[4] === o[4] && obj[5] === o[5] && obj[7] === o[7])) {
              unique.push(o);
            }
            return unique;
        },[]);

        $('#attachReference').html('');
        let list = {
            data: tblData,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'row', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'status', width:'0%',align:'center',type:'hidden'},
                { title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { title: 'Approved \n Item Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Approved Item \n Colour Code', width: '8%', align: 'left', readOnly: true },
                // { title: 'Blend (%) / Content / Material', width: '12%', align: 'left', readOnly: true },
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
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    updateFunction('draft');
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

    $('#cleardraft').click(function () {
        if(selectCount <= 0 && sourcing_result.length == 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    clearFunction('clear');
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

    $('#getValues').click(function () {
        
        let dataValue = sampleRequest_vm.getData();
        let validateField = [2,10,11];
        let optional_validation_field = [];
        let pendingField = "";
        let statusCheck = "no";
        let validatedErrorCount = validateForm(validateField, dataValue);
        
        if(selectCount <= 0 && sourcing_result.length == 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
            
            if(validatedErrorCount > 0)
            {
             swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
            }

            else if($('#req_type').val() == "" || $('#req_type').val() == null ) {
            //alert('Fill All Fields');
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        }
        else if($('#cutoff_date').val() == "" || $('#cutoff_date').val() == null ) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        }
        else if($('#merchant_note').val() == "" || $('#merchant_note').val() == null ) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        }
        // else if($('#bom1ImageUpload').val() == "" || $('#bom1ImageUpload').val() == null ) {
        //     swalWithBootstrapButtons.fire(
        //             alertMessageFunction('validation_error')
        //         )
        // }
        else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    updateFunction('save');
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

    function updateFunction(types) {
        let dataform = new FormData();

        let bom_tbl_data = sampleRequest_vm.getData();

        dataform.append('bom_data', JSON.stringify(bom_tbl_data));
        dataform.append('data', JSON.stringify(req_sel_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('req_type', $('#req_type').val());
        dataform.append('cutoff_date', $('#cutoff_date').val());
        dataform.append('merchant_note', $('#merchant_note').val());
        dataform.append('purchase_req_type', $("input[name='purchase_req_type']:checked").val());
        dataform.append('mode', mode);
        dataform.append('req_id', req_id);
        dataform.append('type', types);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/createPurchaseRequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                val = JSON.parse(data);
                if(val.status == "success")
                {
                    req_id = val.request_id;
                    if(bom1Upload.selectedFiles == 0)
                    {
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(okay => {
                            if (okay) {
                                if(types == "draft") {
                                    window.location.href = base_path + 'WorkInProcess/index/' + encodeURIComponent(btoa(enquiry_id));
                                }
                                else {
                                    window.location.href = base_path + 'MerchantRequestSent/bom';
                                }
                            }
                        });
                    }
                    else {
                        bom1Upload.startUpload();
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(okay => {
                            if (okay) {
                                if(types == "draft") {
                                    window.location.href = base_path + 'WorkInProcess/index/' + encodeURIComponent(btoa(enquiry_id));
                                }
                                else {
                                    window.location.href = base_path + 'MerchantRequestSent/bom';
                                }
                            }
                        });
                    }
                    // *** SAVED MESSAGE *** //
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }


    function clearFunction(type) {
        let dataform = new FormData();

        let bom_tbl_data = sampleRequest_vm.getData();

        
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('type', type);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/clearPurchaseRequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                val = JSON.parse(data);
                if(val.status == "Success")
                {
                     window.location.href = base_path + 'WorkInProcess/index/' + encodeURIComponent(btoa(enquiry_id));
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    // ******** SAVE REQUEST DETAILS ENDS HERE ***************** //

    // ******** SAVE AS DRAFT ENDS HERE ***************** //
    
    
    function getBomRequestImages()
    {
        $('.ImageView').html('');
        var data = new FormData();
        //reqId = request_id[0];
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'bom_request');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/bomrequest/getbomrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                if((imageJSON.length) > 0) {
                for (let i = 0; i < imageJSON.length; i++) {
                    $('.ImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/bom/'+imageJSON[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/bom/'+imageJSON[i].image_url+' target="_blank" title="Open in New Tab">'+
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
                
                $('#bom1ImageUpload').hide();
                } else {
                    
                        $('#bom1ImageUpload').show();
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $(document).on('click','.deleteImg',function(){
        var id = $(this).attr('data-id');

        if (confirm('Are you sure you want to delete the file')) {
            MakeAsynPostRequest(base_path + "WorkInProcess/deleteImageDetails", "&id=" + id, "json", function(data) {
                if(data.status == 'success')
                {
                    $('#cleardraft').hide();
                    $('#saveasdraft').show();
                    getBomRequestImages();
                }
            });
        }

    });
    
    
    let bom1Upload = $("#bom1ImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'request/Bomrequest/imageUploadDetails',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            return {
                enquiry_id: enquiry_id,
                request_id: req_id
            };
        },
        afterUploadAll: function () {
            return "Success";
        },
        autoSubmit: false
    });
    
    $("#bom1ImageUpload").change(function () {
        $('#cleardraft').hide();
        $('#saveasdraft').show();
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});