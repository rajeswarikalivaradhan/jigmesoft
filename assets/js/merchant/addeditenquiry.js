function fnSaveEnquiry() {
    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    //try {
        var OrderEnqRefNo     = $("#frmOrderEnqRefNo").val();
        var EnquiryDate = $("#frmBasicEnqDate").val();
        var Styref = $("#frmBasicStyleRefNo").val();
        var StyDesc     = $("#frmBasicStyleDesc").val();
        //var frmBasicMnote = $("#frmBasicMnote").val();
        var frmBasicEType = $("#frmBasicEType").val();
        var frmBasicMoE = $("#frmBasicMoE").val();
        var frmBasicBrand = $("#frmBasicBrand").val();
        var frmBasicBuyer = $("#frmBasicBuyer").val();
        var frmBasicPs = $("#frmBasicPs").val();
        var frmBasicCountry = $("#frmBasicCountry").val();
        var frmBasicQprice = $("#frmBasicQprice").val();
        var frmBasicBprice = $("#frmBasicBprice").val();
        var frmBasicCprice = $("#frmBasicCprice").val();
        var frmBasicCurrency = $("#frmBasicCurrency").val();
        var frmBasicPqty = $("#frmBasicPqty").val();
        var frmBasicRType = $("#frmBasicRType").val();

        var frmBasicISRany = $("#frmBasicISRany").val();

        var PriceQuotedFor = $("#frmPriceQuotedFor").val();
        
        var frmcombo   = $("#frmcombo").val();
        var frmComponents = $("#frmComponents").val();
        var frmShipmentDate = $("#frmShipmentDate").val();
        var orderstatus=($('#frmAuthoriaztionStatus').val()===null)?0:$('#frmAuthoriaztionStatus').val();
       
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');

        $(".requestTypeSelecting").change(function () {
            console.log(this.value);
        });

        if(jsTrim(OrderEnqRefNo) == "") {
            $('#ErrfrmOrderEnqRefNo').html("Enter Order / Enquiry Ref. No");
            $('#frmOrderEnqRefNo').focus();
            $('#frmOrderEnqRefNo').css("border", "1px solid #B94A48");
            return false;
        }
        if(EnquiryDate == "") {
            $('#ErrfrmBasicEnqDate').html("Select Enquiry Date");
            $('#frmBasicEnqDate').focus();
            $('#frmBasicEnqDate').css("border", "1px solid #B94A48");
            return false;
        }
//        if(jsTrim(Styref) == "") {
//            $('#ErrfrmBasicStyleRefNo').html("Enter Style Ref. No. / Name");
//            $('#frmBasicStyleRefNo').focus();
//            $('#frmBasicStyleRefNo').css("border", "1px solid #B94A48");
//            return false;
//        }

        if(jsTrim(StyDesc) == "") {
            $('#ErrfrmBasicStyleDesc').html("Enter Style Description");
            $('#frmBasicStyleDesc').focus();
            $('#frmBasicStyleDesc').css("border", "1px solid #B94A48");
            return false;
        }
        if(frmBasicEType == "" || frmBasicEType === null) {
            $('#ErrfrmBasicEType').html("Select Enquiry Type");
            $('#frmBasicEType').focus();
            $('#frmBasicEType').css("border", "1px solid #B94A48");
            return false;
        }
        if(frmBasicMoE == "" || frmBasicMoE === null) {
            $('#ErrfrmBasicMoE').html("Select Mode Of Enquiry");
            $('#frmBasicMoE').focus();
            $('#frmBasicMoE').css("border", "1px solid #B94A48");
            return false;
        }
        if(frmBasicBrand == "" ||  frmBasicBrand === null) {
            $('#ErrBasicBrand').html("Select Brand");
            $('#frmBasicBrand').focus();
            $('#frmBasicBrand').css("border", "1px solid #B94A48");
            return false;
        }
        if(frmBasicBuyer == "" ||  frmBasicBuyer === null) {
            $('#ErrBasicBuyer').html("Select Buyer");
            $('#frmBasicBuyer').focus();
            $('#frmBasicBuyer').css("border", "1px solid #B94A48");
            return false;
        }
        if(frmBasicCountry == "" ||  frmBasicCountry === null) {
            $('#ErrfrmBasicCountry').html("Select Country");
            $('#frmBasicCountry').focus();
            $('#frmBasicCountry').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(frmBasicPqty) == "" ||  frmBasicPqty === null) {
            $('#ErrfrmBasicPqty').html("Enter Total Order Qty");
            $('#frmBasicPqty').focus();
            $('#frmBasicPqty').css("border", "1px solid #B94A48");
            return false;
        }
        if(frmBasicPs == "" ||  frmBasicPs === null) {
            $('#ErrfrmBasicPs').html("Select Pcs. / Set");
            $('#frmBasicPs').focus();
            $('#frmBasicPs').css("border", "1px solid #B94A48");
            return false;
        }
        
        if(jsTrim(frmComponents) == "" || frmComponents==0 )
        {
            $('#ErrfrmfrmComponents').html("Enter No.of Component");
            $('#frmComponents').focus();
            $('#frmComponents').css("border", "1px solid #B94A48");
            return false;
        }
        if(jsTrim(frmcombo) == "" || frmcombo==0)
        {
            $('#Errfrmfrmcombo').html("Enter No.of Combo/Colour");
            $('#frmcombo').focus();
            $('#frmcombo').css("border", "1px solid #B94A48");
            return false;
        }
        if(frmShipmentDate == "" || frmShipmentDate==null) {
            $('#ErrfrmShipmentDate').html("Select Shipment Date");
            $('#frmShipmentDate').focus();
            $('#frmShipmentDate').css("border", "1px solid #B94A48");
            return false;
        }
        if(PriceQuotedFor == "" || PriceQuotedFor===null)
        {
            $('#ErrfrmPriceQuotedFor').html("Select Price Quoted For");
            $('#frmPriceQuotedFor').focus();
            $('#frmPriceQuotedFor').css("border", "1px solid #B94A48");
            return false;
        }
        // commented by myself as per client comment
        ////////////////////////////////////////////////////////////////////////
        // if(jsTrim(frmBasicQprice) == "" ||  frmBasicQprice === null) {
        //     $('#ErrfrmBasicQprice').html("Enter Quoted Price");
        //     $('#frmBasicQprice').focus();
        //     $('#frmBasicQprice').css("border", "1px solid #B94A48");
        //     return false;
        // }
        // if(frmBasicCurrency == 0 ||  frmBasicCurrency === null) {
        //     $('#ErrfrmBasicCurrency').html("Select Currency");
        //     $('#frmBasicCurrency').focus();
        //     $('#frmBasicCurrency').css("border", "1px solid #B94A48");
        //     return false;
        // }
        // if(jsTrim(frmBasicBprice) == "" ||  frmBasicBprice === null) {
        //     $('#ErrfrmBasicBprice').html("Enter Buyer's Price");
        //     $('#frmBasicBprice').focus();
        //     $('#frmBasicBprice').css("border", "1px solid #B94A48");
        //     return false;
        // }
        // if(jsTrim(frmBasicCprice) == "" ||  frmBasicCprice === null) {
        //     $('#ErrfrmBasicCprice').html("Enter Confirm Price");
        //     $('#frmBasicCprice').focus();
        //     $('#frmBasicCprice').css("border", "1px solid #B94A48");
        //     return false;
        // }
        ////////////////////////////////////////////////////////////////////////
        if(frmBasicRType == "" ||  frmBasicRType === null) {
            $('#ErrfrmBasicRType').html("Select Request Type");
            $('#frmBasicRType').focus();
            $('#frmBasicRType').css("border", "1px solid #B94A48");
            return false;
        }
        
        // var Parameters = "rfrom=1&draftstatus=2&sd="+encodeURIComponent(StyDesc)+"&styref="+encodeURIComponent(Styref)+"&mt="+encodeURIComponent(frmBasicMnote)+"&enqdt="+
        //     EnquiryDate+"&enquiryid="+GlbEnquiryId+"&os=0&et="+frmBasicEType+"&me="+frmBasicMoE+"&brn="+frmBasicBrand+"&byr="+frmBasicBuyer+"&ps="+frmBasicPs+"&conty="+
        //     frmBasicCountry+"&qp="+frmBasicQprice+"&bp="+frmBasicBprice+"&cp="+frmBasicCprice+"&crncy="+frmBasicCurrency+"&proq="+frmBasicPqty+
        //     "&resend=0&rt="+frmBasicRType+"&israny="+frmBasicISRany+"&orderenqrefno="+encodeURIComponent(OrderEnqRefNo)+"&pricequotedfor="+PriceQuotedFor+"&frmcombo="+ frmcombo +
        //     "&frmComponents=" +frmComponents + "&frmShipmentDate=" + frmShipmentDate;
        // removed this condition in linno:182 -> && frmBasicQprice && frmBasicCurrency && frmBasicBprice && frmBasicCprice
        var Parameters = "rfrom=1&draftstatus=2&sd="+encodeURIComponent(StyDesc)+"&styref="+encodeURIComponent(Styref)+"&enqdt="+
            EnquiryDate+"&enquiryid="+GlbEnquiryId+"&os="+orderstatus+"&et="+frmBasicEType+"&me="+frmBasicMoE+"&brn="+frmBasicBrand+"&byr="+frmBasicBuyer+"&ps="+frmBasicPs+"&conty="+
            frmBasicCountry+"&qp="+frmBasicQprice+"&bp="+frmBasicBprice+"&cp="+frmBasicCprice+"&crncy="+frmBasicCurrency+"&proq="+frmBasicPqty+
            "&resend=0&rt="+frmBasicRType+"&israny="+frmBasicISRany+"&orderenqrefno="+encodeURIComponent(OrderEnqRefNo)+"&pricequotedfor="+PriceQuotedFor+"&frmcombo="+ frmcombo +
            "&frmComponents=" +frmComponents + "&frmShipmentDate=" + frmShipmentDate;
            
            if (OrderEnqRefNo && EnquiryDate && StyDesc && frmBasicEType && frmBasicMoE 
            && frmBasicBrand && frmBasicBuyer && frmBasicCountry && frmBasicPqty 
            && frmBasicPs && frmcombo && frmComponents && frmShipmentDate && PriceQuotedFor && frmBasicRType) {
             swalWithBootstrapButtons.fire(
                            {
                               // title: 'Are you sure want to save the details ?',
                               // text: "If you save You won't be able to revert this!",
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
								MakeAsynPostRequest(base_path+'merchant/updateenquiry',Parameters,'json',fnSaveEnquiryRes);
								return false;
								} 
								else if (result.dismiss === Swal.DismissReason.cancel) {
								//location.reload();
								// 	swalWithBootstrapButtons.fire({
								// 		title: 'Cancelled',
								// 		text: 'Cancelled successfully.',
								// 		type: 'error',
								// 		icon: 'error',
								// 		customClass: {'confirmButton': 'btn btn-secondary px-5'}
								// 	});
								}
                        }); 
            }
            else {
                return false;
            }
        
        // if (confirm("To confirm click OK, else CANCEL")) {
        //     MakeAsynPostRequest(base_path+'merchant/updateenquiry',Parameters,'json',fnSaveEnquiryRes);
        //     return false;
        // }
        // else {
        //     return false;
        // }
        
//    } catch(e) {
//        alert(e);
//    }
}
function fnSaveEnquiryDraft(id) {
    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    //try {
        var OrderEnqRefNo     = $("#frmOrderEnqRefNo").val();
        var EnquiryDate = $("#frmBasicEnqDate").val();
        var Styref = $("#frmBasicStyleRefNo").val();
        var StyDesc     = $("#frmBasicStyleDesc").val();
        //var frmBasicMnote = $("#frmBasicMnote").val();
        var frmBasicEType = $("#frmBasicEType").val();
        var frmBasicMoE = $("#frmBasicMoE").val();
        var frmBasicBrand = $("#frmBasicBrand").val();
        var frmBasicBuyer = $("#frmBasicBuyer").val();
        var frmBasicPs = $("#frmBasicPs").val();
        var frmBasicCountry = $("#frmBasicCountry").val();
        var frmBasicQprice = $("#frmBasicQprice").val();
        var frmBasicBprice = $("#frmBasicBprice").val();
        var frmBasicCprice = $("#frmBasicCprice").val();
        var frmBasicCurrency = $("#frmBasicCurrency").val();
        var frmBasicPqty = $("#frmBasicPqty").val();
        var frmBasicRType = $("#frmBasicRType").val();

        var frmBasicISRany = $("#frmBasicISRany").val();

        var PriceQuotedFor = $("#frmPriceQuotedFor").val();
        
        var frmcombo   = $("#frmcombo").val();
        var frmComponents = $("#frmComponents").val();
        var frmShipmentDate = $("#frmShipmentDate").val();
        
        $('.form-control').css("border", "1px solid #cccccc");
        $('div.herr').text('');

        $(".requestTypeSelecting").change(function () {
            console.log(this.value);
        });
        
        // var Parameters = "rfrom=1&draftstatus=1&sd="+encodeURIComponent(StyDesc)+"&styref="+encodeURIComponent(Styref)+"&mt="+encodeURIComponent(frmBasicMnote)+"&enqdt="+
        //     EnquiryDate+"&enquiryid="+GlbEnquiryId+"&os=0&et="+frmBasicEType+"&me="+frmBasicMoE+"&brn="+frmBasicBrand+"&byr="+frmBasicBuyer+"&ps="+frmBasicPs+"&conty="+
        //     frmBasicCountry+"&qp="+frmBasicQprice+"&bp="+frmBasicBprice+"&cp="+frmBasicCprice+"&crncy="+frmBasicCurrency+"&proq="+frmBasicPqty+
        //     "&resend=0&rt="+frmBasicRType+"&israny="+frmBasicISRany+"&orderenqrefno="+encodeURIComponent(OrderEnqRefNo)+"&pricequotedfor="+PriceQuotedFor+"&frmcombo="+ frmcombo +
        //     "&frmComponents=" +frmComponents + "&frmShipmentDate=" + frmShipmentDate;
        
        
        var Parameters = "rfrom=1&draftstatus=1&sd="+encodeURIComponent(StyDesc)+"&styref="+encodeURIComponent(Styref)+"&enqdt="+
            EnquiryDate+"&enquiryid="+GlbEnquiryId+"&os=0&et="+frmBasicEType+"&me="+frmBasicMoE+"&brn="+frmBasicBrand+"&byr="+frmBasicBuyer+"&ps="+frmBasicPs+"&conty="+
            frmBasicCountry+"&qp="+frmBasicQprice+"&bp="+frmBasicBprice+"&cp="+frmBasicCprice+"&crncy="+frmBasicCurrency+"&proq="+frmBasicPqty+
            "&resend=0&rt="+frmBasicRType+"&israny="+frmBasicISRany+"&orderenqrefno="+encodeURIComponent(OrderEnqRefNo)+"&pricequotedfor="+PriceQuotedFor+"&frmcombo="+ frmcombo +
            "&frmComponents=" +frmComponents + "&frmShipmentDate=" + frmShipmentDate;
            
            if (OrderEnqRefNo!='' || EnquiryDate!=''|| Styref!='' || StyDesc!='' || frmBasicISRany!='' || (frmBasicEType!='' && frmBasicEType != null)  || (frmBasicMoE!='' && frmBasicMoE != null) || (frmBasicBrand!='' && frmBasicBrand != null) || (frmBasicBuyer!='' && frmBasicBuyer!=null) || (frmBasicCountry!='' && frmBasicCountry!=null) || (frmBasicPqty!='' && frmBasicPqty!=null) || (frmBasicPs!='' && frmBasicPs!=null) || 
                (frmcombo !='' && frmcombo !=0)  || (frmComponents!='' && frmComponents!=0) || (frmShipmentDate!='' && frmShipmentDate!=null) || (PriceQuotedFor!='' && PriceQuotedFor!=null) || (frmBasicQprice!='' && frmBasicQprice!=null) || (frmBasicCurrency!='' && frmBasicCurrency!=null) || (frmBasicBprice!='' && frmBasicBprice!=null) || (frmBasicCprice!='' && frmBasicCprice!=null) || (frmBasicRType!='' && frmBasicRType!=null)) {
                
                if(id=='savedraftbtn'){
                    swalWithBootstrapButtons.fire({
                                title: 'Do you want to save the draft details ?',
                                type: 'warning',
                                showCancelButton: true,
                                scrollbarPadding: false,
                                confirmButtonText: 'Yes',
                                cancelButtonText: 'No',
                                reverseButtons: true,
                                width:460,
                                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}}).then(function(result) {
								
								if (result.value) {
								MakeAsynPostRequest(base_path+'merchant/updateenquiry',Parameters,'json',fnSaveEnquiryRes);
								return false;
								} 
								else if (result.dismiss === Swal.DismissReason.cancel) {
								
								}
                    }); 
                }else{
                   // MakeAsynPostRequest(base_path+'merchant/updateenquiry',Parameters,'json',fnSaveEnquiryRes);
					return false;
                }
            }else{
                let enquiryListPath = "merchant/orderEnquiryList";
                enquiryListPath = base_path+enquiryListPath;
                window.location.href = enquiryListPath;
            }
       
//    } catch(e) {
//        alert(e);
//    }
}

var GlbInsertId = 0;
function fnSaveEnquiryRes(data) {
    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    if(data!='') {
        console.log(data,'data');
        if(data.errcode == '404') {
            fnCallSessionExpire();
            return false;
        } else if(data.errcode==-1) {
            $('#ErrBasicAccessories').html(data.msg);
            return false;
        } else if(data.errcode==1) {
            $("#addenqbut").remove();
            GlbInsertId = data.id;
            //console.log(GlbInsertId,'GlbInsertId');
            extraObj.startUpload();
            $("#frmBasicReqDT").text(data.dcreated);
            //console.log(extraObj,'extraObj');
            $("#divSuccessBasicInfoMsgparent").removeClass('hide');
            $("#divSuccessBasicInfoMsg").html("Enquiry has been sent successfully!");
        if(data.draftstatus==2){
            swalWithBootstrapButtons.fire({
                title: 'Saved!',
                //text: 'Operation Completed Successfully.',
                type: 'success',
                icon: 'success',
                customClass: {'confirmButton': 'btn btn-info'}
            }).then((result) => {
                
                if(data.draftstatus==2){
                let enquiryListPath = "components/componentCreation" + '/' + data.eid;
                enquiryListPath = base_path+enquiryListPath;
                window.location.href = enquiryListPath;
                }else{
                let enquiryListPath = "merchant/orderEnquiryList";
                enquiryListPath = base_path+enquiryListPath;
                window.location.href = enquiryListPath;
                }
                
            });
        }else{
            let enquiryListPath = "merchant/orderEnquiryList";
                enquiryListPath = base_path+enquiryListPath;
                window.location.href = enquiryListPath;
        }
        }
        // let enquiryListPath = "merchant/orderEnquiryList";
        // enquiryListPath = base_path+enquiryListPath;
        // setTimeout(function() { 
        //     window.location.href = enquiryListPath;
        // }, 1000);
    }
}

function fncleardraft(val) {
  
    let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
    if(val!='') {
      
      swalWithBootstrapButtons.fire(
                            {
                               // title: 'Are you sure want to save the details ?',
                               // text: "If you save You won't be able to revert this!",
                                title: 'Do you want to clear the draft details ?',
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
								MakeAsynPostRequest(base_path + "merchant/getcleardraftstatus", "id=" + val, "json", function (data) {
								    console.log('clear'+data);
                                    if(data.success==1) {
                                    let enquiryListPath = "merchant/orderEnquiryList";
                                    enquiryListPath = base_path+enquiryListPath;
                                    window.location.href = enquiryListPath;
                                    }
                                });
								} 
								else if (result.dismiss === Swal.DismissReason.cancel) {
								
								}
                            }); 
    }
}

$(document).ready(function() {
    extraObj     = $("#uploadBusinessImg").uploadFile({
        dragDrop: true,
        multiple:true,
        url:base_path+'merchant/enqFileUpload',
        returnType: "json",
        fileName:"myFile",
        allowedTypes: allowedFileTypes,
        dynamicFormData:function () {
            return {'id':GlbInsertId};
        },
        autoSubmit:false
    });
    //console.log(extraObj,'extraObj INI');


        $("#frmBasicBrand").change(function () {
            // console.log($(this).val(), '$(this).val()');
            var BrnId = $(this).val();
             $("#frmBasicBuyer").val('');
             $("#frmBasicCountry").val('');
            MakeAsynPostRequest(base_path + "merchant/getBuyerInfoByBrandId", "rFrom=1&id=" + BrnId, "json", function (data) {
                // console.log(data.buyerId, 'buyerId');
                // console.log(data.companyId, 'companyId');
                if (data.buyername != ''){
                    $("#frmBasicBuyer").val(data.buyername);
                }
                if(data.country != ''){
                   $("#frmBasicCountry").val(data.country); 
                }    
                    
            });
        });
});

$(document).ready(function() {
    $("#submitAuthRequest").click(function () {
        $('#myModal').modal('show');
    });

    $("#submitRequest").click(function () {
        $(".herr").text('');
        try {
            // var pw = $("#frmPin").val();
            var one = $("#numberone").val();
            var two = $("#numbertwo").val();
            var three = $("#numberthree").val();
            var four = $("#numberfour").val();
            GlbAuthStatus = $("#frmBasicOrderStatus").val();

            var val = one+two+three+four;
            // console.log(val);
            if (jsTrim(one) == "" && jsTrim(two) == "" && jsTrim(three) == "" && jsTrim(four) == "") {
                $("#ErrfrmPin").text('Enter PIN');
                return false;
            }

            MakeAsynPostRequest(base_path + 'merchant/fnCheckPin', "rfrom=1&i=" + val + "&enqid=" + GlbEnquiryId + "&s=" +
                            GlbAuthStatus + "&ty=" + GlbIsrIor, 'json', fnAuthRes);
            return false;

            function fnAuthRes(data) {
                if (data != '') {
                    if (data.errcode == '404') {
                        fnCallSessionExpire();
                        return false;
                    } else if (data.errcode == '-1') {
                        $('#ErrfrmPin').text(data.msg);
                        return false;
                    } else if (data.errcode == '1') {
                        $('#myModal').modal('hide');
                        $("#divSuccessBasicInfoDiv").removeClass('hide');
                        $("#divSuccessBasicInfoMsg").text(data.msg);
                        
                        let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
                        swalWithBootstrapButtons.fire(
                            {
                                title: 'Are you sure want to Authorize ?',
                                type: 'warning',
                                showCancelButton: true,
                                scrollbarPadding: false,
                                confirmButtonText: 'Yes, do it!',
                                cancelButtonText: 'No, cancel!',
                                reverseButtons: true,
                                customClass: {'confirmButton': 'btn btn-green mx-2 px-3',  'cancelButton': 'btn btn-red mx-2 px-3'}
                            }
                        ).then(function(result) {
                            if (result.value) {
                                $('#myModal').modal('hide');
                                
                                let idValue = $('#enquiryFormId').val();
                                let orderStatus = 1;
                                let status = 1;
                                MakeAsynPostRequest(base_path + "merchant/submitAuthRequest", 
                                    "rFrom=1&id="+idValue+"&orderStatus="+orderStatus+"&status="+status, "json", function (data) {
                                    // console.log(data);
                                    if(data.statusCode == "200") {
                                        swalWithBootstrapButtons.fire({
                                            title: 'Saved!',text: data.message,type: 'success',
                                            icon: 'success',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                        }).then((result) => {
                                            let enquiryListPath = "merchant/orderEnquiryList";
                                            enquiryListPath = base_path+enquiryListPath;
                                            window.location.href = enquiryListPath;
                                        });
                                        
                                    }
                                    else if(data.statusCode == "203") {
                                        swalWithBootstrapButtons.fire({
                                            title: 'Saved!', text: data.message, type: 'success',
                                            icon: 'info',
                                            customClass: {'confirmButton': 'btn btn-info'}
                                        }).then((result) => {
                                            let enquiryListPath = "merchant/orderEnquiryList";
                                            enquiryListPath = base_path+enquiryListPath;
                                            window.location.href = enquiryListPath;
                                        });
                                        
                                    }
                                    else {
                                        swalWithBootstrapButtons.fire({title: 'Error!',text: data.message,type: 'error',icon: 'error',customClass: {'confirmButton': 'btn btn-info px-5'}});
                                    }
                                });

                                
                            } 
                            else if (result.dismiss === Swal.DismissReason.cancel) {
                                $('#myModal').modal('hide');
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
            }

        } catch (e) {
            alert(e);
        }
    });

})

$(document).ready(function(){
   
    // var href = location.href;
    // var parts = href.split('/');
     
    // var lastSegment = parts.pop() || parts.pop();
    // var id = atob(decodeURIComponent(lastSegment));
    // var segment = '';
    // // local
    // segment = parts[5];
   
    // live
     //segment = parts[4];
   var href = location.href;
   var parts = href.split('/');
while (parts[parts.length - 1] === '') parts.pop();

var lastSegment = parts.pop(); // base64 encoded ID
var segment = parts.pop();     // the one before that
var id = atob(decodeURIComponent(lastSegment)); // decode base64


    if(lastSegment != 'addenquiry' && segment == 'addenquiry')
    {
        
      
		return $.ajax({
			url: base_path+'merchant/getSeperateOrderEnquiryList',
			type:'POST',
            data: {"id": id},
			success:function(data){
				enquiryJSON = $.parseJSON(data);
				 console.log(enquiryJSON);
				if(enquiryJSON[0].draft_status==2){
				$("#enquiry_add_form input").prop("disabled", true);
                $("#enquiry_add_form select").prop("disabled", true);
                $("#enquiry_add_form textarea").prop("disabled", true);
                ///$("#frmBasicMnote").prop("disabled", true);

				}
                
                $('#frmOrderEnqRefNo').val(enquiryJSON[0].orderenqrefno);
                var enquirydate = enquiryJSON[0].enquirydate;
                //alert(enquiryJSON[0].enquirydate);
                if(enquirydate != null && enquirydate!='0000-00-00')
                {
                    var sd = new Date(enquiryJSON[0].enquirydate);
                    var edate = ("0" + (sd.getDate())).slice(-2) + '-' + ("0" + (sd.getMonth() + 1)).slice(-2) + '-' + sd.getFullYear();
                    $('#frmBasicEnqDate').val(edate);
                }
                else{
                    $('#frmBasicEnqDate').val('');
                }
                // var d = new Date(enquiryJSON[0].formattedDateCreated);
                // var edate = ("0" + (d.getDate())).slice(-2) + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + d.getFullYear();
                // $('#frmBasicEnqDate').val(edate);
                $('#frmBasicStyleRefNo').val(enquiryJSON[0].stylenamerefno);
                $('#frmBasicStyleDesc').val(enquiryJSON[0].styledesc);
                $("#frmBasicEType").val(enquiryJSON[0].enquirytype).trigger("change");
                $("#frmBasicMoE").val(enquiryJSON[0].modeofenquiry).trigger("change");
                $("#frmBasicBrand").val(enquiryJSON[0].brandId).trigger("change");
                //////////////////////////////////// commented by myself regards new brand form integration///
                // $('#frmBasicBuyer').val(enquiryJSON[0].buyerId);
                // $("#frmBasicCountry").val(enquiryJSON[0].countryid).trigger("change");
                $('#frmBasicBuyer').val(enquiryJSON[0].buyername);
                $("#frmBasicCountry").val(enquiryJSON[0].country).trigger("change");
                ///////////////////////////////////////
                //$('#frmBasicPqty').val(enquiryJSON[0].total_comp);
                $('#frmBasicPqty').val(enquiryJSON[0].exporderqty).trigger("change");
                $("#frmBasicPs").val(enquiryJSON[0].pcsorset).trigger("change");
                $('#frmcombo').val((enquiryJSON[0].totalcombo==0)?'':enquiryJSON[0].totalcombo);
                $('#frmComponents').val((enquiryJSON[0].totalcomponents==0)?'':enquiryJSON[0].totalcomponents);
                var shipmentdate = enquiryJSON[0].shipmentdate;
                //alert(enquiryJSON[0].shipmentdate);
                if(shipmentdate != null && shipmentdate!='0000-00-00 00:00:00')
                {
                    var sd = new Date(enquiryJSON[0].shipmentdate);
                    var sdate = ("0" + (sd.getDate())).slice(-2) + '-' + ("0" + (sd.getMonth() + 1)).slice(-2) + '-' + sd.getFullYear();
                    $('#frmShipmentDate').val(sdate);
                }
                else{
                    $('#frmShipmentDate').val('');
                }
                $("#frmPriceQuotedFor").val(enquiryJSON[0].pricequotedfor).trigger("change");
                $('#frmBasicQprice').val((enquiryJSON[0].quotedprice!='0.00')?enquiryJSON[0].quotedprice:'');
                $("#frmBasicCurrency").val(enquiryJSON[0].currency).trigger("change");
                $('#frmBasicBprice').val((enquiryJSON[0].buyerprice!='0.00')?enquiryJSON[0].buyerprice:'');
                $("#frmBuyerCurrency").val(enquiryJSON[0].currency).trigger("change");
                $('#frmBasicCprice').val((enquiryJSON[0].confirmprice!='0.00')?enquiryJSON[0].confirmprice:'');
                $("#frmConfirmCurrency").val(enquiryJSON[0].currency).trigger("change");
                $("#frmBasicRType").val(enquiryJSON[0].reqforisrior).trigger("change");
                //commented by me for reqdatetime let create_iso = new Date(enquiryJSON[0].datecreated);
                let create_iso = (enquiryJSON[0].reqdatetime!==null)? new Date(enquiryJSON[0].reqdatetime):'';
                let cdate =  (create_iso!=='')?("0" + (create_iso.getDate())).slice(-2):'';
                let month = (create_iso!=='')?("0" + (create_iso.getMonth()+1)).slice(-2):'';
                let year = (create_iso!=='')?create_iso.getFullYear():'';
                // Hours
                var hours =(create_iso!=='')? create_iso.getHours():'';
                // Minutes
                var minutes = (create_iso!=='')?"0" + create_iso.getMinutes():'';
                var ampm =(hours!=='')? hours >= 12 ? "PM":"AM":'';
                 hours = (hours!=='')?hours % 12:'';
                 hours = (hours!=='')?hours ? hours : 12:'';
                var datecreated =(create_iso!=='')? cdate + '-' + month + '-' + year + ' ' + hours + ':' + minutes.substr(-2) + ' ' + ampm:'';
                $("#frmBasicReqDT").val((enquiryJSON[0].draft_status==2)?datecreated:'');
                $("#frmmercentname").val((enquiryJSON[0].draft_status==2)?enquiryJSON[0].mer_name:'');
                $("#frmAuthorizedBy").val((enquiryJSON[0].draft_status==2)?enquiryJSON[0].authorizedby:'');
                $('#frmBasicISRany').val(enquiryJSON[0].isrrefany);
                //$('#frmAuthoriaztionStatus').val(enquiryJSON[0].orderstatus);
                $('#frmAuthoriaztionStatus').find('option')
				.end()
				.append('<option selected value="'+ enquiryJSON[0].orderstatus +'">'+ORDERENQUIRYSTATUS[enquiryJSON[0].orderstatus]+'</option>');
				$('#frmManagementRemarks').val((enquiryJSON[0].comments!='undefined')?enquiryJSON[0].comments:'').trigger("change");
				
				let createauth_iso = (enquiryJSON[0].dateauthorized!==null && enquiryJSON[0].dateauthorized!=='0000-00-00 00:00:00')? new Date(enquiryJSON[0].dateauthorized):'';
                let cauthdate =  (createauth_iso!=='')?("0" + (createauth_iso.getDate())).slice(-2):'';
                let authmonth = (createauth_iso!=='')?("0" + (createauth_iso.getMonth()+1)).slice(-2):'';
                let authyear = (createauth_iso!=='')?createauth_iso.getFullYear():'';
                // Hours
                var authhours =(createauth_iso!=='')? createauth_iso.getHours():'';
                // Minutes
                var authminutes = (createauth_iso!=='')?"0" + createauth_iso.getMinutes():'';
                var authampm =(authhours!=='')? authhours >= 12 ? "PM":"AM":'';
                 authhours = authhours % 12;
                 authhours = authhours ? authhours : 12;
                var authdatecreated =(createauth_iso!=='')? cauthdate + '-' + authmonth + '-' + authyear + ' ' + authhours + ':' + authminutes.substr(-2) + ' ' + authampm:'';
                $("#frmAuthorizedDate").val((enquiryJSON[0].draft_status==2)?authdatecreated:'');
				
               // $('#frmBasicMnote').val(enquiryJSON[0].merchantnote).trigger("change");
                $('#draftstatus').val(enquiryJSON[0].draft_status).trigger("change");
                if(enquiryJSON[0].draft_status==1){
                    $('#cleardraft').show();
                    $('#savedraft').show();
                }else if(enquiryJSON[0].draft_status==2){
                    $('#cleardraft').hide();
                    $('#savedraft').hide();
                }else{
                    $('#cleardraft').hide();
                    $('#savedraft').show();
                }
                
                if(enquiryJSON[0].orderstatus==3 || enquiryJSON[0].orderstatus==0){
                    $('#editEnable').show();
                }else{
                    $('#editEnable').hide();
                }
			},
			error: function() {
				console.log("Error");  
			}
		});
    }
});
$('#backbtn').on('click', function() {
//alert($('#draftstatus').val());
// if($('#draftstatus').val()==2){
//     let enquiryListPath = "merchant/orderEnquiryList";
//                 enquiryListPath = base_path+enquiryListPath;
//                 window.location.href = enquiryListPath;
// }else{
//     fnSaveEnquiryDraft('backbtn');
// }
let enquiryListPath = "merchant/orderEnquiryList";
   enquiryListPath = base_path+enquiryListPath;
     window.location.href = enquiryListPath;

});
$('#editEnable').on('click', function() {
    $("#enquiry_add_form input").prop("disabled", false);
    $("#enquiry_add_form select").prop("disabled", false);
    $("#enquiry_add_form textarea").prop("disabled", false);
   // $("#frmBasicMnote").prop("disabled", false);
});