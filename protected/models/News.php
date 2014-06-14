<?php

/**
 * @author sudk
 */
class News extends CActiveRecord {

    const TYPE_A=1;
    const TYPE_B=2;
    const TYPE_C=3;
    const TYPE_D=4;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'news';
    }

    public function rules() {
        return array(
            //安全性
            array('news_id,title,summary,content,type,author,public_date,record_time,to_top,creator,lon_lat,addr', 'safe', 'on' => 'create'),
            array('news_id,title,summary,content,type,author,public_date,record_time,to_top,creator,lon_lat,addr', 'safe', 'on' => 'modify'),
        );
    }

    public static function GetType($type=null){
        $ar=array(
            ''=>"---请选择类型---",
            self::TYPE_A=>"A",
            self::TYPE_B=>"B",
            self::TYPE_C=>"C",
            self::TYPE_D=>"D",
        );
        return $type === null ? $ar : $ar[$type];
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = ' 1=1 ';
        $params = array();

        if ($args['creator'] != '') {
            $condition.=' AND creator=:creator';
            $params['creator'] = $args['creator'];
        }

        if ($args['title'] != '') {
            $condition.=' AND title LIKE :title';
            $params['title'] = $args['title'] . '%';
        }

        if ($args['type'] != '') {
            $condition.=' AND type=:type';
            $params['type'] = $args['type'];
        }

        $total_num = News::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {
            $criteria->order = 'to_top DESC,record_time DESC';
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
        $rows = News::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($pages->currentPage + 1);
        $rs['total_num'] = $total_num;
        $rs['total_page'] = ceil($rs['total_num'] / $rs['page_num']);
        $rs['num_of_page'] = $pages->pageSize;
        $rs['rows'] = $rows;

        return $rs;

    }

}