<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EpisodesController
{
    public function index(Season $season)
    {
        return view('episodes.index', [
            'episodes' => $season->episodes,
            'mensagemSucesso' => session('mensagem.sucesso')
        ]);
    }

    public function update(Request $request, Season $season)
    {
//        $watchedEpisodes = $request->episodes;

        // Solução com muitas idas ao banco
        // $season->episodes->each(function (Episode $episode) use ($watchedEpisodes) {
        //     $episode->watched = in_array($episode->id, $watchedEpisodes);
        // });
        // $season->push();

        // Solução com 2 queries
        // DB::transaction(function () use ($watchedEpisodes) {
        //    DB::table('episodes')->whereIn('id', $watchedEpisodes)->update(['watched' => true]);
        //    DB::table('episodes')->whereNotIn('id', $watchedEpisodes)->update(['watched' => false]);
        // });

        // Solução com 1 query
        $watchedEpisodes = implode(', ', $request->episodes ?? []);
        DB::transaction(function () use ($watchedEpisodes, $season) {
         // DB::table('episodes')->where('season_id', $season->id)
         //     ->update(['watched' => DB::raw("case when id in ($watchedEpisodes) then 1 else 0 end")]);

            $season->episodes()->update(['watched' => DB::raw("case when id in ($watchedEpisodes) then 1 else 0 end")]);
        });

//        return back()->with('mensagem.sucesso', 'Episódios assistidos salvos com sucesso');
        return to_route('episodes.index', $season->id)
            ->with('mensagem.sucesso', 'Episódios marcados como assistidos');
    }
}
