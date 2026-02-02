$(document).ready(function () {

    var cadRequest_vm = '';
    var cadReference_vm = '';
    var selectCount = 0;
    getCadRequest();
    getCadRequestImages();

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
    }

    // *********************************************************************************************************************************** 
    // CAD REQUEST STARTS HERE 
    // ***********************************************************************************************************************************
    
    var requirementData = [];
    var requirementReferenceData = [];

    function getCadRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'management/getrequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                cad_requirement_data = JSON.parse(data);
                $('#req_type').val(cad_requirement_data.req_data[0].req_type);
                $('#req_type').trigger('change');
                $('#req_date').val(cad_requirement_data.req_data[0].req_date);
                $('#cutoff_date').val(cad_requirement_data.req_data[0].cutoff_date);
                $('#merchant_note').val(cad_requirement_data.req_data[0].merchant_note);

                if(cad_requirement_data.req_data[0].auth_status == '')
                {
                    cad_requirement_data.req_data[0].auth_status = 0;
                }
                $('#auth_status').val(cad_requirement_data.req_data[0].mgmt_approval);
                $('#auth_status').trigger('change');
                $('#auth_type').val(cad_requirement_data.req_data[0].auth_type);
                $('#auth_type').trigger('change');
                $('#auth_by').val(cad_requirement_data.req_data[0].auth_name);
                $('#auth_date').val(cad_requirement_data.req_data[0].auth_date);
                $('#mgmt_remark').val(cad_requirement_data.req_data[0].mgmt_remark);
                
                append_cad_request(cad_requirement_data);
                append_attach_reference(cad_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getCadRequestImages() {
        $('.ImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'cad_request');
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
                                    '<a href='+base_path+'uploads/request/cad/'+imageJSON[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/cad/'+imageJSON[i].image_url+' target="_blank" title="Open in New Tab">'+
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

    function append_cad_request(data) {
        $('#cadRequest').html('');
        let dd = [], updatedRow = '', index = '', nVal = '';
        let PurposeData = [ 'Costing', 'FCC - Sample', 'FCC - Bulk', 'Cutting - Sample', 'Cutting - Bulk', 'Bit Cutting - Sample',
            'Bit Cutting - Bulk', 'Others' ];
        let list = {
            data: data.data,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                // { type: 'checkbox', title: 'Mark', width: '5%', align: 'center' },
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '7%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData, readOnly: true },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'],readOnly: true },
                { type: 'text', title: 'If Revised or In-line\nPrevious CAD Ref. No.', width: '10%', align: 'center', readOnly: true },
                { type: 'dropdown', title: 'Required\nSize(s)', width: '5%', align: 'left', source: data.sizeData, multiple: true,readOnly: true },
                // { type: 'text', title: 'Assigned\n CAD Reference No.', width: '10%', align: 'left', readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        cadRequest_vm = new Vue({
            el: '#cadRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }

    // *********************************************************************************************************************************** 
    // CAD REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_attach_reference(data) {
        let common_dd = [
            {id: '1', name: 'Attached'}, 
            {id: '2', name: 'Pending'}, 
            {id: '3', name: 'N.A.'}, 
        ];
        $('#attachReference').html('');
        let list = {
            data: data.requestData,
            columns: [
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Approved & Graded\n Measurement Chart', width: '7%', align: 'left', source: common_dd,readOnly: true },
                { type: 'dropdown', title: 'Complete Artwork', width: '7%', align: 'left', source: common_dd,readOnly: true },
                { type: 'dropdown', title: 'How to Measure\n Details', width: '7%', align: 'left', source: common_dd,readOnly: true },
                { type: 'dropdown', title: 'Buyers Original \nSample or Pattern', width: '7%', align: 'left', source: common_dd,readOnly: true },
                { type: 'dropdown', title: "Buyer's Comments", width: '7%', align: 'left', source: common_dd,readOnly: true },
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
        };

        cadReference_vm = new Vue({
            el: '#attachReference',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    $('#getValues').click(function () {
        $('.herr').hide();
        if($('#auth_type').val() == '') {
            $('#err_auth_type').html('Select auth status');
            $('#err_auth_type').show();
        }
        else if($('#mgmt_remark').val() == ''){
            $('#err_mgmt_remark').html('Fill management remark');
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
        dataform.append('request_id', reqId);
        dataform.append('auth_status', $('#auth_status').val());
        dataform.append('auth_type', $('#auth_type').val());
        dataform.append('mgmt_remark', $('#mgmt_remark').val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'management/updateCadAuthorization',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                ).then(okay => {
                    if(okay) 
                    {
                        window.location.href = base_path + 'management/cad';
                    }
                });
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // *********************************************************************************************************************************** 
    // CAD REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});