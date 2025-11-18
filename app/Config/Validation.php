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

    public array $signup = [
        // rules for signup
        
        'profile' => [
            'uploaded[profile]',
            'mime_in[profile, image/jpg,image/jpeg,image/png]',
            'max_size[profile, 4096]'
        ],
        // label, rules, errors
        'username' => [
            'label' => 'Username',
            'rules' => 'required|alpha_dash|min_length[6]|is_unique[tblusers.username]',
            'errors' => [
                'required' => 'All users must have a username.',
                'alpha_dash' => 'You have entered an invalid character for username, the only allowed are....',
                'min_length' => 'Username must be at least 6 characters long.',
                'is_unique' => 'The username {value} is already used.'
            ]
        ],
        
        'password' => 'required|min_length[8]|max_length[50]',
        'confirmpassword' => 'matches[password]',
        'fullname' => 'required',
        'email' => 'required|valid_email'
    ];

    public array $editaccount = [
        // rules when editing an account
    ];
}
