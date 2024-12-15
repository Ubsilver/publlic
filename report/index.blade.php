@extends("dashboard")

@section('content')

@if (auth()->user()->role !== 'admin')
<div class="mt-10">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Написать заявление</h2>
    <form method="POST" action="{{ route('reports.store') }}" class='bg-white shadow-md rounded-lg p-4 flex justify-between border-2 border-gray-300"space-y-4'>
        @csrf
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Описание нарушения</label>
            <textarea id="description" name="description" rows="4" class="w-full min-w-[760px] mt-1 border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Описание" required></textarea>
        </div>
        <div>
            <div>
                <label for="number" class="block text-sm font-medium text-gray-700">Номер нарушившего авто</label>
                <input id="number" name="number" class=" min-w-[300px] w-full mt-1 border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" placeholder="AA999A" required />
            </div>

            <div class="text-right">
                <button type="submit" class="min-w-[300px] mt-8 bg-blue-500 text-white px-20 py-2 mb-4 rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out">
                    Создать
                </button>
            </div>
        </div>

    </form>
</div>
@endif
<div class="container mx-auto p-10">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Все заявления</h1>

    <div class="space-y-4">
        @foreach($reports as $report)
        <div class="bg-white shadow-md rounded-lg p-4 flex justify-between items-center border-2 border-gray-300">
            <div>
                <div class="flex row">
                    <p class="text-sm font-semibold mr-8">Заявление от: {{ $report->created_at->format('d.m.Y') }}</p>
                    @if (auth()->user()->role === 'admin')
                    <p class="text-sm font-semibold">Пользователь: {{ $report->user->name }} {{ $report->user->middlename }}</p>
                    @endif
                </div>
                <p class="text-lg font-semibold mt-2"> Номер авто: {{ $report->number }}</p>
                <p class="text-lg mt-2">{{ $report->description }}</p>
            </div>
            <div class="flex row">
                @if (auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('report.update-status', $report->id) }}">
                    @csrf
                    @method('PUT')
                    <select name="status_id" class="border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500 pr-10">
                        <option value="1" @if ($report->status_id === 1) selected @endif>новое</option>
                        <option value="2" @if ($report->status_id === 2) selected @endif>отклонено</option>
                        <option value="3" @if ($report->status_id === 3) selected @endif>принято</option>
                    </select>
                    <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-200 ease-in-out">
                        Сохранить
                    </button>

                </form>
                @if (auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('reports.destroy', $report->id) }}" class="inline">
                    @csrf
                    @method('delete')
                    <button type="submit" class="text-red-500 border ml-3 py-2 px-6 rounded-lg border-gray-300 hover:border-red-500"><x-eos-delete-forever class="h-6" />
                    </button>
                </form>
                @endif
                @else
                @if ($report->status_id === 1) <span class="text-black font-bold mr-8">новое</span>
                @elseif ($report->status_id === 2) <span class="text-red-500 font-bold mr-8">отклонено</span>
                @elseif ($report->status_id === 3) <span class="text-green-500 font-bold mr-8">принято</span>
                @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>


</div>
@endsection
@include('layouts.flash-messages')