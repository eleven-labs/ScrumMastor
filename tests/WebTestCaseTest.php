<?php
namespace tests;

use Silex\WebTestCase as BaseWebTestCase;
use ScrumMastor\ScrumMastorApplication;

class WebTestCaseTest extends BaseWebTestCase
{

    protected $mongo;

    public function createApplication()
    {
        $app = new ScrumMastorApplication(array('env' => 'test'));

        $this->mongo = $app['mongo'];

        return $app;
    }

    /**
     * Execute all init requests
     * @param  [array] $requests Array of request to execute
     * @return [array] List of requests result
     */
    public function initRequest($requests)
    {
        $client = $this->createClient();
        $data = [];
        foreach ($requests as $key => $request) {
            $client->request($request["type"], $request["url"], $request["parameters"]);
            $this->assertEquals(200, $client->getResponse()->getStatusCode());
            $data[$key] = $client->getResponse()->getContent();
        }

        return $data;
    }

    /**
     * Drops collections passed by drops
     * @param [array] $drops Collections to drops
     */
    public function prepareDb($drops)
    {
        foreach ($drops as $drop) {
            $this->mongo->$drop->drop();
        }

    }

}
