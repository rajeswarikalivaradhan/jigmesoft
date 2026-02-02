<ul class="nav nav-tabs cad d-flex">
    <li class="active"><a data-toggle="tab" href="#cad_requirement_details">CAD REQUIREMENT DETAILS</a></li>
</ul>


<div class="tab-content p-t20">
  <div id="cad_requirement_details" class="tab-pane fade in active">
    <div class="mb-3 pl-0 ml-2 text-royal-blue f-14">CAD REQUIREMENT DETAILS</div>
    <div id="cad_requirementDetails"    class="table-responsive"></div>

    <!-- Action Buttons - All aligned to the right -->
    <div class="col-12 text-right pr-3 py-3" style="gap: 10px;">

      <?php if ($loguserid == 3): ?>
        <a href="<?= base_url('request/Cadrequest/index/' . urlencode(base64_encode($VarEnqId))) ?>" 
           class="btn btn-info btn-sm">
          CAD REQUEST
        </a>
      <?php endif; ?>

      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-royal-blue pag-active text-white" style="color: white!important;" >1</button>
      <span class="mar-lr-10 bf-af-pg" >...</span>
      &nbsp;&nbsp;&nbsp;<a class="btn btn-sm btn-royal-blue sample btnNext">
        Next <i class="fa fa-arrow-right ml-1"></i>
      </a>

      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-info btn-sm ml-3" id="oe_submitCADRequirement">SAVE</button>

    </div>
  </div>
</div>
<!-- FORM DETAILS STARTS HERE -->
<div class="col-12 pr-3 py-3">
     <?php if ($loguserid == 3) { ?>
    <label class="pull-left cus-label"> Remarks </label>
    <textarea name="remarks" class="form-control custom-textarea cad_remarks" placeholder="Free Text" ></textarea>
<?php } ?>
</div>

<div class="col-12 pr-3 py-3">
    <label class="cus-label"> Attachment </label>
      <?php if ($loguserid == 3) { ?>
    <div id="cadImageUpload"></div>
    <?php } ?>
    
    <div class="row">
        <ul class="upload-list-view orderEntryImageView" style="list-style: none;">
        </ul>
    </div>
</div>

<div class="col-12 pr-3 py-3 text-right">
    <button class="btn btn-info mar-l-5rem" id="cad_orderEntryFormSubmit">SAVE</button>
</div>
<!-- FORM DETAILS ENDS HERE -->

<!-- COMMON TABLES STARTS HERE -->

<div class="card border-0 mb-5">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">CAD REQUEST DETAILS</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="cad_requestDetaiLSls" class="col-12 p-0"></div>
        </div>
    </div>
    <!-- <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-sm mar-l-5rem pull-right">SAVE</button>
    </div> -->
</div>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">CAD Q.A. AUDIT  & JOB COMPLETION STATUS</div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="cad_qaAudit" class="col-12 p-0"></div>
        </div>
    </div>
    <!-- <div class="card-footer clearfix bgc-white border-0 p-3">
        <button class="btn btn-sm mar-l-5rem pull-right">SAVE</button>
    </div> -->
</div>

<!-- COMMON TABLES ENDS HERE -->

