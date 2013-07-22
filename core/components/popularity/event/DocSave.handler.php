<?php
/*
* handler class for events:
* 	- OnDocFormSave
* Обновляем дату публикации документа (сброс числа просмотров нам не нужен)
*/
class DocSaveHandler extends PopularityHandlerBase{
	public static function invoke($eventName, $prop, &$data){
		$flag = false;
		if('OnDocFormSave' === $eventName){
			$docId = $prop['id'];
			if(self::$popularity->getPObject($docId)){
				$data['publishedon'] = $prop['resource']->get('publishedon');
				$flag = true;
			}
		}
		return $flag;
		
	}
}