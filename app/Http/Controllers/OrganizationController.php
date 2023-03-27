<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
const whitelist = ['127.0.0.1', "::1","localhost:8111"];

class OrganizationController extends Controller
{
    protected $uploadUrl = 'http://143.198.208.110:8111/storage/';

    public function getAll(Request $request)
    {
        if(in_array($_SERVER['HTTP_HOST'], whitelist)){
            $this->uploadUrl = 'http://localhost:8111/storage/';
        }

        $items = Organization::select(
            'organization.id',
            DB::raw("(CASE WHEN organization_file = NULL THEN 'http://localhost:8111/storage/organization/scg.png'
            ELSE CONCAT('".$this->uploadUrl."', organization_file) END) AS organization_file"),
            'organization.is_publish as is_publish',
            'organization.deleted_at as delete_at',
            'organization.created_at as created_at',
            'organization.created_by as created_by',
            'organization.updated_at as updated_at',
            'organization.updated_by as updated_by',
        )
        ->where('organization.deleted_at', null);

        if ($request->id) {
            $items->where('organization.id',$request->id);
        }

        if ($request->is_publish) {
            $items->where('organization.is_publish',$request->is_publish);
        }

        if($request->orderBy){
            $items = $items->orderBy($request->orderBy, $request->order);
        }else{
            $items = $items->orderBy('organization.id', 'asc');
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

        $item = Organization::select(
            'organization.id',
            DB::raw("(CASE WHEN organization_file = NULL THEN 'http://localhost:8111/storage/organization/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',organization_file) END) AS organization_file"),
            'organization.is_publish as is_publish',
            'organization.deleted_at as delete_at',
            'organization.created_at as created_at',
            'organization.created_by as created_by',
            'organization.updated_at as updated_at',
            'organization.updated_by as updated_by',
        )
        ->where('organization.id', $id)
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
            'organization_file' => 'required',
        ]);
        // 
        
        $pathOrganization = null;
        if(($request->organization_file != "") && ($request->organization_file != 'null')){
            $fileNameOrganization = 'organization-'.rand(10,100).'-'.$request->organization_file->getClientOriginalName();
            $pathOrganization = '/organization/'.$fileNameOrganization;
            Storage::disk('public')->put($pathOrganization, file_get_contents($request->organization_file));
        }else{
            return response()->json([
                'message' => 'error Not File Upload'
            ], 200);
        }

        $item = new Organization;
        $item->organization_file = $pathOrganization;
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
        $item = Organization::where('id', $id)->first();

        // TEST
        $pathOrganization = null;
        if(($request->organization_file != "") && ($request->organization_file != 'null')){
            $fileNameOrganization = 'organization-'.rand(10,100).'-'.$request->organization_file->getClientOriginalName();
            $pathOrganization = '/organization/'.$fileNameOrganization;
            Storage::disk('public')->put($pathOrganization, file_get_contents($request->organization_file));
        }else{
            $pathOrganization = $item->organization_file;
        }


        $item->organization_file = $pathOrganization;
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
        $item = Organization::where('id', $id)->first();

        $item->is_publish = 0;
        $item->deleted_at = Carbon::now();
        $item->save();

        $responseData = [
            'message' => 'success'
        ];

        return response()->json($responseData, 200);
    }
}
