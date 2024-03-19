<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Traits\ResponseTrait;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Validator;

use Yajra\DataTables\DataTables;

class ProductController extends Controller
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

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->has('perPage') ? intval($request->perPage) : 5;
        $page = $request->has('page') ? intval($request->page) : 1;
        $take = $request->has('take') ? intval($request->take) : 20;
        $skip = $request->has('skip') ? intval($request->skip) : 0;
        $search = $request->has('search') ? $request->search : null;

        $query = $this->product->query();

        if ($search !== null) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $products = $query->skip($skip)->take($take)->paginate($perPage, ['*'], 'page', $page);

        return $this->responseSuccess($products, 'Product List Fetched Successfully!');
    }

    public function dataTables(): JsonResponse
    {
        $products = Product::query();
        return DataTables::of($products)->make(true);
    }

    public function store(Request $request): JsonResponse
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 'Validation Error', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $input = $request->all();
        $product = $this->product->create($input);
        if ($product) {
            return $this->responseSuccess($product, 'Product Created Successfully !');
        } else {
            return $this->responseError(null, 'Failed Create Product !', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): JsonResponse
    {
        $product = $this->product->where('id', $id)->first();
        if ($product) {
            return $this->responseSuccess($product, 'Product Created Successfully !');
        } else {
            return $this->responseError(null, 'Product Not Found !', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 'Validation Error', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $product = $this->product->where('id', $id)->first();
        if ($product) {
            $product->update($request->all());
            return $this->responseSuccess($product, 'Product Update Successfully !');
        } else {
            return $this->responseError(null, 'Product Not Found !', Response::HTTP_NOT_FOUND);
        }
    }

    public function delete($id): JsonResponse
    {
        $product = $this->product->where('id', $id)->first();
        if ($product) {
            $product->delete();
            return $this->responseSuccess($product, 'Product Delete Successfully !');
        } else {
            return $this->responseError(null, 'Product Not Found !', Response::HTTP_NOT_FOUND);
        }
    }
}
