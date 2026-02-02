$(document).ready(function () {

    getDraftPIRequest();
    getMerchantImages();
    getPurchaseImages();
    
    let purchase_mode = '';
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
        
        if(mode == "totalEqual") {
            return {
                title: 'Warning',
                text: "Already Bill Paid",
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
        // total = (total > 0) ? total : ''
        return total;
    }
     
    // function footer(grid_name)
    // {
    //     if(grid_name == 'within')
    //     {
    //         return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
    //     }
    //     else if(grid_name == 'inter')
    //     {
    //         return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
    //     }
    //     else if(grid_name == 'import')
    //     {
    //         return [[ '', '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
    //     }
    // }
    
    function footer(grid_name)
    {
        if(grid_name == 'within')
        {
            // return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '',   ]];
        }
        else if(grid_name == 'inter')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
        else if(grid_name == 'import')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
        else if(grid_name == 'payment_request')
        {
            return [[ '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), 9)', '', '', '', '=GPWSUMCOL(TABLE(), 13)' ]];
        }
        else if(grid_name == 'bill_invoice')
        {
            return [[ '', '', '', '', '', '', '', '',  'Total:', '=GPWSUMCOL(TABLE(), 9)', '=GPWSUMCOL(TABLE(), 10)', '=GPWSUMCOL(TABLE(), 11)','=GPWSUMCOL(TABLE(), 12)', '=GPWSUMCOL(TABLE(), 13)', '', ''   ]];
        }
        else if(grid_name == 'bill_paid')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), 14)', ''  ]];
        }
        else if(grid_name == 'payment_others')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), 10)', ''  ]];
        }
        
        else if(grid_name =='credit_note')
        {
            return [[ '', '', '', '', '', '', '',   'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '','' , '', '', ''  ]];
        }
    }

    // *********************************************************************************************************************************** 
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getDraftPIRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('pId', pId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getPIRequestSendDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);
                let fData = bom_requirement_data.fullData[0];
                purchase_mode = fData.mode;
                
                if(purchase_mode == 'within' || purchase_mode == undefined) {
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
                
                $('#pi_ref_no').html(fData.pi_ref_queue_no);
                $('#pi_dt').html(fData.pi_dt);
                $('#purchase_type').html(fData.purchase_type);
                $('#exp_dod').html(fData.exp_dod);
                $('#amount_in_words').html(fData.amount_in_words);
                $('#payment_terms').html(fData.payment_terms);
                
                append_within_state(bom_requirement_data);
                append_purchase_request(bom_requirement_data);
                append_purchase_request_others(bom_requirement_data);
                append_payment_request_bill(bom_requirement_data);
                append_credit_note_request(bom_requirement_data);
                append_payment_paid_request(bom_requirement_data);
                append_request_payment_log(bom_requirement_data);
                append_inter_state(bom_requirement_data);
                append_imports_state(bom_requirement_data);
                appendAddressField(bom_requirement_data.vendor_data, bom_requirement_data.vendor_id);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getMerchantImages()
    {
        $('.ImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'bom_request');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getbomrequestImages',
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
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getPurchaseImages()
    {
        $('.purchaseImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'purchase_dept');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getbomrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                for (let i = 0; i < imageJSON.length; i++) {
                    $('.purchaseImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/bom/purchase/'+imageJSON[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/bom/purchase/'+imageJSON[i].image_url+' target="_blank" title="Open in New Tab">'+
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

    function append_purchase_request(data, tblData = []) {
        $('#paymentRequest').html('');

        let list = {
            data: data.paymentRequst,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: "Req. Count", width: '3%', align: 'center', readOnly: true},
                { type: 'dropdown', title: 'Vendor Name', width: '6%', align: 'left', source: data.vendor_data, readOnly: true },
                { title: "Vendor's Bank Name", width: '10%', align: 'left', readOnly: true},
                { title: 'Account Number', width: '8%', align: 'center', readOnly: true},
                { title: 'IFSC Code /\nSWIFT Code', width: '7%', align: 'center', readOnly: true},
                { type: 'text', title: 'Proforma Invoice No.', width: '6%', align: 'left', readOnly: true},
                { type: 'calendar', title: 'Proforma\n Invoice Date', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Proforma\n Invoice Value', width: '7%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Quoted\n Currency', width: '6%', align: 'left', source: data.currencyList, readOnly: true},
                { type: 'dropdown', title: 'Accepted Mode\n of Payment', width: '7%', align: 'center', source: data.modeOfShipment, readOnly: true},
                { type: 'calendar', title: 'Pay by Date', width: '7%', align: 'center', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                { title: 'Amount\n Payable', width: '6%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Currency', width: '6%', align: 'center', source: data.currencyList, readOnly: true},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('payment_request'),
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2) 
                {
                    // console.log(val)
                    vendorId = val;
                }
                if(col == 3)
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
                if(col == 4)
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
                if(col == 5)
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
                
                if(col == 12) 
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
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
    
    
    function append_purchase_request_others(data, tblData = []) {
        $('#paymentRequestOthers').html('');

        let list = {
            data: data.paymentOthers,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: "Req. Count", width: '3%', align: 'center', readOnly: true},
                { type: 'text',title: 'Pay in Favour of', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Bank Name', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Account Number ', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'IFSC Code /\nSWIFT Code', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Expense Incured Towards', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Ref. D.C. No.', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'D.C. Date', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Ref. Invoice No.', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Invoice Date', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Amount Payble', width: '7%', align: 'right', readOnly: true},
                { type: 'text',title: 'Currency', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Acc. Mode of\nPayment', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Pay by Date', width: '7%', align: 'center', readOnly: true},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('payment_others'),
            
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 11) 
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
            }
            
        };

        paymentRequestOthers_vm = new Vue({
            el: '#paymentRequestOthers',
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
    
    function append_payment_request_bill(data) {

        $('#paymentRequestBill').html('');
        let list = {
            data: data.paymentRequestBill,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: "Req. Count", width: '3%', align: 'center', readOnly: true},
                { type: 'text', title: 'Vendor Name', width: '6%', align: 'left', source: data.vendor_data, readOnly: true },
                { type: 'text', title: "Vendor's Bank Name", width: '10%', align: 'left', readOnly: true},
                { type: 'text', title: 'Account Number', width: '8%', align: 'center', readOnly: true},
                { type: 'text', title: 'IFSC Code/\nSWIFT Code', width: '6%', align: 'center', readOnly: true},
                { type: 'text', title: 'Invoice No.', width: '6%', align: 'left', readOnly: true},
                { type: 'calendar', title: 'Invoice \n Date', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Invoice Value', width: '6%', align: 'right', readOnly: true},
                { type: 'text', title: 'Advance Paid', width: '6%', align: 'right', readOnly: true},
                { type: 'text', title: 'Debits If Any', width: '6%', align: 'right', readOnly: true},
                { type: 'text', title: 'Credits / W.O\n Value If Any', width: '6%', align: 'right',readOnly: true},
                { title: 'Amount\n Payable', width: '6%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Currency', width: '6%', align: 'center', source: data.currencyList, readOnly: true},
                { type: 'dropdown', title: 'Acc. Mode\n of Payment', width: '7%', align: 'center', source: data.modeOfShipment, readOnly: true},
                { type: 'text', title: 'Pay by Date', width: '7%', align: 'center', readOnly: true},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('bill_invoice'),
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 8) 
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 9) 
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 10) 
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 11) 
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        paymentRequestBill_vm = new Vue({
            el: '#paymentRequestBill',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_credit_note_request(data) {
        const description = [ "CREDIT", "CREDIT NOTE", "WRITE-OFF" ];
        const statusDD = [ 
            { id: 0, name: "PENDING" },
            { id: 1, name: "APPROVED" },
            { id: 2, name: "DECLINED" },
            { id: 3, name: "PENDING - RR" },
        ];

        $('#creditNoteDetails').html('');
        let list = {
            data: data.creditNoteDetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Description', width: '7%', align: 'left', type: 'dropdown', source: description, readOnly: true },
                { title: 'Towards \nInvoice No.', width: '6%', align: 'left', type: 'dropdown',source: data.inv_no, readOnly: true },
                { title: 'Credit Into - Account \nName', width: '8%', align: 'center', type: 'text', readOnly: true },
                { title: 'Credit Into - \nAccount Number', width: '8%', align: 'center', type: 'text', readOnly: true },
                { title: 'Credit - Transaction ID /\nCredit Note Ref. No.', width: '6%', align: 'left', readOnly: true },
                { title: 'Transaction / Credit \nNote Date', width: '6%', align: 'center', type:'calendar', options: {format: 'DD/MM/YYYY'}, readOnly: true },
                { title: "Credit / Credit Note /\nWrite-off Amount", width: '6%', align: 'right', readOnly: true },
                { title: "Currency", width: '5%', align: 'center', type: 'dropdown', source: data.currencyList, readOnly: true },
                // { type: 'dropdown', title: 'Acc. Mode\n of Payment', width: '7%', align: 'center', source: data.modeOfShipment, readOnly: true },
                // { type: 'text', title: 'Mode of \nPayment', width: '7%', align: 'center', readOnly: true},
                { title: "If Write-off Approval \nStatus", width: '6%', align: 'center', type:'dropdown', source:statusDD },
                { title: "Approved By", width: '6%', align: 'center',readOnly: true },
                { title: "Approved Date &\nTime", width: '6%', type:'text',  readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('credit_note'),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                
                // if(col == 9) 
                // {
                    
                // }
                // if(col == 11) 
                // {
                    
                // }
                
                
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                
                if(col == 2) 
                {
                    credit = val;
                }
                if(col == 8) 
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
                if(col == 10) 
                {
                    approved_by = data.creditNoteDetails[row][11];
                    if(credit != '' && approved_by == '') {
                        $(cell).removeClass('readonly');
                        
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                
            }
        };

        creditNoteDetails_vm = new Vue({
            el: '#creditNoteDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_payment_paid_request(data) {

        $('#paymentPaidDetails').html('');
        let list = {
            data: data.paymentPaidDetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'Req. Count', width:'5%',align:'center',type:'text',readOnly: true},
                { title: 'Paid in Favour of', width: '7%', align: 'left', readOnly: true },
                { title: 'Bank Name', width: '12%', align: 'left', readOnly: true },
                { title: 'Account Number', width: '10%', align: 'left', readOnly: true },
                { title: 'Mode of\n Payment', width: '6%', align: 'left', readOnly: true },
                { title: 'Transaction ID /\nCheque / Voucher No.', width: '6%', align: 'left', readOnly: true },
                { title: 'Transaction /\nCheque / Voucher Date', width: '6%', align: 'left', readOnly: true },
                { title: 'Paid Towards', width: '8%', align: 'left', readOnly: true },
                { title: 'Amount', width: '6%', align: 'right', readOnly: true },
                { title: "Currency", width: '5%', align: 'left', readOnly: true },
                { title: "Exch. Rate Per \nUnit (Rs.)", width: '6%', align: 'left', readOnly: true },
                { title: "Amount \n(Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('bill_paid'),
            
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                
                if(col == 10) 
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                // if(col == 12) 
                // {
                //     txtValue = numeral(val).format('0.00');
                //     $(cell).text(txtValue);
                //     instance.jexcel.options.data[row][col] = txtValue;
                // }
                if(col == 13) 
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        paymentPaidDetails_vm = new Vue({
            el: '#paymentPaidDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_request_payment_log(data) {

        const requestDD = [ "P.I.APPROVAL", "PAYMENTS","NIL", "PAY. APPROVAL" ];
        const paymentDD = [ "ADV. PAYMENT", "BILL PAYMENT", "OTH. PAYMENT", "NIL" ];
        const statusDD = [ 
            { id: 0, name: "PENDING" },
            { id: 3, name: "PENDING - RR" },
            { id: 1, name: "APPROVED" },
            { id: 2, name: "DECLINED" },
        ];
        const reqStatus = [ 
            { id: 0, name: "PENDING" },
            { id: 1, name: "ACCEPTED" }
        ];
        const approvalDD = [ "REGULAR", "PRIORITY", "HIGH PRIORITY", "IMMEDIATE" ];
        const paymentStatusDD = [ "PENDING", "PART PAID", "PAID" ];

        $('#requestPaymentLog').html('');
        let list = {
            data: data.paymentLog,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: "Req. Count", width: '3%', align: 'center', readOnly: true},
                { type: 'dropdown', title: 'Request For', width: '7%', align: 'left', source: requestDD, readOnly: true },
                { type: 'text', title: 'Request Type', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Req. Date & Time', width: '7%', align: 'center', readOnly: true },
                { type: 'text', title: 'Payment\n Requirement', width: '7%', align: 'left', source: paymentDD, readOnly: true },
                { type: 'dropdown', title: 'Approval Status', width: '7%', align: 'left', source: statusDD },
                { type: 'dropdown', title: 'Approval Type', width: '7%', align: 'left', source: approvalDD },
                { type: 'text', title: 'Approved By', width: '7%', align: 'left', readOnly: true },
                { type: 'text',title: 'Approval \n Date  & Time', width: '7%', align: 'center', readOnly: true },
                // { type: 'dropdown', title: 'Request Status', width: '8%', align: 'left', source: reqStatus, readOnly: true },
                // { type: 'text',title: 'Req. Status Update Date & Time', width: '6%', align: 'center', readOnly: true },
                { type: 'dropdown', title: "Payment Paid\n Status", width: '6%', align: 'left', source: paymentStatusDD, readOnly: true },
                { type: 'text',title: "Payment Paid Status\n Update Date  & Time", width: '8%', align: 'center', readOnly: true,  },
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'Purchase mode', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label) {
                
                if(col == 5) 
                {
                    payment_req = val;
                }
                if(col == 6)
                {
                    status = val;
                    if(data.paymentLog[row][12] == 'CLOSED') {
                       $(cell).addClass('readonly');
                    } else {
                       $(cell).removeClass('readonly'); 
                    }
                }
                
                if(col == 7)
                {
                    if(data.paymentLog[row][12] == 'CLOSED') {
                       $(cell).addClass('readonly');
                    } else {
                       $(cell).removeClass('readonly'); 
                    }
                }
                
                if(col == 10)
                {
                    if(payment_req == 'NIL') {
                        $(cell).addClass('readonly');
                        $(cell).text('NIL');
                    }
                    
                }
                
                
            }
        };

        tblRequestPaymentLog = new Vue({
            el: '#requestPaymentLog',
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
                { title: 'Item\n Description', width: '7%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { title: 'Item Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim. (L*W*H)', width: '6%', align: 'center', readOnly: true },
                { title: 'UOM', width: '8%', align: 'left', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'right', readOnly: true },
                { title: "UOM", width: '6%', align: 'left', readOnly: true },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right', readOnly: true },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                { title: "GST\n (%)", width: '6%', align: 'right', readOnly: true },
                { title: "GST\n VALUE", width: '6%', align: 'right', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('within'),
            
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
                { title: 'Item\n Description', width: '7%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { title: 'Item Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim. (L*W*H)', width: '6%', align: 'center', readOnly: true },
                { title: 'UOM', width: '8%', align: 'center', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'right', readOnly: true },
                { title: "UOM", width: '6%', align: 'center', readOnly: true },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right', readOnly: true },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                { title: "IGST\n (%)", width: '6%', align: 'right', readOnly: true },
                { title: "IGST\n VALUE", width: '6%', align: 'right', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('inter'),
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 9)
                {
                    qty = val;
                }
                if(col == 11) {
                    txtValue = numeral(val).format('0.00');
                    unit_rate = txtValue;
                }
                if(col == 12) {
                    amount = parseFloat(qty) * parseFloat(unit_rate);
                    txtValue = numeral(amount).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 13) {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    igst = txtValue;
                }
                if(col == 14) {
                    igst_value = parseFloat(amount) * parseFloat(igst) / 100;
                    txtValue = numeral(igst_value).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 15) {
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
                { title: 'Item\n Description', width: '7%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { title: 'Item Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim. (L*W*H)', width: '6%', align: 'center', readOnly: true },
                { title: 'UOM', width: '8%', align: 'center', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'right', readOnly: true },
                { title: "UOM", width: '6%', align: 'center', readOnly: true },
                { title: "Currency", width: '5%', align: 'left', type: 'dropdown', source: data.currencyList , readOnly: true },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right', readOnly: true },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('import'),
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 9)
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
    
    $('#print_val').click(function () {
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);
        
         window.location.href = base_path + 'request/Bomrequest/invoicebill/'+encodeURIComponent(btoa(pId)) ;
        
    });

    
    $('#getValues').click(function () {
        let paid_data = paymentPaidDetails_vm.getData();
        let credit_data = creditNoteDetails_vm.getData();
        let paymentLogData = tblRequestPaymentLog.getData();
        let paymentreq_data = paymentRequest_vm.getData();
        let bill_data = paymentRequestBill_vm.getData();
        let others_data = paymentRequestOthers_vm.getData();
        let validatetotAmt = validateTotalAmt(paymentreq_data, bill_data, others_data, paid_data );
        if(validatetotAmt == 0) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('totalEqual')
                )
        } else {
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let payment_log_data = tblRequestPaymentLog.getData();
                updateFunction(payment_log_data);
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
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('data', JSON.stringify(data));

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateManagementPIData',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // getDraftPIRequest();
                // // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                        window.location.href = base_path + 'request/Bomrequest/managamentpurchaseindent';
                    }
                });
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

    function appendAddressField(vendorData, vId) {
        vendorResponse = vendorData;        
        if(vendorResponse !="") {
            let vendorDet = vendorResponse.filter((data) => data.id === vId);
            $("#vendorName").html(vendorDet[0].vendorname);
            $("#vendorAddress").html(vendorDet[0].address);
            $("#vendorContact").html(vendorDet[0].phone);
            $("#vendorEmail").html(vendorDet[0].emailid);
            $("#vendorGst").html(vendorDet[0].gstno);
            $("#vendorIeCode").html(vendorDet[0].iecode);   
        }
        else {
            $("#vendorAddress").html("");
            $("#vendorContact").html("");
            $("#vendorEmail").html("");
            $("#vendorGst").html("");
            $("#vendorIeCode").html("");   
        }

    }
    
    function validateTotalAmt(paymentreq_data, bill_data, others_data, paid_data )
    {
        let errorCount = 0;
        let payReqAmt = 0;
        let billAmt = 0;
        let othersAmt = 0;
        let paidAmt = 0;
        for (let i = 0; i < paymentreq_data.length; i++) {
            if(paymentreq_data[i][12] > 0) {
                payReqAmt = parseFloat(payReqAmt)+parseFloat(paymentreq_data[i][12]);
            }
        }
        for (let i = 0; i < bill_data.length; i++) {
            if(bill_data[i][12] > 0) {
                billAmt = parseFloat(billAmt)+parseFloat(bill_data[i][12]);
            }
        }
        for (let i = 0; i < others_data.length; i++) {
            if(others_data[i][9] > 0) {
                othersAmt = parseFloat(othersAmt)+parseFloat(others_data[i][9]);
            }
        }
        for (let i = 0; i < paid_data.length; i++) {
            if(paid_data[i][13] > 0) {
                paidAmt = parseFloat(paidAmt)+parseFloat(paid_data[i][13]);
            }
        }
        tot_amt = parseFloat(payReqAmt) + parseFloat(billAmt) + parseFloat(othersAmt);
        if(paidAmt == tot_amt) {
            errorCount = 0;
        } else {
            errorCount++;
        }
        return errorCount;
        
    }
    
    $('#acceptSave').click(function () {
         let crediData = creditNoteDetails_vm.getData();
        
            swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    
                     updateAcceptFunction(crediData);
                    
                } 
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
        
    });
    
    function updateAcceptFunction(crediData)
    {
        let dataform = new FormData();
        dataform.append('crediData', JSON.stringify(crediData));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateManagementCreditData',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then((okay) => {
                    if(okay)
                    {
                         window.location.href = base_path + 'request/Bomrequest/managamentpurchaseindent';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});