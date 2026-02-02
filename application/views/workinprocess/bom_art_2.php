<ul class="nav nav-tabs order-entry d-flex">
    <li class="active"><a data-toggle="tab" href="#sampling_approval_details2">SAMPLING & APPROVAL DETAILS</a></li>
    <li><a data-toggle="tab" href="#requirements2">REQUIREMENT</a></li>
    <li><a data-toggle="tab" href="#requirement_qty_consolidated2">REQUIREMENT QTY. CONSOLIDATED</a></li>
    <li><a data-toggle="tab" href="#sourcing_details2">SOURCING DETAILS</a></li>
   
</ul>



<div class="tab-content p-t20">
    <div id="sampling_approval_details2" class="tab-pane fade in active">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">SAMPLING & APPROVAL DETAILS</div>
        <div id="samplingApprovalDetails2"   class="table-responsive"        ></div>
        
        <!-- pagination -->
        <div class="col-12 text-right pr-3 py-3">
            
           <!-- <button class="btn btn-sm btn-royal-blue  px-3 pag-active">1</button>
             -->
           <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active"  style="color: white!important;">1</button>
           
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue order-entry btnNext"> 
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
             <button class="btn btn-info btn-sm mar-l-5rem" id="oe_submitBOMSampleApprovalDetails2">SAVE</button>
        </div>


        <!-- FORM DETAILS STARTS HERE -->
 <div id="samplingExtraSection" >
<div class="col-12 pr-3 py-3">
     <?php if ($loguserid == 3) { ?>
    <label class="pull-left cus-label"> Remarks </label>
    <textarea id="" style="" name="" class="form-control custom-textarea bom2_remarks" placeholder="Free Text" ></textarea>
<?php } ?>
</div>

<div class="col-12 pr-3 py-3">
    <label class="cus-label"> Attachment </label>
      <?php if ($loguserid == 3) { ?>
    <div id="bom2ImageUpload" class="pdt10"></div>
    <?php } ?>
</div>

<div class="row">
    <ul class="upload-list-view orderEntryImageView" style="list-style: none;">
    </ul>
</div>

<div class="col-12 pr-3 py-3 text-right">
    <button class="btn btn-info mar-l-5rem" id="bom2_orderEntryFormSubmit">SAVE</button>
</div>
            </div>

    </div>
    <div id="requirements2" class="tab-pane fade in"  style="background-color: #fff;">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">


         <span>REQUIREMENT</span>
    <button id="fullscreen-toggle1" style="margin-left: auto;" >
        <i class="fas fa-expand"></i> <!-- FontAwesome Icon for Fullscreen -->
    </button>

        </div>

         <div class="table-responsive cus-ovrflw pad-b-5 jexcel-ht">

            
                
                <div id="BOM2requirementDetails" class="jexcel-container"></div>
            </div>
      
        <!-- pagination -->
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue order-entry btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">2</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue order-entry btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
           <button class="btn btn-info btn-sm mar-l-5rem" id="bom2RequirementSubmit">SAVE</button>

        </div>

    </div>
    <div id="requirement_qty_consolidated2" class="tab-pane fade in">     
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">REQUIREMENT QTY. CONSOLIDATED.</div>   

           <div id="bom2requirementQtyConsolidated" style="padding-right: 0; overflow-x: auto !important;" class="table-responsive"></div>
       
        <!-- pagination -->
        <div class="col-12 text-right pr-3 py-3">
           
             <?php if ($loguserid == 3) { ?>
    <?php if ($draftVal > 0) { ?>
        <!-- Show "View Draft" if $draftVal > 0 -->
        <a class="btn btn-info btn-sm ml-5" href="<?php echo base_url(); ?>request/bom2request/index/<?php echo urlencode(base64_encode($VarEnqId)) ?>">View Draft</a> 
    <?php } else { ?> 
        <!-- Show "BOM (Art - 1) REQ." if $draftVal <= 0 -->
          <a class="btn btn-info btn-sm ml-5" href="<?php echo base_url();?>request/bom2request/index/<?php echo urlencode(base64_encode($VarEnqId)) ?>">BOM (Art - 2) REQ.</a>
    <?php } ?>
<?php } ?>
             <a class="btn btn-sm btn-royal-blue order-entry btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">3</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue order-entry btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
           <button class="btn btn-info btn-sm mar-l-5rem" id="bom2RequirementConsolidatedSubmit">SAVE</button>
        </div>

    </div>
    <div id="sourcing_details2" class="tab-pane fade">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">SOURCING DETAILS</div>
         <div id="bom2_sourcingDetails" ></div>
      
        <!-- pagination -->
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue order-entry btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">4</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue order-entry btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="bom2SourceDetailsSubmit">SAVE</button>
        </div>
    </div>
    
</div>


 <!-- COMMON TABLES STARTS HERE -->
<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">BOM (Art - 2) P.I. DETAILS</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive pad-b-20" >
            <div id="bom2Request" class="col-12 p-0"></div>
        </div>
    </div>
    <!-- <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-info btn-sm mar-l-5rem pull-right">SAVE</button>
    </div> -->
</div>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">BOM (Art - 2) IN-HOUSE STATUS</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive pad-b-20">
            <div id="bom2InHouse" class="col-12 p-0"></div>
        </div>
    </div>
    <!-- <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-info btn-sm mar-l-5rem pull-right">SAVE</button>
    </div> -->
</div>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">BOM (Art - 2) ITEM ACCEPTANCE STATUS</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive pad-b-20">
            <div id="bom2ItemAcceptStatus" class="col-12 p-0"></div>
        </div>
    </div>
    <!-- <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-info btn-sm mar-l-5rem pull-right">SAVE</button>
    </div> -->
</div>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">BOM (Art - 2) IN-HOUSE CONSOLIDATE QTY.</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive pad-b-20">
            <div id="bom2InHouseConsolidated" class="col-12 p-0"></div>
        </div>
    </div>
    <!-- <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-info btn-sm mar-l-5rem pull-right">SAVE</button>
    </div> -->
</div>

<!-- COMMON TABLES ENDS HERE -->


<script>
    
    // ******************************************************************************** 
    // BOM REQUEST STARTS HERE 
    // ********************************************************************************
    let bom2_request_wise = {
        data: [],
        columns: [
            { title: 'Item Description', width: '7%', align: 'center', readOnly: true },
            { title: 'Blend (%) /\n Content / Material', width: '12%', align: 'center', readOnly: true },
            { title: 'Garment\n Size', width: '7%', align: 'center', readOnly: true },
            { title: 'Approved\n Item Code', width: '7%', align: 'center', readOnly: true },
            { title: 'Approved Item\n Colour Code', width: '7%', align: 'center', readOnly: true },
            { title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', readOnly: true },
            { title: 'UOM', width: '5%', align: 'center', readOnly: true },
            { title: 'Request\n Date & Time', width: '7%', align: 'center', readOnly: true },
            { title: 'Cutoff\n Date & Time', width: '7%', align: 'center', readOnly: true },
            { title: 'P.I. Issued Status', width: '7%', align: 'center', readOnly: true },
            { title: 'P.I. Ref. No.', width: '7%', align: 'center', readOnly: true },
            { title: 'P.I. Issued\n Date & Time', width: '7%', align: 'center', readOnly: true },
            { title: 'Expected\n Date of Delivery', width: '7%', align: 'center', readOnly: true },
            { title: 'P.I. Qty.', width: '7%', align: 'center', readOnly: true },
            { title: 'UOM', width: '5%', align: 'center', readOnly: true }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };
    var po_size_wise_vm = new Vue({
        el: '#bom2Request',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, bom2_request_wise);
            Object.assign(this, spreadsheet);
        }
    });
    // ******************************************************************************** 
    // BOM REQUEST ENDS HERE 
    // ********************************************************************************
    // ******************************************************************************** 
    // BOM IN HOUSE STARTS HERE 
    // ********************************************************************************
    let bom_2_in_house_wise = {
        data: [],
        columns: [
            { title: 'Item Description', width: 100, align: 'center', readOnly: true },
            { title: 'Blend (%) /\n Content / Material', width: 160, align: 'center', readOnly: true },
            { title: 'Garment\n Size', width: 100, align: 'center', readOnly: true },
            { title: 'Approved \n Item Code', width: 100, align: 'center', readOnly: true },
            { title: 'Approved Item \nColour Code', width: 100, align: 'center', readOnly: true },
            { title: 'Size / Dim. \n(L*W*H)', width: 100, align: 'center', readOnly: true },
            { title: 'UOM', width: 100, align: 'center', readOnly: true },
            { title: 'P.I Ref.no', width: 100, align: 'center', readOnly: true },
            { title: 'P.I Date', width: 100, align: 'center', readOnly: true },
            { title: 'D.C. No. /\n Invoice No.', width: 100, align: 'center', readOnly: true },
            { title: 'D.C. Date /\n Invoice Date.', width: 100, align: 'center', readOnly: true },
            { title: 'Received\n Qty.', width: 100, align: 'center', readOnly: true },
            { title: 'UOM', width: 100, align: 'center', readOnly: true },
            { title: 'Received\n Date & Time', width: 120, align: 'center', readOnly: true },
            { type: 'dropdown', title: 'Merchant\n Approval Status', width: 140, align: 'center', source: ['as', 'as', 'as', 'as']},
            { type: 'calendar', title: 'Merchant Approval \nDate & Time', width: 150, align: 'center', options: { format:'DD/MM/YYYY HH12:MI AM/PM', time:1 } },
            { title: 'Q.A. Status', width: 120, align: 'center', readOnly: true },
            { title: 'Q.A. Status Update\n Date & Time', width: 140, align: 'center', readOnly: true },
            { title: 'Management \nOverriding Status', width: 140, align: 'center', readOnly: true },
            { title: 'Management Status\n Update Date & Time', width: 150, align: 'center', readOnly: true }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };
    var po_size_wise_vm = new Vue({
        el: '#bom2InHouse',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, bom_2_in_house_wise);
            Object.assign(this, spreadsheet);
        }
    });
    // ******************************************************************************** 
    // BOM IN HOUSE ENDS HERE 
    // ********************************************************************************
    // ******************************************************************************** 
    // BOM IN HOUSE CONSOLIDATED STARTS HERE 
    // ********************************************************************************
    let bom_2_in_house_consolidated_wise = {
        data: [],
        columns: [
            { title: 'Item Description', width: '7%', align: 'center', readOnly: true },
            { title: 'Blend (%) /\n Content / Material', width: '12%', align: 'center', readOnly: true },
            { title: 'Garment\n Size', width: '7%', align: 'center', readOnly: true },
            { title: 'Approved\n Item Code', width: '7%', align: 'center', readOnly: true },
            { title: 'Approved Item\n Colour Code', width: '7%', align: 'center', readOnly: true },
            { title: 'Size / Dim.\n (L*W*H)', width: '7%', align: 'center', readOnly: true },
            { title: 'UOM', width: '5%', align: 'center', readOnly: true },
            { title: 'Item In-house Status', width: '7%', align: 'center', readOnly: true },
            { title: 'Item In-house\n Date & Time', width: '8%', align: 'center', readOnly: true },
            { title: 'Q.A. Status', width: '7%', align: 'center', readOnly: true },
            { title: 'Q.A. Status Update \n Date & Time', width: '10%', align: 'center', readOnly: true },
            { title: 'P.I. Qty.', width: '7%', align: 'center', readOnly: true },
            { title: 'UOM', width: '7%', align: 'center', readOnly: true },
            { title: 'Received Qty.', width: '7%', align: 'center', readOnly: true },
            { title: 'UOM', width: '5%', align: 'center', readOnly: true },
            { title: 'BOM Store -\n Item RTI Status', width: '8%', align: 'center', readOnly: true }
        ],
        minDimensions: [4, 3],
        allowDeleteColumn: false,
        allowInsertRow: false,
        allowInsertColumn: false,
    };
    var po_size_consolidated_wise_vm = new Vue({
        el: '#bom2InHouseConsolidated',
        mounted: function () {
            let spreadsheet = jexcel(this.$el, bom_2_in_house_consolidated_wise);
            Object.assign(this, spreadsheet);
        }
    });


    
    // ******************************************************************************** 
    // BOM IN HOUSE CONSOLIDATED ENDS HERE 
    // ********************************************************************************
</script>
<!-- FORM DETAILS ENDS HERE -->