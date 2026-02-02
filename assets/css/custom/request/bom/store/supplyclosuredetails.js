$(document).ready(function () {

    getQABomRequest();

    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    var requirementData = [];
    var selectCount = 0;

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
        
        if(mode == "invalidPin") {
            return {
                title: 'Warning',
                text: "Invalid PIN!",
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

    // *********************************************************************************************************************************** 
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getQABomRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('pId', pId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getBomStoreDetails',
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
                { title:'Mark', width:'6%',align:'center',type:'checkbox'},
                { type: 'dropdown', title: 'Item Description', width: '8%', align: 'left',source:data.item_desc },
                { type: 'dropdown', title: 'Garment\n Size', width: '8%', align: 'left',source:data.garment_size },
                { type: 'dropdown', title: 'Approved\n Item Code', width: '8%', align: 'left',source:data.appr_item_code },
                { type: 'dropdown', title: 'Approved Item\n Colour Code', width: '8%', align: 'left', source:data.appr_item_col_code },
                { type: 'dropdown', title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', source:data.size_dim, readOnly: true },
                { type: 'dropdown', title: 'UOM', width: '7%', align: 'center', source:data.uom, readOnly: true},
                { title: 'D.C. No.', width: '15%', align: 'center' },
                { title: 'D.C. Date', width: '8%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' } },
                { title: 'Item - Lot / Batch\nRef.No.', width: '8%', align: 'right' },
                { title: 'D.C. Qty.', width: '8%', align: 'right' },
                { title: 'Invoice No.', width: '8%', align: 'center' },
                { title: 'Invoice Date', width: '8%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' } },
                { title: 'Invoice Qty.', width: '8%', align: 'right' },
                { title: 'Invoice Rate \n Per Unit', width: '8%', align: 'right' },
                { title: 'Currency', type:'dropdown', source:data.currency, width: '8%', align: 'left' },
                { title: 'Foreign\n Exch. Rate', width: '8%', align: 'right' },
                { title: 'Invoice Value\n (Rs.)', width: '8%', align: 'right', readOnly: true },
                { title: 'GST / IGST (%)', width: '8%', align: 'right' },
                { title: 'Total Invoice \n Value (Rs.)', width: '8%', align: 'right' , readOnly: true},
                { title: 'Received Qty.', width: '8%', align: 'right' },
                { title: 'UOM', width: '5%', align: 'center', type: 'dropdown', source: data.uomData },
                { title: 'Received Date', width: '6%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' } },
                { title: 'Storage Bin /\n Rack Ref. No.', width: '10%', align: 'center', type: 'text' },
                { title:'bom_id', width:'0%',align:'center',type:'hidden'},
                { title:'con_status', width:'0%',align:'center',type:'hidden'},
                { title:'check_status', width:'0%',align:'center',type:'hidden'},
                
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
            tableWidth: "100%",
            onchange: function(instance, cell, col, row, val, label, cellName) {
              if(col == 2) 
              {
                  
              }
              if(col == 3) 
              {
                  
              }
              if(col == 4) 
              {
                  
              }
              if(col == 5) 
              {
                  
              }
              if(col == 14) 
              {
                  
              }
              if(col == 15) 
              {
                  
              }
              if(col == 17) 
              {
                  
              }
              if(col == 19) 
              {
                  
              }
              
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col === 0) {
                    inHouseId = val;
                }
                if(col == 1) {
                    if(data.inhousestatusdetails[row][26] == 'Consolidated' && data.inhousestatusdetails[row][27] === false) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 2) {
                     if(val !== '') {
                        item_val = val;  
                     } else {
                         item_val = '';
                     }
                     if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                    
                }
                if(col == 3) {
                    if(val !== '') {
                        size = val;
                    } else {
                        size = '';
                    }
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                    
                }
                
                if(col == 4) {
                    if(val !== '') {
                        appr_item_code = val;
                    } else {
                        appr_item_code = '';
                    }
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                    
                }
                
                if(col == 5) {
                    if(val !== '') {
                        appr_item_col_code = val;
                    } else {
                        appr_item_col_code = '';
                    }
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                
                if(col == 6) {
                    
                    if(item_val !== '' && size !== '' && appr_item_code !== '' && appr_item_col_code !== ''  ) {
                        let dataform = new FormData();
                        dataform.append('pId', pId);
                        dataform.append('item_desc', item_val);
                        dataform.append('size', size);
                        dataform.append('appr_item_code', appr_item_code);
                        dataform.append('appr_item_col_code', appr_item_col_code);
                        let request = $.ajax({
                        type: "POST",
                        url: base_path + 'request/Bomrequest/getItemData',
                        data: dataform,
                        processData: false,
                        contentType: false,
                        cache: false,
                            success: function (data) {
                                rcdata = $.parseJSON(data);
                                    $.each(rcdata,function(key,value){
                                        if(key == 'size_dim') {
                                            size_dim = value;
                                        }
                                        if(key == 'uom') {
                                            uom = value;
                                        }
                                    });
                                $(cell).text(size_dim);
                                instance.jexcel.options.data[row][col] = size_dim;
                            },
                            error: function () {
                                console.log("Error");
                            }
                        });
                    }
                }
                
                if(col == 7) {
                    
                    if(item_val !== '' && size !== '' && appr_item_code !== '' && appr_item_col_code !== ''  ) {
                        let dataform = new FormData();
                        dataform.append('pId', pId);
                        dataform.append('item_desc', item_val);
                        dataform.append('size', size);
                        dataform.append('appr_item_code', appr_item_code);
                        dataform.append('appr_item_col_code', appr_item_col_code);
                        let request = $.ajax({
                        type: "POST",
                        url: base_path + 'request/Bomrequest/getItemData',
                        data: dataform,
                        processData: false,
                        contentType: false,
                        cache: false,
                            success: function (data) {
                                rcdata = $.parseJSON(data);
                                    $.each(rcdata,function(key,value){
                                        if(key == 'size_dim') {
                                            size_dim = value;
                                        }
                                        if(key == 'uom') {
                                            uom = value;
                                        }
                                    });
                                $(cell).text(uom);
                                instance.jexcel.options.data[row][col] = uom;
                            },
                            error: function () {
                                console.log("Error");
                            }
                        });
                    }
                    
                }
                if(col === 8) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col === 9) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col === 10) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col === 11) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col === 12) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col === 13) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                
                if(col == 14) {
                    txtValue = numeral(val).format('0.00');
                    inv_qty = txtValue;
                    if(inv_qty != 0 && inv_rate != 0 && exch_rate != 0) {
                        tot_amt = parseFloat(inv_qty) * parseFloat(inv_rate) * parseFloat(exch_rate);
                        tot_amts = tot_amt.toFixed(3);
                    }
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col == 15) {
                    txtValue = numeral(val).format('0.00');
                    inv_rate = txtValue;
                    if(inv_qty != 0 && inv_rate != 0 && exch_rate != 0) {
                        tot_amt = parseFloat(inv_qty) * parseFloat(inv_rate) * parseFloat(exch_rate);
                        tot_amts = tot_amt.toFixed(3);
                    }
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col === 16) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col == 17) {
                    tot_amts = 0;
                    txtValue = numeral(val).format('0.00');
                    exch_rate = txtValue;
                    if(inv_qty != 0 && inv_rate != 0 && exch_rate != 0) {
                        tot_amt = parseFloat(inv_qty) * parseFloat(inv_rate) * parseFloat(exch_rate);
                        tot_amts = tot_amt.toFixed(3);
                    }
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }

                if (col == 18) 
                {
                    $(cell).text(tot_amts);
                    instance.jexcel.options.data[row][col] = tot_amts;
                    //tot_amts = 0;
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col == 19) {
                    txtValue = numeral(val).format('0.00');
                    gstAmt = 0;
                    gst = txtValue;
                    if(gst != 0 && tot_amts != 0) {
                        gstAmt = parseFloat(tot_amts) * parseFloat(gst) / 100;
                        totAmt = parseFloat(tot_amts) + parseFloat(gstAmt);
                        totAmts = totAmt.toFixed(2);
                    }
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col == 20) {
                    gstAmt = 0;
                    totAmts = 0;
                    if(gst != 0 && tot_amts != 0) {
                        gstAmt = parseFloat(tot_amts) * parseFloat(gst) / 100;
                        totAmt = parseFloat(tot_amts) + parseFloat(gstAmt);
                        totAmts = totAmt.toFixed(2);
                      // console.log(totAmts);
                    }
                    $(cell).text(totAmts);
                    instance.jexcel.options.data[row][col] = totAmts;
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col === 21) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col === 22) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col === 23) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col === 24) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                }
                if(col == 25) {
                    
                    if(item_val !== '' && size !== '' && appr_item_code !== '' && appr_item_col_code !== ''  ) {
                        let dataform = new FormData();
                        dataform.append('pId', pId);
                        dataform.append('item_desc', item_val);
                        dataform.append('size', size);
                        dataform.append('appr_item_code', appr_item_code);
                        dataform.append('appr_item_col_code', appr_item_col_code);
                        let request = $.ajax({
                        type: "POST",
                        url: base_path + 'request/Bomrequest/getItemData',
                        data: dataform,
                        processData: false,
                        contentType: false,
                        cache: false,
                            success: function (data) {
                                rcdata = $.parseJSON(data);
                                    $.each(rcdata,function(key,value){
                                        if(key == 'request_bom_id') {
                                            request_bom_id = value;
                                        }
                                        
                                    });
                                $(cell).text(request_bom_id);
                                instance.jexcel.options.data[row][col] = request_bom_id;
                            },
                            error: function () {
                                console.log("Error");
                            }
                        });
                    }
                    
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

    // function append_in_house_details(data) {
    //     // console.log(data.bomAppendData);
    //     let inv_qty = 0 ;
    //     let inv_rate = 0 ;
    //     let exch_rate = 0;
    //     let tot_amt = 0;
    //     let tot_amts = 0;
    //     $('#inHouseStatus').html('');
    //     let list = {
    //         data: data.inhousestatusdetails,
    //         columns: [
    //             //{ title:'mode', width:'0%',align:'center',type:'hidden'},
    //             { title:'id', width:'0%',align:'center',type:'hidden'},
    //             { title:'Mark', width:'6%',align:'center',type:'checkbox'},
    //             { type: 'text', title: 'Item Description', width: '8%', align: 'left', readOnly: true},
    //             { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left', filter: sizeFilter , readOnly: true},
    //             { type: 'text', title: 'Approved\n Item Code', width: '8%', align: 'left', filter: itemFilter, readOnly: true },
    //             { type: 'text', title: 'Approved Item\n Colour Code', width: '8%', align: 'left',  filter: colorFilter , readOnly: true},
    //             { type: 'text', title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', filter: diaFilter, readOnly: true },
    //             { type: 'text', title: 'UOM', width: '7%', align: 'center',  filter: uomFilter , readOnly: true},
    //             { title: 'D.C. No.', width: '15%', align: 'center' },
    //             { title: 'D.C. Date', width: '8%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' } },
    //             { title: 'Item - Lot / Batch\nRef.No.', width: '8%', align: 'right' },
    //             { title: 'D.C. Qty.', width: '8%', align: 'right' },
    //             { title: 'Invoice No.', width: '8%', align: 'center' },
    //             { title: 'Invoice Date', width: '8%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' } },
    //             { title: 'Invoice Qty.', width: '8%', align: 'right' },
    //             { title: 'Invoice Rate \n Per Unit', width: '8%', align: 'right' },
    //             { title: 'Currency', type:'dropdown', source:data.currency, width: '8%', align: 'left' },
    //             { title: 'Foreign\n Exch. Rate', width: '8%', align: 'right' },
    //             { title: 'Invoice Value\n (Rs.)', width: '8%', align: 'right', readOnly: true },
    //             { title: 'GST / IGST (%)', width: '8%', align: 'right' },
    //             { title: 'Total Invoice \n Value (Rs.)', width: '8%', align: 'right' , readOnly: true},
    //             { title: 'Received Qty.', width: '8%', align: 'right' },
    //             { title: 'UOM', width: '5%', align: 'center', type: 'dropdown', source: data.uomData },
    //             { title: 'Received Date', width: '6%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' } },
    //             { title: 'Storage Bin /\n Rack Ref. No.', width: '10%', align: 'center', type: 'text' },
    //             { title:'bom_id', width:'0%',align:'center',type:'hidden'},
                
    //         ],
    //         minDimensions: [4, 1],
    //         allowDeleteColumn: false,
    //         allowInsertRow: true,
    //         allowInsertColumn: false,
    //         tableOverflow: true,
    //         tableWidth: "100%",
    //         onchange: function(instance, cell, col, row, val, label, cellName) {
    //           if(col == 14) 
    //           {
                  
    //           }
    //           if(col == 15) 
    //           {
                  
    //           }
    //           if(col == 17) 
    //           {
                  
    //           }
    //           if(col == 19) 
    //           {
                  
    //           }
    //         },

    //         updateTable: function(instance, cell, col, row, val, label, cellName) {
    //             if(col == 14) {
    //                 txtValue = numeral(val).format('0.00');
    //                 inv_qty = txtValue;
    //                 if(inv_qty != 0 && inv_rate != 0 && exch_rate != 0) {
    //                     tot_amt = parseFloat(inv_qty) * parseFloat(inv_rate) * parseFloat(exch_rate);
    //                     tot_amts = tot_amt.toFixed(3);
    //                 }
    //             }
    //             if(col == 15) {
    //                 txtValue = numeral(val).format('0.00');
    //                 inv_rate = txtValue;
    //                 if(inv_qty != 0 && inv_rate != 0 && exch_rate != 0) {
    //                     tot_amt = parseFloat(inv_qty) * parseFloat(inv_rate) * parseFloat(exch_rate);
    //                     tot_amts = tot_amt.toFixed(3);
    //                 }
    //             }
    //             if(col == 17) {
    //                 tot_amts = 0;
    //                 txtValue = numeral(val).format('0.00');
    //                 exch_rate = txtValue;
    //                 if(inv_qty != 0 && inv_rate != 0 && exch_rate != 0) {
    //                     tot_amt = parseFloat(inv_qty) * parseFloat(inv_rate) * parseFloat(exch_rate);
    //                     tot_amts = tot_amt.toFixed(3);
    //                 }
    //             }

    //             if (col == 18) 
    //             {
    //                 $(cell).text(tot_amts);
    //                 instance.jexcel.options.data[row][col] = tot_amts;
    //                 //tot_amts = 0;
    //             }
    //             if(col == 19) {
    //                 txtValue = numeral(val).format('0.00');
    //                 gstAmt = 0;
    //                 gst = txtValue;
    //                 if(gst != 0 && tot_amts != 0) {
    //                     gstAmt = parseFloat(tot_amts) * parseFloat(gst) / 100;
    //                     totAmt = parseFloat(tot_amts) + parseFloat(gstAmt);
    //                     totAmts = totAmt.toFixed(2);
    //                 }
    //             }
    //             if(col == 20) {
    //                 gstAmt = 0;
    //                 totAmts = 0;
    //                 if(gst != 0 && tot_amts != 0) {
    //                     gstAmt = parseFloat(tot_amts) * parseFloat(gst) / 100;
    //                     totAmt = parseFloat(tot_amts) + parseFloat(gstAmt);
    //                     totAmts = totAmt.toFixed(2);
    //                   // console.log(totAmts);
    //                 }
    //                 $(cell).text(totAmts);
    //                 instance.jexcel.options.data[row][col] = totAmts;
    //             }
                
    //         },
    //     };

    //     inHouseStatusReference_vm = new Vue({
    //         el: '#inHouseStatus',
    //         mounted: function () {
    //             let spreadsheet = jexcel(this.$el, list);
    //             Object.assign(this, spreadsheet);
    //         },
    //     });
    // }

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
                { title: 'D.C. Date', width: '6%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' }, readOnly: true },
                { title: 'Item - Lot / Batch\nRef.No.', width: '8%', align: 'right',readOnly: true },
                { title: 'D.C. Qty.', width: '6%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', type: 'dropdown', source: data.uomData, readOnly: true },
                { title: 'Merchant Item \n Approval Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData, readOnly: true },
                { title: 'Merchant Status \n Update Date & Time', width: '8%', align: 'center', type: 'text' , readOnly: true},
                { title: 'Q.A. Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData },
                { title: 'Q.A. Status Update\n Date & Time', width: '8%', align: 'center', readOnly: true },
                { title: 'Management\n Overriding Status', width: '8%', align: 'center', type: 'dropdown', source: approvalStatusData , readOnly: true},
                { title: 'Management Status\n Update Date & Time', width: '8%', align: 'center', readOnly: true },
                { title:'bom_id', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 10) {
                    status = val;
                }
                if(col == 11) {
                    status_date = val;
                }
                if(col == 12)
                {
                    let date = data.itemacceptstatus[row][13];
                    if(status_date !=  '' && date == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
            }
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
           { 'id': "1", 'name': 'DISC. SUPPLY CLOSED' },
           { 'id': "2", 'name': 'SHORT SUPPLY - CLOSED' },
           { 'id': "3", 'name': 'FULL SUPPLY - CLOSED' },
           { 'id': "4", 'name': 'P.I. CANCELLED' }
        ];

        let itemRTIData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'DISCREPANCY' },
           { 'id': "2", 'name': 'READY TO ISSUE' }
        ];

        $('#inHouseConsolidatedQty').html('');
        //console.log(data.inhouseconsolidatedqtydetails);
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
                { title: 'P.I. Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'Received Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'Difference Qty.', width: '5%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', type: 'dropdown', source: data.uomData, readOnly: true },
                { title: 'Supply Closure\n Status', width: '8%', align: 'center', type: 'dropdown', source: supplyClosureData },
                { title: 'Status Update\n Date & Time', width: '8%', align: 'center', type: 'text' , readOnly: true},
                { title:'status', width:'0%',align:'center',type:'hidden'},

            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 7) 
                {
                    pi_qty = val;   
                }
                if(col == 8) 
                {
                    rec_qty = val;
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 9)
                {
                    diff_qty = parseFloat(pi_qty) - parseFloat(rec_qty);   
                    txtValue = numeral(diff_qty).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 11)
                {
                    statusDate = data.inhouseconsolidatedqtydetails[row][12];
                    if(statusDate == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
            }
        };

        inHouseConsolidatedReference_vm = new Vue({
            el: '#inHouseConsolidatedQty',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    // function getReferenceValue(data, status) {

    //     if(status == true) {
    //         let emparr = [];
    //         let length = data.length;
    //         for(let i=0; i < data.length; i++) {
    //             if(i < length-5) {
    //                 emparr.push(data[i])
    //             }
    //         }
    //         for(let i=0; i < 5; i++) {
    //             emparr.push("")
    //         }
    //         // console.log(emparr);
    //         requirementData.push(emparr);
    //         selectCount = selectCount+1;
    //     }
    //     else {
    //         // console.log(data[0])
    //         requirementData = requirementData.filter(function(e) { if(e[0]!== data[0]) return e  })
    //         selectCount = selectCount-1;
    //     }
    //     //append_attach_reference();
    // }

    
    $('#orderStockList').click(function () {
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation')
        ).then(function (result) {
            if (result.value) {
                let inHouseStatus= inHouseStatusReference_vm.getData();
                updatedOrderStockList(inHouseStatus);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });

    function updatedOrderStockList(inHouseStatus) {
        let dataform = new FormData();
        dataform.append('inHouseStatus', JSON.stringify(inHouseStatus));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/moveToOrderStockList',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then((okay) => {
                    if(okay)
                    {
                        window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    function validateForm(validateField, dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            let validateFields = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24];
                for(let j = 0; j < validateFields.length; j++) {
                    let col = validateField[j]
                    
                    if(dataValue[i][col] == "") {
                        console.log(col);
                        errorCount++;
                    }
                }
            }
        return errorCount;
    }
    
    function validateAcceptForm(dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][11] == "") {
                errorCount++;
            }
        }
        return errorCount;
    }
    
    function validateConsolidatedForm(dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][11] == "") {
                errorCount++;
            } else {
                if(dataValue[i][11] == 0) {
                    errorCount++;
                }
            }
        }
        return errorCount;
    }
    
    function validateSupplyClosedForm(dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][11] == 0 ) {
                errorCount++;
            } 
            if(dataValue[i][12] == "" || dataValue[i][12] == null ) {
                errorCount++;
            }
        }
        return errorCount;
    }
    
    $('#getValues').click(function () {
        let inHouseData = inHouseStatusReference_vm.getData();
        let itemAccept = itemAcceptStatusReference_vm.getData();
        let inHouseConsolidate = inHouseConsolidatedReference_vm.getData();
        let validateField = [8,9,10,11,12,13,14,15,16,17,19,21,22,23,24];
        let validatedErrorCount = validateForm(validateField, inHouseData);
        //console.log(validatedErrorCount);
        if(validatedErrorCount > 0 ) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                );
        } else {
            swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    
                    updateFunction(inHouseData, itemAccept, inHouseConsolidate);
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

    function updateFunction(inHouseData, itemAccept, inHouseConsolidate) {
        let dataform = new FormData();
        dataform.append('inHouseData', JSON.stringify(inHouseData));
        dataform.append('itemAccept', JSON.stringify(itemAccept));
        dataform.append('inHouseConsolidate', JSON.stringify(inHouseConsolidate));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateStorePiDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then((okay) => {
                    if(okay)
                    {
                         window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    
    $('#acceptSave').click(function () {
         let itemAccept = itemAcceptStatusReference_vm.getData();
        // let validatedErrorCount = validateAcceptForm(itemAccept);
        
            swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    
                    // updateAcceptFunction(itemAccept);
                     $('#pin').val('');
                     $('#acceptModal').modal('show');
                    
                } 
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
        
    });
    $('#modal_save').click(function () {
        var pin_val = '1234';
        var pin = $('#pin').val();
        if(pin_val == pin) {
            $('#acceptModal').modal('hide');
            let itemAccept = itemAcceptStatusReference_vm.getData();
            updateAcceptFunction(itemAccept);
        } else {
            $('#acceptModal').modal('hide');
            swalWithBootstrapButtons.fire(
                alertMessageFunction('invalidPin')
            );
        }
        
    });
    
    function updateAcceptFunction(itemAccept)
    {
        let dataform = new FormData();
        dataform.append('itemAccept', JSON.stringify(itemAccept));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateStoreAcceptDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then((okay) => {
                    if(okay)
                    {
                         window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $('#save').click(function () {
         let consolidated = inHouseConsolidatedReference_vm.getData();
         let validatedErrorCount = validateConsolidatedForm(consolidated);
        if(validatedErrorCount > 0) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        } else {
            swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    
                    updateSupplyClosedFunction(consolidated);
                     
                    
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
    
    function updateSupplyClosedFunction(consolidated)
    {
        let dataform = new FormData();
        dataform.append('consolidated', JSON.stringify(consolidated));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateSupplyClosedDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then((okay) => {
                    if(okay)
                    {
                         window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $('#supplyClosed').click(function () {
        let inHouseStatus= inHouseStatusReference_vm.getData();
         let consolidated = inHouseConsolidatedReference_vm.getData();
         let validatedErrorCount = validateSupplyClosedForm(consolidated);
        if(validatedErrorCount > 0) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
        } else {
            swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    
                    updateSupplyClosureFunction(consolidated,inHouseStatus);
                     
                    
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
    
    function updateSupplyClosureFunction(consolidated, inHouseStatus)
    {
        let dataform = new FormData();
        dataform.append('inHouseStatus', JSON.stringify(inHouseStatus));
        dataform.append('consolidated', JSON.stringify(consolidated));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pId', pId);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateSupplyClosureDetails',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then((okay) => {
                    if(okay)
                    {
                         window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
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