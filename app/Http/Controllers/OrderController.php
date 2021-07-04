<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\PreOrder;
use App\Models\PreOrderProduct;



class OrderController extends Controller
{
    public function order()
    {
        $order = new Order();
        $order->Customer_Name = Request('CustomerName');
        $order->Phone = Request('Phone');
        $order->Address = Request('Address');
        $order->InvoiceNumber = uniqid();
        $order->OderStatus = "Pending";
        $Productorder = Request('oderProduct');       
        $orderquentity= Request('Quentity');

    //Inside Outside dhaka delivery charge 
        if(strpos($order->Address , "Dhaka")!==false)
        {
            $order->DeliveryCharge = "60 tk";
        }
        else
        {
            $order->DeliveryCharge = "100 tk";
        }
    //end delivery charge


        $flag = true;
        $UnavailableProduct = [] ;

        $product= new Product();
        for($i=0; $i<count($Productorder); $i++)
        {
            $id = $Productorder[$i];
            $product= Product::findorFail($id);
            if($product->quentity>$orderquentity[$i])
            {
                $UnavailableProduct[$i] = 0;
            } 
            else
            {
                $flag= false;
                $UnavailableProduct[$i] = $Productorder[$i];
                // echo $UnavailableProduct[$i];
            }

        }

        if($flag==true)
        {
            $order->save();
            
            for($i=0; $i<count($Productorder); $i++)
            {
                $product= Product::findorFail($Productorder[$i]);
                $product->quentity = $product->quentity-$orderquentity[$i];
                $product->save();
                
                //data into ProductOrderTable
                $OrderProduct = new OrderProduct();

                $OrderProduct->OrderID = $order->id;
                $OrderProduct->ProductID = $Productorder[$i];
                $OrderProduct->ProductOrderQuentity = $orderquentity[$i];
                $OrderProduct->save();
            }
            return response()->json(["message"=>"the order is submitted","order"=>$order]);
        }
        else
        {
            for($i=0; $i<count($UnavailableProduct); $i++)
            {
                if($UnavailableProduct[$i]>0)
                {
                $unavailableProducts = Product::where('id', $UnavailableProduct[$i])->get();
                $similarproduct = Product::where('ProductCat_id', $unavailableProducts[0]->ProductCat_id)->get();
                }
            }
            
            return response()->json(["message"=>"Your Order is not Submitted And Your Order Is","order"=>$order,"MessageTwo"=>'You Can Purchase Below Product', "SimilarProduct"=>$similarproduct]);
        }
        // echo $UnavailableProduct[1];

    }



    public function updateOrder()
    {
        $order = new Order();
        $order= Order::findorFail(Request("orderid"));
        $Productorder = Request('addproducttoorder');       
        $orderquentity= Request('addedQuentity');
        $flag = true;

        $product= new Product();
        for($i=0; $i<count($Productorder); $i++)
        {
            $id = $Productorder[$i];
            $product= Product::findorFail($id);
            if($product->quentity>$orderquentity[$i])
            {

            }
            else
            {
                $flag= false;
            }
        }

        if($flag==true)
        {   
            $OrderProduct = new OrderProduct();
            // $OrderProduct = new OrderProduct();
            for ($i=0; $i<count($Productorder); $i++)
            {     
                //Product table update 
                $product= Product::findorFail($Productorder[$i]);
                $product->quentity = $product->quentity-$orderquentity[$i];
                $product->save();


                $OrderProduct = OrderProduct::where('OrderID', Request("orderid"))
                                            ->where('ProductID',$Productorder[$i])->get();

                
                $OrderProduct2= OrderProduct::where('OrderID', Request("orderid"))
                                            ->where('ProductID',$Productorder[$i])
                                            ->update(['ProductOrderQuentity' =>$OrderProduct[0]->ProductOrderQuentity+$orderquentity[$i]]);
            }
            // return "order is submitted and your Order is " . $order ;
            return response()->json(["message"=>"order is submitted and your Order is ","order"=>$order]);

        }
        else 
        {
            return response()->json(["message"=>"order is submitted and your Order is ","order"=>$order]);        }
        
        
    }

    public function OrderStatus()
    {
        $order = Order::where('id', Request("orderID"))->update(['OderStatus'=>Request("OrderStatus")]);
        return "ORder ID : ".Request("orderID")." is ".Request("OrderStatus");
    }

    public function ListofOrder()
    {
        $orders =  Order::where('OderStatus', Request("OrderStatus"))->get();
        return $orders;
    }

    public function Ordercancel()
    {
        $products = new Product();
        $OrderProducts = OrderProduct::where('OrderID', Request("OrderID"))->get();

        for($i=0; $i<count($OrderProducts); $i++)
        {
            $productsone = Product::where('id', $OrderProducts[$i]->ProductID)->get();

            $updateproductquentity = $productsone[0]->quentity+$OrderProducts[$i]->ProductOrderQuentity;
            echo $updateproductquentity;
            echo "ok";

            $productstwo = Product::where('id', $OrderProducts[$i]->ProductID)
                                    ->update(['quentity' => $updateproductquentity]);
            
        }

        $productorderdelete = OrderProduct::where('OrderID' ,Request("OrderID") )->delete();
        $orderDelete = Order::where('id' ,Request("OrderID") )->delete();
        return "Your Order ID : ". Request("OrderID") ."  Has Been Deleted" ;
    }
    
    public function PreOrder()
    {
        $PreOrder = new PreOrder();
        $PreOrder->Customer_Name = Request('CustomerName');
        $PreOrder->Phone = Request('Phone');
        $PreOrder->Address = Request('Address');
        $PreOrder->price = Request('price');    
        $PreOrder->discountammount= 0; 
        $PreOrder->save();

        $preOrders = Request('PreOder');
        $PreOrderQuentity = Request('PreOrderQuentity');


        

        
        for($i=0; $i<count($preOrders); $i++)
        {
            
            $PreOrderProduct = new PreOrderProduct();
            $PreOrderProduct->OrderID = $PreOrder->id;
            $PreOrderProduct->ProductID = $preOrders[$i];
            $PreOrderProduct->ProductOrderQuentity = $PreOrderQuentity[$i];
            $PreOrderProduct->save();
        }
        return $PreOrderProduct;
    }

    public function giveDiscount()
    {
        $id = Request('PreOrderID');
        $PreOrderProduct = PreOrderProduct::where('OrderID', $id)->get();
        // $preOrderDiscount = Request('discountAmmount');

        if(count($PreOrderProduct)>1) 
        {
            $preorder= PreOrder::findorFail($id);
            $preorder->price = $preorder->price - Request('discountAmmount');
            $preorder->discountammount = Request('discountAmmount');
            $preorder->save();
            return  $preorder;
        }
        return  $preorder;
        
    }
}










































// foreach ($ids as $id)
        // {   
        //     echo "Product ";
        //     foreach($id as $data)
        //     {
        //         echo $data."</br> ";  
        //     }
        // }