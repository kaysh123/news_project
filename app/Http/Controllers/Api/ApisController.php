<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ApiSelect;
use Illuminate\Http\Request;

class ApisController extends Controller
{
    public function index()
    {

        $apis = ApiSelect::get();
        return view('api.index', ['apis' => $apis,]);
    }
    public function apiCreate(Request $request)
    {

        $request->validate([
            'api_name' => 'required',     'api_key' => 'required', 'status' => 'required'
        ]);
        $apis = new ApiSelect();
        $apis->api_name = $request->input('api_name');
        $apis->api_key = $request->input('api_key');
        $apis->status = 0;
        $apis->save();

        return redirect('/api');
    }
    public function enable_api($id)
    {
        // Disable all APIs
        ApiSelect::where('id', '!=', $id)->update(['status' => 0]);
        // Enable the selected API
        $api = ApiSelect::findOrFail($id);
        $api->status = 1;
        $api->save();
        return redirect('/api');
    }
    public function delete_api(Request $request, $id)
    {
        // Validation
        $request->validate([
            'api-id' => 'required|exists:apiselect,id',
        ]);

        try {
            // Perform the delete operation
            $version = ApiSelect::find($request->input('api-id'));
            $version->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function apiUpdate(Request $request, $id)
    {
        // dd($request->all());
        // Validate the request data
        $api = $request->validate([
            'api_name' => 'required',
            'api_key' => 'required',
        ]);
        // dd($id);
        $api = ApiSelect::find($id);
        //$api->api_name = $request->input('api_name');
        $api->api_key = $request->input('api_key');
        $api->save();

        // Redirect to a relevant page
        return redirect('/api');
    }
}