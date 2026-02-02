$(document).ready(function () {

    // **************************************** //
    var selectCount = 0;

    getSurplusDraftDetails();

    $('#externalForm').hide();
    $('#dc_type').html('INTERNAL');
    $("#internal").attr('checked', true).trigger('click');

    $('input[type=radio][name=dc_type]').change(function() {
        if(this.value== 'INTERNAL')
        {
            $('#dc_type').html('INTERNAL');
            $('#internalForm').show();
            $('#externalForm').hide();
        }
        else {
            $('#dc_type').html('EXTERNAL');
            $('#internalForm').hide();
            $('#externalForm').show();
        }
    });

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

        if(mode == "error") {
            return {
                title: 'Error',
                text: "Something went wrong",
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        if(mode === 'uncheck_val') {
            return {
                title: 'Are you sure want to \n save the details ?',
                text: "Some item's are unchecked!",
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
    // SAMPLE REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getSurplusDraftDetails() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', request_id);
        data.append('itemCode', itemCode);
        data.append('pId', pId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getSurplusDCList',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                append_sample_request(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 2].innerHTML)) {
                total += Number(instance.records[j][columnId - 2].innerHTML);
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
        //total = (total > 0) ? total : ''
        return total;
    }
    
    function footer(grid_name)
    {
        if(grid_name == 'issued_footer')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")']];
        }
    }

    function append_sample_request(data) {

        // *** JEXCEL STARTS *** //
        $('#materialIssueDetails').html('');
        let list = {
            data: data.draftData,
            columns: [
                { title:'id', align:'left',type:'hidden'},
                { title:'mode', align:'left',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '8%', align: 'left' },
                { type: 'text', title: 'Original\nP.I. Ref. No.', width: '8%', align: 'center', readOnly: true },
                { type: 'text', title: 'Original\nInvoice No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Item\nDescription', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Blend (%) / Content /\n Material', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Item Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Item Colour Code', width: '10%', align: 'center', readOnly: true },
                { type: 'text', title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'right', readOnly: true },
                { type: 'text', title: 'UOM', width: '6%', align: 'center',  readOnly: true },
                { type: 'text', title: 'Item - Lot / Batch\n Ref. No.', width: '6%', align: 'center',  readOnly: true },
                { type: 'text', title: 'Qty.', width: '6%', align: 'right', readOnly: true },
                { type: 'text', title: 'UOM', width: '6%', align: 'center', readOnly: true },
                { type: 'text', title: 'Unit Rate\n(Rs.)', width: '6%', align: 'right',  readOnly: true },
                { type: 'text', title: 'Amount\n(Rs.)', width: '6%', align: 'right',  readOnly: true },
                { type: 'text', title: 'GST / \n IGST (%)', width: '6%', align: 'right', readOnly: true },
                { type: 'text', title: 'Sub Total\n(Rs.)', width: '6%', align: 'right', readOnly: true },
            ],
            // columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('issued_footer'),
            // footers: [[ '', '', '', '', '', '', '', '', '', 'Total:', '=SUMCOL(TABLE(), COLUMN())' ]],
            // footers: footer(data.column.length),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 1) 
                {
                    getReferenceValue(list.data[row], val, row);
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 13) 
                {
                    qty = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 15) 
                {
                    rate = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 16) 
                {
                    tot_amt = parseFloat(qty) * parseFloat(rate);
                    txtValue = numeral(tot_amt).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    
                }
                if(col == 17) 
                {
                    gst = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 18) 
                {
                    sub_tot = parseFloat(tot_amt) + (parseFloat(tot_amt) * parseFloat(gst) / 100);
                    txtValue = numeral(sub_tot).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            },
        };

        dclist_vm = new Vue({
            el: '#materialIssueDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });

    }

    function getReferenceValue(data, status, row) {
        if(status === true) {
            selectCount = selectCount+1;
        }
        else {
            selectCount = selectCount-1;
        }
    }
    
    function validateCountForm(dclist) {
        let errorCount = 0;
        for (let i = 0; i < dclist.length; i++) {
            if(dclist[i][1]  === true) {
              
            } else {
                errorCount++;
            }
        }
         
        return errorCount;
    }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    $('#clearDraft').click(function () {
        // clearDraftFunction();
        swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    clearDraftFunction();
                } 
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
    });
    
    function clearDraftFunction()
    {
        let dataform = new FormData();
        let dc_data = dclist_vm.getData();
        dataform.append('data', JSON.stringify(dc_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', request_id);
        dataform.append('itemCode', itemCode);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/clearSurplusDraftFunction',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if (okay) {
                        window.location.href = base_path + 'company/Mstoreuser/surplusstocklist';
                    }
                 });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    
    $('#getValues').click(function () {
        let dclist = dclist_vm.getData();
        let validateReqCount = validateCountForm(dclist);
        
        if ($('#received_by').val() === "")
        {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
        }
        else if ($('#amount_in_words').val() === "")
        {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
        }
        else if ($('#payment_terms').val() === "")
        {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
        }
        else if(validateReqCount > 0) {
            swalWithBootstrapButtons.fire(
                // alertMessageFunction('uncheck_val')
                alertMessageFunction('uncheck_val')
            ).then(function (result) {
                if (result.value) {
                    updateFunction();
                }
            });
            
        }
        else {
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
        }
    });

    function updateFunction() {

        let dataform = new FormData();
        let dc_data = dclist_vm.getData();
        dataform.append('data', JSON.stringify(dc_data));
        dataform.append('received_by', $('#received_by').val());
        dataform.append('amount_in_words', $('#amount_in_words').val());
        dataform.append('payment_terms', $('#payment_terms').val());
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', request_id);
        dataform.append('pId', pId);
        dataform.append('itemCode', itemCode);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateSurplusDCList',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if (okay) {
                        window.location.href = base_path + 'company/Mstoreuser/surplusstocklist';
                    }
                 });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

});