/**
 * File Upload grid – same pattern as products.js.
 * Document Type dropdown (like Product list), Upload, View. Data from getTestListDocuments or default row.
 */
$(document).ready(function () {
  // Same structure as products.js: no early return, so tab handler and getFileUploadGrid are always defined
  var file_upload_grid_vm = null;
  var rowFiles = {};
  var rowIds = {};
  var docBaseUrl = '';
  var currentUploadRow = -1;
  var fileUploadStateInterval = null;

  // Document type list (like products_list in products.js)
  var DOCUMENT_TYPES = ['Spec Sheet', 'Test Report', 'Certificate', 'Other'];

  function getFileUploadGrid() {
    if (typeof enquiry_id === 'undefined' || typeof base_path === 'undefined') return;
    $.ajax({
      type: 'GET',
      url: base_path + 'WorkInProcess/getTestListDocuments',
      data: { enquiry_id: enquiry_id },
      dataType: 'json',
      success: function (data) {
        rowFiles = {};
        rowIds = {};
        if (data && data.base_url) docBaseUrl = data.base_url;
        if (data && data.rows && data.rows.length) {
          data.rows.forEach(function (r, idx) {
            rowIds[idx] = r.id;
            rowFiles[idx] = Array.isArray(r.uploaded_files) ? r.uploaded_files : [];
          });
        }
        appendFileUploadGrid(data);
      },
      error: function () {
        rowFiles = {};
        rowIds = {};
        docBaseUrl = '';
        appendFileUploadGrid(null);
      }
    });
  }

  function appendFileUploadGrid(data) {
    var $container = $('#fileUploadGridSheet');
    if (!$container.length) return;
    $container.html('');

    var initialData = [];
    var rows = (data && data.rows && data.rows.length) ? data.rows : [];
    if (rows.length === 0) {
      initialData.push(['', '', 'Upload', 'View']);
    } else {
      rows.forEach(function (r, idx) {
        var files = Array.isArray(r.uploaded_files) ? r.uploaded_files : [];
        var viewLabel = files.length ? 'View (' + files.length + ')' : 'View';
        initialData.push([r.id, r.document_type || '', 'Upload', viewLabel]);
      });
    }

    // Same pattern as products.js: options object then jexcel in Vue mounted
    var file_upload_grid = {
      data: initialData,
      columns: [
        { title: 'id', width: '1px', type: 'hidden' },
        { title: 'Document Type', width: '35%', type: 'dropdown', source: DOCUMENT_TYPES },
        { title: 'Upload', width: '30%', readOnly: true },
        { title: 'View', width: '30%', readOnly: true }
      ],
      minDimensions: [4, 1],
      allowInsertRow: true,
      allowDeleteColumn: false,
      allowInsertColumn: false,
      onchange: function (instance, cell, col, row, val, label, cellName) {
        if (col === 1 || col === 0) {
          setTimeout(function () { updateUploadViewRowState(row); }, 50);
        }
      }
    };

    file_upload_grid_vm = new Vue({
      el: '#fileUploadGridSheet',
      mounted: function () {
        var spreadsheet = jexcel(this.$el, file_upload_grid);
        Object.assign(this, spreadsheet);
        bindFileUploadGridEvents();
        setTimeout(function () { updateAllUploadViewStates(); }, 150);
      },
      methods: {
        saveData: function () {
          saveFileUploadGrid();
        }
      }
    });

    // Attach save button same way as products.js (after grid is created)
    $('#fileUploadGridSubmit')
      .off('click.fileupload')
      .on('click.fileupload', function () {
        saveFileUploadGrid();
      });
  }

  function saveFileUploadGrid() {
    if (!file_upload_grid_vm || !file_upload_grid_vm.getData) return;
    var data = file_upload_grid_vm.getData();
    $.ajax({
      type: 'POST',
      url: base_path + 'WorkInProcess/saveTestListDocuments',
      data: { enquiry_id: enquiry_id, data: JSON.stringify(data) },
      dataType: 'json',
      success: function (res) {
        if (res && res.status === 'success') {
          if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Saved!', text: res.msg || 'Saved successfully.', icon: 'success', customClass: { confirmButton: 'btn btn-info' } });
          } else {
            alert('Saved successfully.');
          }
          getFileUploadGrid();
        } else {
          if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Error', text: (res && res.msg) || 'Save failed.', icon: 'error', customClass: { confirmButton: 'btn btn-info' } });
          }
        }
      },
      error: function () {
        if (typeof Swal !== 'undefined') {
          Swal.fire({ title: 'Error', text: 'Save failed.', icon: 'error', customClass: { confirmButton: 'btn btn-info' } });
        }
      }
    });
  }

  function updateUploadViewRowState(row) {
    var $sheet = $('#fileUploadGridSheet');
    var $tr = $sheet.find('table tbody tr').eq(row);
    if (!$tr.length) return;

    var $uploadTd = $sheet.find('td[data-x="2"][data-y="' + row + '"]');
    var $viewTd = $sheet.find('td[data-x="3"][data-y="' + row + '"]');
    if (!$uploadTd || !$uploadTd.length) {
      $tr.find('td').each(function () {
        var txt = $(this).text().trim();
        if (txt.indexOf('Upload') !== -1 && txt.indexOf('View') === -1) $uploadTd = $(this);
        if (txt.indexOf('View') !== -1) $viewTd = $(this);
      });
    }

    var docType = '';
    if (file_upload_grid_vm && file_upload_grid_vm.getData) {
      var gridData = file_upload_grid_vm.getData();
      if (gridData[row] && gridData[row][1] != null) docType = String(gridData[row][1]).trim();
    }
    if (!docType && file_upload_grid_vm && file_upload_grid_vm.getValueFromCoords) {
      var v = file_upload_grid_vm.getValueFromCoords(1, row);
      if (v != null) docType = String(v).trim();
    }
    var hasFiles = rowFiles[row] && rowFiles[row].length > 0;

    if ($uploadTd && $uploadTd.length) {
      if (docType) $uploadTd.removeClass('file-upload-cell-disabled');
      else $uploadTd.addClass('file-upload-cell-disabled');
    }
    if ($viewTd && $viewTd.length) {
      if (hasFiles) $viewTd.removeClass('file-upload-cell-disabled');
      else $viewTd.addClass('file-upload-cell-disabled');
    }
  }

  function updateAllUploadViewStates() {
    var $sheet = $('#fileUploadGridSheet');
    var $rows = $sheet.find('table tbody tr');
    $rows.each(function (rowIndex) {
      updateUploadViewRowState(rowIndex);
    });
  }

  function bindFileUploadGridEvents() {
    var $sheet = $('#fileUploadGridSheet');
    var $input = $('#fileUploadGridInput');
    if (!$sheet.length) return;

    $sheet.off('click.fileupload').on('click.fileupload', 'td', function (e) {
      var $td = $(this);
      var col = $td.attr('data-x');
      var row = $td.attr('data-y');
      if (col == null || row == null) {
        col = $td.index();
        var $tr = $td.closest('tr');
        row = $tr.parent().is('tbody') ? $tr.index() : $tr.index() - 1;
      } else {
        col = parseInt(col, 10);
        row = parseInt(row, 10);
      }
      if (row < 0) return;

      var cellText = $td.text().trim();
      var isUploadCell = cellText.indexOf('Upload') !== -1 && cellText.indexOf('View') === -1;
      var isViewCell = cellText.indexOf('View') !== -1;

      if (!isUploadCell && !isViewCell) return;

      updateUploadViewRowState(row);
      if ($td.hasClass('file-upload-cell-disabled')) return;

      if (isUploadCell) {
        var docType = file_upload_grid_vm && file_upload_grid_vm.getValueFromCoords(1, row);
        if (!docType || docType === '') return;
        currentUploadRow = row;
        $input.attr('accept', 'image/*,.pdf,.doc,.docx,.xls,.xlsx');
        $input.trigger('click');
      } else if (isViewCell) {
        var files = rowFiles[row];
        if (!files || files.length === 0) return;
        showViewModal(row);
      }
    });

    $input.off('change.fileupload').on('change.fileupload', function () {
      var files = this.files;
      if (!files || files.length === 0 || currentUploadRow < 0) return;
      var docType = file_upload_grid_vm.getValueFromCoords(1, currentUploadRow);
      var rowId = file_upload_grid_vm.getValueFromCoords(0, currentUploadRow);
      var formData = new FormData();
      formData.append('enquiry_id', enquiry_id);
      formData.append('row_id', rowId || '');
      formData.append('document_type', docType || '');
      for (var i = 0; i < files.length; i++) {
        formData.append('myFile[]', files[i]);
      }
      $.ajax({
        type: 'POST',
        url: base_path + 'WorkInProcess/uploadTestListDocument',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        dataType: 'json',
        success: function (res) {
          if (res && res.status === 'success' && res.files && res.files.length) {
            var id = res.id;
            if (!rowFiles[currentUploadRow]) rowFiles[currentUploadRow] = [];
            rowFiles[currentUploadRow] = rowFiles[currentUploadRow].concat(res.files);
            rowIds[currentUploadRow] = id;
            file_upload_grid_vm.setValueFromCoords(0, currentUploadRow, id);
            var label = 'View (' + rowFiles[currentUploadRow].length + ')';
            file_upload_grid_vm.setValueFromCoords(3, currentUploadRow, label);
            updateUploadViewRowState(currentUploadRow);
            if (typeof Swal !== 'undefined') {
              Swal.fire({ title: 'Uploaded', text: res.files.length + ' file(s) uploaded.', icon: 'success', customClass: { confirmButton: 'btn btn-info' } });
            }
          }
        },
        error: function () {
          if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Upload failed', icon: 'error', customClass: { confirmButton: 'btn btn-info' } });
          }
        }
      });
      this.value = '';
      currentUploadRow = -1;
    });
  }

  function showViewModal(row) {
    var files = rowFiles[row];
    var id = rowIds[row];
    if (!files || !files.length || id == null) return;
    var docType = file_upload_grid_vm && file_upload_grid_vm.getValueFromCoords(1, row);
    var title = docType ? 'Uploaded Documents – ' + docType : 'Uploaded Documents';
    $('#fileUploadViewModal').find('.modal-title').text(title);
    var base = docBaseUrl + 'row_' + id + '/';
    var $body = $('#fileUploadViewModalImages');
    if ($body.length) $body.empty();
    var imageExts = /\.(jpe?g|png|gif|bmp|webp)$/i;
    files.forEach(function (name) {
      var url = base + encodeURIComponent(name);
      var $col = $('<div class="col-xs-12 col-sm-6 col-md-4 mb-3"></div>');
      if (imageExts.test(name)) {
        $col.append($('<img>').attr('src', url).css({ maxWidth: '100%', height: 'auto', border: '1px solid #ddd' }).addClass('img-responsive'));
      } else {
        $col.append($('<a>').attr('href', url).attr('target', '_blank').text(name).addClass('btn btn-default'));
      }
      if ($body.length) $body.append($col);
    });
    $('#fileUploadViewModal').modal('show');
  }

  // Same as products.js: load when tab is shown (grid does not render when container is hidden)
  $(document).on('shown.bs.tab', 'a[href="#management_file_upload"]', function () {
    if (typeof enquiry_id === 'undefined' || typeof base_path === 'undefined') return;
    if (!$('#fileUploadGridSheet').length) return;
    if (!file_upload_grid_vm || !file_upload_grid_vm.getData) {
      getFileUploadGrid();
    } else {
      updateAllUploadViewStates();
    }
    if (fileUploadStateInterval) clearInterval(fileUploadStateInterval);
    fileUploadStateInterval = setInterval(function () {
      if (!$('#management_file_upload').length || !$('#management_file_upload').hasClass('active')) {
        if (fileUploadStateInterval) clearInterval(fileUploadStateInterval);
        fileUploadStateInterval = null;
        return;
      }
      if (file_upload_grid_vm && file_upload_grid_vm.getData) updateAllUploadViewStates();
    }, 350);
  });
  $(document).on('shown.bs.tab', 'a[href="#management_test_list"]', function () {
    if (fileUploadStateInterval) { clearInterval(fileUploadStateInterval); fileUploadStateInterval = null; }
  });
  // When TestList tab is shown, if FILE UPLOAD sub-tab is already active, load grid
  $(document).on('shown.bs.tab', 'a[href="#testlist"]', function () {
    if (typeof enquiry_id === 'undefined' || typeof base_path === 'undefined') return;
    if (!$('#fileUploadGridSheet').length) return;
    if ($('#management_file_upload').hasClass('active') && (!file_upload_grid_vm || !file_upload_grid_vm.getData)) {
      setTimeout(getFileUploadGrid, 150);
    }
  });
});
