<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Http\Traits\ResponseTrait;

use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
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

    /**
     * User Model class.
     *
     * @var User
     */
    public $user;

    public function __construct(Product $product, User $user)
    {
        $this->product = $product;
        $this->user = $user;
    }

    public function index(): JsonResponse
    {
        $product = Product::query();
        $user = User::query();

        $totalUser = $user->get()->count();
        $totalUserAktif = $user->where('status', '=', 'active')->get()->count();

        $totalProduct = $product->get()->count();
        $totalProductAktif = $product->where('status', '=', 'active')->get()->count();

        $listProductActive = $product->where('status', '=', 'active')->limit(5)->orderBy('created_at', 'DESC')->get();

        $response = [
            'total_user' => $totalUser,
            'total_user_aktif' => $totalUserAktif,
            'total_product' => $totalProduct,
            'total_product_aktif' => $totalProductAktif,
            'list_product' => $listProductActive
        ];

        return $this->responseSuccess($response, 'Get Data Dashboard Successfully !');
    }
}
