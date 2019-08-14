<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Eloquent\Problem;
use App\Models\Eloquent\Image;
use App\Models\Eloquent\Attachment;
use Storage;

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
        if($request->has('id')){
            if($request->has('title') && $request->has('content')){
                //modify
                $config = [
                    'id'      => $request->input('id'),
                    'title'   => $request->input('title'),
                    'content' => $request->input('content'),
                    'options' => $request->input('options',[])
                ];
                Problem::modify($config);
                return response()->json([
                    'ret'   => 200,
                    'desc'  => 'successful',
                    'data'  => null,
                ]);
            }else{
                //delete
                $problem = Problem::find($request->input('id'));
                if(!empty($problem)){
                    $problem->options()->delete();
                    $problem->images()->delete();
                    $problem->attachments()->delete();
                    $problem->delete();
                    return response()->json([
                        'ret'   => 200,
                        'desc'  => 'successful',
                        'data'  => null,
                    ]);
                }else{
                    return response()->json([
                        'ret'   => 404,
                        'desc'  => 'Problem Not Found',
                        'data'  => null,
                    ]);
                }
            }
        }else{
            if($request->has('title') && $request->has('content')){
                //add
                $config = [
                    'title'   => $request->input('title'),
                    'content' => $request->input('content'),
                    'options' => $request->input('options',[])
                ];
                Problem::modify($config);
                return response()->json([
                    'ret'   => 200,
                    'desc'  => 'successful',
                    'data'  => null,
                ]);
            }else{
                return response()->json([
                    'ret'   => 1003,
                    'desc'  => 'Invalid Params',
                    'data'  => null,
                ]);
            }
        }
    }

    public function modifyMassively(Request $request)
    {
        if(is_array($request->input('questions'))){
            foreach ($request->input('questions') as $question) {
                if(!empty($question['title']) && !empty($question['content'])){
                    $config = [
                        'title'   => $question['title'],
                        'content' => $question['content'],
                        'options' => $question['options'],
                    ];
                    Problem::modify($config);
                }
            }
            return response()->json([
                'ret'   => 200,
                'desc'  => 'successful',
                'data'  => null
            ]);
        }else{
            return response()->json([
                'ret'   => 1003,
                'desc'  => 'error parms',
                'data'  => null
            ]);
        }

    }

    public function addSource(Request $request)
    {
        if(!$request->has('problem_id') || !$request->has('type') || !$request->has('name') || !$request->has('source')){
            return response()->json([
                'ret'   => 1004,
                'desc'  => 'Params Missings.',
                'data'  => null
            ]);
        }
        $type = $request->input('type');
        if(!in_array($type,['image','attachment'])){
            return response()->json([
                'ret'   => 1000,
                'desc'  => 'Resource of unknown type.',
                'data'  => null
            ]);
        }
        $problem = Problem::find($request->input('problem_id'));
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
            $path = '/static/'.$file->store('image','static');
            $problem->images()->create([
                'name'       => $request->input('name'),
                'url'        => $path,
            ]);
            return response()->json([
                'ret'   => 200,
                'desc'  => 'successful',
                'data'  => [
                    'url' => $path,
                ]
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
            $path = '/static/'.$file->store('attachment','static');
            $problem->attachments()->create([
                'name'       => $request->input('name'),
                'url'        => $path,
            ]);
            return response()->json([
                'ret'   => 200,
                'desc'  => 'successful',
                'data'  => [
                    'url' => $path,
                ]
            ]);
        }
    }

    public function deleteSource(Request $request)
    {
        if(!$request->has('url')){
            return response()->json([
                'ret'   => 1004,
                'desc'  => 'Params Missings.',
                'data'  => null
            ]);
        }
        $images = Image::where('url',$request->input('url'))->first();
        if(!empty($images)){
            $old_path = $images->url;
            Storage::disk('static')->delete($old_path);
            $images->delete();
        }
        $attachments = Attachment::where('url',$request->input('url'))->first();
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
