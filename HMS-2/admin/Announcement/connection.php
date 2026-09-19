<?php

Class Connection
  {
        private $db;

        public function __construct() {
                ob_start();
        include '../../Includes/config.php';

        $this->db = $con;
        }
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}
    public function getannouncements()
    {
            $statement=$this->db->query("SELECT * FROM announcement ORDER BY created_at DESC");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
//     public function addannouncement($announcement)
// {
//     $statement= $this->pdo->prepare("INSERT INTO notes (announcement_subject, announcement_text, created_at)
//                                      VALUES (:title, :description, :date)");
    
//     $statement->bindValue('announcement_subject', $announcement['announcement_subject']);
//     $statement->bindValue('announcement_text', $announcement['announcement_text']);
//     $statement->bindValue('created_at', date('Y-m-d H:i:s'));
//     return $statement->execute();
// }
}

return new Connection();