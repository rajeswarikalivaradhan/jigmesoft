<?php
//////////// newly included for business admin ////
define('KN_MASTER_UNITRATE','tbl_unitrate_master');
define('KN_MASTER_DESCRIPTION','tbl_description_master');
define('KN_MASTER_DETAILS','tbl_details_master');
define('KN_MASTER_PACKAGE','tbl_package_master');
define('KN_SUBSCRIBERENQUIRY','tbl_subscriber_enquiry');
define('KN_PROFORMAINVOICE','tbl_proforma_invoice');
define('KN_PROFORMAINVOICEDET','tbl_proformainvoice_detail');
define('KN_SUBSCRIBERLIST','tbl_subscriber_list');
define('KN_MNGUSERDEPTCNT','tbl_mnguser_deptcount');
//////////////////////////////////////////////////////
define('KN_BOM_MASTER','tbl_bom_master');// bom2
define('KN_BOM1_MASTER','tbl_bom1_master');// bom1
//define('KN_USERS','kn_users'); // regard this renamed to kn_mngusers.
define('KN_USERS','kn_mngusers');
define('KN_MUSERS','kn_mngusers');// newly included to manage users
define('KN_USER_DETAILS','kn_user_details');
define('KN_USERROLE_PERMISSION','kn_role_permission');
define('KN_DEPTROLE_PERMISSION','tbl_department_permission');
define('KN_ROLE_PERMISSION_MASTER','tbl_role_permission_master');
/*------------------------MASTER TABLES AtoZ------------------------*/
define('KN_MASTER_ACCEPTANCE_LEVEL', 'kn_master_acceptance_level');
define('KN_MASTER_EXT_LAB','kn_master_ext_lab');
define('ORDER_ENTRY_MASTER_BAG_CARTON_ASSORT_RATIO','order_entry_master_bag_carton_assort_ratio');
define('KN_MASTER_BUYER','kn_master_buyer');
define('KN_MASTER_BRANDBUYER','kn_master_brandbuyer'); // newly added for brand
define('KN_MASTER_TESTINGAUTHORITY','kn_master_testing_authority'); // newly added for testing authority
define('KN_MASTER_AGENTS','kn_master_agents'); // newly added for clearingagent,forwarding,importer,consignor,consignee managed in this table with type
define('KN_MASTER_BRANDS','kn_master_brandbuyer'); // changed to new table by renaming table with new one
// define('KN_MASTER_BRANDS','kn_master_brands'); //old one
define('KN_MASTER_BOM','kn_master_bom');
define('KN_MASTER_COLOR_MATCH_STD','kn_master_color_match_std');
define('KN_MASTER_DYEING_SPECIAL_REQUEST','kn_master_dyeing_special_request');
define('KN_MASTER_EMBELLISHMENT_TYPE','kn_master_embellishment_type');
define('KN_MASTER_MEDIUM_MATERIAL','kn_master_medium_material');
define('KN_MASTER_FABRIC_FINISH_WET_DRY','kn_master_fabric_finish_wet_dry');
define('KN_MASTER_PACKING_CODE','kn_master_packing_code');
define('KN_MASTER_PORT','kn_master_port');
define('KN_MASTER_PROCESS_FLOW','kn_master_process_flow');
define('KN_MASTER_LAB','kn_master_lab');
define('KN_MASTER_GARMENT_PART_DESC','kn_master_garment_part_desc');
define('KN_MASTER_PACKING_MATERIAL','kn_master_packing_material');
define('KN_COMPANY_DETAILS','kn_company_details');
//define('KN_COMPANY_MANAGEMENT','kn_company_management');
define('KN_MGMT_ROLES','kn_mgmt_roles');
define('KN_COMPANY_CONTACT_DETAILS','kn_company_contact_details');
define('KN_COUNTRIES','kn_countries');
define('KN_CONSTANTS','kn_constants');
define('KN_INDSTATES','kn_states');
define('KN_COMPANY_ORDERS','kn_company_orders');

define('KN_MASTER_TYPE_MEDIUM','kn_master_type_medium');
define('KN_MASTER_YARN_MISC','kn_master_yarn_misc');
define('KN_MASTER_BOM_MISC','kn_master_bom_misc');
define('KN_MASTER_YARN_SPL_REQ','kn_master_yarn_spl_req');
define('KN_MASTER_YARN_VENDOR','kn_master_yarn_vendor');
// define('KN_MASTER_CHECKLIST','kn_master_checklist');
define('KN_MASTER_CHECKLIST','tbl_checklist');
define('KN_MASTER_CURRENCY_AMOUNT','kn_master_currency_amount');
define('KN_MASTER_INVOICEDOCUMENT','kn_master_invoicedocument');
define('KN_MASTER_MODEOFENQUIRY','kn_master_modeofenquiry');
define('KN_ORDER_ENQUIRY','kn_order_enquiry');
define('KN_ORDER_ENQUIRY_LOG','kn_order_enquiry_log');
define('KN_MASTER_FABRIC_MISC', 'kn_master_fabric_misc');
define('KN_MASTER_GARMENT_SAMPLING','kn_master_garment_sampling');
define('KN_MASTER_LOGISTICS','kn_master_logistics');
/*------------------------Website Administration------------------------*/
define('KN_STATES','kn_states');
define('KN_MERCHANT_TEAM','kn_merchant_team');
/*define('KN_MASTER_MERCHANT','kn_master_merchant');
define('KN_MASTER_TEAMS','kn_master_teams');*/
define('KN_CAD_REQUEST','kn_cad_request');
define('KN_CAD_REQUEST_LOG','kn_cad_request_log');
define('KN_CAD_REQUIREMENTPURPOSE','kn_cad_requirementpurpose');
//oe - order entry v2.1 jexcel

define('ORDERENTRY_GARMENTPARTSTBL','orderentry_garmentpartstbl');

/**Maintain this format for order entry tables - oe_six (oe_number_tbl))**/

define('ORDERENTRY_FIRSTTBL','orderentry_firsttbl');
define('ORDERENTRY_SIZECHART','orderentry_sizechart');
define('ORDERENTRY_SECONDTBL','orderentry_secondtbl');
define('ORDERENTRY_THIRDTBL','orderentry_thirdtbl');
define('ORDERENTRY_NEW_FOURTH_TBL','orderentry_new_fourth_tbl');
define('ORDERENTRY_DELIVERYSCHEDULESIXTHTBL','orderentry_deliveryschedule_sixthtbl');
define('DELIVERY_SCHEDULE_WIP_LIST','delivery_schedule_wip_list');

define('ORDERENTRY_CUTTINGRATIO_FIFTHTBL','orderentry_cuttingratio_fifthtbl');
/*define('ORDERENTRY_B4SEVENTHKNITTBL','orderentry_b4seventhknittbl');
define('ORDER_ENTRY_EIGHTH','order_entry_eighth');
define('ORDER_ENTRY_NINTH','order_entry_ninth');
define('ORDERENTRY_FABRIC_DETAILS_KNIT_LYCRA','orderentry_fabric_details_knit_lycra');
define('ORDER_ENTRY_FABRIC_KNIT_LYCRA_FORMULA_RES','order_entry_fabric_knit_lycra_formula_res');
define('ORDERENTRY_SEVENTHKNITTBL','orderentry_seventhknittbl');
define('ORDERENTRY_WOVENEIGHTH_TBL','orderentry_woveneighth_tbl');
define('ORDERENTRY_DYEINGNINTH_TBL','orderentry_dyeingninth_tbl');*/
define('ORDERENTRY_EMBLISHMENTTENTH_TBL','orderentry_emblishmenttenth_tbl');
define('ORDERENTRY_ARTWORKCODE_TBL','orderentry_artworkcode_tbl');
/*define('ORDERENTRY_BOM_ARTICLE','oe_bom_article');*/
define('KN_BOM','kn_bom');
define('KN_BOM_CONSOLIDATED','kn_bom_consolidated');
define('KN_BOM_SOURCING','kn_bom_sourcing');
define('KN_BOM_SAMPLING_APPROVAL_DETAILS','kn_bom_sampling_approval_details');
/*define('ORDERENTRY_BOMELEVENTH_TBL','orderentry_bomeleventh_tbl');
define('ORDERENTRY_BOM_CONSOLIDATED','oe_bom_consolidated');
define('ORDERENTRY_BOMCONSTWELFTH_TBL','orderentry_bomconstwelfth');
define('ORDER_ENTRY_BOM_SOURCING_TBL','order_entry_bom_sourcing_tbl');
define('ORDER_ENTRY_BOM_SAMPLING_APPROVAL_DETAILS','order_entry_bom_sampling_approval_details');*/
define('ORDERENTRY_COMGARMENTPROCESSFLOW_TBL','orderentry_comgarmentprocessflow_tbl');
//define('ORDERENTRY_COMGARMENTPROCESSFLOWFOURTEENTH_TBL','orderentry_comgarmentprocessflowfourteenth_tbl');
define('ORDERENTRY_GARMENTSAMPLINGFIFTEEN_TBL','orderentry_garmentsamplingfifteen_tbl');
define('ORDERENTRY_LABTESTINGSIXTEENTH_TBL','orderentry_labtestingsixteenth_tbl');
define('CARTON_AND_BAGS','carton_and_bags');
//define('ORDER_ENTRY_EXT_LAB_TESTING','order_entry_ext_lab_testing');
define('ORDER_ENTRY_PACKING_DETAILS_TBL','order_entry_packing_details_tbl');
define('ORDERENTRY_LOTINSPECTIONTWENTYONE_TBL','orderentry_lotinspectiontwentyone_tbl');
define('ORDERENTRY_DOCANDLOGISTICSTWENTYTWO_TBL','orderentry_docandlogisticstwentytwo_tbl');
define('ORDERENTRY_ALLTBL_REAMRKS','orderentry_alltbl_remarks');
define('ORDERENTRY_COMMONDATA','orderentry_commondata');

//OTHER USERS TABLE
define('KN_CADUSERS','kn_cadusers');
define('KN_FABRICUSERS','kn_fabricusers');
define('KN_STORESUSERS','kn_storesusers');
define('KN_SAMPLE_REQUEST','kn_sample_request');
define('KN_SAMPLE_REQUEST_MISC','kn_sample_request_misc');

define('KN_MERCHANT_SAMPLE_CAD_INDENT','kn_merchant_sample_cad_indent');
define('KN_MERCHANT_SAMPLE_FAB_INDENT','kn_merchant_sample_fab_indent');
define('KN_MERCHANT_SAMPLE_BOM_INDENT','kn_merchant_sample_bom_indent');
define('KN_SAMPLEREQUESTINDENTDETAILS','kn_samplerequestindentdetails');

define('WORKINPROCESSTBL','workinprocesspono');
define('EACHPONOSTATUSTBL','eachponostatustbl');
define('KN_ALLREQUEST','kn_allrequest');
define('KN_BOMPURCHASEREQ','kn_bompurchasereq');
define('KN_BOM_PURCHASE_INDENT_PREVIEW','kn_bom_purchase_indent_preview');
define('KN_BOM_PI_APPROVAL_REQUEST','kn_bom_pi_approval_request');
define('BOMPI','bompi');
define('KN_BOMPI_PAY_PAIDDETAILS','kn_bompi_pay_paiddetails');
define('KN_MASTER_BOM_VENDOR','kn_master_bom_vendor');
define('KN_MASTER_EMBELLISHMENT_VENDOR','kn_master_embellishment_vendor');
define('KN_MASTER_CONSIGNOR','kn_master_consignor');
define('KN_MASTER_CONSIGNEE','kn_master_consignee');
define('KN_MASTER_DYEING_VENDOR','kn_master_dyeing_vendor');
define('KN_MASTER_JOBWRK','kn_master_jobwrk'); // newly included for managing dyeing job work and Enbellishment job work details
define('KN_MASTER_FABRICVENDOR','kn_master_fabricvendor'); // newly included for managing fabric vendor details
define('KN_MASTER_YARNVENDOR','kn_master_yarnvendor'); // newly included for managing yarn vendor details
define('KN_BOM_PURCHASEINDENT','kn_bom_purchaseindent');
define('KN_BOMPI_PROCESSTRACK','kn_bompi_processtrack');
define('KN_BOMPI_DELI_FOLLLUP','kn_bompi_deli_followup');
define('KN_BOM_INVOICESENDREQUEST','kn_bom_invoicesendrequest');
define('KN_BOMSTORE_ITEMRECD_DETAILS_INVOICES','kn_bomstore_itemrecd_details_invoices');
define('KN_BOMSTORE_ITEMRECD_DETAILS_LOT_APPROVAL','kn_bomstore_itemrecd_details_lot_approval');
define('KN_SEND_BOMIND2STORES_STOCKLIST','kn_send_bomind2stores_stocklist');
define('KN_STORES_NEW_ITEM_LIST','kn_stores_new_item_list');
define('KN_NEW_ITEM_QTY_ISSUED_DETAIL_JXL','kn_new_item_qty_issued_detail_jxl');
define('KN_PREVIEW_STORES_DC','kn_preview_stores_dc');

//define('KN_MASTER_REQUEST_TYPE','kn_request_type_urgency');

//Fabric Program
/*define('FABRIC_PROGRAM_THREE_JXL','fabric_program_three_jxl');
define('FABRIC_PROGRAM_FOURTH','fabric_program_fourth_jxl');
define('FABRIC_PROGRAM_FIVE_JXL','fabric_program_five_jxl');
define('FABRIC_PROGRAM_SIX_JXL','fabric_program_six_jxl');
define('FABRIC_PROGRAM_EIGHT_JXL','fabric_program_eight_jxl');
define('FABRIC_PROGRAM_NINE_JXL','fabric_program_nine_jxl');
define('FABRIC_PROGRAM_TEN_JXL','fabric_program_ten_jxl');
define('FABRIC_PROGRAM_ELEVEN_JXL','fabric_program_eleven_jxl');
define('FABRIC_PROGRAM_TWELVE_JXL','fabric_program_twelve_jxl');
define('FABRIC_PROGRAM_THIRTEEN_JXL','fabric_program_thirteen_jxl');
define('FABRIC_PROGRAM_FOURTEEN_JXL','fabric_program_fourteen_jxl');*/
define('KN_USER_DESGN','kn_designations');
//define('KN_MASTER_YARN','kn_master_yarn');
/*define('KN_COMPANY_MACHINE_DETAILS','kn_company_machine_details');*/
/*define('KN_MASTER_DYEING_METHOD','kn_master_dyeing_method');*/
/*define('KN_MASTER_DYEING_TYPE','kn_master_dyeing_type');*/
/*define('KN_MASTER_PRINT_TYPE','kn_master_print_type');*/
//Instead keep ARRUNITOFMEASURE defined in config.php/common.php
/*define('KN_MASTER_UNIT_MEASURE','kn_master_unit_measure');*/
/*define('KN_MASTER_TRIMMING_TYPE','kn_master_trimming_type');*/
/*define('KN_MASTER_SEWING_TRIMMING_TYPE','kn_master_sewing_trimming_type');*/
/*define('KN_MASTER_FABRIC_FINISH','kn_master_fabric_finish');*/
/*define('KN_MASTER_FF_STAGE_FORM','kn_master_ff_stage_form');*/
//define('KN_MASTER_WET_PROCESSING_GREIGE','kn_master_wet_processing_greige');
//define('KN_MASTER_DRY_PROCESSING_FINISHING','kn_master_dry_processing_finishing');
/*define('KN_MASTER_FABRIC_WASH','kn_master_fabric_wash');*/
//define('KN_MASTER_GARMENT_WASH','kn_master_garment_wash');
/*define('KN_MASTER_APPROVAL','kn_master_approval');*/
/*define('KN_MASTER_CATEGORIES','kn_master_categories');*/

/*define('KN_MASTER_LOTINSPECTION','kn_master_lotinspection');*/

/*define('KN_MASTER_FABRIC_DETAILS','kn_master_fabric_details');*/
//define('KN_MASTER_TEAMSMEMBERS','kn_master_teamsmembers');
//define('KN_BOMPAYMENT_REQUEST','kn_bompayment_request');
//define('KN_BOMPAYMENT_REQUEST_CHILD','kn_bompayment_request_child');
//define('ORDERENTRY_MASTERBAGASSORTEIGHTEEN_TBL','orderentry_masterbagassorteighteen_tbl');
//define('ORDERENTRY_MOREOPTMASTERBAGASSORTEIGHTEEN_TBL','orderentry_moreoptmasterbagassorteighteen_tbl');
//define('ORDERENTRY_CARTONBAGASSORTNINETEEN_TBL','orderentry_cartonbagassortnineteen_tbl');
//define('ORDERENTRY_QUANTITYPERCARTONTWENTY_TBL','orderentry_quantitypercartontwenty_tbl');
//define('ORDER_SHIPMENT_SCHEDULE','order_shipment_schedule');
/*define('FPROGRAM_ITEMIZED_FABRIC_REQDETAILS_COLORWISE_DIAWISE','fprogram_itemized_fabric_reqdetails_colorwise_diawise');
define('FPROGRAM_GPARTSCONSCALC','fprogram_gpartsconscalc');
define('FPROGRAM_GPARTSCONSCALC_COMMON','fprogram_gpartsconscalc_common');*/
/*define('FPROGRAM_ITEMIZEDYARNWGTCALC','fprogram_itemizedyarnwgtcalc');
define('FPROGRAM_YARNDYEINGCOLORWISEBREAKUP','fprogram_yarndyeingcolorwisebreakup');
define('FPROGRAM_ITEMIZEDYARNPGM','fprogram_itemizedyarnpgm');
define('FPROGRAM_COUNTWISEYARNREQQTY','fprogram_countwiseyarnreqqty');
define('FPROGRAM_YARNREQUIREMENTDETAILS','fprogram_yarnrequirementdetails');*/
//define('ORDERENTRY_FIFTHTBL','orderentry_fifthtbl');
//define('ORDERENTRY_SIXTHTBL','orderentry_sixthtbl');
//define('ORDERENTRY_ATBL','orderentry_sixthatbl');
//define('ORDERENTRY_KNITSEVENTHTBL','orderentry_knitseventhtbl');
//define('ORDERENTRY_BOMSOURCINGTHIRTEENTH_TBL','orderentry_bomsourcingthirteenth_tbl');
//define('ORDERENTRY_BOMSAMAPPRTHIRTEEN_TBL','orderentry_bomsamapprthirteen_tbl');
//define('KN_BOMSTORE_ITEMRECD_DETAILS_STATUS','kn_bomstore_itemrecd_details_status');
//define('KN_SAMPLE_REQUEST_DETAILS','kn_sample_request_details');
//define('OE_FIRST_TBL','oe_first_tbl');
//define('ORIGINAL_SHIPMENT_SCHEDULE', 'original_shipment_schedule');
//define('REVISED_RESCHEDULE_SHIPMENT', 'revised_reschedule_shipment');
//define('GOODS_SHIPPED_SCHEDULE', 'goods_shipped_schedule');
//define('KN_MASTER_ENQUIRY_TYPE','kn_master_enquiry_type');