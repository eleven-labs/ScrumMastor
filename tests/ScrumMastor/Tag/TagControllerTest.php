<?php

namespace tests\ScrumMastor\Tag;

use tests\WebTestCaseTest;

class TagControllerTest extends WebTestCaseTest
{
    /**
     * @dataProvider providerSetTag
     */
    public function testSetTag($drops, $initParam, $status, $expected)
    {
        //We drop Tasks collections
        $this->prepareDb($drops);

        //We do the init requests for set Some task/tags
        $initData = $this->initRequest($initParam);
        $resultRequest = json_decode($initData[0], true);
        $taskId = $resultRequest["_id"]['$id'];
        $client = $this->createClient();

        //We use $resultRequest who contains _id of task inserted
        $client->request('POST', '/addTag', array('name' => 'Tag test', 'task' => $taskId));
        $this->assertEquals($status, $client->getResponse()->getStatusCode());

        // We get the modified Task
        $task = $this->mongo->tasks->findOne(array('_id' => new \MongoId($taskId)));

        // We test result
        foreach ($expected as $value => $datas) {
            foreach ($datas as $key => $data) {
                $this->assertEquals($task[$value][$key], $data);
            }
        }

    }

    public function providerSetTag()
    {
        return [
            // data set #0 : Classic Add
            [
                //Collections to drop
                ["tasks"],
                //List of init request
                [
                    0 => ["type" => "POST", "url" => "/task", "parameters" => ["model" => '{"title":"cs","description":"sccs","username":"user"}']]
                ],
                //Expected return code
                200,
                //Expected data
                [
                    "Tags" => [
                        0 => "Tag test"
                    ]

                ]
            ]
        ];
    }

    /**
    * TODO
    *
    */
    public function testDelete()
    {
        $this->assertEquals(true, true);
    }

    /**
    * TODO
    *
    */
    public function testSave()
    {
        $this->assertEquals(true, true);
    }

    /**
    * TODO
    *
    */
    public function testList()
    {
        $this->assertEquals(true, true);
    }

    /**
    * TODO
    *
    */
    public function testSearch()
    {
        $this->assertEquals(true, true);
    }

}
