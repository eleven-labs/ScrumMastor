<?php

namespace ScrumMastor\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GithubController
{
    protected $request;
    protected $github;
 
    public function __construct(Request $request, $github) 
    {
        $this->request = $request;
        $this->github = $github;
    }

    public function authAction() 
    {
  	    $code = $this->request->get('code');

        $data = array(
            'client_id'     => $this->github['client_id'],
            'client_secret' => $this->github['client_secret'], 
            'code'          => $code);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->github['url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $response = curl_exec($ch);
        curl_close($ch);

        return new JsonResponse($response, 200);
    }
}