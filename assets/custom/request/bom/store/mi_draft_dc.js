$(document).ready(function () {

     var draf_id = document.getElementById("draf_id").value;
    
    
    var selectCount = 0;
    var selectedArray = [];
    var BOMMaterialIndent = [];
    var UOMDetails = [];
    var itemDescription = $bcm = $garmentSize = $itemCode = $itemColor = $sizeDimension = $uom = [];
    var bom_dynamic_mi_data = [];
    $('#saveRequestDetails').hide();

    getBomRequest();

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

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getBomRequest() {
     
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', request_id);
        data.append('drafId', draf_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getDraftDcDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                console.log(data.sumqty);
                sample_requirement_data = JSON.parse(data);
                append_sample_request(sample_requirement_data);
                append_bom_material_indent(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_sample_request(data) {
        // console.log(data);
        // if(parseInt(data.ref_status) > 0) {
            UOMDetails = data.UOMDetails;
            itemDescription = data.BOMAppendData.itemDescription;
            bcm = data.BOMAppendData.bcm;
            garmentSize = data.BOMAppendData.garmentSize;
            itemCode = data.BOMAppendData.itemCode;
            itemColor = data.BOMAppendData.itemColor;
            sizeDimension = data.BOMAppendData.sizeDimension;
            uom = data.BOMAppendData.uom;
            BOMMaterialIndent = data.bom_mi_tbl_data;
            BomLotData = data.lotNos;
            BomRateData = data.rateLists;
            MiData = data.mi_data;
            
            if(MiData.length > 0)
            {
                $('#cad_req_date').val(MiData[0].cad_req_date);
                $('#cad_cutoff_date').val(MiData[0].cad_cutoff_date);
                $('#bom_req_date').val(MiData[0].bom_req_date);
                $('#bom_cutoff_date').val(MiData[0].bom_cutoff_date);
            }

            append_fabric_material_indent(data.referResult);
            
        // }
    }
    
    function append_bom_material_indent(data) {
          console.log('append_bom_material_indent');
        console.log(data.ind_qtyss);
         //console.log(data.bcm);
      
            let list = {
                data:data.miDraftData,
                columns: [
                    { title:'id', width:'0%',align:'center',type:'hidden'},
                    { title:'status', width:'0%',align:'center',type:'hidden'},
                    // { type: 'checkbox', title: 'Mark', width: '3%', align: 'left'},
                    { type: 'dropdown', title: 'Item Description', width: '8%', align: 'left', source:data.itemDescription},
                    { type: 'dropdown', title: 'Blend (%) / Content /\n Material', width: '8%', align: 'left',  source:data.bcm, readOnly: true },
                    { type: 'dropdown', title: 'Garment \n Size(s)', width: '8%', align: 'left',  source:data.garmentSize, readOnly: true },
                    { type: 'dropdown', title: 'Item Code', width: '8%', align: 'left' , source:data.itemCode, readOnly: true },
                    { type: 'dropdown', title: 'Item Colour\n Code', width: '8%', align: 'left', source:data.itemColor, readOnly: true },
                    { type: 'dropdown', title: 'Size /\n Dimension', width: '8%', align: 'left', source:data.sizes , readOnly: true},
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left',source:data.uomData, readOnly: true },
                    { type: 'dropdown', title: 'M.I. Wise \n Pending Qty.', width: '8%', align: 'right', source:data.ind_qtyss, readOnly: true },
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails , source:data.ind_uom,readOnly: true},
                    { type: 'dropdown', title: 'Item - Lot / \n Batch Ref. No.', width: '8%', align: 'left',source:BomLotData, filter:lotFilter},
                    { type: 'dropdown', 'title': 'Rate \n Unit (Rs).', width: '8%', align: 'center' , source:BomRateData, filter:rateFilter},
                    // { type: 'text', title: 'Lot / Batch wise \n Available Qty', width: '8%', align: 'right' , readOnly: true},
                    { type: 'text', title: 'Issued \n Qty.', width: '8%', align: 'right' },
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails },
                    { title:'MI ID', width:'0%',align:'center',type:'hidden'},
                    
                    
                ],
                minDimensions: [4, 1],
                allowDeleteColumn: true,
                allowInsertRow: true,
                allowInsertColumn: true,
               
                onchange: function(instance, cell, col, row, val, label, cellName) {
                   
                    if(col ==3)
                    {
                        
                    }
                    if(col ==12)
                    {
                        
                    }
                    
                    if(col ==14)
                    {
                        
                    }
                    
                },
                updateTable: function(instance, cell, col, row, val, label, cellName) {
                    if(col === 1) {
                        
                        statusVal = val;
                    }
                    
                    if(col == 2)
                    {
                        
                        item_id = val;
                        if(statusVal === 1) {
                            
                            $(cell).text(data.miDraftData[row][2]);
                            instance.jexcel.options.data[row][col] = data.miDraftData[row][2];
                        }
                    }
                    if(col == 3) 
                    {
                        if(item_id !== '' && (statusVal === false || statusVal === '')) {
                            let bcm = data.bcm;
                            let obj = bcm.find(o => o.item_id === item_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                        }
                    }
                    if(col == 4) 
                    {
                        if(item_id !== '' && (statusVal === false || statusVal === '')) {
                            let garmentSize = data.garmentSize;
                            let obj = garmentSize.find(o => o.item_id === item_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                        }
                    }
                    if(col == 5) 
                    {
                        if(item_id !== ''  && (statusVal === false || statusVal === '')) {
                            let itemCode = data.itemCode;
                            let obj = itemCode.find(o => o.item_id === item_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                        }
                    }
                    if(col == 6) 
                    {
                        if(item_id !== '' && (statusVal === false || statusVal === '')) {
                            let itemColor = data.itemColor;
                            let obj = itemColor.find(o => o.item_id === item_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                        }
                    }
                    if(col == 7) 
                    {
                        if(item_id !== '' && (statusVal === false || statusVal === '')) {
                            let sizes = data.sizes;
                            let obj = sizes.find(o => o.item_id === item_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                        }
                    }
                    
                    if(col == 8) 
                    {
                        if(item_id !== '' && (statusVal === false || statusVal === '')) {
                            let uomData = data.uomData;
                            let obj = uomData.find(o => o.item_id === item_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                        }
                    }
                    
                    if(col == 9) 
                    {
                        indQty = 0;
                        if(item_id !== '' && (statusVal === false || statusVal === '')) {
                            let ind_qtyss = data.ind_qtyss;
                            let obj = ind_qtyss.find(o => o.item_id === item_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                            indQty = obj.name;
                        } else if(statusVal === 1) {
                            indQty = val;
                            
                            //$(cell).text(val);
                            //$(cell).text(data.miDraftData[row][10]);
                            //instance.jexcel.options.data[row][col] = data.miDraftData[row][10];
                             $(cell).text(val);
                        }
                    }
                    if(col == 10) 
                    {
                        if(item_id !== '' && (statusVal === false || statusVal === '')) {
                            let ind_uom = data.ind_uom;
                            let obj = ind_uom.find(o => o.item_id === item_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                        }
                    }
                    if(col == 13)
                    {
                        
                        // qty = val;
                        // if(qty > indQty) {
                        //     alert('Invalid Qty');
                        //     $(cell).text('');
                        //     instance.jexcel.options.data[row][col] = '';
                        // }
                    let qtyNum = parseFloat(val);
                    let indQtyNum = parseFloat(indQty);
                     if (qtyNum > indQtyNum) {
                    alert('Invalid Qty');
                    $(cell).text('');
                             instance.jexcel.options.data[row][col] = '';
       
                       }
                       
                        
                    }
                    
                    
                }
            };
    
            bom_mi_tbl_data = new Vue({
                el: '#bomMaterialIndent',
                mounted: function () {
                    let spreadsheet = jexcel(this.$el, list);
                    Object.assign(this, spreadsheet);
                },
            });

            

        }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    //  FABRIC MATERIAL INDENT STARTS HERE 
    // ***********************************************************************************************************************************

    // ****** Filter ******** //
    
    
    function append_fabric_material_indent(data) {
         //console.log(data);
        for (let i = 0; i < data.length; i++) {
            $('#bomMaterialIndent'+data[i][1]).html('');
           // generateBomMaterialIndent(data[i][1], i);
        }
        
        // *** STARTS BOM MATERIAL INDENT DYNAMIC TABLE BASED ON SELECTION *** /
        
        // function generateBomMaterialIndent(id, i) {
        //     //let BomLotDatas = BomLotData[i];
        //     //let rateDatas = BomRateData[i];
           
        //     let count = 0;
        //     let count1 = 0;
            
        //     let list = {
                
        //         data: BOMMaterialIndent[i],
        //         columns: [
        //             { title:'id', width:'0%',align:'center',type:'hidden'},
        //             { title:'status', width:'0%',align:'center',type:'hidden'},
        //             { type: 'checkbox', title: 'Mark', width: '3%', align: 'left'},
        //             { type: 'dropdown', title: 'Item Description', width: '8%', align: 'left', source:itemDescription},
        //             { type: 'text', title: 'Blend (%) / Content /\n Material', width: '8%', align: 'left',  readOnly: true},
        //             { type: 'text', title: 'Garment \n Size(s)', width: '8%', align: 'left',  readOnly: true},
        //             { type: 'text', title: 'Item Code', width: '8%', align: 'left' ,  readOnly: true},
        //             { type: 'text', title: 'Item Colour\n Code', width: '8%', align: 'left',  readOnly: true},
        //             { type: 'text', title: 'Size /\n Dimension', width: '8%', align: 'left',  readOnly: true},
        //             { type: 'text', title: 'UOM', width: '8%', align: 'left',  readOnly: true},
        //             { type: 'text', title: 'Indent \n Qty.', width: '8%', align: 'right' , readOnly: true},
        //             { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails , readOnly: true},
        //             { type: 'dropdown', title: 'Item - Lot / \n Batch Ref. No.', width: '8%', align: 'left',source:BomLotData},
        //             { type: 'dropdown', 'title': 'Rate \n Unit (Rs).', width: '8%', align: 'right' , source:BomRateData, filter:rateFilter},
        //             { type: 'text', title: 'Lot / Batch wise \n Available Qty', width: '8%', align: 'right' , readOnly: true},
        //             { type: 'text', title: 'M.I. Wise \n Pending Qty.', width: '8%', align: 'left', readOnly: true },
        //             { type: 'text', title: 'Issued \n Qty.', width: '8%', align: 'right' },
        //             { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails },
                    
                    
        //         ],
        //         minDimensions: [4, 1],
        //         allowDeleteColumn: true,
        //         allowInsertRow: true,
        //         allowInsertColumn: true,
               
        //         onchange: function(instance, cell, col, row, val, label, cellName) {
        //             if(col == 2) 
        //             {
        //                 count++;
        //                 if(val === true) {
        //                     selectCount = selectCount+1;
        //                 } else {
        //                     selectCount = selectCount-1;
        //                 }
        //             }
        //             if(col ==12)
        //             {
        //             }
                    
        //         },
        //         updateTable: function(instance, cell, col, row, val, label, cellName) {
        //             if(col === 1) {
        //                 count1++;
                        
        //                 statusVal = val;
        //             }
        //             if(col === 2) 
        //             {
        //                 // if(statusVal === true) {
                
        //                 //     $(cell).addClass('readonly');
        //                 // }
                        
        //             }
                    
                    
        //         }
        //     };
    
        //     bom_mi_tbl_data = new Vue({
        //         el: '#bomMaterialIndent'+id,
        //         mounted: function () {
        //             let spreadsheet = jexcel(this.$el, list);
        //             Object.assign(this, spreadsheet);
        //         },
        //     });

        //     let tblData = { 'tbl_data': bom_mi_tbl_data };
        //     bom_dynamic_mi_data.push(tblData);

        // }
        
        

        // function generateBomMaterialIndent(id, i) {
        //     let count = 0;
        //     let list = {
        //         data: BOMMaterialIndent[i],
        //         columns: [
        //             { title:'id', width:'10%',align:'center',type:'hidden'},
        //             { type: 'checkbox', title: 'mark', width: '8%', align: 'left'},
        //             { type: 'dropdown', title: 'Item Description', width: '8%', align: 'left', source: itemDescription , readOnly: true},
        //             { type: 'dropdown', title: 'Blend (%) / Content /\n Material', width: '8%', align: 'left', source: bcm, filter: bcmFilter , readOnly: true},
        //             { type: 'dropdown', title: 'Garment \n Size(s)', width: '8%', align: 'left', source: garmentSize, filter: garmentFilter , readOnly: true},
        //             { type: 'dropdown', title: 'Item Code', width: '8%', align: 'left' , source: itemCode, filter: itemCodeFilter , readOnly: true},
        //             { type: 'dropdown', title: 'Item Colour\n Code', width: '8%', align: 'left', source: itemColor, filter: itemColorFilter , readOnly: true},
        //             { type: 'dropdown', title: 'Size /\n Dimension', width: '8%', align: 'left', source: sizeDimension, filter: sizeDimensionFilter , readOnly: true},
        //             { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: uom, filter: uomFilter , readOnly: true},
        //             { type: 'text', title: 'Indent \n Qty.', width: '8%', align: 'right' , readOnly: true},
        //             { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails , readOnly: true},
        //             { type: 'text', title: 'Issued \n Qty.', width: '8%', align: 'right' , readOnly: true},
        //             { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails , readOnly: true},
        //             { type: 'text', title: 'D.C. No.', width: '8%', align: 'left', readOnly: true },
        //             { type: 'text', title: 'D.C. \n Date & Time.', width: '8%', align: 'left', readOnly: true },
        //         ],
        //         minDimensions: [4, 1],
        //         allowDeleteColumn: true,
        //         allowInsertRow: true,
        //         allowInsertColumn: true,
        //         onchange: function(instance, cell, col, row, val, label, cellName) {
        //             if(col == 1) 
        //             {
        //                 count++;
        //                 if(val === true) {
        //                     selectCount = selectCount+1;
        //                 } else {
        //                     selectCount = selectCount-1;
        //                 }
        //             }
        //         },
        //         updateTable: function(instance, cell, col, row, val, label, cellName) {
        //             if(col === 1) 
        //             {
        //                 if(val === true) {
        //                     //$(cell).addClass('readonly');
        //                     selectCount = selectCount+1;
        //                 }
        //             }
        //         }
        //     };
    
        //     bom_mi_tbl_data = new Vue({
        //         el: '#bomMaterialIndent'+id,
        //         mounted: function () {
        //             let spreadsheet = jexcel(this.$el, list);
        //             Object.assign(this, spreadsheet);
        //         },
        //     });

        //     let tblData = { 'tbl_data': bom_mi_tbl_data };
        //     bom_dynamic_mi_data.push(tblData);

        // }

        // *** STARTS FABRIC MATERIAL INDENT DYNAMIC TABLE BASED ON SELECTION *** /

    }
    
    

    /********** BOM Filter Details **********/
    
    function lotFilter(instance, cell, c, r, source) {
        
        var item_id = instance.jexcel.getValueFromCoords(c - 6, r);
        
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item == item_id) return true;
            })
        } else {
            return [];
        }
    }

    function rateFilter(instance, cell, c, r, source) {
        var item_id = instance.jexcel.getValueFromCoords(c - 1, r);
         //var item_id = 'ML-1';
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id ) return true;
            })
        } else {
            return [];  
        }
    }

    function bcmFilter(instance, cell, c, r, source) {
        var item_id = instance.jexcel.getValueFromCoords(c - 1, r);
        if (item_id !== "") {
            return source.filter(function (item) {
                if (item.item_id == item_id) return true;
            })
        } else {
            return [];
        }
    }

    function garmentFilter(instance, cell, c, r, source) {
        let bcm_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let item_id = instance.jexcel.getValueFromCoords(c - 2, r);

        if (bcm_id !== "" && item_id !== "") {
            return source.filter(function (item) {
                if (item.item_id == item_id && item.bcm_id == bcm_id) return true;
            })
        } else {
            return [];
        }
    }

    function itemCodeFilter(instance, cell, c, r, source) {
        let garment_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let bcm_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let item_id = instance.jexcel.getValueFromCoords(c - 3, r);

        if (bcm_id != "" && item_id != "" && garment_id != "") {
            return source.filter(function (item) {
                if ((item.item_id == item_id) && (item.bcm_id == bcm_id) && (item.garment_id == garment_id)) return true;
            })
        } else {
            return [];
        }
    }

    function itemColorFilter(instance, cell, c, r, source) {
        let item_code_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let garment_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let bcm_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let item_id = instance.jexcel.getValueFromCoords(c - 4, r);

        if (bcm_id != "" && item_id != "" && garment_id != "" && item_code_id != "") {
            return source.filter(function (item) {
                if ((item.item_id == item_id) && (item.bcm_id == bcm_id) && (item.garment_id == garment_id) && (item.item_code_id == item_code_id)) return true;
            })
        } else {
            return [];
        }
    }

    function sizeDimensionFilter(instance, cell, c, r, source) {
        let item_color_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let item_code_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let garment_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let bcm_id = instance.jexcel.getValueFromCoords(c - 4, r);
        let item_id = instance.jexcel.getValueFromCoords(c - 5, r);

        if (bcm_id != "" && item_id != "" && garment_id != "" && item_code_id != "" && item_color_id != "") {
            return source.filter(function (item) {
                if ((item.item_id == item_id) && (item.bcm_id == bcm_id) && (item.garment_id == garment_id) 
                && (item.item_code_id == item_code_id) && (item.item_color_id == item_color_id)) return true;
            })
        } else {
            return [];
        }
    }

    function uomFilter(instance, cell, c, r, source) {
        let size_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let item_color_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let item_code_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let garment_id = instance.jexcel.getValueFromCoords(c - 4, r);
        let bcm_id = instance.jexcel.getValueFromCoords(c - 5, r);
        let item_id = instance.jexcel.getValueFromCoords(c - 6, r);

        if (bcm_id != "" && item_id != "" && garment_id != "" && item_code_id != "" && item_color_id != "" && size_id != "") {
            return source.filter(function (item) {
                if ((item.item_id == item_id) && (item.bcm_id == bcm_id) && (item.garment_id == garment_id) 
                && (item.item_code_id == item_code_id) && (item.item_color_id == item_color_id) && (item.size_id == size_id)) return true;
            })
        } else {
            return [];
        }
    }

    
    // $('#orderStockList').click(function () {
    //     if(selectCount <= 0) {
    //         swalWithBootstrapButtons.fire(
    //             alertMessageFunction('selecterror')
    //         );
    //     }
    //     else {
    //         swalWithBootstrapButtons.fire(
    //             // *** CONFIRMATION MESSAGE *** //
    //             alertMessageFunction('confirmation_save')
    //         ).then(function (result) {
    //             if (result.value) {
                    
    //                  //console.log(bom_dynamic_mi_data)

    //                 let bom_mi_all_tbl_data = [];
    //                 for (let i = 1; i < bom_dynamic_mi_data.length+1; i++) {
    //                     let bom_mi_data = bom_dynamic_mi_data[i-1].tbl_data.getData();         
    //                     // console.log(bom_mi_data);
    //                     for (let j = 0; j < bom_mi_data.length; j++) {
    //                         bom_mi_all_tbl_data.push(bom_mi_data[j]);                            
    //                     }
    //                 }
    //                 console.log(bom_mi_all_tbl_data);
    //                 updateFunction(bom_mi_all_tbl_data);
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
    
    $('#draftDc').click(function () {
        
            // *** CONFIRMATION MESSAGE *** //
            let issued_data = bom_mi_tbl_data.getData();
           
                swalWithBootstrapButtons.fire(
                alertMessageFunction('confirmation_save')
                ).then(function (result) {
                if (result.value) {
                    updateFunction(issued_data);
                }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
        
    });

    function updateFunction(issued_data) {
        let dataform = new FormData();
        let bom_ref_no = $('#bom_ref_no').val();
        let bom_dept = $('#bom_dept').val();
        dataform.append('issued_data', JSON.stringify(issued_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', request_id);
        dataform.append('bom_ref_no', bom_ref_no);
        dataform.append('bom_dept', bom_dept);
        dataform.append('draf_id', draf_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateDraftStockDetails',
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
                        // window.location.href = base_path + 'company/Mstoreuser/mireceivedlist';
                        location.reload();
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $('#clearDraft').click(function () {
        
            // *** CONFIRMATION MESSAGE *** //
            let issued_data = bom_mi_tbl_data.getData();
           
                swalWithBootstrapButtons.fire(
                alertMessageFunction('confirmation_save')
                ).then(function (result) {
                if (result.value) {
                    updateClearFunction(issued_data);
                }
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
        
    });

    function updateClearFunction(issued_data) {
        let dataform = new FormData();
       
        dataform.append('issued_data', JSON.stringify(issued_data));
         dataform.append('draf_id', draf_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateClearDraftDetails',
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
                        //window.location.href = base_path + 'company/Mstoreuser/mireceivedlist';
                        location.reload();
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $('#getValues').click(function () {
        
            // *** CONFIRMATION MESSAGE *** //
            let issued_data = bom_mi_tbl_data.getData();
            let save_status = 1;
            let received_by = $('#received_by').val();
            //alert(received_by);
             let validateField = [3,4,5,6,7,8,9,10,11,12,13];
             console.log(issued_data);
     let validatedErrorCount = validateForm(validateField, issued_data);
     
     if (validatedErrorCount > 0) {
         swalWithBootstrapButtons.fire(alertMessageFunction('validation_error'));
     }
            else if (received_by.trim() === '' || received_by == null || received_by === undefined) {
   swalWithBootstrapButtons.fire(
       alertMessageFunction('validation_error')
   );
} else {
                swalWithBootstrapButtons.fire(
                alertMessageFunction('confirmation_save')
                ).then(function (result) {
                if (result.value) {
                    updateSaveFunction(issued_data, save_status);
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
     $('#getValues1').click(function () {
        
            // *** CONFIRMATION MESSAGE *** //
            let issued_data = bom_mi_tbl_data.getData();
              let save_status = 0;
            let received_by = $('#received_by').val();
            console.log(received_by)
            let validateField = [3,4,5,6,7,8,9,10,11,12,13];
             console.log(issued_data);
     let validatedErrorCount = validateForm(validateField, issued_data);
     
     if (validatedErrorCount > 0) {
         swalWithBootstrapButtons.fire(alertMessageFunction('validation_error'));
     }
            else if (received_by.trim() === '' || received_by == null || received_by === undefined) {
   swalWithBootstrapButtons.fire(
       alertMessageFunction('validation_error')
   );
} else {
                swalWithBootstrapButtons.fire(
                alertMessageFunction('confirmation_save')
                ).then(function (result) {
                if (result.value) {
                    updateSaveFunction(issued_data, save_status);
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
    $('#movedc').click(function () {
        
            // *** CONFIRMATION MESSAGE *** //
            let issued_data = bom_mi_tbl_data.getData();
            let received_by = $('#received_by').val();
            console.log(received_by)
           if(received_by == '' || received_by == undefined) {
               swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                );
           } else {
                swalWithBootstrapButtons.fire(
                alertMessageFunction('confirmation_save')
                ).then(function (result) {
                if (result.value) {
                    updateSaveFunction2(issued_data);
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

    function validatmidrafdc(dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            
            if(dataValue[i][11] === "" || dataValue[i][12] === null ) {
                errorCount++;
            }
             if(dataValue[i][12] === "" || dataValue[i][12] === null ) {
                errorCount++;
            }
             if(dataValue[i][13] === "" || dataValue[i][12] === null ) {
                errorCount++;
            }
        }
        return errorCount;
    }

    function validateForm(validateField, dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            let validateFields = [3,4,5,6,7,8,9,10,11,12,13];
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

    function updateSaveFunction2(issued_data) {
        let dataform = new FormData();
        let bom_ref_no = $('#bom_ref_no').val();
        let bom_dept = $('#bom_dept').val();
        let received_by = $('#received_by').val();
        dataform.append('issued_data', JSON.stringify(issued_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', request_id);
        dataform.append('bom_ref_no', bom_ref_no);
        dataform.append('bom_dept', bom_dept);
        dataform.append('received_by', received_by);
        dataform.append('draf_id', draf_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateSaveStockDetailsdc',
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
                      
                   //window.location.href = base_path + 'request/Bomrequest/mireceiveddetails/' + encodeURIComponent(btoa(enquiry_id))+'/reqId/' + encodeURIComponent(btoa(request_id));

                     window.location.href = base_path + 'company/Mstoreuser/dclist/' ;

                        //location.reload();
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    function updateSaveFunction(issued_data, save_status) {
        let dataform = new FormData();
        let bom_ref_no = $('#bom_ref_no').val();
        let bom_dept = $('#bom_dept').val();
        let received_by = $('#received_by').val();
        dataform.append('issued_data', JSON.stringify(issued_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', request_id);
        dataform.append('bom_ref_no', bom_ref_no);
        dataform.append('bom_dept', bom_dept);
        dataform.append('received_by', received_by);
        dataform.append('draf_id', draf_id);
        dataform.append('save_status', save_status);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateSaveStockDetails',
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
                      if(save_status == 1) {
                           window.location.href = base_path + 'request/Bomrequest/mireceiveddetails/' + encodeURIComponent(btoa(enquiry_id))+'/reqId/' + encodeURIComponent(btoa(request_id));
                      } else {
                          location.reload();
                      }
                  
                    
                        //location.reload();
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    // *********************************************************************************************************************************** 
    //  FABRIC MATERIAL INDENT ENDS HERE 
    // ***********************************************************************************************************************************

});