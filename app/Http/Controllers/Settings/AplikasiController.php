<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\Aplikasi\BakuAwalRequest;
use App\Repositories\Pengaturan\BakuAwalRepository;
use App\Support\Facades\Memo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Artisan;

class AplikasiController extends Controller implements HasMiddleware
{
    protected BakuAwalRepository $repository;

    public function __construct(BakuAwalRepository $repository)
    {
        $this->repository = $repository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:aplikasi-index', only: ['index']),
            new Middleware('can:aplikasi-update', only: ['update']),
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('aplikasi-gate-' . $user->getKey(), function () use ($user) {
            return [
                'update' => $user->can('aplikasi-update'),
            ];
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gate = $this->gate();
        return inertia('Pengaturan/Aplikasi/Index', compact("gate"));
    }

    /**
     * Optimize clear Aplication.
     */
    public function optimizeClear()
    {
        return Artisan::call('optimize:clear');
    }

    public function bakuAwal(BakuAwalRequest $request)
    {
        return $this->repository->storeOrUpdate($request);
    }
}
