<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Http\Request;

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Illuminate\Support\Facades\DB;


class UpdateImgPath extends Migration
{
    public function FetchAzureUserImages()
    {
        $guzzle = new \GuzzleHttp\Client();
        $url = 'https://login.microsoftonline.com/salvemundi.onmicrosoft.com/oauth2/token';
        $token = json_decode($guzzle->post($url, [
            'form_params' => [
            'client_id' => env("OAUTH_APP_ID"),
            'client_secret' => env("OAUTH_APP_PASSWORD"),
            'resource' => 'https://graph.microsoft.com/',
            'grant_type' => 'client_credentials',
            ],
        ])->getBody()->getContents());

        $accessToken = $token->access_token;

        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $memberuser = DB::table('users')->select('id','AzureID')->get();


        foreach($memberuser as $members)
        {
            try
            {
                $image = $graph->createRequest("GET", '/users/'.$members->AzureID.'/photos/240x240/$value');

                if($image != null)
                {
                    $image->download('public/images/users/'.$members->AzureID.'.jpg');
                    DB::table('users')
                                ->where('id', $members->id)
                                ->update(['ImgPath' => 'images/users/'.$members->AzureID.'.jpeg']);
                }
            }
            catch (\Throwable $th)
            {
                DB::table('users')
                ->where('id', $members->id)
                ->update(['ImgPath' => 'images/SalveMundiLogo.png']);
            }
        }
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->FetchAzureUserImages();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}