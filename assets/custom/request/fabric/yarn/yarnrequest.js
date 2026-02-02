$(document).ready(function () {

    // **************************************** //

    var mode = 'add';
    var yarn_requirement_vm = '';
    var selectCount = 0;
    var selectedArray = [];
    let parts = window.location.href.split('/');
    let request_id = parts[parts.length - 1];
    let req_id = atob(decodeURIComponent(request_id));
    $('#saveRequestDetails').hide();

    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    // Change function
    $('#cad_deprt').on('change', function(){
        let cad_dept = $('#cad_deprt').val();
        $('#fab_dept').val(cad_dept);
        $('#bom_dept').val(cad_dept);
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

    // API Call
    
    var activeNav = $("ul.nav-pills > li > a").attr("href").replace("#", "");
    var fabric = 0, yarn = 0, knitting = 0, dyeing = 0, compacting = 0, lab = 0;
        
    if (activeNav == 'yarn') {
        _call_to_yarn();
    }

    $("ul.nav-pills > li > a").click(function () {
        var currentNav = $(this).attr("href").replace("#", "");
        if (currentNav == 'yarn' && yarn == 0) {
            yarn++;
            _call_to_yarn();
        } else if (currentNav == 'fabric-tbl' && fabric == 0) {
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
        get_itemized_fabric_requirement_details(); // call 8
    }

    function _call_to_yarn() {
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

    // function _call_to_lab() {
    //     get_lab_testing_acceptance_internal_details(); // call lab testing acceptance internal details
    //     get_lab_testing_acceptance_external_details(); // call lab testing acceptance external details
    //     get_external_lab_testing_authority_details(); // call external lab testing authority details
    // }

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
        if(gridname == 'knitting_programme' || gridname == 'knitting_programme_itemized' || gridname == 'fabric_dyeing_programme' || gridname == 'yarn_dyeing_programme') { length = 5; }
        else if(gridname == 'fab_grand_total') { length = 2; }
        else if(gridname == 'yarn_requriment') { length = 4; }
        else { length = 4; }
        let position = columnlength - length;
        for(var i= 1; i <= position; i++) { empar.push(''); }
        if(gridname == 'knitting_programme' || gridname == 'fabric_dyeing_programme') {
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

    // *********************************************************************************************************************************** 
    // FABRIC REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    // ********** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS STARTS HERE  *********** //

    function getFabricWashingCompatingDetails() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/getFabricWashingCompatingDetails',
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

    }

    // ********** FABRIC WASHING COMPACTING & HEAT SETTING DETAILS ENDS HERE  *********** //

    
    // *********************************************************************************************************************************** 
    // FABRIC REQUEST ENDS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    // YARN REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function get_yarn_requirement_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/getYarnReqRequirementDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let resData = JSON.parse(data);
                append_yarn_requirement_details(resData);
                append_pi_details(resData);
                append_in_house_details(resData);
                append_item_accept_status(resData);
                append_in_house_consolidated_qty(resData);
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
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2) 
                {
                    if(val === true) {
                        selectedArray.push(row);
                    } else {
                        selectedArray = selectedArray.filter(function(e) {return e != row})
                    }
                    getReferenceValue(yarn_requirement_list.data[row], val, row);
                }
            }
        };
    
        yarn_requirement_vm = new Vue({
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

    }

    function getReferenceValue(data, status, row) {
        if(status == true) {
            // let emparr = [];
            // let length = data.length;
            // for(let i=0; i < data.length; i++) {
            //     if(i < length-5) {
            //         emparr.push(data[i])
            //     }
            // }
            // for(let i=0; i < 5; i++) {
            //     emparr.push("")
            // }
            // // console.log(emparr);
            // requirementData.push(emparr);
            selectCount = selectCount+1;
        }
        else {
            // console.log(data[0])
            // requirementData = requirementData.filter(function(e) { if(e[0]!== data[0]) return e  })
            selectCount = selectCount-1;
        }
        // append_attach_reference();
    }
    
    // function append_attach_reference() {
    //     let data = requirementData;
    //     let common_dd = [
    //         {id: '1', name: 'Attached'}, 
    //         {id: '2', name: 'Pending'}, 
    //         {id: '3', name: 'N.A.'}, 
    //     ];
    //     $('#attachReference').html('');
    //     let list = {
    //         data: data,
    //         columns: [
    //             { title:'id', width:'10%',align:'center',type:'hidden'},
    //             { type: 'hidden', title: 'Mark', width: '8%', align: 'left' },
    //             { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
    //             { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
    //             { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
    //             { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
    //             { type: 'dropdown', title: 'Approved & Graded\n Measurement Chart', width: '7%', align: 'left', source: common_dd },
    //             { type: 'dropdown', title: 'Complete Artwork', width: '7%', align: 'left', source: common_dd },
    //             { type: 'dropdown', title: 'How to Measure\n Details', width: '7%', align: 'left', source: common_dd },
    //             { type: 'dropdown', title: 'Buyers Original \nSample or Pattern', width: '7%', align: 'left', source: common_dd },
    //             { type: 'dropdown', title: "Buyer's Comments", width: '7%', align: 'left', source: common_dd },
    //         ],
    //         minDimensions: [4, 1],
    //         allowDeleteColumn: false,
    //         allowInsertRow: false,
    //         allowInsertColumn: false,
    //     };

    //     cadReference_vm = new Vue({
    //         el: '#attachReference',
    //         mounted: function () {
    //             let spreadsheet = jexcel(this.$el, list);
    //             Object.assign(this, spreadsheet);
    //         },
    //     });
    // }

    // ********** COMMON TABLE DETAILS STARTS HERE  *********** //
    
    function append_pi_details(data) {
        $('#yarnpidetails').html('');
        let list = {
            data: data.pi_details,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Yarn\n Vendor', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: "Yarn Product\n Code (Vendor's)", width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Yarn Blend (%) /\n Content', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Yarn\n Count', width: '8%', align: 'left', readOnly: true },
                { title: 'Yarn\n Special Request.', width: '7%', align: 'center', readOnly: true },
                { title: 'Yarn Colour', width: '7%', align: 'center', readOnly: true },
                { title: 'P.I. Raised\n Date & Time', width: '8%', align: 'right', readOnly: true },
                { title: 'P.I. Approval\n Status', width: '8%', align: 'right', readOnly: true },
                { title: 'P.I. Approved\n Date & Time', width: '8%', align: 'right', type: 'calendar', readOnly: true },
                { title: 'P.I. Ref. No.', width: '8%', align: 'right', readOnly: true },
                { title: 'P.I. Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'UOM', width: '8%', align: 'right', type: 'calendar', readOnly: true },
                { title: 'P.I. Issued\n Status.', width: '8%', align: 'right', readOnly: true },
                { title: 'P.I. Issued\n Date & Time', width: '8%', align: 'right', readOnly: true },
                { title: 'Expected\n Date of Delivery', width: '5%', align: 'center', type: 'dropdown', source: data.uomData, readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        piDetailsStatusReference_vm = new Vue({
            el: '#yarnpidetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function append_in_house_details(data) {
        $('#inHouseStatus').html('');
        let list = {
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
                { title: 'D.C. No.', width: '8%', align: 'right' },
                { title: 'D.C. Date', width: '8%', align: 'right', type: 'calendar' },
                { title: 'D.C. Qty.', width: '8%', align: 'right' },
                { title: 'Invoice No.', width: '8%', align: 'right' },
                { title: 'Invoice Date', width: '8%', align: 'right', type: 'calendar' },
                { title: 'Invoice Qty.', width: '8%', align: 'right' },
                { title: 'Received Qty.', width: '8%', align: 'right' },
                { title: 'UOM', width: '5%', align: 'center', type: 'dropdown', source: data.uomData },
                { title: 'Received Date', width: '6%', align: 'center', type: 'calendar' },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        piDetailsStatusReference_vm = new Vue({
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
                { title: 'D.C. No.', width: '6%', align: 'right', readOnly: true },
                { title: 'D.C. Date', width: '6%', align: 'right', readOnly: true },
                { title: 'Invoice No.', width: '6%', align: 'right', readOnly: true },
                { title: 'Invoice Date', width: '6%', align: 'right', readOnly: true },
                { title: 'Merchant Item\n Approval Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData, readOnly: true },
                { title: 'Merchant Appl.\n Date & Time', width: '8%', align: 'center', type: 'calendar', readOnly: true },
                { title: 'Q.A. Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData },
                { title: 'Q.A. Status Update\n Date & Time', width: '8%', align: 'center', type: 'calendar', readOnly: true },
                { title: 'Management\n Overriding Status', width: '8%', align: 'center', readOnly: true, type: 'dropdown', source: approvalStatusData },
                { title: 'Management Status\n Update Date & Time', width: '8%', align: 'center', type: 'calendar', readOnly: true },
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
                { title: 'Planned Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'P.I. Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Received Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Difference Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Supply Closure\n Status', width: '8%', align: 'center', type: 'dropdown', source: supplyClosureData },
                { title: 'BOM Store - Item\n RTI Status', width: '8%', align: 'center', type: 'dropdown', source: itemRTIData },
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


    // ********** COMMON TABLE DETAILS ENDS HERE  *********** //
    
    // *********************************************************************************************************************************** 
    // YARN REQUEST ENDS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    // KNITTING REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    // ********** KNITTING PROGRAMME DETAILS STARTS HERE  *********** //

    function get_knitting_programme_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/getKnittingProgrammeDetails',
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
    }

    // ********** KNITTING PROGRAMME DETAILS ENDS HERE  *********** //

    // ********** KNITTING PROGRAMME ITEMIZED YARN REQUIREMENT DETAILS STARTS HERE  *********** //

    function get_knitting_programme_itemized_yarn_requirement_details() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/getKnittingProgrammeItemizedYarnRequirementDetails',
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
    // KNITTING REQUEST ENDS HERE 
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
            url: base_path + 'request/Fabricrequest/getFabricDyeingProgramme_qty',
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
            url: base_path + 'request/Fabricrequest/getFabricDyeingProgramme_finish',
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

    }

    // ********** FABRIC DYEING PROGRAMME - COLOUR & DIA WISE QTY. DETAILS (FD, SDB & DDB) ENDS HERE  *********** //
    
    // ********** YARN DYEING PROGRAMME - COLOUR WISE QTY. DETAILS CONSOLIDATED (YDS & YDJ) STARTS HERE  *********** //

    function getYarnDyeingProgramme_qty() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/getYarnDyeingProgramme_qty',
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
            url: base_path + 'request/Fabricrequest/getYarnDyeingProgramme_finish',
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

    }

    // ********** YARN DYEING PROGRAMME - COLOUR REFERENCE & FINISHING DETAILS (YDS & YDJ) ENDS HERE  *********** //

    // *********************************************************************************************************************************** 
    // DYEING ENDS HERE 
    // ***********************************************************************************************************************************


    // *********************************************************************************************************************************** 
    // COMPACTING STARTS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    // COMPACTING ENDS HERE 
    // ***********************************************************************************************************************************

    
    $('#getValues').click(function () {
        if(selectCount <= 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
            swalWithBootstrapButtons.fire({
                title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
            }).then(function (result) {
                if (result.value) {
                    let data = yarn_requirement_vm.getData();
                    updateFunction(data);
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                    });
                }
            });
        }
        
    });

    function updateFunction(data) {
        
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('data', JSON.stringify(data));
        dataform.append('req_id', req_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/sendYarnReq',
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
    
    // ******** SAVE REQUEST DETAILS ENDS HERE ***************** //

    // ******** SAVE AS DRAFT ENDS HERE ***************** //
    
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

});