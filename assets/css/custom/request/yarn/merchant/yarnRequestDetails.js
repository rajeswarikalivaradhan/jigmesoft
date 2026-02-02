$(document).ready(function () {

    // **************************************** //
    var yarnRequest_vm = '';
    var sampleReference_vm = '';
    var selectCount = 0;
    var selectedArray = [];
    var requirementData = [];
    var sizeData = [];
    var mode = 'add';
    var req_id = '';
    $('#saveRequestDetails').hide();

    getPurchaseRequest();

    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    // Change function
    // $('#cad_deprt').on('change', function(){
    //     let cad_dept = $('#cad_deprt').val();
    //     $('#fab_dept').val(cad_dept);
    //     $('#bom_dept').val(cad_dept);
    // });    
    
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

    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    function getPurchaseRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/getYarnRequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                sample_requirement_data = JSON.parse(data);
                // if(sample_requirement_data.sourcing_result.length > 0) {
                //     req_id = sample_requirement_data.req_data[0].request_id; 
                //     $('#req_type').val(sample_requirement_data.req_data[0].req_type);
                //     $("#req_type").trigger('change');
                //     $('#req_date').val(sample_requirement_data.req_data[0].req_date);
                //     $('#cutoff_date').val(sample_requirement_data.req_data[0].cutoff_date);
                //     $('#merchant_note').val(sample_requirement_data.req_data[0].merchant_note);
                //     $('#'+sample_requirement_data.req_data[0].purchase_req_type).prop('checked',true);
                // }
                append_sample_request(sample_requirement_data);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_sample_request(data) {
        $('#yarnRequest').html('');
        
        let list = {
            data: data.data,
            columns: data.column,
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false
        };

        yarnRequest_vm = new Vue({
            el: '#yarnRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }
    
    // *********************************************************************************************************************************** 
    // SAMPLE REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
    // ******** SAVE REQUEST DETAILS STARTS HERE **************** //

    let req_sel_data = [];

    $('#getValues').click(function () {
        let respValueData = yarnRequest_vm.getData();
        let selectedCount = 0;
        for(let i=0; i < respValueData.length; i++) {
            if(respValueData[i][1] === true) {
                selectedCount +=1;
                req_sel_data.push(respValueData[i])
            }
        }

        if(selectedCount == 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('selecterror')
            );
        }
        else {
            updateFunction();
        }
    });

    function updateFunction() {
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(req_sel_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('req_type', $('#req_type').val());
        dataform.append('cutoff_date', $('#cutoff_date').val());
        dataform.append('merchant_note', $('#merchant_note').val());
        dataform.append('purchase_req_type', $("input[name='purchase_req_type']:checked").val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Fabricrequest/sendFabricRequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                getPurchaseRequest();
                // *** SAVED MESSAGE *** //
                swalWithBootstrapButtons.fire(
                    alertMessageFunction('saved')
                );
                setTimeout(() => {
                    window.location.href = base_path + 'WorkInProcess/index/' + encodeURIComponent(btoa(enquiry_id));
                }, 1000);
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
    
});