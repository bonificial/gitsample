<?php
include_once 'config/dbh.php';

class Portfolio extends Dbh{
    
    public $portfolio_id;
    public $Skills;
    public $Experience;
    public $id_profile;
    
    protected function add_portfolio($id_profile,$skills,$experience){
        $sql = "INSERT INTO portfolio (skills,experience,id_profile) VALUES(?,?,?)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$skills,$experience,$id_profile]);
        return array('message' => 'portfolio successfully added!','status' => 'success');
        $stmt = null;
    }
    protected function update_portfolio($id_portfolio,$skills,$experience){
        $sql = ($experience !== null)?"UPDATE portfolio SET skills=?,experience=? WHERE id_portfolio=?":
                                    "UPDATE portfolio SET skills=? WHERE id_portfolio=?";
        $stmt = $this->connect()->prepare($sql);
        ($experience !== null)?$stmt->execute([$skills,$experience,$id_portfolio]):
                             $stmt->execute([$skills,$id_portfolio]);
        return array('message' => 'portfolio successfully updated!','status' => 'success');
        $stmt = null;
    } 
    protected function delete_portfolio($id_portfolio){
        $sql = "DELETE FROM portfolio WHERE id_portfolio=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id_portfolio]);
        return array('message' => 'portfolio successfully deleted!','status' => 'success');
        $stmt = null;
    } 
}