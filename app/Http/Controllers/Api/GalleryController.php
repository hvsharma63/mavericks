<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource in admin panel.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex(Request $request)
    {
        $limit = isset($request->limit) ? $request->limit : 10;
        $result = Gallery::paginate($limit);
        return response()->json($result, 200);
    }


    /**
     * Display a listing of the resource in website.
     *
     * @return \Illuminate\Http\Response
     */
    public function clientIndex(Request $request)
    {
        //
        $limit = isset($request->limit) ? $request->limit : 10;
        $result = Gallery::paginate($limit);
        $data = $result;
        $result = $result->makeHidden(['id']);
        $data->data = $result;
        return response()->json($data, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'images' => 'required|array',
            'images.*' => 'mimes:jpeg,jpg,png',
        ]);

        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $file) {
                $image = new Gallery();
                $name = time() . '_' . Str::random(6) . '.' . $file->extension();
                $file->move(public_path() . '/images/', $name);
                $image->name = $name;
                $image->save();
            }
        }

        $message = array('message' => 'Images uploaded successfully');
        return response()->json($message);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $image = Gallery::find($id);
        if (!isset($image)) {
            $responseText = 'No record found';
        } else {
            $path = public_path('images') . '\\' . $image->name;
            $image->delete();
            if (File::exists($path)) {
                File::delete($path);
            }
            $responseText = 'Image deleted successfully';
        }

        $message = array('message' => $responseText);
        return response()->json($message);

    }
}
