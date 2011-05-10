<?php

class Model_GadgetList extends Model_Abstract
{
    /**
     * The gadgets in the list
     *
     * @var Array
     */
    protected $_gadgets;

    public function __construct()
    {
        $mapper = new Model_Mapper_GadgetMapper('Model_Dao_Gadget');
        $this->setMapper($mapper);
    }

    /**
     * Get a list of gadgets
     *
     * @return Array
     */
    public function getList()
    {
        $this->populate($this->getMapper()->fetchAll());
        return $this->_gadgets;
    }

    /**
     * Get a list of the amount of gadgets in certain classes.
     * SSO enabled, Group enabled etc.
     */
    public function getCount($order='type', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchCount($order, $dir, $limit, $offset, $countOnly);
    }

    /**
     * Get a list of the amount of available gadgets
     *
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     */
    public function getAll($order='title', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchAvailable($order, $dir, $limit, $offset, $countOnly);
    }

    /**
     * Get a list of the amount of available gadgets
     *
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     */
    public function getAllNonCustom($order='title', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchAllCustom($order, $dir, $limit, $offset, $countOnly);
    }

    /**
     * Get a list of the amount of available gadgets
     *
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the
     *                           full dataset.
     */
    public function getAllCustom($order='title', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchAllNonCustom($order, $dir, $limit, $offset, $countOnly);
    }

    /**
     * Get a list of the amount of gadget usage
     *
     * @param Integer $limit
     * @param Integer $offset
     * @param Boolean $countOnly Return only the number of rows instead of the full dataset.
     */
    public function getUsage($order='num', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchUsage($order, $dir, $limit, $offset, $countOnly);
    }

    public function getInvites($order='num', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchInvites($order, $dir, $limit, $offset, $countOnly);
    }

    public function getTeamTabs($order='num', $dir='asc', $limit=null, $offset=0, $countOnly=false)
    {
       return $this->getMapper()->fetchTeamTabs($order, $dir, $limit, $offset, $countOnly);
    }

    /**
     * Populate the gadget list.
     *
     * @param array $gadgets
     */
    public function populate(Array $gadgets)
    {
        $this->_gadgets = $gadgets;
    }
}