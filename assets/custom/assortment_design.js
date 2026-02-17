(function () {
  var assortmentRedSheet = null;
  var sheetFactory = window.jexcel || window.jspreadsheet;
  var SUMMARY_LABELS = [
    'Qty. Per Carton:',
    'No. of Cartons:',
    'Sub Total Qty:',
    'UOM:',
    'Net Wt (Kgs):',
    'Gross Wt (Kgs):',
    'Carton Nos:'
  ];

  function getGridApi(instance) {
    if (instance && typeof instance.getValueFromCoords === 'function' && typeof instance.setValueFromCoords === 'function') {
      return instance;
    }
    if (instance && instance.jexcel && typeof instance.jexcel.getValueFromCoords === 'function' && typeof instance.jexcel.setValueFromCoords === 'function') {
      return instance.jexcel;
    }
    if (assortmentRedSheet && typeof assortmentRedSheet.getValueFromCoords === 'function' && typeof assortmentRedSheet.setValueFromCoords === 'function') {
      return assortmentRedSheet;
    }
    return null;
  }

  function getCellValue(instance, col, row) {
    var api = getGridApi(instance);
    if (api && typeof api.getValueFromCoords === 'function') {
      return api.getValueFromCoords(col, row);
    }
    if (api && api.options && api.options.data && api.options.data[row]) {
      return api.options.data[row][col];
    }
    return '';
  }

  function isSummaryRow(instance, row) {
    if (row < 0) return false;
    var label = String(getCellValue(instance, 4, row) || '').trim();
    return SUMMARY_LABELS.indexOf(label) >= 0;
  }

  function buildRedOptions() {
    var redCols = [
      { title: 'Style No.', width: 100, type: 'text', readOnly: false },
      { title: 'HS Code', width: 90, type: 'text', readOnly: false },
      { title: 'Unit Packing Code (UPC)', width: 130, type: 'text', readOnly: false },
      { title: 'Description of Goods', width: 150, type: 'text', readOnly: false },
      { title: 'Combo / Colour', width: 110, type: 'text', readOnly: false },
      { title: 'XS', width: 50, type: 'numeric', mask: '#,##' },
      { title: 'S', width: 50, type: 'numeric', mask: '#,##' },
      { title: 'M', width: 50, type: 'numeric', mask: '#,##' },
      { title: 'L', width: 50, type: 'numeric', mask: '#,##' },
      { title: 'XL', width: 50, type: 'numeric', mask: '#,##' },
      { title: 'XXL', width: 50, type: 'numeric', mask: '#,##' },
      { title: '3XL', width: 50, type: 'numeric', mask: '#,##' },
      { title: 'Total', width: 70, readOnly: true }
    ];

    var redData = [
      ['', '', '', '', '', '', '', '', '', '', '', '', ''],
      ['', '', '', '', 'Qty. Per Carton:', '', '', '', '', '', '', '', ''],
      ['', '', '', '', 'No. of Cartons:', '', '', '', '', '', '', '', ''],
      ['', '', '', '', 'Sub Total Qty:', '', '', '', '', '', '', '', ''],
      ['', '', '', '', 'UOM:', '', '', '', '', '', '', '', ''],
      ['', '', '', '', 'Net Wt (Kgs):', '', '', '', '', '', '', '', ''],
      ['', '', '', '', 'Gross Wt (Kgs):', '', '', '', '', '', '', '', ''],
      ['', '', '', '', 'Carton Nos:', '', '', '', '', '', '', '', '']
    ];

    return {
      data: redData,
      columns: redCols,
      minDimensions: [13, 1],
      tableOverflow: true,
      tableWidth: '100%',
      tableHeight: '360px',
      allowInsertRow: true,
      allowDeleteColumn: false,
      allowInsertColumn: false,
      updateTable: function (instance, cell, col, row) {
        var rowIndex = parseInt($(cell).attr('data-y'), 10);
        if (col !== 12) {
          $(cell).removeClass('readonly');
        }
        if (!isNaN(rowIndex) && isSummaryRow(instance, rowIndex)) {
          if (col === 4) {
            cell.style.fontWeight = '600';
            cell.style.textAlign = 'left';
            cell.style.background = '#f8fafc';
          } else if (col < 4) {
            cell.style.background = '#f8fafc';
          } else {
            cell.style.background = '';
            cell.style.fontWeight = '';
          }
        } else {
          cell.style.background = '';
          if (col === 5 || col >= 6) cell.style.fontWeight = '';
        }
        if (col >= 0 && col <= 4) {
          cell.style.textAlign = 'left';
        }
      },
      onchange: function (instance, cell, col, row) {
        var api = getGridApi(instance);
        if (!api) return;
        if (isSummaryRow(instance, row)) return;
        if (col >= 5 && col <= 11) {
          var total = 0;
          for (var i = 5; i <= 11; i++) {
            var val = parseFloat(getCellValue(api, i, row)) || 0;
            total += val;
          }
          api.setValueFromCoords(12, row, total ? total : '');
        }
      },
      oninsertrow: function () {},
      ondeleterow: function () {}
    };
  }

  function initAssortmentDesignGrid() {
    var redEl = document.getElementById('assortmentRedGrid');
    if (!redEl) return;

    if (typeof sheetFactory === 'undefined') {
      console.error('assortment_design.js: jexcel/jspreadsheet is not available');
      return;
    }

    if (!assortmentRedSheet) {
      redEl.innerHTML = '';
      assortmentRedSheet = sheetFactory(redEl, buildRedOptions());
    }
  }

  $(document).on('shown.bs.tab', 'a[href="#management_assortment_design"]', function () {
    setTimeout(initAssortmentDesignGrid, 80);
  });

  $(document).on('shown.bs.tab', 'a[href="#testlist"]', function () {
    if ($('#management_assortment_design').hasClass('active')) {
      setTimeout(initAssortmentDesignGrid, 80);
    }
  });

  $(function () {
    if ($('#management_assortment_design').hasClass('active')) {
      initAssortmentDesignGrid();
    }
  });
})();
