$(document).ready(function () {

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
        
        if(mode == "qtycheck") {
            return {
                title: 'Warning',
                text: "Check Qty. Value",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        if(mode == "statuscheck") {
            return {
                title: 'Warning',
                text: "Check Status",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        if(mode == "micheck") {
            return {
                title: 'Warning',
                text: "Check M.I.Ref.No. Value",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
    }

    
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
        total = numeral(total).format('0.00');
        //total = (total > 0) ? total : ''
        return total;
    }
     
    function footer(grid_name)
    {
        
        if(grid_name == 'in-house')
        {
            return [[ '', '', '', '', '','Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")']];
        }
        if(grid_name == 'received_detail')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:',  '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")',  '=GPWSUMCOL(TABLE(), COLUMN(),"")' ]];
        }
        else if(grid_name == 'mi-issued')
        {
            return [[ '', '', '', '', '', '', '', '', '', 'Total:',  '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")',  '']];
        }
        if(grid_name == 'open_close')
        {
            return [[ '', '', '',  'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")',  '=GPWSUMCOL(TABLE(), COLUMN(), "")',  '=GPWSUMCOL(TABLE(), COLUMN(),"")', '=GPWSUMCOL(TABLE(), COLUMN(), "")',  '=GPWSUMCOL(TABLE(), COLUMN(), "")',  '=GPWSUMCOL(TABLE(), COLUMN(),"")' ,'' ,'=GPWSUMCOL(TABLE(), COLUMN(), "")',  '=GPWSUMCOL(TABLE(), COLUMN(), "")',  '=GPWSUMCOL(TABLE(), COLUMN(),"")' , '', ''  ]];
        }
    }
    
    function miNoFilter(instance, cell, c, r, source) {
        var item_id = instance.jexcel.getValueFromCoords(c - 1, r);
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
        data.append('itemCode', itemCode);
        data.append('pId', pId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getOrderStockDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);

                let link = base_path + 'request/Bomrequest/draftdc/'+btoa(enquiry_id)+'/reqId/'+btoa(dreq_id)+'/miId/'+btoa(bom_requirement_data.miId)+'/itemcode/'+btoa(itemCode)+'/pId/'+btoa(pId);

                $("#draftLink").attr("href", link);
                
                append_item_details(bom_requirement_data);
                append_in_house_details(bom_requirement_data);
                append_item_accept_status(bom_requirement_data);
                append_in_house_consolidated_qty(bom_requirement_data);
                append_shipment_details(bom_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    function append_item_details(data) {
        //console.log('inhouse');
        //console.log(data.itemDetails) ;
        //console.log('inhouse');  
       // console.log('ssssssssssssssssssssssssssssssssssss');
         //console.log(data.shipmentorderclosuredetails);
         // console.log('ssssssssssssssssssssssssssssssssssss');
        $('#itemDetails').html('');
        let list = {
            data: data.itemDetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '8%', align: 'left',  readOnly: true },
                { type: 'text', title: 'Blend (%) / Content / \n Material', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Item Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Item Colour Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size / \n Dimension', width: '7%', align: 'center',  readOnly: true },
                { type: 'text', title: 'UOM', width: '7%', align: 'center',  readOnly: true },
                { title:'inHouse_id', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false
        };

        itemDetails_vm = new Vue({
            el: '#itemDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_in_house_details(data) {
        $('#inHouseQty').html('');
        let list = {
            data: data.inhousestatusdetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title: 'P.I. Ref. No.', width: '10%', align: 'center', type: 'text',  readOnly: true },
                { title: 'Invoice No.', width: '10%', align: 'center', readOnly: true },
                { title: 'Invoice\n Date', width: '10%', align: 'center', readOnly: true },
                { title: 'Item - Lot / Batch\n Ref. No.', width: '8%', align: 'center', readOnly: true },
                { title: 'Qty.', width: '6%', align: 'right', readOnly: true },
                { title: 'UOM', width: '6%', align: 'center', readOnly: true },
                { title: 'Rate Per Unit\n (Rs.)', width: '6%', align: 'right', readOnly: true },
                { title: 'Value\n (Rs.)', width: '6%', align: 'right', readOnly: true },
                { title: 'GST /\nIGST (%)', width: '6%', align: 'right', readOnly: true },
                { title: 'Total Value\n (Rs.)', width: '6%', align: 'right', readOnly: true },
                { title: 'Order Stock Recd. \n Date & Time', width: '8%', align: 'center', type: 'text', options: { format: 'DD/MM/YYYY' }, readOnly: true },
                { title: 'Storage Bin / \n Rack Ref. No.', width: '8%', align: 'center', type: 'text', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            footers: footer('in-house'),
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 6)
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 8)
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 9)
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 10)
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 11)
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        inHouseQty_vm = new Vue({
            el: '#inHouseQty',
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

    function append_item_accept_status(data) {
        let mi_type = [
            { 'id': "1", 'name': 'EXTERNAL' },
            { 'id': "2", 'name': 'INTERNAL' }
        ];

        console.log(data.miindentreceiveddetails)
        $('#miIndentReceived').html('');
        let list = {
            data: data.miindentreceiveddetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'M.I. Ref. No.', width: '10%', align: 'left', readOnly: true },
                { type: 'text', title: 'Sample. Ref. No.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'M.I. Request \n Date & Time', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'M.I. Cutoff \n Date & Time', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'P.O. / Enq. Ref. No.', width: '6%', align: 'left', readOnly: true },
                { title: 'Combo', width: '8%', align: 'left', readOnly: true },
                { title: 'Component', width: '6%', align: 'left', readOnly: true, options: { format: 'DD/MM/YYYY' } },
                { title: 'Colour', width: '6%', align: 'left', readOnly: true },
                { title: 'M.I. Type \n Int. / Ext.', width: '6%', align: 'center', type: 'text', readOnly: true },
                { title: 'Issue to Dept. / \n Vendor Name', width: '8%', align: 'center', readOnly: true },
                { title: 'M.I. Qty.', width: '6%', align: 'right', type: 'text', readOnly: true },
                { title: 'M.I. Wise \n Issued Qty.', width: '6%', align: 'right', type: 'text', readOnly: true },
                { title: 'M.I. Wise \n Pending Qty.', width: '6%', align: 'right', type: 'text', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', type: 'text', readOnly: true },
                
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('received_detail'),
            
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 12)
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 13)
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 14)
                {
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
            }
        };

        miIndentReceived_vm = new Vue({
            el: '#miIndentReceived',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_in_house_consolidated_qty(data) {

        let issuedDD = [ 'PART', 'FULL' ];

        let miissueddetails = data.miissueddetails;
       

        $('#matrerialIssuedDetails').html('');
        let list = {    
            data: miissueddetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'Mark', width:'3%',align:'center',type:'checkbox'},
                { type: 'dropdown', title: 'M.I. Ref. No.', width: '8%', align: 'left', source: data.MIRefNo },
                { type: 'dropdown', title: 'Sample.Ref. No.', width: '8%', align: 'left', source: data.MINo, filter:miNoFilter },
                { type: 'text', title: 'Issue to Dept. / \n Vendor Name', width: '6%', align: 'left', readOnly: true },
                // { type: 'dropdown', title: 'Issue to Dept. / \n Vendor Name', width: '6%', align: 'left', source: data.deptList, filter:deptFilter },
                { type: 'text', title: 'D.C. No.', width: '6%', align: 'left', readOnly: true },
                { type: 'text', title: 'D.C. \n Date & Time', width: '6%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Item - Lot / Batch \n Ref. No.', width: '6%', align: 'left', source: data.lotNo },
                { type: 'dropdown', title: 'Rate Per \n Unit (Rs.)', width: '6%', align: 'left', source: data.rateList, filter: rateFilter },
                { title: 'Issued Qty.', width: '5%', align: 'right', readOnly: true },
                // { title: 'Issued in \n Part / Full', width: '5%', align: 'center', 'type': 'dropdown', source: issuedDD },
                // { title: 'M.I. \n Pending Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'Returned \n Defective Qty.', width: '5%', align: 'right' },
                { title: 'Replaced \n Defective Qty.', width: '5%', align: 'right' },
                { title: 'Returned \n Excess Qty.', width: '5%', align: 'right' },
                // { title: 'Lot Wise \n Balance Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                // { title: 'Average Rate Per Unit (Rs.)', width: '5%', align: 'center', readOnly: true },
                // { title: 'Balance Stock Value (Rs.)', width: '5%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('mi-issued'),
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2) 
                {
                    
                }
                if(col == 3) 
                {
                    
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 1)
                {
                    insertid = val;
                }
                if(col ==2)
                {
                    checkVal = val;
                }
                if(col == 3)
                {
                    
                    mi_id = val;
                    if(insertid != '') {
                         $(cell).text(val);
                         instance.jexcel.options.data[row][col] = val;
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 4)
                {
                    //console.log(val);
                    sam_val = val;
                    if(insertid != '') {
                         miIds = val;
                         sam_val = val;
                         $(cell).text(val);
                         instance.jexcel.options.data[row][col] = val;
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 5)
                {
                    if(mi_id != '' && insertid == '') {
                        let dataform = new FormData();
                        dataform.append('mi_id', mi_id);
                        dataform.append('reqId', reqId);
                        let request = $.ajax({
                        type: "POST",
                        url: base_path + 'request/Bomrequest/getDept',
                        data: dataform,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (data) {
                            // console.log(data);
                            $(cell).text(data);
                            instance.jexcel.options.data[row][col] = data;
                        },
                        error: function () {
                            console.log("Error");
                        }
                        });
                    }
                    if(insertid != '') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                
                if(col == 8) {
                    if(insertid != '') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                    // if(data.miissueddetails[row][col] != '' && insertid != '') {
                    //     $(cell).text(data.miissueddetails[row][col]);
                    //     instance.jexcel.options.data[row][col] = data.miissueddetails[row][col];
                    // }
                }
                
                if(col == 9) {
                    if(insertid != '') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                
                if(col == 10) {
                    if(val > 0) {
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
                    if(insertid != '') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
                if(col == 11) {
                    if(checkVal == true) {
                        $(cell).removeClass('readonly',);
                    } else {
                        $(cell).addClass('readonly');
                    }
                    if(val > 0) {
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
                }
                if(col == 12) {
                    if(checkVal == true) {
                        $(cell).removeClass('readonly',);
                    } else {
                        $(cell).addClass('readonly');
                    }
                    if(val > 0) {
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
                }
                if(col == 13) {
                    if(checkVal == true) {
                        $(cell).removeClass('readonly',);
                    } else {
                        $(cell).addClass('readonly');
                    }
                    if(val > 0) {
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
                }
                
                
                
                if(col == 14)
                {
                    if(mi_id != '' && sam_val != '' && insertid == '' ) {
                        let dataform = new FormData();
                        dataform.append('mi_id', mi_id);
                        dataform.append('reqId', reqId);
                        dataform.append('itemCode',itemCode);
                        dataform.append('sam_val',sam_val);
                        // dataform.append('insertid',insertid);
                        
                        let request = $.ajax({
                        type: "POST",
                        url: base_path + 'request/Bomrequest/getMIUom1',
                        data: dataform,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (data) {
                            // console.log(data);
                            $(cell).text(data);
                            instance.jexcel.options.data[row][col] = data;
                        },
                        error: function () {
                            console.log("Error");
                        }
                        });
                    } else if(mi_id != '' && sam_val != '' && insertid != '' ) {
                        let dataform = new FormData();
                        dataform.append('mi_id', mi_id);
                        dataform.append('reqId', reqId);
                        dataform.append('itemCode',itemCode);
                        dataform.append('sam_val',sam_val);
                        // dataform.append('insertid',insertid);
                        
                        let request = $.ajax({
                        type: "POST",
                        url: base_path + 'request/Bomrequest/getMIUom',
                        data: dataform,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (data) {
                            // console.log(data);
                            $(cell).text(data);
                            instance.jexcel.options.data[row][col] = data;
                        },
                        error: function () {
                            console.log("Error");
                        }
                        });
                    }
                    
                }
            }
        };

        mateiralIssuedDetails_vm = new Vue({
            el: '#matrerialIssuedDetails',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function deptFilter(instance, cell, c, r, source) {
        var item_id = instance.jexcel.getValueFromCoords(c - 1, r);
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id) return true;
            })
        } else {
            return [];
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

    function append_shipment_details(data) {
        //console.log('ttttttttttt');
        //console.log(data.shipmentorderclosuredetails)
           //console.log('ttttttttttt');

        let statusData = [
            { 'id': "0", 'name': 'PENDING' },
            { 'id': "1", 'name': 'APPROVED' },
            { 'id': "2", 'name': 'DISCREPANCY' }
        ];

        let orderStatusData = [
            { 'id': "0", 'name': 'PENDING' },
            { 'id': "1", 'name': 'APPROVED' },
            { 'id': "2", 'name': 'DISCREPANCY' }
        ];

        let stockData = [
            { 'id': "0", 'name': 'PENDING' },
            { 'id': "1", 'name': 'MOVED TO SSL' }
        ];
        
        let houseData = inHouseQty_vm.getData();
        let materialissuedData = mateiralIssuedDetails_vm.getData();
        let uom = '';
        let tol_rece_qty = tol_issued_qty = tol_defective_qty = defective_qty_replace = tol_excess_qty = tol_available_qty = 0;
        for (let i = 0; i < houseData.length; i++) {
            tol_rece_qty += parseFloat(houseData[i][11]);
        }

        for (let i = 0; i < materialissuedData.length; i++) {
            tol_issued_qty += parseFloat(materialissuedData[i][6]);
            tol_defective_qty += parseFloat(materialissuedData[i][9]);
            defective_qty_replace += parseFloat(materialissuedData[i][10]);
            tol_excess_qty += parseFloat(materialissuedData[i][11]);
            uom = materialissuedData[0][13];
            if(i == materialissuedData.length - 1)
            {
                tol_available_qty += parseFloat(materialissuedData[i][12]);
            }
        }
        
        let closureStatus = [
            { 'id': "0", 'name': 'PENDING' },
            { 'id': "1", 'name': 'ORDER CLOSED' }
        ];

        $('#shipmentOrderClosure').html('');
       
        let list = {
            data: data.shipmentorderclosuredetails,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},   
                { title:'id', width:'0%',align:'center',type:'hidden'},
                //{ type: 'text', title: 'Item Lot / \n Batch Ref. No.', width: '6%', align: 'left', readOnly: true },
                 { type: 'text', title: 'Item Lot / \n Batch Ref. No.', width: '6%', align: 'left', readOnly: true,source: data.lotNo },
                { type: 'text', title: 'Rate Per\n Unit', width: '6%', align: 'right', readOnly: true },
                { type: 'text', title: 'Sum Of \n Received Qty.', width: '6%', align: 'right', readOnly: true },
                { type: 'text', title: 'Sum of \n Issued Qty.', width: '6%', align: 'right', readOnly: true },
                { title: 'Sum Of \n Defective Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'Sum of Replaced \n Defective Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'Sum of Returned \n Excess Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'Lot Wise \n Balance Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true },
                { title: 'Balance Stock \n Value (Rs.)', width: '5%', align: 'right', readOnly: true },
                { title: 'Defective Stock \n Value (Rs.)', width: '5%', align: 'right', readOnly: true },
                { title: 'Lot Wise Total \n stock Value (Rs.)', width: '5%', align: 'right', readOnly: true },
                { type: 'dropdown', title: 'Order Closure \n Status', width: '6%', align: 'left', source: closureStatus },
                //{ title: 'Order Closure \n Status', width: '5%', align: 'center', type: 'dropdown', source: closureStatus,readOnly: true },
                { title: 'Status Update \n Date & Time', width: '5%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            footers: footer('open_close'),
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 3)
                {
                    rate = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 4)
                {
                    received_qty = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 5)
                {
                    issued_qty = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 6)
                {
                    def_qty = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 7)
                {
                    rdef_qty = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 8)
                {
                    excess_qty = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 9)
                {
                    
                    //bal_qty = parseFloat(received_qty) - parseFloat(issued_qty) + parseFloat(def_qty) - parseFloat(rdef_qty) + parseFloat(excess_qty)
                   

                    ///bal_qty = parseFloat(received_qty) - parseFloat(issued_qty) - parseFloat(def_qty) - parseFloat(rdef_qty) + parseFloat(excess_qty)
                    bal_qty = parseFloat(received_qty) - parseFloat(issued_qty)  - parseFloat(rdef_qty) + parseFloat(excess_qty)
                    
                    txtValue = numeral(bal_qty).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 11)
                {
                    total = parseFloat(bal_qty) * parseFloat(rate) ; 
                    txtValue = numeral(total).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 12)
                {
                    def_total = parseFloat(def_qty) * parseFloat(rate) ; 
                    txtValue = numeral(def_total).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 13)
                {
                    grd_total = parseFloat(total) + parseFloat(def_total) ; 
                    txtValue = numeral(grd_total).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 14)
                {
                    if(data.shipmentorderclosuredetails[row][15] != '') {
                        $(cell).addClass('readonly');
                    } else {
                        $(cell).removeClass('readonly');
                    }
                }
            }
        };

        shipmentOrderDetails_vm = new Vue({
            el: '#shipmentOrderClosure',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function validateForm(issued_data, order_data, draftMINo ) {
        let errorCount = 0;
        //console.log(issued_data);
         //console.log(order_data);

        for (let i = 0; i < issued_data.length; i++) {
            if(issued_data[i][1] == "" && issued_data[i][6] == "") {
                let miId = issued_data[i][4];
                //console.log(miId);
                let qty = issued_data[i][10];
                    for(let j = 0; j < order_data.length; j++) {
                        if(order_data[j][1] == miId ) {
                            let totalQty = order_data[j][14];
                            //   console.log(miId);
                            if(qty > totalQty) {
                                errorCount++;  
                            } 
                        }
                    }
                
            }
        }
         
        return errorCount;
    }
    
    function validateMIForm(issued_data, draftMINo ) {
        let errorCount = 0;
        for (let i = 0; i < issued_data.length; i++) {
            if(issued_data[i][3] != "" && issued_data[i][6] == "") {
                const miId = issued_data[i][3];
                if(miId == draftMINo || draftMINo == '' ) {
                    
                } else {
                  errorCount++;
                }
            }
        }
         
        return errorCount;
    }
    
    function validateFillForm(issued_data) {
    let errorCount = 0;
    let validateFields = [2,3,4,8,9,10];
        for (let i = 0; i < issued_data.length; i++) {
                for(let j = 0; j < validateFields.length; j++) {
                    let col = validateFields[j]
                    if(issued_data[i][col] == "") {
                        errorCount++;
                    }
                }
            }
        return errorCount;
    }
    
    function validateStatusForm(shipmentData) {
        let errorCount = 0;
        // for (let i = 0; i < shipmentData.length; i++) {
        //     if(shipmentData[i][13]  == 0 && shipmentData[i][14]  == "") {
        //       errorCount++;
        //     }
        // }
        
        for (let i = 0; i < shipmentData.length; i++) {
            if(shipmentData[i][14]  == 0 || shipmentData[i][14]  == '') {
              errorCount++;
            }
        }
         
        return errorCount;
    }

    $('#getValues').click(function () {
        
            // *** CONFIRMATION MESSAGE *** //
            let issued_data = mateiralIssuedDetails_vm.getData();
            //let selecteddata=
            let order_data = miIndentReceived_vm.getData();
            let draftMINo = $('#draftMINo').val();
            let validateReqCount = validateForm(issued_data, order_data, draftMINo);
            alert(validateReqCount);
            let validateMIFormCount = validateMIForm(issued_data, draftMINo);
            let validateFillFormCount = validateFillForm(issued_data);
            
            if(validateReqCount > 0) {
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('qtycheck')
                )
            } else if(validateMIFormCount > 0) {
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('micheck')
                )
            } else if(validateFillFormCount > 0) {
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('selecterror')
                )
            } else {
                swalWithBootstrapButtons.fire(
                alertMessageFunction('confirmation_save')
                ).then(function (result) {
                if (result.value) {
                    let in_house_qty = inHouseQty_vm.getData();
                    let mi_issued_details = mateiralIssuedDetails_vm.getData();
                    let shipment_order_details = shipmentOrderDetails_vm.getData();
                    updateFunction(in_house_qty, mi_issued_details, shipment_order_details);
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

    function updateFunction(in_house_qty, mi_issued_details, shipment_order_details) {
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('itemCode', itemCode);
        dataform.append('in_house_qty', JSON.stringify(in_house_qty));
        dataform.append('mi_issued_details', JSON.stringify(mi_issued_details));
        dataform.append('shipment_order_details', JSON.stringify(shipment_order_details));

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateOrderStockDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                         //window.location.href = base_path + 'company/Mstoreuser/orderstocklist';
                         window.location.href = base_path + 'request/Bomrequest/orderstockdetails' + '/' + encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(reqId)) + '/itemCode/' + encodeURIComponent(btoa(itemCode)) + '/pId/' + encodeURIComponent(btoa(pId)) +'';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $('#status_update').click(function () {
                
                swalWithBootstrapButtons.fire(
                alertMessageFunction('confirmation_save')
                ).then(function (result) {
                if (result.value) {
                    let itemDetails = itemDetails_vm.getData();
                    let shipment_order_details = shipmentOrderDetails_vm.getData();
                    updateStatusFunction( shipment_order_details, itemDetails);
                }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
        
    });

     $('#moverOrderClosure').click(function () {
                
                swalWithBootstrapButtons.fire(
                alertMessageFunction('confirmation_save')
                ).then(function (result) {
                if (result.value) {
                    
                    updateclosurelist();
                }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
        
    });

     function updateclosurelist() {
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('itemCode', itemCode);
       

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateorderclosurelist',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                        window.location.href = base_path + 'company/Mstoreuser/orderclosurelist';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    
    function updateStatusFunction(shipment_order_details, itemDetails) {
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);
        dataform.append('shipment_order_details', JSON.stringify(shipment_order_details));
        dataform.append('itemDetails', JSON.stringify(itemDetails));

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateStatusDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                        window.location.href = base_path + 'company/Mstoreuser/orderstocklist';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $('#orderClose').click(function () {
            let shipmentData = shipmentOrderDetails_vm.getData();
            
            let validateReqCount = validateStatusForm(shipmentData);
            
            if(validateReqCount > 0) {
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('statuscheck')
                )
            } else {
                
                swalWithBootstrapButtons.fire(
                alertMessageFunction('confirmation_save')
                ).then(function (result) {
                if (result.value) {
                    let itemDetails = itemDetails_vm.getData();
                    let shipment_order_details = shipmentOrderDetails_vm.getData();
                    let in_house_qty = inHouseQty_vm.getData();
                    updateCloseFunction( shipment_order_details, itemDetails, in_house_qty);
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
    
    function updateCloseFunction(shipment_order_details, itemDetails, in_house_qty) {
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);
        dataform.append('shipment_order_details', JSON.stringify(shipment_order_details));
        dataform.append('itemDetails', JSON.stringify(itemDetails));
        dataform.append('in_house_qty', JSON.stringify(in_house_qty));

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateOrderCloseDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                       // window.location.href = base_path + 'company/Mstoreuser/orderstocklist';
                          window.location.href = base_path + 'company/Mstoreuser/itemList';
                    }
                });
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