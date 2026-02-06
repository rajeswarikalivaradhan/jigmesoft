<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ProductsModel extends CI_Model
{
    protected $table = 'products';

    public function __construct()
    {
        parent::__construct();
        // If you want to enforce login like other models, uncomment:
        // fnIfCheckUserLoggedIn();
    }

    /**
     * Get all products.
     *
     * @param bool $only_active When TRUE, returns only records with status = 1.
     * @return array
     */
    public function get_all($only_active = false)
    {
        if ($only_active) {
            $this->db->where('status', 1);
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get single product by primary key.
     *
     * @param int $product_id
     * @return array|null
     */
    public function get_by_id($product_id)
    {
        return $this->db
            ->get_where($this->table, ['product_id' => (int) $product_id])
            ->row_array();
    }

    /**
     * Insert new product record.
     *
     * @param array $data
     * @return int Inserted ID
     */
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update existing product record.
     *
     * @param int   $product_id
     * @param array $data
     * @return bool
     */
    public function update($product_id, $data)
    {
        $this->db->where('product_id', (int) $product_id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hard delete product record.
     *
     * @param int $product_id
     * @return bool
     */
    public function delete($product_id)
    {
        $this->db->where('product_id', (int) $product_id);
        return $this->db->delete($this->table);
    }

    /**
     * Soft delete: set status = 0 for a product.
     *
     * @param int $product_id
     * @return bool
     */
    public function soft_delete($product_id)
    {
        $this->db->where('product_id', (int) $product_id);
        return $this->db->update($this->table, ['status' => 0]);
    }

    /**
     * Get products in grid (jsspreadsheet) format, filtered by enquiry.
     *
     * NOTE: Requires an 'enquiry_id' column on the products table.
     *
     * @param int|null $enquiry_id
     * @return array
     */
    public function getProductsGrid($enquiry_id = null)
    {
        if ($enquiry_id !== null && $enquiry_id !== '') {
            $this->db->where('enquiry_id', (int) $enquiry_id);
        }

        $data   = $this->db->order_by('product_id', 'ASC')->get($this->table)->result_array();
        $result = [];

        foreach ($data as $key => $value) {
            $statusText = ((int) $value['status'] === 1) ? 'Active' : 'Inactive';

            // [mode, id, name, category, price, quantity, status]
            $result[$key] = [
                'edit',
                (int) $value['product_id'],
                $value['product_name'],
                $value['category'],
                $value['price'],
                $value['quantity'],
                $statusText
            ];
        }

        // Products list for dropdown (active products only)
        $products_list = $this->get_all(true);
        $output['products_list'] = array_map(function ($p) {
            return [
                'id'       => (int) $p['product_id'],
                'name'     => $p['product_name'],
                'category' => $p['category'],
                'price'    => $p['price'],
                'quantity' => (int) $p['quantity'],
            ];
        }, $products_list);

        $output['column'] = [
            ['title' => "mode",        'width' => '10%', 'align' => 'center', 'type' => 'hidden'],
            ['title' => "id",          'width' => '10%', 'align' => 'center', 'type' => 'hidden'],
            ['title' => "Product",     'width' => '30%', 'align' => 'left'],
            ['title' => "Category",    'width' => '20%', 'align' => 'left'],
            ['title' => "Price",       'width' => '15%', 'align' => 'right'],
            ['title' => "Quantity",    'width' => '15%', 'align' => 'right'],
            ['title' => "Status",      'width' => '10%', 'align' => 'center', 'type' => 'dropdown', 'source' => ['Active', 'Inactive']],
        ];

        $output['data'] = $result;
        return $output;
    }

    /**
     * Update products from grid (jsspreadsheet) data for a given enquiry.
     *
     * NOTE: Requires an 'enquiry_id' column on the products table.
     *
     * @param array    $req_data
     * @param int|null $enquiry_id
     * @return array
     */
    public function updateProductsFromGrid($req_data, $enquiry_id = null)
    {
        $enquiry_id = (int) $enquiry_id;
        $incoming_ids = [];

        // Collect existing IDs from incoming data
        foreach ($req_data as $key => $value) {
            if ($value[0] === "edit" && !empty($value[1])) {
                $incoming_ids[] = (int) $value[1];
            }
        }

        // Delete rows for this enquiry that are not present in incoming data
        if (!empty($incoming_ids)) {
            $this->db->where('enquiry_id', $enquiry_id);
            $this->db->where_not_in('product_id', $incoming_ids);
            $this->db->delete($this->table);
        } else {
            // No existing IDs in request — delete all rows for this enquiry
            $this->db->delete($this->table, ['enquiry_id' => $enquiry_id]);
        }

        // Insert / Update rows
        foreach ($req_data as $key => $value) {
            // Skip completely empty rows (no name, category, price, quantity)
            if (
                (!isset($value[2]) || $value[2] === '' || $value[2] === null) &&
                (!isset($value[3]) || $value[3] === '' || $value[3] === null) &&
                (!isset($value[4]) || $value[4] === '' || $value[4] === null) &&
                (!isset($value[5]) || $value[5] === '' || $value[5] === null)
            ) {
                continue;
            }

            $product = [];
            $product["product_id"]   = isset($value[1]) ? (int) $value[1] : 0;
            $product["product_name"] = isset($value[2]) ? $value[2] : '';
            $product["category"]     = isset($value[3]) ? $value[3] : '';
            $product["price"]        = isset($value[4]) ? (float) $value[4] : 0;
            $product["quantity"]     = isset($value[5]) ? (int) $value[5] : 0;
            $product["enquiry_id"]   = $enquiry_id;

            // Map status text/value back to tinyint
            $statusVal = isset($value[6]) ? $value[6] : 1;
            if ($statusVal === 'Active' || $statusVal === 1 || $statusVal === '1') {
                $product["status"] = 1;
            } else {
                $product["status"] = 0;
            }

            if ($value[0] === "edit" && $product["product_id"] > 0) {
                $this->db->where('product_id', $product["product_id"]);
                $this->db->update($this->table, $product);
            } else {
                unset($product["product_id"]);
                $this->db->insert($this->table, $product);
            }
        }

        return [
            "status"     => "success",
            "statusCode" => "200"
        ];
    }
}

