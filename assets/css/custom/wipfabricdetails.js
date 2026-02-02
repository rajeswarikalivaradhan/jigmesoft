$(document).ready(function () {

    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    var jexcelPlaceHolders = {
        "freetext": "Free Text",
        "dropdown": "Select",
        "datePicker": "Select"
    };

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
    }

    function validateForm(validateField, dataValue, statusCheck, optionalFields, pendingField ) {
        let errorCount = 0;

        if(statusCheck == "yes") {
            for (let i = 0; i < dataValue.length; i++) {
                for(let j = 0; j < validateField.length; j++) {
                    let col = validateField[j];
                    if(dataValue[i][pendingField] == "PENDING") {
                        if(dataValue[i][col] == "") {
                            errorCount++;
                        }
                    }
                    else {
                        for(let k = 0; k < optionalFields.length; k++) {
                            let colVal = optionalFields[k];
                            if(dataValue[i][colVal] == "") {
                                errorCount++;
                            }
                        }
                    }
                }
            } 
        }
        else {
            for (let i = 0; i < dataValue.length; i++) {
                for(let j = 0; j < validateField.length; j++) {
                    let col = validateField[j]
                    if(dataValue[i][col] == "") {
                        errorCount++;
                    }
                }
            }
        }

        return errorCount;
    }

    // var activeNav = $("ul.nav-pills > li > a").attr("href").replace("#", "");
    // if (activeNav == 'fabric') {
    //     _call_to_fabric();
    // }

    var activeNav = $("ul.nav-pills > li > a").attr("href").replace("#", "");
    var fabric = 0, yarn = 0, knitting = 0, dyeing = 0, compacting = 0, lab = 0;
        
    if (activeNav == 'fabric') {
        _call_to_fabric();
    }

    $("ul.nav-pills > li > a").click(function () {
        var currentNav = $(this).attr("href").replace("#", "");
        if (currentNav == 'yarn' && yarn == 0) {
            yarn++;
            _call_to_yarn();
        } else if (currentNav == 'fabric' && fabric == 0) {
            fabric++;
            _call_to_fabric();
        } else if (currentNav == 'knitting' && knitting == 0) {
            knitting++;
            _call_to_knitting();
        } else if (currentNav == 'dyeing' && dyeing == 0) {
            dyeing++;
            _call_to_dyeing();
        } else if (currentNav == 'compacting' && compacting == 0) {
            compacting++;
            _call_to_compacting();
        } else if (currentNav == 'lab' && lab == 0) {
            lab++;
            _call_to_lab();
        }
    });

    function _call_to_fabric() {
        get_color_wise_garment_parts_details(); // call color wise garment parts details
        get_garment_parts_wise_qty_details(); // call garment parts wise qty details
        get_size_wise_garment_parts_details(); // call SIZE & GARMENT PARTS WISE PIECE WEIGHT PER UNIT
        get_fabric_consumption_calc_details(); // call 4
        get_fabric_process_loss_details(); // call 5
        get_fabric_size_spec_code_details();    // call 6
        get_sizewise_dia_dimension(); // call 7 
        get_itemized_fabric_requirement_details(); // call 8
    }

    function _call_to_yarn() {
        get_yarn_dyeing_colour_wise_qty_details(); // call yarn dyeing colour wise qty details
        get_single_double_dye_bath_details(); // call single double dye bath details
        get_yarn_programme_details(); // call yarn programme details
        get_yarn_requirement_details(); // call yarn requirement details
    }

    function _call_to_knitting() {
        get_knitting_programme_details(); // call knitting programme details
        get_knitting_programme_itemized_yarn_requirement_details(); // call knitting programme itemized yarn requirement details
    }

    function _call_to_dyeing() {
        getFabricDyeingProgramme_qty(); // call to fabric dyeing programme qty details 
        getFabricDyeingProgramme_finish(); // call to fabric dyeing programme finishing details 
        getYarnDyeingProgramme_qty(); // call to yarn dyeing programme qty details 
        getYarnDyeingProgramme_finish(); // call to yarn dyeing programme finishing details
    }

    function _call_to_compacting() {
        getFabricWashingCompatingDetails(); // call to compacting details
    }

    function _call_to_lab() {
        get_lab_testing_acceptance_internal_details(); // call lab testing acceptance internal details
        get_lab_testing_acceptance_external_details(); // call lab testing acceptance external details
        get_external_lab_testing_authority_details(); // call external lab testing authority details
    }

    // ******  Table Calculation  ****** //

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
        total = numeral(total).format('0.000');
        total = (total > 0) ? total : ''
        return total;
    }

    function footer(gridname, columnlength)
    {
        let length = '', empar = [];
        if(gridname == 'item_fabric') { length = 3; }
        else if(gridname == 'yarn_programme') { length = 7; }
        else if(gridname == 'knitting_programme' || gridname == 'knitting_programme_itemized' || gridname == 'fabric_dyeing_programme' || gridname == 'yarn_dyeing_programme') { length = 5; }
        else if(gridname == 'fab_grand_total') { length = 2; }
        else if(gridname == 'yarn_requriment') { length = 4; }
        else { length = 4; }
        let position = columnlength - length;
        for(var i= 1; i <= position; i++) { empar.push(''); }
        if(gridname == 'yarn_programme') {
            empar.push('Total:', '=GPWSUMCOL(TABLE(), COLUMN())', '', '=GPWSUMCOL(TABLE(), COLUMN())', '=GPWSUMCOL(TABLE(), COLUMN())');
        }
        else if(gridname == 'knitting_programme' || gridname == 'fabric_dyeing_programme') {
            empar.push('Total:', '=GPWSUMCOL(TABLE(), COLUMN())', '', '0.000', '');
        }
        else if(gridname == 'knitting_programme_itemized') {
            empar.push('=GPWSUMCOL(TABLE(), COLUMN())', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN())',  '0.000', '');
        }
        else if(gridname == 'yarn_dyeing_programme') {
            empar.push('Total:', '=GPWSUMCOL(TABLE(), COLUMN())', '=GPWSUMCOL(TABLE(), COLUMN())',  '0.000', '');
        }
        else if(gridname == 'fab_grand_total') {
            empar.push('Gross Total:', '=GPWSUMCOL(TABLE(), COLUMN())');
        }
        else if(gridname == 'yarn_requriment') {
            empar.push('Total:', '=GPWSUMCOL(TABLE(), COLUMN())', '0.000', '');
        }
        else {
            empar.push('Total:', '=GPWSUMCOL(TABLE(), COLUMN())');
        }
        return [empar];
    }

    // ******************************************************************************** 
    // FABRIC STARTS HERE 
    // ********************************************************************************

     // ********** COLOR WISE GARMENT PART DETAILS STARTS HERE  *********** //

     function get_color_wise_garment_parts_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getColourWiseGarmentPartsDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_color_wise_garment_parts_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_color_wise_garment_parts_details(data) {
        $('#color_wise_garment_parts').html('');
        let color_wise_garment_parts_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var color_wise_garment_parts_vm = new Vue({
            el: '#color_wise_garment_parts',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, color_wise_garment_parts_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    color_wise_garment_parts_details_vm(data);
                },
            }
        });
    
        $('#color_wise_garment_parts_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    color_wise_garment_parts_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    function color_wise_garment_parts_details_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateColourWiseGarmentPartsDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_fabric();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // ********** COLOR WISE GARMENT PART DETAILS ENDS HERE  *********** //

     // ********** GERMENT PARTS WISE QTY DETAILS STARTS HERE  *********** //

     function get_garment_parts_wise_qty_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getGarmentPartsWiseQtyDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_garment_parts_wise_qty_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_garment_parts_wise_qty_details(data) {
        $('#garment_parts_wise_qty').html('');
        let garment_parts_wise_qty_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var garment_parts_wise_qty_vm = new Vue({
            el: '#garment_parts_wise_qty',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, garment_parts_wise_qty_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    garment_parts_wise_qty_details_vm(data);
                },
            }
        });
    }

    // ********** GERMENT PARTS WISE QTY DETAILS ENDS HERE  *********** //

     // ********** GERMENT PARTS WISE QTY DETAILS STARTS HERE  *********** //

     function get_garment_parts_wise_qty_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getGarmentPartsWiseQtyDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_garment_parts_wise_qty_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_garment_parts_wise_qty_details(data) {
        $('#garment_parts_wise_qty').html('');
        let garment_parts_wise_qty_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var garment_parts_wise_qty_vm = new Vue({
            el: '#garment_parts_wise_qty',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, garment_parts_wise_qty_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    garment_parts_wise_qty_details_vm(data);
                },
            }
        });
    
        $('#garment_parts_wise_qty_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    garment_parts_wise_qty_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    function garment_parts_wise_qty_details_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateGarmentPartsWiseQtyDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_fabric();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // ********** GERMENT PARTS WISE QTY DETAILS ENDS HERE  *********** //
     
    // // ********** SIZE & GARMENT PARTS WISE PIECE WEIGHT PER UNIT STARTS HERE  *********** //

    //  function get_fab_size_garment_part_wise() {
    //     var data = new FormData();
    //     data.append('enquiry_id', enquiry_id);
    //     let request = $.ajax({
    //         type: "POST",
    //         url: base_path + 'WorkInProcess/get_fab_size_garment_part_wise',
    //         data: data,
    //         processData: false,
    //         contentType: false,
    //         cache: false,
    //         success: function (data) {
    //             let resData = JSON.parse(data);
    //             // append_garment_parts_wise_qty_details(resData);
    //         },
    //         error: function () {
    //             console.log("Error");
    //         }
    //     });
    // }

    // // ********** SIZE & GARMENT PARTS WISE PIECE WEIGHT PER UNIT ENDS HERE  *********** //

    // ********** SIZE WISE GARMENT PARTS DETAILS STARTS HERE  *********** //

    function get_size_wise_garment_parts_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getSizeWiseGarmentPartsDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_size_wise_garment_parts_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_size_wise_garment_parts_details(data) {
        $('#size_wise_garment_parts').html('');
        let num_of_item = 0;
        let size_wise_garment_parts_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 0) {
                    num_of_item = 0;
                }
                if (col >= 8 && col < data.column.length - 2) 
                {
                    let a = instance.jexcel.options.data[row][col];
                    if(a == '') { 
                        a = 0;
                    }
                    num_of_item += parseFloat(a);
                }
                if(col == data.column.length - 2) 
                {   
                    if(num_of_item != 0) {
                        num_of_item = parseFloat(num_of_item) / parseFloat(data.garmentSizes);
                        num_of_item = num_of_item.toFixed(3);
                    }
                    $(cell).text(num_of_item);
                    instance.jexcel.options.data[row][col] = num_of_item;
                }
            },
        };
    
        var size_wise_garment_parts_vm = new Vue({
            el: '#size_wise_garment_parts',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, size_wise_garment_parts_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    size_wise_garment_parts_details_vm(data);
                },
            }
        });
    
        $('#size_wise_germent_parts_btn').click(function () {

            let validate_field = [];
            let validate_data = size_wise_garment_parts_vm.getData();
            for(let m=0; m < validate_data.length; m++) {
                let f_row = validate_data[m];
                for(let n=0; n < f_row.length; n++) {
                    if(n>=6 && n < f_row.length -2) {
                        validate_field.push(n);
                    }
                }
                break;
            }

            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
                }).then(function (result) {
                    if (result.value) {
                        size_wise_garment_parts_vm.submitData();
                        fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                        swalWithBootstrapButtons.fire({
                            title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                        });
                    }
                });
            }
            else {
                // *** VALIDATION ERROR MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
            }    
        });
    }

    function size_wise_garment_parts_details_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateSizeWiseGarmentPartsDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_fabric();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // ********** SIZE WISE GERMENT PARTS DETAILS ENDS HERE  *********** //

    // ********** FABRIC CONSUMPTION CALCULATION DETAILS STARTS HERE  *********** //

    function get_fabric_consumption_calc_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getFabricConsumptionCalcDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_fabric_consumption_calc_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_fabric_consumption_calc_details(data) {
        $('#fabric_consumption_calc').html('');
        let fabric_consumption_calc_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('fab_grand_total', data.column.length),
        };
    
        var fabric_consumption_calc_vm = new Vue({
            el: '#fabric_consumption_calc',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, fabric_consumption_calc_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    fabric_consumption_calc_details_vm(data);
                },
            }
        });
    
        $('#fabric_consumption_calc_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    fabric_consumption_calc_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    function fabric_consumption_calc_details_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateFabricConsumptionCalcDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_fabric();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // ********** FABRIC CONSUMPTION CALCULATION DETAILS ENDS HERE  *********** //

     // ********** FABRIC PROCESS LOSS DETAILS STARTS HERE  *********** //

     function get_fabric_process_loss_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getFabricProcessLossDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_fabric_process_loss_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_fabric_process_loss_details(data) {
        $('#fabric_process_loss').html('');
        let fabric_process_loss_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('fab_grand_total', data.column.length),
        };
    
        var fabric_process_loss_vm = new Vue({
            el: '#fabric_process_loss',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, fabric_process_loss_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    fabric_consumption_calc_details_vm(data);
                },
            }
        });
    
        $('#fabric_process_loss_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    fabric_process_loss_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    // ********** FABRIC PROCESS LOSS DETAILS ENDS HERE  *********** //

    // ********** FABRIC SIZE SPEC CODE DETAILS STARTS HERE  *********** //

    function get_fabric_size_spec_code_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getFabricSizeSpecCodeDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_fabric_size_spec_code_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_fabric_size_spec_code_details(data) {
        $('#fabric_size_spec_code').html('');
        let fabric_size_spec_code_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var fabric_size_spec_code_vm = new Vue({
            el: '#fabric_size_spec_code',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, fabric_size_spec_code_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    fabric_consumption_calc_details_vm(data);
                },
            }
        });
    
        $('#fabric_size_spec_code_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    fabric_size_spec_code_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    // ********** FABRIC SIZE SPEC CODE DETAILS ENDS HERE  *********** //

    // ********** SIZE WISE REQUIRED FINISHING DIA / DIMENSION STARTS HERE  *********** //

    function get_sizewise_dia_dimension() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/get_sizewise_dia_dimension',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_sizewise_dia_dimension(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_sizewise_dia_dimension(data) {
        $('#fabric_sizewise_dia_dimension').html('');
        let num_of_item = 0;
        let fabric_sizewise_dia_dimension_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 0) {
                    num_of_item = 0;
                }
                if (col >= 8 && col < data.column.length - 2) 
                {
                    let a = instance.jexcel.options.data[row][col];
                    if(a == '') { 
                        a = 0;
                    }
                    num_of_item += parseInt(a);
                }
                // if(col == data.column.length - 2) 
                // {   
                //     if(num_of_item != 0) {
                //         num_of_item = parseInt(num_of_item) / parseInt(data.garmentSizes);
                //     }
                //     $(cell).text(num_of_item);
                //     instance.jexcel.options.data[row][col] = num_of_item;
                // }
            },
        };
    
        var fabric_sizewise_dia_dimension_vm = new Vue({
            el: '#fabric_sizewise_dia_dimension',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, fabric_sizewise_dia_dimension_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    fabric_sizewise_dia_dimension_update(data);
                },
            }
        });
    
        $('#fabric_sizewise_dia_dimension_sve_btn').click(function () {

            let validate_field = [];
            let validate_data = fabric_sizewise_dia_dimension_vm.getData();
            for(let m=0; m < validate_data.length; m++) {
                let f_row = validate_data[m];
                for(let n=0; n < f_row.length; n++) {
                    if(n>=6 && n < f_row.length -2) {
                        validate_field.push(n);
                    }
                }
                break;
            }

            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
                }).then(function (result) {
                    if (result.value) {
                        fabric_sizewise_dia_dimension_vm.submitData();
                        fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                        swalWithBootstrapButtons.fire({
                            title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                        });
                    }
                });
            }
            else {
                // *** VALIDATION ERROR MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
            }    
        });
    }

    function fabric_sizewise_dia_dimension_update(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/update_sizewise_dia_dimension',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_fabric();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // ********** SIZE WISE REQUIRED FINISHING DIA / DIMENSION ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // FABRIC ENDS HERE 
    // ***********************************************************************************************************************************

    
     // ********** ITEMIZED FABRIC REQUIREMENT DETAILS STARTS HERE  *********** //

     function get_itemized_fabric_requirement_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getItemizedFabricRequirementDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_itemized_fabric_requirement_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_itemized_fabric_requirement_details(data) {
        $('#itemized_fabric_requirement').html('');
        let itemized_fabric_requirement_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('item_fabric', data.column.length),
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if(col == 10) {
                    var da = $(cell).text();
                    txtValue = numeral(da).format('0.000');
                    txtValue = (txtValue > 0) ? txtValue : '0';
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    bom_intake = txtValue;
                }
            }
        };
    
        var itemized_fabric_requirement_vm = new Vue({
            el: '#itemized_fabric_requirement',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, itemized_fabric_requirement_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    itemized_fabric_requirement_details_vm(data);
                },
            }
        });
    
        $('#itemized_fabric_requirement_btn').click(function () {

            let validateField = [7,8];
            let dataValue = itemized_fabric_requirement_vm.getData();
            
            let errorCount = 0;
            for (let i = 0; i < dataValue.length; i++) {
                let datarow = dataValue[i];

                if(datarow[2] != "" && datarow[3] != "" && datarow[4] != "" && datarow[5] != "") {
                    if(datarow[6] == "" || datarow[7] == "" || datarow[8] == "" || datarow[9] == "" || datarow[10] == "" || datarow[11] == "" || datarow[12] == "" || datarow[13] == "")  {
                        // *** VALIDATION ERROR MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('validation_error')
                        )
                    }
                    else {
                        let validateLength = 0;
                        if(datarow[6] != "") {
                            let a = datarow[6];
                            let splitValue = a.split(";");
                            splitValue = splitValue.filter(e =>  e);
                            validateLength = splitValue.length;
                        }

                        for(let j = 0; j < validateField.length; j++) {
                            let col = validateField[j];

                            let a = datarow[col];
                            let splitValue = a.split(";");
                            splitValue = splitValue.filter(e =>  e);
                            if(splitValue.length != validateLength) {
                                errorCount++;
                                return swalWithBootstrapButtons.fire({
                                    title: 'Warning',
                                    text: "Please choose Even number of selection in yarn blend / yarn content / yarn count",
                                    icon: 'warning',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        'confirmButton': 'btn btn-secondary px-5'
                                    }
                                })
                                
                            }
                        }
                    }
                }
            }
            
            if(errorCount == 0)
            {
                swalWithBootstrapButtons.fire({
                    title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
                }).then(function (result) {
                    if (result.value) {
                        itemized_fabric_requirement_vm.submitData();
                        fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                        swalWithBootstrapButtons.fire({
                            title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                        });
                    }
                });
            }
            else {
                // *** VALIDATION ERROR MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
            }
        });

        function itemized_fabric_requirement_details_vm(data) {

            let dataform = new FormData();
            dataform.append('data', JSON.stringify(data));
            dataform.append('enquiry_id', enquiry_id);
            let request = $.ajax({
                type: "POST",
                url: base_path + 'WorkInProcess/updateItemizedFabricRequirementDetails',
                data: dataform,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    _call_to_fabric();
                },
                error: function () {
                    console.log("Error");
                }
            });
        }
    }

    // ********** ITEMIZED FABRIC REQUIREMENT DETAILS ENDS HERE  *********** //

    // ********** ITEMIZED FABRIC REQUIREMENT DETAILS ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // FABRIC ENDS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    // YARN STARTS HERE 
    // ***********************************************************************************************************************************

    // ********** YARN DYEING COLOUR WISE QTY DETAILS STARTS HERE  *********** //

    function get_yarn_dyeing_colour_wise_qty_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getYarnDyeingColourWiseQtyDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_yarn_dyeing_colour_wise_qty_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_yarn_dyeing_colour_wise_qty_details(data) {
        $('#yarn_dyeing_colour_wise_qty').html('');
        let yarn_dyeing_colour_wise_qty_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('yarn_dyeing', data.column.length),
        };
    
        var yarn_dyeing_colour_wise_qty_vm = new Vue({
            el: '#yarn_dyeing_colour_wise_qty',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, yarn_dyeing_colour_wise_qty_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    yarn_dyeing_colour_wise_qty_details_vm(data);
                },
            }
        });
    
        $('#yarn_dyeing_colour_wise_qty_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    yarn_dyeing_colour_wise_qty_vm.submitData();
                    fabric = 0, yarn = 0, knitting = 0, dyeing = 0, compacting = 0, lab = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    // ********** YARN DYEING COLOUR WISE QTY DETAILS ENDS HERE  *********** //

    // ********** YARN SINGLE DOUBLE DYE BATH DETAILS STARTS HERE  *********** //

    function get_single_double_dye_bath_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getSingleDoubleDyeBathDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_single_double_dye_bath_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_single_double_dye_bath_details(data) {
        $('#single_double_dye_bath').html('');
        let single_double_dye_bath_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('', data.column.length),
        };

        var single_double_dye_bath_vm = new Vue({
            el: '#single_double_dye_bath',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, single_double_dye_bath_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    single_double_dye_bath_details_vm(data);
                },
            }
        });

        $('#single_double_dye_bath_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    single_double_dye_bath_vm.submitData();
                    fabric = 0, yarn = 0, knitting = 0, dyeing = 0, compacting = 0, lab = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    // ********** YARN SINGLE DOUBLE DYE BATH DETAILS ENDS HERE  *********** //

    // ********** YARN PROGRAMME DETAILS STARTS HERE  *********** //

    function get_yarn_programme_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getYarnProgrammeDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_yarn_programme_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_yarn_programme_details(data) {
        $('#yarn_programme').html('');
        let yarn_programme_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('yarn_programme', data.column.length),
        };
    
        var yarn_programme_vm = new Vue({
            el: '#yarn_programme',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, yarn_programme_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    yarn_programme_details_vm(data);
                },
            }
        });
    
        $('#yarn_programme_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    yarn_programme_vm.submitData();
                    fabric = 0, yarn = 0, knitting = 0, dyeing = 0, compacting = 0, lab = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });

        function yarn_programme_details_vm(data) {
            let dataform = new FormData();
            dataform.append('data', JSON.stringify(data));
            dataform.append('enquiry_id', enquiry_id);
            let request = $.ajax({
                type: "POST",
                url: base_path + 'WorkInProcess/updateYarnProgrammeDetails',
                data: dataform,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    // _call_to_yarn();
                },
                error: function () {
                    console.log("Error");
                }
            });
        }
    }

    // ********** YARN PROGRAMME  DETAILS ENDS HERE  *********** //

    // ********** YARN REQUIREMENT DETAILS STARTS HERE  *********** //

    function get_yarn_requirement_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getYarnRequirementDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_yarn_requirement_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_yarn_requirement_details(data) {
        $('#yarn_requirement').html('');
        let yarn_requirement_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('yarn_requriment', data.column.length)
        };
    
        var yarn_requirement_vm = new Vue({
            el: '#yarn_requirement',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, yarn_requirement_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    yarn_requirement_details_vm(data);
                },
            }
        });
    
        $('#yarn_requirement_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    yarn_requirement_vm.submitData();
                    fabric = 0, yarn = 0, knitting = 0, dyeing = 0, compacting = 0, lab = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });

        function yarn_requirement_details_vm(data) {
            let dataform = new FormData();
            dataform.append('data', JSON.stringify(data));
            dataform.append('enquiry_id', enquiry_id);
            let request = $.ajax({
                type: "POST",
                url: base_path + 'WorkInProcess/updateYarnRequirementDetails',
                data: dataform,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    _call_to_yarn();
                },
                error: function () {
                    console.log("Error");
                }
            });
        }
    }

    // ********** YARN REQUIREMENT DETAILS ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // YARN ENDS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    // KNITTING STARTS HERE 
    // ***********************************************************************************************************************************
    
    // ********** KNITTING PROGRAMME DETAILS STARTS HERE  *********** //

    function get_knitting_programme_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getKnittingProgrammeDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_knitting_programme_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_knitting_programme_details(data) {
        $('#knitting_programme').html('');
        let knitting_programme_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
            tableWidth: '120%',
            maxHeight: '200px',
            footers: footer('knitting_programme', data.column.length)
        };
    
        var knitting_programme_vm = new Vue({
            el: '#knitting_programme',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, knitting_programme_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    knitting_programme_details_vm(data);
                },
            }
        });
    
        $('#knitting_programme_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    knitting_programme_vm.submitData();
                    fabric = 0, yarn = 0, knitting = 0, dyeing = 0, compacting = 0, lab = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });

        function knitting_programme_details_vm(data) {
            let dataform = new FormData();
            dataform.append('data', JSON.stringify(data));
            dataform.append('enquiry_id', enquiry_id);
            let request = $.ajax({
                type: "POST",
                url: base_path + 'WorkInProcess/updateKnittingProgrammeDetails',
                data: dataform,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    _call_to_knitting();
                },
                error: function () {
                    console.log("Error");
                }
            });
        }
    }

    // ********** KNITTING PROGRAMME DETAILS ENDS HERE  *********** //

    // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS STARTS HERE  *********** //

    function get_knitting_programme_itemized_yarn_requirement_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getKnittingProgrammeItemizedYarnRequirementDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_knitting_programme_itemized_yarn_requirement_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_knitting_programme_itemized_yarn_requirement_details(data) {
        $('#knitting_programme_itemized_yarn_requirement').html('');
        let knitting_programme_itemized_yarn_requirement_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('knitting_programme_itemized', data.column.length)
        };
    
        var knitting_programme_itemized_yarn_requirement_vm = new Vue({
            el: '#knitting_programme_itemized_yarn_requirement',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, knitting_programme_itemized_yarn_requirement_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    knitting_programme_itemized_yarn_requirement_details_vm(data);
                },
            }
        });
    }

    // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS ENDS HERE  *********** //
    
    // *********************************************************************************************************************************** 
    // KNITTING ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // DYEING STARTS HERE 
    // ***********************************************************************************************************************************
    
    // ********** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) STARTS HERE  *********** //

    function getFabricDyeingProgramme_qty() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getFabricDyeingProgramme_qty',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_getFabricDyeingProgramme_qty(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    
    function append_getFabricDyeingProgramme_qty(data) {
        $('#FabricDyeingProgrammeQty').html('');
        let FabricDyeingProgramme_qty_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('fabric_dyeing_programme', data.column.length)
        };
    
        var FabricDyeingProgramme_qty_vm = new Vue({
            el: '#FabricDyeingProgrammeQty',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, FabricDyeingProgramme_qty_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    // knitting_programme_itemized_yarn_requirement_details_vm(data);
                },
            }
        });

    }

    // ********** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) ENDS HERE  *********** //
    
    // ********** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) STARTS HERE  *********** //

    function getFabricDyeingProgramme_finish() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getFabricDyeingProgramme_finish',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_getFabricDyeingProgramme_finish(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function colourMatchingFilter(instance, cell, c, r, source) {
        var parts = instance.jexcel.getValueFromCoords(c - 5, r);
        var colour = instance.jexcel.getValueFromCoords(c - 6, r);
        var component = instance.jexcel.getValueFromCoords(c - 7, r);
        var combo = instance.jexcel.getValueFromCoords(c - 8, r);


        if (combo !== "" && component !== "" && colour !== "" && parts !== "") {
            return source.filter(function (item) {
                if (item.combo == combo && item.component == component && item.colour == colour && item.parts == parts) return true;
            })
        } else {
            return [];
        }
    }

    function append_getFabricDyeingProgramme_finish(data) {
        $('#FabricDyeingProgrammeFinish').html('');
        let FabricDyeingProgramme_finish_list = {
            data: data.data,
            columns: [
                { title:'mode', width:'10%',align:'center',type:'hidden'},
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { title: 'Combo', width: '8%', align: 'left', readOnly: true},
                { title: 'Component', width: '8%', align: 'left', readOnly: true},
                { title: 'Colour', width: '8%', align: 'left', readOnly: true},
                { title: 'Garment Parts', width: '8%', align: 'left', readOnly: true},
                { title: 'Fabric Name', width: '8%', align: 'left', type: 'dropdown', source: data.fabric_name_data, readOnly: true},
                { title: 'Pantone No./\n Swatch Ref.', width: '8%', align: 'left'},
                { title: 'Dyeing Special \nRequest If Any', width: '12%', align: 'center', type: 'dropdown', source: data.dsr_data, multiple: true},
                { title: 'Reqd. Fabric \nFinishing Process', width: '8%', align: 'center', type: 'dropdown', source: data.fabric_finish_data, multiple: true},
                { title: 'Blended Fabric - \nColour Matching\nContent', width: '8%', align: 'center', type: 'dropdown', source: data.colourContent, filter: colourMatchingFilter, multiple: true},
                { title: 'Colour Matching\n Standards', width: '8%', align: 'center', type: 'dropdown', source: data.colourStandard},
                { title: 'Approved Lab Dip\n Ref. No', width: '8%', align: 'left'},
                { title: 'Dyeing Vendor Name', width: '8%', align: 'center', type: 'dropdown', source: data.dyeingVendor},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var FabricDyeingProgramme_finish_vm = new Vue({
            el: '#FabricDyeingProgrammeFinish',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, FabricDyeingProgramme_finish_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    updateFabricDyeingProgrammeFinish(data);
                },
            }
        });
    
        $('#FabricDyeingProgrammeFinishBtn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    FabricDyeingProgramme_finish_vm.submitData();
                    fabric = 0, yarn = 0, knitting = 0, dyeing = 0, compacting = 0, lab = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });

        function updateFabricDyeingProgrammeFinish(data) {
            let dataform = new FormData();
            dataform.append('data', JSON.stringify(data));
            dataform.append('enquiry_id', enquiry_id);
            let request = $.ajax({
                type: "POST",
                url: base_path + 'WorkInProcess/updateFabricDyeingProgrammeDetails',
                data: dataform,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    _call_to_dyeing();
                },
                error: function () {
                    console.log("Error");
                }
            });
        }

    }

    // ********** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) ENDS HERE  *********** //
    
    // ********** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) STARTS HERE  *********** //

    function getYarnDyeingProgramme_qty() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getYarnDyeingProgramme_qty',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_getYarnDyeingProgramme_qty(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_getYarnDyeingProgramme_qty(data) {
        $('#YarnDyeingProgrammeQty').html('');
        let YarnDyeingProgramme_qty_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('yarn_dyeing_programme', data.column.length)
        };
    
        var YarnDyeingProgramme_qty_vm = new Vue({
            el: '#YarnDyeingProgrammeQty',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, YarnDyeingProgramme_qty_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    // knitting_programme_itemized_yarn_requirement_details_vm(data);
                },
            }
        });

    }

    // ********** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) ENDS HERE  *********** //
    
    // ********** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) STARTS HERE  *********** //

    function getYarnDyeingProgramme_finish() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getYarnDyeingProgramme_finish',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_getYarnDyeingProgramme_finish(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function yarnColourMatchingFilter(instance, cell, c, r, source) {
        var parts = instance.jexcel.getValueFromCoords(c - 4, r);
        var combo = instance.jexcel.getValueFromCoords(c - 6, r);
        var component = instance.jexcel.getValueFromCoords(c - 5, r);
        var colour = instance.jexcel.getValueFromCoords(c - 7, r);

        if (combo !== "" && component !== "" && colour !== "" && parts !== "") {
            return source.filter(function (item) {
                if (item.combo == combo && item.component == component && item.colour == colour && item.parts == parts) return true;
            })
        } else {
            return [];
        }
    }

    function append_getYarnDyeingProgramme_finish(data) {
        $('#YarnDyeingProgrammeFinish').html('');
        let YarnDyeingProgramme_finish_list = {
            data: data.data,
            columns: [
                { title: 'mode', width:'0%',align:'center',type:'hidden'},
                { title: 'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Colour', width: '8%', align: 'left', readOnly: true},
                { title: 'combo', width: '8%', align: 'left', type: 'hidden'},
                { title: 'component', width: '8%', align: 'left', type: 'hidden'},
                { title: 'parts', width: '8%', align: 'left', type: 'hidden'},
                { title: 'Pantone No./\n Swatch Ref.', width: '8%', align: 'left'},
                { title: 'Dyeing Special \nRequest If Any', width: '12%', align: 'center', type: 'dropdown', source: data.dsr_data, multiple: true},
                { title: 'Reqd. Fabric \nFinishing Process', width: '8%', align: 'center', type: 'dropdown', source: data.fabric_finish_data, multiple: true},
                { title: 'Blended Fabric - \nColour Matching Content', width: '8%', align: 'center', type: 'dropdown', source: data.colourContent, filter: yarnColourMatchingFilter, multiple: true},
                { title: 'Colour Matching\n Standards', width: '8%', align: 'center', type: 'dropdown', source: data.colourStandard},
                { title: 'Approved Lab Dip\n Ref. No', width: '8%', align: 'left'},
                { title: 'Dyeing Vendor Name', width: '8%', align: 'center', type: 'dropdown', source: data.dyeingVendor},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var YarnDyeingProgramme_finish_vm = new Vue({
            el: '#YarnDyeingProgrammeFinish',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, YarnDyeingProgramme_finish_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_yarn_dyeing_programme_finish(data);
                },
            }
        });
    
        $('#YarnDyeingProgrammeFinishBtn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    YarnDyeingProgramme_finish_vm.submitData();
                    fabric = 0, yarn = 0, knitting = 0, dyeing = 0, compacting = 0, lab = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });

        function update_yarn_dyeing_programme_finish(data) {
            let dataform = new FormData();
            dataform.append('data', JSON.stringify(data));
            dataform.append('enquiry_id', enquiry_id);
            let request = $.ajax({
                type: "POST",
                url: base_path + 'WorkInProcess/updateYarnDyeingProgrammeDetails',
                data: dataform,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    _call_to_dyeing();
                },
                error: function () {
                    console.log("Error");
                }
            });
        }

    }

    // ********** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // DYEING ENDS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    // COMPACTING STARTS HERE 
    // ***********************************************************************************************************************************


    // ********** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS STARTS HERE  *********** //

    function getFabricWashingCompatingDetails() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getFabricWashingCompatingDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_FABRIC_WASH_COMPACTING_FINISH_DETAILS(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_FABRIC_WASH_COMPACTING_FINISH_DETAILS(data) {
        $('#fabric_washing_compacting').html('');
        let fabric_washing_compacting_list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var fabric_washing_compacting_vm = new Vue({
            el: '#fabric_washing_compacting',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, fabric_washing_compacting_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_fabric_washing_compacting(data);
                },
            }
        });
    
        
        $('#fabric_washing_compacting_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    fabric_washing_compacting_vm.submitData();
                    fabric = 0, yarn = 0, knitting = 0, dyeing = 0, compacting = 0, lab = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });

        function update_fabric_washing_compacting(data) {
            let dataform = new FormData();
            dataform.append('data', JSON.stringify(data));
            dataform.append('enquiry_id', enquiry_id);
            let request = $.ajax({
                type: "POST",
                url: base_path + 'WorkInProcess/updateFabricWashingCompatingDetails',
                data: dataform,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    _call_to_compacting();
                },
                error: function () {
                    console.log("Error");
                }
            });
        }

    }

    // ********** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // COMPACTING ENDS HERE 
    // ***********************************************************************************************************************************


    
    // *********************************************************************************************************************************** 
    // LAB STARTS HERE 
    // ***********************************************************************************************************************************
     
     // ********** LAB TESTING ACCEPTANCE INTERNAL STARTS HERE  *********** //

     function get_lab_testing_acceptance_internal_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getLabTestingAcceptanceInternalDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_lab_testing_acceptance_internal_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_lab_testing_acceptance_internal_details(data) {
        $('#lab_testing_acceptance_internal').html('');
        let lab_testing_acceptance_internal_list = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Combo / Colour', width: '12%', align: 'left', readOnly: true },
                { title: 'Garment Parts', width: '6%', align: 'left', readOnly: true },
                { title: 'Item Description', width: '16%', align: 'center', readOnly: true },
                { title: 'Req. GSM', width: '6%', align: 'center', readOnly: true },
                { title: 'Req. DIA /\n DIM (Inches)', width: '6%', align: 'right', readOnly: true },
                { title: 'Shrink. Acc.\n Level (L)', width: '6%', align: 'center', type: 'dropdown', source: data.perData},
                { title: 'Shrink. Acc.\n Level (W)', width: '6%', align: 'center', type: 'dropdown', source: data.perData},
                { title: 'Spirality Acc.\n Level', width: '6%', align: 'center', type: 'dropdown', source: data.perData},
                { title: 'Crocking Acc.\n Level (Dry)', width: '6%', align: 'center', type: 'dropdown', source: data.gradeData},
                { title: 'Crocking Acc.\n Level (Wet)', width: '6%', align: 'center', type: 'dropdown', source: data.gradeData},
                { title: 'Fastness Acc.\n Level (Shade)', width: '6%', align: 'center', type: 'dropdown', source: data.gradeData},
                { title: 'Fastness Acc.\n Level (Stain)', width: '6%', align: 'center', type: 'dropdown', source: data.gradeData},
                { title: 'Testing\n Authority', width: '6%', align: 'center', type: 'dropdown', source: data.testingAuthority},
                { title: 'Approving\n Authority', width: '6%', align: 'center', type: 'dropdown', source: data.approvingAuthority}
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var lab_testing_acceptance_internal_vm = new Vue({
            el: '#lab_testing_acceptance_internal',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, lab_testing_acceptance_internal_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    lab_testing_acceptance_internal_details_vm(data);
                },
            }
        });
    
        $('#lab_testing_acceptance_internal_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    lab_testing_acceptance_internal_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    function lab_testing_acceptance_internal_details_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateLabTestingAcceptanceInternalDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_lab();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // ********** LAB TESTING ACCEPTANCE INTERNAL ENDS HERE  *********** //
     
     // ********** LAB TESTING ACCEPTANCE EXTERNAL STARTS HERE  *********** //

     function get_lab_testing_acceptance_external_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getLabTestingAcceptanceExternalDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_lab_testing_acceptance_external_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_lab_testing_acceptance_external_details(data) {
        $('#lab_testing_acceptance_external').html('');
        let lab_testing_acceptance_external_list = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Combo / Colour', width: '8%', align: 'left', type: 'dropdown', source: data.comboData },
                { title: 'Item Description', width: '8%', align: 'left', type: 'dropdown', source: data.itemDescData },
                { title: 'Lab Testing Parameters', width: '8%', align: 'center', type: 'dropdown', source: data.labTestingAuthority },
                { title: 'Acceptance Level', width: '8%', align: 'center', type: 'dropdown', source: data.acceptanceLevel },
                { title: 'Testing Authority', width: '8%', align: 'center', type: 'dropdown', source: data.testingAuthority},
                { title: 'Approving Authority', width: '8%', align: 'center', type: 'dropdown', source: data.approvingAuthority},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var lab_testing_acceptance_external_vm = new Vue({
            el: '#lab_testing_acceptance_external',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, lab_testing_acceptance_external_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    lab_testing_acceptance_external_details_vm(data);
                },
            }
        });
    
        $('#lab_testing_acceptance_external_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    lab_testing_acceptance_external_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    function lab_testing_acceptance_external_details_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateLabTestingAcceptanceEXternalDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_lab();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // ********** LAB TESTING ACCEPTANCE EXTERNAL ENDS HERE  *********** //
     
     // ********** EXTERNAL LAB TESTING AUTHORITY STARTS HERE  *********** //

     function get_external_lab_testing_authority_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getExternalLabTestingAuthorityDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_external_lab_testing_authority_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_external_lab_testing_authority_details(data) {
        $('#external_lab_testing_authority').html('');
        let external_lab_testing_authority_list = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Lab Testing Authority -\n Name', width: '8%', align: 'center', type: 'dropdown', source: data.labTestingAuthority },
                { title: 'Address', width: '8%', align: 'left', readOnly: true },
                { title: 'GST No.', width: '8%', align: 'left', readOnly: true },
                { title: 'Contact Person Name', width: '8%', align: 'left', readOnly: true },
                { title: 'e-mail ID', width: '8%', align: 'left', readOnly: true },
                { title: 'Phone / Mobile No.', width: '8%', align: 'right', readOnly: true },
                { title: 'If On-line Booking System -\n Web Site ID', width: '8%', align: 'left' },
                { title: 'User ID / Pass Word', width: '8%', align: 'left' },
                { title: 'Password Expiry\n Date & Time', width: '8%', align: 'center', type: 'calendar', options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if (col == 2) {
                    labName = val;
                }
                if (col == 3) {
                    let id = labName;
                    let labTestAuthority = data.labTestingAuthority;
                    let value = labTestAuthority.find(v => v.id === id);
                    if(value != undefined) {
                        $(cell).text(value.address);
                        instance.jexcel.options.data[row][col] = value.address;
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }
                if (col == 4) {
                    let id = labName;
                    let labTestAuthority = data.labTestingAuthority;
                    let value = labTestAuthority.find(v => v.id === id);
                    if(value != undefined) {
                        $(cell).text(value.gst);
                        instance.jexcel.options.data[row][col] = value.gst;
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }
                if (col == 5) {
                    let id = labName;
                    let labTestAuthority = data.labTestingAuthority;
                    let value = labTestAuthority.find(v => v.id === id);
                    if(value != undefined) {
                        $(cell).text(value.cname);
                        instance.jexcel.options.data[row][col] = value.cname;
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }
                if (col == 6) {
                    let id = labName;
                    let labTestAuthority = data.labTestingAuthority;
                    let value = labTestAuthority.find(v => v.id === id);
                    if(value != undefined) {
                        $(cell).text(value.email);
                        instance.jexcel.options.data[row][col] = value.email;
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }
                if (col == 7) {
                    let id = labName;
                    let labTestAuthority = data.labTestingAuthority;
                    let value = labTestAuthority.find(v => v.id === id);
                    if(value != undefined) {
                        $(cell).text(value.mobile);
                        instance.jexcel.options.data[row][col] = value.mobile;
                    }
                    else {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                }
            }
        };
    
        var external_lab_testing_authority_vm = new Vue({
            el: '#external_lab_testing_authority',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, external_lab_testing_authority_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    external_lab_testing_authority_details_vm(data);
                },
            }
        });
    
        $('#external_lab_testing_authority_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    external_lab_testing_authority_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    function external_lab_testing_authority_details_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateExternalLabTestingAuthorityDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_lab();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // ********** EXTERNAL LAB TESTING AUTHORITY ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // LAB ENDS HERE 
    // ***********************************************************************************************************************************


});