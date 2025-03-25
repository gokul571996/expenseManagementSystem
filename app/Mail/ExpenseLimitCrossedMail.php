<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpenseLimitCrossedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $categoryName;
    public $setLimit;
    public $actualAmount;

    public function __construct($categoryName, $setLimit, $actualAmount)
    {
        $this->categoryName = $categoryName;
        $this->setLimit = $setLimit;
        $this->actualAmount = $actualAmount;
    }

    public function build()
    {
        return $this->subject('Expense Limit Crossed Alert')
                    ->view('expense_limit_crossed');
    }
}