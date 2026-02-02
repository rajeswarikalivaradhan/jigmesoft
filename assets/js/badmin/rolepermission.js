
$('#editEnable').on('click', function() {
    $("#custom_form input").prop("disabled", false);
    $('#svbtn').show();
});

// Get references to the checkboxes and the Save button
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const saveButton = document.getElementById('svbtn');
        
        // Function to check if any checkbox is checked and enable/disable the Save button
        // function updateSaveButton() {
        //     let anyChecked = false;
            
        //     checkboxes.forEach(checkbox => {
        //         if (checkbox.checked) {
        //             anyChecked = true;
        //         }
        //     });
            
        //     saveButton.disabled = !anyChecked;
        // }
         function updateSaveButton() {
            let anyChecked = false;
            saveButton.disabled = anyChecked;
        }
        // Add event listeners to the checkboxes to trigger the validation
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSaveButton);
        });
        
        
 $(document).ready(function() {
    var edit = $('#editvariable').val(); 
    var status=$('#statuscond').val(); 
    //alert(status);
     // Check conditions
     if (status == 2) {
        $('#editEnable').hide(); // Hide #editEnable
        $('#svbtn').hide();      // Hide #svbtn
        $("#custom_form input").prop("disabled", true); // Disable inputs in #custom_form
    } 
    
    if (edit == 2) {
        $('#svbtn').hide();      // Hide #svbtn
        $("#custom_form input").prop("disabled", true); // Disable inputs in #custom_form
    } else if (status != 2) { // Ensure this block only runs if status != 2
        $('#svbtn').show();      // Show #svbtn
        $("#custom_form input").prop("disabled", false); // Enable inputs in #custom_form
    }
  });
