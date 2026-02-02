$(document).ready(function () {

    getQABomRequest();
    getMerchantImages();
    getPurchaseImages();
    

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
        
        if(mode == "inhouse_error") {
            return {
                title: 'Warning',
                text: "Select atleast one item from BOM(A1) In-House Details",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        if(mode == "status_error") {
            return {
                title: 'Warning',
                text: "Supply closure status not saved",
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
    
    GPWSUMCOL = function(instance, columnId) {
        var total = 0;
        
        for (var j = 0; j < instance.options.data.length; j++) {
            //console.log(instance.records[j][29]);
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                
                if(instance.records[j][29].innerHTML == '2' || instance.records[j][29].innerHTML == '3') {
                    // console.log(instance.records[j][29]);
                } else {
                    total += Number(instance.records[j][columnId - 1].innerHTML);
                }
                
            }
        }
        total = numeral(total).format('0.00');
        //total = (total > 0) ? total : '';
        return total;
    }
    
    function footer(grid_name)
    {
        if(grid_name == 'item_footer')
        {
            return [[ '', '', '', '', '', '', '', '', '', '', '', '', '', '',  '', '', '', '', 'Total:', '=GPWSUMCOL(TABLE(), 19)', '', '=GPWSUMCOL(TABLE(), 21)', '',   ]];
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
              
                let data2=bom_requirement_data.inhousestatusdetails;
                let prderstocklastColumns = data2.map(array => array[array.length - 1]);
                 let supplystocklastColumns = data2.map(array => array[array.length - 2]);
                 let prderstockValue = prderstocklastColumns.some(value => value === '1') ? 1 : 0;
                 let supplystockValue = supplystocklastColumns.some(value => value === '1') ? 1 : 0;

               
                console.log(supplystockValue)
              
              
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

    
    function append_in_house_details(data) {
       
        //data.bomAppendData.itemDescription
        let inv_qty = 0 ;
        let inv_rate = 0 ;
        let exch_rate = 0;
        let tot_amt = 0;
        let tot_amts = 0;
        let currencyList = ["Select", "INR", "SGD", "HKD", "MYR", "USD", "EUR", "JPY", "GBP", "AUD", "CAD", "CHF", "CNH", "SEK","NZD"];
        $('#inHouseStatus').html('');
        const itemDescObj = data.bomAppendData.itemDescription || {};
        const itemDescArr = Object.values(itemDescObj); // Convert object to array of values
        const itemDescSource = itemDescArr.map(o => ({ id: o.id, name: o.name })); // Ensure dropdown gets {id, name}

        let list = {
            data: data.inhousestatusdetails,
            columns: [
                //{ title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'Mark', width:'6%',align:'center',type:'checkbox'},
                //{ type: 'dropdown', title: 'Item Description', width: '12%', align: 'left', source: data.bomAppendData.itemDescription },
                { type: 'dropdown', title: 'Item Description', width: '12%', align: 'left', source: itemDescSource }, // Use normalized source
                { type: 'dropdown', title: 'Garment\n Size', width: '8%', align: 'left', source: data.bomAppendData.garmentSize, filter: sizeFilter },
                { type: 'dropdown', title: 'Approved\n Item Code', width: '10%', align: 'left', source: data.bomAppendData.itemCode, filter: itemFilter },
                { type: 'dropdown', title: 'Approved Item\n Colour Code', width: '10%', align: 'left', source: data.bomAppendData.itemColorCode, filter: colorFilter },
                { type: 'dropdown', title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', source: data.bomAppendData.sizeDia, filter: diaFilter, readOnly: true },
                { type: 'dropdown', title: 'UOM', width: '7%', align: 'center', source: data.bomAppendData.uom, filter: uomFilter, readOnly: true },
                { title: 'D.C. No.', width: '15%', align: 'center' },
                { title: 'D.C. Date', width: '8%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' } },
                { title: 'Item - Lot / Batch\nRef.No.', width: '12%', align: 'right' },
                { title: 'D.C. Qty.', width: '10%', align: 'right' },
                { title: 'Invoice No.', width: '10%', align: 'center' },
                { title: 'Invoice Date', width: '10%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' } },
                { title: 'Invoice Qty.', width: '10%', align: 'right' },
                { title: 'Invoice Rate \n Per Unit', width: '8%', align: 'right' },
                { title: 'Currency', type:'dropdown', source:currencyList, width: '8%', align: 'left' },
                { title: 'Foreign\n Exch. Rate', width: '8%', align: 'right' },
                { title: 'Invoice Value\n (Rs.)', width: '10%', align: 'right', readOnly: true },
                { title: 'GST / IGST (%)', width: '8%', align: 'right' },
                { title: 'Total Invoice \n Value (Rs.)', width: '10%', align: 'right' , readOnly: true},
                { title: 'Received Qty.', width: '8%', align: 'right' },
                { title: 'UOM', width: '7%', align: 'center', type: 'dropdown', source: data.uomData },
                { title: 'Received Date', width: '8%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' } },
                { title: 'Storage Bin /\n Rack Ref. No.', width: '10%', align: 'center', type: 'text' },
                { title:'bom_id',type:'hidden'},
                { title:'con_status', type:'hidden'},
                { title:'check_status', type:'hidden'},
                { title:'supply_status', width:0,type:'hidden'},
                { title:'orderstock_status',type:'hidden'},
                 { title:'supply_status', type:'hidden', width: 0 },
                { title:'orderstock_status', type:'hidden', width: 0 }
            
            ],
             hiddenColumns: [30, 31], // Last two columns
            
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: true,
            allowInsertColumn: false,
            tableOverflow: true,
            footers: footer('item_footer'),
            tableWidth: "170%",
            //hiddenColumns: [30,31] ,
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
                    //inHouseId = val;
                    // inHouseId = '';
                }
                // if(col == 1) {
                //     let cl_status = data.itemacceptstatus[row][17];
                //     if(cl_status == 'Failed') {
                //         $(cell).css('background-color', '#FF8282');
                //         $(cell).css('color', '#FF8282');
                //     }
                // }
                if(col == 1) {
                    // if(data.inhousestatusdetails[row][26] === 'Consolidated' && data.inhousestatusdetails[row][27] === false) {
                    //     $(cell).removeClass('readonly');
                    // } else if(data.inhousestatusdetails[row][26] ==='' && data.inhousestatusdetails[row][27] === '') {
                    //     $(cell).removeClass('readonly');
                    // } else {
                    //     $(cell).addClass('readonly');
                    // }
                     let checkbox = data.inhousestatusdetails[row][1];
                   // console.log(checkbox)
                    if(checkbox == true) {
                       $('#getValues').removeClass('disabled'); 
                        inHouseId = '';
                    }else{
                        inHouseId = 'false';
                          
                    }
                        let orderstock = data.inhousestatusdetails[row][31];
                        if(orderstock == 1) {
                        $(cell).addClass('readonly'); 
                        
                            
                        }else{
                            $(cell).removeClass('readonly');
                        }
                        if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).addClass('readonly'); 
                        }
                    

                    //console.log(orderstock,'orderstock');

                    
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
                     
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    } else {
                        
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
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                       $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
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
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
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
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                         $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                
                if(col == 6) {
                    if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                         $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                    
                    if(item_val !== '' && size !== '' && appr_item_code !== '' && appr_item_col_code !== ''  ) {
                        
                        let sizeDia = data.bomAppendData.sizeDia;
                        let obj = sizeDia.find(o => o.item_id === appr_item_code);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
    
                     }
                }
                
                if(col == 7) {
                    if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                    
                    if(item_val !== '' && size !== '' && appr_item_code !== '' && appr_item_col_code !== ''  ) {
                        
                        let uom = data.bomAppendData.uom;
                        let obj = uom.find(o => o.item_id === appr_item_code);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                         
                    }
                    
                }
                if(col === 8) {
                    if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
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
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col === 10) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col === 11) {
                    txtValue = numeral(val).format('0.00');
                    if(val > 0) {
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col === 12) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col === 13) {
                    txtValue = numeral(val).format('0.00');
                    if(val > 0) {
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                         $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                
                if(col == 14) {
                    txtValue = numeral(val).format('0.00');
                    if(val > 0) {
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
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
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col == 15) {
                    txtValue = numeral(val).format('0.00');
                    if(val > 0) {
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
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
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                         $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col === 16) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                         $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col == 17) {
                    tot_amts = 0;
                    txtValue = numeral(val).format('0.00');
                    if(val > 0) {
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
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
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }

                if (col == 18) 
                {
                    $(cell).text(tot_amts);
                    instance.jexcel.options.data[row][col] = tot_amts;
                    
                    if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                         $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col == 19) {
                    txtValue = numeral(val).format('0.00');
                    if(val > 0) {
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
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
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                         $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
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
                    // if(inHouseId === '') {
                    //      $(cell).removeClass('readonly');
                    //  } else {
                    //      $(cell).addClass('readonly');
                    //  }
                    if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col === 21) {
                    txtValue = numeral(val).format('0.00');
                    if(val > 0) {
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                    }
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col === 22) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col === 23) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col === 24) {
                    if(inHouseId === '') {
                         $(cell).removeClass('readonly');
                     } else {
                         $(cell).addClass('readonly');
                     }
                     if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                }
                if(col == 25) {
                    if(data.inhousestatusdetails[row][26] == 'Failed' && data.inhousestatusdetails[row][28] == 'Yes') {
                        $(cell).css('background-color', '#fc0303ff');
                        $(cell).css('color', '#fc0303ff');
                    }
                    
                    if(item_val !== '' && size !== '' && appr_item_code !== '' && appr_item_col_code !== ''  ) {
                        
                        let req_bom_id = data.bomAppendData.req_bom_id;
                        let obj = req_bom_id.find(o => o.item_id === appr_item_code);
                         $(cell).text(obj.name);
                         instance.jexcel.options.data[row][col] = obj.name;
                         
                        // let dataform = new FormData();
                        // dataform.append('pId', pId);
                        // dataform.append('item_desc', item_val);
                        // dataform.append('size', size);
                        // dataform.append('appr_item_code', appr_item_code);
                        // dataform.append('appr_item_col_code', appr_item_col_code);
                        // let request = $.ajax({
                        // type: "POST",
                        // url: base_path + 'request/Bomrequest/getItemData',
                        // data: dataform,
                        // processData: false,
                        // contentType: false,
                        // cache: false,
                        //     success: function (data) {
                        //         rcdata = $.parseJSON(data);
                        //             $.each(rcdata,function(key,value){
                        //                 if(key == 'request_bom_id') {
                        //                     request_bom_id = value;
                        //                 }
                                        
                        //             });
                        //         $(cell).text(request_bom_id);
                        //         instance.jexcel.options.data[row][col] = request_bom_id;
                        //     },
                        //     error: function () {
                        //         console.log("Error");
                        //     }
                        // });
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
        
        let overridingStatusData = [
           { 'id': "0", 'name': 'PENDING' },
           { 'id': "1", 'name': 'APPROVED' },
           { 'id': "2", 'name': 'REPLACE ITEM' },
           { 'id': "3", 'name': 'RETURN ITEM & REORDER' },
           { 'id': "4", 'name': 'CANCEL P.I. & REORDER' }
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
                { type: 'text', title: 'Item Description', width: '12%', align: 'left', readOnly: true, },
                { type: 'text', title: 'Garment\n Size', width: '10%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '10%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '12%', align: 'left', readOnly: true },
                { title: 'D.C. No.', width: '15%', align: 'center', readOnly: true },
                { title: 'D.C. Date', width: '6%', align: 'center', type: 'calendar', options: { format: 'DD/MM/YYYY' }, readOnly: true },
                { title: 'Item - Lot / Batch\nRef.No.', width: '12%', align: 'right',readOnly: true },
                { title: 'D.C. Qty.', width: '6%', align: 'center', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', type: 'dropdown', source: data.uomData, readOnly: true },
                { title: 'Merchant Item \n Approval Status', width: '10%', align: 'center', type: 'dropdown', source: approvalStatusData, readOnly: true },
                { title: 'Merchant Status \n Update Date & Time', width: '12%', align: 'center', type: 'text' , readOnly: true},
                { title: 'Q.A. Status', width: '12%', align: 'center', type: 'dropdown', source: approvalStatusData },
                { title: 'Q.A. Status Update\n Date & Time', width: '12%', align: 'center', readOnly: true },
                { title: 'Management\n Overriding Status', width: '12%', align: 'center', type: 'dropdown', source: overridingStatusData , readOnly: true},
                { title: 'Management Status\n Update Date & Time', width: '13%', align: 'center', readOnly: true },
                { title:'bom_id', width:'0%',align:'center',type:'hidden'},
                { title:'closed_status', width:'0%',align:'center',type:'hidden'},
                { title:'col_status', width:'0%',align:'center',type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            tableOverflow: true,
            footers: footer('item_footer'),
            tableWidth: "130%",
            
            
            
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 1) {
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                }
                if(col == 2) {
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                }
                if(col == 3) {
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                }
                if(col == 4) {
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                }
                if(col == 5) {
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                }
                if(col == 6) {
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                }
                if(col == 7) {
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                }
                if(col == 8) {
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                }
                if(col == 9) {
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                }
                if(col == 10) {
                    status = val;
                    let mer_status = data.itemacceptstatus[row][10];
                    if(mer_status == "1") {
                        $(cell).css('background-color', '#5DE684');
                    } else if(mer_status == "2") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(mer_status == "0") {
                        $(cell).css('background-color', '#FFA519');
                    } 
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                }
                if(col == 11) {
                    status_date = val;
                   if(status_date == '') {
                        $(cell).text('-');
                        //instance.jexcel.options.data[row][col] = 'N.A.';
                    }
                }
                if(col == 12)
                {
                    // let cl_status = data.itemacceptstatus[row][17];
                    // if(cl_status == 'Failed' && data.itemacceptstatus[row][18] == 'Yes') {
                    //     $(cell).css('background-color', '#FF8282');
                    //     $(cell).css('color', '#FF8282');
                    // }
                    qa_status = val;
                    let qa_status1 = data.itemacceptstatus[row][12];
                    if(qa_status1 == "1") {
                        $(cell).css('background-color', '#5DE684');
                    } else if(qa_status1 == "2") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(qa_status1 == "0") {
                        $(cell).css('background-color', '#FFA519');
                    } 
                    let date = data.itemacceptstatus[row][13];
                    if(status_date !=  '' && date == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 13) {
                    if(val == '') {
                        $(cell).text('-');
                        //instance.jexcel.options.data[row][col] = 'N.A.';
                    }
                    
                }
                if(col == 14) {
                    if(qa_status == 1 && status == 1) {
                        $(cell).text('N.A.');
                        instance.jexcel.options.data[row][col] = 'N.A.';
                         $(cell).css('background-color', '#FFA519');
                    }
                    let mgmt_status = data.itemacceptstatus[row][14];
                    if(mgmt_status == "1") {
                        $(cell).css('background-color', '#5DE684');
                    } else if(mgmt_status == "2") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(mgmt_status == "3") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(mgmt_status == "4") {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(mgmt_status == "0") {
                        $(cell).css('background-color', '#FFA519');
                    }
                    
                    
                }
                if(col == 15)
                {
                    // if(qa_status != 0 && val == '') {
                    //     $(cell).text('N.A.');
                    //     instance.jexcel.options.data[row][col] = 'N.A.';
                    // }
                      let management = data.itemacceptstatus[row][14];
                   
                    let qa_status_2 = data.itemacceptstatus[row][12];
                    let man_status_2 = data.itemacceptstatus[row][10];
                     //console.log("qaaaa"+qa_status_2+"ma"+qa_status_2+"management"+management);
                      if(qa_status_2 != 0 && val == '') {
                        $(cell).text('N.A.');
                       // instance.jexcel.options.data[row][col] = 'N.A.';
                    }
                     if((qa_status_2 == 0 || man_status_2 == 0 || management == 0) ) {
                        $(cell).text('-');
                        //instance.jexcel.options.data[row][col] = '-';
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
                { title: 'Received Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'Difference Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', type: 'dropdown', source: data.uomData, readOnly: true },
                { title: 'Supply Closure\n Status', width: '8%', align: 'center', type: 'dropdown', source: supplyClosureData },
                { title: 'Status Update\n Date & Time', width: '8%', align: 'center', type: 'text' , readOnly: true},
                { title:'status', width:'0%',align:'center',type:'hidden'},
                { title:'order_status', width:0,type:'hidden'},

            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 7) 
                {
                    pi_qty = val; 
                    txtValue = numeral(val).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
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
                    diff_qty = parseFloat(rec_qty) - parseFloat(pi_qty);   
                    txtValue = numeral(diff_qty).format('0.00');
                    $(cell).text(txtValue);
                    instance.jexcel.options.data[row][col] = txtValue;
                }
                if(col == 11)
                {
                     orderstock = data.inhouseconsolidatedqtydetails[row][14];

                    
                    statusDate = data.inhouseconsolidatedqtydetails[row][12];
                    if(statusDate == '') {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }

                    if(orderstock == 1) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }


                    let closure_status =data.inhouseconsolidatedqtydetails[row][11];
                    

                    if(closure_status == "0" || closure_status == 0) {
                        $(cell).text('PENDING');
                        instance.jexcel.options.data[row][col] = 'PENDING';
                        $(cell).css('background-color', '#FFA519');
                    }
                    else if(closure_status == "1" || closure_status == 1) {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(closure_status == "2" || closure_status == 2) {
                        $(cell).css('background-color', '#fc0303ff');
                    } else if(closure_status == "3" || closure_status == 3) {
                        $(cell).css('background-color', '#5DE684');
                    } else if(closure_status == "4"|| closure_status == 4) {
                        $(cell).css('background-color', '#fc0303ff');
                    } else {
                        
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
    
    function inHouseCountForm(inHouseData)
    {
        let errorCount = 0;
        for(let i =0;i<inHouseData.length;i++) {
            if(inHouseData[i][1]==true && inHouseData[i][27]==false) {
                errorCount++;
            } 
        }
        
        return errorCount;
    }

    
    $('#orderStockList').click(function () {
        
        let inHouseData = inHouseStatusReference_vm.getData();
        let validateInHouseCount = inHouseCountForm(inHouseData);
        if(validateInHouseCount == 0) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('inhouse_error')
                );
        } else {
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
        }
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
                        //window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
                         window.location.reload();
                         window.location.href = base_path + 'request/Bomrequest/storepiupdate' + '/' + encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(request_id)) + '/' + encodeURIComponent(btoa(purchase_indent_id));
                     
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
    
    // $('#getValues').click(function () {
    //     let inHouseData = inHouseStatusReference_vm.getData();
    //     let itemAccept = itemAcceptStatusReference_vm.getData();
    //     let inHouseConsolidate = inHouseConsolidatedReference_vm.getData();
    //     let validateField = [8,9,10,11,12,13,14,15,16,17,19,21,22,23,24];
    //     let validatedErrorCount = validateForm(validateField, inHouseData);
    //     //console.log(validatedErrorCount);
    //     if(validatedErrorCount > 0 ) {
    //         swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('validation_error')
    //             );
    //     } else {
    //         swalWithBootstrapButtons.fire(
    //         // *** CONFIRMATION MESSAGE *** //
    //             alertMessageFunction('confirmation_save')
    //         ).then(function (result) {
    //             if (result.value) {
                    
    //                 updateFunction(inHouseData, itemAccept, inHouseConsolidate);
    //             } 
    //             else if (result.dismiss === Swal.DismissReason.cancel) {
    //                 // *** CANCELLED MESSAGE *** //
    //                 swalWithBootstrapButtons.fire(
    //                     alertMessageFunction('cancelled')
    //                 );
    //             }
    //         });
    //     }
    // });

    // function updateFunction(inHouseData, itemAccept, inHouseConsolidate) {
    //     let dataform = new FormData();
    //     dataform.append('inHouseData', JSON.stringify(inHouseData));
    //     dataform.append('itemAccept', JSON.stringify(itemAccept));
    //     dataform.append('inHouseConsolidate', JSON.stringify(inHouseConsolidate));
    //     dataform.append('enquiry_id', enquiry_id);
    //     dataform.append('reqId', reqId);
    //     dataform.append('pId', pId);

    //     let request = $.ajax({
    //         type: "POST",
    //         url: base_path + 'request/Bomrequest/updateStorePiDetails',
    //         data: dataform,
    //         processData: false,
    //         contentType: false,
    //         cache: false,
    //         success: function (data) {
    //             // *** SAVED MESSAGE *** //
    //             swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('saved')
    //             ).then((okay) => {
    //                 if(okay)
    //                 {
    //                      window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
    //                 }
    //             });
    //         },
    //         error: function () {
    //             console.log("Error");
    //         }
    //     });
    // }

    let isRequestInProgress = false;

$('#getValues').click(function () {
     if (isRequestInProgress) return; // Prevent further clicks if request is in progress
     
     let inHouseData = inHouseStatusReference_vm.getData();
     let itemAccept = itemAcceptStatusReference_vm.getData();
     let inHouseConsolidate = inHouseConsolidatedReference_vm.getData();
     let validateField = [8,9,10,11,12,13,14,15,16,17,19,21,22,23,24];
     let validatedErrorCount = validateForm(validateField, inHouseData);
     
     if (validatedErrorCount > 0) {
         swalWithBootstrapButtons.fire(alertMessageFunction('validation_error'));
     } else {
         swalWithBootstrapButtons.fire(alertMessageFunction('confirmation_save'))
         .then(function (result) {
             if (result.value) {
                 isRequestInProgress = true;  // Set flag to true to indicate ongoing request
                 updateFunction(inHouseData, itemAccept, inHouseConsolidate);
             } else if (result.dismiss === Swal.DismissReason.cancel) {
                 // CANCELLED MESSAGE
                 swalWithBootstrapButtons.fire(alertMessageFunction('cancelled'));
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
             swalWithBootstrapButtons.fire(alertMessageFunction('saved'))
             .then((okay) => {
                 if (okay) {
                     //window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
                      window.location.reload();
                      //getQABomRequest();
                      window.location.href = base_path + 'request/Bomrequest/storepiupdate' + '/' + encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(request_id)) + '/' + encodeURIComponent(btoa(purchase_indent_id));
                                      }
             });
         },
        /**
         * Error callback for the AJAX request. If the request fails, 
         * this function will be called with the jqXHR object as the argument.
         * @param {jqXHR} jqXHR The jqXHR object (a superset of the XMLHTTPRequest object)
         */
         error: function () {
             console.log("Error");
         },
         complete: function() {
             isRequestInProgress = false; // Reset flag after the request is completed
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
                        // window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
                         window.location.reload();
                         //getQABomRequest();
                         window.location.href = base_path + 'request/Bomrequest/storepiupdate' + '/' + encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(request_id)) + '/' + encodeURIComponent(btoa(purchase_indent_id));
                      
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
                         //window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
                          // getQABomRequest();
                             window.location.reload();
                         window.location.href = base_path + 'request/Bomrequest/storepiupdate' + '/' + encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(request_id)) + '/' + encodeURIComponent(btoa(purchase_indent_id));
                     
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
                    alertMessageFunction('status_error')
                )
        } else {
            // swalWithBootstrapButtons.fire(
            // // *** CONFIRMATION MESSAGE *** //
            //     alertMessageFunction('confirmation_save')
            // ).then(function (result) {
            //     if (result.value) {
                    //  $('#supply_status').val('');
                    //  $('#supplyModal').modal('show');
                    
            //     } 
            //     else if (result.dismiss === Swal.DismissReason.cancel) {
            //         // *** CANCELLED MESSAGE *** //
            //         swalWithBootstrapButtons.fire(
            //             alertMessageFunction('cancelled')
            //         );
            //     }
            // });
            
            $('#supply_status').val('');
            $('#supplyModal').modal('show');
        }
        
    });
    
    $('#modal_status').click(function () {
            $('#supplyModal').modal('hide');
            let inHouseStatus= inHouseStatusReference_vm.getData();
            let consolidated = inHouseConsolidatedReference_vm.getData();
            let supply_status = $("input[name='supply_status']:checked").val();
            
            updateSupplyClosureFunction(consolidated,inHouseStatus,supply_status);
    });
    
    function updateSupplyClosureFunction(consolidated, inHouseStatus, supply_status)
    {
        let dataform = new FormData();
        dataform.append('inHouseStatus', JSON.stringify(inHouseStatus));
        dataform.append('consolidated', JSON.stringify(consolidated));
        dataform.append('supply_status', supply_status);
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
                         //window.location.href = base_path + 'company/Mstoreuser/purchaseindentlist';
                          window.location.href = base_path + 'company/Mstoreuser/supplyclosurelist';
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