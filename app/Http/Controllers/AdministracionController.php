<?php

namespace App\Http\Controllers;

use App\Models\Instalador;
use App\Services\InstaladorService;
use App\Http\Requests\StoreInstaladorRequest;
use App\Http\Requests\UpdateInstaladorRequest;
use Illuminate\Http\Request;

class AdministracionController extends Controller
{
    public function __construct(private InstaladorService $instaladorService)
    {
        $this->middleware('can:admin-only')->only([
            'instaladoresIndex', 'instaladoresCreate', 'instaladoresStore',
            'instaladoresEdit', 'instaladoresUpdate',
            'instaladoresDestroy', 'instaladoresRestore', 'instaladoresForceDelete',
            'instaladoresToggleActivo',
        ]);

       
    }

    public function index()
    {
        return view('administracion.index');
    }


    public function instaladoresIndex(Request $request)
    {
        $q           = $request->string('q')->toString();
        $perPage     = (int) $request->get('per_page', 10);
        $withTrashed = (bool) $request->boolean('withTrashed', false);

        $instaladores = $this->instaladorService->list($q, $perPage, $withTrashed);

        return view('administracion.instaladores.index', compact('instaladores', 'q', 'perPage', 'withTrashed'));
    }

    public function instaladoresCreate()
    {
        $instalador = new Instalador();
        return view('administracion.instaladores.create', compact('instalador'));
    }

    public function instaladoresStore(StoreInstaladorRequest $request)
    {
        $this->instaladorService->create($request->validated());
        return redirect()->route('administracion.instaladores.index')->with('success', 'Instalador creado correctamente.');
    }

    public function instaladoresEdit(Instalador $instalador)
    {
        return view('administracion.instaladores.edit', compact('instalador'));
    }

    public function instaladoresUpdate(UpdateInstaladorRequest $request, Instalador $instalador)
    {
        $this->instaladorService->update($instalador, $request->validated());
        return redirect()->route('administracion.instaladores.index')->with('success', 'Instalador actualizado correctamente.');
    }

    public function instaladoresDestroy(Instalador $instalador)
    {
        $this->instaladorService->delete($instalador);
        return back()->with('success', 'Instalador eliminado (papelera).');
    }

    public function instaladoresToggleActivo(Instalador $instalador)
    {
        $this->instaladorService->toggleActivo($instalador);
        return back()->with('success', 'Estado operativo actualizado.');
    }

    public function instaladoresRestore(int $id)
    {
        $this->instaladorService->restore($id);
        return back()->with('success', 'Instalador restaurado.');
    }

    public function instaladoresForceDelete(int $id)
    {
        $this->instaladorService->forceDelete($id);
        return back()->with('success', 'Instalador eliminado definitivamente.');
    }
}
