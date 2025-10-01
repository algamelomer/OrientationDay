@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">لوحة التحكم</h1>
            <div>
                <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-indigo-600 mx-2">الصفحة الرئيسية</a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="text-gray-600 hover:text-indigo-600 mx-2">
                    تسجيل الخروج
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if($errors->any())
             <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow" role="alert">
                <p class="font-bold">يرجى إصلاح الأخطاء التالية:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-xl p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-600">إجمالي المسجلين</h3>
                <p class="text-4xl font-bold text-indigo-600 mt-2">{{ $stats['registered'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-xl p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-600">تم تسكينهم</h3>
                <p class="text-4xl font-bold text-green-600 mt-2">{{ $stats['assigned'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-xl p-6 text-center">
                <h3 class="text-lg font-semibold text-gray-600">لم يتم تسكينهم</h3>
                <p class="text-4xl font-bold text-yellow-600 mt-2">{{ $stats['unassigned'] }}</p>
            </div>
        </div>

        <!-- Hall Stats -->
        <div class="bg-white rounded-lg shadow-xl p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700 border-b pb-3">إحصائيات القاعات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pt-4">
                @forelse($halls as $hall)
                     <div class="bg-gray-50 rounded-lg p-4 text-center border">
                        <h4 class="text-lg font-semibold text-gray-700">{{ $hall->name }}</h4>
                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $hallStats[$hall->name] ?? 0 }} / <span class="text-xl text-gray-500">{{ $hall->capacity }}</span>
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">لا توجد قاعات معرفة حالياً.</p>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Hall Management -->
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700 border-b pb-3">إدارة القاعات</h2>
                    <!-- Existing Halls -->
                    <div class="space-y-4 mb-6">
                        @foreach($halls as $hall)
                            <form action="{{ route('halls.update', $hall) }}" method="POST" class="bg-gray-50 p-4 rounded-lg border">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                    <input type="text" name="name" value="{{ $hall->name }}" class="md:col-span-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="اسم القاعة">
                                    <input type="text" name="timing" value="{{ $hall->timing }}" class="md:col-span-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="التوقيت">
                                    <input type="number" name="capacity" value="{{ $hall->capacity }}" class="md:col-span-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="السعة">
                                    <div class="flex items-center space-x-2">
                                        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">حفظ</button>
                                        <button type="button" onclick="document.getElementById('delete-hall-{{$hall->id}}').submit();" class="w-full bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">حذف</button>
                                    </div>
                                </div>
                            </form>
                            <form id="delete-hall-{{$hall->id}}" action="{{ route('halls.destroy', $hall) }}" method="POST" class="hidden" onsubmit="return confirm('هل أنت متأكد من حذف هذه القاعة؟');">
                                @csrf
                            </form>
                        @endforeach
                    </div>
                    <!-- Add New Hall -->
                    <h3 class="text-xl font-semibold mb-3 text-gray-600">إضافة قاعة جديدة</h3>
                    <form action="{{ route('halls.store') }}" method="POST" class="bg-gray-50 p-4 rounded-lg border">
                         @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <input type="text" name="name" class="md:col-span-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="اسم القاعة" required>
                            <input type="text" name="timing" class="md:col-span-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="التوقيت (مثال: 10ص - 11ص)" required>
                            <input type="number" name="capacity" class="md:col-span-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="السعة" required>
                            <button type="submit" class="w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600">إضافة</button>
                        </div>
                    </form>
                </div>

                <!-- Students Table -->
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700 border-b pb-3">الطلاب المسجلون</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 px-4">الاسم</th>
                                    <th class="py-2 px-4">الرقم القومي</th>
                                    <th class="py-2 px-4">المكان المخصص</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $student->name }}</td>
                                        <td class="py-2 px-4">{{ $student->national_id }}</td>
                                        <td class="py-2 px-4">
                                            <form action="{{ route('student.update', $student) }}" method="POST">
                                                @csrf
                                                <div class="flex items-center">
                                                    <select name="place" class="block w-full rounded-md border-gray-300 shadow-sm text-sm" onchange="this.form.submit()">
                                                        <option value="none" @if(!$student->place) selected @endif>-- بدون تسكين --</option>
                                                        @foreach($halls as $hall)
                                                            <option value="{{ $hall->name }}" @if($student->place == $hall->name) selected @endif>{{ $hall->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-gray-500">لا يوجد طلاب مسجلون حالياً.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>

            <!-- Settings & Import Column -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Settings Card -->
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700 border-b pb-3">الإعدادات العامة</h2>
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="whatsapp_group_link" class="block font-medium text-gray-700">رابط مجموعة الواتساب</label>
                                <input type="url" name="whatsapp_group_link" value="{{ old('whatsapp_group_link', $settings['whatsapp_group_link'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">حفظ الإعدادات</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Importer Card -->
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-700 border-b pb-3">استيراد الطلاب</h2>
                    <form action="{{ route('dashboard.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                        <button type="submit" class="mt-4 w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600">استيراد الملف</button>
                    </form>
                </div>

                <!-- Drop Students Card -->
                 <div class="bg-white rounded-lg shadow-xl p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-red-700 border-b border-red-200 pb-3">منطقة الخطر</h2>
                    <form action="{{ route('dashboard.drop') }}" method="POST" onsubmit="return confirm('تحذير! سيتم حذف جميع بيانات الطلاب بشكل نهائي. هل أنت متأكد؟');">
                        @csrf
                        <p class="text-gray-600 mb-4">بالضغط على هذا الزر سيتم حذف جميع الطلاب المسجلين في قاعدة البيانات.</p>
                        <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">حذف جميع الطلاب</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

