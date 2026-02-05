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

    let products_grid = {
      data: data.data,
      columns: data.column,
      minDimensions: [4, 1],
      allowDeleteColumn: false,
      allowInsertRow: true,
      allowInsertColumn: false,
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
                  alertMessageFunction("cancelled")
                );
              }
            });
        } else {
          swalWithBootstrapButtons.fire(
            alertMessageFunction("validation_error")
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

  // Initial load
  getProductsGrid();
});
