$(document).ready(function () {
  
     
    let readonlyStatus = true;
   
    getCadRequest();
    //getCadRequestImages(auth_status);

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
            url: base_path + 'MerchantRequestSent/getrequestDetails',
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
                   $('#auth_status').val(0);  
                }else{
                       $('#auth_status').val(cad_requirement_data.req_data[0].auth_status);
                }
                $('#auth_status').trigger('change');
                $('#auth_type').val(cad_requirement_data.req_data[0].auth_type);
                $('#auth_by').val(cad_requirement_data.req_data[0].auth_name);
                $('#auth_date').val(cad_requirement_data.req_data[0].auth_date);
                $('#mgmt_remark').val(cad_requirement_data.req_data[0].mgmt_remark);

                let auth_status = cad_requirement_data.req_data[0].auth_status;
              
                if(auth_status == 2)
                {
                    readonlyStatus = false;
                    $(".edit.mgmt").prop('disabled', false);
                }

                append_cad_request(cad_requirement_data);
                append_attach_reference(cad_requirement_data);
                getCadRequestImages(auth_status)
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getCadRequestImages(auth_status) {
        $('.ImageView').html('');
        $('.ImageViews').html('');
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
                //let auth_status = cad_requirement_data.req_data[0].auth_status;
           
              
                
                  let subscriberId = imageJSON.subscriber_id;
                 
               
                for (let i = 0; i < imageJSON.images.length; i++) {
                     let deleteIcon = ''; // Default value for delete icon

   
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
                                    '<a href='+base_path+'uploads/request/cad/'+subscriberId+'/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/cad/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                      deleteIcon + 
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );      
                    
                    $('.ImageViews').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+imageJSON.images[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/request/cad/'+imageJSON.images[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/request/cad/'+subscriberId+'/'+imageJSON.images[i].image_url+' target="_blank" title="Open in New Tab">'+
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

    $(document).on('click','.deleteImg',function(){
        var id = $(this).attr('data-id');

        if (confirm('Are you sure you want to delete the file')) {
            MakeAsynPostRequest(base_path + "WorkInProcess/deleteImageDetails", "&id=" + id, "json", function(data) {
                if(data.status == 'success')
                {
                    location.reload(true);
                }
            });
        }

    });

    function append_cad_request(data) {
        $('#cadRequest').html('');
        let dd = [], updatedRow = '', index = '', nVal = '';
        let PurposeData = [ 'Costing', 'FCC - Sample', 'FCC - Bulk', 'Cutting - Sample', 'Cutting - Bulk', 'Bit Cutting - Sample',
            'Bit Cutting - Bulk', 'Others' ];;

        let list = {
            data: data.data,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'dropdown', title: 'Purpose', width: '7%', align: 'left', source: PurposeData, readOnly: readonlyStatus },
                { type: 'dropdown', title: 'Category', width: '7%', align: 'left', source: ['New', 'In-line', 'Revised'],readOnly: readonlyStatus },
                { type: 'dropdown', title: 'If Revised or In-line\nPrevious CAD Ref. No.', width: '10%', align: 'center', source: data.cadRefNo, filter: refFilter, readOnly: readonlyStatus },
                { type: 'dropdown', title: 'Required\nSize(s)', width: '5%', align: 'left', source: data.sizeData, multiple: true,readOnly: true }
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

    function refFilter(instance, cell, c, r, source)
    {
        let req_id = instance.jexcel.getValueFromCoords(c - 3, r);
        let size_id = instance.jexcel.getValueFromCoords(c - 4, r);
        let component_id = instance.jexcel.getValueFromCoords(c - 5, r);
        let combo_id = instance.jexcel.getValueFromCoords(c - 6, r);
        let po_enq_id = instance.jexcel.getValueFromCoords(c - 7, r);

        if (req_id != "" && size_id != "" && component_id != "" && combo_id != "" && po_enq_id != "") {
            return source.filter(function (item) {
                if ((item.req_id == req_id) && (item.size_id == size_id) && (item.component_id == component_id) &&
                    (item.combo_id == combo_id) && (item.po_enq_id == po_enq_id) ) return true;
            })
        } else {
            return [];
        }
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
                { type: 'dropdown', title: 'Approved & Graded\n Measurement Chart', width: '7%', align: 'left', source: common_dd,readOnly: readonlyStatus },
                { type: 'dropdown', title: 'Complete Artwork', width: '7%', align: 'left', source: common_dd,readOnly: readonlyStatus },
                { type: 'dropdown', title: 'How to Measure\n Details', width: '7%', align: 'left', source: common_dd,readOnly: readonlyStatus },
                { type: 'dropdown', title: 'Buyers Original \nSample or Pattern', width: '7%', align: 'left', source: common_dd,readOnly: readonlyStatus },
                { type: 'dropdown', title: "Buyer's Comments", width: '7%', align: 'left', source: common_dd,readOnly: readonlyStatus },
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

        let req_data = cadRequest_vm.getData();
        let ref_data = cadReference_vm.getData();

        $('.herr').hide();
        if($('#req_type').val() == "" || $('#req_type').val() == null ) {
            $('#err_req_type').html("Select request type");
            $('#err_req_type').show();
        } 
        else if($('#merchant_note').val() == "" || $('#merchant_note').val() == null ) {
            $('#err_merchant_note').html("Fill merchant note");
            $('#err_merchant_note').show();
        }
        else {
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
                            if(j >= 6) {
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
                            if(j == 0) {
                                decEmpAr.push(data_a[j]);
                            }
                            if(j >= 4) {
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
    });
    
    function updateFunction(finalData) {
        
        let dataform = new FormData();
        dataform.append('data', JSON.stringify(finalData));
        dataform.append('request_id', reqId);
        dataform.append('req_type', $('#req_type').val());
        dataform.append('cutoff_date', $('#cutoff_date').val());
        dataform.append('merchant_note', $('#merchant_note').val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/editCadRequest',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                data = JSON.parse(data);
                // // *** SAVED MESSAGE *** //
                if(data.status == "success")
                {
                    if(CADUpload.selectedFiles == 0) {
                        window.location.href = base_path + 'MerchantRequestSent/cad';
                    } else {
                        CADUpload.startUpload();
                    }
                }
            },
            error: function () {
                console.log("Error");
            }
        });

    }

    let img_type = 'cad_request';

    let CADUpload = $("#reqCADImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'request/cadrequest/uploadCADReqImages',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            return {
                enquiry_id: enquiry_id,
                type: img_type,
                request_id: reqId,
            };
        },
        afterUploadAll:function () {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('saved')
            ).then(okay => {
                if(okay)
                {
                    window.location.href = base_path + 'MerchantRequestSent/cad';
                }
            });
        },
        autoSubmit: false
    });
    
    $("#reqCADImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // *********************************************************************************************************************************** 
    // CAD REQUEST ENDS HERE 
    // ***********************************************************************************************************************************
    
});