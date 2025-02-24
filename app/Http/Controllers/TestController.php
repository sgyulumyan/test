<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

use App\Enums\TestsStatusEnum; 

use App\Models\Test;
use App\Models\Settings;

class TestController extends Controller
{
    // Отображение списка записей
    public function index(): Response
    {
        return Inertia::render('Tests/Index', [
            'tests' => Test::withoutGlobalScope('allowed')
                ->filter(Request::only('search', 'status'))                
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($test) => [
                    'id' => $test->id,
                    'name' => $test->name,
                    'phone' => $test->phone,
                    'email' => $test->email,
                    'description' => $test->description,
                    'comment' => $test->comment,
                    'status' => $test->status->name,
                ]),
        ]);
    }

    // Форма создания
    public function create(): Response
    {
        return Inertia::render('Tests/Create', [
            'statuses' => collect(TestsStatusEnum::cases())->map(fn($status) => [
                'value' => $status->value,
                'label' => $status->name
            ]),
        ]);
    }

    // Сохранение новой записи
    public function store(): RedirectResponse
    {
        Request::validate([
            'name' => ['required', 'max:100'],
            'email' => ['required', 'max:50', 'email', 'unique:tests,email'],
            'phone' => ['required', 'max:50', 'unique:tests,phone'],
            'description' => ['nullable'],
            'comment' => ['nullable'],
            'status' => ['required', new Enum(TestsStatusEnum::class)],
        ]);

        Test::create(Request::all());

        return Redirect::route('tests')->with('success', 'Test created.');
    }

    // Форма редактирования
    public function edit($id): Response
    {
        $test = Test::withoutGlobalScopes()->findOrFail($id);
        return Inertia::render('Tests/Edit', [
            'test' => [
                'id' => $test->id,
                'name' => $test->name,
                'email' => $test->email,
                'phone' => $test->phone,
                'description' => $test->description,
                'comment' => $test->comment,
                'status' => $test->status,
            ],
            'statuses' => collect(TestsStatusEnum::cases())->map(fn($status) => [
                'value' => $status->value,
                'label' => $status->name
            ]),
            
        ]);
    }

    // Обновление записи
    public function update($id)
    {
        $test = Test::withoutGlobalScopes()->findOrFail($id);
        $test->update(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['required', 'email', Rule::unique('tests')->ignore($id)],
                'phone' => ['required', Rule::unique('tests')->ignore($id)],
                'description' => ['nullable'],
                'comment' => ['nullable'],
                'status' => ['required', new Enum(TestsStatusEnum::class)],
            ])
        );

        return Redirect::back()->with('success', 'Test updated.');
    }

    public function destroy($id): RedirectResponse
    {
        $test = Test::withoutGlobalScopes()->findOrFail($id);
        $test->delete();

        return Redirect::route('tests')->with('success', 'Test deleted.');
    }

    // Генерация 1000 строк
    public function generate(): RedirectResponse
    {
        Test::factory()->count(1000)->create();

        return Redirect::route('tests')->with('success', 'New Tests Generated.');
    }

    // Очистка таблицы
    public function clear(): RedirectResponse
    {
        Test::truncate();

        return Redirect::route('tests')->with('success', 'All Tests Deleted.');
    }

    // редактирование google sheet url
    public function setGoogleSheetUrl()
    {
        $url = Settings::where('key', 'google_sheet_url')->first();
        return Inertia::render('Tests/Settings', [
            'url' => $url->value ?? null,
        ]);
    }

    // Обновление Google Sheet URL
    public function updateGoogleSheetUrl()
    {
        Request::validate([
            'url' => 'required|url',
        ]);

        Settings::setValue('google_sheet_url', Request::all()['url']);

        return Redirect::route('tests.googleSheetUrl')->with('success', 'All Tests Deleted.');
    }
}
