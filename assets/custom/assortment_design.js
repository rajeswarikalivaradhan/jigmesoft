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
  var DISABLED_TOTAL_LABELS = [
    'No. of Cartons:',
    'Sub Total Qty:',
    'Net Wt (Kgs):',
    'Gross Wt (Kgs):',
    'Carton Nos:'
  ];
  var isAutoUpdating = false;
  var loadStarted = false;

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

  function getSummaryLabel(instance, row) {
    return String(getCellValue(instance, 4, row) || '').trim();
  }

  function isDisabledTotalRow(instance, row) {
    return DISABLED_TOTAL_LABELS.indexOf(getSummaryLabel(instance, row)) >= 0;
  }

  function clearFrozenColumnsAfterFirstRow(instance) {
    var api = getGridApi(instance);
    if (!api || !api.options || !api.options.data) return;
    for (var r = 1; r < api.options.data.length; r++) {
      for (var c = 0; c <= 3; c++) {
        if (String(getCellValue(api, c, r) || '').trim() !== '') {
          api.setValueFromCoords(c, r, '');
        }
      }
    }
  }

  function getTotalColumnIndex(instance) {
    var api = getGridApi(instance);
    if (!api || !api.options || !api.options.columns) return 12;
    return Math.max(api.options.columns.length - 1, 0);
  }

  function getSizeStartColumnIndex() {
    return 5;
  }

  function getSizeEndColumnIndex(instance) {
    return Math.max(getTotalColumnIndex(instance) - 1, getSizeStartColumnIndex());
  }

  function findSummaryRow(instance, label) {
    var api = getGridApi(instance);
    if (!api || !api.options || !api.options.data) return -1;
    for (var r = 0; r < api.options.data.length; r++) {
      if (String(getCellValue(api, 4, r) || '').trim() === label) return r;
    }
    return -1;
  }

  function getNumericCellValue(instance, col, row) {
    var raw = getCellValue(instance, col, row);
    var clean = String(raw == null ? '' : raw).replace(/,/g, '').trim();
    var num = parseFloat(clean);
    return isNaN(num) ? 0 : num;
  }

  function setNumericCellValue(instance, col, row, value) {
    var api = getGridApi(instance);
    if (!api) return;
    api.setValueFromCoords(col, row, value ? value : '', true);
  }

  function setCellValue(instance, col, row, value) {
    var api = getGridApi(instance);
    if (!api) return;
    api.setValueFromCoords(col, row, value == null ? '' : value, true);
  }

  function getSheetDataForSave(instance) {
    var api = getGridApi(instance);
    if (!api) return [];
    if (typeof api.getData === 'function') {
      return api.getData();
    }
    if (api.options && api.options.data) {
      return api.options.data;
    }
    return [];
  }

  function getColumnTitles(instance) {
    var api = getGridApi(instance);
    if (!api || !api.options || !api.options.columns) return [];
    var out = [];
    for (var i = 0; i < api.options.columns.length; i++) {
      out.push(String(api.options.columns[i].title || ''));
    }
    return out;
  }

  function serializeSheetData(instance) {
    var matrix = getSheetDataForSave(instance);
    var columns = getColumnTitles(instance);
    var rows = [];
    for (var r = 0; r < matrix.length; r++) {
      var rowObj = {};
      for (var c = 0; c < columns.length; c++) {
        rowObj[columns[c]] = (matrix[r] && typeof matrix[r][c] !== 'undefined') ? matrix[r][c] : '';
      }
      rows.push(rowObj);
    }
    return {
      version: 2,
      columns: columns,
      rows: rows
    };
  }

  function deserializeSheetData(saved) {
    if (!saved) return null;
    if (Array.isArray(saved)) return saved; // legacy format
    if (!saved.rows || !Array.isArray(saved.rows) || !saved.columns || !Array.isArray(saved.columns)) return null;

    var matrix = [];
    for (var r = 0; r < saved.rows.length; r++) {
      var rowObj = saved.rows[r] || {};
      var row = [];
      for (var c = 0; c < saved.columns.length; c++) {
        var title = saved.columns[c];
        row.push(typeof rowObj[title] === 'undefined' ? '' : rowObj[title]);
      }
      matrix.push(row);
    }
    return matrix;
  }

  function sumRowAcrossSizes(instance, row) {
    var start = getSizeStartColumnIndex();
    var end = getSizeEndColumnIndex(instance);
    var total = 0;
    for (var c = start; c <= end; c++) {
      total += getNumericCellValue(instance, c, row);
    }
    return total;
  }

  function recalcDetailRowTotal(instance, row) {
    if (isSummaryRow(instance, row)) return;
    var api = getGridApi(instance);
    if (!api) return;
    var totalCol = getTotalColumnIndex(api);
    setCellValue(api, totalCol, row, '');
  }

  function recalcSummaryRows(instance) {
    var api = getGridApi(instance);
    if (!api) return;

    var qtyPerCartonRow = findSummaryRow(api, 'Qty. Per Carton:');
    var noOfCartonsRow = findSummaryRow(api, 'No. of Cartons:');
    var subTotalQtyRow = findSummaryRow(api, 'Sub Total Qty:');
    var netWtRow = findSummaryRow(api, 'Net Wt (Kgs):');
    var grossWtRow = findSummaryRow(api, 'Gross Wt (Kgs):');
    var totalCol = getTotalColumnIndex(api);
    var start = getSizeStartColumnIndex();
    var end = getSizeEndColumnIndex(api);

    if (qtyPerCartonRow >= 0) {
      for (var c2 = start; c2 <= end; c2++) {
        var sizeSum = 0;
        for (var r2 = 0; r2 < api.options.data.length; r2++) {
          if (r2 === qtyPerCartonRow || r2 === noOfCartonsRow || r2 === subTotalQtyRow) continue;
          if (isSummaryRow(api, r2)) continue;
          sizeSum += getNumericCellValue(api, c2, r2);
        }
        setNumericCellValue(api, c2, qtyPerCartonRow, sizeSum);
      }
    }

    if (qtyPerCartonRow >= 0 && noOfCartonsRow >= 0 && subTotalQtyRow >= 0) {
      for (var c = start; c <= end; c++) {
        var qtyPerCarton = getNumericCellValue(api, c, qtyPerCartonRow);
        var noOfCartons = getNumericCellValue(api, c, noOfCartonsRow);
        setNumericCellValue(api, c, subTotalQtyRow, qtyPerCarton * noOfCartons);
      }
    }

    if (qtyPerCartonRow >= 0) {
      setCellValue(api, totalCol, qtyPerCartonRow, '');
    }
    if (noOfCartonsRow >= 0) {
      setNumericCellValue(api, totalCol, noOfCartonsRow, sumRowAcrossSizes(api, noOfCartonsRow));
    }
    if (subTotalQtyRow >= 0) {
      setNumericCellValue(api, totalCol, subTotalQtyRow, sumRowAcrossSizes(api, subTotalQtyRow));
    }
    if (netWtRow >= 0) {
      setNumericCellValue(api, totalCol, netWtRow, sumRowAcrossSizes(api, netWtRow));
    }
    if (grossWtRow >= 0) {
      setNumericCellValue(api, totalCol, grossWtRow, sumRowAcrossSizes(api, grossWtRow));
    }

  }

  function recalcAllRows(instance) {
    var api = getGridApi(instance);
    if (!api || !api.options || !api.options.data) return;
    clearFrozenColumnsAfterFirstRow(api);
    for (var r = 0; r < api.options.data.length; r++) {
      if (!isSummaryRow(api, r)) recalcDetailRowTotal(api, r);
    }
    recalcSummaryRows(api);
  }

  function buildRedOptions() {
    var redCols = [
      { title: 'Style No.', width: 100, type: 'text', readOnly: false },
      { title: 'HS Code', width: 90, type: 'text', readOnly: false },
      { title: 'Unit Packing Code (UPC)', width: 130, type: 'text', readOnly: false },
      { title: 'Description of Goods', width: 150, type: 'text', readOnly: false },
      { title: 'Combo / Colour', width: 110, type: 'text', readOnly: false },
      { title: 'XS', width: 50, type: 'text' },
      { title: 'S', width: 50, type: 'text' },
      { title: 'M', width: 50, type: 'text' },
      { title: 'L', width: 50, type: 'text' },
      { title: 'XL', width: 50, type: 'text' },
      { title: 'XXL', width: 50, type: 'text' },
      { title: '3XL', width: 50, type: 'text' },
      { title: 'Total', width: 70, type: 'text', readOnly: false }
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
      freezeColumns: 4,
      allowInsertRow: true,
      allowDeleteColumn: false,
      allowInsertColumn: false,
      updateTable: function (instance, cell, col, row) {
        var rowIndex = parseInt($(cell).attr('data-y'), 10);
        $(cell).removeClass('readonly');
        if (!isNaN(rowIndex) && col === getTotalColumnIndex(instance) && isDisabledTotalRow(instance, rowIndex)) {
          $(cell).addClass('readonly');
          cell.style.background = '#f8fafc';
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
        if (!isNaN(rowIndex) && rowIndex > 0 && col >= 0 && col <= 3) {
          cell.style.background = '#f8fafc';
        }
        if (col >= 0 && col <= 4) {
          cell.style.textAlign = 'left';
        }
      },
      onchange: function (instance, cell, col, row) {
        if (isAutoUpdating) return;
        var api = getGridApi(instance);
        if (!api) return;
        isAutoUpdating = true;
        try {
          var totalCol = getTotalColumnIndex(api);
          if (col === totalCol && isDisabledTotalRow(api, row)) {
            recalcSummaryRows(api);
            return;
          }

          if (row > 0 && col >= 0 && col <= 3) {
            api.setValueFromCoords(col, row, '');
            return;
          }

          var sizeStart = getSizeStartColumnIndex();
          var sizeEnd = getSizeEndColumnIndex(api);
          var summaryLabel = String(getCellValue(api, 4, row) || '').trim();
          var editableSummaryRows = {
            'No. of Cartons:': true,
            'UOM:': true,
            'Net Wt (Kgs):': true,
            'Gross Wt (Kgs):': true,
            'Carton Nos:': true
          };
          var isEditableSummaryRow = !!editableSummaryRows[summaryLabel];
          var isSizeEdit = col >= sizeStart && col <= sizeEnd;
          if (!isSizeEdit || (isSummaryRow(instance, row) && !isEditableSummaryRow)) return;

          if (!isSummaryRow(instance, row)) {
            recalcDetailRowTotal(api, row);
          }
          recalcSummaryRows(api);
        } finally {
          isAutoUpdating = false;
        }
      },
      oninsertrow: function (instance) {
        if (isAutoUpdating) return;
        isAutoUpdating = true;
        try {
          recalcAllRows(instance);
        } finally {
          isAutoUpdating = false;
        }
      },
      ondeleterow: function (instance) {
        if (isAutoUpdating) return;
        isAutoUpdating = true;
        try {
          recalcAllRows(instance);
        } finally {
          isAutoUpdating = false;
        }
      }
    };
  }

  function createAssortmentSheet(redEl, savedData) {
    var options = buildRedOptions();
    var matrix = deserializeSheetData(savedData);
    if (matrix && matrix.length) {
      options.data = matrix;
    }
    redEl.innerHTML = '';
    assortmentRedSheet = sheetFactory(redEl, options);
    isAutoUpdating = true;
    try {
      recalcAllRows(assortmentRedSheet);
    } finally {
      isAutoUpdating = false;
    }
  }

  function loadAssortmentSheetData(callback) {
    if (typeof enquiry_id === 'undefined' || typeof base_path === 'undefined') {
      callback(null);
      return;
    }
    $.ajax({
      method: 'GET',
      url: base_path + 'WorkInProcess/getAssortmentDesignSheetData',
      data: { enquiry_id: enquiry_id },
      dataType: 'json',
      success: function (res) {
        if (!res || res.status !== 'success' || !res.sheet_data) {
          callback(null);
          return;
        }
        try {
          callback(JSON.parse(res.sheet_data));
        } catch (e) {
          callback(null);
        }
      },
      error: function () {
        callback(null);
      }
    });
  }

  function saveAssortmentSheetData() {
    var api = getGridApi(assortmentRedSheet);
    if (!api || typeof enquiry_id === 'undefined' || typeof base_path === 'undefined') return;
    var payload = serializeSheetData(api);
    $.ajax({
      method: 'POST',
      url: base_path + 'WorkInProcess/saveAssortmentDesignSheetData',
      dataType: 'json',
      data: {
        enquiry_id: enquiry_id,
        sheet_data: JSON.stringify(payload)
      },
      success: function (res) {
        if (res && res.status === 'success') {
          alert('Saved successfully');
        } else {
          alert((res && res.msg) ? res.msg : 'Unable to save');
        }
      },
      error: function () {
        alert('Unable to save');
      }
    });
  }

  function initAssortmentDesignGrid() {
    var redEl = document.getElementById('assortmentRedGrid');
    if (!redEl) return;

    if (typeof sheetFactory === 'undefined') {
      console.error('assortment_design.js: jexcel/jspreadsheet is not available');
      return;
    }

    if (!assortmentRedSheet && !loadStarted) {
      loadStarted = true;
      loadAssortmentSheetData(function (savedData) {
        createAssortmentSheet(redEl, savedData);
      });
      return;
    }

    if (!assortmentRedSheet) {
      createAssortmentSheet(redEl, null);
      return;
    }

    isAutoUpdating = true;
    try {
      recalcAllRows(assortmentRedSheet);
    } finally {
      isAutoUpdating = false;
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
    $(document).on('click', '#assortmentDesignSave', function (e) {
      e.preventDefault();
      saveAssortmentSheetData();
    });

    if ($('#management_assortment_design').hasClass('active')) {
      initAssortmentDesignGrid();
    }
  });
})();
