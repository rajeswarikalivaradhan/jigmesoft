$(document).ready(function () {
    var paidTracking=[];
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
        
        if(mode == "paiderror") {
            return {
                title: 'Warning',
                text: "Save Status & Proceed to Bill Closure",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        if(mode == "statuserror") {
            return {
                title: 'Warning',
                text: "Status Incorrect",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }

        if(mode == "amounterror") {
            return {
                title: 'Warning',
                text: "Please check for pending payments",
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
    
    ADVCOL = function(instance) {
        var total = 0;
        let tbl_data = paymentRequest_vm.getData();
        adv_paid = tbl_data[0][12];
        total = numeral(adv_paid).format('0.00');
        total = (total > 0) ? total : '';
        adv_paid = total;
        return total;
    }

    AMTPAY = function(instance) {        
        var total = 0;
        inv_val = 0; debit = 0; amt_pay = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            inv_val += Number(instance.records[j][9].innerHTML);
            debit += Number(instance.records[j][11].innerHTML);
        }
        total = inv_val - (Number(adv_paid) + debit);
        total = numeral(total).format('0.00');
        total = (total > 0) ? total : '';
        amt_pay = total;
        return amt_pay;
    }
     
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
            return [[ '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), 9)', '=GPWSUMCOL(TABLE(), 10)', '=GPWSUMCOL(TABLE(), 11)', '=GPWSUMCOL(TABLE(), 12)','=GPWSUMCOL(TABLE(), 13)', '', '',   ]];
        }
        else if(grid_name == 'bill_paid')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), 14)', ''  ]];
        }
        else if(grid_name == 'payment_others')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), 12)', ''  ]];
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
            url: base_path + 'request/Bomrequest/getBillPaidDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);
                paidTracking=bom_requirement_data.paymentLog;
                
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
                   let subscriberId = imageJSON.subscriber_id;
                  
                for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.ImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON.images[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/bom/'+subscriberId+'/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/bom/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    // '<a href="javascript:void(0);" data-id='+subscriberId+'/'+imageJSON.images[i].image_url+' class="deleteImg" title="Delete">'+
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
                  let subscriberId = imageJSON.subscriber_id;
                 
              for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.purchaseImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON.images[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/bom/purchase/'+subscriberId+'/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/bom/purchase/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
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

    function append_purchase_request(data, tblData = []) {
        $('#paymentRequest').html('');

        let list = {
            data: data.paymentRequst,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: "Req. Count", width: '3%', align: 'center', readOnly: true},
                { type: 'dropdown', title: 'Vendor Name', width: '8%', align: 'left', source: data.vendor_data, readOnly: true },
                { title: "Vendor's Bank Name", width: '8%', align: 'left', readOnly: true},
                { title: 'Account Number', width: '8%', align: 'center', readOnly: true},
                { title: 'IFSC Code /\nSWIFT Code', width: '7%', align: 'center', readOnly: true},
                { type: 'text', title: 'Proforma \nInvoice No.', width: '8%', align: 'left', readOnly: true},
                { type: 'calendar', title: 'Proforma\n Invoice Date', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Proforma\n Invoice Value', width: '6%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Quoted\n Currency', width: '4%', align: 'left', source: data.currencyList, readOnly: true},
                { type: 'dropdown', title: 'Accepted Mode\n of Payment', width: '6%', align: 'center', source: data.modeOfShipment, readOnly: true},
                { title: 'Pay by\n Date & Time', width: '8%', align: 'center', type: 'calendar', options: { format:'DD-MM-YYYY HH12:MI AM/PM', time:1 }, readOnly: true },
                { title: 'Amount\n Payable', width: '5%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Currency', width: '4%', align: 'center', source: data.currencyList, readOnly: true},
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
                    vendorId = val;
                }
                if(col == 3)
                {
                    if(vendorId != '')
                    {
                        let index = data.vendor_data.findIndex(p => p.id == vendorId);
                        // let fil_vendor = [];
                        fil_vendor = data.vendor_data[index];
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
                { title: "Req. Count", width: '4%', align: 'center', readOnly: true},
                { type: 'text', title: 'Vendor Name', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: "Vendor's Bank Name", width: '8%', align: 'left', readOnly: true},
                { type: 'text', title: 'Account Number', width: '8%', align: 'center', readOnly: true},
                { type: 'text', title: 'IFSC Code/\nSWIFT Code', width: '6%', align: 'center', readOnly: true},
                { type: 'text', title: 'Invoice No.', width: '7%', align: 'center', readOnly: true},
                { type: 'calendar', title: 'Invoice \n Date', width: '6%', align: 'center', readOnly: true},
                { type: 'text', title: 'Invoice Value', width: '6%', align: 'right', readOnly: true},
                { type: 'text', title: 'Advance Paid', width: '6%', align: 'right', readOnly: true},
                { type: 'text', title: 'Debit Value \nIf Any', width: '6%', align: 'right', readOnly: true},
                { type: 'text', title: 'Credits / W.O\n Value If Any', width: '6%', align: 'right'},
                { title: 'Amount\n Payable', width: '6%', align: 'right', readOnly: true},
                { type: 'text', title: 'Currency', width: '5%', align: 'center',  readOnly: true},
                { type: 'text', title: 'Acc. Mode\n of Payment', width: '5%', align: 'center',  readOnly: true},
                { type: 'text', title: 'Pay by Date', width: '6%', align: 'center', readOnly: true},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('bill_invoice'),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                
                if(col == 11) 
                {
                    
                }
                
                
                
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 0) 
                {
                    rowVal = val;
                }
                if(col == 11) 
                {
                    if(rowVal == '') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 8) 
                {
                    inv_amt = numeral(val).format('0.00');
                    $(cell).text(inv_amt);
                    instance.jexcel.options.data[row][col] = inv_amt;
                }
                if(col == 9) 
                {
                    adVal = numeral(val).format('0.00');
                    $(cell).text(adVal);
                    instance.jexcel.options.data[row][col] = adVal;
                }
                if(col == 10) 
                {
                    debits = numeral(val).format('0.00');
                    $(cell).text(debits);
                    instance.jexcel.options.data[row][col] = debits;
                }
                if(col == 11) 
                {
                    credits = numeral(val).format('0.00');
                    $(cell).text(credits);
                    instance.jexcel.options.data[row][col] = credits;
                }
                if(col == 12) 
                {
                    total = 0;
                    total = parseFloat(inv_amt) - parseFloat(adVal) - parseFloat(debits) + parseFloat(credits);
                    txtValue = numeral(total).format('0.00');
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
                { title: 'Description', width: '6%', align: 'left', type: 'dropdown', source: description },
                { title: 'Towards \nInvoice No.', width: '7%', align: 'left', type: 'dropdown',source: data.pi_invno},
                { title: 'Credit Into - \nAccount Name', width: '7%', align: 'center', type: 'text' },
                { title: 'Credit Into - \nAccount Number', width: '7%', align: 'center', type: 'text' },
                { title: 'Credit - Transaction ID /\nCredit Note Ref. No.', width: '6%', align: 'left' },
                { title: 'Transaction / Credit \nNote Date', width: '6%', align: 'center', type:'calendar', options: {format: 'DD/MM/YYYY'} },
                { title: "Credit / Credit Note /\nWrite-off Amount", width: '6%', align: 'right',readOnly: true },
                { title: "Currency", width: '4%', align: 'center', type: 'dropdown', source: data.currencyList,readOnly: true },
                // { type: 'dropdown', title: 'Acc. Mode\n of Payment', width: '7%', align: 'center', source: data.modeOfShipment },
                // { type: 'text', title: 'Mode of \nPayment', width: '7%', align: 'center'},
                { title: "If Write-off \nApproval Status", width: '5%', align: 'center', type:'dropdown', source:statusDD, readOnly: true },
                { title: "Approved By", width: '5%', align: 'center',readOnly: true },
                { title: "Approved \nDate &Time", width: '5%', type:'text',  readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('credit_note'),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                
                if(col == 2) 
                {
                    
                }
                
                
                
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 1) 
                {
                    row_id = val;
                }
                if(col == 2) 
                {
                    status = data.creditNoteDetails[row][10];
                    if(row_id != '' && status != 2) {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 3) 
                {
                    invVal = val;
                    // if(data.paymentRequestBill[row][11] > 0) {
                    //     txtValue = data.paymentRequestBill[row][6]; 
                    //     $(cell).text(txtValue);
                    //     instance.jexcel.options.data[row][col] = txtValue;
                    // }
                    
                    // getBillData(val);
                    
                    
                }
                if(col == 4) 
                {
                    if(row_id != '' && status != 2) {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 5) 
                {
                    if(row_id != '' && status != 2 ) {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 6) 
                {
                    if(row_id != '' && status != 2 ) {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 7) 
                {
                    if(row_id != '' && status != 2 ) {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                
                
                if(col == 8) 
                {
                    
                    // if(data.paymentRequestBill[row][11] > 0) {
                    //     txtValue = data.paymentRequestBill[row][11]; 
                    //     $(cell).text(txtValue);
                    //     instance.jexcel.options.data[row][col] = txtValue;
                    // }
                    // if(val > 0) {
                    // txtValue = numeral(val).format('0.00');
                    // $(cell).text(txtValue);
                    // instance.jexcel.options.data[row][col] = txtValue;
                    // }
                    if(invVal != '' ) {
                        const billDatas = getBillData(invVal);
                        $(cell).text(billDatas['amt']);
                        instance.jexcel.options.data[row][col] = billDatas['amt'];
                    }
                }
                
                if(col == 9) 
                {
                    // if(data.paymentRequestBill[row][11] > 0) {
                    //     txtValue = data.paymentRequestBill[row][13]; 
                    //     $(cell).text(txtValue);
                    //     instance.jexcel.options.data[row][col] = txtValue;
                    // }
                    
                    if(invVal != '' ) {
                        const billDatas = getBillData(invVal);
                        $(cell).text(billDatas['currency']);
                        instance.jexcel.options.data[row][col] = billDatas['currency'];
                    }
                }
                
                // if(col == 11) 
                // {
                //     unit = numeral(val).format('0.00');
                //     $(cell).text(unit);
                //     instance.jexcel.options.data[row][col] = unit;
                // }
                
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
    
//     insertrow = function(obj) {
//     alert('new row added on table: ' + $(obj).prop('id'));
//     // console.log(obj);
// }

/*var customColumn = {
    // Methods
    openEditor : function(cell) {
        var editor = document.createElement('select');
        $(cell).html(editor);
        $(editor).prop('class', 'editor');
        $(editor).html('<option>Brazil</option><option>Canada</option>')
    },
    getValue : function(cell) {
        return cell.innerHTML;
    },
    setValue : function(cell, value) {
        cell.innerHTML = value;
    }
}

$(document).on("click",".newdropdown", function () {
    var readonlycheck = $(this)[0].className;
    if(readonlycheck.includes('readonly'))
    {

        
    }
    else
    {
        console.log($(this).children('jdropdown-content'));
        if($(this)[0].children.length>0)
        {
            console.log($(this)[0].children[0].children[1].innerText);
        }
        for(var i=0;i<bom_requirement_data.paymentLog.length;i++)
        {
            console.log(bom_requirement_data.paymentLog[i][12]);
        }
    }
        
});*/


function getBillData(val)
{
    const checkData = [];
    let payment_data = paymentRequestBill_vm.getData();
    for (let k = 0; k < payment_data.length; k++) {
        if(payment_data[k][6]  == val) {
            checkData['amt'] = payment_data[k][11];
            checkData['currency'] = payment_data[k][13];
        }
    }
    
    return checkData;
    
}

function dropdownFilter(instance, cell, c, r, source) {
  //get the manufacturer_id from the previus column
  //var manufacturer_id = instance.jexcel.getValueFromCoords(c - 1, r);
  var returnArray =[];
  for(var i=0;i<paidTracking.length;i++)
  {
     // console.log(paidTracking[i]);
      source.filter(function (item) {
        if(item==paidTracking[i][1] && paidTracking[i][12]!="PAID")
        {
            returnArray.push(item);
        }
    })
  }
    return returnArray;
}

    
    function append_payment_paid_request(data) {
        const paymentDD = [ "ADV. PAYMENT", "BILL PAYMENT", "OTH. PAYMENT", "NIL" ];
        var rcdata = [];
        $('#paymentPaidDetails').html('');
        let list = {
            data: data.paymentPaidDetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'Req. Count', width:'4%',align:'center',type:'dropdown',source:data.rc_no,filter: dropdownFilter},
                { title: 'Paid in Favour of', width: '8%', align: 'left', type: 'text', readOnly: true },
                { title: 'Bank Name', width: '8%', align: 'left', readOnly: true },
                { title: 'Account Number', width: '8%', align: 'center', readOnly: true },
                { title: 'Mode of\n Payment', width: '6%', align: 'center', type: 'dropdown', source: [ 'IMPS', 'NEFT', 'RTGS', 'SWIFT', 'CHEQUE', 'CASH' ] },
                { title: 'Transaction ID /\nCheque / Voucher No.', width: '10%', align: 'left' },
                { title: 'Transaction\nCheque / Voucher Date', width: '8%', align: 'center', type:'calendar', options: {format: 'DD/MM/YYYY'} },
                { title: "Paid Towards", width: '6%', align: 'left', type: 'dropdown', source: paymentDD , readOnly: true },
                { title: "Amount", width: '6%', align: 'right' },
                { title: "Currency", width: '4%', align: 'center', type: 'dropdown', source: data.currencyList, readOnly: true },
                { title: "Exch. Rate Per \nUnit (Rs.)", width: '6%', align: 'right' },
                { title: "Amount", width: '6%', align: 'right',readOnly: true },
            ],
            minDimensions: [4, 1],
           // oninsertrow:insertrow,
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('bill_paid'),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 1) 
                {
                    row_id = val;
                    
                    
                }
                if(col == 2) 
                {
                    
                    
                }
                
                
                if(col == 10) 
                {
                    
                }
                if(col == 12) 
                {
                    
                }
                
                
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                
                if(col == 1) 
                {
                    row_id = val;
                }
                if(col == 2) 
                {
                    $(cell).addClass('newdropdown');
                    if(row_id != '') {
                        $(cell).addClass('readonly');
                        $(cell).type = 'text';
                    }
                    rc_no =val;
                }
                if(col == 3) 
                {
    
                    if(row_id == '' && rc_no != '') {
                    let dataform = new FormData();
                    dataform.append('pId', pId);
                    dataform.append('row_id', rc_no);
                    let request = $.ajax({
                    type: "POST",
                    url: base_path + 'request/Bomrequest/getRCData',
                    data: dataform,
                    processData: false,
                    contentType: false,
                    cache: false,
                        success: function (data) {
                            rcdata = $.parseJSON(data);
                                $.each(rcdata,function(key,value){
                                    if(key == 'vendor_name') {
                                        vendor_name = value;
                                    }
                                });
                            $(cell).text(vendor_name);
                            instance.jexcel.options.data[row][col] = vendor_name;
                        },
                        error: function () {
                            console.log("Error");
                        }
                    });
                    
                } else {
                    // if(vendorId != '')
                    // {
                    //     let index = data.vendor_data.findIndex(p => p.id == vendorId);
                    //     // let fil_vendor = [];
                    //     fil_vendor = data.vendor_data[index];
                    //     // console.log(fil_vendor)
                    //     if(fil_vendor != undefined)
                    //     {
                    //         $(cell).text(fil_vendor.vendorname);
                    //         instance.jexcel.options.data[row][col] = fil_vendor.vendorname;
                    //     }
                    //     else {
                    //         $(cell).text('');
                    //         instance.jexcel.options.data[row][col] = '';
                    //     }
                    // }
                }
                }
                if(col == 4) 
                {
    
                    if(row_id == '' && rc_no != '') {
                    let dataform = new FormData();
                    dataform.append('pId', pId);
                    dataform.append('row_id', rc_no);
                    let request = $.ajax({
                    type: "POST",
                    url: base_path + 'request/Bomrequest/getRCData',
                    data: dataform,
                    processData: false,
                    contentType: false,
                    cache: false,
                        success: function (data) {
                            rcdata = $.parseJSON(data);
                                $.each(rcdata,function(key,value){
                                    if(key == 'bank_name') {
                                        bank_name = value;
                                    }
                                });
                            $(cell).text(bank_name);
                            instance.jexcel.options.data[row][col] = bank_name;
                        },
                        error: function () {
                            console.log("Error");
                        }
                    });
                    
                } else {
                    // if(vendorId != '')
                    // {
                    //     let index = data.vendor_data.findIndex(p => p.id == vendorId);
                    //     // let fil_vendor = [];
                    //     fil_vendor = data.vendor_data[index];
                    //     // console.log(fil_vendor)
                    //     if(fil_vendor != undefined)
                    //     {
                    //         $(cell).text(fil_vendor.bankname);
                    //         instance.jexcel.options.data[row][col] = fil_vendor.bankname;
                    //     }
                    //     else {
                    //         $(cell).text('');
                    //         instance.jexcel.options.data[row][col] = '';
                    //     }
                    // }
                }
                }
                if(col == 5) 
                {
    
                    if(row_id == '' && rc_no != '') {
                    let dataform = new FormData();
                    dataform.append('pId', pId);
                    dataform.append('row_id', rc_no);
                    let request = $.ajax({
                    type: "POST",
                    url: base_path + 'request/Bomrequest/getRCData',
                    data: dataform,
                    processData: false,
                    contentType: false,
                    cache: false,
                        success: function (data) {
                            rcdata = $.parseJSON(data);
                                $.each(rcdata,function(key,value){
                                    if(key == 'acc_no') {
                                        acc_no = value;
                                    }
                                });
                            $(cell).text(acc_no);
                            instance.jexcel.options.data[row][col] = acc_no;
                        },
                        error: function () {
                            console.log("Error");
                        }
                    });
                    
                } else {
                    // if(vendorId != '')
                    // {
                    //     let index = data.vendor_data.findIndex(p => p.id == vendorId);
                    //     // let fil_vendor = [];
                    //     fil_vendor = data.vendor_data[index];
                    //     // console.log(fil_vendor)
                    //     if(fil_vendor != undefined)
                    //     {
                    //         $(cell).text(fil_vendor.accountno);
                    //         instance.jexcel.options.data[row][col] = fil_vendor.accountno;
                    //     }
                    //     else {
                    //         $(cell).text('');
                    //         instance.jexcel.options.data[row][col] = '';
                    //     }
                    // }
                }
                }
                // if(col == 4)
                // {
                //     // console.log(vendorId)
                //     if(vendorId != '')
                //     {
                //         let index = data.vendor_data.findIndex(p => p.id == vendorId);
                //         // let fil_vendor = [];
                //         fil_vendor = data.vendor_data[index];
                //         // console.log(fil_vendor)
                //         if(fil_vendor != undefined)
                //         {
                //             $(cell).text(fil_vendor.bankname);
                //             instance.jexcel.options.data[row][col] = fil_vendor.bankname;
                //         }
                //         else {
                //             $(cell).text('');
                //             instance.jexcel.options.data[row][col] = '';
                //         }
                //     }
                // }
                // if(col == 5)
                // {
                //     if(vendorId != '')
                //     {
                //         let index = data.vendor_data.findIndex(p => p.id == vendorId);
                //         // let fil_vendor = [];
                //         fil_vendor = data.vendor_data[index];
                //         // console.log(fil_vendor)
                //         if(fil_vendor != undefined)
                //         {
                //             $(cell).text(fil_vendor.accountno);
                //             instance.jexcel.options.data[row][col] = fil_vendor.accountno;
                //         }
                //         else {
                //             $(cell).text('');
                //             instance.jexcel.options.data[row][col] = '';
                //         }
                //     }
                // }
                if(col == 6) 
                {
                    if(row_id != '') {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 7) 
                {
                    if(row_id != '') {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 8) 
                {
                    if(row_id != '') {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 9) 
                {
                    if(row_id != '') {
                        $(cell).addClass('readonly');
                    }
                    if(row_id == '' && rc_no != '') {
                    let dataform = new FormData();
                    dataform.append('pId', pId);
                    dataform.append('row_id', rc_no);
                    let request = $.ajax({
                    type: "POST",
                    url: base_path + 'request/Bomrequest/getRCData',
                    data: dataform,
                    processData: false,
                    contentType: false,
                    cache: false,
                        success: function (data) {
                            rcdata = $.parseJSON(data);
                                $.each(rcdata,function(key,value){
                                    if(key == 'req_for') {
                                        req_for = value;
                                    }
                                });
                            $(cell).text(req_for);
                            instance.jexcel.options.data[row][col] = req_for;
                        },
                        error: function () {
                            console.log("Error");
                        }
                    });
                    
                } else {
                    
                } 
                    
                }
                
                if(col == 10) 
                {
                 
                    if(row_id != '') {
                        $(cell).addClass('readonly');
                    }
                    //console.log(row_id);
                    //console.log(rc_no);
                    if(row_id == '' && rc_no != '') {
                        rate= val;
                        let dataform = new FormData();
                        dataform.append('enquiry_id', enquiry_id);
                        dataform.append('reqId', reqId);
                        dataform.append('pId', pId);
                        dataform.append('row_id', rc_no);
                        let request = $.ajax({
                        type: "POST",
                        url: base_path + 'request/Bomrequest/getRCData',
                        data: dataform,
                        processData: false,
                        contentType: false,
                        cache: false,
                            success: function (data) {
                                rcdata = $.parseJSON(data);
                                //console.log(rcdata);
                                $.each(rcdata,function(key,value){
                                    if(key == 'total_amt') {
                                        total_amt = value;
                                    } else if(key == 'paid_amt') {
                                        paid_amt = value;
                                    }
                                });
                                
                                //console.log(paid_amt);
                                  
                                 paid_tot = parseFloat(val) + parseFloat(paid_amt);
                                 //console.log(total_amt);
                                 if(paid_tot <= total_amt) {
                                    txtValue = numeral(val).format('0.00');
                                    $(cell).text(txtValue);
                                    instance.jexcel.options.data[row][col] = txtValue;
                                    $('#savePayment').show();
                                 } else {
                                     if(val > 0) {
                                     amount = 0;
                                     txtValue = numeral(amount).format('0.00');
                                    $(cell).text(txtValue);
                                    instance.jexcel.options.data[row][col] = txtValue;
                                    $('#savePayment').hide();
                                     alert('Enter Correct Value');
                                     }
                                 }
                                
                            },
                            error: function () {
                                console.log("Error");
                            }
                        });
                        
                        
                    } else if(row_id != '' && rc_no != '') {
                        rate = val;
                        
                    }else {
                        rate = 0;
                    }
                }
                if(col == 11) 
                {
                    if(row_id != '') {
                        $(cell).addClass('readonly');
                    }
                    if(row_id == '' && rc_no != '') {
                    let dataform = new FormData();
                    dataform.append('pId', pId);
                    dataform.append('row_id', rc_no);
                    let request = $.ajax({
                    type: "POST",
                    url: base_path + 'request/Bomrequest/getRCData',
                    data: dataform,
                    processData: false,
                    contentType: false,
                    cache: false,
                        success: function (data) {
                            rcdata = $.parseJSON(data);
                                $.each(rcdata,function(key,value){
                                    if(key == 'currency') {
                                        currency = value;
                                    }
                                });
                            $(cell).text(currency);
                            instance.jexcel.options.data[row][col] = currency;
                        },
                        error: function () {
                            console.log("Error");
                        }
                    });
                    
                } else {
                    
                } 
                }
                
                if(col == 12) 
                {
                    
                    if(val > 0 ) {
                    unit = numeral(val).format('0.00');
                    $(cell).text(unit);
                    instance.jexcel.options.data[row][col] = unit;
                    } else {
                       unit = 0; 
                    }
                    if(row_id != '') {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 13)
                {
                    //console.log(rate);
                
                   if(rate != '' && unit !=  '') {
                       
                    amount = parseFloat(rate) * parseFloat(unit);
                    txtValue = numeral(amount).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    //$('#getValues').attr('disabled','false');
                   } else {
                        amount = 0;
                        txtValue = numeral(amount).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                   }
                
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
    
    function append_purchase_request_others(data, tblData = []) {
        $('#paymentRequestOthers').html('');

        let list = {
            data: data.paymentOthers,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: "Req. Count", width: '4%', align: 'center', readOnly: true},
                { type: 'text',title: 'Pay in Favour of', width: '8%', align: 'center', readOnly: true},
                { type: 'text',title: 'Bank Name', width: '8%', align: 'center', readOnly: true},
                { type: 'text',title: 'Account Number ', width: '8%', align: 'center', readOnly: true},
                { type: 'text',title: 'IFSC Code /\nSWIFT Code', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Expenses \nIncured Towards', width: '7%', align: 'center', readOnly: true},
                { type: 'text',title: 'Ref. \nD.C. No.', width: '8%', align: 'center', readOnly: true},
                { type: 'text',title: 'D.C. Date', width: '6%', align: 'center', readOnly: true},
                { type: 'text',title: 'Ref. \nInvoice No.', width: '8%', align: 'center', readOnly: true},
                { type: 'text',title: 'Invoice \nDate', width: '6%', align: 'center', readOnly: true},
                { type: 'text',title: 'Amount \nPayable', width: '7%', align: 'right', readOnly: true},
                { type: 'text',title: 'Currency', width: '5%', align: 'center', readOnly: true},
                { type: 'text',title: 'Mode of\nPayment', width: '5%', align: 'center', readOnly: true},
                { type: 'text',title: 'Pay by Date', width: '6%', align: 'center', readOnly: true},
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
    
    function append_request_payment_log(data) {

        const requestDD = [ "P.I.APPROVAL", "PAYMENTS","NIL", "PAY. APPROVAL" ];
        const paymentDD = [ "ADV. PAYMENT", "BILL PAYMENT", "OTH. PAYMENT", "NIL" ];
        const statusDD = [ 
            { id: 0, name: "PENDING" },
            { id: 3, name: "RR - PENDING" },
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
                { type: 'dropdown', title: 'Request Type', width: '7%', align: 'left', source: approvalDD, readOnly: true },
                { type: 'text', title: 'Req. Date & Time', width: '7%', align: 'center', readOnly: true },
                { type: 'text', title: 'Payment\n Requirement', width: '7%', align: 'left', source: paymentDD, readOnly: true },
                { type: 'dropdown', title: 'Approval Status', width: '7%', align: 'center', source: statusDD, readOnly: true },
                { type: 'dropdown', title: 'Approval Type', width: '7%', align: 'left', source: approvalDD, readOnly: true },
                { type: 'text', title: 'Approved By', width: '7%', align: 'left', readOnly: true },
                { type: 'text',title: 'Approval \n Date  & Time', width: '7%', align: 'center', readOnly: true },
                // { type: 'dropdown', title: 'Request Status', width: '8%', align: 'left', source: reqStatus },
                // { type: 'text',title: 'Req. Status Update Date & Time', width: '6%', align: 'center', readOnly: true },
                { type: 'dropdown', title: "Payment Paid\n Status", width: '6%', align: 'left', source: paymentStatusDD },
                { type: 'text',title: "Payment Paid Status \n Update Date  & Time", width: '8%', align: 'center', readOnly: true },
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'rc_tot', width:'0%',align:'center',type:'hidden'},
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
                    status_val = val;

                    let Approval_status = data.paymentLog[row][6];
                    
                    if(Approval_status == 0 ) {
                        $(cell).css('background-color', '#FFA519');
                    }
                    else if(Approval_status == 1) {
                        $(cell).css('background-color', '#5DE684');
                    } else if(Approval_status == 2) {
                        $(cell).css('background-color', '#fc0303e1');
                    } else if(Approval_status == 3) {
                        $(cell).css('background-color', '#fc0303e1');
                    } 
                     else if(Approval_status == 4) {
                        $(cell).css('background-color', '#fc0303e1');
                    } 
                }
                if(col == 8)
                {
                    approvedBy = val;
                }
                if(col == 10)
                {
                    if(payment_req == 'NIL') {
                        $(cell).addClass('readonly');
                        $(cell).text('NIL');
                    }
                    if(data.paymentLog[row][12] == 'PAID') {
                       $(cell).addClass('readonly');
                    } 
                    if(status_val != 1) {
                        $(cell).addClass('readonly');
                    }

                    
                     let payment_status = data.paymentLog[row][10];
                    console.log(payment_status);
                    if(payment_status == 'PAID' ) {
                        $(cell).css('background-color', '#5DE684');
                    }
                    else if(payment_status == 'PART PAID') {
                        $(cell).css('background-color', '#5DE684');
                    } else if(payment_status == 'DISCREPANCY') {
                        $(cell).css('background-color', '#fc0303e1');
                    }else{
                         //$(cell).text('PENDING');
                         $(cell).text('-');
                        //$(cell).css('background-color', '#FFA519');
                    }
                }
                
                // if(col == 12)
                // {
                //     if(status_val == 1) {
                //         $(cell).addClass('readonly');
                //     } else {
                //         $(cell).removeClass('readonly');
                //     }
                // }
                
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
                { title: 'Qty.', width: '6%', align: 'left', readOnly: true },
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
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right',readOnly: true },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                { title: "IGST\n (%)", width: '6%', align: 'right', readOnly: true},
                { title: "IGST\n VALUE", width: '6%', align: 'right', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('inter'),
            // updateTable: function(instance, cell, col, row, val, label) {
            //     if(col == 9)
            //     {
            //         qty = val;
            //     }
            //     if(col == 11) {
            //         txtValue = numeral(val).format('0.00');
            //         unit_rate = txtValue;
            //     }
            //     if(col == 12) {
            //         amount = parseFloat(qty) * parseFloat(unit_rate);
            //         txtValue = numeral(amount).format('0.00');
            //         $(cell).text(txtValue);
            //         instance.jexcel.options.data[row][col] = txtValue;
            //     }
            //     if(col == 13) {
            //         txtValue = numeral(val).format('0.00');
            //         $(cell).text(txtValue);
            //         instance.jexcel.options.data[row][col] = txtValue;
            //         igst = txtValue;
            //     }
            //     if(col == 14) {
            //         igst_value = parseFloat(qty) * parseFloat(igst) / 100;
            //         txtValue = numeral(igst_value).format('0.00');
            //         $(cell).text(txtValue);
            //         instance.jexcel.options.data[row][col] = txtValue;
            //     }
            //     if(col == 15) {
            //         sub_total = parseFloat(qty) + parseFloat(igst_value);
            //         txtValue = numeral(sub_total).format('0.00');
            //         $(cell).text(txtValue);
            //         instance.jexcel.options.data[row][col] = txtValue;
            //     }
            // }
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
                { title: "Currency", width: '5%', align: 'left', type: 'dropdown', source: data.currencyList, readOnly: true },
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

    function validateForm(validateField, dataValue ) {
        let errorCount = 0;
        let tot_count = dataValue.length;
        let addCount = 0;
        for (let i = 0; i < tot_count; i++) {
            if(dataValue[i][1] == "") {
                addCount++;
            }
        }
        if(addCount > 0) {
            for (let i = 0; i < dataValue.length; i++) {
                if(dataValue[i][2] != "") {
                    for(let j = 0; j < validateField.length; j++) {
                        let col = validateField[j]
                        if(dataValue[i][col] == "") {
                            errorCount++;
                        }
                    }
                } 
            }
        } else {
            errorCount++;
        }
        return errorCount;
    }

    function validatePaidForm(validateField, dataValue, checkdata ) {
        let errorCount = 0;
        let checkCount = 0;
        let total_check = dataValue.length;
        for (let k = 0; k < checkdata.length; k++) {
            
            if(checkdata[k][11] > 0) {
                checkCount++;
            }
        }
        if(checkCount > total_check) {
            errorCount++;
        } else if(checkCount == 0) {
            errorCount++;
        } else {
            for (let i = 0; i < checkCount; i++) {
                if(dataValue[i][2] > 0) {
                    errorCount++;
                }
                
                for (let i = 0; i < checkCount; i++) {
                    
                if(dataValue[i][2] != '') {
                if(dataValue[i][2] == "CREDIT") {
                    let validateFields = [3,4,5,6,7,8,9];
                    for(let j = 0; j < validateFields.length; j++) {
                        let col = validateFields[j]
                        if(dataValue[i][col] == "") {
                            errorCount++;
                        }
                    }
                }
                else if(dataValue[i][2] == "CREDIT NOTE") {
                    let validateFields = [3,6,7,8,9];
                    for(let j = 0; j < validateFields.length; j++) {
                        let col = validateFields[j]
                        if(dataValue[i][col] == "") {
                            errorCount++;
                        }
                    }
                } 
                else {
                    let validateFields = [3,8,9];
                    for(let j = 0; j < validateFields.length; j++) {
                        let col = validateFields[j]
                        if(dataValue[i][col] == "") {
                            errorCount++;
                        }
                    }
                }
                
              }  else {
                  errorCount++;
              }
            }
        }
        }
        
        
        return errorCount;
    }
    
    
    function validatePaymentLogForm(paymentLogData,paid_data)
    {
        let errorCount = 0;
        let row_data = [];
        for (let i = 0; i < paid_data.length; i++) {
            //row_data = paid_data[i][2];
            row_data.push(paid_data[i][2]);
        }
        //console.log(row_data);
        for (let k = 0; k < paymentLogData.length; k++) {
            if(paymentLogData[k][10] != 'NIL') {
            if(paymentLogData[k][10] != '' && paymentLogData[k][12] != 'PAID') {
            // if(paymentLogData[k][10] == 'PART PAID' || paymentLogData[k][10] == 'PAID') {
                let row = paymentLogData[k][1];
                if(row_data.includes(row)) {
                    
                let tot_rc_amt = 0;
                if(paymentLogData[k][10] == 'PART PAID') {
                    //console.log('Part');
                    let tot_amt = paymentLogData[k][13];
                    
                    for (let i = 0; i < paid_data.length; i++) {
                        if(paid_data[i][2] == row) {
                            tot_rc_amt = parseFloat(tot_rc_amt) + parseFloat(paid_data[i][10]);
                        }
                    }
                    
                    if(tot_amt == tot_rc_amt) {
                        //console.log('yes');
                        errorCount++;
                    } else {
                        
                    }
                        
                } 
                else if(paymentLogData[k][10] == 'PAID') {
                    
                    let tot_amt = paymentLogData[k][13];
                    
                    for (let i = 0; i < paid_data.length; i++) {
                        if(paid_data[i][2] == row) {
                            tot_rc_amt = parseFloat(tot_rc_amt) + parseFloat(paid_data[i][10]);
                        }
                    }
                    if(tot_amt == tot_rc_amt) {
                        
                    } else if(tot_amt > tot_rc_amt) {
                        errorCount++;
                    }
                    
                } else if(paymentLogData[k][10] == 'PENDING') {
                    errorCount++;   
                }
                } else {
                  errorCount++; 
                } 
                
            // } 
            
            } 
        }
            
        }
        return errorCount;
    }
    
    $('#saveCredit').click(function () {
        let payment_data = paymentRequestBill_vm.getData();
        let credit_data = creditNoteDetails_vm.getData();
        let validateCreditField = [2,3,4,5,6,7,8,9];
        let validateCreditCount = validatePaidForm(validateCreditField, credit_data, payment_data);
        if(validateCreditCount > 0) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        } else {
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                updateCreditDetails(credit_data,payment_data);
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
    
    // $('#savePayment').click(function () {
    //     let paid_data = paymentPaidDetails_vm.getData();
    //     let payment_request_data = paymentRequestBill_vm.getData();
    //     let validateField = [6,7,8,9,10,11,12];
    //     let validatePaidCount = validateForm(validateField, paid_data);
    //     if(validatePaidCount > 0) {
    //         swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('validation_error')
    //             )
    //     } else {
    //     swalWithBootstrapButtons.fire(
    //         // *** CONFIRMATION MESSAGE *** //
    //         alertMessageFunction('confirmation_save')
    //     ).then(function (result) {
    //         if (result.value) {
    //             updatePaymentDetails(paid_data,payment_request_data);
    //         } 
    //         else if (result.dismiss === Swal.DismissReason.cancel) {
    //             // *** CANCELLED MESSAGE *** //
    //             swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('cancelled')
    //             );
    //         }
    //     });
    //     }
    // });

    $('#savePayment').click(function () {
    // Disable the Save button to prevent multiple clicks
    $('#savePayment').prop('disabled', true);

    let paid_data = paymentPaidDetails_vm.getData();
    let payment_request_data = paymentRequestBill_vm.getData();
    let validateField = [6, 7, 8, 9, 10, 11, 12];
    let validatePaidCount = validateForm(validateField, paid_data);

    if (validatePaidCount > 0) {
        swalWithBootstrapButtons.fire(
            alertMessageFunction('validation_error')
        ).then(function () {
            // Re-enable the Save button if validation fails
            $('#savePayment').prop('disabled', false);
        });
    } else {
        swalWithBootstrapButtons.fire(
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                updatePaymentDetails(paid_data, payment_request_data);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }

            // Re-enable the Save button after the process
            $('#savePayment').prop('disabled', false);
        });
    }
});
    
    function updateCreditDetails(credit_data,payment_data) {
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);
        dataform.append('credit_data', JSON.stringify(credit_data));
        dataform.append('payment_data', JSON.stringify(payment_data));

        let request = $.ajax({
            type: "POST",
                url: base_path + 'request/Bomrequest/updateCreditDetails',
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
                        window.location.href = base_path + 'request/Bomrequest/financereqreceiveddetails' + '/' + encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(reqId)) + '/' + encodeURIComponent(btoa(pId)) +'';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    let isSubmitting1 = false;
    function updatePaymentDetails(paid_data) {
        if (isSubmitting1) return;  // Prevent submission if already in progress
       isSubmitting1 = true;  
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);
        dataform.append('paid_data', JSON.stringify(paid_data));

        let request = $.ajax({
            type: "POST",
                url: base_path + 'request/Bomrequest/updatePaymentDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                 isSubmitting1 = false;
                // getDraftPIRequest();
                // // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                        window.location.href = base_path + 'request/Bomrequest/financereqreceiveddetails' + '/' + encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(reqId)) + '/' + encodeURIComponent(btoa(pId)) +'';
                    }
                });
            },
            error: function () {1
                isSubmitting1 = false; 
                console.log("Error");
            }
        });
    }

    
    $('#getValues').click(function () {
        let piTotal = 0;
        if(purchase_mode == 'within') {
            let within = withinStateReference_vm.getData()
            piTotal = withInStateTotal(within);
        }
        else if(purchase_mode == 'inter') {
            let inter = interStateReference_vm.getData()
            piTotal = interStateTotal(inter);
        }
        else if(purchase_mode == 'imports') {
            let imports = importStateReference_vm.getData()
            piTotal = importsStateTotal(imports);
        }
        billVal = numeral(piTotal).format('0.00');
        
        let paymentLogData = tblRequestPaymentLog.getData();
        let paid_data = paymentPaidDetails_vm.getData();
        let validatePaymentLogcount  = validatePaymentLogForm(paymentLogData,paid_data);
        //console.log(validatePaymentLogcount);
        if(validatePaymentLogcount > 0) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('statuserror')
                )
        } else {
            swalWithBootstrapButtons.fire(
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

    function withInStateTotal(within)
    {
        let billAmt = 0;
        for (let i = 0; i < within.length; i++) {
            if(within[i][15] > 0) {
                billAmt = parseFloat(billAmt)+parseFloat(within[i][15]);
            }
        }

        return billAmt;
    }

    function interStateTotal(inter)
    {
        let billAmt = 0;
        for (let i = 0; i < inter.length; i++) {
            if(inter[i][15] > 0) {
                billAmt = parseFloat(billAmt)+parseFloat(inter[i][15]);
            }
        }

        return billAmt;
    }

    function importsStateTotal(imports)
    {
        let billAmt = 0;
        for (let i = 0; i < imports.length; i++) {
            if(imports[i][13] > 0) {
                billAmt = parseFloat(billAmt)+parseFloat(imports[i][13]);
            }
        }

        return billAmt;
    }
    
    function validateCreditForm(validateField, dataValue ) {
        let errorCount = 0;
        let dataCount = dataValue.length;
        for (let i = 0; i < dataCount; i++) {
            if(dataValue[i][11] != "" && dataValue[i][0] == '') {
                if(dataValue[i][11] == "CASH") {
                    let validateFields = [2,6,7,8,9,10,11,12];
                    for(let j = 0; j < validateFields.length; j++) {
                        let col = validateFields[j]
                        if(dataValue[i][col] == "") {
                            errorCount++;
                        }
                    }
                }
                else if(dataValue[i][11] == "CHEQUE") {
                    let validateFields = [2,3,4,6,7,8,9,10,11,12];
                    for(let j = 0; j < validateFields.length; j++) {
                        let col = validateFields[j]
                        if(dataValue[i][col] == "") {
                            errorCount++;
                        }
                    }
                } 
                else {
                    for(let j = 0; j < validateField.length; j++) {
                        let col = validateField[j]
                        if(dataValue[i][col] == "") {
                            errorCount++;
                        }
                    }
                }
            } else {
                errorCount++;
            }
            
        }
        return errorCount;
    }
    function validatePaidDetaills(dataValue)
    {
        let errorCount = 0;
        let dataCount = dataValue.length;
        for (let i = 0; i < dataCount; i++) {
            errorCount++;
        }
        
        return errorCount;
        
    }

    function updateFunction(data) {
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);
        dataform.append('data', JSON.stringify(data));

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateFinanceReqRecDetails',
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
                         window.location.href = base_path + 'company/mfinanceuser/requestreceivedlist';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $('#billClosed').click(function () {
        let payment_data = tblRequestPaymentLog.getData();
        let paymentreq_data = paymentRequest_vm.getData();
        let bill_data = paymentRequestBill_vm.getData();
        let others_data = paymentRequestOthers_vm.getData();
        let paid_data = paymentPaidDetails_vm.getData();
        let paymentLogData = tblRequestPaymentLog.getData();
        let credit_data = creditNoteDetails_vm.getData();
        let validatepaymentField = [12];
        let validatePaymentCount = validatePaymentDetaills(validatepaymentField, payment_data);
        let validatetotAmt = validateTotalAmt(paymentreq_data, bill_data, others_data, paid_data, paymentLogData, credit_data );
        let validatePaidCount = validatePaidDetaills(paymentPaidDetails_vm);
        console.log(validatePaidCount);
        if(validatePaymentCount > 0) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('paiderror')
                )
        } else if(validatetotAmt != 'PAID') {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('amounterror')
                )
        } else if (validatePaidCount == 0) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('amounterror')
                )
        } else {
            swalWithBootstrapButtons.fire(
            alertMessageFunction('confirmation_save')
            ).then(function (result) {
            if (result.value) {
                
                billclosedFunction();
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
    
    function validatePaymentDetaills(validateField, dataValue)
    {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][12] != "PAID") {
                errorCount++;
            }
        }
        return errorCount;
    }
    
    function validateTotalAmt(paymentreq_data, bill_data, others_data, paid_data, paymentLogData, credit_data)
    {
        let errorCount = 0;
        let pendingCount = 0;
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
            if(bill_data[i][12] != 0) {
                billAmt = parseFloat(billAmt)+parseFloat(bill_data[i][12]);
            }
        }
        for (let i = 0; i < others_data.length; i++) {
            if(others_data[i][11] > 0) {
                othersAmt = parseFloat(othersAmt)+parseFloat(others_data[i][11]);
            }
        }
        for (let i = 0; i < paid_data.length; i++) {
            if(paid_data[i][10] > 0) {
                paidAmt = parseFloat(paidAmt)+parseFloat(paid_data[i][10]);
            }
        }

        for (let i = 0; i < paymentLogData.length; i++) {
            if(paymentLogData[i][12] == 'PAID') {
                
            } else {
                pendingCount++;
            }
        }
        for (let i = 0; i < credit_data.length; i++) {
            if(credit_data[i][2] == 'WRITE-OFF' && credit_data[i][10] == 1) {
                
            } else if(credit_data[i][2] == 'CREDIT') {
                
            } else if(credit_data[i][2] == 'CREDIT NOTE') {
                
            } else if(credit_data[i][2] == '') {
                
            } else {
                pendingCount++;
            }
        }
        
        tot_amt = parseFloat(payReqAmt) + parseFloat(billAmt) + parseFloat(othersAmt);
        // console.log(tot_amt);
        // console.log(paidAmt);
        //console.log(billAmt);
        if(pendingCount == 0) {
            if(paidAmt == tot_amt) {
                errorCount = 'PAID';
            } else {
                errorCount = 'PENDING';
            }
        } else {
            errorCount = 'PENDING';
        }
        //console.log(errorCount);
        return errorCount;
        
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

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});


function billclosedFunction()
    {
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateBillClosed',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                if(data == 'Success') {
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                         window.location.href = base_path + 'company/mfinanceuser/requestreceivedlist';
                    }
                });
                } else {
                     window.location.href = base_path + 'company/mfinanceuser/requestreceivedlist';
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }