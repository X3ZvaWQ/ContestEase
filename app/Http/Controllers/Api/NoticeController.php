<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Eloquent\Announcement;

class NoticeController extends Controller
{
    public function fetch()
    {
        return response()->json([
            'ret'  => 200,
            'desc' => 'successful',
            'data' => Announcement::fetch(),
        ]);
    }

    public function modify(Request $request)
    {
        $request->validate([
            'id'      => 'integer',
            'title'   => 'required|string',
            'content' => 'required|string',
        ]);
        $id = $request->input('id');
        $config = [
            'title'   => $request->input('title'),
            'content' => $request->input('content')
        ];
        if(!empty($id)){
            $config['id'] = $id;
        }
        Announcement::modify($config);
        return response()->json([
            'ret'   => 200,
            'desc'  => 'successful',
            'data'  => null
        ]);
    }
}
