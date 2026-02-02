$(document).ready(function () {

    
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

    // ******************************************************************************** 
    // UPLOAD ATTACHMENT STARTS HERE
    // ********************************************************************************

    // ***********************************  ORDER ENTRY STARTS HERE  *********************************** //
    let remarkNimageJSON = '';
    let type = 'orderEntry';
    getRemarksNImageDetails();

    function getRemarksNImageDetails() {
        $('.orderEntryImageView').html('');
        return $.ajax({
            url: base_path+'WorkInProcess/getRemarksNImageDetails',
            data: {"type": type, "enquiry_id": enquiry_id},
            type:'POST',
            success:function(data){
                remarkNimageJSON = $.parseJSON(data);
                if(remarkNimageJSON.remarksData.length > 0) {
                    $('.remarks').val(remarkNimageJSON.remarksData[0].remarks);
                    $('.cad_remarks').val(remarkNimageJSON.remarksData[0].remarks);
                    $('.sample_remarks').val(remarkNimageJSON.remarksData[0].remarks);
                    $('.bom_remarks').val(remarkNimageJSON.remarksData[0].remarks);
                }
                for (let i = 0; i < remarkNimageJSON.filesData.length; i++) {
                    $('.orderEntryImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+remarkNimageJSON.filesData[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href='+base_path+'uploads/workinprocess/orderentry/'+remarkNimageJSON.filesData[i].image_url+' title="Download" download>'+
                                        '<i class="fa fa-download fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href='+base_path+'uploads/workinprocess/orderentry/'+remarkNimageJSON.filesData[i].image_url+' target="_blank" title="Open in New Tab">'+
                                        '<i class="fa fa-file fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                    '<a href="javascript:void(0);" data-id='+remarkNimageJSON.filesData[i].wip_files_id+' class="deleteImg" title="Delete">'+
                                        '<i class="fa fa-trash fa-lg" aria-hidden="true"></i>'+
                                    '</a>'+
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                }
            },      
            error: function() {
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
                    getRemarksNImageDetails();
                }
            });
        }

    });

    orderEntryUpload = $("#orderentryImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'WorkInProcess/updateWipFormDetails',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            let remarks = $('.remarks').val();
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
            location.reload(true);
        },
        autoSubmit: false,
    });

    // function updateWipRemarksDetails(season, eclass, dividept, subclass) {
    //     let remarks = $('#remarks').val();

    //     request = $.ajax({
    //         type: "POST",
    //         url: base_path+'WorkInProcess/updateWipDetails',
    //         data: {"remarks":remarks, "type": type, 'enquiry_id': enquiry_id, 'season': season, 'class': eclass, 'dividept': dividept, 'subclass': subclass},
    //         success:function(data){
    //             var insertResult;
    //             insertResult = $.parseJSON(data);
    //             if(insertResult.status=="success")
    //             {
    //                 console.log('Success');
    //             }
    //         },
    //         error: function() {
    //             console.log("Error");
    //         }
    //     });
    // }

    $("#orderEntryFormSubmit").click(function () {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
        }).then(function (result) {
            if (result.value) {
                let remarks = $('.remarks').val();
                updateFunction(remarks);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                });
            }
        });
    });

    $("#cad_orderEntryFormSubmit").click(function () {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
        }).then(function (result) {
            if (result.value) {
                let remarks = $('.cad_remarks').val();
                updateFunction(remarks);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                });
            }
        });
    });

    $("#sample_orderEntryFormSubmit").click(function () {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
        }).then(function (result) {
            if (result.value) {
                let remarks = $('.sample_remarks').val();
                updateFunction(remarks);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                });
            }
        });
    });

    $("#bom_orderEntryFormSubmit").click(function () {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
        }).then(function (result) {
            if (result.value) {
                let remarks = $('.bom_remarks').val();
                updateFunction(remarks);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                });
            }
        });
    });

    $("#bom2_orderEntryFormSubmit").click(function () {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
        }).then(function (result) {
            if (result.value) {
                let remarks = $('.bom2_remarks').val();
                updateFunction(remarks);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                });
            }
        });
    });

    function updateFunction(remarks) {
        if(remarks == '')
        {
            orderEntryUpload.startUpload();
            cadUpload.startUpload();
            samplingUpload.startUpload();
            bom1Upload.startUpload();
        }
        else {
            orderEntryUpload.startUpload();
            cadUpload.startUpload();
            samplingUpload.startUpload();
            bom1Upload.startUpload();
            request = $.ajax({
                type: "POST",
                url: base_path+'WorkInProcess/updateWipFormDetails',
                data: {"remarks":remarks, "type": type, 'enquiry_id': enquiry_id},
                success:function(data){
                    var insertResult;
                    insertResult = $.parseJSON(data);
                    if(insertResult[0].status=="success")
                    {
                        swalWithBootstrapButtons.fire({
                            title: 'Saved', text: 'Saved successfully.', type: 'success', icon: 'success', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                        });
                    }
                },
                error: function() {
                    console.log("Error");
                }
            });
        }
    }

    // ***********************************  ORDER ENTRY ENDS HERE  *********************************** //

    // ***********************************  CAD STARTS HERE  *********************************** //

    let cadUpload = $("#cadImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'WorkInProcess/updateWipFormDetails',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            let remarks = $('.cad_remarks').val();
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
            location.reload(true);
        },
        autoSubmit: false,
    });

    // $("#orderEntryFormSubmit").change(function () {
    //     var BrnId = $(this).val();
    //     console.log(BrnId);
    // });

    // ***********************************  CAD ends HERE  *********************************** //

    // ***********************************  SAMPLING STARTS HERE  *********************************** //

    let samplingUpload = $("#samplingImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'WorkInProcess/updateWipFormDetails',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            let remarks = $('.sample_remarks').val();
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
            location.reload(true);
        },
        autoSubmit: false
    });

    $("#samplingImageUpload").change(function () {
        var BrnId = $(this).val();
    });
    
    let samplingApprovalUpload = $("#samplingApprovalImageUpload").uploadFile({
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

    $("#samplingApprovalImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // ***********************************  SAMPLING ends HERE  *********************************** //

    // ***********************************  EMBELLISHMENT STARTS HERE  *********************************** //

    let embellishmentUpload = $("#embellishmentImageUpload").uploadFile({
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

    $("#embellishmentImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // ***********************************  EMBELLISHMENT ENDS HERE  *********************************** //

    // ***********************************  BOM 1 STARTS HERE  *********************************** //

    let bom1Upload = $("#bom1ImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'WorkInProcess/updateWipFormDetails',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            let remarks = $('.bom_remarks').val();
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
            location.reload(true);
        },
        autoSubmit: false
    });

    $("#bom1ImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // ***********************************  BOM 1 ENDS HERE  *********************************** //

    // ***********************************  BOM 2 STARTS HERE  *********************************** //

    let bom2Upload = $("#bom2ImageUpload").uploadFile({
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

    $("#bom2ImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // ***********************************  BOM 2 ENDS HERE  *********************************** //

    // ***********************************  CAD STARTS HERE  *********************************** //

    let packingUpload = $("#packingImageUpload").uploadFile({
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

    $("#packingImageUpload").change(function () {
        var BrnId = $(this).val();
        console.log(BrnId);
    });

    // ***********************************  CAD ends HERE  *********************************** //


    // ******************************************************************************** 
    // UPLOAD ATTACHMENT ENDS HERE
    // ********************************************************************************
});