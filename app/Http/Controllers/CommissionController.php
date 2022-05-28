<?php

namespace App\Http\Controllers;

use App\Classes\ManagePosTeller;
use Illuminate\Http\Request;

class CommissionController extends Controller
{

    public $manager;

    public function __construct()
    {
        $this->manager = new ManagePosTeller();
    }


    public function index()
    {
        return $this->manager->fetchAllCommission();
    }

    public function store(Request $request)
    {
        return $this->manager->createCommission($request);
    }

    public function logAgentTeller(Request $request)
    {
        return $this->manager->createAgentTeller($request);
    }

    public function transactionOnPlatform(Request $request)
    {
        $query = $request->query('date');
        return $this->manager->fetchTransactions($query);
    }
}
