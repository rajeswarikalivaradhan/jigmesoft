function fncleardraft(val,encodedsubscriberid) {
  
    if(val!='') {
      swalWithBootstrapButtons.fire(
                            {
                               // title: 'Are you sure want to save the details ?',
                               // text: "If you save You won't be able to revert this!",
                                title: 'Do you want to clear the draft details ?',
                                type: 'warning',
                                showCancelButton: true,
                                scrollbarPadding: false,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                reverseButtons: true,
                                width:460,
                                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                            }
							).then(function(result) {
								if (result.value) {
								MakeAsynPostRequest(base_path + GlbBAdminFdr + "mreqrcved/getproformacleardraftstatus", "id=" + val, "json", function (data) {
								    console.log('clear'+data);
                                    if(data.success==1) {
                                    let redirectpath = base_path + GlbBAdminFdr + 'mreqrcved/raiseproforma/'+ encodedsubscriberid;
                                    window.location.href = redirectpath;
                                    }
                                });
								} 
								else if (result.dismiss === Swal.DismissReason.cancel) {
								
								}
                            }); 
    }
}