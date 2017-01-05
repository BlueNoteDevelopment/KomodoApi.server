<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$host = $_SERVER['HTTP_HOST']; 

preg_match('/(?:http[s]*\:\/\/)*(.*?)\.(?=[^\/]*\..{2,5})/',$host,$output);

$client_domain = $output[1];

$func[0] = function($a,$b){
    return $a+$b;
};

$myfunc = $func[0];
$c = $myfunc(6,7);
?>

<html>

<body>
<h1>Hi2</h1>

<?php  
echo $host; 
echo '<br/>';
echo $client_domain;
echo '<br/>';
echo $c;
?>  


</body>
</html>