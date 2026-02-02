$(document).ready(function () {

    // **************************************** //
    var selectCount = 0;
    var selectedArray = [];
    var requirementData = [];
    var sizeData = [];
    var cad_ref_data = [];
    var cadMaterialIndent = [];
    var BOMMaterialIndent = [];
    var FabricMaterialIndent = [];
    var UOMDetails = [];
    var itemDescription = bcm = garmentSize = itemCode = itemColor = sizeDimension = uom = [];
    var fabricColor = fabricGarment = fabricBlend = fabricContent = fabricName = fabricGSM = fabricDIA = fabricUOM = [];
    var bom_dynamic_mi_data = [];
    var fabric_dynamic_mi_data = [];
    $('#saveRequestDetails').hide();

    getSampleRequest();
    getSampleRequestImages();

    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    // Change function
    $('#cad_deprt').on('change', function(){
        let cad_dept = $('#cad_deprt').val();
        $('#fab_dept').val(cad_dept);
        $('#bom_dept').val(cad_dept);
    });

    $('#cad_req_date').on('change', function(){
        let cad_req_date = $('#cad_req_date').val();
        $('#fab_req_date').val(cad_req_date);
        $('#bom_req_date').val(cad_req_date);
    });

    $('#cad_cuttoff_date').on('change', function(){
        let cad_cuttoff_date = $('#cad_cuttoff_date').val();
        $('#fab_cuttoff_date').val(cad_cuttoff_date);
        $('#bom_cuttoff_date').val(cad_cuttoff_date);
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

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getSampleRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/getSampleRequestSentList',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);

                let reqData = sample_requirement_data.req_data;

                $('#req_type').val(reqData[0].req_type);
                $('#req_type').trigger('change');
                $('#req_date').val(reqData[0].req_date);
                $('#cutoff_date').val(reqData[0].cutoff_date);
                $('#merchant_note').val(reqData[0].merchant_note);
                append_sample_request(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getSampleRequestImages() {
        $('.ImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'sample_request');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getcadrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                for (let i = 0; i < imageJSON.length; i++) {
                    $('.ImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/sample/'+imageJSON[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/sample/'+imageJSON[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href="javascript:void(0);" data-id='+imageJSON[i].wip_files_id+' class="deleteImg" title="Delete">'+
                                        '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
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

    function append_sample_request(data) {
        // *** ASSIGING SIZEDATA VALUE FROM DATA *** //
        sizeData = sizeData.splice(0, sizeData.length)
        for(let item of data.sizeData) {
            sizeData.push(item);
        }
        // *** JEXCEL STARTS *** //
        $('#sampleRequest').html('');
        let PurposeData = [ 'Development', 'Order Conf.', 'Shipment' ];
        
        let list = {
            data: data.data,
            columns: [
                { title:'mode', width:'10%',align:'center',type:'hidden'},
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData, readOnly: true },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'], readOnly: true },
                { type: 'dropdown', title: 'If Revised or In-line\n Prev. Sample Ref. No.', width: '8%', align: 'left', source: data.sampleRefNo, readOnly: true },
                { type: 'dropdown', title: 'Required\n Size(s)', width: '7%', align: 'left', source: data.sizeData, multiple: true, readOnly: true },
                { title: 'Qty. (Pcs.)', width: '5%', align: 'center', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 2) 
                {
                    if(val === true) {
                        selectedArray.push(row);
                    } else {
                        selectedArray = selectedArray.filter(function(e) {return e != row})
                    }
                    getReferenceValue(list.data[row], val, row);
                }
            }
        };

        sampleRequest_vm = new Vue({
            el: '#sampleRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });

        if(parseInt(data.ref_status) > 0) {
            requirementData = data.referResult;
            cad_ref_data = data.cad_ref_data;
            cadMaterialIndent = data.cadMaterialIndent;
            UOMDetails = data.UOMDetails;
            itemDescription = data.BOMAppendData.itemDescription;
            bcm = data.BOMAppendData.bcm;
            garmentSize = data.BOMAppendData.garmentSize;
            itemCode = data.BOMAppendData.itemCode;
            itemColor = data.BOMAppendData.itemColor;
            sizeDimension = data.BOMAppendData.sizeDimension;
            uom = data.BOMAppendData.uom;
            fabricColor = data.FabricAppendData.fabricColor;
            fabricGarment = data.FabricAppendData.fabricGarment;
            fabricBlend = data.FabricAppendData.fabricBlend;
            fabricContent = data.FabricAppendData.fabricContent;
            fabricName = data.FabricAppendData.fabricName;
            fabricGSM = data.FabricAppendData.fabricGSM;
            fabricDIA = data.FabricAppendData.fabricDIA;
            fabricUOM = data.FabricAppendData.fabricUOM;
            BOMMaterialIndent = data.bom_mi_tbl_data;
            FabricMaterialIndent = data.fabric_mi_tbl_data;
            MiData = data.mi_data;

            if(MiData.length > 0)
            {
                $('#cad_req_date').val(MiData[0].cad_req_date);
                $('#cad_cutoff_date').val(MiData[0].cad_cutoff_date);
                $('#bom_req_date').val(MiData[0].bom_req_date);
                $('#bom_cutoff_date').val(MiData[0].bom_cutoff_date);
            }

            append_attach_reference();
            append_cad_material_indent();
            append_fabric_material_indent(true, data.referResult);
            $('#saveRequestDetails').show();
            selectCount = parseInt(data.ref_status);
            mode = 'edit';
            req_id = data.req_id;
        }
    }


    function getReferenceValue(data, status, row) {
        // console.log(data);
        if(selectedArray.length === 0) {
            $('#saveRequestDetails').hide();
        } else {
            $('#saveRequestDetails').show();
        }
        if(status == true) {
            let emparr = [];
            let length = data.length;
            for(let i=0; i < data.length; i++) {
                if(i < length-6) {
                    emparr.push(data[i])
                }
            }
            for(let i=0; i < 5; i++) {
                emparr.push("")
            }
            // console.log(emparr);
            requirementData.push(emparr);
            selectCount = selectCount+1;
        }
        else {
            // console.log(data[0])
            requirementData = requirementData.filter(function(e) { if(e[1]!== data[1]) return e  })
            selectCount = selectCount-1;
        }
        append_attach_reference();
        // append_cad_material_indent();
        // append_fabric_material_indent(status, row);
    }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_attach_reference() {
        let data = requirementData;
        let common_dd = [
            { id: '1', name: 'Attached' }, 
            { id: '2', name: 'Pending' }, 
            { id: '3', name: 'N.A.' }, 
        ];
        
        $('#attachReference').html('');
        let list = {
            data: data,
            columns: [
                { title:'mode', width:'10%',align:'center',type:'hidden'},
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'hidden', title: 'Mark', width: '8%', align: 'left' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Approved & Graded\n Measurement Chart', width: '7%', align: 'left', source: common_dd, readOnly: true },
                { type: 'dropdown', title: 'Complete Artwork', width: '7%', align: 'left', source: common_dd, readOnly: true },
                { type: 'dropdown', title: 'How to Measure\n Details', width: '7%', align: 'left', source: common_dd, readOnly: true },
                { type: 'dropdown', title: 'Buyers Original \nSample or Pattern', width: '7%', align: 'left', source: common_dd, readOnly: true },
                { type: 'dropdown', title: "Buyer's Comments", width: '7%', align: 'left', source: common_dd, readOnly: true },
            ],
            minDimensions: [4, 0],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        sampleReference_vm = new Vue({
            el: '#attachReference',
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
    //  CAD MATERIAL INDENT STARTS HERE 
    // ***********************************************************************************************************************************
    
    function append_cad_material_indent() {
        let data = requirementData;
        let requirementSource = [
            {id: '1', name: 'Bit Marker'}, 
            {id: '2', name: 'Pattern'}, 
            {id: '3', name: 'Pattern (Size Set)'}, 
            {id: '4', name: 'Lay Marker'}, 
            {id: '5', name: 'Others'}, 
        ];
        
        let purposeSource = [
            {id: '1', name: 'Costing'}, 
            {id: '2', name: 'FCC - Sample'}, 
            {id: '3', name: 'FCC - Bulk'}, 
            {id: '4', name: 'Cutting - Sample'}, 
            {id: '5', name: 'Cutting - Bulk'}, 
            {id: '6', name: 'Bit Cutting - Sample'}, 
            {id: '7', name: 'Bit Cutting - Bulk'}, 
            {id: '8', name: 'Others'}, 
        ];
        
        $('#cadMaterialIndent').html('');
        let list = { 
            data: cadMaterialIndent,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'hidden', title: 'Mark', width: '8%', align: 'left' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'CAD Ref. No.', width: '12%', align: 'left', source: cad_ref_data, readOnly: true },
                { type: 'dropdown', title: 'Requirement', width: '7%', align: 'left', source: requirementSource, readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: purposeSource, readOnly: true },
                { type: 'dropdown', title: 'Required \nSize(s)', width: '7%', align: 'left', source: sizeData, readOnly: true },
                { type: 'text', title: 'D.C. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'calendar', title: 'D.C. \nDate & Time', width: '8%', align: 'left', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
            ], 
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        cad_material_vm = new Vue({
            el: '#cadMaterialIndent',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    // *********************************************************************************************************************************** 
    //  CAD MATERIAL INDENT ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    //  FABRIC MATERIAL INDENT STARTS HERE 
    // ***********************************************************************************************************************************
        
    function append_fabric_material_indent(status, data) {
        for (let i = 0; i < data.length; i++) {
            $('#bomMaterialIndent'+data[i][1]).html('');
            generateBomMaterialIndent(data[i][1], i);
            $('#fabricMaterialIndent'+data[i][1]).html('');
            generateFabricMaterialIndent(data[i][1], i);
        }
        
        // *** STARTS BOM MATERIAL INDENT DYNAMIC TABLE BASED ON SELECTION *** /

        function generateBomMaterialIndent(id, i) {
            let list = {
                data: BOMMaterialIndent[i],
                columns: [
                    { title:'id', width:'10%',align:'center',type:'hidden'},
                    { type: 'dropdown', title: 'Item Description', width: '8%', align: 'left', source: itemDescription , readOnly: true},
                    { type: 'dropdown', title: 'Blend (%) / Content /\n Material', width: '8%', align: 'left', source: bcm, filter: bcmFilter , readOnly: true},
                    { type: 'dropdown', title: 'Garment \n Size(s)', width: '8%', align: 'left', source: garmentSize, filter: garmentFilter , readOnly: true},
                    { type: 'dropdown', title: 'Item Code', width: '8%', align: 'left' , source: itemCode, filter: itemCodeFilter , readOnly: true},
                    { type: 'dropdown', title: 'Item Colour\n Code', width: '8%', align: 'left', source: itemColor, filter: itemColorFilter , readOnly: true},
                    { type: 'dropdown', title: 'Size /\n Dimension', width: '8%', align: 'left', source: sizeDimension, filter: sizeDimensionFilter , readOnly: true},
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: uom, filter: uomFilter , readOnly: true},
                    { type: 'text', title: 'M.I. Qty.', width: '8%', align: 'right' , readOnly: true},
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails , readOnly: true},
                    { type: 'text', title: 'D.C. No.', width: '8%', align: 'left', readOnly: true },
                    { type: 'text', title: 'D.C. \n Date & Time.', width: '8%', align: 'left', readOnly: true },
                ],
                minDimensions: [4, 1],
                allowDeleteColumn: true,
                allowInsertRow: true,
                allowInsertColumn: true,
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

        // *** STARTS FABRIC MATERIAL INDENT DYNAMIC TABLE BASED ON SELECTION *** /

        function generateFabricMaterialIndent(id, i) {
            list = {
                data: FabricMaterialIndent[i],
                columns: [
                    { title:'id', width:'10%',align:'center',type:'hidden'},
                    { type: 'text', title: 'Fabric Ref. No.', width: '8%', align: 'left', readOnly: true},
                    { type: 'dropdown', title: 'Colour', width: '8%', align: 'left', source: fabricColor, readOnly: true },
                    { type: 'dropdown', title: 'Garment Parts', width: '8%', align: 'left', source: fabricGarment, filter: fabricGarmentFilter ,readOnly: true},
                    { type: 'dropdown', title: 'Fabric \n Blend (%)', width: '8%', align: 'left', source: fabricBlend, filter: fabricBlendFilter ,readOnly: true},
                    { type: 'dropdown', title: 'Fabric \n Content', width: '8%', align: 'left', source: fabricContent, filter: fabricContentFilter,readOnly: true},
                    { type: 'dropdown', title: 'Fabric \n Name', width: '8%', align: 'left', source: fabricName, filter: fabricNameFilter,readOnly: true},
                    { type: 'dropdown', title: 'GSM', width: '8%', align: 'left', source: fabricGSM, filter: fabricGSMFilter,readOnly: true},
                    { type: 'dropdown', title: 'DIA / DIM \n (W*H)', width: '8%', align: 'left', source: fabricDIA, filter: fabricDIAFilter,readOnly: true},
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: fabricUOM, filter: fabricUOMFilter ,readOnly: true},
                    { type: 'text', title: 'M.I. Qty.', width: '8%', align: 'right' , readOnly: true},
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails , readOnly: true},
                    { type: 'text', title: 'D.C. No.', width: '8%', align: 'left', readOnly: true },
                    { type: 'text', title: 'D.C.\nDate & Time.', width: '8%', align: 'left', readOnly: true },
                ],
                minDimensions: [4, 1],
                allowDeleteColumn: true,
                allowInsertRow: true,
                allowInsertColumn: true,
            };
    
            fabric_mi_tbl_data = new Vue({
                el: '#fabricMaterialIndent'+id,
                mounted: function () {
                    let spreadsheet = jexcel(this.$el, list);
                    Object.assign(this, spreadsheet);
                },
            });

            let tblData = { 'tbl_data': fabric_mi_tbl_data };
            fabric_dynamic_mi_data.push(tblData);

        } 

    }
    
    /*********** Fabric Filter Details ********* */

    function fabricGarmentFilter(instance, cell, c, r, source) {
        var color_id = instance.jexcel.getValueFromCoords(c - 1, r);
        if (color_id !== "") {
            return source.filter(function (item) {
                if (item.color_id == color_id) return true;
            })
        } else {
            return [];
        }
    }

    function fabricBlendFilter(instance, cell, c, r, source) {
        let garment_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let color_id = instance.jexcel.getValueFromCoords(c - 2, r);
        if (garment_id !== "" && color_id !== "") {
            return source.filter(function (item) {
                if (item.color_id == color_id && item.garment_id == garment_id) return true;
            })
        } else {
            return [];
        }
    }

    function fabricContentFilter(instance, cell, c, r, source) {
        let blend_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let garment_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let color_id = instance.jexcel.getValueFromCoords(c - 3, r);

        if (garment_id != "" && color_id != "" && blend_id != "") {
            return source.filter(function (item) {
                if ((item.color_id == color_id) && (item.garment_id == garment_id) && (item.blend_id == blend_id)) return true;
            })
        } else {
            return [];
        }
    }

    function fabricNameFilter(instance, cell, c, r, source) {
        let content_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let blend_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let garment_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let color_id = instance.jexcel.getValueFromCoords(c - 4, r);

        if (garment_id != "" && color_id != "" && blend_id != "" && content_id != "") {
            return source.filter(function (item) {
                if ((item.color_id == color_id) && (item.garment_id == garment_id) && (item.blend_id == blend_id) && (item.content_id == content_id)) return true;
            })
        } else {
            return [];
        }
    }

    function fabricGSMFilter(instance, cell, c, r, source) {
        let name_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let content_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let blend_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let garment_id = instance.jexcel.getValueFromCoords(c - 4, r);
        let color_id = instance.jexcel.getValueFromCoords(c - 5, r);

        if (garment_id != "" && color_id != "" && blend_id != "" && content_id != "" && name_id != "") {
            return source.filter(function (item) {
                if ((item.color_id == color_id) && (item.garment_id == garment_id) && (item.blend_id == blend_id) 
                && (item.content_id == content_id) && (item.name_id == name_id)) return true;
            })
        } else {
            return [];
        }
    }

    function fabricDIAFilter(instance, cell, c, r, source) {
        let gsm_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let name_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let content_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let blend_id = instance.jexcel.getValueFromCoords(c - 4, r);
        let garment_id = instance.jexcel.getValueFromCoords(c - 5, r);
        let color_id = instance.jexcel.getValueFromCoords(c - 6, r);

        if (garment_id != "" && color_id != "" && blend_id != "" && content_id != "" && name_id != "" && gsm_id != "") {
            return source.filter(function (item) {
                if ((item.color_id == color_id) && (item.garment_id == garment_id) && (item.blend_id == blend_id) 
                && (item.content_id == content_id) && (item.name_id == name_id) && (item.gsm_id == gsm_id)) return true;
            })
        } else {
            return [];
        }
    }

    function fabricUOMFilter(instance, cell, c, r, source) {
        let dia_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let gsm_id = instance.jexcel.getValueFromCoords(c - 2, r);
        let name_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let content_id = instance.jexcel.getValueFromCoords(c - 4, r);
        let blend_id = instance.jexcel.getValueFromCoords(c - 5, r);
        let garment_id = instance.jexcel.getValueFromCoords(c - 6, r);
        let color_id = instance.jexcel.getValueFromCoords(c - 7, r);

        if (garment_id != "" && color_id != "" && blend_id != "" && content_id != "" && name_id != "" && gsm_id != ""  && dia_id != "") {
            return source.filter(function (item) {
                if ((item.color_id == color_id) && (item.garment_id == garment_id) && (item.blend_id == blend_id) 
                && (item.content_id == content_id) && (item.name_id == name_id) && (item.gsm_id == gsm_id) && (item.dia_id == dia_id)) return true;
            })
        } else {
            return [];
        }
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
        
    
    // *********************************************************************************************************************************** 
    //  FABRIC MATERIAL INDENT ENDS HERE 
    // ***********************************************************************************************************************************
    
});