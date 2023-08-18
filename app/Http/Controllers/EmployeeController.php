<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;

class EmployeeController extends Controller
{
    //
    public function index()
    {
        $employees = User::all();

        return view('employee',compact('employees'));
    }

    public function show($id)
    {
        $user = User::find($id);
        $shifts = Shift::where('user_id', $id)->orderBy('date', 'asc')->get();

        //出勤に数を計算
        $distinctDates = Shift::where('user_id', $id)
        ->whereNotNull('start_time') // 出勤しているレコードのみ
        ->distinct()
        ->get(['date']); // dateが各レコードの日付カラムです。

        $attendanceDaysCount = count($distinctDates);

        // 総労働時間を計算
        $totalMinutes = 0;

        $allRecords = Shift::where('user_id', $id)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->get();

        foreach ($allRecords as $record) {
            $checkIn = new Carbon($record->start_time);
            $checkOut = new Carbon($record->end_time);

            $totalMinutes += $checkOut->diffInMinutes($checkIn);
        }

        // 分を時間と分に変換
        $totalHours = intdiv($totalMinutes, 60);
        $remainderMinutes = $totalMinutes % 60;

        return view('employee-shift', compact('user','shifts','attendanceDaysCount','totalHours','remainderMinutes'));
    }
}
