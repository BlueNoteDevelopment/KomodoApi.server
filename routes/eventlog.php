<?php

$app->map(['POST','PUT'],"/api/eventlog", function ($request, $response, $arguments) {
    $data = $request->getParsedBody();
    
    if ($data===null || $data==='' ){
        throw new Exception("POST data is empty",500);
    }
    
    $evtdata = array_change_key_case($data);
    unset($data);
    
    $t = $this->token;
    
    $evt = new App\Models\EventLog();
    
    $evt->log_name = (isset($evtdata['logname'])) ? $evtdata['logname'] : 'GENERAL' ;
    $evt->event_datetime_utc = (isset($evtdata['utcdatetime'])) ? $evtdata['utcdatetime'] : new DateTime() ;
    $evt->timezone_offset = (isset($evtdata['timezoneoffset']))? $evtdata['timezoneoffset'] : 0  ;
    $evt->source = (isset($evtdata['computername'])) ?  $evtdata['computername'] : '';
    $evt->ip = $_SERVER['REMOTE_ADDR'];
    $evt->event_code = (isset($evtdata['code']))? $evtdata['code'] : '';
    $evt->login_id = (isset($t->decoded->sub->id)) ? $t->decoded->sub->id : 0;
    $evt->message = (isset($evtdata['message'])) ? $evtdata['message'] : '';
    $evt->level = (isset($evtdata['level'])) ? $evtdata['level'] : 0;
    $evt->object_data = (isset($evtdata['objectdata'])) ? json_encode($evtdata['objectdata']) : null;
    $evt->collection_id = (isset($evtdata['collectionid'])) ? $evtdata['collectionid']: 0;
    
    $mapper = $this->repository->EventLogs();        
    $mapper->save($evt);
    
    
    $result = ["status" => "OK", "code" =>0];

    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
});

/*
 * @api /api/eventlog/{days}[/{level}]
 */
$app->get("/api/eventlog/{days:[0-9]+}[/{level:[0-9]}]", function ($request, $response, $arguments) {
    return executeGetEventLog($this,$request, $response, $arguments);
});

$app->get("/api/eventlog/{source}/{days:[0-9]+}[/{level:[0-9]}]", function ($request, $response, $arguments) {
    return executeGetEventLog($this,$request, $response, $arguments);
});

$app->delete("/api/eventlog/{source}/{days:[0-9]+}", function ($request, $response, $arguments) {
    return executeDeleteEventLog($this,$request, $response, $arguments);
});

$app->delete("/api/eventlog/{days:[0-9]+}", function ($request, $response, $arguments) {
    return executeDeleteEventLog($this,$request, $response, $arguments);
});

/*
 * DRY method for executing Event Log querry
 * @function executeGetEventLog
 * @param $target => $app ($this)
 * 
 */
function executeGetEventLog($target,$request, $response, $arguments){
        
    $mapper = $target->repository->EventLogs();  
    
    
    $date =  new DateTime();
    $date->sub( new DateInterval('P'.abs($arguments['days']).'D'));
    
    $where = ['event_datetime_utc >=' => $date->format('Y-m-d')];
    
    if(array_key_exists('source', $arguments)){
        $where['source ='] = $arguments['source'];
    }
    
    if(array_key_exists('level', $arguments)){
        $where['level >='] = $arguments['level'];
    }
    
    $e = $mapper->all()->where($where)->limit(500)->execute();
    
    $result = $e->jsonSerialize();

    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
}


function executeDeleteEventLog($target,$request, $response, $arguments){
        
    $mapper = $target->repository->EventLogs();  
    
    
    $date =  new DateTime();
    $date->sub( new DateInterval('P'.abs($arguments['days']).'D'));
    
    $where = ['event_datetime_utc <=' => $date->format('Y-m-d')];
    
    if(array_key_exists('source', $arguments)){
        $where['source ='] = $arguments['source'];
    }
    
    $mapper->delete($where);
    
    $result = ["status" => "OK", "code" =>0];

    return $response->withStatus(201)
        ->withHeader("Content-Type", "application/json")
        ->write(json_encode($result, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
}

//[/{hostname}]
