<?php

class Portal_GadgetController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                         ->addActionContext('list-official', 'json')
                         ->addActionContext('list-custom', 'json')
                         ->initContext();
    }

    public function listCustomAction()
    {
        $inputFilter = $this->_helper->FilterLoader();

        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);

        $service = new Portal_Service_Gadget();
        $results = $service->searchCustom($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
    }

    public function updateAction()
    {
        // action body
    }

    public function editAction()
    {
        // action body
    }
}