<?php
/**
 *
 */

class EngineBlock_Service_GroupProviderGroupMemberFilter
{
    public function listSearch(Surfnet_Search_Parameters $params, $group_provider_id = '')
    {
        return $this->_searchWhere($params, "group_provider_id = '$group_provider_id'");
    }

    protected function _searchWhere(Surfnet_Search_Parameters $params, $where = '')
    {
        // select Group Member Filter record(s)
        $dao = new EngineBlock_Model_DbTable_GroupProviderGroupMemberFilter();

        $query = $dao->select()->from($dao);
        if (strlen(trim($where)) > 0) {
            $query->where($where);
        }
        if ($params->getLimit()) {
            $query->limit($params->getLimit(), $params->getOffset());
        }
        if ($params->getSortByField()) {
            $query->order('group_provider_group_member_filter.' . $params->getSortByField() . ' ' . $params->getSortDirection());
        }
        $groupMemberFilterRecords = $dao->fetchAll($query);
        $totalCount = $dao->fetchRow(
            $query->reset(Zend_Db_Select::LIMIT_COUNT)
                    ->reset(Zend_Db_Select::LIMIT_OFFSET)
                    ->columns(array('count' => 'COUNT(*)'))
        )->offsetGet('count');

        return new Surfnet_Search_Results($params, $groupMemberFilterRecords, $totalCount);
    }

    public function fetchById($group_provider_id, $group_member_filter_id)
    {
        $mapper = new EngineBlock_Model_Mapper_GroupProviderGroupMemberFilter(new EngineBlock_Model_DbTable_GroupProviderGroupMemberFilter());
        return $mapper->fetchById($group_provider_id, $group_member_filter_id);
    }

    public function save($data, $overwrite = false)
    {
        if (isset($data['group_provider_id']) && isset($data['group_member_filter_id']) && $data['group_member_filter_id'] == $data['org_group_member_filter_id'] && !$overwrite) {
            $group_provider_id = htmlentities($data['group_provider_id']);
            $group_member_filter_id = htmlentities($data['group_member_filter_id']);
            $groupMemberFilterService = new EngineBlock_Service_GroupProviderGroupMemberFilter();
            $groupMemberFilter = $groupMemberFilterService->fetchById($group_provider_id, $group_member_filter_id);
        }
        else {
            $groupMemberFilter = new EngineBlock_Model_GroupProviderGroupMemberFilter();
        }
        $groupMemberFilter->populate($data);

        $form = new EngineBlock_Form_GroupProviderGroupMemberFilter();
        if (!$form->isValid($groupMemberFilter->toArray())) {
            $formErrors = $form->getErrors();
            $modelErrors = array();
            foreach ($formErrors as $fieldName => $fieldErrors) {
                foreach ($fieldErrors as $fieldError) {
                    switch ($fieldError) {
                        case 'isEmpty':
                            $error = 'Field is obligatory, but no input given';
                            break;
                        default:
                            $error = $fieldError;
                    }

                    if (!isset($modelErrors[$fieldName])) {
                        $modelErrors[$fieldName] = array();
                    }
                    $modelErrors[$fieldName][] = $error;
                }
            }
            $groupMemberFilter->errors = $modelErrors;
            return $groupMemberFilter;
        }

        $mapper = new EngineBlock_Model_Mapper_GroupProviderGroupMemberFilter(new EngineBlock_Model_DbTable_GroupProviderGroupMemberFilter());
        $isNewRecord = (isset($data['org_group_member_filter_id']) && $groupMemberFilter->group_member_filter_id != $data['org_group_member_filter_id']);
        $mapper->save($groupMemberFilter, $isNewRecord);

        // if the PK changes, it is saved as a new record, so the original record should be deleted
        if ($isNewRecord) {
            $this->delete($data['group_provider_id'], $data['org_group_member_filter_id']);
        }

        return $groupMemberFilter;
    }

    public function delete($group_provider_id, $group_filter_id)
    {
        $dao = new EngineBlock_Model_DbTable_GroupProviderGroupFilter();
        if (strlen(trim($group_provider_id)) > 0 && strlen(trim($group_filter_id)) > 0) {
            return $dao->delete("group_provider_id='$group_provider_id' AND group_filter_id='$group_filter_id'");
        } else {
            return false;
        }
    }

    public function updateGroupProviderId($oldId, $newId)
    {
        if (strlen($oldId) > 0 && strlen($newId) > 0 && $oldId != $newId) {
            $dao = new EngineBlock_Model_DbTable_GroupProviderDecorator();
            return $dao->update(array('group_provider_id' => $newId), "group_provider_id = '$oldId'");
        } else {
            // ignore
            return true;
        }

    }
}