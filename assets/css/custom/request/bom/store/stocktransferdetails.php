$(document).ready(function () {

    // let parts = window.location.href.split('/');
    // let request_id = parts[parts.length - 1];
    // let req_id = atob(decodeURIComponent(request_id));

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
        
        if(mode == "pivalidation_error") {
            return {
                title: 'Warning',
                text: "Check PI Ref. No. Or Transfer Category",
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
    
    function rateFilter(instance, cell, c, r, source) {
        var item_id = instance.jexcel.getValueFromCoords(c - 1, r);
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id) return true;
            })
        } else {
            return [];
        }
    }
    function gstFilter(instance, cell, c, r, source) {
        var item_id = instance.jexcel.getValueFromCoords(c - 2, r);
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id) return true;
            })
        } else {
            return [];
        }
    }

    // *********************************************************************************************************************************** 
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getQABomRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('pId', pId);
        data.append('itemCode', itemCode);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getSurplusStockDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);
                
                let link = base_path + 'request/Bomrequest/surplus_draftdc/'+btoa(enquiry_id)+'/reqId/'+btoa(reqId)+'/itemcode/'+btoa(itemCode)+'/pId/'+btoa(pId);
                $("#draftLink").attr("href", link);
                
                append_item_details(bom_requirement_data);
                append_in_house_details(bom_requirement_data);
                append_material_indent_received_details(bom_requirement_data);
                append_material_issued_details(bom_requirement_data);
                append_shipment_order_closure_details(bom_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    function append_item_details(data) {
        $('#surplusitemdetails').html('');
        let list = {
            data: data.itemdetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Brand', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Item Description', width: '12%', align: 'left', readOnly: true },
                { type: 'text', title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Item Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Item Colour Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Size / Dimension', width: '7%', align: 'center', readOnly: true },
                { title: 'UOM', width: '7%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        surplusitemReference_vm = new Vue({
            el: '#surplusitemdetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_in_house_details(data) {
        $('#surplusStockDetails').html('');
        let list = {
            data: data.inhousestatusdetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Original\n WIP Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Original\n P.I. Ref. No.', width: '12%', align: 'left', readOnly: true },
                { type: 'text', title: 'Invoice No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Invoice Date', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Item - Lot / Batch\nRef. No. Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Surplus Stock\nQty.', width: '7%', align: 'center', readOnly: true },
                { title: 'UOM', width: '7%', align: 'center', readOnly: true },
                { title: 'Rate Per Unit \n (Rs.)', width: '7%', align: 'center', readOnly: true },
                { title: 'Stock Value\n(Rs.)', width: '7%', align: 'center', readOnly: true },
                { title: 'GST / IGST\n (%)', width: '7%', align: 'center', readOnly: true },
                { title: 'Total Stock\n Value (Rs.)', width: '7%', align: 'center', readOnly: true },
                { title: 'Surplus Stock Recd\nDate & Time', width: '6%', align: 'center', type: 'text', readOnly: true },
                { title: 'Storage Bin /\n Rack Ref. No.', width: '8%', align: 'center',readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label) {
                
                if(col == 7)
                {
                    qty = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 9)
                {
                    rate = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 10)
                {
                    tot = parseFloat(qty) * parseFloat(rate) ;
                    txtValue = numeral(tot).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
                if(col == 11)
                {
                    gst = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 12)
                {
                    subtot = parseFloat(tot) + (parseFloat(tot) * parseFloat(gst) / 100 );
                    txtValue = numeral(subtot).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
                
                
            }
        };

        inHouseStatusReference_vm = new Vue({
            el: '#surplusStockDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_material_indent_received_details(data) {
        $('#materialIndentReceived').html('');
        let list = {
            data: data.purchaseIndent,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'WIP. Ref. No.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Queue No.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'P.I. Ref. No.', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'P.I. Date & Time.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'P.I. Cuttoff\n Date & Time.', width: '6%', align: 'left', readOnly: true },       
                { type: 'text', title: 'Transfer to', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'Qty. Type', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'P.I. Qty.', width: '5%', align: 'right', readOnly: true },
                { type: 'text', title: 'Issued Qty.', width: '5%', align: 'right', readOnly: true },
                { type: 'text', title: 'Pending Qty.', width: '5%', align: 'right', readOnly: true },
                { type: 'text', title: 'UOM', width: '4%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 9)
                {
                    pi_qty = val;
                    txtValue = numeral(pi_qty).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 10)
                {
                    issue_qty = val;
                    txtValue = numeral(issue_qty).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 11)
                {
                    pending_qty = parseFloat(pi_qty) - parseFloat(issue_qty);
                    txtValue = numeral(pending_qty).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        itemAcceptStatusReference_vm = new Vue({
            el: '#materialIndentReceived',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_material_issued_details(data) {
        
        let stockDD = [ 'STOCK TRAN.', 'SURPLUS TRAN.' ];

        $('#materialIssuedDetails').html('');
        let list = {
            data: data.issued_details,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '3%' },
                { type: 'dropdown', title: 'P.I. Ref. No', width: '6%', align: 'left', source:data.piRefNo },
                { type: 'dropdown', title: 'Transfer\nCategory', width: '6%', align: 'left',source:stockDD },
                { type: 'text', title: 'S.T.M. Ref. No.', width: '6%', align: 'left', readOnly: true },
                // { title: 'Issued Qty.', width: '6%', align: 'right', readOnly: true },
                { type: 'text', title: 'Stock Transfer\nDate & Time', width: '6%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Invoice No.', width: '8%', align: 'left', source:data.inv_no },
                { type: 'text', title: 'Invoice Date', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Item - Lot / Batch\nRef. No.', width: '8%', align: 'left', source:data.lot_no },
                { type: 'dropdown', title: 'Rate Per\nUnit (Rs.)', width: '8%', align: 'left', source:data.rateList,filter: rateFilter },
                { type: 'dropdown', title: 'GST', width: '5%', align: 'left', readOnly: true },
                { type: 'text', title: 'Issued Qty.', width: '8%', align: 'right'  },
                { title: 'UOM', width: '6%', align: 'text', type: 'calendar', readOnly: true },
                
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 7) 
                {
                    
                }
                if(col == 9) 
                {
                    
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 0) {
                    issue_id = val;
                }
                if(col == 3) {
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).text(val);
                        instance.jexcel.options.data[row][col] = val;
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 4) {
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 7) {
                    inv_no = val;
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 8) {
                    if(inv_no != '') {
                        let inv_date = data.inv_date;
                        let obj = inv_date.find(o => o.item_id === inv_no);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                    }
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 9) {
                    lot_no = val;
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 10) {
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                
                if(col == 11) {
                    if(lot_no != '') {
                        let gst = data.gstList;
                        let obj = gst.find(o => o.item_id === lot_no);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                    }
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 12) {
                    if(issue_id == '') {
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 13) {
                    if(lot_no != '') {
                        let uom = data.uomList;
                        let obj = uom.find(o => o.item_id === lot_no);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                    }
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
            }
            
        };

        issuedDetals_vm = new Vue({
            el: '#materialIssuedDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_shipment_order_closure_details(data) {

        let stockClosureStatus = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'SCRAP & ARCHIVE' }
        ];

        // let availableStockData = [
        //   { 'id': "0", 'name': 'SOLD AS SECONDS' },
        //   { 'id': "1", 'name': 'UNUSABLE' },
        //   { 'id': "2", 'name': 'DEFECTIVE' }
        // ];

        $('#surplusStockClosure').html('');
        let list = {
            data: data.inhouseconsolidatedqtydetails,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '3%' },
                { type: 'text', title: 'Item - Lot / Batch\nRef. No.', width: '8%', align: 'center', readOnly: true },
                { type: 'text', title: 'Rate Per\nUnit (Rs.)', width: '6%', align: 'right', readOnly: true },
                { title: 'Sum of\nReceived Qty.', width: '6%', align: 'right', readOnly: true },
                { title: 'Sum of\nIssued Qty.', width: '6%', align: 'right', readOnly: true },
                { title: 'Scrap & Archive\nQty.', width: '6%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'left', readOnly: true },
                { title: 'Lot Wise\nBalance Qty.', width: '6%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'left', readOnly: true },
                { title: 'Balanace Stock\nValue(Rs.)', width: '7%', align: 'right', readOnly: true },
                { title: 'Stock Closure\n Status', width: '7%', align: 'center', type: 'dropdown' ,source:stockClosureStatus },
                { title: 'Status Update\n Date & Time', width: '6%', align: 'right', type: 'text', readOnly: true },
                { title: 'If Scrap & Archive\n Approved By', width: '7%', align: 'center', type: 'text', readOnly: true },
                { title: 'Approved\n Date & Time', width: '6%', align: 'right', type: 'text', readOnly: true },
                
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 4)
                {
                    rate = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 5)
                {
                    rec_qty = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 6)
                {
                    issue_qty = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 7)
                {
                    if(val == '') {
                        scrap_qty = 0;
                    } else {
                        scrap_qty = val;
                    }
                        txtValue = numeral(scrap_qty).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 9)
                {
                    //console.log(scrap_qty);
                    bal_qty = parseFloat(rec_qty) - parseFloat(issue_qty) - parseFloat(scrap_qty);
                    txtValue = numeral(bal_qty).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                
                if(col == 11)
                {
                    tot_amt = parseFloat(bal_qty) * parseFloat(rate);
                    txtValue = numeral(tot_amt).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        inHouseConsolidatedReference_vm = new Vue({
            el: '#surplusStockClosure',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
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
    
    // function validateCheckForm(inHouseData, issuedData) {
    //     for(let i=0;i<inHouseData.length;i++) {
    //         if(inHouseData[i][3] == '') {
                
    //         }
            
    //     }
    // }
    
    function validateCheckForm(inHouseData, issuedData ) {
        //console.log(issuedData);
        let errorCount = 0;
        for (let i = 0; i < issuedData.length; i++) {
            let lot_no = issuedData[i][9];
            //console.log(lot_no);
            let qty = issuedData[i][12];
            for(let j = 0; j < inHouseData.length; j++) {
                if(inHouseData[j][6] == lot_no ) {
                    let totalQty = inHouseData[j][7];
                    if(qty > totalQty) {
                        colsole.log(qty);
                        errorCount++;  
                    } 
                }
            }
        }
         
        return errorCount;
    }
    
    
    function validateDubForm(dataValue ) {
        let errorCount = 0;
        let piVal = [];
        let catVal = [];
        let piLength = dataValue.length;
        if(piLength > 0) {
            for (let i = 0; i < dataValue.length; i++) {
                if(dataValue[i][0]  == '') {
                    piVal.push(dataValue[i][3]);
                    catVal.push(dataValue[i][4]);
                }
            }
            let piValLength = piVal.length;
            if(piValLength > 0) {
                for(let i=0;i<piValLength;i++) {
                    if(piVal[0] != piVal[i]) {
                        errorCount++;
                    }
                }
            }
            
            let catValLength = catVal.length;
            if(catValLength > 0) {
                    for(let i=0;i<catValLength;i++) {
                    if(catVal[0] != catVal[i]) {
                        errorCount++;
                    }
                }
            }
            
        }
       
        return errorCount;
    }
    
    $('#draftSave').click(function () {
        let issuedData = issuedDetals_vm.getData();
        let inHouseData = inHouseStatusReference_vm.getData();
        let validateField = [3,4,7,8,9,10,11,12,13];
        let validateReqCount = validateForm(validateField, issuedData);
        let validateTotCount = validateCheckForm(inHouseData, issuedData);
        let validateDubCount = validateDubForm(issuedData);
        // console.log(validateTotCount);
        if(validateReqCount > 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
                )
        } else if(validateDubCount > 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('pivalidation_error')
                )
        } else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
                ).then(function (result) {
                if (result.value) {
                    
                    updateFunctionDraft(issuedData);
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
    
    
    function updateFunctionDraft(issuedData) {
        let dataform = new FormData();
        dataform.append('issuedData', JSON.stringify(issuedData));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);
        dataform.append('itemCode', itemCode);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateSurplusDraft',
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
                        window.location.href = base_path + 'request/Bomrequest/surplusstockdetails' + '/' + encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(reqId)) + '/itemCode/' + encodeURIComponent(btoa(itemCode)) + '/pId/' + encodeURIComponent(btoa(pId)) +'';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    
    $('#getValues').click(function () {
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let inHouseData = inHouseStatusReference_vm.getData();
                let itemAccept = itemAcceptStatusReference_vm.getData();
                let inHouseConsolidate = inHouseConsolidatedReference_vm.getData();
                updateFunction(inHouseData, itemAccept, inHouseConsolidate);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });

    function updateFunction(inHouseData, itemAccept, inHouseConsolidate) {
        let dataform = new FormData();
        dataform.append('inHouseData', JSON.stringify(inHouseData));
        dataform.append('itemAccept', JSON.stringify(itemAccept));
        dataform.append('inHouseConsolidate', JSON.stringify(inHouseConsolidate));
        dataform.append('enquiry_id', enquiry_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateStorePiDetails',
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
                    window.location.href = base_path + 'request/Bomrequest/bompurchaseindentlist';
                }, 1000);
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