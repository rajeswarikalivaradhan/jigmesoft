<ul class="nav nav-tabs order-entry d-flex">
    <li class="active"><a data-toggle="tab" href="#combo_colour">Combo / Colour Wise Qty. Break-up</a></li>
    <li><a data-toggle="tab" href="#po_size_wise">P.O. & Size Wise Qty. Break-up</a></li>
    <li><a data-toggle="tab" href="#component_intake">Component Intake Wise Itemized Qty.</a></li>
    <li><a data-toggle="tab" href="#po_wise_delivery">P.O. Wise Delivery Schedule</a></li>
    <li><a data-toggle="tab" href="#complete_garment">Complete Garment Process Flow</a></li>
</ul>

<div class="tab-content p-t20">
    <div id="combo_colour" class="tab-pane fade in active">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">Combo / Colour Wise Qty. Break-up</div>
        <div id="comboColourSizeSheet" class="table-responsive"></div>
        <!-- pagination -->
        <div class="col-12 text-right pr-3 py-3">
            
           <!-- <button class="btn btn-sm btn-royal-blue  px-3 pag-active">1</button>
             -->
           <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active"  style="color: white!important;">1</button>
           
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue order-entry btnNext"> 
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="oe_submitColourCombo">SAVE</button>
        </div>
         <!-- FORM DETAILS STARTS HERE -->
<div class="col-12 pr-3 py-3">
     <?php if ($loguserid == 3) { ?>
   <label class="pull-left cus-label"> Remarks </label>
    <textarea name="remarks" class="form-control custom-textarea remarks" placeholder="Free Text" ></textarea>
<?php } ?>
   
</div>

<div class="col-12 pr-3 py-3">
    <label class="cus-label"> Attachment </label>
    <?php if ($loguserid == 3) { ?>
  <div id="orderentryImageUpload"></div>
<?php } ?>
   
    
    <div class="row">
        <ul class="upload-list-view orderEntryImageView" style="list-style: none;">
        </ul>
    </div>
</div>

<div class="col-12 pr-3 py-3 text-right">
    <button class="btn btn-info mar-l-5rem" id="orderEntryFormSubmit">SAVE</button>
</div>
<!-- FORM DETAILS ENDS HERE -->

       

    </div>
    <div id="po_size_wise" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">P.O. & Size Wise Qty. Break-up</div>
        <div id="poSizeWiseSheet" class="table-responsive"></div>
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
            <button class="btn btn-info btn-sm mar-l-5rem" id="oe_submitPOSize">SAVE</button>
        </div>

    </div>
    <div id="component_intake" class="tab-pane fade">     
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">Component Intake Wise Itemized Qty.</div>   
        <div id="oe_component_intake_wise" class="table-responsive"></div>
        <!-- pagination -->
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue order-entry btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">3</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue order-entry btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="oe_submit_component_intake">SAVE</button>
        </div>

    </div>
    <div id="po_wise_delivery" class="tab-pane fade">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">P.O. Wise Delivery Schedule</div>
        <div id="poWiseDelivery"  class="table-responsive"></div>
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
            <button class="btn btn-info btn-sm mar-l-5rem" id="oe_submitPOWiseDelivery">SAVE</button>
        </div>
    </div>
    <div id="complete_garment" class="tab-pane fade">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">Complete Garment Process Flow</div>
        <div id="oe_complete_process" class="table-responsive"></div>
        <!-- pagination -->
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue order-entry btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">5</button>
            <button class="btn btn-info btn-sm mar-l-5rem" id="oe_submitCompleteProcess">SAVE</button>
        </div>

    </div>
</div>

