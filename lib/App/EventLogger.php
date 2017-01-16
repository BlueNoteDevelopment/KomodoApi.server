<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

/**
 *  EventLogger: Provides static methods to create log entries for Service events
 *
 * @author swm03
 * 
 */
class EventLogger {
    //put your code here
    /**
     * 
     * @param App\Repository $repository
     * @param Spot\Models\EventLog $eventEntity
     * @return boolean
     */    
    static function addEventLogEntry($repository,$eventEntity){
        $mapper = $repository->EventLogs();        
        $mapper->save($eventEntity);
        return true;
    }
    
    static function addQuickEventLogEntry($repository,$message,$source,$userid,$code,$level = 1,$collectionId=0){
        $evt = new Models\EventLog();
    
        $evt->log_name = 'SERVER' ;
        $evt->event_datetime_utc =  new \DateTime() ;
        $evt->timezone_offset = date_offset_get( new \DateTime()) ;
        $evt->source = $source;
        $evt->ip = $_SERVER['REMOTE_ADDR'];
        $evt->event_code = $code;
        $evt->login_id = $userid;
        $evt->message = $message;
        $evt->level = $level;
        $evt->object_data = null;
        $evt->collection_id = $collectionId;
        
        return EventLogger::addEventLogEntry($repository,$evt);
    }
    
    
}
