<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Middleware\Autenticador;
use App\Http\Requests\SeriesRequest;
use App\Mail\SeriesCreated;
use App\Models\Series;
use App\Models\User;
use App\Repositories\SeriesRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class SeriesController extends Controller
{

    public function __construct(private SeriesRepository $repository)
    {
//        $this->middleware(Autenticador::class)->except('index');
    }

    public function index(): View
    {
        $series = Series::with(['seasons'])->get();
        $mensagemSucesso = session('mensagem.sucesso');

        return view('series.index')
            ->with('series', $series)
            ->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create(): View
    {
        return view('series.create');
    }

    public function store(SeriesRequest $request): RedirectResponse
    {
        $coverPath = $request->hasFile('cover')
            ? $request->file('cover')
                ->store('series_cover', 'public')
            : null;

        $request->coverPath = $coverPath;

        $serie = $this->repository->add($request);
        \App\Events\SeriesCreated::dispatch(
            $serie->nome,
            $serie->id,
            $request->seasonsQty,
            $request->episodesPerSeason
        );

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '$serie->nome' adicionada com sucesso");
    }

    public function destroy(Series $series): RedirectResponse
    {
        $series->delete();
        \App\Jobs\DeleteSeriesCover::dispatch($series->cover);

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '$series->nome' removida com sucesso");
    }

    public function edit(Series $series): View
    {
        return view('series.edit')->with('serie', $series);
    }

    public function update(Series $series, SeriesRequest $request)
    {
        $series->fill($request->all());
        $series->save();

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '$series->nome' atualizada com sucesso");
    }
}
