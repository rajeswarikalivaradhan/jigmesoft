$(document).ready(function () {

    // **************************************** //
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
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getMIReceivedDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                append_sample_request(sample_requirement_data);
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
            generateBomMaterialIndent(data[i][1], i);
        }
        
        // *** STARTS BOM MATERIAL INDENT DYNAMIC TABLE BASED ON SELECTION *** /
        
        function generateBomMaterialIndent(id, i) {
            let count = 0;
            let list = {
                data: BOMMaterialIndent[i],
                columns: [
                    { title:'id', width:'10%',align:'center',type:'hidden'},
                    { title:'status', width:'10%',align:'center',type:'hidden'},
                    { type: 'checkbox', title: 'Mark', width: '5%', align: 'left'},
                    { type: 'text', title: 'Item Description', width: '10%', align: 'left', readOnly: true},
                    { type: 'text', title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left',  readOnly: true},
                    { type: 'text', title: 'Garment \n Size(s)', width: '6%', align: 'left',  readOnly: true},
                    { type: 'text', title: 'Item Code', width: '8%', align: 'left' ,  readOnly: true},
                    { type: 'text', title: 'Item Colour\n Code', width: '7%', align: 'left',  readOnly: true},
                    { type: 'text', title: 'Size /\n Dimension', width: '7%', align: 'left',  readOnly: true},
                    { type: 'text', title: 'UOM', width: '6%', align: 'left',  readOnly: true},
                    { type: 'text', title: 'Indent \n Qty.', width: '8%', align: 'right' , readOnly: true},
                    { type: 'dropdown', title: 'UOM', width: '6%', align: 'left', source: UOMDetails , readOnly: true},
                    { type: 'text', title: 'Issued \n Qty.', width: '8%', align: 'right' , readOnly: true},
                    { type: 'dropdown', title: 'UOM', width: '6%', align: 'left', source: UOMDetails , readOnly: true},
                    { type: 'text', title: 'M.I. Issued Status.', width: '10%', align: 'left', readOnly: true },
                    { type: 'text', title: 'Status Updated \n Date & Time.', width: '10%', align: 'center',  readOnly: true},
                    
                ],
                minDimensions: [4, 1],
                allowDeleteColumn: true,
                allowInsertRow: true,
                allowInsertColumn: true,
                onchange: function(instance, cell, col, row, val, label, cellName) {
                    if(col == 2) 
                    {
                        count++;
                        if(val === true) {
                            selectCount = selectCount+1;
                        } else {
                            selectCount = selectCount-1;
                        }
                    }
                },
                updateTable: function(instance, cell, col, row, val, label, cellName) {
                    if(col === 1) {
                        statusVal = val;
                    }
                    if(col === 2) 
                    {
                        if(statusVal === true) {
                
                            $(cell).addClass('readonly');
                        }
                        
                    }
                    if(col === 15) 
                    {
                        let  mistatus =val;
                       
                        if (mistatus === null || mistatus === undefined || mistatus === '') {
                    $(cell).text('-');
                          } 
                        
                    }

                    
                }
            };
    
            bom_mi_tbl_data = new Vue({
                el: '#bomMaterialIndent'+id,
                mounted: function () {
                    let spreadsheet = jexcel(this.$el, list);
                    Object.assign(this, spreadsheet);
                },
            });

            let tblData = { 'tbl_data': bom_mi_tbl_data };
            bom_dynamic_mi_data.push(tblData);

        }

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

    
    $('#orderStockList').click(function () {
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
                    
                     //console.log(bom_dynamic_mi_data)

                    let bom_mi_all_tbl_data = [];
                    for (let i = 1; i < bom_dynamic_mi_data.length+1; i++) {
                        let bom_mi_data = bom_dynamic_mi_data[i-1].tbl_data.getData();         
                        // console.log(bom_mi_data);
                        for (let j = 0; j < bom_mi_data.length; j++) {
                            bom_mi_all_tbl_data.push(bom_mi_data[j]);                            
                        }
                    }
                    console.log(bom_mi_all_tbl_data);
                    updateFunction(bom_mi_all_tbl_data);
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

    $('#issuedc').click(function () {
       
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    
                     //console.log(bom_dynamic_mi_data)

                    let bom_mi_all_tbl_data = [];
                    for (let i = 1; i < bom_dynamic_mi_data.length+1; i++) {
                        let bom_mi_data = bom_dynamic_mi_data[i-1].tbl_data.getData();         
                        // console.log(bom_mi_data);
                        for (let j = 0; j < bom_mi_data.length; j++) {
                            bom_mi_all_tbl_data.push(bom_mi_data[j]);                            
                        }
                    }
                    console.log(bom_mi_all_tbl_data);
                    updateFunction_issueddc(bom_mi_all_tbl_data);
                } 
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
        
    });

    function updateFunction(data) {
        let dataform = new FormData();
        dataform.append('bom_data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', request_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateOrderStockBom',
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
                        window.location.href = base_path + 'company/Mstoreuser/mireceivedlist';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function updateFunction_issueddc(data) {
        let dataform = new FormData();
        dataform.append('bom_data', JSON.stringify(data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('reqId', request_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateOrderStockissuedc',
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
                        window.location.href = base_path + 'company/Mstoreuser/dclist';
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