<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        class DefaultCode
        {
            public $parameters_type=array();
            public $parameters_name=array();
            public $default_code;
            public $function_name;
            public $class_name;
            public $return_type;
            public $dimensions=array();
            public $Map=array();
            public $MapDimensions=array(array());
            
            public function setDefaultCode($code)
            {
                $this->default_code=$code;
            }
            public function setParametersType($type)
            {
                $this->parameters_type=$type;
            }
            public function setParametersName($name)
            {
                $this->parameters_name=$name;
            }
            public function setDimensionOfVariables($dimension)
            {
                $this->dimensions=$dimension;
            }
            
            public function setFunctionName($name)
            {
                $this->function_name=$name;
            }
            public function setClassName($name)
            {
                $this->class_name=$name;
            }
            public function setReturnType($type)
            {
				$this->return_type=$type;
			}
			public function mapDimensions()
			{
				if($this->name=="C"||$this->name=="C++")
				{
					$this->MapDimensions[0][0]="";
					$this->MapDimensions[1][0]="*";
					$this->MapDimensions[1][1]="[]";
					$this->MapDimensions[2][0]="**";
				}
				if($this->name=="Java")
				{
					$this->MapDimensions[0][0]="";
					$this->MapDimensions[1][0]="[]";
					$this->MapDimensions[1][1]="ArrayList";
					$this->MapDimensions[1][2]="Set<Integer>";
					$this->MapDimensions[2][0]="[][]";
				}
				
			}
            public function getDefaultCode($default_code)
            {
                return $this->default_code;
            }
            public function getReturnType()
            {
                return $this->return_type;
            }
            public function getClassName()
            {
                return $this->class_name;
            }
            public function getFunctionName()
            {
                return $this->function_name;
            }
            public function getParameterNames()
            {
                return $this->parameters_name;
            }
            public function getParameterTypes()
            {
				$Map_parameters=array();
				for($i=0;$i<count($this->parameters_type);$i++)
				{
					$dim=$this->dimensions[$i];
					$string=$this->MapDimensions[$dim][0];
					$str=$this->parameters_type[$i];
					$str=strtolower($str);
					$Map_parameters[$i]=$this->Map[$str];
					$Map_parameters[$i]=$Map_parameters[$i].$string;
				}
                return $Map_parameters;
            }
        }
        class Language extends DefaultCode
        {
            public $name;
            public function __construct($name) {
                $this->name=$name;
            }
            public function setinitialTag($MyFile)
            {
			   $lines=file($MyFile);
               for($i=0;$i<count($lines);$i++)
               {
			     $str=explode(" ",$lines[$i]);
			     $type=strtolower($str[0]);
			     $tag=strtolower($str[1]);
			     $this->Map[$type]=$tag;
		       }
			}
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
            
            public function sendSuggestions()
            {
				
			}
        }
        class Main
        {
		public function load()
		{
        $lang=new Language("Java");
        $File=$lang->name."Tagger.txt";
        $lang->setinitialTag($File);
        $lang->mapDimensions();
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
            $parameters_type[$i]=$val;
        }
        $lang->setParametersType($parameters_type);
        $lang->setParametersName($parameters_name);
        $lang->setDimensionofvariables($dimensions);
        $code=$lang->generateSourceCode();
        echo $code;
	    }
	    }
	    $main=new Main();
	    $main->load();
        ?>
    </body>
</html>
