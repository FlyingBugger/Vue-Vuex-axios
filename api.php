<?php

$dDates=(array)json_decode(file_get_contents("php://input"));
empty($dDates)?$dDates=$_POST:null;

if(empty($dDates)){
  if(!isset($_FILES['file'])){
    header("HTTP/1.1 403 Forbidden");
    return false;
  }
  $dir=date("Y-m-d",time());
  $pathTemp="../static/upload/".$dir;
  if(!file_exists($pathTemp)){
    mkdir($pathTemp,0777,true);
  }
  $data=$_FILES['file'];
  $type=explode(".",$data['name'])[1];
  $name=(string) time().uniqid().".".$type;

	$normal="static/upload/".$dir;

  $upAction=move_uploaded_file($data['tmp_name'],$pathTemp."/".$name);
  if($upAction){
    echo json_encode(array(
      "errorCode"=>1,
      "path"=>$normal."/".$name
    ));
  }else{
    echo json_encode(array(
      "errorCode"=>0
    ));
  }
}elseif(@$dDates['act']=='delete'){
  $target=$dDates['filePath'];
  $res=unlink($target);
  echo $res;
}elseif(@$dDates['act']=="getAll"){

  $db=new mysqli("192.168.20.104","root","102098hchab","weixin");
  $uid=(string) time().rand();

  $iexists="select count(*) from festival where `sname`='{$dDates['sname']}' and `phone`='{$dDates['phone']}' limit 1";
  $re=$db->query($iexists);
  $row=mysqli_fetch_array($re,MYSQLI_NUM);
  $time=date("Y-m-d H-i-s",time());
$infos=json_encode($dDates['short'],JSON_UNESCAPED_UNICODE);
  if($row[0]=='1'){
    echo 0;
  }else{
    $sql="INSERT INTO `festival` (`sname`, `address`, `type`, `info`, `name`, `phone`, `pics`, `uid`,`time`,`detailsAddr`,`pos`,`shortInfos`) VALUES ('{$dDates['sname']}', '{$dDates['address']}', '{$dDates['type']}', '{$dDates['info']}', '{$dDates['uname']}', '{$dDates['phone']}','{$dDates['pics']}',{$uid},'{$time}','{$dDates['detailsAddr']}','{$dDates['position']}','{$infos}')";
    $db=$db->query($sql);
    echo $db;
  }

}


  ?>
