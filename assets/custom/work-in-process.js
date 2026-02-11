var wipJSON;
const activeBtn = document.getElementById("btn-active");
const inactiveBtn = document.getElementById("btn-inactive");
$(document).ready(function () {
  if (sessionStorage.getItem("keepSearchOpen") === "true") {
    $(".search_area").removeClass("hide"); // show search div
    $(".fa-search-plus").removeClass("fa-search-plus").addClass("fa-search");
    sessionStorage.removeItem("keepSearchOpen"); // clear flag
  }

  var wipJSON;
  $.when(getWIPList()).done(function () {
    dispDetails(wipJSON);
  });

  $(document).ajaxStart(function (a) {
    $.LoadingOverlay("show", { image: "../assets/img/fullpage.gif" });
  });
  $(document).ajaxStop(function () {
    $.LoadingOverlay("hide");
  });

  function getWIPList() {
    return $.ajax({
      url: base_path + "merchant/getWIPList",
      type: "POST",
      success: function (data) {
        wipJSON = $.parseJSON(data);
        console.log(wipJSON, "wipJSON");
      },
      error: function () {
        console.log("Error");
      },
    });
  }

  function dispDetails(wipJSON) {
    if ($.fn.DataTable.isDataTable("#workInProgressTbl")) {
      $("#workInProgressTbl").DataTable().destroy();
    }

    $("#workInProgressTbl tbody").empty();
    $("#workInProgressTbl").dataTable({
      aaData: wipJSON,
      aaSorting: [],
      aoColumns: [
        {
          mDataProp: function (data, type, full, meta) {
            return (
              '<tr><td><input type="checkbox" id="' +
              data.id +
              '" class="allcbox"></td>'
            );
          },
        },
        {
          mDataProp: function (data, type, full, meta) {
            return (
              '<td><a href="' +
              base_path +
              "Merchant/wipPrecosting/" +
              encodeURIComponent(btoa(data.id)) +
              '">' +
              data.isriorcode +
              "</a></td>"
            );
          },
        },
        {
          mDataProp: function (data, type, full, meta) {
            var d = new Date(data.dateauthorized);
            var time = d.toLocaleString("en-US", {
              hour: "2-digit",
              minute: "2-digit",
              hour12: true,
            });
            var dFormat =
              ("0" + d.getDate()).slice(-2) +
              "/" +
              ("0" + (d.getMonth() + 1)).slice(-2) +
              "/" +
              d.getFullYear() +
              " " +
              time;
            return dFormat;
          },
        },
        { mDataProp: "orderenqrefno" },
        { mDataProp: "stylenamerefno" },
        {
          mDataProp: function (data, type, full, meta) {
            urlIdPart = encodeURIComponent(btoa(data.id));
            if (data.show == 1) {
              var Budget =
                '<a href="' +
                base_path +
                "budgetCosting/index/" +
                urlIdPart +
                '" >' +
                "Budget</a>";
              var orderEntryLink =
                '<a href="' +
                base_path +
                "orderentryvtwo/entry/" +
                urlIdPart +
                '" >' +
                "Order Entry</a>";
              var CAD =
                '<a href="javascript:void(0)" target="_blank">CAD Requirement</a>';
              var bomLink =
                '<a href="' +
                base_path +
                "billofmaterials/article_1/" +
                urlIdPart +
                '" >' +
                "BOM Program</a>";
              var fabricProgramLink =
                '<a href="' +
                base_path +
                "fabricprogram/home/" +
                urlIdPart +
                '" >' +
                "Fabric Programme</a>";
              var sampleReqLink =
                '<a href="' +
                base_path +
                "msamplerequest/addeditsamplerequest/" +
                urlIdPart +
                '" >' +
                "Sample Requirement</a>";
              var bomPurchaseRequestLink =
                '<a href="' +
                base_path +
                "mpurchase/addeditbompurchase/" +
                urlIdPart +
                '" >' +
                "BOM (A1) Programme</a>";
              var bomA2 =
                '<a href="javascript:void(0)" >BOM (A2) Programme</a>';
              var establishment =
                '<a href="javascript:void(0)" >Establishment Programme</a>';
              var packing = '<a href="javascript:void(0)" >Packing Details</a>';
              var lab = '<a href="javascript:void(0)" >Lab Requirement</a>';
              var fidetails = '<a href="javascript:void(0)" >F.I. Details</a>';
              var documentLogistics =
                '<a href="javascript:void(0)" >Documentation & Logistics</a>';
              var checklist = '<a href="javascript:void(0)" >Check List</a>';
              var precosting = '<a href="javascript:void(0)" >Pre-costing</a>';
              var cadRequestLink =
                '<a href="javascript:void(0)" >' + "CAD Request</a>";
              var sampleRequestLink =
                '<a href="javascript:void(0)" >' + "Sample Request</a>";
              var bomRequestLink =
                '<a href="javascript:void(0)" >' + "BOM Request</a>";
            } else {
              var Budget = '<a href="javascript:void(0)" >Budget</a>';
              // var orderEntryLink = '<a href="javascript:void(0)" target="_blank">Order Entry</a>';
              var orderEntryLink =
                '<a href="' +
                base_path +
                "WorkInProcess/index/" +
                urlIdPart +
                '" >' +
                "Order Entry</a>";
              var CAD = '<a href="javascript:void(0)" >CAD Requirement</a>';
              var bomLink = '<a href="javascript:void(0)" >BOM Program</a>';
              var fabricProgramLink =
                '<a href="' +
                base_path +
                "WorkInProcess/fabric_program/" +
                urlIdPart +
                '" >Fabric Program</a>';
              var cadRequestLink =
                '<a href="javascript:void(0)" >CAD Request</a>';
              var sampleReqLink =
                '<a href="javascript:void(0)" >Sample Requirement</a>';
              var bomPurchaseRequestLink =
                '<a href="javascript:void(0)" >BOM (A1) Request</a>';
              var bomA2 =
                '<a href="javascript:void(0)" >BOM (A2) Programme</a>';
              var establishment =
                '<a href="javascript:void(0)" >Establishment Programme</a>';
              var packing = '<a href="javascript:void(0)" >Packing Details</a>';
              var lab = '<a href="javascript:void(0)" >Lab Requirement</a>';
              var fidetails = '<a href="javascript:void(0)" >F.I. Details</a>';
              var documentLogistics =
                '<a href="javascript:void(0)" >Documentation & Logistics</a>';
              var checklist = '<a href="javascript:void(0)" >Check List</a>';
              var precosting = '<a href="javascript:void(0)" >Pre-costing</a>';
              var cadRequestLink =
                '<a href="javascript:void(0)" >' + "CAD Request</a>";
              var sampleRequestLink =
                '<a href="javascript:void(0)" >' + "Sample Request</a>";
              var bomRequestLink =
                '<a href="javascript:void(0)" >' + "BOM Request</a>";
            }

            if (data.reqforisrior == 1)
              return (
                '<td><div class="dropdown">' +
                '<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                data.brandname +
                '<span class="caret"></span>' +
                "</button>" +
                '<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">' +
                "<li>" +
                orderEntryLink +
                "</li>" +
                "<li>" +
                fabricProgramLink +
                "</li>" +
                "</ul></div><td>"
              );
            else if (data.reqforisrior == 2)
              return (
                '<td><div class="dropdown">' +
                '<button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                data.brandname +
                '<span class="caret"></span>' +
                "</button>" +
                '<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">' +
                "<li>" +
                orderEntryLink +
                "</li>" +
                "<li>" +
                fabricProgramLink +
                "</li>" +
                "</ul></div><td>"
              );
          },
        },
        { mDataProp: "poQtySampleRefQty" },
        { mDataProp: "poQtySampleQty" },
        { mDataProp: "poPcsSet" },
        { mDataProp: "poShipmentDate" },
        // {
        // 	"mDataProp": function ( data, type, full, meta) {
        //         var d = new Date(data.formattedShipmentSubDate);
        //         var time = d.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
        //         var dFormat = ("0" + (d.getDate())).slice(-2) + '/' + ("0" + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear() + ' ' + time;
        // 		return dFormat;
        // 	}
        // },
        {
          mDataProp: function (data, type, full, meta) {
            return "-";
          },
        },
        {
          mDataProp: function (data, type, full, meta) {
            var d = new Date(data.formattedDateUpdated);
            var time = d.toLocaleString("en-US", {
              hour: "2-digit",
              minute: "2-digit",
              hour12: true,
            });
            var dFormat =
              ("0" + d.getDate()).slice(-2) +
              "/" +
              ("0" + (d.getMonth() + 1)).slice(-2) +
              "/" +
              d.getFullYear() +
              " " +
              time;
            return dFormat;
          },
        },
        {
          mDataProp: function (data, type, full, meta) {
            if (data.status == "1") return "Active";
            else if (data.status == "2") return "Inactive";
            else return "";
          },
        },
      ],
    });
  }

  // $('#searchButton').click(function() {
  //     var form = $('#searchForm')[0];
  //     var data = new FormData(form);
  //     var url = base_path + "merchant/searchWIPList";
  //     $.ajax({
  //         url: url,
  //         method: "POST",
  //         data: data,
  //         contentType: false,
  //         cache: false,
  //         processData: false,
  //         success: function(data) {
  //             wipJSON = $.parseJSON(data);
  //             dispDetails(wipJSON);
  //         }
  //     });
  // });

  // $('#refreshBtn').on('click', function () {
  //     // location.reload();
  //     var element = document.getElementById('searchForm').reset();
  //         $('.js-example-basic-single').val(null).trigger('change');
  //         $('#searchButton').trigger('click');
  // });
  $("#refreshBtn").on("click", function () {
    sessionStorage.setItem("keepSearchOpen", "true"); // remember user preference
    location.reload(); // reload the page
  });

  let swalWithBootstrapButtons = Swal.mixin({ buttonsStyling: false });

  $("#btnChangeStatus").on("click", function () {
    var dropdownOpt = $("#frmItemStatus").val();
    console.log(dropdownOpt, "dropdownOpt");
    var SelectedIdObject = commonCheckbox();
    var checkBoxLength = SelectedIdObject[1];
    if (dropdownOpt > 0) {
      if (checkBoxLength >= 1) {
        var idJson = JSON.stringify(SelectedIdObject[0]);
        var StatusText = "Deactivate";
        if (dropdownOpt == 1) {
          var StatusText = "Activate";
        }
        swalWithBootstrapButtons
          .fire({
            title: "Do you want to " + StatusText + " this record ?",
            type: "warning",
            showCancelButton: true,
            scrollbarPadding: false,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            reverseButtons: true,
            width: 460,
            customClass: {
              confirmButton: "btn btn-green mx-2 px-3",
              cancelButton: "btn btn-red mx-2 px-3",
            },
          })
          .then(function (result) {
            if (result.value) {
              MakeAsynPostRequest(
                base_path + "dashboard/changeWipStatus",
                "id=" + idJson + "&cs=" + dropdownOpt,
                "json",
                function (data) {
                  $.when(getWIPList()).done(function () {
                    dispDetails(wipJSON);
                  });
                },
              );
            }
          });
        // if (confirm('Do you want to ' + StatusText + ' this records?')) {
        //     MakeAsynPostRequest(base_path + 'dashboard/changeWipStatus', 'id=' + idJson + '&cs=' + dropdownOpt, 'json', function (data) {
        //         $.when(getWIPList()).done(function(){
        //             dispDetails(wipJSON);
        //         });
        //     });
        // }
      }
    } else {
      // alert('Select a option');
      swalWithBootstrapButtons.fire({
        title: "Select a option!",
        type: "error",
        icon: "error",
        customClass: { confirmButton: "btn btn-info px-5" },
      });
    }
    if (checkBoxLength == 0) {
      // alert('Select a record');
      swalWithBootstrapButtons.fire({
        title: "Select a record!",
        type: "error",
        icon: "error",
        customClass: { confirmButton: "btn btn-info px-5" },
      });
    }
  });

  $(".allwiplist li a").click(function () {
    var id = $(this).attr("id");
    //alert(id);
    // $('.allwiplist li.active').removeClass('active');
    //     $(this).closest('li').addClass('active');

    $(this).closest("ul").find("li.active").removeClass("active");
    $(this).closest("li").addClass("active");

    //console.log($(this));
    var allstatus;
    if (id == "active" || id == "isractive" || id == "ioractive") {
      allstatus = 1;
      $(this).closest("ul").find("li.active").removeClass("active");

      // alert("act");
    } else if (id == "inactive" || id == "isrinactive" || id == "iorinactive") {
      allstatus = 2;
      $(this).closest("ul").find("li.active").removeClass("active");
    } else {
      allstatus = "";
      $(this).closest("ul").find("li.active").removeClass("active");
    }

    var isr;
    if (id == "isractive" || id == "isrinactive") {
      isr = "something";
    } else {
      isr = "";
    }
    var ior;
    if (id == "ioractive" || id == "iorinactive") {
      ior = "something";
    } else {
      ior = "";
    }
    //alert(id);
    var url = base_path + "merchant/searchWIPList";
    $.ajax({
      url: url,
      method: "POST",
      data: { status: allstatus, isr: isr, ior: ior },
      success: function (data) {
        console.log(data);
        enquiryJSON = $.parseJSON(data);
        dispDetails(enquiryJSON);
      },
    });
  });

  $("#btn-active").on("click", function () {
    satusval = "1";
    activeBtn.classList.add("active");
    inactiveBtn.classList.remove("active");
    const wipJSON1 = wipJSON.filter((item) => item.status === satusval);
    dispDetails(wipJSON1);
  });
  $("#btn-inactive").on("click", function () {
    satusval = "2";
    inactiveBtn.classList.add("active");
    activeBtn.classList.remove("active");
    const wipJSON1 = wipJSON.filter((item) => item.status === satusval);
    dispDetails(wipJSON1);
  });

  //           $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

  //     // Helper: parse table date format "dd/mm/yyyy hh:mm am/pm"
  //     function parseTableDate(str) {
  //         if (!str) return null;
  //         var parts = str.split(' ')[0].split('/'); // ["dd","mm","yyyy"]
  //         var timePart = str.split(' ')[1];         // "hh:mm"
  //         var ampm = str.split(' ')[2];             // "am" or "pm"

  //         var hours = 0, minutes = 0;
  //         if (timePart) {
  //             var timeParts = timePart.split(':');
  //             hours = parseInt(timeParts[0], 10);
  //             minutes = parseInt(timeParts[1], 10);
  //             if (ampm && ampm.toLowerCase() === 'pm' && hours < 12) hours += 12;
  //             if (ampm && ampm.toLowerCase() === 'am' && hours === 12) hours = 0;
  //         }

  //         return new Date(parts[2], parts[1] - 1, parts[0], hours, minutes);
  //     }

  //     // Helper: parse input date format "dd-mm-yyyy"
  //     function parseInputDate(str) {
  //         if (!str) return null;
  //         var parts = str.split('-'); // ["dd","mm","yyyy"]
  //         return new Date(parts[2], parts[1] - 1, parts[0]);
  //     }

  //     // --- Request Date filter ---
  //     var requestFrom = parseInputDate($('#RequestFrom').val());
  //     var requestTo   = parseInputDate($('#RequestTo').val());

  //     // Get all dates from the cell (comma-separated)
  //     var cellDates = data[9] ? data[9].split(',') : []; // column index 9
  //     var requestDates = cellDates.map(function(d) { return parseTableDate(d.trim()); });

  //     // Adjust filter times
  //     if (requestFrom) requestFrom.setHours(0,0,0,0);
  //     if (requestTo) requestTo.setHours(23,59,59,999);

  //     // Check if any date in the row falls within the range
  //     var isInRange = requestDates.some(function(date) {
  //         if (!date) return false;
  //         date.setHours(0,0,0,0);
  //         if (requestFrom && date < requestFrom) return false;
  //         if (requestTo && date > requestTo) return false;
  //         return true;
  //     });

  //     return isInRange;
  // });

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    // Helper: parse table date format "dd/mm/yyyy hh:mm am/pm"
    function parseTableDate(str) {
      if (!str) return null;
      var parts = str.split(" ")[0].split("/"); // ["dd","mm","yyyy"]
      var timePart = str.split(" ")[1]; // "hh:mm"
      var ampm = str.split(" ")[2]; // "AM" or "PM"

      var hours = 0,
        minutes = 0;
      if (timePart) {
        var timeParts = timePart.split(":");
        hours = parseInt(timeParts[0], 10);
        minutes = parseInt(timeParts[1], 10);
        if (ampm && ampm.toLowerCase() === "pm" && hours < 12) hours += 12;
        if (ampm && ampm.toLowerCase() === "am" && hours === 12) hours = 0;
      }

      return new Date(parts[2], parts[1] - 1, parts[0], hours, minutes);
    }

    // Helper: parse input date format "dd-mm-yyyy"
    function parseInputDate(str) {
      if (!str) return null;
      var parts = str.split("-"); // ["dd","mm","yyyy"]
      return new Date(parts[2], parts[1] - 1, parts[0]);
    }

    // Get filter dates
    var requestFrom = parseInputDate($("#RequestFrom").val());
    var requestTo = parseInputDate($("#RequestTo").val());

    if (requestFrom) requestFrom.setHours(0, 0, 0, 0);
    if (requestTo) requestTo.setHours(23, 59, 59, 999);

    // Get all dates from the cell (split by newlines)
    var cellValue = data[9] || ""; // column index 9
    var cellDates = cellValue.split(/\r?\n/); // split by newline (Windows or Unix)
    var requestDates = cellDates.map(function (d) {
      return parseTableDate(d.trim());
    });

    // Check if any date is within range
    var isInRange = requestDates.some(function (date) {
      if (!date) return false;
      date.setHours(0, 0, 0, 0);
      if (requestFrom && date < requestFrom) return false;
      if (requestTo && date > requestTo) return false;
      return true;
    });

    return isInRange;
  });

  $("#searchButton").on("click", function () {
    var fromDate = $("#RequestFrom").val().trim();
    var toDate = $("#RequestTo").val().trim();
    // var cutfromDate = $('#CutoffFrom').val().trim();
    // var cuttoDate = $('#CutoffTo').val().trim();

    // Helper function to parse dd-mm-yyyy
    function parseDate(str) {
      var parts = str.split("-"); // ["dd", "mm", "yyyy"]
      return new Date(parts[2], parts[1] - 1, parts[0]); // year, month (0-based), day
    }

    // 1️⃣ Check RequestFrom / RequestTo
    if (fromDate !== "") {
      if (fromDate === "" || toDate === "") {
        swalWithBootstrapButtons.fire({
          title: "Select both From and To dates!",
          icon: "error",
          customClass: { confirmButton: "btn btn-info px-5" },
        });
        return false;
      }
    }

    if (fromDate !== "" && toDate !== "") {
      var from = parseDate(fromDate);
      var to = parseDate(toDate);

      if (from >= to) {
        swalWithBootstrapButtons.fire({
          title: "Invalid date range. From date cannot be later than To date.",
          icon: "error",
          customClass: { confirmButton: "btn btn-info px-5" },
        });
        return false;
      }
    }
    // 2️⃣ Check CutoffFrom / CutoffTo
    //     if(cutfromDate!==''){
    //     if (cutfromDate === '' || cuttoDate === '') {
    //         swalWithBootstrapButtons.fire({
    //             title: 'Select both Cutoff From and To dates!',
    //             icon: 'error',
    //             customClass: { 'confirmButton': 'btn btn-info px-5' }
    //         });
    //         return false;
    //     }
    // }
    //   if(cutfromDate!=='' && cuttoDate!==''){
    //     var cutFrom = parseDate(cutfromDate);
    //     var cutTo = parseDate(cuttoDate); // ✅ make sure this matches your input ID exactly: CutoffTo

    //     if (cutFrom >= cutTo) {
    //         swalWithBootstrapButtons.fire({
    //             title: 'Invalid cutoff date range. From date cannot be later than To date.',
    //             icon: 'error',
    //             customClass: { 'confirmButton': 'btn btn-info px-5' }
    //         });
    //         return false;
    //     }
    //   }
    var table = $("#workInProgressTbl").DataTable();

    var wip_ref_no = $("#wip_ref_no").val().toLowerCase();
    var style_ref_no = $("#style_ref_no").val().toLowerCase();
    var brandId = $("#brandId").val().toLowerCase();
    var po_sam_ref_no = $("#po_sam_ref_no").val().toLowerCase();
    var po_sam_qty = $("#po_sam_qty").val().toLowerCase();
    var pcs_set = $("#pcs_set").val().toLowerCase();

    table
      .column(1)
      .search(wip_ref_no)
      .column(4)
      .search(style_ref_no)
      .column(5)
      .search(brandId)
      .column(6)
      .search(po_sam_ref_no)
      .column(7)
      .search(po_sam_qty)
      .column(8)
      .search(pcs_set)

      .draw(); // ✅ redraw triggers custom filter
  });
});
