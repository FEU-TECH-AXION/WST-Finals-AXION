<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Model_Equipments;
use App\Models\Model_Borrow_Log;
use App\Models\Model_History;

class Borrow extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index($perpage = 10)
    {
        $equipmentsModel = new Model_Equipments();

        $data = [
            'title' => "Axion - Borrow Equipments",
            'equipments' => $equipmentsModel
                ->where('item_type', 'equipment')
                ->where('status', 'active')
                ->orderBy('item_name', 'ASC')
                ->paginate($perpage),
            'pager' => $equipmentsModel->pager,
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('view_borrow', $data)
            . view('include/view_footer');
    }

    // Borrow form for selected equipment
    public function form($id)
    {
        $equipmentsModel = new Model_Equipments();
        $equipment = $equipmentsModel->find($id);

        if (!$equipment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Equipment not found');
        }

        // Get accessories
        $accessories = $equipmentsModel->where('parent_item_id', $id)->findAll();

        $data = [
            'title' => "Axion - Borrow Equipment",
            'equipment' => $equipment,
            'accessories' => $accessories
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('view_borrow_form', $data)
            . view('include/view_footer');
    }

     // Borrow submission
    public function submit()
    {
        $equipmentsModel = new Model_Equipments();
        $borrowLogModel = new Model_BorrowLog();
        $historyModel = new Model_History();

        $user_id = $this->session->get('user_id');
        $item_id = $this->request->getPost('item_id');
        $expected_return = $this->request->getPost('expected_return_date');

        $equipment = $equipmentsModel->find($item_id);
        if (!$equipment || $equipment['quantity'] < 1) {
            $this->session->setFlashdata('error', 'Equipment not available for borrow.');
            return redirect()->back();
        }

        // Insert borrow log for parent equipment
        $borrowLogModel->insert([
            'item_id' => $item_id,
            'user_id' => $user_id,
            'borrow_date' => date('Y-m-d H:i:s'),
            'expected_return_date' => $expected_return,
            'status' => 'borrowed'
        ]);

        $historyModel->insert([
            'item_id' => $item_id,
            'user_id' => $user_id,
            'action' => 'borrowed',
            'borrowed_date' => date('Y-m-d H:i:s')
        ]);

        // Borrow all accessories
        $accessories = $equipmentsModel->where('parent_item_id', $item_id)->findAll();
        foreach ($accessories as $acc) {
            $borrowLogModel->insert([
                'item_id' => $acc['item_id'],
                'user_id' => $user_id,
                'borrow_date' => date('Y-m-d H:i:s'),
                'expected_return_date' => $expected_return,
                'status' => 'borrowed'
            ]);

            $historyModel->insert([
                'item_id' => $acc['item_id'],
                'user_id' => $user_id,
                'action' => 'borrowed',
                'borrowed_date' => date('Y-m-d H:i:s')
            ]);
        }

        $this->session->setFlashdata('success', 'Equipment borrowed successfully.');
        return redirect()->to('/borrow');
    }
}