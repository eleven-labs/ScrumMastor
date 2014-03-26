<?php
namespace ScrumMastor\Service;

class TaskService
{
    protected $mongo;

    /**
     * Inject MongoDB dependencies
     * @param MongoDB MongoDB Service
     */
    public function __construct($mongo)
    {
        $this->mongo = $mongo;
    }

    /**
     * Check if ID is MongoId type
     * @param string ID to check
     * @return boolean
     */
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

    /**
     * Check if ID exist in databse
     * @param string ID to check
     * @return boolean
     */
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

    /**
     * Insert task in database
     * @param array Data to insert
     * @return boolean
     */
    public function insertTask($data)
    {
        try {
            $this->mongo->tasks->insert($data);   
        } catch (\Exception $ex) {
            return false;
        }
        return true;
    }

    /**
     * Remove task in database
     * @param array 
     * @return boolean
     */
    public function removeTask($data)
    {
        try {
            $this->mongo->tasks->remove($data);
        } catch (\Exception $ex) {
            return false;
        }
        return true;
    }

    /**
     * Update task with newData
     * @param array Data to update
     * @param array New data 
     * @return boolean
     */
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
