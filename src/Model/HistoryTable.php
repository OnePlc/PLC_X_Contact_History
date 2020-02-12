<?php
/**
 * HistoryTable.php - History Table
 *
 * Table Model for History History
 *
 * @category Model
 * @package Contact\History
 * @author Verein onePlace
 * @copyright (C) 2020 Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

namespace OnePlace\Contact\History\Model;

use Application\Controller\CoreController;
use Application\Model\CoreEntityTable;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;

class HistoryTable extends CoreEntityTable {

    /**
     * HistoryTable constructor.
     *
     * @param TableGateway $tableGateway
     * @since 1.0.0
     */
    public function __construct(TableGateway $tableGateway) {
        parent::__construct($tableGateway);

        # Set Single Form Name
        $this->sSingleForm = 'contacthistory-single';
    }

    /**
     * Get History Entity
     *
     * @param int $id
     * @return mixed
     * @since 1.0.0
     */
    public function getSingle($id) {
        # Use core function
        return $this->getSingleEntity($id,'History_ID');
    }

    /**
     * Save History Entity
     *
     * @param History $oHistory
     * @return int History ID
     * @since 1.0.0
     */
    public function saveSingle(History $oHistory) {
        $aData = [];

        $aData = $this->attachDynamicFields($aData,$oHistory);

        $id = (int) $oHistory->id;

        if ($id === 0) {
            # Add Metadata
            $aData['created_by'] = CoreController::$oSession->oUser->getID();
            $aData['created_date'] = date('Y-m-d H:i:s',time());
            $aData['modified_by'] = CoreController::$oSession->oUser->getID();
            $aData['modified_date'] = date('Y-m-d H:i:s',time());

            # Insert History
            $this->oTableGateway->insert($aData);

            # Return ID
            return $this->oTableGateway->lastInsertValue;
        }

        # Check if History Entity already exists
        try {
            $this->getSingle($id);
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(sprintf(
                'Cannot update History with identifier %d; does not exist',
                $id
            ));
        }

        # Update Metadata
        $aData['modified_by'] = CoreController::$oSession->oUser->getID();
        $aData['modified_date'] = date('Y-m-d H:i:s',time());

        # Update History
        $this->oTableGateway->update($aData, ['History_ID' => $id]);

        return $id;
    }

    /**
     * Generate new single Entity
     *
     * @return History
     * @since 1.0.0
     */
    public function generateNew() {
        return new History($this->oTableGateway->getAdapter());
    }
}