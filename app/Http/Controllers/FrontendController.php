<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Traits\ResponseTrait;

use Illuminate\Http\JsonResponse;

class FrontendController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Product Model class.
     *
     * @var Product
     */
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function index(): JsonResponse
    {
        $product = Product::query();

        $productNews = $product->where('status', '=', 'active')->orderBy('id', 'desc')->limit(5)->get();

        $productAvailable = $product->where('status', '=', 'active')->inRandomOrder()->limit(10)->get();

        $response = [
            'product_news' => $productNews,
            'product_available' => $productAvailable,
        ];

        return $this->responseSuccess($response, 'Get Data Successfully !');
    }
}
