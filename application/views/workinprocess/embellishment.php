<ul class="nav nav-tabs embellishment d-flex">
    <li class="active"><a data-toggle="tab" href="#embellishment_details">EMBELLISHMENT DETAILS</a></li>
    <!-- <li><a data-toggle="tab" href="#embellishment_status_details">EMBELLISHMENT APPROVAL DETAILS</a></li> -->
    <!-- <li><a data-toggle="tab" href="#embellishment_vendor_details">EMBELLISHMENT VENDOR DETAILS</a></li> -->
</ul>

<div class="tab-content p-t20 ">
    <div id="embellishment_details" class="tab-pane fade in active">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">EMBELLISHMENT DETAILS</div>
        <div id="embellishmentDetails" class="table-responsive"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <!-- <button class="btn btn-sm btn-royal-blue px-3 pag-active">1</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue embellishment btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a> -->
            <button class="btn btn-info btn-sm mar-l-5rem" id="oe_submitEmbellishmentDetails">SAVE</button>
        </div>

    </div>
    <!-- <div id="embellishment_status_details" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">EMBELLISHMENT APPROVAL DETAILS</div>
        <div id="embellishmentStatusDetails"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue embellishment btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active">2</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue embellishment btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="oe_submitEmbellishmentStatusDetails">SAVE</button>
        </div>

    </div> -->
    <!-- <div id="embellishment_vendor_details" class="tab-pane fade">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">EMBELLISHMENT VENDOR DETAILS</div>
        <div id="embellishmentVendorDetails"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue embellishment btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active">3</button>
            <button class="btn btn-info btn-sm mar-l-5rem" id="oe_submitEmbellishmentVendorDetails">SAVE</button>
        </div>

    </div> -->
</div>

<!-- COMMON TABLES STARTS HERE -->

<div class="card border-0 mb-2">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">EMBELLISHMENT VENDOR DETAILS</div>
    </div>
    <div class="card-body border-0 collapse show">
      
            <div id="embellishmentVendorDetails" class="table-responsive" class="col-12 p-20"></div>
       
    </div>
    <div class="card-footer clearfix bgc-white border-0  pr-3 py-3">
        <button class="btn btn-info btn-sm mar-l-5rem pull-right" id="oe_submitEmbellishmentVendorDetails">SAVE</button>
    </div>
</div>

<!-- COMMON TABLES ENDS HERE -->

<!-- FORM DETAILS STARTS HERE -->
<div class="col-12 pr-3 py-3">
      <?php if ($loguserid == 3) { ?>
    <label class="pull-left cus-label"> Remarks </label>
    <textarea id="" style="" name="" class="form-control custom-textarea  emb_remarks" placeholder="Free Text" ></textarea>
<?php } ?>
</div>

<div class="col-12 pr-3 py-3">
    <label class="cus-label"> Attachment </label>
      <?php if ($loguserid == 3) { ?>
    <div id="embellishmentImageUpload"></div>
    <?php } ?>
</div>

 <div class="row">
            <ul class="upload-list-view orderEntryImageView" style="list-style: none;">
            </ul>
</div>

<div class="col-12 pr-3 py-3 text-right">
    <!-- <button class="btn btn-info mar-l-5rem" id="embellishmentFormSubmit">SAVE</button> -->
     <button class="btn btn-info mar-l-5rem" id="emb_orderEntryFormSubmit">SAVE</button>
</div>
<!-- FORM DETAILS ENDS HERE -->