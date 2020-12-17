<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Http\Request;

use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Illuminate\Support\Facades\DB;
class CreateUsersTable extends Migration
{

    public function FetchAzureUsers()
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

        $userarray = $graph->createRequest("GET", '/users/?$top=900')
                      ->setReturnType(Model\User::class)
                      ->execute();
        foreach ($userarray as $users) {
            DB::table('users')->insert(
                array(
                    'AzureID' => $users->getId(),
                    'DisplayName' => $users->getDisplayName(),
                    'FirstName' => $users->getGivenName(),
                    'Lastname' => $users->getSurname(),
                    'PhoneNumber' => "",
                    'email' => $users->getMail()
                )
            );
        }
    }




    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('AzureID');
            $table->string('DisplayName');
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('PhoneNumber')->nullable();
            $table->string('email')->nullable();
            $table->string('ImgPath')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        $this->FetchAzureUsers();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
