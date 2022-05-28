<?php


namespace App\Traits;


use App\Models\AccountType;
use App\Models\Country;
use App\Models\EducationalQualification;
use App\Models\EmploymentStatus;
use App\Models\IdCardType;
use App\Models\LGA;
use App\Models\LoanRepayment;
use App\Models\MonthlyIncome;
use App\Models\PaymentCard;
use App\Models\ResidentialStatus;
use App\Models\State;
use App\Models\Transaction;
use App\Models\TrBank;
use App\Models\TrWallet;
use App\Models\User;
use App\Models\UserActivity;

trait ManagesEndpoints
{
    protected $endpoints = [
        'states' => State::class,
        'lgas' => LGA::class,
        'residential-statuses' => ResidentialStatus::class,
        'employment-statuses' => EmploymentStatus::class,
        'educational-qualifications' => EducationalQualification::class,
        'monthly-incomes' => MonthlyIncome::class,
        'loan-repayments' => LoanRepayment::class,
        'payment-cards' => PaymentCard::class,
        'countries' => Country::class,
        'account-types' => AccountType::class,
        'user-activities' => UserActivity::class,
        'id-cards' => IdCardType::class,
        'transactions' => Transaction::class,
        'wallet-transactions' => TrWallet::class,
        'bank-transactions' => TrBank::class,

    ];

    protected $rules = [
        'states' => [
            'store' => [
                'state' => 'required',
                'capital' => 'required',
            ],
            'update' => [
                'state' => 'required',
                'capital' => 'required',
            ]
        ],
        'lgas' => [
            'store' => [
                'state_id' => 'required',
                'lga' => 'required',
            ],
            'update' => [
                'state_id' => 'required',
                'lga' => 'required',
            ]
        ],
        'residential-statuses' => [
            'store' => [
                'status' => 'required',
            ],
            'update' => [
                'status' => 'required',
            ]
        ],
        'educational-statuses' => [
            'store' => [
                'status' => 'required',
            ],
            'update' => [
                'status' => 'required',
            ]
        ],
        'educational-qualifications' => [
            'store' => [
                'name' => 'required',
            ],
            'update' => [
                'name' => 'required',
            ]
        ],
        'monthly-incomes' => [
            'store' => [
                'range' => 'required',
            ],
            'update' => [
                'range' => 'required',
            ]
        ],
        'loan-repayments' => [
            'store' => [
                'loan_id' => 'required',
                'amount' => 'required',
            ],
            'update' => [
                'loan_id' => 'required',
                'amount' => 'required',
            ]
        ],
        'payment-cards' => [
            'store' => [

            ],
            'update' => [

            ]
        ],

    ];

    protected $path = [

    ];

    protected $paginate = [
        'payment-cards',
    ];

}
