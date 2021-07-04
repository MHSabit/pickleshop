<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/product/add",[ProductController::class,'addproduct']);
Route::post("/product/IncreaseStock",[ProductController::class,'IncreaseStock']);
Route::post("/product/DecreaseStock",[ProductController::class,'DecreaseStock']);


//order
Route::post("/order",[OrderController::class,'order']);

// order multiple time or update order
Route::post("/order/update",[OrderController::class,'updateOrder']);

//Assigin For Delivery or  delivered 
Route::post("/order/status",[OrderController::class,'OrderStatus']);

//Pending Order or delivered product 
Route::get("/order/orderList",[OrderController::class,'ListofOrder']);

//delivery Faild and updateproduct Table 
Route::post("/order/cancel",[OrderController::class,'Ordercancel']);

//pre Order 
Route::post("/preorder",[OrderController::class,'PreOrder']);


//give Discunt 
Route::post("/preorder/discount",[OrderController::class,'giveDiscount']);

//give discount product delivery
Route::post("/preorder/deliver",[OrderController::class,'giveDiscount']);







