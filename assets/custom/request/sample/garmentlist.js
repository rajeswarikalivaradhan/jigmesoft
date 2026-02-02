$(document).ready(function () {


   
    // **************************************** //
    var selectCount = 0;

     var sizeData = [];

    getSampleRequest();

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

    function getSampleRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', request_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/getjobdclist',
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

    function append_sample_request(data) {
        // console.log(data);
        //  sizeData = sizeData.splice(0, sizeData.length)
        // for(let item of data.sizeData) {
        //     sizeData.push(item);
        // }

        // *** JEXCEL STARTS *** //
        $('#materialIssueDetails').html('');
        console.log(data.jobstatusdata);

        let list = {
            data: data.jobstatusdata,
            columns: [
                { title:'id', align:'left',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '8%', align: 'left' },
                { type: 'text', title: 'P.O. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Color', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Item Issued', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Sample Ref. No.', width: '10%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Issued \n Size(s)', width: '6%', align: 'center', source: data.sizeData, readOnly: true },
                
                { type: 'text', title: 'Issued Qty. \n (Nos.)', width: '6%', align: 'right' },
                { type: 'hidden', title: "Hidden" },
                
            ],
            // columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: [[ '', '', '', '', '', '', '', '', '', '', 'Total:', '=SUMCOL(TABLE(), COLUMN())' ]],
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
    
    // $('#getValues').click(function () {
    //     alert("Update Function_save");
    //     let req_data = dclist_vm.getData();
    //     req_data = req_data.filter(function(e) { if(e[1] === true) return e });
    //     let validate_filed_1 = [10];
    //     let validatedErrorCount_1 = validateForm(validate_filed_1, req_data);

    //     if(selectCount <= 0) {
    //         swalWithBootstrapButtons.fire(
    //             alertMessageFunction('selecterror')
    //         );
    //     }
    //     else if ($('#received_by').val() == "" || validatedErrorCount_1 > 0)
    //     {
    //         swalWithBootstrapButtons.fire(
    //             alertMessageFunction('validation_error')
    //         );
    //     }
    //     else {
    //         swalWithBootstrapButtons.fire(
    //             // *** CONFIRMATION MESSAGE *** //
    //             alertMessageFunction('confirmation_save')
    //         ).then(function (result) {
    //             if (result.value) {
    //                    alert("Update Function1111");
    //                 updateFunction();
    //             } 
    //             else if (result.dismiss === Swal.DismissReason.cancel) {
    //                    alert("Update Function1111111");
    //                 // *** CANCELLED MESSAGE *** //
    //                 swalWithBootstrapButtons.fire(
    //                     alertMessageFunction('cancelled')
    //                 );
    //             }
    //         });
    //     }
    // });

     $('#getValues').click(function () {
        //$('.herr').hide();
         //let req_data = dclist_vm.getData();
        //req_data = req_data.filter(function(e) { if(e[1] === true) return e });
        if($('#received_by').val() == "" || $('#received_by').val() == null ) {
            $('#err_dep_remarks').html("Fill dept. remark");
            $('#err_dep_remarks').show();
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
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', request_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/updategarmentDCList',
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
                        window.location.href = base_path + 'company/msamplinguser/samplequeuelist';
                    }
                 });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

});