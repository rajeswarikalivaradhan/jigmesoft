$(document).ready(function () {

    // **************************************** //
    
    $('#saveRequestDetails').hide();

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

    // $('#sample').prop('checked',true);
    // $('#qty_type').html('( SAMPLE QTY. )');
    
    // $('input[type=radio][name=purchase_req_type]').change(function() {
    //     let qty_type = $(this).val();
    //     if(qty_type == 'SAMPLE') {
    //         $('#sample').prop('checked',true);
    //         $('#qty_type').html('( SAMPLE QTY. )');
    //     }
    //     else if(qty_type == 'BULK') {
    //         $('#bulk').prop('checked',true);
    //         $('#qty_type').html('( BULK QTY. )');
    //     }
    //     else if(qty_type == 'REVISED') {
    //         $('#revised').prop('checked',true);
    //         $('#qty_type').html('( REVISED QTY. )');
    //     }
    //     else if(qty_type == 'SHORTAGE') {
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
                }
                $('#auth_by').val(sample_requirement_data.req_data[0].auth_name);
                let qty_type = sample_requirement_data.req_data[0].purchase_req_type;
                    if(qty_type == 'SAMPLE') {
                        $('#sample').prop('checked',true);
                        $('#qty_type').html('( SAMPLE QTY. )');
                    }
                    else if(qty_type == 'BULK') {
                        $('#qty_type').html('( BULK QTY. )');
                    }
                    else if(qty_type == 'REVISED') {
                        $('#qty_type').html('( REVISED QTY. )');
                    }
                    else if(qty_type == 'SHORTAGE') {
                        $('#qty_type').html('( SHORTAGE QTY. )');
                    }
                // if(sample_requirement_data.req_data[0].mgmt_approval == 1) {
                //     $('#auth_by').val(sample_requirement_data.req_data[0].auth_name);
                // }
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
                { title: 'Contact Person / e-mail ID /\n Phone / Mobile', width: '8%', align: 'left', readOnly: true },
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
        dataform.append('request_id', reqId);

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
                       // window.location.href = base_path + 'management/bom';
                        window.location.href = base_path + 'management/common_list';
                    }
                 });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

});