<?php
include_once 'config/dbh.php';

class Finance extends Dbh{
    
    public $id_finance;
    public $type;
    public $amount;
    public $topic;
    public $transaction_date;
    public $id_profile;
    
}