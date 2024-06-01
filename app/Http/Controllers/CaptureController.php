<?php

namespace App\Http\Controllers;

use App\Models\Capture;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CaptureController extends ResponseController
{
    public function getCaptures(Request $request)
    {
        try {
            $captures = Capture::where('user_id', Auth::user()->id)->get();
            return $this->sendResponse('Get captures successful', $captures);
        }
        catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getRecentCaptures(Request $request, $count)
    {
        try {
            $captures = Capture::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->take($count)->get();
            return $this->sendResponse('Get captures successful', $captures);
        }
        catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    
    public function getCapturebyCaptureID(Request $request, $capture_id)
    {
        try {
            $captures = Capture::findOrFail($capture_id);
            return $this->sendResponse('Get captures successful', $captures);
        }
        catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function storeImage(UploadedFile $file, $folder = null, $filename = null)
    {
        $name = !is_null($filename) ? $filename : date('ymdhis') . '_' . Str::random(6);
        return $file->storeAs($folder, $name . "." . $file->extension(), 'gcs');
    }

    public function storeCapture(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'image' => 'required|mimes:png,jpg,jpeg',
            ],
            [
                'image.required' => 'Image can\'t be empty',
                'image.mimes' => 'Allowed image extensions are PNG, JPG, JPEG'
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors(), 422);
            }

            // classify belom

            $image_url = $request->hasFile('image') ? $this->storeImage($request->file('image'), 'captures') : null;
            $capture = Capture::create([
                'image_url' => $image_url,
                'result' => 'sangat segar',
                'rate' => 96,
                'user_id' => Auth::user()->id,
                'fish_id' => 1,
            ]);

            return $this->sendResponse('Store capture successful', $capture);
        }
        catch (Exception $e){
            return $this->sendError($e->getMessage());
        }
    }
}
