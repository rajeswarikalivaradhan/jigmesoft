<script src="<?= base_url() ?>assets/ace/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
<script src="<?= base_url() ?>assets/ace/node_modules/interactjs/dist/interact.js"></script>
<script src="<?php echo base_url();?>assets/js/jexcel/numeral.min.js"></script>
<?php 
$userInfo = fnGetUserLoggedInfo('1');
$userType = $userInfo['usertype'];
?>
<style>
<?php if($ArrEnquiryInfo[0]->orderstatus==4 || $ArrEnquiryInfo[0]->orderstatus==1 || $userType==2) { ?>
 td {
        background-color: rgb(247 247 247 / 45%);
    }
<?php } ?>
    .table-responsive{
        overflow-x: unset !important;
    }
    .text-cyan-br {
        color: #055EE1;
    }
    .swal2-icon.swal2-warning{
        border-color:#FFCC00!important;
        color:#FFCC00!important;
        border:2px solid #FFCC00!important;
        width:3em!important;
        height:3em!important;
        margin:12px auto 10px!important;
    }
    .swal2-warning .swal2-icon-content{
        font-size:30px!important;
    }
    .btn-sm{
        font-size:12px!important;
    }
    /*.swal2-icon{*/
    /*    border:2px solid transparent!important;*/
    /*    width:3em!important;*/
    /*    height:3em!important;*/
    /*    margin:12px auto 10px!important;*/
    /*}*/
    /*.swal2-icon .swal2-icon-content{*/
    /*    font-size:30px!important;*/
    /*}*/
    .swal2-titles{
        color:red!important;
        font-weight:500!important;
    }
    /*.swal2-content{*/
    /*    color:black!important;*/
    /*    font-size:19px!important;*/
    /*}*/
    .swal2-popup{
        padding: 10px 0px 10px 0px!important;
    }

     .nav-tabs-faded {
    .nav-link, .btn {
         &:not(.active):not(:hover) {
             opacity:100;     
             }
     } 
    }
    
</style>
<div class="loading" id="loadder" style="display:none;"></div>
<div class="card-header pt-2 pb-3 pl-0 bgc-white border-0 " style="">
    <div class="card-title f-20">
        <b style="font-size: 20px;color: #333"><?php echo ($requestFor == 1) ? 'Pre-Costing' : 'Budgeted Cost'; ?></b>
    </div>
    <div class="card-toolbar no-border"> 
        <ol class="breadcrumb float-sm-right p-0 pb-1 mr-2">
           
            <?php if($accessPermission != false){  ?>
            <?php if(isset($checkDraftorNot) && ($checkDraftorNot>0)){  ?>
            <li class="breadcrumb-item f-13 active"><a class="btn btn-sm btn-royal-blue btn-text-slide-x" href="<?php echo base_url('components/componentCreation') . '/' . urlencode(base64_encode($VarEnqId)) ?>">
            <i class="fa fa-circle-o"></i>View Draft Component Details</a></li>
            <?php }else{ ?>
                <div class="d-flex gap-2">
                     <?php if($userType == 3){  ?>
                      <a class="btn btn-sm btn-royal-blue btn-text-slide-x" href="<?= base_url('merchant/orderEnquiryList'); ?>">
        <i class="fa fa-circle-o"></i>&nbsp;Back
    </a>&nbsp;&nbsp;
   
                 <?php }else{ ?>
                    <a class="btn btn-sm btn-royal-blue btn-text-slide-x" href="<?= base_url('management/orderEnquiryList'); ?>">
        <i class="fa fa-circle-o"></i>&nbsp;Back
    </a>&nbsp;&nbsp; 
    
                 <?php } ?>                    
    <a class="btn btn-sm btn-royal-blue btn-text-slide-x" href="<?= base_url('components/componentCreation') . '/' . urlencode(base64_encode($VarEnqId)); ?>">
        <i class="fa fa-circle-o"></i>&nbsp;View Component Details
    </a>
    
</div>

<?php } }?>
        </ol>
    </div><!-- /.card-toolbar -->
</div>

<?php //var_dump($components);?>

 <div class="col-12 pb-3 px-0">
    <div class="col-12 px-0" style="border-top: 1px solid #022b61;"></div>
</div>
<ul class="pad-l-0-i pt-2 nav nav-tabs nav-tabs-simple nav-tabs-static nav-tabs-faded flex-nowrap radius-0 px-3 pt-2 nav-tabs-scroll is-scrollable pad-l-0-i" id="component" role="tablist11">
    <?php
    foreach ($components as $key => $component)
    { 
    ?>
        <li class="nav-item mr-1" data-id="<?= $component['component_id'] ?>" data-name="<?= $component['component_name'] ?>">
            <a class="btn btn-light-lightgrey btn-a-purple py-2 border-0 btn-sm f-13 radius-b-0 radius-t-1 <?= $key == 0 ? 'active' : '' ?>" id="component_tab_<?= $component['component_id'] ?>" data-toggle="pill"
               href="#component_tab_content_<?= $component['component_id'] ?>" role="tab" aria-controls="component_tab_content_<?= $component['component_id'] ?>"
               aria-selected="<?= $key == 0 ? 'true' : 'false' ?>">&nbsp;&nbsp;&nbsp;<?= $component['component_name'] ?>&nbsp;&nbsp;&nbsp;</a>
        </li>
       
        <?php
    }
    ?>
</ul>
<div class="tab-content bgc-white p-0 border-0" id="componentContent" style="margin-top: 3px">
    <?php
    foreach ($components as $key => $component)
    {
        ?>
        <div class="tab-pane fade <?= $key == 0 ? 'active show' : '' ?>" id="component_tab_content_<?= $component['component_id'] ?>"
             role="tabpanel" aria-labelledby="component_tab_<?= $component['component_id'] ?>">
            <ul class="nav nav-justified nav-tabs nav-tabs-static nav-tabs-faded radius-b-0 radius-t-1 overflow-hidden" id="component_sub_tab_<?= $component['component_id'] ?>" role="tablist">
                <li class="nav-item nav-item-r-border">
                    <a class="btn btn-light-lightgrey btn-a-purple border-0 radius-b-0 radius-t-1 btn-sm f-13 py-2 active" id="gar_pce_wgt_<?= $component['component_id'] ?>" data-toggle="pill"
                       href="#gar_pce_wgt_content_<?= $component['component_id'] ?>" role="tab"
                       aria-controls="gar_pce_wgt_content_<?= $component['component_id'] ?>" aria-selected="true">Gar. Pce. Wgt.</a>
                </li>
                <li class="nav-item nav-item-r-border">
                    <a class="btn btn-light-lightgrey btn-a-purple border-0 radius-b-0 radius-t-1  btn-sm f-13 py-2 " id="yarn_cost_<?= $component['component_id'] ?>" data-toggle="pill"
                       href="#yarn_cost_content_<?= $component['component_id'] ?>" role="tab"
                       aria-controls="yarn_cost_content_<?= $component['component_id'] ?>" aria-selected="false" onclick="test('<?= $component['component_id'] ?>')">Yarn Cost</a>
                </li>
                <li class="nav-item nav-item-r-border">
                    <a class="btn btn-light-lightgrey btn-a-purple border-0 radius-b-0 radius-t-1  btn-sm f-13 py-2" id="knitting_cost_<?= $component['component_id'] ?>" data-toggle="pill"
                       href="#knitting_cost_content_<?= $component['component_id'] ?>" role="tab"
                       aria-controls="knitting_cost_content_<?= $component['component_id'] ?>" aria-selected="false">Knitting Cost</a>
                </li>
                <li class="nav-item nav-item-r-border">
                    <a class="btn btn-light-lightgrey btn-a-purple border-0 rradius-b-0 radius-t-1  btn-sm f-13 py-2 " id="dyeing_cost_<?= $component['component_id'] ?>" data-toggle="pill"
                       href="#dyeing_cost_content_<?= $component['component_id'] ?>" role="tab"
                       aria-controls="dyeing_cost_content_<?= $component['component_id'] ?>" aria-selected="false" onclick="test1('<?= $component['component_id'] ?>')">
                        Dyeing Cost
                    </a>
                </li>
                <li class="nav-item nav-item-r-border">
                    <a class="btn btn-light-lightgrey btn-a-purple border-0 radius-b-0 radius-t-1  btn-sm f-13 py-2" id="emb_cost_<?= $component['component_id'] ?>" data-toggle="pill"
                       href="#emb_cost_content_<?= $component['component_id'] ?>" role="tab"
                       aria-controls="emb_cost_content_<?= $component['component_id'] ?>" aria-selected="false">Emb. Cost</a>
                </li>
                <li class="nav-item nav-item-r-border">
                    <a class="btn btn-light-lightgrey btn-a-purple border-0 radius-b-0 radius-t-1 btn-sm f-13 py-2" id="Bom_art1_cost_<?= $component['component_id'] ?>" data-toggle="pill"
                       href="#Bom_art1_cost_content_<?= $component['component_id'] ?>" role="tab"
                       aria-controls="Bom_art1_cost_content_<?= $component['component_id'] ?>" aria-selected="false">BOM (Art - 1) Cost</a>
                </li>
                <li class="nav-item nav-item-r-border">
                    <a class="btn btn-light-lightgrey btn-a-purple border-0 radius-0 btn-sm f-13 py-2" id="Bom_art2_cost_<?= $component['component_id'] ?>" data-toggle="pill"
                       href="#Bom_art2_cost_content_<?= $component['component_id'] ?>" role="tab"
                       aria-controls="Bom_art2_cost_content_<?= $component['component_id'] ?>" aria-selected="false">BOM (Art - 2) Cost</a>
                </li>
                <li class="nav-item nav-item-r-border">
                    <a class="btn btn-light-lightgrey btn-a-purple border-0 radius-0 btn-sm f-13 py-2" id="cip_cost_<?= $component['component_id'] ?>" data-toggle="pill"
                       href="#cip_cost_content_<?= $component['component_id'] ?>" role="tab"
                       aria-controls="cip_cost_content_<?= $component['component_id'] ?>" aria-selected="false">CMT & CIP Cost</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light-lightgrey btn-a-purple border-0 radius-b-0 radius-t-1  btn-sm f-13 py-2" id="other_exp_<?= $component['component_id'] ?>" data-toggle="pill"
                       href="#other_exp_content_<?= $component['component_id'] ?>" role="tab"
                       aria-controls="other_exp_content_<?= $component['component_id'] ?>" aria-selected="false">Other Expenses</a>
                </li>
            </ul>
            <div class="tab-content p-0 border-0 brc-grey-l1" id="component_sub_tab_<?= $component['component_id'] ?>_content">
                <div class="tab-pane fade active show" id="gar_pce_wgt_content_<?= $component['component_id'] ?>" role="tabpanel"
                     aria-labelledby="gar_pce_wgt_<?= $component['component_id'] ?>">
                    <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">Garment Piece Weight</div>
                    <div id="garment_parts_<?= $component['component_id'] ?>" class="col-12 p-0 w-100 garment_parts"></div>
                    <div class="col-12 text-right pr-3 py-3">
                        <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">1</button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button role="tab" aria-controls="component_tab_content_<?= $component['component_id'] ?>"
                                onclick='$("#yarn_cost_<?= $component['component_id'] ?>").trigger("click")'
                                href="#yarn_cost_content_<?= $component['component_id'] ?>" class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                        </button>
                        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                        <button class="btn btn-sm btn-royal-blue-submit access_permission mar-l-5rem" id="garment_parts_btn_<?= $component['component_id'] ?>">Save</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="yarn_cost_content_<?= $component['component_id'] ?>" role="tabpanel"
                     aria-labelledby="yarn_cost_<?= $component['component_id'] ?>">
                    <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">Yarn Cost</div>
                    <div id="yarn_cost_grid_<?= $component['component_id'] ?>" class="p-0 col-12 w-100 yarn_cost_tbl"></div>
                    <div class="col-12 text-right pr-3 py-3">
                        <button role="tab"
                                onclick='$("#gar_pce_wgt_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
                        </button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">2</button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button role="tab"
                                onclick='$("#knitting_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                        </button>
                        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                        <button class="btn btn-sm btn-royal-blue-submit access_permission mar-l-5rem" id="yarn_cost_grid_btn_<?= $component['component_id'] ?>">Save</button>
                        <?php } ?>
                    </div>

                </div>
                <div class="tab-pane fade" id="knitting_cost_content_<?= $component['component_id'] ?>" role="tabpanel"
                     aria-labelledby="knitting_cost_<?= $component['component_id'] ?>">
                    <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">Knitting Cost</div>
                    <div id="knitting_cost_grid_<?= $component['component_id'] ?>" class="col-12 p-0 w-100 knitting_cost_grid"></div>
                    <div class="col-12 text-right pr-3 py-3">
                        <button role="tab"
                                onclick='$("#yarn_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
                        </button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">3</button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button role="tab" aria-controls="component_tab_content_<?= $component['component_id'] ?>"
                                onclick='$("#dyeing_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                        </button>
                        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                        <button class="btn btn-sm btn-royal-blue-submit access_permission mar-l-5rem" id="knitting_cost_grid_btn_<?= $component['component_id'] ?>">Save</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade pr-0 pl-0" id="dyeing_cost_content_<?= $component['component_id'] ?>" role="tabpanel"
                     aria-labelledby="dyeing_cost_<?= $component['component_id'] ?>">
                    <!--<div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">DYEING COST</div>-->
                    <div id="dying_cost_grid_<?= $component['component_id'] ?>" class="p-0 w-100 dying_cost_grid_combo"></div>
                          
                    <div class="card border-0">
                        <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">Fabric Processing Details - <span style="color:grey"><?= $component['dying_type'] == 1 ? 'Solid Color' : 'Yarn Dye / Multi Color' ?></span></div>
                            
                        <div class="card-body border-0 p-0 collapse show">
                            <div id="fabric_processing_details_<?= $component['component_id'] ?>" class="p-0 fabric_processing_details"></div>
                        </div>
                         <div class="card-footer clearfix bgc-white border-0 p-3 float-right text-right">
                         <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                            <a href="javascript:void(0)" class="btn btn-sm btn-royal-blue-submit access_permission mar-l-5rem" id="fabric_processing_details_btn<?= $component['component_id'] ?>">Save</a>
                            <?php } ?>
                         </div>
                        </div>
                    
                    <div class="card border-0">
                        <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">Dyeing & Processing Cost - <span style="color:grey"><?= $component['dying_type'] == 1 ? 'Solid Color' : 'Yarn Dye / Multi Color' ?></span></div>
                       
                        <div class="card-body border-0 p-0 collapse show">
                            <div id="dying_cost_avg_grid_<?= $component['component_id'] ?>" class="p-0 dying_cost_avg_grid"></div>
                        </div>
                        <div class="card-footer clearfix bgc-white border-0 p-3 float-right text-right">
                            <button role="tab"
                                    onclick='$("#knitting_cost_<?= $component['component_id'] ?>").trigger("click")'
                                    class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
                            </button>
                            <span class="mar-lr-10 bf-af-pg">...</span>
                            <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">4</button>
                            <span class="mar-lr-10 bf-af-pg">...</span>
                            <button role="tab" aria-controls="component_tab_content_<?= $component['component_id'] ?>"
                                    onclick='$("#emb_cost_<?= $component['component_id'] ?>").trigger("click")'
                                    class="btn btn-sm btn-royal-blue btn-text-slide-x">
                                Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                            </button>
                            <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                            <a href="javascript:void(0)" class="btn btn-sm btn-royal-blue-submit access_permission mar-l-5rem" id="dying_cost_avg_grid_btn_<?= $component['component_id'] ?>">Save</a>
                            <?php } ?>
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="emb_cost_content_<?= $component['component_id'] ?>" role="tabpanel"
                     aria-labelledby="emb_cost_<?= $component['component_id'] ?>">
                    <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">Embellishment Cost</div>
                    <div class="p-0 w-100 emp_cost_grid" id="emp_cost_grid_<?= $component['component_id'] ?>"></div>
                    <div class="col-12 text-right pr-3 py-3">
                        <button role="tab"
                                onclick='$("#dyeing_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
                        </button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">5</button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button role="tab" aria-controls="component_tab_content_<?= $component['component_id'] ?>"
                                onclick='$("#Bom_art1_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                        </button>
                        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                        <button class="btn btn-sm btn-royal-blue-submit access_permission mar-l-5rem" id="emp_cost_grid_btn_<?= $component['component_id'] ?>">Save</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="Bom_art1_cost_content_<?= $component['component_id'] ?>" role="tabpanel"
                     aria-labelledby="Bom_art1_cost_<?= $component['component_id'] ?>">
                    <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">BOM (Article - 1) Cost</div>
                    <div class="col-12 w-100 p-0 bom_art_1" id="bom_art_1_<?= $component['component_id'] ?>"></div>
                    <div class="col-12 text-right pr-3 py-3">
                        <button role="tab"
                                onclick='$("#emb_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
                        </button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">6</button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button role="tab" aria-controls="component_tab_content_<?= $component['component_id'] ?>"
                                onclick='$("#Bom_art2_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                        </button>
                        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                        <button class="btn btn-sm btn-royal-blue-submit access_permission  mar-l-5rem" id="bom_art_1_btn_<?= $component['component_id'] ?>">Save</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="Bom_art2_cost_content_<?= $component['component_id'] ?>" role="tabpanel"
                     aria-labelledby="Bom_art2_cost_<?= $component['component_id'] ?>">
                    <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">BOM (Article - 2) Cost</div>
                    <div class="p-0 w-100 bom_art_2" id="bom_art_2_<?= $component['component_id'] ?>"></div>
                    <div class="col-12 text-right pr-3 py-3">
                        <button role="tab"
                                onclick='$("#Bom_art1_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
                        </button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">7</button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button role="tab" aria-controls="component_tab_content_<?= $component['component_id'] ?>"
                                onclick='$("#cip_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                        </button>
                        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                        <button class="btn btn-royal-blue-submit pull-right btn-sm access_permission mar-l-5rem" id="bom_art_2_btn_<?= $component['component_id'] ?>">Save</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="cip_cost_content_<?= $component['component_id'] ?>" role="tabpanel"
                     aria-labelledby="cip_cost_<?= $component['component_id'] ?>">
                    <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">CMT & CIP Cost</div>
                    <div class="p-0 w-100 cmt_cip_cost" id="cmt_cip_cost_<?= $component['component_id'] ?>"></div>
                    <div class="col-12 text-right pr-3 py-3">
                        <button role="tab"
                                onclick='$("#Bom_art2_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
                        </button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active af-bf">8</button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button role="tab" aria-controls="component_tab_content_<?= $component['component_id'] ?>"
                                onclick='$("#other_exp_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            Next <i class="btn-text-2 move-right fa fa-arrow-right text-120 align-text-bottom mr-2"></i>
                        </button>
                        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                        <button class="btn btn-royal-blue-submit pull-right btn-sm access_permission mar-l-5rem" id="cmt_cip_cost_btn_<?= $component['component_id'] ?>">Save</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="other_exp_content_<?= $component['component_id'] ?>" role="tabpanel"
                     aria-labelledby="other_exp_<?= $component['component_id'] ?>">
                    <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16">Other Expenses</div>
                    <div  class="p-0 w-100 other_exp_grid" id="other_exp_grid_<?= $component['component_id'] ?>"></div>
                    <div class="col-12 text-right pr-3 py-3">
                        <button role="tab"
                                onclick='$("#cip_cost_<?= $component['component_id'] ?>").trigger("click")'
                                class="btn btn-sm btn-royal-blue btn-text-slide-x">
                            <i class="btn-text-2 move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i> Previous
                        </button>
                        <span class="mar-lr-10 bf-af-pg">...</span>
                        <button class="btn btn-sm btn-royal-blue btn-text-slide-x px-3 pag-active">9</button>
                        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                        <button class="btn btn-royal-blue-submit pull-right btn-sm access_permission mar-l-5rem" id="other_exp_grid_btn_<?= $component['component_id'] ?>">Save</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">Fabric Cost Per Garment</div>
        <div class="card-tools d-none">
            <a href="#" data-action="expand" class="card-toolbar-btn text-white d-style" draggable="false">
                <i class="fa fa-expand d-n-active pr-3"></i>
                <i class="fa fa-compress d-active pr-3"></i>
            </a>
            <a href="#" data-action="reload" class="card-toolbar-btn text-white" draggable="false" onClick="fabricCostGrid(enquiry_id)">
                <i class="fas fa-sync-alt pr-3"></i>
            </a>
            <a href="#" data-action="toggle" class="card-toolbar-btn text-white" draggable="false">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="fabric_cost_grid" class="col-12 p-0"></div>
        </div>
        <!-- /.table-responsive -->
    </div>
   
    <div class="card-footer clearfix bgc-white border-0 p-3">
         <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
        <a href="javascript:void(0)" class="btn btn-sm btn-royal-blue-submit float-right access_permission" id="fabric_cost_grid_btn">Save</a>
        <?php } ?>
    </div>
    
</div>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">Actual Cost Per Garment</div>
        <div class="card-tools d-none">
            <a href="#" data-action="expand" class="card-toolbar-btn text-white d-style" draggable="false">
                <i class="fa fa-expand d-n-active pr-3"></i>
                <i class="fa fa-compress d-active pr-3"></i>
            </a>
            <a href="#" data-action="reload" class="card-toolbar-btn text-white" draggable="false" onClick="actualCostGrid(enquiry_id)">
                <i class="fas fa-sync-alt pr-3"></i>
            </a>
            <a href="#" data-action="toggle" class="card-toolbar-btn text-white" draggable="false">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="actual_cost_grid"></div>
        </div>
        <!-- /.table-responsive -->
    </div>
    
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
        <a href="javascript:void(0)" class="btn btn-sm btn-royal-blue-submit float-right access_permission" id="actual_cost_grid_btn">Save</a>
        <?php } ?>
    </div>
    
</div>


<?php if($requestFor == 1) { ?>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">Profit Percentage </div>
        <div class="card-tools d-none">
            <a href="#" data-action="expand" class="card-toolbar-btn text-white d-style" draggable="false">
                <i class="fa fa-expand d-n-active pr-3"></i>
                <i class="fa fa-compress d-active pr-3"></i>
            </a>
            <a href="#" data-action="reload" class="card-toolbar-btn text-white" draggable="false" onClick="isrCostGrid(enquiry_id)">
                <i class="fas fa-sync-alt pr-3"></i>
            </a>
            <a href="#" data-action="toggle" class="card-toolbar-btn text-white" draggable="false">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="isr_proft_per_grid"></div>
        </div>
    </div>
    
    <div class="card-footer clearfix bgc-white border-0 p-3">
        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
        <a href="javascript:void(0)" class="btn btn-sm btn-royal-blue-submit float-right access_permission" id="isr_proft_per_grid_btn">Save</a>
         <?php } ?>
    </div>
   
</div>
<?php } ?>

<?php if($requestFor == 2) {  ?>

<div class="card border-0">
    <div class="card-header p-3 border-0 bgc-white tbl-head mb-1">
        <div class="card-title text-white f-14 text-500">Budgeted Cost</div>
        <div class="card-tools d-none">
            <a href="#" data-action="expand" class="card-toolbar-btn text-white d-style" draggable="false">
                <i class="fa fa-expand d-n-active pr-3"></i>
                <i class="fa fa-compress d-active pr-3"></i>
            </a>
            <a href="#" data-action="reload" class="card-toolbar-btn text-white" draggable="false" onClick="iorCostGrid(enquiry_id)">
                <i class="fas fa-sync-alt pr-3"></i>
            </a>
            <a href="#" data-action="toggle" class="card-toolbar-btn text-white" draggable="false">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <div class="card-body border-0 p-0 collapse show">
        <div class="table-responsive">
            <div id="ior_budgeted_grid"></div>
        </div>
    </div>

    <div class="card-footer clearfix bgc-white border-0 p-3">
        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
        <a href="javascript:void(0)" class="btn btn-sm btn-royal-blue-submit float-right" id="ior_budgeted_grid_btn">Save</a>
        <?php } ?>
    </div>
    
</div>
<?php } ?>


<script src="<?= base_url() ?>assets/js/ajax.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
<script src="<?= base_url() ?>assets/js/jexcelNew/jexcel.js"></script>
<script src="<?= base_url() ?>assets/js/jexcelNew/jsuites.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jexcelNew/jspreadsheet.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jexcelNew/jsuites.css" type="text/css" />

<script>

    function goBack() {
    if (document.referrer !== "") {
      window.location.href = document.referrer;
    } else {
      window.location.href = "<?= base_url() ?>"; // fallback if no referrer
    }
  }


    var SUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        total = numeral(total).format('0.00');
//        total  = parseFloat(total).toFixed(2);
        total = (total > 0) ? total : ''
        return total;
    }

    var GPWSUMCOL = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
//       total = parseFloat(total).toFixed(3);
        total = numeral(total).format('0.000');
        total = (total > 0) ? total : ''
        return total;
    }
    
    var SUMCOL_NOFORMAT = function(instance, columnId) {
        var total = 0;
        for (var j = 0; j < instance.options.data.length; j++) {
            if (Number(instance.records[j][columnId - 1].innerHTML)) {
                total += Number(instance.records[j][columnId - 1].innerHTML);
            }
        }
        total = numeral(total).format('0');
//        total  = parseFloat(total).toFixed(2);
        total = (total > 0) ? total : ''
        return total;
    }


</script>
<script>
    $(document).ready(function() {
        
    // Your code here
});

    

    var enquiry_id = <?php echo $VarEnqId; ?>;
    var components = [];
    var preCosting = [];
    var dyingDropdown = [];
    var tabSelectionCompId = '';
    
    $(function (){
        $('#component li').each(function (i) {
            components.push({id:$(this).attr('data-id'),name:$(this).attr('data-name')});
//            preCosting.push({
//                componet_id:$(this).attr('data-id'),
//                yarnCost:[],
//            });
        });
        loadGrids();
        
    });
    var bomVue = [];
    function loadGrids(){
        $.each(components,function (index,component) {
            preCostingGrid(component.id,'garment_parts');
            preCostingGrid(component.id,'yarn_cost_grid');
            preCostingGrid(component.id,'knitting_cost_grid');
            
            preCostingGrid(component.id,'bom_art_1');
            preCostingGrid(component.id,'bom_art_2');
            preCostingGrid(component.id,'cmt_cip_cost');
            preCostingGrid(component.id,'other_exp_grid');
            
            dyeingCost(component.id);
             dyeing_fabric_process(component.id);
            dyingCostAvgGrid(component.id);
            preCostingGrid(component.id,'emp_cost_grid');
        });
    }
    
    /** Fabric Cost / Actual Cost **/
    fabricCostGrid(enquiry_id);
    actualCostGrid(enquiry_id);
    <?php if($requestFor == 1) {  ?>
    isrCostGrid(enquiry_id);
    <?php } else { ?>
    iorCostGrid(enquiry_id);
    <?php } ?>
    
    
    var swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    function preCostingGrid(component_id,grid_name)
    {
        var grid_unique_id = getGridUniqueId(grid_name);

        var weight = 0;
        //$("#loadder").show();
        // alert(1);
        MakeAsynPostRequest(base_path + "preCosting/preCostingColumns", "enquiry_id=" + enquiry_id + '&component_id='+component_id + '&grid_unique_id=' + grid_unique_id, "json", function(data) {
              
                if (data.column.length)
                {                                                            // here already they given 5 for info
                   let min_dimensions = (grid_name === "other_exp_grid") ? 4 : data.column.length;
                   let options = {
                        data: data.data,
                        <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                        editable:true,
                        <?php } else { ?>
                        editable:false,
                        <?php } ?>
                        columns: data.column,
                        minDimensions: [min_dimensions, 1],
                        allowDeleteColumn:false,
                        footers: footer(grid_name, data.column.length),
                        allowInsertRow: (grid_name == 'knitting_cost_grid') ? false : true,
                        allowInsertColumn:false,
                        updateTable: function(instance, cell, col, row, val, label, cellName) {
                                if(grid_name == 'garment_parts')
                                {
                                    var poQtyColId = data.column.length - 1;
                                    if (col === 0) 
                                    {
                                        colsVal = 0;
                                    }
                                    if (col >= 2 && col <= data.column.length - 2 && val != "") 
                                    {
                                        /** FORMAT INPUT **/
                                        var txtValue = numeral(val).format('0.000');
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        colsVal = parseFloat(colsVal) + parseFloat(txtValue);
                                    }
                                    if (col === poQtyColId) {
                                        avgCol = data.column.length - 3;
                                        colsVal = (colsVal / avgCol);
                                        colsVal = numeral(colsVal).format('0.000');
                                        colsVal  = (colsVal > 0) ? colsVal : '';
                                        $(cell).text(colsVal);
                                        instance.jexcel.options.data[row][col] = colsVal;
                                    }
                                }
                                else if(grid_name == "yarn_cost_grid")
                                {
                                    if(col === 0)
                                    {   weight = 0;
                                        if(preCosting.length)
                                        {   
                                            preCosting.forEach(function(value) {
                                               // console.log('compo'+value);
                                            if(val == value.id)
                                            {
                                                weight = value.total;
                                            }
                                        })   
                                        }
                                    }
                                    if(col === 6)
                                    {
                                        /** Format Input **/
                                        yarncost = 0;
                                        txtValue = numeral(val).format('0.00');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        yarncost = txtValue;
                                    }
                                    if(col === 7)
                                    {
                                        if(!preCosting.length)
                                        {
                                            weight = val;
                                        }
                                        $(cell).text(weight)
                                        instance.jexcel.options.data[row][col] = weight;
                                    }
                                    if(col === 8)
                                    {
                                        txtValue = numeral(val).format('0.00');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        
                                        avg_piece = 0;
                                        yarn_cost = 0;
                                        avg_piece = ((parseFloat(weight) * parseFloat(txtValue)) / 100);
                                    }
                                    if(col === 9)
                                    {
                                        avg_piece = numeral(avg_piece).format('0.000');
                                        avg_piece  = (avg_piece > 0) ? avg_piece : '';
                                        $(cell).text(avg_piece);
                                        instance.jexcel.options.data[row][col] = avg_piece;
                                    }
                                    if(col === 10)
                                    {
                                        yarn_cost = parseFloat(yarncost) * avg_piece
                                        yarn_cost = numeral(yarn_cost).format('0.00');
                                        yarn_cost  = (yarn_cost > 0) ? yarn_cost : '';
                                        $(cell).text(yarn_cost);
                                        instance.jexcel.options.data[row][col] = yarn_cost;
                                    }
                                }
                                else if (grid_name == "knitting_cost_grid")
                                {
                                    
                                    if(col === 5)
                                    {
                                        txtValue = numeral(val).format('0.000');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                    }
                                    if(col === 6)
                                    {   // console.log('knitting'+val);
                                        txtValue = numeral(val).format('0.00');
                                        txtValue  = (txtValue >= 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                    }
                                    
                                    if(col === 9)
                                    {   
                                        txtValue = numeral(val).format('0.00');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        kn_cost = txtValue;
                                    }
                                    if(col === 10)
                                    {   
                                        kn_cost_total = parseFloat(val) * parseFloat(kn_cost);
                                    }
                                    if(col === 11)
                                    {   
                                        kn_cost_total = numeral(kn_cost_total).format('0.00');
                                        kn_cost_total  = (kn_cost_total > 0) ? kn_cost_total : '';
                                        $(cell).text(kn_cost_total);
                                        instance.jexcel.options.data[row][col] = kn_cost_total;
                                    }
                                }
                                else if(grid_name == "cmt_cip_cost")
                                {
                                    if(col === 2)
                                    { 
                                        var txtValue = numeral(val).format('0.00');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        cost_per_operation = txtValue;
                                    }
                                    if(col === 3)
                                    {   
                                        // var txtValue = numeral(val).format('0.00');
                                        // txtValue  = (txtValue > 0) ? txtValue : '';
                                        // $(cell).text(txtValue);
                                        // instance.jexcel.options.data[row][col] = txtValue;
                                        // no_cost_operation = txtValue;

                                        cmt_cip_cost = Number($(cell).text());
                                        $(cell).text((cmt_cip_cost>0)?cmt_cip_cost.toFixed(3):'');
                                        instance.jexcel.options.data[row][col] = (cmt_cip_cost>0)?cmt_cip_cost.toFixed(3):'';
                                        no_cost_operation = cmt_cip_cost;


                                    }
                                    if(col === 4)
                                    {   
                                        cost_per_gm = parseFloat(cost_per_operation) * parseFloat(no_cost_operation);
                                        cost_per_gm = numeral(cost_per_gm).format('0.00');
                                        cost_per_gm  = (cost_per_gm > 0) ? cost_per_gm : '';
                                        $(cell).text(cost_per_gm);
                                        instance.jexcel.options.data[row][col] = cost_per_gm;
                                    }
                                }
                                else if(grid_name === "other_exp_grid")
                                {
                                    if(col === 1)
                                    {  
                                        txtValue = numeral(val).format('0.00');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        total_cost = txtValue;
                                    }
                                    if(col === 2)
                                    {   
                                        txtValue = numeral(val).format('0');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        order_cost = txtValue;
                                    }
                                    
                                    if(col === 4)
                                    {   
                                        txtValue = numeral(val).format('0');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        no_comps = txtValue;
                                    }
                                    
                                    if(col === 5)
                                    {   
                                        avg_cost_gm = parseFloat(total_cost) / (parseFloat(order_cost) * parseFloat(no_comps));
                                        avg_cost_gm = numeral(avg_cost_gm).format('0.00');
                                        avg_cost_gm  = (avg_cost_gm > 0) ? avg_cost_gm : '';
                                        $(cell).text(avg_cost_gm);
                                        instance.jexcel.options.data[row][col] = avg_cost_gm;
                                    }
                                }
                                else if(grid_name === "bom_art_1" || grid_name === "bom_art_2")
                                {
                                    if(col === 1)
                                    {   
                                        BOMIntake = Number($(cell).text());
                                        $(cell).text((BOMIntake>0)?BOMIntake.toFixed(3):'');
                                        instance.jexcel.options.data[row][col] = (BOMIntake>0)?BOMIntake.toFixed(3):'';
                                        intake_qty = BOMIntake
                                    }
                                    if(col === 3)
                                    {   
                                        txtValue = numeral(val).format('0.00');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        bom_cost = txtValue;
                                    }
                                    if(col === 4)
                                    {   
                                        bom_cost_gm = parseFloat(intake_qty) * parseFloat(bom_cost);
                                        bom_cost_gm = numeral(bom_cost_gm).format('0.00')
                                        bom_cost_gm  = (bom_cost_gm > 0) ? bom_cost_gm : '';
                                        $(cell).text(bom_cost_gm);
                                        instance.jexcel.options.data[row][col] = bom_cost_gm;
                                    }
                                }
                                else if(grid_name === "emp_cost_grid")
                                {
                                     //var poQtyColId = data.column.length - 1;
                                    // if(col === 0)
                                    // {   
                                    //     emp_cost = '';
                                    //     emp_order_qty = '';
                                    // }
                                    if(col === 6)
                                    {
                                        txtValue = numeral(val).format('0.00');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                        instance.jexcel.options.data[row][col] = txtValue;
                                        emp_cost = txtValue;
                                    }
                                    if(col === 7)
                                    {
                                        txtValue = numeral(val).format('0');
                                        txtValue  = (txtValue > 0) ? txtValue : '';
                                        $(cell).text(txtValue);
                                         instance.jexcel.options.data[row][col] = txtValue;
                                        emp_order_qty = val;
                                    }
                                    if(col === 8)
                                    {
                                        total_emp_cost = parseFloat(emp_cost) * parseFloat(emp_order_qty);
                                        total_emp_cost = numeral(total_emp_cost).format('0.00');
                                        total_emp_cost  = (total_emp_cost > 0) ? total_emp_cost : '';
                                        $(cell).text(total_emp_cost);
                                        instance.jexcel.options.data[row][col] = total_emp_cost;
                                    }
                                    
                                }
               
                        },
                        ondeleterow: function(instance) {
                            console.log("exactly from ");
                            console.log('The table ' + $(instance).prop('id') + ' is blur');
                            console.log(instance.jexcel.options.data);

                        },
                        onbeforedeleterow : function(instance,rowNo,colNo) {
                            console.log('The table ' + $(instance).prop('id') + ' changes before ');
                            console.log(instance.jexcel.options.data[rowNo]);
                            console.log(instance.jexcel.options.data);
                        },
                        
                        //  oninsertrow : function(instance, cell, col, row, val, label, cellName) {
                        // //      if(grid_name === "other_exp_grid"){
                        // //   // console.log(instance.jexcel.getRowData([1])); 
                          
                        // //      }
                        // },   

                    }
                    
                    let k = new Vue({
                        el: '#'+ grid_name + '_' + component_id,
                        mounted: function() {
                            let spreadsheet = jspreadsheet(this.$el, options);
                            Object.assign(this, spreadsheet);
                        },
                        methods: {
                            deletedRow : function(instance) {
                                console.log('The table ' + $(instance).prop('id') + ' is blur');
                                console.log("Deleted   ");
                                console.log(instance);

                            },
                            submitData: function() {
                                let data = this.getData();
                                MakeAsynPostRequest(base_path + "preCosting/preCostingUpdate", "enquiry_id=" + enquiry_id + "&object=" + JSON.stringify(data) + "&component_id=" + component_id + "&grid_unique_id=" + grid_unique_id, "json", function(data) {
                                        swalWithBootstrapButtons.fire({
                                            title: 'Saved!',
                                            type: 'success',
                                            icon: 'success',
                                            width:460,
                                            customClass: {'confirmButton': 'btn btn-info'}
                                            
                                        }).then(function(result) {
                                        
                                        if(grid_name == "garment_parts" || grid_name == "yarn_cost_grid" || grid_name == "knitting_cost_grid"){
                                            fabricCostGrid(enquiry_id) 
                                        }else if(grid_name === "emp_cost_grid" || grid_name == 'bom_art_1' || grid_name == 'bom_art_2' || grid_name == 'cmt_cip_cost' || grid_name == 'other_exp_grid'){
                                            actualCostGrid(enquiry_id)
                                        }
                                        if(grid_name == "garment_parts")
                                        {
                                            preCosting = [];
                                        }
                                        reLoadGrid(component_id, grid_name);
                                       
                                        });
                                        
                                        
                                },grid_name + '_' + component_id);
                            },
                        }
                    });
                      let unsavedChanges = 0;
                     $('#' + 'yarn_cost_'+ component_id).click(function (){
                           // alert("yarn_cost_content");
                              test(component_id);
                     
                    let validate = 0;
                    let data = k.getData();

                   
                    if(grid_name == "garment_parts"){
                        //alert('Please save garment parts first');
                         //console.log(data);
                         validate = validateFiled('garment_parts', data);
                          empvalidate=validateEmpGridColumn('garment_parts', data); 
                           //console.log(data.length);
                         
                        if(empvalidate==0 && validate==5)
                        {
                            
                        }
                        else if(empvalidate==0 && validate==0){
                            
                        

                        }
                        else{
                            alert('Please fill and  save garment parts first');
                            return false;
                        }
                    }
                                    
                            

                    });
                    
                    $('#' + grid_name + '_btn_'+ component_id).click(function (){
                        let validate = 0;
                        let data = k.getData();
                        //console.log('yarn'+data);
                        validate = validateFiled(grid_name, data);
                        empvalidate=validateEmpGridColumn(grid_name, data);
                        //alert(empvalidate);
                        if(validate == 0 && empvalidate==0)
                        {
                            if(grid_name=='yarn_cost_grid'){ // to validate minimum selection og garment parts
                            let precheck=[];
                            let check=[];
                            for(i=0;i<preCosting.length;i++){ // garment parts id value in seperate array
                               
                              check[i]=preCosting[i]['id'];
                           
                            }
                            for(i=0;i<data.length;i++){ // garment parts id value in seperate array from data array of zero th column
                              if(data[i][0]){
                                  precheck[i]=data[i][0];
                              }
                            }
                            const containAll = check.every(element => { // checking presence of data morethan once from one array to another
                              return precheck.indexOf(element) !== -1;
                            });
                            // console.log(containAll);
                            if(containAll==false){
                                swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                html: "Please fill the missing garment parts.<br>Each garment parts must be selected at least once.",
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles'
                                }
                            })
                            return false;
                            }
                            }
                            
                            swalWithBootstrapButtons.fire(
                                {
                                   // title: 'Do you want to save the '+(getGridNameId(grid_name))+' details ?',
                                   title: 'Do you want to proceed with saving these details ?',
                                    type: 'warning',
                                    showCancelButton: true,
                                    scrollbarPadding: false,
                                    confirmButtonText: 'Yes',
                                    cancelButtonText: 'No',
                                    reverseButtons: true,
                                    customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                                }
                            ).then(function(result) {
                                if (result.value) {
                                    let unsavedChanges = 1;
                                    k.submitData();
                                    const $activePanel = $('.tab-pane.active.show[role="tabpanel"]');
                                    const $tabList = $activePanel.find('ul[role="tablist"]');
                                    const $activeTab = $tabList.find('[role="tab"].active');
                                   if ($activeTab.attr('id')?.startsWith('dyeing_cost_')) {
                                   return; // ❌ Stop here — don't move to the next tab
                                    }
                                    const $nextTab = $activeTab.closest('li').next().find('[role="tab"]');
                                    
                                 if ($nextTab.length) {
                                 setTimeout(function () {
            test(component_id);
            $nextTab.tab('show');
        }, 2000);
    } else {
        console.log("No more tabs");
    }
                                } 
                                //commented by me on 20/03/23
                                else if (result.dismiss === Swal.DismissReason.cancel) {
                                
                                }
                            });
                            //k.submitData();
                        }
                        else {
                            swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                text: "Please fill all the fields to continue.",
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles'
                                }
                            })
                        }

                        });
                    //bomVue[component][art_id]
                   // bomVue.push({component:component,art_id:art_id,vueObj:k})

                }
            },grid_name + '_' + component_id);
        
        // setTimeout(() => {
        //     $("#loadder").hide();
        // }, 1500);
        
    }
    
    
    function dyeingCost(component_id)
    {
        let grid_unique_id = 4;
        let accessPermission = '<?php echo $accessPermission; ?>';
      //  $("#loadder").show();
         MakeAsynPostRequest(base_path + "preCosting/preCostingColumns", "enquiry_id=" + enquiry_id + "&component_id=" + component_id + "&grid_unique_id=" + grid_unique_id, "json", function(gridData) {
             
             let data = [];
             let updatedRow = '';
             let blend_val = "";
             let content_val = "";
             let count_val = "";
             let changeRender = false; 
             
             gridData.forEach(function(value) { 
                data = value;
                let combo_id = data.combo_id;
                let combo_name = data.combo_name;
                dyingDropdown[component_id] = data.allDropDownData;
                
                let combo_div_id = 'dying_cost_grid' + '_' + component_id+ '_combo_' + combo_id;
                $('#'+combo_div_id).html(""); //newly included for garment data not loaded automatically in this grid issue
                let comboHtml = ''
                
                if($('#'+combo_div_id).length===0){
                    comboHtml = '\
                    <div class="p-0 card border-0">\
                        <div class="mt-3 mb-2 pl-0 ml-2 text-royal-blue f-16 d-flex">Dyeing Cost <div class="page-info text-cyan-br text-nowrap pl-3">-&nbsp;&nbsp;<span style="color:grey"> '+ combo_name +'</span></div> </div>\
                        <div class="card-body border-0 bg-white p-0 collapse show">\
                         <div class="p-0" id="'+combo_div_id+'"></div>\
                      </div>\
                      <div class="card-footer clearfix bgc-white border-0 p-3">\
                      <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                      <button type="button" class="btn btn-sm btn-royal-blue-submit float-right access_permission" id="'+combo_div_id+'_btn">\
                         Save\
                      </button>\
                      <?php } ?>
                    </div></div>';
                    $('#dying_cost_grid' + '_' + component_id).append(comboHtml);
                }
                
                let  garmentpartdata = data.garment_parts;
               
                let options = {
                    data: data.data,
                    //columns: data.column,
                    <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                    editable:true,
                    <?php } else { ?>
                    editable:false,
                    <?php } ?>
                    columns:[
                        { title:'Color', width:'8%',align:'center'},
                        { type:'dropdown',title:'Garment Parts', width:'7%',source: data.garment_parts, align:'center' },
                        { type:'dropdown',title:'Yarn\nBlend (%)', width:'8%',source: data.blend, filter: blendFilter, align:'center' },
                        { type:'dropdown',title:'Yarn\nContent', width:'10%',source: data.content, filter: contentFilter,align:'center' },
                        { type:'dropdown',title:'Yarn\nCount', width:'6%', source: data.counts, filter: countFilter,align:'center' },
                        { type:'dropdown',title:'Dyeing Special\nReq. If Any', width:'10%',source: data.sp_data,align:'center'},
                        { type:'dropdown',title:'Dyeing\nType', width:'5%', source: ['FD','YDS','YDJ','SDB','DDB']},
                        { title:'Dyeing Cost\nPer Kg. (Rs.)', width:'6%',align:'center'},
                        { title:'No.of Feeder\nPer Repeat', width:'6%'},
                        { title:'No.of Feeder\nPer Colour', width:'6%'},
                        { title:'Colour (%)', width:'5%', readOnly:true,align:'center'},
                        { title:'Parts Wise Ave.\nWgt. Per Gar. (Kg)', width:'8%',readOnly:true,align:'center'},
                        { title:'Color Wise Ave.\nWgt. Per Gar. (Kg)', width:'8%', readOnly: true,align:'center'},
                        { title:'Dyeing Cost Per\nGarment (Rs)', width:'7%', readOnly: true,align:'center'},
                        ],
                    minDimensions: [5, 1],
                    allowDeleteColumn: false,
                    footers: [['', '', '', '','','', '','','','','', 'Total : ', '=GPWSUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
                    allowInsertRow: true,
                    allowInsertColumn: false,
                    updateTable: function (instance, cell, col, row, val, label, cellName) {
                        if(col === 0)
                        {   
                            firval = '';
                            secval = '';
                            thirdval = '';
                            fourval = '';
                            garment_val = '';
                            no_feed_repeat = 0;
                            no_feed_color = 0;
                            dying_cost = 0;
                        }
                        if(col === 1)
                        {   
                            firval = val;
                        }
                        if(col === 2)
                        {   
                            secval = val;
                        }
                        if(col === 3)
                        {   
                            thirdval = val;
                        }
                        if(col === 4)
                        {   
                            fourval = val;
                        }
                        
                        dyingDropdown[component_id].forEach(function(value) {
                        if(firval == value.grament_id && secval == value.blend_id && thirdval == value.content_id && fourval == value.counts_id)
                        {
                            garment_val = value.total;
                        }
                        });
                        
                        if(col === 7)
                        {
                            txtValue = numeral(val).format('0.00');
                            //txtValue  = (txtValue > 0) ? txtValue : '';
                            $(cell).text(txtValue);
                            instance.jexcel.options.data[row][col] = txtValue;
                            dying_cost = txtValue;
                        }
                        
                        if(col === 8)
                        {
                            txtValue = numeral(val).format('0');
                            txtValue  = (txtValue > 0) ? txtValue : '';
                            $(cell).text(txtValue);
                            instance.jexcel.options.data[row][col] = txtValue;
                            no_feed_repeat = txtValue;
                        }
                        if(col === 9)
                        {
                            txtValue = numeral(val).format('0');
                            txtValue  = (txtValue > 0) ? txtValue : '';
                            $(cell).text(txtValue);
                            instance.jexcel.options.data[row][col] = txtValue;
                            no_feed_color = txtValue;
                        }
                        if(col === 10)
                        {
                            color_cal = ((parseFloat(no_feed_color) * 100) / parseFloat(no_feed_repeat))
                            color_cal  = numeral(color_cal).format('0.00');
                            color_cal  = (color_cal > 0) ? color_cal : '';
                            $(cell).text(color_cal);
                            instance.jexcel.options.data[row][col] = color_cal;
                        }
                       if(col === 11)
                        {   
                            $(cell).text(garment_val);
                            instance.jexcel.options.data[row][col] = garment_val;
                        }
                        
                       if(col === 12)
                        {   
                            com_wise_weight = (parseFloat(color_cal) * parseFloat(garment_val) / 100);
                            com_wise_weight  = numeral(com_wise_weight).format('0.000');
                            com_wise_weight  = (com_wise_weight > 0) ? com_wise_weight : '';
                            $(cell).text(com_wise_weight);
                            instance.jexcel.options.data[row][col] = com_wise_weight;
                        }
                        
                        if(col === 13)
                        {   
                            dying_cost_per_gp = parseFloat(dying_cost) * parseFloat(com_wise_weight);
                            dying_cost_per_gp  = numeral(dying_cost_per_gp).format('0.00');
                            //dying_cost_per_gp  = (dying_cost_per_gp > 0) ? dying_cost_per_gp : '';
                            $(cell).text(dying_cost_per_gp);
                            instance.jexcel.options.data[row][col] = dying_cost_per_gp;
                        }
                    }
                };

                let k = new Vue({
                    el: '#'+combo_div_id,
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                            MakeAsynPostRequest(base_path + "preCosting/updateDyingCost", "enquiry_id=" + enquiry_id + "&object=" + JSON.stringify(data) + "&component_id=" + component_id + "&combo_id=" + combo_id, "json", function (data) {                                
                                swalWithBootstrapButtons.fire({title: 'Saved!',type: 'success',icon: 'success',width:460,customClass: {'confirmButton': 'btn btn-info'}})
                                .then(function(rslt){
                                    // Reload dying cost avg grid
                                $('#dying_cost_avg_grid_'+ component_id).html("");
                                dyingCostAvgGrid(component_id);
                                fabricCostGrid(enquiry_id)
                                });
                                
                            });
                        },
                    }
                });

                $('#'+combo_div_id + '_btn').click(function (){
                    let validate = 0;
                    let data = k.getData();
                    validate = validateFields(combo_div_id,component_id,combo_id, data);
                if(validate == 0)
                {
                    ////////// to validate minimum selection og garment parts /////
                            let prechecks=[];
                            let checks=[];
                            for(i=0;i<garmentpartdata.length;i++){ // garment parts id value in seperate array
                              checks[i]=garmentpartdata[i]['id'];
                            }
                            for(i=0;i<data.length;i++){ // garment parts id value in seperate array from data array of first column
                              if(data[i][1]){
                                  prechecks[i]=data[i][1];
                              }
                            }
                             const containsAll = checks.every(element => { // checking presence of data morethan once from one array to another
                              return prechecks.indexOf(element) !== -1;
                            });
                            
                    ///////////////////////////////////////////////////

                    if(containsAll==true) {
                    swalWithBootstrapButtons.fire(
                        {
                            title: 'Do you want to proceed with saving these details ?',
                            type: 'warning',
                            showCancelButton: true,
                            scrollbarPadding: false,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            reverseButtons: true,
                            customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                        }
                    ).then(function(result) {
                        if (result.value) {
                            k.submitData();
                                 
                        } 
                        //commented by me on 20/03/23
                        else if (result.dismiss === Swal.DismissReason.cancel) {
                            
                            // commented by myself regards to retain last state
                            // $('#'+combo_div_id).html("");
                            // dyeingCost(component_id)
                            // $('#dying_cost_avg_grid_'+ component_id).html("");
                            // dyingCostAvgGrid(component_id);
                           
                        }
                    });
                    }else{
                       swalWithBootstrapButtons.fire({
                            title: 'Warning',
                            html: "Please fill the missing garment parts.<br>Each garment parts must be selected at least once.",
                            icon: 'warning',
                            width:460,
                            confirmButtonText: 'OK',
                            customClass: {
                                'confirmButton': 'btn btn-info',
                                'title':'swal2-titles'
                            }
                        }) 
                    }
                }else{
                       swalWithBootstrapButtons.fire({
                            title: 'Warning',
                            text: "Please fill all the fields to continue.",
                            icon: 'warning',
                            width:460,
                            confirmButtonText: 'OK',
                            customClass: {
                                'confirmButton': 'btn btn-info',
                                'title':'swal2-titles'
                            }
                        }) 
                    }
                });
            });
            
         });

        //  setTimeout(() => {
        //     $("#loadder").hide();
        // }, 1500);
    }

     function dyeing_fabric_process(component_id)
    {
        let grid_unique_id = '12';
        let k; 
       // $("#loadder").show();
        MakeAsynPostRequest(base_path + "preCosting/preCostingColumns", "enquiry_id=" + enquiry_id + "&component_id=" + component_id + "&grid_unique_id=" + grid_unique_id, "json", function(data) {
        //let dd=[]; let txt;
         let dd = [], desdd = [], updatedRow = '', desUpdatedRow = '', index = '', desIndex = '';
        let dyingType = data.dyingType;
        let options = {
                data: data.data,
                <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                editable:true,
                <?php } else { ?>
                editable:false,
                <?php } ?>
                columns: data.column,
                minDimensions: [3, 1],
                allowDeleteColumn: false,
                //footers: [['', '', '', '','','', '', 'Total : ', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
                //allowInsertRow: false,
                allowInsertColumn: false,

               onchange: function(instance, cell, col, row, val, label, cellName) {
                 if(col == 1) 
                 {
                    updatedRow = row;
                    txt = $(cell).text();
                    dd = data.column[1]['source'];
                    if(txt != '')
                    {
                        index = dd.findIndex(data => txt.includes( data.name ));
                        options.data[row][2] = dd[index].pcntry;
                    }
                    else
                    {
                        options.data[row][1] = '';
                        options.data[row][2] = '';
                    }
                }
                
             },
            
                updateTable: function (instance, cell, col, row, val, label, cellName) {
                   
                if(col == 1)
                {
                    val = $(cell).text();
                }
                if(col == 2) 
                {
                    if(val != '' && dd.length > 0 && row == updatedRow)
                    {
                        $(cell).text(dd[index]['pcntry']);
                    }
                    else if(val == '')
                    {
                        $(cell).text('');
                    }
                }
                    
                },
                
            };
            
              k = new Vue({
                    el: '#'+'fabric_processing_details_' + component_id,
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                              //console.log("dddddddddddddddddddddddddddddd");
                            console.log(data);
                            MakeAsynPostRequest(base_path + "preCosting/updateDyingCostAvg_fabricprocess", "enquiry_id=" + enquiry_id + "&object=" + JSON.stringify(data) + "&component_id=" + component_id + "&dyingType=" + dyingType, "json", function (data) {
                            swalWithBootstrapButtons.fire({title: 'Saved!',type: 'success',icon: 'success',width:460,customClass: {'confirmButton': 'btn btn-info'}}).
                            then(function(rslt){
                                  $('#dying_cost_avg_grid_'+ component_id).html("");
                                   dyingCostAvgGrid(component_id);
                                fabricCostGrid(enquiry_id)
                                //dyingCostAvgGrid(component.id); 
                            });
                                 
                            });
                        },
                    }
                });
                
               
        });


         $('#' + 'fabric_processing_details_btn'+ component_id).click(function (){

                    let validate = 0;
                    let data = k.getData();
                      //console.log(data);

                    validate = validationFeilds_fabric('fabric_processing_details', data);
                    

                    if(validate == 0)
                    {
                        swalWithBootstrapButtons.fire(
                            {
                                title: 'Do you want to proceed with saving these details ?',
                                type: 'warning',
                                showCancelButton: true,
                                scrollbarPadding: false,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                reverseButtons: true,
                                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                            }
                        ).then(function(result) {
                            if (result.value) {
                               
                                k.submitData();
                                   
                                   
                            } 
                            //commented by me on 20/03/23
                            else if (result.dismiss === Swal.DismissReason.cancel) {
                                
                                // commented by myself regards to retain last state 
                                //  $('#dying_cost_avg_grid_'+ component_id).html("");
                                //  dyingCostAvgGrid(component_id);
                               
                            }
                        });
                    }
                    else
                    {
                        swalWithBootstrapButtons.fire({
                            title: 'Warning',
                            text: "Please ensure all fields under 'Fabric Processing Details' are filled in and saved.",
                            icon: 'warning',
                            width:460,
                            confirmButtonText: 'OK',
                            customClass: {
                                'confirmButton': 'btn btn-info',
                                'title':'swal2-titles'
                            }
                        })
                    }
                    });
        // setTimeout(() => {
        //     $("#loadder").hide();
        // }, 1500);
    }
    
    function dyingCostAvgGrid(component_id)
    {
        let grid_unique_id = '5';
       // $("#loadder").show();
        MakeAsynPostRequest(base_path + "preCosting/preCostingColumns", "enquiry_id=" + enquiry_id + "&component_id=" + component_id + "&grid_unique_id=" + grid_unique_id, "json", function(data) {
        
        let dyingType = data.dyingType;
        let options = {
                data: data.data,
                <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                editable:true,
                <?php } else { ?>
                editable:false,
                <?php } ?>
                columns: data.column,
                minDimensions: [5, 1],
                allowDeleteColumn: false,
                footers: [['', '', '', '','','', '', 'Total : ', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']],
                allowInsertRow: false,
                allowInsertColumn: false,
                updateTable: function (instance, cell, col, row, val, label, cellName) {
                    
                    if(col === 0)
                    {
                        prop_dying_cost = '';
                        wet_process_cost = '';
                        dry_process_cost = '';
                        compact_cost = '';
                        com_wise_pce = '';
                        order_qty = '';
                    }
                    if(col === 1)
                    {
                        txtValue = numeral(val).format('0.00');
                        // txtValue  = (txtValue > 0) ? txtValue : '0.00';
                        txtValue  = (txtValue > 0) ? txtValue : '';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        prop_dying_cost = txtValue;
                    }
                    if(col === 2)
                    {
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '0.00';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        wet_process_cost = txtValue;
                    }
                    if(col === 3)
                    {
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '0.00';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        dry_process_cost = txtValue;
                    }
                    if(col === 4)
                    {
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '0.00';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        compact_cost = txtValue;
                    }
                    if(col === 5)
                    {
                        if(prop_dying_cost == '') {prop_dying_cost = "0.00";}
                        if(wet_process_cost == '') {wet_process_cost = "0.00";}
                        if(dry_process_cost == '') {dry_process_cost = "0.00";}
                        if(compact_cost == '') {compact_cost = "0.00";}
                        // console.log(compact_cost);
                        total_dying = parseFloat(prop_dying_cost) + parseFloat(wet_process_cost) + parseFloat(dry_process_cost) + parseFloat(compact_cost);
                        total_dying = numeral(total_dying).format('0.00');
                        total_dying  = (total_dying > 0) ? total_dying : '';
                        $(cell).text(total_dying);
                        instance.jexcel.options.data[row][col] = total_dying;
                    }
                    if(col === 6)
                    {
                        com_wise_pce = val;
                    }
                    if(col === 7)
                    {
                        txtValue = numeral(val).format('0');
                        txtValue  = (txtValue > 0) ? txtValue : '';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        order_qty = txtValue;
                    }
                    if(col === 8)
                    {
                        fabric = parseFloat(com_wise_pce) * parseFloat(order_qty);
                        fabric = numeral(fabric).format('0.00');
                        fabric  = (fabric > 0) ? fabric : '';
                        $(cell).text(fabric);
                        instance.jexcel.options.data[row][col] = fabric;
                    }
                    if(col === 9)
                    {
                        actual_cost = parseFloat(total_dying) * parseFloat(fabric);
                        actual_cost = numeral(actual_cost).format('0.00');
                        actual_cost  = (actual_cost > 0) ? actual_cost : '';
                        $(cell).text(actual_cost);
                        instance.jexcel.options.data[row][col] = actual_cost;
                    }
                    
                }
            };
            
             let k = new Vue({
                    el: '#'+'dying_cost_avg_grid_' + component_id,
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                            //console.log(data);
                            MakeAsynPostRequest(base_path + "preCosting/updateDyingCostAvg", "enquiry_id=" + enquiry_id + "&object=" + JSON.stringify(data) + "&component_id=" + component_id + "&dyingType=" + dyingType, "json", function (data) {
                            swalWithBootstrapButtons.fire({title: 'Saved!',type: 'success',icon: 'success',width:460,customClass: {'confirmButton': 'btn btn-info'}}).
                            then(function(rslt){fabricCostGrid(enquiry_id) });
                                 
                            });
                        },
                    }
                });
                
                $('#' + 'dying_cost_avg_grid_btn_'+ component_id).click(function (){

                    let validate = 0;
                    let data = k.getData();
                    validate = validateFiled('dying_cost_avg_grid', data);

                    if(validate == 0)
                    {
                        swalWithBootstrapButtons.fire(
                            {
                                title: 'Do you want to proceed with saving these details ?',
                                type: 'warning',
                                showCancelButton: true,
                                scrollbarPadding: false,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                reverseButtons: true,
                                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                            }
                        ).then(function(result) {
                            if (result.value) {
                                k.submitData();
                                    const $activePanel = $('.tab-pane.active.show[role="tabpanel"]');
                                    const $tabList = $activePanel.find('ul[role="tablist"]');
                                    const $activeTab = $tabList.find('[role="tab"].active');
                                    const $nextTab = $activeTab.closest('li').next().find('[role="tab"]');
                                   if ($nextTab.length) {
                                    $nextTab.tab('show');
                                    setTimeout(() => {
                                  if ($tabList.length) {
                                  $('html, body').animate({
                                    scrollTop: $("#loadder").offset().top
                                   }, 300); // smooth scroll
                                    }
                                      }, 200)
                                  } else {
                                    console.log("No more tabs");
                                   }
                                   
                            } 
                            //commented by me on 20/03/23
                            else if (result.dismiss === Swal.DismissReason.cancel) {
                                
                                // commented by myself regards to retain last state 
                                //  $('#dying_cost_avg_grid_'+ component_id).html("");
                                //  dyingCostAvgGrid(component_id);
                               
                            }
                        });
                    }
                    else
                    {
                        swalWithBootstrapButtons.fire({
                            title: 'Warning',
                            text: "Please fill all the fields to continue11.",
                            icon: 'warning',
                            width:460,
                            confirmButtonText: 'OK',
                            customClass: {
                                'confirmButton': 'btn btn-info',
                                'title':'swal2-titles'
                            }
                        })
                    }
                    });
        });
        // setTimeout(() => {
        //     $("#loadder").hide();
        // }, 1500);
    }
    
    function fabricCostGrid(enquiry_id)
    {
        $("#fabric_cost_grid").html("");
       // $("#loadder").show();
        MakeAsynPostRequest(base_path + "preCosting/getfabricCost", "enquiry_id=" + enquiry_id, "json", function(data) {
        //console.log(data);
        let options = {
                data: data.data,
                <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                editable:true,
                <?php } else { ?>
                editable:false,
                <?php } ?>
                columns: data.column,
                minDimensions: [5, 1],
                allowDeleteColumn: false,
                allowInsertRow: false,
                allowInsertColumn: false,
                updateTable: function (instance, cell, col, row, val, label, cellName) { 
                    if (col === 0) 
                    {
                        fabric_cost = 0.00;
                    }
                    if (col > 0 && col < 4 && val !="") {
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '0.00';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        fabric_cost = parseFloat(fabric_cost) + parseFloat(txtValue);
                    }
                    
                    if(col === 4)
                    {
                        fabric_cost =  roundNumber(fabric_cost,2);
                        fabric_cost = parseFloat(fabric_cost).toFixed(2);
                        $(cell).text(fabric_cost);
                        instance.jexcel.options.data[row][col] = fabric_cost;
                    }
                    if(col === 5)
                    {
                        avg_pece_weight = val;
                    }
                    if(col === 6)
                    {
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        fabric_loss = txtValue;
                        // if(fabric_loss!=''){
                        //     $("#fnSaveEnquiry").prop("disabled", false);
                        // }else{
                        //     $("#fnSaveEnquiry").prop("disabled", true); 
                        // }
                    }
                    
                    if(col === 7)
                    {
                        actual_fabric = ((parseFloat(avg_pece_weight) * parseFloat(fabric_loss)) / 100) + parseFloat(avg_pece_weight);
                        actual_fabric = numeral(actual_fabric).format('0.000');
                        //actual_fabric =  roundNumber(actual_fabric,2);
                        //actual_fabric = parseFloat(actual_fabric).toFixed(2);
                        actual_fabric = actual_fabric > 0 ? actual_fabric : '0.000'
                        $(cell).text(actual_fabric);
                        instance.jexcel.options.data[row][col] = actual_fabric;
                    }
                    if(col === 8)
                    {
                        fabric_for_grament = parseFloat(fabric_cost) *  parseFloat(actual_fabric);
                        //fabric_for_grament = roundNumber(fabric_for_grament,2);
                        fabric_for_grament = numeral(fabric_for_grament).format('0.00');
                        //fabric_for_grament = parseFloat(fabric_for_grament).toFixed(2);
                        fabric_for_grament = fabric_for_grament > 0 ? fabric_for_grament : '0.00'
                        $(cell).text(fabric_for_grament);
                        instance.jexcel.options.data[row][col] = fabric_for_grament;
                    }
                }
            };
            
             let k = new Vue({
                    el: '#fabric_cost_grid',
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                            // console.log(data);
                            MakeAsynPostRequest(base_path + "preCosting/updateFabricCost", "enquiry_id=" + enquiry_id + "&object=" + JSON.stringify(data), "json", function (data) {
                             swalWithBootstrapButtons.fire({title: 'Saved!',type: 'success',icon: 'success',width:460,customClass: {'confirmButton': 'btn btn-info'}})
                             .then(function(result){
                                 reLoadOtherGrid('fabric_cost_grid',enquiry_id);
                                 actualCostGrid(enquiry_id)
                             });
                        });
                        },
                    }
                });
                 $('#fabric_cost_grid_btn').click(function (){
                        let validate = 0;
                        let fabric_validate = 0;
                        let data = k.getData();
                       // console.log('fabric'+data);
                        validate = validateFiled('fabric_cost_grid', data);
                        fabric_validate=validategridFields('fabric_cost_grid', data);
                        //alert(fabric_validate);
                if(validate == 0 && fabric_validate=='')
                {
                    swalWithBootstrapButtons.fire(
                        {
                            title: 'Do you want to proceed with saving these details ?',
                            type: 'warning',
                            showCancelButton: true,
                            scrollbarPadding: false,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            reverseButtons: true,
                            customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                        }
                    ).then(function(result) {
                        if (result.value) {
                            k.submitData();
                        }
                        else if (result.dismiss === Swal.DismissReason.cancel) {
                            
                             // commented by myself regards to retain last state 
                             //fabricCostGrid(enquiry_id);
                             //*****************//
                             
                            // commented by me on 20/03/23
                            // swalWithBootstrapButtons.fire({
                            //     title: 'Cancelled',
                            //     type: 'error',
                            //     icon: 'error',
                            //     customClass: {'confirmButton': 'btn btn-secondary px-5'}
                                
                            // })
                            // .then(function(rlt) { console.log(rlt)
                            //     if(rlt.isConfirmed==true){
                            //         // $("#fabric_cost_grid").load();
                            //          //fabricCostGrid(enquiry_id);
                            //       // $('#fabric_cost_grid').html("");             
                            //         fabricCostGrid(enquiry_id);
                            //     }
                            // });
                        }
                    });
                }else {
                            swalWithBootstrapButtons.fire({
                                title: 'Warning',
                               // text: (fabric_validate==0)?"Please fill all the fields of Dyeing and Processing Cost in component details.":"Please fill all the fields to continue.",
                               text:(
                                         (fabric_validate == 1)
                                            ? "Please ensure all fields under 'Yarn Cost' are filled in and saved."
                                            : (
                                                (fabric_validate == 2)
                                                    ? "Please ensure all fields under 'Knitting Cost' are filled in and saved"
                                                    : (
                                                        (fabric_validate == 3)
                                                            ? "Please ensure all fields under 'Dyeing  Cost' are filled in and saved."
                                                            : "Please ensure 'Fabric Processing Loss (%)' field is filled in and saved."
                                                    )
                                              )
                                    ),
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles'
                                }
                            })
                        }
                });
                // commented by me $('#fabric_cost_grid_btn').click(function (){
                //     swalWithBootstrapButtons.fire(
                //         {
                //             title: 'Are you sure want to save the details ?',
                //             text: "If you save You won't be able to revert this!",
                //             type: 'warning',
                //             showCancelButton: true,
                //             scrollbarPadding: false,
                //             confirmButtonText: 'Yes, do it!',
                //             cancelButtonText: 'No, cancel!',
                //             reverseButtons: true,
                //             customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                //         }
                //     ).then(function(result) {
                //         if (result.value) {
                //             k.submitData();

                //             swalWithBootstrapButtons.fire({title: 'Saved!',type: 'success',icon: 'success',customClass: {'confirmButton': 'btn btn-info px-5'}});
                //         } else if (result.dismiss === Swal.DismissReason.cancel) {
                //             swalWithBootstrapButtons.fire({
                //                 title: 'Cancelled',
                //                
                //                 type: 'error',
                //                 icon: 'error',
                //                 customClass: {'confirmButton': 'btn btn-secondary px-5'}
                //             });
                //         }
                //     });
                // });
        });
        // setTimeout(() => {
        //     $("#loadder").hide();
        // }, 1500);
    }
    
    function actualCostGrid(enquiry_id)
    {
        $("#actual_cost_grid").html("");
        MakeAsynPostRequest(base_path + "preCosting/getActualCost", "enquiry_id=" + enquiry_id, "json", function(data) {
        let options = {
                data: data.data,
                <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                editable:true,
                <?php } else { ?>
                editable:false,
                <?php } ?>
                columns: data.column,
                minDimensions: [5, 1],
                allowDeleteColumn: false,
                allowInsertRow: false,
                allowInsertColumn: false,
                // footers: [['', '', '', '','','', '','','', 'Total : ', '=SUMCOL(TABLE(), COLUMN())','','','','', 'Total : ', '=SUMCOL(TABLE(), COLUMN())']],
               //commented by me footers: [['', '', '', '','','', '','','','', 'Total : ', '=SUMCOL(TABLE(), COLUMN())','','=SUMCOL(TABLE(), COLUMN())','', 'Total : ', '=SUMCOL(TABLE(), COLUMN())']],
                footers: [['', '', '', '','','', '','','','', '', '','Total : ','=SUMCOL(TABLE(), COLUMN())','', '', '=SUMCOL(TABLE(), COLUMN())']],
                updateTable: function (instance, cell, col, row, val, label, cellName) { 
                    
                    if (col === 0) 
                    {
                        sum_cost = 0;
                        overheads = 0;
                        profit = 0;
                        intake_qty = 0;
                        exch_rate_unit = 0;
                        excess_qty = 0;
                    }
                    if (col > 0 && col < 7 && val !="") {
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '0.00';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        sum_cost = parseFloat(sum_cost) + parseFloat(txtValue);
                    }
                    if(col === 7)
                    { 
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        excess_qty=txtValue;
                        //  if(txtValue!=''){
                        //     $("#fnSaveEnquiry").prop("disabled", false);
                        // }else{
                        //     $("#fnSaveEnquiry").prop("disabled", true); 
                        // }
                    }
                    if(col === 8)
                    {
                        avg_cost = (sum_cost * excess_qty) / 100 + sum_cost;
                        avg_cost = numeral(avg_cost).format('0.00');
                        //avg_cost =  roundNumber(avg_cost,2);
                        //avg_cost = parseFloat(avg_cost).toFixed(2);
                        avg_cost = avg_cost > 0 ? avg_cost : '0.00';
                        $(cell).text(avg_cost);
                        instance.jexcel.options.data[row][col] = avg_cost;
                    }
                    if(col === 9)
                    {
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        overheads = txtValue;
                        // if(overheads!=''){
                        //     $("#fnSaveEnquiry").prop("disabled", false);
                        // }else{
                        //     $("#fnSaveEnquiry").prop("disabled", true); 
                        // }
                    }
                    if(col === 10)
                    {
                        txtValue = numeral(val).format('0');
                        txtValue  = (txtValue > 0) ? txtValue : '';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        intake_qty = txtValue;
                        // if(intake_qty!=''){
                        //     $("#fnSaveEnquiry").prop("disabled", false);
                        // }else{
                        //     $("#fnSaveEnquiry").prop("disabled", true); 
                        // }
                    }

                    if(col === 11)
                    {
                        cost_plus = ((parseFloat(avg_cost) * parseFloat(overheads)) / 100) + parseFloat(avg_cost);
                        cost_plus = numeral(cost_plus).format('0.00');
                        //cost_plus =  roundNumber(cost_plus,2);
                        //cost_plus = parseFloat(cost_plus).toFixed(2);
                        cost_plus = cost_plus > 0 ? cost_plus : '0.00';
                        if(intake_qty == 0) {
                            intake_qty = 1;
                        }
                        cost_plus = cost_plus * intake_qty;
                        $(cell).text(cost_plus);
                        instance.jexcel.options.data[row][col] = cost_plus;
                        // if(cost_plus!=''){
                        //     $("#fnSaveEnquiry").prop("disabled", false);
                        // }else{
                        //     $("#fnSaveEnquiry").prop("disabled", true); 
                        // }
                    }
                    if(col === 12)
                    {
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        profit = txtValue;
                    }
                    
                    if(col === 13)
                    {
                        actual_cost_garm = ((parseFloat(cost_plus) * parseFloat(profit)) / 100) + parseFloat(cost_plus);
                        actual_cost_garm = numeral(actual_cost_garm).format('0.00');
                        //actual_cost_garm =  roundNumber(actual_cost_garm,2);
                        //actual_cost_garm = parseFloat(actual_cost_garm).toFixed(2);
                        actual_cost_garm = actual_cost_garm > 0 ? actual_cost_garm : '0.00';
                        $(cell).text(actual_cost_garm);
                        instance.jexcel.options.data[row][col] = actual_cost_garm;
                    }

                    // if(col === 13)
                    // {
                    //     txtValue = numeral(val).format('0');
                    //     txtValue  = (txtValue > 0) ? txtValue : '';
                    //     $(cell).text(txtValue);
                    //     instance.jexcel.options.data[row][col] = txtValue;
                    //     intake_qty = txtValue;
                    // }

                    if(col === 15)
                    {
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        exch_rate_unit = txtValue;
                        // if(exch_rate_unit!=''){
                        //     $("#fnSaveEnquiry").prop("disabled", false);
                        // }else{
                        //     $("#fnSaveEnquiry").prop("disabled", true); 
                        // }
                    }
                    if(col === 16)
                    {
                        // cost_in_forg_exch = (parseFloat(actual_cost_garm) * parseFloat(intake_qty)) / parseFloat(exch_rate_unit);
                        cost_in_forg_exch = parseFloat(actual_cost_garm) / parseFloat(exch_rate_unit);
                        cost_in_forg_exch = numeral(cost_in_forg_exch).format('0.00');
                        //cost_in_forg_exch =  roundNumber(cost_in_forg_exch,2);
                        //cost_in_forg_exch = parseFloat(cost_in_forg_exch).toFixed(2);
                        cost_in_forg_exch = cost_in_forg_exch > 0 ? cost_in_forg_exch : '0.00';
                        // if(profit == 0) {
                        //     profit = 0
                        // }
                        // cost_in_forg_exch = profit / exch_rate_unit;
                        $(cell).text(cost_in_forg_exch);
                        instance.jexcel.options.data[row][col] = cost_in_forg_exch;
                    }
                     
                }
            };
            
             let k = new Vue({
                    el: '#actual_cost_grid',
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                           // console.log(data);
                            MakeAsynPostRequest(base_path + "preCosting/updateActualCost", "enquiry_id=" + enquiry_id + "&object=" + JSON.stringify(data), "json", function (data) {
                            swalWithBootstrapButtons.fire({title: 'Saved!',type: 'success',icon: 'success',width:460,customClass: {'confirmButton': 'btn btn-info'}}).then(function(result){
                             <?php if($requestFor == 1) {  ?>
                                isrCostGrid(enquiry_id);
                                <?php } else { ?>
                                iorCostGrid(enquiry_id);
                                <?php } ?>
                            });
                            });
                        },
                    }
                });
                
                $('#actual_cost_grid_btn').click(function (){
                        let validate = 0;
                        let actual_cost_validate = 0;
                        let data = k.getData();
                        validate = validateFiled('actual_cost_grid', data);
                        actual_cost_validate=validategridFields('actual_cost_grid', data);
                         //alert(actual_cost_validate);
                if(validate == 0 && actual_cost_validate==''){
                    swalWithBootstrapButtons.fire(
                        {
                            title: 'Do you want to proceed with saving these details ?',
                            type: 'warning',
                            showCancelButton: true,
                            scrollbarPadding: false,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            reverseButtons: true,
                            customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                        }
                    ).then(function(result) {
                        if (result.value) {
                            k.submitData();
                        }
                        // commented by me on 20/03/23
                        else if (result.dismiss === Swal.DismissReason.cancel) {
                            // commented by myself regards to retain last state 
                            // actualCostGrid(enquiry_id)
                            
                        }
                    });
                }else {
                            swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                text: (actual_cost_validate==1)?"Please ensure all fields under 'Fabric Cost per Garment' are filled in and saved..":"Please ensure all fields under 'Actual Cost Per Garment' are filled in and saved..",
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles'
                                }
                            })
                      }
                });
        });
        
    }
    
    /** ISR COST **/
    function isrCostGrid(enquiry_id)
    {
        $("#isr_proft_per_grid").html("");
        MakeAsynPostRequest(base_path + "preCosting/getIsrCost", "enquiry_id=" + enquiry_id, "json", function(data) {
         //console.log(data+'isr');
        let options = {
                data: data.data,
                <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                editable:true,
                <?php } else { ?>
                editable:false,
                <?php } ?>
                columns: data.column,
                minDimensions: [5, 1],
                allowDeleteColumn: false,
                allowInsertRow: false,
                allowInsertColumn: false,
                updateTable: function (instance, cell, col, row, val, label, cellName) { 
                     if(col === 0)
                     {
                         //console.log(val);
                         avg_cost = val;
                     }
                     if(col === 1)
                     {
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        invoice_val = txtValue;
                        //  if(invoice_val!='' && invoice_val!='0.00'){
                        //     $("#fnSaveEnquiry").prop("disabled", false);
                        // }else{
                        //     $("#fnSaveEnquiry").prop("disabled", true); 
                        // }
                     }   
                    //  if(col === 2)
                    //  {
                    //     if($(cell).text()!=''){
                    //          $("#fnSaveEnquiry").prop("disabled", false);
                    //     }else{
                    //         $("#fnSaveEnquiry").prop("disabled", true);
                    //     }
                    //  }
                     if(col === 3 || col === 4)
                     {
                        txtValue = numeral(val).format('0.00');
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        if(col === 3)
                        {
                            exch_rate = txtValue
                            // if(exch_rate!='' && exch_rate!='0.00'){
                            // $("#fnSaveEnquiry").prop("disabled", false);
                            // }else{
                            //     $("#fnSaveEnquiry").prop("disabled", true); 
                            // }
                        }
                        else
                        {
                             agent_comm = txtValue
                            //  if(agent_comm!='' && agent_comm!='0.00'){
                            // $("#fnSaveEnquiry").prop("disabled", false);
                            // }else{
                            //     $("#fnSaveEnquiry").prop("disabled", true); 
                            // }
                        }
                     }  
                     
                     if(col === 5)
                     {
                        comm_value = ((parseFloat(invoice_val) * parseFloat(exch_rate) * parseFloat(agent_comm)) / 100);
                        comm_value = numeral(comm_value).format('0.00');
                        $(cell).text(comm_value);
                        instance.jexcel.options.data[row][col] = comm_value;
                     }
                     if(col === 6)
                     {
                        cost_plus = (parseFloat(avg_cost) + parseFloat(comm_value));
                        cost_plus = numeral(cost_plus).format('0.00');
                        $(cell).text(cost_plus);
                        instance.jexcel.options.data[row][col] = cost_plus;
                     }
                     if(col === 7)
                     {
                        expecetd_invoice = (parseFloat(invoice_val) * parseFloat(exch_rate));
                        expecetd_invoice = numeral(expecetd_invoice).format('0.00');
                        $(cell).text(expecetd_invoice);
                        instance.jexcel.options.data[row][col] = expecetd_invoice;
                     }
                     if(col === 8)
                     {
                        // expecetd_profit = (parseFloat(cost_plus) - parseFloat(expecetd_invoice));
                        expecetd_profit = (parseFloat(expecetd_invoice) - parseFloat(cost_plus));
                        expecetd_profit = numeral(expecetd_profit).format('0.00');
                        $(cell).text(expecetd_profit);
                        
                        instance.jexcel.options.data[row][col] = expecetd_profit;
                     }
                     if(col === 9)
                     {
                        expecetd_profit_per = ((parseFloat(expecetd_profit) * 100) / parseFloat(cost_plus));
                        expecetd_profit_per = numeral(expecetd_profit_per).format('0.00');
                        $(cell).text(expecetd_profit_per);
                        instance.jexcel.options.data[row][col] = expecetd_profit_per;
                     }
                }
            };
            
             let k = new Vue({
                    el: '#isr_proft_per_grid',
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                            //console.log(data);
                            MakeAsynPostRequest(base_path + "preCosting/updateIsrCost", "enquiry_id=" + enquiry_id + "&object=" + JSON.stringify(data), "json", function (data) {
                            if(data){
                            //$("#submitAuthRequest").prop("disabled", false);
                            $("#fnSaveEnquiry").prop("disabled", false);
                             $("#frmBasicMnote").prop("disabled", false);
                             $('#uploadimg').show();
                             $('#uploadimglabel').show();
                            swalWithBootstrapButtons.fire({title: 'Saved!',type: 'success',icon: 'success',width:460,customClass: {'confirmButton': 'btn btn-info'}});
                            }
                            });
                        },
                    }
                });
                
                $('#isr_proft_per_grid_btn').click(function (){
                        let validate = 0;
                        let data = k.getData();
                        validate = validateFiled('isr_proft_per_grid', data);
                if(validate == 0){
                    // $("#fnSaveEnquiry").prop("disabled", false);
                    swalWithBootstrapButtons.fire(
                        {
                            title: 'Do you want to proceed with saving these details ?',
                            type: 'warning',
                            showCancelButton: true,
                            scrollbarPadding: false,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            reverseButtons: true,
                            customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                        }
                    ).then(function(result) {
                        if (result.value) {
                            k.submitData();
                        } 
                        // commented by me on 20/03/23
                        else if (result.dismiss === Swal.DismissReason.cancel) {
                           // commented by myself regards to retain last state 
                           // isrCostGrid(enquiry_id);
                            
                        }
                    });
                }else{
                    swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                text: "Please ensure all fields under 'Actual Cost Per Garment' are filled in and saved..",
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles'
                                }
                    }) 
                }
                });
        });
        
    }
    
    
    /** IOR COST **/
    function iorCostGrid(enquiry_id)
    {
        $("#ior_budgeted_grid").html("");
        MakeAsynPostRequest(base_path + "preCosting/getIorCost", "enquiry_id=" + enquiry_id, "json", function(data) {
        
        let options = {
                data: data.data,
                <?php if($ArrEnquiryInfo[0]->orderstatus==0 || ($ArrEnquiryInfo[0]->orderstatus==3 && $userType!=2)) { ?>
                editable:true,
                <?php } else { ?>
                editable:false,
                <?php } ?>
                columns: data.column,
                minDimensions: [5, 1],
                allowDeleteColumn: false,
                allowInsertRow: false,
                allowInsertColumn: false,
                updateTable: function (instance, cell, col, row, val, label, cellName) { 

                     if(col === 0)
                     {
                         avg_cost = val;
                     }
                     if(col === 1)
                     {
                        
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        invoice_val = txtValue;
                        // if(invoice_val!='' && invoice_val!='0.00'){
                        //     $("#fnSaveEnquiry").prop("disabled", false);
                        // }else{
                        //     $("#fnSaveEnquiry").prop("disabled", true); 
                        // }
                        
                     }  
                    //  if(col === 2)
                    //  {
                    //     if($(cell).text()!=''){
                    //          $("#fnSaveEnquiry").prop("disabled", false);
                    //     }else{
                    //         $("#fnSaveEnquiry").prop("disabled", true);
                    //     }
                    //  }
                     if(col === 3 || col === 4)
                     {
                        txtValue = numeral(val).format('0.00');
                        
                        if(col === 3)
                        {   txtValue  = (txtValue > 0) ? txtValue : '';
                            $(cell).text(txtValue);
                            instance.jexcel.options.data[row][col] = txtValue;
                            exch_rate = txtValue
                            // if(exch_rate!='' && exch_rate!='0.00'){
                            //  $("#fnSaveEnquiry").prop("disabled", false);
                            // }else{
                            //     $("#fnSaveEnquiry").prop("disabled", true);
                            // }
                        }
                        else
                        {    $(cell).text(txtValue);
                             instance.jexcel.options.data[row][col] = txtValue;
                             agent_comm = txtValue
                            //  if(agent_comm!='' && agent_comm!='0.00'){
                            //  $("#fnSaveEnquiry").prop("disabled", false);
                            // }else{
                            //     $("#fnSaveEnquiry").prop("disabled", true);
                            // }
                        }
                     }  
                     
                     if(col === 5)
                     {
                        comm_value = ((parseFloat(invoice_val) * parseFloat(exch_rate)) * (agent_comm/100));
                        comm_value = numeral(comm_value).format('0.00');
                        $(cell).text(comm_value);
                        instance.jexcel.options.data[row][col] = comm_value;
                     }
                     if(col === 6)
                     {
                        cost_plus = (parseFloat(avg_cost) + parseFloat(comm_value));
                        cost_plus = numeral(cost_plus).format('0.00');
                        $(cell).text(cost_plus);
                        instance.jexcel.options.data[row][col] = cost_plus;
                     }
                     
                     if(col === 7)
                     {
                        txtValue = numeral(val).format('0.00');
                        txtValue  = (txtValue > 0) ? txtValue : '';
                        $(cell).text(txtValue);
                        instance.jexcel.options.data[row][col] = txtValue;
                        total_order = txtValue;
                        // if(total_order!=''){
                        //      $("#fnSaveEnquiry").prop("disabled", false);
                        // }else{
                        //     $("#fnSaveEnquiry").prop("disabled", true);
                        // }
                     }
                    //  if(col === 8)
                    //  {
                    //     if($(cell).text()!=''){
                    //          $("#fnSaveEnquiry").prop("disabled", false);
                    //     }else{
                    //         $("#fnSaveEnquiry").prop("disabled", true);
                    //     }
                    //  }
                     if(col === 9)
                     {
                        budget_cost = (parseFloat(cost_plus) * parseFloat(total_order));
                        budget_cost = numeral(budget_cost).format('0.00');
                        $(cell).text(budget_cost);
                        instance.jexcel.options.data[row][col] = budget_cost;
                     }
                     if(col === 10)
                     {
                        expected_total_invoice = (parseFloat(invoice_val) * parseFloat(exch_rate) * parseFloat(total_order));
                        expected_total_invoice = numeral(expected_total_invoice).format('0.00');
                        $(cell).text(expected_total_invoice);
                        instance.jexcel.options.data[row][col] = expected_total_invoice;
                     }
                     if(col === 11)
                     {
                        // expected_profit_val = (parseFloat(budget_cost) - parseFloat(expected_total_invoice));
                        expected_profit_val = (parseFloat(expected_total_invoice) - parseFloat(budget_cost));
                        expected_profit_val = numeral(expected_profit_val).format('0.00');
                        $(cell).text(expected_profit_val);
                        instance.jexcel.options.data[row][col] = expected_profit_val;
                     }
                     if(col === 12)
                     {
                        expected_profit_per = ((parseFloat(expected_profit_val) * 100) / parseFloat(budget_cost));
                        expected_profit_per = numeral(expected_profit_per).format('0.00');
                        $(cell).text(expected_profit_per);
                        instance.jexcel.options.data[row][col] = expected_profit_per;
                     }
                }
            };
            
             let k = new Vue({
                    el: '#ior_budgeted_grid',
                    mounted: function () {
                        let spreadsheet = jspreadsheet(this.$el, options);
                        Object.assign(this, spreadsheet);
                    },
                    methods: {
                        submitData: function () {
                            let data = this.getData();
                           // console.log(data);
                            MakeAsynPostRequest(base_path + "preCosting/updateIorCost", "enquiry_id=" + enquiry_id + "&object=" + JSON.stringify(data), "json", function (data) {
                            //$("#submitAuthRequest").prop("disabled", false);
                            $("#fnSaveEnquiry").prop("disabled", false);
                             $("#frmBasicMnote").prop("disabled", false);
                             $('#uploadimg').show();
                             $('#uploadimglabel').show();
                            swalWithBootstrapButtons.fire({title: 'Saved!',type: 'success',icon: 'success',width:460,customClass: {'confirmButton': 'btn btn-info'}});
                            });
                        },
                    }
                });
                
                $('#ior_budgeted_grid_btn').click(function (){
                        let validate = 0;
                        let data = k.getData();
                        validate = validateFiled('ior_budgeted_grid', data);
                         //validate = validateFiled_bugget('ior_budgeted_grid', data);
                          //budget_cost_validate=validateFiled_bugget('ior_budgeted_grid', data);
                          actual_cost_validate=validategridFields('actual_cost_grid', data);
                          //alert(actual_cost_validate);
                       
                if(validate == 0 &&  actual_cost_validate==0){
                    //   $("#fnSaveEnquiry").prop("disabled", false);
                      swalWithBootstrapButtons.fire(
                        {
                            title: 'Do you want to proceed with saving these details ?',
                            type: 'warning',
                            showCancelButton: true,
                            scrollbarPadding: false,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            reverseButtons: true,
                            customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                        }
                    ).then(function(result) {
                        if (result.value) {
                            k.submitData();
                        } 
                        //commented by me on 20/03/23
                        else if (result.dismiss === Swal.DismissReason.cancel) {
                            // commented by myself regards to retain last state 
                            // commented by myself regards to retain last state  iorCostGrid(enquiry_id);
                            
                        }
                    });
                }else{
                    swalWithBootstrapButtons.fire({
                                title: 'Warning',
                                //text: "Please fill all the fields to continue11.",
                                 text: (actual_cost_validate==1)?"Please ensure all fields under 'Actual Cost Per Garment' are filled in and saved..":"Please ensure all fields under 'Budgeted Cost' are filled in and saved..",
                                icon: 'warning',
                                width:460,
                                confirmButtonText: 'OK',
                                customClass: {
                                    'confirmButton': 'btn btn-info',
                                    'title':'swal2-titles'
                                }
                    }) 
                }
                });
        });
        
    }
    
    function reLoadOtherGrid(enquiry_id, grid_name)
    {
        if(grid_name === 'fabric_cost_grid')
        {
            fabricCostGrid(enquiry_id);
        }
      
    }
    
    /** Reload the Grid for updating the grid data in the real time **/
    function reLoadGrid(component_id, grid_name)
    {
        if(grid_name === 'garment_parts')
        {
            $('#yarn_cost_grid_'+ component_id).html("");             
            preCostingGrid(component_id,'yarn_cost_grid');
            
            $('#knitting_cost_grid_'+ component_id).html("");
            preCostingGrid(component_id,'knitting_cost_grid');
            
            $('#dying_cost_avg_grid_'+ component_id).html("");
            dyingCostAvgGrid(component_id);
        }
        else if(grid_name === 'yarn_cost_grid')
        {
            $('#knitting_cost_grid_'+ component_id).html("");
            preCostingGrid(component_id,'knitting_cost_grid');
            
            $('#dying_cost_avg_grid_'+ component_id).html("");
            dyingCostAvgGrid(component_id);
        }else if(grid_name=='knitting_cost_grid'){ // newly included for garment data not loaded automatically in this grid issue
            dyeingCost(component_id);
            $('#dying_cost_avg_grid_'+ component_id).html("");
            dyingCostAvgGrid(component_id);
            
        }
    }
    
    function getGridUniqueId(grid_name)
    {
        var id = '';
        switch(grid_name) {
            case "garment_parts":
              id = 1;
              break;
            case "yarn_cost_grid":
              id = 2;
              break;
          case "knitting_cost_grid":
              id = 3;
              break;
          case "emp_cost_grid":
              id = 7;
              break;
            case "bom_art_1":
              id = 8;
              break;
            case "bom_art_2":
              id = 9;
              break;
          case "cmt_cip_cost":
              id = 10; 
              break;
          case "other_exp_grid":
              id = 11;
              break;
          case "other_exp_grid":
              id = 12;
              break;
            default:
              id = 1;
          }
          return id;
    }

    function getGridNameId(grid_name)
    {
        var name = '';
        switch(grid_name) {
            case "garment_parts":
                name = 'Gar. Pce. Wgt';
                break;
            case "yarn_cost_grid":
                name = 'Yarn Cost';
                break;
            case "knitting_cost_grid":
                name = 'Knitting Cost';
                break;
            case "bom_art_1":
                name = 'BOM (Art - 1) Cost';
                break;
            case "bom_art_2":
                name = 'BOM (Art - 2) Cost';
                break;
            case "cmt_cip_cost":
                name = 'CMT & CIP Cost';
                break;
            case "other_exp_grid":
                name = 'Other Expenses';
                break;
            default:
                name = 1;
        }
        return name;
    }

    function footer(grid_name, columnlength)
    {
        if(grid_name == 'garment_parts')
        {
            let position = columnlength - 2;
            let jxlTableFooter = [];
            for(var i= 1; i<= position; i++)
            {
                jxlTableFooter.push('');
            }
            jxlTableFooter.push('Total : ', '=GPWSUMCOL(TABLE(), COLUMN())');
            return [jxlTableFooter];
        }
        else if(grid_name == 'yarn_cost_grid')
        {
            return [['', '', '', '','','', '','', 'Total : ', '=GPWSUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']];
        }
        else if(grid_name === 'knitting_cost_grid')
        {
             return [['', '', '', '','','', '','','', 'Total : ', '=GPWSUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']];
        }
        else if(grid_name === 'cmt_cip_cost')
        {
             return [['', '', '', 'Total : ','=SUMCOL(TABLE(), COLUMN())']];
        }
        else if(grid_name === 'other_exp_grid')
        {
             return [['', '','','', 'Total : ','=SUMCOL(TABLE(), COLUMN())']];
        }
        else if(grid_name === "bom_art_1" || grid_name === "bom_art_2")
        {
             return [['', '','', 'Total : ','=SUMCOL(TABLE(), COLUMN())']];
        }
        else if(grid_name === "emp_cost_grid")
        {
            // return [['', '', '', '','', 'Total : ', '=SUMCOL(TABLE(), COLUMN())', '=SUMCOL(TABLE(), COLUMN())']];
            return [['', '', '', '','','', ' ', 'Total:', '=SUMCOL(TABLE(), COLUMN())']];
        }
    }
    
    function successMsg(msg) {
        $.aceToaster.add({
            placement: 'tr',
            body: "<p class='p-3 mb-0 text-center'>\
                        <span class='d-inline-block text-center mb-3 py-3 px-1 border-1 brc-success radius-round'>\
                            <i class='fa fa-check fa-2x w-6 text-success-m1 mx-2px'></i>\
                        </span><br />\
                        "+msg+"\
                    </p>\
                    <button data-dismiss='toast' class='btn btn-block btn-info radius-t-0 border-0'>OK</button>",
            width: 360,
            delay: 4500,
            close: false,
            className: 'bgc-white-tp1 shadow ',

            bodyClass: 'border-0 p-0 text-dark-tp2',
            headerClass: 'd-none',
        });
    }
    
    function test(component_id)
    {



       

        $('#yarn_cost_grid_'+component_id).append(this.loading_div);
        MakeAsynPostRequest(base_path + "preCosting/getPieceWeight", "enquiry_id=" + enquiry_id +  "&component_id=" + component_id, "json", function(data) {
            $('#yarn_cost_grid_'+component_id+' > div.bs-card-loading-overlay').remove();
            preCosting = data;
         
        });

    }

//     function checkGridEmpty(component_id) {
       
//     const gridEl = document.getElementById('garment_parts_' + 1);
 
//     // Get jspreadsheet instance
//     const grid = jspreadsheet.get(gridEl);
      
//       //alert(alert);
      
//     if (!grid) {
//         alert('Grid not initialized');
        
//     }else{
//         alert('Grid initialized');
//     }

//     // Get grid data (2D array)
//     const data = grid.getData();

//     // Check if all cells are empty
//     const isGridEmpty = data.length === 0 || data.every(row =>
//         row.every(cell => cell === null || cell === undefined || cell.toString().trim() === "")
//     );

//     if (isGridEmpty) {
//         alert('Grid is empty');
//     } else {
//         alert('Grid has data');
//     }
// }

    
    function test1(component_id)
    {
        tabSelectionCompId = component_id;
        // console.log('tabSelectionCompId', tabSelectionCompId)
        // MakeAsynPostRequest(base_path + "preCosting/getDyingDropDownData", "enquiry_id=" + enquiry_id +  "&component_id=" + component_id, "json", function(data) {
        //     //$('#yarn_cost_grid_'+component_id+' > div.bs-card-loading-overlay').remove();
        //     dyingDropdown = data;
        // });
    }
    
    var blendFilter = function (instance, cell, c, r, source) {
        let firstValue = instance.jexcel.getValueFromCoords(c - 1, r);
        let blend_array = [];
        let blen_id = '';
        dyingDropdown[tabSelectionCompId].forEach(function(value) {
             if(firstValue == value.grament_id)
             {
                 if(blen_id != value.blend_id)
                 {
                     blen_id = value.blend_id;
                     blend_array.push({'id': value.blend_id, 'name': value.blend_name})
                 } 
             }
        });
        return blend_array; 
    };
    
    var contentFilter = function (instance, cell, c, r, source) {
        let secondValue = instance.jexcel.getValueFromCoords(c - 1, r);
        let firstValue = instance.jexcel.getValueFromCoords(c - 2, r);
        let content_array = [];
        let content_id = '';
        
        dyingDropdown[tabSelectionCompId].forEach(function(value) {
             if(firstValue == value.grament_id && secondValue == value.blend_id)
             {
                 if(content_id != value.content_id)
                 {
                     content_id = value.content_id;
                     content_array.push({'id': value.content_id, 'name': value.content_name})
                 }
             }
        });
        return content_array; 
    };
    
    var countFilter = function (instance, cell, c, r, source) {
        let thirdValue = instance.jexcel.getValueFromCoords(c - 1, r);
        let secondValue = instance.jexcel.getValueFromCoords(c - 2, r);
        let firstValue = instance.jexcel.getValueFromCoords(c - 3, r);
        let count_array = [];
        let count_id = '';
        
        dyingDropdown[tabSelectionCompId].forEach(function(value) {
             if(firstValue == value.grament_id && secondValue == value.blend_id && thirdValue == value.content_id)
             {
                 if(count_id != value.counts_id)
                 {
                     count_id = value.counts_id;
                     count_array.push({'id': value.counts_id, 'name': value.count_name})
                 } 
             }
        });
        return count_array; 
    };
    
    
    function roundNumber(num, scale) {
     if(!("" + num).includes("e")) {
       return +(Math.round(num + "e+" + scale)  + "e-" + scale);
     } else {
       var arr = ("" + num).split("e");
       var sig = ""
       if(+arr[1] + scale > 0) {
         sig = "+";
       }
       return +(Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
     }
   }
   
    $( document ).ready(function() {
        <?php if($accessPermission == false) { ?>
        $('.access_permission').hide();
        <?php } ?>
    });
 function validateFields(grid_name,componentid,comboid,data) {
      let validate_fields = [];
     if(grid_name == 'dying_cost_grid_'+componentid+'_combo_'+comboid) {
         validate_fields = [0,1,2,3,4,5,6,8,9];
        }
       // console.log(grid_name+componentid+'_combo_'+comboid)
        validate = validateForm(validate_fields, data);
        return validate;
 }
function validationempty(grid_name,data) {
    if(grid_name == 'other_exp_grid') {
       
        let row = 1;
        let col = 3;

        if (
            data[row] &&
            (data[row][col] === null || data[row][col] === undefined || data[row][col].toString().trim() === "")
        ) {
           alert("Cell at row " + row + ", column " + col + " is empty");
        } else {
            alert("Cell at row " + row + ", column " + col + " is not empty");
        }
    }
}
    function validateFiled(grid_name,data) {
        let validate_filed = [];
       
        if(grid_name == 'garment_parts') {
            validate_filed = [0,1];
            for (let i = 0; i < data.length; i++) {
                data[i].forEach(function (value, index) {
                    if (index >= 2 && index <= data[i].length - 2) 
                    {
                        validate_filed.push(index);
                    }
                });
            }
        }
        else if(grid_name == 'yarn_cost_grid') {
            validate_filed = [0,1,2,3,4,5,6,7,8];
        }
        else if(grid_name == 'knitting_cost_grid') {
            validate_filed = [4,5,7,8,9];
        }
        // else if(grid_name == 'emp_cost_grid') {
        //     validate_filed = [1,2,3,4,5,6];
        // }
        else if(grid_name == 'bom_art_1') {
            validate_filed = [0,1,2,3];
        }
        else if(grid_name == 'bom_art_2') {
            validate_filed = [0,1,2,3];
        }
        else if(grid_name == 'cmt_cip_cost') {
            validate_filed = [0,1,2,3];
        }
        else if(grid_name == 'other_exp_grid') {
            validate_filed = [0,1,2,3,4];
        }
        else if(grid_name == 'dying_cost_avg_grid') {
            validate_filed = [1,2,3,4,7];
        }
        else if(grid_name == 'fabric_cost_grid') {
            validate_filed = [6];
        }
        else if(grid_name == 'actual_cost_grid') {
            validate_filed = [7,9,10,12,14,15];
        }
        else if(grid_name == 'ior_budgeted_grid') {
            validate_filed = [1,2,3,4,7,8];
        }
        else if(grid_name == 'isr_proft_per_grid') {
            validate_filed = [1,2,3,4];
        }
        validate = validateForm(validate_filed, data);
        return validate;
     
    }

    function validateFiled_bugget(grid_name,data) {
        let validate_filed = [];
       
       
       
         if(grid_name == 'ior_budgeted_grid') {
            validate_filed = [1,2,3,4,7,8];
        }
        
        validate = validateForm(validate_filed, data);
        alert(validate);
        return validate;

     
    }

    function validateForm(validateField, dataValue) {
       // console.log(dataValue)
        let errorCount = 0;
        for (let i = 0; i < dataValue.length; i++) {
            for(let j = 0; j < validateField.length; j++) {
                let col = validateField[j];
                if(dataValue[i][col] == "") {
                    errorCount++;
                }
            }
        }
        return errorCount;
    }
    function validategridFields(grid_name,data) {
      let validate_columns = [];
      if(grid_name == 'fabric_cost_grid') {
            validate_columns = [1,2,3];
        }else if(grid_name == 'actual_cost_grid') {
            validate_columns = [1];
        }
        validatedcolumn = validateGridColumn(validate_columns, data);
        return validatedcolumn;
    }

    function validationFeilds_fabric(grid_name,data) {
    let validate_fields = [];
     if(grid_name == 'fabric_processing_details') {
         validate_fields = [0,1,2,3];
        }
       // console.log(grid_name+componentid+'_combo_'+comboid)
        validate = validateForm(validate_fields, data);
        return validate;
 }
    function validateGridColumn(validateField, dataValue) {
       // console.log(dataValue)
        let errorCount = 0;
        let field='';
        for (let i = 0; i < dataValue.length; i++) {
            for(let j = 0; j < validateField.length; j++) {
                let col = validateField[j];
                if(dataValue[i][col] == "" || dataValue[i][col] == "0.00") {
                    errorCount++;
                    field=col;
                    break;
                }
                 
            }
        }
        return field;
    }
    
 
   
function validateEmpGridColumn(grid_name, dataValue) {
       let errorCount = 0;
      // let field='';
       if(grid_name=='emp_cost_grid'){
           validateField=[2,3,4,5,6,7];
       }else{
           validateField=[];
       }
        
        for (let i = 0; i < dataValue.length; i++) {
            //console.log(dataValue[i][1].toUpperCase());
            if(dataValue[i][1]!='' && (dataValue[i][1].toUpperCase()!="NIL")){
                for(let j = 0; j < validateField.length; j++) {
                let col = validateField[j];
                if(dataValue[i][col] == "" || dataValue[i][col] == "0.00") {
                    errorCount++;
                 //   field=col;
                }
            }
            }
        }
        return errorCount;
    }
</script>
