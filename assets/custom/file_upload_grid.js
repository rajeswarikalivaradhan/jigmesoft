/**
 * File Upload grid - same pattern as products.js.
 * Document Type dropdown, row-wise Upload and View actions.
 */
$(document).ready(function () {
  var file_upload_grid_vm = null;
  var rowFiles = {};
  var rowIds = {};
  var rowFolderNames = {};
  var docBaseUrl = '';
  var currentUploadRow = -1;
  var fileUploadStateInterval = null;
  var pendingDeleteRowIds = [];

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
        rowFolderNames = {};
        if (data && data.base_url) docBaseUrl = data.base_url;
        if (data && data.rows && data.rows.length) {
          data.rows.forEach(function (r, idx) {
            rowIds[idx] = r.id;
            rowFiles[idx] = Array.isArray(r.uploaded_files) ? r.uploaded_files : [];
            rowFolderNames[idx] = r.folder_name || buildFolderName(r.document_type || '', r.id);
          });
        }
        appendFileUploadGrid(data);
      },
      error: function () {
        rowFiles = {};
        rowIds = {};
        rowFolderNames = {};
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
      rows.forEach(function (r) {
        var files = Array.isArray(r.uploaded_files) ? r.uploaded_files : [];
        var viewLabel = files.length ? 'View (' + files.length + ')' : 'View';
        initialData.push([r.id, r.document_type || '', 'Upload', viewLabel]);
      });
    }

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
      onbeforedeleterow: function (instance, rowNumber, numOfRows) {
        pendingDeleteRowIds = [];
        var start = parseInt(rowNumber, 10);
        var count = parseInt(numOfRows, 10);
        if (isNaN(start) || start < 0) return true;
        if (isNaN(count) || count < 1) count = 1;
        for (var i = start; i < start + count; i++) {
          var rid = file_upload_grid_vm && file_upload_grid_vm.getValueFromCoords ? file_upload_grid_vm.getValueFromCoords(0, i) : '';
          rid = rid ? parseInt(rid, 10) : 0;
          if (!isNaN(rid) && rid > 0) pendingDeleteRowIds.push(rid);
        }
        return true;
      },
      ondeleterow: function () {
        if (pendingDeleteRowIds && pendingDeleteRowIds.length) {
          deleteRowsImmediately(pendingDeleteRowIds.slice());
        }
        pendingDeleteRowIds = [];
      },
      onchange: function (instance, cell, col, row) {
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

  function getDocTypeForRow(row) {
    var docType = '';
    if (file_upload_grid_vm && file_upload_grid_vm.getData) {
      var gridData = file_upload_grid_vm.getData();
      if (gridData[row] && gridData[row][1] != null) docType = String(gridData[row][1]).trim();
    }
    if (!docType && file_upload_grid_vm && file_upload_grid_vm.getValueFromCoords) {
      var v = file_upload_grid_vm.getValueFromCoords(1, row);
      if (v != null) docType = String(v).trim();
    }
    return docType;
  }

  function renderRowButtons($uploadTd, $viewTd, row, hasDocType, hasFiles) {
    var viewCount = hasFiles ? rowFiles[row].length : 0;

    if ($uploadTd && $uploadTd.length) {
      $uploadTd
        .toggleClass('file-upload-cell-disabled', !hasDocType)
        .html(
          '<button type="button" class="btn btn-xs btn-info file-action-btn file-upload-btn" data-row="' + row + '"' + (hasDocType ? '' : ' disabled') + '><i class="fas fa-upload" aria-hidden="true"></i> Upload</button>'
        );
    }

    if ($viewTd && $viewTd.length) {
      $viewTd
        .toggleClass('file-upload-cell-disabled', !hasFiles)
        .html(
          '<button type="button" class="btn btn-xs btn-default file-action-btn file-view-btn" data-row="' + row + '"' + (hasFiles ? '' : ' disabled') + '><i class="fas fa-eye" aria-hidden="true"></i> View' + (viewCount ? ' (' + viewCount + ')' : '') + '</button>'
        );
    }
  }

  function sanitizeDocType(docType) {
    var normalized = String(docType || '').toLowerCase().trim();
    normalized = normalized.replace(/[^a-z0-9]+/g, '_');
    normalized = normalized.replace(/^_+|_+$/g, '');
    return normalized || 'document';
  }

  function buildFolderName(docType, rowId) {
    if (!rowId) return '';
    return sanitizeDocType(docType) + '_' + rowId;
  }

  function updateUploadViewRowState(row) {
    var $sheet = $('#fileUploadGridSheet');
    var $uploadTd = $sheet.find('td[data-x="2"][data-y="' + row + '"]');
    var $viewTd = $sheet.find('td[data-x="3"][data-y="' + row + '"]');

    if ((!$uploadTd || !$uploadTd.length || !$viewTd || !$viewTd.length) && $sheet.find('table tbody tr').eq(row).length) {
      var $cells = $sheet.find('table tbody tr').eq(row).find('td');
      $uploadTd = $uploadTd.length ? $uploadTd : $cells.eq(2);
      $viewTd = $viewTd.length ? $viewTd : $cells.eq(3);
    }

    var docType = getDocTypeForRow(row);
    var hasFiles = !!(rowFiles[row] && rowFiles[row].length > 0);

    renderRowButtons($uploadTd, $viewTd, row, !!docType, hasFiles);
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

    $sheet.off('click.fileuploadUpload').on('click.fileuploadUpload', '.file-upload-btn', function () {
      var row = parseInt($(this).attr('data-row'), 10);
      if (isNaN(row) || row < 0) return;

      var docType = getDocTypeForRow(row);
      if (!docType) {
        updateUploadViewRowState(row);
        return;
      }

      currentUploadRow = row;
      $input.attr('accept', 'image/*,.pdf,.doc,.docx,.xls,.xlsx');
      $input.trigger('click');
    });

    $sheet.off('click.fileuploadView').on('click.fileuploadView', '.file-view-btn', function () {
      var row = parseInt($(this).attr('data-row'), 10);
      if (isNaN(row) || row < 0) return;

      var files = rowFiles[row];
      if (!files || files.length === 0) {
        updateUploadViewRowState(row);
        return;
      }
      showViewModal(row);
    });

    $input.off('change.fileupload').on('change.fileupload', function () {
      var files = this.files;
      if (!files || files.length === 0 || currentUploadRow < 0) return;
      var targetRow = currentUploadRow;
      var existingFiles = (rowFiles[targetRow] || []).slice();
      var selectedFileNames = [];

      var docType = file_upload_grid_vm.getValueFromCoords(1, targetRow);
      if (!docType || String(docType).trim() === '') {
        var invalidRow = currentUploadRow;
        this.value = '';
        currentUploadRow = -1;
        updateUploadViewRowState(invalidRow);
        return;
      }

      var rowId = file_upload_grid_vm.getValueFromCoords(0, targetRow);
      var formData = new FormData();
      formData.append('enquiry_id', enquiry_id);
      formData.append('row_id', rowId || '');
      formData.append('document_type', docType || '');
      for (var i = 0; i < files.length; i++) {
        formData.append('myFile[]', files[i]);
        selectedFileNames.push(files[i].name);
      }

      // Enable View immediately after file selection for this row.
      if (selectedFileNames.length) {
        rowFiles[targetRow] = existingFiles.concat(selectedFileNames);
        file_upload_grid_vm.setValueFromCoords(3, targetRow, 'View (' + rowFiles[targetRow].length + ')');
        updateUploadViewRowState(targetRow);
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
          if (res && res.status === 'success') {
            var id = res.id;
            if (res.files && res.files.length) {
              rowFiles[targetRow] = existingFiles.concat(res.files);
            } else {
              rowFiles[targetRow] = existingFiles;
            }
            rowIds[targetRow] = id;
            rowFolderNames[targetRow] = (res.folder_name || buildFolderName(docType, id));
            file_upload_grid_vm.setValueFromCoords(0, targetRow, id);
            var label = 'View' + (rowFiles[targetRow].length ? ' (' + rowFiles[targetRow].length + ')' : '');
            file_upload_grid_vm.setValueFromCoords(3, targetRow, label);
            updateUploadViewRowState(targetRow);

            if (res.files && res.files.length && typeof Swal !== 'undefined') {
              Swal.fire({ title: 'Uploaded', text: res.files.length + ' file(s) uploaded.', icon: 'success', customClass: { confirmButton: 'btn btn-info' } });
            }
          } else {
            rowFiles[targetRow] = existingFiles;
            file_upload_grid_vm.setValueFromCoords(3, targetRow, 'View' + (existingFiles.length ? ' (' + existingFiles.length + ')' : ''));
            updateUploadViewRowState(targetRow);
          }
        },
        error: function () {
          rowFiles[targetRow] = existingFiles;
          file_upload_grid_vm.setValueFromCoords(3, targetRow, 'View' + (existingFiles.length ? ' (' + existingFiles.length + ')' : ''));
          updateUploadViewRowState(targetRow);
          if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Upload failed', icon: 'error', customClass: { confirmButton: 'btn btn-info' } });
          }
        }
      });

      this.value = '';
      currentUploadRow = -1;
    });

    $(document).off('click.fileuploadDelete').on('click.fileuploadDelete', '#fileUploadViewModal .file-delete-btn', function () {
      var $btn = $(this);
      var row = parseInt($btn.attr('data-row'), 10);
      var fileName = decodeURIComponent($btn.attr('data-file') || '');
      if (isNaN(row) || row < 0 || !fileName) return;

      var rowId = rowIds[row] || (file_upload_grid_vm && file_upload_grid_vm.getValueFromCoords ? file_upload_grid_vm.getValueFromCoords(0, row) : '');
      if (!rowId) return;

      var doDelete = function () {
        $.ajax({
          type: 'POST',
          url: base_path + 'WorkInProcess/deleteTestListDocumentFile',
          data: { enquiry_id: enquiry_id, row_id: rowId, file_name: fileName },
          dataType: 'json',
          success: function (res) {
            if (!res || res.status !== 'success') {
              if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Delete failed', text: (res && res.msg) || 'Unable to delete file.', icon: 'error', customClass: { confirmButton: 'btn btn-info' } });
              }
              return;
            }

              var files = rowFiles[row] || [];
              rowFiles[row] = files.filter(function (name) { return name !== fileName; });
              if (res.folder_deleted) {
                rowFolderNames[row] = '';
              }
              var viewLabel = 'View' + (rowFiles[row].length ? ' (' + rowFiles[row].length + ')' : '');
              file_upload_grid_vm.setValueFromCoords(3, row, viewLabel);
              updateUploadViewRowState(row);

            showViewModal(row);
            if (!rowFiles[row].length) {
              $('#fileUploadViewModal').modal('hide');
            }
          },
          error: function () {
            if (typeof Swal !== 'undefined') {
              Swal.fire({ title: 'Delete failed', text: 'Unable to delete file.', icon: 'error', customClass: { confirmButton: 'btn btn-info' } });
            }
          }
        });
      };

      if (typeof Swal !== 'undefined') {
        Swal.fire({
          title: 'Delete file?',
          text: 'Do you want to delete this file?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          cancelButtonText: 'Cancel',
          customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-default' }
        }).then(function (result) {
          if (result.isConfirmed) doDelete();
        });
      } else if (window.confirm('Do you want to delete this file?')) {
        doDelete();
      }
    });
  }

  function deleteRowsImmediately(rowIdsToDelete) {
    if (!rowIdsToDelete || !rowIdsToDelete.length) return;
    var total = rowIdsToDelete.length;
    var done = 0;
    var hasError = false;

    rowIdsToDelete.forEach(function (rid) {
      $.ajax({
        type: 'POST',
        url: base_path + 'WorkInProcess/deleteTestListDocumentRow',
        data: { enquiry_id: enquiry_id, row_id: rid },
        dataType: 'json',
        success: function (res) {
          if (!res || res.status !== 'success') hasError = true;
        },
        error: function () {
          hasError = true;
        },
        complete: function () {
          done++;
          if (done === total) {
            if (hasError && typeof Swal !== 'undefined') {
              Swal.fire({ title: 'Delete warning', text: 'Some row attachments could not be deleted fully.', icon: 'warning', customClass: { confirmButton: 'btn btn-info' } });
            }
            getFileUploadGrid();
          }
        }
      });
    });
  }

  function showViewModal(row) {
    var files = rowFiles[row];
    var id = rowIds[row];
    if (!files || !files.length || id == null) return;

    var docType = file_upload_grid_vm && file_upload_grid_vm.getValueFromCoords(1, row);
    var title = docType ? 'Uploaded Documents - ' + docType : 'Uploaded Documents';
    $('#fileUploadViewModal').find('.modal-title').text(title);

    var folderName = rowFolderNames[row] || buildFolderName(docType, id);
    var base = docBaseUrl + folderName + '/';
    var $body = $('#fileUploadViewModalImages');
    if ($body.length) $body.empty();

    var imageExts = /\.(jpe?g|png|gif|bmp|webp)$/i;
    files.forEach(function (name) {
      var url = base + encodeURIComponent(name);
      var $col = $('<div class="col-xs-12 col-sm-6 col-md-4 file-view-item"></div>');
      var $card = $('<div class="file-item-card"></div>');
      var encodedName = encodeURIComponent(name);
      var extMatch = /\.([^.]+)$/.exec(name);
      var extLabel = extMatch && extMatch[1] ? ('.' + extMatch[1].toUpperCase()) : 'FILE';
      var isImage = imageExts.test(name);

      var $deleteBtn = $('<button type="button" class="btn btn-xs btn-danger file-delete-btn"><i class="fas fa-trash" aria-hidden="true"></i><span>Delete</span></button>')
        .attr('data-row', row)
        .attr('data-file', encodedName)
        .addClass('file-action-btn');
      $card.append($deleteBtn);

      if (isImage) {
        $card.append($('<img>').attr('src', url).addClass('img-responsive file-preview-image').attr('alt', name));
      } else {
        $card.append($('<div class="file-preview-icon"><i class="far fa-file-alt" aria-hidden="true"></i></div>'));
      }

      $card.append($('<div class="file-name text-truncate"></div>').text(name));
      $card.append($('<div class="file-meta"></div>').text(extLabel));
      $card.append(
        $('<div class="file-item-actions"></div>').append(
          $('<a></a>')
            .attr('href', url)
            .attr('target', '_blank')
            .addClass('btn btn-default btn-sm file-open-link')
            .html('<i class="fas fa-eye" aria-hidden="true"></i> View')
        )
      );
      $col.append($card);
      if ($body.length) $body.append($col);
    });

    $('#fileUploadViewModal').modal('show');
  }

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
    if (fileUploadStateInterval) {
      clearInterval(fileUploadStateInterval);
      fileUploadStateInterval = null;
    }
  });

  $(document).on('shown.bs.tab', 'a[href="#testlist"]', function () {
    if (typeof enquiry_id === 'undefined' || typeof base_path === 'undefined') return;
    if (!$('#fileUploadGridSheet').length) return;
    if ($('#management_file_upload').hasClass('active') && (!file_upload_grid_vm || !file_upload_grid_vm.getData)) {
      setTimeout(getFileUploadGrid, 150);
    }
  });
});


