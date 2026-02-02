$(document).ready(function () {

    
    var swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: false
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
       
       getRemarksNImageDetails();
    });



    //let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    
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
        
         let tab = $('.nav-pills li.active a').attr('href');
       let type = '';

    if (tab) {
        switch (tab) {
            case '#order_entry': type = 'orderentry'; break;
            case '#cad': type = 'cad'; break;
            case '#sample': type = 'sample'; break;
            case '#embellishment': type = 'embellishment'; break;
            case '#bom_art_1': type = 'bom1'; break;
            case '#bom_art_2': type = 'bom2'; break;
            case '#packing': type = 'packing'; break;
            case '#final_inspection': type = 'finalinspection'; break;
            case '#documentation': type = 'documentation'; break;
        }
    }
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
                     const subscriber_id = remarkNimageJSON.subscriber_id || ''; // or get it from elsewhere
                     //alert(subscriber_id);
                  let activeTabId = $('.nav-pills li.active a').attr('href').replace('#', '');
                  const fileUrl = base_path + 'uploads/workinprocess/' + type + '/' + subscriber_id + '/' + remarkNimageJSON.filesData[i].image_url;

                   $('.orderEntryImageView').append(
                        '<li class="file-viwer-jig">'+
                            '<div style="padding: 10px 0;">'+
                                '<div class="ajax-file-upload-filename">'+remarkNimageJSON.filesData[i].image_url+'</div>'+
                                '<div class="upload-action-btn">'+
                                    '<a href="' + fileUrl + '" title="Download" download>' +
                                     '<i class="fa fa-download fa-lg" aria-hidden="true"></i>' +
                                        '</a>' +
                    // Second link to open in a new tab
                                  '<a href="' + fileUrl + '" target="_blank" title="Open in New Tab">' +
                                  '<i class="fa fa-file fa-lg" aria-hidden="true"></i>' +
                                   '</a>' +
                                   
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

     $("#orderEntryFormSubmit").click(function () {
    swalWithBootstrapButtons.fire({
            title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
        }).then(function (result) {
        if (result.isConfirmed) {
          
            Swal.fire({
                title: 'Saving...',
                html: `<div class="custom-loader"></div>`,
                allowOutsideClick: false,
                showConfirmButton: false
            });

            // ✅ Start upload (actual save logic)
            orderEntryUpload.startUpload();
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: 'Cancelled',
                text: 'Cancelled successfully.',
                icon: 'error',
                customClass: { confirmButton: 'btn btn-secondary px-5' }
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
                 Swal.fire({
                title: 'Saving...',
                html: `<div class="custom-loader"></div>`,
                allowOutsideClick: false,
                showConfirmButton: false
            });
             // ✅ Start upload (actual save logic)
                 cadUpload.startUpload();
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
               Swal.fire({
                title: 'Saving...',
                html: `<div class="custom-loader"></div>`,
                allowOutsideClick: false,
                showConfirmButton: false
            });
             // ✅ Start upload (actual save logic)
               samplingUpload.startUpload();

            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                });
            }
        });
    });

     $("#emb_orderEntryFormSubmit").click(function () {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
        }).then(function (result) {
            if (result.value) {
                let remarks = $('.emb_remarks').val();
                 Swal.fire({
                title: 'Saving...',
                html: `<div class="custom-loader"></div>`,
                allowOutsideClick: false,
                showConfirmButton: false
            });
             // ✅ Start upload (actual save logic)
                 embUpload.startUpload();
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
                 Swal.fire({
                title: 'Saving...',
                html: `<div class="custom-loader"></div>`,
                allowOutsideClick: false,
                showConfirmButton: false
            });
             // ✅ Start upload (actual save logic)
                 bom1Upload.startUpload();
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
                Swal.fire({
                title: 'Saving...',
                html: `<div class="custom-loader"></div>`,
                allowOutsideClick: false,
                showConfirmButton: false
            });
             // ✅ Start upload (actual save logic)
                 bom2Upload.startUpload();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                });
            }
        });
    });

     $("#packing_orderEntryFormSubmit").click(function () {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
        }).then(function (result) {
            if (result.value) {
                let remarks = $('.packing_remarks').val();
                Swal.fire({
                title: 'Saving...',
                html: `<div class="custom-loader"></div>`,
                allowOutsideClick: false,
                showConfirmButton: false
            });
             // ✅ Start upload (actual save logic)
                 packingUpload.startUpload();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                });
            }
        });
    });

    $("#final_orderEntryFormSubmit").click(function () {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
        }).then(function (result) {
            if (result.value) {
                let remarks = $('.final_remarks').val();
                Swal.fire({
                title: 'Saving...',
                html: `<div class="custom-loader"></div>`,
                allowOutsideClick: false,
                showConfirmButton: false
            });
             // ✅ Start upload (actual save logic)
                 finalUpload.startUpload();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelled', text: 'Cancelled successfully.', type: 'error', icon: 'error', customClass: { 'confirmButton': 'btn btn-secondary px-5' }
                });
            }
        });
    });

    $("#document_orderEntryFormSubmit").click(function () {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure want to \n save the details ?', text: "If you save You won't be able to revert this!", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes, do it!', cancelButtonText: 'No, cancel!', reverseButtons: true, customClass: { 'confirmButton': 'btn btn-green mx-2 px-3', 'cancelButton': 'btn btn-red mx-2 px-3' }
        }).then(function (result) {
            if (result.value) {
                let remarks = $('.final_remarks').val();
                Swal.fire({
                title: 'Saving...',
                html: `<div class="custom-loader"></div>`,
                allowOutsideClick: false,
                showConfirmButton: false
            });
             // ✅ Start upload (actual save logic)
                 documentUpload.startUpload();
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

     orderEntryUpload = $("#orderentryImageUpload").uploadFile({
    url: base_path + 'WorkInProcess/updateWipFormDetails',
    fileName: "myFile",
    allowedTypes: allowedFileTypes,
    multiple: true,
    autoSubmit: false,
    dynamicFormData: function () {
        return {
            enquiry_id: enquiry_id,
            type: type,
            remarks: $('.remarks').val()
        };
    },
    afterUploadAll: function () {
        // ✅ Close loading spinner ONLY after upload is done
        Swal.close();

        // ✅ Show success popup
        swalWithBootstrapButtons.fire({
            title: 'Saved Successfully!',
            text: 'Your details have been saved.',
            icon: 'success',
            customClass: { confirmButton: 'btn btn-green px-5' }
        }).then(() => {
            getRemarksNImageDetails();
            $(".ajax-file-upload-statusbar").remove(); // Optional: clear upload UI
        });
    }
});

     cadUpload = $("#cadImageUpload").uploadFile({
        url: base_path + 'WorkInProcess/updateWipFormDetails',
    fileName: "myFile",
    allowedTypes: allowedFileTypes,
    multiple: true,
    autoSubmit: false,
        dynamicFormData:function () {
            let remarks = $('.cad_remarks').val();
             let type = 'cad';
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
            Swal.close();

        // ✅ Show success message
        swalWithBootstrapButtons.fire({
            title: 'Saved Successfully!',
            text: 'Your CAD details have been saved.',
            icon: 'success',
            customClass: { confirmButton: 'btn btn-green px-5' }
        }).then(() => {
            getRemarksNImageDetails(); // if you have separate logic, update this
            $(".ajax-file-upload-statusbar").remove();
        });
        },
        //autoSubmit: false,
    });

    // $("#orderEntryFormSubmit").change(function () {
    //     var BrnId = $(this).val();
    //     console.log(BrnId);
    // });

    // ***********************************  CAD ends HERE  *********************************** //

    // ***********************************  SAMPLING STARTS HERE  *********************************** //

    let samplingUpload = $("#samplingImageUpload").uploadFile({
        url: base_path + 'WorkInProcess/updateWipFormDetails',
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        multiple: true,
         autoSubmit: false,
        dynamicFormData:function () {
            let remarks = $('.sample_remarks').val();
             let type = 'sample';
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
             Swal.close();

        // ✅ Show success message
        swalWithBootstrapButtons.fire({
            title: 'Saved Successfully!',
            text: 'Your sample details have been saved.',
            icon: 'success',
            customClass: { confirmButton: 'btn btn-green px-5' }
        }).then(() => {
            getRemarksNImageDetails(); // if you have separate logic, update this
            $(".ajax-file-upload-statusbar").remove();
        });
        
        },
        
    });

    let embUpload = $("#embellishmentImageUpload").uploadFile({
        url: base_path + 'WorkInProcess/updateWipFormDetails',
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        multiple: true,
       autoSubmit: false,
        dynamicFormData:function () {
            let remarks = $('.cad_remarks').val();
             let type = 'embellishment';
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
            Swal.close();

        // ✅ Show success message
        swalWithBootstrapButtons.fire({
            title: 'Saved Successfully!',
            text: 'Your Embellishment details have been saved.',
            icon: 'success',
            customClass: { confirmButton: 'btn btn-green px-5' }
        }).then(() => {
            getRemarksNImageDetails(); // if you have separate logic, update this
            $(".ajax-file-upload-statusbar").remove();
        });
        },
        //autoSubmit: false,
    });

    $("#samplingImageUpload").change(function () {
        var BrnId = $(this).val();
    });
    
   

   

    // ***********************************  EMBELLISHMENT ENDS HERE  *********************************** //

    // ***********************************  BOM 1 STARTS HERE  *********************************** //

    let bom1Upload = $("#bom1ImageUpload").uploadFile({
        url: base_path + 'WorkInProcess/updateWipFormDetails',
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        multiple: true,
       autoSubmit: false,
        dynamicFormData:function () {
            let remarks = $('.bom_remarks').val();
              let type = 'bom1';
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
           Swal.close();

        // ✅ Show success message
        swalWithBootstrapButtons.fire({
            title: 'Saved Successfully!',
            text: 'Your Bom1 details have been saved.',
            icon: 'success',
            customClass: { confirmButton: 'btn btn-green px-5' }
        }).then(() => {
            getRemarksNImageDetails(); // if you have separate logic, update this
            $(".ajax-file-upload-statusbar").remove();
        });
        },
       
    });

    $("#bom1ImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // ***********************************  BOM 1 ENDS HERE  *********************************** //

    // ***********************************  BOM 2 STARTS HERE  *********************************** //
 let bom2Upload = $("#bom2ImageUpload").uploadFile({
        url: base_path + 'WorkInProcess/updateWipFormDetails',
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        multiple: true,
       autoSubmit: false,
        dynamicFormData:function () {
            let remarks = $('.bom2_remarks').val();
              let type = 'bom2';
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
           Swal.close();

        // ✅ Show success message
        swalWithBootstrapButtons.fire({
            title: 'Saved Successfully!',
            text: 'Your Bom2 details have been saved.',
            icon: 'success',
            customClass: { confirmButton: 'btn btn-green px-5' }
        }).then(() => {
            getRemarksNImageDetails(); // if you have separate logic, update this
            $(".ajax-file-upload-statusbar").remove();
        });
        },
       
    });

    $("#bom2ImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    

    // ***********************************  BOM 2 ENDS HERE  *********************************** //

    // ***********************************  packing STARTS HERE  *********************************** //

      // ***********************************  BOM 2 STARTS HERE  *********************************** //
 let packingUpload = $("#packingImageUpload").uploadFile({
        url: base_path + 'WorkInProcess/updateWipFormDetails',
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        multiple: true,
       autoSubmit: false,
        dynamicFormData:function () {
            let remarks = $('.bom2_remarks').val();
              let type = 'packing';
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
           Swal.close();

        // ✅ Show success message
        swalWithBootstrapButtons.fire({
            title: 'Saved Successfully!',
            text: 'Your Packing details have been saved.',
            icon: 'success',
            customClass: { confirmButton: 'btn btn-green px-5' }
        }).then(() => {
            getRemarksNImageDetails(); // if you have separate logic, update this
            $(".ajax-file-upload-statusbar").remove();
        });
        },
       
    });

    $("#bom2ImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    let finalUpload = $("#fianlImageUpload").uploadFile({
        url: base_path + 'WorkInProcess/updateWipFormDetails',
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        multiple: true,
       autoSubmit: false,
        dynamicFormData:function () {
            let remarks = $('.bom2_remarks').val();
              let type = 'finalinspection';
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
           Swal.close();

        // ✅ Show success message
        swalWithBootstrapButtons.fire({
            title: 'Saved Successfully!',
            text: 'Your Packing details have been saved.',
            icon: 'success',
            customClass: { confirmButton: 'btn btn-green px-5' }
        }).then(() => {
            getRemarksNImageDetails(); // if you have separate logic, update this
            $(".ajax-file-upload-statusbar").remove();
        });
        },
       
    });

    $("#fianlImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    let documentUpload = $("#documentImageUpload").uploadFile({
        url: base_path + 'WorkInProcess/updateWipFormDetails',
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        multiple: true,
       autoSubmit: false,
        dynamicFormData:function () {
            let remarks = $('.bom2_remarks').val();
              let type = 'documentation';
            return {
                enquiry_id: enquiry_id,
                type: type,
                remarks: remarks,
            };
        },
        afterUploadAll: function () {
           Swal.close();

        // ✅ Show success message
        swalWithBootstrapButtons.fire({
            title: 'Saved Successfully!',
            text: 'Your Packing details have been saved.',
            icon: 'success',
            customClass: { confirmButton: 'btn btn-green px-5' }
        }).then(() => {
            getRemarksNImageDetails(); // if you have separate logic, update this
            $(".ajax-file-upload-statusbar").remove();
        });
        },
       
    });

    $("#documentImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    // ***********************************  CAD ends HERE  *********************************** //


    // ******************************************************************************** 
    // UPLOAD ATTACHMENT ENDS HERE
    // ********************************************************************************
});