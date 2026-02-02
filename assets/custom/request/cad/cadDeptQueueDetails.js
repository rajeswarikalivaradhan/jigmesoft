$(document).ready(function () {
    getCadRequest();
    getCadMerchantRequestImages();
    getCadQARequestImages();
    getCadRequestImages();
    var selectCount = 0;
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
        
        if(mode === 're_scheduled') {
            return {
                title: 'Are you sure want to \n Re-Scheduled the Job?',
                text: "If you Re-Scheduled the Job You won't be able to revert this!",
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
        
        if(mode == "editcount_error") {
            return {
                title: 'Warning',
                text: "Edit Limit Exceeds",
                icon: 'warning',
                confirmButtonText: 'OK',
                customClass: {
                    'confirmButton': 'btn btn-secondary px-5'
                }
            }
        }
        
        // if(mode == "selecterror") {
        //     return {
        //         title: 'Warning',
        //         text: "Please select atleast one requirement",
        //         icon: 'warning',
        //         confirmButtonText: 'OK',
        //         customClass: {
        //             'confirmButton': 'btn btn-secondary px-5'
        //         }
        //     }
        // }
        
        // if(mode == "checkError") {
        //     return {
        //         title: 'Warning',
        //         text: "Please select CheckBox",
        //         icon: 'warning',
        //         confirmButtonText: 'OK',
        //         customClass: {
        //             'confirmButton': 'btn btn-secondary px-5'
        //         }
        //     }
        // }
        
        
    }

    // *********************************************************************************************************************************** 
    // CAD REQUEST STARTS HERE 
    // ***********************************************************************************************************************************

    var jobStatusData = [];
    var cad_requirement_data = [];

    function getCadRequest() {
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/getcadqarequestDetails',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                cad_requirement_data = JSON.parse(data);
                append_cad_request(cad_requirement_data);
                append_attach_reference(cad_requirement_data);
                append_qa_status(cad_requirement_data);
                // if(cad_requirement_data.jobStatusData.length > 0)
                // {
                //     jobStatusData = cad_requirement_data.jobStatusData;
                    append_job_status(cad_requirement_data);
                // }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getCadMerchantRequestImages() {
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
                let subscriberId = imageJSON.subscriber_id;
                for (let i = 0; i < imageJSON.images.length; i++) {
                  
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

    function getCadQARequestImages() {
        $('.QAImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'qa_request');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getcadrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                  let subscriberId = imageJSON.subscriber_id;
                  
               if(imageJSON.images.length > 0) {
                    //alert(imageJSON.images.length);
                    $('.qaImg').show();
              for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.QAImageView').append(
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
                                    
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                }
                }
                else {
                    $('.qaImg').hide();
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function getCadRequestImages() {
        $('.CADImageView').html('');
        var data = new FormData();
        data.append('enquiry_id', enquiry_id);
        data.append('reqId', reqId);
        data.append('type', 'cad_qa_request');
        let request = $.ajax({
            type: "POST",
            url: base_path + 'MerchantRequestSent/getcadrequestImages',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                imageJSON = $.parseJSON(data);
                  let subscriberId = imageJSON.subscriber_id;
                
                if(imageJSON.images.length > 0) {
                  
                    $('.cadImg').show();
                for (let i = 0; i < imageJSON.images.length; i++) {
                    $('.CADImageView').append(
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
                                    
                                '</div>'+
                            '</div>'+
                        '</li>'
                    );               
                }
                }  else {
                    $('.cadImg').hide();
                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function append_cad_request(data) {
        $('#cadRequest').html('');
        let dd = [], updatedRow = 'A', index = '', nVal = '';
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
            // onchange: function(instance, cell, col, row, val, label, cellName) {
            //     if(col == 1) 
            //     {
            //         updatedRow = row;
            //         getReferenceValue(list.data[row], val);
            //     }
            // },
            // updateTable: function(instance, cell, col, row, val, label, cellName) {
            //     if(col == 1) 
            //     {
            //         // console.log(updatedRow)
            //         // console.log(row)
            //         if(val == true && row != updatedRow)
            //         {
            //             console.log(updatedRow)
            //             console.log(row)
            //             cell.classList.add('readonly');
            //         }
            //     }
            // }
        };

        cadRequest_vm = new Vue({
            el: '#cadRequest',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            }
        });
    }
    
    // function getReferenceValue(data, status) {

    //     if(status == true) {
    //         let emparr = [];
    //         let length = data.length;
    //         for(let i=0; i < data.length; i++) {
    //             if(i == 0)
    //             {
    //                 emparr.push(data[i]);
    //             }
    //             if(i > 1 && i < length-5) {
    //                 emparr.push(data[i]);
    //             }
    //             if(i==11)
    //             {
    //                 emparr.push(data[i]);
    //             }
    //         }
    //         for(let i=0; i < 3; i++) {
    //             emparr.push("");
    //         }
    //         // console.log(emparr);
    //         jobStatusData.push(emparr);
    //         selectCount = selectCount+1;
    //     }
    //     else {
    //         // console.log(data[0])
    //         jobStatusData = jobStatusData.filter(function(e) { if(e[0]!== data[0]) return e  })
    //         selectCount = selectCount-1;
    //     }

    //     if(jobStatusData.length == 0)
    //     {
    //         $('#jobStatusTbl').html('');
    //     } else {
    //         append_job_status();
    //     }
    // }

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
    
    // *********************************************************************************************************************************** 
    // ATTACHMENT REFERENCE ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // QA STATUS UPDATE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_qa_status(data) {
        let common_dd = [
        
        { id: '0', name: 'IN QUEUE' }, 
        { id: '1', name: 'SCHEDULED' }, 
        { id: '2', name: 'RE-SCHEDULED' }, 
        { id: '3', name: 'Q.A.IN PROGRESS' },
        { id: '4', name: 'DISCREPANCY' }, 
        { id: '5', name: 'PASS' }, 
        { id: '6', name: 'PASS COND.' }, 
        { id: '7', name: 'FAIL' }, 
        { id: '8', name: '-' }, 
        { id: '9', name: 'IN-QUEUE RR'},
        
    ];

        $('#qaStatusTbl').html('');
        let hai = "AM";
        let list = {
            data: data.qaStatusData,
            columns: [
                { title:'id', width:'10%',align:'center',type:'hidden'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Assigned\nCAD Reference No.', width: '7%', align: 'left', readOnly: true },
                { type: 'text', title: 'Q.A. Request Sent\nDate & Time', width: '7%', align: 'center', readOnly: true },
                // { type: 'calendar', title: 'Q.A. Scheduled\nDate & Time', width: '7%', align: 'left', options: { format:'DD/MM/YYYY HH12:MI', time:1 } },
                { type: 'calendar', title: 'Q.A. Scheduled\nDate & Time', width: '7%', align: 'center', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                // { type: 'calendar', title: 'Q.A. Scheduled\nDate & Time', width: '7%', align: 'left', readOnly: true, options: { format:'DD/MM/YYYY HH12:MI AM/PM' , time:1 } },
                { type: 'dropdown', title: 'Q.A. Status.', width: '7%', align: 'center', source: common_dd, readOnly: true },
                { type: 'text', title: 'Q.A. Status Update\nDate & Time', width: '7%', align: 'center',readOnly: true }
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            updateTable: function (instance, cell, col, row, val, label) {
            if (col === 9) { // Q.A. Status column index
              let  Date= data.qaStatusData[row][8];
              //alert(Date);
                  
                setTimeout(() => {
                     let statusId= val?.toString();
                   
                 
                  
                    let backgroundColor = '';
                    let textColor = 'black';
                    const group1 = ['0', '1', '2', '3'];
                  
                    const group2 = ['5', '6'];
                   
                    const group3 = ['4','7','9'];

                    if (group1.includes(statusId)) {
                        backgroundColor = '#FFA519'; // light yellow
                        } else if (group2.includes(statusId)) {
                        backgroundColor = '#5DE684'; // light green
                       
                    } else if (group3.includes(statusId)) {
                        backgroundColor = '#fc0303ff'; // light PURPLE
                       
                    }

                    
                    $(cell).css({
                        'background-color': backgroundColor,
                        'color': textColor,
                        'font-weight': 'bold'
                    });
                }, 10);
            }
        }
        };

        

        cadReference_vm = new Vue({
            el: '#qaStatusTbl',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });
    }
    
    // *********************************************************************************************************************************** 
    // QA STATUS UPDATE ENDS HERE 
    // ***********************************************************************************************************************************
    
    // *********************************************************************************************************************************** 
    // JOB STATUS UPDATE STARTS HERE 
    // **********************************************************************************************************************************
    
    function append_job_status_old(data) {
        
         let common_dd = [
            { id: '0', name: 'IN-QUEUE'}, 
            { id: '1', name: 'SCHEDULED'}, 
            { id: '2', name: 'RESCHEDULED'}, 
            { id: '8', name: 'WORK IN PROG.'},
            { id: '7', name: 'RE-WORK IN PROG.'},
            { id: '6', name: 'RE-WORK PEND.'},
            { id: '3', name: 'Q.A. REQ.SENT'}, 
            { id: '5', name: 'Q.A. RR SENT'},
            { id: '4', name: 'COMPLETED'},  
           
          ];


        let updatedRow = '';
        let job_sta_update = '';
        let job_re_sta_update = '';
        let job_sta = '';

    

        $('#jobStatusTbl').html('');
        let list = {
            data: data.jobStatusData,
            columns: [
                { title:'id', type:'hidden'},
                { title:'Job Status Update', type:'hidden'},
                { title:'Job Re Status Update', type:'hidden'},
                { title:'Mark', type:'checkbox', width:'2%'},
                { type: 'text', title: 'P.O. No. /\n Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Size Spec \n Code / Fit', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
                { type: 'text', title: 'Assigned\nCAD Reference No.', width: '7%', align: 'left', readOnly: true },
                { type: 'calendar', title: 'Job Scheduled\nDate & Time', width: '7%', align: 'center', options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
                { type: 'dropdown', title: 'Job Status', width: '7%', align: 'center', source: common_dd},
                { type: 'text', title: 'Job Status Update\nDate & Time', width: '7%', align: 'center', readOnly: true },
                { title:'editCount', type:'hidden'},
                { title:'qaStatus', type:'hidden'},
                { title:'qaStatus2', type:'hidden'},
            ],
            minDimensions: [4, 1],
            allowDeleteColumn: false,
            allowInsertRow: false,
            allowInsertColumn: false,
            onchange: function(instance, cell, col, row, val, label, cellName) {
                if(col == 10) 
                {
                    updatedRow = row;
                    job_sta = val;
                }
            },
            updateTable: function(instance, cell, col, row, val, label) {
                if(col == 1)
                {
                    job_sta_update = val;
                }
                if(col == 2)
                {
                    job_re_sta_update = val;
                }
                if(col == 3)
                {
                    // if(data.jobStatusData[row][14] == 'Yes') {
                    //     $(cell).removeClass('readonly');
                    // } else {
                    //     $(cell).addClass('readonly');
                    // }

                     $(cell).removeClass('readonly');
                }
                if(col == 10) {
                    if(data.jobStatusData[row][11] == 0 || data.jobStatusData[row][11] == 1 || data.jobStatusData[row][11] == 2 )
                    {
                        $(cell).removeClass('readonly');
                    }
                    else 
                    {
                        $(cell).addClass('readonly');
                    }
                }
               
if (col === 11) {
  setTimeout(() => {
    const dropdown = cell.querySelector('select');
    if (dropdown) {
      const currentVal = instance.getValueFromCoords(col, row);

      // Clear existing options
      dropdown.innerHTML = '';

      // Example: allow only '4' and '5' to be selectable when status == 5
      const conditionStatus = data.jobStatusData[row][15]; // adjust condition as needed

      common_dd.forEach(opt => {
        const option = document.createElement('option');
        option.value = opt.id;
        option.textContent = opt.name;

        // Disable options if not '4' or '5' and condition is met
        if (conditionStatus == 5 && !(opt.id === '4' || opt.id === '5')) {
          option.disabled = true;
          option.style.color = '#999'; // Optional: greyed-out look
        }

        dropdown.appendChild(option);
      });

      // Keep current selection if still valid
      const validIds = common_dd.map(opt => opt.id);
      if (!validIds.includes(currentVal)) {
        instance.setValueFromCoords(col, row, '4'); // default to '4' if invalid
      }
    }
  }, 10);
}
                if(col == 11 )
                {

                     
                    let allowedOptions = common_dd
                  if(data.jobStatusData[row][11] == 0  || data.jobStatusData[row][11] == 4 )
                    {
                        $(cell).addClass('readonly');
                    }else{
                        $(cell).removeClass('readonly');
                        
                    }

                    if(data.jobStatusData[row][15]==4 && data.jobStatusData[row][15] == '4'){
                       $(cell).addClass('readonly');
                    }else{
                        $(cell).removeClass('readonly');
                    }

                 
                   


                    

                     setTimeout(() => {
                    const statusId = val?.toString();

                  
                    let backgroundColor = '';
                    let textColor = 'black';
                    const group1 = ['0', '1', '2', '3','8'];
                  
                    const group2 = ['4'];
                   
                    const group3 = ['7', '5','6'];

                    if (group1.includes(statusId)) {
                        backgroundColor = '#FFA519'; // light yellow
                        } 
                    else if (group2.includes(statusId)) {
                        backgroundColor = '#5DE684'; // light green
                       
                    } else if (group3.includes(statusId)) {
                        backgroundColor = '#fc0303ff'; // light PURPLE
                       
                    }

                    $(cell).css({
                        'background-color': backgroundColor,
                        'color': textColor,
                        'font-weight': 'bold'
                    });
                }, 10);
                }



            }
        };

        job_status_tbl_vm = new Vue({
            el: '#jobStatusTbl',
            mounted: function () {
                let spreadsheet = jexcel(this.$el, list);
                Object.assign(this, spreadsheet);
            },
        });

    }


    

function filterDropdownOptions(conditionStatus) {
    return common_dd.filter(option => {
        // Example filtering logic based on conditionStatus (value from column 11)
        if (conditionStatus === '0') {
            return option.id !== '0';  // Remove 'IN-QUEUE' if status is '0'
        } else if (conditionStatus === '1') {
            return option.id !== '1';  // Remove 'SCHEDULED' if status is '1'
        }
        // Return all options if no condition matches
        return true;
    });
}
function append_job_status(data) {
    let common_dd = [
        { id: '0', name: 'IN-QUEUE' },
        { id: '1', name: 'SCHEDULED' },
        { id: '2', name: 'RESCHEDULED' },
        { id: '8', name: 'WORK IN PROG.' },
        { id: '3', name: 'Q.A. REQ.SENT' },
        { id: '6', name: 'RE-WORK PEND.' },
        { id: '7', name: 'RE-WORK IN PROG.' },
        { id: '5', name: 'Q.A. RR SENT' },
        { id: '4', name: 'COMPLETED' },
    ];

    let updatedRow = '';
    let job_sta_update = '';
    let job_re_sta_update = '';
    let job_sta = '';

    $('#jobStatusTbl').html('');
    let list = {
        data: data.jobStatusData,
        columns: [
            { title: 'id', type: 'hidden' },
            { title: 'Job Status Update', type: 'hidden' },
            { title: 'Job Re Status Update', type: 'hidden' },
            { title: 'Mark', type: 'checkbox', width: '2%' },
            { type: 'text', title: 'P.O. No. / Enq. Ref. No.', width: '8%', align: 'left', readOnly: true },
            { type: 'text', title: 'Combo / Colour', width: '8%', align: 'left', readOnly: true },
            { type: 'text', title: 'Component', width: '8%', align: 'left', readOnly: true },
            { type: 'text', title: 'Size Spec Code / Fit', width: '8%', align: 'left', readOnly: true },
            { type: 'text', title: 'Requirement', width: '8%', align: 'left', readOnly: true },
            { type: 'text', title: 'Assigned CAD Reference No.', width: '7%', align: 'left', readOnly: true },
            { type: 'calendar', title: 'Job Scheduled Date & Time', width: '7%', align: 'center', options: { format: 'DD/MM/YYYY HH12:MI AM/PM', time: 1 } },
           { type: 'dropdown', title: 'Job Status', width: '7%', align: 'center', source: common_dd ,filter: jobstatus_updation,},
         
            { type: 'text', title: 'Job Status Update Date & Time', width: '7%', align: 'center', readOnly: true },
            { title: 'editCount', type: 'hidden' },
            { title: 'qaStatus', type: 'hidden' },
            { title: 'qaStatus2', type: 'hidden' },
            { title: 'qa_approval', type: 'hidden' },
        ],
        minDimensions: [4, 1],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
        onchange: function (instance, cell, col, row, val, label, cellName) {
            if (col == 10) {
                updatedRow = row;
                job_sta = val;
            }
        },
        updateTable: function (instance, cell, col, row, val, label) {
            if (col == 1) job_sta_update = val;
            if (col == 2) job_re_sta_update = val;
            if (col == 3) $(cell).removeClass('readonly');
            if (col == 10) {
                if(data.jobStatusData[row][3] == true )
                {
                      $(cell).removeClass('readonly');
                   if (['0', '1', '2'].includes(String(data.jobStatusData[row][11]))) {
                    $(cell).removeClass('readonly');
                } else {
                    $(cell).addClass('readonly');
                } 
                }else{
                   $(cell).addClass('readonly');
                }
                


            }


           

             if(col == 11 )
                {
                   if(data.jobStatusData[row][11]=== true)
                   {
                  
                  if(data.jobStatusData[row][11] == 0  || data.jobStatusData[row][11] == 4 )
                    {
                        $(cell).addClass('readonly');
                    }else{
                        $(cell).removeClass('readonly');
                        
                    }

                    if(data.jobStatusData[row][15]==4 && data.jobStatusData[row][15] == '4'){
                       $(cell).addClass('readonly');
                    }else{
                        $(cell).removeClass('readonly');
                    }
                }else{
                    $(cell).removeClass('readonly');
                }

                   

                 
                   setTimeout(() => {
                    const statusId = val?.toString();

                  
                    let backgroundColor = '';
                    let textColor = 'black';
                    const group1 = ['0', '1', '2', '3','8'];
                  
                    const group2 = ['4'];
                   
                    const group3 = ['7', '5','6'];

                    if (group1.includes(statusId)) {
                        backgroundColor = '#FFA519'; // light yellow
                        } 
                    else if (group2.includes(statusId)) {
                        backgroundColor = '#5DE684'; // light green
                       
                    } else if (group3.includes(statusId)) {
                        backgroundColor = '#fc0303ff'; // light PURPLE
                       
                    }

                    $(cell).css({
                        'background-color': backgroundColor,
                        'color': textColor,
                        'font-weight': 'bold'
                    });
                      
                      //const dropdown = $(cell).html; 
                      //console.log("pavi",dropdown);
                }, 10);
                }
        }
                


        
    };

    job_status_tbl_vm = new Vue({
        el: '#jobStatusTbl',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, list);
            Object.assign(this, spreadsheet);
        },
    });
}


function jobstatus_updation(instance, cell, c, r, source) {
   
    let qa_status = instance.jexcel.getValueFromCoords(15, r);
    let qa_approvel = instance.jexcel.getValueFromCoords(16, r);
    let job_status = instance.jexcel.getValueFromCoords(11, r); // Get job status from the cell
     let checkbox = instance.jexcel.getValueFromCoords(3, r);
    let filteredOptions1;
   

   
    if ((checkbox === true) && ( qa_status != '1' && qa_status != '2' && qa_status != '3' && qa_status != '4'  && qa_status != '9' ) ) { // Assuming `data` is available and the 3rd index is correct
      
     $(cell).removeClass('readonly'); 
   
    let filteredOptions;
    if (job_status == '0') {
         filteredOptions = source.map(function (item) {
           
            if (item.id == '8'  ) {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
    else if (job_status == '1' ) {
        filteredOptions = source.map(function (item) {
            // Disable '0' option for job_status '1'
            if (item.id == '8'  ) {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
    else if (job_status == '2') {
        filteredOptions = source.map(function (item) {
            // Disable '0' and '1' options for job_status '2'
            if (item.id == '8' ) {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
    else if (job_status == '3') {
        filteredOptions = source.map(function (item) {
            // Enable '3' and '4' options, disable others
            if (item.id == '3' || item.id == '4') {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
    else if (job_status == '5') {
        filteredOptions = source.map(function (item) {
            // Enable '5' and '4' options, disable others
            if (item.id == '5' || item.id == '4') {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
    else if (job_status == '6') {
        filteredOptions = source.map(function (item) {
            // Enable '5' and '4' options, disable others
            if (item.id == '7' || item.id == '6' || item.id == '4' || item.id == '5') {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
    else if (job_status == '7') {
        filteredOptions = source.map(function (item) {
            // Enable '7', '4', '5' options, disable others
            if (item.id == '7' || item.id == '4' || item.id == '5') {
                return { ...item, disabled: false };
            } else {
                return { ...item, disabled: true };
            }
        });
    }
    else if (job_status == '8') {
        filteredOptions = source.map(function (item) {
            // Disable '0', '1', and '2', enable others
            if (item.id == '0' || item.id == '1' || item.id == '2' || item.id == '6') {
                return { ...item, disabled: true };
            } else {
                return { ...item, disabled: false };
            }
        });
    }
    else {
        filteredOptions = source.map(function (item) {
            return { ...item, disabled: true }; // Disable all options for other job statuses
        });
        
    }
     return filteredOptions;

    // Ensure the dropdown reflects the filtered options with enabled/disabled items
   

   // Remove readonly class if condition is met
    } else {
        filteredOptions1 = source.map(function (item) {
            return { ...item, disabled: true }; // Disable all options for other job statuses
        });
          //return $(cell).addClass('readonly');
           return filteredOptions1;
    }    
    
    
     // Ensure the dropdown reflects the filtered options with enabled/disabled items
  
}



    
    
    // *********************************************************************************************************************************** 
    // JOB STATUS UPDATE ENDS HERE 
    // ***********************************************************************************************************************************
    
    function validateForm(dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][10] == 1 ) {
                errorCount++;
            } 

        }
        return errorCount;
    }
    
    function validateFormCount(dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][12] == 3 ) {
                errorCount++;
            } 

        }
        return errorCount;
    }
    
    function validateQAForm(dataValue ) {
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            if(dataValue[i][14] == 'Yes' ) {
                if(dataValue[i][3] == false ) {
                    errorCount++;
                }
            } 

        }
        return errorCount;
    }

      $('#cad_qa_req').click(function () {

     if ($('input[type="checkbox"]:checked').length === 0) {
        swalWithBootstrapButtons.fire(
            'Error!',
            'Please select at least one JOB STATUS.',
            'warning'
        );
    } else{
       // alert(" 666");
           let jobData = job_status_tbl_vm.getData();
           let filteredData = jobData.filter(row => row[3] === true);
          let ids = filteredData.map(row => row[0]);
            let columnValues = [];
         jobData.forEach(row => {
      columnValues.push(row[11]); // Access the 11th column (index is 0-based)
      });

      let valueToCheck1 = '3';  // Replace 'value1' with the first value you want to check
      let valueToCheck2 = '5';  
          
          
          //let idsString = ids.join(',');
          let idsString = ids.join(',');
       
           let base64Encoded3 = btoa(idsString);
           let idsStrings = encodeURIComponent(base64Encoded3); 
          

           let base64Encoded1 = btoa(enquiry_id);
           let enquiry_ids = encodeURIComponent(base64Encoded1);
           let base64Encoded2 = btoa(reqId);
           let requiredIds = encodeURIComponent(base64Encoded2);
            if(idsStrings!= null && enquiry_ids != null && requiredIds != null){
                if (columnValues.includes(valueToCheck1) || columnValues.includes(valueToCheck2)) {
                window.location.href = base_path + 'request/Cadrequest/qarequest/' + enquiry_ids + '/reqId/' + requiredIds +'/checkid/'+ idsStrings; 
                }else{
                     swalWithBootstrapButtons.fire(
            'Error!',
            'Please select  JOB STATUS PROPERLY.',
            'warning'
        );
                }
            }
             
     }
            });

    $('#getValues').click(function () {
        
        let jobData = job_status_tbl_vm.getData();
        let jobDataCount = validateForm(jobData);
        let jobEditCount = validateFormCount(jobData);
        $('.herr').hide();
        if($('#dep_remarks').val() == "" || $('#dep_remarks').val() == null ) {
            $('#err_dep_remarks').html("Fill cad dept. remarks");
            $('#err_dep_remarks').show();
        } else if(jobEditCount > 0) {
            swalWithBootstrapButtons.fire(
                    alertMessageFunction('editcount_error')
                );
        } 
        
        else if ($('input[type="checkbox"]:checked').length === 0) {
        swalWithBootstrapButtons.fire(
            'Error!',
            'Please select at least one JOB STATUS.',
            'warning'
        );
    } 
else if(jobDataCount > 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('re_scheduled')
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
    
    $('#qaCompleted').click(function () {
        
        let jobData = job_status_tbl_vm.getData();
        let jobDataCount = validateQAForm(jobData);
        
        if(jobDataCount > 0) {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('checkError')
            );
        }
        else {
            swalWithBootstrapButtons.fire(
                // *** CONFIRMATION MESSAGE *** //
                alertMessageFunction('confirmation_save')
            ).then(function (result) {
                if (result.value) {
                    updateCompletedFunction();
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

    function updateFunction_1() {

        let dataform = new FormData();
        let job_status_data = job_status_tbl_vm.getData();
        dataform.append('job_status_data', JSON.stringify(job_status_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('request_id', reqId);
        dataform.append('dep_remarks', $('#dep_remarks').val());

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/UpdateCadQueueRemark',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let res = JSON.parse(data);
                if(res.status == "success")
                {
                    // getCadRequest();
                    if(CADUpload.selectedFiles == 0) {
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(okay => {
                            if(okay) {
                                window.location.href = base_path+"company/mcaduser/cadqueuelist";
                            }
                        });
                    } else {
                        CADUpload.startUpload();
                    }
                }
                else if(res.status == "job_failure"){
                    swalWithBootstrapButtons.fire(
                        alertMessageFunction('cancelled')
                    );

                }
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    function updateFunction() {
    let dataform = new FormData();
    let job_status_data = job_status_tbl_vm.getData();

    dataform.append('job_status_data', JSON.stringify(job_status_data));
    dataform.append('enquiry_id', enquiry_id);
    dataform.append('request_id', reqId);
    dataform.append('dep_remarks', $('#dep_remarks').val());

    $.ajax({
        type: "POST",
        url: base_path + 'request/Cadrequest/UpdateCadQueueRemark',
        data: dataform,
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) {
            try {
                let res = JSON.parse(data);
                console.log("Parsed response:", res);

                if (res.status === "success") {
                    if (CADUpload.selectedFiles == 0) {
                        swalWithBootstrapButtons.fire(alertMessageFunction('saved'))
                            .then(okay => {
                                if (okay) {
                                    window.location.href = base_path + "company/mcaduser/cadqueuelist";
                                }
                            });
                    } else {
                        CADUpload.startUpload();
                    }
                } else if (res.status === "job_failure") {
                   swalWithBootstrapButtons.fire({
                  title: 'Job status  Failed',
                  text: 'The job process CompleteMent has failed',
                  icon: 'error',
                  confirmButtonText: 'OK'
                    }).then((result) => {
        if (result.isConfirmed) {
            //window.location.href = base_path + "company/mcaduser/cadqueuelist";
            window.location.href = base_path + "request/Cadrequest/cadDeptQueueDetails/"+ encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(reqId));
            
        }
    });
                    
                    } else if (res.status === "req_failure") {
                   swalWithBootstrapButtons.fire({
                  title: 'Job status REQ.SENT   Failed',
                  text: 'The submit CAD QA REQUEST BUTTON',
                  icon: 'error',
                  confirmButtonText: 'OK'
                    }).then((result) => {
        if (result.isConfirmed) {
           // window.location.href = base_path + "company/mcaduser/cadqueuelist";
            window.location.href = base_path + "request/Cadrequest/cadDeptQueueDetails/"+ encodeURIComponent(btoa(enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(reqId));
            //return '<a class="bold" href="' + base_path +'request/Cadrequest/cadDeptQueueDetails/'+ encodeURIComponent(btoa(data.enquiry_id)) + '/reqId/' + encodeURIComponent(btoa(data.request_id)) + '">' + data.ref_queue_no + '</a>';
        }
    });
                    
                    } 
                    
                    else {
                    swalWithBootstrapButtons.fire("Something went wrong!");
                    console.warn("Unexpected status:", res.status);
                }
            } catch (e) {
                console.error("Error parsing response:", e);
                swalWithBootstrapButtons.fire("Invalid response from server.");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX error:", error);
            swalWithBootstrapButtons.fire("Server error occurred.");
        }
    });
}
    
    function updateCompletedFunction() {

        let dataform = new FormData();
        let job_status_data = job_status_tbl_vm.getData();
        dataform.append('job_status_data', JSON.stringify(job_status_data));
        dataform.append('enquiry_id', enquiry_id);
        dataform.append('request_id', reqId);

        //console.log(job_status_data);
        

        let request = $.ajax({
            type: "POST",
            url: base_path + 'request/Cadrequest/updateJobCompleted',
            data: dataform,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                let res = JSON.parse(data);
                if(res.status == "success")
                {
                    // getCadRequest();
                    if(CADUpload.selectedFiles == 0) {
                        swalWithBootstrapButtons.fire(
                            alertMessageFunction('saved')
                        ).then(okay => {
                            if(okay) {
                                window.location.href = base_path+"company/mcaduser/cadqueuelist";
                            }
                        });
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

    let type = "cad_qa_request";

    let CADUpload = $("#cadReqImageUpload").uploadFile({
        dragDrop: true,
        multiple: true,
        url:base_path+'request/cadrequest/uploadCADReqImages',
        returnType: "json",
        fileName: "myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            return {
                enquiry_id: enquiry_id,
                type: type,
                request_id: reqId,
            };
        },
        afterUploadAll:function () {
            swalWithBootstrapButtons.fire(
                alertMessageFunction('saved')
            ).then(okay => {
                if(okay) {
                    window.location.href = base_path+"company/mcaduser/cadqueuelist";
                }
            });
        },
        autoSubmit: false
    });

    $("#cadReqImageUpload").change(function () {
        var BrnId = $(this).val();
        // console.log(BrnId);
    });

    
});