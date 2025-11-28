<?php
namespace App\Controllers;

class Equipments extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    public function index($perpage = 4)
    {
        $equipmentsModel = model('Model_Equipments');
        $equipmentsModel->orderBy("equipment_name");
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
        $equipmentsModel = model('Model_Equipments');
        $equipment = $equipmentsModel->find($id);

        if (! $equipment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Equipment not found');
        }

        $data = [
            'title' => "Axion - Equipment Details",
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
        $validation = \Config\Services::validation();
        $equipmentsModel = model('Model_Equipments');

        $data = [
            'equipment_name' => $this->request->getPost('equipment_name'),
            'price'        => $this->request->getPost('price'),
            'stock'        => $this->request->getPost('stock'),
        ];

        if (! $validation->run($data, 'equipments')) {
            $data['title'] = "Axion - Add New Equipment";
            $data['validation'] = $validation;

            return view('include/view_head', $data)
                . view('include/view_nav')
                . view('view_equipment_add', $data)
                . view('include/view_footer');
        }

        if ($equipmentsModel->where('equipment_name', $data['equipment_name'])->first()) {
            $data['title'] = "Axion - Add New Equipment";
            $data['validation'] = $validation;
            $data['nameExists'] = "Equipment name already exists.";

            return view('include/view_head', $data)
                . view('include/view_nav')
                . view('view_equipment_add', $data)
                . view('include/view_footer');
        }

        $equipmentsModel->insert($data);
        $this->session->setFlashdata('success', 'Equipment added successfully.');

        return redirect()->to('equipments');
    }

    public function edit($id)
    {
        $equipmentsModel = model('Model_Equipments');

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
        $equipmentsModel = model('Model_Equipments');
        $validation = \Config\Services::validation();

        $existingEquipment = $equipmentsModel->find($id);
        if (! $existingEquipment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Equipment not found');
        }

        $data = [
            'equipment_name' => $this->request->getPost('equipment_name'),
            'price'        => $this->request->getPost('price'),
            'stock'        => $this->request->getPost('stock'),
        ];

        $rules = [
            'equipment_name' => [
                'label' => 'Equipment Name',
                'rules' => "required|min_length[3]|max_length[100]|is_unique[tblequipments.equipment_name,id,{$id}]",
                'errors' => [
                    'required'   => 'Please enter a equipment name.',
                    'min_length' => 'Equipment name must be at least 3 characters.',
                    'max_length' => 'Equipment name cannot exceed 100 characters.',
                    'is_unique'  => 'This equipment already exists.'
                ]
            ],
            'price' => [
                'label' => 'Price',
                'rules' => 'required|decimal|greater_than[0]',
                'errors' => [
                    'required'     => 'Please enter the equipment price.',
                    'decimal'      => 'Price must be a valid number.',
                    'greater_than' => 'Price must be greater than zero.'
                ]
            ],
            'stock' => [
                'label' => 'Stock',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required'              => 'Please enter the available stock.',
                    'integer'               => 'Stock must be a whole number.',
                    'greater_than_equal_to' => 'Stock cannot be negative.'
                ]
            ]
        ];

        if (! $this->validate($rules)) {
            $data['title'] = "Axion - Edit Equipment";
            $data['validation'] = $validation;
            $data['equipment'] = $existingEquipment;

            return view('include/view_head', $data)
                . view('include/view_nav')
                . view('view_equipment_edit', $data)
                . view('include/view_footer');
        }

        $equipmentsModel->update($id, $data);
        $this->session->setFlashdata('success', 'Equipment updated successfully.');

        return redirect()->to('equipments');
    }

    public function delete($id)
    {
        model('Model_Equipments')->delete($id);
        $this->session->setFlashdata('success', 'Equipment deleted successfully.');

        return redirect()->to('equipments');
    }
}
?>
