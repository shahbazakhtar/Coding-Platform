<?php
$xmlDoc=new DOMDocument();
$xmlDoc->load("links.xml");

$x=$xmlDoc->getElementsByTagName('data');

//get the q parameter from URL
$q=$_POST[''];
if (strlen($q)>0) {
  $hint="";
  for($i=0; $i<($x->length); $i++) {
    $y=$x->item($i)->getElementsByTagName('type');
    $z=$x->item($i)->getElementsByTagName('suggestion');
    if ($y->item(0)->nodeType==1) {
      //find a link matching the search text
      if (stristr($y->item(0)->childNodes->item(0)->nodeValue,$q)) {
        if ($hint=="") {
          $hint=$z->item(0)->childNodes->item(0)->nodeValue;
        } else {
          $hint=$hint . "<br />".$z->item(0)->childNodes->item(0)->nodeValue ;
        }
      }
    }
  }
}
if ($hint=="") {
  $response="no suggestion";
} else {
  $response=$hint;
}

//output the response
echo $response;
?>
