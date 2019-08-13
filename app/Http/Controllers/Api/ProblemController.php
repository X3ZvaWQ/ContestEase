<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Eloquent\Problem;

class ProblemController extends Controller
{
    public function list()
    {
        return response()->json([
            'ret'  => 200,
            'desc' => 'successful',
            'data' => Problem::fetch(),
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
        $options = $request->input('options');
        $config = [
            'title'   => $request->input('title'),
            'content' => $request->input('content')
        ];
        if(!empty($id)){
            $config['id'] = $id;
        }
        if(!empty($options)){
            $config['options'] = $options;
        }
        Problem::modify($config);
        return response()->json([
            'ret'   => 200,
            'desc'  => 'successful',
            'data'  => null
        ]);
    }

    public function modifyMassively(Request $request)
    {
        $request->validate([
            'questions.title'   => 'required|string',
            'questions.content' => 'required|string',
        ]);
        foreach ($request->input('questions') as $question) {
            $config = [
                'title'   => $question['title'],
                'content' => $question['content'],
                'options' => $question['options'],
            ];
            Problem::modify($config);
        }
        return response()->json([
            'ret'   => 200,
            'desc'  => 'successful',
            'data'  => null
        ]);
    }

    public function addSource(Request $request)
    {
        $request->validate([
            'id'      => 'required|integer',
            'type'    => 'required|string',
            'name'    => 'required|string',
            'source'  => 'required|file'
        ]);
        $type = $request->input('type');
        if(!in_array($type,['image','attachment'])){
            return response()->json([
                'ret'   => 1000,
                'desc'  => 'Resource of unknown type.',
                'data'  => null
            ]);
        }
        $problem = Problem::find($request->input('id'));
        if(empty($problem)){
            return response()->json([
                'ret'   => 404,
                'desc'  => 'Problem Not Found.',
                'data'  => null
            ]);
        }
        if($type == 'image'){
            //image
            $file = $request->file('source');
            if(!$file->isValid() || !in_array($file->extension(),['jpg','jpeg','png','bmp','gif'])){
                return response()->json([
                    'ret'   => 1001,
                    'desc'  => 'The uploaded file is invalid',
                    'data'  => null
                ]);
            }
            $path = '/'.$file->store('image','static');
            $problem->images()->create([
                'name'       => $request->input('name'),
                'url'        => $path,
            ]);
            return response()->json([
                'ret'   => 200,
                'desc'  => 'successful',
                'data'  => null
            ]);
        }else{
            //attachment
            $file = $request->file('source');
            if(!$file->isValid()){
                return response()->json([
                    'ret'   => 1001,
                    'desc'  => 'The uploaded file is invalid',
                    'data'  => null
                ]);
            }
            $path = '/'.$file->store('attachment','static');
            $problem->attachments()->create([
                'name'       => $request->input('name'),
                'url'        => $path,
            ]);
            return response()->json([
                'ret'   => 200,
                'desc'  => 'successful',
                'data'  => null
            ]);
        }
    }

    public function deleteSource(Request $request)
    {
        $request->validate([
            'id'      => 'required|integer',
            'url'     => 'required|string'
        ]);
        $problem = Problem::find($request->input('id'));
        if(empty($problem)){
            return response()->json([
                'ret'   => 404,
                'desc'  => 'Problem Not Found.',
                'data'  => null
            ]);
        }
        $images = $problem->images()->where('url',$request->input('url'));
        if(!empty($images)){
            $old_path = $images->url;
            Storage::disk('static')->delete($old_path);
            $images->delete();
        }
        $attachments = $problem->attachments()->where('url',$request->input('url'));
        if(!empty($attachments)){
            $old_path = $attachments->url;
            Storage::disk('static')->delete($old_path);
            $attachments->delete();
        }
        return response()->json([
            'ret'   => 200,
            'desc'  => 'successful',
            'data'  => null
        ]);
    }
}
