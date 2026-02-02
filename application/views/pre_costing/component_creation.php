<?php
/**
 * @var array $components
 * @var array $sizes
 * @var int $enquiry_id
 */
//$this->load->view(CNFCOMPANY . 'template/header');
$this->load->view(CNFCOMPANY . 'customheader'); 
$userInfo = fnGetUserLoggedInfo('1');
$userType = $userInfo['usertype'];
?>

<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css"/>

<!-- Add Vue and Bootstrap-Vue JS just before the closing </body> tag -->
<div id="app" class="pb-0 mb-0"> <?php // var_dump($ArrEnquiryInfo[0]);?>
    <div class="page-header pl-3 text-royal-blue py-2" style="font-size: 20px;color:#022B61!important "> <?php echo (isset($components) && count($components)>0) ? 'Component Details':'Add Component Details'; ?>
        <div class="page-info text-cyan-br text-nowrap pl-3">
        </div>
        <div class="ml-auto pr-3">
            <?php if($lastSegement != "wiplist") { ?>
                <a href="<?=($userType==2)?base_url('management/enquiryview').'/'.urlencode(base64_encode($enquiry_id)):base_url('preCosting/index').'/'.urlencode(base64_encode($enquiry_id))?>" class="btn btn-sm  btn-royal-blue btn-text-slide-x hide">
                    <!--<i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i>-->
                    Back
                </a>
                <?php if($userType==2 ) { ?>
                <!-- <a href="javascript:void(0)" @click="backbtn" class="btn btn-sm  btn-royal-blue btn-text-slide-x ">
                  
                    Back111
                </a> -->
                <button onclick="goBack()" class="btn btn-sm btn-royal-blue btn-text-slide-x">Back</button>&nbsp;&nbsp;
                
                <?php } ?>
                
                <?php if($userType==3 ) { ?>
                <!-- <a href="<?php echo base_url('merchant/addenquiry').'/'.urlencode(base64_encode($enquiry_id)); ?>" class="btn btn-sm  btn-royal-blue btn-text-slide-x ">
                     Back1
                </a> -->
                <!-- <a href="<?php echo base_url('preCosting/index').'/'.urlencode(base64_encode($enquiry_id)); ?>" class="btn btn-sm  btn-royal-blue btn-text-slide-x ">
                     Back
                </a> -->
                 <button onclick="goBack()" class="btn btn-sm btn-royal-blue btn-text-slide-x">Back</button>&nbsp;&nbsp;
                
                 <?php } ?>
                <?php if(isset($components) && count($components)>0 && $components[0]['draft_status']==2) { ?>
                <button id="editbtn" class="btn btn-royal-blue btn-sm <?= !empty($sizes['selectedNames'])?'':'hide'?>"
                onclick="$('input').attr('readonly',false);$('select').attr('disabled',false),$('#sizeRangeSelection').attr('readonly',true),$('#TotalSize').attr('readonly',true),$('#compsavebtn').show(),$('.deletebtns').attr('disabled',false),$('#editstatus').val(2),$('i').removeClass('icondisabled'),$('.add_component').attr('disabled',false)">Edit</button>
                <?php  }  ?>
            <?php  } else { ?>
                <a href="<?=($userType==2)?base_url('management/enquiryview').'/'.urlencode(base64_encode($enquiry_id)):base_url('preCosting/index').'/'.urlencode(base64_encode($enquiry_id))?>" class="btn btn-sm btn-royal-blue btn-text-slide-x hide">
                    <!--<i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i>-->
                    Back
                </a>
                <a href="javascript:void(0)" @click="backbtn" class="btn btn-sm btn-royal-blue btn-text-slide-x ">
                    <!--<i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i>-->
                    Back
                </a>
            <?php }  ?>
        </div>
    </div>
    <div class="col-12 pt-1" style="border-bottom: 1px solid #022b61"></div>
    <div class="pos-rel overflow-hidden radius-1 py-0 px-3" >
        <div class="pos-rel d-flex" >
            <div class="text-royal-blue ml-4 mb-0 mt-1 text-100" style="font-size: 14px !important; padding: 10px 5px">
                <span class="pl-5 ml-5 d-none">
                    &nbsp;&nbsp;Order / Enquiry Ref.No.: <?= $ArrEnquiryInfo[0]->orderenqrefno ?>
                </span>

            </div>

        </div>
    </div>  
    <div class="col-12 p-0">
         <input type="hidden" id="editstatus" value="1" >
        <!--<div v-if="seen" role="alert" class="alert alert-warning bgc-warning-l4 brc-warning-m3 border-2 d-flex align-items-center">-->
        <!--    <i class="fas fa-exclamation-circle mr-3 fa-2x text-orange"></i>-->
        <!--    <div class="text-dark-tp2">-->
        <!--        {{ $data.msg }}-->
        <!--    </div>-->

        <!--    <button type="button" class="close align-self-start ml-auto text-danger-d2 text-150" >-->
        <!--        <span @click="closeAlert">×</span>-->
        <!--    </button>-->
        <!--</div>-->
      
        <div v-for="(find, index) in components" class="card border-0 p-0">
            <div class="card border-0 p-0">
                <div class="card-body border-0 pt-0" style="">
                    <div class="d-flex d-inline-flex d-inline w-100 col-12  border-0">
                        <!--<div class="col-1 p-0">&nbsp;</div>-->
                        
                        <div class="col-3 pt-4 pb-2 bgc-gray">
                            <div class="form-group row">
                            <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                                <label for="id-form-field-focus-1" class="mb-0">
                                Component
                            </label></div>
                            <div class="col-8">
                                <input v-model="find.comp_name" type="text" onkeyup="changeaction(this.value)"
                                       class="form-control brc-on-focus brc-primary-m1 py-1 my-1" placeholder="Free Text">
                            </div>
                        </div>
                        </div>
                        <div class="col-4  pt-4 pb-2 bgc-gray">
                            <div class="form-group row">
                                <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                                    <label for="id-form-field-focus-1" class="mb-0">Dyeing Type</label>
                                </div>
                                <div class="col-8">
                                    <select class="form-control brc-on-focus brc-primary-d1 py-1 my-1" v-model="find.dying_type" onchange="changeaction(this.value),dyeing_change(this.value)">
                                        <option value="0" selected disabled>Select</option>
                                        <option value="1">Solid Color</option>
                                        <option value="2">Yarn Dye / Multi Colour</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-4  pt-4 pb-2 bgc-gray">
                             <div class="form-group row">
                                <div class="col-4 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                                    <label for="id-form-field-focus-1" class="mb-0">Combo / Colour</label>
                                </div>
                                <div class="col-8">
                                   <div v-for="(obj, colorIndex) in find.colourCombos" class="d-flex">
                                <div class="col-11 p-0 pb-3">
                                    <input v-model="obj.name" type="text" placeholder="Free Text" onkeyup="changeaction(this.value)" class="form-control brc-on-focus brc-primary-m1">
                                </div>
                                <!-- *************** Conditionality render button based on index****************** -->
                                <div v-if="colorIndex == 0">
                                    <?php if(($ArrEnquiryInfo[0]->orderstatus!=0 && $ArrEnquiryInfo[0]->orderstatus!=3)||($userType==2)) { ?>
                                    <div data-toggle="tooltip" data-placement="top"
                                        data-original-title="Add More Color Combo"
                                        class="mt-2 text-right card-toolbar-btn text-green text-110">
                                        <i class="fa fa-plus icondisabled"></i>
                                    </div>
                                     <?php } else { ?>
                                     <div @click="addColorCombos(index)" title data-toggle="tooltip" data-placement="top"
                                        data-original-title="Add More Color Combo"
                                        class="mt-2 text-right card-toolbar-btn text-green text-110">
                                        <i class="fa fa-plus iconbtn"></i>
                                    </div>
                                     <?php } ?>
                                </div>
                                <div v-else>
                                    <?php if(($ArrEnquiryInfo[0]->orderstatus!=0 && $ArrEnquiryInfo[0]->orderstatus!=3)||($userType==2)) { ?>
                                    <div data-toggle="tooltip" data-placement="top"
                                       class=" mt-2 my_tooltip pr-2 card-toolbar-btn text-danger-m1 text-110">
                                        <i class="fa fa-times icondisabled"></i>
                                    </div>
                                    <?php } else { ?>
                                    <div @click="removeColorCombos(index,colorIndex)" title data-toggle="tooltip" data-placement="top" 
                                         class=" mt-2 my_tooltip pr-2 card-toolbar-btn text-danger-m1 text-110">
                                         <i class="fa fa-times iconbtn"></i>
                                    </div>
                                    <?php } ?>
                                </div>

                            </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="col-1  pt-4 pb-2 text-center bgc-gray" v-if="index == 0"> 
                            <button @click="deleteFind(index)" id="deletebtn" disabled <?php if(($ArrEnquiryInfo[0]->orderstatus!=0 && $ArrEnquiryInfo[0]->orderstatus!=3)||($userType==2)) { echo 'disabled';} ?> class="btncomponent btn btn-grey btn-h-danger btn-a-danger py-2 px-3 btn-text-slide-y pt-2 pb-3 mb-1">
                                <i class="fa fa-trash-alt text-130 px-2 btn-text-1 mt-1"></i>
                                <span class="text-75 text-600 btn-text-2">Delete?</span>
                            </button>
                        </div>
                        <div class="col-1  pt-4 pb-2 text-center bgc-gray" v-else> 
                            <button @click="deleteFind(index)" id="deletebtn" <?php if(($ArrEnquiryInfo[0]->orderstatus!=0 && $ArrEnquiryInfo[0]->orderstatus!=3)||($userType==2)) { echo 'disabled';} ?> class="deletebtns btncomponent btn btn-grey btn-h-danger btn-a-danger py-2 px-3 btn-text-slide-y pt-2 pb-3 mb-1">
                                <i class="fa fa-trash-alt text-130 px-2 btn-text-1 mt-1"></i>
                                <span class="text-75 text-600 btn-text-2">Delete?</span>
                            </button>
                        </div>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div>

        <div class="d-flex d-inline-flex d-inline w-100 col-12  border-0">
            <div class="col-10">
                 <label class="px-1" style="color:#ff0000 !important;">
                    Note :
                  </label>
                  <label class="px-1">
                    No. of Components - <?=$ArrEnquiryInfo[0]->totalcomponents; ?> ,  No. of Combo / Colour Per Component - <?=$ArrEnquiryInfo[0]->totalcombo; ?>
                 </label>
            </div>
            <div class="col-2" id="addBtnRow"> 
                <button  style="float: right;margin:0px 5px;font-size:12px!important;" <?php if(($ArrEnquiryInfo[0]->orderstatus!=0 && $ArrEnquiryInfo[0]->orderstatus!=3) || ($userType==2) || (isset($components) && count($components)>0 && $components[0]['draft_status']==2)) { echo 'disabled';} ?> @click="addFind" type="button" class="btncomponent add_component btn btn-sm btn-royal-blue d-block">
                    <i class="fa fa-plus mr-1"></i>
                    Add
                </button>
            </div>
        </div>
 <div v-if="componentsCreated" class="card border-0 pb-0 mb-0">
            <div class="card border-0 pb-0 mb-0">
                <div class="card-body border-0 pb-2 mb-1">
                    <div class="d-flex d-inline-flex d-inline w-100 col-12  border-0">
                        <div class="col-3 bgc-gray pt-4 pb-2">
							<div class="form-group row">
								<div class="col-5 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                       Size Chart Type
                                    </label>
                                </div>
								<div class="col-7 pb-3">
									<select class="form-control" v-model="size_type"  id="size_type" @change="sizetypechange()" 
											onclick='size_on_click(this.value)' 
											onchange='size_range_changes(this.value)'>
											<option value="0" selected disabled> Select </option>
										<?php
											$masterSizeTypes = ARR_SIZE_CHART;
											foreach ($masterSizeTypes as $type => $masterSizeType) { 
										?>
											<option value="<?= $type ?>" > <?php echo $masterSizeType ?> </option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-3 bgc-gray pt-4 pb-2">
							<div class="form-group row">
								<div class="col-5 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
                                    <label for="id-form-field-focus-1" class="mb-0">
                                       Total No. of Sizes
                                    </label>
                                </div>
								<div class="col-7 pb-3">
								    <!--onblur="checktotsize(this.value)"-->
								    	<input id="updateaction"  type="hidden" value="1">
								    	<input id="dyeing_change"  type="hidden" value="2">
								    	<input id="changeaction"  type="hidden" value="">
									<input class="form-control"  v-model="totalsize"  readonly type="text" onkeyup="checktotsize(this.value,'size_type')" onkeypress="return onlyNumbernodecimal(event);" @keyup="totalsz()"  id="TotalSize" placeholder="Free Text">
								</div>
							</div>
						</div>
                        <div class="col-6 pt-4 pb-2 sz-rg-ptch bgc-gray<?= !empty($sizes['selectedNames'])?'':'hide'?> " id="overall_size_range" >
							<div class="form-group row">
								<div class="col-2 px-120 col-form-label text-sm-right pr-0 my-1 py-1">
									<label for="id-form-field-focus-1" class="mb-0">
									   Size Range
									</label>
								</div>
								<div id="size_saved" class="col-10">
									<input class="form-control" id="sizeRangeSelection" value="<?= $sizes['selectedNames'] ?>" readonly placeholder="Auto Update">
								</div>
							</div>
                        </div>
						<br>
                    </div>
                    <div class="col-12 row pt-4"> 
                        <!-- ------second row----------->
                            <div id="size_insert" class="col-12 form-check custom-checkbox <?= !empty($sizes['selectedNames'])?'hide':'' ?>">
                                <div class="col-12 mx-4 p-4 bgc-gray col-sm-12 row pb-3" id="stdSize">
                                    <label class='col-1 px-0 bgc-h-green-l1' v-for="std_option in std_options">
                                        <input class="bgc-primary-d2" style="margin-right: 0" 
                                               type='checkbox' onchange='standardSizeSelection(this)'
                                               :data-value="std_option.size_name"
                                               v-model='selected_ids' :value="std_option.id" >
                                        &nbsp;{{std_option.size_name}}
                                    </label>
                                </div>
                                <div class="col-12 mx-4 p-4 bgc-gray col-sm-12 row hide" id="customSize">
                                    <div class="col-1 pt-1 pr-1 pl-0" v-for="custom_size_values in custom_value">
                                        <input type='text' onkeyup='manualsize(this)'  v-model='custom_size_values'  class='form-control'>
                                    </div>
                                    <!--<div class="col-1 pt-1 pr-1 pl-0" id="custom_size_values">-->
                                    
                                    <!--</div>-->
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer bgc-white d-inline-flex d-inline d-inline-block row w-100 col-12 py-0 my-0" style=" border-top: transparent !important; border-bottom: 0 solid #022B61; background-color: white !important;">
            <div class="col-12 text-right mx-3" id="saveBtnRow">
                <!-- <button @click="formSubmit" class="btn-sm mx-8 btn-bold text-center pu btn btn-royal-blue-submit mb-1">
                    SAVE <?= !empty($sizes['selectedNames'])?'CHANGES':''?>
                </button> -->
                <?php if($checkDraftorNot > 0) { ?>
                <button  @click="cleardraft" id="clear_draft_btn" class="btn btn-royal-blue thm-submit-btn pu mb-1 mx-1  btncomponent">
                    Clear Draft
                    <!--<?= !empty($sizes['selectedNames'])?'Changes':''?>-->
                </button>
                <?php } ?>
                <button  @click="draftSubmit" id="save_draft_btn" class="btn btn-royal-blue thm-submit-btn pu mb-1 mx-1 btncomponent">
                    Save as Draft
                    <!--<?= !empty($sizes['selectedNames'])?'Changes':''?>-->
                </button>
                <button @click="formSubmit" id="compsavebtn" class="btn btn-info thm-submit-btn pu mb-1 mx-1 btncomponent">
                    Save 
                    <!--<?= !empty($sizes['selectedNames'])?'Changes':''?>-->
                </button>
                
            </div>
            <div class="col-1"> </div>
            <div class="col-5 pl-2 ml-3 ">
                &nbsp;
                <a href="<?=base_url('preCosting/index').'/'.urlencode(base64_encode($enquiry_id))?>" class="hide btn btn-sm btn-small btn-royal-blue btn-text-slide-x mb-1">
                    <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i>Back
                </a>
            </div>
            
            <div class="col-2"> </div>
        </div>
    </div>
    <pre id="print" class="bg-danger"></pre>
    <pre id="print1"></pre>
</div>
<script>

    function goBack() {
    if (document.referrer !== "") {
      window.location.href = document.referrer;
    } else {
      window.location.href = "<?= base_url() ?>"; // fallback if no referrer
    }
  }

    // *********** STANDARD CHART SELECTION HANDLING STARTS****************
   
     // var editselect='<?=$sizes['selectedNames']?>';
     //console.log(editselect.split(",").filter(s => s.trim()))
    // var editedsel=editselect.split(",").filter(s => s.trim());
       // var valueSelected =(editedsel.length>0)? editedsel:[];
      // valueSelected.indexOf(str) === -1
      
    //  yet to umcomment for validation on total size
    function checktotsize(value,id){
         var sizetype=$("#"+id).val();
        if(value==''){
            if(sizetype==1){
                 $('#stdSize input[type="checkbox"]').filter(':checked').each(function() {
                        
                  this.checked=false
                });
                $("#sizeRangeSelection").val('');
                obj.selected_ids=[];
               // obj.size_ids=[];
               // console.log(obj.selected_ids);
            }else if(sizetype==2){
                 $("#sizeRangeSelection").val('');
                  obj.selected_ids=[];
                  //obj.size_ids=[];
                  $("#customSize").html('');
                // console.log(obj.custom_size_values);
            }
        }
        else{
          
        }
$("#updateaction").val(2); 
$("#changeaction").val(1); 
    }

    function manualsize(e) {
          var valuesentered =[];   
         let totsize=$('#TotalSize').val();
          $('#customSize input[type="text"]').each(function() {
                
                 if(this.value!=''){
                   
                    valuesentered.push(this.value);
                 }else{
                   valuesentered = valuesentered.filter(item => item != this.value);
                // valuesentered = [];
                }
               
            });
           
                if(valuesentered.length<=totsize){
                        valuesentered=valuesentered;
                        $("#updateaction").val(2); 
                        $("#changeaction").val(1); 
                }else{
                   //valuesentered = [];
                     valuesentered = [valuesentered.filter(item => item != e.value)];
                     alert('You cannot enter now , because it reaches total number of sizes'); 
                     e.value="";
                }     
                  obj.custom_size_values=valuesentered;
                  let append_value = valuesentered.join(", ");
            $("#sizeRangeSelection").val(append_value);  
            
        }
         

    var valueSelected =[];
          
    function standardSizeSelection(e) {
      
        let inputChecked = e.checked;
        let inputCheckeds=$('#stdSize input[type="checkbox"]').filter(':checked');
        //  alert(inputCheckeds.length);
        let inputValue = e.getAttribute("data-value");
        let totsize=$('#TotalSize').val();
        if(inputChecked && totsize!='') {
            if(inputCheckeds.length<=totsize){
                valueSelected.push(inputValue);
                $("#updateaction").val(2); 
                $("#changeaction").val(1); 
            }else{
                alert('You cannot select now , because it reaches total number of sizes'); 
                e.checked=false;
                // $("#sizeRangeSelection").val('<?=$sizes['selectedNames']?>');
                //var selected = [];
                // $('#stdSize input[type="checkbox"]').filter(':checked').each(function() {
                //     selected.push($(this).attr('data-value'));
                // });

            }
            
             
        }
        else {
            valueSelected = valueSelected.filter(item => item != inputValue);
        }
        Selected=[]
        if(totsize!=''){
            $('#stdSize input[type="checkbox"]').filter(':checked').each(function() {
                    
               Selected.push($(this).attr('data-value'));
            });
            
        }else{
               //alert('Total Size should not be empty'); 
                var swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                    swalWithBootstrapButtons.fire({
                                    title: 'Total No. of Sizes should not be empty',
                                   // text: 'Cancelled successfully.',
                                    type: 'warning',
                                    icon: 'warning',
                                    width: 460,
                                    customClass: {'confirmButton': 'btn btn-info'}
                                });
               e.checked=false;
                Selected=[];

        }
        
        let append_value = Selected.join(", ");
        $("#sizeRangeSelection").val(append_value);
    }
    
        /////////////////////////////////////////////////////////////
        
        // var valueSelected =[];
        // function standardSizeSelectionold(e) {

        //     let inputChecked = e.checked;
        //     let inputCheckeds=$('#stdSize input[type="checkbox"]').filter(':checked');
        //     //  alert(inputCheckeds.length);
        //     let inputValue = e.getAttribute("data-value");
        //     let totsize=$('#TotalSize').val();
        //     if(inputChecked) {
        //         if(inputCheckeds.length<=totsize){
        //             valueSelected.push(inputValue);
        //         }else{
        //             alert('You cannot select now , because it reaches total number of sizes'); 
        //             e.checked=false;
        //             // $("#sizeRangeSelection").val('<?=$sizes['selectedNames']?>');
        //             //var selected = [];
        //             // $('#stdSize input[type="checkbox"]').filter(':checked').each(function() {
        //             //     selected.push($(this).attr('data-value'));
        //             // });

        //         }
                
                 
        //     }
        //     else {
        //         valueSelected = valueSelected.filter(item => item != inputValue);
        //     }
        //     let append_value = valueSelected.join(", ");
        //     $("#sizeRangeSelection").val(append_value);
        // }
    // *********** STANDARD CHART SELECTION HANDLING ENDS******************

    var first_time = true;
    function size_on_click(v){
       
        (v!=0)?$('#TotalSize').attr('readonly',false):'';
         obj.totalsize=''; // on click here removed values for total size
         checktotsize('','size_type'); // values reset for standard size selection picker and individual textbox block
        if(first_time && v) {
            console.log('i calld first time ah ?');
            //$("#size_saved").addClass("hide");
            $("#size_insert,#overall_size_range").removeClass("hide");
            first_time = false;
        }
    }
    
    function size_range_changes(v){
        if(v==1){
            $("#updateaction").val(2); $("#changeaction").val(1); 
            $('#TotalSize').attr('readonly',false);
            $("#stdSize,#overall_size_range").removeClass("hide");
            $("#customSize").addClass("hide");
        }else if(v==2){
            $("#updateaction").val(2); $("#changeaction").val(1); 
            $('#TotalSize').attr('readonly',false);
            $("#stdSize").addClass("hide");
            $("#customSize,#overall_size_range").removeClass("hide");
        }else{
            $("#updateaction").val(1); $("#changeaction").val(1); 
            $("#overall_size_range").addClass('hide');
        }

        //$("#stdSize,#customSize").toggleClass("hide");
    }
    function warningMsg(msg=''){
        $.aceToaster.add({
            placement: 'tr',
            body: "<div class='bgc-orange-d1 text-white px-3 pt-3' data-dismiss='toast'>\
                        <div class='border-2 brc-white px-3 py-25 radius-round'>\
                            <i class='fa fa-times text-150'></i>\
                        </div>\
                    </div>\
                    <div class='p-3 mb-0 flex-grow-1'>\
                        <h4 class='text-130'>Warning</h4>\
                        "+msg+"\
                    </div>\
                    <button  class='hide align-self-start btn btn-xs btn-outline-grey btn-h-light-grey py-2px mr-1 mt-1 border-0 text-150'>&times;</button>",

            width: 420,
            delay: 4500,
            close: false,
            className: 'bgc-white-tp1 shadow border-0',
            bodyClass: 'd-flex border-0 p-0 text-dark-tp2',
            headerClass: 'd-none',
        })
    }
  function changeaction(vals) {
                
    if(vals!=''){
         $("#changeaction").val(1);
    }
}
    var obj;
    var component_limit = '<?php echo $ArrEnquiryInfo[0]->totalcomponents; ?>';  
    var combo_limit='<?php echo $ArrEnquiryInfo[0]->totalcombo;?>';
    var selections = <?= json_encode($sizes['selections']) ?>;
    var selected_ids = <?= json_encode($sizes['selected_ids']) ?>;
    var std_options = <?= json_encode($sizes['std_options']) ?>;
    var size_type = <?= $sizes['size_type'] ?>;
    var enquiry_id = '<?php echo $enquiry_id; ?>';
    var custom_size_values = <?= json_encode($sizes['custom_size_values']) ?>;
    var custom_value = <?= json_encode($sizes['custom_value']) ?>;
    var comp = <?= json_encode($components) ?>;
    var totalsize = <?= $sizes['totalsize'] ?>;
    var dd = [{comp_name: "Top",id:1,dying_type:'0',colourCombos:[{id:1,name:'colorfull 1'},
            {id:2,name:'colorfull 2'}]}];
    var draftstatus=(comp.length>0)?comp[0]['draft_status']:1;
    var orderstatus='<?php echo $ArrEnquiryInfo[0]->orderstatus;?>';
    var user_type='<?php echo $userType;?>';
    
    $(document).ready(function() {
       if(draftstatus==2){
          $("#save_draft_btn").hide();
          $("#compsavebtn").hide();
       }else{
          $("#save_draft_btn").show();
          $("#compsavebtn").show();
       }
      
       if((orderstatus == 0 || orderstatus == 3  || orderstatus == 1  ) && (user_type != 2)  ){
           $("#editbtn").show();
       }else{
           $("#editbtn").hide();
       }
        if(size_type == 1) {
            $("#stdSize").addClass("show").removeClass('hide');
            $("#customSize").addClass("hide").removeClass('show');
        }else if(size_type == 2){
            $("#customSize").addClass("show").removeClass('hide');
            $("#stdSize").addClass("hide").removeClass('show');
        }else{
            $("#stdSize,#customSize").addClass("hide");
        }
        
        obj = new Vue({
            el: '#app',
            data: {
                components: comp,
                msg:'',
                std_options:std_options,
                size_ids: selections,
                size_type: size_type,
                //selected_ids:selected_ids,
                selected_ids:selections,
                custom_size_values:custom_size_values,
                custom_value:custom_value,
                totalsize:totalsize,
                seen:false,
                componentsCreated: comp.length >= 1
            },
            methods: {
                // closeAlert: function () {
                //     this.seen = false;
                // },
                totalsz:function(){
                if(this.size_type==2){
                    
                    $("#customSize").html(''); 
                    if(this.totalsize!='' && this.totalsize>=1 && this.totalsize<=24){
                        var i;
                        for(i=1;i<=this.totalsize;i++){
                            if(i<=this.totalsize){
                            var sizestr='';
                                // sizestr='\
                                //       <div class="col-1 pt-1 pr-1 pl-0"><input type="text" name="customvalue[]" onkeyup="manualsize(this)" v-model="custom_size_values" class="form-control" id="customvalue'+i+'"\></div>';
                                //       $("#customSize").append(sizestr);  
                                 sizestr='\
                                      <div class="col-1 pt-1 pr-1 pl-0"><input type="text"  onkeyup="manualsize(this)" v-model="custom_size_values" class="form-control"\></div>';
                                      $("#customSize").append(sizestr);  
                            }
                        }
                        return true;
                    }else{
                         return false;
                        this.totalsize='';
                        $("#sizeRangeSelection").val('');  
                    }
                  }
                  $("#updateaction").val(2); $("#changeaction").val(1); 
                },
                sizetypechange:function(){
                    
                   if(this.size_type==1){
                     //  this.selected_ids=[];
                   }else{
                     $("#customSize").html(''); 
                   }
                   this.totalsize="";
                   $('#sizeRangeSelection').val('');
                },
                addColorCombos:function (index){
                  //  console.log("i was called da karthi");
                    //console.log(index);
                    //console.log((this.components[index].colourCombos.length)+1)
                    if($('#editstatus').val()==1 && draftstatus==2){
                        return false;
                    }else{
                        if(((this.components[index].colourCombos.length)+1)<=combo_limit){
                        this.components[index].colourCombos.push({id:0,name:''});
                       // this.seen = false;
                    }else{
                        //commented by me warningMsg('Not allowed more then '+combo_limit+' colour components');
                         warningMsg('Exceeds maximum combo/colour limit per component.')
                        //  this.msg = 'Not allowed more then '+combo_limit+' colour components';
                        //  this.seen = true;
                    }
                    
                    }
                    
                },
                addFind: function () {
                    this.componentsCreated = true;
                    if($('#editstatus').val()==1 && draftstatus==2){
                        return false;
                    }else{
                    if(this.components.length< component_limit) {
                        this.components.push({id: 0,comp_name: '',dying_type:'0',colourCombos:[{id:0,name:''}]});
                        //this.seen = false;
                    }else{
                       // commented by me warningMsg('Not allowed more then '+component_limit+' components');
                       warningMsg('Exceeds maximum component limit.');
                        // this.msg = 'Not allowed more then '+component_limit+' components';
                        // this.seen = true;
                    }
                    }
                },
                removeColorCombos:function (index,colorIndex){
                    //alert($('#editstatus').val());
                    // console.log("i was called da karthi");
                    // console.log(index);
                    if($('#editstatus').val()==1 && draftstatus==2){
                        return false;
                    }else{
                    var comboId = this.components[index].colourCombos[colorIndex].id;
                    if(comboId===0){
                        this.removeCombo(index,colorIndex);
                    }else{
                        let component_name = this.components[index].comp_name;
                        let combo_name = this.components[index].colourCombos[colorIndex].name;
                        var swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                        var kar = this;
                        swalWithBootstrapButtons.fire(
                            {
                                title: 'Are you sure to delete the Combo / Colour details ?',
                               // title: 'Are you sure, you want to delete the "'+component_name+'" Component\'s Color / Combo "'+combo_name+'"?',
                               // text: "If you delete, all the related contents will be removed, you can't revert it!",
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
                                MakeAsynPostRequest(base_path + "components/deleteCombo", "comboId=" + comboId, "json", function(data) {
                                    if(data)
                                        kar.removeCombo(index,colorIndex);
                                });
                                swalWithBootstrapButtons.fire({title: 'Deleted!',
                                //text: 'Operation completed successfully.',
                                type: 'success',icon: 'success',width: 460,customClass: {'confirmButton': 'btn btn-info'}});
                            } 
                            // else if (result.dismiss === Swal.DismissReason.cancel) {
                            //     swalWithBootstrapButtons.fire({
                            //         title: 'Cancelled',
                            //         text: 'Cancelled successfully.',
                            //         type: 'error',
                            //         icon: 'error',
                            //         customClass: {'confirmButton': 'btn btn-secondary px-5'}
                            //     });
                            // }
                        });
                    }
                    }
                },
                removeCombo:function (index,colorIndex){
                    if(this.components[index].colourCombos.length>1) {
                        this.components[index].colourCombos.slice(colorIndex, 1)
                        this.$delete(this.components[index].colourCombos,colorIndex);
                    }
                },
                deleteComp:function (index){
                    if(this.components.length<2){
                        warningMsg('At least one component need.');
                        // this.msg = 'At least one component need ';
                        // this.seen = true;
                    }else{
                        //this.seen = false;
                    }
                    var swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                        var kar = this;
                        swalWithBootstrapButtons.fire(
                            {
                            title: 'Are you sure to delete the component details?',
                            // title: 'Are you sure want to remove the "'+component_name+'" Component?',
                            //text: "If you delete, all the related contents will be removed, you can't revert it!",
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
                                kar.components.splice(index, 1);
                            } 
                        });
                   // this.components.splice(index, 1);
                },
                deleteFind: function (index) {
                    var component_id = parseInt(this.components[index].id);
                    if(component_id===0){
                        this.deleteComp(index);
                    } else {
                        let component_name = this.components[index].comp_name;
                        var swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                        var kar = this;
                        swalWithBootstrapButtons.fire(
                            {
                            title: 'Are you sure to delete the component details?',
                            // title: 'Are you sure want to remove the "'+component_name+'" Component?',
                            //text: "If you delete, all the related contents will be removed, you can't revert it!",
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
                                MakeAsynPostRequest(base_path + "components/delete", "component_id=" + component_id, "json", function(data) {
                                    if(data)
                                        kar.deleteComp(index);
                                });

                                swalWithBootstrapButtons.fire({title: 'Deleted!',
                                //text: 'Operation completed successfully.',
                                type: 'success',icon: 'success',width: 460,customClass: {'confirmButton': 'btn btn-info'}});
                            } 
                            // else if (result.dismiss === Swal.DismissReason.cancel) {
                            //     swalWithBootstrapButtons.fire({
                            //         title: 'Cancelled',
                            //         text: '',
                            //         type: 'error',
                            //         icon: 'error',
                            //         customClass: {'confirmButton': 'btn btn-secondary px-5'}
                            //     });
                            // }
                        });
                    }
                },
                formSubmit: function ()
                {   
                    console.log(this.components)
                   // console.log('compo'+this.components.length +component_limit);
                    let is_validation = true;
                    if(this.components.length < component_limit){
                        // warningMsg('At-least one component require to perform pre-costing');
                        // commented by myself warningMsg('Please add ' + (component_limit-this.components.length) +' component');
                        warningMsg('Please fill all component details.');
                        is_validation = false;
                        return false;
                    }
                    $.each(this.components,function (key,component){
                       // console.log('gffggf'+component.colourCombos.length);
                        //$('#print').append("<br>"+JSON.stringify(component));
                        if(!component.comp_name.trim()){
                            warningMsg("Component Name should not be empty.");
                            is_validation = false;
                        }
                        if(component.dying_type==0){
                            warningMsg("Please select Dyeing Type.");
                            is_validation = false;
                        }
                        if(component.colourCombos.length < combo_limit){
                            //warningMsg('Please add ' + (combo_limit-component.colourCombos.length) +' Combo / Colour');
                            warningMsg('Please fill all Combo / Colour details.');
                            is_validation = false;
                        }else{
                          $.each(component.colourCombos,function (i,colorCombo){
                            if(!colorCombo.name.trim()){
                                warningMsg('Please fill all Combo / Colour details.');
                                is_validation = false;
                            }
                        });  
                        }
                        
                    });
                    
                    if(this.size_type==0){
                        warningMsg('Please select Size Chart Type.');
                        this.selected_ids=[];
                        is_validation = false;
                        // alert(this.totalsize);
                    }
                    if(this.totalsize==0 || this.totalsize==''){
                        warningMsg('Total No. of Sizes should not be empty or zero.');
                        this.selected_ids=[];
                        is_validation = false;
                        // alert(this.totalsize);
                    }
                    if((this.selected_ids.length<1 && this.size_type==1) || (this.custom_size_values.length<1 && this.size_type==2)){
                        warningMsg('Size Range should not be empty.');
                        is_validation = false;
                    }
                   // alert(this.selected_ids.length);
                    if((this.totalsize!=this.selected_ids.length && this.size_type==1)){
                        warningMsg('Please select number of sizes equivelent to Total No. of Sizes.');
                        is_validation = false;
                    }
                    
                    if(this.totalsize!=this.custom_size_values.length && this.size_type==2){
                        warningMsg('Please enter number of sizes equivelent to Total No. of Sizes.');
                        is_validation = false;
                    }
                   // alert($("#updateaction").val());
                    if(is_validation){
                        var post_data = {
                            components:this.components,
                            std_size_ids:this.selected_ids,
                            pc_size_insert:(this.size_ids.length >= 1?1:0),
                            size_type:this.size_type,
                            totalsize:this.totalsize,
                            custom_size_values:(this.size_type==2?this.custom_size_values:[]),
                            updateaction:$("#updateaction").val(),
                            dyeingchange:$('#dyeing_change').val(),
                            draft_status:2
                        };


                        var swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                        swalWithBootstrapButtons.fire(
                            {   
                                  title: 'Do you want to save the details ?',
                               // title: 'Are you sure, you want to insert or update the component details?',
                               // text: "If you insert or update, You won't be able to revert the action!",
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
                                MakePostRequest(base_path + 'components/insertComponent',
                                    {post_data:post_data,enquiry_id:enquiry_id},
                                    'json', function (data) {
                                       console.log(data);
                                    });
                                swalWithBootstrapButtons.fire({
                                    title: 'Saved!',
                                   // text: ' Operation completed successfully.',
                                    type: 'success',
                                    icon: 'success',
                                    width: 460,
                                    customClass: {'confirmButton': 'btn btn-info'}
                                }).then(function() {
                                    let idValue = "<?php echo $enquiry_id; ?>";
                                    let usertype="<?php echo $userType ?>";
                                    if(usertype==2){
                                     let enquiryListPath = "management/enquiryview/"+encodeURIComponent(btoa(idValue)); 
                                     enquiryListPath = base_path+enquiryListPath;
                                     window.location.href = enquiryListPath;
                                    }else{
                                     let enquiryListPath = "preCosting/index/"+encodeURIComponent(btoa(idValue));
                                     enquiryListPath = base_path+enquiryListPath;
                                     window.location.href = enquiryListPath;
                                    }
                                });
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                               // commented by myself to retain its last state
                               // location.reload();
                            }
                            // else if (result.dismiss === Swal.DismissReason.cancel) {
                            //     swalWithBootstrapButtons.fire({
                            //         title: 'Cancelled',
                            //         text: '',
                            //         type: 'error',
                            //         icon: 'error',
                            //         customClass: {'confirmButton': 'btn btn-secondary px-5'}
                            //     });
                            // }
                        });
                    }


                    //this.components.forEach()


                    /*var GlbMasterSizeChartId = $("#frmOrderSizeChartList").val();
                    if (GlbMasterSizeChartId == "") {
                        $('#ErrOrderSizeChartList').html("Select Size Chart Type");
                        $('#frmOrderSizeChartList').focus();
                        $('#frmOrderSizeChartList').css("border", "1px solid #ff0000");
                        return false;
                    }

                    var GlbSelSizeChart = [];
                    if (GlbMasterSizeChartId == 1) {
                        var GlbSelSizeChart = $('.frmSubChartSelection:checkbox:checked').map(function () {
                            return this.value;
                        }).get();

                    } else {

                        var GlbSelSizeChart = $('.frmSubChartCustomSelection:checkbox:checked').map(function () {
                            return this.value;
                        }).get();
                    }*/

                    //MakePostRequest(base_path + 'components/insertComponent', "&object=" + JSON.stringify(this.components)+"&enquiry_id=" + enquiry_id, 'json', '');
                },
                draftformSubmit: function ()
                {
                   // console.log('compo'+this.components.length +component_limit);
                    let is_validation = true;
                    if(this.components.length > component_limit){
                        // warningMsg('At-least one component require to perform pre-costing');
                        // commented by myself warningMsg('Please add ' + (component_limit-this.components.length) +' component');
                        warningMsg('Please fill all component details.');
                        is_validation = false;
                        return false;
                    }
                    $.each(this.components,function (key,component){
                       // console.log('gffggf'+component.colourCombos.length);
                        //$('#print').append("<br>"+JSON.stringify(component));
                        if(!component.comp_name.trim()){
                            warningMsg("component Name should not be empty.");
                            is_validation = false;
                        }
                        if(component.colourCombos.length > combo_limit){
                           // warningMsg('Please add ' + (combo_limit-component.colourCombos.length) +' Combo / Colour');
                           warningMsg('Please fill all Combo / Colour details.');
                            is_validation = false;
                        }else{
                          $.each(component.colourCombos,function (i,colorCombo){
                            if(!colorCombo.name.trim()){
                                warningMsg('Combo / Colour details should not empty.');
                                is_validation = false;
                            }
                        });  
                        }
                        
                    });
                    if(this.size_type==0){
                        warningMsg('Please select Size Chart Type.');
                        this.selected_ids=[];
                        is_validation = false;
                        // alert(this.totalsize);
                    }
                    if(this.totalsize==0 || this.totalsize==''){
                        warningMsg('Total No. of Sizes should not be empty or zero.');
                        this.selected_ids=[];
                        is_validation = false;
                        // alert(this.totalsize);
                    }
                    if((this.selected_ids.length<1 && this.size_type==1) || (this.custom_size_values.length<1 && this.size_type==2)){
                        warningMsg('Size Range should not be empty.');
                        is_validation = false;
                    }
                   // alert(this.selected_ids.length);
                    if((this.totalsize!=this.selected_ids.length && this.size_type==1)){
                        warningMsg('Please select number of sizes equivelent to Total No. of Sizes.');
                        is_validation = false;
                    }
                    
                    if(this.totalsize!=this.custom_size_values.length && this.size_type==2){
                        warningMsg('Please enter number of sizes equivelent to Total No. of Sizes.');
                        is_validation = false;
                    }
                   // alert($("#updateaction").val());
                    if(is_validation){
                        var post_data = {
                            components:this.components,
                            std_size_ids:this.selected_ids,
                            pc_size_insert:(this.size_ids.length >= 1?1:0),
                            size_type:this.size_type,
                            totalsize:this.totalsize,
                            custom_size_values:(this.size_type==2?this.custom_size_values:[]),
                            updateaction:$("#updateaction").val(),
                            dyeingchange:$('#dyeing_change').val(),
                            draft_status:1
                        };


                        var swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                        swalWithBootstrapButtons.fire(
                            {   
                                  title: 'Do you want to save the draft details ?',
                               // title: 'Are you sure, you want to insert or update the component details?',
                               // text: "If you insert or update, You won't be able to revert the action!",
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
                                MakePostRequest(base_path + 'components/insertComponent',
                                    {post_data:post_data,enquiry_id:enquiry_id},
                                    'json', function (data) {
                                      // console.log(data);
                                       if(data.draftstatus==1) {
                                        let enquiryListPath = "merchant/orderEnquiryList";
                                        enquiryListPath = base_path+enquiryListPath;
                                        window.location.href = enquiryListPath;
                                    }
                                    });
                               
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                            //   location.reload();
                            // commented by myself to retain its last state
                            }
                        });
                    }


                    //this.components.forEach()


                    /*var GlbMasterSizeChartId = $("#frmOrderSizeChartList").val();
                    if (GlbMasterSizeChartId == "") {
                        $('#ErrOrderSizeChartList').html("Select Size Chart Type");
                        $('#frmOrderSizeChartList').focus();
                        $('#frmOrderSizeChartList').css("border", "1px solid #ff0000");
                        return false;
                    }

                    var GlbSelSizeChart = [];
                    if (GlbMasterSizeChartId == 1) {
                        var GlbSelSizeChart = $('.frmSubChartSelection:checkbox:checked').map(function () {
                            return this.value;
                        }).get();

                    } else {

                        var GlbSelSizeChart = $('.frmSubChartCustomSelection:checkbox:checked').map(function () {
                            return this.value;
                        }).get();
                    }*/

                    //MakePostRequest(base_path + 'components/insertComponent', "&object=" + JSON.stringify(this.components)+"&enquiry_id=" + enquiry_id, 'json', '');
                },
                draftSubmit: function ()
                {   
                    let is_validation = true;
                    if(this.components.length > component_limit){
                        // warningMsg('At-least one component require to perform pre-costing');
                        // warningMsg('Please add ' + (component_limit-this.components.length) +' component');
                        warningMsg('Please fill all component details.');
                        is_validation = false;
                        return false;
                    }
                    $.each(this.components,function (key,component){
                       
                        if(component.colourCombos.length > combo_limit){
                           // warningMsg('Please add ' + (combo_limit-component.colourCombos.length) +' Combo / Colour');
                           warningMsg('Please fill all Combo / Colour details.');
                            is_validation = false;
                        }
                    });
                    
                    if($("#changeaction").val()!='' && $("#changeaction").val()==1){
                       if(is_validation){
                        var post_data = {
                            components:this.components,
                            std_size_ids:this.selected_ids,
                            pc_size_insert:(this.size_ids.length >= 1?1:0),
                            size_type:this.size_type,
                            totalsize:this.totalsize,
                            custom_size_values:(this.size_type==2?this.custom_size_values:[]),
                            updateaction:$("#updateaction").val(),
                            dyeingchange:$('#dyeing_change').val(),
                            draft_status:1
                        };
                        var swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                        swalWithBootstrapButtons.fire(
                            {   
                                  title: 'Do you want to save the draft details ?',
                              // title: 'Are you sure, you want to insert or update the component details?',
                              // text: "If you insert or update, You won't be able to revert the action!",
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
                                MakePostRequest(base_path + 'components/insertComponent',
                                    {post_data:post_data,enquiry_id:enquiry_id},
                                    'json', function (data) {
                                      //console.log(data);
                                      if(data.draftstatus==1) {
                                        let idValue = "<?php echo $enquiry_id; ?>";
                                         let enquiryListPath = "preCosting/index/"+encodeURIComponent(btoa(idValue));
                                         enquiryListPath = base_path+enquiryListPath;
                                         window.location.href = enquiryListPath;
                                     
                                    //  let enquiryListPath = "merchant/orderEnquiryList";
                                    //     enquiryListPath = base_path+enquiryListPath;
                                    //     window.location.href = enquiryListPath;
                                    }
                                    });
                               
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                            //   location.reload();
                            // commented by myself to retain its last state
                            }
                        });
                    }
                    }else{
                                    let idValue = "<?php echo $enquiry_id; ?>";
                                    let enquiryListPath = "preCosting/index/"+encodeURIComponent(btoa(idValue));
                                    enquiryListPath = base_path+enquiryListPath;
                                    window.location.href = enquiryListPath;
                    }
                },
                cleardraft:function(){
                    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                    swalWithBootstrapButtons.fire(
                            {
                                title: 'Do you want to clear the draft details?',
                                type: 'warning',
                                showCancelButton: true,
                                scrollbarPadding: false,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                reverseButtons: true,
                                width:460,
                                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                            }
							).then(function(result) {
								if (result.value) {
								MakeAsynPostRequest(base_path + "components/getcleardraftstatus", "enquiry_id=" + enquiry_id, "json", function (data) {
								    console.log('clear'+data);
                                    if(data.success==1) {
                                    let idValue = "<?php echo $enquiry_id; ?>"
                                    let enquiryListPath = "preCosting/index/"+encodeURIComponent(btoa(idValue));
                                    enquiryListPath = base_path+enquiryListPath;
                                    window.location.href = enquiryListPath;
                                    }
                                });
								} 
								else if (result.dismiss === Swal.DismissReason.cancel) {
								
								}
                            }); 
                },
                backbtnSubmit: function ()
                {
                    let is_validation = true;
                    if(this.components.length > component_limit){
                        // warningMsg('At-least one component require to perform pre-costing');
                        // commented by myself on 13/06/23 warningMsg('Please add ' + (component_limit-this.components.length) +' component');
                        warningMsg('Please fill all component details.');
                        is_validation = false;
                        return false;
                    }
                    $.each(this.components,function (key,component){
                       
                        if(component.colourCombos.length > combo_limit){
                           // warningMsg('Please add ' + (combo_limit-component.colourCombos.length) +' Combo / Colour');
                           warningMsg('Please fill all Combo / Colour details.');
                            is_validation = false;
                        }
                    });
                    
                    if($("#changeaction").val()!='' && $("#changeaction").val()==1){
                       if(is_validation){
                        var post_data = {
                            components:this.components,
                            std_size_ids:this.selected_ids,
                            pc_size_insert:(this.size_ids.length >= 1?1:0),
                            size_type:this.size_type,
                            totalsize:this.totalsize,
                            custom_size_values:(this.size_type==2?this.custom_size_values:[]),
                            updateaction:$("#updateaction").val(),
                            dyeingchange:$('#dyeing_change').val(),
                            draft_status:1
                        };
                        MakePostRequest(base_path + 'components/insertComponent',
                                    {post_data:post_data,enquiry_id:enquiry_id},
                                    'json', function (data) {
                                      //console.log(data);
                                      if(data.draftstatus==1) {
                                        let enquiryListPath = "merchant/orderEnquiryList";
                                        enquiryListPath = base_path+enquiryListPath;
                                        window.location.href = enquiryListPath;
                                    }
                                    });
                    }
                    }else{
                        let idValue = "<?php echo $enquiry_id; ?>"
                        let usertype="<?php echo $userType ?>";
                        let lastsegment="<?php echo $lastSegement ?>";
                        // commented by myself regards redirection to wip component list
                        // if(usertype==2){
                        //  let enquiryListPath = "management/enquiryview/"+encodeURIComponent(btoa(idValue)); 
                        //  enquiryListPath = base_path+enquiryListPath;
                        //  window.location.href = enquiryListPath;
                        // }else{
                        //  let enquiryListPath = "preCosting/index/"+encodeURIComponent(btoa(idValue));
                        //  enquiryListPath = base_path+enquiryListPath;
                        //  window.location.href = enquiryListPath;
                        // }
                        
                        if(usertype==2){
                         if(lastsegment!='wiplist') {
                         let enquiryListPath = "management/enquiryview/"+encodeURIComponent(btoa(idValue)); 
                         enquiryListPath = base_path+enquiryListPath;
                         window.location.href = enquiryListPath;
                         }else{
                         let enquiryListPath = "Merchant/wipPrecosting/"+encodeURIComponent(btoa(idValue)); 
                         enquiryListPath = base_path+enquiryListPath;
                         window.location.href = enquiryListPath;
                         }
                        }else{
                         if(lastsegment=='wiplist') {
                         let enquiryListPath = "Merchant/wipPrecosting/"+encodeURIComponent(btoa(idValue)); 
                         enquiryListPath = base_path+enquiryListPath;
                         window.location.href = enquiryListPath;
                         }else{
                         let enquiryListPath = "preCosting/index/"+encodeURIComponent(btoa(idValue));
                         enquiryListPath = base_path+enquiryListPath;
                         window.location.href = enquiryListPath;  
                         }
                         
                        }
                    }
                },
                backbtn:function () {
                    if(draftstatus==2){
                        let idValue = "<?php echo $enquiry_id; ?>";
                        let usertype="<?php echo $userType ?>";
                        let lastsegment="<?php echo $lastSegement ?>";
                        // alert(lastsegment);
                        if(usertype==2){
                         if(lastsegment!='wiplist') {
                         let enquiryListPath = "management/enquiryview/"+encodeURIComponent(btoa(idValue)); 
                         enquiryListPath = base_path+enquiryListPath;
                         window.location.href = enquiryListPath;
                         }else{
                         let enquiryListPath = "Merchant/wipPrecosting/"+encodeURIComponent(btoa(idValue)); 
                         enquiryListPath = base_path+enquiryListPath;
                         window.location.href = enquiryListPath;
                         }
                        }else{
                         if(lastsegment=='wiplist') {
                         let enquiryListPath = "Merchant/wipPrecosting/"+encodeURIComponent(btoa(idValue)); 
                         enquiryListPath = base_path+enquiryListPath;
                         window.location.href = enquiryListPath;
                         }else{
                         let enquiryListPath = "preCosting/index/"+encodeURIComponent(btoa(idValue));
                         enquiryListPath = base_path+enquiryListPath;
                         window.location.href = enquiryListPath;  
                         }
                         
                        }
                        
                    }else{
                        this.backbtnSubmit();
                    }
                }
            }
        });
        //console.log(obj.components);
       
        $('.my_tooltip')
        .tooltip({
            template: '<div class="tooltip" role="tooltip"><div class="arrow brc-purple-d2"></div>' +
                '<div class="shadow border-2 radius-2 brc-purple-d2 bgc-purple-l4 tooltip-inner text-dark-tp1 text-110 text-600 px-2 pb-15"></div></div>'
        });

       
        <?php if(!empty($sizes['selectedNames'])){ ?>
            <?php if(isset($components) && count($components)>0 && $components[0]['draft_status']==2) { ?>
            $('input').attr('readonly',true);
            $('select').attr('disabled',true);
            <?php } else { ?>
             $('input').attr('readonly',false);
             $('select').attr('disabled',false);
            <?php } ?>
            if(draftstatus==1){
                 $('#sizeRangeSelection').attr('readonly',true);
                 $('#TotalSize').attr('readonly',true);
                 $("#size_insert").addClass("hide");
             }
        <?php } else { ?>
            
            $('#addBtnRow').hide();
            $('#saveBtnRow').hide();
           
            setTimeout(function (){
                if(comp.length==0){            
                $('.add_component').trigger('click');
                }
                $('#addBtnRow').show();
                $('#saveBtnRow').show();               
            }, 1200);
            if(draftstatus==1){
                 $('#sizeRangeSelection').attr('readonly',true);
                 $('#TotalSize').attr('readonly',true);
                 $("#size_insert").addClass("hide");
             }
        <?php
            }
        ?>
    });

    let lastSementValue = "<?php echo $lastSegement; ?>";
        
    //commented by myself on 24_05_23 for showing buttons in disabed state
    // if(lastSementValue == 'wiplist') {
    //     $('.btncomponent').hide();
    //     $('#addBtnRow').hide();
    // }
    
 function onlyNumbernodecimal(evt) {  /// for allowing only number 

        // Only ASCII charactar in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        // console.log(ASCIICode);
        
       
        if (ASCIICode>46 && ASCIICode<58) {
            return true; 
        }
  
        return false; 
    } 
    function fnShowSubChartInfoChange(VarMasterChartId) {
        $('#frmOrderSizeChartList').css("border", "1px solid #d2d6de");
        $("#ErrOrderSizeChartList").text('');
        MakePostRequest(base_path + 'preCosting/getSizeCharts', "sc=" + VarMasterChartId, 'json', fnShowSubChartRes);
        return false;
    }
    function fnShowSubChartRes(data) {
        if (data != '') {
            if (data.errcode != undefined) {
                if (data.errcode == '404') {
                    fnCallSessionExpire();
                    return false;
                } else {
                    $("#divSubChartList").html(data.ss);
                }
            }
        }
    }

    $('#id-sweeralert-1').on('click', function() {
        var swalWithBootstrapButtons = Swal.mixin({
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Do you want to delete the details ?',
            //title: 'Are you sure?',
            //text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            scrollbarPadding: false,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            reverseButtons: true,
            customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
        }).then(function(result) {
            if (result.value) {
                swalWithBootstrapButtons.fire({
                    // title: 'Deleted successfully!',
                    title: 'Deleted!',
                    text: '',
                    type: 'success',
                    icon: 'success',
                    width: 460,
                    customClass: {'confirmButton': 'btn btn-info'}
                })
            } 
            // else if (result.dismiss === Swal.DismissReason.cancel) {
            //     swalWithBootstrapButtons.fire({
            //         title: 'Cancelled',
            //         text: '',
            //         type: 'error',
            //         icon: 'error',
            //         customClass: {'confirmButton': 'btn btn-secondary px-5'}
            //     })
            // }
        })
    });
    $(document).ready(function() {
    if($('#editstatus').val()==1 && draftstatus==2){
         $('.deletebtns').attr('disabled',true) ; 
         $('.iconbtn').addClass('icondisabled');
      }else{
         $('.deletebtns').attr('disabled',false) ; 
         $('.iconbtn').removeClass('icondisabled');
      }
    });
    function dyeing_change(value){
        if(value==2){
            $('#dyeing_change').val(1);
        }
    }
</script>
<style>
    /* new start*/
    .sz-rg-ptch {
        background: #f7f7f7;
        margin: 0px 0px 0px 5px;
    }
    #stdSize > label > input {
        border-radius: 50% !important;
    }
    /* new end*/
    .text-cyan-br{
        color: #055EE1;
    }
    .btn-royal-blue:focus, .btn-royal-blue.focus {
        box-shadow: 0 0 0 0 rgba(2, 43, 97, 0.5);
    }


    input[type="checkbox"] {
        border-radius: 0 !important;
    }


    .gray_header {
        background-color: #D0D1D1;
        color: #022B61;
    }

    .alert-royal-blue {
        color: #000000;
        background-color: #3489fa;
        border-color: #207efa;
    }

    .alert-royal-blue hr {
        border-top-color: #076ff9;
    }

    .alert-royal-blue .alert-link {
        color: #000000;
    }

    .badge-royal-blue {
        color: #fff;
        background-color: #022B61;
    }

    .badge-royal-blue[href]:hover, .badge-royal-blue[href]:focus {
        color: #fff;
        background-color: #00142f;
    }

    .bg-royal-blue {
        background-color: #022B61 !important;
    }

    a.bg-royal-blue:hover, a.bg-royal-blue:focus,
    button.bg-royal-blue:hover,
    button.bg-royal-blue:focus {
        background-color: #00142f !important;
    }

    .border-royal-blue {
        border-color: #022B61 !important;
    }


    .btn-royal-blue-gray{
        background-color: #ebecec;
        color: #022B61;
        border-color: #FEFEFE;
        padding: 5px 10px;
    }

    .btn-royal-blue-gray:hover{
        background-color: #ffffff;
        border-color: #A0A0A0;
    }

    .btn-royal-blue-secondary {
        color: #022B61;
        background-color: transparent;
        border-color: #093065;
    }
    .btn-royal-blue-secondary:hover {
        color: #ffffff;
        background-color: #022B61;
        border-color: #093065;
        box-shadow: 0 0 0 0.2rem rgba(2, 43, 97, 0.5);
    }

    .btn-royal-blue.disabled, .btn-royal-blue:disabled {
        color: #fff;
        background-color: #767679;
        border-color: #767679;
    }

    .btn-royal-blue:not(:disabled):not(.disabled):active, .btn-royal-blue:not(:disabled):not(.disabled).active, .show > .btn-royal-blue.dropdown-toggle {
        color: #fff;
        background-color: #00142f;
        border-color: #000e20;
    }

    .btn-royal-blue:not(:disabled):not(.disabled):active:focus, .btn-royal-blue:not(:disabled):not(.disabled).active:focus, .show > .btn-royal-blue.dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(2, 43, 97, 0.5);
    }
    .btn-royal-blue
    {
    color: #022B61!important;
    background-color: #ebecec!important;
    border-color: #D0D1D1!important;
    transition: none !important;
    font-size:12px!important;
    }
    .btn-royal-blue:hover {
    color: #fff!important;
    background-color: #011b3e!important;
    border-color: #00142f!important;
    font-size:12px!important;
    }
    .btn-outline-royal-blue {
        color: #022B61;
        background-color: transparent;
        border-color: #022B61;
    }

    .btn-outline-royal-blue:hover {
        color: #fff;
        background-color: #003B87;
        border-color: rgba(2, 43, 97, 0.71);
    }

    .btn-outline-royal-blue:focus, .btn-outline-royal-blue.focus {
        box-shadow: 0 0 0 0.2rem rgba(2, 43, 97, 0.5);
    }

    .btn-outline-royal-blue.disabled, .btn-outline-royal-blue:disabled {
        color: #022B61;
        background-color: transparent;
    }

    .btn-outline-royal-blue:not(:disabled):not(.disabled):active, .btn-outline-royal-blue:not(:disabled):not(.disabled).active, .show > .btn-outline-royal-blue.dropdown-toggle {
        color: #fff;
        background-color: #022B61;
        border-color: #022B61;
    }

    .btn-outline-royal-blue:not(:disabled):not(.disabled):active:focus, .btn-outline-royal-blue:not(:disabled):not(.disabled).active:focus, .show > .btn-outline-royal-blue.dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(2, 43, 97, 0.5);
    }

    .list-group-item-royal-blue {
        color: #000000;
        background-color: #207efa;
    }

    .list-group-item-royal-blue.list-group-item-action:hover, .list-group-item-royal-blue.list-group-item-action:focus {
        color: #000000;
        background-color: #076ff9;
    }

    .list-group-item-royal-blue.list-group-item-action.active {
        color: #fff;
        background-color: #000000;
        border-color: #000000;
    }

    .table-royal-blue,
    .table-royal-blue > th,
    .table-royal-blue > td {
        background-color: #207efa;
    }

    .table-hover .table-royal-blue:hover {
        background-color: #076ff9;
    }

    .table-hover .table-royal-blue:hover > td,
    .table-hover .table-royal-blue:hover > th {
        background-color: #076ff9;
    }

    .thm-submit-btn{
        text-transform:none!important;
        padding:0.25rem 0.5rem!important;
        border-radius:0.175rem!important;
        font-size:12px!important;
        box-shadow:none!important;
      
    }

    a.text-royal-blue:hover, a.text-royal-blue:focus {
        color: #00142f !important;
    }

    .icondisabled{
        color:#81838880!important;
    }
    .btn-sm, .btn-group-sm > .btn{
        font-size:12px!important;
    }
    /*.col-2 {
        border: 1px solid red !important;
    }
    .col-1 {
        border: 1px solid #131213 !important;
    }

    .col-3 {
        border: 1px solid #2600ff !important;
    }*/
</style>
<?php $this->load->view(CNFCOMPANY . 'template/footer'); ?>
<script src="<?php echo base_url();?>assets/js/commonfunctions.js?rn=<?php echo CNFJSCSSRANDNO?>"></script>
