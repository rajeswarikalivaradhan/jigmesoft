<ul class="nav nav-tabs sample d-flex">
    <li class="active"><a data-toggle="tab" href="#sample_submission_details">GARMENT SAMPLE SUBMISSION DETAILS</a></li>
</ul>

<div class="tab-content p-t20">
    <div id="sample_submission_details" class="tab-pane fade in active">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">GARMENT SAMPLE SUBMISSION DETAILS</div>
        <div id="sample_submissionDetails" class="table-responsive"></div>
        
        <div class="col-12 text-right pr-3 py-3">
          <?php
    if ($loguserid == 3) {
        // Output the link if loguserid == 3
        echo '<a href="' . base_url() . 'request/Samplerequest/index/' . urlencode(base64_encode($VarEnqId)) . '" class="btn btn-info btn-sm mar-l-5rem" id="sample_req1">';
        
        // Conditional text based on $checkDraftorNot
        if ($checkDraftorNot == 0) {
            echo "SAMPLE REQUEST";
        } else {
            echo "VIEW DRAFT";
        }
        
        echo '</a>';
    }
   ?>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active mar-l-5rem" style="color: white!important;">1</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue sample btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="oe_submitSampleDetails">SAVE</button>
        </div>

        <!-- FORM DETAILS STARTS HERE -->
        <div class="col-12 pr-3 py-3">
             <?php if ($loguserid == 3) { ?>
            <label class="pull-left cus-label"> Remarks </label>
            <textarea name="remarks" class="form-control custom-textarea sample_remarks" placeholder="Free Text" ></textarea>
        <?php } ?>
        </div>

        <div class="col-12 pr-3 py-3">
            <label class="cus-label"> Attachment </label>
              <?php if ($loguserid == 3) { ?>
            <div id="samplingImageUpload"></div>
            <?php } ?>
        </div>
        
        <div class="row">
            <ul class="upload-list-view orderEntryImageView" style="list-style: none;">
            </ul>
        </div>

        <div class="col-12 pr-3 py-3 text-right">
            <button class="btn btn-info mar-l-5rem" id="sample_orderEntryFormSubmit">SAVE</button>
        </div>
        <!-- FORM DETAILS ENDS HERE -->

    </div>
</div>

<!-- COMMON TABLES STARTS HERE -->
<div class="card border-0 mb-5">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">GARMENT SAMPLE REQUEST DETAILS</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="sample_requestDetails" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <!-- <button class="btn btn-info btn-sm mar-l-5rem pull-right">SAVE</button> -->
    </div>
</div>

<div class="card border-0 mb-5">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">SAMPLE Q.A. AUDIT  & JOB COMPLETION STATUS</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="sample_qaAudit" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <!-- <button class="btn btn-info btn-sm mar-l-5rem pull-right">SAVE</button> -->
    </div>
</div>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">GARMENT SAMPLE DESPATCH & APPROVAL DETAILS</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="sample_dispatchDetails" class="col-12 p-0"></div>
        </div>
    </div>
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-info btn-sm mar-l-5rem pull-right" id="sampleDespatchApproval">SAVE</button>
    </div>
</div>

<!-- COMMON TABLES ENDS HERE -->
