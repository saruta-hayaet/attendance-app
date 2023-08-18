<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift;
use Carbon\Carbon;

class ShiftsController extends Controller
{
    //
    public function create()
    {
        return view('shifts-input');
    }

    public function store(Request $request)
    {
        Shift::create([
            'user_id' =>auth()->user()->id,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
            'date' => $request->shift_date,
        ]);

        return view('shifts-input');
    }

    public function show()
    {
        $shifts = Shift::where('user_id', Auth::id())->orderBy('date', 'asc')->get();


        //出勤に数を計算
        $distinctDates = Shift::where('user_id', Auth::id())
        ->whereNotNull('start_time') // 出勤しているレコードのみ
        ->distinct()
        ->get(['date']); // dateが各レコードの日付カラムです。

        $attendanceDaysCount = count($distinctDates);

        // 総労働時間を計算
        $totalMinutes = 0;

        $allRecords = Shift::where('user_id', Auth::id())
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

        return view('shifts-show', compact('shifts','attendanceDaysCount','totalHours','remainderMinutes'));
    }
}
