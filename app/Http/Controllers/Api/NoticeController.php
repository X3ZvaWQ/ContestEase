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
        if($request->has('id')){
            if($request->has('title') && $request->has('content')){
                //modify
                $config = [
                    'id'      => $request->input('id'),
                    'title'   => $request->input('title'),
                    'content' => $request->input('content')
                ];
                Announcement::modify($config);
                return response()->json([
                    'ret'   => 200,
                    'desc'  => 'successful',
                    'data'  => null
                ]);
            }else{
                //delete
                $notice = Announcement::find($request->input('id'));
                if(!empty($notice)){
                    $notice->delete();
                }
                return response()->json([
                    'ret'   => 200,
                    'desc'  => 'successful',
                    'data'  => null
                ]);
            }
        }else{
            if($request->has('title') && $request->has('content')){
                //create
                $config = [
                    'title'   => $request->input('title'),
                    'content' => $request->input('content')
                ];
                Announcement::modify($config);
                return response()->json([
                    'ret'   => 200,
                    'desc'  => 'successful',
                    'data'  => null
                ]);
            }else{
                //error
                return response()->json([
                    'ret'   => 1003,
                    'desc'  => 'Invalid Params',
                    'data'  => null,
                ]);
            }
        }
    }
}
