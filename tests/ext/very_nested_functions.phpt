--TEST--
Check if we can safely override function being called deep in the call stack
--FILE--
<?php
function test($a){
    return 'FUNCTION ' . $a;
}

dd_trace("test", function($a){
    return 'OLD HOOK ' . test($a);
});


function callNested($nestLevel, $counter){
    if ($nestLevel > 0) {
        if ($nestLevel == 50) {
            return test(callNested($nestLevel - 1, $counter + 1));
        } else {
            return callNested($nestLevel - 1, $counter + 1);
        }
    } else {
        return test($counter);
    }
}

echo callNested(100000, 0) . PHP_EOL;

?>
--EXPECT--
OLD HOOK FUNCTION OLD HOOK FUNCTION 100000
