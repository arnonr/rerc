<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
const whitelist = ['127.0.0.1', "::1","localhost:8111"];

class EquipmentController extends Controller
{
    protected $uploadUrl = 'http://143.198.208.110:8111/storage/';

    public function getAll(Request $request)
    {
        if(in_array($_SERVER['HTTP_HOST'], whitelist)){
            $this->uploadUrl = 'http://localhost:8111/storage/';
        }

        $items = Equipment::select(
            'equipment.id',
            'equipment.name',
            'equipment.lab_room_id',
            DB::raw("(CASE WHEN equipment_file = NULL THEN 'http://localhost:8111/storage/equipment/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',equipment_file) END) AS equipment_file"),
            DB::raw("(CASE WHEN equipment_vdo_file = NULL THEN 'http://localhost:8111/storage/equipment/vdo/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',equipment_vdo_file) END) AS equipment_vdo_file"),
            'equipment.detail',
            'equipment.level',
            'equipment.is_publish as is_publish',
            'equipment.deleted_at as delete_at',
            'equipment.created_at as created_at',
            'equipment.created_by as created_by',
            'equipment.updated_at as updated_at',
            'equipment.updated_by as updated_by',
        )
        ->where('equipment.deleted_at', null)
        ->join('lab_room','lab_room.id','=','equipment.lab_room_id');

        if ($request->id) {
            $items->where('equipment.id',$request->id);
        }

        if ($request->name) {
            $items->where('equipment.name','LIKE',"%" . $request->name. "%");
        }

        if ($request->lab_room_id) {
            $items->where('equipment.lab_room_id',$request->lab_room_id);
        }

        if ($request->level) {
            $items->where('equipment.level',$request->level);
        }

        if ($request->is_publish) {
            $items->where('equipment.is_publish',$request->is_publish);
        }

        if($request->orderBy){
            $items = $items->orderBy($request->orderBy, $request->order);
        }else{
            $items = $items->orderBy('equipment.id', 'asc');
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

        $item = Equipment::select(
            'equipment.id',
            'equipment.name',
            'equipment.lab_room_id',
            DB::raw("(CASE WHEN equipment_file = NULL THEN 'http://localhost:8111/storage/equipment/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',equipment_file) END) AS equipment_file"),
            DB::raw("(CASE WHEN equipment_vdo_file = NULL THEN 'http://localhost:8111/storage/equipment/vdo/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',equipment_vdo_file) END) AS equipment_vdo_file"),
            'equipment.detail',
            'equipment.level',
            'equipment.is_publish as is_publish',
            'equipment.deleted_at as delete_at',
            'equipment.created_at as created_at',
            'equipment.created_by as created_by',
            'equipment.updated_at as updated_at',
            'equipment.updated_by as updated_by',
        )
        ->where('equipment.id', $id)
        ->join('lab_room','lab_room.id','=','equipment.lab_room_id')
        ->first();
        
        return response()->json([
            'message' => 'success',
            'data' => $item,
        ], 200);
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'lab_room_id' => 'required',
            'equipment_file' => 'required',
        ]);
        // 
        
        $pathEquipment = null;
        if(($request->equipment_file != "") && ($request->equipment_file != 'null')){
            $fileNameEquipment = 'equipment-'.rand(10,100).'-'.$request->equipment_file->getClientOriginalName();
            $pathEquipment = '/equipment/'.$fileNameEquipment;
            Storage::disk('public')->put($pathEquipment, file_get_contents($request->equipment_file));
        }else{
            return response()->json([
                'message' => 'error Not File Upload'
            ], 200);
        }

        $pathEquipmentVDO = null;
        if(($request->equipment_vdo_file != "") && ($request->equipment_vdo_file != 'null')){
            $fileNameEquipmentVDO = 'equipment-vdo-'.rand(10,100).'-'.$request->equipment_vdo_file->getClientOriginalName();
            $pathEquipmentVDO = '/equipment/vdo/'.$fileNameEquipmentVDO;
            Storage::disk('public')->put($pathEquipmentVDO, file_get_contents($request->equipment_vdo_file));
        }

        $level = 1;
        if(($request->level == null) || ($request->level == 'null')){
            $check_level = Equipment::where('lab_room_id',$request->lab_room_id)->max('level');
            if($check_level){
                $level = $check_level + 1;
            }
        }

        $item = new Equipment;
        $item->equipment_file = $pathEquipment;
        $item->equipment_vdo_file = $pathEquipmentVDO;
        $item->lab_room_id = $request->lab_room_id;
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
            'id' => 'required',
        ]);

        $id = $request->id;
        $item = Equipment::where('id', $id)->first();

        // TEST
        $pathEquipment = null;
        if(($request->equipment_file != "") && ($request->equipment_file != 'null')){
            $fileNameEquipment = 'equipment-'.rand(10,100).'-'.$request->equipment_file->getClientOriginalName();
            $pathEquipment = '/equipment/'.$fileNameEquipment;
            Storage::disk('public')->put($pathEquipment, file_get_contents($request->equipment_file));
        }else{
            $pathEquipment = $item->equipment_file;
        }

        $pathEquipmentVDO = null;
        if(($request->equipment_vdo_file != "") && ($request->equipment_vdo_file != 'null')){
            $fileNameEquipmentVDO = 'equipment-vdo-'.rand(10,100).'-'.$request->equipment_vdo_file->getClientOriginalName();
            $pathEquipmentVDO = '/equipment/vdo/'.$fileNameEquipmentVDO;
            Storage::disk('public')->put($pathEquipmentVDO, file_get_contents($request->equipment_vdo_file));
        }else{
            $pathEquipmentVDO = $item->equipment_vdo_file;
        }

        $item->equipment_file = $pathEquipment;
        $item->equipment_vdo_file = $pathEquipmentVDO;
        $item->name = isset($request->name) ? $request->name : $item->name;
        $item->lab_room_id = isset($request->lab_room_id) ? $request->lab_room_id : $item->lab_room_id;
        $item->detail = isset($request->detail) ? $request->detail : $item->detail;
        $item->is_publish = isset($request->is_publish) ? $request->is_publish : $item->is_publish;
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
        $item = Equipment::where('id', $id)->first();

        $item->level = null;
        $item->is_publish = 0;
        $item->deleted_at = Carbon::now();
        $item->save();

        // เรียงลำดับใหม่
        $items = Equipment::where('deleted_at', null)->where('lab_room_id', $item->lab_room_id)->orderBy('level', 'asc')->get();

        $i = 1; 
        foreach($items as $it){
            $it->level = $i;
            $i++;
            $it->save();
        }

        $responseData = [
            'message' => 'success'
        ];

        return response()->json($responseData, 200);
    }

    public function editLevel($id, Request $request)
    {
        $request->validate([
            'id as required',
            'type as required',
        ]);

        $id = $request->id;
        $type = $request->type;

        $item = Equipment::where('id', $id)->first();

        $item1 = null;
        if($type == 'IC'){
            $item1 = Equipment::where('level', $item->level + 1)->where('lab_room_id', $item->lab_room_id)->first();
        }

        if($type == 'DC'){
            $item1 = Equipment::where('level', $item->level - 1)->where('lab_room_id', $item->lab_room_id)->first();
        }

        if($item1 != null){
            $level = $item1->level;
            $level1 = $item->level;

            $item->level = $level;
            $item->save();

            $item1->level = $level1;
            $item1->save();
        }

        $responseData = [
            'message' => 'success',
            // 'data' => $item,
        ];
        
        return response()->json($responseData, 200);
    }
}
