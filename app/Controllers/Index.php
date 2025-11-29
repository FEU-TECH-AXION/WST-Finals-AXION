<?php

namespace App\Controllers;

class Index extends BaseController {
    public function index() {

        // Add dynamic data to the views
        $data = array(
            'title' => ' AXION App Landing',
            // This can be from the database
            'name' => 'JayCee', 
        );

        return view('include\view_head', $data)
            .view('include\view_nav')
            .view('view_index', $data)
            .view('include\view_footer');
    }

    public function add($num1, $num2){
        $sum = $num1 + $num2;
        echo "The sum of $num1 and $num2 is $sum";
    }
}

?>