<?php
/*
* class for events:
*     - OnWebPagePrerender
* Подсчет числа просмотров
*/
class viewHandler extends PopularityHandlerBase{
    public static function invoke($eventName, $prop, &$data){
		$flag = false;
		
		if (!isset($_SESSION['HitsPage']))  $_SESSION['HitsPage'] = array();
        if (!isset($_SESSION['HandlerView']))  $_SESSION['HandlerView'] = array();
		
		$docId = self::$popularity->modx->resource->get('id');
		$save = in_array($docId, $_SESSION['HitsPage']) && !in_array($docId, $_SESSION['HandlerView']);
        
		if(self::$popularity->getPObject($docId)){
			if(self::$popularity->isNew() && 
				self::$popularity->modx->resource->get('publishedon')==0 && 
				self::$popularity->modx->resource->get('published')==1
			){
				$data['publishedon'] = self::$popularity->modx->resource->get('createdon');
			}
			if($save){
                $data['view'] = self::$popularity->pObject->get('view')+1;
                $_SESSION['HandlerView'][] = $docId;
				$flag = true;
			}
		}
		self::$popularity->daysUpdate(
			self::$popularity->getConfig('intervalDaysUpdate'),
			self::$popularity->getConfig('limitDaysUpdate')
		);
		return $flag;
	}
}