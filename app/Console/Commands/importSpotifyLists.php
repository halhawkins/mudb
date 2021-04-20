<?php

namespace App\Console\Commands;
use App\Models\spotify_top200;
use App\Models\spotify_viral50;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use illuminate\Support\Facades\DB;
use Aerni\Spotify\Facades\SpotifyFacade as Spotify;
use Illuminate\Support\Carbon;

class importSpotifyLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:insert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Top200 and Viral50 lists from Spotify into DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function importLists(){
        $spath = str_replace("\\","/",\storage_path("app/stage/"));
        $top200 = preg_grep('/^(top200)/i', scandir($spath));
        $viral50 = preg_grep('/^(viral50)/i', scandir($spath));

        spotify_top200::whereDate('created_at', Carbon::today())->delete();    
        spotify_viral50::whereDate('created_at', Carbon::today())->delete();    
        if(env('ECHO_IMPORT_RESULTS')==1){
            print_r($top200);
            print_r($viral50);
        }
        foreach ($top200 as $key => $value) {
            if(($handle     =   fopen($spath . $value, "r")) !== FALSE){
                $cnt = 0;
    
                while(($row =   fgetcsv($handle)) !== FALSE){
                    if(is_numeric($row[0])){
                        $rec = new spotify_top200;
                        if(env('ECHO_IMPORT_RESULTS')==1)
                            print($row[1] . " by " . $row[2] . " id=" . \basename($row[4]) . " requested.\n");
                        $rec->position = $row[0];
                        $rec->track_name = $row[1];
                        $rec->artist = $row[2];
                        $rec->streams = $row[3];
                        $rec->spotify_id = \basename($row[4]);
                        $data = Spotify::track(\basename($row[4]))->get();
                        $rec->spotify_data = json_encode($data);
                        $rec->save();
                        /* 
                            Import 5 records then sleep for a few seconds
                            to avoid rate limiting.
                        */
                        if($cnt%5 === 0) 
                            sleep(2);
                        $cnt++;
                    }
                }
                \fclose($handle);
            }
        }
        foreach ($viral50 as $key => $value) {
            if(($handle     =   fopen($spath . $value, "r")) !== FALSE){
                $cnt=0;
                while(($row =   fgetcsv($handle)) !== FALSE){
                    if(is_numeric($row[0])){
                        
                        $rec = new spotify_viral50;
                        if(env('ECHO_IMPORT_RESULTS')==1)
                            print($row[1] . " by " . $row[2] . " id=" . \basename($row[3]) . " requested.\n");
                        $rec->position = $row[0];
                        $rec->track_name = $row[1];
                        $rec->artist = $row[2];
                        $rec->spotify_id = \basename($row[3]);
                        $data = Spotify::track(\basename($row[3]))->get();
                        $rec->spotify_data = json_encode($data);
                        $rec->save();
                        /* 
                            Import 5 records then sleep for a few seconds
                            to avoid rate limiting.
                        */
                        if($cnt%5 === 0)
                            sleep(2);
                        $cnt++;
                    }
                }
                \fclose($handle);
            }
        }
    }

    public function archiveLists(){
        $destdir = str_replace("\\","/",\storage_path("app/archive/"));
        $sourcedir = str_replace("\\","/",\storage_path("app/stage/"));
        if(!file_exists($destdir)){
            Storage::makeDirectory("archive");
        }
        $files = \scandir($sourcedir);
        foreach ($files as $file) {
            if($file !== "." && $file !== "..")
                rename($sourcedir . '/' . $file, $destdir . '/' . $file);
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->importLists();
        // $this->archiveLists();

        return 0;
    }
}
