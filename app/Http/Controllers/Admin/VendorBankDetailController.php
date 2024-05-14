<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorBankDetail;
use Illuminate\Http\Request;

class VendorBankDetailController extends Controller
{
    public function vendor_bank_details($id)
    {
        $bank_details = VendorBankDetail::where('vendor_id',$id)->first();
        $vendor = Vendor::find($id);
        if($bank_details)
        {
            return view('admin.vendor_bank_detail.update_bank_detail',compact('vendor','bank_details'));
        }
        else
        {
            return view('admin.vendor_bank_detail.create_bank_detail',compact('vendor'));
        }
    }

    public function add_bank_details(Request $request)
    {
        $request->validate(
        [
            'bank_name' => 'bail|required',
            'branch_name' => 'bail|required',
            'ifsc_code' => 'bail|required|regex:/^[A-Za-z]{4}\d{7}$/',
            'clabe' => 'bail|required|numeric|digits:18',
            'account_number' => 'bail|required'
        ],
        [
            'ifsc_code.required' => 'IFSC Code Field Is Required.',
            'ifsc_code.regex' => 'IFSC Code Invalid.',
        ],
        );
        $data = $request->all();
        $details = VendorBankDetail::create($data);
        return redirect('admin/vendor/'.$details->vendor_id)->with('msg','vendor bank Detail updated successfully');
    }

    public function update_bank_details(Request $request,$id)
    {
        $request->validate(
        [
            'bank_name' => 'bail|required',
            'branch_name' => 'bail|required',
            'ifsc_code' => 'bail|required|regex:/^[A-Za-z]{4}\d{7}$/',
            'clabe' => 'bail|required|numeric|digits:18',
            'account_number' => 'bail|required'
        ],
        [
            'ifsc_code.required' => 'IFSC Code Field Is Required.',
            'ifsc_code.regex' => 'IFSC Code Invalid.',
        ],
        );
        $data = VendorBankDetail::find($id);
        $data->update($request->all());
        return redirect('admin/vendor/'.$data->vendor_id)->with('msg','vendor bank Detail updated successfully');
    }
}
