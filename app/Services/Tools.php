<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Tools
{
    public function onSave($table, $req, $id = "", $routerName)
    {
        $status = $id ? "Update success." : "Create success.";
        DB::beginTransaction();
        try {
            $item = $req->all();
            $table::updateOrCreate(['id' => $id], $item);
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-'.$routerName.'-list', 1);
        } catch (\Exception $error) {
            DB::rollback();
            Session::flash('warning', $status);
            return redirect()->back();
        }
    }
    public function onRestore($table,$id)
    {
        try {
            DB::beginTransaction();
            $table::withTrashed()->where('id', $id)->restore();
            DB::commit();
            Session::flash('success', 'Restore success!');
            return response()->json([
                'message'=>'success',
                'status'=>200
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Move to restore unsuccess!');
            return response()->json([
                'message'=>'unsuccess',
                'status'=>404,
                'error'=>$e
            ]);
        }
    }
    public function onDelete($table,$id){
        DB::beginTransaction();
        try {
            $table::where('id', $id)->delete();
            DB::commit();
            Session::flash('success', 'Delete success!');
            return response()->json([
                'message'=>'success',
                'status'=>200
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Delete unsuccess!');
            return response()->json([
                'message'=>'unsuccess',
                'status'=>404,
                'error'=>$e
            ]);
        }
    }
    public function onDestroy($table,$id){
        DB::beginTransaction();
        try {
            $table::where('id', $id)->forceDelete();
            DB::commit();
            Session::flash('success', 'Delete success!');
            return response()->json([
                'message'=>'success',
                'status'=>200
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Delete unsuccess!');
            return response()->json([
                'message'=>'unsuccess',
                'status'=>404,
                'error'=>$e
            ]);
        }
    }
}
