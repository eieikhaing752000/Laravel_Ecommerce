<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\PaymentFormRequest;

class PaymentController extends Controller
{
    public function index()
    {
        $payments=Payment::all();
        return view('admin.payment.index',compact('payments'));
    }
    public function create()
    {
        return view('admin.payment.create');
    }
    public function store(PaymentFormRequest $request){
        $validatedData=$request->validated();
        if($request->hasFile('image')){
            $file=$request->file('image');
            $ext=$file->getClientOriginalExtension();
            $filename=time().'.'.$ext;
            $file->move('uploads/payment/',$filename);
            $validatedData['image']="uploads/payment/$filename";
            // dd($validatedData['image']);
        }
        $validatedData['status']=$request->status==true?'1':'0';
        Payment::create([
            'name'=>$validatedData['name'],
            'image'=>$validatedData['image'],
            'status'=>$validatedData['status'],
        ]);
        return redirect('admin/payment')->with('message','Payment Added Successfully');
    }
    public function edit(Payment $payment)
    {
        return view('admin.payment.edit',compact('payment'));
    }
    public function update(PaymentFormRequest $request,Payment $payment)
    {
        // dd($request);
        $validatedData=$request->validated();
        if($request->hasFile('image')){
            $destination=public_path($payment->image);
            if(File::exists($destination)){
                File::delete($destination);
            }
            $file=$request->file('image');
            $ext=$file->getClientOriginalExtension();
            $filename=time().'.'.$ext;
            $file->move('uploads/payment/',$filename);
            $validatedData['image']="uploads/payment/$filename";
        }
        $validatedData['status']=$request->status==true?'1':'0';
        $pay=Payment::where('id',$payment->id)->update([
            'name'=>$validatedData['name'],
            'image'=>$validatedData['image'] ?? $payment->image,
            'status'=>$validatedData['status'],
        ]);
        dd($pay);
        return redirect('admin/payment')->with('message','Payment Updated Successfully');
    }
     public function destroy(Payment $payment)
    {
        if($payment->count()>0){
        $destination=public_path($payment->image);
            if(File::exists($destination)){
                File::delete($destination);
            }
        $payment->delete();
        return redirect('admin/payment')->with('message','Payment Deleted Successfully');
        }
        return redirect('admin/payment')->with('message','Something Went Wrong');
    }
}
