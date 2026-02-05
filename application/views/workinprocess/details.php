<?php $this->load->view(CNFCOMPANY.'template/pageheader');?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/datatables.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/bootstrap-datepicker.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/select2.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/uploadfile-order.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/wip.css" />
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<!-- *********************** JEXCEL CSS LOADS HERE ************************-->
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jexcelNew/jspreadsheet.css" />
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/jexcelNew/jsuites.css" />

<!-- *********************** JEXCEL SCRIPTS LOADS HERE ************************-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ajax.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/vue.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcelNew/jexcel.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcelNew/jsuites.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jexcel/numeral.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/new/adminlte.js"></script>




<!-- *************** CUSTOM STYLE HERE ********* -->
<style>
table.table.tbl-procs-border {
    margin-bottom: 0;
}

#table-responsive{
    min-height: unset;
    overflow: unset;
    
}
    #comboColourSizeSheet{
     min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
            }

            #poSizeWiseSheet{
     min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
            }
            #oe_component_intake_wise{
     min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
            }
           
#poWiseDelivery {
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}
#oe_complete_process {
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}
#cad_requirementDetails{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}
#sample_submissionDetails{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}
#embellishmentVendorDetails{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}
.table-responsive.cus-ovrflw {
    min-height: 200px;
    max-height: 650px; 
    overflow-x: auto;
    overflow-y: auto;
    
    
}
#embellishmentDetails{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
    
}
#samplingApprovalDetails{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
    

}
#bom1requirementQtyConsolidated{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}
#bom1_sourcingDetails{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}
#samplingApprovalDetails2{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
    
}

#bom2_sourcingDetails{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}
#bom2requirementQtyConsolidated{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}

#finalInspectionStandardsDetails{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}
#consignee_details{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}



.table-responsive.cus-ovrflw {
  transition: max-height 0.3s ease;
}

.card-title1 {
       color: white; /* Text color */
      text-align: center; /* Center text horizontally */
      font-size: 12px; /* Font size */
      font-weight: 500; /* Font weight */
}


/* #BOM1requirementDetails > .jexcel_content {
    overflow: unset !important;
  
} */

/* #BOM1requirementDetails > .jexcel_content {
    /* overflow: unset !important;
     max-height: 600px;
    overflow-y: auto;
    overflow-x: hidden;
      
    line-height: 10px;
    position: relative; */
    overflow-x: hidden;  /* Allow dropdowns to appear outside */
    /* max-height: 600px;
      overflow-y: auto; */
         
  
}

#BOM1requirementDetails > .jexcel_content {
   
    max-width: 100%;
   overflow-x: auto;
    overflow: visible !important;  
    
         
  
} */

#BOM1requirementDetails  {
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
    
}


#BOM2requirementDetails{
    min-height: 200px;
    position: relative;
    overflow: visible;
    pointer-events: auto;
}

#BOM2requirementDetails > .jexcel_content {
    overflow: unset !important;
}

.your-dropdown-class {
    z-index: 9999; /* Increase the z-index to ensure it is on top */
}
   

.mb-3 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        /* Fullscreen button styling */
        #fullscreen-toggle , #fullscreen-toggle1{
            background-color: #ebecec; /* YouTube Red */
            color: #022b61; /* Icon color */
            font-size: 20px; /* Icon size */
            
            
            border-color: #D0D1D1;
            border: none; /* Remove default border */
            border: none; /* Remove default border */
            border-radius: 15%; /* Rounded button */
         
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth hover effect */
        }

        /* Hover effect for the button */
        #fullscreen-toggle:hover , #fullscreen-toggle1:hover{
            background-color: #022b61; /* Darker red (similar to YouTube hover effect) */
             color: white; /* Icon color */
            transform: scale(1.1); /* Slightly enlarge the button */
        }

        /* Optional: Button focus effect for accessibility */
        #fullscreen-toggle:focus,#fullscreen-toggle1:focus {
            outline: none; /* Remove outline on focus */
          
        }

        /* Icon adjustment (optional, in case you need more control over the icon) */
        #fullscreen-toggle i,#fullscreen-toggle1 i {
            font-size: 10x; /* Adjust icon size */
        }



#requirement, #requirement2 {
    width: 100%;
    max-height: 90vh; /* Set a maximum height for the content */
    overflow: auto; /* Allow scrolling inside the content */
    display: block;
    flex-grow: 1; /* Allow the content to grow and fill the available space */
    background-color: white;
}

##requirement2{
    width: 100%;
    max-height: 90vh; /* Set a maximum height for the content */
    overflow: auto; /* Allow scrolling inside the content */
    display: block;
    flex-grow: 1; /* Allow the content to grow and fill the available space */
    background-color: white;
}


.table-responsive {
    position: relative;
    overflow: hidden; /* Prevents dropdown from overflowing outside the table */
}


.jexcel_dropdown_list {
    z-index: 9999; /* Ensure the dropdown appears on top */
}

/* Optional: If you want to make the dropdown appear above the cell */
.jexcel td .jexcel_dropdown {
    position: absolute;
    z-index: 9999; /* Bring it on top */
}


select {
  width: 250px;
}

option {
  white-space: pre-line; /* Ensure line breaks are respected */
  padding: 5px;
}

.custom-loader {
    margin: 20px auto;
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0%   { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}


#samplingApprovalDetails .jexcel > tbody > tr > td.jexcel_calendar .jexcel_calendar-popup {
    position: absolute;
    top: 120%;  /* Move it further below the cell */
    left: 0;    /* Align it with the left edge of the cell */
    z-index: 9999;  /* Ensure it stays on top */
    background-color: yellow !important; /* Temporary background for debugging */
}

.nav.nav-tabs.sample.d-flex {
    display: flex;
    justify-content: space-between;  /* Distributes space between the text and button */
    align-items: center;  /* Vertically centers the content */
    width: 100%;  /* Ensures the container takes up the full width */
}

.pull-right {
    margin-left: auto; /* This pushes the button to the right */
}


/* .jcalendar .jcalendar-container {
    position: fixed !important; 
    top: -59.5938px !important; 
    left: 1018.16px !important;
} */

    
    /* .jcalendar-focus{
          position: absolute !important;
          right: 0 !important;
  .jcalendar-container{
          position: absolute !important;
           top:0px !important;
           left: 0px !important;
           right: 2px !important;
           display: flex;
           justify-content: flex-end;

        
    }

    } */

    /* .jcalendar-focus{
          position: absolute !important;
          right: 0 !important;
  .jcalendar-container{
          position: absolute !important;
           top:0px !important;
           left: 0px !important;
           right: 2px !important;
           display: flex;
           justify-content: flex-end;

        
    }

    }

    .jexcel_content {
    position: static !important;
} */


     body.col15-cal-open .jcalendar-focus {
    position: absolute !important;
    right: 0 !important;
  }

  body.col15-cal-open .jcalendar-container {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 2px !important;
    display: flex;
    justify-content: flex-end;
    z-index: 99999 !important;
  }

  /* Your original rule */
  .jexcel_content { position: static !important; }

    
    






 
</style>

<!-- <style>
   .table-responsive {
    max-width: 100%;
    overflow-x: auto;
    position: relative;  /* Make sure the table container is positioned */
}

.jspreadsheet {
    table-layout: fixed; /* Fix the layout so columns don't stretch excessively */
    overflow-x: auto;    /* Allow horizontal scrolling */
}

.jexcel_dropdown_list {
    max-height: 150px;   /* Limit height to prevent the dropdown from expanding too much */
    overflow-y: auto;    /* Allow scrolling if dropdown list is too long */
}
    </style> -->
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <?php $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>

        <div class="content px-4">

            <!-- *********************** ORDER PROCESSING START HERE ************************-->

            <?php require('order_processing_edit.php'); ?>

            <!-- *********************** ORDER PROCESSING ENDS HERE ************************-->

            <!-- *********************** WIP DETAILS START HERE ************************-->
            <div class="card-header pb-3 bgc-white border-0 " style="">
                <div class="card-title f-20">
                    <b style="font-size: 20px !important; padding-left: 5px; margin-left: 3px;color: #333">ORDER PROCESSING </b>
                </div>
            </div>
            <div class="col-12 pb-3 px-0">
                <div class="col-12 px-0" style="border-top: 1px solid #022b61;"></div>
            </div>
            <ul class="nav nav-pills pt-2 px-3">
                <li class="active"><a data-toggle="tab" href="#order_entry">ORDER ENTRY</a></li>
                <li><a data-toggle="tab" href="#cad">CAD</a></li>
                <li><a data-toggle="tab" href="#sample">SAMPLE</a></li>
                <li><a data-toggle="tab" href="#embellishment">EMBELLISHMENT</a></li>
                <li><a data-toggle="tab" href="#bom_art_1">BOM Article - 1</a></li>
                <li><a data-toggle="tab" href="#bom_art_2">BOM Article - 2</a></li>
                <!-- <li><a data-toggle="tab" href="#lab">LAB</a></li> -->
                <li><a data-toggle="tab" href="#packing">PACKING</a></li>
                <li><a data-toggle="tab" href="#final_inspection">FINAL INSPECTION</a></li>
                <li><a data-toggle="tab" href="#documentation">DOCUMENTATION</a></li>
                <li><a data-toggle="tab" href="#checklist">CHECKLIST</a></li>
                <li><a data-toggle="tab" href="#testlist">TestList</a></li>
                <div class="pull-right">
                <a id="backbtns" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                </div>
            </ul>

                <div class="tab-content mt-1">
                    <div id="order_entry" class="tab-pane fade in active">
                        <?php require('order_entry.php'); ?>
                    </div>
                    <div id="cad" class="tab-pane fade">
                        <?php require('cad.php'); ?>
                    </div>
                    <div id="sample" class="tab-pane fade">
                        <?php require('sample.php'); ?>
                    </div>
                    <div id="embellishment" class="tab-pane fade">
                        <?php require('embellishment.php'); ?>
                    </div>
                    <div id="bom_art_1" class="tab-pane fade">
                        <?php require('bom_art_1.php'); ?>
                    </div>  
                <div id="bom_art_2" class="tab-pane fade">
                    <?php require('bom_art_2.php'); ?>
                </div>
                <div id="packing" class="tab-pane fade">
                    <?php require('packing.php'); ?>
                </div>
                <div id="final_inspection" class="tab-pane fade">
                    <?php require('final_inspection.php'); ?>
                </div>
                <div id="documentation" class="tab-pane fade">
                    <?php require('documentation.php'); ?>
                </div>
                <div id="checklist" class="tab-pane fade">
                    <?php require('checklist.php'); ?>
                </div>
                <div id="testlist" class="tab-pane fade">
                    <?php require('testlist.php'); ?>
                </div>
            </div>


            <!-- *********************** WIP DETAILS ENDS HERE ************************-->


        </div>

        <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
    <script src="<?php echo base_url();?>assets/js/datatables.min.js"></script>
    <?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.uploadfile.min.js?s=<?php echo str_rand(5) ?>"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <!-- Test Data -->
    <script type="text/javascript" src="<?php echo base_url('assets/custom/products.js'); ?>"></script>
  
    
      <?php 
    if ($loguserid == 3) {
        echo' <script type="text/javascript" src="'. base_url() .'assets/custom/wipform.js"></script>';
        echo '<script type="text/javascript" src="' . base_url() . 'assets/custom/wipdetails.js"></script>';
    } elseif ($loguserid == 2) {
      
         echo' <script type="text/javascript" src="'. base_url() .'assets/custom/wipformmanag.js"></script>';
         echo '<script type="text/javascript" src="' . base_url() . 'assets/custom/wipdetailsManag.js"></script>';
    } else {
      
        echo '<script type="text/javascript">console.log("Invalid user ID");</script>';
    }
?>


    <script type="text/javascript" src="<?php echo base_url(); ?>assets/custom/datepair.js"></script>
    <script>
        var enquiry_id = <?php echo $VarEnqId; ?>;
        // *************** order entry tab panel previous and next trigger *************** 
        $('#backbtns').click(function () {
        let $active = $('.nav-pills > li.active');
        let $prev = $active.prev('li');
        
        if ($prev.length) {
            $prev.find('a').trigger('click');
        }
    });
        $('.order-entry.btnNext').click(function () {
            $('.nav-tabs.order-entry > .active').next('li').find('a').trigger('click');
        });

        $('.order-entry.btnPrevious').click(function () {
            $('.nav-tabs.order-entry > .active').prev('li').find('a').trigger('click');
        });
        // *************** cad tab panel previous and next trigger *************** 
        $('.cad.btnNext').click(function () {
            $('.nav-tabs.cad > .active').next('li').find('a').trigger('click');
        });

        $('.cad.btnPrevious').click(function () {
            $('.nav-tabs.cad > .active').prev('li').find('a').trigger('click');
        });
        // *************** sample tab panel previous and next trigger *************** 
        $('.sample.btnNext').click(function () {
            $('.nav-tabs.sample > .active').next('li').find('a').trigger('click');
        });

        $('.sample.btnPrevious').click(function () {
            $('.nav-tabs.sample > .active').prev('li').find('a').trigger('click');
        });
        // *************** embellishment tab panel previous and next trigger *************** 
        $('.embellishment.btnNext').click(function () {
            $('.nav-tabs.embellishment > .active').next('li').find('a').trigger('click');
        });

        $('.embellishment.btnPrevious').click(function () {
            $('.nav-tabs.embellishment > .active').prev('li').find('a').trigger('click');
        });
        // *************** bom article 1 tab panel previous and next trigger *************** 
        $('.bom-article.btnNext').click(function () {
            $('.nav-tabs.bom-article > .active').next('li').find('a').trigger('click');
        });

        $('.bom-article.btnPrevious').click(function () {
            $('.nav-tabs.bom-article > .active').prev('li').find('a').trigger('click');
        });
        // *************** bom article 2 tab panel previous and next trigger *************** 
        $('.bom-article2.btnNext').click(function () {
            $('.nav-tabs.bom-article2 > .active').next('li').find('a').trigger('click');
        });

        $('.bom-article2.btnPrevious').click(function () {
            $('.nav-tabs.bom-article2 > .active').prev('li').find('a').trigger('click');
        });
        // *************** final inspection tab panel previous and next trigger *************** 
        $('.final-inspection.btnNext').click(function () {
            $('.nav-tabs.final-inspection > .active').next('li').find('a').trigger('click');
        });

        $('.final-inspection.btnPrevious').click(function () {
            $('.nav-tabs.final-inspection > .active').prev('li').find('a').trigger('click');
        });
        // *************** final inspection tab panel previous and next trigger *************** 
        $('.documentation.btnNext').click(function () {
            $('.nav-tabs.documentation > .active').next('li').find('a').trigger('click');
        });

        $('.documentation.btnPrevious').click(function () {
            $('.nav-tabs.documentation > .active').prev('li').find('a').trigger('click');
        });
        
    </script>

    <script>

   
//   const fullscreenButton = document.getElementById('fullscreen-toggle');
//   const tableWrapper = document.querySelector('.table-responsive.cus-ovrflw.pad-b-5.jexcel-ht');

//   fullscreenButton.addEventListener('click', () => {
//    const elem = document.getElementById("requirements");

//     if (!document.fullscreenElement) {
//         if (elem.requestFullscreen) {
//             elem.requestFullscreen();
//         } else if (elem.webkitRequestFullscreen) { /* Safari */
//             elem.webkitRequestFullscreen();
//         } else if (elem.msRequestFullscreen) { /* IE11 */
//             elem.msRequestFullscreen();
//         }
//     } else {
//         if (document.exitFullscreen) {
//             document.exitFullscreen();
//         }
//     }



//     document.addEventListener('fullscreenchange', () => {
//     const isFullScreen = !!document.fullscreenElement;
//     if (isFullScreen) {
//       tableWrapper.style.maxHeight = "90vh";
//     } else {
//       tableWrapper.style.maxHeight = "400px"; // Your default value
//     }
//   });


//   });


//   const fullscreenButton1 = document.getElementById('fullscreen-toggle1');
//   const tableWrapper1 = document.querySelector('.table-responsive.cus-ovrflw.pad-b-5.jexcel-ht');

//   fullscreenButton1.addEventListener('click', () => {
//    const elem1 = document.getElementById("requirements2");

//     if (!document.fullscreenElement) {
//         if (elem1.requestFullscreen) {
//             elem1.requestFullscreen();
//         } else if (elem1.webkitRequestFullscreen) { /* Safari */
//             elem1.webkitRequestFullscreen();
//         } else if (elem1.msRequestFullscreen) { /* IE11 */
//             elem1.msRequestFullscreen();
//         }
//     } else {
//         if (document.exitFullscreen) {
//             document.exitFullscreen();
//         }
//     }


//      document.addEventListener('fullscreenchange', () => {
//     const isFullScreen1 = !!document.fullscreenElement;
//     if (isFullScreen1) {
      
//       tableWrapper1.style.maxHeight = "90vh";
//     } else {
//       tableWrapper1.style.maxHeight = "400px"; // Your default value
//     }
//   });
//   });


  

  function requestFS(el) {
  if (el.requestFullscreen) return el.requestFullscreen();
  if (el.webkitRequestFullscreen) return el.webkitRequestFullscreen(); // Safari
  if (el.msRequestFullscreen) return el.msRequestFullscreen();         // IE11
}

function exitFS() {
  if (document.exitFullscreen) return document.exitFullscreen();
  if (document.webkitExitFullscreen) return document.webkitExitFullscreen();
  if (document.msExitFullscreen) return document.msExitFullscreen();
}

// Map buttons to containers
const pairs = [
  { btnId: 'fullscreen-toggle',  containerId: 'requirements'  },
  { btnId: 'fullscreen-toggle1', containerId: 'requirements2' }
];

// Keep container -> its table wrapper
const containerToTable = new Map();

pairs.forEach(({ btnId, containerId }) => {
  const btn = document.getElementById(btnId);
  const container = document.getElementById(containerId);
  if (!btn || !container) return;

  // Find the table INSIDE this container
  const tableWrapper = container.querySelector('.table-responsive.cus-ovrflw.pad-b-5.jexcel-ht');
  containerToTable.set(container, tableWrapper);

  btn.addEventListener('click', () => {
    const currentFS =
      document.fullscreenElement ||
      document.webkitFullscreenElement ||
      document.msFullscreenElement;

    if (currentFS === container) {
      exitFS();
    } else {
      requestFS(container);
    }
  });
});

document.addEventListener('fullscreenchange', () => {
  const fsElement = document.fullscreenElement;

  if (fsElement && (fsElement.id === 'requirements' || fsElement.id === 'requirements2')) {
    fsElement.style.padding = '20px';
  } else {
    pairs.forEach(({ containerId }) => {
      const container = document.getElementById(containerId);
      if (container) container.style.padding = '0'; // Reset when exiting fullscreen
    });
  }
});

// Single fullscreen change handler
function onFSChange() {
  const fsEl =
    document.fullscreenElement ||
    document.webkitFullscreenElement ||
    document.msFullscreenElement;

  // Reset all to default
  containerToTable.forEach(tbl => { if (tbl) tbl.style.maxHeight = '400px'; });

  // Bump only the active one
  if (fsEl && containerToTable.has(fsEl)) {
    const tbl = containerToTable.get(fsEl);
    if (tbl) tbl.style.maxHeight = '85vh';
  }
}

// Attach once (with vendor events)
['fullscreenchange', 'webkitfullscreenchange', 'MSFullscreenChange']
  .forEach(evt => document.addEventListener(evt, onFSChange));


 
  
</script>

    

