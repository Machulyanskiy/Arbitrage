<?php
class Stat_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    public function getRealIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))        // Определяем IP
        { $ip=$_SERVER['HTTP_CLIENT_IP']; }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
        { $ip=$_SERVER['HTTP_X_FORWARDED_FOR']; }
        else { $ip=$_SERVER['REMOTE_ADDR']; }
        return $ip;
    }
    
    public function writeLog()  {
        $file=realpath(FCPATH)."/static/log/stat.log";    // файл для записи истории посещения сайта
        $col_zap=4999;    // ограничиваем количество строк log-файла 
        if (strstr($_SERVER['HTTP_USER_AGENT'], 'YandexBot')) {$bot='YandexBot';} //Выявляем поисковых ботов
        elseif (strstr($_SERVER['HTTP_USER_AGENT'], 'Googlebot')) {$bot='Googlebot';}
        else { $bot=$_SERVER['HTTP_USER_AGENT']; }
        
        $ip = $this->getRealIpAddr();
        $date = date("H:i:s d.m.Y");        // определяем дату и время события
        $home = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];    // определяем страницу сайта
        $lines = file($file);
        while(count($lines) > $col_zap) array_shift($lines);
        $lines[] = $date."|".$bot."|".$ip."|".$home."|\r\n";
        file_put_contents($file, $lines);
    }
    
    function viewArticle($id) {
        $this->db->start_cache();
        $this->db->where('idArticle', $id);
        $this->db->set('viewsArticle', 'viewsArticle+1', FALSE);
        $this->db->update('article');
        $this->db->stop_cache();
        $this->db->flush_cache();
        
        //$this->db->start_cache();
        //$this->db->where('idArticle', $id);
        //$this->db->set('viewsArticle', 'viewsArticle+1', FALSE);
        //$this->db->update('article');
        //$this->db->stop_cache();
        //$this->db->flush_cache();
    }
    
    function viewCategory($name)
    {
        $this->db->start_cache();
        $this->db->where("nameCategoryView",$name);
        $this->db->where("dateCategoryView",date('Y-m-d'));
        $this->db->set('numberCategoryView','numberCategoryView+1',false);
        $result = $this->db->update("categoryView");
        $rows = $this->db->affected_rows();
        $this->db->stop_cache();
        $this->db->flush_cache();
       // $afftectedRows=$this->db->affected_rows();
        if($rows>0)
        {
            return true;
        }
        else
        {
            $this->db->start_cache();
            $data = array(
                "nameCategoryView"=>$name,
                "dateCategoryView"=>date('Y-m-d')
            );
            $this->db->insert("CategoryView",$data);
            $this->db->stop_cache();
            $this->db->flush_cache();
        }
    }
}
?>