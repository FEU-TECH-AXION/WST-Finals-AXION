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
        $conditionFilter = $this->request->getGet('condition') ?? '';
        $roleFilter = $this->request->getGet('role') ?? '';

        $equipmentsModel = new Model_Equipments();
        $historyModel = new Model_History();

        // ACTIVE EQUIPMENT
        $equipmentsActiveQuery = $equipmentsModel->where('status', 'active')
                                                 ->where('item_type', 'equipment');

        if ($conditionFilter) {
            $equipmentsActiveQuery->where('item_condition', $conditionFilter);
        }

        $equipmentsActive = $equipmentsActiveQuery->orderBy('item_name', 'ASC')->findAll();

        // UNUSABLE EQUIPMENT
        $equipmentsUnusableQuery = $equipmentsModel->whereIn('item_condition', ['broken', 'under repair']);

        if ($conditionFilter) {
            $equipmentsUnusableQuery->where('item_condition', $conditionFilter);
        }

        $equipmentsUnusable = $equipmentsUnusableQuery->orderBy('item_name', 'ASC')->findAll();

        // USER BORROWING HISTORY
        $historyQuery = $historyModel->join('users', 'history.user_id = users.user_id', 'left')
                                     ->select('history.*, users.name, users.role');

        if ($roleFilter) {
            $historyQuery->where('users.role', $roleFilter);
        }

        $history = $historyQuery->orderBy('date_created', 'DESC')->findAll();

        // Pass all data to the view
        $data = [
            'title' => "Axion - Reports",
            'equipmentsActive'   => $equipmentsActive,
            'equipmentsUnusable' => $equipmentsUnusable,
            'history'            => $history,
            'conditionFilter'    => $conditionFilter,
            'roleFilter'         => $roleFilter
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('view_report', $data)
            . view('include/view_footer');
    }
}
