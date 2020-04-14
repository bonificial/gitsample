<?php
include_once 'config/dbhmodel.php';

class Certification extends Dbh{
    
    public $id_certificate;
    public $title;
    public $description;
    public $issuedBy;
    public $issuedOn;
    public $id_portfolio;

}