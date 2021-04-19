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

    public function getCSV($id){
        switch($id){
            case 0:
                $url = 'https://spotifycharts.com/regional/global/daily/latest/download';
                $prefix = 'top200-';
                break;
            case 1:
                $url = 'https://spotifycharts.com/viral/global/daily/latest/download';
                $prefix = 'viral50-';
                break;
            default:
                return array("error","Invalid download id");
        }    
        $ch = curl_init($url);
        // $dir = substr($_SERVER["DOCUMENT_ROOT"], 0, strrpos($_SERVER["DOCUMENT_ROOT"], '/')) . "/stage/";
        if(!file_exists(str_replace("\\","/",\storage_path("app/stage/")))){
            Storage::makeDirectory("stage");
            // Storage::makeDirectory(str_replace("\\","/",\storage_path("app/stage/")));
        }
        $dir = str_replace("\\","/",\storage_path("app/stage/"));
        $file_name = $prefix . date('m-d-Y') . '.csv';
        $save_file_loc = $dir . $file_name;

        $fp = fopen($save_file_loc, 'wb');

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_exec($ch);

        curl_close($ch);
        fclose($fp);
    
    }

    public function importLists(){
        $spath = str_replace("\\","/",\storage_path("app/stage/"));
        $top200 = preg_grep('/^(top200)/i', scandir($spath));
        $viral50 = preg_grep('/^(viral50)/i', scandir($spath));
        // print_r($viral50);
        spotify_top200::whereDate('created_at', Carbon::today())->delete();    
        spotify_viral50::whereDate('created_at', Carbon::today())->delete();    
        foreach ($top200 as $key => $value) {
            if(($handle     =   fopen($spath . $value, "r")) !== FALSE){
                $cnt = 0;
    
                while(($row =   fgetcsv($handle)) !== FALSE){
                    if(is_numeric($row[0])){
                        print_r($row);
                        $rec = new spotify_top200;
                        $rec->position = $row[0];
                        $rec->track_name = $row[1];
                        $rec->artist = $row[2];
                        $rec->streams = $row[3];
                        $rec->spotify_id = \basename($row[4]);
                        $data = Spotify::track(\basename($row[4]))->get();
                        $rec->spotify_data = json_encode($data);
                        $rec->save();
                        if($cnt%5 === 0)
                            sleep(2);
                        $cnt++;
                        echo $row[1].' by ' . $row[2] . " saved.\n";
                    }
                    // DB::table('spotify_to200')
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
                        $rec->position = $row[0];
                        $rec->track_name = $row[1];
                        $rec->artist = $row[2];
                        $rec->spotify_id = \basename($row[3]);
                        $data = Spotify::track(\basename($row[3]))->get();
                        $rec->spotify_data = json_encode($data);
                        $rec->save();
                        if($cnt%5 === 0)
                            sleep(2);
                        $cnt++;
                         // echo \basename($rec->spotify_id) . "\n";
                        echo $row[1].' by ' . $row[2] . " saved.\n";
                    }
                    // DB::table('spotify_to200')
                }
                \fclose($handle);
            }
        }
        // $this->archiveLists();
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
        $this->getCSV(0);
        $this->getCSV(1);
        // echo storage_path();
        $this->importLists();

        return 0;
    }
}
