<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Model_Equipments;
use App\Models\Model_Reservation;

class Reserve extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();

        // // Check role: only Associates can access
        // if ($this->session->get('role') !== 'associate') {
        //     throw new \CodeIgniter\Exceptions\PageForbiddenException('Only Associates can reserve equipment.');
        // }
    }

    // Show list of equipment that can be reserved
    public function index()
    {
        $equipmentsModel = new Model_Equipments();
        $reservationModel = new Model_Reservation();

        $equipments = $equipmentsModel
            ->where('item_type', 'equipment')
            ->where('status', 'active')
            ->orderBy('item_name', 'ASC')
            ->findAll();

        // Attach availability info
        foreach ($equipments as &$item) {
            $item['reserved'] = $reservationModel
                ->where('item_id', $item['item_id'])
                ->where('status', 'active')
                ->first() ? true : false;
        }

        $data = [
            'title' => "Axion - Reserve Equipment",
            'equipments' => $equipments
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('view_reserve', $data)
            . view('include/view_footer');
    }

    // Reservation form for a specific equipment
    public function form($item_id)
    {
        $equipmentsModel = new Model_Equipments();
        $equipment = $equipmentsModel->find($item_id);

        if (!$equipment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Equipment not found');
        }

        $data = [
            'title' => "Axion - Reserve Equipment",
            'equipment' => $equipment
        ];

        return view('include/view_head', $data)
            . view('include/view_nav')
            . view('view_reserve_form', $data)
            . view('include/view_footer');
    }

    // Submit reservation
    public function submit()
    {
        $reservationModel = new Model_Reservation();
        $equipmentsModel = new Model_Equipments();

        $user_id = $this->session->get('user_id');
        $item_id = $this->request->getPost('item_id');
        $reserved_date = $this->request->getPost('reserved_date');
        $start_time = $this->request->getPost('start_time');
        $end_time = $this->request->getPost('end_time');

        // Validate date (must be at least 1 day ahead)
        $today = date('Y-m-d');
        if ($reserved_date <= $today) {
            $this->session->setFlashdata('error', 'Reserved date must be at least 1 day ahead.');
            return redirect()->back()->withInput();
        }

        // Check if equipment is already reserved for the same date & time
        $existing = $reservationModel
            ->where('item_id', $item_id)
            ->where('reserved_date', $reserved_date)
            ->where('status', 'active')
            ->first();

        if ($existing) {
            $this->session->setFlashdata('error', 'This equipment is already reserved for the selected date.');
            return redirect()->back()->withInput();
        }

        // Insert reservation
        $reservationModel->insert([
            'item_id' => $item_id,
            'user_id' => $user_id,
            'reserved_date' => $reserved_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => 'active'
        ]);

        $this->session->setFlashdata('success', 'Equipment reserved successfully.');
        return redirect()->to('/reserve');
    }
}
