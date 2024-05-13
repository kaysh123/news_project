<?php

namespace App\Http\Controllers\Version;


use App\Http\Controllers\Controller;
use App\Models\Version\AppVersion;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    public function checkUpdate(Request $request)
    {
        $clientVersionCode = $request->input('version_code');

        $latestVersion = AppVersion::orderBy('created_at', 'desc')->first();

        if (!$latestVersion) {
            $response['message'] = "No app versions found.";
            $response['status'] = false;
            return response()->json($response);
        }

        $forceUpdate = $latestVersion->force_update;
        $latestVersionCode = $latestVersion->version_code;
        if ($clientVersionCode < $latestVersionCode) {
            $response['message'] = "A new app version is available.";
            $response['status'] = true;
            $response['data'] = $latestVersion;
            return response()->json($response);
            //return response()->json($response);
        } else {
            $response['message'] = "App is up to date..";
            $response['status'] = false;
            return response()->json($response);
        }
    }
    public function addVersion(Request $request)
    {
        // Validate the request data
        $request->validate([
            'version_name' => 'required',
            'version_code' => 'required|unique:app_versions',
            'force_update' => 'boolean',
            'release_notes' => 'nullable|string',
        ]);

        // Create a new app version record
        $appVersion = AppVersion::create([
            'version_name' => $request->input('version_name'),
            'version_code' => $request->input('version_code'),
            'force_update' => $request->input('force_update', false), // Default to false if not provided
            'release_notes' => $request->input('release_notes'),
        ]);

        return response()->json(['message' => 'App version added successfully.', 'data' => $appVersion], 201);
    }
}
