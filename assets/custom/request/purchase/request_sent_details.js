$(document).ready(function () {
    
    let editCount = 0;
    let parts = window.location.href.split('/');
    let request_id = parts[parts.length - 2];
    let req_id = atob(decodeURIComponent(request_id));

    getDraftPIRequest();
    getMerchantImages()
    getPurchaseRequestImagesss();

    
    let purchase_mode = $('#p_mode').val();
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
    

    SUBSUMCOL = function(instance, columnId) {
        let total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        total = numeral(total).format('0.00');
        //console.log(total);
        total = (total > 0) ? total : '';
        if(editCount > 0) {
            get_amt();
        }
        return total;
    }
     
    function footer(grid_name)
    {
        if(grid_name == 'within')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '', '=SUBSUMCOL(TABLE(), COLUMN(), "")',  ]];
        }
        else if(grid_name == 'inter')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=SUBSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
        else if(grid_name == 'import')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=SUBSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
    }

    // *********************************************************************************************************************************** 
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    let bom_requirement_data = '';

    function getDraftPIRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', req_id);
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
                let pi_data = bom_requirement_data.pi_data[0];
                let payment_data = bom_requirement_data.paymentLog[0];
                purchase_mode = pi_data.mode;
                appl_status = payment_data[5];
                
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

                $('#purchase_type').val(pi_data.purchase_type);
                $('#exp_dod').val(pi_data.exp_dod);
                $('#amount_in_words').val(pi_data.amount_in_words);
                $('#payment_terms').val(pi_data.payment_terms);
                // $('#p_mode').val(purchase_mode);
                
                // if(appl_status == 2){
                //     $(".pi_decline").attr("readonly", false); 
                //     $('#purchaseImageUpload').show();
                // }
                // else{ 
                //     $(".pi_decline").attr("readonly", true); 
                //     $('#purchaseImageUpload').hide();
                // }

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
        data.append('reqId', req_id);
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
        data.append('reqId', req_id);
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
    
    $(document).on('click','.deleteImg',function(){
        var id = $(this).attr('data-id');

        if (confirm('Are you sure you want to delete the file')) {
            MakeAsynPostRequest(base_path + "WorkInProcess/deleteImageDetails", "&id=" + id, "json", function(data) {
                if(data.status == 'success')
                {
                    getPurchaseRequestImagesss();
                }
            });
        }

    });
    // function getPurchaseRequestImagesss()
    // {
    //     $('.purchaseImageView').hide();
    // }
    function getPurchaseRequestImagesss()
    {
        $('.purchaseImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', req_id);
        data.append('type', 'purchase_dept');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getpurchaserequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                 let subscriberId = imageJSON.subscriber_id;
                
                if((imageJSON.images.length) > 0) {
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
                $('#purchaseImageUpload').hide();
                } else {
                    
                        $('#purchaseImageUpload').show();
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
                { title: "RC No", width: '4%', align: 'center', readOnly: true},
                { type: 'dropdown', title: 'Vendor Name', width: '6%', align: 'left', source: data.vendor_data, readOnly: true },
                { title: "Vendor's Bank Name", width: '10%', align: 'left', readOnly: true},
                { title: 'Account Number', width: '8%', align: 'center', readOnly: true},
                { title: 'IFSC Code / \n SWIFT Code', width: '7%', align: 'center', readOnly: true},
                //{ title: 'SWIFT Code', width: '7%', align: 'center', readOnly: true},
                { type: 'text', title: 'Proforma Invoice No.', width: '6%', align: 'left'},
                { type: 'calendar', title: 'Proforma\n Invoice Date', width: '6%', align: 'left'},
                { type: 'text', title: 'Proforma\n Invoice Value', width: '6%', align: 'left'},
                { type: 'dropdown', title: 'Quoted\n Currency', width: '6%', align: 'left', source: data.currencyList},
                { type: 'dropdown', title: 'Accepted Mode\n of Payment', width: '7%', align: 'center', source: data.modeOfShipment},
                { title: 'Pay by\n Date & Time', width: '8%', align: 'center', type: 'calendar', options: { format:'DD-MM-YYYY HH12:MI AM/PM', time:1 } },
                { title: 'Amount\n Payable', width: '6%', align: 'right'},
                { type: 'dropdown', title: 'Currency', width: '6%', align: 'center', source: data.currencyList},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
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
                if(col == 5)
                {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                
                if(col == 6)
                {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 7)
                {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 8)
                {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 9)
                {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 10)
                {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 11)
                {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 12)
                {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 13)
                {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                
                // if(usertype == 7 && appl_status == 2) {

                //     if(col == 5)
                //     {
                //         $(cell).removeClass('readonly');
                //     }
                    
                //     if(col == 6)
                //     {
                //         $(cell).removeClass('readonly');
                //     }
                //     if(col == 7)
                //     {
                //         $(cell).removeClass('readonly');
                //     }
                    
                //     if(col == 8)
                //     {
                //         $(cell).removeClass('readonly');
                //         $(cell).addClass('jexcel_dropdown');
                //     }
                //     if(col == 9)
                //     {
                //         $(cell).removeClass('readonly');
                //         $(cell).addClass('jexcel_dropdown');
                //     }
                    
                //     if(col == 10)
                //     {
                //         $(cell).removeClass('readonly');
                //     }
                //     if(col == 11)
                //     {
                //         $(cell).removeClass('readonly');
                //     }
                    
                //     if(col == 12)
                //     {
                //         $(cell).removeClass('readonly');
                //         $(cell).addClass('jexcel_dropdown');
                //     }
                // }
                
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
                { title: "RC No", width: '4%', align: 'center', readOnly: true},
                { type: 'dropdown', title: 'Request For', width: '7%', align: 'left', source: requestDD, readOnly: true },
                { type: 'dropdown', title: 'Request Type', width: '7%', align: 'left', source: approvalDD,  },
                { type: 'text', title: 'Req. Date & Time', width: '7%', align: 'center', readOnly: true },
                { type: 'dropdown', title: 'Payment\n Requirement', width: '7%', align: 'left', source: paymentDD },
                //{ type: 'dropdown', title: 'Payment\n Requirement', width: '7%', align: 'left', source: paymentDD, readOnly: true },
                { type: 'dropdown', title: 'Approval Status', width: '7%', align: 'center', source: statusDD, readOnly: true },
                { type: 'dropdown', title: 'Approval Type', width: '7%', align: 'left', source: approvalDD, readOnly: true },
                { type: 'text', title: 'Approval By', width: '7%', align: 'left',  readOnly: true },
                { type: 'text',title: 'Approval \n Date  & Time', width: '7%', align: 'center', readOnly: true },
                // { type: 'dropdown', title: 'Request Status', width: '8%', align: 'left', source: statusDD, readOnly: true },
                // { type: 'calendar',title: 'Req. Status Update Date & Time', width: '6%', align: 'center', readOnly: true },
                { type: 'dropdown', title: "Payment Paid\n Status", width: '6%', align: 'left', source: paymentStatusDD, readOnly: true },
                { type: 'calendar',title: "Payment Paid Status\nUpdate Date  & Time", width: '8%', align: 'center', readOnly: true },
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'Purchase mode', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,

            updateTable: function(instance, cell, col, row, val, label) {
                if(usertype == 2) {

                    if(col == 4)
                    {
                        $(cell).removeClass('readonly');
                    }

                    if(col == 5)
                    {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 3)
                {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 5)
                {
                    let Approval_status = data.paymentLog[row][6];
                     console.log(Approval_status);
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                     
                    

                }
                if(col == 6)
                {
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
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right' },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
                { title: "GST\n (%)", width: '6%', align: 'right' },
                { title: "GST\n VALUE", width: '6%', align: 'right', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('within'),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 11)
                {
                    editCount = parseInt(editCount) + 1;
                    
                }
                if(col == 13)
                {
                    editCount = parseInt(editCount) + 1;
                    
                }
            },
            
            updateTable: function(instance, cell, col, row, val, label) {
                
                if(col == 9)
                {
                    qty = val;
                }
                if(col == 11) {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                    txtValue = numeral(val).format('0.00');
                    unit_rate = txtValue;
                    $(cell).text(unit_rate);
                    instance.jexcel.options.data[row][col] = unit_rate;
                }
                if(col == 12) {
                    amount = parseFloat(qty) * parseFloat(unit_rate);
                    txtValue = numeral(amount).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 13) {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    gst = txtValue;
                }
                if(col == 14) {
                    gst_value = parseFloat(amount) * parseFloat(gst) / 100;
                    txtValue = numeral(gst_value).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
                if(col == 15) {
                    sub_total = parseFloat(amount) + parseFloat(gst_value);
                    txtValue = numeral(sub_total).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
                // if(appl_status == 2) {

                //     if(col == 11)
                //     {
                //         $(cell).removeClass('readonly');
                //     }

                //     if(col == 13)
                //     {
                //         $(cell).removeClass('readonly');
                //     }
                    
                // }
                
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
                { title: 'Item\n Description', width: '7%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { title: 'Item Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim. (L*W*H)', width: '6%', align: 'center', readOnly: true },
                { title: 'UOM', width: '8%', align: 'center', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'right', readOnly: true },
                { title: "UOM", width: '6%', align: 'center', readOnly: true },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right'},
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
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 11)
                {
                    editCount = parseInt(editCount) + 1;
                    if(editCount > 0) {
                        get_amt();
                    }
                }
                if(col == 13)
                {
                    editCount = parseInt(editCount) + 1;
                    if(editCount > 0) {
                        get_amt();
                    }
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                
                if(col == 9)
                {
                    qty = val;
                }
                if(col == 11) {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                    txtValue = numeral(val).format('0.00');
                    unit_rate = txtValue;
                    $(cell).text(unit_rate);
                    instance.jexcel.options.data[row][col] = unit_rate;
                }
                if(col == 12) {
                    amount = parseFloat(qty) * parseFloat(unit_rate);
                    txtValue = numeral(amount).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 13) {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    gst = txtValue;
                }
                if(col == 14) {
                    gst_value = parseFloat(amount) * parseFloat(gst) / 100;
                    txtValue = numeral(gst_value).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
                if(col == 15) {
                    sub_total = parseFloat(amount) + parseFloat(gst_value);
                    txtValue = numeral(sub_total).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
                // if(appl_status == 2) {

                //     if(col == 11)
                //     {
                //         $(cell).removeClass('readonly');
                //     }

                //     if(col == 13)
                //     {
                //         $(cell).removeClass('readonly');
                //     }
                    
                // }
                
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
                { title: "Currency", width: '5%', align: 'left', type: 'dropdown', source: data.currencyList },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'right' },
                { title: "Amount\n (Rs.)", width: '6%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('import'),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 12)
                {
                    editCount = parseInt(editCount) + 1;
                    if(editCount > 0) {
                        get_amt();
                    }
                }
                
            },
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 9)
                {
                    qty = val;
                }
                if(col == 12) {
                    if(usertype == 7 && appr_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
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
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let req_data = paymentRequest_vm.getData();
                let log_data = tblRequestPaymentLog.getData();
                let purchaseIndentData = '';
                if(purchase_mode == 'within') {
                    purchaseIndentData = withinStateReference_vm.getData();
                }
                else if(purchase_mode == 'inter') {
                    purchaseIndentData = interStateReference_vm.getData();
                }
                else if(purchase_mode == 'imports') {
                    purchaseIndentData = importStateReference_vm.getData();
                }
                updateFunction(req_data, purchaseIndentData, log_data);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });

    $('#getValues2').click(function () {
      
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let req_data = paymentRequest_vm.getData();
                let log_data = tblRequestPaymentLog.getData();
                let purchaseIndentData = '';
                if(purchase_mode == 'within') {
                    purchaseIndentData = withinStateReference_vm.getData();
                }
                else if(purchase_mode == 'inter') {
                    purchaseIndentData = interStateReference_vm.getData();
                }
                else if(purchase_mode == 'imports') {
                    purchaseIndentData = importStateReference_vm.getData();
                }
                updateFunction2(req_data, purchaseIndentData, log_data);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });


     function updateFunction2(data, purchaseData, log_data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('log_data', JSON.stringify(log_data));
        dataform.append('purchaseIndentData', JSON.stringify(purchaseData));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', req_id);
        dataform.append('purchase_indent_id', pId);
        dataform.append('mode', purchase_mode);
        dataform.append('pi_cutoff_dt', $('#pi_cutoff_dt').val());
        dataform.append('purchase_dept_note', $('#purchase_dept_note').val());
        dataform.append('vendorOption', $('#vendorOption').val());
        dataform.append('purchase_type', $('#purchase_type').val());
        dataform.append('payment_terms', $('#payment_terms').val());
        dataform.append('amount_in_words', $('#amount_in_words').val());
        dataform.append('exp_dod', $('#exp_dod').val());
        purchaseUpload.startUpload();
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updatePurchaseIndent_cancelpi',
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
                        window.location.href = base_path + 'company/mpurchaseuser/purchasesentlist';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }


    function updateFunction(data, purchaseData, log_data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('log_data', JSON.stringify(log_data));
        dataform.append('purchaseIndentData', JSON.stringify(purchaseData));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', req_id);
        dataform.append('purchase_indent_id', pId);
        dataform.append('mode', purchase_mode);
        dataform.append('pi_cutoff_dt', $('#pi_cutoff_dt').val());
        dataform.append('purchase_dept_note', $('#purchase_dept_note').val());
        dataform.append('vendorOption', $('#vendorOption').val());
        dataform.append('purchase_type', $('#purchase_type').val());
        dataform.append('payment_terms', $('#payment_terms').val());
        dataform.append('amount_in_words', $('#amount_in_words').val());
        dataform.append('exp_dod', $('#exp_dod').val());
        purchaseUpload.startUpload();
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updatePurchaseIndent_request',
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
                        window.location.href = base_path + 'company/mpurchaseuser/purchasesentlist';
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

    $("#vendorOption").change(function() {
        let optionValue = $('option:selected', this).attr('data-index');
        if(optionValue !="") {
            let vendorDet = vendorResponse[optionValue];
            purchaseVendorData[0][1] = vendorDet.id;
            append_purchase_request(bom_requirement_data, purchaseVendorData);
            $("#vendorAddress").html(vendorDet.address);
            $("#vendorContact").html(vendorDet.phone);
            $("#vendorEmail").html(vendorDet.emailid);
            $("#vendorGst").html(vendorDet.gstno);
            $("#vendorIeCode").html(vendorDet.iecode);   
        }
        else {
            $("#vendorAddress").html("");
            $("#vendorContact").html("");
            $("#vendorEmail").html("");
            $("#vendorGst").html("");
            $("#vendorIeCode").html("");   
        }
    });
    
    let purchaseUpload = $("#purchaseImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'request/Bomrequest/purchaseImageUploadDetails',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            return {
                enquiry_id: enquiry_id,
                request_id: req_id,
            };
        },
        afterUploadAll: function () {
            
        },
        autoSubmit: false
    });


    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});


function get_amt()
    {
        
        
        purchaseIndentData = '';
        let p_mode = $('#p_mode').val();
            if(p_mode == 'within') {
                purchaseIndentData = withinStateReference_vm.getData();
            }
            else if(p_mode == 'inter') {
                purchaseIndentData = interStateReference_vm.getData();
            }
            else if(p_mode == 'imports') {
                purchaseIndentData = importStateReference_vm.getData();
            }
          
            getAmtToWords(purchaseIndentData,p_mode);
        
    }
    
    function getAmtToWords(purchaseIndentData,p_mode)
    {
        let tot = 0;
        let curr = '';
        if(p_mode == 'imports' ) {
            for (let i = 0; i < purchaseIndentData.length; i++) {
                if(purchaseIndentData[i][13] > 0) {
                    curr = purchaseIndentData[i][11];
                    tot = parseFloat(tot) +parseFloat(purchaseIndentData[i][13]) ;
                }
            }
            
            if(tot > 0) {
            var num =numeral(tot).format('0.00');;
            var splittedNum =num.toString().split('.')
            var nonDecimal=splittedNum[0]
            var decimal=splittedNum[1]
            
            if(decimal > 0) {
                var value=curr+"  "+price_in_words(Number(nonDecimal))+" point "+price_in_words1(decimal)+" "+"Only";
            } else {
                var value= curr+"  "+price_in_words(Number(nonDecimal))+" "+"Only";
            }
            
            // console.log(value);
            $('#amount_in_words').val(value);
        }
        
        } else {
            console.log('test');
            for (let i = 0; i < purchaseIndentData.length; i++) {
                if(purchaseIndentData[i][15] > 0) {
                    tot = parseFloat(tot) +parseFloat(purchaseIndentData[i][15]) ;
                }
            }
            
            if(tot > 0) {
            var num =numeral(tot).format('0.00');;
            var splittedNum =num.toString().split('.')
            var nonDecimal=splittedNum[0]
            var decimal=splittedNum[1]
            
            if(decimal > 0) {
                var value="Rupees"+" "+price_in_words(Number(nonDecimal))+" and Paise"+price_in_words(decimal)+" "+"Only";
            } else {
                var value="Rupees"+" "+price_in_words(Number(nonDecimal))+" "+"Only";
            }
            console.log(value);
            $('#amount_in_words').val(value);
        }
        }
        
        
        
    }
    
function price_in_words(price) {
    var sglDigit = ["Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"],
    dblDigit = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"],
    tensPlace = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"],
    handle_tens = function(dgt, prevDgt) {
      return 0 == dgt ? "" : " " + (1 == dgt ? dblDigit[prevDgt] : tensPlace[dgt])
    },
    handle_utlc = function(dgt, nxtDgt, denom) {
      return (0 != dgt && 1 != nxtDgt ? " " + sglDigit[dgt] : "") + (0 != nxtDgt || dgt > 0 ? " " + denom : "")
    };

  var str = "",
    digitIdx = 0,
    digit = 0,
    nxtDigit = 0,
    words = [];
  if (price += "", isNaN(parseInt(price))) str = "";
  else if (parseInt(price) > 0 && price.length <= 10) {
    for (digitIdx = price.length - 1; digitIdx >= 0; digitIdx--) switch (digit = price[digitIdx] - 0, nxtDigit = digitIdx > 0 ? price[digitIdx - 1] - 0 : 0, price.length - digitIdx - 1) {
      case 0:
        words.push(handle_utlc(digit, nxtDigit, ""));
        break;
      case 1:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 2:
        words.push(0 != digit ? " " + sglDigit[digit] + " Hundred" + (0 != price[digitIdx + 1] && 0 != price[digitIdx + 2] ? " and" : "") : "");
        break;
      case 3:
        words.push(handle_utlc(digit, nxtDigit, "Thousand"));
        break;
      case 4:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 5:
        words.push(handle_utlc(digit, nxtDigit, "Lakh"));
        break;
      case 6:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 7:
        words.push(handle_utlc(digit, nxtDigit, "Crore"));
        break;
      case 8:
        words.push(handle_tens(digit, price[digitIdx + 1]));
        break;
      case 9:
        words.push(0 != digit ? " " + sglDigit[digit] + " Hundred" + (0 != price[digitIdx + 1] || 0 != price[digitIdx + 2] ? " and" : " Crore") : "")
    }
    str = words.reverse().join("")
  } else str = "";
  return str

}

function price_in_words1(price) {
    var sglDigit = ["Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"],
    handle_utlc = function(dgt, nxtDgt, denom) {
      return ( 1 != nxtDgt ? " " + sglDigit[dgt] : "") 
    };

  var str = "",
    digitIdx = 0,
    digit = 0,
    nxtDigit = 0,
    words = [];
  if (price += "", isNaN(parseInt(price))) str = "";
  else if (parseInt(price) > 0 && price.length <= 2) {
    for (digitIdx = price.length - 1; digitIdx >= 0; digitIdx--) switch (digit = price[digitIdx] - 0, nxtDigit = digitIdx > 0 ? price[digitIdx - 1] - 0 : 0, price.length - digitIdx - 1) {
      case 0:
        words.push(handle_utlc(digit, nxtDigit, ""));
        break;
      case 1:
        words.push(handle_utlc(digit, price[digitIdx + 1]));
        break;
    }
    str = words.reverse().join("")
  } else str = "";
  return str

}

