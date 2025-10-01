<div class="container mx-auto">
    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Student Search</h1>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search students..." class="px-4 py-2 border rounded-md">
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full bg-white">
            <thead class="bg-green-800 text-white">
                <tr>
                    <th class="w-1/3 text-right py-3 px-4 uppercase font-semibold text-sm">الاسم</th>
                    <th class="w-1/3 text-right py-3 px-4 uppercase font-semibold text-sm">الرقم القومي</th>
                    <th class="text-right py-3 px-4 uppercase font-semibold text-sm">المكان المخصص</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach($students as $student)
                    <tr>
                        <td class="w-1/3 text-right py-3 px-4">{{ $student->name }}</td>
                        <td class="w-1/3 text-right py-3 px-4">{{ $student->national_id }}</td>
                        <td class="text-right py-3 px-4">
                            <select wire:change="updatePlace({{ $student->id }}, $event.target.value)" class="block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="none" @if(!$student->place) selected @endif>-- بدون تسكين --</option>
                                @foreach($halls as $hall)
                                    <option value="{{ $hall->name }}" @if($student->place == $hall->name) selected @endif>{{ $hall->name }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>
