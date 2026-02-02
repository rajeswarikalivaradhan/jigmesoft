$(document).ready(function () {
  

    // **************************************** //
    var sampleRequest_vm = '';
    var sampleReference_vm = '';
    var selectCount = 0;
    var selectedArray = [];
    var requirementData = [];
    var sizeData = [];
    var cad_ref_data = [];
    var cadMaterialIndent = [];
    var BOMMaterialIndent = [];
    var FabricMaterialIndent = [];
    var UOMDetails = [];
    var itemDescription = bcm = garmentSize = itemCode = itemColor = sizeDimension = uom = type=[];
    //var itemDescription2 = bcm2 = garmentSize2 = itemCode2 = itemColor2 = sizeDimension2 = uom2 = [];

    
    var fabricColor = fabricGarment = fabricBlend = fabricContent = fabricName = fabricGSM = fabricDIA = fabricUOM = [];
    var bom_dynamic_mi_data = [];
    var fabric_dynamic_mi_data = [];
    var mode = 'add';
    var req_id = '';
    $('#saveRequestDetails').hide();
    $('input[name=issued_type]').attr("disabled",true);
    
    getVendorDetails();
    
        $('input[type=radio][name=issued_to]').change(function() {
            $('input[name=issued_type]').attr("disabled",false);
        });
          let issued_type = [];
         let issued_types = [];
        $("input:checkbox[name=issued_type]:checked").each(function(){
            issued_type.push($(this).val());
        });
        $("input:checkbox[name=issued_type]:not(:checked)").each(function(){
            issued_types.push($(this).val());
        });
        //console.log(issued_types);
        let count = issued_type.length;
        for(let i=0;i<count;i++) {
            let types = issued_type[i];
            if(types == 'CAD') {
                $('#CAD').show();
            } else if(types == 'FABRIC') {
                $('#FABRIC').show();
            } else if(types == 'BOM') {
                $('#BOM').show();
            } 
        }
        let count1 = issued_types.length;
        for(let i=0;i<count1;i++) {
            let types = issued_types[i];
            if(types == 'CAD') {
                $('#CAD').hide();
            } else if(types == 'FABRIC') {
                $('#FABRIC').hide();
            } else if(types == 'BOM') {
                $('#BOM').hide();
            }
        }

    
    getSampleRequestImages();
    getSampleReqDetails();
    

    //$("#internal").attr('checked', true).trigger('click');
    
     
    
    function getSampleReqDetails()
    {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/checkDraftorNot',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                count = JSON.parse(data);
                 if(count > 0) {
                     $('input[name=issued_to]').attr("disabled",false);
                 } else {
                     $(".issued_to").hover(
        function ()
        {
            $('.err_issue').html($("<span style='color:red;'> Save as Draft </span>"));
        },
        function ()
        {
            $('.err_issue').html($(""));
        });
                     $('input[name=issued_to]').attr("disabled",true);
                 }
            }
            
        });
    }

    $('input[type=radio][name=issued_to]').change(function() {
        
        // errorCount = 0;
        //   let refData = sampleReference_vm.getData();
            
        //     for (let i = 0; i < refData.length; i++) {
        //         let validateFields = [2,3,4,5,6,7,8,9,10,11];
        //         for (let i = 0; i < refData.length; i++) {
        //             for(let j = 0; j < validateFields.length; j++) {
        //                 let col = validateFields[j];
        //                 if(refData[i][col] === "") {
        //                     errorCount++;
        //                 }
        //             }
        //         }
        //     }
            
        
        $('#cad_dept,#fab_dept,#bom_dept').html('<option value="">Select</option>');
        if(this.value== 'INTERNAL')
        {
            $('#cad_dept,#fab_dept,#bom_dept').append('<option value="">Select</option>'+
                    '<option value="SAMPLE DEPT.">SAMPLE DEPT.</option>'+
                    '<option value="PRODUCTION DEPT.">PRODUCTION DEPT.</option>'
            );
        }
        else {
            for (let i = 0; i < vendorDetails.length; i++) {
                vendorOption = "<option value='"+vendorDetails[i].id+"'>"+vendorDetails[i].name+"</option>";
                $('#cad_dept,#fab_dept,#bom_dept').append(vendorOption);
            }
        }
        
    });

    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
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
    
    
    
    // $('#CAD').hide();
    // $('#FABRIC').hide();
    // $('#BOM').hide();
      $('.issued_type').change(function() {
          
         let issued_type = [];
         let issued_types = [];
        $("input:checkbox[name=issued_type]:checked").each(function(){
            issued_type.push($(this).val());
        });
        $("input:checkbox[name=issued_type]:not(:checked)").each(function(){
            issued_types.push($(this).val());
        });
        //console.log(issued_types);
        let count = issued_type.length;
        for(let i=0;i<count;i++) {
            let types = issued_type[i];
            if(types == 'CAD') {
                $('#CAD').show();
            } else if(types == 'FABRIC') {
                $('#FABRIC').show();
            } else if(types == 'BOM') {
                $('#BOM').show();
            } 
        }
        let count1 = issued_types.length;
        for(let i=0;i<count1;i++) {
            let types = issued_types[i];
            if(types == 'CAD') {
                $('#CAD').hide();
            } else if(types == 'FABRIC') {
                $('#FABRIC').hide();
            } else if(types == 'BOM') {
                $('#BOM').hide();
            }
        }
        
            
    });
    
    
    function getSampleRequestImages() {
        $('.ImageView').html('');
        imgCount = 0;
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', req_id);
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
                let imgCount = imageJSON.length;
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
        
        if(mode == "draft_error") {
            return {
                title: 'Warning',
                text: "Please Save Draft Values First",
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

        if(mode == "img_error") {
            return {
                title: 'Error',
                text: "Please upload attachment",
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


    function getVendorDetails() {
        let request = $.ajax({
            type: "GET",
            url: base_path + 'request/Samplerequest/getVendorDetails',
            success: function (data) {
                vendorDetails = JSON.parse(data);
                getSampleRequest();
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getSampleRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/getSampleRequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                append_sample_request(sample_requirement_data);

                if(sample_requirement_data.mi_data.length > 0)
                {
                    if(sample_requirement_data.mi_data[0].type == "EXTERNAL") {
                        $("#external").attr('checked', true).trigger('click')
                        $('input[type=radio][name=issued_to]').change();
                    }
                    if(sample_requirement_data.mi_data[0].type != "") {
                        $('input[name=issued_type]').attr("disabled",false);
                    }
                    $('#cad_dept').val(sample_requirement_data.mi_data[0].cad_dept);
                    $('#cad_dept').trigger('change');
                    $('#fab_dept').val(sample_requirement_data.mi_data[0].fab_dept);
                    $('#fab_dept').trigger('change');
                    $('#bom_dept').val(sample_requirement_data.mi_data[0].bom_dept);
                    $('#bom_dept').trigger('change');
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
        let category = '';
        // *** JEXCEL STARTS *** //
        $('#sampleRequest').html('');
        let PurposeData = [ 'Development', 'Order Conf.', 'Shipment' ];
        let list = {
            data: data.data,
            columns: [
                { title:'mode', width:'10%',align:'center',type:'hidden'},
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'checkbox', title: 'Mark', width: '7%', align: 'left' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'] },
                { type: 'dropdown', title: 'If Revised or In-line\n Prev. Sample Ref. No.', width: '10%', align: 'center', source: data.sampleRefNo, filter: refFilter, readOnly: true },
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
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if( col == 2) {
                    mark = val;
                }
                if(col == 8) 
                {
                    if(mark == true) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                
                if(col == 9) 
                {
                    category = val;
                    if(mark == true) {
                        $(cell).removeClass('readonly');
                    } else {
                        $(cell).addClass('readonly');
                    }
                }
                if(col == 10) 
                {
                    if(category == "New" || category == "")
                    {
                        $(cell).addClass('readonly');
                    }
                    else {
                        $(cell).removeClass('readonly');
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
            // itemDescription = data.BOMAppendData.itemDescription;
            // bcm = data.BOMAppendData.bcm;
            // garmentSize = data.BOMAppendData.garmentSize;
            // itemCode = data.BOMAppendData.itemCode;
            // itemColor = data.BOMAppendData.itemColor;
            // sizeDimension = data.BOMAppendData.sizeDimension;
            // uom = data.BOMAppendData.uom;


            // itemDescription2 = data.BOM2AppendData.itemDescription;
            // bcm2 = data.BOM2AppendData.bcm;
            // garmentSize2 = data.BOM2AppendData.garmentSize;
            // itemCode2 = data.BOM2AppendData.itemCode;
            // itemColor2 = data.BOM2AppendData.itemColor;
            // sizeDimension2 = data.BOM2AppendData.sizeDimension;
            // uom2 = data.BOM2AppendData.uom;
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
            }

            append_attach_reference();
            append_cad_material_indent();
            append_fabric_material_indent(true, data.referResult,data.sumqty);
            $('#saveRequestDetails').show();
            selectCount = parseInt(data.ref_status);
            mode = 'edit';
            req_id = data.req_id;
            
            if(req_id != '' || req_id != null) {
                //console.log(data.reqData[0].req_type);
                getSampleRequestImages();
                $('#req_type').val(data.reqData[0].req_type).trigger('change');
                // $('#req_type').val(data.reqData[0].req_type);
                $('#cutoff_date').val(data.reqData[0].cutoff_date);
                $('#merchant_note').val(data.reqData[0].merchant_note);
            }
        }
    }

    function refFilter(instance, cell, c, r, source)
    {
        let size_id = instance.jexcel.getValueFromCoords(c - 4, r);
        let component_id = instance.jexcel.getValueFromCoords(c - 5, r);
        // let combo_id = instance.jexcel.getValueFromCoords(c - 6, r);
        let po_enq_id = instance.jexcel.getValueFromCoords(c - 7, r);

        if (size_id != "" && component_id != "" && po_enq_id != "") {
            return source.filter(function (item) {
                if ((item.size_id == size_id) && (item.component_id == component_id) && (item.po_enq_id == po_enq_id) ) return true;
            })
        } else {
            return [];
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
                { type: 'dropdown', title: 'Approved & Graded\n Measurement Chart', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: 'Complete Artwork', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: 'How to Measure\n Details', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: 'Buyers Original \nSample or Pattern', width: '7%', align: 'left', source: common_dd },
                { type: 'dropdown', title: "Buyer's Comments", width: '7%', align: 'left', source: common_dd },
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

    // ******** SAVE REQUEST DETAILS STARTS HERE **************** //
    $('#saveRequestDetails').click(function () {
        if(selectCount <= 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
            
            let req_data = sampleRequest_vm.getData();
            let ref_data = sampleReference_vm.getData();
            req_data = req_data.filter(function(e) { if(e[2] === true) return e })
            ref_data = ref_data.sort();

            let validate_filed_1 = [8,9,11];
            let validatedErrorCount_1 = validateForm(validate_filed_1, req_data);
            // console.log(validatedErrorCount_1);
            
            let validate_filed_2 = [7,8,9,10,11];
            let validatedErrorCount_2 = validateForm(validate_filed_2, ref_data);
            // console.log(validatedErrorCount_2);

            // $('.herr').hide();
            // if($('#req_type').val() == "" || $('#req_type').val() == null ) {
            //     $('#err_req_type').html("Select request type");
            //     $('#err_req_type').show();
            // } 
            // else if($('#merchant_note').val() == "" || $('#merchant_note').val() == null ) {
            //     $('#err_merchant_note').html("Fill merchant note");
            //     $('#err_merchant_note').show();
            // }
            // else 
            if(validatedErrorCount_1 == 0 && validatedErrorCount_2 == 0) {
                swalWithBootstrapButtons.fire(
                    // *** CONFIRMATION MESSAGE *** //
                    alertMessageFunction('confirmation_save')
                ).then(function (result) {
                    if (result.value) {
                        let req_empArr = [];
                        for(let i=0; i < req_data.length; i++) {
                            let data_a = req_data[i];
                            let decEmpAr = [];
                            for(let j=0; j < data_a.length; j++) {
                                if(j == 0) {
                                    decEmpAr.push(data_a[j]);
                                }
                                if(j == 1) {
                                    decEmpAr.push(data_a[j]);
                                }
                                if(j >= 8) {
                                    decEmpAr.push(data_a[j]);
                                }
                            }
                            req_empArr.push(decEmpAr);
                        }

                        let ref_empArr = [];
                        for(let i=0; i < ref_data.length; i++) {
                            let data_a = ref_data[i];
                            let decEmpAr = [];
                            for(let j=0; j < data_a.length; j++) {
                                // if(j == 1) {
                                //     decEmpAr.push(data_a[j]);
                                // }
                                if(j >= 7) {
                                    decEmpAr.push(data_a[j]);
                                }
                            }
                            ref_empArr.push(decEmpAr);
                        }

                        let finalArray = [];
                        for(let i=0; i < req_empArr.length; i++)  {
                            const arr1 = req_empArr[i];
                            const arr2 = ref_empArr[i];
                            const conArr = arr1.concat(arr2);
                            finalArray.push(conArr);
                        }
                        updateFunction(finalArray);
                    }
                    else if (result.dismiss === Swal.DismissReason.cancel) {
                        // *** CANCELLED MESSAGE *** //
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('cancelled')
                        );
                    }
                });
            }
            else {
                // *** VALIDATION ERROR MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('validation_error')
                )
            }
        }
    });

    function updateFunction(finalData) {

        let dataform = new FormData();
        dataform.append('data', JSON.stringify(finalData));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('req_type', $('#req_type').val());
        dataform.append('cutoff_date', $('#cutoff_date').val());
        dataform.append('merchant_note', $('#merchant_note').val());
        dataform.append('mode', mode);
        dataform.append('req_id', req_id);

        

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/createSampleRequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // getSampleRequest();
                // append_cad_material_indent();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay) {
                        getSampleReqDetails();
                        location.reload(true);
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    // ******** SAVE REQUEST DETAILS ENDS HERE ***************** //

    // ******** SAVE AS DRAFT STARTS HERE **************** //

    $('#saveasdraft').click(function () {
        let issued_type = [];
        swalWithBootstrapButtons.fire(
        // *** CONFIRMATION MESSAGE *** //
        alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                // common form data
                var dataValue = new FormData();
                 
        
                // get cad mi form data
                var cad_form = $('#cad_mi_form')[0];
                var cad_mi_details = new FormData(cad_form);
                // get fabric mi form data
                var fab_form = $('#fab_mi_form')[0];
                var fab_mi_details = new FormData(fab_form);
                // get bom mi form data
                var bom_form = $('#bom_mi_form')[0];
                var bom_mi_details = new FormData(bom_form);
        
                // store cad mi form details
                for(var pair of cad_mi_details.entries()) {
                    dataValue.append(pair[0], pair[1]);
                }
        
                // store bom mi form details
                for(var pair of bom_mi_details.entries()) {
                    dataValue.append(pair[0], pair[1]);
                }
        
                // store fabric mi form details
                for(var pair of fab_mi_details.entries()) {
                    dataValue.append(pair[0], pair[1]);
                }
        
                // get cad material indent table data
                let cad_mi_tbl_data = cad_material_vm.getData();
        
                // get bom material indent data
                let bom_mi_all_tbl_data = [];
                for (let i = 1; i < requirementData.length+1; i++) {
                    let bom_mi_data = bom_dynamic_mi_data[i-1].tbl_data.getData();
                    var bom_tbl_data = { "bom_key_name": bom_mi_data };
        
                    const altObj = Object.fromEntries(
                        Object.entries(bom_tbl_data).map(([key, value]) => 
                          // Modify key here
                          [`${requirementData[i-1][1]}`, value]
                        )
                    )
        
                    bom_mi_all_tbl_data.push(altObj);
        
                }

                // get fabric material indent data
                let fabric_mi_all_tbl_data = [];
                for (let i = 1; i < requirementData.length+1; i++) {
                    let fabric_mi_data = fabric_dynamic_mi_data[i-1].tbl_data.getData();
                    var fabric_tbl_data = { "fabric_key_name": fabric_mi_data };
        
                    const altObj = Object.fromEntries(
                        Object.entries(fabric_tbl_data).map(([key, value]) => 
                          // Modify key here
                          [`${requirementData[i-1][1]}`, value]
                        )
                    )
        
                    fabric_mi_all_tbl_data.push(altObj);
        
                }
                $("input:checkbox[name=issued_type]:checked").each(function(){
                    issued_type.push($(this).val());
                });
                
                
                dataValue.append('cad_req_date', $('#cad_req_date').val());
                // dataValue.append('sample_req_date', $('#cad_req_date').val());
                // dataValue.append('cad_dept', $('#cad_dept').val());
                dataValue.append('fab_req_date', $('#fab_req_date').val());
                // dataValue.append('bom_dept', $('#bom_dept').val());
                dataValue.append('bom_req_date', $('#bom_req_date').val());
                dataValue.append('cad_mi_tbl_data', JSON.stringify(cad_mi_tbl_data));
                dataValue.append('fabric_mi_tbl_data', JSON.stringify(fabric_mi_all_tbl_data));
                dataValue.append('bom_mi_tbl_data', JSON.stringify(bom_mi_all_tbl_data));
                dataValue.append('enquiry_id', enquiry_id);
                dataValue.append('request_id', req_id);
                dataValue.append('req_type', $('#req_type').val());
                dataValue.append('cutoff_date', $('#cutoff_date').val());
                dataValue.append('merchant_note', $('#merchant_note').val());
                // dataValue.append('type', $('input[type=radio][name=issued_to]').val());
                dataValue.append('type', $('input[name="issued_to"]:checked').val());
                dataValue.append('issued_type', issued_type);
                console.log(dataValue);
                updateDraftFunction(dataValue);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });

    // ******** SAVE AS DRAFT ENDS HERE ***************** //

    // ******** CLEAR DRAFT STARTS HERE **************** //

    $('#clearRequestDetails').click(function () {swalWithBootstrapButtons.fire(
        // *** CONFIRMATION MESSAGE *** //
        alertMessageFunction('confirmation_save')
        ).then(function (result) {
            if (result.value) {
                let ref_data = sampleReference_vm.getData();
                
                let dataValue = new FormData();
                dataValue.append('data', JSON.stringify(ref_data));
                dataValue.append('req_id', req_id);
                clearFunction(dataValue);
            } 
            else if (result.dismiss === Swal.DismissReason.cancel) {
                // *** CANCELLED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('cancelled')
                );
            }
        });
    });

    // ******** CLEAR DRAFT ENDS HERE ***************** //

    // ******** SAVE REQUEST ENDS HERE ***************** //

    
    $('#getValues').click(function () {
        issued_typee  = [];
        // if(sampleUpload.selectedFiles == 0 || imgCount == 0) {
        //     swalWithBootstrapButtons.fire(
        //         alertMessageFunction('img_error')
        //     );
        // }
        let issuedTo = $('input[name="issued_to"]:checked').val();
        let req_types = $('#req_type').val();
        let merchant_notes = $('#merchant_note').val();
        $("input:checkbox[name=issued_type]:checked").each(function(){
            issued_typee.push($(this).val());
        });
        
        if(issuedTo == '' || issuedTo == undefined) {
            
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
        } else if(req_types == '' || req_type == undefined) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
        } else if(merchant_notes == '' || merchant_note == undefined) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
        } if((issued_typee.length) == '0' || (issued_typee.length) == 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('validation_error')
            );
        }
        else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    // common form data
                    var dataValue = new FormData();
            
                    // get cad mi form data
                    var cad_form = $('#cad_mi_form')[0];
                    var cad_mi_details = new FormData(cad_form);
                    // get bom mi form data
                    var bom_form = $('#bom_mi_form')[0];
                    var bom_mi_details = new FormData(bom_form);
                    // get fabric mi form data
                    var fab_form = $('#fab_mi_form')[0];
                    var fab_mi_details = new FormData(fab_form);
            
                    // store cad mi form details
                    for(var pair of cad_mi_details.entries()) {
                        dataValue.append(pair[0], pair[1]);
                    }

                    // store fabric mi form details
                    for(var pair of fab_mi_details.entries()) {
                        dataValue.append(pair[0], pair[1]);
                    }
            
                    // store bom mi form details
                    for(var pair of bom_mi_details.entries()) {
                        dataValue.append(pair[0], pair[1]);
                    }

                    // get reference table data
                    let sample_details = sampleReference_vm.getData();
                    
                    // get cad material indent table data
                    let cad_mi_tbl_data = cad_material_vm.getData();
            
                    // get bom material indent data
                    let bom_mi_all_tbl_data = [];
                    for (let i = 1; i < requirementData.length+1; i++) {
                        let bom_mi_data = bom_dynamic_mi_data[i-1].tbl_data.getData();
                        var bom_tbl_data = { "bom_key_name": bom_mi_data };
            
                        const altObj = Object.fromEntries(
                            Object.entries(bom_tbl_data).map(([key, value]) => 
                            // Modify key here
                            [`${requirementData[i-1][1]}`, value]
                            )
                        )
            
                        bom_mi_all_tbl_data.push(altObj);
            
                    }

                    // get fabric material indent data
                    let fabric_mi_all_tbl_data = [];
                    for (let i = 1; i < requirementData.length+1; i++) {
                        let fabric_mi_data = fabric_dynamic_mi_data[i-1].tbl_data.getData();
                        var fabric_tbl_data = { "fabric_key_name": fabric_mi_data };
            
                        const altObj = Object.fromEntries(
                            Object.entries(fabric_tbl_data).map(([key, value]) => 
                            // Modify key here
                            [`${requirementData[i-1][1]}`, value]
                            )
                        )
            
                        fabric_mi_all_tbl_data.push(altObj);
            
                    }
                    
                    $("input:checkbox[name=issued_type]:checked").each(function(){
                        issued_type.push($(this).val());
                    });
                     
                    dataValue.append('cad_req_date', $('#cad_req_date').val());
                    dataValue.append('fab_req_date', $('#fab_req_date').val());
                    dataValue.append('bom_req_date', $('#bom_req_date').val());
                    dataValue.append('cad_mi_tbl_data', JSON.stringify(cad_mi_tbl_data));
                    dataValue.append('fabric_mi_tbl_data', JSON.stringify(fabric_mi_all_tbl_data));
                    dataValue.append('bom_mi_tbl_data', JSON.stringify(bom_mi_all_tbl_data));
                    dataValue.append('enquiry_id', enquiry_id);
                    dataValue.append('request_id', req_id);
                    dataValue.append('req_type', $('#req_type').val());
                    dataValue.append('cutoff_date', $('#cutoff_date').val());
                    dataValue.append('merchant_note', $('#merchant_note').val());
                    dataValue.append('sample_details', JSON.stringify(sample_details));
                    // dataValue.append('type', $('input[type=radio][name=issued_to]').val());
                    dataValue.append('type', $('input[name="issued_to"]:checked').val());
                    
                    dataValue.append('issued_type', issued_type);

                    updateMIFunction(dataValue);
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

    function clearFunction(dataValue) {
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/clearSampleReqDetails',
            data: dataValue,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                let val = JSON.parse(data);
                if(val.status == 'success')
                {
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('saved')
                    ).then(function (result) {
                        if (result.value) {
                            window.location.reload(true);
                        }
                    });
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

    function updateDraftFunction(dataValue) {
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/saveSampleReqDraft',
            data: dataValue,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                let val = JSON.parse(data);
                if(val.status == 'success')
                {
                    if(sampleUpload.selectedFiles == 0) {
                         swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(okay => {
                            if(okay)
                            {
                                window.location.href = base_path + 'MerchantRequestSent/sample';
                            }
                        });
                    }
                    else {
                        sampleUpload.startUpload();
                        //location.reload(true);
                    }
                }
                else {
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('error')
                    );
                }
                // if(val.status == 'success')
                // {
                //     swalWithBootstrapButtons.fire(
                //         alertMessageFunction('saved')
                //     ).then(function (result) {
                //         if (result.value) {
                //             location.reload(true);
                //         }
                //     });
                // }
                // else {
                //     swalWithBootstrapButtons.fire(
                //         alertMessageFunction('error')
                //     );
                // }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function updateMIFunction(dataValue) {
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Samplerequest/saveSampleReqDetails',
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
                        ).then(okay => {
                            if(okay)
                            {
                                window.location.href = base_path + 'MerchantRequestSent/sample';
                            }
                        });
                    }
                    else {
                         sampleUpload.startUpload();
                         //window.location.href = base_path + 'MerchantRequestSent/sample';
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

    // ******** SAVE REQUEST ENDS HERE ***************** //
    
    // let sampleUploaddraft = $("#samReqImageUpload").uploadFile({
    //     dragDrop: true,
    //     multiple: true,
    //     url:base_path+'request/Samplerequest/imageUploadDetails',
    //     returnType: "json",
    //     fileName: "myFile",
    //     allowedTypes: allowedFileTypes,
    //     dynamicFormData:function () {
    //         return {
    //             enquiry_id: enquiry_id,
    //             request_id: req_id,
    //             type: 'sample_request',
    //         };
    //     },
    //     afterUploadAll: function () {
    //         swalWithBootstrapButtons.fire(
    //             alertMessageFunction('saved')
    //         ).then(okay => {
    //             if(okay)
    //             {
    //                 location.reload(true);
    //             }
    //         });
    //     },
    //     autoSubmit: false
    // });
    
    
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
                    //return true;
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
                { type: 'dropdown', title: 'CAD Ref. No.', width: '12%', align: 'left', source: cad_ref_data, filter: cadRefFilter  },
                { type: 'dropdown', title: 'Requirement', width: '7%', align: 'left', source: requirementSource },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: purposeSource },
                { type: 'dropdown', title: 'Required \nSize(s)', width: '7%', align: 'left', source: sizeData },
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

    function cadRefFilter(instance, cell, c, r, source)
    {
        //console.log('hai')
        let size_id = instance.jexcel.getValueFromCoords(c - 1, r);
        let component_id = instance.jexcel.getValueFromCoords(c - 2, r);
        // let combo_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let po_id = instance.jexcel.getValueFromCoords(c - 4, r);
        // console.log(size_id)
        // console.log(component_id)
        // console.log(po_id)
        // if ((item.size_id == size_id) && (item.component_id == component_id) && (item.po_id == po_id)) return true;

        if (size_id != "" && component_id != "" && po_id != "") {
            return source.filter(function (item) {
                if ((item.size_id == size_id) && (item.component_id == component_id)) return true;
            })
        } else {
            return [];
        }
    }

    // *********************************************************************************************************************************** 
    //  CAD MATERIAL INDENT ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    //  FABRIC MATERIAL INDENT STARTS HERE 
    // ***********************************************************************************************************************************
        
    function append_fabric_material_indent(status, data,data2) {
       //console.log(data2);
        for (let i = 0; i < data.length; i++) {
            $('#bomMaterialIndent'+data[i][1]).html('');
          
            generateBomMaterialIndent(data[i][1], i, data2);
            //generateBomMaterialIndent2(data[i][1], i);
            $('#fabricMaterialIndent'+data[i][1]).html('');
            generateFabricMaterialIndent(data[i][1], i);
        }
        
        // *** STARTS BOM MATERIAL INDENT DYNAMIC TABLE BASED ON SELECTION *** /

        function generateBomMaterialIndent(id, i, data2) {
             let artical = [
            {id: '1', name: 'BOM(A1)'}, 
            {id: '2', name: 'BOM(A2)'}, 
            
        ];
        console.log(data2);

            let list = {
                data: BOMMaterialIndent[i],
                columns: [
                    { title:'id', width:'10%',align:'center',type:'hidden'},
                    { type: 'dropdown', title: 'BOM Artical', width: '8%', align: 'left', source: artical },
                   
                    { type: 'dropdown', title: 'Item Description', width: '10%', align: 'left', source: itemDescription,filter: itemdesc },
                    { type: 'dropdown', title: 'Blend (%) / Content /\n Material', width: '12%', align: 'left', source: bcm, filter: bcmFilter },
                    { type: 'dropdown', title: 'Garment \n Size(s)', width: '6%', align: 'left', source: garmentSize, filter: garmentFilter },
                    { type: 'dropdown', title: 'Item Code', width: '8%', align: 'left' , source: itemCode, filter: itemCodeFilter },
                    { type: 'dropdown', title: 'Item Colour\n Code', width: '8%', align: 'left', source: itemColor, filter: itemColorFilter },
                    { type: 'dropdown', title: 'Size /\n Dimension', width: '8%', align: 'left', source: sizeDimension, filter: sizeDimensionFilter, readOnly: true },
                    { type: 'dropdown', title: 'UOM', width: '7%', align: 'left', source: uom, filter: uomFilter, readOnly: true },
                    { type: 'text', title: 'M.I. Qty.', width: '7%', align: 'right' },
                    { type: 'dropdown', title: 'UOM', width: '7%', align: 'left', source: UOMDetails },
                    { type: 'text', title: 'D.C. No.', width: '8%', align: 'left', readOnly: true },
                    { type: 'text', title: 'D.C. \n Date & Time.', width: '8%', align: 'left', readOnly: true },
                ],
                minDimensions: [4, 1],
                allowDeleteColumn: true,
                allowInsertRow: true,
                allowInsertColumn: true,
                onchange: function(instance, cell, col, row, val, label, cellName) {
                    if(col == 5) {
                        
                    }
                    if(col == 9) {
                       qty = parseFloat(val);
                         let item_code = instance.jexcel.getValueFromCoords(col - 4, row);
                         let all_item_codeqty = data2.find(o => o.appr_item_code == item_code);
                        let totqty = parseFloat(all_item_codeqty.total_qty);
                        //alert(all_item_codeqty.total_qty);
                        if(qty > totqty ) {
                             alert('Quantity is less than total quantity');
                            instance.jexcel.setValueFromCoords(col, row, '');
                        }
                        
                    }
                },
                updateTable: function(instance, cell, col, row, val, label, cellName) {
                    if(col == 1) {
                        item_val = val;
                    }
                    if(col == 4) {
                        size = val;
                    }
                    if(col == 5) {
                        appr_item_code = val;
                    }
                    if(col == 6) {
                        item_color_code = val;
                    }
                    if(col == 7) {
                        if(item_val !== '' && size !== '' && appr_item_code !== '' && item_color_code !== ''  ) {
                            let sizeDia = sizeDimension;
                            let obj = sizeDia.find(o => o.item_code_id === appr_item_code, o =>o.item_color_id === item_color_code);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                            
                            size_id = obj.name;
                        }
                    }
                    if(col == 8) {
                        if(item_val !== '' && size !== '' && appr_item_code !== '' && item_color_code !== ''  ) {
                            let uomData = uom;
                            let obj = uomData.find(o => o.item_code_id === appr_item_code, o =>o.item_color_id === item_color_code, o => o.size_id === size_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
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


         function generateBomMaterialIndent2(id, i) {
             let artical = [
            {id: '1', name: 'Bom1'}, 
            {id: '2', name: 'bom2'}, 
            
        ];

            let list = {
                data: BOMMaterialIndent[i],
                columns: [
                    { title:'id', width:'10%',align:'center',type:'hidden'},
                   
                    { type: 'dropdown', title: 'Item Description', width: '8%', align: 'left', source: itemDescription2 },
                    { type: 'dropdown', title: 'Blend (%) / Content /\n Material', width: '8%', align: 'left', source: bcm2, filter: bcmFilter },
                    { type: 'dropdown', title: 'Garment \n Size(s)', width: '8%', align: 'left', source: garmentSize2, filter: garmentFilter },
                    { type: 'dropdown', title: 'Item Code', width: '8%', align: 'left' , source: itemCode2, filter: itemCodeFilter },
                    { type: 'dropdown', title: 'Item Colour\n Code', width: '8%', align: 'left', source: itemColor2, filter: itemColorFilter },
                    { type: 'dropdown', title: 'Size /\n Dimension', width: '8%', align: 'left', source: sizeDimension2, filter: sizeDimensionFilter, readOnly: true },
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: uom2, filter: uomFilter, readOnly: true },
                    { type: 'text', title: 'M.I. Qty.', width: '8%', align: 'right' },
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails },
                    { type: 'text', title: 'D.C. No.', width: '8%', align: 'left', readOnly: true },
                    { type: 'text', title: 'D.C. \n Date & Time.', width: '8%', align: 'left', readOnly: true },
                ],
                minDimensions: [4, 1],
                allowDeleteColumn: true,
                allowInsertRow: true,
                allowInsertColumn: true,
                onchange: function(instance, cell, col, row, val, label, cellName) {
                    if(col == 5) {
                        
                    }
                },
                updateTable: function(instance, cell, col, row, val, label, cellName) {
                    if(col == 1) {
                        item_val = val;
                    }
                    if(col == 3) {
                        size = val;
                    }
                    if(col == 4) {
                        appr_item_code = val;
                    }
                    if(col == 5) {
                        item_color_code = val;
                    }
                    if(col == 6) {
                        if(item_val !== '' && size !== '' && appr_item_code !== '' && item_color_code !== ''  ) {
                            let sizeDia = sizeDimension2;
                            let obj = sizeDia.find(o => o.item_code_id === appr_item_code, o =>o.item_color_id === item_color_code);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                            
                            size_id = obj.name;
                        }
                    }
                    if(col == 7) {
                        if(item_val !== '' && size !== '' && appr_item_code !== '' && item_color_code !== ''  ) {
                            let uomData = uom2;
                            let obj = uomData.find(o => o.item_code_id === appr_item_code, o =>o.item_color_id === item_color_code, o => o.size_id === size_id);
                            $(cell).text(obj.name);
                            instance.jexcel.options.data[row][col] = obj.name;
                        }
                    }
                }
                
            };
    
            bom_mi_tbl_data2 = new Vue({
                el: '#bom2MaterialIndent'+id,
                mounted: function () {
                    let spreadsheet = jexcel(this.$el, list);
                    Object.assign(this, spreadsheet);
                },
            });

            let tblData2 = { 'tbl_data': bom_mi_tbl_data2 };
            bom_dynamic_mi_data.push(tblData2);


          

        }


        // *** STARTS FABRIC MATERIAL INDENT DYNAMIC TABLE BASED ON SELECTION *** /

        function generateFabricMaterialIndent(id, i) {
            list = {
                data: FabricMaterialIndent[i],
                columns: [
                    { title:'id', width:'10%',align:'center',type:'hidden'},
                    { type: 'text', title: 'Fabric Ref. No.', width: '8%', align: 'left'},
                    { type: 'dropdown', title: 'Colour', width: '8%', align: 'left', source: fabricColor },
                    { type: 'dropdown', title: 'Garment Parts', width: '8%', align: 'left', source: fabricGarment, filter: fabricGarmentFilter },
                    { type: 'dropdown', title: 'Fabric \n Blend (%)', width: '8%', align: 'left', source: fabricBlend, filter: fabricBlendFilter },
                    { type: 'dropdown', title: 'Fabric \n Content', width: '8%', align: 'left', source: fabricContent, filter: fabricContentFilter},
                    { type: 'dropdown', title: 'Fabric \n Name', width: '8%', align: 'left', source: fabricName, filter: fabricNameFilter},
                    { type: 'dropdown', title: 'GSM', width: '8%', align: 'left', source: fabricGSM, filter: fabricGSMFilter},
                    { type: 'dropdown', title: 'DIA / DIM \n (W*H)', width: '8%', align: 'left', source: fabricDIA, filter: fabricDIAFilter},
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: fabricUOM, filter: fabricUOMFilter },
                    { type: 'text', title: 'M.I. Qty.', width: '8%', align: 'right' },
                    { type: 'dropdown', title: 'UOM', width: '8%', align: 'left', source: UOMDetails },
                    { type: 'text', title: 'D.C. No.', width: '8%', align: 'left', readOnly: true },
                    { type: 'text', title: 'D.C.\nDate & Time.', width: '8%', align: 'left', readOnly: true },
                ],
                minDimensions: [4, 1],
                allowDeleteColumn: true,
                allowInsertRow: true,
                allowInsertColumn: true,
                updateTable:function(instance, cell, col, row, val, label, cellName) {
                    if(col == 10) {
                        var da = $(cell).text();
                        txtValue = numeral(da).format('0.000');
                        txtValue = (txtValue > 0) ? txtValue : '0';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        bom_intake = txtValue;
                    }
                }
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

    function itemdesc(instance, cell, c, r, source) {
        var bom = instance.jexcel.getValueFromCoords(c - 1, r);
        if(bom == 1) {
            var bomtype = 'bom1';
        } else {
            var bomtype = 'bom2';
        }
    
       
        if (bom !== "") {
            return source.filter(function (item) {
                if (item.type == bomtype) return true;
            })
        } else {
            return [];
        }
    }
    function bcmFilter(instance, cell, c, r, source) {
        var item_id = instance.jexcel.getValueFromCoords(c - 1, r);
        console.log(source);
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