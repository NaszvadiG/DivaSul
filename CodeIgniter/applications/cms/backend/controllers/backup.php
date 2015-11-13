<?php
class Backup extends MX_Controller
{
    function index()
    {
        $this->load->view('backup');
    }

    function download()
    {
        $this->load->dbutil();
    
        $prefs = array(     
            'format' => 'zip',             
            'filename' => 'backup.sql'
        );

        $backup = &$this->dbutil->backup($prefs); 

        $db_name = 'backup_'. date("Y-m-d-H-i-s") .'.zip';
        $save = SERVERPATH.'cms/media/backup/'.$db_name;

        $this->load->helper('file');
        write_file($save, $backup); 

        $this->load->helper('download');
        force_download($db_name, $backup); 
    }
}
?>
