$(document).ready(function () {

    let parts = window.location.href.split('/');
    let request_id = parts[parts.length - 1];
    let req_id = atob(decodeURIComponent(request_id));

    getDraftPIRequest();
    
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
    }

    // *********************************************************************************************************************************** 
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getDraftPIRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', req_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getPaymentRequestReceiveDetails',
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

                $('#supply_lead_time').html(fData.supply_lead_time);
                $('#payment_terms').html(fData.payment_terms);
                $('#pi_cutoff_dt').val(fData.pi_appl_cutoff_date_time);
                $('#purchase_dept_note').val(fData.purchase_dept_notes);
                append_purchase_request(bom_requirement_data);
                append_amount_paid_request(bom_requirement_data);
                append_within_state(bom_requirement_data);
                append_inter_state(bom_requirement_data);
                append_imports_state(bom_requirement_data);
                appendAddressField(bom_requirement_data.vendor_data, fData.vendor_id);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_purchase_request(data) {
        $('#paymentRequest').html('');

        let list = {
            data: data.paymentRequst,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'dropdown', title: 'Vendor Name', width: '6%', align: 'left', source: data.vendor_data , readOnly: true},
                { type: 'text', title: 'Proforma No.', width: '6%', align: 'left', readOnly: true},
                { type: 'calendar', title: 'Proforma\n Date', width: '6%', align: 'left', readOnly: true},
                { type: 'text', title: 'Proforma\n Value', width: '6%', align: 'left', readOnly: true},
                { type: 'dropdown', type: 'text', title: 'Quoted\n Currency', width: '6%', align: 'left', source: [], readOnly: true},
                { type: 'dropdown', title: 'Accepted Mode\n of Payment', width: '7%', align: 'center', source: [], readOnly: true},
                { type: 'calendar', title: 'Pay by Date', width: '7%', align: 'center', readOnly: true},
                { title: 'Amount\n Payable', width: '6%', align: 'right', readOnly: true},
                { type: 'dropdown', title: 'Currency', width: '6%', align: 'center', source: [], readOnly: true},
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
                if(col == 11)
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
                if(col == 12)
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
                if(col == 13)
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
                { title: 'id', width:'0%',align:'center',type:'hidden'},
                { title: 'payment_id', width:'0%',align:'center',type:'hidden'},
                { title: 'Paid in Favour of', width: '7%', align: 'left', readOnly: true },
                { title: 'Bank Name', width: '12%', align: 'left', readOnly: true },
                { title: 'Account Number', width: '10%', align: 'left', readOnly: true },
                { title: 'Mode of\n Payment', width: '6%', align: 'left', type:"dropdown", source: ["ON-LINE", "CHEQUE"]},
                { title: 'Transaction ID / Code', width: '6%', align: 'left'},
                { title: 'Transaction\n Date', width: '6%', align: 'left', type: 'calendar'},
                { title: 'Cheque No.', width: '8%', align: 'left'},
                { title: 'Cheque Date', width: '6%', align: 'left', type: 'calendar'},
                { title: "Amount Paid", width: '6%', align: 'left'},
                { title: "Currency", width: '5%', align: 'left', type:"dropdown",},
                { title: "Advance Paid in\n Full / Part", width: '6%', align: 'left', type:"dropdown", source: ["PART", "FULL"]},
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

        let perData = data.stateDetails;
        let count = 0;
        if(purchase_mode == 'within')
        {
            count = 10;
            for(let i = 0;i < count;i++)
            {
                perData[0].push('');
            }
        }

        $('#withinStateDetails').html('');
        let list = {
            data: perData,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Item\n Description', width: '7%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { title: 'Item Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim. (L*W*H)', width: '6%', align: 'left', readOnly: true },
                { title: 'UOM', width: '8%', align: 'left', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'left', readOnly: true },
                { title: "UOM", width: '6%', align: 'left', readOnly: true },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'left', readOnly: true },
                { title: "Amount\n (Rs.)", width: '6%', align: 'left', readOnly: true },
                { title: "GST\n (%)", width: '6%', align: 'left', readOnly: true },
                { title: "CGST\n (%)", width: '6%', align: 'left', readOnly: true },
                { title: "CGST\n VALUE", width: '6%', align: 'left', readOnly: true },
                { title: "SGST\n (%)", width: '6%', align: 'left', readOnly: true },
                { title: "SGST\n VALUE", width: '6%', align: 'left', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'left', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
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

        let perData = data.stateDetails;
        let count = 0;
        if(purchase_mode == 'inter')
        {
            count = 7;
            for(let i = 0;i < count;i++)
            {
                perData[0].push('');
            }
        }

        $('#interStateDetails').html('');
        let list = {
            data: perData,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Item\n Description', width: '7%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { title: 'Item Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim. (L*W*H)', width: '6%', align: 'left', readOnly: true },
                { title: 'UOM', width: '8%', align: 'left', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'left', readOnly: true },
                { title: "UOM", width: '6%', align: 'left', readOnly: true },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'left', readOnly: true },
                { title: "Amount\n (Rs.)", width: '6%', align: 'left', readOnly: true },
                { title: "IGST\n (%)", width: '6%', align: 'left', readOnly: true },
                { title: "IGST\n VALUE", width: '6%', align: 'left', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'left', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
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

        let perData = data.stateDetails;
        let count = 0;
        if(purchase_mode == 'imports')
        {
            count = 6;
            for(let i = 0;i < count;i++)
            {
                perData[0].push('');
            }
        }

        $('#importsStateDetails').html('');
        let list = {
            data: perData,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Item\n Description', width: '7%', align: 'left', readOnly: true },
                { title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { title: 'Item Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim. (L*W*H)', width: '6%', align: 'left', readOnly: true },
                { title: 'UOM', width: '8%', align: 'left', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'left', readOnly: true },
                { title: "UOM", width: '6%', align: 'left', readOnly: true },
                { title: "Currency", width: '5%', align: 'left', readOnly: true },
                { title: "Unit Rate (Rs.)", width: '5%', align: 'left', readOnly: true },
                { title: "Amount\n (Rs.)", width: '6%', align: 'left', readOnly: true },
                { title: "Sub Total\n (Rs.)", width: '6%', align: 'left', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        importStateReference_vm = new Vue({
            el: '#importsStateDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    
    $('#saveDetailsss').click(function () {
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let req_data = sourceDetailsReference_vm.getData();
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
        dataform.append('reqId', req_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updatePaymentAdvanceDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
                // setTimeout(() => {
                //     window.location.href = base_path + 'company/mqausers/managementbomqueue';
                // }, 1000);
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
            let vendorDet = vendorResponse[vId];
            $("#vendorName").html(vendorDet.vendorname);
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

    }

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});