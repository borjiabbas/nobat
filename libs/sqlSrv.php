<?php

class SqlServer{
    private $server = '192.168.166.20';
    private $database = 'shafa';
    private $username = 'EmdadiReadOnly';
    private $password = '123456Emm';
    private $connection;
    public $time;
    public $queryPrefixQ="SELECT TOP 1 [Paper Code3],[Adm Code] FROM paper WHERE [Part Code]=28 ";
    function __construct()
    {
        $this->time= new shTime();
        $connectionInfo = array( "Database"=>$this->database, "UID"=>$this->username, "PWD"=>$this->password, "CharacterSet" => "UTF-8");
        $this->connection = sqlsrv_connect( $this->server, $connectionInfo);
        if( $this->connection === false ) {
            die( "EE:in init DATABASE".print_r( sqlsrv_errors(), true ));
        }
    }
    public function Query($sqlString){
        $Result = sqlsrv_query( $this->connection, $sqlString );
        if( $Result === false) {
            die( "EE:in Run Query your Query is:".$sqlString." ".print_r( sqlsrv_errors(), true) );
        }
        return $Result;

    }
    public function maxQueue($drCode,$date){
            $dayLike=$date."%";
        $sqlString=$this->queryPrefixQ."AND [Doctor Code]=".$drCode." AND [Date] LIKE '{$dayLike}' ORDER BY [Paper Code3] DESC";
         $res=$this->Query($sqlString);
        if($this->hasRows($res)){
              $row = sqlsrv_fetch_array($res , SQLSRV_FETCH_ASSOC);
            return (int)$row['Paper Code3'];
        }else
        {
            return false;
        }

        }
    public function FreeSql($result){
        sqlsrv_free_stmt( $result);
    }
    public function ServerClose(){
        sqlsrv_close( $this->connection );
    }
    public function hasRows($Result){
        $rows = sqlsrv_has_rows($Result);
        return $rows;
    }
    public function getDrName($drCode){
        $sqlString="SELECT TOP 1 [Name] FROM [Shafa].[dbo].[Dr-List] where Code=".$drCode;
        $res=$this->Query($sqlString);
        if($this->hasRows($res)) {
            $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
            $drName=explode("-",$row["Name"]);
            $drName=$drName[1]." ".$drName[0];
            return$drName;
        }else{
            return false;
        }

    }
    public function nextQueue($drCode,$date,$lastQueue,$nextDayFlag=true){
        $response=array();
        $dayLike=$date."%";
        $sqlString=$this->queryPrefixQ."AND [Paper Code3] > ".$lastQueue." AND [Doctor Code]=".$drCode."
         AND [Date] LIKE '{$dayLike}' ORDER BY [Adm Code] ASC";
        $res=$this->Query($sqlString);
        if($this->hasRows($res)){
            $row = sqlsrv_fetch_array($res , SQLSRV_FETCH_ASSOC);
            $response["queue"]=$row['Paper Code3'];
            if(!$nextDayFlag){
                $response["qDate"]=$date;
            }
            return $response;
        }elseif($nextDayFlag && !$this->time->isToday($date)){

                $NextDay = $this->time->Tomorrow($date);
                $this->nextQueue($drCode, $NextDay, 0, false);
        }else{
            return false;
        }
    }
    public function roomAutoInit($doctors){
        $sqlString="SELECT TOP 1 [Doctor Code] FROM paper WHERE [Part Code]=28 ";
        if(sizeof($doctors)) {
            $sqlString .= "AND [Doctor Code] NOT IN (";
            foreach ($doctors as $doctor) {
                $sqlString .= "'{$doctor}',";
            }
            $sqlString = rtrim($sqlString, ",");
            $sqlString .= ") ";
        }

        $sqlString.="ORDER BY [Adm Code] DESC";
        $res=$this->Query($sqlString);
        if($this->hasRows($res)) {
            $row = sqlsrv_fetch_array($res , SQLSRV_FETCH_ASSOC);
            return  $row['Doctor Code'];
        }else{
            return false;
        }
    }
    public function triageDataAll($doctor,$queue,$day){
            $data=array();
            $dayLike=$day."%";
        $sqlString = "SELECT TOP (10) [Paper].[Adm Code],[Paper Code3],[BP],[AdmID].[Name]
                    ,[Family],[TriageLevel],[MedicalHistory],[DrugHistory]
                    ,[SPo2],[BS],[P],[R],[T],[isSleepy],[isRisk],[isPain],[AllergyDrugID]
                    ,[isMokhatere],[isDistress],[isCyanosis],[isShock]
                    ,[AdmEmergComplaintCategoryList].[Name] as ComplaintCatName
                    ,[AdmEmergComplaintList].[Name] as ComplaintName
                    ,[AdmEmergVigilantLevelList].[Name] as VigilantLevel
                    FROM [Shafa].[dbo].[Paper] LEFT JOIN [AdmEmerg ] ON
					[AdmEmerg ].AdmCode= Paper.[Adm Code] 
					LEFT JOIN [AdmData] ON
					[AdmData].[Adm Code]= Paper.[Adm Code]
					LEFT JOIN [AdmID] ON
					[AdmID].[ID Code]= AdmData.[ID Code] 
                    LEFT JOIN [AdmEmergComplaintCategoryList ] ON
                    [AdmEmerg ].[ComplaintCategory]=[AdmEmergComplaintCategoryList].[Code]
                    LEFT JOIN [AdmEmergComplaintList] ON 
                    [AdmEmerg ].[ComplaintID]=[AdmEmergComplaintList].[Code]
                    LEFT JOIN [AdmEmergVigilantLevelList] ON
                    [AdmEmerg ].[VigilantLevelID]=[AdmEmergVigilantLevelList].[Code]
		            WHERE [Paper].[Part Code]=28 AND [Paper].[Doctor Code]='{$doctor}'
                    AND [Paper].[Paper Code3]>={$queue} 
                    AND [Paper].[Date] LIKE '$dayLike' 
		            ORDER BY Paper.[Paper Code3] ASC";
            $res=$this->Query($sqlString);
            if($this->hasRows($res)){
                while ($row=sqlsrv_fetch_array($res , SQLSRV_FETCH_ASSOC)) {
                    $row += [ "queue" => (int)$row['Paper Code3'] ];
                    array_push($data, $row);
                }
            }
      return $data;
    }
    public function triage($doctors){
        $data= array();
        foreach ($doctors as $doctor) {
            $dayLike=$doctor['qDate']."%";
            $sqlString = "SELECT TOP (10) [Paper].[Adm Code],[Paper Code3],[BP],[AdmID].[Name]
                    ,[Family],[TriageLevel],[MedicalHistory],[DrugHistory]
                    ,[SPo2],[BS],[P],[R],[T],[isSleepy],[isRisk],[isPain],[AllergyDrugID]
                    ,[isMokhatere],[isDistress],[isCyanosis],[isShock]
                    ,[AdmEmergComplaintCategoryList].[Name] as ComplaintCatName
                    ,[AdmEmergComplaintList].[Name] as ComplaintName
                    ,[AdmEmergVigilantLevelList].[Name] as VigilantLevel
                    FROM [Shafa].[dbo].[Paper] LEFT JOIN [AdmEmerg ] ON
					[AdmEmerg ].AdmCode= Paper.[Adm Code] 
					LEFT JOIN [AdmData] ON
					[AdmData].[Adm Code]= Paper.[Adm Code]
					LEFT JOIN [AdmID] ON
					[AdmID].[ID Code]= AdmData.[ID Code] 
                    LEFT JOIN [AdmEmergComplaintCategoryList ] ON
                    [AdmEmerg ].[ComplaintCategory]=[AdmEmergComplaintCategoryList].[Code]
                    LEFT JOIN [AdmEmergComplaintList] ON 
                    [AdmEmerg ].[ComplaintID]=[AdmEmergComplaintList].[Code]
                    LEFT JOIN [AdmEmergVigilantLevelList] ON
                    [AdmEmerg ].[VigilantLevelID]=[AdmEmergVigilantLevelList].[Code]
		            WHERE [Paper].[Part Code]=28 AND [Paper].[Doctor Code]='{$doctor["drCode"]}'
                    AND [Paper].[Paper Code3]>={$doctor["queue"]} 
                    AND [Paper].[Date] LIKE '$dayLike' 
		            ORDER BY Paper.[Paper Code3] ASC";
            $res = $this->Query($sqlString);
            if ($this->hasRows($res)) {
                $drCode=$doctor['drCode'];
                $data["$drCode"]=array();
                while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
                    $row += [ "queue" => (int)$row['Paper Code3'] ];
                    array_push($data["$drCode"], $row);
                }
            }
        }
        return $data;
    }
}
