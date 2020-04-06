<?php
include_once 'config/dbhmodel.php';

class Wallet extends Dbh{
    public $id_wallet;
    public $amount;
    public $account_number;
    public $id_user;

}