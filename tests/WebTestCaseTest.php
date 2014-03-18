<?php
namespace tests;
use Silex\WebTestCase as BaseWebTestCase;
use ScrumMastor\ScrumMastorApplication;
class WebTestCaseTest extends BaseWebTestCase
{
    public function createApplication()
    {
        $app = new ScrumMastorApplication(array('env' => 'test'));

        return $app;
    }

}
