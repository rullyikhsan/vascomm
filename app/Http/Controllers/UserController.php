<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Traits\ResponseTrait;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Validator;

use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * User Model class.
     *
     * @var User
     */
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->has('perPage') ? intval($request->perPage) : 5;
        $page = $request->has('page') ? intval($request->page) : 1;
        $take = $request->has('take') ? intval($request->take) : 20;
        $skip = $request->has('skip') ? intval($request->skip) : 0;
        $search = $request->has('search') ? $request->search : null;

        $query = $this->user->query();

        if ($search !== null) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        $users = $query->skip($skip)->take($take)->paginate($perPage, ['*'], 'page', $page);

        return $this->responseSuccess($users, 'User List Fetched Successfully!');
    }

    public function dataTables(): JsonResponse
    {
        $users = User::query();
        return DataTables::of($users)->make();
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
        $user = $this->user->create($input);
        if ($user) {
            return $this->responseSuccess($user, 'User Created Successfully !');
        } else {
            return $this->responseError(null, 'Failed Create User !', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): JsonResponse
    {
        $user = $this->user->where('id', $id)->first();
        if ($user) {
            return $this->responseSuccess($user, 'User Created Successfully !');
        } else {
            return $this->responseError(null, 'User Not Found !', Response::HTTP_NOT_FOUND);
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

        $user = $this->user->where('id', $id)->first();
        if ($user) {
            $user->update($request->all());
            return $this->responseSuccess($user, 'User Update Successfully !');
        } else {
            return $this->responseError(null, 'User Not Found !', Response::HTTP_NOT_FOUND);
        }
    }

    public function delete($id): JsonResponse
    {
        $user = $this->user->where('id', $id)->first();
        if ($user) {
            $user->delete();
            return $this->responseSuccess($user, 'User Delete Successfully !');
        } else {
            return $this->responseError(null, 'User Not Found !', Response::HTTP_NOT_FOUND);
        }
    }
}
