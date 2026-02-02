<?php $this->load->view(CNFCOMPANY . 'template/pageheader'); 
$ArrProfileInfo = fnGetUserLoggedInfo(1);
?>
    <!--<body class="sidebar-mini skin-black wysihtml5-supported sidebar-collapse">-->
    <body class="layout-top-nav">
<div class="wrapper">
    <?php $this->load->view(CNFCOMPANY . 'template/badmintemplateheader');
    // $this->load->view(CNFCOMPANY . 'template/templateheader'); ?>
    <aside class="main-sidebar hide">
        <?php // $this->load->view(CNFCOMPANY . 'template/templateleftmenu'); ?>
    </aside>
    <div class="content-wrapper">
        <section class="content">
            <section class="invoice form-horizontal">
                <div class="row"> 
                    <div class="col-xs-12">
                        <h2 class="page-header text-royal-black mx-4" style="border-bottom:0px"> <?php echo (empty($Edit) && empty($VaruserId)) ? 'Add Subscriber Login':'View / Edit Subscriber Login' ?>
                          <div class="pull-right">
                                            <input type="hidden" id="id" value="<?php if (!empty($BasicInfo->id)) { echo $BasicInfo->id;} ?>">
                                            <div class="ml-auto pr-3">
                                             <a href="<?php echo base_url(CNFBADMIN . 'msubscription/manage') ?>" id="backbtn" class="btn custbtn btn-sm mx-3 btn-royal-blue btn-text-slide-x">Back</a>
                                            <?php 
                                                if(isset($Edit) && (empty($Edit))) { echo ''; } else {
                                            ?>
                                            <a id="editEnable" class="btn custbtn btn-royal-blue btn-sm px-3" onclick="$('#svbtn').show()">Edit</a>
                                            <?php } ?>
                                            </div>
                                        </div>
                        <div class="col-sm-12 " style="padding: 7px 25px;border-bottom: 1px solid #022B61;"></div>
                        </h2>
                        <h4 class="mr-2 py-2 text-royal-blue">
                        </h4>
                    </div><!-- /.col -->
                </div>
                <div class="row no-rad-form add-form-mar" id="custom_form">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="hide">
                                <div class="form-group">
                                    <div class="col-sm-12 text-center" style="color:navy;font-size:22px;font-weight:600">
                                        AZIBO INFOTECH PRIVATE LIMITED
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <div class="col-sm-12 text-center" style="font-size:14px;font-weight:600">
                                        LOGIN
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-3 control-label">
                                            User Name <span class="mandatory">*</span>
                                        </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="username" value="<?php if (isset($BasicInfo) && !empty($BasicInfo->contactname)) {echo $BasicInfo->contactname;}else{ echo $subscriberrefno;} ?>" placeholder="Free Text" disabled>
                                        <input type="hidden" id="subscriber_id" value="<?php echo $subscriber_id;?>">
                                        <div class="herr" id="Errusername"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-3 control-label">
                                            Company Prefix <span class="mandatory">*</span>
                                        </label>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" id="company_prefix" value="<?php if (isset($companyprefix) && !empty($companyprefix)){ echo $companyprefix; }?>"  placeholder="Free Text" >
                                        <div class="herr" id="Errcompany_prefix"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                        <label for="id-form-field-focus-1" class="col-sm-3 control-label">
                                           Login ID <span class="mandatory">*</span>
                                        </label>
                                    <div class="col-sm-6">
                                        <div class="col-sm-6 pr-0">
                                        <input type="text" class="form-control" id="login_prefix" value="<?php if (isset($loginprefix) && !empty($loginprefix)){ echo $loginprefix; }?>"   placeholder="Free Text" >
                                        </div>
                                        <div class="col-sm-6 pl-0">
                                        <input type="text" class="form-control" id="login_suffix" value="<?php if (isset($loginsuffix)){ echo $loginsuffix; } ?>" disabled placeholder="Free Text" >
                                        </div>
                                        <div class="herr" id="Errloginid"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group">
                                    <label for="id-form-field-focus-1" class="col-sm-3 control-label">
                                       Password: <span class="mandatory">*</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="text" id="password" name="password" class="form-control" disabled placeholder="Free Text" value="<?php if (isset($BasicInfo) && !empty($BasicInfo->password)) { echo $BasicInfo->password;}else { echo $randompwd;} ?>">
                                        <div class="herr" id="Errpassword"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                <div class="row">
                    <div class="col-xs-12 py-4" style="padding-right:30px">
                        <button class="btn btn-info pull-right mx-2" id="svbtn" onclick="return fnSave();">Save</button>
                    </div>
                </div>
            </section>     
        </section>
    </div>
    <?php $this->load->view(CNFCOMPANY . 'template/templatefooter'); ?>
    <div class="control-sidebar-bg"></div>
</div>
<script src="<?php echo base_url() ?>assets/js/<?php echo CNFBADMIN ?>login.js"></script>
<?php $this->load->view(CNFCOMPANY . 'template/pagefooter'); ?>
<script>
 var GlbId = "<?php echo isset($VaruserId)?$VaruserId:''; ?>";
 
 if(GlbId!=''){
        $('#svbtn').hide();
        $('#login_prefix').prop("disabled", true);
        $('#company_prefix').prop("disabled", true);
    }
    // $(document).ready(function() {
    //   generatePassword();
    // });
    // function generatePassword() {
    //         var length = 8; // You can change the length of the password
    //         var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+{}[]:;<>,.?/~"; // Characters to include in the password
    //         var password = "";

    //         for (var i = 0; i < length; i++) {
    //             var randomIndex = Math.floor(Math.random() * charset.length);
    //             password += charset.charAt(randomIndex);
    //         }

    //         document.getElementById("password").value = password;
    // }
</script>