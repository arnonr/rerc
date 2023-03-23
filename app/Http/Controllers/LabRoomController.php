<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabRoom;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
const whitelist = ['127.0.0.1', "::1","localhost:8111"];

class LabRoomController extends Controller
{
    protected $uploadUrl = 'http://143.198.208.110:8111/storage/';

    public function getAll(Request $request)
    {
        if(in_array($_SERVER['HTTP_HOST'], whitelist)){
            $this->uploadUrl = 'http://localhost:8111/storage/';
        }

        $items = LabRoom::select(
            'id',
            'name',
            DB::raw("(CASE WHEN lab_room_file = NULL THEN 'http://localhost:8111/storage/lab_room/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',lab_room_file) END) AS lab_room_file"),
            'detail',
            'level',
            'is_publish as is_publish',
            'deleted_at as delete_at',
            'created_at as created_at',
            'created_by as created_by',
            'updated_at as updated_at',
            'updated_by as updated_by',
        )
        ->where('deleted_at', null);
        

        if ($request->id) {
            $items->where('id',$request->id);
        }

        if ($request->level) {
            $items->where('level',$request->level);
        }

        if ($request->is_publish) {
            $items->where('is_publish',$request->is_publish);
        }

        if($request->orderBy){
            $items = $items->orderBy($request->orderBy, $request->order);
        }else{
            $items = $items->orderBy('id', 'asc');
        }

        $count = $items->count();
        $perPage = $request->perPage ? $request->perPage : 10;
        $currentPage = $request->currentPage ? $request->currentPage : 1;

        $totalPage = ceil($count /$perPage) == 0 ? 1 : ceil($count / $perPage);
        $offset = $perPage * ($currentPage - 1);
        $items = $items->skip($offset)->take($perPage);
        $items = $items->get();

        return response()->json([
            'message' => 'success',
            'data' => $items,
            'totalPage' => $totalPage,
            'totalData' => $count,
        ], 200);
    }

    public function get($id)
    {
        if(in_array($_SERVER['HTTP_HOST'], whitelist)){
            $this->uploadUrl = 'http://localhost:8111/storage/';
        }

        $item = LabRoom::select(
            'id',
            'name',
            DB::raw("(CASE WHEN lab_room_file = NULL THEN 'http://localhost:8111/storage/lab_room/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',lab_room_file) END) AS lab_room_file"),
            'detail',
            'level',
            'is_publish as is_publish',
            'deleted_at as delete_at',
            'created_at as created_at',
            'created_by as created_by',
            'updated_at as updated_at',
            'updated_by as updated_by',
        )
        ->where('id', $id)
        ->first();
        
        return response()->json([
            'message' => 'success',
            'data' => $item,
        ], 200);
    }

    public function add(Request $request)
    {
        $request->validate([
            'name as required',
            'lab_room_file as required',
        ]);

        $pathLabRoom = null;
        if(($request->lab_room_file != "") && ($request->lab_room_file != 'null')){
            $fileNameLabRoom = 'lab_room-'.rand(10,100).'-'.$request->lab_room_file->getClientOriginalName();
            $pathLabRoom = '/slide/'.$fileNameLabRoom;
            Storage::disk('public')->put($pathLabRoom, file_get_contents($request->lab_room_file));
        }else{
            return response()->json([
                'message' => 'error Not File Upload'
            ], 200);
        }

        $level = 1;
        if($request->level == null){
            $check_level = LabRoom::max('level')->first();
            if($check_level){
                $level = $check_level + 1;
            }
        }

        $item = new LabRoom;
        $item->lab_room_file = $pathLabRoom;
        $item->name = $request->name;
        $item->detail = $request->detail;
        $item->level = $level;
        $item->is_publish = $request->is_publish;
        $item->created_by = 'arnonr';
        $item->save();

        $responseData = [
            'message' => 'success',
            'data' => $item,
        ];
        
        return response()->json($responseData, 200);
    }

    public function edit($id, Request $request)
    {
        $request->validate([
            'id as required',
            'name as required',
            'lab_room_file as required',
        ]);

        $id = $request->id;
        $item = LabRoom::where('id', $id)->first();

        // TEST
        $pathLabRoom = null;
        if(($request->lab_room_file != "") && ($request->lab_room_file != 'null')){
            $fileNameLabRoom = 'lab_room-'.rand(10,100).'-'.$request->lab_room_file->getClientOriginalName();
            $pathLabRoom = '/slide/'.$fileNameLabRoom;
            Storage::disk('public')->put($pathLabRoom, file_get_contents($request->lab_room_file));
        }else{
            $pathLabRoom = $item->lab_room_file;
        }

        $level = 1;
        if($request->level == null){
            $check_level = LabRoom::max('level')->first();
            if($check_level){
                $level = $check_level + 1;
            }
        }

        $item->lab_room_file = $pathLabRoom;
        $item->name = $request->name;
        $item->detail = $request->detail;
        $item->level = $level;
        $item->is_publish = $request->is_publish;
        $item->updated_by = 'arnonr';
        $item->save();

        $responseData = [
            'message' => 'success',
            'data' => $item,
        ];
        
        return response()->json($responseData, 200);
    }

    public function delete($id)
    {
        $item = LabRoom::where('id', $id)->first();

        $item->deleted_at = Carbon::now();
        $item->save();

        $responseData = [
            'message' => 'success'
        ];

        return response()->json($responseData, 200);
    }
}
