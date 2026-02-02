$(document).ready(function () {

    let parts = window.location.href.split('/');
    let request_id = parts[parts.length - 1];
    let req_id = atob(decodeURIComponent(request_id));

    getQABomRequest();
    getPurchaseRequestImages();

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

    function getPurchaseRequestImages()
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

    // *********************************************************************************************************************************** 
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getQABomRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', req_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getMerchantBomQueueDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);
                
                let requirement = bom_requirement_data.req_data[0].purchase_req_type;

                if(requirement == "SAMPLE") {
                    $('#requirement').html('SAMPLE QTY.');
                }
                else if(requirement == "BULK") {
                    $('#requirement').html('BULK QTY.');
                }
                else if(requirement == "DISCREPANCY ") {
                    $('#requirement').html('DISCREPANCY  QTY.');
                }
                else if(requirement == "SHORTAGE") {
                    $('#requirement').html('SHORTAGE QTY.');
                }
                // $('#req_type').val(sample_requirement_data.req_data[0]['req_type']);
                // $('#merchant_note').val(sample_requirement_data.req_data[0]['merchant_note']);
                // $('#req_date').val(sample_requirement_data.req_data[0]['req_date']);
                // $('#auth_status').val(sample_requirement_data.req_data[0]['auth_status']);
                // $('#auth_type').val(sample_requirement_data.req_data[0]['auth_type']);
                // $('#auth_by').val(sample_requirement_data.req_data[0]['auth_by']);
                // $('#auth_date').val(sample_requirement_data.req_data[0]['auth_date']);
                // $('#mgmt_remark').val(sample_requirement_data.req_data[0]['mgmt_remark']);
                // $('#req_status').val(sample_requirement_data.req_data[0]['req_status']);
                // $('#req_status').val(sample_requirement_data.req_data[0]['req_status']);
                // $('#request_id').val(sample_requirement_data.req_data[0]['request_id']);
                append_bom_request(bom_requirement_data);
                append_source_details(bom_requirement_data);
                append_pi_details(bom_requirement_data);
                append_in_house_status(bom_requirement_data);
                append_item_accept_status(bom_requirement_data);
                append_in_house_consolidated_qty(bom_requirement_data);
                if(bom_requirement_data.req_data[0].mgmt_approval == 1) {
                    $('#auth_by').val(bom_requirement_data.req_data[0].auth_name);
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_bom_request(data) {
        $('#purchaseRequest').html('');

        let list = {
            data: data.purchaserequest,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
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
            allowInsertColumn: false
        };

        purchaseRequest_vm = new Vue({
            el: '#purchaseRequest',
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
    
    function append_source_details(data) {

        $('#sourceDetails').html('');
        let list = {
            data: data.sourcedetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
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
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        sourceDetailsReference_vm = new Vue({
            el: '#sourceDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_pi_details(data) {
        $('#piDetails').html('');
        let list = {
            data: data.pidetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Raised\n Date & Time', width: '5%', align: 'right', readOnly: true },
                { title: 'P.I. Ref. No.', width: '10%', align: 'right', readOnly: true },
                { title: 'P.I. Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Approval\n Status', width: '6%', align: 'right', readOnly: true },
                { title: 'P.I. Approved\n Date & Time', width: '5%', align: 'right', readOnly: true },
                { title: 'Expected\n Date of Delivery', width: '5%', align: 'right', readOnly: true },
                //{ title: 'Qty. Type', width: '8%', align: 'right', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
              updateTable: function(instance, cell, col, row, val, label) {

                 if(col == 11)
                {
                     mStatus1 = data.pidetails[row][11];

                       if(mStatus1 == "APPROVED") {
                        $(cell).css('background-color', '#5DE684');
                    } else if(mStatus1 == "PENDING") {
                        $(cell).css('background-color', '#FFA519');
                    } else if(mStatus1 == "DECLINED") {
                        $(cell).css('background-color', '#fc0303ff');
                    }
                     //console.log(mStatus1);

                }

              }

        };

        piDetailsReference_vm = new Vue({
            el: '#piDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_in_house_status(data) {
        $('#inHouseStatus').html('');
        let list = {
            data: data.inhousestatusdetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '15%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '8%', align: 'center', readOnly: true },
                { title: 'UOM', width: '8%', align: 'center', readOnly: true },
                { title: 'P.I. Ref. No.', width: '15%', align: 'right', readOnly: true },
                { title: 'D.C. No.', width: '15%', align: 'right', readOnly: true },
                { title: 'D.C. Date', width: '8%', align: 'right',  type: 'calendar', options: { format: 'DD/MM/YYYY' },readOnly: true },
                // { title: 'Item - Lot / Batch\nRef.No.', width: '8%', align: 'right', readOnly: true },
                { title: 'D.C. Qty.', width: '10%', align: 'right', readOnly: true },
                { title: 'Invoice No.', width: '15%', align: 'right', readOnly: true },
                { title: 'Invoice Date', width: '8%', align: 'right', type: 'calendar', options: { format: 'DD/MM/YYYY' }, readOnly: true },
                { title: 'Invoice Qty.', width: '10%', align: 'right', readOnly: true },
                { title: 'Received Date', width: '8%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' }, readOnly: true },
                { title: 'Received Qty.', width: '10%', align: 'right', readOnly: true },
                { title: 'UOM', width: '8%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            tableOverflow: true,
            tableWidth: "130%",
            
        };

        inHouseStatusReference_vm = new Vue({
            el: '#inHouseStatus',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_item_accept_status(data) {

        let approvalStatusData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'APPROVED' },
           { 'id': "2", 'name': 'DISCREPANCY' }
        ];

        let qaStatusData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'APPROVED' },
           { 'id': "2", 'name': 'DISCREPANCY' }
        ];
        
        let overridingStatusData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'APPROVED' },
           { 'id': "2", 'name': 'REPLACE ITEM' },
           { 'id': "3", 'name': 'RETURN ITEM & REORDER' },
           { 'id': "4", 'name': 'CANCEL P.I. & REORDER' },

            
        ];

        $('#itemAcceptStatus').html('');
        let list = {
            data: data.itemacceptstatus,
            columns: [
                { title:'id', width:'0%', align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '9%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '8%', align: 'left', readOnly: true },
                { title: 'D.C. No.', width: '14%', align: 'right', readOnly: true },
                { title: 'D.C. Date', width: '8%', align: 'right', readOnly: true, type:'calendar', options: { format: 'DD/MM/YYYY' } },
                { title: 'D.C. Qty.', width: '10%', align: 'right', readOnly: true,  },
                { title: 'UOM', width: '6%', align: 'right', readOnly: true },
                { title: 'Merchant Item \n Approval Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData },
                { title: 'Merchant Status \n Update Date & Time', width: '10%', align: 'center', type: 'text', readOnly: true },
                { title: 'Q.A. Status', width: '8%', align: 'center', type: 'dropdown', source: qaStatusData, readOnly: true },
                { title: 'Q.A. Status Update\n Date & Time', width: '10%', align: 'center', readOnly: true },
                { title: 'Management\n Overriding Status', width: '8%', align: 'center', type: 'dropdown', source: overridingStatusData, readOnly: true },
                { title: 'Management Status\n Update Date & Time', width: '10%', align: 'center',  readOnly: true },
                { title:'bom_id', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            tableOverflow: true,
            tableWidth: "130%",
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 5)
                {
                    dcno = val;
                }
                if(col == 9)
                {

                    //mStatus = '';
                    mStatus = data.itemacceptstatus[row][10];
                    if(mStatus === '' && dcno !== '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                      mStatus1 = data.itemacceptstatus[row][9];
                    if(mStatus1 == "1") {
                        $(cell).css('background-color', '#5DE684');
                    } else if(mStatus1 == "2") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(mStatus1 == "0" || mStatus === "") {
                        $(cell).css('background-color', '#FFA519');
                    }
                }
                 if(col == 10) {
                    if(val == '') {
                        $(cell).text('-');
                        //instance.jexcel.options.data[row][col] = 'N.A.';
                    }
                    
                }
                if(col == 11)
                {
                    qa_status = val;
                     if(qa_status == "1") {
                        $(cell).css('background-color', '#5DE684');
                    } else if(qa_status == "2") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(qa_status == "0" || qa_status === "") {
                        $(cell).css('background-color', '#FFA519');
                    }
                }
                 if(col == 12) {
                    if(val == '') {
                        $(cell).text('-');
                        //instance.jexcel.options.data[row][col] = 'N.A.';
                    }
                    
                }
                if(col == 13) 
                {

                     let qa_status_1 = data.itemacceptstatus[row][11];
                     let man_status_1 = data.itemacceptstatus[row][9];
                   
                     if(qa_status_1 == 1 && man_status_1 == 1) {
                        $(cell).text('N.A.');
                        instance.jexcel.options.data[row][col] = 'N.A.';
                          $(cell).css('background-color', '#FFA519');
                    }
                    let mgmt_status = data.itemacceptstatus[row][13];
                   if(mgmt_status == "1") {
                        $(cell).css('background-color', '#5DE684');
                    } else if(mgmt_status == "2") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(mgmt_status == "3") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(mgmt_status == "4") {
                        $(cell).css('background-color', '#ff2e2eb0');
                    } else if(mgmt_status == "0" || mgmt_status === '') {
                        $(cell).css('background-color', '#FFA519');
                    }
                }
                if(col === 14)
                {
                     let qa_status_2 = data.itemacceptstatus[row][11];
                    let man_status_2 = data.itemacceptstatus[row][9];
                      if(qa_status_2 != 0 && val == '') {
                        $(cell).text('N.A.');
                        instance.jexcel.options.data[row][col] = 'N.A.';
                    }
                     if((qa_status_2 == 0 || man_status_2 == 0) && val == '') {
                        $(cell).text('-');
                        instance.jexcel.options.data[row][col] = '-';
                    }
                    
                    
                }
                
            }
        };

        itemAcceptStatusReference_vm = new Vue({
            el: '#itemAcceptStatus',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    let supplyClosureData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'DISC. SUPPLY CLOSED' },
           { 'id': "2", 'name': 'SHORT SUPPLY - CLOSED' },
           { 'id': "3", 'name': 'FULL SUPPLY - CLOSED' },
           { 'id': "4", 'name': 'P.I. CANCELLED' }
        ];

    function append_in_house_consolidated_qty(data) {
        console.log( data.inhouseconsolidatedqtydetails);
        $('#inHouseConsolidatedQty').html('');
        let list = {
            data: data.inhouseconsolidatedqtydetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'Received Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'Difference Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Supply Closure\n Status', width: '8%', align: 'center', readOnly: true, },
                { title: 'Status Update \n Date & Time', width: '8%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 7) {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 8) {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 9) {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
                if(col ==11) {
                    let closure_status = data.inhouseconsolidatedqtydetails[row][11];
                    console.log(closure_status);
                    if(closure_status == "PENDING" ) {
                        $(cell).css('background-color', '#FFA519');
                    }
                    else if(closure_status == "DISC. SUPPLY CLOSED") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(closure_status == "SHORT SUPPLY CLOSED") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(closure_status == "FULL SUPPLY CLOSED") {
                        $(cell).css('background-color', '#5DE684');
                    } else if(closure_status == "P.I. CANCELLED") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else {
                        $(cell).css('background-color', '#FFA519');
                    }
                }
                 if(col == 12) {
                    //console.log(val);
                    let closure_status1 = data.inhouseconsolidatedqtydetails[row][11];
                    if(closure_status1 == "PENDING" ) {
                        $(cell).text('-');
                        instance.jexcel.options.data[row][col] = '-';
                    }
                    
                }
            }
            
        };

        inHouseConsolidatedReference_vm = new Vue({
            el: '#inHouseConsolidatedQty',
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
                let req_data = itemAcceptStatusReference_vm.getData();
                updateFunction(req_data);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });

    function updateFunction(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateMerchantQueue',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                getQABomRequest();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
                setTimeout(() => {
                    window.location.href = base_path + 'company/mqausers/merchantbomqueue';
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
    
    $('#saveData').click(function () {
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let req_data = itemAcceptStatusReference_vm.getData();
                updateMerchantStatus(req_data);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });
    
    function updateMerchantStatus(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', req_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateMerchantInHouseDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                getQABomRequest();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                        window.location.href = base_path + 'company/mqausers/merchantbomqueue';
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