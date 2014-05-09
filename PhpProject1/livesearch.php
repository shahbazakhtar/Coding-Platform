<?php
$name=$_POST["name"];
$q=$_POST['str'];
$dim=$_POST['dim'];
$type=$_POST['type'];
$lang=$_POST['lang'];
$id=$_POST['id'];
$type=strtolower($type);
$hint="";
if (strlen($q)>0) {
		if($lang=="C")
		{
		if($dim==1)
		{
			echo '<select name="dropdown" onchange="setData(this.value,'.$id.')">
			      <option value="">-- Select --</option>
                  <option value="int*">int* '.$name.' </option>
                  <option value="int[]">int '.$name. '[]</option>
                  </select>';
		}
		if($dim==2)
		{
			echo '<select name="dropdown" onchange="setData(this.value,'.$id.')">
			      <option value="">-- Select --</option>
                  <option value="int**">int** '.$name.' </option>
                  </select>';
		}
	    }
	    if($lang=="Java")
	    {
			if($dim==1)
		    {
			echo '<select name="dropdown" onchange="setData(this.value,'.$id.')">
			      <option value="">-- Select --</option>
                  <option value="int[]">int[] '.$name.' </option>
                  <option value="ArrayList">ArrayList '.$name. '</option>
                  <option value="Set&lt;Integer&gt;">Set&ltInteger&gt '.$name. '</option>
                  </select>';
		    }
		    if($dim==2)
		    {
			echo '<select name="dropdown" onchange="setData(this.value,'.$id.')">
			      <option value="">-- Select --</option>
                  <option value="int[][]">int[][] '.$name.' </option>
                  </select>';
		    }
		}
		
} 
if ($hint=="") {
  $response="no suggestion";
} else {
  $response=$hint;
}

?>
