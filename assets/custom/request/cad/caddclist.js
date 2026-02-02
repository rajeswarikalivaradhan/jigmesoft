$(document).ready(function () {

    // **************************************** //
    var selectCount = 0;

    getCadMIDetails();
    
    let link = base_path + 'request/Cadrequest/dc_print/'+btoa(enquiry_id)+'/reqId/'+btoa(request_id)+'/miId/'+btoa(miId)+'/dc/'+btoa(dc);

                $("#print").attr("href", link);
    let link1 = base_path + 'request/Cadrequest/dc_print_pdf/'+btoa(enquiry_id)+'/reqId/'+btoa(request_id)+'/miId/'+btoa(miId)+'/dc/'+btoa(dc);

 $("#printpdf").attr("href", link1);

    $('#externalForm').hide();
    // $('#dc_type').html('INTERNAL');
    // $("#internal").attr('checked', true).trigger('click');

    // $('input[type=radio][name=dc_type]').change(function() {
    //     if(this.value== 'INTERNAL')
    //     {
    //         $('#dc_type').html('INTERNAL');
    //         $('#internalForm').show();
    //         $('#externalForm').hide();
    //     }
    //     else {
    //         $('#dc_type').html('EXTERNAL');
    //         $('#internalForm').hide();
    //         $('#externalForm').show();
    //     }
    // });

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

    function getCadMIDetails() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('dc', dc);
        data.append('miId', miId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/getCADDCList',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                append_sample_request(sample_requirement_data);
                let type = $('#type').val();
                if(type == 'INTERNAL')
                {
                    $('#internalForm').show();
                    $('#externalForm').hide();
                }
                else {
                    $('#internalForm').hide();
                    $('#externalForm').show();
                }
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

    function append_sample_request(data) {
        let requirementSource = [
            {id: '1', name: 'Bit Marker'}, 
            {id: '2', name: 'Pattern'}, 
            {id: '3', name: 'Pattern (Size Set)'}, 
            {id: '4', name: 'Lay Marker'}, 
            {id: '5', name: 'Others'}, 
        ];

        // *** JEXCEL STARTS *** //
        $('#materialIssueDetails').html('');
        let list = {
            data: data.cadMIData,
            columns: [
                { title:'id', align:'left',type:'hidden'},
                { type: 'text', title: 'P.O. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                // { type: 'text', title: 'Color', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec Code', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'CAD Ref. No.', width: '10%', align: 'center', readOnly: true, source: data.cadRefNo },
                { type: 'dropdown', title: 'Item Issued', width: '7%', align: 'center', source: requirementSource , readOnly: true },
                { type: 'dropdown', title: 'Issued \n Size(s)', width: '6%', align: 'center', source: data.sizeData, readOnly: true },
                { type: 'text', title: 'Total No. of\n Parts Issued', width: '6%', align: 'right', readOnly: true },
            ],
            // columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            // footers: [[ '', '', '', '', '', '', '', '', '', 'Total:', '=SUMCOL(TABLE(), COLUMN())' ]],
            // footers: footer(data.column.length),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 1) 
                {
                    getReferenceValue(list.data[row], val, row);
                }
            }
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
        if(status == true) {
            selectCount = selectCount+1;
        }
        else {
            selectCount = selectCount-1;
        }
    }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
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

    function updateFunction_old() {

        let dataform = new FormData();
        let dc_data = dclist_vm.getData();
        dataform.append('data', JSON.stringify(dc_data));
        dataform.append('received_by', $('#received_by').val());
        dataform.append('mi_type', $('input[type=radio][name=dc_type]').val());
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', request_id);
        dataform.append('miId', miId);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/updateDCList',
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
                        window.location.href = base_path + 'company/mcaduser/cadindentlist';
                    }
                 });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function updateFunction() {

        let dataform = new FormData();
        let dc_data = dclist_vm.getData();
        dataform.append('item_received_status', $('#item_received_status').val());
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', request_id);
        dataform.append('miId', miId);
        dataform.append('dc', dc);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/cadrequest/updateMIDCList',
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
                        window.location.href = base_path + 'company/msamplinguser/micaddclist';
                    }
                 });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

});