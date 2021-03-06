<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        /**
         * Sourcecode class binds information about the sourcecode i.e. about class_name,
         * function_name,return_type,parameters_name,parameters_type,dimensions of each parameters passed
         * 0 dim-for simple integer or char,1 dim for 1-D array,2 dim for 2-D array;
         * 
         **/
        class SourceCode
        {
            public $parameters_type=array();
            public $parameters_name=array();
            public $default_code;
            public $function_name;
            public $class_name;
            public $return_type;
            public $dimensions=array();
            public $Map=array(array());
            public $MapDimensions=array(array());
            
            /*
             * sets the default code for any language that is taken through a file "lang_name.txt"
             * @param $code ---a string of default code taken through a file "language_name.txt" where language_name may be "C" or "Java"
             * */
             
            public function setDefaultCode($code)
            {
                $this->default_code=$code;
            }
            /*
             * sets the type of parameters passed to a function by the content_provider
             * @param $type --- an array containing info about the type of each parameters whether it is integer,character or other.
             * */
            public function setParametersType($type)
            {
                $this->parameters_type=$type;
            }
            /*
             * sets the name of parameters passed to a function by the content_provider
             * @param $name--- an array containing name of each parameters.
             * */
            public function setParametersName($name)
            {
                $this->parameters_name=$name;
            }
            /*
             * sets the dimensions of each parameters passed to a function by the content_provider
             * @param $dimension --- an array containing info about the dimension of each parameters whether it is 0(integer),1(1-D array) or 2(2-D array).
             * */
            public function setDimensionOfVariables($dimension)
            {
                $this->dimensions=$dimension;
            }
            /*
             * sets the name of a function given by the content_provider
             * @param $name --- a string indicating name .
             * */
            public function setFunctionName($name)
            {
                $this->function_name=$name;
            }
             /*
             * sets the name of a class given by the content_provider
             * @param $name --- a string indicating name .
             * */
             
            public function setClassName($name)
            {
                $this->class_name=$name;
            }
            /*
             * sets the return_type of a function given by the content_provider
             * @param $type--- a string indicating return_type of function - int,char,etc;
             * */
            
            public function setReturnType($type)
            {
		$this->return_type=$type;
	    }
	   /*
             * method to get the default code for any language that is taken through a file "lang_name.txt"
             * */
            public function getDefaultCode()
            {
                return $this->default_code;
            }
            /*
             * method to get the return_type of the function given by the content provider
             * */
            public function getReturnType()
            {
                return $this->return_type;
            }
            /*
             * method to get the Classname given by the content provider
             * */
            public function getClassName()
            {
                return $this->class_name;
            }
            /*
             * method to get the functionname given by the content provider
             * */
            public function getFunctionName()
            {
                return $this->function_name;
            }
            /*
             * method to get the name of each and every parameters given by the content provider.
             * returns an array
             * */
            public function getParameterNames()
            {
                return $this->parameters_name;
            }
            
             /*
             * method to get the type of each and every parameters given by the content provider.
             * Each parameter_type is mapped to its default_type like Integer is mapped to int,Character to char
             * Also dimensions of parameters are taken care of like 1-D param is mapped to int* not to just int
             * returns an array.
             * */
             
            public function getParameterTypes()
            {
		$Map_parameters=array();
		for($i=0;$i<count($this->parameters_type);$i++)
		{
			$dim=$this->dimensions[$i];
			$str=$this->parameters_type[$i];
			$str=strtolower($str);
			$Map_parameters[$i]=$this->Map[$str][$dim];
		}
                return $Map_parameters;
            }
        }
        /*
         * Language class extends the features of SourceCode class
         * */
        class Language extends SourceCode
        {
            public $name;
            /*
             * constructor to set the name of a language of which source we want to generate;
             * */
             
            public function __construct($name) {
                $this->name=$name;
            }
            
            /*
             * method to tag parameters_type to its original type in a language.
             * like Integer in 0 dim to int or integer in 1 dimension to int* and so on. 
             * supports extensibility to any data_type in future.
             * @param $Myfile-- filename where the tagger of each types are stored.
             * Types,dimension and its tag must be separated by a space(" ") in the file
             * sets the associative_array[Type][dimension]=tag; 
             * */
            public function setinitialTag($MyFile)
            {
	       $lines=file($MyFile);
               for($i=0;$i<count($lines);$i++)
               {
	            $str=explode(" ",$lines[$i]);
	            $type=strtolower($str[0]);
	            $dim=$str[1];
	            $tag=$str[2];
	            $this->Map[$type][$dim]=$tag;
	       }
	    }
			
	    /*
             * method that replaces the string like CLASSNAME in default_code by 'class_name' given by the content_provider,
             * RETURN_TYPE by 'return_type' of the function and so on.
             * @return the sourcecode after replacement
             * */
             
            public function generateSourceCode()
            {
                $parameter_type=$this->getParameterTypes();
                $parameter_name=$this->getParameterNames();
                $final_string="";
                for($i=0;$i<count($parameter_name)-1;$i++)
                {
                    $final_string=$final_string.$parameter_type[$i]." ".$parameter_name[$i].",";
                }
                $final_string=$final_string.$parameter_type[$i]." ".$parameter_name[$i];
                $code=$this->getDefaultCode();
                $class_name=$this->getClassName();
                $code=str_replace("CLASSNAME",$class_name,$code);
                $func_name=$this->getFunctionName();
                $return_type=$this->getReturnType();
                $code=str_replace("RETURN_TYPE",$return_type,$code);
                $code=str_replace("FUNC_NAME",$func_name,$code);
                $code=str_replace("PARAMETERS",$final_string,$code);
                return $code;
            }
        }
        /*
         * Main class of the project
         * 
         * */
        class Main
        {
			
		/*
		 * creates the Language class object and stores the default code in a string 'code'.
		 * Take all the values from the form by $_POST method and sets the value for class_name,function_name,parameters_type of
		 * the language object by calling their methods.
		 * Finally echoes the modified code.
		 * */
		public function load()
		{
		     if(isset($_POST['submit'])) // check if the value has come through POST method by submit button or not otherwise sendsuggestions;
		     {
		        $lang_name=$_POST['lang'];
                        $lang=new Language($lang_name);
                        $File=$lang_name."Tagger.txt";
                        $lang->setinitialTag($File);
                        $MyFile=$lang->name.".txt";
                        $lines=file($MyFile);
                        $code="";
                        for($i=0;$i<count($lines);$i++)
                        {
			$code=$code.$lines[$i];
			$code=$code.'<br>';
		        }
		        $lang->setDefaultCode($code);
                        $lang->setClassName($_POST['class_name']);
                        $lang->setReturnType($_POST['return_type']);
                        $lang->setFunctionName($_POST['func_name']);
                        $number_of_arguments=$_POST['no_of_arguments'];
                        $parameters_name=array();
                        $parameters_type=array();
                        $dimensions=array();
                        for($i=0;$i<$number_of_arguments;$i++)
                        {
                            $val=$_POST['var_name'.$i];
                            $parameters_name[$i]=$val;
                            $val=$_POST['dim'.$i];
                            $dimensions[$i]=$val;
                            $val=$_POST['var_type'.$i];
                            if($lang_name[0]=="C"&&($val=="int[]"||$val=="char[]"))
                            {
				$parameters_name[$i]=$parameters_name[$i].'[]';
			    }
                            $parameters_type[$i]=$val;
                       }
                       $lang->setParametersType($parameters_type);
                       $lang->setParametersName($parameters_name);
                       $lang->setDimensionofvariables($dimensions);
                       $code=$lang->generateSourceCode();
                       echo $code;
	            }
	            else $this->sendSuggestions(); // if the data has not come through "submit" button then we have to send suggestions.
	        }
	 
	 /*
	 * function that send the suggestion to the frontend using the value of the dimension the variable,language
	 * send by the POST method by the front-end.echoes the suggestion by creating the drop-down list 
         * */
	public function sendSuggestions()
	{
	 /* few variable that stores the value send by input.html through post method
          * stores the name of the arguments,its dimensions for which we have to display suggestions
          */
              $name=$_POST["name"];
              $q=$_POST['str'];
              $dim=$_POST['dim'];
              $type=$_POST['type'];
              $lang=$_POST['lang'];
              $id=$_POST['id'];
              $type=strtolower($type);
              //if length of typed string is greater than zero
            if(strlen($q)>0){
            
	      if($lang[0]=="C")
	      {
		if($dim==1)
		{
		  echo '<select name="dropdown" onchange="setData(this.value,'.$id.')">
			      <option value="">-- Select --</option>';	      
	          $MyFile=$lang."suggestiondim1.txt";
                  $lines=file($MyFile);
                  for($i=0;$i<count($lines);$i++)
                  {
		        $s=$lines[$i];
			if((strncmp($s,"int[]",strlen("int[]")))==0)
			{
			    $str=$name.'[]';
			    echo '<option value="'.$s.'">int '.$str.' </option>';
			}
			else if((strncmp($s,"char[]",strlen("char[]")))==0)
			{
			    $str=$name.'[]'; 
			    echo '<option value="'.$s.'">char '.$str.' </option>';
			}
			else echo '<option value="'.$s.'">'.$s.' '.$name.' </option>';
		  }
                  echo '</select>';
		}
		if($dim==2)
		{
		   echo '<select name="dropdown" onchange="setData(this.value,'.$id.')">
			      <option value="">-- Select --</option>';
                   $MyFile=$lang."suggestiondim2.txt";
                   $lines=file($MyFile);
                   for($i=0;$i<count($lines);$i++)
                   {
			$s=$lines[$i];
			echo '<option value="'.$s.'">'.$s.' '.$name.' </option>';
		   }
                   echo '</select>';
		}
	      }
	      if($lang=="Java")
	      {
		   if($dim==1)
		   {
		    echo '<select name="dropdown" onchange="setData(this.value,'.$id.')">
			      <option value="">-- Select --</option>';
	            $MyFile=$lang."suggestiondim1.txt";
                    $lines=file($MyFile);
                    for($i=0;$i<count($lines);$i++)
                    {
		        $s=$lines[$i];
			echo '<option value="'.$s.'">'.$s.' '.$name.' </option>';
		    }
                    echo '</select>';
		   }
		   if($dim==2)
		   {
		    echo '<select name="dropdown" onchange="setData(this.value,'.$id.')">
			      <option value="">-- Select --</option>';
	            $MyFile=$lang."suggestiondim2.txt";
                    $lines=file($MyFile);
                    for($i=0;$i<count($lines);$i++)
                    {
			$s=$lines[$i];
			echo '<option value="'.$s.'">'.$s.' '.$name.' </option>';
		    }
                    echo '</select>';
		   }
	      }	
           }
        }
       }
	/*
	 * Main class object to call the load() method;
	 * */
	 $main=new Main();
	 $main->load();
        ?>
    </body>
</html>
