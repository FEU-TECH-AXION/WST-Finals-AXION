<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    // --------------------------------------------------------------------
    // USER AUTHENTICATION RULES
    // --------------------------------------------------------------------
    public array $signin = [
        // rules when signing in
        'email' => 'required|valid_email',
        'password' => 'required'
    ];

    public array $forgotPassword = [
        // rules when signing in
    ];

    public array $signup = [
        // rules for signup
        
        'first_name' => [
            'rules' => 'required|regex_match[/^[A-Za-z\s\-.]+$/]',
            'errors' => [
                'required' => 'First name is required.',
                'regex_match' => 'First name can only contain letters, spaces, hyphens, and periods.'
            ]
        ],
        'middle_name' => [
            'rules' => 'permit_empty|regex_match[/^[A-Za-z\s\-.]+$/]',
            'errors' => [
                'regex_match' => 'Middle name can only contain letters, spaces, hyphens, and periods.'
            ]
        ],
        'last_name' => [
            'rules' => 'required|regex_match[/^[A-Za-z\s\-.]+$/]',
            'errors' => [
                'required' => 'Last name is required.',
                'regex_match' => 'Last name can only contain letters, spaces, hyphens, and periods.'
            ]
        ],
        'user_photo' => [
            'uploaded[user_photo]',
            'mime_in[user_photo, image/jpg,image/jpeg,image/png]',
            'max_size[user_photo, 4096]'
        ],
        'extension_name' => [
            'rules' => 'permit_empty|regex_match[/^(Jr\.?|Sr\.?|I{1,3}|IV|V|VI{0,3}|X)$/i]',
            'errors' => [
                'regex_match' => 'Please enter a valid extension like Jr., Sr., III, IV.',
            ]
        ],
        'password' => [
            'rules' => 'required|min_length[8]|regex_match[/[@_!]/]',
            'errors' => [
                'required' => 'Password is required.',
                'min_length' => 'Password must be at least 8 characters long.',
                'regex_match' => 'Password must include at least one special character (@, _, or !).'
            ]
        ],

        'confirmpassword' => 'matches[password]',
        'email' => 'required|valid_email'
    ];

    public array $editaccount = [
        // rules when editing an account
    ];

    // --------------------------------------------------------------------
    // INVENTORY MANAGEMENT RULES
    // --------------------------------------------------------------------

    public array $addInventory = [
        'item_name' => [
            'label'  => 'Item Name',
            'rules'  => "required|regex_match[/^[A-Za-z\s,'-]+$/]",
            'errors' => [
                'required'    => 'Item name is required.',
                'regex_match' => 'Item name must contain only letters, spaces, comma, and apostrophe.',
            ],
        ],
        'item_type' => [
            'label'  => 'Item Type',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Item type is required.',
            ],
        ],
        'quantity' => [
            'label'  => 'Quantity',
            'rules'  => 'required|integer|greater_than[0]',
            'errors' => [
                'required'     => 'Quantity is required.',
                'integer'      => 'Quantity must be a whole number.',
                'greater_than' => 'Quantity must be greater than 0.',
            ],
        ],
        'location' => [
            'label'  => 'Location',
            'rules'  => 'permit_empty|max_length[50]',
            'errors' => [
                'max_length' => 'Location must not exceed 50 characters.',
            ],
        ],
        'status' => [
            'label'  => 'Status',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Status is required.',
            ],
        ],
    ];

    public array $editInventory = [
        'item_name' => [
            'label'  => 'Item Name',
            'rules'  => "required|regex_match[/^[A-Za-z\s,'-]+$/]",
            'errors' => [
                'required'    => 'Item name is required.',
                'regex_match' => 'Item name must contain only letters, spaces, comma, and apostrophe.',
            ],
        ],
        'item_type' => [
            'label'  => 'Item Type',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Item type is required.',
            ],
        ],
        'quantity' => [
            'label'  => 'Quantity',
            'rules'  => 'required|integer|greater_than[0]',
            'errors' => [
                'required'     => 'Quantity is required.',
                'integer'      => 'Quantity must be a whole number.',
                'greater_than' => 'Quantity must be greater than 0.',
            ],
        ],
        'location' => [
            'label'  => 'Location',
            'rules'  => 'permit_empty|max_length[50]',
            'errors' => [
                'max_length' => 'Location must not exceed 50 characters.',
            ],
        ],
        'status' => [
            'label'  => 'Status',
            'rules'  => 'required',
            'errors' => [
                'required' => 'Status is required.',
            ],
        ],
    ];




}
