<ul class="nav nav-tabs fabric d-flex">
    <li class="-none active"><a data-toggle="tab" href="#color_wise_garment_parts_details">Garment Parts & Ex. Qty. Entry</a></li>
    <li class="-none"><a data-toggle="tab" href="#garment_parts_wise_qty_details">Itemized Qty. * Ex. Qty. (%)</a></li>
    <li class="-none"><a data-toggle="tab" href="#size_wise_garment_parts_details">Piece Weight Entry</a></li>
    <li class="-none"><a data-toggle="tab" href="#fabric_consumption_calc_details">FCC - (Qty. * Piece Weight)</a></li>
    <li class="-none"><a data-toggle="tab" href="#fabric_process_loss_details">FCC - (Qty. * Piece Weight * Process Loss)</a></li>
    <li class="-none"><a data-toggle="tab" href="#fabric_size_spec_code_details">FCC - (Cumulative)</a></li>
    <li class="-none"><a data-toggle="tab" href="#fabric_size_finishdata_dimension">Reqd. Finishing DIA / DIM</a></li>
    <li class="-none"><a data-toggle="tab" href="#itemized_fabric_requirement_details">Fabric Requirement Details</a></li>
</ul>

<div class="tab-content p-t20">
    <div id="color_wise_garment_parts_details" class="tab-pane fade in active">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">COLOUR WISE GARMENT PARTS & EXCESS QTY. ENTRY</div>
        <div id="color_wise_garment_parts"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <button class="btn btn-sm btn-royal-blue px-3 pag-active mar-l-5rem" style="color: white!important;">1</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue fabric btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="color_wise_garment_parts_btn">SAVE</button>
        </div>

    </div>
    <div id="garment_parts_wise_qty_details" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">GARMRENT PARTS WISE QTY. DETAILS - (ITEMIZED QTY. * EXCESS QTY. %)</div>
        <div id="garment_parts_wise_qty"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue fabric btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">2</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue fabric btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
        </div>

    </div>
    <div id="size_wise_garment_parts_details" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">SIZE & GARMENT PARTS WISE PIECE WEIGHT PER UNIT ENTRY</div>
        <div id="size_wise_garment_parts"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue fabric btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">3</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue fabric btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="size_wise_germent_parts_btn">SAVE</button>
        </div>

    </div>
    <div id="fabric_consumption_calc_details" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">FABRIC CONSUMPTION CALCULATION - (QTY. * PIECE WEIGHT)</div>
        <div id="fabric_consumption_calc"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue fabric btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">4</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue fabric btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="fabric_consumption_calc_btn">SAVE</button>
        </div>

    </div>
    <div id="fabric_process_loss_details" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">FABRIC CONSUMPTION CALCULATION - (QTY. * PIECE WEIGHT * PROCESSING LOSS)</div>
        <div id="fabric_process_loss"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue fabric btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">5</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue fabric btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
        </div>

    </div>
    <div id="fabric_size_spec_code_details" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">FABRIC CONSUMPTION CALCULATION - (SIZE SPEC CODE WISE CUMULATIVE)</div>
        <div id="fabric_size_spec_code"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue fabric btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">6</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue fabric btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <!-- <button class="btn btn-info btn-sm mar-l-5rem" id="fabric_size_spec_code_btn">SAVE</button> -->
        </div>

    </div>
    
    <div id="fabric_size_finishdata_dimension" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">SIZE WISE REQUIRED FINISHING DIA / DIMENSION</div>
        <div id="fabric_sizewise_dia_dimension"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <a class="btn btn-sm btn-royal-blue fabric btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">7</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue fabric btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="fabric_sizewise_dia_dimension_sve_btn">SAVE</button>
        </div>
    </div>

    <div id="itemized_fabric_requirement_details" class="tab-pane fade in">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14 txt-upcs">ITEMIZED FABRIC REQUIREMENT DETAILS - (COLOUR, FABRIC & DIA WISE)</div>
        <div id="itemized_fabric_requirement"></div>
        
        <div class="col-12 text-right pr-3 py-3">
            <a href="<?php echo base_url(); ?>request/fabricrequest/index/<?php echo urlencode(base64_encode($VarEnqId)); ?>" class="btn btn-info btn-sm ml-5">Fabric Req.</a>
            <a class="btn btn-sm btn-royal-blue fabric btnPrevious">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
            </a>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <button class="btn btn-sm btn-royal-blue px-3 pag-active" style="color: white!important;">8</button>
            <span class="mar-lr-10 bf-af-pg">...</span>
            <a class="btn btn-sm btn-royal-blue fabric btnNext">
                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
            </a>
            <button class="btn btn-info btn-sm mar-l-5rem" id="itemized_fabric_requirement_btn">SAVE</button>
        </div>
        
    </div>
</div>