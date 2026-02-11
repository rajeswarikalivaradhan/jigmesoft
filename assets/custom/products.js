$(document).ready(function () {
  // *******************************
  // PRODUCTS GRID WITH JSSPREADSHEET
  // *******************************

  var products_grid_vm = null;

  var swalWithBootstrapButtons = Swal.mixin({
    buttonsStyling: false,
  });

  function alertMessageFunction(mode) {
    if (mode === "confirmation_save") {
      return {
        title: "Are you sure want to \n save the details ?",
        type: "warning",
        showCancelButton: true,
        scrollbarPadding: false,
        confirmButtonText: "Yes, do it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true,
        customClass: {
          confirmButton: "btn btn-green mx-2 px-3",
          cancelButton: "btn btn-red mx-2 px-3",
        },
      };
    }

    if (mode == "saved") {
      return {
        title: "Saved!",
        text: "Operation completed successfully.",
        icon: "success",
        customClass: {
          confirmButton: "btn btn-info px-5",
        },
      };
    }

    if (mode == "cancelled") {
      return {
        title: "Cancelled",
        text: "Cancelled successfully.",
        icon: "error",
        customClass: {
          confirmButton: "btn btn-secondary px-5",
        },
      };
    }

    if (mode == "validation_error") {
      return {
        title: "Warning",
        text: "Please fill all mandatory fields",
        icon: "warning",
        confirmButtonText: "OK",
        customClass: {
          confirmButton: "btn btn-secondary px-5",
        },
      };
    }
  }

  function validateProductsGrid(dataValue) {
    // product_name (2), category (3), price (4), quantity (5)
    let validateField = [2, 4]; // name & price mandatory
    let errorCount = 0;

    for (let i = 0; i < dataValue.length; i++) {
      // Skip completely empty rows
      if (
        (dataValue[i][2] === "" || dataValue[i][2] === null) &&
        (dataValue[i][3] === "" || dataValue[i][3] === null) &&
        (dataValue[i][4] === "" || dataValue[i][4] === null) &&
        (dataValue[i][5] === "" || dataValue[i][5] === null)
      ) {
        continue;
      }

      for (let j = 0; j < validateField.length; j++) {
        let col = validateField[j];
        if (dataValue[i][col] === "" || dataValue[i][col] === null) {
          errorCount++;
        }
      }
    }

    return errorCount;
  }

  function getProductsGrid() {
    let request = $.ajax({
      type: "POST",
      url: base_path + "Products/getProductsGrid",
      data: { enquiry_id: enquiry_id },
      success: function (data) {
        let res = JSON.parse(data);
        appendProductsGrid(res);
      },
      error: function () {
        console.log("Error loading products grid");
      },
    });
  }

  function appendProductsGrid(data) {
    $("#productsGridSheet").html("");

    let dd = [],
      updatedRow = -1,
      index = -1;

    // Set Product column as dropdown with products list (when available)
    if (
      data.column &&
      data.column[2] &&
      (data.products_list || []).length > 0
    ) {
      data.column[2].type = "dropdown";
      data.column[2].source = data.products_list;
    }

    function calcRowTotal(data, row) {
      var p = parseFloat(data[row] && data[row][4]) || 0;
      var q = parseInt(data[row] && data[row][5], 10) || 0;
      return p * q;
    }
    // Footer row like Combo/Colour Wise: "Total:" on left, sum in Total column
    function productsGridFooter() {
      return [
        ["", "", "", "", "", "Total:", '=SUMCOL(TABLE(), COLUMN(), "")', ""],
      ];
    }

    let products_grid = {
      data: data.data,
      columns: data.column,
      minDimensions: [8, 1],
      allowDeleteColumn: false,
      allowInsertRow: true,
      allowInsertColumn: false,
      footers: productsGridFooter(),
      onchange: function (instance, cell, col, row, val, label, cellName) {
        if (col == 2) {
          updatedRow = row;
          var txt = $(cell).text();
          dd = products_grid.columns[2]["source"] || [];
          if (txt != "") {
            index = dd.findIndex(function (p) {
              return (
                txt.includes(p.name) ||
                p.name === txt ||
                String(p.id) === String(txt)
              );
            });
            if (index >= 0) {
              // Store product_id so dropdown shows selection after save/reload
              products_grid.data[row][2] = dd[index].id;
              products_grid.data[row][3] = dd[index].category || "";
              products_grid.data[row][4] =
                dd[index].price !== undefined && dd[index].price !== null
                  ? dd[index].price
                  : "";
              // Quantity: user enters; Total = price * quantity
              products_grid.data[row][5] = "";
            } else {
              products_grid.data[row][3] = "";
              products_grid.data[row][4] = "";
              products_grid.data[row][5] = "";
            }
          } else {
            products_grid.data[row][3] = "";
            products_grid.data[row][4] = "";
            products_grid.data[row][5] = "";
          }
          products_grid.data[row][6] = calcRowTotal(products_grid.data, row);
        }
        if (col == 4 || col == 5) {
          products_grid.data[row][6] = calcRowTotal(products_grid.data, row);
        }
      },
      updateTable: function (instance, cell, col, row, val, label, cellName) {
        if (col == 2) {
          val = $(cell).text();
        }
        var src =
          products_grid.columns[2] && products_grid.columns[2].source
            ? products_grid.columns[2].source
            : [];
        var rowProductId =
          instance &&
          instance.options &&
          instance.options.data &&
          instance.options.data[row]
            ? instance.options.data[row][2]
            : null;
        var idxByLoad = -1;
        if (rowProductId != null && rowProductId !== "" && src.length) {
          idxByLoad = src.findIndex(function (p) {
            return String(p.id) === String(rowProductId);
          });
        }
        if (col == 3) {
          if (val != "" && dd.length > 0 && row == updatedRow && index >= 0) {
            $(cell).text(dd[index]["category"] || "");
          } else if (idxByLoad >= 0) {
            $(cell).text(src[idxByLoad]["category"] || "");
          } else if (val == "") {
            $(cell).text("");
          }
        }
        if (col == 4) {
          if (val != "" && dd.length > 0 && row == updatedRow && index >= 0) {
            $(cell).text(
              dd[index]["price"] !== undefined && dd[index]["price"] !== null
                ? dd[index]["price"]
                : "",
            );
          } else if (idxByLoad >= 0) {
            $(cell).text(
              src[idxByLoad]["price"] !== undefined &&
                src[idxByLoad]["price"] !== null
                ? src[idxByLoad]["price"]
                : "",
            );
          } else if (val == "") {
            $(cell).text("");
          }
        }
        if (col == 5) {
          // Quantity is user-entered only; do not fill from product dropdown
          if (val == "") {
            $(cell).text("");
          }
        }
        if (col == 6) {
          var gridData =
            instance && instance.options && instance.options.data
              ? instance.options.data
              : products_grid.data;
          var total = calcRowTotal(gridData, row);
          $(cell).text(total > 0 ? total : "");
        }
      },
    };

    products_grid_vm = new Vue({
      el: "#productsGridSheet",
      mounted: function () {
        let spreadsheet = jexcel(this.$el, products_grid);
        Object.assign(this, spreadsheet);
      },
      methods: {
        submitData: function () {
          let data = this.getData();
          updateProductsGrid(data);
        },
      },
    });

    // Attach click handler to save button
    $("#productsGridSubmit")
      .off("click")
      .on("click", function () {
        let data = products_grid_vm.getData();
        let validate = validateProductsGrid(data);

        if (validate === 0) {
          swalWithBootstrapButtons
            .fire(alertMessageFunction("confirmation_save"))
            .then(function (result) {
              if (result.value) {
                products_grid_vm.submitData();
              } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                  alertMessageFunction("cancelled"),
                );
              }
            });
        } else {
          swalWithBootstrapButtons.fire(
            alertMessageFunction("validation_error"),
          );
        }
      });
  }

  function updateProductsGrid(data) {
    let dataform = new FormData();
    dataform.append("data", JSON.stringify(data));
    dataform.append("enquiry_id", enquiry_id);

    let request = $.ajax({
      type: "POST",
      url: base_path + "Products/updateProductsGrid",
      data: dataform,
      processData: false,
      contentType: false,
      cache: false,
      success: function (data) {
        var res = $.parseJSON(data);
        if (res.status == "success") {
          getProductsGrid();
          swalWithBootstrapButtons.fire(alertMessageFunction("saved"));
        } else {
          swalWithBootstrapButtons.fire({
            title: "Error!",
            text: "Operation Failed.",
            icon: "error",
            customClass: {
              confirmButton: "btn btn-info px-5",
            },
          });
        }
      },
      error: function () {
        console.log("Error updating products grid");
      },
    });
  }

  // Load products grid when TestList tab is shown (grid does not render when container is hidden)
  $(document).on("shown.bs.tab", 'a[href="#testlist"]', function () {
    if (!products_grid_vm || !products_grid_vm.getData) {
      getProductsGrid();
    }
  });
  // If TestList is already active on load (e.g. direct link), load immediately
  if ($("#testlist").hasClass("active")) {
    getProductsGrid();
  }
});
