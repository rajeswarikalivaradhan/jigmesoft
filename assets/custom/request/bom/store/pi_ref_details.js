$(document).ready(function () {

    var inHouseStatusReference_vm = '';
    var itemAcceptStatusReference_vm = '';
    let inHouseConsolidatedReference_vm = '';
    getQABomRequest();

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

        if(mode == "confirmation") {
            return {
                title: 'Are you sure want to \n move the details ?',
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
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getQABomRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getSupplyClosureList',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);
                append_in_house_details(bom_requirement_data);
                append_item_accept_status(bom_requirement_data);
                append_in_house_consolidated_qty(bom_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_in_house_details(data) {
        //console.log(data.inhousestatusdetails);
        let inv_qty = 0 ;
        let inv_rate = 0 ;
        let exch_rate = 0;
        let tot_amt = 0;
        let tot_amts = 0;
        $('#inHouseStatus').html('');
        let list = {
            data: data.inhousestatusdetails,
            columns: [
                //{ title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left' , readOnly: true},
                { type: 'text', title: 'Approved\n Item Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', readOnly: true },
                { type: 'text', title: 'UOM', width: '7%', align: 'center', readOnly: true },
                { title: 'P.I. Ref. No.', width: '15%', align: 'center', type: 'text', readOnly: true },
                { title: 'D.C. No.', width: '15%', align: 'center',  type: 'text', readOnly: true},
                { title: 'D.C. Date', width: '8%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' }, readOnly: true },
                { title: 'D.C. Qty.', width: '8%', align: 'right' , readOnly: true},
                { title: 'Invoice No.', width: '8%', align: 'center', readOnly: true },
                { title: 'Invoice Date', width: '8%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' }, readOnly: true },
                { title: 'Invoice Qty.', width: '8%', align: 'right', readOnly: true},
                { title: 'Invoice Rate \n Per Unit', width: '8%', align: 'right' , readOnly: true},
                { title: 'Currency', type:'dropdown', source:data.currency, width: '8%', align: 'left' , readOnly: true},
                { title: 'Foreign\n Exch. Rate', width: '8%', align: 'right', readOnly: true },
                { title: 'Invoice Value\n (Rs.)', width: '8%', align: 'right', readOnly: true },
                { title: 'Received Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', type: 'dropdown', source: data.uomData, readOnly: true },
                { title: 'Received Date', width: '8%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' } , readOnly: true},
                { title: 'Storage Bin /\n Rack Ref. No.', width: '10%', align: 'center', type: 'text' , readOnly: true},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,


            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 13) {
                    txtValue = numeral(val).format('0.00');
                    inv_qty = txtValue;
                    if(inv_qty != 0 && inv_rate != 0 && exch_rate != 0) {
                        tot_amt = parseFloat(inv_qty) * parseFloat(inv_rate) * parseFloat(exch_rate);
                        tot_amts = tot_amt.toFixed(3);
                    }
                }
                if(col == 14) {
                    txtValue = numeral(val).format('0.00');
                    inv_rate = txtValue;
                    if(inv_qty != 0 && inv_rate != 0 && exch_rate != 0) {
                        tot_amt = parseFloat(inv_qty) * parseFloat(inv_rate) * parseFloat(exch_rate);
                        tot_amts = tot_amt.toFixed(3);
                    }
                }
                if(col == 16) {
                    txtValue = numeral(val).format('0.00');
                    exch_rate = txtValue;
                    if(inv_qty != 0 && inv_rate != 0 && exch_rate != 0) {
                        tot_amt = parseFloat(inv_qty) * parseFloat(inv_rate) * parseFloat(exch_rate);
                        tot_amts = tot_amt.toFixed(3);
                    }
                }

                if (col == 17) 
                {
                    $(cell).text(tot_amts);
                    tot_amts = 0;
                }
                
            },
        };

        inHouseStatusReference_vm = new Vue({
            el: '#inHouseStatus',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    // FILTER STARTS //

    function sizeFilter(instance, cell, c, r, source) {
        var item_id = instance.jexcel.getValueFromCoords(c - 1, r);
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id) return true;
            })
        } else {
            return [];
        }
    }

    function itemFilter(instance, cell, c, r, source) {
        var size_id = instance.jexcel.getValueFromCoords(c - 1, r);
        var item_id = instance.jexcel.getValueFromCoords(c - 2, r);
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id && val.size_id == size_id) return true;
            })
        } else {
            return [];
        }
    }

    function colorFilter(instance, cell, c, r, source) {
        var item_code_id = instance.jexcel.getValueFromCoords(c - 1, r);
        var size_id = instance.jexcel.getValueFromCoords(c - 2, r);
        var item_id = instance.jexcel.getValueFromCoords(c - 3, r);
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id && val.size_id == size_id && val.item_code_id == item_code_id) return true;
            })
        } else {
            return [];
        }
    }

    function diaFilter(instance, cell, c, r, source) {
        var color_id = instance.jexcel.getValueFromCoords(c - 1, r);
        var item_code_id = instance.jexcel.getValueFromCoords(c - 2, r);
        var size_id = instance.jexcel.getValueFromCoords(c - 3, r);
        var item_id = instance.jexcel.getValueFromCoords(c - 4, r);
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id && val.size_id == size_id && val.item_code_id == item_code_id 
                    && val.color_id == color_id) 
                return true;
            })
        } else {
            return [];
        }
    }

    function uomFilter(instance, cell, c, r, source) {
        var dia_id = instance.jexcel.getValueFromCoords(c - 1, r);
        var color_id = instance.jexcel.getValueFromCoords(c - 2, r);
        var item_code_id = instance.jexcel.getValueFromCoords(c - 3, r);
        var size_id = instance.jexcel.getValueFromCoords(c - 4, r);
        var item_id = instance.jexcel.getValueFromCoords(c - 5, r);
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id && val.size_id == size_id && val.item_code_id == item_code_id 
                    && val.color_id == color_id && val.dia_id == dia_id) 
                return true;
            })
        } else {
            return [];
        }
    }

    function refNoFilter(instance, cell, c, r, source) {
        var uom_id = instance.jexcel.getValueFromCoords(c - 1, r);
        var dia_id = instance.jexcel.getValueFromCoords(c - 2, r);
        var color_id = instance.jexcel.getValueFromCoords(c - 3, r);
        var item_code_id = instance.jexcel.getValueFromCoords(c - 4, r);
        var size_id = instance.jexcel.getValueFromCoords(c - 5, r);
        var item_id = instance.jexcel.getValueFromCoords(c - 6, r);
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id && val.size_id == size_id && val.item_code_id == item_code_id 
                    && val.color_id == color_id && val.dia_id == dia_id && val.uom_id == uom_id)
                return true;
            })
        } else {
            return [];
        }
    }

    // FILTER ENDS //


    $('#submitPIDetails').click(function () {
            
            let inhouse_data = inHouseStatusReference_vm.getData();
            let item_data = itemAcceptStatusReference_vm.getData();
            let cons_data = inHouseConsolidatedReference_vm.getData();
            inhouse_data = inhouse_data.filter(function(e) { if(e[1] === true) return e })
            // console.log(req_data);
            // let validate_filed_1 = [7,8];
            // let validatedErrorCount_1 = validateForm(validate_filed_1, inhouse_data);
            // console.log(validatedErrorCount_1);
            
            // let validate_filed_2 = [6,7,8,9,10];
            // let validatedErrorCount_2 = validateForm(validate_filed_2, item_data);
            // console.log(validatedErrorCount_2);

            // let validate_filed_3 = [6,7,8,9,10];
            // let validatedErrorCount_3 = validateForm(validate_filed_2, cons_data);
            // console.log(validatedErrorCount_2);

            // $('.herr').hide();
            // if($('#req_type').val() == "" || $('#req_type').val() == null ) {
            //     $('#err_req_type').html("Select request type");
            //     $('#err_req_type').show();
            // } 
            // else if($('#merchant_note').val() == "" || $('#merchant_note').val() == null ) {
            //     $('#err_merchant_note').html("Fill merchant note");
            //     $('#err_merchant_note').show();
            // }
            //else if(validatedErrorCount_1 == 0 && validatedErrorCount_2 == 0 && validatedErrorCount_3 == 0) {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        let req_empArr = [];
                        for(let i=0; i < inhouse_data.length; i++) {
                            let data_a = inhouse_data[i];
                            let decEmpAr = [];
                            for(let j=0; j < data_a.length; j++) {
                                if(j == 0) {
                                    decEmpAr.push(data_a[j]);
                                }
                                if(j >= 7) {
                                    decEmpAr.push(data_a[j]);
                                }
                            }
                            req_empArr.push(decEmpAr);
                        }

                        let ref_empArr = [];
                        for(let i=0; i < item_data.length; i++) {
                            let data_a = item_data[i];
                            let decEmpAr = [];
                            for(let j=0; j < data_a.length; j++) {
                                if(j == 0) {
                                    decEmpAr.push(data_a[j]);
                                }
                                if(j >= 6) {
                                    decEmpAr.push(data_a[j]);
                                }
                            }
                            ref_empArr.push(decEmpAr);
                        }

                        let finalArray = [];
                        for(let i=0; i < req_empArr.length; i++)  {
                            const arr1 = req_empArr[i];
                            const arr2 = ref_empArr[i];
                            const conArr = arr1.concat(arr2);
                            finalArray.push(conArr);
                        }

                        updateFunction(finalArray);
                    } 
                    else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
                    }
                });
            // }
            // else {
            //     // *** VALIDATION ERROR MESSAGE *** //
            //     swalWithBootstrapButtons.fire(
            //         alertMessageFunction('validation_error')
            //     )
            // }
        
    });

    // let reqId = '';

    function updateFunction(finalData) {
        
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(finalData));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('req_type', $('#req_type').val());
        dataform.append('cutoff_date', $('#cutoff_date').val());
        dataform.append('merchant_note', $('#merchant_note').val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updatePurchaseIndentList',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                data = JSON.parse(data);
                // getCadRequest();
                // // *** SAVED MESSAGE *** //
                if(data.status == "success")
                {
                    reqId = data.requestId;
                    if(CADUpload.seletedFiles == 0)
                    {
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(okay => {
                            if(okay)
                            {
                                window.location.href = base_path + 'Mstoreuser/purchaseindentlist';
                            }
                        });
                    }
                    else {
                        CADUpload.startUpload();
                    }
                }
            },
            error: function () {
                console.log("Error");
            }
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
           { 'id': "2", 'name': 'REJECTED' }
        ];

        $('#itemAcceptStatus').html('');
        let list = {
            data: data.itemacceptstatus,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'D.C. No.', width: '15%', align: 'center', readOnly: true },
                { title: 'D.C. Date', width: '6%', align: 'center', readOnly: true, options: { format: 'DD/MM/YYYY' } },
                { title: 'D.C. Qty.', width: '6%', align: 'center', readOnly: true },
                { title: 'UOM', width: '6%', align: 'center', readOnly: true },
                { title: 'Invoice No.', width: '6%', align: 'center', readOnly: true },
                { title: 'Invoice Date', width: '6%', align: 'center', readOnly: true, options: { format: 'DD/MM/YYYY' } },
                { title: 'Merchant Item\n Approval Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData, readOnly: true },
                { title: 'Merchant Status \n Update Date & Time', width: '8%', align: 'center', readOnly: true },
                { title: 'Q.A. Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData, readOnly: true },
                { title: 'Q.A. Status Update\n Date & Time', width: '8%', align: 'center', readOnly: true },
                { title: 'Management\n Overriding Status', width: '8%', align: 'center', readOnly: true, type: 'dropdown', source: approvalStatusData, readOnly: true },
                { title: 'Management Status\n Update Date & Time', width: '8%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false
        };

        itemAcceptStatusReference_vm = new Vue({
            el: '#itemAcceptStatus',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_in_house_consolidated_qty(data) {

        let supplyClosureData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'DISCREPANCY' },
           { 'id': "2", 'name': 'SUPPLY CLOSED' }
        ];

        let itemRTIData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'DISCREPANCY' },
           { 'id': "2", 'name': 'READY TO ISSUE' }
        ];

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
                { title: 'Received Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'Difference Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Supply Closure\n Status', width: '8%', align: 'center', type: 'dropdown', source: supplyClosureData, readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        inHouseConsolidatedReference_vm = new Vue({
            el: '#inHouseConsolidatedQty',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});