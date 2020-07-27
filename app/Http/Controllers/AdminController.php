<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemManager;
class AdminController extends Controller
{

    function Login(Request $request){
        $message = '';
        $user = DB::table('user_access')
                    ->where('uname', $request->uname)
                    ->get();
        if ($user->count() > 0) {
            if (Hash::check($request->pass, $user[0]->pass)) {
                $message = 'success';
            }
            else{
                $message = 'Password Not Match';
            }
        }
        else{
            $message = 'Username not found.';
        }

        return ['message' => $message, 'user' => $user];
    }
    function GetBranch(){
        $branch = DB::table('branch')
                    ->select('branch.*')
                    ->get();
        return ['branch' => $branch];
    }
    function CreateBranch(Request $request){
        $message = '';
        $this->validate($request, [
            'name' => 'required',
            'longitude' => 'required',
            'latitude' => 'required'
        ]);
        $branch = DB::table('branch')
                    ->insert([
                        'name' => $request->name,
                        'longnitude' => $request->longitude,
                        'latitude' => $request->latitude
                    ]);
        if ($branch) {
            $message = 'success';
        }
        else{
            $message = 'Error Occured Try again later';
        }
        return ['message' => $message];
    }
    function UpdateBranch(Request $request){
        $message = '';
        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required'
        ]);
        $branch = DB::table('branch')
                    ->where('id', $request->id)
                    ->update([
                        'name' => $request->name,
                        'address' => $request->address,
                        'longitude' => $request->longitude,
                        'latitude' => $request->latitude
                    ]);
        if ($branch) {
            $message = 'success';
        }
        else{
            $message = 'Error Occured Try again later';
        }
        return ['message' => $message];
    }


    function GenerateKey(){
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = substr(str_shuffle($permitted_chars), 0, 16);

        $check = DB::table('employee')
                    ->where('emplo_key', $code)
                    ->get();

        if ($check->count() > 0) {
            $this->GenerateKey();
        }
        else{
            return $code;
        }
    }
    function GetEmplo(){
        $emplo = DB::table('employee')
                    ->join('branch', 'employee.branch_id', 'branch.id')
                    ->select('employee.*', 'branch.name')
                    ->get();

        return ['emplo' => $emplo];
    }
    function CreateEmplo(Request $request){
        $message = '';
        $this->validate($request, [
            'fullname' => 'required',
            'branch' => 'required',
            'image' => 'required'
        ]);

        $check = DB::table('employee')
                    ->where('fullname', $request->fullname)
                    ->get();

        $file = $request->image;
        $name = $request->fullname.$check->count().'.png';
        $fileBin = file_get_contents($file);
        if ( file_put_contents("./myfiles/EmployeeImage/".$name, $fileBin)) {
            $emplo = DB::table('employee')
                        ->insert([
                            'fullname' => $request->fullname,
                            'emplo_key' => $this->GenerateKey(),
                            'branch_id' => $request->branch,
                            'image' => $name
                        ]);

            if ($emplo) {
                $message = 'success';
            }
            else{
                $message = 'Somethings went wrong. Try again later';
            }
        }
        return ['message' => $message];
    }

    function GetEmployeeDetails($key){
        $message = '';
        $details = '';
        $check = DB::table('employee')
                    ->join('branch', 'employee.branch_id', 'branch.id')
                    ->select('employee.id as emploID','employee.fullname', 'branch.*')
                    ->where('emplo_key', $key)
                    ->get();

        if ($check->count() > 0) {
            $message = 'success';
            $details = $check[0];
        }
        else{
            $message = 'Employee Not Found';
        }

        return ['message' => $message, 'details' => $details];
    }
    function GetEmployeeLogs($id){
        $logs = DB::table('employee_log')
                    ->where('emplo_id', $id)
                    ->get();

        return ['logs' => $logs];
    }
    function EmployeeTimein(Request $request){
        $message = '';
        $logs = DB::table('employee_log')
                    ->where('emplo_id', $request->id)
                    ->where('date', $request->date)
                    ->where('time_in', '!=', '')
                    ->get();

        if ($logs->count() > 0) {
            $message = 'Already Time in';
        }
        else{
            $timein = DB::table('employee_log')
                        ->insert([
                            'emplo_id' => $request->id,
                            'date' => $request->date,
                            'time_in' => $request->time
                        ]);

            if ($timein) {
               $message = 'success';
            }
            else{
                $message = 'Somethings went wrong.';
            }
        }

        return ['message' => $message];
    }
    function EmployeeTimeout(Request $request){
        $message = '';
        $logs = DB::table('employee_log')
                    ->where('emplo_id', $request->id)
                    ->where('date', $request->date)
                    ->get();

        if ($logs->count() > 0) {
            $logs = DB::table('employee_log')
                    ->where('emplo_id', $request->id)
                    ->where('date', $request->date)
                    ->where('time_out', '!=' , '')
                    ->get();

            if ($logs->count() > 0) {
                $message = 'Already Logout';
            }
            else{
                $out =  DB::table('employee_log')
                        ->where('emplo_id', $request->id)
                        ->where('date', $request->date)
                        ->update([
                            'time_out' => $request->time
                        ]);

                if ($out) {
                    $message = 'success';
                }
                else{
                    $message = 'Somethings went wrong';
                }
            }
        }
        else{
            $message = 'Not Login';
        }

        return ['message' => $message];
    }
}
