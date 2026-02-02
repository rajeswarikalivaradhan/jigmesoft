$(document).ready(function () {

    $('#assortType1').hide();
    $('#assortType2').hide();
    $('#assortType3').hide();
    $('#assortType4').hide();
    $('#assortType5').hide();
    $('#assortType6').hide();
    $('#assortType7').hide();
    $('#assortType8').hide();
    
    getSampleReqDetails();

    var oe_color_combo_data = [];
    var oe_po_sizewise_data = [];
    var oe_component_intakewise_data = [];
    var oe_po_wise_delivery_data = [];
    var oe_complete_process_data = [];

    var cad_requirement_data = [];
    
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
        
        if(mode == "empty") {
            return {
                text: 'No Pending Sample Requirement.',
                type: 'empty',
                icon: 'empty',
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

    // ******************************************************************************** 
    // ORDER ENTRY STARTS HERE 
    // ********************************************************************************

    var activeNav = $("ul.nav-pills > li > a").attr("href").replace("#", "");
    var cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0,
        final_inspection = 0, documentation = 0, checklist = 0;

    if (activeNav == 'order_entry') {
        _call_to_orderEntry();
        // getRemarksNImageDetails();
    }

    $("ul.nav-pills > li > a").click(function () {
        var currentNav = $(this).attr("href").replace("#", "");
        if (currentNav === 'cad' && cad === 0) {
            cad++;
            _call_to_cadDetails();
        } else if (currentNav === 'fabric' && fabric === 0) {
            fabric++;
        } else if (currentNav === 'sample' && sample === 0) {
            sample++;
            _call_to_sampling();
        } else if (currentNav === 'embellishment' && embellishment === 0) {
            embellishment++;
            _call_to_embellishment();
        } else if (currentNav === 'bom_art_1' && bom_art_1 === 0) {
            bom_art_1++;
            _call_to_bom_artcle_1();
        } else if (currentNav === 'bom_art_2' && bom_art_2 === 0) {
            bom_art_2++;
            _call_to_bom_artcle_2();
        } else if (currentNav === 'packing' && packing === 0) {
            packing++;
            _call_to_packing();
        } else if (currentNav === 'final_inspection' && final_inspection === 0) {
            final_inspection++;
            _call_to_final_inspection();
        } else if (currentNav === 'documentation' && documentation === 0) {
            documentation++;
            _call_to_documentation();
        } else if (currentNav === 'checklist' && checklist === 0) {
            checklist++;
            _call_to_checklist();
        }
    });

    function _call_to_orderEntry() {
        getComboColourDetails(); // call combo color details
        getPoSizewiseDetails(); // call po size wise quantity
        get_oe_component_intakewise(); // call component intake wise
        getPoWiseDeliveryDetails(); // call po wise delivery 
        get_oe_complete_process(); // call complete garment process
    }
    
    function _call_to_cadDetails() {
        get_cad_requirement_details(); // call cad requirement
        get_cad_common_table_details(); // CAD common table
    }
    
    function _call_to_sampling() {
        get_sample_requirement_details(); // call sample requirement
        // get_sample_dispatch_details(); // call to sample dispatch details
        get_sample_common_table_details(); // call to sample common table
    }
    
    function _call_to_embellishment() {
        get_embellishment_details(); // call embellishment details
        get_embellishment_status_details(); // call embellishment status details
        get_embellishment_vendor_details(); // call embellishment vendor details
    }
    
    function _call_to_bom_artcle_1() {
        get_bom_sampling_approval_details(); // call bom article 1 details
        get_bom_requirement_details(); // call bom article 1 requirement
        get_bom_requirement_Consolidated_details(); // call bom article 1 requirement consolidate
        get_bom1_sourcing_details(); // call bom article 1 source
        get_bom1_sampling_despatch(); // BOM 1 sample despatch
        get_bom_1_common_table_details(); // BOM 1 common table
    }
    
    function _call_to_bom_artcle_2() {
        get_bom2_sampling_approval_details(); // call bom article 2 details
        get_bom2_requirement_details(); // call bom article 2 requirement
        get_bom2_requirement_Consolidated_details(); // call bom article 2 requirement consolidate
        get_bom2_sourcing_details(); // call bom article 2 source
        get_bom2_sampling_despatch(); // BOM 2 sample despatch
        get_bom_2_common_table_details(); // BOM 1 common table
    }

    function _call_to_checklist() {
        get_manag_checklist_details(); // call management checklist details
    }
    
    function _call_to_packing() {
        getPacking_details(); // call packing details
        get_component_wise_packing(); // call packing common details
    }

    function _call_to_final_inspection() {
        get_final_inspection_standard_details(); // call final inspection details
    }

    function _call_to_documentation() {
        get_details_of_consignee_logistics(); // call documentation details
    }
    
    function getSampleReqDetails()
    {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/get_req_empty_value',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                count = JSON.parse(data);
                // console.log(count);
                if(count == 0 || count == '0') {
                    $('#sample_req').show();
                    $('#sample_req1').hide();
                } else {
                    $('#sample_req').hide();
                    $('#sample_req1').show();
                }
                            
            }
            
        });
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
        total = (total > 0) ? total : '';
        return total;
    }

    
    function footer(grid_name, columnlength)
    {
        if(grid_name == 'comboColour')
        {
            return [[ '', '', '', '', '', 'Total Qty.:', '=SUMCOL(TABLE(), COLUMN(), "")', 'Set' ]];
        }
        else if(grid_name == 'poSizeWise')
        {
            let empar = [];
            let position = columnlength - 3;
            for(var i= 1; i<= position; i++)
            {
                empar.push('');
            }
            empar.push('Total Qty.:', '=SUMCOL(TABLE(), COLUMN(), "")', 'Set');
            return [empar];
        }
        else if(grid_name == 'intakeWise')
        {
            let empar = [];
            let position = columnlength - 2;
            for(var i= 1; i<= position; i++)
            {
                empar.push('');
            }
            empar.push('Total : ', '=SUMCOL(TABLE(), COLUMN(), "twoColumn")');
            return [empar];
            
        }
        else if(grid_name == 'oe_4thtable')
        {
            return [[ '', '', '', '', '', 'Total Qty.:', '=SUMCOL(TABLE(), COLUMN(), "")', 'Set', '', '', '', '', '']];
        }
        else if(grid_name == 'packing_assort_7')
        {
            let empar = [];
            let position = columnlength - 3;
            for(var i= 1; i<= position; i++)
            {
                empar.push('');
            }
            empar.push('Total Qty: ', '=SUMCOL(TABLE(), COLUMN(), "")', '=SUMCOL(TABLE(), COLUMN(), "")');
            return [empar];
        }
        else if(grid_name == 'packing_assort_8')
        {
            let empar = [];
            let position = columnlength - 2;
            for(var i= 1; i<= position; i++)
            {
                empar.push('');
            }
            empar.push('Total Qty: ', '=SUMCOL(TABLE(), COLUMN(), "")');
            return [empar];
        }
    }

    //*************** COMBO / COLOUR WISE QUANTITY BREAKUP STARTS HERE *************** //

    function getComboColourDetails() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getComboColourDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                oe_color_combo_data = JSON.parse(data);
                appendComboColourDetailss(oe_color_combo_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function appendComboColourDetailss(data) {
        $('#comboColourSizeSheet').html('');
        let combo_color_wise = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            // footers: [[ '', '', '', '', '', 'Total Qty.:', '=SUMCOL(TABLE(), COLUMN())', 'Set' ]],
            footers: footer('comboColour', data.column.length),
        };

        var combo_color_wise_vm = new Vue({
            el: '#comboColourSizeSheet',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, combo_color_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    updateSaveComboColour(data);
                },
            }
        });

        $('#oe_submitColourCombo').click(function () {

            let validate_filed = [2,3,4,5,6];
            let data = combo_color_wise_vm.getData();
                        // let validate = validateForm(validate_filed, data);
                        let optional_validation_field = [];
                        let pendingField = "";
                        let statusCheck = "no";
                        let validate = validateForm(validate_filed, data, statusCheck, optional_validation_field, pendingField);            

            if(validate == 0)
            {
                swalWithBootstrapButtons.fire({
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
                }).then(function (result) {
                    if (result.value) {
                        combo_color_wise_vm.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: 'Cancelled',
                            text: 'Cancelled successfully.',
                            type: 'error',
                            icon: 'error',
                            customClass: {
                                'confirmButton': 'btn btn-secondary px-5'
                            }
                        });
                    }
                });
            }
            else
            {
                swalWithBootstrapButtons.fire({
                    title: 'Warning',
                    text: "Please Fill All the fields to continue",
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    customClass: {
                        'confirmButton': 'btn btn-secondary px-5'
                    }
                })
            }

        });
    }

    function updateSaveComboColour(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateColorComboDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                var data = $.parseJSON(data);
                if(data.status == 'success')
                {
                    _call_to_orderEntry();
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!',
                        text: 'Operation completed successfully.',
                        type: 'success',
                        icon: 'success',
                        customClass: {
                            'confirmButton': 'btn btn-info px-5'
                        }
                    });
                }
                else 
                {
                    swalWithBootstrapButtons.fire({
                        title: 'Error!',
                        text: 'Operation Failed.',
                        type: 'error',
                        icon: 'error',
                        customClass: {
                            'confirmButton': 'btn btn-info px-5'
                        }
                    });
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    //*************** COMBO / COLOUR WISE QUANTITY BREAKUP ENDS HERE *************** //

    //*************** PO SIZE WISE QUANTITY BREAKUP STARTS HERE *************** //

    function getPoSizewiseDetails() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getPoSizewiseDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                oe_po_sizewise_data = JSON.parse(data);
                append_oe_posizewise(oe_po_sizewise_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_oe_posizewise(data) {
        $('#poSizeWiseSheet').html('');
        let dd = [];
        let inputCount = '';
        let updatedRow = '';
        let index = '';
        let oe_po_size_wise = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('poSizeWise', data.column.length),
            // nestedHeaders: data.nestedHeader,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 3) 
                {
                    updatedRow = row;
                    txt = $(cell).text();
                    dd = data.column[3]['source'];
                    inputCount = data.inputCount;
                    if(txt != '')
                    {
                        index = dd.findIndex(data => txt.includes( data.name ));
                        oe_po_size_wise.data[row][4] = dd[index].component;
                        oe_po_size_wise.data[row][5] = dd[index].colour;
                        oe_po_size_wise.data[row][6] = dd[index].intake_qty;
                        oe_po_size_wise.data[row][8+inputCount] = dd[index].pcs_set;
                    }
                    else
                    {
                        oe_po_size_wise.data[row][4] = '';
                        oe_po_size_wise.data[row][5] = '';
                        oe_po_size_wise.data[row][6] = '';
                        oe_po_size_wise.data[row][8+inputCount] = '';
                    }
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                var poQtyColId = data.column.length - 2;
                if (col === 0) 
                {
                    colsVal = 0;
                }
                if(col == 2)
                {
                    val = $(cell).text();
                }
                if(col == 4) 
                {
                    if(val != '' && dd.length > 0 && row == updatedRow)
                    {
                        $(cell).text(dd[index]['component']);
                    }
                    else if(val == '')
                    {
                        $(cell).text('');
                    }
                }
                if(col == 5) 
                {
                    if(val != '' && dd.length > 0 && row == updatedRow)
                    {
                        $(cell).text(dd[index]['colour']);
                    }
                    else if(val == '')
                    {
                        $(cell).text('');
                    }
                }
                if(col == 6) 
                {
                    if(val != '' && dd.length > 0 && row == updatedRow)
                    {
                        $(cell).text(dd[index]['intake_qty']);
                    }
                    else if(val == '')
                    {
                        $(cell).text('');
                    }
                }
                if (col >= 7 && col <= data.column.length - 3 && val != "") 
                {
                    var txtValue = numeral(val).format('0');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    colsVal = parseFloat(colsVal) + parseFloat(txtValue);
                }
                if (col === poQtyColId) {
                    avgCol = data.column.length - 9;
                    colsVal = numeral(colsVal).format('0');
                    colsVal  = (colsVal > 0) ? colsVal : '';
                    $(cell).text(colsVal);
                    instance.jexcel.options.data[row][col] = colsVal;
                }
                if(col == poQtyColId+1) 
                {
                    if(val != '' && dd.length > 0 && row == updatedRow)
                    {
                        $(cell).text(dd[index]['pcs_set']);
                    }
                    else if(val == '')
                    {
                        $(cell).text('');
                    }
                }
            },
        };

        var oe_po_size_wise_vm = new Vue({
            el: '#poSizeWiseSheet',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, oe_po_size_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    updateSavePOSize(data);
                },
            }
        });

        $('#oe_submitPOSize').click(function () {

            let validate_filed = [2,3];
            let data = oe_po_size_wise_vm.getData();

            for (let i = 0; i < data.length; i++) {
                data[i].forEach(function (value, index) {
                    if (index >= 7 && index <= data[i].length - 3 && value == "") 
                    {
                        validate_filed.push(index);
                    }
                });

            }

            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validate = validateForm(validate_filed, data, statusCheck, optional_validation_field, pendingField);
            // let validate = validateForm(validate_filed, data);

            if(validate == 0)
            {
                let comboTotal = 0;
                let poSizeTotal = 0;
                
                for (let i = 0; i < oe_color_combo_data.data.length; i++) {
                    comboTotal += parseFloat(oe_color_combo_data.data[i][6]);
                }
    
                for (let i = 0; i < oe_po_sizewise_data.data.length; i++) {
                    poSizeTotal += parseFloat(oe_po_sizewise_data.data[i][oe_po_sizewise_data.data[i].length - 2]);
                }
    
                if(comboTotal == poSizeTotal)
                {
                    swalWithBootstrapButtons.fire({
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
                    }).then(function (result) {
                        if (result.value) {
                            oe_po_size_wise_vm.submitData();
                            cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            swalWithBootstrapButtons.fire({
                                title: 'Cancelled',
                                text: 'Cancelled successfully.',
                                type: 'error',
                                icon: 'error',
                                customClass: {
                                    'confirmButton': 'btn btn-secondary px-5'
                                }
                            });
                        }
                    });
                }
                else
                {
    
                    swalWithBootstrapButtons.fire({
                        title: 'Warning',
                        text: "Combo / Colour wise quantity total and po sizw wise total should be equal to proceed next step",
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        customClass: {
                            'confirmButton': 'btn btn-secondary px-5'
                        }
                    })
    
                }
            }
            else
            {
                swalWithBootstrapButtons.fire({
                    title: 'Warning',
                    text: "Please Fill All the fields to continue",
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    customClass: {
                        'confirmButton': 'btn btn-secondary px-5'
                    }
                })
            }
            
        });

    }

    function updateSavePOSize(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updatePOSizeDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                var data = $.parseJSON(data);
                if(data.status == 'success')
                {
                    _call_to_orderEntry();
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!',
                        text: 'Operation completed successfully.',
                        type: 'success',
                        icon: 'success',
                        customClass: {
                            'confirmButton': 'btn btn-info px-5'
                        }
                    });
                }
                else 
                {
                    swalWithBootstrapButtons.fire({
                        title: 'Error!',
                        text: 'Operation Failed.',
                        type: 'error',
                        icon: 'error',
                        customClass: {
                            'confirmButton': 'btn btn-info px-5'
                        }
                    });
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }


    //*************** PO SIZE WISE QUANTITY BREAKUP ENDS HERE *************** //

    //*************** COMPONENT INTAKE WISE ITEMIZED STARTS HERE ************* //

    function get_oe_component_intakewise() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getOrderEntryComponentItemized',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                oe_component_intakewise_data = JSON.parse(data);
                append_oe_component_intakewise(oe_component_intakewise_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_oe_component_intakewise(data) {
        $('#oe_component_intake_wise').html('');
        let as = [];
        let inputCount = '';
        // alert(data.column.length);
        let oe_component_intake_wise = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('intakeWise', data.column.length)
        };

        var oe_component_intake_wise_vm = new Vue({
            el: '#oe_component_intake_wise',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, oe_component_intake_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_oe_component_intake(data);
                },
            }
        });

        $('#oe_submit_component_intake').click(function () {

            let validate_filed = [7];
            let data = oe_component_intake_wise_vm.getData();
                        // let validate = validateForm(validate_filed, data);
            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validate = validateForm(validate_filed, data, statusCheck, optional_validation_field, pendingField);

            if(validate == 0)
            {
                swalWithBootstrapButtons.fire({
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
                }).then(function (result) {
                    if (result.value) {
                        oe_component_intake_wise_vm.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                        _call_to_orderEntry();
                        swalWithBootstrapButtons.fire({
                            title: 'Saved!',
                            text: 'Operation completed successfully.',
                            type: 'success',
                            icon: 'success',
                            customClass: {
                                'confirmButton': 'btn btn-info px-5'
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: 'Cancelled',
                            text: 'Cancelled successfully.',
                            type: 'error',
                            icon: 'error',
                            customClass: {
                                'confirmButton': 'btn btn-secondary px-5'
                            }
                        });
                    }
                });
            }
            else
            {
                swalWithBootstrapButtons.fire({
                    title: 'Warning',
                    text: "Please fill all size spec. code",
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    customClass: {
                        'confirmButton': 'btn btn-secondary px-5'
                    }
                })
            }

        });
    }

    function update_oe_component_intake(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateOrderEntryComponentItemized',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    //*************** COMPONENT INTAKE WISE ITEMIZED ENDS HERE *************** //

    //*************** PO WISE DELIVERY STARTS HERE *************** //

    function getPoWiseDeliveryDetails() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getPoWiseDeliveryDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                oe_po_wise_delivery_data = JSON.parse(data);
                append_oe_powisedelivery(oe_po_wise_delivery_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_oe_powisedelivery(data) {
        $('#poWiseDelivery').html('');
        let dd = [], desdd = [], updatedRow = '', desUpdatedRow = '', index = '', desIndex = '';
        let oe_po_wise_delivery = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('oe_4thtable', data.column.length),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 9) 
                {
                   
                    updatedRow = row;
                    txt = $(cell).text();
                    dd = data.column[9]['source'];
                    if(txt != '')
                    {
                        index = dd.findIndex(data => txt.includes( data.name ));
                        oe_po_wise_delivery.data[row][10] = dd[index].pcntry;
                    }
                    else
                    {
                        oe_po_wise_delivery.data[row][9] = '';
                        oe_po_wise_delivery.data[row][10] = '';
                    }
                }
                if(col == 11) 
                {
                    desUpdatedRow = row;
                    destxt = $(cell).text();
                    desdd = data.column[11]['source'];
                    if(destxt != '')
                    {
                        desIndex = desdd.findIndex(data => destxt.includes( data.name ));
                        oe_po_wise_delivery.data[row][12] = desdd[desIndex].pcntry;
                    }
                    else
                    {
                        oe_po_wise_delivery.data[row][11] = '';
                        oe_po_wise_delivery.data[row][12] = '';
                    }
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) {
                if(col == 9)
                {
                    val = $(cell).text();
                }
                if(col == 10) 
                {
                    if(val != '' && dd.length > 0 && row == updatedRow)
                    {
                        $(cell).text(dd[index]['pcntry']);
                    }
                    else if(val == '')
                    {
                        $(cell).text('');
                    }
                }
                if(col == 11)
                {
                    des_city = $(cell).text();
                }
                if(col == 12) 
                {
                    if(des_city != '' && desdd.length > 0 && row == desUpdatedRow)
                    {
                        $(cell).text(desdd[desIndex]['pcntry']);
                    }
                    else if(des_city == '')
                    {
                        $(cell).text('');
                    }
                }
            },
        };

        var oe_po_wise_delivery_vm = new Vue({
            el: '#poWiseDelivery',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, oe_po_wise_delivery);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    updateSavePOSizeDelivery(data);
                },
            }
        });

        $('#oe_submitPOWiseDelivery').click(function () {

            let validate_filed = [3,4,8,9,11];
            let data = oe_po_wise_delivery_vm.getData();
            // let validate = validateForm(validate_filed, data);
            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validate = validateForm(validate_filed, data, statusCheck, optional_validation_field, pendingField);

            if(validate == 0)
            {
                swalWithBootstrapButtons.fire({
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
                }).then(function (result) {
                    if (result.value) {
                        oe_po_wise_delivery_vm.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: 'Cancelled',
                            text: 'Cancelled successfully.',
                            type: 'error',
                            icon: 'error',
                            customClass: {
                                'confirmButton': 'btn btn-secondary px-5'
                            }
                        });
                    }
                });
            }
            else
            {
                swalWithBootstrapButtons.fire({
                    title: 'Warning',
                    text: "Please Fill All the fields to continue",
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    customClass: {
                        'confirmButton': 'btn btn-secondary px-5'
                    }
                })
            }
        });

    }

    function updateSavePOSizeDelivery(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updatePoWiseDeliveryDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                var data = $.parseJSON(data);
                if(data.status == 'success')
                {
                    _call_to_orderEntry();
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!',
                        text: 'Operation completed successfully.',
                        type: 'success',
                        icon: 'success',
                        customClass: {
                            'confirmButton': 'btn btn-info px-5'
                        }
                    });
                }
                else 
                {
                    swalWithBootstrapButtons.fire({
                        title: 'Error!',
                        text: 'Operation Failed.',
                        type: 'error',
                        icon: 'error',
                        customClass: {
                            'confirmButton': 'btn btn-info px-5'
                        }
                    });
                }

            },
            error: function () {
                console.log("Error");
            }
        });
    }

    //*************** PO WISE DELIVERY ENDS HERE *************** //

    // ********** COMPLETE GARMENT PROCESS FLOW STARTS HERE ***********/
    
    function comboFilter(instance, cell, c, r, source) {
        var po_enq_id = instance.jexcel.getValueFromCoords(c - 1, r);
        if (po_enq_id !== "") {
            return source.filter(function (item) {
                //console.log(item)
                if (item.po_enq_id == po_enq_id) return true;
            })
        } else {
            return [];
        }
    }
    
    function componentFilter(instance, cell, c, r, source) {
        var combo_id = instance.jexcel.getValueFromCoords(c - 1, r);
        if (combo_id !== "") {
            return source.filter(function (item) {
                if (item.combo_id == combo_id) return true;
            })
        } else {
            return [];
        }
    }
    
    function colorFilter(instance, cell, c, r, source) {
        let component_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let combo_id = instance.jexcel.getValueFromCoords(c - 2, r);
        if (component_id !== "") {
            return source.filter(function (item) {
                if (item.component_id == component_id && item.combo_id == combo_id) return true;
            })
        } else {
            return [];
        }
    }

    function specFilter(instance, cell, c, r, source) {
        let colour_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let component_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let combo_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let po_enq_id = instance.jexcel.getValueFromCoords(c - 4, r);

        if (colour_id != "" && component_id != "" && combo_id != "" && po_enq_id != "") {
            return source.filter(function (item) {
                if ((item.colour_id == colour_id) && (item.component_id == component_id) &&
                    (item.combo_id == combo_id) && (item.po_enq_id == po_enq_id) ) return true;
            })
        } else {
            return [];
        }
    }

    function get_oe_complete_process() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getOrderEntryCompleteProcess',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                oe_complete_process_data = JSON.parse(data);
                append_oe_complete_process(oe_complete_process_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_oe_complete_process(data) {
        $('#oe_complete_process').html('');
        let dd = [], desdd = [], updatedRow = '', desUpdatedRow = '', index = '', desIndex = '';
        let oe_complete_process = {
            data: data.data,
            columns: [
                { title:'mode', width:'10%',align:'center',type:'hidden'},
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type:'dropdown',title:'P.O. No. / Enq. Ref. No.', width:'12%', source: data.poEnqRefNo, align:'left' },
                { type:'dropdown',title:'Combo', width:'8%', source: data.poCombo, filter: comboFilter, align:'left' },
                { type:'dropdown',title:'Component', width:'10%', source: data.poComponent, filter: componentFilter, align:'left' },
                { type:'dropdown',title:'Colour', width:'10%', source: data.poColor, filter: colorFilter, align:'left' },
                { type:'dropdown',title:'Size Spec Code / Fit', width:'10%',source: data.specCode, filter: specFilter, align:'left' },
                { type:'dropdown',title:'Process Flow Description', width:'10%', source: data.processFlow, align:'left' },
                { title:'Remarks', width:'10%',align:'left' },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2) 
                {
                    updatedRow = row;
                    txt = $(cell).text();
                    oe_complete_process.data[row][3] = '';
                    oe_complete_process.data[row][4] = '';
                    oe_complete_process.data[row][5] = '';
                    oe_complete_process.data[row][6] = '';
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2)
                {
                    val = $(cell).text();
                }
                if(col == 3) 
                {
                    if(val == '') { $(cell).text(''); }
                }
                if(col == 4) 
                {
                    if(val == '') { $(cell).text(''); }
                }
                if(col == 5) 
                {
                    if(val == '') { $(cell).text(''); }
                }
            },
        };

        var oe_complete_process_vm = new Vue({
            el: '#oe_complete_process',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, oe_complete_process);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_oe_component_process(data);
                },
            }
        });

        $('#oe_submitCompleteProcess').click(function () {

            let validate_filed = [2,3,4,5,6,7,8];
            let data = oe_complete_process_vm.getData();
            // let validate = validateForm(validate_filed, data);
            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validate = validateForm(validate_filed, data, statusCheck, optional_validation_field, pendingField);

            if(validate == 0)
            {                
                swalWithBootstrapButtons.fire({
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
                }).then(function (result) {
                    if (result.value) {
                        oe_complete_process_vm.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: 'Cancelled',
                            text: 'Cancelled successfully.',
                            type: 'error',
                            icon: 'error',
                            customClass: {
                                'confirmButton': 'btn btn-secondary px-5'
                            }
                        });
                    }
                });
            }
            else
            {
                swalWithBootstrapButtons.fire({
                    title: 'Warning',
                    text: "Please Fill All the fields to continue",
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    customClass: {
                        'confirmButton': 'btn btn-secondary px-5'
                    }
                })
            }

        });
    }    

    function update_oe_component_process(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updatePoCompleteProcess',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                var data = $.parseJSON(data);
                if(data.status == 'success')
                {
                    _call_to_orderEntry();
                    swalWithBootstrapButtons.fire({
                        title: 'Saved!',
                        text: 'Operation completed successfully.',
                        type: 'success',
                        icon: 'success',
                        customClass: {
                            'confirmButton': 'btn btn-info px-5'
                        }
                    });
                }
                else 
                {
                    swalWithBootstrapButtons.fire({
                        title: 'Error!',
                        text: 'Operation Failed.',
                        type: 'error',
                        icon: 'error',
                        customClass: {
                            'confirmButton': 'btn btn-info px-5'
                        }
                    });
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }


    // ********** COMPLETE GARMENT PROCESS FLOW ENDS HERE *********** /

    // ********************************************************************************************************************************** 
    // ORDER ENTRY ENDS HERE 
    // **********************************************************************************************************************************

    // ********************************************************************************************************************************** 
    // CAD STARTS HERE 
    // **********************************************************************************************************************************

    // ********** CAD REQUIREMENT DETAILS STARTS HERE *********** //

    function cadSpecFilter(instance, cell, c, r, source) {
        let component_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let combo_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let po_enq_id = instance.jexcel.getValueFromCoords(c - 3, r);

        if (component_id != "" && combo_id != "" && po_enq_id != "") {
            return source.filter(function (item) {
                if ((item.component_id == component_id) &&
                    (item.combo_id == combo_id) && (item.po_enq_id == po_enq_id) ) return true;
            })
        } else {
            return [];
        }
    }

    function get_cad_requirement_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getCADRequirement',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                cad_requirement_data = JSON.parse(data);
                append_cad_requirement(cad_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_cad_requirement(data) {
        $('#cad_requirementDetails').html('');
        let dd = [], updatedRow = '', index = '', nVal = '';
        let cad_requirement = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'dropdown', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', source: data.poEnqRefNo, placeholder: jexcelPlaceHolders.dropdown },
                { type: 'dropdown', title: 'Combo / Colour', width: '8%', align: 'left', source: data.poCombo, filter: comboFilter },
                { type: 'dropdown', title: 'Component', width: '8%', align: 'left', source: data.poComponent, filter: componentFilter },
                { type: 'dropdown', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', source: data.specCode, filter: cadSpecFilter, readOnly: true },
                { type: 'dropdown', title: 'Requirement', width: '8%', align: 'left', source: ['Mini Marker', 'Bit Marker', 'Pattern', 'Pattern (Size Set)', 'Lay Marker', 'Others'], },
                { type: 'dropdown', title: 'Required Size(s)', width: '7%', align: 'center', source: data.sizeData },
                { type: 'calendar', title: 'Planned\n Date', width: '7%', align: 'center' },
                // { title: 'Cutoff Date & Time', width: '7%', align: 'center', readOnly: true },
                { type: 'text', title: 'Actual\n Date & Time', width: '7%', align: 'center', readOnly: true },
                { title:'status', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 4) 
                {
                    updatedRow = row;
                    txt = $(cell).text();
                    dd = cad_requirement.columns[5]['source'];
                    let po_no = cad_requirement.data[row][2];
                    let comboColor = cad_requirement.data[row][3];
                    if(txt != '')
                    {
                        index = dd.findIndex(function (val) {
                            if (val.combo_id == comboColor && val.po_enq_id == po_no && val.component_id == txt ) return true;
                        });
                        cad_requirement.data[row][5] = dd[index]['name'];
                        // console.log(dd[index]['name'])
                    }
                    else
                    {
                        cad_requirement.data[row][5] = '';
                        cad_requirement.data[row][6] = '';
                    }
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) {
                if(col == 2) {
                    // console.log(data.data);
                    if(data.data[row][10] == 'Sent') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 3) {
                    if(data.data[row][10] == 'Sent') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 4)
                {
                    nVal = $(cell).text();
                    if(data.data[row][10] == 'Sent') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 5) 
                {
                    if(nVal != '' && dd.length > 0 && row == updatedRow)
                    {
                        $(cell).text(dd[index]['name']);
                        instance.jexcel.options.data[row][col] = dd[index]['name'];
                    }
                    else if(nVal == '')
                    {
                        $(cell).text('');
                    }
                    if(data.data[row][10] == 'Sent') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 6) {
                    if(data.data[row][10] == 'Sent') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 7) {
                    if(data.data[row][10] == 'Sent') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 8) {
                    if(data.data[row][10] == 'Sent') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
            },
        };

        var cad_requirementDetails = new Vue({
            el: '#cad_requirementDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, cad_requirement);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_oe_cad_requirement(data);
                },
            }
        });
    
        $('#oe_submitCADRequirement').click(function () {

            let validate_data = cad_requirementDetails.getData();
            let validate_filed = [2, 3, 4, 5, 6, 7, 8];
            // let validatedErrorCount = validateForm(validate_filed, validate_data);
            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_filed, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        cad_requirementDetails.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
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

    function update_oe_cad_requirement(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateCADRequirement',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_cadDetails();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // ********** CAD REQUIREMENT DETAILS ENDS HERE *********** //

    // ********************************************************************************************************************************** 
    // CAD ENDS HERE 
    // **********************************************************************************************************************************

    
    // ********************************************************************************************************************************** 
    // SAMPLING STARTS HERE 
    // **********************************************************************************************************************************

     // ********** SAMPLE REQUIREMENT DETAILS STARTS HERE  *********** //

    function get_sample_requirement_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getSampleDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                append_sample_requirement(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_sample_requirement(data) {
        $('#sample_submissionDetails').html('');
        let dd = [], updatedRow = '', index = '', nVal = '';
        let sample_requirement_wise = {
        data: data.data,
        columns: [
            { title:'mode', width:'10%',align:'center',type:'hidden'},
            { title:'id', width:'10%',align:'center',type:'hidden'},
            { type: 'dropdown', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', source: data.poEnqRefNo, },
            { type: 'dropdown', title: 'Combo', width: '8%', align: 'left', source: data.poCombo,  filter: comboFilter, },
            { type: 'dropdown', title: 'Component', width: '8%', align: 'left', source: data.poComponent,  filter: componentFilter, },
            { type: 'dropdown', title: 'Colour', width: '8%', align: 'left', source: data.poColor,  filter: colorFilter, readOnly: true },
            { type: 'dropdown', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', source: data.specCode,  filter: specFilter, readOnly: true },
            { type: 'dropdown', title: 'Requirement', width: '8%', align: 'left', source: ['Proto Sample', 'Dev Sample', 'Fit Sample', 'Salesman Sample', 'Photo Shoot Sample', 'PP Sample', 'Size Set', 'TOP', 'Others']},
            { type: 'dropdown', title: 'Required Size(s)', width: '5%', align: 'left', source: data.sizeData, multiple:true},
            { type: 'text', title: 'Req. \n Qty.(Pcs.)', width: '5%', align: 'center', source: ['1', '2', '3', '4', '5', '6', '7', '8' , '9', '10'], },
            { type: 'dropdown', title: "Buyer's \nAppl. Days", width: '7%', align: 'left', source: ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun'], multiple:true},
            { title: 'Planned \nDate', width: '7%', align: 'center', type: 'calendar' },
            // { title: 'Cutoff \nDate & Time', width: '7%', align: 'center', readOnly: true },
            { title: 'Actual \nDate', width: '7%', align: 'center', readOnly: true },
            { type: 'hidden', title: 'status', width: '0%', align: 'center' },
            ], 
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 4) 
                {
                    // updatedRow = row;
                    // txt = $(cell).text();
                    // dd = sample_requirement_wise.columns[6]['source'];
                    // let po_no = sample_requirement_wise.data[row][2];
                    // let combo = sample_requirement_wise.data[row][3];
                    // let component = sample_requirement_wise.data[row][4];   
                    // if(txt != '')
                    // {
                    //     index = dd.findIndex(function (val) {
                    //         if (val.combo_id == combo && val.po_enq_id == po_no && val.component_id == component && val.colour_id == txt ) return true;
                    //     });
                    //     sample_requirement_wise.data[row][6] = dd[index]['name'];
                    // }
                    // else
                    // {
                    //     sample_requirement_wise.data[row][7] = '';
                    //     sample_requirement_wise.data[row][8] = '';
                    // }
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) {
                if(col == 2)
                {
                    po_id = val;   
                    if(data.data[row][13] == 1 || data.data[row][13] == "1") {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 3)
                {
                    combo = val;   
                    if(data.data[row][13] == 1 || data.data[row][13] == "1") {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 4)
                {
                    component = val;   
                    if(data.data[row][13] == 1 || data.data[row][13] == "1") {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 5)
                {
                    // nVal = $(cell).text();
                    if(component != '' && combo != '') {
                        let poColor = data.poColor;
                        let obj = poColor.find(o => o.component_id === component && o.combo_id === combo);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                         //colval = obj.name;
                    }
                }
                
                if(col == 6)
                {
                    // nVal = $(cell).text();
                    if(component != '' && combo != '') {
                        let specCode = data.specCode;
                        let obj = specCode.find(o => o.po_enq_id === po_id && o.combo_id === combo && o.component_id === component  && o.colour_id === colval );
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                    }
                   
                }
                if(col == 7) {
                    if(data.data[row][13] == 1 || data.data[row][13] == "1") {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 8) {
                    if(data.data[row][13] == 1 || data.data[row][13] == "1") {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                
                if(col == 9) {
                    if(data.data[row][13] == 1 || data.data[row][13] == "1") {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                
                if(col == 10) {
                    if(data.data[row][13] == 1 || data.data[row][13] == "1") {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                
                if(col == 11) {
                    if(data.data[row][13] == 1 || data.data[row][13] == "1") {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                
                
            },
        };

        var sam_requirementDetails = new Vue({
            el: '#sample_submissionDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, sample_requirement_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_oe_sample_requirement(data);
                },
            }
        });
    
        $('#oe_submitSampleDetails').click(function () {

            let validate_data = sam_requirementDetails.getData();
            let validate_field = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
            let optional_validation_field = [];
            // let validatedErrorCount = validateForm(validate_field, validate_data, 'no', optional_validation_field);
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        sam_requirementDetails.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
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

    function update_oe_sample_requirement(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateSampleDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_sampling();
                getSampleReqDetails();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // ********** SAMPLE REQUIREMENT DETAILS ENDS HERE  *********** //

    // ********** SAMPLE DISPATCH DETAILS ENDS HERE  *********** //

    // function get_sample_dispatch_details() {
    //     let data = [];
    //     append_sample_dispatch(data)
    // }

    // function append_sample_dispatch(data) {
    //     $('#sample_dispatchDetails').html('');
    //     let sample_dispatch_wise = {
    //         data: [],
    //         columns: [
    //             { title: 'P.O. No. /\nEnq. Ref. No.', width: '8%', align: 'center', readOnly: true },
    //             { title: 'Combo / Colour', width: '8%', align: 'center', readOnly: true },
    //             { title: 'Component', width: '8%', align: 'center', readOnly: true },
    //             { title: 'Reqirement Sent', width: '8%', align: 'center', readOnly: true },
    //             { title: 'Assigned Sample \n Reference No.', width: '8%', align: 'center', readOnly: true },
    //             { title: 'Sample Despatch \n Airway Bill No.', width: '8%', align: 'center' },
    //             { type: 'calendar', title: 'Airway Bill \n Date & Time', width: '7%', align: 'center' },
    //             { type: 'dropdown', title: 'Delivery Status', width: '7%', source: ['PENDING', 'DELIVERED', 'LOST IN TRANS.', 'OTHERS'], align: 'center' },
    //             { type: 'calendar', title: 'Delivery Date \n (Tracker ID.)', width: '7%', align: 'center' },
    //             { type: 'dropdown', title: 'Approval \n Status', width: '7%', source: ['PENDING', 'APPROVED', 'APP. (AMEND.)', 'REVISED SAMPLE', 'DROPPED'], align: 'center' },
    //             { type: 'dropdown', title: 'Approved By', width: '7%', source: ['BUYER', 'LIASON OFFICE', 'BUYING OFFICE', 'OTHERS'], align: 'center' },
    //             { type: 'calendar', title: 'Approval Received \n Date & Time', width: '7%', align: 'center' }
    //         ],
    //         minDimensions: [4, 3],
    //         allowDeleteColumn: false,
    //         allowInsertRow: true,
    //         allowInsertColumn: false,
    //     };

    //     var sam_requirementDetails = new Vue({
    //         el: '#sample_dispatchDetails',
    //         mounted: function () {
    //             let spreadsheet = jexcel(this.$el, sample_dispatch_wise);
    //             Object.assign(this, spreadsheet);
    //         },
    //         methods: {
    //             submitData: function () {
    //                 let data = this.getData();
    //                 // update_oe_sample_requirement(data);
    //             },
    //         }
    //     });
    // }

    // ********** SAMPLE DISPATCH DETAILS ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // SAMPLING ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // EMBELLISHMENT PROGRAMME STARTS HERE 
    // ***********************************************************************************************************************************
    
    // ********** EMBELLISHMENT DETAILS STARTS HERE  *********** //

    function get_embellishment_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getEmbellishmentDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_embellishment_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_embellishment_details(data) {
        $('#embellishmentDetails').html('');
        let dd = [], updatedRow = '', index = '', nVal = '';
        let embellishment_wise = {
            data: data.data,
            columns: [
                { title:'mode', width:'10%',align:'center',type:'hidden'},
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'dropdown', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', source: data.poEnqRefNo},
                { type: 'dropdown', title: 'Combo', width: '8%', align: 'left', source: data.poCombo, filter: comboFilter},
                { type: 'dropdown', title: 'Component', width: '8%', align: 'left', source: data.poComponent, filter: componentFilter},
                { type: 'dropdown', title: 'Colour', width: '8%', align: 'left', source: data.poColor, filter: colorFilter },
                // { type: 'dropdown', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', source: data.specCode, filter: specFilter, readOnly: true},
                { title: 'Artwork Name /\n Code', width: '8%', align: 'left'},
                { title: 'Type', width: '7%', align: 'left', type: 'dropdown', source: data.type, multiple: true},
                { title: 'Medium / Material', width: '7%', align: 'left', type: 'dropdown', source: data.type_medium, multiple: true},
                { title: 'Grading Details \n(%)', width: '7%', align: 'center' },
                { title: 'Size Group', width: '7%', align: 'center' },
                { title: 'Approval Status', width: '8%', align: 'left', type: 'dropdown', source: ['PENDING', 'APPROVED', 'APP. (AMEND.)', 'REVISED SAMPLE', 'DROPPED']},
                { title: 'Approved By', width: '7%', align: 'left', type: 'dropdown', source: ['Buyer', 'Buyers Agent', 'Liason office', 'Buying Office', 'Third Party'] },
                { title: 'Approved Date', width: '7%', align: 'center', type: 'calendar' },
                { title: 'App. Sample /\n Strike Off Ref. No.', width: '7%', align: 'left' }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            // onchange: function(instance, cell, col, row, val, label, cellName) {
            //     if(col == 5) 
            //     {
            //         updatedRow = row;
            //         txt = $(cell).text();
            //         dd = embellishment_wise.columns[6]['source'];
            //         let po_no = embellishment_wise.data[row][2];
            //         let combo = embellishment_wise.data[row][3];
            //         let component = embellishment_wise.data[row][4];
            //         if(txt != '')
            //         {
            //             index = dd.findIndex(function (val) {
            //                 if (val.combo_id == combo && val.po_enq_id == po_no && val.component_id == component && val.colour_id == txt ) return true;
            //             });
            //             console.log(index)
            //             console.log(dd)
            //             // embellishment_wise.data[row][6] = dd[index]['name'];
            //         }
            //         else
            //         {
            //             embellishment_wise.data[row][5] = '';
            //             embellishment_wise.data[row][6] = '';
            //         }
            //     }
            // },
            // updateTable: function(instance, cell, col, row, val, label, cellName, des_city) {
            //     if(col == 5)
            //     {
            //         nVal = $(cell).text();
            //     }
            //     if(col == 6) 
            //     {
            //         if(nVal != '' && dd.length > 0 && row == updatedRow)
            //         {
            //             $(cell).text(dd[index]['name']);
            //             instance.jexcel.options.data[row][col] = dd[index]['name'];
            //         }
            //         else if(nVal == '')
            //         {
            //             $(cell).text('');
            //         }
            //     }
            // },
        };

        var embellishmentDetails = new Vue({
            el: '#embellishmentDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, embellishment_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_oe_embellishment(data);
                },
            }
        });
    
        $('#oe_submitEmbellishmentDetails').click(function () {

            let validate_data = embellishmentDetails.getData();
            let validate_field = [2, 3, 4, 5, 6, 7, 8, 9, 10];
            let optional_validation_field = [];
            // let validatedErrorCount = validateForm(validate_field, validate_data, 'no', optional_validation_field);
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        embellishmentDetails.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
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

    function update_oe_embellishment(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateEmbellishmentDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_embellishment();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // ********** EMBELLISHMENT DETAILS ENDS HERE  *********** //
    
    // ********** EMBELLISHMENT APPROVAL DETAILS STARTS HERE  *********** //

    function get_embellishment_status_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getEmbellishmentStatusDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_embellishment_status_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_embellishment_status_details(data) {
        $('#embellishmentStatusDetails').html('');
        let dd = [], updatedRow = '', index = '';
        let embellishment_status_wise = {
            data: data.data,
            columns: [
                { title:'mode', width:'8%',align:'center',type:'hidden', readOnly: true},
                { title:'id', width:'8%',align:'center',type:'hidden', readOnly: true},
                { title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true},
                { title: 'Combo', width: '8%', align: 'left', readOnly: true},
                { title: 'Component', width: '8%', align: 'left', readOnly: true},
                { title: 'Colour', width: '8%', align: 'left', readOnly: true},
                { title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true},
                { title: 'Artwork Name /\n Code', width: '8%', align: 'left', readOnly: true},
                { title: 'Approval Status', width: '8%', align: 'left', type: 'dropdown', source: ['PENDING', 'APPROVED', 'APP. (AMEND.)', 'REVISED SAMPLE', 'DROPPED']},
                { title: 'Approved By', width: '7%', align: 'left', type: 'dropdown', source: ['Buyer', 'Buyers Agent', 'Liason office', 'Buying Office', 'Third Party'] },
                { title: 'Approved Date', width: '7%', align: 'center', type: 'calendar' },
                { title: 'App. Sample /\n Strike Off Ref. No.', width: '7%', align: 'left' }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) {
                if(col == 8) 
                {
                    if(val == '')
                    {
                        $(cell).text('PENDING');
                        instance.jexcel.options.data[row][col] = 'PENDING';
                    }                    
                }
                if(col == 9) 
                {
                    prevCol = col-1;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 10) 
                {
                    prevCol = col-2;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 11) 
                {
                    prevCol = col-3;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
            },
        };

        var embellishmentStatusDetails = new Vue({
            el: '#embellishmentStatusDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, embellishment_status_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_oe_embellishment_status(data);
                },
            }
        });
    
        $('#oe_submitEmbellishmentStatusDetails').click(function () {

            let validate_data = embellishmentStatusDetails.getData();
            let validate_field = [8];
            let optional_validation_field = [];
            // let validatedErrorCount = validateForm(validate_field, validate_data, 'no', optional_validation_field);
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        embellishmentStatusDetails.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
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

    function update_oe_embellishment_status(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateEmbellishmentStatusDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_embellishment();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // ********** EMBELLISHMENT APPROVAL DETAILS ENDS HERE  *********** //
     
    // ********** EMBELLISHMENT VENDOR DETAILS STARTS HERE  *********** //

    function get_embellishment_vendor_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getEmbellishmentVendorDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_embellishment_vendor_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_embellishment_vendor_details(data) {
        $('#embellishmentVendorDetails').html('');
        let dd = [], updatedRow = '', index = '';
        let embellishment_vendor_wise = {
            data: data.data,
            columns: [
                { title:'mode', width:'8%',align:'center',type:'hidden', readOnly: true},
                { title:'id', width:'8%',align:'center',type:'hidden', readOnly: true},
                { title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', type:'hidden'},
                { title: 'Combo', width: '8%', align: 'left', readOnly: true},
                { title: 'Component', width: '8%', align: 'left', readOnly: true},
                { title: 'Colour', width: '8%', align: 'left', readOnly: true},
                { title: 'Artwork Name /\n Code', width: '8%', align: 'left', readOnly: true},
                { title: 'Approved Sample /\n Strike Off Ref. No.', width: '8%', align: 'left', readOnly: true},
                { title: 'Vendor\n Name & Address', width: '7%', align: 'left', type: 'dropdown', source: data.embellishmentVendor },
                { title: 'Contact Person, e-mail\n ID & Mobile No.', width: '10%', align: 'left', readOnly: true },
                { title: 'Quotation\n Ref. No. / Date', width: '10%', align: 'left' },
                { title: 'Quotation\n Approved By', width: '7%', align: 'left', type: 'dropdown', source: ['Management', ' Gen. Manager', 'Fac. Manager', 'Merch. Head', 'Merchant'] },
                { title: 'Job Scheduled\n Date & Time', width: '7%', align: 'center', type: 'calendar' },
                { title: 'Expected Job \n Completion Date', width: '7%', align: 'center', type: 'calendar' }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 8) 
                {
                    updatedRow = row;
                    txt = $(cell).text();
                    dd = embellishment_vendor_wise.columns[8]['source'];
                    if(txt != '')
                    {
                        index = dd.findIndex(data => txt.includes( data.name ));
                        embellishment_vendor_wise.data[row][9] = dd[index]['contactpersonname']+', '+dd[index]['emailid']+', '+dd[index]['mobile'];
                    }
                    else
                    {
                        embellishment_vendor_wise.data[row][8] = '';
                        embellishment_vendor_wise.data[row][9] = '';
                    }
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) {
                if(col == 8)
                {
                    val = $(cell).text();
                }
                if(col == 9) 
                {
                    if(val != '' && dd.length > 0 && row == updatedRow)
                    {
                        $(cell).text(dd[index]['contactpersonname']+', '+dd[index]['emailid']+', '+dd[index]['mobile']);
                    }
                    else if(val == '')
                    {
                        $(cell).text('');
                    }
                }
            },
        };

        var embellishmentVendorDetails = new Vue({
            el: '#embellishmentVendorDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, embellishment_vendor_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_oe_embellishment_vendor(data);
                },
            }
        });
    
        $('#oe_submitEmbellishmentVendorDetails').click(function () {
            
            let validate_data = embellishmentVendorDetails.getData();
            let validate_field = [8];            
            let optional_validation_field = [];
            // let validatedErrorCount = validateForm(validate_field, validate_data, 'no', optional_validation_field);
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        embellishmentVendorDetails.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
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

    function update_oe_embellishment_vendor(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateEmbellishmentVendorDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_embellishment();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // ********** EMBELLISHMENT VENDOR DETAILS ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // EMBELLISHMENT PROGRAMME ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // BOM ARTICLE ONE PROGRAMME STARTS HERE 
    // ***********************************************************************************************************************************

    // ********** SAMPLE APPROVAL STARTS HERE  *********** //

    function get_bom_sampling_approval_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getBOMSamplingApprovalDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_bom_sampling_approval_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_bom_sampling_approval_details(data) {
        $('#samplingApprovalDetails').html('');
        let dd = [], updatedRow = '', index = '';
        let bom_sampling_details_wise = {
            data: data.data,
            columns: [
                { title: 'mode', width:'0%',align:'center',type:'hidden'},
                { title: 'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Item Description', 'width': '8%', align: 'left', type: 'dropdown', source: data.materialData},
                { title: 'Blend (%)', 'width': '5%', align: 'left', type: 'dropdown', source: data.blendSource },
                { title: 'Content', 'width': '7%', align: 'left', type: 'dropdown', source: data.contentSource},
                { title: 'Material', 'width': '6%', align: 'left', type: 'dropdown', source: data.materialSource},
                { title: 'Garment\n Size', 'width': '4%', align: 'center', type: 'dropdown', source: data.sizeData},
                // { title: 'Category', align: 'center', type: 'dropdown', source: [{ id: '1', name: 'In-Line' }, { id: '2', name: 'New' }, { id: '3', name: 'Revised' }]},
                // { title: 'Is BOM Appl. Needed', align: 'center', type: 'dropdown', source: [{ id: '1', name: 'Yes' }, { id:'2', name:'No' }]},
                { title: 'Sample Submission.\n Planned Date', 'width': '6%', align: 'center', type: 'calendar'},
                { title: 'Sample Submission.\n Actual Date', 'width': '6%', align: 'center', type: 'calendar' },
                { title: 'Approval Status', 'width': '7%', align: 'left', type: 'dropdown', source: [{ id: '1', name: 'PENDING' }, { id: '2', name: 'APPROVED' }, { id: '3', name: 'APP. (AMEND)' }, { id: '4', name: 'REVISED SAMPLE' }, { id: '5', name: 'DROPPED' }]},
                { title: 'Approved\n Item Code', 'width': '7%', align: 'left' },
                { title: 'Approved Item\n Colour Code', 'width': '7%', align: 'left'},
                { title: 'Size / Dim.\n (L*W*H)', 'width': '6%',align: 'center' },
                { title: 'UOM', 'width': '5%', align: 'left', type: 'calendar', type: 'dropdown', source: data.UOMDetails },
                { title: 'Approved By', 'width': '7%', align: 'left', type: 'calendar', type: 'dropdown', source: ['Buyer', 'Liason Office', 'Buying Office', 'Others'] },
                { title: 'Approval Received\n Date & Time', 'width': '5%', align: 'center', type: 'calendar' },
                // { title: 'Despatch\n For Appl.', 'width': '4%', align: 'center', type: 'checkbox' }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
            tableWidth: "120%",
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) { 
                if(col == 9) 
                {
                    if(val == '')
                    {
                        $(cell).text('PENDING');
                        instance.jexcel.options.data[row][col] = 'PENDING';
                    }                    
                }
                if(col == 10) 
                {
                    prevCol = col-1;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 11) 
                {
                    prevCol = col-2;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 12) 
                {
                    prevCol = col-3;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 13) 
                {
                    prevCol = col-4;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 14) 
                {
                    prevCol = col-5;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 15) 
                {
                    prevCol = col-6;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                    cell.classList.add('cornerdp');   
                }
                if(col == 16) 
                {
                    prevCol = col-7;
                    if(instance.jexcel.options.data[row][prevCol] == '2') {
                        cell.classList.add('readonly');
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
            }
        };

        var bomSamplingApprovalDetails = new Vue({
            el: '#samplingApprovalDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom_sampling_details_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom_sampling_approval_details(data);
                },
            }
        });
    
        $('#oe_submitBOMSampleApprovalDetails').click(function () {
            // swalWithBootstrapButtons.fire({
            //     title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            // }).then(function (result) {
            //     if (result.value) {
            //         bomSamplingApprovalDetails.submitData();
            //         fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    
            //         swalWithBootstrapButtons.fire({
            //             title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
            //         });
            //     } else if (result.dismiss === Swal.DismissReason.cancel) {
            //         swalWithBootstrapButtons.fire({
            //             title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
            //         });
            //     }
            // });
            let validate_data = bomSamplingApprovalDetails.getData();
            // let validate_field = [11];
            // let optional_validation_field = [12, 13, 14, 15, 16, 17];
            // let pendingField = 11;
            // let statusCheck = "yes";
            let validate_field = [2,3,4,5,6];
            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        bomSamplingApprovalDetails.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
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

    function update_bom_sampling_approval_details(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateBOMSamplingApprovalDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_bom_artcle_1();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // **********  SAMPLE APPROVAL ENDS HERE  *********** //
    
     // ********** BOM 1 REQUIREMENT STARTS HERE  *********** //

     function blendFilter(instance, cell, c, r, source) {
        alert(source);
        let item_name = instance.jexcel.getValueFromCoords(c - 1, r);
        console.log(item_name);
        if (item_name !== "") {
            return source.filter(function (item) {
                if (item.item_name == item_name) return true;
            })
        } else {
            return [];
        }
    }
    
    function contentFilter(instance, cell, c, r, source) {
        let blend_name = instance.jexcel.getValueFromCoords(c - 1, r);
        let item_name = instance.jexcel.getValueFromCoords(c - 2, r);
        if (blend_name !== "") {
            return source.filter(function (item) {
                if (item.blend_name == blend_name && item.item_name == item_name) return true;
            })
        } else {
            return [];
        }
    }
    
    function materialFilter(instance, cell, c, r, source) {
        let content_name = instance.jexcel.getValueFromCoords(c - 1, r);
        let blend_name = instance.jexcel.getValueFromCoords(c - 2, r);
        let item_name = instance.jexcel.getValueFromCoords(c - 3, r);
        if (content_name !== "") {
            return source.filter(function (item) {
                if (item.blend_name == blend_name && item.item_name == item_name && item.content_name == content_name) return true;
            })
        } else {
            return [];
        }
    }
    
    function sizeFilter(instance, cell, c, r, source) {
        let material_name = instance.jexcel.getValueFromCoords(c - 1, r);
        let content_name = instance.jexcel.getValueFromCoords(c - 2, r);
        let blend_name = instance.jexcel.getValueFromCoords(c - 3, r);
        let item_name = instance.jexcel.getValueFromCoords(c - 4, r);
        if (material_name !== "") {
            return source.filter(function (item) {
                if (item.blend_name == blend_name && item.item_name == item_name && item.content_name == content_name
                    && item.material_name == material_name ) 
                return true;
            })
        } else {
            return [];
        }
    }
    
    function appr_item_Filter(instance, cell, c, r, source) {
        let garment_size_name = instance.jexcel.getValueFromCoords(c - 1, r);
        let material_name = instance.jexcel.getValueFromCoords(c - 2, r);
        let content_name = instance.jexcel.getValueFromCoords(c - 3, r);
        let blend_name = instance.jexcel.getValueFromCoords(c - 4, r);
        let item_name = instance.jexcel.getValueFromCoords(c - 5, r);
        if (garment_size_name !== "") {
            return source.filter(function (item) {
                if (item.blend_name == blend_name && item.item_name == item_name && item.content_name == content_name
                    && item.material_name == material_name && item.garment_size_name == garment_size_name ) 
                return true;
            })
        } else {
            return [];
        }
    }
    
    function appr_item_color_Filter(instance, cell, c, r, source) {
        let appr_item_code = instance.jexcel.getValueFromCoords(c - 1, r);
        let garment_size_name = instance.jexcel.getValueFromCoords(c - 2, r);
        let material_name = instance.jexcel.getValueFromCoords(c - 3, r);
        let content_name = instance.jexcel.getValueFromCoords(c - 4, r);
        let blend_name = instance.jexcel.getValueFromCoords(c - 5, r);
        let item_name = instance.jexcel.getValueFromCoords(c - 6, r);
        if (appr_item_code !== "") {
            return source.filter(function (item) {
                if (item.blend_name == blend_name && item.item_name == item_name && item.content_name == content_name
                    && item.material_name == material_name && item.garment_size_name == garment_size_name && item.appr_item_code == appr_item_code) 
                return true;
            })
        } else {
            return [];
        }
    }

    function size_dim_Filter(instance, cell, c, r, source) {
        let appr_item_colour_code = instance.jexcel.getValueFromCoords(c - 1, r);
        let appr_item_code = instance.jexcel.getValueFromCoords(c - 2, r);
        let garment_size_name = instance.jexcel.getValueFromCoords(c - 3, r);
        let material_name = instance.jexcel.getValueFromCoords(c - 4, r);
        let content_name = instance.jexcel.getValueFromCoords(c - 5, r);
        let blend_name = instance.jexcel.getValueFromCoords(c - 6, r);
        let item_name = instance.jexcel.getValueFromCoords(c - 7, r);
        if (appr_item_colour_code !== "") {
            return source.filter(function (item) {
                if (item.blend_name == blend_name && item.item_name == item_name && item.content_name == content_name
                    && item.material_name == material_name && item.garment_size_name == garment_size_name && item.appr_item_code == appr_item_code
                    && item.appr_item_colour_code == appr_item_colour_code) 
                return true;
            })
        } else {
            return [];
        }
    }

    function uom_Filter(instance, cell, c, r, source) {
        let size_dim = instance.jexcel.getValueFromCoords(c - 1, r);
        let appr_item_colour_code = instance.jexcel.getValueFromCoords(c - 2, r);
        let appr_item_code = instance.jexcel.getValueFromCoords(c - 3, r);
        let garment_size_name = instance.jexcel.getValueFromCoords(c - 4, r);
        let material_name = instance.jexcel.getValueFromCoords(c - 5, r);
        let content_name = instance.jexcel.getValueFromCoords(c - 6, r);
        let blend_name = instance.jexcel.getValueFromCoords(c - 7, r);
        let item_name = instance.jexcel.getValueFromCoords(c - 8, r);

        if (size_dim !== "") {
            return source.filter(function (item) {
                if (item.blend_name == blend_name && item.item_name == item_name && item.content_name == content_name
                    && item.material_name == material_name && item.garment_size_name == garment_size_name && item.appr_item_code == appr_item_code
                    && item.appr_item_colour_code == appr_item_colour_code && item.size_dim == size_dim) 
                return true;
            })
        } else {
            return [];
        }
    }


    function get_bom_requirement_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getBOM1RequirementDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_bom_requirement_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_bom_requirement_details(data) {
        $('#BOM1requirementDetails').html('');
        let dd = data.appendData.calculatedAmmount, updatedRow = "", inupdatedRow = "", filterValue = "", itemQty = "";
        //data.blendSource
        alert(JSON.stringify(data.blendSource));
        
        let uom = "";
        let bom_intake = "";
        let changeStatus = false;
        let bom1_requirement_details = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'dropdown',title: 'P.O. No. /\nEnq. Ref. No.',width: 130,align: 'left',source: data.appendData.poEnqRefNo,},
                { type: 'dropdown', title: 'Combo', width: 130, align: 'left',source: data.appendData.poCombo, filter: comboFilter,},
                { type: 'dropdown', title: 'Component',width: 130,align: 'left',source: data.appendData.poComponent, filter: componentFilter,},
                { type: 'dropdown', title: 'Colour', width: 130, align: 'left', source: data.appendData.poColor, filter: colorFilter, },
                { type: 'dropdown', title: 'Size Spec \n Code / Fit', width: 130, align: 'left', source: data.appendData.specCode, filter: specFilter, },
                { title: 'Item Description', width: 140, align: 'left', type: 'dropdown', source: data.itemSource},
                { title: 'Blend (%)', width: 110, align: 'center', type: 'dropdown', source: data.blendSource, filter: blendFilter },
                { title: 'Content', width: 130, align: 'left', type: 'dropdown', source: data.contentSource, filter: contentFilter},
                { title: 'Material', width: 120, align: 'left', type: 'dropdown', source: data.materialSource, filter: materialFilter},
                { title: 'Garment\n Size', width: 110, align: 'center', type: 'dropdown', source: data.garmentsizeSource, filter: sizeFilter },
                { title: 'Approved\n Item Code', width: 130, align: 'left', type: 'dropdown', source: data.apprItemCodeSource, filter: appr_item_Filter },
                { title: 'Approved Item\n Colour Code', width: 130, align: 'left', type: 'dropdown', source: data.apprItemColourCodeSource, filter: appr_item_color_Filter },
                { title: 'Size / Dim.\n (L*W*H)', width: 120, align: 'center', type: 'dropdown', source: data.sizeDimSource, filter: size_dim_Filter },
                { title: 'UOM', width: 110, align: 'left', type: 'dropdown', source: data.uomSource, filter: uom_Filter },
                { title: 'Itemized Qty.\n (Pcs.)', width: 120, align: 'right', readOnly:true },
                { title: 'BOM\n Intake', width: '4%', align: 'center', decimal:',' },
                { title: 'Required\n BOM Qty.', width: 120, align: 'right' },
                { type: 'dropdown', title: 'UOM', width: 110, align: 'left', source: data.UOMDetails }
            ],

            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
            tableWidth: "120%",

            
            onchange: function(instance, cell, col, row, val, label, cellName) {
                // if(col == 15) 
                // {
                //     changeStatus = true;
                //     updatedRow = row;
                //     txt = $(cell).text();
                //     if(txt != '')
                //     {
                //         let po_ref = bom1_requirement_details.data[row][2];
                //         let combo = bom1_requirement_details.data[row][3];
                //         let component = bom1_requirement_details.data[row][4];
                //         let colour = bom1_requirement_details.data[row][5];
                //         let spec = bom1_requirement_details.data[row][6];
                //         filterValue = dd.findIndex(function (item) {
                //             if (item.po_ref == po_ref && item.combo == combo && item.component == component &&
                //                 item.colour == colour && item.spec == spec ) return true;
                //         })
                //         itemQty = dd[filterValue].amount;
                //         bom1_requirement_details.data[row][16] = dd[filterValue].amount;
                //     }
                //     else
                //     {
                //         bom1_requirement_details.data[row][16] = "";
                //     }
                // }
                // if(col == 17) {
                //     inupdatedRow = row;
                // }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 15)
                {
                    uom = $(cell).text();
                }
                if(col == 16) 
                {
                    // itemQty = $(cell).text();
                    // if(uom != '' && dd.length == 1 && row == updatedRow)
                    // {
                    if(uom != '')
                    {
                        let po_ref = bom1_requirement_details.data[row][2];
                        let combo = bom1_requirement_details.data[row][3];
                        let component = bom1_requirement_details.data[row][4];
                        let colour = bom1_requirement_details.data[row][5];
                        let spec = bom1_requirement_details.data[row][6];
                        filterValue = dd.findIndex(function (item) {
                            if (item.po_ref == po_ref && item.combo == combo && item.component == component &&
                                item.colour == colour && item.spec == spec ) return true;
                        })
                        itemQty = dd[filterValue].amount;
                        $(cell).text(itemQty);
                        instance.jexcel.options.data[row][col] = itemQty;
                    }
                    else if(uom == '')
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    // else
                    // {
                    //     $(cell).text(dd[filterValue].amount);
                    //     instance.jexcel.options.data[row][col] = dd[filterValue].amount;
                    // }
                }
                if(col == 17) {
                    // console.log(dd.length)
                    
                    // if(uom == '' && row == updatedRow)
                    if(uom == '')
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    // else if(uom != '' && row == updatedRow && row != inupdatedRow)
                    // {
                    //     $(cell).text('0.00000');
                    //     instance.jexcel.options.data[row][col] = txtValue;
                    //     bom_intake = txtValue;  
                    // }
                    // else if(row == inupdatedRow)
                    else if(uom != '')
                    {
                        var da = $(cell).text();
                        txtValue = Number(da).toFixed(5);
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        bom_intake = txtValue;
                    }
                }
                if(col == 18) {
                    // if(dd.length != 1 && inupdatedRow != "" && row == inupdatedRow)
                    // {
                    //     val = $(cell).text();
                    //     reqQty = parseInt(val) * parseFloat(bom_intake);
                    //     reqQty = Math.round(reqQty);
                    //     $(cell).text(reqQty);
                    //     instance.jexcel.options.data[row][col] = reqQty;
                    // }
                    // else if(uom == '' && row == updatedRow)
                    // {
                    //     $(cell).text('');
                    //     instance.jexcel.options.data[row][col] = '';
                    // }
                    // else if(uom == '' && row == updatedRow && row != inupdatedRow)
                    // {
                    //     $(cell).text('');
                    //     instance.jexcel.options.data[row][col] = '';
                    //     bom_intake = txtValue;  
                    // }
                    // else if(uom != '' && inupdatedRow != "" && row == inupdatedRow)
                    // {
                        if(uom != '' && bom_intake != '')
                        {
                            if(itemQty == "") {itemQty = 0}
                            if(bom_intake == "") {bom_intake = 0}
                            reqQty = parseInt(itemQty) * parseFloat(bom_intake);
                            reqQty = Math.round(reqQty);
                            $(cell).text(reqQty);
                            instance.jexcel.options.data[row][col] = reqQty;
                        }
                        else
                        {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                        }
                    // }
                    // else {
                    //     reqQty = parseInt($(cell).text()) * parseFloat(bom_intake);
                    //     $(cell).text(reqQty);
                    //     instance.jexcel.options.data[row][col] = reqQty;
                    // }
                }
            },
        };

        var bom1requirementDetails = new Vue({
            el: '#BOM1requirementDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_requirement_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom_requirement_details(data);
                },
            }
        });
    
        $('#bom1RequirementSubmit').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    bom1requirementDetails.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    // 
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

    function update_bom_requirement_details(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateBOM1RequirementDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_bom_artcle_1();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // **********  BOM 1 REQUIREMENT DETAILS ENDS HERE  *********** //

     // **********  BOM 1 REQUIREMENT CONSOLIDATED STARTS HERE  *********** //

     function get_bom_requirement_Consolidated_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getBOM1ConsolidatedReq',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_bom_requirement_consolidated_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
     }

    function append_bom_requirement_consolidated_details(data) {
        $('#bom1requirementQtyConsolidated').html('');
        let dd = [], updatedRow = '', filterValue = [];
        let bom_intake = "", excess_qty="", plan_bom="";
        let bom1_requirement_qty_consolidated_wise = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'bom_id', width:'0%',align:'center',type:'hidden'},
                { title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                // { title: 'Blend (%) / Content / Material', width: '7%', align: 'center', readOnly: true },
                { title: 'Blend (%)', width: '6%', align: 'left', readOnly: true},
                { title: 'Content', width: '7%', align: 'left', readOnly: true},
                { title: 'Material', width: '6%', align: 'left', readOnly: true},
                { title: 'Garment\n Size', width: '5%', align: 'left', readOnly: true },
                { title: 'Approved\n Item Code', width: '7%', align: 'left', readOnly: true },
                { title: 'Approved Item\n Colour Code', width: '7%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'left', readOnly: true },
                { title: 'Consolidated. Reqd. \n BOM Qty.', width: '7%', align: 'right', readOnly: true },
                { title: 'Excess Qty.\n (%)', width: '7%', align: 'center' },
                { title: 'Planned\n BOM Qty.', width: '7%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'left', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            tableOverflow: true,
            tableWidth: "120%",
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) {
                if(col == 6)
                {
                    val = $(cell).text();
                }
                if(col == 12) {
                    txtValue = numeral(val).format('0.00');
                    bom_intake = txtValue;
                }
                if(col == 13) {
                    txtValue = numeral(val).format('0');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    excess_qty = txtValue;
                }
                if(col == 14) {
                    //plan_bom = parseFloat(bom_intake) * parseFloat(excess_qty);
                    plan_bom = ((parseFloat(bom_intake) * parseFloat(excess_qty)) / 100) + parseFloat(bom_intake);
                    txtValue = numeral(plan_bom).format('0');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    excess_qty = txtValue;
                }
            },
        };
    
        var bom1_requirement_consld_vm = new Vue({
            el: '#bom1requirementQtyConsolidated',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_requirement_qty_consolidated_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom_req_consld(data);
                },
            }
        });
    
        $('#bom1RequirementConsolidatedSubmit').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    bom1_requirement_consld_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    // _call_to_bom_artcle_1();
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

    function update_bom_req_consld(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateBom1ReqConsolidated',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // **********  BOM 1 REQUIREMENT CONSOLIDATED ENDS HERE  *********** //
     
    // **********  BOM 1 SOURCING STARTS HERE  *********** //

    function get_bom1_sourcing_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getBOM1Sourcing',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_bom1_sourcing_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_bom1_sourcing_details(data) {
        $('#bom1_sourcingDetails').html('');
        let dd = [], updatedRow = '', filterValue = [];
        let bom_intake = "", excess_qty="", plan_bom="";
        let bom1_sourcing_details_wise = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'left',type:'hidden'},
                { title:'id', width:'0%',align:'left',type:'hidden'},
                { title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { title: 'Approved \n Item Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Approved \n Item Colour Code', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Sourcing Advice', width: '7%', align: 'left', source: data.sourceData, },
                { type: 'dropdown', title: 'Vendor Location', width: '7%', align: 'left', source: ['Local', 'Within State', 'Within Country', 'Overseas'], },
                { type: 'dropdown', title: 'Vendor Name & \n Address', width: '7%', align: 'left', source:data.bomVendor },
                { title: 'Contact Person / e-mail ID / Phone / Mobile', width: '7%', align: 'left', readOnly: true },
                { title: 'GST / IE Code Details', width: '7%', align: 'left', readOnly: true },
                { title: 'If On-line Ordering System\n Website / User ID / Password', width: '10%', align: 'left' },
                { type: 'calendar', title: 'Password Expiry \n Date & Time', width: '7%', align: 'center' }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            // tableOverflow: true,
            // tableWidth: "120%",
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 7) 
                {
                    // updatedRow = row;
                    // txt = $(cell).text();
                    // dd = bom1_sourcing_details_wise.columns[7]['source'];
                    // console.log(dd)
                    // if(txt != '')
                    // {
                    //     index = dd.findIndex(data => txt.includes( data.name ));
                    //     bom1_sourcing_details_wise.data[row][8] = dd[index]['contactpersonname']+' / '+dd[index]['emailid']+' / '+dd[index]['phone']+' / '+dd[index]['mobile'];
                    //     bom1_sourcing_details_wise.data[row][9] = dd[index]['gstno']+' / '+dd[index]['iecode'];
                    // }
                    // else
                    // {
                    //     bom1_sourcing_details_wise.data[row][8] = '';
                    //     bom1_sourcing_details_wise.data[row][9] = '';
                    // }
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) {
                if(col == 1)
                {
                    insertid = val;
                }
                if(col == 5) {
                    if(insertid != '') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                } 
                if(col == 6) {
                    if(insertid != '') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 7)
                {
                    vendor_name = val;
                    if(insertid != '') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 8) {
                    if(vendor_name != '') {
                        let bom_email = data.bom_email;
                        let obj = bom_email.find(o => o.item_id === vendor_name);
                        //console.log(obj);
                        $(cell).text(obj.name);
                        instance.jexcel.options.data[row][col] = obj.name;
                    }   
                    
                }
                if(col == 9) {
                    if(vendor_name != '') {
                        let bom_gst = data.bom_gst;
                        let obj = bom_gst.find(o => o.item_id === vendor_name);
                        $(cell).text(obj.name);
                        instance.jexcel.options.data[row][col] = obj.name;
                    }
                }
                // if(col == 6)
                // {
                //     if(val != '' && dd.length > 0 && row == updatedRow)
                //     {
                //         $(cell).text(dd[index]['contactpersonname']+' / '+dd[index]['emailid']+' / '+dd[index]['phone']+' / '+dd[index]['mobile']);
                //     }
                //     else if(val == '')
                //     {
                //         $(cell).text('');
                //     }
                // }
                // if(col == 7) 
                // {
                //     if(val != '' && dd.length > 0 && row == updatedRow)
                //     {
                //         $(cell).text(dd[index]['gstno']+' / '+dd[index]['iecode']);
                //     }
                //     else if(val == '')
                //     {
                //         $(cell).text('');
                //     }
                // }
                // if(col == 9) 
                // {      
                //     cell.classList.add('cornerdp');           
                // }
            },
        };
    
         bom1_sourcing_vm = new Vue({
            el: '#bom1_sourcingDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_sourcing_details_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    // let validateField = [5,6,7];
                    // let validatedErrorCount = validateSourceForm(validateField, data);
                    // console.log(validatedErrorCount);
                    // if(validatedErrorCount > 0) {
                    //     swalWithBootstrapButtons.fire(
                    //     alertMessageFunction('validation_error'))
                    // } else {
                    //     update_bom1_sourcing_vm(data);
                    // }
                    
                    update_bom1_sourcing_vm(data);
                },
            }
        });
    

    
        $('#bom1SourceDetailsSubmit').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    let data = bom1_sourcing_vm.getData();
                    let validateField = [5,6,7];
                    let validatedErrorCount = validateSourceForm(validateField, data);
                    if(validatedErrorCount > 0) {
                        swalWithBootstrapButtons.fire(
                        alertMessageFunction('validation_error'))
                    } else {
                        bom1_sourcing_vm.submitData();
                        fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                        _call_to_bom_artcle_1();
                        swalWithBootstrapButtons.fire({
                            title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                        });
                    }
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }
    
    
    
    function validateSourceForm(validateField, dataValue) {
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
    
    
    // $('#bom1SourceDetailsSubmit').click(function () {
    //         swalWithBootstrapButtons.fire({
    //             title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
    //         }).then(function (result) {
    //             if (result.value) {
    //                 updateSourceFunction();
    //             } else if (result.dismiss === Swal.DismissReason.cancel) {
    //                 swalWithBootstrapButtons.fire({
    //                     title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
    //                 });
    //             }
    //         });
    //     });
        
    // function updateSourceFunction()
    // {
    //     let dataform = new FormData();
    //     let sourcing_data = bom1_sourcing_vm.getData();
    //     dataform.append('data', JSON.stringify(sourcing_data));
    //     dataform.append('enquiry_id', enquiry_id);
    //     let request = $.ajax({
    //         type: "POST",
    //         url: base_path + 'WorkInProcess/updateBom1Sourcing',
    //         data: dataform,
    //         processData: false,
    //         contentType: false,
    //         cache: false,
    //         success: function (data) {
    //             window.location.href = base_path + 'WorkInProcess/index/' + encodeURIComponent(btoa(enquiry_id));
    //         },
    //         error: function () {
    //             console.log("Error");
    //         }
    //     });
    // }

    function update_bom1_sourcing_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateBom1Sourcing',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                window.location.href = base_path + 'WorkInProcess/index/' + encodeURIComponent(btoa(enquiry_id));
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // **********  BOM 1 SOURCING ENDS HERE  *********** //


    // ********** BOM 1 SAMPLING DESPATCH & DELIVERY STATUS STATS HERE  *********** //

    function get_bom1_sampling_despatch() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/get_bom1_sampling_despatch',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_get_bom1_sampling_despatch(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_get_bom1_sampling_despatch(data) {
        $('#bom1samplingdespatch').html('');
        let dd = [], updatedRow = '', index = '';
        let bom1_sampling_despatch = {
            data: data.data,
            columns: [
                { title: 'mode', width:'0%',align:'center',type:'hidden'},
                { title: 'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Item Description', align: 'center', type: 'dropdown', 'readOnly': true, source: data.materialData},
                { title: 'Blend (%)', align: 'center', type: 'dropdown', 'readOnly': true, source: data.blendSource },
                { title: 'Content', align: 'center', type: 'dropdown', 'readOnly': true, source: data.contentSource},
                { title: 'Material', align: 'center', type: 'dropdown', 'readOnly': true, source: data.materialSource},
                { title: 'Garment Size', align: 'center', type: 'dropdown', 'readOnly': true, source: data.sizeData},
                { title: 'Assigned \n Sample Ref. No', align: 'center', type: 'text'},
                { title: 'Vendor Name', align: 'center', type: 'dropdown', source: data.bomVendor},
                { title: 'Sample Despatch \n Airway Bill No.', align: 'center', type: 'text'},
                { title: 'Airway Bill\n Date & Time', align: 'center', type: 'calendar'},
                { title: 'Delivery Status', align: 'center', type: 'text'},
                { title: 'Delivery Date & Time\n(Tracker ID).', align: 'center', type: 'calendar'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };

        var bom1_sampling_despatch_vm = new Vue({
            el: '#bom1samplingdespatch',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_sampling_despatch);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom1_sampling_despatch(data);
                },
            }
        });
    
        $('#bom1sampling_despatch-btn').click(function () {

            let validate_data = bom1_sampling_despatch_vm.getData();
            let validate_field = [2,3,4,5,6,7,8,9,10,11,12];
            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        bom1_sampling_despatch_vm.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
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

    function update_bom1_sampling_despatch(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/update_bom1_sampling_despatch',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_bom_artcle_1();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // **********  BOM 1 SAMPLING DESPATCH & DELIVERY STATUS ENDS HERE  *********** //


    // *********************************************************************************************************************************** 
    // BOM ARTICLE ONE PROGRAMME ENDS HERE 
    // ***********************************************************************************************************************************
    

    // *********************************************************************************************************************************** 
    // BOM ARTICLE TWO PROGRAMME STARTS HERE 
    // ***********************************************************************************************************************************

    // ********** SAMPLE APPROVAL STARTS HERE  *********** //
    function get_bom2_sampling_approval_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getBOM2SamplingApprovalDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_bom2_sampling_approval_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_bom2_sampling_approval_details(data) {
        $('#samplingApprovalDetails2').html('');
        let dd = [], updatedRow = '', index = '';
        let bom_sampling_details_wise = {
            data: data.data,
            columns: [
                { title: 'mode', width:'0%',align:'center',type:'hidden'},
                { title: 'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Item Description', 'width': '8%', align: 'left', type: 'dropdown', source: data.materialData},
                { title: 'Blend (%)', 'width': '5%', align: 'left', type: 'dropdown', source: data.blendSource },
                { title: 'Content', 'width': '7%', align: 'left', type: 'dropdown', source: data.contentSource},
                { title: 'Material', 'width': '6%', align: 'left', type: 'dropdown', source: data.materialSource},
                { title: 'Garment\n Size', 'width': '4%', align: 'center', type: 'dropdown', source: data.sizeData},
                // { title: 'Category', align: 'center', type: 'dropdown', source: [{ id: '1', name: 'In-Line' }, { id: '2', name: 'New' }, { id: '3', name: 'Revised' }]},
                // { title: 'Is BOM Appl. Needed', align: 'center', type: 'dropdown', source: [{ id: '1', name: 'Yes' }, { id:'2', name:'No' }]},
                { title: 'Sample Submission.\n Planned Date', 'width': '5%', align: 'center', type: 'calendar'},
                { title: 'Sample Submission.\n Actual Date', 'width': '5%', align: 'center', type: 'calendar' },
                { title: 'Approval Status', 'width': '7%', align: 'left', type: 'dropdown', source: [{ id: '1', name: 'PENDING' }, { id: '2', name: 'APPROVED' }, { id: '3', name: 'APP. (AMEND)' }, { id: '4', name: 'REVISED SAMPLE' }, { id: '5', name: 'DROPPED' }]},
                { title: 'Approved\n Item Code', 'width': '7%', align: 'left' },
                { title: 'Approved Item\n Colour Code', 'width': '7%', align: 'left'},
                { title: 'Size / Dim.\n (L*W*H)', 'width': '6%',align: 'center' },
                { title: 'UOM', 'width': '5%', align: 'left', type: 'calendar', type: 'dropdown', source: data.UOMDetails },
                { title: 'Approved By', 'width': '7%', align: 'center', type: 'calendar', type: 'dropdown', source: ['Buyer', 'Liason Office', 'Buying Office', 'Others'] },
                { title: 'Appl. Recd.\n Date & Time', 'width': '5%', align: 'left', type: 'calendar' },
                // { title: 'Despatch\n For Appl.', 'width': '4%', align: 'center', type: 'checkbox' }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
            tableWidth: "120%",
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) { 
                if(col == 9) 
                {
                    if(val == '')
                    {
                        $(cell).text('PENDING');
                        instance.jexcel.options.data[row][col] = 'PENDING';
                    }                    
                }
                if(col == 10) 
                {
                    prevCol = col-1;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING' || instance.jexcel.options.data[row][prevCol] == '1') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 11) 
                {
                    prevCol = col-2;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING' || instance.jexcel.options.data[row][prevCol] == '1') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 12) 
                {
                    prevCol = col-3;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING' || instance.jexcel.options.data[row][prevCol] == '1') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 13) 
                {
                    prevCol = col-4;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING' || instance.jexcel.options.data[row][prevCol] == '1') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 14) 
                {
                    prevCol = col-5;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING' || instance.jexcel.options.data[row][prevCol] == '1') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 15) 
                {
                    prevCol = col-6;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING' || instance.jexcel.options.data[row][prevCol] == '1') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 16) 
                {
                    prevCol = col-7;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING' || instance.jexcel.options.data[row][prevCol] == '1') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
                if(col == 17) 
                {
                    prevCol = col-8;
                    if(instance.jexcel.options.data[row][prevCol] == 'PENDING' || instance.jexcel.options.data[row][prevCol] == '1') {
                        cell.classList.add('readonly');
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                    cell.classList.add('cornerdp');   
                }
                if(col == 18) 
                {
                    prevCol = col-9;
                    if(instance.jexcel.options.data[row][prevCol] == '2') {
                        cell.classList.add('readonly');
                    } else {
                        cell.classList.remove('readonly');
                    }                    
                }
            }
        };

        var bomSamplingApprovalDetails = new Vue({
            el: '#samplingApprovalDetails2',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom_sampling_details_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom2_sampling_approval_details(data);
                },
            }
        });
    
        $('#oe_submitBOMSampleApprovalDetails2').click(function () {
            // swalWithBootstrapButtons.fire({
            //     title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            // }).then(function (result) {
            //     if (result.value) {
            //         bomSamplingApprovalDetails.submitData();
            //         fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    
            //         swalWithBootstrapButtons.fire({
            //             title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
            //         });
            //     } else if (result.dismiss === Swal.DismissReason.cancel) {
            //         swalWithBootstrapButtons.fire({
            //             title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
            //         });
            //     }
            // });
            let validate_data = bomSamplingApprovalDetails.getData();
            // let validate_field = [11];
            // let optional_validation_field = [12, 13, 14, 15, 16, 17];
            // let pendingField = 11;
            // let statusCheck = "yes";
            let validate_field = [2,3,4,5,6];
            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        bomSamplingApprovalDetails.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
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

    function update_bom2_sampling_approval_details(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateBOM2SamplingApprovalDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_bom_artcle_2();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // ********** BOM 2 SAMPLE APPROVAL ENDS HERE  *********** //

     // ********** BOM 2 REQUIREMENT STARTS HERE  *********** //

     function get_bom2_requirement_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getBOM2RequirementDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_bom2_requirement_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_bom2_requirement_details(data) {
        $('#BOM2requirementDetails').html('');
        let dd = data.appendData.calculatedAmmount, updatedRow = "", inupdatedRow = "", filterValue = "", itemQty = "";
        let uom = "";
        let bom_intake = "";
        let changeStatus = false;
        let bom2_requirement_details = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'dropdown',title: 'P.O. No. /\nEnq. Ref. No.',width: 130,align: 'left',source: data.appendData.poEnqRefNo,},
                { type: 'dropdown', title: 'Combo', width: 130, align: 'left',source: data.appendData.poCombo, filter: comboFilter,},
                { type: 'dropdown', title: 'Component',width: 130,align: 'left',source: data.appendData.poComponent, filter: componentFilter,},
                { type: 'dropdown', title: 'Colour', width: 130, align: 'left', source: data.appendData.poColor, filter: colorFilter, },
                { type: 'dropdown', title: 'Size Spec \n Code / Fit', width: 130, align: 'left', source: data.appendData.specCode, filter: specFilter, },
                { title: 'Item Description', width: 140, align: 'left', type: 'dropdown', source: data.itemSource},
                { title: 'Blend (%)', width: 110, align: 'center', type: 'dropdown', source: data.blendSource, filter: blendFilter },
                { title: 'Content', width: 130, align: 'left', type: 'dropdown', source: data.contentSource, filter: contentFilter},
                { title: 'Material', width: 120, align: 'left', type: 'dropdown', source: data.materialSource, filter: materialFilter},
                { title: 'Garment\n Size', width: 110, align: 'center', type: 'dropdown', source: data.garmentsizeSource, filter: sizeFilter },
                { title: 'Approved\n Item Code', width: 130, align: 'left', type: 'dropdown', source: data.apprItemCodeSource, filter: appr_item_Filter },
                { title: 'Approved Item\n Colour Code', width: 130, align: 'left', type: 'dropdown', source: data.apprItemColourCodeSource, filter: appr_item_color_Filter },
                { title: 'Size / Dim.\n (L*W*H)', width: 120, align: 'center', type: 'dropdown', source: data.sizeDimSource, filter: size_dim_Filter },
                { title: 'UOM', width: 110, align: 'left', type: 'dropdown', source: data.uomSource, filter: uom_Filter },
                { title: 'Itemized Qty.\n (Pcs.)', width: 120, align: 'right', readOnly:true },
                { title: 'BOM\n Intake', width: '4%', align: 'center', decimal:',' },
                { title: 'Reqd.\n BOM Qty.', width: 120, align: 'right' },
                { type: 'dropdown', title: 'UOM', width: 110, align: 'left', source: data.UOMDetails }
            ],

            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
            tableWidth: "120%",

            
            onchange: function(instance, cell, col, row, val, label, cellName) {
                // if(col == 15) 
                // {
                //     changeStatus = true;
                //     updatedRow = row;
                //     txt = $(cell).text();
                //     if(txt != '')
                //     {
                //         let po_ref = bom1_requirement_details.data[row][2];
                //         let combo = bom1_requirement_details.data[row][3];
                //         let component = bom1_requirement_details.data[row][4];
                //         let colour = bom1_requirement_details.data[row][5];
                //         let spec = bom1_requirement_details.data[row][6];
                //         filterValue = dd.findIndex(function (item) {
                //             if (item.po_ref == po_ref && item.combo == combo && item.component == component &&
                //                 item.colour == colour && item.spec == spec ) return true;
                //         })
                //         itemQty = dd[filterValue].amount;
                //         bom1_requirement_details.data[row][16] = dd[filterValue].amount;
                //     }
                //     else
                //     {
                //         bom1_requirement_details.data[row][16] = "";
                //     }
                // }
                // if(col == 17) {
                //     inupdatedRow = row;
                // }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 15)
                {
                    uom = $(cell).text();
                }
                if(col == 16) 
                {
                    // itemQty = $(cell).text();
                    // if(uom != '' && dd.length == 1 && row == updatedRow)
                    // {
                    if(uom != '')
                    {
                        let po_ref = bom2_requirement_details.data[row][2];
                        let combo = bom2_requirement_details.data[row][3];
                        let component = bom2_requirement_details.data[row][4];
                        let colour = bom2_requirement_details.data[row][5];
                        let spec = bom2_requirement_details.data[row][6];
                        filterValue = dd.findIndex(function (item) {
                            if (item.po_ref == po_ref && item.combo == combo && item.component == component &&
                                item.colour == colour && item.spec == spec ) return true;
                        })
                        itemQty = dd[filterValue].amount;
                        $(cell).text(itemQty);
                        instance.jexcel.options.data[row][col] = itemQty;
                    }
                    else if(uom == '')
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    // else
                    // {
                    //     $(cell).text(dd[filterValue].amount);
                    //     instance.jexcel.options.data[row][col] = dd[filterValue].amount;
                    // }
                }
                if(col == 17) {
                    // console.log(dd.length)
                    
                    // if(uom == '' && row == updatedRow)
                    if(uom == '')
                    {
                        $(cell).text('');
                        instance.jexcel.options.data[row][col] = '';
                    }
                    // else if(uom != '' && row == updatedRow && row != inupdatedRow)
                    // {
                    //     $(cell).text('0.00000');
                    //     instance.jexcel.options.data[row][col] = txtValue;
                    //     bom_intake = txtValue;  
                    // }
                    // else if(row == inupdatedRow)
                    else if(uom != '')
                    {
                        var da = $(cell).text();
                        txtValue = Number(da).toFixed(5);
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        bom_intake = txtValue;
                    }
                }
                if(col == 18) {
                    // if(dd.length != 1 && inupdatedRow != "" && row == inupdatedRow)
                    // {
                    //     val = $(cell).text();
                    //     reqQty = parseInt(val) * parseFloat(bom_intake);
                    //     reqQty = Math.round(reqQty);
                    //     $(cell).text(reqQty);
                    //     instance.jexcel.options.data[row][col] = reqQty;
                    // }
                    // else if(uom == '' && row == updatedRow)
                    // {
                    //     $(cell).text('');
                    //     instance.jexcel.options.data[row][col] = '';
                    // }
                    // else if(uom == '' && row == updatedRow && row != inupdatedRow)
                    // {
                    //     $(cell).text('');
                    //     instance.jexcel.options.data[row][col] = '';
                    //     bom_intake = txtValue;  
                    // }
                    // else if(uom != '' && inupdatedRow != "" && row == inupdatedRow)
                    // {
                        if(uom != '' && bom_intake != '')
                        {
                            if(itemQty == "") {itemQty = 0}
                            if(bom_intake == "") {bom_intake = 0}
                            reqQty = parseInt(itemQty) * parseFloat(bom_intake);
                            reqQty = Math.round(reqQty);
                            $(cell).text(reqQty);
                            instance.jexcel.options.data[row][col] = reqQty;
                        }
                        else
                        {
                            $(cell).text('');
                            instance.jexcel.options.data[row][col] = '';
                        }
                    // }
                    // else {
                    //     reqQty = parseInt($(cell).text()) * parseFloat(bom_intake);
                    //     $(cell).text(reqQty);
                    //     instance.jexcel.options.data[row][col] = reqQty;
                    // }
                }
            },
        };

        var bom2requirementDetails = new Vue({
            el: '#BOM2requirementDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom2_requirement_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom2_requirement_details(data);
                },
            }
        });
    
        $('#bom2RequirementSubmit').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    bom2requirementDetails.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    // 
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

    function update_bom2_requirement_details(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateBOM2RequirementDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_bom_artcle_2();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // **********  BOM 2 REQUIREMENT DETAILS ENDS HERE  *********** //

     // **********  BOM 2 REQUIREMENT CONSOLIDATED STARTS HERE  *********** //

    function get_bom2_requirement_Consolidated_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getBOM2ConsolidatedReq',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_bom2_requirement_consolidated_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_bom2_requirement_consolidated_details(data) {
        $('#bom2requirementQtyConsolidated').html('');
        let dd = [], updatedRow = '', filterValue = [];
        let bom_intake = "", excess_qty="", plan_bom="";
        let bom1_requirement_qty_consolidated_wise = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'bom_id', width:'0%',align:'center',type:'hidden'},
                { title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                // { title: 'Blend (%) / Content / Material', width: '7%', align: 'center', readOnly: true },
                { title: 'Blend (%)', width: '6%', align: 'left', readOnly: true},
                { title: 'Content', width: '7%', align: 'left', readOnly: true},
                { title: 'Material', width: '6%', align: 'left', readOnly: true},
                { title: 'Garment\n Size', width: '5%', align: 'left', readOnly: true },
                { title: 'Approved\n Item Code', width: '7%', align: 'left', readOnly: true },
                { title: 'Approved Item\n Colour Code', width: '7%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'left', readOnly: true },
                { title: 'Consl. Reqd. \n BOM Qty.', width: '7%', align: 'right', readOnly: true },
                { title: 'Excess Qty.\n (%)', width: '7%', align: 'center' },
                { title: 'Planned\n BOM Qty.', width: '7%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'left', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            tableOverflow: true,
            tableWidth: "120%",
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) {
                if(col == 6)
                {
                    val = $(cell).text();
                }
                if(col == 12) {
                    txtValue = numeral(val).format('0.00');
                    bom_intake = txtValue;
                }
                if(col == 13) {
                    txtValue = numeral(val).format('0');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    excess_qty = txtValue;
                }
                if(col == 14) {
                    //plan_bom = parseFloat(bom_intake) * parseFloat(excess_qty);
                    plan_bom = ((parseFloat(bom_intake) * parseFloat(excess_qty)) / 100) + parseFloat(bom_intake);
                    txtValue = numeral(plan_bom).format('0');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                    excess_qty = txtValue;
                }
            },
        };
    
        var bom1_requirement_consld_vm = new Vue({
            el: '#bom2requirementQtyConsolidated',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_requirement_qty_consolidated_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom2_req_consld(data);
                },
            }
        });
    
        $('#bom2RequirementConsolidatedSubmit').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    bom1_requirement_consld_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    _call_to_bom_artcle_2();
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

    function update_bom2_req_consld(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateBom2ReqConsolidated',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // **********  BOM 2 REQUIREMENT CONSOLIDATED ENDS HERE  *********** //
     
    // **********  BOM 2 SOURCING STARTS HERE  *********** //

    function get_bom2_sourcing_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getBOM2Sourcing',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_bom2_sourcing_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_bom2_sourcing_details(data) {
        $('#bom2_sourcingDetails').html('');
        let dd = [], updatedRow = '', filterValue = [];
        let bom_intake = "", excess_qty="", plan_bom="";
        let bom1_sourcing_details_wise = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'left',type:'hidden'},
                { title:'id', width:'0%',align:'left',type:'hidden'},
                { title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Sourcing Advice', width: '7%', align: 'left', source: data.sourceData, },
                { type: 'dropdown', title: 'Vendor Location', width: '7%', align: 'left', source: ['Local', 'Within State', 'Within Country', 'Overseas'], },
                { type: 'dropdown', title: 'Vendor Name & \n Address', width: '7%', align: 'left', source: data.bomVendor, },
                { title: 'Contact Person / e-mail ID / Phone / Mobile', width: '7%', align: 'left', readOnly: true },
                { title: 'GST / IE Code Details', width: '7%', align: 'left', readOnly: true },
                { title: 'If On-line Ordering System\n Website / User ID / Password', width: '10%', align: 'left' },
                { type: 'calendar', title: 'Password Expiry \n Date & Time', width: '7%', align: 'center' }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            // tableOverflow: true,
            // tableWidth: "120%",
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 5) 
                {
                    updatedRow = row;
                    txt = $(cell).text();
                    dd = bom1_sourcing_details_wise.columns[5]['source'];
                    console.log(dd)
                    if(txt != '')
                    {
                        index = dd.findIndex(data => txt.includes( data.name ));
                        bom1_sourcing_details_wise.data[row][6] = dd[index]['contactpersonname']+' / '+dd[index]['emailid']+' / '+dd[index]['phone']+' / '+dd[index]['mobile'];
                        bom1_sourcing_details_wise.data[row][7] = dd[index]['gstno']+' / '+dd[index]['iecode'];
                    }
                    else
                    {
                        bom1_sourcing_details_wise.data[row][6] = '';
                        bom1_sourcing_details_wise.data[row][7] = '';
                    }
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName, des_city) {
                if(col == 3)
                {
                    val = $(cell).text();
                }
                if(col == 6) 
                {
                    if(val != '' && dd.length > 0 && row == updatedRow)
                    {
                        $(cell).text(dd[index]['contactpersonname']+' / '+dd[index]['emailid']+' / '+dd[index]['phone']+' / '+dd[index]['mobile']);
                    }
                    else if(val == '')
                    {
                        $(cell).text('');
                    }
                }
                if(col == 7) 
                {
                    if(val != '' && dd.length > 0 && row == updatedRow)
                    {
                        $(cell).text(dd[index]['gstno']+' / '+dd[index]['iecode']);
                    }
                    else if(val == '')
                    {
                        $(cell).text('');
                    }
                }
                if(col == 9) 
                {      
                    cell.classList.add('cornerdp');           
                }
            },
        };
    
        var bom1_sourcing_vm = new Vue({
            el: '#bom2_sourcingDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_sourcing_details_wise);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom2_sourcing_vm(data);
                },
            }
        });
    

    
        $('#bom2SourceDetailsSubmit').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    bom1_sourcing_vm.submitData();
                    fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    _call_to_bom_artcle_2();
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

    function update_bom2_sourcing_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateBom2Sourcing',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // **********  BOM 2 SOURCING ENDS HERE  *********** //

    // ********** BOM 1 SAMPLING DESPATCH & DELIVERY STATUS STATS HERE  *********** //

    function get_bom2_sampling_despatch() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/get_bom2_sampling_despatch',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_get_bom2_sampling_despatch(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_get_bom2_sampling_despatch(data) {
        $('#bom2samplingdespatch').html('');
        let dd = [], updatedRow = '', index = '';
        let bom1_sampling_despatch = {
            data: data.data,
            columns: [
                { title: 'mode', width:'0%',align:'center',type:'hidden'},
                { title: 'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Item Description', align: 'center', type: 'dropdown', 'readOnly': true, source: data.materialData},
                { title: 'Blend (%)', align: 'center', type: 'dropdown', 'readOnly': true, source: data.blendSource },
                { title: 'Content', align: 'center', type: 'dropdown', 'readOnly': true, source: data.contentSource},
                { title: 'Material', align: 'center', type: 'dropdown', 'readOnly': true, source: data.materialSource},
                { title: 'Garment Size', align: 'center', type: 'dropdown', 'readOnly': true, source: data.sizeData},
                { title: 'Assigned \n Sample Ref. No', align: 'center', type: 'text'},
                { title: 'Vendor Name', align: 'center', type: 'dropdown', source: data.bomVendor},
                { title: 'Sample Despatch \n Airway Bill No.', align: 'center', type: 'text'},
                { title: 'Airway Bill\n Date & Time', align: 'center', type: 'calendar'},
                { title: 'Delivery Status', align: 'center', type: 'text'},
                { title: 'Delivery Date & Time\n(Tracker ID).', align: 'center', type: 'calendar'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };

        var bom1_sampling_despatch_vm = new Vue({
            el: '#bom2samplingdespatch',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_sampling_despatch);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom2_sampling_despatch(data);
                },
            }
        });

        $('#bom2sampling_despatch-btn').click(function () {

            let validate_data = bom1_sampling_despatch_vm.getData();
            let validate_field = [2,3,4,5,6,7,8,9,10,11,12];
            let optional_validation_field = [];
            let pendingField = "";
            let statusCheck = "no";
            let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

            if(validatedErrorCount == 0)
            {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        bom1_sampling_despatch_vm.submitData();
                        cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
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

    function update_bom2_sampling_despatch(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/update_bom2_sampling_despatch',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_bom_artcle_2();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
            },
            error: function () {
                console.log("Error");
            }
        });
    }

        // **********  BOM 1 SAMPLING DESPATCH & DELIVERY STATUS ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // BOM ARTICLE TWO PROGRAMME ENDS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    // MANAGEMENT / MERCHANT CHECK LIST STARTS HERE 
    // ***********************************************************************************************************************************
     
     // **********  MANAGEMENT CHECK LIST STARTS HERE  *********** //

     function get_manag_checklist_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getManagementChecklistDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_manag_checklist_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_manag_checklist_details(data) {
        $('#mag_check_list_details').html('');
        let mag_general_check_list = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'Description', width: '30%', align: 'left', readOnly: true },
                { title: 'N.A.', width: '4%', align: 'center', type: 'checkbox'},
                { title: 'N.R.', width: '4%', align: 'center', type: 'checkbox'},
                { title: 'P.R.', width: '4%', align: 'center', type: 'checkbox'},
                { title: 'Recd.', width: '4%', align: 'center', type: 'checkbox',},
                { title: 'Request for\n Missing Details', width: '7%', align: 'center', type: 'checkbox' },
                { title: 'Request Sent\n Date & Time', width: '7%', align: 'center', type: 'calendar', readOnly: true },
                { title: 'Details Received\n Date & Time', width: '7%', align: 'center', type: 'calendar', readOnly: true },
                { title: 'Recent Update', width: '7%', align: 'center', type: 'calendar', readOnly: true },
                { title: 'Remarks', width: '8%', align: 'left' }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var mag_general_check_list_vm = new Vue({
            el: '#mag_check_list_details',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, mag_general_check_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    mag_check_list_details_vm(data);
                },
            }
        });
    
        $('#management_check_list_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    mag_general_check_list_vm.submitData();
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

    function mag_check_list_details_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateManagementCheckList',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_checklist();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // **********  MANAGEMENT CHECK LIST ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // MANAGEMENT / MERCHANT CHECK LIST ENDS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    // PACKING STARTS HERE 
    // ***********************************************************************************************************************************

    function getPacking_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getPackingDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                setTimeout(() => {
                    append_packing_details(resData);
                }, 500);
                
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_packing_details(data_packing) {
        
        let data = [];
        let packData = data_packing.packingDetails;
        for(let i=0; i < packData.length; i++) {
            data = packData[i];
            let packing_id = data.pck_id;
            let assortment_type= data.assortment_type;
        
            if(assortment_type == "1" || assortment_type == "2" || assortment_type == "3" || assortment_type == "4") {
                // assort_type 1 and 3
                let num_of_item = 0;
                let total_item = 0;
                let prevVal = 0;

                // assort_type 2 and 4
                let total_item_2 = 0;
                $('#packingTables'+packing_id).html('');
                let options = {
                    data: data.data,
                    columns: data.column,
                    minDimensions: [data.column.length, 1],
                    allowDeleteColumn: false,
                    allowInsertRow: true,
                    allowInsertColumn: false,
                    updateTable: function(instance, cell, col, row, val, label, cellName) {
                        let tablelength = instance.jexcel.options.columns.length;
                        if(assortment_type == '1' || assortment_type == '3')
                        {   
                            if(col == 0) {
                                num_of_item = 0;
                            }
                            if (col >= 3 && col < tablelength - 3 ) 
                            {
                                let a = instance.jexcel.options.data[row][col];
                                if(a == '') { 
                                    a = 0;
                                }
                                num_of_item += parseInt(a);
                            }
                            if(col == tablelength - 3) {
                                instance.jexcel.options.data[row][col] = num_of_item;
                                $(cell).text(num_of_item);
                            }
                            if(col == tablelength - 2) {
                                prevVal = instance.jexcel.options.data[row][col];
                            }
                            if(col == tablelength - 1) 
                            {   
                                if(prevVal == '') { 
                                    prevVal = 0;
                                }
                                total_item = parseInt(num_of_item) * parseInt(prevVal);
                                instance.jexcel.options.data[row][col] = total_item;
                                $(cell).text(total_item);
                            }
                        }
                        else if(assortment_type == '2' || assortment_type == '4')
                        {   
                            if(col == 0) {
                                total_item_2 = 0;
                            }
                            if (col >= 3 && col < tablelength - 1 ) 
                            {
                                let a = instance.jexcel.options.data[row][col];
                                if(a == '') { 
                                    a = 0;
                                }
                                total_item_2 += parseInt(a);
                            }
                            
                            if(col == tablelength - 1) 
                            {   
                                instance.jexcel.options.data[row][col] = total_item_2;
                                $(cell).text(total_item_2);
                            }
                        }
                        
                    },
                };

                let k = new Vue({
                    el: '#packingTables'+packing_id,
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                            let pck_combo_color_id = 0 // ** dummy variable for assort 1 to 4 **
                            updatePackingDetails(data, assortment_type, packing_id, pck_combo_color_id);
                        },
                    }
                });

                $('#savePck'+packing_id +'_btn').click(function (){
                    //k.submitData();

                    if(assortment_type == "1" || assortment_type == "2" ) 
                    {
                        swalWithBootstrapButtons.fire(
                            // *** CONFIRMATION MESSAGE *** //
                            alertMessageFunction('confirmation_save')
                        ).then(function (result) {
                            if (result.value) {
                                k.submitData();
                                cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                // *** CANCELLED MESSAGE *** //
                                swalWithBootstrapButtons.fire(
                                    alertMessageFunction('cancelled')
                                );
                            }
                        });
                    }
                    else {
                        let validate_field = [];
                        let validate_data = k.getData();
                        for(let m=0; m < validate_data.length; m++) {
                            let f_row = validate_data[m];
                            for(let n=0; n < f_row.length; n++) {
                                if(n>=2) {
                                    validate_field.push(n);
                                }
                            }
                        }
                        let optional_validation_field = [];
                        let pendingField = "";
                        let statusCheck = "no";
                        let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);

                        if(validatedErrorCount == 0)
                        {
                            swalWithBootstrapButtons.fire(
                                // *** CONFIRMATION MESSAGE *** //
                                alertMessageFunction('confirmation_save')
                            ).then(function (result) {
                                if (result.value) {
                                    k.submitData();
                                    cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    // *** CANCELLED MESSAGE *** //
                                    swalWithBootstrapButtons.fire(
                                        alertMessageFunction('cancelled')
                                    );
                                }
                            });
                        }
                        else {
                            // *** VALIDATION ERROR MESSAGE *** //
                            swalWithBootstrapButtons.fire(
                                alertMessageFunction('validation_error')
                            )
                        }
                    }
                });
                
            }
            else {
                
                // *** assort type 7 ***
                let num_of_item = 0;
                let total_item = 0;
                let prevVal = 0;
                
                // *** assort type 7 ***
                let num_of_item_8 = 0;

                dataUniqueTable = data.packingTableData;
                for(let j=0; j < dataUniqueTable.length; j++) {
                    data = dataUniqueTable[j];

                    let assort_idd = 'aa'+data.details.pck_id + data.details.enquiry_id + data.details.pck_combo_color_id;
                    $('#packingTables'+assort_idd).html('');
                    let options = {
                        data: data.data,
                        columns: data.column,
                        minDimensions: [data.column.length, 1],
                        allowDeleteColumn: false,
                        allowInsertRow: false,
                        allowInsertColumn: false,
                        footers: footer('packing_assort_'+assortment_type, data.column.length),
                        updateTable: function(instance, cell, col, row, val, label, cellName) {
                            let table_data = instance.jexcel.options.data;
                            let table_column = instance.jexcel.options.columns;
                            if(assortment_type == '5') {
                                if(row == table_data.length - 3 && col >= 3) {
                                    // console.log(row, col)
                                    let calVall = 0;
                                    for(let k=0; k < table_data.length; k++) {
                                        let tbRow = table_data[k];
                                        if(k < table_data.length - 3) {
                                            if(tbRow[col] == "") {
                                                tbRow[col] = 0;
                                            }
                                            calVall += parseInt(tbRow[col]);
                                        }
                                    }
                                    instance.jexcel.options.data[row][col] = calVall;
                                    $(cell).text(calVall);
                                    cell.classList.add('readonly');
                                }
                                if(row == table_data.length - 1 && col >= 3) {
                                    // console.log(row, col)
                                    let calVall = 0;
                                    let a = 0;
                                    let b = 0;
                                    for(let k=0; k < table_data.length; k++) {
                                        let tbRow = table_data[k];
                                        if(k == table_data.length - 3) {
                                            a = tbRow[col];
                                        }
                                        if(k == table_data.length - 2) {
                                            b = tbRow[col];
                                        }
                                    }
                                    if(a == "") {a = 0}
                                    if(b == "") {b = 0}
                                    calVall = parseInt(a) * parseInt(b);
                                    instance.jexcel.options.data[row][col] = calVall;
                                    $(cell).text(calVall);
                                    cell.classList.add('readonly');
                                }
                            }
                            if(assortment_type == '6') {
                                if(row == table_data.length - 1 && col >= 3) {
                                    let calVall = 0;
                                    for(let k=0; k < table_data.length; k++) {
                                        let tbRow = table_data[k];
                                        if(k < table_data.length - 1) {
                                            calVall += parseInt(tbRow[col]);
                                        }
                                    }
                                    instance.jexcel.options.data[row][col] = calVall;
                                    $(cell).text(calVall);
                                    cell.classList.add('readonly');
                                }
                            }
                            if(assortment_type == '7') {
                                //  *** ROW WISE CALCULATION *** //
                                let tablelength = instance.jexcel.options.columns.length;
                                if(col == 0) {
                                    num_of_item = 0;
                                }
                                if (col >= 3 && col < tablelength - 3 ) 
                                {
                                    let a = instance.jexcel.options.data[row][col];
                                    if(a == '') { 
                                        a = 0;
                                    }
                                    num_of_item += parseInt(a);
                                }
                                if(col == tablelength - 3) {
                                    instance.jexcel.options.data[row][col] = num_of_item;
                                    $(cell).text(num_of_item);
                                }
                                if(col == tablelength - 2 ) {
                                    prevVal = instance.jexcel.options.data[row][col];
                                }
                                if(col == tablelength - 1) 
                                {   
                                    if(prevVal == '') { 
                                        prevVal = 0;
                                    }
                                    total_item = parseInt(num_of_item) * parseInt(prevVal);
                                    instance.jexcel.options.data[row][col] = total_item;
                                    $(cell).text(total_item);
                                }
                            }
                            if(assortment_type == '8') {
                                //  *** ROW WISE CALCULATION *** //
                                let tablelength = instance.jexcel.options.columns.length;
                                if(col == 0) {
                                    num_of_item_8 = 0;
                                }
                                if (col >= 3 && col < tablelength - 1 ) 
                                {
                                    let a = instance.jexcel.options.data[row][col];
                                    if(a == '') { a = 0; }
                                    num_of_item_8 += parseInt(a);
                                }
                                if(col == tablelength - 1 ) 
                                {   
                                    instance.jexcel.options.data[row][col] = num_of_item_8;
                                    $(cell).text(num_of_item_8);
                                }
                            }
                        },
                    };

                    let k = new Vue({
                        el: '#packingTables'+assort_idd,
                        mounted: function () {
                            let spreadsheet = jspreadsheet(this.$el, options);
                            Object.assign(this, spreadsheet);
                        },
                        methods: {
                            submitData: function () {
                                let data1 = this.getData();
                                let pck_id = data.details.pck_id;
                                let pck_combo_color_id = data.details.pck_combo_color_id;
                                updatePackingDetails(data1, assortment_type, pck_id, pck_combo_color_id);
                            },
                        }
                    });

                    $('#savePckAss'+assort_idd +'_btn').click(function () {
                        //k.submitData();
                        let validate_field = [];
                        let validate_data = k.getData();
                        // console.log(validate_data);
                        for(let m=0; m < validate_data.length; m++) {
                            let f_row = validate_data[m];
                            for(let n=0; n < f_row.length; n++) {
                                if(n>=3) {
                                    validate_field.push(n);
                                }
                            }
                        }

                        let optional_validation_field = [];
                        let pendingField = "";
                        let statusCheck = "no";
                        let validatedErrorCount = validateForm(validate_field, validate_data, statusCheck, optional_validation_field, pendingField);
                        validatedErrorCount = 0;
                        if(validatedErrorCount == 0)
                        {
                            swalWithBootstrapButtons.fire(
                                // *** CONFIRMATION MESSAGE *** //
                                alertMessageFunction('confirmation_save')
                            ).then(function (result) {
                                if (result.value) {
                                    k.submitData();
                                    cad = 0, fabric = 0, lab = 0, sample = 0, embellishment = 0, bom_art_1 = 0, bom_art_2 = 0, packing = 0, final_inspection = 0, documentation = 0, checklist = 0;
                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    // *** CANCELLED MESSAGE *** //
                                    swalWithBootstrapButtons.fire(
                                        alertMessageFunction('cancelled')
                                    );
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
            }

        }
    }

    function updatePackingDetails(data, assortment_type, packing_id, pck_combo_color_id) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('assortment_type', assortment_type);
        dataform.append('packing_id', packing_id);
        dataform.append('pck_combo_color_id', pck_combo_color_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updatePackingDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_packing();
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // *********************************************************************************************************************************** 
    // PACKING ENDS HERE 
    // ***********************************************************************************************************************************

    
    // *********************************************************************************************************************************** 
    // FINAL INSPECTION STARTS HERE 
    // ***********************************************************************************************************************************
     
     // **********  FINAL INSPECTION STANDARD STARTS HERE  *********** //

     function get_final_inspection_standard_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getFinalInspectionStandardDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_final_inspection_standard_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_final_inspection_standard_details(data) {
        $('#finalInspectionStandardsDetails').html('');
        
        unsaved = false;
        var LotSize = ['2 to 8', '9 to 15', '16 to 25', '26 to 50', '51 to 90', '91 to 150', '151 to 280', '281 to 500', '501 to 1200', '1201 to 3200', '3201 to 10000', '10001 to 35000',
        '35001 to 150000', '150001 to 500000', '500001 and over'];
        var gilevels = ['-', 'I', 'II', 'III','S1', 'S2', 'S3', 'S4'];
        var SampleSizeCl = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R'];
        const sscL_sammplS = ['A2', 'B3', 'C5', 'D8', 'E13', 'F20', 'G32', 'H50', 'J80', 'K125', 'L200', 'M315', 'N500', 'P800', 'Q1250', 'R2000'];
        const gi = {};
        var SampleSize = [2, 3, 5, 8, 13, 20, 32, 50, 80, 125, 200, 315, 500, 800, 1250, 2000];
        var Aql = ['0.065', '0.10', '0.15', '0.25', '0.40', '0.65', '1.0', '1.5', '2.5', '4.0', '6.5'];

        const SampleSizeCl_SampleSizeGrp = {};
    var AcceptRejNo = {};
    const AcRejObj = {};
    const aqlGroup = {};
    gi[LotSize[0] + 'I'] = 'A';
    gi[LotSize[1] + 'I'] = 'A';
    gi[LotSize[2] + 'I'] = 'B';
    gi[LotSize[3] + 'I'] = 'C';
    gi[LotSize[4] + 'I'] = 'C';
    gi[LotSize[5] + 'I'] = 'D';
    gi[LotSize[6] + 'I'] = 'E';
    gi[LotSize[7] + 'I'] = 'F';
    gi[LotSize[8] + 'I'] = 'G';
    gi[LotSize[9] + 'I'] = 'H';
    gi[LotSize[10] + 'I'] = 'J';
    gi[LotSize[11] + 'I'] = 'K';
    gi[LotSize[12] + 'I'] = 'L';
    gi[LotSize[13] + 'I'] = 'M';
    gi[LotSize[14] + 'I'] = 'N';
    gi[LotSize[0] + 'II'] = 'A';
    gi[LotSize[1] + 'II'] = 'B';
    gi[LotSize[2] + 'II'] = 'C';
    gi[LotSize[3] + 'II'] = 'D';
    gi[LotSize[4] + 'II'] = 'E';
    gi[LotSize[5] + 'II'] = 'F';
    gi[LotSize[6] + 'II'] = 'G';
    gi[LotSize[7] + 'II'] = 'H';
    gi[LotSize[8] + 'II'] = 'J';
    gi[LotSize[9] + 'II'] = 'K';
    gi[LotSize[10] + 'II'] = 'L';
    gi[LotSize[11] + 'II'] = 'M';
    gi[LotSize[12] + 'II'] = 'N';
    gi[LotSize[13] + 'II'] = 'P';
    gi[LotSize[14] + 'II'] = 'Q';
    gi[LotSize[0] + 'III'] = 'B';
    gi[LotSize[1] + 'III'] = 'C';
    gi[LotSize[2] + 'III'] = 'D';
    gi[LotSize[3] + 'III'] = 'E';
    gi[LotSize[4] + 'III'] = 'F';
    gi[LotSize[5] + 'III'] = 'G';
    gi[LotSize[6] + 'III'] = 'H';
    gi[LotSize[7] + 'III'] = 'J';
    gi[LotSize[8] + 'III'] = 'K';
    gi[LotSize[9] + 'III'] = 'L';
    gi[LotSize[10] + 'III'] = 'M';
    gi[LotSize[11] + 'III'] = 'N';
    gi[LotSize[12] + 'III'] = 'P';
    gi[LotSize[13] + 'III'] = 'Q';
    gi[LotSize[14] + 'III'] = 'R';
    gi[LotSize[0] + 'S1'] = 'A';
    gi[LotSize[1] + 'S1'] = 'A';
    gi[LotSize[2] + 'S1'] = 'A';
    gi[LotSize[3] + 'S1'] = 'A';
    gi[LotSize[4] + 'S1'] = 'B';
    gi[LotSize[5] + 'S1'] = 'B';
    gi[LotSize[6] + 'S1'] = 'B';
    gi[LotSize[7] + 'S1'] = 'B';
    gi[LotSize[8] + 'S1'] = 'C';
    gi[LotSize[9] + 'S1'] = 'C';
    gi[LotSize[10] + 'S1'] = 'C';
    gi[LotSize[11] + 'S1'] = 'C';
    gi[LotSize[12] + 'S1'] = 'D';
    gi[LotSize[13] + 'S1'] = 'D';
    gi[LotSize[14] + 'S1'] = 'D';
    gi[LotSize[0] + 'S2'] = 'A';
    gi[LotSize[1] + 'S2'] = 'A';
    gi[LotSize[2] + 'S2'] = 'A';
    gi[LotSize[3] + 'S2'] = 'B';
    gi[LotSize[4] + 'S2'] = 'B';
    gi[LotSize[5] + 'S2'] = 'B';
    gi[LotSize[6] + 'S2'] = 'C';
    gi[LotSize[7] + 'S2'] = 'C';
    gi[LotSize[8] + 'S2'] = 'C';
    gi[LotSize[9] + 'S2'] = 'D';
    gi[LotSize[10] + 'S2'] = 'D';
    gi[LotSize[11] + 'S2'] = 'D';
    gi[LotSize[12] + 'S2'] = 'E';
    gi[LotSize[13] + 'S2'] = 'E';
    gi[LotSize[14] + 'S2'] = 'E';
    gi[LotSize[0] + 'S3'] = 'A';
    gi[LotSize[1] + 'S3'] = 'A';
    gi[LotSize[2] + 'S3'] = 'B';
    gi[LotSize[3] + 'S3'] = 'B';
    gi[LotSize[4] + 'S3'] = 'C';
    gi[LotSize[5] + 'S3'] = 'C';
    gi[LotSize[6] + 'S3'] = 'D';
    gi[LotSize[7] + 'S3'] = 'D';
    gi[LotSize[8] + 'S3'] = 'E';
    gi[LotSize[9] + 'S3'] = 'E';
    gi[LotSize[10] + 'S3'] = 'F';
    gi[LotSize[11] + 'S3'] = 'F';
    gi[LotSize[12] + 'S3'] = 'G';
    gi[LotSize[13] + 'S3'] = 'G';
    gi[LotSize[14] + 'S3'] = 'H';
    gi[LotSize[0] + 'S4'] = 'A';
    gi[LotSize[1] + 'S4'] = 'A';
    gi[LotSize[2] + 'S4'] = 'B';
    gi[LotSize[3] + 'S4'] = 'C';
    gi[LotSize[4] + 'S4'] = 'C';
    gi[LotSize[5] + 'S4'] = 'D';
    gi[LotSize[6] + 'S4'] = 'E';
    gi[LotSize[7] + 'S4'] = 'E';
    gi[LotSize[8] + 'S4'] = 'F';
    gi[LotSize[9] + 'S4'] = 'G';
    gi[LotSize[10] + 'S4'] = 'G';
    gi[LotSize[11] + 'S4'] = 'H';
    gi[LotSize[12] + 'S4'] = 'J';
    gi[LotSize[13] + 'S4'] = 'J';
    gi[LotSize[14] + 'S4'] = 'K';
    SampleSizeCl_SampleSizeGrp['A'] = 2;
    SampleSizeCl_SampleSizeGrp['B'] = 3;
    SampleSizeCl_SampleSizeGrp['C'] = 5;
    SampleSizeCl_SampleSizeGrp['D'] = 8;
    SampleSizeCl_SampleSizeGrp['E'] = 13;
    SampleSizeCl_SampleSizeGrp['F'] = 20;
    SampleSizeCl_SampleSizeGrp['G'] = 32;
    SampleSizeCl_SampleSizeGrp['H'] = 50;
    SampleSizeCl_SampleSizeGrp['J'] = 80;
    SampleSizeCl_SampleSizeGrp['K'] = 125;
    SampleSizeCl_SampleSizeGrp['L'] = 200;
    SampleSizeCl_SampleSizeGrp['M'] = 315;
    SampleSizeCl_SampleSizeGrp['N'] = 500;
    SampleSizeCl_SampleSizeGrp['P'] = 800;
    SampleSizeCl_SampleSizeGrp['Q'] = 1250;
    SampleSizeCl_SampleSizeGrp['R'] = 2000;

    AcRejObj['A|#|2|#|6.5'] = '0 / 1';
    AcRejObj['B|#|3|#|4.0'] = '0 / 1';
    AcRejObj['C|#|5|#|2.5'] = '0 / 1';
    AcRejObj['D|#|8|#|1.5'] = '0 / 1';
    AcRejObj['D|#|8|#|6.5'] = '1 / 2';
    AcRejObj['E|#|13|#|1.0'] = '0 / 1';
    AcRejObj['E|#|13|#|4.0'] = '1 / 2';
    AcRejObj['E|#|13|#|6.5'] = '2 / 3';
    AcRejObj['F|#|20|#|2.5'] = '1 / 2';
    AcRejObj['F|#|20|#|4.0'] = '2 / 3';
    AcRejObj['F|#|20|#|6.5'] = '3 / 4';
    AcRejObj['G|#|32|#|1.5'] = '1 / 2';
    AcRejObj['G|#|32|#|2.5'] = '2 / 3';
    AcRejObj['G|#|32|#|4.0'] = '3 / 4';
    AcRejObj['G|#|32|#|6.5'] = '5 / 6';
    AcRejObj['H|#|50|#|1.0'] = '1 / 2';
    AcRejObj['H|#|50|#|1.5'] = '2 / 3';
    AcRejObj['H|#|50|#|2.5'] = '3 / 4';
    AcRejObj['H|#|50|#|4.0'] = '5 / 6';
    AcRejObj['H|#|50|#|6.5'] = '7 / 8';
    AcRejObj['J|#|80|#|1.0'] = '2 / 3';
    AcRejObj['J|#|80|#|1.5'] = '3 / 4';
    AcRejObj['J|#|80|#|2.5'] = '5 / 6';
    AcRejObj['J|#|80|#|4.0'] = '7 / 8';
    AcRejObj['J|#|80|#|6.5'] = '10 / 11';
    AcRejObj['K|#|125|#|1.0'] = '3 / 4';
    AcRejObj['K|#|125|#|1.5'] = '5 / 6';
    AcRejObj['K|#|125|#|2.5'] = '7 / 8';
    AcRejObj['K|#|125|#|4.0'] = '10 / 11';
    AcRejObj['K|#|125|#|6.5'] = '14 / 15';
    AcRejObj['L|#|200|#|1.0'] = '5 / 6';
    AcRejObj['L|#|200|#|1.5'] = '7 / 8';
    AcRejObj['L|#|200|#|2.5'] = '10 / 11';
    AcRejObj['L|#|200|#|4.0'] = '14 / 15';
    AcRejObj['L|#|200|#|6.5'] = '21 / 22';
    AcRejObj['M|#|315|#|1.0'] = '7 / 8';
    AcRejObj['M|#|315|#|1.5'] = '10 / 11';
    AcRejObj['M|#|315|#|2.5'] = '14 / 15';
    AcRejObj['M|#|315|#|4.0'] = '21 / 22';
    AcRejObj['N|#|500|#|1.0'] = '10 / 11';
    AcRejObj['N|#|500|#|1.5'] = '14 / 15';
    AcRejObj['N|#|500|#|2.5'] = '21 / 22';
    AcRejObj['P|#|800|#|1.0'] = '14 / 15';
    AcRejObj['P|#|800|#|1.5'] = '21 / 22';
    AcRejObj['Q|#|1250|#|1.0'] = '21 / 22';
    //
    for (let a = 0; a < sscL_sammplS.length; a++) {
        for (let b = 0; b < 11; b++) {
            if (b == 10 && a == 1) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if ((b == 9 || b == 10) && a == 2) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if ((b == 9 || b == 10) && a == 2) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if ((b == 8 || b == 9 || b == 10) && a == 3) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if ((b == 7 || b == 8 || b == 9) && a == 4) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if (b == 10 && a == 4) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if ((b == 6 || b == 7 || b == 8) && a == 5) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if (b == 9 && a == 5) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if (b == 10 && a == 5) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if ((b == 5 || b == 6 || b == 7) && a == 6) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if (b == 8 && a == 6) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if (b == 9 && a == 6) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if (b == 10 && a == 6) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if ((b == 4 || b == 5 || b == 6) && a == 7) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if (b == 7 && a == 7) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if (b == 8 && a == 7) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if (b == 9 && a == 7) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if (b == 10 && a == 7) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if ((b == 3 || b == 4 || b == 5) && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if (b == 6 && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if (b == 7 && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if (b == 8 && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if (b == 9 && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if (b == 10 && a == 8) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if ((b == 2 || b == 3 || b == 5) && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if (b == 5 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if (b == 6 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if (b == 7 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if (b == 8 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if (b == 9 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if (b == 10 && a == 9) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if ((b == 1 || b == 2 || b == 3) && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if(b == 4 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if(b == 5 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 6 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 7 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 8 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 9 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if(b == 10 && a == 10) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if((b == 0 || b == 1 || b == 2) && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if(b == 3 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if(b == 4 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 5 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 6 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 7 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 8 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if(b == 9 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if(b == 10 && a == 11) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if((b == 0 || b == 1) && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if(b == 2 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if(b == 3 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 4 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 5 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 6 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 7 && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if((b == 8 || b == 9 || b == 10) && a == 12) {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if(b == 0 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '1 / 2';
            }
            else if(b == 1 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if(b == 2 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 3 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 4 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 5 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 6 && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if((b == 7 || b == 8 || b == 9 || b == 10) && a == 13) { //P
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if(b == 0 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '2 / 3';
            }
            else if(b == 1 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 2 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 3 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 4 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 5 && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 15';
            }
            else if((b == 6 || b == 7 || b == 8 || b == 9 || b == 10) && a == 14) { //Q
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else if(b == 0 && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '3 / 4';
            }
            else if(b == 1 && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '5 / 6';
            }
            else if(b == 2 && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '7 / 8';
            }
            else if(b == 3 && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '10 / 11';
            }
            else if(b == 4 && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '14 / 25';
            }
            else if((b == 5 || b == 6 || b == 7 || b == 8 || b == 9 || b == 10) && a == 15) { //R
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '21 / 22';
            }
            else {
                AcceptRejNo[sscL_sammplS[a] + '||' + Aql[b]] = '0 / 1';
            }
        }
    }
    //console.log(AcceptRejNo, 'AcceptRejNo');
    AcRejObj['B|#|3|#|4.0'] = '0 / 1';
    AcRejObj['C|#|5|#|2.5'] = '0 / 1';
    AcRejObj['D|#|8|#|1.5'] = '0 / 1';
    AcRejObj['D|#|8|#|6.5'] = '1 / 2';
    AcRejObj['E|#|13|#|1.0'] = '0 / 1';
    AcRejObj['E|#|13|#|4.0'] = '1 / 2';
    AcRejObj['E|#|13|#|6.5'] = '2 / 3';
    AcRejObj['F|#|20|#|2.5'] = '1 / 2';
    AcRejObj['F|#|20|#|4.0'] = '2 / 3';
    AcRejObj['F|#|20|#|6.5'] = '3 / 4';
    AcRejObj['G|#|32|#|1.5'] = '1 / 2';
    AcRejObj['G|#|32|#|2.5'] = '2 / 3';
    AcRejObj['G|#|32|#|4.0'] = '3 / 4';
    AcRejObj['G|#|32|#|6.5'] = '5 / 6';
    AcRejObj['H|#|50|#|1.0'] = '1 / 2';
    AcRejObj['H|#|50|#|1.5'] = '2 / 3';
    AcRejObj['H|#|50|#|2.5'] = '3 / 4';
    AcRejObj['H|#|50|#|4.0'] = '5 / 6';
    AcRejObj['H|#|50|#|6.5'] = '7 / 8';
    AcRejObj['J|#|80|#|1.0'] = '2 / 3';
    AcRejObj['J|#|80|#|1.5'] = '3 / 4';
    AcRejObj['J|#|80|#|2.5'] = '5 / 6';
    AcRejObj['J|#|80|#|4.0'] = '7 / 8';
    AcRejObj['J|#|80|#|6.5'] = '10 / 11';
    AcRejObj['K|#|125|#|1.0'] = '3 / 4';
    AcRejObj['K|#|125|#|1.5'] = '5 / 6';
    AcRejObj['K|#|125|#|2.5'] = '7 / 8';
    AcRejObj['K|#|125|#|4.0'] = '10 / 11';
    AcRejObj['K|#|125|#|6.5'] = '14 / 15';
    AcRejObj['L|#|200|#|1.0'] = '5 / 6';
    AcRejObj['L|#|200|#|1.5'] = '7 / 8';
    AcRejObj['L|#|200|#|2.5'] = '10 / 11';
    AcRejObj['L|#|200|#|4.0'] = '14 / 15';
    AcRejObj['L|#|200|#|6.5'] = '21 / 22';
    AcRejObj['M|#|315|#|1.0'] = '7 / 8';
    AcRejObj['M|#|315|#|1.5'] = '10 / 11';
    AcRejObj['M|#|315|#|2.5'] = '14 / 15';
    AcRejObj['M|#|315|#|4.0'] = '21 / 22';
    AcRejObj['N|#|500|#|1.0'] = '10 / 11';
    AcRejObj['N|#|500|#|1.5'] = '14 / 15';
    AcRejObj['N|#|500|#|2.5'] = '21 / 22';
    AcRejObj['P|#|800|#|1.0'] = '14 / 15';
    AcRejObj['P|#|800|#|1.5'] = '21 / 22';
    AcRejObj['Q|#|1250|#|1.0'] = '21 / 22';

    var GlbApprovingauthority = ['Buyer', 'Buyer Agent', 'Buyer Office', 'Buyer Office Agent', 'Third Party'];


        let final_inspection_standard = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { title: 'P.O. / Sample\n Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'Pcs. / Set', width: '8%', align: 'center', readOnly: true },
                { title: 'Lot / Batch Size', width: '8%', align: 'center', readOnly: true },
                { title: 'General / Special\n Inspection Level', width: '8%', align: 'center', type: 'dropdown', source: gilevels },
                { title: 'Sample Size\n Code Letter', width: '8%', align: 'center', readOnly: true },
                { title: 'Sample Size', width: '8%', align: 'right', readOnly: true },
                { title: 'Critical\n AQL', width: '8%', align: 'center', type: 'dropdown', source: Aql },
                { title: 'Major\n AQL', width: '8%', align: 'center', type: 'dropdown', source: Aql },
                { title: 'Minor\n AQL', width: '8%', align: 'center', type: 'dropdown', source: Aql },
                { title: 'Inspection\n Authority', width: '8%', align: 'left', type: 'dropdown', source: GlbApprovingauthority },
                { title: 'FI Scheduled\n Date', width: '8%', align: 'center', type: 'calendar' },
                { title: 'FI Offered\n Date', width: '8%', align: 'center', type: 'calendar', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            onchange:function () {
                unsaved = true;
            },
            updateTable:function(instance, cell, col, row, val, label, cellName) {
                if (col == 6) {
                    lotsizeUs = val;
                }
                if (col == 7) {
                    giLevelUs = val;
                }
                if (col == 8) {
                    sampleSizeCodeLetter = gi[lotsizeUs + giLevelUs];
                    $(cell).text(sampleSizeCodeLetter);
                    instance.jexcel.options.data[row][col] = sampleSizeCodeLetter;
                }
                if(col == 9) {
                    sampleSize = SampleSizeCl_SampleSizeGrp[sampleSizeCodeLetter];
                    $(cell).text(SampleSizeCl_SampleSizeGrp[sampleSizeCodeLetter]);
                    instance.jexcel.options.data[row][col] = SampleSizeCl_SampleSizeGrp[sampleSizeCodeLetter];
                }

            }
        };
    
        var final_inspection_standard_vm = new Vue({
            el: '#finalInspectionStandardsDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, final_inspection_standard);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    final_inspection_standard_details_vm(data);
                },
            }
        });
    
        $('#final_inspection_standard_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    final_inspection_standard_vm.submitData();
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

    function final_inspection_standard_details_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateFinalInspectionStandard',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_final_inspection();
                unsaved = false;
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // **********  FINAL INSPECTION STANDARD ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // FINAL INSPECTION ENDS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    // DOCUMENTATION STARTS HERE 
    // ***********************************************************************************************************************************
     
     // ********** DETAILS OF CONSIGNEE & LOGISTICS FIRMS STARTS HERE  *********** //

    function get_details_of_consignee_logistics() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getDetailsOfConsigneeLogistics',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_details_of_consignee_logistics(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    
    function append_details_of_consignee_logistics(data) {
        $('#consignee_details').html('');
        let consignee_details_list = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'P.O. No. / Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { title: 'Consignor / Shipper / Exporter', width: '8%', align: 'left', type: 'dropdown', source: data.consignorAgent},
                { title: 'Clearing Agent: Name / Address /\n Contact Person  / Mobile No.', width: '8%', align: 'left', type: 'dropdown', source: data.clearingAgent},
                { title: 'Forwarding Agent: Name / Address /\n Contact Person  / Mobile No.', width: '8%', align: 'left', type: 'dropdown', source: data.forwadingAgent},
                { title: 'Importer - If other than Consignee: Name /\n Address / Contact Person  / Mobile No.', width: '8%', align: 'left', type: 'dropdown', source: data.importerAgent},
                { title: 'Consignee: Name / Address / Contact\n Person  / Mobile No.', width: '8%', align: 'left', type: 'left', type: 'dropdown', source: data.consigneeAgent}
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var consignee_details_list_vm = new Vue({
            el: '#consignee_details',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, consignee_details_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    consignee_details_vm(data);
                },
            }
        });
    
        $('#consignee_details_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    consignee_details_list_vm.submitData();
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

    function consignee_details_vm(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateDetailsOfConsigneeLogistics',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_documentation();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

     // ********** DETAILS OF CONSIGNEE & LOGISTICS FIRMS ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // DOCUMENTATION ENDS HERE 
    // ***********************************************************************************************************************************
    
    // **********  COMPONENT WISE PACKING DETAILS STARTS HERE   ********* //

    function get_component_wise_packing() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/get_component_wise_packing',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_component_wise_packing(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_component_wise_packing(data) {
        $('#componentWisePackingCodeDetails').html('');
        let component_wise_packing_list = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'P.O. No. / Enq. Ref. No.', width: '8%', align: 'center', readOnly: true },
                { title: 'Combo', width: '8%', align: 'center', readOnly: true },
                { title: 'Component', width: '8%', align: 'center', readOnly: true },
                { title: 'Colour', width: '8%', align: 'center', readOnly: true },
                { title: 'Intake Qty. Per\n Comp. (Nos.)', width: '8%', align: 'center', readOnly: true },
                { title: 'Component Wise\n Packing Code', width: '8%', align: 'center', type: 'dropdown', source: data.packingSource, autocomplete: true, multiple: true },
                { title: 'Packing\n Type', width: '7%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
        };
    
        var component_wise_packing_list_vm = new Vue({
            el: '#componentWisePackingCodeDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, component_wise_packing_list);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_component_wise_packing(data);
                },
            }
        });
    
        $('#component_wise_packing_btn').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    component_wise_packing_list_vm.submitData();
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

    function update_component_wise_packing(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/update_component_wise_packing',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                _call_to_packing();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // **********  COMPONENT WISE PACKING DETAILS ENDS HERE   *********** //
    
    
    // ********** BOM ARTICLE 1 COMMON TABLE STARTS HERE  *********** //

    function get_bom_1_common_table_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getBomCommonTableDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_bom_1_pi_details(resData);
                append_bom_1_in_house_status_details(resData);
                append_bom_1_item_accept_status_details(resData);
                append_bom_1_in_house_consolidated_qty_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_bom_1_pi_details(data) {
        let approvalStatusData = [
            { 'id': "0", 'name': 'PENDING' },
            { 'id': "1", 'name': 'APPROVED' },
            { 'id': "2", 'name': 'DISCREPANCY' }
         ];
        $('#bomRequest').html('');
        let bom1_pi_details = {
            data: data.pidetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Raised\n Date & Time', width: '6%', align: 'right', readOnly: true },
                { title: 'P.I. Ref. No.', width: '6%', align: 'right', readOnly: true },
                { title: 'P.I. Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Approval\n Status', width: '6%', type:'dropdown', align: 'right', readOnly: true,source:approvalStatusData },
                { title: 'P.I. Approved\n Date & Time', width: '6%', align: 'right', readOnly: true },
                { title: 'Expected\n Date of Delivery', width: '8%', align: 'right', readOnly: true },
                { title: 'Qty. Type', width: '8%', align: 'right', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var bom1PIDetails = new Vue({
            el: '#bomRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_pi_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                },
            }
        });
    }

    function append_bom_1_in_house_status_details(data) {
        $('#bomInHouse').html('');
        let bom1_in_house_status_details = {
            data: data.inhousestatusdetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', readOnly: true },
                { title: 'UOM', width: '7%', align: 'center', readOnly: true },
                { title: 'P.I. Ref. No.', width: '8%', align: 'right', readOnly: true },
                { title: 'D.C. No.', width: '8%', align: 'right', readOnly: true },
                { title: 'D.C. Date', width: '8%', align: 'right', readOnly: true },
                { title: 'D.C. Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'Invoice No.', width: '8%', align: 'right', readOnly: true },
                { title: 'Invoice Date', width: '8%', align: 'right', readOnly: true },
                { title: 'Invoice Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'Received Date', width: '6%', align: 'center', readOnly: true },
                { title: 'Received Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var bom1PIDetails = new Vue({
            el: '#bomInHouse',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_in_house_status_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                },
            }
        });
    }

    function append_bom_1_item_accept_status_details(data) {
        $('#bomItemAcceptStatus').html('');
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
         let bom1_item_accept_status = {
             data: data.itemacceptstatus,
             columns: [
                 { title:'id', width:'0%',align:'center',type:'hidden'},
                 { type: 'text', title: 'Item Description', width: '6%', align: 'left', readOnly: true },
                 { type: 'text', title: 'Garment\n Size', width: '6%', align: 'left', readOnly: true },
                 { type: 'text', title: 'Approved\n Item Code', width: '6%', align: 'left', readOnly: true },
                 { type: 'text', title: 'Approved Item\n Colour Code', width: '6%', align: 'left', readOnly: true },
                 { title: 'D.C. No.', width: '6%', align: 'right', readOnly: true },
                 { title: 'D.C. Date', width: '6%', align: 'right', readOnly: true },
                 { title: 'D.C. Qty.', width: '6%', align: 'right', readOnly: true },
                 { title: 'UOM', width: '6%', align: 'right', readOnly: true },
                 //{ title: 'Invoice No.', width: '6%', align: 'right', readOnly: true },
                 //{ title: 'Invoice Date', width: '6%', align: 'right', readOnly: true },
                 { title: 'Merchant Item\n Approval Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData, readOnly: true },
                 { title: 'Merchant Status\n Update Date & Time', width: '8%', align: 'center', type: 'text', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                 { title: 'Q.A. Status', width: '8%', align: 'center', type: 'dropdown', source: qaStatusData, readOnly: true },
                 { title: 'Q.A. Status Update\n Date & Time', width: '8%', align: 'center', type: 'text', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                 { title: 'Management\n Overriding Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData, readOnly: true },
                 { title: 'Management Status\n Update Date & Time', width: '8%', align: 'center', type: 'text', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
             ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var bom1PIDetails = new Vue({
            el: '#bomItemAcceptStatus',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_item_accept_status);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                },
            }
        });
    }

    function append_bom_1_in_house_consolidated_qty_details(data) {
        $('#bomInHouseConsolidated').html('');
        let supplyClosureData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'DISC. SUPPLY CLOSED' },
           { 'id': "2", 'name': 'SHORT SUPPLY - CLOSED' },
           { 'id': "3", 'name': 'FULL SUPPLY - CLOSED' },
           { 'id': "4", 'name': 'P.I. CANCELLED' }
        ];
        let bom1_in_house_consolidated_qty_details = {
            data: data.inhouseconsolidatedqtydetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                // { title: 'Planned Qty.', width: '5%', align: 'right', readOnly: true },
                // { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Qty.', width: '5%', align: 'right', readOnly: true },
              //  { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Received Qty.', width: '5%', align: 'right', readOnly: true },
               // { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Difference Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Supply Closure\n Status', width: '8%', align: 'center', type: 'dropdown', source: supplyClosureData, readOnly: true },
                { title: 'Status Update\n Date & Time', width: '8%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
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
            }
        };

        var bom1PIDetails = new Vue({
            el: '#bomInHouseConsolidated',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_in_house_consolidated_qty_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom_sampling_approval_details(data);
                },
            }
        });
    }

    // **********  BOM ARTICLE 1 COMMON TABLE ENDS HERE  *********** //
    
    // ********** BOM ARTICLE 2 COMMON TABLE STARTS HERE  *********** //

    function get_bom_2_common_table_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getBom2CommonTableDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_bom_2_pi_details(resData);
                append_bom_2_in_house_status_details(resData);
                append_bom_2_item_accept_status_details(resData);
                append_bom_2_in_house_consolidated_qty_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_bom_2_pi_details(data) {
        $('#bom2Request').html('');
        let bom1_pi_details = {
            data: data.pidetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Raised\n Date & Time', width: '6%', align: 'right', readOnly: true },
                { title: 'P.I. Approval\n Status', width: '6%', align: 'right', readOnly: true },
                { title: 'P.I. Approved\n Date & Time', width: '6%', align: 'right', readOnly: true },
                { title: 'P.I. Ref. No.', width: '6%', align: 'right', readOnly: true },
                { title: 'P.I. Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Issued\n Status', width: '8%', align: 'right', readOnly: true },
                { title: 'P.I. Issued\n Date & Time', width: '8%', align: 'right', readOnly: true },
                { title: 'Expected\n Date of Delivery', width: '8%', align: 'right', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var bom1PIDetails = new Vue({
            el: '#bom2Request',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_pi_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                },
            }
        });
    }

    function append_bom_2_in_house_status_details(data) {
        $('#bom2InHouse').html('');
        let bom2_in_house_status_details = {
            data: data.inhousestatusdetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', readOnly: true },
                { title: 'UOM', width: '7%', align: 'center', readOnly: true },
                { title: 'P.I. Ref. No.', width: '8%', align: 'right', readOnly: true },
                { title: 'D.C. No.', width: '8%', align: 'right', readOnly: true },
                { title: 'D.C. Date', width: '8%', align: 'right', readOnly: true },
                { title: 'D.C. Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'Invoice No.', width: '8%', align: 'right', readOnly: true },
                { title: 'Invoice Date', width: '8%', align: 'right', readOnly: true },
                { title: 'Invoice Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'Received Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Received Date', width: '6%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var bom2PIDetails = new Vue({
            el: '#bom2InHouse',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom2_in_house_status_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                },
            }
        });
    }

    function append_bom_2_item_accept_status_details(data) {
        $('#bom2ItemAcceptStatus').html('');
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
         let bom2_item_accept_status = {
             data: data.itemacceptstatus,
             columns: [
                 { title:'id', width:'0%',align:'center',type:'hidden'},
                 { type: 'text', title: 'Item Description', width: '6%', align: 'left', readOnly: true },
                 { type: 'text', title: 'Garment\n Size', width: '6%', align: 'left', readOnly: true },
                 { type: 'text', title: 'Approved\n Item Code', width: '6%', align: 'left', readOnly: true },
                 { type: 'text', title: 'Approved Item\n Colour Code', width: '6%', align: 'left', readOnly: true },
                 { title: 'D.C. No.', width: '6%', align: 'right', readOnly: true },
                 { title: 'D.C. Date', width: '6%', align: 'right', readOnly: true },
                 { title: 'Invoice No.', width: '6%', align: 'right', readOnly: true },
                 { title: 'Invoice Date', width: '6%', align: 'right', readOnly: true },
                 { title: 'Merchant Item\n Approval Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData, readOnly: true },
                 { title: 'Merchant Appl.\n Date & Time', width: '8%', align: 'center', type: 'calendar', readOnly: true },
                 { title: 'Q.A. Status', width: '8%', align: 'center', type: 'dropdown', source: qaStatusData, readOnly: true },
                 { title: 'Q.A. Status Update\n Date & Time', width: '8%', align: 'center', type: 'calendar', readOnly: true },
                 { title: 'Management\n Overriding Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData },
                 { title: 'Management Status\n Update Date & Time', width: '8%', align: 'center', type: 'calendar', readOnly: true },
             ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var bom1PIDetails = new Vue({
            el: '#bom2ItemAcceptStatus',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom2_item_accept_status);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                },
            }
        });
    }

    function append_bom_2_in_house_consolidated_qty_details(data) {
        $('#bom2InHouseConsolidated').html('');
        let bom2_in_house_consolidated_qty_details = {
            data: data.inhouseconsolidatedqtydetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '6%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Planned Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Received Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Difference Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Supply Closure\n Status', width: '8%', align: 'center', readOnly: true },
                { title: 'BOM Store - Item\n RTI Status', width: '8%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var bom1PIDetails = new Vue({
            el: '#bom2InHouseConsolidated',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom2_in_house_consolidated_qty_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    update_bom2_sampling_approval_details(data);
                },
            }
        });
    }

    // **********  BOM ARTICLE 2 COMMON TABLE ENDS HERE  *********** //

    // ********** CAD COMMON TABLE STARTS HERE  *********** //

    function get_cad_common_table_details() {
        var data = new FormData();
        
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getCADCommonTableDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_cad_req_details(resData);
                append_cad_qa_audit_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_cad_req_details(data) {
        $('#cad_requestDetaiLSls').html('');
        let bom1_pi_details = {
            data: data.reqdetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\nEnq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Reqirement', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Request\n Date & Time', width: '10%', align: 'center', readOnly: true },
                { type: 'text', title: 'Cutoff\n Date & Time', width: '10%', align: 'center', readOnly: true },
                { title: 'Authorization\n Status', width: '6%', align: 'center', readOnly: true },
                { title: 'Request\n Status', width: '6%', align: 'center', readOnly: true },
                { title: 'CAD\n Queue No.', width: '10%', align: 'center', readOnly: true },
                { type: 'text', title: 'Queue No. Assigned \n Date & Timestamp', width: '10%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var bom1PIDetails = new Vue({
            el: '#cad_requestDetaiLSls',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_pi_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                },
            }
        });
    }

    function append_cad_qa_audit_details(data) {
        $('#cad_qaAudit').html('');
        let bom1_in_house_status_details = {
            data: data.qaauditdetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\nEnq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Reqirement', width: '8%', align: 'left', readOnly: true },
                { title: 'Assigned \n CAD Reference No.', width: '10%', align: 'center', readOnly: true },
                { title: 'Q.A. Status', width: '8%', align: 'center', readOnly: true },
                { title: 'Q.A. Status Update \n Date & Time', width: '10%', align: 'center', readOnly: true },
                { title: 'Job Status', width: '8%', align: 'center', readOnly: true },
                { title: 'Job Status Update \n Date & Time', width: '10%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var bom1PIDetails = new Vue({
            el: '#cad_qaAudit',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, bom1_in_house_status_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                },
            }
        });
    }

    // ********** CAD COMMON TABLE ENDS HERE  *********** //

    // **********  ORDER ENTRY HEADER DATA SAVE STARTS HERE  *********** //

    $('#order_process_save').click(function () {
        $('.herr').hide();
        if($('#total_order_qty').val() == "" || $('#total_order_qty').val() == null ) {
            //alert('Fill All Fields');
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        }
        else if($('#uom').val() == "" || $('#uom').val() == null ) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        }
        else if($('#season').val() == "" || $('#season').val() == null ) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        }
        else if($('#class').val() == "" || $('#class').val() == null ) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        }
        else if($('#divi_dept').val() == "" || $('#divi_dept').val() == null ) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        }
        else if($('#sub_class').val() == "" || $('#sub_class').val() == null ) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        }
        else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    updateOrderProcess();
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

    function updateOrderProcess() {

        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('total_order_qty', $('#total_order_qty').val());
        dataform.append('uom', $('#uom').val());
        dataform.append('season', $('#season').val());
        dataform.append('season', $('#season').val());
        dataform.append('class', $('#class').val());
        dataform.append('divi_dept', $('#divi_dept').val());
        dataform.append('sub_class', $('#sub_class').val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/UpdateOrderProcess',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );

                setTimeout(() => {
                    location.reload(true);
                }, 1000);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // **********  ORDER ENTRY HEADER DATA SAVE ENDS HERE  *********** //

    // ********** SAMPLE COMMON TABLE STARTS HERE  *********** //

    function get_sample_common_table_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/getSampleCommonTableDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_sam_req_details(resData);
                append_sam_qa_audit_details(resData);
                append_sam_despatch_details(resData);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_sam_req_details(data) {
        $('#cad_requestDetaiLSls').html('');
        let sam_req_details = {
            data: data.reqdetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\nEnq. Ref. No.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Colour', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Request\n Date & Time', width: '6%', align: 'center', readOnly: true },
                { type: 'text', title: 'Cutoff\n Date & Time', width: '6%', align: 'center', readOnly: true },
                { title: 'Authorization\n Status', width: '6%', align: 'center', readOnly: true },
                { title: 'Request\n Status', width: '6%', align: 'center', readOnly: true },
                { title: 'Sample\n Queue No.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Queue No. Assigned \n Date & Time', width: '6%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var sample_req_details = new Vue({
            el: '#sample_requestDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, sam_req_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                },
            }
        });
    }

    function append_sam_qa_audit_details(data) {
        $('#sample_qaAudit').html('');
        let sam_qa_audit_details = {
            data: data.qaauditdetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\nEnq. Ref. No.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo ', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Colour', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { title: 'Assigned \n Sample Reference No.', width: '6%', align: 'left', readOnly: true },
                { title: 'Q.A. Status', width: '6%', align: 'center', readOnly: true },
                { title: 'Q.A. Status Update \n Date & Time', width: '6%', align: 'center', readOnly: true },
                { title: 'Job Status', width: '6%', align: 'center', readOnly: true },
                { title: 'Job Status Update \n Date & Time', width: '6%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
        };

        var sample_qa_audit_details = new Vue({
            el: '#sample_qaAudit',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, sam_qa_audit_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                },
            }
        });
    }

    function append_sam_despatch_details(data) {
        $('#sample_dispatchDetails').html('');

        let deliveryStatus = [
            { id: 0, name: "PENDING" },
            { id: 1, name: "DELIVERED" },
            { id: 2, name: "LOST IN TRANS." },
            { id: 3, name: "OTHERS" }
        ]

        let approvalStatus = [
            { id: 0, name: "PENDING" },
            { id: 1, name: "APPROVED" },
            { id: 2, name: "APP. (AMEND.)" },
            { id: 4, name: "REVISED SAMPLE" },
            { id: 5, name: "DROPPED" }
        ]

        let approvalBy = [
            { id: 1, name: "BUYER" },
            { id: 2, name: "LIASON OFFICE" },
            { id: 3, name: "BUYING OFFICE" },
            { id: 4, name: "OTHERS" }
        ]

        let sam_despatch_details = {
            data: data.despatchdetails,
            columns: [
                { type: 'hidden', title: 'id', width: '0%', align: 'center', readOnly: true },
                { title: 'P.O. No. /\nEnq. Ref. No.', width: '6%', align: 'left', readOnly: true },
                { title: 'Combo', width: '6%', align: 'left', readOnly: true },
                { title: 'Component', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Colour', width: '6%', align: 'left', readOnly: true },
                { title: 'Requirement Sent', width: '8%', align: 'left', readOnly: true },
                { title: 'Assigned Sample \n Reference No.', width: '8%', align: 'left', readOnly: true },
                { title: 'Sample Despatch \n Airway Bill No.', width: '8%', align: 'left' },
                { type: 'calendar', title: 'Airway Bill \n Date & Time', width: '7%', align: 'center', options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                { type: 'dropdown', title: 'Delivery Status', width: '7%', source: deliveryStatus, align: 'center' },
                { type: 'calendar', title: 'Delivery Date \n (Tracker ID.)', width: '7%', align: 'center' },
                { type: 'dropdown', title: 'Approval \n Status', width: '7%', source: approvalStatus, align: 'center' },
                { type: 'dropdown', title: 'Approved By', width: '7%', source: approvalBy, align: 'center' },
                { type: 'calendar', title: 'Approval Received \n Date & Time', width: '7%', align: 'center', options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                { type: 'hidden', title: 'Job Status', width: '0%', align: 'center' },
                { type: 'hidden', title: 'Del Status', width: '0%', align: 'center' },
                { type: 'hidden', title: 'App Status', width: '0%', align: 'center' },
                { type: 'hidden', title: 'App By', width: '0%', align: 'center' },
                { type: 'hidden', title: 'App date', width: '0%', align: 'center' },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            // tableOverflow: true,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 9) {
                    
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 7) {
                    
                    if((data.despatchdetails[row][14] == '4' || data.despatchdetails[row][14] == 4) && (data.despatchdetails[row][15] != '1' || data.despatchdetails[row][15] != 1) ) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 8) {
                    if((data.despatchdetails[row][14] == '4' || data.despatchdetails[row][14] == 4) && (data.despatchdetails[row][15] != '1' || data.despatchdetails[row][15] != 1) ) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 9) {
                    delVal = val;
                    if((data.despatchdetails[row][14] == '4' || data.despatchdetails[row][14] == 4) && (data.despatchdetails[row][15] != '1' || data.despatchdetails[row][15] != 1) ) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                
                if(col == 10) {
                    if(data.despatchdetails[row][15] == '1' || data.despatchdetails[row][15] == 1)  {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 11) {
                    
                    if((data.despatchdetails[row][16] == 'Yes' || data.despatchdetails[row][16] == "0") && (data.despatchdetails[row][15] == '1' || data.despatchdetails[row][15] == 1))  {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 12) {
                    if(data.despatchdetails[row][17] == 'Yes' && (data.despatchdetails[row][15] == '1' || data.despatchdetails[row][15] == 1))  {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 13) {
                    
                    if(data.despatchdetails[row][18] == 'Yes' && (data.despatchdetails[row][15] == '1' || data.despatchdetails[row][15] == 1))  {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                
            }
            
        };

        var sample_despatch_details = new Vue({
            el: '#sample_dispatchDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, sam_despatch_details);
                Object.assign(this, spreadsheet);
            },
            methods: {
                submitData: function () {
                    let data = this.getData();
                    updateSampleDespatchApproval(data);
                },
            }
        });
        
        $('#sampleDespatchApproval').click(function () {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    sample_despatch_details.submitData();
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        });
    }

    function updateSampleDespatchApproval(data) {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'WorkInProcess/updateSampleDespatchApproval',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                get_sample_common_table_details();
                swalWithBootstrapButtons.fire({
                    title: 'Saved!', text: 'Operation completed successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-info px-5' }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $('#sample_req').click(function() {
        swalWithBootstrapButtons.fire(
            alertMessageFunction('empty')
        );
    });

    // ********** SAMPLE COMMON TABLE ENDS HERE  *********** //

});