<?php

namespace App\Http\Controllers\Version;

use App\Http\Controllers\Controller;
use App\Models\Version\AppVersion;
use Faker\Core\Version;
use Illuminate\Http\Request;

class VersionsController extends Controller
{
    public function index()
    {
        $versions = AppVersion::get();
        return view('version.index', ['versions' => $versions,]);
    }
    public function deleteVersion(Request $request, $id)
    {
        // Validation
        $request->validate([
            'version-id' => 'required|exists:app_versions,id',
        ]);

        try {
            // Perform the delete operation
            $version = AppVersion::find($request->input('version-id'));
            $version->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
