<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Model_Equipments;
use App\Models\Model_History;

class Report extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index()
    {
        // Get filters from GET
        $activeCondition   = $this->request->getGet('active_condition') ?? '';
        $unusableCondition = $this->request->getGet('unusable_condition') ?? '';
        $roleFilter        = $this->request->getGet('role') ?? '';

        $equipmentsModel = new Model_Equipments();
        $historyModel    = new Model_History();

        // -------------------- ACTIVE EQUIPMENT --------------------
        $equipmentsActiveQuery = $equipmentsModel->where('status', 'active')
                                                 ->where('item_type', 'equipment');
        if ($activeCondition) {
            $equipmentsActiveQuery->where('item_condition', $activeCondition);
        }
        $equipmentsActive = $equipmentsActiveQuery->orderBy('item_name', 'ASC')->findAll();

        // -------------------- UNUSABLE EQUIPMENT --------------------
        $equipmentsUnusableQuery = $equipmentsModel->whereIn('item_condition', ['broken', 'under repair']);
        if ($unusableCondition) {
            $equipmentsUnusableQuery->where('item_condition', $unusableCondition);
        }
        $equipmentsUnusable = $equipmentsUnusableQuery->orderBy('item_name', 'ASC')->findAll();

        // -------------------- USER BORROWING HISTORY --------------------
        $historyQuery = $historyModel
            ->join('users', 'history.user_id = users.user_id', 'left')
            ->join('inventory', 'history.item_id = inventory.item_id', 'left')
            ->select('history.*, users.name, users.role, inventory.item_name');

        if ($roleFilter) {
            $historyQuery->where('users.role', $roleFilter);
        }

        $history = $historyQuery->orderBy('date_created', 'DESC')->findAll();

        // -------------------- PASS DATA TO VIEW --------------------
        $data = [
            'title'               => "Axion - Reports",
            'equipmentsActive'    => $equipmentsActive,
            'equipmentsUnusable'  => $equipmentsUnusable,
            'history'             => $history,
            'activeCondition'     => $activeCondition,
            'unusableCondition'   => $unusableCondition,
            'roleFilter'          => $roleFilter
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('view_report', $data)
            . view('include/view_footer');
    }
}
