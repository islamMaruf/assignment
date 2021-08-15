<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Student;
use Illuminate\Http\Request;


class DashboardController extends BaseController
{
    public function __invoke()
    {
        $data['students'] = Student::count();
        $data['departments'] = Department::count();
        return $this->sendResponse($data, "Dashboard statistics");
    }
}
