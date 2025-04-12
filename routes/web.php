<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/generate', function (Request $request) {
    $html = $request->input('html');
    $filename = $request->input('filename', 'document.pdf');

    if (!$html) {
        return response()->json(['error' => 'Missing HTML'], 422);
    }

    try {
        $pdf = Browsershot::html($html)
            ->format('A4')
            ->margins(15, 15, 15, 15)
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->pdf();

        return response($pdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    } catch (\Exception $e) {
        return response()->json(['error' => 'PDF generation failed', 'message' => $e->getMessage()], 500);
    }
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');
