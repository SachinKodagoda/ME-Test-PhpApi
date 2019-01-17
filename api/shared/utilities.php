<?php
    class Utilities{
        function getPaging($page, $total_rows, $records_per_page, $page_url){

            // INITIALIZE PAGING ARRAY
            $paging_arr = array();

            // LINK FOR FIRST PAGE BUTTON
            $paging_arr["first"] = $page > 1 ? "{$page_url}page=1" : "";

            // LINK FOR PRIVIOUS PAGE BUTTON
            $pre_val = $page - 1;
            $paging_arr["pervious"] = $pre_val > 0 ? "{$page_url}page={$pre_val}" : "{$page_url}page=1";

            // CALCULATE TOTAL PAGES
            $total_pages = ceil($total_rows / $records_per_page);

            // LINK RANGE TO SHOW 3=> 123 #4# 567
            $range = 2;

            // DISPLAY PAGES AROUND THE CURRENT PAGE
            $initial_num = $page - $range;
            $critical_num = $total_pages - (2 * $range);
            if($initial_num <= 0){
                $condition_limit_num = ($page + $range - $initial_num)  + 2;
            }else{
                $condition_limit_num = ($page + $range)  + 1;
            }
            if($initial_num >= $critical_num){
                $initial_num = $critical_num;
            }

            $paging_arr['pages'] = array();
            $page_count=0;

            for($x = $initial_num; $x < $condition_limit_num; $x++){
                if(($x > 0) && ($x <= $total_pages)){
                    $paging_arr['pages'][$page_count]["page"] = $x;
                    $paging_arr['pages'][$page_count]["url"] = "{$page_url}page={$x}";
                    $paging_arr['pages'][$page_count]["current_page"] = $x==$page ? "yes" : "no";
                    $page_count++;
                }
            }

            // LINK FOR PRIVIOUS PAGE BUTTON
            $next_val = $page + 1;
            $paging_arr["next"] = $next_val >= $page_count ? "{$page_url}page={$total_pages}" : "{$page_url}page={$next_val}";

            // LINK FOR LAST PAGE BUTTON
            $paging_arr["last"] = $page<$total_pages ? "{$page_url}page={$total_pages}" : "";

            // RETURN DATA IN JSON FORMAT
            return $paging_arr;
        }

    }
?>