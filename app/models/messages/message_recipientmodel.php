<?php
include_once 'config/dbh.php';

class MessageRecipient extends Dbh{
    public $_id;
    public $recipientId;
    public $isRead;
    public $deleteRequestByRecipient;
}