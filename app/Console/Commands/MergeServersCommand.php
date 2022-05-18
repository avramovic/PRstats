<?php

namespace PRStats\Console\Commands;

use Illuminate\Console\Command;
use PRStats\Models\Server;

class MergeServersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servers:merge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $servers = Server::all();
        $choices = $servers->map(function ($srv) {
            $srv->name .= '###'.$srv->id;
            return $srv;
        })
            ->pluck('name')
            ->toArray();
        $choice1 = $this->choice('Srv?', $choices);
        $choice2 = $this->choice('Srv?', $choices);

        if ($choice1 == $choice2) {
            $this->error('You can\'t merge server with itself!');
            return 1;
        }

        $srvA = explode('###', $choice1);
        $srvB = explode('###', $choice2);

        $serverA = Server::findOrFail(array_pop($srvA));
        $serverB = Server::findOrFail(array_pop($srvB));

        if ($serverA->updated_at->gt($serverB->updated_at)) {
            $toKeep  = $serverA;
            $toMerge = $serverB;
        } else {
            $toKeep  = $serverB;
            $toMerge = $serverA;
        }

        if (!$this->confirm(vsprintf('Are you sure you want to merge %s into %s (keeping %s)? This can not be undone!',[
            $toMerge->name . '###' . $toMerge->id,
            $toKeep->name . '###' . $toKeep->id,
            $toKeep->name . '###' . $toKeep->id,
        ]))) {
            $this->error('User abort... chicken! :D');
            return 2;
        }

        if (!empty($toMerge->server_id) && !empty($toKeep->server_id) && $toMerge->server_id != $toKeep->server_id && !$this->confirm(vsprintf('Both servers have different IDs. Are you sure you want to merge %s into %s (keeping %s)? This can not be undone!',[
                $toMerge->server_id,
                $toKeep->server_id,
                $toKeep->server_id,
            ]))) {
            $this->error('User abort... chicken! :D');
            return 3;
        }

        //update players' server_id
        $this->mergeTableData('players', $toKeep, $toMerge);

        //update matches
        $this->mergeTableData('matches', $toKeep, $toMerge);

        //add scores
        $this->line('Merging scores...');
        $toKeep->total_score += $toMerge->total_score;
        $toKeep->total_kills += $toMerge->total_kills;
        $toKeep->total_deaths += $toMerge->total_deaths;
        $toKeep->games_played += $toMerge->games_played;

        if ($toKeep->created_at->gt($toMerge->created_at)) {
            $this->line('Changing created_at...');
            $toKeep->created_at = $toMerge->created_at;
        }

        if (empty($toKeep->server_id) && !empty($toMerge->server_id)) {
            $this->line('Setting server ID...');
            $toKeep->server_id = $toMerge->server_id;
        }

        //update server to keep
        $toKeep->save();

        //delete server to merge
        $toMerge->delete();

        $this->info('Done!');

        return 0;
    }

    protected function mergeTableData($table, $toKeep, $toMerge, $column = 'server_id')
    {
        $this->line(sprintf('Updating %s...', $table));
        $updated = \DB::table($table)
            ->where($column, $toMerge->id)
            ->update([
                $column => $toKeep->id,
            ]);
        $this->info(sprintf('Updated %d %s',$updated, $table));
        return $updated;
    }
}
