<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); ?>
<body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/badmintemplateheader');?>
    <div class="content-wrapper">
        <section class="content">
            <section class="invoice form-horizontal">
                <div class="row"> 
                    <div class="col-xs-12">
                <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"><?php  echo $ArrUserType[$usertype_id].'-';?>  View / Edit User Roles
                  <div class="pull-right">
                                    <div class="ml-auto pr-3">
                                    <a  id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                                    <?php  
                                        if(isset($ArrRoleInfo) && count($ArrRoleInfo)==1 && in_array("", $ArrRoleInfo)) { echo ''; } else {
                                    ?>
                                    <?php if($proformastatus==2 || $confirmstatus==1) {?>
                                    <a id="editEnable_disabled" disabled class="btn custbtn btn-royal-blue btn-sm px-3" >Edit</a>
                                    <?php } else { ?>
                                    <a id="editEnable"  class="btn custbtn btn-royal-blue btn-sm px-3" >Edit</a>
                                    <?php }} ?>
                                    </div>
                                </div>
                <div class="col-sm-12 " style="padding: 7px 25px;border-bottom: 1px solid #022B61;"></div>
                </h2>
                <h4 class="mr-2 py-2 text-royal-blue">
                </h4>
            </div><!-- /.col -->
                </div>
                <div class="row no-rad-form add-form-mar" id="custom_form">
                    <input type="hidden" id="subscriber_id" value="<?php echo $subscriber_id ;?>">
                     <input type="hidden" id="dept_id" value="<?php echo $usertype_id ;?>">
                     <input type="hidden" id="proforma_id" value="<?php echo $proforma_id ;?>">
                    <input type="hidden" id="userid" value="">
                    <input type="hidden" id="editvariable" value="<?php  echo (isset($ArrRoleInfo) && count($ArrRoleInfo)==1 && (in_array("", $ArrRoleInfo))) ? '1' : '2';?>">
                    <input type="hidden" id="statuscond" value="<?php echo $Arrdeptwiseinfostatus;?>">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-md-7 control-label labelclr">
                                     Purchase Indent List
                                    </label>
                                <div class="col-md-5">
                                   <input type="checkbox" name="title" value="Purchase Indent List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Purchase Indent List',$ArrRoleInfo)) { echo 'checked';}?> >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-md-7 control-label labelclr">
                                     Supply Closure List
                                    </label>
                                <div class="col-md-5">
                                   <input type="checkbox" name="title" value="Supply Closure List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Supply Closure List',$ArrRoleInfo)) { echo 'checked';}?> >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-md-7 control-label labelclr">
                                     M.I. List
                                    </label>
                                <div class="col-md-5">
                                   <input type="checkbox" name="title" value="M.I. List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('M.I. List',$ArrRoleInfo)) { echo 'checked';}?> >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-md-7 control-label labelclr">
                                     Order Stock List
                                    </label>
                                <div class="col-md-5">
                                   <input type="checkbox" name="title" value="Order Stock List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Order Stock List',$ArrRoleInfo)) { echo 'checked';}?> >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-md-7 control-label labelclr">
                                     Order Closure List
                                    </label>
                                <div class="col-md-5">
                                   <input type="checkbox" name="title" value="Order Closure List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Order Closure List',$ArrRoleInfo)) { echo 'checked';}?> >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-md-7 control-label labelclr">
                                     D.C. List
                                    </label>
                                <div class="col-md-5">
                                   <input type="checkbox" name="title" value="D.C. List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('D.C. List',$ArrRoleInfo)) { echo 'checked';}?> >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-md-7 control-label labelclr">
                                     Surplus Stock List
                                    </label>
                                <div class="col-md-5">
                                   <input type="checkbox" name="title" value="Surplus Stock List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Surplus Stock List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-md-7 control-label labelclr">
                                    Stock Transfer Memo List
                                    </label>
                                <div class="col-md-5">
                                   <input type="checkbox" name="title" value="Stock Transfer Memo List" <?php if(isset($ArrRoleInfo) && count($ArrRoleInfo)>0 && in_array('Stock Transfer Memo List',$ArrRoleInfo)) { echo 'checked';}?>>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 py-4" style="padding-right:30px">
                        <button class="btn btn-info pull-right mx-2" id="svbtn"  disabled="true">Save</button>
                    </div>
                </div>
        </section>     
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
<script>
    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    var subscriber_id = $("#subscriber_id").val();
    var department_id = $("#dept_id").val();
    var proforma_id = $("#proforma_id").val();
    
    $('#backbtn').on('click', function() {
    let redirectpath = base_path + GlbBAdminFdr +  'msubscription/detviews/' + encodeURIComponent(base64_encode(subscriber_id)) + '/' + encodeURIComponent(base64_encode(proforma_id));
                 window.location.href = redirectpath;
    });
     $('#svbtn').click(function (){
        swalWithBootstrapButtons.fire(
            {
                title: 'Do you want to save the details ?',
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
                var items = document.getElementsByName('title');
                var deptid = $("#dept_id").val();
                var selectedItems=[];
                   for(var i=0; i<items.length; i++){
                        if(items[i].type=='checkbox' && items[i].checked==true){
                            selectedItems.push(items[i].value);
                        } 
                    }
                        
                    const dataToSend = {
                                    rfrom: 1,
                                    dept_id: deptid,
                                    subscriber_id:subscriber_id,
                                    proforma_id:proforma_id,
                                    statuscheck:'',
                                    object:'',
                                    title: selectedItems.join(','), // Convert array to a comma-separated string
                    };
                    MakeAsynPostRequest(base_path + GlbBAdminFdr + "msubscription/updateInfo",dataToSend, "json",function (data) {
                        if (data != '') {
                            if (data.errcode == '404') {
                            fnCallSessionExpire();
                            return false;
                        } else if (data.errcode == -1) {
                            swalWithBootstrapButtons.fire({
                                title: data.msg,type: 'warning',
                                icon: 'warning',
                                customClass: {'confirmButton': 'btn btn-info'}
                            });
                            return false;
                        } else if (data.errcode == 1 || data.errcode == 2) {
                                swalWithBootstrapButtons.fire({
                                            title: 'Saved!',type: 'success',
                                            icon: 'success',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                }).then((result) => {
                                    // let redirectpath = base_path + GlbBAdminFdr + 'msubscription/detviews/'+ encodeURIComponent(base64_encode(subscriber_id));
                                    // window.location.href = redirectpath;
                                     location.reload();
                                   
                                });
                        }
                    }
                });
            }else if (result.dismiss === Swal.DismissReason.cancel) {
             
            }
        });
    });  
</script>
<script src="<?php echo base_url(); ?>assets/js/badmin/rolepermission.js"></script>