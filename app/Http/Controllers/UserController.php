<?php

namespace App\Http\Controllers;

use App\Models\Purchased;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Process user authentication
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);
        $validation = Validator::make($data, [
            'email'     =>  'required|email',
            'password'    => 'required|string'
        ]);
        if ($validation->fails()) return self::returnFailed($validation->errors()->first(), 400);


        $token = auth()->claims(['type' => 'user'])->attempt($data);
        if ($token) {
            $token = self::respondWithToken($token);
            $user = Auth::user();

            return self::returnSuccess(["token" => $token, "user" => $user]);
        }

        return self::returnFailed("Invalid credentials", 401);
    }

    /**
     * Fetch authenticated user data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUser(Request $request)
    {
        return self::returnSuccess(Auth::user());
    }

    /**
     * Fetch products purchased by authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserProducts()
    {
        return self::returnSuccess(Auth::user()->products);
    }

    /**
     * Create new purchased product data for authenticated user
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function createUserProduct(Request $request)
    {
        Log::info("\n");
        Log::info(json_encode($request->all()));
        $data = $request->only(['sku']);
        $validation = Validator::make($data, [
            'sku' => 'required|string|exists:products,sku'
        ]);

        if ($validation->fails()) return self::returnFailed($validation->errors()->first(), 400);

        $newPurchase = Purchased::firstOrCreate([
            "user_id"       => Auth::user()->id,
            "product_sku"   => $request->sku
        ]);

        return self::returnSuccess($newPurchase, "successful", 201);
    }

    /**
     * Delete a single purchased product data for authenticated user
     * @param string $SKU
     * @return \Illuminate\Http\Response
     */
    public function deleteUserProduct($SKU)
    {
        $validation = Validator::make(["sku" => $SKU], [
            'sku' => 'required|string|exists:purchased,product_sku'
        ]);

        if ($validation->fails()) return self::returnFailed($validation->errors()->first(), 400);

        $purchasedProduct = Auth::user()->purchases()->where("product_sku", $SKU)->first();
        if ($purchasedProduct) {
            $purchasedProduct->delete();
            return self::returnSuccess($purchasedProduct, "successful", 202);
        }

        return self::returnFailed("No such purchase associated with this user", 404);
    }
}
