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
    //var itemDescription = bcm = garmentSize = itemCode = itemColor = sizeDimension = uom = [];
     var itemDescription = bcm = garmentSize = itemCode = itemColor = sizeDimension = uom = type=[];
   
    var fabricColor = fabricGarment = fabricBlend = fabricContent = fabricName = fabricGSM = fabricDIA = fabricUOM = [];
    var bom_dynamic_mi_data = [];
    var fabric_dynamic_mi_data = [];
    var req_id = '';
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
        if(mode == "checkError") {
            return {
                title: 'Warning',
                text: "Select Item in Job Status Update",
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
        data.append('reqId', request_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/getQASampleRequestList',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                append_sample_request(sample_requirement_data);
                append_qa_status_update(sample_requirement_data);
                append_job_status_update(sample_requirement_data);
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
        data.append('reqId', request_id);
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
                console.log(imageJSON);
                let subscriberId = imageJSON.subscriber_id;
               for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.ImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON.images[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/sample/'+subscriberId+'/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/sample/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
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
            itemDescription = data.BOMAppendData.itemDescription.concat(data.BOM2AppendData.itemDescription);
            bcm = data.BOMAppendData.bcm.concat(data.BOM2AppendData.bcm);
            garmentSize = data.BOMAppendData.garmentSize.concat(data.BOM2AppendData.garmentSize);
            itemCode = data.BOMAppendData.itemCode.concat(data.BOM2AppendData.itemCode);
            itemColor = data.BOMAppendData.itemColor.concat(data.BOM2AppendData.itemColor);
            sizeDimension = data.BOMAppendData.sizeDimension.concat(data.BOM2AppendData.sizeDimension);
            uom = data.BOMAppendData.uom.concat(data.BOM2AppendData.uom);
            type = data.BOMAppendData.type.concat(data.BOM2AppendData.type);


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
                let issued_type = MiData[0].issued_type;
                let issued_type1 = issued_type.split(",");
                if (issued_type1.includes("BOM")) {
                    $('#bomDiv').show();
                } else {
                    $('#bomDiv').hide();
                }
                if (issued_type1.includes("CAD")) {
                    $('#cadDiv').show();
                } else {
                    $('#cadDiv').hide();
                }
                if (issued_type1.includes("FABRIC")) {
                    $('#fabDiv').show();
                } else {
                    $('#fabDiv').hide();
                }
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
                { type: 'text', title: 'D.C. \n Date & Time', width: '8%', align: 'left', readOnly: true },
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
                    { type: 'dropdown', title: 'Item Description', width: '10%', align: 'left', source: itemDescription , readOnly: true},
                    { type: 'dropdown', title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', source: bcm, filter: bcmFilter , readOnly: true},
                    { type: 'dropdown', title: 'Garment \n Size(s)', width: '6%', align: 'left', source: garmentSize, filter: garmentFilter , readOnly: true},
                    { type: 'dropdown', title: 'Item Code', width: '8%', align: 'left' , source: itemCode, filter: itemCodeFilter , readOnly: true},
                    { type: 'dropdown', title: 'Item Colour\n Code', width: '8%', align: 'left', source: itemColor, filter: itemColorFilter , readOnly: true},
                    { type: 'dropdown', title: 'Size /\n Dimension', width: '8%', align: 'left', source: sizeDimension, filter: sizeDimensionFilter , readOnly: true},
                    { type: 'dropdown', title: 'UOM', width: '7%', align: 'left', source: uom, filter: uomFilter , readOnly: true},
                    { type: 'text', title: 'M.I. Qty.', width: '7%', align: 'right' , readOnly: true},
                    { type: 'dropdown', title: 'UOM', width: '7%', align: 'left', source: UOMDetails , readOnly: true},
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
    
      $('#qaRequestLink').click(function () {

           let jobStatusmessage = {
    title: 'Error!',
    text: 'Please select proprer JOB STATUS. ',
    icon: 'warning'
};      

     if ($('input[type="checkbox"]:checked').length === 0) {
        swalWithBootstrapButtons.fire(
            'Error!',
            'Please select at least one JOB STATUS.',
            'warning'
        );
    }else{
        let jobData = jobsampleReference_vm.getData();
    let filteredData = jobData.filter(row => row[3] === true); 
     let ids = filteredData.map(row => row[0]); 
     // Assuming column 3 is the "true" condition
    let jobstatus = filteredData.map(row => row[12]); 
   jobstatus_request='3';
   jobstatus_rrequest='5';
    let idsString = ids.join(',');
           let base64Encoded3 = btoa(idsString);
           let idsStrings = encodeURIComponent(base64Encoded3); 
          

           let base64Encoded1 = btoa(enquiry_id);
           let enquiry_ids = encodeURIComponent(base64Encoded1);
           let base64Encoded2 = btoa(req_id);
           let requiredIds = encodeURIComponent(base64Encoded2);

   if (jobstatus.every(status => status === jobstatus_request || status === jobstatus_rrequest)) {
        
            if(idsStrings!= null && enquiry_ids != null && requiredIds != null){
                 window.location.href = base_path + 'request/Samplerequest/qarequest/' + enquiry_ids + '/reqId/' + requiredIds +'/checkid/'+ idsStrings;
                 // window.location.href = base_path + 'request/Samplerequest/qarequest/' + enquiry_ids + '/reqId/' + requiredIds;  
            }
        
    }else{
       swalWithBootstrapButtons.fire(jobStatusmessage); 
    }
    }
});
    $('#getValues').click(function () {

        let jobStatusmessage = {
    title: 'Error!',
    text: 'Please Submit SampleQ.A .request button  ',
    icon: 'warning'
};      
let qaStatusmessage = {
    title: 'Error!',
    text: 'Please job status is not applicable for complectioin   ',
    icon: 'warning'
};      
        $('.herr').hide();
        if($('#dep_remarks').val() == "" || $('#dep_remarks').val() == null ) {
            $('#err_dep_remarks').html("Fill dept. remark");
            $('#err_dep_remarks').show();
        }
         else if ($('input[type="checkbox"]:checked').length === 0) {
        swalWithBootstrapButtons.fire(
            'Error!',
            'Please select at least one job Status.',
            'warning'
        );
    } else if ($('#dep_remarks').val().trim() !== "") { // Added trim() to avoid empty spaces
    let jobData = jobsampleReference_vm.getData();
    let filteredData = jobData.filter(row => row[3] === true);  // Assuming column 3 is the "true" condition
    let jobstatus = filteredData.map(row => row[12]); 
    // let qastatus = filteredData.map(row => row[12]);
    // console.log(qastatus);
   jobstatus_request='3';
   jobstatus_rrequest='5';

   

    if (jobstatus.includes(jobstatus_request) || jobstatus.includes(jobstatus_rrequest)) {
       
        swalWithBootstrapButtons.fire(jobStatusmessage); // Assuming jobStatusmessage is predefined
    }
    else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    updateFunction();
                } 
                else if (result.dismiss === Swal.DismissReason.cancel) {
                    // *** CANCELLED MESSAGE *** //
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );
                }
            });
        }
}
        
    
        
    });

    function updateFunction() {

        let dataform = new FormData();
        let tbl_data = jobsampleReference_vm.getData();
        dataform.append('data', JSON.stringify(tbl_data));
        dataform.append('dep_remarks', $('#dep_remarks').val());
        dataform.append('request_id', req_id);
        dataform.append('enquiry_id', enquiry_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/updateQASampleRequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                 let res = JSON.parse(data);
                    if (res.status === "success") {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if (okay) {
                         window.location.href = base_path + 'company/msamplinguser/samplequeuelist';
                    }
                 });
                } else if (res.status === "job_failure") {
                   swalWithBootstrapButtons.fire({
                  title: 'Job status  Failed',
                  text: 'The job process CompleteMent has failed',
                  icon: 'error',
                  confirmButtonText: 'OK'
                    }).then((result) => {
        if (result.isConfirmed) {
            //window.location.href = base_path + "company/mcaduser/cadqueuelist";
              window.location.href = base_path + 'company/msamplinguser/samplequeuelist';
           // window.location.href = base_path + "request/Cadrequest/cadDeptQueueDetails/"+ encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(reqId));
            
        }
    });
                    
                    } 
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    $('#qaCompleted').click(function () {
        
        let jobData = jobsampleReference_vm.getData();
        let jobDataCount = validateQAForm(jobData);
        
        if(jobDataCount > 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('checkError')
            );
        }
        else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    updateCompletedFunction();
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
    
    function updateCompletedFunction() {

        let dataform = new FormData();
        let job_status_data = jobsampleReference_vm.getData();
        dataform.append('job_status_data', JSON.stringify(job_status_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('request_id', req_id);
        

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/updateJobCompleted',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let res = JSON.parse(data);
                if(res.status == "success")
                {
                    swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if (okay) {
                         window.location.href = base_path + 'company/msamplinguser/samplequeuelist';
                    }
                 });
                    // window.location.href = base_path+"company/msamplinguser/samplequeuelist"; 
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    
    function append_qa_status_update(data) {

        // let common_dd = [
        //     { id: '0', name: 'PENDING'},
        //     { id: '3', name: 'PENDING - RR'},
        //     { id: '1', name: 'SCHEDULED'}, 
        //     { id: '2', name: 'RESCHEDULED'},
        //     { id: '4', name: 'DISCREPANCY'}, 
        //     { id: '5', name: 'PASS'}, 
        //     { id: '6', name: 'PASS COND.'}, 
        //     { id: '7', name: 'FAIL'}, 
        // ];
        let common_dd = [
        { id: '0', name: 'IN QUEUE' }, 
        { id: '1', name: 'SCHEDULED' }, 
        { id: '2', name: 'RE-SCHEDULED' }, 
        { id: '3', name: 'Q.A. IN PROGRESS' },
        { id: '4', name: 'NEED ALTERATION' }, 
        { id: '5', name: 'PASS' }, 
        { id: '6', name: 'PASS COND.' }, 
        { id: '7', name: 'FAIL' }, 
        { id: '8', name: '-' },
         { id: '9', name: 'IN-QUEUE RR'}
    ];

        $('#qaStatusReference').html('');
        let list = {
            data: data.qastatusdata,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Assigned Sample\n Reference No.', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Q.A. Request Sent\n Date & Time', width: '7%', align: 'center', readOnly: true },
                { type: 'calendar', title: 'Q.A. Scheduled\n Date & Time', width: '7%', align: 'center', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                { type: 'dropdown', title: 'Q.A. Status', width: '7%', align: 'center', source: common_dd, readOnly: true },
                { type: 'text', title: "Q.A. Status Update\n Date & Time", width: '7%', align: 'center', readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
             updateTable: function (instance, cell, col, row, val, label) {
            if (col === 9) { // Q.A. Status column index
                setTimeout(() => {
                    const statusId = val?.toString();
                

                  
                    let backgroundColor = '';
                    let textColor = 'black';
                    const group1 = ['0', '1', '2', '3'];
                  
                    const group2 = ['5', '6'];
                   
                    const group3 = ['7','4','9'];

                    if (group1.includes(statusId)) {
                        backgroundColor = '#FFA519'; // light yellow
                        } else if (group2.includes(statusId)) {
                        backgroundColor = '#5DE684'; // light green
                       
                    } else if (group3.includes(statusId)) {
                        backgroundColor = '#fc0303ff'; // light PURPLE
                       
                    }

                    
                    $(cell).css({
                        'background-color': backgroundColor,
                        'color': textColor,
                        'font-weight': 'bold'
                    });
                }, 10);
            }
        }
        };

        qasampleReference_vm = new Vue({
            el: '#qaStatusReference',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }

    function append_job_status_update(data) {

        // let common_dd = [
        //     { id: '0', name: 'IN - QUEUE'}, 
        //     { id: '1', name: 'SCHEDULED'}, 
        //     { id: '2', name: 'RESCHEDULED'}, 
        //     { id: '3', name: 'Q.A. REQ.'}, 
        //     { id: '5', name: 'Q.A. RR'},
        //     { id: '4', name: 'COMPLETED'}, 
        //     { id: '6', name: 'REWORK'},
        // ];

        let common_dd = [
            { id: '0', name: 'IN-QUEUE'}, 
            { id: '1', name: 'SCHEDULED'}, 
            { id: '2', name: 'RESCHEDULED'}, 
            { id: '8', name: 'JOB IN PROG.'},
            { id: '3', name: 'Q.A. REQ.SENT'}, 
            { id: '6', name: 'ALT. PEND.'},
            { id: '7', name: 'ALT. IN PROG.'},
            { id: '5', name: 'Q.A. RR SENT '},
            { id: '10', name: 'REWORK'},
            { id: '4', name: 'COMPLETED'}, 
            { id: '9', name: 'GAR. ISSUED'}, 
           
          ];

        let updatedRow = '';
        let job_sta_update = '';
        let job_re_sta_update = '';
        let job_sta = '';

        $('#jobStatusReference').html('');
        let list = {
            data: data.jobstatusdata,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { title:'Job Status Update', type:'hidden'},
                { title:'Job Re Status Update', type:'hidden'},
                { title:'Mark', type:'checkbox', width:'2%'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Requirement', width: '7%', align: 'left', readOnly: true },
                { title: 'Assigned Sample\n Reference No.', width: '7%', align: 'left', readOnly: true },
                { type: 'calendar', title: 'Job Scheduled\n Date & Time', width: '7%', align: 'center', options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                { type: 'dropdown', title: 'Job Status', width: '7%', align: 'center', source: common_dd,filter: jobstatus_updation, },
                { type: 'text', title: 'Job Status Update\n Date & Time', width: '7%', align: 'center', readOnly: true },
                { title:'compS tatus', type:'hidden'},
                { title:'qastatus', type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 11) 
                {
                    updatedRow = row;
                    job_sta = val;
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 1)
                {
                    job_sta_update = val;
                }
                if(col == 2)
                {
                    job_re_sta_update = val;
                }
                if(col == 3)
                {
                    // if(data.jobstatusdata[row][14] == 'Yes') {
                    //     $(cell).removeClass('readonly');
                    // } else {
                    //     $(cell).addClass('readonly');
                    // }
                     $(cell).removeClass('readonly');
                }
                if(col == 11) {
                 
                    if(data.jobstatusdata[row][12] == 0 || data.jobstatusdata[row][12] == 1 || data.jobstatusdata[row][12] == 2 )
                    {$(cell).removeClass('readonly'); }
                    else 
                    { $(cell).addClass('readonly');
                    }

                //     if(data.jobStatusData[row][3] == true )
                // {
                //       $(cell).removeClass('readonly');
                //    if (['0', '1', '2'].includes(String(data.jobStatusData[row][12]))) {
                //     $(cell).removeClass('readonly');
                // } else {
                //     $(cell).addClass('readonly');
                // } 
                // }else{
                //    $(cell).addClass('readonly');
                // }
                }
                if(col == 12 )
                {
                
                   
                    // if(data.jobstatusdata[row][12] == 0 || data.jobstatusdata[row][12] == "0" || data.jobstatusdata[row][12] == 4 || data.jobstatusdata[row][12] == "4"|| data.jobstatusdata[row][12] == 9 || data.jobstatusdata[row][12] == "9"  ) {
                    //      $(cell).addClass('readonly');
                    // }   

                     setTimeout(() => {
                    const statusId = val?.toString();

                  
                    let backgroundColor = '';
                    let textColor = 'black';
                    const group1 = ['0', '1', '2', '3', '8'];
                  
                    const group2 = ['4', '9'];
                   
                    const group3 = ['5','6','7','10'];

                    if (group1.includes(statusId)) {
                        backgroundColor = '#FFA519'; // light yellow
                        } 
                    else if (group2.includes(statusId)) {
                        backgroundColor = '#5DE684'; // light green
                       
                    } else if (group3.includes(statusId)) {
                        backgroundColor = '#fc0303ff'; // light PURPLE
                       
                    }

                    $(cell).css({
                        'background-color': backgroundColor,
                        'color': textColor,
                        'font-weight': 'bold'
                    });
                }, 10);
                }
            }
        };

        jobsampleReference_vm = new Vue({
            el: '#jobStatusReference',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    function jobstatus_updation(instance, cell, c, r, source) {
          let qa_status = instance.jexcel.getValueFromCoords(15, r);
        
         let checkbox = instance.jexcel.getValueFromCoords(3, r);
          let job_status = instance.jexcel.getValueFromCoords(12, r);
         let filteredOptions1;
      //console.log(checkbox,'source');
       if ((checkbox === true) && ( qa_status != '1' && qa_status != '2' && qa_status != '3'  && qa_status != '9'  && qa_status != '7' ) ) {
         //$(cell).removeClass('readonly');
         let filteredOptions;
    if (job_status == '0' || job_status == '1' || job_status == '2' ) {
         filteredOptions = source.map(function (item) {
           
            if (item.id == '8'  ) {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
    else if (job_status == '3') {
        filteredOptions = source.map(function (item) {
            // Enable '3' and '4' options, disable others
            if (item.id == '3' || item.id == '4') {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
     else if ((job_status == '4' ) ) {
        filteredOptions = source.map(function (item) {
            // Enable '3' and '4' options, disable others
            if (item.id == '4' ) {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
    else if (job_status == '5') {
        filteredOptions = source.map(function (item) {
            // Enable '3' and '4' options, disable others
            if (item.id == '4' ||item.id == '5') {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
     else if (job_status == '6') {
        filteredOptions = source.map(function (item) {
            // Enable '3' and '4' options, disable others
            if (item.id == '5' ||item.id == '6' || item.id == '7') {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
     else if (job_status == '7') {
        filteredOptions = source.map(function (item) {
            // Enable '3' and '4' options, disable others
            if (item.id == '7' ||item.id == '5') {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
     else if (job_status == '8') {
        filteredOptions = source.map(function (item) {
            // Enable '3' and '4' options, disable others
            if (item.id == '3' || item.id == '8') {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }else{  
        filteredOptions = source.map(function (item) {
            // Disable all options for other job statuses
            return { ...item, disabled: true };
        });

    }
    
    
     
    
  
     return filteredOptions;
       }
       else {
        filteredOptions1 = source.map(function (item) {
            return { ...item, disabled: true }; // Disable all options for other job statuses
        });
          //return $(cell).addClass('readonly');
           return filteredOptions1;
    }    
    }
    function validateQAForm(dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][14] == 'Yes' ) {
                if(dataValue[i][3] == false ) {
                    errorCount++;
                }
            } 

        }
        return errorCount;
    }


});