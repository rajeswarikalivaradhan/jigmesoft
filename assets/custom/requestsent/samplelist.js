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
    var vendorDetails = '';
    $('#saveRequestDetails').hide();

    //vendorDetails = getVendorDetails();
    getSampleRequest();
    
    // function getVendorDetails() {
    //     let request = $.ajax({
    //         type: "GET",
    //         url: base_path + 'request/Samplerequest/getVendorDetails',
    //         success: function (data) {
    //             vendorDetails = JSON.parse(data);
    //             //getSampleRequest();
    //         },
    //         error: function () {
    //             console.log("Error");
    //         }
            
    //     });
        
    //     return vendorDetails;
    // }
    
    //getSampleRequestImages();
    //getSampleReqImages();
    
    // let typeVal = $('#type').val();
    // if(typeVal !== '') {
        
    // $('#cad_dept,#fab_dept,#bom_dept').html('<option value="">Select</option>');
    //     if(typeVal== 'INTERNAL')
    //     {
    //         $('#cad_dept,#fab_dept,#bom_dept').append('<option value="">Select</option>'+
    //                 '<option value="SAMPLE DEPT.">SAMPLE DEPT.</option>'+
    //                 '<option value="PRODUCTION DEPT.">PRODUCTION DEPT.</option>'
    //         );
    //     }
    //     else {
    //         let request = $.ajax({
    //         type: "GET",
    //         url: base_path + 'request/Samplerequest/getVendorDetails',
    //         success: function (data) {
    //             vendorDetails = JSON.parse(data);
                
    //             for (let i = 0; i < vendorDetails.length; i++) {
    //             vendorOption = "<option value='"+vendorDetails[i].id+"'>"+vendorDetails[i].name+"</option>";
    //             $('#cad_dept,#fab_dept,#bom_dept').append(vendorOption);
    //         }
    //         }
            
    //     });
            
    //     }
    // }

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

                  let auth_status = sample_requirement_data.req_data[0].auth_status;

                // $('#req_type').val(reqData[0].req_type);
                // $('#req_type').trigger('change');
                // $('#req_date').val(reqData[0].req_date);
                // $('#cutoff_date').val(reqData[0].cutoff_date);
                // $('#merchant_note').val(reqData[0].merchant_note);
                append_sample_request(sample_requirement_data);
                getSampleRequestImages(auth_status)
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getSampleRequestImages(auth_status) {
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
                  let subscriberId = imageJSON.subscriber_id;
                
                 for (let i = 0; i < imageJSON.images.length; i++) {
                     let deleteIcon = ''; 
                       if (auth_status == 2) {
                 deleteIcon = 
            '<a href="javascript:void(0);" data-id="'+imageJSON.images[i].wip_files_id+'" class="deleteImg" title="Delete">'+
                '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
            '</a>';
              }
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
                                    deleteIcon + 
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
    
    function getSampleReqImages() {
        $('.ImageViews').html('');
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
                    $('.ImageViews').append(
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
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'] },
                { type: 'dropdown', title: 'If Revised or In-line\n Prev. Sample Ref. No.', width: '8%', align: 'left', source: data.sampleRefNo, readOnly: true },
                { type: 'dropdown', title: 'Required\n Size(s)', width: '7%', align: 'left', source: data.sizeData, multiple: true, readOnly: true },
                { title: 'Qty. (Pcs.)', width: '5%', align: 'center' }
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
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                    if(col === 6) {
                        if(data.req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                    if(col === 7) {
                        if(data.req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                    if(col === 8) {
                        if(data.req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                    if(col === 11) {
                        if(data.req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                    
                },
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
                //console.log(MiData[0].cad_dept);
                $('#cad_dept').val(MiData[0].cad_dept).trigger('change');
                $('#fab_dept').val(MiData[0].fab_dept).trigger('change');
                $('#bom_dept').val(MiData[0].bom_dept).trigger('change');
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
                
                // if(MiData[0].type !=  '' ) {
                //     $('#cad_dept,#fab_dept,#bom_dept').html('<option value="">Select</option>');
                //     if(MiData[0].type== 'INTERNAL')
                //     {
                //         $('#cad_dept,#fab_dept,#bom_dept').append('<option value="">Select</option>'+
                //             '<option value="SAMPLE DEPT.">SAMPLE DEPT.</option>'+
                //             '<option value="PRODUCTION DEPT.">PRODUCTION DEPT.</option>'
                //         );
                //     }
                //     else {
                //         let request = $.ajax({
                //         type: "GET",
                //         url: base_path + 'request/Samplerequest/getVendorDetails',
                //         success: function (data) {
                //             vendorDetails = JSON.parse(data);
                
                //             for (let i = 0; i < vendorDetails.length; i++) {
                //                 vendorOption = "<option value='"+vendorDetails[i].id+"'>"+vendorDetails[i].name+"</option>";
                //                 $('#cad_dept,#fab_dept,#bom_dept').append(vendorOption);
                //             }
                //         }
            
                //         });
            
                //     }
                // }
                
                
            }
            let reqData =  data.req_data;
            append_attach_reference(reqData);
            append_cad_material_indent(reqData);
            append_fabric_material_indent(true, data.referResult, data.req_data);
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
        let reqData = row;
        append_attach_reference(reqData);
        // append_cad_material_indent();
        // append_fabric_material_indent(status, row);
    }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_attach_reference(reqData) {
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
                { type: 'dropdown', title: 'Approved & Graded\n Measurement Chart', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: 'Complete Artwork', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: 'How to Measure\n Details', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: 'Buyers Original \nSample or Pattern', width: '7%', align: 'left', source: common_dd},
                { type: 'dropdown', title: "Buyer's Comments", width: '7%', align: 'left', source: common_dd},
            ],
            minDimensions: [4, 0],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 7) {
                    if(reqData[0].auth_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 8) {
                    if(reqData[0].auth_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 9) {
                    if(reqData[0].auth_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 10) {
                    if(reqData[0].auth_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 11) {
                    if(reqData[0].auth_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
            }
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
    
    function append_cad_material_indent(reqData) {
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
                { type: 'dropdown', title: 'CAD Ref. No.', width: '12%', align: 'left', source: cad_ref_data  },
                { type: 'dropdown', title: 'Requirement', width: '7%', align: 'left', source: requirementSource  },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: purposeSource },
                { type: 'dropdown', title: 'Required \nSize(s)', width: '7%', align: 'left', source: sizeData },
                { type: 'text', title: 'D.C. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'calendar', title: 'D.C. \nDate & Time', width: '8%', align: 'left', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
            ], 
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 6) {
                    if(reqData[0].auth_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 7) {
                    if(reqData[0].auth_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 8) {
                    if(reqData[0].auth_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 9) {
                    if(reqData[0].auth_status == 2) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                
            }
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
        
    function append_fabric_material_indent(status, data, req_data) {
        for (let i = 0; i < data.length; i++) {
            $('#bomMaterialIndent'+data[i][1]).html('');
            generateBomMaterialIndent(data[i][1], i, req_data);
            $('#fabricMaterialIndent'+data[i][1]).html('');
            generateFabricMaterialIndent(data[i][1], i);
        }
        
        // *** STARTS BOM MATERIAL INDENT DYNAMIC TABLE BASED ON SELECTION *** /

        function generateBomMaterialIndent(id, i, req_data) {
            // console.log(req_data[0].auth_status);
            let list = {
                data: BOMMaterialIndent[i],
                columns: [
                    { title:'id', width:'10%',align:'center',type:'hidden'},
                    { type: 'dropdown', title: 'Item Description', width: '8%', align: 'left', source: itemDescription },
                    { type: 'dropdown', title: 'Blend (%) / Content /\n Material', width: '8%', align: 'left', source: bcm, filter: bcmFilter },
                    { type: 'dropdown', title: 'Garment \n Size(s)', width: '8%', align: 'left', source: garmentSize, filter: garmentFilter },
                    { type: 'dropdown', title: 'Item Code', width: '8%', align: 'left' , source: itemCode, filter: itemCodeFilter},
                    { type: 'dropdown', title: 'Item Colour\n Code', width: '8%', align: 'left', source: itemColor, filter: itemColorFilter},
                    { type: 'dropdown', title: 'Size /\n Dimension', width: '8%', align: 'left', source: sizeDimension, filter: sizeDimensionFilter, readOnly: true},
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: uom, filter: uomFilter, readOnly: true },
                    { type: 'text', title: 'M.I. Qty.', width: '8%', align: 'right' },
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails},
                    { type: 'text', title: 'D.C. No.', width: '8%', align: 'left', readOnly: true },
                    { type: 'text', title: 'D.C. \n Date & Time.', width: '8%', align: 'left', readOnly: true },
                ],
                minDimensions: [4, 1],
                allowDeleteColumn: true,
                allowInsertRow: true,
                allowInsertColumn: true,
                updateTable: function(instance, cell, col, row, val, label, cellName) {
                    if(col === 1) {
                        item_val = val;
                        if(req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                    if(col === 2) {
                        
                        if(req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                    if(col === 3) {
                        size = val;
                        if(req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                    if(col === 4) {
                        appr_item_code = val;
                        if(req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                    if(col === 5) {
                        item_color_code = val;
                        if(req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                    if(col == 6) {
                        
                        if(item_val !== '' && size !== '' && appr_item_code !== '' && item_color_code !== ''  ) {
                            let sizeDia = sizeDimension;
                            let obj = sizeDia.find(o => o.item_code_id === appr_item_code, o =>o.item_color_id === item_color_code);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                            
                            size_id = obj.name;
                        }
                    }
                    if(col == 7) {
                        if(item_val !== '' && size !== '' && appr_item_code !== '' && item_color_code !== ''  ) {
                            let uomData = uom;
                            let obj = uomData.find(o => o.item_code_id === appr_item_code, o =>o.item_color_id === item_color_code, o => o.size_id === size_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                        }
                    }
                    
                    if(col === 8) {
                        if(req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                    if(col === 9) {
                        if(req_data[0].auth_status == 2) {
                            $(cell).removeClass('readonly');
                        } else {
                            $(cell).addClass('readonly');
                        }
                    }
                },
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
    
    // $('#getValues').click(function () {
        
    //         swalWithBootstrapButtons.fire(
    //             // *** CONFIRMATION MESSAGE *** //
    //             alertMessageFunction('confirmation_save')
    //         ).then(function (result) {
    //             if (result.value) {
    //                 // common form data
    //                 var dataValue = new FormData();
            
    //                 // get cad mi form data
    //                 var cad_form = $('#cad_mi_form')[0];
    //                 var cad_mi_details = new FormData(cad_form);
    //                 // get bom mi form data
    //                 var bom_form = $('#bom_mi_form')[0];
    //                 var bom_mi_details = new FormData(bom_form);
    //                 // get fabric mi form data
    //                 var fab_form = $('#fab_mi_form')[0];
    //                 var fab_mi_details = new FormData(fab_form);
            
    //                 // store cad mi form details
    //                 for(var pair of cad_mi_details.entries()) {
    //                     dataValue.append(pair[0], pair[1]);
    //                 }

    //                 // store fabric mi form details
    //                 for(var pair of fab_mi_details.entries()) {
    //                     dataValue.append(pair[0], pair[1]);
    //                 }
            
    //                 // store bom mi form details
    //                 for(var pair of bom_mi_details.entries()) {
    //                     dataValue.append(pair[0], pair[1]);
    //                 }

    //                 // get reference table data
    //                 let sample_details = sampleReference_vm.getData();
                    
    //                 // get cad material indent table data
    //                 let cad_mi_tbl_data = cad_material_vm.getData();
            
    //                 // get bom material indent data
    //                 let bom_mi_all_tbl_data = [];
    //                 for (let i = 1; i < requirementData.length+1; i++) {
    //                     let bom_mi_data = bom_dynamic_mi_data[i-1].tbl_data.getData();
    //                     var bom_tbl_data = { "bom_key_name": bom_mi_data };
            
    //                     const altObj = Object.fromEntries(
    //                         Object.entries(bom_tbl_data).map(([key, value]) => 
    //                         // Modify key here
    //                         [`${requirementData[i-1][1]}`, value]
    //                         )
    //                     )
            
    //                     bom_mi_all_tbl_data.push(altObj);
            
    //                 }

    //                 // get fabric material indent data
    //                 let fabric_mi_all_tbl_data = [];
    //                 for (let i = 1; i < requirementData.length+1; i++) {
    //                     let fabric_mi_data = fabric_dynamic_mi_data[i-1].tbl_data.getData();
    //                     var fabric_tbl_data = { "fabric_key_name": fabric_mi_data };
            
    //                     const altObj = Object.fromEntries(
    //                         Object.entries(fabric_tbl_data).map(([key, value]) => 
    //                         // Modify key here
    //                         [`${requirementData[i-1][1]}`, value]
    //                         )
    //                     )
            
    //                     fabric_mi_all_tbl_data.push(altObj);
            
    //                 }
                    

    //                let dataform = new FormData();
    //                dataValue.append('enquiry_id', enquiry_id);
    //                dataValue.append('auth_status', $('#auth_status').val());
    //                dataValue.append('auth_type', $('#auth_type').val());
    //                dataValue.append('mgmt_remark', $('#mgmt_remark').val());
    //                dataValue.append('request_id', req_id)
                    
    //                 // dataValue.append('cad_req_date', $('#cad_req_date').val());
    //                 // dataValue.append('fab_req_date', $('#fab_req_date').val());
    //                 // dataValue.append('bom_req_date', $('#bom_req_date').val());
    //                 // dataValue.append('auth_status', $('#auth_status').val());
    //                 // dataValue.append('cad_mi_tbl_data', JSON.stringify(cad_mi_tbl_data));
    //                 // dataValue.append('fabric_mi_tbl_data', JSON.stringify(fabric_mi_all_tbl_data));
    //                 // dataValue.append('bom_mi_tbl_data', JSON.stringify(bom_mi_all_tbl_data));
    //                 // dataValue.append('enquiry_id', enquiry_id);
    //                 // dataValue.append('request_id', req_id);
    //                 // dataValue.append('req_type', $('#req_type').val());
    //                 // dataValue.append('cutoff_date', $('#cutoff_date').val());
    //                 // dataValue.append('merchant_note', $('#merchant_note').val());
    //                 // dataValue.append('sample_details', JSON.stringify(sample_details));
    //                 // dataValue.append('issued_type', $('#issued_type').val());
    //                 // dataValue.append('type', $('#type').val());
    //                 // dataValue.append('auth_type', $('#auth_type').val());
    //                 // dataValue.append('mgmt_remark', $('#mgmt_remark').val());
                   
                    

    //                 updateMIFunction(dataValue);
    //             } 
    //             else if (result.dismiss === Swal.DismissReason.cancel) {
    //                 // *** CANCELLED MESSAGE *** //
    //                 swalWithBootstrapButtons.fire(
    //                     alertMessageFunction('cancelled')
    //                 );
    //             }
    //         });
        
    // });
    
    function updateMIFunction(dataValue) {
        let request = $.ajax({
            type: "POST",
           // url: base_path + 'request/Samplerequest/saveSampleReqDetails',
            url: base_path + 'request/Samplerequest/updateManagementSampleRequest',
            data: dataValue,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let val = JSON.parse(data);
                if(val.status == 'success')
                {
                    if(sampleUpload.selectedFiles == 0) {
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(function (result) {
                            if (result.value) {
                                 window.location.href = base_path + 'MerchantRequestSent/sample';
                            }
                        });
                    }
                    else {
                        sampleUpload.startUpload();
                    }
                }
                else {
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('error')
                    );
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    $('#getValues').click(function () {
        $('.herr').hide();
        if($('#auth_status').val() == "" || $('#auth_status').val() == null ) {
            $('#err_auth_status').html("Select authorization status");
            $('#err_auth_status').show();
        } 
        else if($('#auth_type').val() == "" || $('#auth_type').val() == null ) {
            $('#err_auth_type').html("Select authorization type");
            $('#err_auth_type').show();
        }
        else if($('#mgmt_remark').val() == "" || $('#mgmt_remark').val() == null ) {
            $('#err_mgmt_remark').html("Fill management reamrk");
            $('#err_mgmt_remark').show();
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
    });

    function updateFunction() {

        let dataform = new FormData();
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('auth_status', $('#auth_status').val());
        dataform.append('auth_type', $('#auth_type').val());
        dataform.append('mgmt_remark', $('#mgmt_remark').val());
        dataform.append('request_id', req_id);

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/updateManagementSampleRequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    sampleUpload.startUpload();
                    if (okay) {
                        window.location.href = base_path + 'management/sample';
                    }
                 });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    
    let sampleUpload = $("#samReqImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'request/Samplerequest/imageUploadDetails',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            return {
                enquiry_id: enquiry_id,
                request_id: req_id,
                type: 'sample_request',
            };
        },
        afterUploadAll: function () {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('saved')
            ).then(okay => {
                if(okay)
                {
                    window.location.href = base_path + 'MerchantRequestSent/sample';
                }
            });
        },
        autoSubmit: false
    });
    
    $("#samReqImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });
    
    $(document).on('click','.deleteImg',function(){
        var id = $(this).attr('data-id');

        if (confirm('Are you sure you want to delete the file')) {
            MakeAsynPostRequest(base_path + "WorkInProcess/deleteImageDetails", "&id=" + id, "json", function(data) {
                if(data.status == 'success')
                {
                    getSampleRequestImages();
                }
            });
        }

    });
    
    // $('#getValues').click(function () {
    //     $('.herr').hide();
    //     if($('#req_type').val() == "" || $('#req_type').val() == null ) {
    //         $('#err_req_type').html("Select Request type");
    //         $('#err_req_type').show();
    //     } 
    //     else if($('#cutoff_date').val() == "" || $('#cutoff_date').val() == null ) {
    //         $('#err_cutoff_date').html("Select Cuttoff Date");
    //         $('#err_cutoff_date').show();
    //     }
    //     else if($('#merchant_note').val() == "" || $('#merchant_note').val() == null ) {
    //         $('#err_merchant_note').html("Fill Merchant Note");
    //         $('#err_merchant_note').show();
    //     }
    //     else {
    //         swalWithBootstrapButtons.fire(
    //             // *** CONFIRMATION MESSAGE *** //
    //             alertMessageFunction('confirmation_save')
    //         ).then(function (result) {
    //             if (result.value) {
    //                 updateFunction();
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
    
    // function updateFunction() {
    //     let sampleRequest = sampleRequest_vm.getData();
    //     let cad_material = cad_material_vm.getData();
    //     let bom_mi_tbl_data = bom_mi_tbl_data.getData();
    //     let fabric_mi_tbl_data = fabric_mi_tbl_data.getData();
    //     let dataform = new FormData();
    //     dataform.append('enquiry_id', enquiry_id);
    //     dataform.append('req_type', $('#req_type').val());
    //     dataform.append('cutoff_date', $('#cutoff_date').val());
    //     dataform.append('merchant_note', $('#merchant_note').val());
    //     dataform.append('request_id', req_id);
    //     dataform.append('sampleRequest', JSON.stringify(sampleRequest));
    //     dataform.append('cad_material', JSON.stringify(cad_material));
    //     dataform.append('bom_mi_tbl_data', JSON.stringify(bom_mi_tbl_data));
    //     dataform.append('fabric_mi_tbl_data', JSON.stringify(fabric_mi_tbl_data));

    //     let request = $.ajax({
    //         type: "POST",
    //         url: base_path + 'request/Samplerequest/saveSampleReqDetails',
    //         data: dataform,
    //         processData: false,
    //         contentType: false,
    //         cache: false,
    //         success: function (data) {
    //             // *** SAVED MESSAGE *** //
    //             swalWithBootstrapButtons.fire(
    //                 alertMessageFunction('saved')
    //             ).then(okay => {
    //                 if (okay) {
    //                     window.location.href = base_path + 'MerchantRequestSent/sample';
    //                 }
    //              });
    //         },
    //         error: function () {
    //             console.log("Error");
    //         }
    //     });
    // }
        
    
    // *********************************************************************************************************************************** 
    //  FABRIC MATERIAL INDENT ENDS HERE 
    // ***********************************************************************************************************************************
    
});