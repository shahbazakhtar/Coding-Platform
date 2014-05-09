<?php
$name=$_POST["name"];
$q=$_POST['str'];
$dim=$_POST['dim'];
$type=$_POST['type'];
$lang=$_POST['lang'];
$type=strtolower($type);
$hint="";
if (strlen($q)>0) {
	if($dim==1)
	{
		if($lang=="C")
		{
		if($dim==1)
		{
			$hint.='int* '.$name;
			$hint.='<br>';
			$hint.='int '.$name.'[]';
		}
		if($dim==2)
		{
			echo $dim;
			$hint.='int** '.$name;
		}
	    }
	    if($lang=="Java")
	    {
			if($dim==1)
		    {
			$hint.='int[] '.$name;
			$hint.='<br>';
			$hint.='ArrayList '.$name;
			$hint.='<br>';
			$hint.='Set<Integer> '.$name;
		    }
		    if($dim==2)
		    {
			$hint.='int[][] '.$name;
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
