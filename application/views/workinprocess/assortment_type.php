<?php
/**
 * @var array $components
 * @var array $sizes
 * @var int $enquiry_id
 */
$this->load->view(CNFCOMPANY . 'template/header');
$last = $this->uri->total_segments();
$lastSegment = $this->uri->segment($last);

if($lastSegment == 'edit') {
    $enquiry_id = $this->uri->segment($last-1);
}
else {
    $enquiry_id = $this->uri->segment($last);
}
$mode = $assortmentDetails["mode"];
// echo json_encode($assortmentType);
// echo "<pre>";
// echo json_encode($assortmentDetails["data"]);
// print_r($assortmentDetails["data"]);
// echo "</pre>";

?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.css" rel="stylesheet" /> -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/custom.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/wip.css"/>

<!-- Add Vue and Bootstrap-Vue JS just before the closing </body> tag -->
<div id="" class="pb-0 mb-0">

    <div class="d-flex packing-head">
        <h1 class="text-royal-blue">Select Assortment type</h1>
        <!-- <h1><i class="fa fa-angle-double-right text-90 " style="color: #FF9900"></i></h1>
        <h1 class="text-cyan-br"><a href="javascript:void(0);">Select Assortment type</a></h1> -->
        <div class="ml-auto pr-3">
            <a href="<?php echo base_url(); ?>/WorkInProcess/index/<?php echo $enquiry_id; ?>" 
                class="btn btn-sm mx-3 btn-royal-blue btn-text-slide-x ">
                <i class="btn-text-2  move-left fa fa-arrow-left text-120 align-text-bottom mr-2"></i>Back
            </a>
            <?php if($lastSegment == 'edit') { ?>
                <a href="javascript:void(0);" class="btn btn-sm mx-3 btn-royal-blue btn-text-slide-x" onClick="enableEdit()">
                    <i class="btn-text-2 move-left fa fa-pencil text-120 align-text-bottom mr-2"></i>Edit
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="col-12 pt-1" style="border-bottom: 1px solid #022b61; margin-bottom: 20px;"></div>
    <!-- <div class="pos-rel overflow-hidden radius-1 py-0 px-3" >
        <div class="pos-rel d-flex" >
            <div class="text-royal-blue ml-4 mb-0 mt-1 text-100" style="font-size: 14px !important; padding: 10px 5px">
                <span class=""> WIP Ref. No: </span>
            </div>
        </div>
    </div> -->

    <div class="col-12 p-0">
         <div class="col-12 pb-2 pt-3 pl-5 mar-b-15">MPG -Master Poly Bag  /  CAR-Cartone Assortment Ratio</div>
        <!-- <div v-if="seen" role="alert" class="alert alert-warning bgc-warning-l4 brc-warning-m3 border-2 d-flex align-items-center">
            <i class="fas fa-exclamation-circle mr-3 fa-2x text-orange"></i>
            <div class="text-dark-tp2">
                {{ $data.msg }}
            </div>

            <button type="button" class="close align-self-start ml-auto text-danger-d2 text-150" >
                <span @click="closeAlert">×</span>
            </button>
        </div> -->

        <div id="app11">
            <div v-for="(find, index) in components" class="card border-0 p-0">
                <div class="card border-0 p-0">
                    <div class="card-body border-0 pt-0" style="">
                        <div class="d-flex d-inline-flex d-inline w-100 col-12  border-0">
                            <div class="row col-6">
                                <div class="col-5 bgc-gray">
                                    <div class="col-12 pb-2 pt-3 mar-b-15">P.O. / Enq. Ref. No.</div>
                                    <div class="col-12">
                                        <input v-model="find.pono_enq_refno" type="text" readOnly
                                            class="form-control brc-on-focus brc-primary-m1 py-1 my-1">
                                    </div>
                                </div>

                                <div class="col-7 bgc-gray">
                                    <div class="col-12 pb-2 pt-3 mar-b-15">Assortment Type</div>
                                    <div class="col-12">
                                        <select class="form-control brc-on-focus brc-primary-d1 py-1 my-1 initialHide" 
                                                v-model="find.assortment_type"
                                                @change="onChangeAssortType($event, index, find)">
                                            <option value="0">Select</option>
                                            <option v-for="item in assort_type_value" :key="item.assortment_id" :value="item.assortment_id">
                                                {{ item.type }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div v-if="find.assortment_type > 4" class="row col-6">
                                <div class="col-6 bgc-gray">
                                    <div class="d-flex d-inline-flex d-inline w-100">
                                        <div class="col-11 pl-0 pt-3" >No. of Combo / Colour <br> Assortments</div>
                                    </div>
                                    
                                    <div v-for="(colorItem, colorIndex) in find.colourCombos" class="d-fle">
                                    <!-- {{colorItem}} -->
                                        <!-- {{find.ids}} -->
                                        <div class="col-11 p-0 pb-3 initialHide">
                                            <?php if($mode == "edit") { ?>
                                            <select :class="`form-control brc-on-focus brc-primary-d1 py-1 my-1 js-example-basic-multiple comboColor${index}${colorIndex}`" 
                                                    multiple>
                                                <!-- <option v-for="(colorValue) in find.sourceColourCombos" :value="colorValue.po_combo" 
                                                    :selected="colorItem.includes(colorValue.po_combo)"> -->
                                                <option v-for="(colorValue) in find.sourceColourCombos" :value="colorValue.po_combo" 
                                                :selected="colorItem.includes(colorValue.po_combo)">
                                                    {{ colorValue.po_combo }} 
                                                </option>
                                            </select>
                                            <?php } else { ?>
                                                <select :class="`form-control brc-on-focus brc-primary-d1 py-1 my-1 js-example-basic-multiple comboColor${index}${colorIndex}`" 
                                                        multiple>
                                                    <option v-for="(colorValue) in find.sourceColourCombos" :value="colorValue.po_combo">
                                                        {{ colorValue.po_combo }}
                                                    </option>
                                                </select>
                                            <?php }  ?>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="col-6 bgc-gray">
                                    <div class="d-flex d-inline-flex d-inline w-100">
                                        <div class="col-11 pl-0 pt-3">No. of Size <br> Assortments</div>
                                    </div>
                                    <div v-for="(sizeItem, sizeIndex) in find.sizes" class="d-flex">
                                        <div class="col-11 p-0 pb-3 initialHide">
                                            <?php if($mode == "edit") { ?>
                                                <select :class="`form-control brc-on-focus brc-primary-d1 py-1 my-1 js-example-basic-multiple noOfSizeAssortments${index}${sizeIndex}`"
                                                        multiple="multiple">
                                                    <!-- <option v-for="(sizeValue) in find.sourceSizes" :value="sizeValue.size_name" 
                                                            :selected="sizeItem.includes(sizeValue.size_name)"> -->
                                                    <option v-for="(sizeValue) in find.sourceSizes" :value="sizeValue.size_name" 
                                                    :selected="sizeItem.includes(sizeValue.size_name)">
                                                        {{ sizeValue.size_name }} 
                                                    </option>
                                                </select>
                                            <?php } else { ?>
                                                <select :class="`form-control brc-on-focus brc-primary-d1 py-1 my-1 js-example-basic-multiple noOfSizeAssortments${index}${sizeIndex}`"
                                                    multiple="multiple">
                                                    <option v-for="(sizeValue) in find.sourceSizes" :value="sizeValue.size_name">
                                                        {{ sizeValue.size_name }} 
                                                    </option>
                                                </select>
                                            <?php }  ?>
                                        </div>
                                        <div v-if="sizeIndex == 0">
                                            <div @click="addColorCombos(index)" title data-toggle="tooltip" data-placement="top"
                                                data-original-title="Add More Color Combo"
                                                class="mt-2 text-right card-toolbar-btn text-green text-110">
                                                <i class="fa fa-plus"></i>
                                            </div>
                                        </div>
                                        <div v-else>
                                            <div @click="removeColorCombos(index, sizeIndex, find.ids[sizeIndex])" title data-toggle="tooltip" data-placement="top"
                                                data-original-title="Remove Color Combo" class=" mt-2 my_tooltip pr-2 card-toolbar-btn text-danger-m1 text-110">
                                                <i class="fa fa-times"></i>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="card-footer bgc-white d-inline-flex d-inline d-inline-block row w-100 col-12 py-0 my-0" style=" border-top: transparent !important; border-bottom: 0 solid #022B61; background-color: white !important;">
                <div class="col-10 text-right pr-6" id="saveBtnRow">
                    <button @click="formSubmit" class="btn btn-info thm-submit-btn pu mb-1 btncomponent btnsavee">
                        SAVE 
                    </button>
                </div>
            </div>
        </div>
    </div>
    <pre id="print" class="bg-danger"></pre>
    <pre id="print1"></pre>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.16/vue.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script> -->
<script>
    // *** INITIALLY LOADS VARIABLES FROM PHP DATA *** //

    let lastsegmentt = "<?php echo $lastSegment;?>";
    if(lastsegmentt == 'edit') {
        $(".initialHide").prop('disabled', true);
        $(".initialHide").css("pointer-events", "all");
        $(".btnsavee").hide();
    }
    else {
        $(".initialHide").prop('disabled', false);
        $(".initialHide").css("pointer-events", "all");
        $(".btnsavee").show();
    }

    function enableEdit() {
        $(".initialHide").prop('disabled', false);
        $(".initialHide").css("pointer-events", "all");
        $(".btnsavee").show();
    }

    $(document).ready(function() {
        
        createMultipleSelect();
        var assortmentTypeValues = <?= json_encode($assortmentType) ?>;

     
        let comp = <?= json_encode($assortmentDetails["data"]) ?>;

        obj = new Vue({
            el: '#app11',
            data: {
                components: comp,
                assort_type_value: assortmentTypeValues,
                // seleted: ['combo-1'],
            },
            methods: {
                onChangeAssortType: function (event, index, itemValue) {
                    if(event.target.value >= 4) {
                        if(itemValue.colourCombos.length == 0) {
                            this.addColorCombos(index);
                        }
                        createMultipleSelect();
                    }
                    else {

                    }
                },
                closeAlert: function () {
                    this.seen = false;
                },
                addColorCombos:function (index) {
                    // this.components[index].colourCombos.push({id:0, uniqueId:'', names: [], sizes: ''});
                    // this.components[index].sizes.push({id:0, names:''});
                    this.components[index].colourCombos.push([]);
                    this.components[index].sizes.push([]);
                    this.components[index].ids.push([]);
                    createMultipleSelect();
                    $(".initialHide").prop('disabled', false);
                    $(".initialHide").css("pointer-events", "all");
                    $(".btnsavee").show();
                },
                removeColorCombos:function (index, colorIndex, ids) {
                    // var comboId = this.components[index].colourCombos[colorIndex].id;
                    var comboId = this.components[index].colourCombos[colorIndex].length;
                    if(comboId === 0){
                        this.removeCombo(index, colorIndex);
                    } else {
                        //console.log(ids);
                        var swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                        var kar = this;
                        swalWithBootstrapButtons.fire(
                            {
                                title: 'Are you sure, you want to delete this ?',
                                text: "If you delete, all the related contents will be removed, you can't revert it!",
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
                                var dataform = new FormData();
                                dataform.append('data', ids);
                                let request = $.ajax({
                                    type: "POST",
                                    url: base_path + 'WorkInProcess/deleteAssortEntry',
                                    data: dataform,
                                    processData: false,
                                    contentType: false,
                                    cache: false,
                                    success: function (data) {
                                        swalWithBootstrapButtons.fire({title: 'Deleted!',text: 'Operation completed successfully.',type: 'success',icon: 'success',customClass: {'confirmButton': 'btn btn-info px-5'}});
                                        location.reload();
                                    },
                                    error: function () {
                                        console.log("Error");
                                    }
                                });                                
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                swalWithBootstrapButtons.fire({
                                    title: 'Cancelled',
                                    text: 'Cancelled successfully.',
                                    type: 'error',
                                    icon: 'error',
                                    customClass: {'confirmButton': 'btn btn-secondary px-5'}
                                });
                            }
                        });
                    }
                },
                removeCombo:function (index,colorIndex){
                    if(this.components[index].colourCombos.length>1) {
                        this.components[index].colourCombos.slice(colorIndex, 1)
                        this.$delete(this.components[index].colourCombos,colorIndex);
                        
                        this.components[index].sizes.slice(colorIndex, 1)
                        this.$delete(this.components[index].sizes,colorIndex);
                        
                        this.components[index].sizes.slice(colorIndex, 1)
                        this.$delete(this.components[index].ids,colorIndex);
                    }
                },
                formSubmit: function ()
                {
                    // console.log(this.components);
                    let mode = "<?php echo $mode; ?>";
                    let is_validation = true;
                    let dataValue = [];
                    let errorCount = 0;
                    $.each(this.components,function (key,component) {
                        
                        //console.log(component.assortment_type);
                        let assrt = component.assortment_type;
                        if( assrt == "0") {
                            errorCount +=1;
                            return false;
                        }
                        else if( assrt == "1" || assrt == "2" || assrt == "3" || assrt == "4") {

                            let res_data = {};
                            res_data.enquiry_id = component.enquiry_id;
                            res_data.po_enq_id = component.po_enq_id;
                            res_data.pono_enq_refno = component.pono_enq_refno;
                            res_data.assortment_type = component.assortment_type;
                            res_data.pck_id = "";
                            dataValue.push(res_data);
                        }
                        else if( assrt == "5" || assrt == "6" || assrt == "7" || assrt == "8") {
                            for(let i=0;i<component.colourCombos.length;i++)
                            {
                                let val = $('.comboColor'+key+i).val();
                                let val2 = $('.noOfSizeAssortments'+key+i).val();

                                if(val.length == 0 || val2.length == 0) {
                                    errorCount +=1;
                                }

                                let res_data = {};

                                res_data.uniqueId = i+1;
                                res_data.names = val;
                                res_data.sizes = val2;
                                res_data.enquiry_id = component.enquiry_id;
                                res_data.po_enq_id = component.po_enq_id;
                                res_data.pono_enq_refno = component.pono_enq_refno;
                                res_data.assortment_type = component.assortment_type;
                                res_data.editStatus = component.editStatus;

                                if(mode == 'edit') {
                                    res_data.pck_id = component.pck_id;
                                    res_data.pck_combo_color_id  = component.ids;
                                }
                                else {
                                    res_data.pck_id = "";
                                    res_data.pck_combo_color_id  = [];
                                }
                                dataValue.push(res_data);
                            }
                        }
                    });

                    // console.log(errorCount);
                    // console.log($(".noOfSizeAssortments").val());
                    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                    if(errorCount == 0) {
                        updateDetails();
                    }
                    else {
                        swalWithBootstrapButtons.fire({
                            title: 'Warning',
                            text: "Please fill all free text and select fields",
                            icon: 'warning',
                            confirmButtonText: 'OK',
                            customClass: {'confirmButton': 'btn btn-secondary px-5'}
                        });
                    }

                    function updateDetails() {
                        swalWithBootstrapButtons.fire(
                        {
                            title: 'Are you sure, you want to save this ?', text: "", type: 'warning', showCancelButton: true, scrollbarPadding: false, confirmButtonText: 'Yes', cancelButtonText: 'No', reverseButtons: true, customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                        }
                        ).then(function(result) {
                            if (result.value) {

                                var dataform = new FormData();
                                dataform.append('dataValue', JSON.stringify(dataValue));
                                dataform.append('mode', mode);
                                let request = $.ajax({
                                    type: "POST",
                                    url: base_path + 'WorkInProcess/updateAssortmentDetails',
                                    data: dataform,
                                    processData: false,
                                    contentType: false,
                                    cache: false,
                                    success: function (data) {
                                        
                                        // let enquiryListPath = "merchant/orderEnquiryList";
                                        // enquiryListPath = base_path+enquiryListPath;
                                        // setTimeout(function() { 
                                        //     window.location.href = enquiryListPath;
                                        // }, 1000);
                                        window.history.back();
                                    },
                                    error: function () {
                                        console.log("Error");
                                    }
                                });   
                                                        
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                swalWithBootstrapButtons.fire({
                                    title: 'Cancelled',
                                    text: 'Cancelled successfully.',
                                    type: 'error',
                                    icon: 'error',
                                    customClass: {'confirmButton': 'btn btn-secondary px-5'}
                                });
                            }
                        });
                    }
                }
            },
        });
        
    });


 
</script>
<?php $this->load->view(CNFCOMPANY . 'template/footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function createMultipleSelect() {
        setTimeout(() => {
            $('.js-example-basic-multiple').select2();
        }, 100);
    }
</script>
