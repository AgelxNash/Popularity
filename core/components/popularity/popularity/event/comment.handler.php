<?php
/*
* class for events:
*	- OnCommentRemove
*	- OnBeforeCommentSave
*/
class commentHandler extends PopularityHandlerBase{
	public static function invoke($eventName, $prop, &$data){
		$flag = false;
		if(('OnBeforeCommentSave' === $eventName && 'new' == $prop['mode']) || 'OnCommentRemove' === $eventName){
            //В $docId получаем ID статьи
			if(self::$popularity->getPObject($docId)){
				//В $comment получаем общее число комментариев у статьи
				$data['comment'] = intval($comment);
				if($eventName == 'OnBeforeCommentSave'){
					$data['comment']+=1;//+1 у нас же Before
				}
				$flag = true;
			}
		}
		return $flag;
	}
}