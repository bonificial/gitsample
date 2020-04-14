<?php
include_once 'config/dbh.php';

class MessageRecipient extends Dbh{
    public $id_messr;
    public $is_read;
    public $recipient_id;
}