##API Server: API Reference

###Event Log
Verbs: GET,POST/PUT, DELETE

**POST [PUT]**
*Route*: /api/eventlog
*Token* *Required*: True
*Post Body*: application/json

{
	            logName: 'service', //name of the log to write to. 
	            utcDateTime: d.toUTCString(), //utc datetime (required)
	            timezoneOffset: -1 * (d.getTimezoneOffset() / 60), //+/- 11
	            level: 0, //1=message,2=warning,3=error,4=critical
	            message: '', //display message
	            objectData: null, //optional json object to store as string
	            computerName: os.hostname(), //optional system client name
	            collectionId: '', //identifier for collection error originates from
	            clientId: '', //client account id
        };

**GET**
*Route*: /api/eventlog/{days}[/level]
*Token* *Required*: True

days: # of days to retrieve the log entries
level: minimum error level `<optional>`

*example:*
/api/eventlog/30/3
Get the last 30 days of log entries with level of ERROR or CRITICAL

*Route*:/api/eventlog/{source}/{days}[/{level}]
*Token* *Required*: True

source: name of host computer to get logs for
days: # of days to retrieve the log entries
level: minimum error level `<optional>`

*example:*
/api/eventlog/HOST1/30/0
Get the last 30 days of log entries with any log level for service machine named HOST1

**DELETE**
*Route*: /api/eventlog/{source}/{days}
*Token* *Required*: True

source: name of host computer to get logs for
days: # of days to keep the log entries

*example:*
/api/eventlog/HOST1/30
Deletes the from the beginning of time until last 30 days of log entries with any log level for service machine named HOST1

*Route*: /api/eventlog/{days}
*Token* *Required*: True

days: # of days to keep the log entries

*example 1:*
/api/eventlog/HOST1/30
Deletes the from the beginning of time until last 30 days of log entries 

*example 2:*
/api/eventlog/HOST1/0
Deletes all log entries
