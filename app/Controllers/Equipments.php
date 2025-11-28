<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Model_Equipments;

class Equipments extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index($perpage = 10)
    {
        $equipmentsModel = new Model_Equipments();
        $equipmentsModel->orderBy("item_name", "ASC");

        $queryResult = $equipmentsModel->paginate($perpage);

        $data = [
            'title' => "Axion - Equipments List",
            'equipments' => $queryResult,
            'pager' => $equipmentsModel->pager,
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('view_equipments', $data)
            . view('include/view_footer');
    }


    public function view($id)
    {
        $equipmentsModel = new Model_Equipments();
        $equipment = $equipmentsModel->find($id);

        if (!$equipment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Equipment not found');
        }

        $data = [
            'title'     => "Axion - Equipment Details",
            'equipment' => $equipment
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('view_equipment', $data)
            . view('include/view_footer');
    }


    public function add()
    {
        $data = ['title' => "Axion - Add New Equipment"];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('view_equipment_add', $data)
            . view('include/view_footer');
    }


    public function insert()
    {
        $equipmentsModel = new Model_Equipments();

        $data = [
            'item_name'      => $this->request->getPost('item_name'),
            'item_type'      => $this->request->getPost('item_type'),
            'parent_item_id' => $this->request->getPost('parent_item_id') ?: null,
            'quantity'       => $this->request->getPost('quantity'),
            'item_condition' => $this->request->getPost('item_condition'),
            'location'       => $this->request->getPost('location'),
            'status'         => $this->request->getPost('status'),
        ];

        $rules = [
            'item_name' => 'required|min_length[2]',
            'quantity' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Prevent duplicate item_name
        if ($equipmentsModel->where('item_name', $data['item_name'])->first()) {
            return redirect()->back()
                ->withInput()
                ->with('nameExists', 'Equipment already exists.');
        }

        $equipmentsModel->insert($data);
        $this->session->setFlashdata('success', 'Equipment added successfully.');

        return redirect()->to('/equipments');
    }


    public function edit($id)
    {
        $equipmentsModel = new Model_Equipments();

        $data = [
            'title' => "Axion - Edit Equipment",
            'equipment' => $equipmentsModel->find($id)
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('view_equipment_edit', $data)
            . view('include/view_footer');
    }


    public function update($id)
    {
        $equipmentsModel = new Model_Equipments();

        $existing = $equipmentsModel->find($id);
        if (!$existing) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Equipment not found');
        }

        $data = [
            'item_name'      => $this->request->getPost('item_name'),
            'item_type'      => $this->request->getPost('item_type'),
            'parent_item_id' => $this->request->getPost('parent_item_id') ?: null,
            'quantity'       => $this->request->getPost('quantity'),
            'item_condition' => $this->request->getPost('item_condition'),
            'location'       => $this->request->getPost('location'),
            'status'         => $this->request->getPost('status'),
        ];

        $rules = [
            'item_name' => "required|min_length[2]|is_unique[inventory.item_name,item_id,{$id}]",
            'quantity' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('validation', $this->validator);
        }

        $equipmentsModel->update($id, $data);
        $this->session->setFlashdata('success', 'Equipment updated successfully.');

        return redirect()->to('/equipments');
    }


    public function delete($id)
    {
        $equipmentsModel = new Model_Equipments();
        $equipmentsModel->delete($id);

        $this->session->setFlashdata('success', 'Equipment deleted successfully.');
        return redirect()->to('/equipments');
    }
}
