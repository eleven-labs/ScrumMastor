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
                return true; //id found
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getTaskById($id, $filter = array('_id' => true))
    {
        if ($this->existId($id)) {
            $task = $this->mongo->tasks->find(
                    array('_id' => new \MongoId($id)),
                    $filter);
            return iterator_to_array($task);
        } else {
            return false;
        }
    }

    public function insertTask($data)
    {
        try {
            $this->mongo->tasks->insert($data);
        } catch (\Exception $ex) {
            return false;
        }

        return true;
    }

    public function removeTask($data)
    {
        try {
            $this->mongo->tasks->remove($data);
        } catch (\Exception $ex) {
            return false;
        }

        return true;
    }

    public function updateTask($data, $newData)
    {
        try {
            $this->mongo->tasks->update(
                $data,
                $newData
            );
        } catch (\Exception $ex) {
            return false;
        }

        return true;
    }
}
