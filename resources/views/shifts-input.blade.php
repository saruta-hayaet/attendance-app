<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('シフト入力') }}
        </h2>
    </x-slot>

    <div class="shift-form">
        <form class="form-main" action="{{ route ('shifts_input.store') }}" method="post">
            @csrf
            <div class="input-line">
                <label>シフト日:</label>
                <input type="date" name="shift_date" required>
            </div>
            <div class="input-line">
                <label>開始時間:</label>
                <input type="time" name="start_time" required>
            </div>
            <div class="input-line">
                <label>終了時間:</label>
                <input type="time" name="end_time" required>
            </div>
            <div class="shift-button">
                <button type="submit">シフトを保存</button>
            </div>
        </form>
    </div>

</x-app-layout>


