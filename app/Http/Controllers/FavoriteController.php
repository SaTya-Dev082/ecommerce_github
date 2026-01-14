<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function index()
    {
        $data = [];
        $product = Product::find("status");
        if ($product->status != "favorite") {
            return response()->json([
                "status" => false,
                "message" => "No favorite products found",
                "favorites" => []
            ], 200);
        } else {
            $data[] = $product;
        }
        // $favorites=Favorite::orderBy("id","DESC")->with("product")->get();
        return response()->json([
            "status" => true,
            "message" => "All Favorites Retrieved",
            "favorites" => $data
        ], 200);
    }
}
