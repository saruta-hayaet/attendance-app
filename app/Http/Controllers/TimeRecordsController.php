<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TimeRecord;
use Carbon\Carbon;

class TimeRecordsController extends Controller
{
    public function checkIn(Request $request)
    {
        $userId = auth()->id();

        /**
         * 打刻は1日一回までにしたい
         * DB
         */
        $oldTimestamp = TimeRecord::where('user_id', $userId)->latest()->first();
        if ($oldTimestamp) {
            $oldTimestampPunchIn = new Carbon($oldTimestamp->check_in);
            $oldTimestampDay = $oldTimestampPunchIn->startOfDay();
        } else {
            $timestamp = TimeRecord::create([
                'user_id' => $userId,
                'check_in' => Carbon::now(),
                'date' => today(),
            ]);

            return redirect()->back()->with('my_status', '出勤打刻が完了しました');

        }

        $newTimestampDay = Carbon::today();

        /**
         * 日付を比較する。同日付の出勤打刻で、かつ直前のTimestampの退勤打刻がされていない場合エラーを吐き出す。
         */
        if (($oldTimestampDay == $newTimestampDay) && (empty($oldTimestamp->check_out))){
            return redirect()->back()->with('error', 'すでに出勤打刻がされています');
        }

        $timestamp = TimeRecord::create([
            'user_id' => $userId,
            'check_in' => Carbon::now(),
            'date' => today(),
        ]);

        return redirect()->back()->with('my_status', '出勤打刻が完了しました');
    }

    public function checkOut()
    {
        $userId = auth()->id();
        $timestamp = TimeRecord::where('user_id', $userId)->latest()->first();

        if( !empty($timestamp->check_out)) {
            return redirect()->back()->with('error', '既に退勤の打刻がされているか、出勤打刻されていません');
        }
        $timestamp->update([
            'check_out' => Carbon::now()
        ]);

        return redirect()->back()->with('my_status', '退勤打刻が完了しました');
    }

    public function show()
    {
        $records = TimeRecord::where('user_id', Auth::id())->get();

        //出勤に数を計算
        $distinctDates = TimeRecord::where('user_id', Auth::id())
        ->whereNotNull('check_in') // 出勤しているレコードのみ
        ->distinct()
        ->get(['date']); // dateが各レコードの日付カラムです。

        $attendanceDaysCount = count($distinctDates);

        // 総労働時間を計算
        $totalMinutes = 0;

        $allRecords = TimeRecord::where('user_id', Auth::id())
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->get();

        foreach ($allRecords as $record) {
            $checkIn = new Carbon($record->check_in);
            $checkOut = new Carbon($record->check_out);

            $totalMinutes += $checkOut->diffInMinutes($checkIn);
        }

        // 分を時間と分に変換
        $totalHours = intdiv($totalMinutes, 60);
        $remainderMinutes = $totalMinutes % 60;

        return view('achievements', compact('records','attendanceDaysCount','totalHours','remainderMinutes'));
    }
}
