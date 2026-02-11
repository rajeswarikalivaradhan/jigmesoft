-- Test List Document Uploads: one DB row per grid row, multiple files stored as JSON per row.
-- Run this in your database to create the table.

CREATE TABLE IF NOT EXISTS tbl_testlist_document_uploads (
  id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  enquiry_id INT(11) UNSIGNED NOT NULL,
  document_type VARCHAR(100) NOT NULL DEFAULT '',
  uploaded_files TEXT COMMENT 'JSON array of filenames e.g. ["file1.png","file2.pdf"]',
  created_at DATETIME DEFAULT NULL,
  updated_at DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  KEY idx_enquiry_id (enquiry_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='WIP Test list file upload - one row per grid row, files as JSON';

-- Example queries:

-- Insert one row (e.g. new grid row)
-- INSERT INTO tbl_testlist_document_uploads (enquiry_id, document_type, uploaded_files, created_at, updated_at)
-- VALUES (146, 'Spec Sheet', '[]', NOW(), NOW());

-- Update document_type and append files for a row (application will merge JSON)
-- UPDATE tbl_testlist_document_uploads
-- SET document_type = 'Test Report', uploaded_files = '["doc1.png","doc2.pdf"]', updated_at = NOW()
-- WHERE id = 1 AND enquiry_id = 146;

-- Get all rows for an enquiry (order by id = grid row order)
-- SELECT id, enquiry_id, document_type, uploaded_files, created_at, updated_at
-- FROM tbl_testlist_document_uploads
-- WHERE enquiry_id = 146
-- ORDER BY id;

-- Get single row by id and enquiry_id
-- SELECT id, enquiry_id, document_type, uploaded_files FROM tbl_testlist_document_uploads
-- WHERE id = 1 AND enquiry_id = 146;
