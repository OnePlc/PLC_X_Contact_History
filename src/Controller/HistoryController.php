<?php
/**
 * HistoryController.php - Main Controller
 *
 * Main Controller for Contact History Plugin
 *
 * @category Controller
 * @package Contact\History
 * @author Verein onePlace
 * @copyright (C) 2020  Verein onePlace <admin@1plc.ch>
 * @license https://opensource.org/licenses/BSD-3-Clause
 * @version 1.0.0
 * @since 1.0.0
 */

declare(strict_types=1);

namespace OnePlace\Contact\History\Controller;

use Application\Controller\CoreEntityController;
use Application\Model\CoreEntityModel;
use OnePlace\Contact\History\Model\HistoryTable;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\AdapterInterface;

class HistoryController extends CoreEntityController {
    /**
     * Contact Table Object
     *
     * @since 1.0.0
     */
    protected $oTableGateway;

    /**
     * ContactController constructor.
     *
     * @param AdapterInterface $oDbAdapter
     * @param ContactTable $oTableGateway
     * @since 1.0.0
     */
    public function __construct(AdapterInterface $oDbAdapter,HistoryTable $oTableGateway,$oServiceManager) {
        $this->oTableGateway = $oTableGateway;
        $this->sSingleForm = 'contacthistory-single';
        parent::__construct($oDbAdapter,$oTableGateway,$oServiceManager);

        if($oTableGateway) {
            # Attach TableGateway to Entity Models
            if(!isset(CoreEntityModel::$aEntityTables[$this->sSingleForm])) {
                CoreEntityModel::$aEntityTables[$this->sSingleForm] = $oTableGateway;
            }
        }
    }

    public function attachHistoryForm($oItem = false) {
        $oForm = CoreEntityController::$aCoreTables['core-form']->select(['form_key'=>'contacthistory-single']);
        $aFields = [
            'history-base' => CoreEntityController::$aCoreTables['core-form-field']->select(['form' => 'contacthistory-single']),
        ];

        # Try to get adress table
        try {
            $oHistoryTbl = CoreEntityController::$oServiceManager->get(HistoryTable::class);
        } catch(\RuntimeException $e) {
            //echo '<div class="alert alert-danger"><b>Error:</b> Could not load address table</div>';
            return [];
        }

        if(!isset($oHistoryTbl)) {
            return [];
        }

        $aHistories = [];
        $oPrimaryHistory = false;
        if($oItem) {
            # load contact addresses
            $oHistories = $oHistoryTbl->fetchAll(false, ['contact_idfs' => $oItem->getID()]);
            # get primary address
            if (count($oHistories) > 0) {
                foreach ($oHistories as $oAddr) {
                    $aHistories[] = $oAddr;
                }
            }
        }

        # Pass Data to View - which will pass it to our partial
        return [
            # must be named aPartialExtraData
            'aPartialExtraData' => [
                # must be name of your partial
                'contact_history'=> [
                    'oHistories'=>$aHistories,
                    'oForm'=>$oForm,
                    'aFormFields'=>$aFields,
                ]
            ]
        ];
    }

    public function attachHistoryToContact($oItem,$aRawData) {
        $oItem->contact_idfs = $aRawData['ref_idfs'];

        return $oItem;
    }

    public function addAction() {
        /**
         * You can just use the default function and customize it via hooks
         * or replace the entire function if you need more customization
         *
         * Hooks available:
         *
         * contact-add-before (before show add form)
         * contact-add-before-save (before save)
         * contact-add-after-save (after save)
         */
        $iContactID = $this->params()->fromRoute('id', 0);

        return $this->generateAddView('contacthistory','contacthistory-single','contact','view',$iContactID,['iContactID'=>$iContactID]);
    }
}
