<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
	  <ul class="sidebar-menu">
		<li class="header">MAIN NAVIGATION</li>
		<li class="treeview">
			<a href="<?php echo base_url()?>dashboard/">
				<i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa"></i>
			</a>
		</li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i> <span>Config.</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 1 <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().CNFCOMPANY?>macceptancelevel/manage">Acceptance Level</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mauth/manageapprovalauth">Approval Authority</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mbom/manage/">Bill Of Materials</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mbomsourcing/managebomsrc/">Bill Of Materials<br/>Sourcing & Supplier</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mbrand/manage/">Brands</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mbuyer/manage/">Buyers</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mastersetup/managechecklist/">Check list</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mcolormatchstd/manage">Colour Match Standard</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 2 <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mdyeingmethod/managedyeingmethod/">Dyeing Method</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mdyeintype/managedyeintype/">Dyeing Type</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mdsr/managedsr/">Dyeing Special<br/>Request</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>membelltype/manageembelltype/">Embellishmnet</a></li>
                            <li>
                                <a href="<?php echo base_url().CNFCOMPANY?>mastersetup/managemodeofenquiry/">Mode of Enquiry</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url().CNFCOMPANY?>mastersetup/manageenquirytype/">Enquiry Type</a>
                            </li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mfabrictype/managefabricknit/">Fabric Details - Knit</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mfabrictype/managefabricwoven/">Fabric Details - Woven</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mwpg/managewpg/">Fabric Finish </a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mdpf/managedpf/">Fabric Finish Stage / Form</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 3 <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mgarmentsampling/manage/">Garment Sampling<br/>Requirement</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mgpd/managegpd/">Garment Parts</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mauth/manageinspectionauth">Inspection Authority</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mlab/managelab/">Lab</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mlotinspection/manage/">Lot Inspection <br/>Details</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mlogistics/manage">Logistics Details</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mpackingmaterial/managepackingmaterial/">Packing Material</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mpackingcode/managepackingcode/">Packing Code</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 4 <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mprocessflow/manageprocessflow/">Process Flow</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mport/manageport/">Port</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>msizerange/managesizerange/">Size Range</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mauth/managetauth">Testing Authority</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>munitmeasure/manageunitmeasure/">Unit of Measure</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>myarn/manageyarnpurchasetype/">Yarn Purchase Type</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>myarn/manageyarn/">Yarn Spec. Request</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>myarnvendor/manageyarnvendor">Yarn Vendor</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>myarncount/manageyarncount/">Yarn Details</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> CAD Master Data <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mcadrequirement/managecadrequirement/">CAD Requirement</a></li>

                        </ul>
                    </li>

                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="fa fa-gears"></i><span>Merchant</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('merchant/manageenquiry') ?>">Enquiry List</a></li>
                    <li><a href="<?php echo base_url()."merchant/managewip"?>">WIP List</a></li>
                    <li><a href="<?php echo base_url()?>merchant/mcadrequest/managecadrequest">CAD REQUEST LIST</a></li>
                </ul>

            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i><span>CAD</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url().CNFCOMPANY?>mcadrequest/cadreceivedlist">RECEIVED LIST</a></li>
                    <li><a href="<?php echo base_url().CNFCOMPANY?>mcadrequest/cadqueuelist">QUEUE LIST</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#"><i class="fa fa-gears"></i> <span>Config.</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 1 <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().CNFCOMPANY?>macceptancelevel/manage">Acceptance Level</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mauth/manageapprovalauth">Approval Authority</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mbom/manage/">Bill Of Materials</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mbomsourcing/managebomsrc/">Bill Of Materials<br/>Sourcing & Supplier</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mbrand/manage/">Brands</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mbuyer/manage/">Buyers</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mastersetup/managechecklist/">Check list</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mcolormatchstd/manage">Colour Match Standard</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 2 <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mdyeingmethod/managedyeingmethod/">Dyeing Method</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mdyeintype/managedyeintype/">Dyeing Type</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mdsr/managedsr/">Dyeing Special<br/>Request</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>membelltype/manageembelltype/">Embellishmnet</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mastersetup/managemodeofenquiry/">Mode of Enquiry</a></li>

                            <li><a href="<?php echo base_url().CNFCOMPANY?>mastersetup/manageenquirytype/">Enquiry Type</a></li>
                            
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mfabrictype/managefabricknit/">Fabric Details - Knit</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mfabrictype/managefabricwoven/">Fabric Details - Woven</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mwpg/managewpg/">Fabric Finish </a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mdpf/managedpf/">Fabric Finish Stage / Form</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 3 <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mgarmentsampling/manage/">Garment Sampling<br/>Requirement</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mgpd/managegpd/">Garment Parts</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mauth/manageinspectionauth">Inspection Authority</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mlab/managelab/">Lab</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mlotinspection/manage/">Lot Inspection <br/>Details</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mlogistics/manage">Logistics Details</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mpackingmaterial/managepackingmaterial/">Packing Material</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mpackingcode/managepackingcode/">Packing Code</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> Master Data Conf. 4 <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mprocessflow/manageprocessflow/">Process Flow</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mport/manageport/">Port</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>msizerange/managesizerange/">Size Range</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mauth/managetauth">Testing Authority</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>munitmeasure/manageunitmeasure/">Unit of Measure</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>myarn/manageyarnpurchasetype/">Yarn Purchase Type</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>myarn/manageyarn/">Yarn Spec. Request</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>myarnvendor/manageyarnvendor">Yarn Vendor</a></li>
                            <li><a href="<?php echo base_url().CNFCOMPANY?>myarncount/manageyarncount/">Yarn Details</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-circle-o"></i> CAD Master Data <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url().CNFCOMPANY?>mcadrequirement/managecadrequirement/">CAD Requirement</a></li>

                        </ul>
                    </li>

                </ul>
            </li>

      </ul>
	</section>
	<!-- /.sidebar -->