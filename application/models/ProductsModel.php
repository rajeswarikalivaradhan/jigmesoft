<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ProductsModel extends CI_Model
{
    protected $table = 'products';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all products (master list: product_id, product_name, category, price, status).
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
     */
    public function get_by_id($product_id)
    {
        return $this->db
            ->get_where($this->table, ['product_id' => (int) $product_id])
            ->row_array();
    }

    /**
     * Get product_id by product_name (first match). Used when saving grid by name.
     */
    public function get_product_id_by_name($product_name)
    {
        if ($product_name === '' || $product_name === null) {
            return null;
        }
        $row = $this->db->select('product_id')
            ->from($this->table)
            ->where('product_name', $product_name)
            ->limit(1)
            ->get()
            ->row_array();
        return $row ? (int) $row['product_id'] : null;
    }

    /**
     * Insert new product record (master table).
     */
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update existing product record (master table).
     */
    public function update($product_id, $data)
    {
        $this->db->where('product_id', (int) $product_id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Hard delete product record (master table).
     */
    public function delete($product_id)
    {
        $this->db->where('product_id', (int) $product_id);
        return $this->db->delete($this->table);
    }

    /**
     * Soft delete: set status = 0 (master table).
     */
    public function soft_delete($product_id)
    {
        $this->db->where('product_id', (int) $product_id);
        return $this->db->update($this->table, ['status' => 0]);
    }

    /**
     * Get products in grid format for an enquiry.
     * Uses enquiry_products (JOIN products) so product_name, category, price come from products.
     * Grid columns: mode, line_id, product_name, category, price, quantity, total, status.
     */
    public function getProductsGrid($enquiry_id = null)
    {
        $enquiry_id = (int) $enquiry_id;
        $this->db->select('ep.id AS line_id, ep.product_id, p.product_name, p.category, p.price, ep.quantity, ep.total, ep.status');
        $this->db->from(KN_ENQUIRY_PRODUCTS . ' ep');
        $this->db->join($this->table . ' p', 'p.product_id = ep.product_id', 'inner');
        $this->db->where('ep.enquiry_id', $enquiry_id);
        $this->db->order_by('ep.id', 'ASC');
        $data = $this->db->get()->result_array();

        $result = [];
        foreach ($data as $key => $value) {
            $statusText = ((int) $value['status'] === 1) ? 'Active' : 'Inactive';
            $total = isset($value['total']) && $value['total'] !== null ? (float) $value['total'] : (float) $value['price'] * (int) $value['quantity'];

            // Column 2 = product_id so dropdown shows selected product after save/reload
            $result[$key] = [
                'edit',
                (int) $value['line_id'],
                (int) $value['product_id'],
                $value['category'],
                $value['price'],
                (int) $value['quantity'],
                $total,
                $statusText
            ];
        }

        // Dropdown: products list (id, name, category, price) - no quantity
        $products_list = $this->get_all(true);
        $output['products_list'] = array_map(function ($p) {
            return [
                'id'       => (int) $p['product_id'],
                'name'     => $p['product_name'],
                'category' => $p['category'],
                'price'    => $p['price'],
            ];
        }, $products_list);

        $output['column'] = [
            ['title' => "mode",    'width' => '10%', 'align' => 'center', 'type' => 'hidden'],
            ['title' => "id",      'width' => '10%', 'align' => 'center', 'type' => 'hidden'],
            ['title' => "Product", 'width' => '25%', 'align' => 'left'],
            ['title' => "Category", 'width' => '15%', 'align' => 'left'],
            ['title' => "Price",    'width' => '12%', 'align' => 'right'],
            ['title' => "Quantity", 'width' => '12%', 'align' => 'right'],
            ['title' => "Total",    'width' => '14%', 'align' => 'right', 'readOnly' => true],
            ['title' => "Status",   'width' => '12%', 'align' => 'center', 'type' => 'dropdown', 'source' => ['Active', 'Inactive']],
        ];

        $output['data'] = $result;
        return $output;
    }

    /**
     * Save grid to enquiry_products. products table is not modified.
     * Grid row: 0=mode, 1=line_id, 2=product_name, 3=category, 4=price, 5=quantity, 6=total, 7=status.
     */
    public function updateProductsFromGrid($req_data, $enquiry_id = null)
    {
        $enquiry_id = (int) $enquiry_id;
        $incoming_line_ids = [];

        foreach ($req_data as $key => $value) {
            $line_id = isset($value[1]) ? $value[1] : '';
            if ($line_id !== '' && $line_id !== null) {
                $incoming_line_ids[] = (int) $line_id;
            }
        }

        // Delete lines for this enquiry that are not in incoming data
        if (!empty($incoming_line_ids)) {
            $this->db->where('enquiry_id', $enquiry_id);
            $this->db->where_not_in('id', $incoming_line_ids);
            $this->db->delete(KN_ENQUIRY_PRODUCTS);
        } else {
            $this->db->delete(KN_ENQUIRY_PRODUCTS, ['enquiry_id' => $enquiry_id]);
        }

        foreach ($req_data as $key => $value) {
            $col2        = isset($value[2]) ? $value[2] : '';
            $price       = isset($value[4]) ? (float) $value[4] : 0;
            $quantity    = isset($value[5]) ? (int) $value[5] : 0;
            $total       = isset($value[6]) ? (float) $value[6] : ($price * $quantity);

            if ($col2 === '' && $price == 0 && $quantity == 0) {
                continue;
            }

            // Column 2 can be product_id (numeric) or product_name (after selection)
            $product_id = (is_numeric($col2) && (int) $col2 > 0) ? (int) $col2 : $this->get_product_id_by_name(trim($col2));
            if ($product_id === null) {
                continue;
            }

            $statusVal = isset($value[7]) ? $value[7] : 1;
            $status = ($statusVal === 'Active' || $statusVal === 1 || $statusVal === '1') ? 1 : 0;

            $line_id = isset($value[1]) ? $value[1] : '';
            $line_id = ($line_id !== '' && $line_id !== null) ? (int) $line_id : 0;

            $row = [
                'enquiry_id' => $enquiry_id,
                'product_id' => $product_id,
                'quantity'   => $quantity,
                'total'      => $total,
                'status'     => $status,
            ];

            if ($line_id > 0) {
                $this->db->where('id', $line_id);
                $this->db->where('enquiry_id', $enquiry_id);
                $this->db->update(KN_ENQUIRY_PRODUCTS, $row);
            } else {
                $this->db->insert(KN_ENQUIRY_PRODUCTS, $row);
            }
        }

        return [
            'status'     => 'success',
            'statusCode' => '200'
        ];
    }
}
