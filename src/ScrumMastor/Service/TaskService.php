<?php
namespace ScrumMastor\Service;

class TaskService
{
    protected $mongo;

    public function __construct($mongo)
    {
        $this->mongo = $mongo;
    }

    public function isValidId($id)
    {
        if (empty($id)) {
            return false;
        }

        try {
            $mongoId = new \MongoId($id);

            return true;
        } catch (\MongoException $e) {
            return false;
        }
    }

    public function existId($id)
    {
        if ($this->isValidId($id)) {
            $task = $this->mongo->tasks->find(array('_id' => new \MongoId($id)), array('_id' => true));

            if ($task->count() === 1) {
                return true; //id not found
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function insertTask($data)
    {
        $return = $this->mongo->tasks->insert($data);
        if ($return['err'] === null) {
            return true;
        } else {
            return false;
        }
    }

    public function removeTask($data)
    {
        $return = $this->mongo->tasks->remove($data);
        if ($return['err'] === null) {
            return true;
        } else {
            return false;
        }
    }

    public function updateTask($data, $newData)
    {
        $return = $this->mongo->tasks->update(
                $data,
                $newData
            );
        if ($return['err'] === null) {
            return true;
        } else {
            return false;
        }
    }
}
