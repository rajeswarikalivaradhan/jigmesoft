$(document).ready(function () {

    let parts = window.location.href.split('/');
    let request_yarn_id = parts[parts.length - 1];
    let req_yarn_id = atob(decodeURIComponent(request_yarn_id));

    getDraftPIRequest();

    $('#mode').html('WITHIN STATE');
    $('#withinStateDetails').show();
    $('#interStateDetails').hide();
    $('#importsStateDetails').hide();

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

    SUMCOL = function(instance, columnId, twoColumn) {
        var total = 0;
        var id = 1;
        for (var j = 0; j < instance.options.data.length; j++) {
            if(twoColumn == 'twoColumn')
            {
                id = 2;
            }
            if (Number(instance.records[j][columnId - id].innerHTML)) {
                total += Number(instance.records[j][columnId - id].innerHTML);
            }
        }
        total = numeral(total).format('0');
        total = (total > 0) ? total : '';
        return total;
    }


    GPWSUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        total = numeral(total).format('0.00');
        total = (total > 0) ? total : ''
        return total;
    }
     
    function footer(grid_name)
    {
        if(grid_name == 'within')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
        else if(grid_name == 'inter')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
        else if(grid_name == 'import')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
    }

    // *********************************************************************************************************************************** 
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    let yarn_requirement_data = '';

    function getDraftPIRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqYarnId', req_yarn_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/getReqSentListDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                yarn_requirement_data = JSON.parse(data);
                
                purchase_mode = yarn_requirement_data.req_payment_data[0].mode;
                if(purchase_mode == 'within') {
                    $('#mode').html('WITHIN STATE');
                    $('#withinStateDetails').show();
                    $('#interStateDetails').hide();
                    $('#importsStateDetails').hide();
                }
                else if(purchase_mode == 'inter') {
                    $('#mode').html('INTER STATE');
                    $('#withinStateDetails').hide();
                    $('#interStateDetails').show();
                    $('#importsStateDetails').hide();
                }
                else if(purchase_mode == 'imports') {
                    $('#mode').html('IMPORTS');
                    $('#withinStateDetails').hide();
                    $('#interStateDetails').hide();
                    $('#importsStateDetails').show();
                }

                append_within_state(yarn_requirement_data);
                append_purchase_request(yarn_requirement_data);
                append_amount_paid_request(yarn_requirement_data);
                append_inter_state(yarn_requirement_data);
                append_imports_state(yarn_requirement_data);
                appendAddressField(yarn_requirement_data.vendor_data, yarn_requirement_data.req_payment_data[0].vendor_id);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_purchase_request(data) {
        $('#paymentRequest').html('');

        let list = {
            data: data.purchaseVendorData,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'dropdown', title: 'Vendor Name', width: '6%', align: 'left', source: data.vendor_data, readOnly: true },
                { type: 'text', title: 'Proforma No.', width: '6%', align: 'left', readOnly: true},
                { type: 'calendar', title: 'Proforma\n Date', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Proforma\n Value', width: '6%', align: 'left', readOnly: true},
                { type: 'dropdown', title: 'Quoted\n Currency', width: '6%', align: 'left', source: data.currencyList, readOnly: true},
                { type: 'dropdown', title: 'Accepted Mode\n of Payment', width: '7%', align: 'center', source: data.modeOfShipment, readOnly: true},
                { type: 'calendar', title: 'Pay by Date', width: '7%', align: 'center', readOnly: true},
                { title: 'Amount\n Payable', width: '6%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Currency', width: '6%', align: 'center', source: data.currencyList, readOnly: true},
                { title: "Vendor's Bank Name", width: '10%', align: 'left', readOnly: true},
                { title: 'Account Number', width: '8%', align: 'center', readOnly: true},
                { title: 'IFSC Code', width: '7%', align: 'center', readOnly: true},
                { title: 'SWIFT Code', width: '7%', align: 'center', readOnly: true}
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 1) 
                {
                    // console.log(val)
                    vendorId = val;
                }
                if(col == 10)
                {
                    // console.log(vendorId)
                    if(vendorId != '')
                    {
                        let index = data.vendor_data.findIndex(p => p.id == vendorId);
                        // let fil_vendor = [];
                        fil_vendor = data.vendor_data[index];
                        // console.log(fil_vendor)
                        if(fil_vendor != undefined)
                        {
                            $(cell).text(fil_vendor.bankname);
                            instance.jexcel.options.data[row][col] = fil_vendor.bankname;
                        }
                        else {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                        }
                    }
                }
                if(col == 11)
                {
                    if(vendorId != '')
                    {
                        let index = data.vendor_data.findIndex(p => p.id == vendorId);
                        // let fil_vendor = [];
                        fil_vendor = data.vendor_data[index];
                        // console.log(fil_vendor)
                        if(fil_vendor != undefined)
                        {
                            $(cell).text(fil_vendor.accountno);
                            instance.jexcel.options.data[row][col] = fil_vendor.accountno;
                        }
                        else {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                        }
                    }
                }
                if(col == 12)
                {
                    if(vendorId != '')
                    {
                        let index = data.vendor_data.findIndex(p => p.id == vendorId);
                        // let fil_vendor = [];
                        fil_vendor = data.vendor_data[index];
                        // console.log(fil_vendor)
                        if(fil_vendor != undefined)
                        {
                            $(cell).text(fil_vendor.ifscode);
                            instance.jexcel.options.data[row][col] = fil_vendor.ifscode;
                        }
                        else {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                        }
                    }
                }
                if(col == 13)
                {
                    if(vendorId != '')
                    {
                        let index = data.vendor_data.findIndex(p => p.id == vendorId);
                        // let fil_vendor = [];
                        fil_vendor = data.vendor_data[index];
                        // console.log(fil_vendor)
                        if(fil_vendor != undefined)
                        {
                            $(cell).text(fil_vendor.swiftcode);
                            instance.jexcel.options.data[row][col] = fil_vendor.swiftcode;
                        }
                        else {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                        }
                    }
                }
            }
        };

        paymentRequest_vm = new Vue({
            el: '#paymentRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }

    // *********************************************************************************************************************************** 
    // Purchase REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_amount_paid_request(data) {

        $('#advancePaidDetails').html('');
        let list = {
            data: data.advancepaiddetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Paid in Favour of', width: '7%', align: 'left', readOnly: true },
                { title: 'Bank Name', width: '12%', align: 'left', readOnly: true },
                { title: 'Account Number', width: '10%', align: 'left', readOnly: true },
                { title: 'Mode of\n Payment', width: '6%', align: 'left', readOnly: true },
                { title: 'Transaction ID / Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Transaction\n Date', width: '6%', align: 'left', readOnly: true },
                { title: 'Cheque No.', width: '8%', align: 'left', readOnly: true },
                { title: 'Cheque Date', width: '6%', align: 'left', readOnly: true },
                { title: "Amount Paid", width: '6%', align: 'left', readOnly: true },
                { title: "Currency", width: '5%', align: 'left', readOnly: true },
                { title: "Advance Paid in\n Full / Part", width: '6%', align: 'left', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        sourceDetailsReference_vm = new Vue({
            el: '#advancePaidDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_within_state(data) {
        $('#withinStateDetails').html('');
        let list = {
            data: data.withinStateDetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Yarn Vendor /\n Brand', width: '7%', align: 'left', type: 'dropdown', source: data.vendor_data, readOnly: true },
                { title: "Yarn Product Code (Vendor's)", width: '12%', align: 'left', readOnly: true },
                { title: 'Yarn Blend (%)', width: '10%', align: 'left', type: 'dropdown', source: data.yarn_blend_data, readOnly: true },
                { title: 'Yarn Content', width: '6%', align: 'left', type: 'dropdown', source: data.yarn_content_data, readOnly: true },
                { title: 'Yarn\n Count', width: '6%', align: 'left', type: 'dropdown', source: data.yarn_count_data, readOnly: true },
                { title: 'Yarn Special\n Request.', width: '6%', align: 'center', type: 'dropdown', source: data.yarn_req_data, readOnly: true },
                { title: 'Yarn Purchase\n Type', width: '8%', align: 'left', readOnly: true },
                { title: 'Yarn\n Colour', width: '6%', align: 'left', readOnly: true },
                { title: "Reqd. Qty.\n (Kgs.)", width: '6%', align: 'right', readOnly: true },
                { title: "Prog. Qty.\n (Kgs.)", width: '5%', align: 'right', readOnly: true },
                { title: "Rate Per Kg.\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                { title: "GST\n (%)", width: '6%', align: 'right', readOnly: true },
                { title: "CGST\n (%)", width: '6%', align: 'right', readOnly: true },
                { title: "CGST\n VALUE", width: '6%', align: 'right', readOnly: true },
                { title: "SGST\n (%)", width: '6%', align: 'right', readOnly: true },
                { title: "SGST\n VALUE", width: '6%', align: 'right', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('within'),
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 11)
                {
                    qty = val;
                }
                if(col == 12) {
                    txtValue = numeral(val).format('0.00');
                    unit_rate = txtValue;
                }
                if(col == 13) {
                    amount = parseFloat(qty) * parseFloat(unit_rate);
                    txtValue = numeral(amount).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    amount = txtValue;
                }
                if(col == 14) {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    gst = txtValue;
                }
                if(col == 15) {
                    cgst = parseFloat(gst) / 2;
                    txtValue = numeral(cgst).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 16) {
                    cgst_value = parseFloat(amount) * parseFloat(cgst) / 100;
                    txtValue = numeral(cgst_value).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 17) {
                    sgst = parseFloat(gst) / 2;
                    txtValue = numeral(sgst).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 18) {
                    sgst_value = parseFloat(amount) * parseFloat(sgst) / 100;
                    txtValue = numeral(sgst_value).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 19) {
                    sub_total = parseFloat(amount) + parseFloat(cgst_value) + parseFloat(sgst_value);
                    txtValue = numeral(sub_total).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        withinStateReference_vm = new Vue({
            el: '#withinStateDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_inter_state(data) {
        $('#interStateDetails').html('');
        let list = {
            data: data.interStateDetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Yarn Vendor /\n Brand', width: '7%', align: 'left', type: 'dropdown', source: data.vendor_data, readOnly: true },
                { title: "Yarn Product Code (Vendor's)", width: '12%', align: 'left', readOnly: true },
                { title: 'Yarn Blend (%)', width: '10%', align: 'left', type: 'dropdown', source: data.yarn_blend_data, readOnly: true },
                { title: 'Yarn Content', width: '6%', align: 'left', type: 'dropdown', source: data.yarn_content_data, readOnly: true },
                { title: 'Yarn\n Count', width: '6%', align: 'left', type: 'dropdown', source: data.yarn_count_data, readOnly: true },
                { title: 'Yarn Special\n Request.', width: '6%', align: 'center', type: 'dropdown', source: data.yarn_req_data, readOnly: true },
                { title: 'Yarn Purchase\n Type', width: '8%', align: 'left', readOnly: true },
                { title: 'Yarn\n Colour', width: '6%', align: 'left', readOnly: true },
                { title: "Reqd. Qty.\n (Kgs.)", width: '6%', align: 'right', readOnly: true },
                { title: "Prog. Qty.\n (Kgs.)", width: '5%', align: 'right' },
                { title: "Rate Per Kg.\n (Rs.)", width: '6%', align: 'right' },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                { title: "IGST\n (%)", width: '6%', align: 'right' },
                { title: "IGST\n VALUE", width: '6%', align: 'right', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('inter'),
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 11)
                {
                    qty = val;
                }
                if(col == 12) {
                    txtValue = numeral(val).format('0.00');
                    unit_rate = txtValue;
                }
                if(col == 13) {
                    amount = parseFloat(qty) * parseFloat(unit_rate);
                    txtValue = numeral(amount).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    amount = txtValue;
                }
                if(col == 14) {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    igst = txtValue;
                }
                if(col == 15) {
                    igst_value = parseFloat(amount) * parseFloat(igst) / 100;
                    txtValue = numeral(igst_value).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 16) {
                    sub_total = parseFloat(amount) + parseFloat(igst_value);
                    txtValue = numeral(sub_total).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        interStateReference_vm = new Vue({
            el: '#interStateDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_imports_state(data) {
        $('#importsStateDetails').html('');
        let list = {
            data: data.importStateDetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Yarn Vendor /\n Brand', width: '7%', align: 'left', type: 'dropdown', source: data.vendor_data, readOnly: true },
                { title: "Yarn Product Code (Vendor's)", width: '12%', align: 'left', readOnly: true },
                { title: 'Yarn Blend (%)', width: '10%', align: 'left', type: 'dropdown', source: data.yarn_blend_data, readOnly: true },
                { title: 'Yarn Content', width: '6%', align: 'left', type: 'dropdown', source: data.yarn_content_data, readOnly: true },
                { title: 'Yarn\n Count', width: '6%', align: 'left', type: 'dropdown', source: data.yarn_count_data, readOnly: true },
                { title: 'Yarn Special\n Request.', width: '6%', align: 'center', type: 'dropdown', source: data.yarn_req_data, readOnly: true },
                { title: 'Yarn Purchase\n Type', width: '8%', align: 'left', readOnly: true },
                { title: 'Yarn\n Colour', width: '6%', align: 'left', readOnly: true },
                { title: "Reqd. Qty.\n (Kgs.)", width: '6%', align: 'right', readOnly: true },
                { title: "Prog. Qty.\n (Kgs.)", width: '5%', align: 'right' },
                { title: "currency", width: '6%', align: 'right', type: 'dropdown', source: data.currencyList },
                { title: "Rate Per Kg.\n (Rs.)", width: '6%', align: 'right' },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('import'),
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 11)
                {
                    qty = val;
                }
                if(col == 13) {
                    txtValue = numeral(val).format('0.00');
                    unit_rate = txtValue;
                }
                if(col == 14) {
                    amount = parseFloat(qty) * parseFloat(unit_rate);
                    txtValue = numeral(amount).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        importStateReference_vm = new Vue({
            el: '#importsStateDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }


    $('#getValues').click(function () {
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
    });

    function updateFunction() {
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('req_yarn_id', req_yarn_id);
        dataform.append('pi_req_status', $('#pi_req_status').val());
        dataform.append('mgmt_appl_remarks', $('#mgmt_appl_remarks').val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/updateYarnMgmtPIAppl',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // getDraftPIRequest();
                // // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
                setTimeout(() => {
                    window.location.href = base_path + 'request/Fabricrequest/yarnmgmtpilist';
                }, 1000);
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

    function appendAddressField(vendorData, id) {
        vendorResponse = vendorData;
        for (let i = 0; i < vendorResponse.length; i++) {
            if(vendorResponse[i].id == id) {
                $("#vendorName").html(vendorResponse[i].name);
            }
        }
    }

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});