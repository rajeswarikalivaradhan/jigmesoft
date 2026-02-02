let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});

function fnSave() {
  
   var items = document.getElementsByName('title');
   var userid = $("#userid").val();
   var selectedItems=[];
   for(var i=0; i<items.length; i++){
        if(items[i].type=='checkbox' && items[i].checked==true){
            selectedItems.push(items[i].value);
        } 
        }
        //console.log(selectedItems.join(','));
        
         const dataToSend = {
                        rfrom: 1,
                        userid: userid,
                        title: selectedItems.join(','), // Convert array to a comma-separated string
        };
            swalWithBootstrapButtons.fire(
            {
              // title: 'Are you sure want to save the details ?',
              // text: "If you save You won't be able to revert this!",
                title: 'Do you want to save the details ?',
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
                    MakeAsynPostRequest(base_path + GlbBAdminFdr + "muser/updateInfo",dataToSend ,"json",function (data) {
                        if (data != '') {
                            if (data.errcode == '404') {
                                fnCallSessionExpire();
                                return false;
                            } else if (data.errcode == -1) {
                                //$('#AnyErrElse').text(data.msg);
                                swalWithBootstrapButtons.fire({
                                    title: data.msg,type: 'warning',
                                    icon: 'warning',
                                    customClass: {'confirmButton': 'btn btn-info'}
                                });
                                return false;
                            } else if (data.errcode == 1) {
                                //console.log(data,'data');
                                GlbId = data.id;
                                swalWithBootstrapButtons.fire({
                                                title: 'Saved!',text: data.message,type: 'success',
                                                icon: 'success',
                                                customClass: {'confirmButton': 'btn btn-info'}
                                }).then((result) => {
                                                    let redirectpath = base_path + GlbBAdminFdr + 'muser/manage';
                                                    window.location.href = redirectpath;
                                });
                                
                            }
                        }
                });
				}
            }); 

}
$('#editEnable').on('click', function() {
    $("#custom_form input").prop("disabled", false);
    $('#svbtn').show();
});

// Get references to the checkboxes and the Save button
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const saveButton = document.getElementById('svbtn');
        
        // Function to check if any checkbox is checked and enable/disable the Save button
        function updateSaveButton() {
            let anyChecked = false;
            
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    anyChecked = true;
                }
            });
            
            saveButton.disabled = !anyChecked;
        }
        
        // Add event listeners to the checkboxes to trigger the validation
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSaveButton);
        });
        
        
 $(document).ready(function() {
    var edit = $('#editvariable').val(); 
    if(edit==2){
        $('#svbtn').hide();
        $("#custom_form input").prop("disabled", true);
    } else { 
       $('#svbtn').show();
       $("#custom_form input").prop("disabled", false);
    }
  });
