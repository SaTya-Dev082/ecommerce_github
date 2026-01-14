<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller


{
    // Get all products
    public function index()
    {
        $products = Product::orderBy("id", "DESC")->get();
        return response()->json([
            "status" => true,
            "message" => "All Products Retrieved",
            "products" => $products
        ], 200);
    }

    // Create new product
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name" => "required|string",
            "description" => "nullable|string",
            "price" => "required|numeric|min:0",
            "quantity" => "required|integer|min:0",
            "discount" => "nullable|numeric|min:0|max:100",
            "category_id" => "required|exists:categories,id",
            "stock" => "nullable|in:in_stock,out_of_stock,incoming",
        ]);
        if ($validate->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation Error",
                "errors" => $validate->errors()
            ], 422);
        } else {
            if ($request->hasFile("image_url")) {
                $imagePath = $request->file("image_url")->store("products", "public");
                $imageUrl = "/storage/" . $imagePath;
            } else {
                $imageUrl = $request->image_url;
            }
            $product = Product::create([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "quantity" => $request->quantity,
                "discount" => $request->discount,
                "category_id" => $request->category_id,
                "stock" => $request->stock,
                "image_url" => $imageUrl
            ]);
            return response()->json([
                "status" => true,
                "message" => "Product Created Successfully",
                "product" => $product
            ], 201);
        }
    }
    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                "status" => false,
                "message" => "Product Not Found"
            ], 404);
        }
        $validate = Validator::make($request->all(), [
            "name" => "sometimes|required|string",
            "description" => "nullable|string",
            "price" => "sometimes|required|numeric|min:0",
            "quantity" => "sometimes|required|integer|min:0",
            "discount" => "nullable|numeric|min:0|max:100",
            "category_id" => "sometimes|required|exists:categories,id",
            "stock" => "nullable|in:in_stock,out_of_stock,incoming",
        ]);
        if ($validate->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation Error",
                "errors" => $validate->errors()
            ], 422);
        } else {
            $data = $request->only([
                "name",
                "description",
                "price",
                "quantity",
                "discount",
                "category_id",
                "stock",
            ]);

            if ($request->hasFile("image_url")) {

                // delete old image
                if ($product->image_url) {
                    $oldPath = public_path($product->image_url);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }

                // upload new image
                $imagePath = $request->file("image_url")->store("products", "public");
                $data["image_url"] = "/storage/" . $imagePath;
            }

            $product->update($data);


            return response()->json([
                "status" => true,
                "message" => "Product Updated Successfully",
                "product" => $product
            ], 200);
        }
    }
    // Delete product
    public function destroy(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                "status" => false,
                "message" => "Product Not Found"
            ], 404);
        }
        $image = $product->image_url;
        $imageName = basename($image);
        $imagePath = storage_path("app/public/products/" . $imageName);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
        $product->delete();
        return response()->json([
            "status" => true,
            "message" => "Product Deleted Successfully"
        ], 200);
    }
}
