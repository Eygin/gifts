<?php
    function miniMaxSum(array $arr)
    {
        $result = [];

        foreach($arr as $i) {
            $new_arr = array_filter($arr, function ($element) use ($i)
            {
                return $element !== $i;
            });

            $result[] = array_sum($new_arr);
        }
        
        $min = min($result);
        $max = max($result);
        echo $min ." ". $max;
    }

    miniMaxSum([1,2,3,4,5]);
?>