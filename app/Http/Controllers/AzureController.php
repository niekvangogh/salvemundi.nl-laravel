<?php

namespace App\Http\Controllers;

use App\Mail\SendMailInschrijving;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Cashier\SubscriptionBuilder\RedirectToCheckoutResponse;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class AzureController extends Controller
{
    public static function connectToAzure(): Graph
    {
        $guzzle = new Client();
        $url = 'https://login.microsoftonline.com/salvemundi.onmicrosoft.com/oauth2/token';
        $token = json_decode($guzzle->post($url, [
            'form_params' => array(
                'client_id' => env("OAUTH_APP_ID"),
                'client_secret' => env("OAUTH_APP_PASSWORD"),
                'resource' => 'https://graph.microsoft.com/',
                'grant_type' => 'client_credentials',
            ),
        ])->getBody()->getContents());

        $accessToken = $token->access_token;

        $graph = new Graph();
        $graph->setAccessToken($accessToken);
        return $graph;
    }

    public static function createAzureUser($registration,$transaction)
    {
        if($registration == null)
        {
            Log::error('WHERE IS MY OBJECT');
        }
        $randomPass = Str::random(40);
        $graph = AzureController::connectToAzure();
        $data = [
            'accountEnabled' => true,
            'displayName' => $registration->firstName." ".$registration->lastName,
            'givenName' => $registration->firstName,
            'surname' => $registration->lastName,
            'mailNickname' => $registration->firstName,
            'mobilePhone' => $registration->phoneNumber,
            'userPrincipalName' => $registration->firstName.".".$registration->lastName."@lid.salvemundi.nl",
            'passwordProfile' => [
                'forceChangePasswordNextSignIn' => true,
                'password' => $randomPass,
            ],
        ];
        Log::info(json_encode($data));
        Mail::to($registration->email)
            ->send(new SendMailInschrijving($registration->firstName, $registration->lastName, $registration->insertion, $transaction->paymentStatus, $randomPass));

        $createUser = $graph->createRequest("POST", "/users")
            ->addHeaders(array("Content-Type" => "application/json"))
            ->setReturnType(Model\User::class)
            ->attachBody(json_encode($data))
            ->execute();
        $newUserID = $createUser->getId();
        Log::info('New user id:'.$newUserID);
        $userEmail = $registration->firstName.".".$registration->lastName."@lid.salvemundi.nl";
        $userObject = User::where('email', $userEmail)->first();
        $userObject->AzureID = $newUserID;
        $userObject->save();

        //AzureController::fetchSpecificUser($newUserID);
        return $randomPass;
    }

    public static function fetchSpecificUser($userId)
    {
        $graph = AzureController::connectToAzure();

        $fetchedUser = $graph->createRequest("GET",'/users/'.$userId)
            ->setReturnType(Model\User::class)
            ->execute();
        $newUser = new User;
        $newUser->AzureID = $fetchedUser->getId();
        $newUser->DisplayName = $fetchedUser->getDisplayName();
        $newUser->FirstName = $fetchedUser->getGivenName();
        $newUser->LastName = $fetchedUser->getSurname();
        $newUser->PhoneNumber = $fetchedUser->getMobilePhone();
        $newUser->email = $fetchedUser->getGivenName().".".$fetchedUser->getSurname()."@lid.salvemundi.nl";
        $newUser->ImgPath = "images/SalveMundi-Vector.svg";
        $newUser->save();
        AzureController::createSubscription('registration',$fetchedUser->getId());
    }

    public static function createSubscription(string $plan,$id)
    {
        $user = User::where('AzureID',$id)->first();

        $name = ucfirst($plan) . ' membership';

        if(!$user->subscribed($name, $plan)) {

            $result = $user->newSubscription($name, $plan)->create();

            if(is_a($result, RedirectToCheckoutResponse::class)) {
                return $result; // Redirect to Mollie checkout
            }
            return back()->with('status', 'Welcome to the ' . $plan . ' plan');
        }
        return back()->with('status', 'You are already on the ' . $plan . ' plan');
    }
}
