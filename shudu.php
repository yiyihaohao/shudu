<?php
/*
用PHP来计算数独游戏  喜欢可以START
*/
//定义一个二维数组
$arr = [
    [0,0,0,0,0,0,0,3,1],
    [0,0,2,0,9,0,0,0,0],
    [5,1,8,0,0,0,0,0,0],
    [7,0,0,0,0,0,0,0,2],
    [4,0,0,0,7,0,6,9,8],
    [0,9,0,0,0,6,5,0,0],
    [0,0,0,0,1,3,0,0,0],
    [0,6,0,0,5,7,1,0,0],
    [2,0,0,6,0,0,0,7,0],
];

$list = [];
$temp_area = [];

for($i =0;$i < count($arr);$i++){

    for($l =0;$l < count($arr[$i]);$l++){
        $value = $arr[$i][$l];
        $x = $i+1;
        $y = $l+1;
        
        $area = get_area($x,$y);

        //echo $x.'--'.$y.'==>'.$area."\r\n";
        $no = get_no($area,$temp_area);
        $obj = new item($value,$x,$y,$area,$no,$list);
        $list[] = $obj;
    }

}


do{
    

    foreach($list as &$t){
    
        if($t->value !== 0){
            continue;
        }
        
        $t->check_area($list);
        if($t->value){
            echo $t->area.'--'.$t->no.'---'.$t->value."\r\n";
            $arr[($t->x)-1][($t->y)-1] = $t->value;
        }
            
       
    }

    foreach($list as &$t){
        
        if($t->value !== 0){
            continue;
        }
        
            
        $t->diff($list);
        if($t->value){
            echo $t->area.'--'.$t->no.'---'.$t->value."\r\n";
            $arr[($t->x)-1][($t->y)-1] = $t->value;
        }
    }
    // if($i == 27)
    // {var_dump($list[38]);    exit;}

    
        $list = [];
        $temp_area = [];

        for($i =0;$i < count($arr);$i++){

            for($l =0;$l < count($arr[$i]);$l++){
                $value = $arr[$i][$l];
                $x = $i+1;
                $y = $l+1;
                
                $area = get_area($x,$y);

                //echo $x.'--'.$y.'==>'.$area."\r\n";
                $no = get_no($area,$temp_area);
                $obj = new item($value,$x,$y,$area,$no,$list);
                $list[] = $obj;
            }

        }



} while(get_zero($list) > 0);

foreach($arr as $l){
    echo join(' , ',$l)."\r\n";
}

function get_zero($list){
    $i = 0;
    foreach($list as &$t){
       if($t->value === 0){
            $i++;
        }
    }
    return $i;
}

function get_no($area,&$temp_area){
    if(isset($temp_area[$area])){
        $temp_area[$area]++;
    }else{
        $temp_area[$area] = 1;
    }
    return $temp_area[$area];
}

function get_area($x,$y){
    
    if($x <=3){
        $xx=0    ;
    }else if($x>3 & $x <=6){
        $xx=1    ;
    }else if($x>6 & $x <=9){
        $xx=2    ;
    }

    if($y>0 & $y <=3){
        return $xx*3+1;
    }
    if($y>3 & $y <=6){
        return $xx*3+2;
    }
    if($y>6 & $y <=9){

        return $xx*3+3;
    }
}

class item{
    public $value;
    public $x;
    public $y;
    public $area;
    public $no;
    //public $list;
    public $v1 = null;
    public $nohas=[];
    
    
    function __construct($value,$x,$y,$area,$no){
        $this->area = $area;
        $this->x = $x;
        $this->y = $y;
        $this->no = $no;
        //$this->list = $list;
        $this->value = $value;
        
        
    }

    public function check_area(& $list){
        $nohas = [1,2,3,4,5,6,7,8,9];
        foreach($list as  $temp){
            if($temp == $this) {
                continue;
            }
            if($this->value != 0){
                continue;
            }
            if($temp->area == $this->area && $temp->value != 0){
                
                $key = array_search($temp->value, $nohas,true);
                
                if ($key !== false) {
                    
                    unset($nohas[$key]);
            
                }
            }


            if($temp->x == $this->x && $temp->value != 0){
                $key = array_search($temp->value, $nohas);
                if ($key !== false) {
                    unset($nohas[$key]);
                }
            }

            if($temp->y == $this->y && $temp->value != 0){
                $key = array_search($temp->value, $nohas);
                if ($key !== false) {
                    unset($nohas[$key]);
                }
            }
        }
        if(count($nohas) === 1){
            $this->value = current($nohas);
            //return;
        }
        

        $this->nohas = $nohas;
        
    }

    public function diff( $list){
        //block diff
        $block_arr = [];
        $diff=[];
        foreach($list as  $temp){
            if($temp == $this) {
                continue;
            }
            if($this->value != 0){
                continue;
            }
            
            if($temp->area == $this->area ){
                // var_dump($temp->area."---".$temp->no);
                // var_dump($temp->nohas);
               $block_arr = array_merge($temp->nohas,$block_arr);
                
            }

        }
        $diff = array_diff($this->nohas,$block_arr);
        if(count($diff) == 1){
            $this->value = current($diff);
            // var_dump($this);
            // exit;
            // return;
        }

        //x
        $block_arr = [];
        $diff=[];
        foreach($list as  $temp){
            if($temp == $this) {
                continue;
            }
            if($this->value != 0){
                continue;
            }
            if($temp->x == $this->x  ){
                $block_arr = array_merge($temp->nohas,$block_arr);
            }

        }
        $diff = array_diff($this->nohas,$block_arr);
        if(count($diff) == 1){
            $this->value = current($diff);
            return;
        }

        //y
        $block_arr = [];
        $diff=[];
        foreach($list as  $temp){
            if($temp == $this) {
                continue;
            }
            if($this->value != 0){
                continue;
            }
            if($temp->y == $this->y  ){
                $block_arr = array_merge($temp->nohas,$block_arr);
            }

        }
        $diff = array_diff($this->nohas,$block_arr);
        if(count($diff) == 1){
            $this->value = current($diff);
            return;
        }

    }
}
