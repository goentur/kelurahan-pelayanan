<?php

namespace App\Http\Controllers\Pendataan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\NopRequest;
use App\Http\Requests\Pendataan\Spop\AddBangunanRequest;
use App\Http\Requests\Pendataan\Spop\DataDetailRequest;
use App\Http\Requests\Pendataan\Spop\DataRequest;
use App\Http\Requests\Pendataan\Spop\DeleteBangunanRequest;
use App\Http\Requests\Pendataan\Spop\StoreRequest;
use App\Http\Requests\Pendataan\Spop\UpdateBangunanRequest;
use App\Models\Pendataan\PendataanSpop;
use App\Models\Ref\RefAtap;
use App\Models\Ref\RefDinding;
use App\Models\Ref\RefJenisBangunan;
use App\Models\Ref\RefJenisPendataanSpop;
use App\Models\Ref\RefJenisTanah;
use App\Models\Ref\RefKondisi;
use App\Models\Ref\RefKonstruksi;
use App\Models\Ref\RefLangit;
use App\Models\Ref\RefLantai;
use App\Models\Ref\RefPekerjaanSubjekPajak;
use App\Models\Ref\RefStatusSubjekPajak;
use App\Repositories\Pendataan\SpopRepository;
use App\Support\Facades\Memo;

class SpopController extends Controller
{
    public function __construct(
        protected SpopRepository $repository,
    ) {}

    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('pendataan-spop-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('pendataan-spop-create'),
                'update' => $user->can('pendataan-spop-update'),
                'delete' => $user->can('pendataan-spop-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        $jenis = RefJenisPendataanSpop::select("id", "nama")->get();
        $status = RefStatusSubjekPajak::select("id", "nama")->get();
        $pekerjaan = RefPekerjaanSubjekPajak::select("id", "nama")->get();
        $tanah = RefJenisTanah::select("id", "nama")->get();
        $jenisBangunan = RefJenisBangunan::select("id", "nama")->get();
        $kondisi = RefKondisi::select("id", "nama")->get();
        $konstruksi = RefKonstruksi::select("id", "nama")->get();
        $atap = RefAtap::select("id", "nama")->get();
        $dinding = RefDinding::select("id", "nama")->get();
        $lantai = RefLantai::select("id", "nama")->get();
        $langit = RefLangit::select("id", "nama")->get();
        return inertia('pendataan/spop/index', compact("gate", "jenis", "status", "pekerjaan", "tanah", "jenisBangunan", "kondisi", "konstruksi", "atap", "dinding", "lantai", "langit"));
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request));
        back()->with('success', 'Data berhasil ditambahkan');
    }

    public function cekNop(NopRequest $request)
    {
        return response()->json($this->repository->cekNop($request));
    }

    public function store(StoreRequest $request)
    {
        $this->repository->store($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }

    public function dataDetail(DataDetailRequest $request)
    {
        return response()->json($this->repository->dataDetail($request));
    }

    public function addBangunan(AddBangunanRequest $request)
    {
        $this->repository->addBangunan($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }

    public function updateBangunan(UpdateBangunanRequest $request)
    {
        $this->repository->updateBangunan($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }

    public function deleteBangunan(DeleteBangunanRequest $request)
    {
        $this->repository->deleteBangunan($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy(PendataanSpop $pendataanSpop)
    {
        $this->repository->delete($pendataanSpop);
        back()->with('success', 'Data berhasil dihapus');
    }
}
