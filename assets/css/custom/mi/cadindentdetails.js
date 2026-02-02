$(document).ready(function () {

    // **************************************** //

    getCadMIDetails();

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
        data.append('reqId', reqId);
        data.append('miId', miId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/getCadMIDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                cad_mi_data = JSON.parse(data);
                append_cad_material_indent(cad_mi_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************

    // ******** SAVE REQUEST ENDS HERE ***************** //
    
    $('#getValues').click(function () {
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                // common form data
                var dataValue = new FormData();
                
                // get cad material indent table data
                let cad_mi_tbl_data = cad_material_vm.getData();
                
                dataValue.append('cad_mi_tbl_data', JSON.stringify(cad_mi_tbl_data));
                dataValue.append('reqId', reqId);
                dataValue.append('enquiry_id', enquiry_id);

                updateFunction(dataValue);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });

    function updateFunction(dataValue) {
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/updateCadIndentDetails',
            data: dataValue,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let val = JSON.parse(data);
                if(val.status == 'success')
                {
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('saved')
                    ).then(function (result) {
                        if (result.value) {
                            window.location.href = base_path + 'company/mcaduser/cadindentlist';
                        }
                    });
                }
                else {
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('error')
                    );
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // ******** SAVE REQUEST ENDS HERE ***************** //

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    //  CAD MATERIAL INDENT STARTS HERE 
    // ***********************************************************************************************************************************
    
    function append_cad_material_indent(data) {
        let requirementSource = [
            {id: '1', name: 'Bit Marker'}, 
            {id: '2', name: 'Pattern'}, 
            {id: '3', name: 'Pattern (Size Set)'}, 
            {id: '4', name: 'Lay Marker'}, 
            {id: '5', name: 'Others'}, 
        ];
        
        let purposeSource = [
            {id: '1', name: 'Costing'}, 
            {id: '2', name: 'FCC - Sample'}, 
            {id: '3', name: 'FCC - Bulk'}, 
            {id: '4', name: 'Cutting - Sample'}, 
            {id: '5', name: 'Cutting - Bulk'}, 
            {id: '6', name: 'Bit Cutting - Sample'}, 
            {id: '7', name: 'Bit Cutting - Bulk'}, 
            {id: '8', name: 'Others'}, 
        ];
        
        $('#cadMaterialIndent').html('');
        let list = { 
            data: data.cadMIData,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec Code', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'CAD Ref. No.', width: '12%', align: 'center', readOnly: true, source: data.cadRefNo },
                { type: 'dropdown', title: 'Requirement', width: '7%', align: 'left', source: requirementSource, readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: purposeSource, readOnly: true },
                { type: 'dropdown', title: 'Required \nSize(s)', width: '7%', align: 'left', source: data.sizeData, readOnly: true },
                { type: 'dropdown', title: 'Item \nIssued', width: '7%', align: 'left', source: requirementSource  },
                { type: 'dropdown', title: 'Issued \nSize(s)', width: '7%', align: 'left', source: data.sizeData  },
                { type: 'text', title: 'Total No. of Parts \nIssued', width: '8%', align: 'right' },
                { type: 'text', title: 'D.C. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'calendar', title: 'D.C. \nDate & Time', width: '8%', align: 'left', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
            ], 
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        cad_material_vm = new Vue({
            el: '#cadMaterialIndent',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    // *********************************************************************************************************************************** 
    //  CAD MATERIAL INDENT ENDS HERE 
    // ***********************************************************************************************************************************

});