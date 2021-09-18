<?php
namespace App\Helper;
use Illuminate\Support\Facades\Storage;

class Log {

    public $log_name = "Admin", $causer_id = 1, $causer_type = "Admin", $subject_id, $subject_type, $description, $properties, $date_time;

    public function setOpt($log_name, $causer_id, $causer_type)
    {
        $this->log_name=$log_name;
        $this->causer_id = $causer_id;
        $this->causer_type = $causer_type;
    }

    public function setReq($subject_id, $subject_type, $description, $properties){
            date_default_timezone_set("Asia/Rangoon");
            $date_time = date("d-m-Y h:i:sa");
            $this->subject_id=$subject_id;
            $this->subject_type = $subject_type;
            $this->description = $description;
            $this->properties = $properties;
            $this->date_time = $date_time;
    }
    
    public function store()
    {
        $data['Date Time'] = $this->date_time;
        $data['Log Name'] = $this->log_name;
        $data['Causer Id'] = $this->causer_id;
        $data['Causer Model Type'] = $this->causer_type;
        $data['Subject Id'] = $this->subject_id;
        $data['Subject Model Type'] = $this->subject_type;
        $data['Description'] = $this->description;
        $data['Properties'] = \json_encode($this->properties);
        $log_file = date('d-m-Y'). ".txt";
        //$contents = Storage::get($log_file);
        if (Storage::disk('log')->exists($log_file)) {
            $contents = Storage::disk('log')->get($log_file);
            $contents .= \json_encode($data);
            Storage::disk('log')->put("$log_file", $contents);
        }else {
            Storage::disk('log')->put("$log_file", \json_encode($data));
        }
       return true;
    }
}