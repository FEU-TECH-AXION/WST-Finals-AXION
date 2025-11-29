<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Model_Equipments;
use App\Models\Model_BorrowLog;
use App\Models\Model_History;

class ReturnController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    // Show borrowed items for the logged-in user
    public function index()
    {
        $borrowLogModel = new Model_BorrowLog();
        $equipmentsModel = new Model_Equipments();

        $user_id = $this->session->get('user_id');

        // Get borrowed items
        $borrowed_items = $borrowLogModel
            ->select('borrow_log.*, inventory.item_name, inventory.item_type')
            ->join('inventory', 'inventory.item_id = borrow_log.item_id')
            ->where('borrow_log.user_id', $user_id)
            ->where('borrow_log.status', 'borrowed')
            ->orderBy('borrow_log.borrow_date', 'ASC')
            ->findAll();

        // Attach accessories for each borrowed equipment
        foreach ($borrowed_items as &$item) {
            if ($item['item_type'] === 'equipment') {
                $item['accessories'] = $equipmentsModel
                    ->where('parent_item_id', $item['item_id'])
                    ->findAll();
            } else {
                $item['accessories'] = [];
            }
        }

        $data = [
            'title' => "Axion - Return Borrowed Items",
            'borrowed_items' => $borrowed_items
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('return/list', $data)
            . view('include/view_footer');
    }

    // Return form for a specific borrowed item
    public function form($borrow_id)
    {
        $borrowLogModel = new Model_BorrowLog();
        $equipmentsModel = new Model_Equipments();

        $borrow = $borrowLogModel->find($borrow_id);

        if (!$borrow) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Borrow record not found');
        }

        $item = $equipmentsModel->find($borrow['item_id']);

        // Get accessories if parent
        $accessories = [];
        if ($item['item_type'] === 'equipment') {
            $accessories = $equipmentsModel
                ->where('parent_item_id', $item['item_id'])
                ->findAll();
        }

        $data = [
            'title' => "Axion - Return Equipment",
            'borrow' => $borrow,
            'item' => $item,
            'accessories' => $accessories
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('return/form', $data)
            . view('include/view_footer');
    }

    // Submit return
    public function submit()
    {
        $borrowLogModel = new Model_BorrowLog();
        $historyModel   = new Model_History();
        $equipmentsModel = new Model_Equipments();

        $borrow_id = $this->request->getPost('borrow_id');
        $condition = $this->request->getPost('condition');
        $remarks   = $this->request->getPost('remarks');

        $borrow = $borrowLogModel->find($borrow_id);
        if (!$borrow) {
            $this->session->setFlashdata('error', 'Borrow record not found');
            return redirect()->back();
        }

        // Update parent item borrow log to returned
        $borrowLogModel->update($borrow_id, ['status' => 'returned']);

        // Update inventory condition
        $equipmentsModel->update($borrow['item_id'], ['item_condition' => $condition]);

        // Log history
        $historyModel->insert([
            'item_id' => $borrow['item_id'],
            'user_id' => $borrow['user_id'],
            'action' => 'returned',
            'returned_date' => date('Y-m-d H:i:s'),
            'new_status' => $condition,
            'remarks' => $remarks
        ]);

        // Handle accessories
        $item = $equipmentsModel->find($borrow['item_id']);
        if ($item['item_type'] === 'equipment') {
            $accessories = $equipmentsModel->where('parent_item_id', $item['item_id'])->findAll();
            foreach ($accessories as $acc) {
                $borrow_acc = $borrowLogModel
                    ->where(['item_id' => $acc['item_id'], 'user_id' => $borrow['user_id'], 'status' => 'borrowed'])
                    ->first();

                if ($borrow_acc) {
                    $borrowLogModel->update($borrow_acc['borrow_id'], ['status' => 'returned']);
                    $historyModel->insert([
                        'item_id' => $acc['item_id'],
                        'user_id' => $borrow['user_id'],
                        'action' => 'returned',
                        'returned_date' => date('Y-m-d H:i:s'),
                        'new_status' => 'good',
                        'remarks' => 'Accessory returned'
                    ]);
                }
            }
        }

        $this->session->setFlashdata('success', 'Equipment returned successfully.');
        return redirect()->to('/return');
    }
}
