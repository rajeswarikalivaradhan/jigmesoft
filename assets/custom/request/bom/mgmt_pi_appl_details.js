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

         if(mode == "approval_type") {
            return {
                title: 'Warning',
                text: "Please Select Field - Approval Type",
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

    function getDraftPIRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('pId', pId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getMgmtPIApplDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);
                let fData = bom_requirement_data.fullData[0];
                let pi_data = bom_requirement_data.pi_data[0];
                purchase_mode = pi_data.mode;
                
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

                $('#purchase_type').html(pi_data.purchase_type);
                $('#exp_dod').html(pi_data.exp_dod);
                $('#amount_in_words').html(pi_data.amount_in_words);
                $('#payment_terms').html(pi_data.payment_terms);
                
                append_within_state(bom_requirement_data);
                append_purchase_request(bom_requirement_data);
                append_payment_request_bill(bom_requirement_data);
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

    function getPurchaseImages()
    {
        $('.purchaseImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('pId', pId);
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
                { type: 'dropdown', title: 'Vendor Name', width: '6%', align: 'left', source: data.vendor_data, readOnly: true },
                { title: "Vendor's Bank Name", width: '10%', align: 'left', readOnly: true},
                { title: 'Account Number', width: '8%', align: 'center', readOnly: true},
                { title: 'IFSC Code / \n SWIFT Code', width: '7%', align: 'center', readOnly: true},
                // { title: 'SWIFT Code', width: '7%', align: 'center', readOnly: true},
                { type: 'text', title: 'Proforma Invoice No.', width: '6%', align: 'left', readOnly: true},
                { type: 'calendar', title: 'Proforma\nInvoice Date', width: '6%', align: 'center', options: { format:'DD/MM/YYYY ', time:1 }, readOnly: true},
                { type: 'text', title: 'Proforma\n Invoice Value', width: '7%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Quoted\n Currency', width: '6%', align: 'left', source: data.currencyList, readOnly: true},
                { type: 'dropdown', title: 'Accepted Mode\n of Payment', width: '7%', align: 'center', source: data.modeOfShipment, readOnly: true},
                { type: 'calendar', title: 'Pay by Date', width: '7%', align: 'center', readOnly: true},
                { title: 'Amount\n Payable', width: '6%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Currency', width: '6%', align: 'center', source: data.currencyList, readOnly: true},
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
                if(col == 2)
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
                if(col == 3)
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
                            $(cell).text(fil_vendor.ifscode);
                            instance.jexcel.options.data[row][col] = fil_vendor.ifscode;
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
    
    function append_payment_request_bill(data) {

        $('#paymentRequestBill').html('');
        let list = {
            data: data.paymentRequestBill,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'dropdown', title: 'Vendor Name', width: '6%', align: 'left', source: data.vendor_data, readOnly: true },
                { type: 'text', title: "Vendor's Bank Name", width: '10%', align: 'left', readOnly: true},
                { type: 'text', title: 'Account Number', width: '8%', align: 'center', readOnly: true},
                { type: 'text', title: 'IFSC Code', width: '6%', align: 'center', readOnly: true},
                { type: 'text', title: 'SWIFT Code', width: '6%', align: 'center', readOnly: true},
                { type: 'text', title: 'Invoice No.', width: '6%', align: 'left', readOnly: true},
                { type: 'calendar', title: 'Invoice \n Date', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Invoice Value', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Advance Paid', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Debits If Any', width: '6%', align: 'left', readOnly: true},
                { type: 'dropdown', title: 'Acc. Mode\n of Payment', width: '7%', align: 'center', source: data.modeOfShipment, readOnly: true},
                { type: 'calendar', title: 'Pay by Date', width: '7%', align: 'center', readOnly: true},
                { title: 'Amount\n Payable', width: '6%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Currency', width: '6%', align: 'center', source: data.currencyList, readOnly: true},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        tblPaymentRequestBill = new Vue({
            el: '#paymentRequestBill',
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
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Paid in Favour of', width: '7%', align: 'left', readOnly: true },
                { title: 'Bank Name', width: '12%', align: 'left', readOnly: true },
                { title: 'Account Number', width: '10%', align: 'left', readOnly: true },
                { title: 'Mode of\n Payment', width: '6%', align: 'left', readOnly: true },
                { title: 'Transaction ID / Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Transaction\n Date', width: '6%', align: 'left', readOnly: true },
                { title: 'Cheque No.', width: '8%', align: 'left', readOnly: true },
                { title: 'Cheque Date', width: '6%', align: 'left', readOnly: true },
                { title: "Paid Towards", width: '6%', align: 'left', readOnly: true },
                { title: "Amount", width: '5%', align: 'left', readOnly: true },
                { title: "Currency", width: '6%', align: 'left', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        sourceDetailsReference_vm = new Vue({
            el: '#paymentPaidDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_request_payment_log(data) {

        console.log(data.paymentLog);
        const requestDD = [ "P.I. APPROVAL", "PAYMENTS" ];
        const paymentDD = [ "ADV. PAYMENT", "BILL PAYMENT", "NIL" ];
        const statusDD = [ 
            { id: 0, name: "PENDING" },
            { id: 3, name: "PENDING - RR" },
            { id: 1, name: "APPROVED" },
            { id: 2, name: "DECLINED" },
            { id: 4, name: "P.I. CANCELLED" },
        ];
        const approvalDD = [ "REGULAR", "PRIORITY", "HIGH PRIORITY", "IMMEDIATE" ];
        const paymentStatusDD = [ "PENDING", "DISCREPANCY", "PART PAID", "FULL PAID" ];

        $('#requestPaymentLog').html('');
        let list = {
            data: data.paymentLog,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'dropdown', title: 'Request For', width: '7%', align: 'left', source: requestDD, readOnly: true },
                { type: 'dropdown', title: 'Request Type', width: '7%', align: 'left', source: approvalDD, readOnly: true },
                { type: 'text', title: 'Req. Date & Time', width: '7%', align: 'center', readOnly: true },
                { type: 'dropdown', title: 'Payment\n Requirement', width: '7%', align: 'left', source: paymentDD, readOnly: true },
                { type: 'dropdown', title: 'Approval Status', width: '7%', align: 'left', source: statusDD },
                { type: 'dropdown', title: 'Approval Type', width: '7%', align: 'left', source: approvalDD },
                { type: 'text', title: 'Approved By', width: '7%', align: 'left', readOnly:true },
                { type: 'text',title: 'Approval \n Date  & Time', width: '7%', align: 'center', readOnly: true },
                // { type: 'dropdown', title: 'Request Status', width: '8%', align: 'left', source: statusDD, readOnly: true },
                // { type: 'calendar',title: 'Req. Status Update Date & Time', width: '6%', align: 'center', readOnly: true },
                { type: 'dropdown', title: "Payment Paid\n Status", width: '6%', align: 'left', source: paymentStatusDD, readOnly: true },
                { type: 'text',title: "Payment Paid Status\nUpdate Date  & Time", width: '8%', align: 'center', readOnly: true, },
                { title:'id', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 5)
                {
                    if(val == '') {
                        txtValue = 0;
                        $(cell).text('PENDING');
                        instance.jexcel.options.data[row][col] = 'PENDING';
                    }
                    if(data.paymentLog[row][11] == 2 || data.paymentLog[row][11] == 1) {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }


                    let Approval_status = data.paymentLog[row][5];
                    
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
                
                if(col == 6)
                {
                    if(data.paymentLog[row][11] == 2 || data.paymentLog[row][11] == 1) {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
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
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right', readOnly: true },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                { title: "IGST\n (%)", width: '6%', align: 'right' , readOnly: true},
                { title: "IGST\n VALUE", width: '6%', align: 'right', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('inter'),
            
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
                { title: "Currency", width: '5%', align: 'left', type: 'dropdown', source: data.currencyList ,readOnly: true},
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right' ,readOnly: true },
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

    
    $('#getValues').click(function () {
        let tblRequestPaymentLog_data = tblRequestPaymentLog.getData();
       
        let validatetotAmt = validateTotalAmt(tblRequestPaymentLog_data );

        if(validatetotAmt == 0) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('approval_type')
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


     function validateTotalAmt(tblRequestPaymentLog_data )
    {
        //console.log(tblRequestPaymentLog_data);
        let errorCount = 0;
       
        for (let i = 0; i < tblRequestPaymentLog_data.length; i++) {
            //alert(tblRequestPaymentLog_data[i][6]);

            let data=tblRequestPaymentLog_data[i][6];
           if (data == "") {
          errorCount = 0;
            } else {
        errorCount = 1;
            }

            
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
            url: base_path + 'request/Bomrequest/updateManagementPurchaseIndentAppl',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
           success: function (data) {
        // Assuming 'data' is the response from the server
         swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then((okay) => {
                    if(okay)
                    {
                         window.location.href = base_path + 'management/allmanagamentpurchaseindent';
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

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});