<?php
class Popularity {
    public $modx = null;
    protected $eventHandler = array();
    public $setData = array();
    private $isNew = null;
	public $pObject = null;
	private $config = array();
	
	function __construct(modX &$modx,array $config = array()) {
        $this->modx = &$modx;
        $corePath = $this->modx->getOption('popularity.core_path',null,$modx->getOption('core_path').'components/popularity/');
        
        $this->config = array(
            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'event' => $corePath.$this->modx->getOption('popularity.HandlerEventFolders',null,'event').'/',
			'eventAlias' => $this->modx->fromJson($this->modx->getOption('popularity.eventAlias')),
			'intervalDaysUpdate' => $this->modx->getOption('popularity.intervalDaysUpdate',null,86400),
			'limitDaysUpdate' => $this->modx->getOption('popularity.limitDaysUpdate',null,5),
        );
		$this->modx->addPackage('popularity', $this->config['modelPath']);
    }
	public function getConfig($name){
		return isset($this->config[$name]) ? $this->config[$name] : null;
	}
	
	public function isNew(){
		return $this->isNew;
	}
	public function getPObject($id = null){
		$flag = false;
		if(isset($id)){
			$this->setData['docid'] = $id;
		}
		if($this->docId(true) && (null === $this->pObject || isset($id))){
			$this->pObject = $this->pObject = $this->modx->getObject("objPopularity", array('docid'=>$this->docId()));
			if(null===$this->pObject){
				$this->isNew = true;
				$this->pObject = $this->modx->newObject("objPopularity");
			}else{
				$this->isNew = false;
			}
		}
		if(!($flag = ($this->pObject instanceof objPopularity))){
			$this->pObject = null;
			$this->isNew = null;
		}else{
			if(isset($id)){
				$this->setData = array_merge(
					$this->pObject->toArray(),
					$this->setData
				);
			}
		}
		return $flag;
	}
	protected function eventAlias($name){
		$out = $name;
		$aliases = $this->getConfig('eventAlias');
		if(isset($aliases[$name])){
			$out = $aliases[$name];
		}
		if(!preg_match('/^[a-zA-Z0-9\_]+$/',$out)){
			$out = null;
		}
		return $this->loadEventHandler($out);
	}
	
	public function event($name,$prop){
		$flag = false;
		$aliasname = $this->eventAlias($name);
		if(!empty($aliasname) && ($eHand = $this->_getEventHandler($aliasname))!==null){
			$flag = $eHand::invoke($name, $prop, $this->setData);
		}
		return $flag;
	}
	public function calcRang($obj){
        $ageK= (1-$obj->get('days')/($obj->get('days')+1));
        $total = $ageK + $ageK*$obj->get('view') + $ageK*$obj->get('comment') + $obj->get('comment');
        return $total;
	}
	
	public function flishData(){
		$this->setData = array();
		return $this;
	}
	
	public function docId($validate = false){
		$doc = isset($this->setData['docid']) ? $this->setData['docid'] : null;
		return ($validate) ? (isset($doc) && $doc>0) : $doc;
	}
	
	public function calcDays($pubtime){
		$d1 = date_create(date('Y-m-d H:i:s',strtotime($pubtime)));
		$d2 = date_create(date('Y-m-d H:i:s'));
		$interval = date_diff($d1, $d2);
		return $interval->days;
	}
	public function reloadData(&$obj, $data=array()){
        if(!empty($data)){
            $_data = $data;
        }else{
            $_data = &$this->setData;
        }
        
        $days = $obj->get('days');
		if($this->isNew()){
			if(empty($_data['publishedon'])){
				$_data['publishedon'] = time();
			}
			$obj->set('publishedon',$_data['publishedon']);
		}
		$_data['days'] = $this->calcDays($obj->get('publishedon'));
		$_data['days_update'] = time();
    	$obj->fromArray($_data);
        $_data['rang'] = $this->calcRang($obj);
		$obj->set('rang', $_data['rang']);
		return $_data;
	}
	
	private function _getEventHandler($name){
		return (isset($this->eventHandler[$name]) && $name.'Handler' == $this->eventHandler[$name]) ? $this->eventHandler[$name] : null;
	}
	public function saveData(){
		$flag = false;
		if($this->getPObject()){
			$this->reloadData($this->pObject);
			if($this->pObject->save()){
				$flag = true;
			}
		}
		$this->isNew = null;
		return $flag;
	}
	protected function loadEventHandler($name=null){
		$flag = $name;
		if(null!==$name && null===$this->_getEventHandler($name)){
			$className = $name.'Handler';
			$eventFolder = $this->getConfig('event');
			if(!class_exists($className) && file_exists($eventFolder.$name.'.handler.php')){
				include_once($eventFolder.$name.'.handler.php');
			}
			if(class_exists($className)){
				$className::$popularity = &$this;
				$this->eventHandler[$name] = $className;
			}else{
				$flag = false;
			}
		}
		return $flag;
	}
	public function daysUpdate($time = 86400,$limit = 3){
    	$q = $this->modx->newQuery("objPopularity");
		$q->where(array("days_update:<=" => (time()-$time)))->limit($limit);
		$q = $this->modx->getCollection("objPopularity",$q);
		foreach($q as $item){
			$this->reloadData($item,$item->toArray());
			$item->save();
		}
	}
}
abstract class PopularityHandlerBase{
	public static $popularity = null;
	
	/*
	* @param $eventName Оригинальное имя события
	* @param $prop Параметры события
	* @param $data результатирующий массив для записи в базу
	*/
	abstract public static function invoke($eventName, $prop, &$data);
}