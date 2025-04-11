<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Support\Facades\Memo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Artisan;

class AplikasiController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:aplikasi-index', only: ['index']),
            new Middleware('can:aplikasi-optimize-clear', only: ['optimizeClear'])
        ];
    }

    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('aplikasi-' . $user->getKey(), function () use ($user) {
            return [
                'optimizeClear' => $user->can('aplikasi-optimize-clear'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('pengaturan/aplikasi/index', compact("gate"));
    }

    public function optimizeClear()
    {
        return Artisan::call('optimize:clear');
    }
}
