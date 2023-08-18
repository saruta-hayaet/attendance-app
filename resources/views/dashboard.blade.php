<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('打刻') }}
        </h2>
    </x-slot>

    @if(session('my_status'))
        <script>
            alert('{{ session('my_status') }}');
        </script>
    @endif
    @if(session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="current-time" id="current-time"></div>
            <div class="form-block">
                <form action="{{ route('time_records.check_in') }}" method="post">
                    @csrf
                    <button class="p-6 text-gray-100 dark:text-gray-50 button">
                        出勤
                    </button>
                </form>
                <form action="{{ route('time_records.check_out') }}" method="post">
                    @csrf
                    <button class="p-6 text-gray-100 dark:text-gray-50 button">
                        退勤
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function updateCurrentTime() {
            const now = new Date();
            const formattedTime = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
            document.getElementById('current-time').innerText = formattedTime;
        }
        setInterval(updateCurrentTime, 1000); // 1秒ごとに時間を更新
    </script>
</x-app-layout>
