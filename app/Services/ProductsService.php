<?php

namespace App\Services;

use App\Models\ProductsModel;
use Illuminate\Support\Facades\DB;

use App\Traits\Language;
use Config;

class ProductsService
{
    use Language;

    private $productsModel;

    public function __construct()
    {
        $this->productsModel = new ProductsModel();
    }

    public function execute(array $requestedData, int $adminId)
    {
        return $this->productsModel->storeProduct($requestedData, $adminId);
    }

    public function update(int $productId, array $requestedData)
    {
        return $this->productsModel->updateProduct($productId, $requestedData);
    }

    public function loadProducts()
    {
        return $this->productsModel->getProducts();
    }

    public function getProductsByBrand()
    {
        return ProductsModel::select('brand_name', DB::raw('count(*) as product_count'))
            ->groupBy('brand_name')
            ->get();
    }

    public function loadPagination()
    {
        return $this->productsModel->getPaginate();
    }

    public function loadProduct(int $productId)
    {
        return $this->productsModel->getProduct($productId);
    }

    public function loadIsActiveFunction(int $productId, int $value)
    {
        return $this->productsModel->setActive($productId, $value);
    }

    public function loadProductsByCreatedAt()
    {
        return $this->productsModel->getProductsByCreatedAt();
    }

    public function checkIfProductHaveAssignedSale(int $productId)
    {
        $product = $this->productsModel->findClientByGivenClientId($productId);
    
        $countSales = $product->sales()->count();
    
        if ($countSales > 0) {
            return 'Cannot delete the product. It has assigned sales.';
        } else {
            return true;
        }
    }
    

    public function loadCountProducts()
    {
        return $this->productsModel->countProducts();
    }
}
