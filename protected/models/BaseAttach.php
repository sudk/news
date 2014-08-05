<?php
/**
 * 文件存储
 * @author yangtl
 *
 */
class BaseAttach extends CActiveRecord {

	const TYPE_News = '1';
	
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function tableName(){
		return 'base_attach';
	}
	
	public function rules(){
		return array(
		    //安全性
			array('relation_id,type,path,abstract,record_time', 'safe', 'on' => 'create'),
			array('id,relation_id,type,path,abstract,record_time', 'safe', 'on' => 'modify'),
		);
	}

    public static function AddAttaches($attaches,$relation_id,$type,$abstract=""){
        $conn=Yii::app()->db;
        $transaction = $conn->beginTransaction();
        try{
            $d=date("Y-m-d H:i:s");
            if(count($attaches)){

                $sql = "delete from base_attach where relation_id=:relation_id and type=:type ";
                $command = $conn->createCommand($sql);
                $command->bindParam(":relation_id",$relation_id, PDO::PARAM_STR);
                $command->bindParam(":type",$type, PDO::PARAM_STR);
                $command->execute();

                foreach($attaches as $attach){
                    $sql = "insert into  base_attach set
                relation_id=:relation_id,
                path=:path,
                type=:type,
                abstract=:abstract,
                record_time=:record_time ";
                    $command = $conn->createCommand($sql);
                    $command->bindParam(":relation_id",$relation_id, PDO::PARAM_STR);
                    $command->bindParam(":path",$attach, PDO::PARAM_STR);
                    $command->bindParam(":type",$type, PDO::PARAM_STR);
                    $command->bindParam(":abstract",$abstract,PDO::PARAM_STR);
                    $command->bindParam(":record_time",$d,PDO::PARAM_STR);
                    $command->execute();
                }
            }
            $transaction->commit();
            return array('status'=>1,'msg'=>'操作成功');
        } catch (Exception $ex) {
            $transaction->rollBack();
            return array('status'=>-1,'desc'=>'订单状态处理失败，未知错误');
        }
    }

    public static function FindOneByRId($r_id,$type){
        $condition="relation_id = '{$r_id}' and type='{$type}'";
        $row = Yii::app()->db->createCommand()
            ->select("*")
            ->from("base_attach")
            ->where($condition)
            ->queryRow();
        return $row;
    }

    public static function FindByNewsRows($rows,$type){
        if($rows){
            $relation_ids=array();
            foreach($rows as $row){
                $relation_ids[]=$row['news_id'];
            }
            $str=implode("','",$relation_ids);
            $condition="relation_id in ('{$str}') and type='{$type}'";
            $attaches = Yii::app()->db->createCommand()
                ->select("*")
                ->from("base_attach")
                ->where($condition)
                ->queryAll();
            if($attaches){
                $rs=array();
                foreach($attaches as $attache){
                    if(isset($rs[$attache['relation_id']])){
                        continue;
                    }
                    $rs[$attache['relation_id']]=$attache['path'];
                }
                return $rs;
            }
        }
        return false;
    }

    public static function  MakeURL($attachs,$id){
        if(isset($attachs[$id])){
            return Yii::app()->params['assets_path'].$attachs[$id];
        }else{
            return Yii::app()->params['assets_path']."base.jpg";
        }
    }

    public static function queryList($page, $pageSize, $args = array()) {

        $condition = ' 1=1 ';
        $params = array();

        if ($args['type'] != '') {
            $condition.=' AND type=:type';
            $params['type'] = $args['type'];
        }

        $total_num = BaseAttach::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $criteria->order = 'record_time DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $criteria->order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $criteria->order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;

        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = BaseAttach::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($pages->currentPage + 1);
        $rs['total_num'] = $total_num;
        $rs['total_page'] = ceil($rs['total_num'] / $rs['page_num']);
        $rs['num_of_page'] = $pages->pageSize;
        $rs['url'] = "./?r=mobile/img/grid";
        $rs['rows'] = $rows;

        return $rs;

    }


}
