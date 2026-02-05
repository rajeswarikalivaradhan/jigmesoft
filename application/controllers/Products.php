<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller
{
    public $companyid;
    public $userid;
    public $mysqldatetime;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('xssclean');
        fnIfCheckUserLoggedIn();
        $this->load->model('ProductsModel', 'products');

        $ArrUserLoggedInfo   = fnGetUserLoggedInfo('1');
        $this->companyid     = $ArrUserLoggedInfo['companyid'];
        $this->userid        = $ArrUserLoggedInfo['id'];
        $this->mysqldatetime = date('Y-m-d H:i:s');
    }

    /**
     * List products.
     * If accessed via AJAX/POST with 'json' = 1, returns JSON.
     */
    public function index()
    {
        $only_active = (int) $this->input->get_post('only_active') === 1;
        $is_json     = (int) $this->input->get_post('json') === 1;

        $products = $this->products->get_all($only_active);

        if ($is_json) {
            echo json_encode(['errcode' => 1, 'data' => $products]);
            return;
        }

        // Adjust view path as per your structure if needed
        $this->load->view('products/index', ['products' => $products]);
    }

    /**
     * Get a single product by ID (JSON).
     */
    public function view($id = 0)
    {
        $id       = (int) $id;
        $product  = $this->products->get_by_id($id);

        if (empty($product)) {
            echo json_encode(['errcode' => -1, 'msg' => 'Product not found']);
            return;
        }

        echo json_encode(['errcode' => 1, 'data' => $product]);
    }

    /**
     * Create or update a product (JSON).
     * If 'product_id' is present, updates; otherwise inserts.
     */
    public function save()
    {
        $product_id = (int) xssclean($this->input->post('product_id'));

        $data = [
            'product_name' => xssclean($this->input->post('product_name')),
            'category'     => xssclean($this->input->post('category')),
            'price'        => xssclean($this->input->post('price')),
            'quantity'     => (int) xssclean($this->input->post('quantity')),
            'status'       => (int) xssclean($this->input->post('status')) ?: 1
        ];

        if ($data['product_name'] === '') {
            echo json_encode(['errcode' => -1, 'msg' => 'Product name is required']);
            return;
        }

        if ($product_id > 0) {
            $this->products->update($product_id, $data);
        } else {
            $product_id = $this->products->insert($data);
        }

        echo json_encode(['errcode' => 1, 'msg' => 'Saved successfully', 'product_id' => $product_id]);
    }

    /**
     * Soft delete a product (status = 0).
     */
    public function delete($id = 0)
    {
        $id = (int) $id;

        if ($id <= 0) {
            echo json_encode(['errcode' => -1, 'msg' => 'Invalid product ID']);
            return;
        }

        $this->products->soft_delete($id);
        echo json_encode(['errcode' => 1, 'msg' => 'Deleted successfully']);
    }

    /**
     * Get products in jsspreadsheet grid format.
     */
    public function getProductsGrid()
    {
        $enquiry_id = (int) xssclean($this->input->post('enquiry_id'));
        $data = $this->products->getProductsGrid($enquiry_id);
        echo json_encode($data);
    }

    /**
     * Save products from jsspreadsheet grid.
     * Expects POST 'data' as JSON (array of rows).
     */
    public function updateProductsGrid()
    {
        $object   = xssclean($this->input->post('data'));
        $req_data = json_decode($object);
        $enquiry_id = (int) xssclean($this->input->post('enquiry_id'));
        $data     = $this->products->updateProductsFromGrid($req_data, $enquiry_id);
        echo json_encode($data);
    }
}

