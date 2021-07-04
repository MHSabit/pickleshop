<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;


class ProductController extends Controller
{
    public function addproduct()
    {
        $products= new Product();
        $products->name = Request('name');
        $products->price = Request('price');
        $products->weight = Request('weight');
        $products->quentity = Request('quentity');
        $products->ProductCat_id = Request('ProductCat_id');
        $products->taste = Request('taste');
        $product = Product::where("name",Request('name'))->get();
        if(count($product)>0){
            return response()->json(["error"=>"the name already exists"]);
        }else{
            $products->save();
            return response()->json(["products"=>$products]);

        }
    }

    public function IncreaseStock()
    {
        $product= new Product();
        $product= Product::findorFail(Request('Product_id'));
        $product->quentity = $product->quentity+Request('addproduct');
        $product->save();
        return response()->json(["product"=>$product]);

    }

    public function DecreaseStock()
    {
        $product = new Product();
        $product= Product::findorFail(Request('Product_id'));
            if($product->quentity - Request('decreaseproduct')>=0){
                $product->quentity = $product->quentity-Request('decreaseproduct');
            }
        $product->save();
        return $product;
    }
}
