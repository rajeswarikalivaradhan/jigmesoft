<ul class="nav nav-tabs cad d-flex" role="tablist">
    <li class="active" role="presentation"><a data-toggle="tab" href="#management_test_list" role="tab">TEST LIST</a></li>
    <li role="presentation"><a data-toggle="tab" href="#management_file_upload" role="tab">FILE UPLOAD</a></li>
</ul>

<style>
    /* Ensure only active sub-tab pane is visible so TEST LIST grid shows correctly */
    #testlist-sub-tabs .tab-pane { display: none; }
    #testlist-sub-tabs .tab-pane.active { display: block; }
</style>
<div class="tab-content p-t20" id="testlist-sub-tabs">
    <div id="management_test_list" class="tab-pane fade in active">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14">TEST LIST</div>
        <div id="productsGridSheet"></div>
        <style>
            /* Bold "Total:" label and its amount in the grid footer (6th and 7th cells in footer row) */
            #productsGridSheet tfoot td:nth-child(6),
            #productsGridSheet tfoot td:nth-child(7),
            #productsGridSheet .jexcel tfoot tr td:nth-child(6),
            #productsGridSheet .jexcel tfoot tr td:nth-child(7) { font-weight: bold; }
        </style>
        <div class="card border-0">
            <div class="card-footer clearfix bgc-white border-0 p-3">
                <button class="btn btn-info btn-sm mar-l-5rem pull-right" id="productsGridSubmit">SAVE</button>
            </div>
        </div>
    </div>
    <div id="management_file_upload" class="tab-pane fade">
        <div class="mb-3 pl-0 ml-2 text-royal-blue f-14">FILE UPLOAD</div>
        <p class="text-muted small mb-2">Select a Document Type, then use Upload to add files. Use View to see uploaded images.</p>
        <style>
            #fileUploadGridSheet { min-height: 220px; }
            #fileUploadGridSheet table tbody td:nth-child(3),
            #fileUploadGridSheet table tbody td:nth-child(4) { cursor: pointer; font-weight: 500; text-align: center; vertical-align: middle; }
            #fileUploadGridSheet .file-upload-cell-disabled { opacity: 0.5; pointer-events: none; cursor: not-allowed !important; color: #999; }
            #fileUploadGridSheet .file-action-btn { min-width: 95px; display: inline-flex; justify-content: center; align-items: center; gap: 6px; }
            #fileUploadViewModalImages .file-view-item {
                margin-bottom: 12px;
                padding-left: 8px;
                padding-right: 8px;
            }
            #fileUploadViewModalImages .file-item-card {
                position: relative;
                border: 1px solid #d9dde5;
                border-radius: 10px;
                padding: 10px;
                min-height: 188px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                gap: 6px;
                background: #ffffff;
            }
            #fileUploadViewModalImages .file-preview-image {
                width: 46px;
                height: 46px;
                object-fit: contain;
                border: none;
                border-radius: 4px;
                background: transparent;
            }
            #fileUploadViewModalImages .file-preview-icon {
                width: 46px;
                height: 46px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #f97316;
                font-size: 38px;
                border: none;
                border-radius: 0;
                background: transparent;
            }
            #fileUploadViewModalImages .file-name {
                width: 100%;
                text-align: center;
                font-size: 14px;
                color: #111827;
                font-weight: 600;
                line-height: 1.3;
                margin-top: 4px;
                word-break: break-word;
                min-height: 36px;
                max-height: 36px;
                overflow: hidden;
            }
            #fileUploadViewModalImages .file-meta {
                width: 100%;
                text-align: center;
                font-size: 12px;
                color: #7b8494;
                line-height: 1.2;
                text-transform: uppercase;
            }
            #fileUploadViewModalImages .file-item-actions {
                width: 100%;
                display: flex;
                justify-content: center;
                margin-top: auto;
                padding-top: 4px;
            }
            #fileUploadViewModalImages .file-open-link {
                width: 100%;
                min-width: 95px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                border-radius: 9px;
                border: 1px solid #d1d5db;
                background: #f8fafc;
                color: #111827;
                font-weight: 600;
            }
            #fileUploadViewModalImages .file-open-link:hover {
                background: #eef2f7;
                color: #111827;
            }
            #fileUploadViewModalImages .file-delete-btn {
                position: absolute;
                top: 6px;
                right: 6px;
                width: 24px;
                height: 24px;
                min-width: 24px;
                padding: 0;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            #fileUploadViewModalImages .file-delete-btn i { font-size: 12px; }
            #fileUploadViewModalImages .file-delete-btn span { display: none; }
        </style>
        <div id="fileUploadGridSheet"></div>
        <div class="card border-0">
            <div class="card-footer clearfix bgc-white border-0 p-3">
                <button class="btn btn-info btn-sm mar-l-5rem pull-right" id="fileUploadGridSubmit">SAVE</button>
            </div>
        </div>
        <input type="file" id="fileUploadGridInput" multiple accept="image/*,.pdf" style="display:none;">
    </div>
</div>

<!-- Modal: View uploaded images for a row -->
<div class="modal fade" id="fileUploadViewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Uploaded Documents</h4>
            </div>
            <div class="modal-body" id="fileUploadViewModalBody">
                <div class="row" id="fileUploadViewModalImages"></div>
            </div>
        </div>
    </div>
</div>

