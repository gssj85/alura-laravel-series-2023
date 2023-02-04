<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SeriesRequest;
use App\Models\Series;
use App\Repositories\SeriesRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SeriesController
{
    public function __construct(private readonly SeriesRepository $seriesRepository) {}

    public function index(Request $request)
    {
        $query = Series::query();
        if ($request->has('nome')) {
            $query->where('nome', $request->nome);
        }

        return $query->paginate(2);
    }

    public function store(SeriesRequest $request)
    {
        return response()->json($this->seriesRepository->add($request), 201);
    }

    public function upload(Request $request)
    {
        $binaryData = $request->getContent();

        $uniqueFileName = md5(uniqid(microtime(), true));
        $fileExtension = substr(strrchr(getimagesizefromstring($binaryData)['mime'], '/'), 1);
        $fileName = "$uniqueFileName.$fileExtension";

        Storage::disk('public')->put("series_cover/$fileName", $binaryData);

        return "series_cover/$fileName";
    }

//    // Busca Séries com Temporadas e Episódios
//    public function show(int $series)
//    {
//        return Series::whereId($series)->with('seasons.episodes')->first();
//    }

    public function show(int $series)
    {
        $seriesModel = Series::with('seasons.episodes')->find($series);
        return $seriesModel ?? response()->json(['message' => 'Series not found'], Response::HTTP_NOT_FOUND);
    }

    public function update(int $series, SeriesRequest $request)
    {
        // Faz um SELECT para buscar a Série e só depois atualiza.
        // $series->fill($request->all());
        // $series->save();
        // return $series;

        // Atualiza com uma query, sem buscar a Série antes.
        Series::where('id', $series)->update($request->all());
        // retorno de uma resposta que não contenha a série, já que não fizemos um `SELECT`
        return response()->json(['message' => 'Series updated!']);

    }

    public function destroy(int $series, Authenticatable $user)
    {
        dd($user->tokenCan('series:delete'));
        Series::destroy($series);
        return response()->noContent();
    }
}
