$(document).ready(function () {

    

    // **************************************** //
    var selectCount = 0;
    var selectedArray = [];
    var BOMMaterialIndent = [];
    var UOMDetails = [];
    var itemDescription = $bcm = $garmentSize = $itemCode = $itemColor = $sizeDimension = $uom = $ind_qtyss = ind_uom  = $itemColours = [];
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
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getDraftDcDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                //console.log(data);
                sample_requirement_data = JSON.parse(data);
                append_sample_request(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_sample_request(data) {
        // Make sure that the data contains the necessary fields
        console.log(data);

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
        ind_qtyss = data.ind_qtyss;
        ind_uom = data.ind_uom;
        itemColours = data.itemDescription;
         BOMMaterialIndent1 = data.bom_mi_tbl_data[0];
         BOMMaterialIndent2 = data.bom_mi_tbl_data[1];

         BOMMaterialIndent3=BOMMaterialIndent1.concat(BOMMaterialIndent2);





        
        if(MiData.length > 0) {
            $('#cad_req_date').val(MiData[0].cad_req_date);
            $('#cad_cutoff_date').val(MiData[0].cad_cutoff_date);
            $('#bom_req_date').val(MiData[0].bom_req_date);
            $('#bom_cutoff_date').val(MiData[0].bom_cutoff_date);
        }

        // Call function to populate the BOM Material Indent
        append_fabric_material_indent(data.referResult);
    }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************

    // *********************************************************************************************************************************** 
    //  FABRIC MATERIAL INDENT STARTS HERE 
    // ***********************************************************************************************************************************

    // ****** Filter ******** //
    function append_fabric_material_indent(data) {
        console.log(data);
        for (let i = 0; i < data.length; i++) {
            $('#bomMaterialIndent' + data[i][1]).html('');
            generateBomMaterialIndent(data[i][1], i);
        }

        // *** STARTS BOM MATERIAL INDENT DYNAMIC TABLE BASED ON SELECTION *** //

        function generateBomMaterialIndent(id, i) {

            //let   itemcode;
         

            const group = BOMMaterialIndent[i];

            console.log(BomRateData);
           
           


const itemDescSource1 = group.map(row => ({
  id: row[0],      // col 0
  name: row[3]     // col 3
}));
            
          

   
            let count = 0;
            let list = {
                data:data.miDraftData[i],
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
                            $(cell).text(data.miDraftData[row][10]);
                            instance.jexcel.options.data[row][col] = data.miDraftData[row][10];
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
                        qty = val;
                        if(val > indQty) {
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

            let tblData = { 'tbl_data': bom_mi_tbl_data };
            bom_dynamic_mi_data.push(tblData);
        }


       

        // *** STARTS FABRIC MATERIAL INDENT DYNAMIC TABLE BASED ON SELECTION *** /
    }


// function filterBcmByItemDescription(selectedItemId, instance, row, col) {
//     // Filter the bcm data based on the selected itemDescription
//     const filteredBcm = bcm.filter(bcmItem => bcmItem.item_id === selectedItemId);
//     const filteredBcmSource = filteredBcm.map(o => ({ id: o.id, name: o.name }));

//     // Now update the bcm dropdown with the filtered options
//     instance.jexcel.setValueFromCoords(col, row, filteredBcmSource[0]?.name); // Autofill the dropdown value with the first item

//     // Update the source for this column dropdown dynamically
//     const column = instance.jexcel.getColumn(col);
//     column.source = filteredBcmSource;
//     instance.jexcel.updateColumn(col);
// }

//     function garmentFilter(instance, cell, c, r, source) {
//         let bcm_id = instance.jexcel.getValueFromCoords(c - 1, r);
//         let item_id = instance.jexcel.getValueFromCoords(c - 2, r);

//         if (bcm_id !== "" && item_id !== "") {
//             return source.filter(function (item) {
//                 if (item.item_id == item_id && item.bcm_id == bcm_id) return true;
//             })
//         } else {
//             return [];
//         }
//     }

//     function itemCodeFilter(instance, cell, c, r, source) {
//         let garment_id = instance.jexcel.getValueFromCoords(c - 1, r);
//         let bcm_id = instance.jexcel.getValueFromCoords(c - 2, r);
//         let item_id = instance.jexcel.getValueFromCoords(c - 3, r);

//         if (bcm_id != "" && item_id != "" && garment_id != "") {
//             return source.filter(function (item) {
//                 if ((item.item_id == item_id) && (item.bcm_id == bcm_id) && (item.garment_id == garment_id)) return true;
//             })
//         } else {
//             return [];
//         }
//     }

//     function itemColorFilter(instance, cell, c, r, source) {
//         let item_code_id = instance.jexcel.getValueFromCoords(c - 1, r);
//         let garment_id = instance.jexcel.getValueFromCoords(c - 2, r);
//         let bcm_id = instance.jexcel.getValueFromCoords(c - 3, r);
//         let item_id = instance.jexcel.getValueFromCoords(c - 4, r);

//         if (bcm_id != "" && item_id != "" && garment_id != "" && item_code_id != "") {
//             return source.filter(function (item) {
//                 if ((item.item_id == item_id) && (item.bcm_id == bcm_id) && (item.garment_id == garment_id) && (item.item_code_id == item_code_id)) return true;
//             })
//         } else {
//             return [];
//         }
//     }

//     function sizeDimensionFilter(instance, cell, c, r, source) {
//         let item_color_id = instance.jexcel.getValueFromCoords(c - 1, r);
//         let item_code_id = instance.jexcel.getValueFromCoords(c - 2, r);
//         let garment_id = instance.jexcel.getValueFromCoords(c - 3, r);
//         let bcm_id = instance.jexcel.getValueFromCoords(c - 4, r);
//         let item_id = instance.jexcel.getValueFromCoords(c - 5, r);

//         if (bcm_id != "" && item_id != "" && garment_id != "" && item_code_id != "" && item_color_id != "") {
//             return source.filter(function (item) {
//                 if ((item.item_id == item_id) && (item.bcm_id == bcm_id) && (item.garment_id == garment_id) 
//                 && (item.item_code_id == item_code_id) && (item.item_color_id == item_color_id)) return true;
//             })
//         } else {
//             return [];
//         }
//     }

//     function uomFilter(instance, cell, c, r, source) {
//         let size_id = instance.jexcel.getValueFromCoords(c - 1, r);
//         let item_color_id = instance.jexcel.getValueFromCoords(c - 2, r);
//         let item_code_id = instance.jexcel.getValueFromCoords(c - 3, r);
//         let garment_id = instance.jexcel.getValueFromCoords(c - 4, r);
//         let bcm_id = instance.jexcel.getValueFromCoords(c - 5, r);
//         let item_id = instance.jexcel.getValueFromCoords(c - 6, r);

//         if (bcm_id != "" && item_id != "" && garment_id != "" && item_code_id != "" && item_color_id != "" && size_id != "") {
//             return source.filter(function (item) {
//                 if ((item.item_id == item_id) && (item.bcm_id == bcm_id) && (item.garment_id == garment_id) 
//                 && (item.item_code_id == item_code_id) && (item.item_color_id == item_color_id) && (item.size_id == size_id)) return true;
//             })
//         } else {
//             return [];
//         }
//     }


     function lotFilter(instance, cell, c, r, source) {
        
        //var item_id = instance.jexcel.getValueFromCoords(c - 5, r);
         var item_id = 'ML-1';
     
        
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
        if (item_id !== "") {
            return source.filter(function (val) {
                if (val.item_id == item_id ) return true;
            })
        } else {
            return [];
        }
    }


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
                    updateSaveFunction(issued_data);
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

    function updateSaveFunction(issued_data) {
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
   









});
