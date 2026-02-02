$(document).ready(function () {

   

    // **************************************** //
    var sampleRequest_vm = '';
    var sampleReference_vm = '';
    var selectCount = 0;
    var selectedArray = [];
    var requirementData = [];
    var sizeData = [];
    var mode = 'add';
    $('#saveRequestDetails').hide();
    //$('#bom1ImageUpload').hide();

    getPurchaseRequest();
    getPurchaseRequestImages();

    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    // Change function
    $('#cad_deprt').on('change', function(){
        let cad_dept = $('#cad_deprt').val();
        $('#fab_dept').val(cad_dept);
        $('#bom_dept').val(cad_dept);
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

    // $('#sample').prop('checked',true);
    // $('#qty_type').html('( SAMPLE QTY. )');
    
    // $('input[type=radio][name=purchase_req_type]').change(function() {
    //     let qty_type = $(this).val();
    //     if(qty_type == 'sample') {
    //         $('#sample').prop('checked',true);
    //         $('#qty_type').html('( SAMPLE QTY. )');
    //     }
    //     else if(qty_type == 'bulk') {
    //         $('#bulk').prop('checked',true);
    //         $('#qty_type').html('( BULK QTY. )');
    //     }
    //     else if(qty_type == 'revised') {
    //         $('#revised').prop('checked',true);
    //         $('#qty_type').html('( REVISED QTY. )');
    //     }
    //     else if(qty_type == 'shortage') {
    //         $('#shortage').prop('checked',true);
    //         $('#qty_type').html('( SHORTAGE QTY. )');
    //     }
    // });

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getPurchaseRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/getPurchaseRequestSentDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                if(sample_requirement_data.sourcing_result.length > 0) {
                    $('#req_type').val(sample_requirement_data.req_data[0].req_type);
                    $("#req_type").trigger('change');

                    $('#req_date').val(sample_requirement_data.req_data[0].req_date);
                    $('#cutoff_date').val(sample_requirement_data.req_data[0].cutoff_date);
                    $('#merchant_note').val(sample_requirement_data.req_data[0].merchant_note);
                    $('#'+sample_requirement_data.req_data[0].purchase_req_type).prop('checked',true);
                    $('input[type=radio][name=purchase_req_type]').trigger('change');
                    
                    $('#auth_status').val(sample_requirement_data.req_data[0].auth_status);
                    $("#auth_status").trigger('change');
                    $('#auth_type').val(sample_requirement_data.req_data[0].auth_type);
                    $("#auth_type").trigger('change');
                    $('#auth_by').val(sample_requirement_data.req_data[0].auth_name);
                    $('#auth_date').val(sample_requirement_data.req_data[0].auth_date);
                    $('#mgmt_remark').val(sample_requirement_data.req_data[0].mgmt_remark);
                    let qty_type = sample_requirement_data.req_data[0].purchase_req_type;
                    if(qty_type == 'SAMPLE') {
                        $('#sample').prop('checked',true);
                        $('#qty_type').html('( SAMPLE QTY. )');
                    }
                    else if(qty_type == 'BULK') {
                        $('#qty_type').html('( BULK QTY. )');
                    }
                   else if(qty_type == 'DISCREPANCY') {
                        $('#qty_type').html('( DISCREPANCY QTY. )');
                    }
                    else if(qty_type == 'SHORTAGE') {
                        $('#qty_type').html('( SHORTAGE QTY. )');
                    }
                }
                append_sample_request(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getPurchaseRequestImages()
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
              
                if((imageJSON.images.length) > 0) {
                for (let i = 0; i < imageJSON.images.length; i++) {
                // Note (usertype == 3 || usertype == 15) added this condition instead of usertype == 3 in this file
                if((usertype == 3 || usertype == 15) && mgmt_approval == 2)
                {
                    var delete_data = '<a href="javascript:void(0);" data-id='+imageJSON.images[i].wip_files_id+' class="deleteImg" title="Delete">'+
                                        '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>';
                } else {
                    var delete_data = '<a href="javascript:void(0);"  class="" title="Delete">'+
                                        '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>';
                }
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
                                    // delete_data+
                                    '</a>'+
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                }
                } else {
                    if((usertype == 3 || usertype == 15) || mgmt_approval == "2")
                    {
                        $('#bom1ImageUpload').show();
                    }
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
            MakeAsynPostRequest(base_path + "MerchantRequestSent/deleteImageDetails", "&id=" + id, "json", function(data) {
                if(data.status == 'success')
                {
                    getPurchaseRequestImages();
                }
            });
        }

    });

    let req_sel_data = [];
    let table_data = [];
    let sourcing_result = [];
    let copy_sourcing_result = [];

    function append_sample_request(data) {
        // *** ASSIGING SIZEDATA VALUE FROM DATA *** //

        sourcing_result = data.sourcing_result;
        copy_sourcing_result = data.sourcing_result;
        req_sel_data = req_sel_data.concat(sourcing_result);

        // *** JEXCEL STARTS *** //
        $('#sampleRequest').html('');
        
        let list = {
            data: data.data,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'bom_1_con_id', width:'0%',align:'center',type:'hidden'},
                { type: 'text', title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Blend (%) / Content / Material', width: '12%', align: 'left', readOnly: true },
                { type: 'text', title: 'Garment\n Size', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved\n Item Code', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Approved Item\n Colour Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', readOnly: true },
                { title: 'UOM', width: '7%', align: 'center', readOnly: true },
                { title: 'Consolidated\n Reqd. BOM Qty.', width: '8%', align: 'right', readOnly: true },
                { title: 'Excess Qty.\n (%)', width: '7%', align: 'center', readOnly: true },
                { title: 'Planned BOM Qty.', width: '5%', align: 'right', readOnly: true },
                { title: 'UOM', width: '5%', align: 'center', readOnly: true }
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
                    getReferenceValue(data.totData[row], val, row);
                    
                }
                
                if(col == 9) 
                {
                    con_val = val;
                }
                
                if(col == 10) 
                {
                    ex_val = val;
                }
            },
            updateTable: function(instance, cell, col, row, val, label, cellName) {
                if(col == 9) 
                {
                    if((usertype == 3 || usertype == 15) && mgmt_approval == 2)
                    {
                        
                        $(cell).removeClass('readonly');
                    }
                    else {
                        $(cell).addClass('readonly');
                    }
                    con_val = val;
                }
                if(col == 10) 
                {
                    if((usertype == 3 || usertype == 15) && mgmt_approval == 2)
                    {
                        $(cell).removeClass('readonly');
                    }
                    else {
                        $(cell).addClass('readonly');
                    }
                    
                    ex_val = val;
                }
                
                if(col == 11) {
                        amount = parseFloat(con_val) + (parseFloat(con_val) * parseFloat(ex_val) / 100);
                        tot = numeral(amount).format('0.00');
                        //console.log(tot);
                        $(cell).text(tot);
                        instance.jexcel.options.data[row][col] = tot;
                    
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

        if(sourcing_result.length > 0) {
            mode = 'edit';
            append_attach_reference(sourcing_result);
            $('#saveRequestDetails').show();
        }
    }


    function getReferenceValue(data, status, row) {
        let bom_data = data;
        let id = bom_data.bom_1_req_consld_id;

        if(selectedArray.length === 0) {
            $('#saveRequestDetails').hide();
        } else {
            $('#saveRequestDetails').show();
        }

        if(status == true) {
            
            let count = 0;
            for (i = 0; i < sourcing_result.length; i++) {
                if(id == sourcing_result[i][1]) {
                    count++;
                    sourcing_result[i][2] = true;
                    copy_sourcing_result.push(sourcing_result[i]);
                }
            }

            if(count == 0) {
                let combineValue = [ 'add', bom_data.bom_1_req_consld_id, status, bom_data.item_desc, bom_data.appr_item_code, bom_data.appr_item_col_code, 
                bom_data.sourcing_advice, bom_data.vendor_location, bom_data.vendor_name_address, bom_data.contact_email, 
                bom_data.gst, bom_data.online_order_sys, bom_data.pass_expiry_date ];
                table_data.push(combineValue);
                req_sel_data.push(combineValue);
            }

            selectCount = selectCount+1;
        }
        else {
            for (i = 0; i < req_sel_data.length; i++) {
                if(id == req_sel_data[i][1]) {
                    copy_sourcing_result = copy_sourcing_result.filter(function(e) { if(e[1] !== req_sel_data[i][1]) return e });
                    req_sel_data[i][2] = false;
                }
            }
            
            table_data = table_data.filter(function(e) { if(e[1] !== data['bom_1_req_consld_id']) return e });
            selectCount = selectCount-1;
        }
        append_attach_reference(copy_sourcing_result);
    }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
 
    function append_attach_reference(data) {
        
        var tblData = data.concat(table_data);

        tblData = tblData.reduce((unique, o) => {
            if(!unique.some(obj => obj[3] === o[3] && obj[4] === o[4])) {
              unique.push(o);
            }
            return unique;
        },[]);

        // console.log(tblData)

        $('#attachReference').html('');
        let list = {
            data: tblData,
            columns: [
                { title:'mode', width:'0%',align:'center',type:'hidden'},
                { title:'id', width:'0%',align:'center',type:'hidden'},
                { title:'status', width:'0%',align:'center',type:'hidden'},
                { title: 'Item Description', width: '8%', align: 'left', readOnly: true },
                { title: 'Approved \n Item Code', width: '8%', align: 'left', readOnly: true },
                { title: 'Approved Item \n Colour Code', width: '8%', align: 'left', readOnly: true },
                // { title: 'Blend (%) / Content / Material', width: '12%', align: 'left', readOnly: true },
                { title: 'Sourcing Advice', width: '8%', align: 'left', readOnly: true },
                { title: 'Vendor Location', width: '8%', align: 'left', readOnly: true },
                { title: 'Vendor Name & Address', width: '7%', align: 'left', readOnly: true },
                { title: 'Contact Person / e-mail ID \n/ Phone / Mobile', width: '8%', align: 'left', readOnly: true },
                { title: 'GST / IE Code Details', width: '7%', align: 'left', readOnly: true },
                { title: 'If On-line Ordering System\n Website / User ID / Password', width: '10%', align: 'left', readOnly: true },
                { title: "Password Expiry\n Date & Time", width: '7%', align: 'left', readOnly: true },
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

    $('#saveasdraft').click(function () {
        if(selectCount <= 0 && sourcing_result.length == 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
            updateFunction('draft');
        }
    });

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
        let bom_tbl_data = sampleRequest_vm.getData();
        dataform.append('bom_data', JSON.stringify(bom_tbl_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('auth_status', $('#auth_status').val());
        dataform.append('auth_type', $('#auth_type').val());
        dataform.append('mgmt_remark', $('#mgmt_remark').val());
        dataform.append('request_id', reqId);
        dataform.append('req_type', $('#req_type').val());
        dataform.append('cutoff_date', $('#cutoff_date').val());
        dataform.append('merchant_note', $('#merchant_note').val());
        bom1Upload.startUpload();

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Bomrequest/updateManagementBOMRequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if (okay) {
                        
                        window.location.href = base_path + 'MerchantRequestSent/bom';
                    }
                 });
            },
            error: function () {
                console.log("Error");
            }
        });
    }
    
    // ******** SAVE REQUEST DETAILS ENDS HERE ***************** //

    // ******** SAVE AS DRAFT ENDS HERE ***************** //
    
    
    let bom1Upload = $("#bom1ImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'request/Bomrequest/imageUploadDetails',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            return {
                enquiry_id: enquiry_id,
                request_id: reqId
            };
        },
        afterUploadAll: function () {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('saved')
            ).then(okay => {
                if (okay) {
                    window.location.href = base_path + 'MerchantRequestSent/bom';
                }
            });
        },
        autoSubmit: false
    });
    
    $("#bom1ImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});