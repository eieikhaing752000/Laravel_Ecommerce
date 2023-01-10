<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ProductFormRequest;

class ProductController extends Controller
{
    public function index()
    {
        $products=Product::all();
        return view('admin.products.index',compact('products'));
    }
    public function create()
    {
        $categories=Category::all();
        $brands=Brand::all();
        return view('admin.products.create',compact('categories','brands'));
    }
    public function store(ProductFormRequest $request){
        $validatedDate=$request->validated();

        $category=Category::findOrFail($validatedDate['category_id']);
        
        $product=$category->products()->create([
            'category_id'=>$validatedDate['category_id'],
            'name'=>$validatedDate['name'],
            'slug'=>Str::slug($validatedDate['slug']),
            'brand'=>$validatedDate['brand'],
            'small_description'=>$validatedDate['small_description'],
            'description'=>$validatedDate['description'],
            'original_price'=>$validatedDate['original_price'],
            'selling_price'=>$validatedDate['selling_price'],
            'quantity'=>$validatedDate['quantity'],
            'trending'=>$request->trending==true ?'1':'0',
            'status'=>$request->status==true ?'1':'0',
            'meta_title'=>$validatedDate['meta_title'],
            'meta_keyword'=>$validatedDate['meta_keyword'],
            'meta_description'=>$validatedDate['meta_description'],
        ]);
        if($request->hasFile('image')){
            $uploadPath='uploads/products/';
            $i=1;
            foreach($request->file('image')as $imageFile){
                $extention=$imageFile->getClientOriginalExtension();
                $filename=time().$i++.'.'.$extention;
                $imageFile->move($uploadPath,$filename);
                $finalImagePathName=$uploadPath.$filename;

                $product->productImages()->create([
                'product_id'=>$product->id,
                'image'=>$finalImagePathName,
                ]);
            }
        }
        return redirect('/admin/products')->with('message','Product Added Successfully');
        // return $product->id;
    }
    public function edit(int $product_id)
    {
        $categories=Category::all();
        $brands=Brand::all();
        $product=Product::findOrFail($product_id);
        return view('admin.products.edit',compact('categories','brands','product'));
    }
    public function update(ProductFormRequest $request,int $product_id)
    {
        $validatedDate=$request->validated();
        $product=Category::findOrFail($validatedDate['category_id'])
                ->products()->where('id',$product_id)->first();
        if($product){
            $product->update([
            'category_id'=>$validatedDate['category_id'],
            'name'=>$validatedDate['name'],
            'slug'=>Str::slug($validatedDate['slug']),
            'brand'=>$validatedDate['brand'],
            'small_description'=>$validatedDate['small_description'],
            'description'=>$validatedDate['description'],
            'original_price'=>$validatedDate['original_price'],
            'selling_price'=>$validatedDate['selling_price'],
            'quantity'=>$validatedDate['quantity'],
            'trending'=>$request->trending==true ?'1':'0',
            'status'=>$request->status==true ?'1':'0',
            'meta_title'=>$validatedDate['meta_title'],
            'meta_keyword'=>$validatedDate['meta_keyword'],
            'meta_description'=>$validatedDate['meta_description'],
        ]);
        if($request->hasFile('image')){
            $uploadPath='uploads/products/';
            $i=1;
            foreach($request->file('image')as $imageFile){
                $extention=$imageFile->getClientOriginalExtension();
                $filename=time().$i++.'.'.$extention;
                $imageFile->move($uploadPath,$filename);
                $finalImagePathName=$uploadPath.$filename;

                $product->productImages()->create([
                'product_id'=>$product->id,
                'image'=>$finalImagePathName,
                ]);
            }
        }
        return redirect('/admin/products')->with('message','Product Updated Successfully');
        }else{
            return redirect('admin/products')->with('message','No Such Product Id Found');
        }
    }
    public function destroyImage(int $product_image_id)
    {
        $productImage=ProductImage::findOrFail($product_image_id);
        if(File::exists($productImage->image)){
            File::delete($productImage->image);
        }
        $productImage->delete();
        return redirect()->back()->with('message','Product Image Deleted');    
    }
    public function destroy(int $product_id){
        $product=Product::findOrFail($product_id);
        if($product->productImages){
            foreach($product->productImages as $image){
                if(File::exists($image->image)){
                    File::delete($image->image);
                }
            }
        }
        $product->delete();
        return redirect()->back()->with('message','Product Deleted with all its image');
    }
}
