$(document).ready(function () {

    getDraftPIRequest();
    getMerchantImages();
    getPurchaseImages();
    
    let purchase_mode = '';
    $('#withinStateDetails').show();
    //$('#interStateDetails').hide();
    //$('#importsStateDetails').hide();

    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });
    
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
        total = (total > 0) ? total : ''
        return total;
    }

    ADVCOL = function(instance) {
        var total = 0;
        let tbl_data = paymentRequest_vm.getData();
        adv_paid = tbl_data[0][12];
        total = numeral(adv_paid).format('0.00');
        total = (total > 0) ? total : '';
        adv_paid = total;
        return total;
    }

    AMTPAY = function(instance) {        
        var total = 0;
        inv_val = 0; debit = 0; amt_pay = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            inv_val += Number(instance.records[j][9].innerHTML);
            debit += Number(instance.records[j][11].innerHTML);
        }
        total = inv_val - (Number(adv_paid) + debit);
        total = numeral(total).format('0.00');
        total = (total > 0) ? total : '';
        amt_pay = total;
        return amt_pay;
    }
     
    function footer(grid_name)
    {
        if(grid_name == 'within')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
        else if(grid_name == 'inter')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
        else if(grid_name == 'import')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")' ]];
        }
        else if(grid_name == 'bill_invoice')
        {
            return [[ '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '=ADVCOL(TABLE())', '=GPWSUMCOL(TABLE(), COLUMN(), "")', '', '', '=AMTPAY(TABLE())', ''  ]];
        }
        else if(grid_name == 'bill_paid')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), COLUMN(), "")', ''  ]];
        }
    }

    // *********************************************************************************************************************************** 
    // Purchase REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getDraftPIRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('pId', pId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getsurplusissuedetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                bom_requirement_data = JSON.parse(data);
                console.log(bom_requirement_data);
                //let fData = bom_requirement_data.fullData[0];
                
                
                //append_within_state(bom_requirement_data);
                append_material_issued_details(bom_requirement_data);
                // append_purchase_request(bom_requirement_data);
                // append_payment_request_bill(bom_requirement_data);
                // append_payment_paid_request(bom_requirement_data);
                // append_request_payment_log(bom_requirement_data);
                // append_inter_state(bom_requirement_data);
                // append_imports_state(bom_requirement_data);
                // appendAddressField(bom_requirement_data.vendor_data, bom_requirement_data.vendor_id);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getMerchantImages()
    {
        $('.ImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
         data.append('pId', pId);
        data.append('type', 'bom_request');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getbomrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                 let subscriberId = imageJSON.subscriber_id;
                
                 for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.ImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON.images[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/bom/'+subscriberId+'/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/bom/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    // '<a href="javascript:void(0);" data-id='+subscriberId+'/'+imageJSON.images[i].image_url+' class="deleteImg" title="Delete">'+
                                    //     '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
                                    // '</a>'+
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    function append_material_issued_details_old(data) {
         let count = 0;
         
        console.log(data.issued_details);

        //var enq=data.mienqur_id;
        
        let stockDD = [ 'STOCK TRAN.', 'SURPLUS TRAN.' ];

        $('#materialIssuedDetails').html('');
        let list = {
            data: data.issued_details,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '3%' },
                { type: 'dropdown', title: 'P.I. Ref. No', width: '12%', align: 'left', source:data.piRefNo, },
                { type: 'dropdown', title: 'Transfer\nCategory', width: '6%', align: 'left',source:stockDD ,},
                { type: 'dropdown', title: 'Item Description', width: '6%', align: 'left',source:data.itemdescriptions,},
                { type: 'text' ,title: 'Garment Size', width: '6%', align: 'text',  readOnly: true },
                { type: 'text', title: 'Item Code', width: '8%', align: 'left',  },
                { type: 'text', title: 'Item Colour Code', width: '8%', align: 'left', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Dimension ', width: '8%', align: 'left',  },
                { type: 'text', title: 'UOM', width: '8%', align: 'left', },
                { type: 'dropdown', title: 'Invoice No.', width: '6%', align: 'left', source:data.inv_no,},
                { type: 'text', title: 'Invoice Date', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Item Lot / Batch Ref No.', width: '6%', align: 'left',source:data.lot_no },
                { type: 'dropdown', title: 'Rate Per Unit (Rs.)', width: '6%', align: 'left', source:data.rateList,filter: rateFilter},
                { type: 'text', title: 'GST', width: '8%', align: 'right'  },
                { type: 'text', title: 'Issued Qty.', width: '8%', align: 'right'  },

                { title: 'UOM', width: '6%', align: 'text',  readOnly: true },
                { title:'enquiry_id', width:'0%',align:'center',type:'hidden',},
                
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {


                 if(col == 2) 
                    {
                        count++;
                        if(val === true) {
                            selectCount = selectCount+1;
                        } else {
                            selectCount = selectCount-1;
                        }

                        console.log(selectCount);
                    }
                if(col == 4) {
    //                 var bom_id = 0;
    // let selectedItem = data.itemdescription.find(item => item.name === newValue); // Corrected here, use newValue instead of instance
    // if (selectedItem) {
    //     bom_id = selectedItem.id;
    //     console.log('Bom ID:', bom_id);  // You can use bom_id further
    //     // You can perform actions with the bom_id like updating the cell or some other logic
    // }



                    
                }
                
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
                        //$(cell).addClass('readonly');
                    }
                }
                if(col == 4) {
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).text(val);
                        instance.jexcel.options.data[row][col] = val;
                        //$(cell).addClass('readonly');
                    }
                }
                 if(col == 5) {
                    itemdes=val;
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        //$(cell).text(val);
                        instance.jexcel.options.data[row][col] = val;
                        //$(cell).addClass('readonly');
                    }
                  
                }

                 if (col == 6) {
   

                    let bom_id = instance.jexcel.getValueFromCoords(col - 1, row);
                    if (bom_id !== '') {
                    let gms = data.garmentSize;
                    let obj = gms.find(o => o.id == bom_id);
                     if (obj) {
                     $(cell).text(obj.name);
                      instance.jexcel.options.data[row][col] = obj.name;
                     } 
                         }
}
                
                  if(col == 7) 
                    {
                        //alert(bom_id);
                         
                        let bom_id = instance.jexcel.getValueFromCoords(col - 2, row);
                        //let bom_id = '364';
                        if(bom_id !== '') {
                             //$(cell).text('pavi');
                            let gms = data.itemCode;
                            let obj = gms.find(o => o.id == bom_id);
                            if(obj) {
                                  $(cell).text(obj.name);
                                   instance.jexcel.options.data[row][col] = obj.name;
                                //instance.jexcel.options.data[row][col] = obj.name;
                            }
                        }
        
                }
                
                  if(col == 8) 
                    {
                        //alert(bom_id);
                         
                        let bom_id = instance.jexcel.getValueFromCoords(col - 3, row);
                        
                        if(bom_id !== '') {
                            
                            let gms = data.itemColorCode;
                            let obj = gms.find(o => o.id == bom_id);
                            if(obj) {
                                  $(cell).text(obj.name);
                                   instance.jexcel.options.data[row][col] = obj.name;
                                //instance.jexcel.options.data[row][col] = obj.name;
                            }
                        }
        
                }
                 if(col == 9) 
                    {
                        //alert(bom_id);
                         
                        let bom_id = instance.jexcel.getValueFromCoords(col - 4, row);
                        
                        if(bom_id !== '') {
                             //$(cell).text('pavi');
                            let sdim = data.sizedim;
                            let obj = sdim.find(o => o.id == bom_id);
                            if(obj) {
                                  $(cell).text(obj.name);
                                   instance.jexcel.options.data[row][col] = obj.name;
                                //instance.jexcel.options.data[row][col] = obj.name;
                            }
                        }
        
                }
                if(col == 10) 
                    {
                        //alert(bom_id);
                         
                        let bom_id = instance.jexcel.getValueFromCoords(col - 5, row);
                        
                        if(bom_id !== '') {
                             //$(cell).text('pavi');
                            let uom = data.uom;
                            let obj = uom.find(o => o.id == bom_id);
                            if(obj) {
                                  $(cell).text(obj.name);
                                   instance.jexcel.options.data[row][col] = obj.name;
                                //instance.jexcel.options.data[row][col] = obj.name;
                            }
                        }
        
                }
                 if(col == 11) {
                    // //   let bom_id = instance.jexcel.getValueFromCoords(col - 6, row);
                    // //  if(bom_id != '') {
                    // //     let inv = data.inv_no;
                    // //     console.log(inv);
                    // //     let obj = inv.find(o => o.item_id === lot_no);
                    // //      $(cell).text(obj.name);
                    // //      instance.jexcel.options.data[row][col] = obj.name;
                    // // }
                    // let inv_codes = data.item_desc;
                    // let filtered = inv_codes.filter(item => item.name === 'WCL-1');
                    // console.log(filtered);
                    inv_no = val;
                   
                }
                if(col == 12) {
                    if(inv_no != '') {
                        let inv_date = data.inv_date;
                        let obj = inv_date.find(o => o.item_id === inv_no);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                    }
                   
                }
                 if(col == 13) {
                    lot_no = val;
                   
                }
                if(col == 15) {
                    if(lot_no != '') {
                        let gst = data.gstList;
                        console.log(gst);
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
                if(col == 16) {
                    if(issue_id == '') {
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        $(cell).removeClass('readonly');
                    } else {
                        //$(cell).addClass('readonly');
                    }
                }
                if(col == 17) {
                    //console.log()
                    console.log(data.uomList);
                    if(lot_no != '') {
                        let uom = data.uomList;
                        let obj = uom.find(o => o.item_id === lot_no);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                    }
                    // if(issue_id == '') {
                    //     $(cell).removeClass('readonly');
                    // } else {
                    //     $(cell).addClass('readonly');
                    // }
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
    function append_material_issued_details(data) {
         let count = 0;
         
        console.log(data.issued_details);

        //var enq=data.mienqur_id;
        
        let stockDD = [ 'STOCK TRAN.', 'SURPLUS TRAN.' ];

        $('#materialIssuedDetails').html('');
        let list = {
            data: data.issued_details,
            columns: [
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '3%' },
                { type: 'dropdown', title: 'P.I. Ref. No', width: '12%', align: 'left', source:data.piRefNo, },
                { type: 'dropdown', title: 'Transfer\nCategory', width: '6%', align: 'left',source:stockDD ,},
                { type: 'dropdown', title: 'Item Description', width: '6%', align: 'left',source:data.itemdescription,},
                { type: 'dropdown' ,title: 'Garment Size', width: '6%', align: 'text',  source:data.garmentSize,filter: sizeFilter },
                { type: 'dropdown', title: 'Item Code', width: '8%', align: 'left',source:data.itemCode,filter: itemFilter   },
                { type: 'dropdown', title: 'Item Colour Code', width: '8%', align: 'left', width: '8%', align: 'left', source:data.itemColorCode,filter: colorFilter },
                { type: 'dropdown', title: 'Size Dimension ', width: '8%', align: 'left', source:data.sizedim,filter: diaFilter, },
                { type: 'dropdown', title: 'UOM', width: '8%', align: 'left',source:data.uom,filter: uomFilter, },
                { type: 'dropdown', title: 'Invoice No.', width: '6%', align: 'left', source:data.inv_no,filter: invoicno,},
                { type: 'text', title: 'Invoice Date', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Item Lot / Batch Ref No.', width: '6%', align: 'left',source:data.lot_no,filter: lotno, },
                { type: 'dropdown', title: 'Rate Per Unit (Rs.)', width: '6%', align: 'left', source:data.rateList,filter: rateFilter},
                { type: 'text', title: 'GST', width: '8%', align: 'right'  },
                { type: 'text', title: 'Issued Qty.', width: '8%', align: 'right'  },

                { title: 'UOM', width: '6%', align: 'text',  readOnly: true },
                { title:'enquiry_id', width:'0%',align:'center',type:'hidden',},
                
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {


                 if(col == 2) 
                    {
                        count++;
                        if(val === true) {
                            selectCount = selectCount+1;
                        } else {
                            selectCount = selectCount-1;
                        }

                        console.log(selectCount);
                    }
                if(col == 4) {
    //                 var bom_id = 0;
    // let selectedItem = data.itemdescription.find(item => item.name === newValue); // Corrected here, use newValue instead of instance
    // if (selectedItem) {
    //     bom_id = selectedItem.id;
    //     console.log('Bom ID:', bom_id);  // You can use bom_id further
    //     // You can perform actions with the bom_id like updating the cell or some other logic
    // }



                    
                }
                
                if(col == 7) 
                {
                    
                }
                if(col == 9) 
                {
                    
                }
                  if(col == 16) 
                {
                      let issuedval = Number(val);
                     var item_id = instance.jexcel.getValueFromCoords(col - 9, row);
                     //alert(item_id);
                     let gst = data.bomqty;
                    let selectedItem = gst.find(item => item.size_id === item_id);
                    if (selectedItem) {
                        let bomqty = selectedItem.name;
                         if (issuedval > bomqty) {
                alert("Issued  Quantity should be less than or equal to  Qty" );
                 instance.jexcel.setValueFromCoords(col, row, '', true);
                
            } 
                    }
                    
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
                        //$(cell).addClass('readonly');
                    }
                }
                if(col == 4) {
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).text(val);
                        instance.jexcel.options.data[row][col] = val;
                        //$(cell).addClass('readonly');
                    }
                }
                 if(col == 5) {
                    itemdes=val;
                    
                    if(issue_id == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        //$(cell).text(val);
                        instance.jexcel.options.data[row][col] = val;
                        //$(cell).addClass('readonly');
                    }
                  
                }

                 if (col == 6) {
                   

                  
                     }
                
                  if(col == 7) 
                    {
                        // //alert(bom_id);
                         
                        // let bom_id = instance.jexcel.getValueFromCoords(col - 2, row);
                        // //let bom_id = '364';
                        // if(bom_id !== '') {
                        //      //$(cell).text('pavi');
                        //     let gms = data.itemCode;
                        //     let obj = gms.find(o => o.id == bom_id);
                        //     if(obj) {
                        //           $(cell).text(obj.name);
                        //            instance.jexcel.options.data[row][col] = obj.name;
                        //         //instance.jexcel.options.data[row][col] = obj.name;
                        //     }
                        // }
        
                }
                
                  if(col == 8) 
                    {
                        //alert(bom_id);
                         
                        // let bom_id = instance.jexcel.getValueFromCoords(col - 3, row);
                        
                        // if(bom_id !== '') {
                            
                        //     let gms = data.itemColorCode;
                        //     let obj = gms.find(o => o.id == bom_id);
                        //     if(obj) {
                        //           $(cell).text(obj.name);
                        //            instance.jexcel.options.data[row][col] = obj.name;
                        //         //instance.jexcel.options.data[row][col] = obj.name;
                        //     }
                        // }
        
                }
                 if(col == 9) 
                    {
                        uom=val;
                        //alert(bom_id);
                         
                        // let bom_id = instance.jexcel.getValueFromCoords(col - 4, row);
                        
                        // if(bom_id !== '') {
                        //      //$(cell).text('pavi');
                        //     let sdim = data.sizedim;
                        //     let obj = sdim.find(o => o.id == bom_id);
                        //     if(obj) {
                        //           $(cell).text(obj.name);
                        //            instance.jexcel.options.data[row][col] = obj.name;
                        //         //instance.jexcel.options.data[row][col] = obj.name;
                        //     }
                        // }
                        
        
                }
                if(col == 10) 
                    {
                        //alert(bom_id);
                         
                        // let bom_id = instance.jexcel.getValueFromCoords(col - 5, row);
                        
                        // if(bom_id !== '') {
                        //      //$(cell).text('pavi');
                        //     let uom = data.uom;
                        //     let obj = uom.find(o => o.id == bom_id);
                        //     if(obj) {
                        //           $(cell).text(obj.name);
                        //            instance.jexcel.options.data[row][col] = obj.name;
                        //         //instance.jexcel.options.data[row][col] = obj.name;
                        //     }
                        // }
        
                }
                 if(col == 11) {
                    // //   let bom_id = instance.jexcel.getValueFromCoords(col - 6, row);
                    // //  if(bom_id != '') {
                    // //     let inv = data.inv_no;
                    // //     console.log(inv);
                    // //     let obj = inv.find(o => o.item_id === lot_no);
                    // //      $(cell).text(obj.name);
                    // //      instance.jexcel.options.data[row][col] = obj.name;
                    // // }
                    // let inv_codes = data.item_desc;
                    // let filtered = inv_codes.filter(item => item.name === 'WCL-1');
                    // console.log(filtered);
                    inv_no = val;
                   
                }
                if(col == 12) {
                    if(inv_no != '') {
                        let inv_date = data.inv_date;
                        let obj = inv_date.find(o => o.item_id === inv_no);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                    }
                   
                }
                 if(col == 13) {
                    lot_no = val;
                   
                }
                if(col == 15) {
                    if(lot_no != '') {
                        let gst = data.gstList;
                        console.log(gst);
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
                if(col == 16) {
                    if(issue_id == '') {
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        $(cell).removeClass('readonly');
                    } else {
                        //$(cell).addClass('readonly');
                    }
                }
                if(col == 17) {
                    //console.log()
                    console.log(data.uomList);
                    if(lot_no != '') {
                        let uom = data.uomList;
                        let obj = uom.find(o => o.item_id === lot_no);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                    }
                    // if(issue_id == '') {
                    //     $(cell).removeClass('readonly');
                    // } else {
                    //     $(cell).addClass('readonly');
                    // }
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
function sizeFilter(instance, cell, c, r, source) {
        var item_id = instance.jexcel.getValueFromCoords(c - 1, r);
       //alert(item_id);
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
                //if (val.item_id == item_id && val.size_id == size_id) return true;
                 if ( val.item_id == size_id && val.item_des == item_id) return true;
            })
        } else {
            return [];
        }
    }

    function rateFilter(instance, cell, c, r, source) {
       
        var item_id = instance.jexcel.getValueFromCoords(c - 1, r);
         var item_code1 = instance.jexcel.getValueFromCoords(c - 7, r);
          var item_code = item_code1.replace(/\s+/g, '');
        //console.log(item_code);
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_code == item_code && val.item_id == item_id) return true;
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
                //if (val.item_id == item_id && val.size_id == size_id && val.item_code_id == item_code_id) return true;
                if ( val.item_id == item_code_id) return true;
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
                // if (val.item_id == item_id && val.size_id == size_id && val.item_code_id == item_code_id 
                //     && val.color_id == color_id) 
                     if ( val.id == item_code_id && val.size_id == size_id && val.item_id == color_id) 
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
                if ( val.size_id == size_id && val.id == item_code_id) 
                return true;
            })
        } else {
            return [];
        }
    }
    function invoicno(instance, cell, c, r, source) {
        
        var item_code1 = instance.jexcel.getValueFromCoords(c - 4, r);
        var item_code = item_code1.replace(/\s+/g, '');
       
        
        if (item_code !== "") {
            return source.filter(function (val) {
                if ( val.item_id == item_code) 
                return true;
            })
        } else {
            return [];
        }
    }
     function lotno(instance, cell, c, r, source) {
        
        var item_code1 = instance.jexcel.getValueFromCoords(c - 4, r);
         var item_code = item_code1.replace(/\s+/g, '');
        var invoicno = instance.jexcel.getValueFromCoords(c - 2, r);
       
       
       
        if (item_code !== "") {
            return source.filter(function (val) {
                if ( val.item_id == item_code && val.id == invoicno)  
                return true;
            })
        } else {
            return [];
        }
    }
    

    function rateFilter1(instance, cell, c, r, source) {
        var item_id = instance.jexcel.getValueFromCoords(c - 2, r);
         let inv_codes = data.item_desc;
        let filtered = inv_codes.filter(item => item.name === 'WCL-1');
       
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id  ) return true;
            })
        } else {
            return [];
        }
    }


     $('#save').click(function () {
        
          if(selectCount <= 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let surplus_pi_data = issuedDetals_vm.getData();
                 let save_status = 1;
                //console.log(surplus_pi_data);
                updateFunction(surplus_pi_data, save_status);
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

     $('#save1').click(function () {
        
          if(selectCount <= 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let surplus_pi_data = issuedDetals_vm.getData();
                //console.log(surplus_pi_data);
                 let save_status = 0;
                updateFunction(surplus_pi_data, save_status);
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
     $('#clear_data').click(function () {
        //alert(selectCount);
         
        swalWithBootstrapButtons.fire(
            // *** CONFIRMATION MESSAGE *** //
            alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let surplus_pi_data = issuedDetals_vm.getData();
                //console.log(surplus_pi_data);
                clearFunction(surplus_pi_data);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    
    });

    function clearFunction(data) {
       
        
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('purchase_id', pId);
        dataform.append('data', JSON.stringify(data));
        console.log(dataform);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/clearsurplusstockdatatransfer',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // getDraftPIRequest();
                // // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                        //window.location.href = base_path + 'request/Bomrequest/surpluspurchaseindentlist';
                        window.location.replace(window.location.href);
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }


    function updateFunction(data, save_status) {
        
        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', reqId);
        dataform.append('pid', pId);
        dataform.append('data', JSON.stringify(data));
        dataform.append('save_status', save_status);
        console.log(dataform);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updatesurplusstockdatatransfer',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // getDraftPIRequest();
                // // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay)
                    {
                       if(save_status == 1) {
                        
                          window.location.href = base_path + 'request/Bomrequest/surpluspurchaseindentdetailspiref/' 
    + encodeURIComponent(btoa(enquiry_id)) 
    + '/reqId/' + encodeURIComponent(btoa(reqId)) 
    + '/' + encodeURIComponent(btoa(pId));
                      } else {
                        
                          location.reload();
                      }
                  
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }



    function getPurchaseImages()
    {
        $('.purchaseImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'purchase_dept');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getbomrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                let subscriberId = imageJSON.subscriber_id;
               
                for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.purchaseImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON.images[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/bom/purchase/'+subscriberId+'/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/bom/purchase/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    // '<a href="javascript:void(0);" data-id='+imageJSON[i].wip_files_id+' class="deleteImg" title="Delete">'+
                                    //     '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
                                    // '</a>'+
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

   
    // *********************************************************************************************************************************** 
    // Purchase REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
    
    
    
    
    
       
   
    
   function bcmFilter(instance, cell, c, r, source) {
        let itemdesc = instance.jexcel.getValueFromCoords(c - 1, r);
        alert(itemdesc);
       

        // if (bcm_id != "" && item_id != "" && garment_id != "" && item_code_id != "") {
        //     return source.filter(function (item) {
        //         if ((item.item_id == item_id) && (item.bcm_id == bcm_id) && (item.garment_id == garment_id) && (item.item_code_id == item_code_id)) return true;
        //     })
        // } else {
        //     return [];
        // }
    }

    
    

   



    

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

    function appendAddressField(vendorData, vId) {
        vendorResponse = vendorData;        
        if(vendorResponse !="") {
            let vendorDet = vendorResponse.filter((data) => data.id === vId);
            $("#vendorName").html(vendorDet[0].vendorname);
            $("#vendorAddress").html(vendorDet[0].address);
            $("#vendorContact").html(vendorDet[0].phone);
            $("#vendorEmail").html(vendorDet[0].emailid);
            $("#vendorGst").html(vendorDet[0].gstno);
            $("#vendorIeCode").html(vendorDet[0].iecode);   
        }
        else {
            $("#vendorAddress").html("");
            $("#vendorContact").html("");
            $("#vendorEmail").html("");
            $("#vendorGst").html("");
            $("#vendorIeCode").html("");   
        }

    }

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});