<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(Auth::check()){
            $limit = isset($request->limit) ? $request->limit : 10;
            $result = Contact::paginate($limit);
            return response()->json($result, 200);
        }
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
        $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email:rfc,dns',
            'phone_number' => 'required|between:12,15',
            'message' => 'required|min:3|max:255'
        ]);

        $result = Contact::create($request->all());
        if (isset($result)) {
            $message = array('message' => 'Form Filled Up Successfully', 'result' => $result);
            $statusCode = 200;
        } else {
            $message = array('message' => 'Something Went wrong');
            $statusCode = 500;
        }
        return response()->json($message, $statusCode);
    }

}
