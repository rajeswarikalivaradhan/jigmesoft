let swalWithBootstrapButtons = Swal.mixin({buttonsStyling: false});
function fnPagination(VarURL) {
    var Parameters = GlbSearchParam;
    $("#DivTotalCntResult").html('');
    MakeAsynPostRequest(VarURL, Parameters, 'json', fnListRes);
}
function fnList() {
    var href = location.href;
    var parts = href.split('/');
     //console.log(parts)
    var lastSegment = parts.pop() || parts.pop();
    var id = atob(decodeURIComponent(lastSegment));
   // alert(id);
    $("#DivTotalCntResult").html('');
    GlbSearchParam = "rfrom=1&suscriberid="+ id ;
    MakeAsynPostRequest(base_path + GlbBAdminFdr + 'msubscription/detview', GlbSearchParam, 'json', fnListRes);
}
let hasProformaStandby = false; // Flag to check if any row has proforma_status == 3

function fnListRes(data) {
    //console.log(data.re, 'data');
   
    if (data != '') {
        if (data.errcode != undefined) {
            if (data.errcode == '404') {
                fnCallSessionExpire();
                return false;
            } else {
                var PageContent = '';              
                if (data.cn > 0) {
                    ListCount = '<div style="font-weight:bold;">Number of Record(s) : ' + data.cn + '</div>';
                    if (data.ct > 0) {
                    $.each(data.re, function (index, value) {
                        if (value.proforma_status == 3) {
                            hasProformaStandby = true; // Set flag to true if any row has proforma_status == 3
                        }
    
                        $('.header-titles').html(value.subscriber_refno);
                        PageContent = PageContent + '<tr>' + 
                                    '<td><input style="margin:0px!important" type="checkbox" class="allcbox" id="' + value.id + '"></td>' +
                                    '<td style="width:10%"><a href="' + base_path  + 'invoice/subproformainv/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.profinvno + '</a></td>' +
                                    '<td style="width:10%">' + value.profinvdate + '</td>' +
                                    '<td style="width:9%">' + value.profinvval + '</td><td style="width:10%">';

                        // Process supplData array
                        if(value.supplData.length>0){
                        $.each(value.supplData, function (idx, supplDataItem) {
                            // Append supplementary data to the row
                            PageContent += '<a href="' + base_path + 'invoice/subproformainv/' + encodeURIComponent(base64_encode(supplDataItem.suppliment_id)) + '">' + supplDataItem.supplprofinvono + '</a></br>';
                        });
                        }else{
                            PageContent += '-';  
                        }
                        // Continue appending remaining columns
                        PageContent += '</td><td style="width:9%">' + value.paymentmode + '</td>' +
                            '<td style="width:10%">' + value.chequeno + '</td>' +
                            '<td style="width:9%">' + value.chequedate + '</td>' +
                            '<td style="width:5%;">' + value.transval + '</td>' +
                            '<td style="width:5%"><a  href="' + base_path + GlbBAdminFdr + 'msubscription/invoice/' + encodeURIComponent(base64_encode(value.id)) + '">' + value.invno + '</a></td>' +
                            '<td style="width:9%">' + value.invdate + '</td>' +
                            '<td style="width:3%">' + value.invval + '</td><td style="width:3%">';
                    
                        // Process supplData array
                        if(value.supplData.length>0){
                            $.each(value.supplData, function (idx, supplDataItems) {
                            PageContent += '<a  href="' + base_path + GlbBAdminFdr + 'msubscription/invoice/' + encodeURIComponent(base64_encode(supplDataItems.suppliment_id)) + '">' + supplDataItems.supplinvono + '</a></br>' ;
                            }); 
                        }else{
                            PageContent += '-';
                        }
                        // if (value.proforma_status == 3) {
                        //     PageContent += '</td><td style="width:3%"><input type="button" class="proforma-btn" data-status="3" data-profstatus="'+hasProformaStandby+'" value="Pro. Inv."></td>';
                        // } else {
                        //     PageContent += '</td><td style="width:3%"><input type="button" class="proforma-btn" data-status="0" data-profstatus="'+hasProformaStandby+'" data-url="' + base_path + GlbBAdminFdr + 'mreqrcved/raiseproforma/' + encodeURIComponent(base64_encode(value.subscriber_id)) + '/sub" value="Pro. Inv."></td>';
                        // }
                        PageContent += '</td><td style="width:3%">'+value.proformainv_status+'</td>';
                        PageContent +='<td style="width:3%"><a href="' + base_path + GlbBAdminFdr + 'msubscription/detviews/' + encodeURIComponent(base64_encode(value.subscriber_id)) + '/' + encodeURIComponent(base64_encode(value.id)) + '">View</a></td>';
                        PageContent += '</tr>';
                     });
                    }
                    $("#DivTotalCntResult").html(ListCount);
                } else {
                    PageContent = PageContent + '<tr><td colspan="12" class="pdl15 herr text-center" style="padding-left:10px;">No Records(s) found</td></tr>';
                    $("#DivTotalCntResult").html('');
                }
                if (data.pa != undefined) {
                    console.log(base64_decode(data.pa))
                    $("#ResPagination").html(base64_decode(data.pa));
                }
                
                // $('tbody').empty();
                // $('#brandTblList').append(PageContent);
                //tableId
                $('#tableId tbody').empty();
                $('#tableId').append(PageContent).DataTable();
            }
        }
    }
}
// $(document).on('click', '.proforma-btn', function () { // for each row wise btn validation
//     let status = $(this).data('profstatus');
//     let url = $(this).data('url');
//     if (status==true) {
//         alert('Already one proforma in standby state.');
//         return false;
//     } else if (url) {
//         window.location.href = url;
//     }
// });

$(document).on('click', '.proformainv-btn', function () {
    var href = location.href;
    var parts = href.split('/');
     //console.log(parts)
    var lastSegment = parts.pop() || parts.pop();
    var subscriber_id = atob(decodeURIComponent(lastSegment));
    var url=base_path + GlbBAdminFdr + 'mreqrcved/raiseproforma/' + encodeURIComponent(base64_encode(subscriber_id)) + '/sub';
    if (hasProformaStandby==true) {
        // alert('Already a proforma exsist in standby mode.');
        // return false;
        swalWithBootstrapButtons.fire({
            title: 'Already a proforma exsist in standby mode.',
            type: 'warning',
            icon: 'warning',
            width:460,
            allowOutsideClick:false,
            customClass: {'confirmButton': 'btn btn-info'}
        });
        return false;
    } else if (url) {
        window.location.href = url;
    }
});